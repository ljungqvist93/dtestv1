<?php

	include '../engine/connect.php';

	if(isset($_GET['id'])){
		$post = $db->prepare("
			SELECT * FROM posts WHERE id = :id
		");
		$post->execute(['id' => $_GET['id']]);
		$post = $post->fetch(PDO::FETCH_ASSOC);

		if($post['published'] == 1){
			$publish = $db->prepare("
				UPDATE posts
				SET published = 0
				WHERE id = :id
			");
			$publish->execute(['id' => $_GET['id']]);
		} else {
			$publish = $db->prepare("
				UPDATE posts
				SET published = 1
				WHERE id = :id
			");
			$publish->execute(['id' => $_GET['id']]);
		}

		header('Location: overview.php');
	}
?>