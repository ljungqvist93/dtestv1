<?= include 'assets/parts/head.php';

    $tagId = $_GET['type'];
    
    $posts = $db->query("
        SELECT * FROM posts
        WHERE section = $tagId
        AND published = 1
        ORDER BY created_at DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
?>
<main>
    <?php foreach ($posts as $post) :?>
        <article>
            <a href="post.php?slug=<?= $post['slug']; ?>" class="postLink"></a>
                <header>
                    <h1><?= $post['title']; ?></h1>
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
                                    434
                                </li>
                            </ul>
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
                </header>
                <picture>
                    <img src="<?= $post['coverImage']; ?>" alt="">
                </picture>
        </article>
    <?php endforeach; ?>
</main>