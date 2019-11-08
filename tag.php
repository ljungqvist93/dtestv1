<?= include 'assets/parts/head.php';

    if(!isset($_GET['id'])) {
        header('Location: /');
        die('Nope.');
    }

    $tagId = $_GET['id'];

    $tag = $db->prepare("
        SELECT *
        FROM tags
        WHERE id = :id 
        LIMIT 1
    ");
    $tag->execute(['id' => $tagId]);
    $tag = $tag->fetch(PDO::FETCH_ASSOC);

    if(!$tag) {
        header('Location: /');
        die('Nope.');
    }

    $posts = $db->prepare("
        SELECT posts.*
        FROM posts
        LEFT JOIN post_tag ON posts.id = post_tag.post_id
        WHERE published = 1
        AND post_tag.tag_id = :tag_id
        ORDER BY created_at DESC
    ");
    $posts->execute(['tag_id' => $tagId]);
    $posts = $posts->fetchAll(PDO::FETCH_ASSOC);
    
?>
<main>
    <div class="mt40">
        <?php foreach ($posts as $post) :?>
            <?php include 'assets/parts/posts.php'; ?>
        <?php endforeach; ?>
    </div>
</main>