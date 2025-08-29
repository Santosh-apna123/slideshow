<?php
include 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $maxFileSize = 2 * 1024 * 1024;

        $fileTmp  = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name']; 
        $fileType = mime_content_type($fileTmp);
        $fileSize = $_FILES['image']['size'];

        $fileInfo = pathinfo($fileName);
        $fileExt  = strtolower($fileInfo['extension'] ?? '');

        if (!in_array($fileType, $allowedTypes)) {
            $message = '<div class="alert alert-danger">Invalid image type. Only JPG, PNG, GIF, and WebP allowed.</div>';
        } elseif (!in_array($fileExt, $allowedExtensions)) {
            $message = '<div class="alert alert-danger">Invalid file extension. Only JPG, PNG, GIF, and WebP allowed.</div>';
        } elseif ($fileSize > $maxFileSize) {
            $message = '<div class="alert alert-danger">File too large. Max allowed size is 2MB.</div>';
        } else {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $destination = $uploadDir . basename($fileName);

            if (file_exists($destination)) {
                unlink($destination);
            }

            if (move_uploaded_file($fileTmp, $destination)) {
                $imageUrl = $conn->real_escape_string($destination);

                $stmt = $conn->prepare("DELETE FROM slideshow_images WHERE image_url = ?");
                $stmt->bind_param("s", $imageUrl);
                $stmt->execute();
                $stmt->close();

                $stmt = $conn->prepare("INSERT INTO slideshow_images (image_url) VALUES (?)");
                $stmt->bind_param("s", $imageUrl);
                $stmt->execute();
                $stmt->close();

                echo "<script>
                    alert('Image uploaded successfully ');
                    window.location.href = 'index.php';
                </script>";
                exit;
            } else {
                $message = '<div class="alert alert-danger">Failed to move uploaded file.</div>';
            }
        }
    } else {
        $message = '<div class="alert alert-warning">No image selected or upload error.</div>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload Image</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header"><h2 class="mt-4 text-center">Upload Image</h2></div>
            <div class="row">
                <div class="card-body">
                    <div class="col-lg-12">
                        <?php if (!empty($message)) echo $message; ?>
                        <form action="upload.php" method="post" enctype="multipart/form-data">
                            <div class="mt-3 mb-3">
                                <input type="file" name="image" accept="image/*" required class="form-control">
                            </div>
                            <div class="mt-3">
                                <center><button type="submit" class="btn btn-primary">Upload Image</button></center>
                            </div>
                        </form>
                    </div>
                 </div>   
            </div>     
        </div>        
    </div>
</body>
</html>