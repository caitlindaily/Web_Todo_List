<?php

$todo_items = [];
// //add the
// $newTodo = $_POST[''];
// $todo_items = $newTodo;
//==================================================
function open($filename = 'list_items.txt') 
{
    $filesize = filesize($filename);
    $content = fopen($filename, 'r');
    $fileString = trim(fread($content, $filesize));
    $file = explode("\n", $fileString);
    fclose($content);
	return $file;
}
//===================================================
function save($list, $filename = 'list_items.txt')
{   
    $handle = fopen($filename, 'w');
    $string = implode("\n", $list);
    fwrite($handle, $string . PHP_EOL);
    fclose($handle); 
}
//====================================================	
 $listItems = open();

// foreach ($listItems as $list_item) {
// 	array_push($todo_items, $list_item);
// }
// foreach($_POST as $value) {
// 	array_push($todo_items, $value);
// }
?>

<!DOCTYPE html>
<html>
<head>
// <?php
	var_dump($_GET);
	var_dump($_POST);
?>	
	<title>TODO List</title>
</head>
<body>

	<form method="POST">
		<label for="item"></label>
		<input id="item" name="item" type="text" placeholder="Enter new item">
		<button type="submit">Submit</button>
		
	</form>

	<h1>TODO List</h1>

		<ul>
			<?php
			
			if (!empty($_POST)) {
				$listItems[] = "{$_POST['item']}";
				save($listItems);
			}

			foreach ($listItems as $item) {
				echo "<li>$item</li>";
			}
			

			?>

		</ul>
	<h2>Add an item:</h2>	
		
</body>
</html>