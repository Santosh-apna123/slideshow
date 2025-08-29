<?php
include 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM slideshow_images WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$image = $result->fetch_assoc();

if (!$image) {
    die("Image not found");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $targetDir = "uploads/";
    $fileName = time() . "_" . basename($_FILES["image"]["name"]);
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        if (file_exists($image['image_url'])) {
            unlink($image['image_url']);
        }

        $stmt = $conn->prepare("UPDATE slideshow_images SET image_url=? WHERE id=?");
        $stmt->bind_param("si", $targetFile, $id);
        $stmt->execute();

        header("Location: admin_slideshow.php");
        exit;
    } else {
        $error = "Error uploading file.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Image</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

  <h2 class="mb-4">Edit Slideshow Image</h2>

  <?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form action="" method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Current Image</label><br>
      <img src="<?= $image['image_url'] ?>" width="200">
    </div>
    <div class="mb-3">
      <label class="form-label">Upload New Image</label>
      <input type="file" name="image" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Update</button>
    <a href="admin_slideshow.php" class="btn btn-secondary">Cancel</a>
  </form>

</body>
</html>
