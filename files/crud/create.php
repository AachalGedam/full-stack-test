<?php
include '../db.php';

$success = $error = '';

// Form submission logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image = $_FILES['image']['name'] ?? '';

    if ($title && $description && $image) {
        $targetDir = '../images/';
        $targetFile = $targetDir . basename($image);

        // Optional: Validate file type and size
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($fileType, $allowed)) {
            $error = "Only JPG, JPEG, PNG, or WEBP files are allowed.";
        } elseif (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            // Insert into DB
            try {
                $stmt = $conn->prepare("INSERT INTO your_table (title, description, image) VALUES (:title, :description, :image)");
                $stmt->execute([
                    ':title' => $title,
                    ':description' => $description,
                    ':image' => basename($image)
                ]);
                $success = "Slide added successfully!";
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
        } else {
            $error = "Failed to upload image.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create New Slide</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
</head>
<body class="bg-light">

<div class="container py-5">
  <h2 class="mb-4">Create New Slide</h2>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form action="" method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow-sm rounded">
    <div class="form-group">
      <label for="title">Title <span class="text-danger">*</span></label>
      <input type="text" name="title" id="title" class="form-control" required />
    </div>

    <div class="form-group">
      <label for="description">Description <span class="text-danger">*</span></label>
      <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
    </div>

    <div class="form-group">
      <label for="image">Upload Image <span class="text-danger">*</span></label>
      <input type="file" name="image" id="image" class="form-control-file" accept="image/*" required />
    </div>

    <button type="submit" class="btn btn-primary">Create Slide</button>
    <a href="../index.php" class="btn btn-secondary ml-2">Back to Home</a>
  </form>
</div>

</body>
</html>

