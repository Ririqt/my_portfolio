<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../skills/skills.css">
    <title> Skills </title>

    <script>
    if (window.history.replaceState ) {
        window.history.replaceState(null, null, window.location.href );
    }
    </script>
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

        if (isset($_GET['delete'])) {
            $delete_id = intval($_GET['delete']);
            $sql = "DELETE FROM skills WHERE id = $delete_id"; // important for delete // 
            $conn->query($sql);
            header("location: skills.php");
            exit;
        }

        $skill_name = $type = $rate = "";
        $skill_nameErr = $typeErr = $rateErr = "";

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $skill_name = test_input($_POST["skill_name"]);
            $type = test_input($_POST["type"]);
            $rate = test_input($_POST["rate"]);


            $sql = "INSERT INTO skills (name, type, rate) VALUES ('$skill_name', '$type', '$rate')"; 
            if ($conn->query($sql) === TRUE) {
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        $sql = "SELECT id, name, type, rate FROM skills";
        $result = mysqli_query($conn,$sql);

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    } 

    $conn->close();
        // if($result->num_rows>0){
        //         while($row = $result->fetch_assoc()) {
        //             echo $row['name'];
        //             echo $row['type'];
        //         }
        //     } else {
        //         echo "Add Something";
        //     }
    ?> 
    <div class="header">
        <div class="header_container"> 
            <h1 class="header_title"> <a href="#index"> My Portfolio </a> </h1>
                <nav class="header_text">
                    <ul>
                    <li> <a href="#about"> About </a> </li>
                    <li> <a href="../admin/skills/skills.php"> Skills </a> </li>
                    <li id="projects"> <a href="../admin/projects.php"> Projects </a> </li>
                    <li> <a href="../php/logout.php"> Log Out </a> </li>
                    </ul>
                </nav>
        </div>
    </div>

    <form method="POST" class="skill_form"> 
        <div class="project_section">
            <div class="project_form"> Input Your Skill Here </div>
                <div class="project_container">
                    <div class="project_name">
                        <label for="name"> Skill: </label>
                        <input type="text" id="skill_name" name="skill_name" placeholder="Your Skill" required>
                    </div>

                    <div class="status"> <br>
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
        </div> 
        
        <div class="rate_table_section"> 
            <div class="rate_table_container">  
                <div class="rate_table"> Rate your Skill </div>
                    <label for="very_good"> 5 - Very Good <br />
                        <input type="radio" id="very_good" name="rate" value="5 - Very Good">
                    </label> 
                    
                    <label for="good"> 4 - Good<br /> 
                        <input type="radio" id="good" name="rate" value="4 - Good"> 
                    </label>  
                    
                    <label for="satisfactory"> 3 - Satisfactory <br />
                        <input type="radio" id="satisfactory" name="rate" value="3 - Satisfactory">
                    </label>

                    <label for="bad"> 2 - Bad <br />
                        <input type="radio" id="bad" name="rate" value="2 - Bad">
                    </label>

                    <label for="very_bad"> 1 - Very Bad <br />
                        <input type="radio" id="very_bad" name="rate" value="1 - Very Bad">
                    </label>

                    <input type="submit" value="Submit">
                    </div> 
                   
            </div>
         </div>
 
        <div id="table" class="table">
                    <table>
                        <tr>
                            <th scope="col"> Skill </th>
                            <th scope="col" id="col1"> Type </th>
                            <th scope="col" id="col2"> Rate </th>
                            <th scope="col" id="col3"> Delete </th>
                            <th scope="col" id="col3"> Edit </th>
                        </tr>

                        <?php
                            if($result->num_rows>0){
                                while($row = $result->fetch_assoc()) {
                        ?>
                            <tr> 
                                <td> <?php echo $row['name']; ?> </td>
                                <td> <?php echo $row['type']; ?> </td>
                                <td> <?php echo $row["rate"]; ?> </td>
                                <td> <a href="skills.php?delete=<?php echo $row["id"];?>" onclick="return confirm('Delete?')"> Delete </a> </td>
                                <td> <a href="projects/edit.php?edit=<?php echo $row["id"];?>"> Edit </a> </td> 
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
        
                    
</form>
</body>
</html>