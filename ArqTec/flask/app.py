from flask import Flask, request, jsonify
import joblib
import pandas as pd

app = Flask(__name__)

# Cargar modelo
modelo = joblib.load('modelo_desercion.pkl')

# Ruta para predicción
@app.route('/predecir', methods=['POST'])
def predecir():
    datos = request.get_json()

    columnas = [
        'Tuition fees up to date',
        'International',
        'Curricular units 2nd sem (approved)',
        'Curricular units 2nd sem (enrolled)',
        'Debtor',
        'Scholarship holder',
        'Curricular units 1st sem (approved)',
        'Displaced'
    ]

    df = pd.DataFrame([datos], columns=columnas)
    pred = modelo.predict(df)[0]
    return jsonify({'resultado': int(pred)})

if __name__ == '__main__':
    app.run(debug=True)
