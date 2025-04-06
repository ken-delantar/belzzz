import re
import sys
import json
import logging
from datetime import datetime, timedelta
from typing import Dict, Tuple, List, Optional

# Configure logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

# Scoring configuration (adjusted for SMBE in PHP)
SCORING_CONFIG = {
    "max_price": 1_000_000_000.0,
    "price_weight": 0.5,
    "timeline_weight": 0.3,
    "validity_weight": 0.2,
    "early_window_months": 6,  # Consider next 6 months as "early"
    "email_regex": r"^[\w\.-]+@[\w\.-]+\.\w+$"
}

def parse_date(date_str: str, formats: List[str] = ["%Y-%m-%d", "%B %Y", "%B %d, %Y"]) -> Optional[datetime]:
    """Safely parse a date string with multiple formats."""
    for fmt in formats:
        try:
            return datetime.strptime(date_str, fmt)
        except ValueError:
            continue
    return None

def score_bid(details: Dict[str, str], config: Dict = SCORING_CONFIG) -> Tuple[float, List[str]]:
    """Score the bid with dynamic pricing and timeline evaluation."""
    score = 0.0
    notes = []
    now = datetime.now()

    # Pricing evaluation (PHP-based)
    if "pricing" in details and details["pricing"]:
        try:
            price = float(details["pricing"].replace('₱', '').replace(',', '').strip())
            if price <= 0:
                notes.append("Invalid pricing (non-positive).")
            else:
                # Normalized score: higher for lower prices, capped at max_price
                score += config["price_weight"] * 100 * max(0, (config["max_price"] - price) / config["max_price"])
                if price > config["max_price"]:
                    notes.append(f"Pricing exceeds typical SMBE range (₱{config['max_price']}).")
        except (ValueError, TypeError):
            notes.append("Invalid pricing format (must be a number, e.g., ₱50,000).")
    else:
        notes.append("Pricing missing.")

    # Delivery timeline evaluation (dynamic)
    if "delivery_timeline" in details and details["delivery_timeline"]:
        timeline = details["delivery_timeline"].strip()
        timeline_date = parse_date(timeline)
        if timeline_date:
            days_until = (timeline_date - now).days
            if days_until < 0:
                notes.append("Delivery timeline is in the past.")
                score += config["timeline_weight"] * 20  # Minimal score for past dates
            elif days_until <= 30 * config["early_window_months"]:
                # Scale score: max for <1 month, decreasing to 50% at window end
                score += config["timeline_weight"] * 100 * max(0.5, 1 - days_until / (30 * config["early_window_months"]))
            else:
                score += config["timeline_weight"] * 50  # Later timelines get lower score
        else:
            notes.append("Invalid delivery timeline format (expected e.g., 'March 2025' or '2025-03-15').")
            score += config["timeline_weight"] * 50  # Default for unparseable
    else:
        notes.append("Delivery timeline missing.")

    # Valid until evaluation
    if "valid_until" in details and details["valid_until"]:
        valid_date = parse_date(details["valid_until"])
        if valid_date:
            if valid_date > now:
                days_valid = (valid_date - now).days
                score += config["validity_weight"] * min(100, days_valid / 30)  # Scale by validity duration
            else:
                notes.append("Bid expired.")
        else:
            notes.append("Invalid valid_until date format (expected e.g., '2025-06-01').")
    else:
        notes.append("Valid until date missing.")

    # Cap score at 100
    score = min(score, 100)
    logger.info(f"Scored bid: {score}, Notes: {notes}")
    return score, notes

def check_fraud(details: Dict[str, str], config: Dict = SCORING_CONFIG) -> Tuple[bool, List[str]]:
    """Enhanced fraud detection for SMBE context."""
    is_fraud = False
    fraud_notes = []

    # Required fields check
    required_fields = ["proposal_title", "pricing", "delivery_timeline", "valid_until", "vendor_name", "email"]
    missing_fields = [field for field in required_fields if not details.get(field)]
    if missing_fields:
        is_fraud = True
        fraud_notes.append(f"Missing required fields: {', '.join(missing_fields)}.")

    # Email validation
    if "email" in details and details["email"]:
        if not re.match(config["email_regex"], details["email"].strip()):
            is_fraud = True
            fraud_notes.append("Invalid email format.")

    # Pricing outlier check (SMBE-specific)
    if "pricing" in details and details["pricing"]:
        try:
            price = float(details["pricing"].replace('₱', '').replace(',', '').strip())
            if price > config["max_price"] * 2 or price < config["max_price"] * 0.05:
                is_fraud = True
                fraud_notes.append(f"Pricing outlier detected: ₱{price} (expected range ₱{config['max_price']*0.05}-₱{config['max_price']*2}).")
        except (ValueError, TypeError):
            pass

    logger.info(f"Fraud check: {is_fraud}, Notes: {fraud_notes}")
    return is_fraud, fraud_notes

def analyze_bid(bid_data: str) -> Dict:
    """Analyze bid data from JSON input."""
    try:
        details = json.loads(bid_data)
        if not isinstance(details, dict):
            return {"error": "Input must be a JSON object."}
    except json.JSONDecodeError:
        return {"error": "Invalid JSON input."}

    score, score_notes = score_bid(details)
    is_fraud, fraud_notes = check_fraud(details)

    result = {
        "proposal_title": details.get("proposal_title", "Untitled"),
        "vendor_name": details.get("vendor_name"),
        "email": details.get("email"),
        "pricing": details.get("pricing"),
        "delivery_timeline": details.get("delivery_timeline"),
        "valid_until": details.get("valid_until"),
        "ai_score": round(score, 2),
        "is_fraud": is_fraud,
        "notes": {"scoring": score_notes, "fraud": fraud_notes}
    }
    return result

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"error": "No bid data provided. Usage: python script.py '<json_data>'"}))
        sys.exit(1)

    bid_data = sys.argv[1]
    result = analyze_bid(bid_data)
    print(json.dumps(result, indent=2))
