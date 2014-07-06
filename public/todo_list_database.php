<?php

//Establish Database connection
$dbc = new PDO('mysql:host=127.0.0.1;dbname=todo', 'caitlin', 'delinda');
$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


class InvalidInputException extends Exception{};

require_once('classes/filestore.php');

$todo = new Filestore('testlist.txt');

$todo_items = $todo->read();
//====================================================	
//Check if file was uploaded and no errors
if (count($_FILES) > 0 && $_FILES['file1']['error'] == 0) {
	if ($_FILES['file1']['type'] == 'text/plain') {
		$upload_directory = '/vagrant/sites/todo.dev/public/uploads/';
		$filename = basename($_FILES['file1']['name']);
		$saved_file = $upload_directory . $filename;
		move_uploaded_file($_FILES['file1']['tmp_name'], $saved_file);	
		
		$uploaded_file = $todo->read($saved_file);
		$todo_items = array_merge($todo_items, $uploaded_file);
		$todo->write($todo_items);
	}else {
		echo "Please upload plain text file only.";
	}
}
//Adding the item
try {
	if (!empty($_POST)) {
		if (empty($_POST['item']) || (strlen($_POST['item']) > 240)) {
				throw new InvalidInputException("Post cannot be empty or longer than 240 characters.");
			}	
		$todo_items[] = $_POST;
		$query = "INSERT INTO todo (item) VALUES (:item)";
		$stmt = $dbc->prepare($query);
		var_dump($_POST);

	} 
} catch (InvalidInputException $e) {
	$msg = $e->getMessage() . PHP_EOL;
}	

//Delete an item
if (isset($_GET['removeIndex'])) {
	$removeIndex = $_GET['removeIndex'];
	unset($todo_items[$removeIndex]);
	$todo->write($todo_items);
	header('Location: /todo_list2.php');
	exit(0);
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>TODO List</title>
	<link rel="stylesheet" href="todo_list_stylesheet.css">
</head>
<body>
	<? if(isset($msg)) : ?>
		<h2><?= $msg; ?></h2>
	<? endif; ?>

	<h1>TODO List</h1>
		<ul>
			<? foreach ($todo_items as $index => $item) : ?>
			<?= "<li>$item <a href=\"todo_list2.php?removeIndex={$index}\">Remove</a></li>"; ?>
			<? endforeach; ?>
		</ul>
	<h2>Add an item:</h2>
	<p>
	<form method="POST" action="/todo_list2.php">
		<label for="item"></label>
		<input id="item" name="item" type="text" placeholder="Enter new item">
		<button type="submit">Submit</button>
	</form>
	</p>
	<h2>Upload a File</h2>
	<form method="POST" enctype="multipart/form-data">
		<p>	
			<label for="file1">Upload File: </label>
			<input type="file" id="file1" name="file1">
		</p>
		<p>
			<input type="submit" value="upload">
		</p>		
	</form>	

<script>








</script>
</body>
</html>