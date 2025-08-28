<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta HTTP-EQUIV="Pragma" content="no-cache">
    <meta HTTP-EQUIV="Expires" content="-1" >
    <link rel="stylesheet" href="/my_portfolio/css/projects.css">
    <title> Projects </title>

    <script>
    if (window.history.replaceState ) {
        window.history.replaceState(null, null, window.location.href );
    }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script> 

        $(document).ready(function(){ // jQuery: Document Ready Event //$(the selector).action() 
            

            $('#data').after('<div id="nav"></div>'); // Insert the content that is inside the ()
            var rowsShown = 6; // var is declaring a variable 
            var rowsTotal = $('#data tbody tr').length; //selects the tbody tr andd get its length
            var numPages = rowsTotal/rowsShown; //determining the number of pages by dividing the number of total to the number of shown
            for(i = 0;i < numPages;i++) {
                var pageNum = i + 1;
                $('#nav').append('<a href="javascript:void()" rel="'+i+'">'+pageNum+'</a> '); //"+n+" is a concatenation of a variable
                }
            $('#data tbody tr').hide(); //hide the extra in page 1 but still works without this when refreshing
            $('#data tbody tr').slice(0, rowsShown).show(); //show the rows that is within the range of the slice
            $('#nav a:first').addClass('active'); //add the class of active to show the current page
            $('#nav a').bind('click', function(){ //attaches an event for the element and specifies the function to run when the 'click' occurs

                $('#nav a').removeClass('active'); //removing the class of active in previous link
                $(this).addClass('active'); // adding again the class to the current active link
                var currPage = $(this).attr('rel'); // getting the value of a current page
                console.log(JSON.stringify(currPage));
                var startItem = currPage * rowsShown; // specifies the number of items that is the current page
                var endItem = startItem + rowsShown; // specifies the number of items within the total of all the pages
                $('#data tbody tr').css('opacity','0.0').hide().slice(startItem, endItem).css('display','table-row').animate({opacity:1}, 300);
            });
        });

    </script>
</head>
<body>
    <?php
        session_start();
        require_once($_SERVER['DOCUMENT_ROOT']. "/my_portfolio/php/config.php");
        include('backend_projects.php');
    
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
                            <input type="file" name="fileToUpload">
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
</body>
</html>