<?php
include 'connect.php';
	$subpage = 1;
	$populars = $db->query("
		SELECT
		    *
		FROM
		    posts
		WHERE published = 1
		ORDER BY
		    views DESC
		LIMIT 5;
    ")->fetchAll(PDO::FETCH_ASSOC);
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

        $tagIds = $_POST['tags'];

		$insertPage = $db->prepare("
			INSERT INTO posts
				(title, coverImage, thumbImage,
				section, slug, postText, created_at, updated_at, published, views)
			VALUES
				(:title, :coverImage, :thumbImage, 
				:section, :slug, :postText, NOW(), NOW(), 0, 0)
		");

        $data = [
			'title' 		=>	$title,
			'coverImage'	=>	$coverImage,
			'thumbImage'	=>	$thumbImage,

			'section'		=>	(int)$section,
			'slug'			=>	$slug,
			'postText'		=>	$postText
        ];
        $insertPage->execute($data);
        $postId = $db->lastInsertId();

        $existingTags = [];
        $tagsToCreate = [];
        foreach($tagIds as $tagId) {
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
            $tagStatement = $db->prepare("INSERT INTO post_tag (post_id, tag_id) VALUES (?, ?)");
            $tagStatement->execute([$postId, (int)$tagId]);
        }

		header('Location: overview.php');
	}
    include '../assets/parts/head.php';
?>
<script type="text/javascript">
	function loadImage(url) {
		document.getElementById('head_pic').setAttribute('src', url);
	}
</script>
<script src="https://www.danielljungqvist.se/ckeditor/ckeditor.js"></script>
<i class="fal fa-bars" id="toggleAdmin"></i>
<div id="createPage">
    <main>
        <form action="">
            <div class="wrapper">
                <div id="adminTools">
                    <div id="pictures">
                        <label for="">Bild på postsidan</label>
                        <input type="text" name="coverImage" id="coverImage" />
                        <input type="button" value="Use" id="coverImageButton" onclick="loadImage(document.getElementById('coverImage').value)" />
                                    
                        <label for="thumbImage">Bild på förstasidan</label>
                        <input type="text" name="thumbImage">

                        <label for="slug">URL</label>
                        <input type="text" name="slug" placeholder="slug">
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
                                <div id="alltags" style="position:absolute; background-color: #333; color: #fff; min-width: 100px; border-radius: 4px; padding: 2px 4px; display: none; z-index: 10;">
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
                        <button type="submit" formaction="" formmethod="POST">Uppdatera post</button>
                    </div>
                </div>
            </div>
            <div class="wrapper">
                <div id="head">
                    <textarea name="title" id="editTitle"></textarea>
                </div>
                <div id="coverImg">
                    <img src="<?= $post['coverImage']; ?>" id="head_pic" />
                </div>
            </div>
            <div id="postText_wrapper">
                <textarea name="postText" id="text" cols="30" rows="10"></textarea>
                <script>
                    CKEDITOR.replace('text');
                </script>
            </div>
        </div>
    </form>
                                </main>
<?php include '../assets/parts/bottom.php'; ?>