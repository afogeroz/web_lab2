<?php
$servername = "localhost";
$username = "";
$password = "";
$dbname = "";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$score = $_POST['score'];

$sql = "INSERT INTO UserScores (username, score) VALUES ('$username', $score)";

if ($conn->query($sql) === TRUE) {
    echo "Данные успешно вставлены";
} else {
    echo "Ошибка: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>