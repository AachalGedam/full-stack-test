<?php
include '../db.php';

$id = $_GET['id'] ?? null;
$slide = null;
$success = $error = '';

// Step 1: Fetch existing slide data
if ($id) {
    try {
        $stmt = $conn->prepare("SELECT * FROM your_table WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $slide = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$slide) {
            $error = "Slide not found.";
        }
    } catch (PDOException $e) {
        $error = "Error fetching slide: " . $e->getMessage();
    }
} else {
    $error = "Missing slide ID.";
}

// Step 2: Handle update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $slide) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image = $slide['image']; // default to existing image

    if ($title && $description) {
        // Handle image upload if a new one was selected
        if (!empty($_FILES['image']['name'])) {
            $newImage = $_FILES['image']['name'];
            $targetDir = '../images/';
            $targetFile = $targetDir . basename($newImage);
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($fileType, $allowed)) {
                $error = "Only JPG, JPEG, PNG, or WEBP files are allowed.";
            } elseif (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $image = basename($newImage);
            } else {
                $error = "Failed to upload image.";
            }
        }

        if (!$error) {
            // Update the DB
            try {
                $stmt = $conn->prepare("UPDATE your_table SET title = :title, description = :description, image = :image WHERE id = :id");
                $stmt->execute([
                    ':title' => $title,
                    ':description' => $description,
                    ':image' => $image,
                    ':id' => $id
                ]);
                $success = "Slide updated successfully!";
                // Refresh data
                $slide['title'] = $title;
                $slide['description'] = $description;
                $slide['image'] = $image;
            } catch (PDOException $e) {
                $error = "Error updating slide: " . $e->getMessage();
            }
        }
    } else {
        $error = "Title and description are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Slide</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
</head>
<body class="bg-light">

<div class="container py-5">
  <h2 class="mb-4">Update Slide</h2>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <?php if ($slide): ?>
  <form action="" method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow-sm rounded">
    <div class="form-group">
      <label for="title">Title <span class="text-danger">*</span></label>
      <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($slide['title']) ?>" required />
    </div>

    <div class="form-group">
      <label for="description">Description <span class="text-danger">*</span></label>
      <textarea name="description" id="description" class="form-control" rows="4" required><?= htmlspecialchars($slide['description']) ?></textarea>
    </div>

    <div class="form-group">
      <label>Current Image</label><br>
      <img src="../images/<?= htmlspecialchars($slide['image']) ?>" alt="Image" style="width: 150px; height: auto; border: 1px solid #ccc;">
    </div>

    <div class="form-group">
      <label for="image">Change Image (optional)</label>
      <input type="file" name="image" id="image" class="form-control-file" accept="image/*" />
    </div>

    <button type="submit" class="btn btn-primary">Update Slide</button>
    <a href="../index.php" class="btn btn-secondary ml-2">Back to Home</a>
  </form>
  <?php endif; ?>
</div>

</body>
</html>
