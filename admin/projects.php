<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta HTTP-EQUIV="Pragma" content="no-cache">
    <meta HTTP-EQUIV="Expires" content="-1" >
    <link rel="stylesheet" href="../css/projects.css">
    <title> Projects </title>

    <script>
    if (window.history.replaceState ) {
        window.history.replaceState(null, null, window.location.href );
    }
    </script>
</head>
<body>
    <?php
        session_start();
        require_once "../php/config.php";

        if (isset($_GET['delete'])) {
            $delete_id = intval($_GET['delete']);
            $sql = "DELETE FROM projects WHERE id = $delete_id";
            $conn->query($sql);
            header("Location: projects.php");
            exit;
        }
        $project_name = $description = $status = "";
        $projectNameErr = $descriptionErr = "";

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $project_name = test_input($_POST["project_name"]);
            $description = test_input($_POST['description']);
            $status = test_input($_POST["status"]);

            $sql = "INSERT INTO projects (name, description, status) VALUES ('$project_name', '$description', '$status')";
            if ($conn->query($sql) === TRUE) {
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        $sql = "SELECT id, name, description, status FROM projects";
        $result = mysqli_query($conn,$sql);

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
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
            <div class="project_form"> Create a Project Here </div>
                <div class="project_container">
                    <div class="project_name">
                        <label for="name"> Name: </label>
                        <input type="text" id="project_name" name="project_name" placeholder="Your Project Name" required>
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

                <div class="create_button">
                    <button type="submit"> Create </button>
                </div>

                <div id="table" class="table">
                <table border="2">
                    <tr>
                        <th> Project Name </th>
                        <th> Description </th>
                        <th> Status </th> 
                        <th> Delete </th>
                        <th> Edit </th>
                    </tr>
                        <?php
                            if($result->num_rows>0){
                                while($row = $result->fetch_assoc()) {
                        ?>
                            <tr> 
                                <td> <?php echo $row['name']; ?> </td>
                                <td> <?php echo $row['description']; ?> </td>
                                <td> <?php echo $row["status"]; ?> </td>
                                <td> <a href="projects.php?delete=<?php echo $row["id"];?>" onclick="return confirm('Delete?')"> Delete </a> </td>
                                <td> <a href="projects/edit.php?edit=<?php echo $row["id"];?>" onclick="return confirm('Proceed to Edit?')"> Edit </a> </td>
                            </tr>
                        
                        <?php
                                }
                            } else {
                                
                        ?>
                            <tr> <td colspan="4"> Add something here. </td> </tr>
                        <?php
                        }
                        ?>
                </table>
                </div>
            </div>
        </div>

    </form>
    
    <?php
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_destroy();
            $conn->close();
            // exit;
        }
    ?>
</body>
</html>