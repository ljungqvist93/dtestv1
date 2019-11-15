<?php
	include 'connect.php';

	$thisId = $_GET['id'];
	$cover = $_GET['cover'];

    $fetchImages = $db->query("
        SELECT * FROM postimages WHERE cover = $cover AND postimages.postId = $thisId ORDER BY created DESC
	")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Upload Images</title>
	<style type="text/css">
		img{
			max-width:600px;
		}
	</style>
</head>
<body>
	<form action="upload.php" method="POST" enctype="multipart/form-data">
		<input type="file" name="image">
		<input type="submit" value="Lägg till">
		<select name="cover" id="">
			<option value="0">postimage</option>
			<option value="1">coverimage</option>
		</select>
        <input type="hidden" name="id" value="<?= $thisId; ?>">
	</form>
	<?php if ($cover == 0): ?>
		<a href="images.php?id=<?= $thisId; ?>&cover=1">cover</a>
	<?php else: ?>
		<a href="images.php?id=<?= $thisId; ?>&cover=0">postimages</a>
	<?php endif; ?>

	<br><br>
	<?php foreach ($fetchImages as $imageurl): ?>
	<a href="delete.php?imageName=<?php echo $imageurl['imageName'] ?>&id=<?= $thisId ?>">Ta bort</a>
	<img src="image/<?php echo $imageurl['imageName']; ?>"><br>
	<?php endforeach; ?>
</body>
</html>
