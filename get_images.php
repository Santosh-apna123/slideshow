<?php
include 'config.php';
header('Content-Type: application/json');

$query = "SELECT image_url FROM slideshow_images ORDER BY uploaded_at DESC";
$result = $conn->query($query);

$response = ['images' => []];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response['images'][] = $row;
    }
}

echo json_encode($response);
$conn->close();
?>
