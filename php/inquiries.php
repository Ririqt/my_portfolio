<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Inquiries </title>
    <link rel="stylesheet" href="../css/inquiries.css">

</head>
<body>
    <?php
    session_start();
    require_once "config.php";

    $user_id = $_SESSION['id'];
    $rows_per_page = 2;

    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;

    $offset = ($page - 1) * $rows_per_page;

    $total_query = "SELECT COUNT(*) as total FROM contacts ";
    $total_result = mysqli_query($conn, $total_query);
    $total = mysqli_fetch_assoc($total_result)['total'];

    $total_pages = ceil($total / $rows_per_page); 

    $query = "SELECT * FROM contacts LIMIT $rows_per_page OFFSET $offset";
    $result = mysqli_query($conn, $query);
    ?>
    <div class="header">
        <div class="header_container"> 
            <div class="nav_bar">
                <h1 class="header_title"> <a href="/my_portfolio/php/dashboard.php"> My Portfolio </a> </h1>
                <nav class="header_text">
                    <ul>
                        <li> <a href="../admin/about/about.php"> About </a> </li>
                        <li id="skills"> <a href="../admin/skills/skills.php"> Skills </a> </li>
                        <li id="projects"> <a href="../admin/projects/projects.php"> Projects </a> </li>
                        <li id="logs"> <a href="../php/logs.php"> Logs </a> </li>
                        <li id="inquiries"> <a href="../php/inquiries.php"> Inquiries </a> </li>
                        <li> <a href="/my_portfolio/php/logout.php" onclick="return confirm('Are you sure you want to Log Out?')"> Log Out </a> </li>
                    </ul>
                
                </nav> 
                <div class="user"> User: <?php echo $_SESSION['name'];  ?> </div> 
            </div>
        </div>
    </div>

    <div class="table_container"> 
        <div id="table" class="activity_log_data_table">
                <table class="styled_table" id="data">
                    <thead>
                        <tr class="message_row">
                            <th> No. </th>
                            <th> Name </th>
                            <th> Subject </th>
                            <th> Messages </th>
                        </tr>
                    </thead>
        <?php
        // $sql = "SELECT * FROM contacts";
        // $result = mysqli_query($conn,$sql);

        // echo json_encode($result);
        // exit;
        if(mysqli_num_rows($result)>0) {
            $i = 1;
                    while($row = mysqli_fetch_assoc($result)) {
        ?>

        <tbody>
            <tr>
                <td> <?php echo $i; $i++; ?> </td>  
                <td> <?php echo $row['name']; ?> </td>  
                <td> <?php echo $row['subject']; ?> </td> 
                <td> <?php echo $row['message']; ?> </td> 
            <?php
                }
            } else {
                echo '<tr> <td colspan="3" id="add">' , "Nothing in the Inquiries" , "</td>" , "</tr>";
            }
        ?>
            </tr>
        </tbody>
                </table>
        </div>
    </div>

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