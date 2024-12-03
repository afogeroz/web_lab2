<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Страница теста</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #C0C0C0;
            border: 20px solid #008B8B;
            border-radius: 10px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input {
            padding: 10px;
            width: 100%;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 20px;
            background-color: #008B8B;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #006666;
        }
    </style>
</head>

<?php
session_start();
session_destroy();
?>
<body>
    <div class="container">
        <h1>Тест</h1>
        <form action="test.php" method="post">
            <label for="username">Введите Ваше имя:</label>
            <input type="text" id="username" name="username" required>
            <button type="submit">Начать</button>
        </form>
    </div>
</body>

</html>