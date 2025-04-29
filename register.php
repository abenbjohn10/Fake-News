<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "fake_news";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = $_POST['username'];
$pass = $_POST['password'];

// Check if user already exists
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

$message = "";
$type = "";

if ($result->num_rows > 0) {
    $message = "User already exists! Try another username.";
    $type = "error";
} else {
    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, SHA2(?, 256))");
    $stmt->bind_param("ss", $user, $pass);
    if ($stmt->execute()) {
        $message = "Registration successful! You can now login.";
        $type = "success";
    } else {
        $message = "Something went wrong! Please try again.";
        $type = "error";
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Status</title>
    <style>
        /* Popup Container */
        #popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            display: none;
            z-index: 9999;
            text-align: center;
            max-width: 300px;
        }
        /* Success Style */
        .success {
            border-left: 8px solid #4CAF50;
        }
        /* Error Style */
        .error {
            border-left: 8px solid #F44336;
        }
        /* Button */
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 15px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body onload="showPopup()">

<div id="popup" class="<?php echo $type; ?>">
    <h2><?php echo ucfirst($type); ?></h2>
    <p><?php echo $message; ?></p>
    <button onclick="redirect()">OK</button>
</div>

<script>
    // Show Popup
    function showPopup() {
        document.getElementById('popup').style.display = 'block';
    }

    // Redirect Function
    function redirect() {
        <?php if ($type === 'success') { ?>
            window.location.href = "index.html";
        <?php } else { ?>
            window.location.href = "register.html";
        <?php } ?>
    }
</script>

</body>
</html>
