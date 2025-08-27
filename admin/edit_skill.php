<?php
require_once 'auth_check.php';
require_once '../inc/config.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: edit_skills.php');
    exit;
}

$stmt = $conn->prepare("SELECT name, category FROM skills WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$skill = $result->fetch_assoc();

if (!$skill) {
    header('Location: edit_skills.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Skill</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'nav.php'; ?>
    <main class="content">
        <h1>Edit Skill</h1>
        <section class="card">
            <form action="actions.php" method="post">
                <input type="hidden" name="action" value="update_skill">
                <input type="hidden" name="id" value="<?= $id ?>">
                <label>Skill Name: <input type="text" name="name" value="<?= htmlspecialchars($skill['name']) ?>" required></label>
                <label>Category: <input type="text" name="category" value="<?= htmlspecialchars($skill['category']) ?>" required></label>
                <button type="submit" class="btn">Update Skill</button>
            </form>
        </section>
    </main>
</body>
</html>
