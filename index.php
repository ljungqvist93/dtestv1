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
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Test -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-7566748514057450"
     data-ad-slot="6165240872"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
    <div class="mt40">
        <?php foreach ($posts as $post): ?>
            <?php include 'assets/parts/posts.php'; ?>
        <?php endforeach; ?>
    </div>
</main>

<?php include 'assets/parts/bottom.php'; ?>