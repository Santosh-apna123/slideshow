<?php
include 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $stmt = $conn->prepare("DELETE FROM slideshow_images WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header("Location: admin_slideshow.php");
exit;
?>
