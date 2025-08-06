<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/my_portfolio/css/edit_skills.css"> 
    <title> Edit Your Skills </title>
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
        $sql = "SELECT * FROM skills WHERE id = $edit_id"; //Note: select all // 
        $result = mysqli_query($conn,$sql);
        
        if (mysqli_num_rows($result) == 1) {
            $get = mysqli_fetch_assoc($result); // Note: getting the result into an array //
            $name = $get['name'];
            $type = $get['type'];
            $rate = $get['rate'];
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['edit'])) {
        $edit_id = intval($_GET['edit']);
        $name = trim($_POST['skill_name']);
        $type = trim($_POST['type']);
        $rate = trim($_POST['rate']);

        $sql = "UPDATE skills SET name='$name', type='$type', rate='$rate' WHERE id=$edit_id"; //Note: important for update // 
         if ($conn->query($sql) === TRUE) {
            header("Location: ../skills/skills.php");
            exit;
        } else {
            echo "Update failed: " . $conn->error;
        }
    }
?>
    <div class="header">
        <div class="header_container"> 
            <h1 class="header_title"> <a href="/my_portfolio/php/dashboard.php"> My Portfolio </a> </h1>
                <nav class="header_text">
                    <ul>
                    <li> <a href="../about/about.php"> About </a> </li>
                    <li id="skills"> <a href="../skills/skills.php"> Skills </a> </li>
                    <li id="projects"> <a href="../projects.php"> Projects </a> </li>
                    <li> <a href="/my_portfolio/php/logout.php"> Log Out </a> </li>
                    </ul>
                </nav>
        </div>
    </div>

    <form method="POST" class="skill_form"> 
        <div class="skill_section">
            <div class="skill_form"> Edit your Skill Here </div>
                <div class="skill_container">
                    <div class="skill_name">
                        <label for="name"> Skill: </label>
                        <input type="text" id="skill_name" name="skill_name" value="<?php echo htmlspecialchars($name); ?>">
                    </div>
                    
                     <div class="type"> <br>
                        <label for="type"> Type: </label>
                        <select id="type" name="type" required>
                            <option value=""> Select Type </option>
                            <option value="Soft" <?php if ($type == 'Soft') echo 'selected'; ?>> Soft skills </option>
                            <option value="Hard" <?php if ($type == 'Hard') echo 'selected'; ?>> Hard skills </option>
                            <option value="Transferable" <?php if ($type == 'Transferable') echo 'selected'; ?>> Transferable skills </option>
                            <option value="Personal" <?php if ($type == 'Personal') echo 'selected'; ?>> Personal skills </option>
                            <option value="Knowledge-based" <?php if ($type == 'Knowledge-based') echo 'selected'; ?>> Knowledge-based skills </option>
                        </select>
                    </div>
                </div> 

                <div class="rate_table_section"> 
                    <div class="rate_table_container">  
                        <div class="rate_table"> Rate your Skill </div>
                            <label for="rate"> 5 - Very Good <br />
                                <input type="radio" id="very_good" name="rate" value="5 - Very Good" <?php echo ($rate == "5 - Very Good") ? "checked" : ""; ?>>
                            </label> 
                            
                            <label for="rate"> 4 - Good<br /> 
                                <input type="radio" id="good" name="rate" value="4 - Good" <?php echo ($rate == "4 - Good") ? "checked" : ""; ?>> 
                            </label>  
                            
                            <label for="rate"> 3 - Satisfactory <br />
                                <input type="radio" id="satisfactory" name="rate" value="3 - Satisfactory" <?php echo ($rate == "3 - Satisfactory") ? "checked" : ""; ?>>
                            </label>

                            <label for="rate"> 2 - Bad <br />
                                <input type="radio" id="bad" name="rate" value="2 - Bad" <?php echo ($rate == "2 - Bad") ? "checked" : ""; ?>>
                            </label>

                            <label for="rate"> 1 - Very Bad <br />
                                <input type="radio" id="very_bad" name="rate" value="1 - Very Bad" <?php echo ($rate == "1 - Very Bad") ? "checked" : ""; ?>>
                            </label>
                    </div> 
                </div>
        

                <div class="edit_button">
                    <button type="submit"> Confirm Changes </button>
                </div>

    </form>

        <div class="back_button">
            <button onclick="location.href='../skills/skills.php'" type="button"> Go back </button>
        </div>
    </div>
</body>
</html>
