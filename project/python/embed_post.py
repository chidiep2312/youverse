
import sys
import json
from sentence_transformers import SentenceTransformer
import mysql.connector

post_id = sys.argv[1]

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


model = SentenceTransformer('all-MiniLM-L6-v2')
text = post['title'] + "\n" + post['content']
embedding = model.encode(text).tolist() 

embedding_json = json.dumps(embedding)
cursor.execute("UPDATE posts SET embedding = %s WHERE id = %s", (embedding_json, post_id))
db.commit()


