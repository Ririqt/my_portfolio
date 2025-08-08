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
    include($_SERVER['DOCUMENT_ROOT']. "/my_portfolio/admin/about/backend_about.php");

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
                    <li> <a href="../admin/projects/projects.php"> Projects </a> </li>
                    <li> <a href="../php/logout.php"> Log Out </a> </li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="welcome"> Dashboard <br> </div>
        <?php echo '<div class="full_name">',"Welcome ", $_SESSION["name"], "!", '</div>'; ?>


    <div class="about_section">
        <div class="about_circle">
            <div class="about"> About </div>
            <div class="about_container">
                <div class="details">
                    <div class="column_1"> 
                        <div class="about_name">
                            <label for="details"> Name: </label> <?php echo $name ?> 
                        </div>

                        <div class="about_email">
                        <label for="details">  Email: </label> <?php echo $email ?> 
                        </div>

                        <div class="about_degree">
                            <label for="details"> Degree: </label><?php echo $selected_data['degree'] ?> 
                        </div>

                        <div class="about_birthday">
                        <label for="details"> Birthday: </label><?php echo $selected_data['birthday'] ?> 
                        </div>
                    </div>

                    <div class="column_2">
                        <div class="about_experience">
                        <label for="details"> Experience: </label><?php echo $selected_data['experience'] ?> 
                        </div>

                        <div class="about_phone">
                            <label for="details"> Phone: </label><?php echo $selected_data['phone'] ?> 
                        </div>

                        <div class="about_address">
                            <label for="details"> Address: </label><?php echo $selected_data['address'] ?> 
                        </div>

                        <div class="about_company">
                            <label for="details"> Company: </label><?php echo $selected_data['company'] ?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="skills_section">
        <div class="skills_circle">
            <div class="skill"> Skills </div>
            <div class="skills_container"> 



            </div>
        </div>
    </div>






























    <div class="button">
        <button onclick="location.href='../html/index.html'" type="button"> Go to your Portfolio </button>
    </div>
</body>
</html>