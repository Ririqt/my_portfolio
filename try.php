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
                    <label for="project_name"> Name: </label>
                    <input type="text" id="project_name" name="project_name" placeholder="Your Project Name" required>
                </div>

                <div class="description">
                    <label for="description"> Description: </label>
                    <textarea id="description" name="description" placeholder="Description" required></textarea>
                </div>

                <div class="status">
                    <label for="status"> Status: </label>
                    <select id="status" name="status" required>
                        <option value="">-- Select Status --</option>
                        <option value="Start">Start</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Done">Done</option>
                    </select>
                </div>
            </div>

            <div class="register_button">
                <button type="submit"> Create </button>
            </div>
        </div>
    </form>

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
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $project_name = test_input($_POST["project_name"]);
            $description = test_input($_POST["description"]);
            $status = test_input($_POST["status"]);

            $sql = "INSERT INTO projects (name, description, status) VALUES ('$project_name', '$description', '$status')";
            if ($conn->query($sql) !== TRUE) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        $sql = "SELECT id, name, description, status FROM projects";
        $result = mysqli_query($conn, $sql);

        echo '<div id="table">';
        echo '<table border="1">';
        echo '<tr>
                <th> Project Name </th>
                <th> Description </th>
                <th> Status </th> 
                <th> Delete </th>
              </tr>';

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row["name"]) . '</td>';
                echo '<td>' . htmlspecialchars($row["description"]) . '</td>';
                echo '<td>' . htmlspecialchars($row["status"]) . '</td>';
                echo '<td><a href="projects.php?delete=' . $row["id"] . '" onclick="return confirm(\'Are you sure you want to delete this project?\')">Delete</a></td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="4">No projects found.</td></tr>';
        }

        echo '</table>';
        echo '</div>';

        $conn->close();

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
    ?>
</body>
</html>
