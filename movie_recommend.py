from flask import Flask, request, jsonify
import pandas as pd
import json
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

app = Flask(__name__)

def get_recommendations(title):
    df = pd.read_csv("C:/Users/Anbewwwwwwwwwwwwww/Downloads/Compressed/archive_10/tmdb_5000_movies.csv")

    def genres_and_keywords_to_string(row):
        genres = json.loads(row['genres'])
        genres = ' '.join(''.join(j['name'].split()) for j in genres)

        keywords = json.loads(row['keywords'])
        keywords = ' '.join(''.join(j['name'].split()) for j in keywords)
        return "%s %s" % (genres, keywords)

    df['string'] = df.apply(genres_and_keywords_to_string, axis=1)
    tfidf = TfidfVectorizer(max_features=2000)
    X = tfidf.fit_transform(df['string'])
    movie2idx = pd.Series(df.index, index=df['title'])

    idx = movie2idx[title]
    if type(idx) == pd.Series:
        idx = idx.iloc[0]

    query = X[idx]
    scores = cosine_similarity(query, X)
    scores = scores.flatten()
    recommended_idx = (-scores).argsort()[1:11]
    return list(df['title'].iloc[recommended_idx])

@app.route('/recommendations', methods=['GET'])
def recommendations():
    title = request.args.get('title')
    result = get_recommendations(title)
    return jsonify(result)

if __name__ == '__main__':
    app.run()
