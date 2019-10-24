<?= include 'assets/parts/head.php';

    if (!empty($_GET['slug'])) {
        $slug = $_GET['slug'];

        $post = $db->prepare("
            SELECT *
            FROM posts
            WHERE slug = :slug 
            LIMIT 1
        ");
        $post->execute(['slug' => $slug]);
        $post = $post->fetch(PDO::FETCH_ASSOC);

        $id = $post['id'];

        $covers = $db->query("
            SELECT postimages.*
            FROM posts
            JOIN postimages
                ON posts.id = postimages.postId
            WHERE posts.id = $id
        ")->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo 'nope';
    }
?>
<main id="postPage">
    <picture id="caruselle" >
        <?php foreach ($covers as $cover): ?>
            <div><img src="engine/image/<?= $cover['imageName']; ?>" alt=""></div>
        <?php endforeach; ?>
    </picture>
        <div id="dateViews_tags">
            <div id="dateViews">
                <ul class="inline">
                    <li id="date">
                        <i class="fad fa-calendar-alt"></i>
                        <?= human_readable_time_diff($post['created_at']); ?> in 
                        <strong>
                            <?php if ($post['section'] === '1'): ?>
                                <a href="">Guide</a>
                            <?php else: ?>
                                <a href="">Article</a>
                            <?php endif; ?>
                        </strong>
                    </li>
                    <li id="views">
                        <i class="fad fa-eye"></i>
                        <?= $post['views'] ?>
                    </li>
                </u>
            </div>
            <div id="tags">
                <?php $tags = get_post_tags($post['id'], $db); ?>
                <ul class="tags inline">
                    <?php foreach($tags as $tag): ?>
                        <li><a href="tag.php?id=<?= $tag['id'] ?>"><?= $tag['label']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <header>
        <h1><?= $post['title']; ?></h1>
    </header>
    <div id="postText">
        <?= $post['postText'] ?>
    </div>
</main>

<?php include 'assets/parts/bottom.php'; ?>