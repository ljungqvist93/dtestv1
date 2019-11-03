<?php
    $subpage = 1;
    include 'connect.php';
    include '../assets/parts/head.php';
    

    $saves = $db->query("
        SELECT * FROM posts WHERE published = 0 ORDER BY created_at DESC
    ")->fetchAll(PDO::FETCH_ASSOC);


    $published = $db->query("
        SELECT * FROM posts WHERE published = 1 ORDER BY created_at DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

?>
<main id="overviewPage">
<div class="wrapper">
    <?php foreach ($saves as $save): ?>
        <article>
            <div class="postCover">
                <a href="edit.php?id=<?= $save['id']; ?>">
                    <img src="<?= $save['thumbImage']; ?>" alt="">
                </a>
            </div>
            <div class="postDesc">
                <h2><a href="../post.php?slug=<?= $save['slug']; ?>"><?= $save['title']; ?></a></h2>
                
                <?php if ($save['published'] == 1): ?>
                    <a href="publish.php?id=<?= $save['id']; ?>">
                        Published
                    </a>
                <?php else: ?>
                    <a href="publish.php?id=<?= $save['id']; ?>">
                        Not published
                    </a>
                <?php endif; ?>
                <a href="">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </div>
        </article>
    <?php endforeach; ?>
<br><br><br><br><br><br><br><br>
    <?php foreach ($published as $save): ?>
        <article>
            <div class="postCover">
                <a href="edit.php?id=<?= $save['id']; ?>">
                    <img src="<?= $save['thumbImage']; ?>" alt="">
                </a>
            </div>
            <div class="postDesc">
                <h2><a href="../post.php?slug=<?= $save['slug']; ?>"><?= $save['title']; ?></a></h2>
                
                <?php if ($save['published'] == 1): ?>
                    <a href="publish.php?id=<?= $save['id']; ?>">
                        Published
                    </a>
                <?php else: ?>
                    <a href="publish.php?id=<?= $save['id']; ?>">
                        Not published
                    </a>
                <?php endif; ?>
                <a href="">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </div>
        </article>
    <?php endforeach; ?>
</div>
</main>
<?php include '../assets/parts/bottom.php'; ?>