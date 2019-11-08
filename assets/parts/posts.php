<article class="posts">
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
            <h2><?= $post['title']; ?></h2>
        </header>
</article>