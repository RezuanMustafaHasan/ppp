<?php
require_once 'auth_check.php';
require_once '../inc/config.php';

$action = $_REQUEST['action'] ?? '';

switch ($action) {
    case 'create':
        handle_create($conn);
        break;
    case 'update':
        handle_update($conn);
        break;
    case 'delete':
        handle_delete($conn);
        break;
    default:
        // No action specified, redirect to dashboard
        header("Location: index.php");
        exit;
}

function handle_create($conn) {
    // Basic validation
    if (empty($_POST['title']) || empty($_POST['content']) || empty($_POST['publish_date'])) {
        die("Error: Please fill all required fields.");
    }

    $sql = "INSERT INTO blogs (title, content, publish_date, image_url) VALUES (?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $_POST['title'], $_POST['content'], $_POST['publish_date'], $_POST['image_url']);

        if (!$stmt->execute()) {
            die("Error creating post: " . $stmt->error);
        }
        $stmt->close();
    } else {
        die("Error preparing statement: " . $conn->error);
    }

    header("Location: index.php");
    exit;
}

function handle_update($conn) {
    // Basic validation
    if (empty($_POST['id']) || empty($_POST['title']) || empty($_POST['content']) || empty($_POST['publish_date'])) {
        die("Error: Please fill all required fields.");
    }

    $sql = "UPDATE blogs SET title = ?, content = ?, publish_date = ?, image_url = ? WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssi", $_POST['title'], $_POST['content'], $_POST['publish_date'], $_POST['image_url'], $_POST['id']);

        if (!$stmt->execute()) {
            die("Error updating post: " . $stmt->error);
        }
        $stmt->close();
    } else {
        die("Error preparing statement: " . $conn->error);
    }

    header("Location: index.php");
    exit;
}

function handle_delete($conn) {
    if (empty($_GET['id'])) {
        die("Error: No ID specified for deletion.");
    }

    $id = intval($_GET['id']);
    $sql = "DELETE FROM blogs WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            die("Error deleting post: " . $stmt->error);
        }
        $stmt->close();
    } else {
        die("Error preparing statement: " . $conn->error);
    }

    header("Location: index.php");
    exit;
}

$conn->close();
?>
