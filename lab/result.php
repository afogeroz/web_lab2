<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результаты тестирования</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            text-align: center;
            margin: 50px auto;
        }

        .container {
            max-width: 800px;
            padding: 20px;
            background-color: #C0C0C0;
            border: 20px solid #008B8B;
            border-radius: 10px;
            display: inline-block;
        }

        canvas {
            margin-top: 20px;
        }
		.btn-retry {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #008B8B;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Результаты тестирования</h3>

        <?php
		session_start();
        $servername = "localhost";
        $username = "";
        $password = ""; 
        $dbname = "";
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        $scoreGroups = [];
        for ($i = 0; $i <= 8; $i++) {
            $scoreGroups[$i] = 0;
        }
		
        $user = $_SESSION["username"];
		
		sleep(2);
		$sql = "SELECT * FROM UserScores WHERE username = '$user' ORDER BY id DESC LIMIT 1";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastTestScore = $row['score'];
           
        } else {
            echo "<p>Нет данных о результатах тестирования для пользователя $user.</p>";
        }
        $sql = "SELECT * FROM UserScores";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $score = $row['score'];
                $scoreGroups[$score]++;
            }
        } else {
            echo "<p>Нет данных о результатах тестирования.</p>";
        }

        $conn->close();
        ?>

        <div>
            <canvas id="scoreChart" width="400" height="200"></canvas>
        </div>
		<button class="btn-retry" onclick="retryTest()">Пройти тест заново</button>
    </div>

    <script>
        var ctx = document.getElementById('scoreChart').getContext('2d');
        var scoreGroups = <?php echo json_encode(array_values($scoreGroups)); ?>;
        var labels = ['0/5','1/5', '2/5', '3/5', '4/5', '5/5'];
		var lasttest = <?php echo json_encode($lastTestScore); ?>;
		var backgroundColors = Array(6).fill('rgba(200, 200, 200, 0.5)');
		if (lasttest >= 0 && lasttest <= 5) {
			backgroundColors[lasttest] = 'rgba(0, 200, 200, 0.5)';
		}
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    backgroundColor: backgroundColors,
                    data: scoreGroups,
                    borderWidth: 1
                }]
            },
            options: {
				plugins: {
						legend: {
							display: false,
						}
                
                }
            }
        });
		 function retryTest() {
            window.location.href = 'index.php';
        }
    </script>
</body>
</html>