 <?php
 require_once "config.php";
    $per_page = 2;
    $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;

    $start = ($page - 1) * $per_page;
    $sql = "SELECT * FROM users ORDER BY id LIMIT $start, $per_page";
    $result = $conn->query($sql);
    ?>
    <table class="user-table">
        <tr><th>ID</th><th>First Name</th><th> Email</th></tr>
        <?php while ($row = $result->fetch_array()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <!-- <td><?php //echo $row['LastName']; ?></td> -->
            </tr>
        <?php } ?>
    </table>