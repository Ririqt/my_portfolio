














































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
    
    $id = $name = $description = $status = "";

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_GET['edit'])) {
        $edit_id = intval($_GET['edit']);
        $sql = "SELECT * FROM projects WHERE id = $edit_id";
        $result = mysqli_query($conn,$sql);
        
        if ($result && mysqli_num_rows($result) == 1) {
            $get = mysqli_fetch_assoc($result);
            $name = $get['name'];
            $description = $get['description'];
            $status = $get['status'];
        } else {
            echo "Project not found.";
            exit;
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['edit'])) {
        $id = intval($_GET['edit']);
        $name = trim($_POST['project_name']);
        $description = trim($_POST['description']);
        $status = trim($_POST['status']);

        $sql = "UPDATE projects SET name='$name', description='$description', status='$status' WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            header("Location: ../admin/projects.php");
            exit;
        } else {
            echo "Update failed: " . $conn->error;
        }
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
                        <input type="text" id="project_name" name="project_name" value="<?php echo htmlspecialchars($name); ?>">
                    </div>

                    <div class="description">
                        <label for="description"> Description: </label>
                        <textarea type="text" id="description" name="description" rows="4" cols="38" placeholder="description" required> <?php echo htmlspecialchars($description); ?> </textarea>
                    </div>

                    <div class="status"> <br>
                        <label for="status"> Status: </label>
                        <select id="status" name="status" required>
                            <option value=""> Select Status </option>
                            <option value="Start" <?php if ($status == 'Start') echo 'selected'; ?>> Start </option>
                            <option value="In Progress" <?php if ($status == 'In Progress') echo 'selected'; ?>> In Progress </option>
                            <option value="Done" <?php if ($status == 'Done') echo 'selected'; ?>> Done </option>
                        </select>
                    </div>
                </div>

                <div class="edit_button">
                    <button type="submit"> Submit Changes </button>
                </div>
    </form>
</body>
</html>
