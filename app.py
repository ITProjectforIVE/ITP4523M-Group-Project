from flask import Flask, jsonify
from flask import request

app = Flask(app)

# Define exchange rates
EXCHANGE_RATES = {
    'JPY': 110,
    'EUR': 0.82,
    'HKD': 7.8
}

@app.route('/cost_convert/<float:amount>/<currency>/<float:rate>', methods=['GET'])
def cost_convert(amount, currency, rate):
    # Check if the amount and rate are positive
    if amount <= 0 or rate <= 0:
        return jsonify(result="rejected", reason="Amount and rate must be positive numbers."), 400

    # Validate the currency and rate
    if currency not in EXCHANGE_RATES:
        return jsonify(result="rejected", reason="Invalid currency. Must be 'EUR', 'HKD', or 'JPY'."), 400

    # Check if the provided rate matches the expected exchange rate
    expected_rate = EXCHANGE_RATES[currency]
    if rate != expected_rate:
        return jsonify(result="rejected", reason="Provided rate does not match the expected rate."), 400

    # Perform conversion
    converted_amount = amount * rate

    return jsonify(result="accepted", converted_amount=converted_amount)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=80)