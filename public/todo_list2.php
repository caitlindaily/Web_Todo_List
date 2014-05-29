<?php

$todo_items = [];
function open() 
{
    $filename = 'list_items.txt';
    $filesize = filesize($filename);
    $content = fopen($filename, 'r');
    $fileString = trim(fread($content, $filesize));
    $file = explode("\n", $fileString);
    fclose($content);
	return $file;
}
$listItems = open();
foreach ($listItems as $list_item) {
	array_push($todo_items, $list_item);
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
			foreach ($todo_items as $todo_item) {
				echo "<li>$todo_item</li>\n";
			}
			?>
		</ul>
	<h2>Add an item:</h2>	
<form method="POST">
	<label for="add"></label>
	<input id="add" name="add" type="text" placeholder="Enter new item">
	<button type="submit">Submit</button>
</form>		
</body>
</html>