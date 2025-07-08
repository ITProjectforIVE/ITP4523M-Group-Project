from flask import Flask, jsonify

app = Flask(__name__)

# Predefined exchange rates
exchange_rates = {
    "JPY": 110,
    "EUR": 0.82,
    "HKD": 7.8
}

@app.route('/cost_convert/<float:amount>/<currency>/<float:rate>', methods=['GET'])
def cost_convert(amount, currency, rate):
    # Validate amount
    if amount <= 0:
        return jsonify({"result": "rejected", "reason": "Amount must be a positive number"})

    # Validate currency
    if currency not in exchange_rates:
        return jsonify({"result": "rejected", "reason": "Error: Currency must be 'HKD', 'EUR', or 'JPY'"})

    # Validate rate
    if rate <= 0:
        return jsonify({"result": "rejected", "reason": "Rate must be a positive number"})

    # Perform conversion
    converted_amount = amount * rate
    return jsonify({"result": "accepted", "converted_amount": converted_amount})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8080)