<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <title> Dashboard </title>
</head>
<body>
    <div class="header">
        <div class="header_container"> 
            <h1 class="header_title"> <a href="#index"> My Portfolio </a> </h1>
                <nav class="header_text">
                    <ul>
                    <li> <a href="#about"> About </a> </li>
                    <li> <a href="#skills"> Skills </a> </li>
                    <li> <a href="../admin/projects.php"> Projects </a> </li>
                    <li> <a href="../php/logout.php"> Log Out </a> </li>
                    </ul>
                </nav>
        </div>
    </div>

    <div class="welcome">
        Dashboard 
    </div>
    <?php
    session_start(); 

    $name = $email = "";
    require_once "config.php";

    echo "Welcome ", $_SESSION["name"], "<br>";
    echo "Your details ", $_SESSION["email"];
    
    ?>
</body>
</html>