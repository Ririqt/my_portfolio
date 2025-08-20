<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/my_portfolio/css/skills.css">
    <title> Skills </title>

    <script>
    if (window.history.replaceState ) {
        window.history.replaceState(null, null, window.location.href );
    }
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script> 
        $(document).ready(function(){ // jQuery: Document Ready Event //$(the selector).action() 
        $('#data').after('<div id="nav"></div>');
            var rowsShown = 6;
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

    </script>
</head>
<body>
    <?php
        session_start();
        require_once($_SERVER['DOCUMENT_ROOT']. "/my_portfolio/php/config.php");
        include('backend_skills.php');

        // unset($_SESSION['success_message']);
    ?> 

    <div class="header">
        <div class="header_container"> 
            <div class="nav_bar">
                <h1 class="header_title"> <a href="/my_portfolio/php/dashboard.php"> My Portfolio </a> </h1>
                <nav class="header_text">
                    <ul>
                        <li> <a href="../about/about.php"> About </a> </li>
                        <li id="skills"> <a href="../skills/skills.php"> Skills </a> </li>
                        <li id="projects"> <a href="../projects/projects.php"> Projects </a> </li>
                        <li> <a href="/my_portfolio/php/logout.php" onclick="return confirm('Are you sure you want to Log Out?')"> Log Out </a> </li>
                    </ul>
                </nav> 
                <div class="user"> User: <?php echo $_SESSION['name'];  ?> </div> 
            </div>
        </div>
    </div>
    

    
    <form action="skills.php" method="POST" class="skill_form"> 
        <div class="skill_section">
            <div class="skill_form"> Input Your Skill Here </div>
                <div class="skill_container">
                    <div class="skill_name">
                        <label for="name"> Skill: </label>
                        <input type="text" id="skill_name" name="skill_name" placeholder="Your Skill" required>
                    </div>

                    <div class="type">
                        <label for="type"> Type: </label>
                        <select id="type" name="type" required>
                            <option value=""> Select Type </option>
                            <option value="Soft"> Soft skills </option>
                            <option value="Hard"> Hard skills </option>
                            <option value="Transferable"> Transferable skills </option>
                            <option value="Personal"> Personal skills </option>
                            <option value="Knowledge-based"> Knowledge-based skills </option>
                        </select>
                    </div>
                </div>

                <div class="rate_table_section"> 
                    <div class="rate_table_container">  
                        <div class="rate_table"> Rate your Skill </div>
                            <label for="rate"> 5 - Very Good <br />
                                <span class="star_rating">
                                    <input type="radio" id="very_good" name="rate" value="Very Good" required><i></i>
                                </span>
                            </label> 
                            
                            <label for="rate"> 4 - Good<br /> 
                                <input type="radio" id="good" name="rate" value="Good" required> 
                            </label>  
                            
                            <label for="rate"> 3 - Satisfactory <br />
                                <input type="radio" id="satisfactory" name="rate" value="Satisfactory" required>
                            </label>

                            <label for="rate"> 2 - Bad <br />
                                <input type="radio" id="bad" name="rate" value="Bad" required>
                            </label>

                            <label for="rate"> 1 - Very Bad <br />
                                <input type="radio" id="very_bad" name="rate" value="Very Bad" required>
                            </label>
                        </div>  

                        <label for="submit">
                            <input type="submit" value="Submit">
                        </label>
                    </div>
                </div>
        </div> 
        <?php
            if (isset($_SESSION['success_message'])) {
                echo '<div class="success-message">'
                    . $_SESSION['success_message'] . '</div>';
            // Clear the session variable
                unset($_SESSION['success_message']); 
            }
        
            if (isset($_SESSION['deleted_message'])) {
                echo '<div class="deleted-message">'
                    . $_SESSION['deleted_message'] . '</div>';
            // Clear the session variable
                unset($_SESSION['deleted_message']); 
            }
        
            if (isset($_SESSION['edited_message'])) {
                echo '<div class="edited-message">'
                    . $_SESSION['edited_message'] . '</div>';
            // Clear the session variable
                unset($_SESSION['edited_message']); 
            }

            if (isset($_SESSION['edit_none_message'])) {
                echo '<div class="edit-none-message">' . $_SESSION['edit_none_message'] . '</div>';
                unset($_SESSION['edit_none_message']); 
            }
        ?>
        <div id="table" class="table">
                    <table class="styled_table" id=data>
                        <thead>
                            <tr>
                                <th scope="col"> Name </th>
                                <th scope="col" id="col1"> Type </th>
                                <th scope="col" id="col2"> Skills </th>
                                <th scope="col" id="col3"> Edit </th>
                                <th scope="col" id="delete"> <a href="skills.php?delete_all=<?php echo $user_id;?>" onclick="return confirm('Are you sure you want to Delete all of the entries?')"> Delete </a> </th>
                            </tr>
                        </thead>
                        <?php
                             if(mysqli_num_rows($result)>0) {
                                while($row = mysqli_fetch_assoc($result)) {  
                        ?>
                        <tbody>
                            <tr> 
                                <td> <?php echo $row['name']; ?> </td>
                                <td> <?php echo $row['type']; ?> </td>
                                <td> 
                                <?php 
                                // $rate = ["5 - Very Good", "4 - Good", "3 - Satisfactory", "2 - Bad", "1 - Very Bad"];  

                                // foreach ($rate as $value) {
                                //     $checked = ($row['rate'] === $value) ? "checked" : "";
                                ?>
                                    <?php if ($row['rate'] ===  "Very Good") {
                                    echo '<span style="color:#00d312"> Very Good </span>', '<br>';
                                
                                    } elseif ($row['rate'] ===  "Good") {
                                    echo '<span style="color:#9fe64e"> Good </span>';
                                
                                    } elseif ($row['rate'] ===  "Satisfactory") {
                                    echo '<span style="color:#fcf400"> Satisfactory </span>';

                                    } elseif ($row['rate'] ===  "Bad") {
                                    echo '<span style="color:#f7bc25"> Bad </span>';

                                    } elseif ($row['rate'] ===  "Very Bad") {
                                    echo '<span style="color:#fa230f"> Very Bad </span>';
                                } 
                                    ?>
                                <?php
                                // }
                                ?>
                                </td>
                                <td> <a href="../skills/edit_skills.php?edit=<?php echo $row["id"];?>"> Edit </a> </td> 
                                <td> <a href="skills.php?delete=<?php echo $row["id"];?>" onclick="return confirm('Are you sure you want to Delete?')"> Delete </a> </td>
                            </tr>
                        <?php
                                }
                                } else {
                        ?>
                            <tr> <td colspan="5" id="add"> Add something here. </td> </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
        </div>             
</form>
</body>
</html>