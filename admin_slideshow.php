<?php
include 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $targetDir = "uploads/";
    $fileName = time() . "_" . basename($_FILES["image"]["name"]);
    $targetFile = $targetDir . $fileName;
    header("Location: admin_slideshow.php");
    exit;
}
$result = $conn->query("SELECT * FROM slideshow_images ORDER BY uploaded_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin - Slideshow</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

  <h2 class="mb-4 text-center">Slideshow Admin Panel</h2>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Preview</th>
        <th>Image URL</th>
        <th>Uploaded At</th>
        <th>Edit</th>
        <th>Delete</th>

      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><img src="<?= $row['image_url'] ?>" width="100"></td>
          <td><?= $row['image_url'] ?></td>
          <td><?= $row['uploaded_at'] ?></td>
          <td><a href="Edit.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Edit</a></td>
          <td><a href="delete.php?delete=<?= $row['id'] ?>" 
          class="btn btn-danger btn-sm">Delete</a>
        </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

</body>
</html>
