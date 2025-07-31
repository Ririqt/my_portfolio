<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/projects.css">
    <title> Projects </title>
</head>
<body>
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
                        <textarea type="text" id="description" name="description" placeholder="description" required> </textarea>
                    </div>

                    <div class="status"> Status: <br>
                        <label for="done">  Start: </label>
                        <input type="radio" id="done" name="done" value="done">
                        <label for="started"> In Progress: </label>
                        <input type="radio" id="started" name="started" value= "done">
                        <label for="started">  Done: </label>
                        <input type="radio" id="started" name="started" value= "done">
                    </div>
                </div>

                <div class="register_button">
                    <button type="submit"> Create </button>
                </div>
            </div>
        </div>

        <div id="table">
            <table>
                <tr>
                    <th> Project Name </th>
                    <th> Description </th>
                    <th> Status </th> 
                    <th> Delete </th>
                </tr>
                <tr>
                    <td><?php ?></td>
                </tr>
            </table>
        
        </div>
    </form>
    <?php

        session_start();
        require_once "../php/config.php";

        $project_name = $description = $status = "";
        $projectNameErr = $descriptionErr = "";

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            
            $project_name = test_input($_POST["project_name"]);
            $description = test_input($_POST['description']);
            //$status = test_input($_POST['status']);
            $sql = "INSERT INTO projects (name, description, status) VALUES ('$project_name', '$description', '$status')";
            if ($conn->query($sql) === TRUE) {
                // echo "New Project Record created successfully <br>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;

            }
            $sql = "SELECT id, name, description FROM projects";
            $result = mysqli_query($conn,$sql);

            if($result->num_rows>0){
                while($row = $result->fetch_assoc()) {
                    echo "Test: " . $row["id"]. " " . $row["name"]. " " . $row["description"]. "<br>";
                }
            } else {
            echo "0 results";
            }
            $conn->close();
        }

        echo "Name: {$project_name} <br>";
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    } 
    ?>
</body>
</html>