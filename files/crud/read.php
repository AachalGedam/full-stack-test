<?php
include '../db.php';

try {
    $stmt = $conn->query("SELECT * FROM your_table ORDER BY created_at DESC");
    $slides = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Slides</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
</head>
<body class="bg-light">

<div class="container py-5">
  <h2 class="mb-4">All Slides</h2>

  <a href="create.php" class="btn btn-success mb-3"> Add New Slide</a>

  <div class="table-responsive">
    <table class="table table-bordered table-hover bg-white">
      <thead class="thead-dark">
        <tr>
          <th>#</th>
          <th>Image</th>
          <th>Title</th>
          <th>Description</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($slides) > 0): ?>
          <?php foreach ($slides as $index => $slide): ?>
            <tr>
              <td><?= $index + 1 ?></td>
              <td>
                <img src="../images/<?= htmlspecialchars($slide['image']) ?>" alt="Slide Image" width="80" height="80" style="object-fit: cover; border: 1px solid #ccc;">
              </td>
              <td><?= htmlspecialchars($slide['title']) ?></td>
              <td><?= nl2br(htmlspecialchars($slide['description'])) ?></td>
              <td><?= date('Y-m-d H:i', strtotime($slide['created_at'])) ?></td>
              <td>
                <a href="update.php?id=<?= $slide['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                <a href="delete.php?id=<?= $slide['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this slide?');">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="text-center text-muted">No slides found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <a href="../assignment.php" class="btn btn-secondary">← Back to Home</a>
</div>

</body>
</html>
