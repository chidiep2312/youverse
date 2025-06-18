import sys
import json
import re
from sentence_transformers import SentenceTransformer
import mysql.connector

post_id = sys.argv[1]

# Kết nối DB
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="PassWord123",
    database="homie",
)
cursor = db.cursor(dictionary=True)


cursor.execute("SELECT title, content FROM posts WHERE id = %s", (post_id,))
post = cursor.fetchone()
if not post:
    sys.exit()


def split_sentences(text):
    text = re.sub(r"\s+", " ", text.strip())
    return re.split(r'(?<=[.!?])\s+', text)

full_text = post['title'] + ". " + post['content']
sentences = split_sentences(full_text)
sentences = [s for s in sentences if len(s.strip()) > 5]  

model = SentenceTransformer('all-MiniLM-L6-v2')
embeddings = model.encode(sentences).tolist()

data = {
    "sentences": sentences,
    "embeddings": embeddings
}
embedding_json = json.dumps(data, ensure_ascii=False)


cursor.execute("UPDATE posts SET vio_embedding = %s WHERE id = %s", (embedding_json, post_id))
db.commit()
cursor.close()
