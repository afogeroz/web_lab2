<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тест</title>
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

    h3 {
        margin-bottom: 10px;
    }

    label {
        display: block;
        margin-bottom: 10px;
        text-align: left;
    }

    input {
        padding: 10px;
        margin-bottom: 10px;
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

    .question-container {
        display: none;
    }

    .active {
        display: block;
    }
</style>
</head>
<body>
    <?php
	session_start();
	if(isset($_SESSION["number"])==false){
		$servername = "localhost";
		$username = "";
		$password = ""; 
		$dbname = "";
		$conn = new mysqli($servername, $username, $password, $dbname);
		$sql = "SELECT * FROM questions ORDER BY RAND() LIMIT 5";
		$result = $conn->query($sql);
		$result = $conn->query($sql);
		$questions = [];
		$i = 0;
		$answers = [];
		while ($row = $result->fetch_assoc()) {
			$i++;
			$question_number = $i;
			$question_id = $row['question_id'];
			$question_text = $row['question_text'];
			$question_type = $row['question_type'];
			$questions[$question_number] = [
				'question_id' => $question_id,
				'question_text' => $question_text,
				'question_type' => $question_type,
			]; 
			$options_sql = "SELECT * FROM options WHERE question_id = $question_id";
			$options_result = $conn->query($options_sql);
			$options = [];
			while ($option_row = $options_result->fetch_assoc()) {
				$option_text = $option_row['option_text'];
				$is_correct = $option_row['is_correct'];
			  
				$options[] = [
					'option_text' => $option_text,
					'is_correct' => $is_correct,
				];
			}
			$answers[$question_number] = [
				'options' => $options,
				'question_type' => $question_type,
			];
		}
		$username = $_POST["username"];
		$_SESSION["number"] = 1;
		$_SESSION["username"] = $username;
		$_SESSION["answers"] = $answers;
		$_SESSION["questions"] = $questions;
		$_SESSION["score"] = 0;
		$_SESSION["userAnswers"] = [];
	}
	echoQuestion($_SESSION["number"]);
	 if (isset($_POST['myButton'])) {
		$questionNumber = $_SESSION["number"];
		$selectedAnswers = $_POST["question{$questionNumber}"] ?? [];
		$_SESSION["userAnswers"][$questionNumber] = $selectedAnswers;
		$correctAnswers = getCorrectAnswers($_SESSION["number"]);
		if($_SESSION["userAnswers"][$_SESSION["number"]] == $correctAnswers){
			$_SESSION["score"]++;
		}
		if($_SESSION["number"] < 5){
			$_SESSION["number"]++;
			header("Location: test.php");
			exit();
		}
		else{
			$username = $_SESSION["username"];
		$score = $_SESSION["score"];

		$servername = "localhost";
		$username_db = "";
		$password_db = ""; 
		$dbname = "";
		$conn = new mysqli($servername, $username_db, $password_db, $dbname);

		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		$insertQuery = "INSERT INTO UserScores (username, score) VALUES ('$username', '$score')";
		$conn->query($insertQuery);
			header("Location: result.php");
			exit();
		}
    }
	
	
	function echoQuestion($questionNumber)
{
    $questions = $_SESSION["questions"] ?? [];
    $options = $_SESSION["answers"][$questionNumber]['options'] ?? [];
    $questionType = $questions[$questionNumber]['question_type'] ?? '';

    if (!empty($questions[$questionNumber]['question_text'])) {
		echo "<div class='container'>";
        echo "<h3>{$questions[$questionNumber]['question_text']}</h3>";
        echo "<form method='post'>";
        foreach ($options as $option_id => $option) {
            $inputType = ($questionType == 'single') ? 'radio' : 'checkbox';
            $isChecked = in_array($option['option_text'], $_SESSION["userAnswers"][$questionNumber] ?? []);
            echo "<label><input type='{$inputType}' name='question{$questionNumber}[]' value='{$option['option_text']}'";
            echo $isChecked ? " checked" : "";
            echo " data-option-text='{$option['option_text']}'> {$option['option_text']}</label><br>";
        }
		if($_SESSION["number"] < 5){echo "<input type='submit' name='myButton' value='Следующий вопрос'>";}
		else{echo "<input type='submit' name='myButton' value='Завершить тестирование'>";}
        
        echo "</form>";
		echo "</div>";
    } else {
        echo "<p>Вопрос не найден.</p>";
    }
}
function getCorrectAnswers($questionNumber)
{
   
    if (isset($_SESSION["answers"][$questionNumber])) {
        $correctAnswers = [];
        $options = $_SESSION["answers"][$questionNumber]['options'];

        foreach ($options as $option) {
            if ($option['is_correct']) {
                $correctAnswers[] = $option['option_text'];
            }
        }

        return $correctAnswers;
    } else {
        return []; 
    }
}
	?>
	
</body>
</html>