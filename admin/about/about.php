<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/my_portfolio/css/about.css">
    <title> About Me </title>

    <script>
    if (window.history.replaceState ) {
        window.history.replaceState(null, null, window.location.href );
    }
    </script>

</head>
<body>
    <?php
        session_start();
        
        require_once($_SERVER['DOCUMENT_ROOT']. "/my_portfolio/php/config.php");
        include 'backend_about.php';
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

    <div class="about_section">
        <div class="about_form"> Your Details  </div>
            <div class="about_container">
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

                <form action="../about/edit_about.php">
                    <input type="submit" value="Edit About" />
                </form>
            </div>
        </div>

</body>
</html>