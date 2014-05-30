<?php

$todo_items = [];

//==================================================
function open_list($filename = 'list_items.txt') 
{	
    $filesize = filesize($filename);
    if ($filesize > 0) 
    {
	    $content = fopen($filename, 'r');
	    $fileString = trim(fread($content, $filesize));
	    $file = explode("\n", $fileString);
	    fclose($content);
		return $file;
	} else {
		return [];
	}	
}
//===================================================
function save_list($list, $filename = 'list_items.txt')
{   
    $handle = fopen($filename, 'w');
    $string = implode("\n", $list);
    fwrite($handle, $string . PHP_EOL);
    fclose($handle); 
}
//====================================================	
$loaded_file = open_list();

//Check if file was uploaded and no errors
if (count($_FILES) > 0 && $_FILES['file1']['error'] == 0) {
	if ($_FILES['file1']['type'] == 'text/plain') {
	//Where are the files being uploaded
		$upload_directory = '/vagrant/sites/todo.dev/public/uploads/';
	//Grab file being uploaded by basename
		$filename = basename($_FILES['file1']['name']);
	//Create new saved filename by using original name + upload directory name
		$saved_file = $upload_directory . $filename;
	//Move file from it's temp location to uploads directory
		move_uploaded_file($_FILES['file1']['tmp_name'], $saved_file);	
		
		$uploaded_file = open_list($saved_file);
		$loaded_file = array_merge($loaded_file, $uploaded_file);
	}else {
		echo "Please upload plain text file only.";
	}
}
//Test Uploading////////////////
if (isset($saved_file)) {
    // If we did, show a link to the uploaded file
    echo "<p>You can download your file <a href='/uploads/{$filename}'>here</a>.</p>";
}


?>

<!DOCTYPE html>
<html>
<head>
   <?php
	var_dump($_GET);
	var_dump($_POST);
	?>	
	<title>TODO List</title>
</head>
<body>
	<h1>TODO List</h1>
		<ul>
			<?php
			//Saving the item
			if (!empty($_POST)) {
				$loaded_file[] = "{$_POST['item']}";
				$todo_items [] = $loaded_file;
			}
			//Delete an item
			if (isset($_GET['removeIndex'])) {
				$removeIndex = $_GET['removeIndex'];
 				unset($loaded_file[$removeIndex]);
 			}
			//Listing the item
			foreach ($loaded_file as $index => $item) {
				echo "<li>$item <a href=\"todo_list2.php?removeIndex={$index}\">Remove</a></li>";
			}
			save_list($loaded_file);

			?>
		</ul>
	<h2>Add an item:</h2>
	<form method="POST" action="/todo_list2.php">
		<label for="item"></label>
		<input id="item" name="item" type="text" placeholder="Enter new item">
		<button type="submit">Submit</button>
	</form>
	<form method="POST" enctype="multipart/form-data">
		<p>	
			<label for="file1">Upload File: </label>
			<input type="file" id="file1" name="file1">
			<input type="submit" value="upload">
		</p>		
	</form>	
</body>
</html>