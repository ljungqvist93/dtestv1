<?php
include 'connect.php';
    $subpage = 1;
    $postId = $_GET['id'];

	$post = $db->prepare("
        SELECT *
        FROM posts
        WHERE id = :id
	");
	$post->execute(['id' => $_GET['id']]);
    $post = $post->fetch(PDO::FETCH_ASSOC);

    $postTags = $db->query("
        SELECT *
        FROM tags
        LEFT JOIN
            post_tag
            ON
            tags.id = post_tag.tag_id
        WHERE post_tag.post_id = ".$post['id']."
    ")->fetchAll(PDO::FETCH_ASSOC);

    $postTagIds = [];
    foreach($postTags as $tag) {
        $postTagIds[] = $tag['id'];
    }

    $tags = $db->query("
        SELECT *
        FROM tags
    ")->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($_POST)) {

		$title 			= 	$_POST['title'];
		$coverImage 	= 	$_POST['coverImage'];
		$thumbImage 	= 	$_POST['thumbImage'];

		$section 		=	$_POST['section'];
		$slug 			=	$_POST['slug'];
		$postText 		=	$_POST['postText'];
		$id 			= 	$_POST['id'];

		$updatePost = $db->prepare("

			UPDATE posts
			SET
				title 			=		:title,
				coverImage 		=		:coverImage,
				thumbImage 		= 		:thumbImage,

				section 		= 		:section,
				slug 			= 		:slug,
				postText 		= 		:postText
			WHERE id = :id

		");

		$updatePost->execute([
			'id'				=>		$id,

			'title' 		=>	$title,
			'coverImage'	=>	$coverImage,
			'thumbImage'	=>	$thumbImage,

			'section'		=>	$section,
			'slug'			=>	$slug,
			'postText'		=>	$postText
        ]);

        $postTags = $db->prepare("
            DELETE
            FROM post_tag
            WHERE post_id = :id
        ");
        $postTags->execute(['id' => $id]);
        $newNewTagIds = $_POST['tags'];

        $existingTags = [];
        $tagsToCreate = [];
        foreach($newNewTagIds as $tagId) {
                $tag = $db->prepare("SELECT * FROM tags WHERE id = ?");
                $tag->execute([(int)$tagId]);
            $tag = $tag->fetch(PDO::FETCH_ASSOC);
            if($tag) {
                $existingTags[] = $tagId;
                continue;
            }
            $tagsToCreate[] = $tagId;
        }

        $createdTags = [];
        foreach($tagsToCreate as $tagString) {
            $tag = $db->prepare("INSERT INTO tags (label) VALUES (?)");
            $tag->execute([$tagString]);
            $tagId = $db->lastInsertId();
            $createdTags[] = $tagId;
        }

        $newTagIds = array_merge($existingTags, $createdTags);

        foreach($newTagIds as $tagId) {
            $postTags = $db->prepare("
                INSERT INTO post_tag
                (post_id, tag_id)
                VALUES
                (:post_id, :tag_id)
            ");
            $postTags->execute(['post_id' => $id, 'tag_id' => $tagId]);
        }
        header("Refresh:0");
        
    }
    
    include '../assets/parts/head.php';    
?>
<script type="text/javascript">
	function loadImage(url) {
		document.getElementById('head_pic').setAttribute('src', url);
	}
</script>
<div id="frameholder">
<iframe id="imageIframe" src="images.php?id=<?= $postId; ?>&cover=0"></iframe>
</div>
<script src="https://www.danielljungqvist.se/ckeditor/ckeditor.js"></script>
<div id="postAdmin" style="margin-top:150px;">
    <i class="fal fa-bars" id="toggleAdmin"></i>
    <i class="fal fa-image" id="imageToggler"></i>
    <main>
    <form action="">
        <div class="wrapper">
            <div id="adminTools">
                <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
				<div id="pictures">
					<label for="">Bild på postsidan</label>
					<input type="text" name="coverImage" id="coverImage" value="<?php echo $post['coverImage']; ?>" />
					<input type="button" value="Use" id="coverImageButton" onclick="loadImage(document.getElementById('coverImage').value)" />
								
					<label for="thumbImage">Bild på förstasidan</label>
					<input type="text" name="thumbImage" value="<?php echo $post['thumbImage']; ?>">

					<label for="slug">URL</label>
					<input type="text" name="slug" placeholder="slug" value="<?php echo $post['slug']; ?>">
				</div>
				<div id="selects">
					<select name="section" id="section">
						<option value="0">Guide</option>
						<option value="1">Artikel</option>
					</select>
                </div>
                <div id="tags" style="margin: 10px 0px;">
                    <div id="newtag">
                        <div id="selectedtags" style="margin-bottom:10px;"></div>
                        <div style="position:relative;">
                            <input type="text" id="newtaginput" placeholder="Select or create a tag" autocomplete="off" />
                            <button id="newtagbutton">Add new tag</button>
                            <div id="alltags">
                                <ul id="alltagslist" style="list-style:none; margin: 0; padding: 0;">
                                    <?php foreach($tags as $tag): ?>
                                        <li class="tagentries" data-id="<?= $tag['id']; ?>" data-label="<?= $tag['label']; ?>"><?= $tag['label']; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php include '../assets/js/tags.php'; ?>
                        </div>
                    </div>
                </div>
                <input name="title" id="editTitle" value="<?= $post['title']; ?>">
				<div id="buttons">
					<button type="submit" formaction="" formmethod="POST">Update post!</button>
				</div>
            </div>
        </div>
    </form>
</main>
</div>

<?= include '../assets/parts/bottom.php'; ?>
