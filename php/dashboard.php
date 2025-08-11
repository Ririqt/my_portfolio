<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <!-- <script> 

        var x = " // echo "$row"";
            document.write(x);
        var div = document.getElementById( 'myDiv' );
            if (score >= 85) {
            div.style.color = 'blue';
        }
    </script> -->
    <title> Dashboard </title>
</head>
<body>
    <?php
    session_start(); 

    $name = $email = "";
    require_once "config.php";
    $user_id = $_SESSION['id'];
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    //include($_SERVER['DOCUMENT_ROOT']. "/my_portfolio/admin/about/backend_about.php");
    //include($_SERVER['DOCUMENT_ROOT']. "/my_portfolio/admin/skills/backend_skills.php");
    // include($_SERVER['DOCUMENT_ROOT']. "/my_portfolio/admin/projects/backend_projects.php");

    // $query = "SELECT * FROM skills WHERE user_id=$user_id";
    // $result = mysqli_query($conn,$query);

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
        <div class="about_container"> 
            <div class="about_picture container"> </div>
            <div class="about_text"> 
                <div class="about"> ABOUT </div>
                <!-- <div class="about_role"> Your Role </div> -->
                <div class="about_details">
                    <div class="details">
                        <div class="column_1"> 
                            <div class="about_name">
                                <label for="details"> Name: </label> <?php echo $name ?> 
                            </div>

                            <div class="about_degree">
                                <label for="details"> Degree: </label><?php echo $selected_data['degree'] ?> 
                            </div>

                            <div class="about_phone">
                                <label for="details"> Phone: </label><?php echo $selected_data['phone'] ?> 
                            </div>

                            <div class="about_address">
                                <label for="details"> Address: </label><?php echo $selected_data['address'] ?> 
                            </div>
                        </div>

                        <div class="column_2">                        
                            <div class="about_birthday">
                                <label for="details"> Birthday: </label><?php echo $selected_data['birthday'] ?> 
                            </div>

                            <div class="about_experience">
                                <label for="details"> Experience: </label><?php echo $selected_data['experience'] ?> 
                            </div>

                            <div class="about_email">
                                <label for="details">  Email: </label> <?php echo $email ?> 
                            </div>

                            <div class="about_company">
                                <label for="details"> Company: </label><?php echo $selected_data['company'] ?> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="skills_section">
        <div class="skills_circle">
            <div class="skill"> SKILLS </div>
            <div class="skills_container"> 
                <div class="skills_fill">
                        <?php
                            $query = "SELECT * FROM skills WHERE user_id=$user_id";
                            $result = mysqli_query($conn,$query);

                            if(mysqli_num_rows($result)>0) {
                                while($row = mysqli_fetch_assoc($result)) {  
                
                                echo '<div class="skills_details">', $row['name'], '<br>';  
                                if ($row['rate'] ===  "5 - Very Good") {
                                    echo '<span style="color:#3ed13b"> Very Good </span>', '<br>' ;
                                } elseif ($row['rate'] ===  "4 - Good") {
                                    echo '<span style="color:#9fe64e"> Good </span>','<br>';
                                } elseif ($row['rate'] ===  "3 - Satisfactory") {
                                    echo '<span style="color:#fcf400"> Satisfactory </span>','<br>';
                                } elseif ($row['rate'] ===  "2 - Bad") {
                                    echo '<span style="color:#f7bc25"> Bad </span>','<br>';
                                } elseif ($row['rate'] ===  "1 - Very Bad") {
                                    echo '<span style="color:#fa230f"> Very Bad </span>','<br>';
                                }
                                echo '</div>'; 
                                }
                            } else {
                                echo "Add something here";
                            }
                        ?>
                </div>
             </div>
        </div>
    </div>
    
    <div class="projects_section">
        <div class="projects_circle">
            <div class="projects"> PROJECTS </div>
            <div class="projects_container"> 
                <div class="projects_fill">
                            <?php
                                $query = "SELECT * FROM projects WHERE user_id=$user_id";
                                $result = mysqli_query($conn,$query);

                                if(mysqli_num_rows($result)>0) {
                                    while($row = mysqli_fetch_assoc($result)) { 
                                    echo '<div class="projects_details">', $row['name'], '<br>';  
                                    echo $row['description'], '<br>';  
                                    echo $row["status"], '<br>','</div>';  
                                    }
                                } else {
                                     echo "Add something here"; 
                                }
                            ?>
                </div>
            </div>
        </div>
    </div>
     

    <div class="button">
        <button onclick="location.href='../html/index.html'" type="button"> Go to your Portfolio </button>
    </div> 


</body>
</html>