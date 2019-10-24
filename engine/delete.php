<?php

require 'conn.php';

if (isset($_GET['imageName'])) {
	$target = $_GET['imageName'];
	$postId = $_GET['id'];

	$unlink = unlink('image/' . $target);

	if ($unlink) {
		$deleteDB = $db->prepare("DELETE FROM postimages WHERE imageName = :imageName");
		$deleteDB->execute(['imageName' => $target]);

		header('Location: images.php?cover=0&id=' . $postId);

	}

}