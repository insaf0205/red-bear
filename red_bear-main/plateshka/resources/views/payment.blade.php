<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Страница оплаты</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }

        .payment-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
            border-top: 5px solid #FF6B35;
        }

        .payment-container h2 {
            color: #333;
            margin-bottom: 25px;
            font-size: 24px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-item label {
            font-weight: bold;
            color: #555;
            font-size: 16px;
        }

        .info-item span {
            color: #333;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 12px 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
            text-align: center;
        }

        .form-group input[type="text"]:focus {
            border-color: #FF6B35;
            outline: none;
        }

        .pay-button {
            background-color: #FF6B35;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
            box-sizing: border-box;
        }

        .pay-button:hover {
            background-color: #e05e2e;
        }

        .error {
            color: #d63031;
            font-size: 14px;
            margin-top: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="payment-container">
    <h2>Оплата заказа</h2>
    <div class="info-item">
        <label>Сумма платежа:</label>
        <span id="payment-amount">{{$payment->sum}} RUB</span>
    </div>
    <div class="info-item">
        <label>Статус платежа:</label>
        <span
            id="payment-status">@if($payment->status === "pending")
                Ожидает оплаты
            @elseif($payment->status === "success")
                Оплата прошла успешно
            @else
                Оплата не прошла
            @endif</span>
    </div>
    @if($payment->status === "pending")
        <form method="post" action="/payments/{{$payment->id}}">
            @method('PATCH')
            @csrf
            <div class="form-group">
                <label for="card-number">Номер карты:</label>
                <input type="text" id="card-number" name="card_number" placeholder="0000 0000 0000 0000" maxlength="19"
                       inputmode="numeric"
                       onkeyup="formatCardNumber(this)" required>
                @error('error')
                <span class="error">
                    {{$message}}
                </span>
                @enderror

                @error('card_number')
                <span class="error">
                    {{$message}}
                </span>
                @enderror
            </div>
            <button class="pay-button">Оплатить</button>
        </form>
    @endif
</div>

<script>
    function formatCardNumber(input) {
        let value = input.value.replace(/\D/g, '');
        let formattedValue = '';
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) {
                formattedValue += ' ';
            }
            formattedValue += value[i];
        }
        input.value = formattedValue;
    }
</script>
</body>
</html>
