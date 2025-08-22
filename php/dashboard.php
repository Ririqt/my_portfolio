<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <title> Dashboard </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- <script src=""></script> -->
    <!-- <script> 
        $('.button_log').on('click', function () {
            debugger;
            var div = $("#close");
            div.show();
        });

        $('.close_div').on('click', function(){
            var div = $("#close");
            div.hide();

        });
    </script> -->

    <script> 
    $(document).ready(function(){
        $("button").click(function(){
            $("#table").toggle();
        });
    });
    </script>
    
    <!-- <script> 
        $(document).ready(function(){ // jQuery: Document Ready Event //$(the selector).action() 
        $('#data').after('<div id="nav"></div>');
            var rowsShown = 5;
            var rowsTotal = $('#data tbody tr').length;
            var numPages = rowsTotal/rowsShown;
            for(i = 0;i < numPages;i++) {
                var pageNum = i + 1;
                $('#nav').append('<a href="javascript:void()" rel="'+i+'">'+pageNum+'</a> ');
            }
        $('#data tbody tr').hide();
        $('#data tbody tr').slice(0, rowsShown).show();
        $('#nav a:first').addClass('active');
        $('#nav a').bind('click', function(){

            $('#nav a').removeClass('active');
            $(this).addClass('active');
            var currPage = $(this).attr('rel');
            var startItem = currPage * rowsShown;
            var endItem = startItem + rowsShown;
            $('#data tbody tr').css('opacity','0.0').hide().slice(startItem, endItem).
            css('display','table-row').animate({opacity:1}, 300);
        });
    });

    </script> -->
</head>
<body>
    <?php
    session_start(); 
    require_once "config.php";
    if(empty($_SESSION['name']) && (empty($_SESSION['email'])) && (empty($_SESSION['id']))) {
        header('location: ../html/login.html');
        exit;
    }   

    $name = $email = $user_id = "";
    
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
        "role"=>"",
        "description"=>"",
        "file_name"=>"",
    ];

     

    if(mysqli_num_rows($result)>0) {
        $selected_data = mysqli_fetch_assoc($result);  
    }

    
    ?>
    
    <div class="header">
        <div class="header_container"> 
            <div class="nav_bar">
                <h1 class="header_title"> <a href="/my_portfolio/php/dashboard.php"> My Portfolio </a> </h1>
                <nav class="header_text">
                    <ul>
                        <li> <a href="../admin/about/about.php"> About </a> </li>
                        <li id="skills"> <a href="../admin/skills/skills.php"> Skills </a> </li>
                        <li id="projects"> <a href="../admin/projects/projects.php"> Projects </a> </li>
                        <li> <a href="/my_portfolio/php/logout.php" onclick="return confirm('Are you sure you want to Log Out?')"> Log Out </a> </li>
                    </ul>
                
                </nav> 
                <div class="user"> User: <?php echo $_SESSION['name'];  ?> </div> 
            </div>
        </div>
    </div>

    <div class="welcome"> Dashboard </div>
    
    
        <?php if (isset($_SESSION['login_message'])) {
                echo '<div class="login_message">'
                    . $_SESSION['login_message'] . '</div>';
            // Clear the session variable
                unset($_SESSION['login_message']);
        }?>
        <?php echo '<div class="full_name">', "Welcome ", $_SESSION["name"], "!", '</div>'; ?>
    <div class="quick_stats"> Quick Stats: 
        <?php
            $query = "SELECT COUNT(*) as total FROM skills WHERE user_id=$user_id";
            $result = mysqli_query($conn,$query);
            $data = mysqli_fetch_assoc($result);
            echo "<br> Total Skills:", $data['total']; 
        ?>

        <?php
            $query = "SELECT COUNT(*) as total FROM projects WHERE user_id=$user_id";
            $result = mysqli_query($conn,$query);
            $data = mysqli_fetch_assoc($result);
            echo "<br> Total Projects:", $data['total']; 
        ?>
    </div>


    <div class="about_section">
        <div class="about_container"> 
            <div class="about_picture container" style="background-image: url(<?php echo '../uploads/'. $user_id. "/". $selected_data['file_name']; ?>);"> </div>
            <div class="about_text"> 
                <div class="about"> <a href="../admin/about/about.php"> ABOUT </a> </div>
                <?php echo '<div class="about_role">', $selected_data['role'],'</div>'; ?>
                <?php echo '<div class="about_description">', $selected_data['description'],'</div>'; ?>
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
            <div class="skill"> <a href="../admin/skills/skills.php"> SKILLS </a> </div>
            <div class="skills_container" > 
                <div class="skills_fill">
                        <?php
                            $query = "SELECT * FROM skills WHERE user_id=$user_id";
                            $result = mysqli_query($conn,$query);

                            if(mysqli_num_rows($result)>0) {
                                while($row = mysqli_fetch_assoc($result)) {  
                
                                echo '<div class="skills_details">', '<a href="../admin/skills/edit_skills.php?edit=' , $row["id"] , '">', $row['name'],  '</a>', ' -';  
                                if ($row['rate'] ===  "Very Good") {
                                    echo '<span style="color:#83ff00"> Very Good </span>', '<br>';
                                    echo '<div class="very_good_container">', '<div class="very_good_bar">', '</div>','</div>'; 
                                } elseif ($row['rate'] ===  "Good") {
                                    echo '<span style="color:#9fe64e"> Good </span>';
                                    echo '<div class="good_container">', '<div class="good_bar">', '</div>','</div>'; 
                                } elseif ($row['rate'] ===  "Satisfactory") {
                                    echo '<span style="color:#fcf400"> Satisfactory </span>';
                                    echo '<div class="satisfactory_container">', '<div class="satisfactory_bar">', '</div>','</div>'; 
                                } elseif ($row['rate'] ===  "Bad") {
                                    echo '<span style="color:#f7bc25"> Bad </span>';
                                    echo '<div class="bad_container">', '<div class="bad_bar">', '</div>','</div>'; 
                                } elseif ($row['rate'] ===  "Very Bad") {
                                    echo '<span style="color:#fa230f"> Very Bad </span>';
                                    echo '<div class="very_bad_container">', '<div class="very_bad_bar">', '</div>','</div>'; 
                                }
                                echo '</div>'; 
                                }
                            } else {
                                echo '<div class="add_skills">' , "Add something by pressing the 'Skills' ", '</div>'; // put div here
                            }
                        ?>
                </div>
             </div>
        </div>
    </div>
    
    <div class="projects_section">
        <div class="projects_circle">
            <div class="projects"> <a href="../admin/projects/projects.php"> PROJECTS </a> </div>
            <div class="projects_container"> 
                <div class="projects_fill">
                    <?php
                        $query = "SELECT * FROM projects WHERE user_id=$user_id";
                        $result = mysqli_query($conn,$query);

                        if(mysqli_num_rows($result)>0) {
                            while($row = mysqli_fetch_assoc($result)) { 
                            echo '<div class="projects_details container" style="background-image: url('. '../uploads/projects/' . $user_id. "/". $row['file_name'].')"'.");". '>'                                                                     , '<div class="projects_bar"> ', 
                                    '<div class="projects_name">', 
                                        '<a href="../admin/projects/edit_projects.php?edit=' , $row["id"] , '">', $row['name'], '</a>' 
                                    ,'</div>';  
                            echo $row['description'], '<br>';  
                            echo $row["status"], '<br>', '</div>','</div>';  
                            }
                        } else {
                            echo "Add something here"; // put div here as well
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="activity_log"> 
        <button> Logs </button> 
    </div>    
        <div id="table" class="activity_log_data_table">
            <table class="styled_table" id="data">
                <thead>
                    <tr>
                        <th> User </th>
                        <th> Action </th>
                        <th> Time </th>
                    </tr>
                </thead>
        <?php
            $query = "SELECT * FROM logs ORDER BY timestamp DESC LIMIT 5 ";
            $result = mysqli_query($conn, $query);
            
            if(mysqli_num_rows($result)>0) {
                while($row = mysqli_fetch_assoc($result)) {
        ?>
        <tbody>
            <tr>
                <td> <?php echo $row['user_id']; ?> </td>  
                <td> <?php echo $row['action']; ?> </td> 
                <td> <?php echo $row['timestamp']; ?> </td> 
            <?php
                }
            } else {
                echo '<tr> <td colspan="3" id="add">' , "Nothing in the Logs" , "</td>" , "</tr>";
            }
        ?>
            </tr>
        </tbody>
            </table>

            <div class="see_all"> <a href="../php/logs.php"> See all </a> </div>
        </div>
    

    <div class="button">
        <button onclick="location.href='../html/index.html'" type="button"> Go to your Portfolio </button>
    </div> 


</body>
</html>