<?php 
    $postPage = 0;
    require 'assets/parts/head.php';

    $posts = $db->query("
		SELECT *
        FROM posts
        WHERE published = 1
        ORDER BY created_at DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
?>
<main>
    <div class="mt40">
        <?php foreach ($posts as $post): ?>
            <?php include 'assets/parts/posts.php'; ?>
        <?php endforeach; ?>
    </div>
</main>

<?php include 'assets/parts/bottom.php'; ?>