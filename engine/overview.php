<?php
    $subpage = 1;
    include 'connect.php';
    include '../assets/parts/head.php';
    

    $saves = $db->query("
        SELECT * FROM posts ORDER BY created_at DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

?>
<main>
<div class="wrapper">
    <ul id="overviewList">
        <?php foreach ($saves as $post): ?>
            <li>
                <ul class="options">
                    <li><a href="edit.php?id=<?= $post['id']; ?>">edit</a></li>
                    <li><a href="del.php?id=<?= $post['id']; ?>">del</a></li>
                    <li>
                        <?php if ($post['published'] == 1): ?>
                            <a href="publish.php?id=<?php echo $post['id']; ?>">
                                published
                            </a>
                        <?php else: ?>
                            <a href="publish.php?id=<?php echo $post['id']; ?>">
                                not published
                            </a>
                        <?php endif; ?>
                    </li>
                </ul>
                <a href="images.php?id=<?= $post['id']; ?>"><div class="covers">covers</div></a>
                <a href="../post.php?slug=<?= $post['slug']; ?>&cover=0">
                    <?= $post['title']; ?>
                </a> 
            </li>
        <?php endforeach; ?>
    </ul>
</div>
</main>
<?php include '../assets/parts/bottom.php'; ?>