import re
import sys
import json
import logging
import numpy as np
from datetime import datetime
from typing import Dict, Tuple, List, Optional
from sklearn.linear_model import LinearRegression, LogisticRegression
from sklearn.preprocessing import StandardScaler
import pickle

# Configure logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

# Mock training data (replace with real data in practice)
TRAINING_DATA = {
    "bids": [
        {"pricing": "₱50000", "delivery_days": 30, "valid_days": 60, "score": 85, "is_fraud": 0},
        {"pricing": "₱2000000", "delivery_days": 180, "valid_days": 10, "score": 40, "is_fraud": 1},
        {"pricing": "₱100000", "delivery_days": 60, "valid_days": 90, "score": 75, "is_fraud": 0},
        # Add more examples...
    ]
}

# Pre-trained model paths (save/load after training)
SCORE_MODEL_PATH = "score_model.pkl"
FRAUD_MODEL_PATH = "fraud_model.pkl"
SCALER_PATH = "scaler.pkl"

def parse_date(date_str: str, formats: List[str] = ["%Y-%m-%d", "%B %Y", "%B %d, %Y"]) -> Optional[datetime]:
    """Safely parse a date string with multiple formats."""
    for fmt in formats:
        try:
            return datetime.strptime(date_str, fmt)
        except ValueError:
            continue
    return None

def extract_features(details: Dict[str, str]) -> Tuple[np.ndarray, List[str]]:
    """Extract features from bid details for ML models."""
    features = []
    notes = []
    now = datetime.now()

    # Pricing feature
    price = 0.0
    if "pricing" in details and details["pricing"]:
        try:
            price = float(details["pricing"].replace('₱', '').replace(',', '').strip())
            features.append(price)
        except (ValueError, TypeError):
            notes.append("Invalid pricing format.")
            features.append(0.0)
    else:
        notes.append("Pricing missing.")
        features.append(0.0)

    # Delivery timeline feature (days until delivery)
    delivery_days = 0
    if "delivery_timeline" in details and details["delivery_timeline"]:
        timeline_date = parse_date(details["delivery_timeline"])
        if timeline_date:
            delivery_days = (timeline_date - now).days
            features.append(max(0, delivery_days))  # No negative days
        else:
            notes.append("Invalid delivery timeline format.")
            features.append(0)
    else:
        notes.append("Delivery timeline missing.")
        features.append(0)

    # Validity feature (days valid)
    valid_days = 0
    if "valid_until" in details and details["valid_until"]:
        valid_date = parse_date(details["valid_until"])
        if valid_date:
            valid_days = (valid_date - now).days
            features.append(max(0, valid_days))
        else:
            notes.append("Invalid valid_until format.")
            features.append(0)
    else:
        notes.append("Valid until missing.")
        features.append(0)

    return np.array(features).reshape(1, -1), notes

def train_models(training_data: Dict) -> Tuple[LinearRegression, LogisticRegression, StandardScaler]:
    """Train ML models on historical bid data."""
    X = []
    y_score = []
    y_fraud = []

    # Prepare training data
    for bid in training_data["bids"]:
        price = float(bid["pricing"].replace('₱', '').replace(',', ''))
        X.append([price, bid["delivery_days"], bid["valid_days"]])
        y_score.append(bid["score"])
        y_fraud.append(bid["is_fraud"])

    X = np.array(X)
    y_score = np.array(y_score)
    y_fraud = np.array(y_fraud)

    # Scale features
    scaler = StandardScaler()
    X_scaled = scaler.fit_transform(X)

    # Train scoring model (regression)
    score_model = LinearRegression()
    score_model.fit(X_scaled, y_score)

    # Train fraud model (classification)
    fraud_model = LogisticRegression()
    fraud_model.fit(X_scaled, y_fraud)

    # Save models and scaler
    with open(SCORE_MODEL_PATH, 'wb') as f:
        pickle.dump(score_model, f)
    with open(FRAUD_MODEL_PATH, 'wb') as f:
        pickle.dump(fraud_model, f)
    with open(SCALER_PATH, 'wb') as f:
        pickle.dump(scaler, f)

    return score_model, fraud_model, scaler

def load_models() -> Tuple[LinearRegression, LogisticRegression, StandardScaler]:
    """Load pre-trained models."""
    try:
        with open(SCORE_MODEL_PATH, 'rb') as f:
            score_model = pickle.load(f)
        with open(FRAUD_MODEL_PATH, 'rb') as f:
            fraud_model = pickle.load(f)
        with open(SCALER_PATH, 'rb') as f:
            scaler = pickle.load(f)
    except FileNotFoundError:
        logger.info("Training new models as no pre-trained models found.")
        score_model, fraud_model, scaler = train_models(TRAINING_DATA)
    return score_model, fraud_model, scaler

def analyze_bid(bid_data: str) -> Dict:
    """Analyze bid data using ML models."""
    try:
        details = json.loads(bid_data)
        if not isinstance(details, dict):
            return {"error": "Input must be a JSON object."}
    except json.JSONDecodeError:
        return {"error": "Invalid JSON input."}

    # Load ML models
    score_model, fraud_model, scaler = load_models()

    # Extract features
    features, notes = extract_features(details)
    features_scaled = scaler.transform(features)

    # Predict score and fraud
    score = score_model.predict(features_scaled)[0]
    score = max(0, min(100, score))  # Cap between 0 and 100
    is_fraud_prob = fraud_model.predict_proba(features_scaled)[0][1]
    is_fraud = is_fraud_prob > 0.5

    fraud_notes = []
    if is_fraud:
        fraud_notes.append(f"Fraud probability: {is_fraud_prob:.2f}")
    else:
        fraud_notes.append(f"Legitimate probability: {1-is_fraud_prob:.2f}")

    # Compile result
    result = {
        "proposal_title": details.get("proposal_title", "Untitled"),
        "vendor_name": details.get("vendor_name"),
        "email": details.get("email"),
        "pricing": details.get("pricing"),
        "delivery_timeline": details.get("delivery_timeline"),
        "valid_until": details.get("valid_until"),
        "ai_score": round(score, 2),
        "is_fraud": bool(is_fraud),
        "notes": {"scoring": notes, "fraud": fraud_notes}
    }
    return result

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"error": "No bid data provided. Usage: python script.py '<json_data>'"}))
        sys.exit(1)

    bid_data = sys.argv[1]
    result = analyze_bid(bid_data)
    print(json.dumps(result, indent=2))
