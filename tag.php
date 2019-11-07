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
    <?php foreach ($posts as $post) :?>
        <article>
            <a href="post.php?slug=<?= $post['slug']; ?>" class="postLink"></a>
                <picture>
                    <source srcset="<?= $post['thumbImage']; ?>" media="(max-width:600px)">
                    <source srcset="<?= $post['coverImage']; ?>" media="(min-width:601px)">
                    <img src="<?= $post['thumbImage']; ?>" alt="">
                </picture>
                <header>
                    <div id="dateViews_tags">
                        <div id="dateViews">
                            <ul class="inline">
                                <li id="date">
                                    <i class="fad fa-calendar-alt"></i>
                                    <?= human_readable_time_diff($post['created_at']); ?> in 
                                    <strong>
                                        <?php if ($post['section'] === '1'): ?>
                                            <a href="section.php?type=0">Guide</a>
                                        <?php else: ?>
                                            <a href="section.php?type=1">Article</a>
                                        <?php endif; ?>
                                    </strong>
                                </li>
                                <li id="views">
                                    <i class="fad fa-eye"></i>
                                    <?= $post['views']; ?>
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
                    <h1><?= $post['title']; ?></h1>
                </header>
        </article>
    <?php endforeach; ?>
</main>