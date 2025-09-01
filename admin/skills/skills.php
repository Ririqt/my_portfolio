<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Skills of the user">
    <link rel="stylesheet" href="/my_portfolio/css/skills.css">
    <title> Skills </title>

</head>
<body>
    <?php
        session_start();
        require_once($_SERVER['DOCUMENT_ROOT']. "/my_portfolio/php/config.php");
        include('backend_skills.php');

        $user_id = $_SESSION['id'];
        $rows_per_page = 6;

        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;

        $offset = ($page - 1) * $rows_per_page;

        $total_query = "SELECT COUNT(*) as total FROM skills WHERE user_id=$user_id";
        $total_result = mysqli_query($conn, $total_query);
        $total = mysqli_fetch_assoc($total_result)['total'];

        $total_pages = ceil($total / $rows_per_page); 

        $query = "SELECT * FROM skills WHERE user_id=$user_id LIMIT $rows_per_page OFFSET $offset";
        $result = mysqli_query($conn, $query);
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
                        <li id="logs"> <a href="/my_portfolio/php/logs.php"> Logs </a> </li>
                        <li id="inquiries"> <a href="/my_portfolio/php/inquiries.php"> Inquiries </a> </li>
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

            if (isset($_SESSION['error'])) {
                echo '<div class="edited-message">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']); 
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
                            if ($row['rate'] ===  "Very Good") {
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

<div id="nav"> 
    <?php if ($total_pages > 1): //Pagination ?>
        <?php 
            $max_visible = 5;
            $start = max(1, $page - floor($max_visible / 2)); //floor rounds down
            $end = min($total_pages, $start + $max_visible - 1);

            if ($end - $start + 1 < $max_visible) {
                $start = max(1, $end - $max_visible + 1);
            }
        ?>

        <?php if ($page > 1): ?>
            <a href="?page=1"> First </a> <!-- Page 1 this is useful when page has been double-triple digit-->
            <a href="?page=<?php echo $page - 1; ?>"> Prev </a> <!-- Allocating the previous page -->
        <?php endif; ?> <!-- signifies the end of the condition statement-->

        <!-- Page numbers -->
        <?php for ($i = $start; $i <= $end; $i++): ?>
            <?php if ($i == $page): ?> <!-- If i reaches the certain page it will echo that i--> 
                <b> <?php echo $i; ?> </b> <!-- Current Page -->
            <?php else: ?> <!-- Everything Else -->
                <a href="?page=<?php echo $i; ?>"> <!-- allocating the other pages --> <?php echo $i; ?> </a>
            <?php endif; ?>
        <?php endfor; ?>

        <!-- Next and Last -->
        <?php if ($page < $total_pages): ?> 
            <a href="?page=<?php echo $page + 1; ?>">Next</a>
            <a href="?page=<?php echo $total_pages; ?>">Last</a>
        <?php endif; ?>

    <?php endif; ?>
</div>
</body>    
    <script>
    if (window.history.replaceState ) {
        window.history.replaceState(null, null, window.location.href );
    }
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</html>