

import pickle
import requests
import re

# Replace with your NewsAPI key
API_KEY = "85f75efd0dca49da9bf7cfe8178d1e5b"

# Load the trained model and vectorizer
with open("model.pkl", "rb") as model_file:
    clf = pickle.load(model_file)

with open("vectorizer.pkl", "rb") as vectorizer_file:
    vectorizer = pickle.load(vectorizer_file)

# Preprocessing function
def clean_text(text):
    """Remove special characters and keep keywords only"""
    text = text.lower()
    text = re.sub(r'[^a-zA-Z0-9\s]', '', text)
    return "+".join(text.split()[:5])  # Use only the first 5 keywords for better matching

def validate_news(statement):
    query = clean_text(statement)
    
    # Search recent and past news separately
    url_recent = f"https://newsapi.org/v2/top-headlines?q={query}&language=en&apiKey={API_KEY}"
    url_archive = f"https://newsapi.org/v2/everything?q={query}&language=en&apiKey={API_KEY}"
    
    response_recent = requests.get(url_recent).json()
    response_archive = requests.get(url_archive).json()
    
    all_articles = response_recent.get("articles", []) + response_archive.get("articles", [])
    
    if len(all_articles) == 0:
        return "Possibly Fake News ❌ (No relevant articles found)"
    
    reliable_sources = ["bbc.com", "cnn.com", "nytimes.com", "reuters.com","Biztoc.com","sportskeeda.com"]
    trusted_count = sum(1 for article in all_articles if any(src in article["url"] for src in reliable_sources))
    
    if trusted_count >= 2:
        return "Possibly Real News ✅ (Verified in multiple trusted sources)"
    elif trusted_count == 1:
        return "Possibly Real News ✅ (Found in one trusted source)"
    else:
        return "Possibly Fake News ❌ (Found, but not from trusted sources)"


# Prediction function
def predict_news(statement):
    # Preprocess and vectorize input
    cleaned_statement = clean_text(statement)
    input_vector = vectorizer.transform([cleaned_statement]).toarray()
    
    # Predict using ML model
    prediction = clf.predict(input_vector)
    result = "Real News ✅" if prediction[0] == 1 else "Fake News ❌"
    
    # Validate with improved API function
    api_validation = validate_news(statement)
    
    return f"Model Prediction: {result}\nAPI Validation: {api_validation}"

# Example Usage
if __name__ == "__main__":
    test_statement = input("Enter a news headline: ")
    print(predict_news(test_statement))
