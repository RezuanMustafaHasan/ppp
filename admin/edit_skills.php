<?php
require_once 'auth_check.php';
require_once '../inc/config.php'; // For database connection

// Fetch all skills to display
$skills = [];
$sql = "SELECT id, name, category FROM skills ORDER BY category, name";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $skills[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Skills</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'nav.php'; ?>
    <main class="content">
        <h1>Edit Skills</h1>

        <!-- Add Skill Form -->
        <section class="card">
            <h2>Add New Skill</h2>
            <form action="actions.php" method="post">
                <input type="hidden" name="action" value="add_skill">
                <label>Skill Name: <input type="text" name="name" required></label>
                <label>Category: <input type="text" name="category" required></label>
                <button type="submit" class="btn">Add Skill</button>
            </form>
        </section>

        <!-- Existing Skills Table -->
        <section class="card">
            <h2>Existing Skills</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($skills)): ?>
                        <tr>
                            <td colspan="3">No skills found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($skills as $skill): ?>
                            <tr>
                                <td><?= htmlspecialchars($skill['name']) ?></td>
                                <td><?= htmlspecialchars($skill['category']) ?></td>
                                <td>
                                    <a href="edit_skill.php?id=<?= $skill['id'] ?>" class="btn">Edit</a>
                                    <a href="actions.php?action=delete_skill&id=<?= $skill['id'] ?>" class="btn danger" onclick="return confirm('Are you sure you want to delete this skill?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
