<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "my_portfolio";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if(empty($_SESSION['name']) && (empty($_SESSION['email'])) && empty($_SESSION['id'])) {
            header('location: /my_portfolio/html/login.html');
            exit;
        }

        $id = $degree = $birthday = $experience = $address = $company = $phone = "";
        $degreeErr = $birthdayErr = $experienceErr = $addressErr = $companyErr = $phoneErr = "";

        $user_id = $_SESSION['id'];
        $name = $_SESSION['name'];
        $email = $_SESSION['email'];
        $query = "SELECT * FROM about WHERE user_id=$user_id";
        $result = mysqli_query($conn,$query);
        $selected_data = [
            "degree"=>"",
            "birthday"=>"",
            "experience"=>"",
            "phone"=>"",
            "address"=>"",
            "company"=>"",
        ];
        
        if(mysqli_num_rows($result)>0) {
            $selected_data = mysqli_fetch_assoc($result);  
        }
    ?>

    <div class="header">
        <div class="header_container"> 
            <h1 class="header_title"> <a href="/my_portfolio/php/dashboard.php"> My Portfolio </a> </h1>
            <nav class="header_text">
                <ul>
                    <li> <a href="#about"> About </a> </li>
                    <li> <a href="../skills/skills.php"> Skills </a> </li>
                    <li> <a href="../projects/projects.php"> Projects </a> </li>
                    <li> <a href="/my_portfolio/php/logout.php" onclick="return confirm('Are you sure you want to Log Out?')"> Log Out </a> </li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="about_section">
        <div class="about_form"> Your Details 
            <div class="about_container">
                <div class="about_name">
                    Name: <?php echo $name ?>
                </div>

                <div class="about_email">
                    Email: <?php echo $email ?>
                </div>

                <div class="about_degree">
                    Degree: <?php echo $selected_data['degree'] ?>
                </div>

                <div class="about_birthday">
                    Birthday: <?php echo $selected_data['birthday'] ?>
                </div>

                <div class="about_experience">
                    Experience: <?php echo $selected_data['experience'] ?>
                </div>

                <div class="about_phone">
                    Phone: <?php echo $selected_data['phone'] ?>
                </div>

                <div class="about_address">
                    Address: <?php echo $selected_data['address'] ?>
                </div>

                <div class="about_company">
                    Company: <?php echo $selected_data['company'] ?>
                </div>

                <form action="../about/edit_about.php">
                    <input type="submit" value="Edit About" />
                </form>
            </div>
        </div>
    </div>

    <?php 
    mysqli_close($conn); 
    ?>

</body>
</html>