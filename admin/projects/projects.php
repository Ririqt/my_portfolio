<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta HTTP-EQUIV="Pragma" content="no-cache">
    <meta HTTP-EQUIV="Expires" content="-1" >
    <meta name="description" content="Projects of the user">
    <link rel="stylesheet" href="/my_portfolio/css/projects.css">
    <title> Projects </title>
</head>
<body>
    <?php
        session_start();
        require_once($_SERVER['DOCUMENT_ROOT']. "/my_portfolio/php/config.php");
        include('backend_projects.php');

        $user_id = $_SESSION['id'];
        $rows_per_page = 6;

        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;

        $offset = ($page - 1) * $rows_per_page;

        $total_query = "SELECT COUNT(*) as total FROM projects WHERE user_id=$user_id";
        $total_result = mysqli_query($conn, $total_query);
        $total = mysqli_fetch_assoc($total_result)['total'];

        $total_pages = ceil($total / $rows_per_page); 

        $query = "SELECT * FROM projects WHERE user_id=$user_id LIMIT $rows_per_page OFFSET $offset";
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

    <form action="projects.php" method="POST" class="project_form" enctype="multipart/form-data"> 
        <div class="project_section">
            <div class="project_create"> Create a Project Here </div>
                <div class="project_container">
                    <div class="project_name">
                        <label for="name"> Name: </label>
                        <input type="text" id="project_name" name="project_name" placeholder="Your Project Name" required>
                    </div>

                    <div class="description">
                        <label for="description"> Description: </label>
                        <textarea type="text" id="description" name="description" rows="4" cols="38" placeholder="description" required> </textarea>
                    </div>

                    <div class="status"> 
                        <label for="status"> Status: </label>
                        <select id="status" name="status" required>
                            <option value=""> Select Status </option>
                            <option value="Start"> Start </option>
                            <option value="In Progress"> In Progress </option>
                            <option value="Done"> Done </option>
                        </select>
                    </div>

                    <div class="for_file">
                        <div class="about_file">
                            <label for="upload"> Project Picture: 
                            <input type="file" name="fileToUpload"> </label>
                        </div>
                    </div>

                    <div class="create_button">
                        <button type="submit"> Create </button>
                    </div>
                </div>

                <?php
                    if (isset($_SESSION['success_message'])) {
                        echo '<div class="success-message">' . $_SESSION['success_message'] . '</div>';
                        unset($_SESSION['success_message']); 
                    }
                
                    if (isset($_SESSION['deleted_message'])) {
                        echo '<div class="deleted-message">' . $_SESSION['deleted_message'] . '</div>';
                        unset($_SESSION['deleted_message']); 
                    }
                
                    if (isset($_SESSION['edited_message'])) {
                        echo '<div class="edited-message">' . $_SESSION['edited_message'] . '</div>';
                        unset($_SESSION['edited_message']); 
                    }

                    if (isset($_SESSION['error'])) {
                        echo '<div class="edited-message">' . $_SESSION['error'] . '</div>';
                        unset($_SESSION['error']); 
                    }

                    if (isset($_SESSION['edit_none_message'])) {
                        echo '<div class="edit-none-message">' . $_SESSION['edit_none_message'] . '</div>';
                        unset($_SESSION['edit_none_message']); 
                    }
                ?>

                <div id="table" class="table">
                <table class="styled_table" id="data">
                    <thead>
                        <tr>
                            <th id="name"> Name </th>
                            <th id="description"> Description </th>
                            <th> Status </th> 
                            <th id='delete'> <a href="projects.php?delete_all=<?php echo $user_id;?>" onclick="return confirm('Are you sure you want to Delete all of the entries?')"> Delete </a> </th>
                            <th id='edit'> Edit </th>
                            <th id='upload'> Upload </th>
                        </tr>
                    </thead>
                        <?php
                            if(mysqli_num_rows($result)>0) {
                                while($row = mysqli_fetch_assoc($result)) { 
                        ?>
                        <tbody>
                            <tr> 
                                <td> <?php echo $row['name']; ?> </td>
                                <td id="description"> <?php echo $row['description']; ?> </td>
                                <td> <?php echo $row["status"]; ?> </td>
                                <td> <a href="projects.php?delete=<?php echo $row["id"];?>" onclick="return confirm('Are you sure you want to Delete?')"> Delete </a> </td>
                                <td> <a href="edit_projects.php?edit=<?php echo $row["id"];?>"> Edit </a> </td>
                                <td> <?php if ($row['file_name'] === null || $row['file_name'] == "") {
                                        echo "No File Uploaded";
                                    } else {
                                        echo $row['file_name'];
                                        } ?> </td>
                        
                            </tr>
                        
                        <?php
                                }
                            } else {
                        ?>
                            <tr> <td colspan="6" id="add"> Add something here. </td> </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                </table>
                </div>
            </div>
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
</html>
 <script>
    if (window.history.replaceState ) {
        window.history.replaceState(null, null, window.location.href );
    }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    