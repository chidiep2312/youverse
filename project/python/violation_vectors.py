import json
from sentence_transformers import SentenceTransformer
import mysql.connector

with open("bad_words.txt", "r", encoding="utf-8") as f:
    bad_words = [line.strip() for line in f if line.strip()]

model = SentenceTransformer('all-MiniLM-L6-v2')

db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="PassWord123",
    database="homie",
)
cursor = db.cursor()

for word in bad_words:
    vector = model.encode(word).tolist()
    embedding_json = json.dumps(vector)

    cursor.execute(
        "INSERT INTO violation_vectors (keyword, embedding) VALUES (%s, %s)",
        (word, embedding_json)
    )

db.commit()
cursor.close()
