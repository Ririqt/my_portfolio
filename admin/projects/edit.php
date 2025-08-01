<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

    if (isset($_GET['edit'])) {
        $edit_id = $_GET['edit'];
        $update = true;
        $sql = "SELECT id,name,description,status FROM projects WHERE id = $edit_id";
        $result = mysqli_query($conn,$sql);
        echo $_GET['edit'];
        echo $edit_id;
        
        if (count($result)==1) {
            $get = mysqli_fetch_array($result);
            $name = $get['name'];
            $description = $get['address'];
        }

        $conn->query($sql);
    }
?>

    <div class="header">
        <div class="header_container"> 
            <h1 class="header_title"> <a href="#index"> My Portfolio </a> </h1>
                <nav class="header_text">
                    <ul>
                    <li> <a href="#about"> About </a> </li>
                    <li id="projects"> <a href="../admin/projects.php"> Projects </a> </li>
                    <li> <a href="../php/logout.php"> Log Out </a> </li>
                    </ul>
                </nav>
        </div>
    </div>

    <form method="POST" class="project_form"> 
        <div class="project_section">
            <div class="project_form"> Edit a Project Here </div>
                <div class="project_container">
                    <div class="project_name">
                        <label for="name"> Name: </label>
                        <input type="text" id="project_name" name="project_name" value="<?php echo $row['name']; ?>">
                    </div>

                    <div class="description">
                        <label for="description"> Description: </label>
                        <textarea type="text" id="description" name="description" rows="4" cols="38" placeholder="description" required> </textarea>
                    </div>

                    <div class="status"> <br>
                        <label for="status"> Status: </label>
                        <select id="status" name="status" required>
                            <option value=""> Select Status </option>
                            <option value="Start"> Start </option>
                            <option value="In Progress"> In Progress </option>
                            <option value="Done"> Done </option>
                        </select>
                    </div>
                </div>

                <div class="edit_button">
                    <button type="submit"> Edit </button>
                </div>
    </form>
</body>
</html>
