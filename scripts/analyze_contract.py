import os
import sys
import pytesseract
import cv2
import PyPDF2
import json
import logging
from pathlib import Path
from datetime import datetime
import exiftool  # For metadata extraction (requires pip install pyexiftool)

# Setup logging
logging.basicConfig(level=logging.INFO, format='%(levelname)s: %(message)s')
logger = logging.getLogger(__name__)

# Tesseract path configuration
if os.name == 'nt':  # Windows
    pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'
else:  # Linux/Mac
    pytesseract.pytesseract.tesseract_cmd = '/usr/bin/tesseract'

def setup_exiftool():
    """Ensure ExifTool is available."""
    try:
        with exiftool.ExifTool() as et:
            if not et.executable:
                raise Exception("ExifTool not found")
    except Exception as e:
        logger.error(f"ExifTool setup failed: {e}")
        raise SystemExit("Please install ExifTool (https://exiftool.org/)")

def extract_text_from_pdf(file_path):
    """Extract text from a PDF file."""
    try:
        with open(file_path, 'rb') as f:
            pdf = PyPDF2.PdfReader(f)
            text = ""
            for page in pdf.pages:
                extracted = page.extract_text()
                if extracted:
                    text += extracted + "\n"
            return text.strip() if text else None
    except Exception as e:
        logger.error(f"PDF text extraction failed: {e}")
        return None

def extract_text_from_image(file_path):
    """Extract text from an image using OCR."""
    try:
        img = cv2.imread(file_path)
        if img is None:
            raise ValueError("Image could not be loaded")
        text = pytesseract.image_to_string(img)
        return text.strip() if text else None
    except Exception as e:
        logger.error(f"Image OCR failed: {e}")
        return None

def extract_metadata(file_path):
    """Extract metadata from PDF or image files using ExifTool."""
    try:
        with exiftool.ExifTool() as et:
            metadata = et.get_metadata(file_path)
        return {
            "creator": metadata.get("PDF:Creator", metadata.get("EXIF:Software", "Unknown")),
            "creation_date": metadata.get("PDF:CreateDate", metadata.get("EXIF:DateTimeOriginal", None)),
            "modified_date": metadata.get("PDF:ModifyDate", metadata.get("File:FileModifyDate", None)),
            "author": metadata.get("PDF:Author", metadata.get("EXIF:Artist", "Unknown")),
            "file_size": metadata.get("File:FileSize", "Unknown"),
        }
    except Exception as e:
        logger.error(f"Metadata extraction failed: {e}")
        return None

def detect_image_tampering(file_path):
    """Check for potential image tampering."""
    try:
        img = cv2.imread(file_path)
        if img is None:
            return False, "Image could not be loaded"

        # Blur detection (low variance indicates potential tampering)
        gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
        blur = cv2.Laplacian(gray, cv2.CV_64F).var()
        blur_threshold = 100
        blur_notes = f"Blur variance: {blur:.2f}"
        tampering_detected = blur < blur_threshold

        # Edge detection (abnormal edges might indicate edits)
        edges = cv2.Canny(gray, 100, 200)
        edge_density = cv2.countNonZero(edges) / (img.shape[0] * img.shape[1])
        edge_threshold = 0.1  # Adjust based on testing
        if edge_density > edge_threshold:
            tampering_detected = True
            blur_notes += f" | High edge density: {edge_density:.3f}"

        return tampering_detected, blur_notes
    except Exception as e:
        logger.error(f"Tampering detection failed: {e}")
        return False, f"Error in tampering check: {str(e)}"

def analyze_text(text):
    """Analyze extracted text for fraud indicators."""
    if not text:
        return True, "No text extracted"

    text_lower = text.lower()
    expected_terms = [
        "contract agreement",       # General contract term
        "vendor signature",         # Signature of the vendor
        "contract id",              # Unique identifier for the contract
        "payment terms",            # Financial terms
        "effective date",           # Start date of the contract
        "expiration date",          # End date of the contract
        "terms and conditions",     # Legal terms
        "service description",      # What the contract covers
        "vendor name",              # Name of the vendor
        "client name",              # Name of the client/customer
        "agreement number",         # Another identifier variant
        "scope of work",            # Details of services provided
        "liability clause",         # Legal responsibility
        "termination clause"        # Conditions for ending the contract
    ]
    branding_terms = ["bus travels", "bus transportation"]  # Flexible branding
    missing_terms = [term for term in expected_terms if term not in text_lower]
    branding_check = all(term not in text_lower for term in branding_terms)  # Must have at least one
    term_check = len(missing_terms) > len(expected_terms) / 2 or branding_check
    notes = f"Missing terms: {', '.join(missing_terms)}" if missing_terms else "All expected terms present"
    if branding_check:
        notes += " | Missing branding term (e.g., 'bus travels' or 'bus transportation')"

    # Additional checks
    suspicious_phrases = ["fake", "fraud", "test", "photoshop"]
    suspicious_found = [phrase for phrase in suspicious_phrases if phrase in text_lower]
    if suspicious_found:
        term_check = True
        notes += f" | Suspicious phrases: {', '.join(suspicious_found)}"

    # Check for date consistency (updated regex for month names)
    import re
    date_pattern = r"(january|february|march|april|may|june|july|august|september|october|november|december)\s+\d{1,2},\s+\d{4}|\d{4}-\d{2}-\d{2}|\d{2}/\d{2}/\d{4}"
    dates = re.findall(date_pattern, text_lower)
    if not dates:
        term_check = True
        notes += " | No valid dates found"

    return term_check, notes

def analyze_metadata(metadata):
    """Analyze metadata for fraud indicators."""
    if not metadata:
        return True, "Metadata extraction failed"

    notes = ""
    is_fraud = False

    # Check creation/modification dates
    creation_date = metadata.get("creation_date")
    modified_date = metadata.get("modified_date")
    if creation_date and modified_date:
        try:
            c_date = datetime.strptime(creation_date.split("+")[0], "%Y:%m:%d %H:%M:%S")
            m_date = datetime.strptime(modified_date.split("+")[0], "%Y:%m:%d %H:%M:%S")
            if m_date < c_date:
                is_fraud = True
                notes += "Modified date before creation date | "
            if (datetime.now() - m_date).days > 365:  # Old file
                notes += "File over a year old | "
        except ValueError:
            notes += "Invalid date format in metadata | "

    # Check creator/author
    creator = metadata.get("creator", "").lower()
    author = metadata.get("author", "").lower()
    suspicious_tools = ["photoshop", "gimp", "fake"]
    if any(tool in creator or tool in author for tool in suspicious_tools):
        is_fraud = True
        notes += f"Suspicious creator/author: {creator}/{author} | "

    return is_fraud, notes.strip(" | ")

def analyze_file(file_path):
    """Analyze a contract file for fraud with multiple validations."""
    file_path = Path(file_path)
    if not file_path.exists():
        return {"is_fraud": True, "notes": "File not found"}

    # Determine file type and extract text
    is_image = file_path.suffix.lower() in ['.jpg', '.jpeg', '.png']
    if file_path.suffix.lower() == '.pdf':
        text = extract_text_from_pdf(file_path)
        # Fallback to OCR if PDF text extraction fails (e.g., scanned PDF)
        if not text:
            logger.info("PDF text extraction failed, attempting OCR fallback")
            text = extract_text_from_image(file_path)
            is_image = True  # Enable tampering check for scanned PDFs
    else:
        text = extract_text_from_image(file_path)

    # Initialize result
    result = {"is_fraud": False, "notes": ""}

    # Text analysis
    text_fraud, text_notes = analyze_text(text)
    if text_fraud:
        result["is_fraud"] = True
    result["notes"] += f"Text analysis: {text_notes}"

    # Image tampering (if applicable)
    if is_image:
        tampering_detected, tampering_notes = detect_image_tampering(file_path)
        if tampering_detected:
            result["is_fraud"] = True
        result["notes"] += f" | Image analysis: {tampering_notes}"

    # Metadata analysis
    metadata = extract_metadata(file_path)
    meta_fraud, meta_notes = analyze_metadata(metadata)
    if meta_fraud:
        result["is_fraud"] = True
    result["notes"] += f" | Metadata analysis: {meta_notes}"

    # Clean up notes
    result["notes"] = result["notes"].strip().strip("|").strip()
    if not result["notes"]:
        result["notes"] = "No issues detected"

    return result

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"is_fraud": True, "notes": "No file path provided"}))
        sys.exit(1)

    setup_exiftool()  # Ensure ExifTool is ready
    file_path = sys.argv[1]
    result = analyze_file(file_path)
    print(json.dumps(result))
