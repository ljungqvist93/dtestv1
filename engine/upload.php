<?php

require 'conn.php';

if (isset($_FILES['image'])) {

	$postId = $_POST['id'];
	$cover = $_POST['cover'];

	$fileNameNew = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 25);
	
	$image = $_FILES['image'];

	$imageName = $image['name'];
	$image_tmp = $image['tmp_name'];

	$file_ext = explode('.', $imageName);
	$file_ext = strtolower(end($file_ext));

	$newName = $fileNameNew . '.' . $file_ext;
	$fileDest = 'image/' . $newName;

	$add = ['imageName' => $newName];

	$addDB = $db->prepare("INSERT INTO postimages (imageName, postId, cover, created) VALUES (:imageName, $postId, $cover, NOW())");
	$addDB->execute(['imageName' => $newName]);

	if (move_uploaded_file($image_tmp, $fileDest)) {
		header('Location: images.php?cover=0&id=' . $postId);
	} else {
		echo 'Nope.';
	}
}