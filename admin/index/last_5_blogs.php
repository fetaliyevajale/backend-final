<?php
include('../config/config.php'); 

// Son 5 blog
$stmt = $conn->query("SELECT * FROM blogs ORDER BY id DESC LIMIT 5");
$last_5_blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="blog-container">
    <?php foreach ($last_5_blogs as $blog): ?>
        <div class="blog-card">
            <h3><?= htmlspecialchars($blog['title']) ?></h3>
            <p><?= htmlspecialchars($blog['description']) ?></p>
            <p>Kateqoriya: <?= htmlspecialchars($blog['category_id']) ?></p>
        </div>
    <?php endforeach; ?>
</div>
