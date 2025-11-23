<?php
$host = "localhost";
$dbname = "citycaredb";
$user = "root";
$pass = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $location = $_POST["location"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // INSERT query
    $stmt = $conn->prepare("
        INSERT INTO users (name, surname, username, email, location, password)
        VALUES (:name, :surname, :username, :email, :location, :password)
    ");

    // error check
    if (!$stmt) {
        die("Prepare failed: " . implode(" ", $conn->errorInfo()));
    }

    $success = $stmt->execute([
        ":name" => $name,
        ":surname" => $surname,
        ":username" => $username,
        ":email" => $email,
        ":location" => $location,
        ":password" => $password
    ]);

    if (!$success) {
        die("Execute failed: " . implode(" ", $stmt->errorInfo()));
    }

    echo "Account created successfully!";
    header("Location: ../public/login.php");
}
?>
