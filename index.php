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
    <div class="mt40">
        <div class="ad top">
            <!-- Test -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:864px;height:222px"
                 data-ad-client="ca-pub-7566748514057450"
                 data-ad-slot="6165240872"></ins>
            <script>
                 (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
        <?php foreach ($posts as $post): ?>
            <?php include 'assets/parts/posts.php'; ?>
        <?php endforeach; ?>
    </div>
</main>

<?php include 'assets/parts/bottom.php'; ?>