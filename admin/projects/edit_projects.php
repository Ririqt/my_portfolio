<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/my_portfolio/css/edit.css">
    <title> Edit Your Project </title>
</head>
<body>
<?php
    session_start();
    
    require_once($_SERVER['DOCUMENT_ROOT']. "/my_portfolio/php/config.php");
    include('backend_edit_projects.php');
    
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

    <form method="POST" class="project_form" enctype="multipart/form-data"> 
        <div class="project_section">
            <div class="project_form"> Edit The Project Here </div>
                <div class="project_container">
                    <div class="project_name">
                        <label for="name"> Name: </label>
                        <input type="text" id="project_name" name="project_name" value="<?php echo htmlspecialchars($name); ?>">
                    </div>

                    <div class="description">
                        <label for="description"> Description: </label>
                        <textarea type="text" id="description" name="description" rows="4" cols="38" placeholder="description" required> <?php echo htmlspecialchars($description); ?> </textarea>
                    </div>

                    <div class="status"> 
                        <label for="status"> Status: </label>
                        <select id="status" name="status" required>
                            <option value=""> Select Status </option>
                            <option value="Start" <?php if ($status == 'Start') echo 'selected'; ?>> Start </option>
                            <option value="In Progress" <?php if ($status == 'In Progress') echo 'selected'; ?>> In Progress </option>
                            <option value="Done" <?php if ($status == 'Done') echo 'selected'; ?>> Done </option>
                        </select>
                    </div>
                    
                    <div class="for_file">
                        <div class="about_file">
                            <!-- <label for="files">Select file</label> -->
                            <input type="file" name="fileToUpload" value="<?php echo '../../uploads/projects/'. $user_id. "/". $file_name; ?>">
                        </div>
                    </div>

                    <div class="edit_button">
                        <button type="submit"> Confirm Changes </button>
                        <input type="reset" value="Reset">
                    </div>
                    <div class="back_button">
                        <button onclick="location.href='../projects/projects.php'" type="button"> Back </button>
                    </div>
                </div>

                
    </form>

    
</body>
</html>
