<?php include 'assets/parts/head.php';

    $tagId = $_GET['type'];
    
    $posts = $db->query("
        SELECT * FROM posts
        WHERE section = $tagId
        AND published = 1
        ORDER BY created_at DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
?>
<main>
    <div class="mt40">
        <?php foreach ($posts as $post) :?>
            <?php include 'assets/parts/posts.php'; ?>
        <?php endforeach; ?>
    </div>
</main>