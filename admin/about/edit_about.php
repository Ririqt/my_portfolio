<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/my_portfolio/css/edit_about.css">
    <title> Edit About </title>
</head>
<body>

    <?php
    session_start();

    require_once($_SERVER['DOCUMENT_ROOT']. "/my_portfolio/php/config.php");
    include 'backend_edit_about.php';
    ?>

    <div class="header">
        <div class="header_container"> 
            <h1 class="header_title"> <a href="/my_portfolio/php/dashboard.php"> My Portfolio </a> </h1>
            <nav class="header_text">
                <ul>
                    <li id="about"> <a href="#about"> About </a> </li>
                    <li> <a href="../skills/skills.php"> Skills </a> </li>
                    <li> <a href="../projects/projects.php"> Projects </a> </li>
                    <li> <a href="/my_portfolio/php/logout.php" onclick="return confirm('Are you sure you want to Log Out?')"> Log Out </a> </li>
                </ul>
            </nav>
        </div>
    </div>

    <form action="edit_about.php" method="POST" class="project_form"> 
        <div class="about_section">
            <div class="about_form"> Edit Your Details </div>
                <div class="about_container">
                    <div class="about_name">
                        <label for="details"> Name: </label> <?php echo $name ?> 
                    </div>

                    <div class="about_email">
                        <label for="details"> Email: </label> <?php echo $email ?> 
                    </div>

                    <div class="about_degree">
                        <label for="details"> Degree: </label>
                        <input type="text" id="degree" name="degree" value="<?php echo $selected_data['degree']?>" required>
                    </div>

                    <div class="about_birthday">
                        <label for="details"> Birthday: </label>
                        <input type="date" id="birthday" name="birthday" value="<?php echo $selected_data['birthday']?>" required>
                    </div>

                    <div class="about_experience">
                        <label for="details"> Experience: </label>
                        <input type="text" id="experience" name="experience" value="<?php echo $selected_data['experience']?>" required>
                    </div>

                    <div class="about_phone">
                        <label for="details"> Phone: </label>
                        <input type="number" id="phone" name="phone" value="<?php echo $selected_data['phone']?>" required>
                    </div>

                    <div class="about_address">
                        <label for="details"> Address: </label>
                        <input type="text" id="address" name="address" value="<?php echo $selected_data['address']?>" required>
                    </div>

                    <div class="about_company">
                        <label for="details"> Company: </label>
                        <input type="text" id="company" name="company" value="<?php echo $selected_data['company']?>" required>
                    </div>

                    <label for="submit">
                        <input type="Submit" name="Submit" value="Submit">
                    </label>
                    
                    <div class="back_button">
                        <button onclick="location.href='../about/about.php'" type="button"> Go back </button>
                    </div>
                </div>
            </div>
    </form>

    
</body>
</html>