<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <title> Dashboard </title>
</head>
<body>
    <?php
    session_start(); 

    $name = $email = "";
    require_once "config.php";
    
    // if (session_status() != PHP_SESSION_ACTIVE) {
    //     header("location: ../html/login.html");
    //     exit;
    // } 
    
    if(empty($_SESSION['name']) && (empty($_SESSION['email']))) {
        header('location: ../html/login.html');
        exit;
    }

    ?>
    
    <div class="header">
        <div class="header_container"> 
            <h1 class="header_title"> <a href="/my_portfolio/html/index.html"> My Portfolio </a> </h1>
                <nav class="header_text">
                    <ul>
                    <li> <a href="../admin/about/about.php"> About </a> </li>
                    <li> <a href="../admin/skills/skills.php"> Skills </a> </li>
                    <li> <a href="../admin/projects.php"> Projects </a> </li>
                    <li> <a href="../php/logout.php"> Log Out </a> </li>
                    </ul>
                </nav>
        </div>
    </div>

    <div class="welcome">
        Dashboard <br>
        <?php
        echo "Welcome ", $_SESSION["name"], "<br>";
        echo "Your details ", $_SESSION["email"];

        ?>
    </div>

    <!-- <div class="text">
        You can now access the Navigation Bar!
    </div> -->
    

</body>
</html>