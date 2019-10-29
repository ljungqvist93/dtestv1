<?php
    $postPage = 1;

    include 'assets/parts/head.php';

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
            WHERE posts.id = $id AND cover = 1
        ")->fetchAll(PDO::FETCH_ASSOC);

        $count = $db->prepare("
            UPDATE posts SET views = views +1 WHERE id = $id
        ");
        $count->execute();
    } else {
        echo 'nope';
    }
?>
<title><?= $post['title']; ?> - Daniel Ljungqvist</title>
<meta property="og:type" content="article" />
<meta property="og:title" content="<?= $post['title']; ?>" />
<meta property="og:image" content="<?= $post['thumbImage']; ?>" />
<meta name="author" content="danielljungqvist.se" />

<meta name="twitter:title" content="<?= $post['title']; ?>" />
<meta name="twitter:description" content="" />
<meta name="twitter:site" content="@danielljungqvist" />
<meta name="twitter:creator" content="@ljungqvist93" />
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="<?= $post['thumbImage']; ?>">
<?php include 'assets/parts/menu.php'; ?>
</head>
<body>
<main id="postPage">
    <div id="caruselle">
        <?php foreach ($covers as $cover): ?>
            <div><img src="engine/image/<?= $cover['imageName']; ?>" alt=""></div>
        <?php endforeach; ?>
    </div>
    <div id="mScale">
        <img src="<?php echo $post['thumbImage']; ?>" alt="">
    </div>
    <div id="dateViews_tags">
        <div id="dateViews">
            <ul class="inline">
                <li id="date">
                    <i class="fad fa-calendar-alt"></i>
                     in 
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
        <nav id="share" class="inline">
            <ul>
                <li>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=https://www.danielljungqvist.se/post.php?slug=<?= $post['slug']; ?>"  target="_blank" class="share-popup">
                        <i class="fab fa-facebook"></i>
                    </a>
                </li>
                <li>
                    <a href="http://www.twitter.com/intent/tweet?url=https://www.danielljungqvist.se/post.php?slug=<?= $post['slug']; ?>&via=ljungqvist93&text=<?= $post['title']; ?>" target="_blank" class="share-popup">
                        <i class="fab fa-twitter"></i>
                    </a>
                </li>
                <li>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=https://www.danielljungqvist.se/post.php?slug=airdroid&title=The%Last%of%Us%Part%II%has%been%Delayed%until%May%2020">
                        <i class="fab fa-linkedin"></i>
                    </a>
                </li>
                <li>
                    <i class="fab fa-get-pocket"></i>
                </li>
            </ul>
        </nav>
        <?= $post['postText'] ?>
    </div>
</main>

<?php include 'assets/parts/bottom.php'; ?>