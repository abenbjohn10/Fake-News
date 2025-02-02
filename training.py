import pandas as pd
import numpy as np
import re
import string
from sklearn.model_selection import train_test_split
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.tree import DecisionTreeClassifier
from sklearn.metrics import accuracy_score, classification_report

# Load dataset from CSV
df = pd.read_csv("fake_news_dataset.csv")

def preprocess_text(text):
    """Function to clean the text data.""" 
    text = text.lower()  # Convert to lowercase
    text = re.sub(r'\[.*?\]', '', text)  # Remove text in square brackets
    text = re.sub(f"[{string.punctuation}]", '', text)  # Remove punctuation
    text = re.sub(r'\w*\d\w*', '', text)  # Remove words containing numbers
    return text

# Apply preprocessing
df['text'] = df['text'].apply(preprocess_text)
