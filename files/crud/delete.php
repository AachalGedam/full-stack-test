<?php
include '../db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        // First, fetch the slide to get the image filename
        $stmt = $conn->prepare("SELECT image FROM your_table WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $slide = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($slide) {
            // Delete image from the folder if it exists
            $imagePath = '../assets/images/' . $slide['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete file
            }

            // Delete the record from database
            $deleteStmt = $conn->prepare("DELETE FROM your_table WHERE id = :id");
            $deleteStmt->execute([':id' => $id]);
        }
    } catch (PDOException $e) {
        die("Error deleting slide: " . $e->getMessage());
    }
}

// Redirect back to read.php
header("Location: read.php");
exit;
?>
