<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Log Out</title>
</head>
<body>
    <?php
        session_start();
        require_once("config.php");
        $user_id = $_SESSION['id'];
        $action = "Logged Out";
        $sql = "INSERT INTO logs (user_id, action) VALUES ('$user_id', '$action')";

        if ($conn->query($sql) === TRUE) {
            
        }
        session_destroy(); //important for log out
        header("location: /my_portfolio/html/login.html"); // proceed to log in
        exit;
    ?>
</body>
</html>
