<?php
include 'connect.php';
    $subpage = 1;

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
    
    $postId = $_GET['id'];

        $covers = $db->query("
            SELECT postimages.*, posts.*
            FROM posts
            JOIN postimages
                ON posts.id = postimages.postId
            WHERE posts.id = $postId AND cover = 1
        ")->fetchAll(PDO::FETCH_ASSOC);
    
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
    <main id="edit">
    <form action="">
        <div class="wrapper">
            <div id="adminTools">
                <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
				<div id="pictures">
					<label for="">Stor bild</label>
					<input type="text" name="coverImage" id="coverImage" value="<?php echo $post['coverImage']; ?>" />
					<input type="button" value="Use" id="coverImageButton" onclick="loadImage(document.getElementById('coverImage').value)" />
								
					<label for="thumbImage">Mobilbild</label>
					<input type="text" name="thumbImage" value="<?php echo $post['thumbImage']; ?>">

					<label for="slug">URL</label>
					<input type="text" name="slug" placeholder="slug" value="<?php echo $post['slug']; ?>">
				</div>
				<div id="selects">
					<select name="section" id="section">
                        <?php if ($post['section'] == 0): ?>
    						<option value="0">artiel</option>
    						<option value="1">guide</option>
                        <?php else: ?>
                            <option value="1">guide</option>
                            <option value="0">artikel</option>
                        <?php endif; ?>
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
                            <script>
                                let tagInput = document.getElementById('newtaginput');
                                let tagButton = document.getElementById('newtagbutton');
                                let allTags = document.getElementById('alltags');
                                let tags = document.getElementsByClassName('tagentries');
                                let selectedTags = document.getElementById('selectedtags');
                                let allTagsList = [];
                                let chosenTags = [];

                                function updateList() {
                                    let chosenIds = chosenTags.map(e => e.id);
                                    for(let i = 0; i < tags.length; i++) {
                                        if(chosenIds.indexOf(tags[i].dataset.id) === -1) {
                                            tags[i].style.display = 'block';
                                            continue;
                                        }
                                        tags[i].style.display = 'none';
                                    }
                                }

                                function createElement(id, label) {
                                    let wrapperElement = document.createElement('div');
                                    wrapperElement.id = 'tag_'+id;
                                    wrapperElement.className = 'tag';
                                    let inputElement = document.createElement('input');
                                    inputElement.type = 'hidden';
                                    inputElement.name = 'tags[]';
                                    inputElement.value = id;
                                    let labelElement = document.createElement('span');
                                    labelElement.innerHTML = label;
                                    labelElement.style.marginLeft = '4px';
                                    let deleteElement = document.createElement('i');
                                    deleteElement.className = 'fa fa-trash text-red';
                                    deleteElement.dataset.id = id;
                                    deleteElement.style.cursor = 'pointer';
                                    deleteElement.addEventListener('click', function(e) {
                                        e.target.parentNode.parentNode.removeChild(e.target.parentNode);
                                        let newArray = [];
                                        for(let j = 0; j < chosenTags.length; j++) {
                                            if(chosenTags[j].id === e.target.dataset.id) {
                                                continue;
                                            }
                                            newArray.push(chosenTags[j]);
                                        }
                                        chosenTags = newArray;
                                        updateList();
                                    });
                                    wrapperElement.appendChild(inputElement);
                                    wrapperElement.appendChild(deleteElement);
                                    wrapperElement.appendChild(labelElement);
                                    selectedTags.appendChild(wrapperElement);
                                    chosenTags.push({
                                        id,
                                        label,
                                    });
                                    updateList();
                                    tagInput.value = '';
                                }

                                {
                                    let tagsOnLoad = [
                                        <?php foreach($postTags as $tag): ?>
                                            {
                                                id: '<?= $tag['id']; ?>',
                                                label: '<?= $tag['label']; ?>',
                                            },
                                        <?php endforeach; ?>
                                    ];
                                    for(let i = 0; i < tagsOnLoad.length; i++) {
                                        createElement(tagsOnLoad[i].id, tagsOnLoad[i].label);
                                    }
                                }

                                for(let i = 0; i < tags.length; i++) {
                                    tags[i].addEventListener('mousedown', e => {
                                        let id = e.target.dataset.id;
                                        if(chosenTags.map(e => e.id).indexOf(id) !== -1) {
                                            return;
                                        }
                                        let label = e.target.dataset.label;
                                        createElement(id, label);
                                    });
                                }
                                tagInput.addEventListener('focus', e => {
                                    allTags.style.display = 'block';
                                });
                                tagInput.addEventListener('blur', e => {
                                    allTags.style.display = 'none';
                                });
                                tagInput.addEventListener('input', e => {
                                    let input = e.target.value.toLowerCase();
                                    for(let i = 0; i < tags.length; i++) {
                                        let id = tags[i].dataset.id;
                                        let chosenIds = chosenTags.map(e => e.id);
                                        if(chosenIds.indexOf(id) !== -1) {
                                            continue;
                                        }
                                        let label = tags[i].dataset.label.toLowerCase();
                                        if(label.includes(input)) {
                                            tags[i].style.display = 'block';
                                            continue;
                                        }
                                        tags[i].style.display = 'none';
                                    }
                                });
                                tagButton.addEventListener('click', e => {
                                    e.preventDefault();
                                    let label = tagInput.value;
                                    if(label === '') {
                                        return;
                                    }
                                    createElement(label, label);
                                });
                            </script>
                        </div>
                    </div>
                </div>
				<div id="buttons">
					<button type="submit" formaction="" formmethod="POST">Update post!</button>
				</div>
            </div>
        </div>
        <div id="caruselle">
            <?php foreach ($covers as $cover): ?>
                <div><img src="image/<?= $cover['imageName']; ?>" alt=""></div>
            <?php endforeach; ?>
        </div>
        <textarea name="title" id="editTitle"><?= $post['title']; ?></textarea>
        <div id="textField">
            <textarea name="postText" id="text" cols="30" rows="10"><?= $post['postText']; ?></textarea>
            <script>
                CKEDITOR.replace('text');
            </script>
        </div>
    </form>
</main>
</div>

<?= include '../assets/parts/bottom.php'; ?>
