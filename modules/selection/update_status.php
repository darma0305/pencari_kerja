<?php
require_once '../../config/db.php';
check_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $app_id = (int)$_POST['app_id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $query = "UPDATE applications SET status = '$status' WHERE id = $app_id";
    
    if (mysqli_query($conn, $query)) {
        header('Location: index.php?msg=updated');
        exit();
    }
}
header('Location: index.php');
exit();
?>
