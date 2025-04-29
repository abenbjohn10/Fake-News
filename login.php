<?php
// DB connection
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "fake_news";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$user = $_POST['username'];
$pass = $_POST['password'];

// Prepare and execute query
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = SHA2(?, 256)");
$stmt->bind_param("ss", $user, $pass);
$stmt->execute();
$result = $stmt->get_result();

// Output styled response
echo '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Status</title>
    <style>
        body {
            font-family: "Inter", sans-serif;
            background: linear-gradient(135deg, #dfe9f3, #ffffff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .message-box {
            background: #ffffff;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            text-align: center;
            max-width: 360px;
            animation: fadeIn 0.8s ease forwards;
            opacity: 0;
        }
        h2 {
            margin-bottom: 1.5rem;
            font-size: 1.6rem;
            color: #333;
        }
        .success {
            color: #28a745;
        }
        .error {
            color: #dc3545;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            margin-top: 1rem;
            background: #4a90e2;
            color: #fff;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #357ab8;
        }
        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
        }
    </style>
</head>
<body>
';

if ($result->num_rows === 1) {
    echo '
    <div class="message-box">
        <h2 class="success">Login Successful!</h2>
        <p>Welcome to the Fake News Website.</p>
        <a class="btn" href="welcome.php"> Check Article</a>
    </div>
    ';
} else {
    echo '
    <div class="message-box">
        <h2 class="error">Login Failed!</h2>
        <p>Invalid username or password.</p>
        <a class="btn" href="index.html">Try Again</a>
    </div>
    ';
}

echo '
</body>
</html>
';

$stmt->close();
$conn->close();
?>
