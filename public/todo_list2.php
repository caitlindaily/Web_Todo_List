<?php

//Establish Database connection
$dbc = new PDO('mysql:host=127.0.0.1;dbname=todo', 'caitlin', 'delinda');
$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


try {
	//--Adding Todo Items--//
	if (!empty($_POST['item'])) {
		if (empty($_POST['item']) || (strlen($_POST['item']) > 240)) {
				throw new Exception("Post cannot be empty or longer than 240 characters.");
			}	
		$query = "INSERT INTO todo (item) VALUES (:item)";
		$stmt = $dbc->prepare($query);

		$stmt->bindValue(':item', $_POST['item'], PDO::PARAM_STR);
		$stmt->execute();
	} 
	//--Delete an item--//
	if (isset($_POST['remove'])) {
		$stmt = $dbc->prepare('DELETE FROM todo WHERE id = :id');
		$stmt->bindValue(':id', $_POST['remove'], PDO::PARAM_INT);
		$stmt->execute();
		header('Location: /todo_list2.php');
		exit(0);
	}
} catch (Exception $e) {
	$msg = $e->getMessage() . PHP_EOL;
}	

//====================================================
//--Determining Max Items Per Page--//
$limit = 5;
$count = $dbc->query('SELECT count(*) FROM todo')->fetchColumn();
$numPages = ceil($count / $limit);

//--Pagination Buttons--//
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$nextPage = $page + 1;
$prevPage = $page - 1;
$offset = ($page - 1) * $limit;
	
//--Applying Item Limit & Offset Number--//
$stmt = $dbc->prepare('SELECT * FROM todo LIMIT :limit OFFSET :offset');
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

//--Retrieve List Data--//
$getList = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
	<title>TODO List</title>
	<!--Bootstrap and Stylesheet-->
	<link rel="stylesheet" href="todo_list_stylesheet.css">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
  	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
</head>
<body>
	<!--Exception Error Message-->
	<? if(isset($msg)) : ?>
		<h2><?= $msg; ?></h2>
	<? endif; ?>

	<!---Display of List-->
	<h1>TODO List</h1>
		<ul>
			<? foreach ($getList as $item) : ?>
			<li><?=  $item['item']; ?><button class="btn btn- btn-xs btn-remove" data-todo="<?= $item['id']; ?>">Remove</button></li>
			<? endforeach; ?>
		</ul>

	<!--Hidden Form to Delete Item-->
	<p>
	<form method="POST" id="remove-form" action="todo_list2.php">
	    <input id="remove-id" type="hidden" name="remove">
	</form>
	</p>

	<!-- Pagination -->
	<ul class="pager">
		<?if($page == 1 && $numPages > 1): ?>
  			<li class="active"><a href="/todo_list2.php?page=<?= $nextPage; ?>">Next</a></li>
  		<? endif; ?>	
  		<?if ($page > 1 && $numPages > $page) : ?>
  			<li class="active"><a href="/todo_list2.php?page=<?= $nextPage; ?>">Next</a></li>
  			<li class="active"><a href="/todo_list2.php?page=<?= $prevPage; ?>">Previous</a></li>
  		<? endif; ?>	
  		<? if ($page == $numPages && $numPages > 1) : ?>
  			<li class="active"><a href="/todo_list2.php?page=<?= $prevPage; ?>">Previous</a></li>
		<?endif; ?>
  	</ul>
	</div>	

	<!--Form: Adding Item-->
	<h2>Add an item:</h2>
	<p>
	<form method="POST" action="/todo_list2.php">
		<label for="item"></label>
		<input id="item" name="item" type="text" placeholder="Enter new item">
		<button type="submit">Submit</button>
	</form>

	<!--JQuery-->
	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	<script>

	$('.btn-remove').click(function () {
	    var todoId = $(this).data('todo');
	    if (confirm('Are you sure you want to remove item ' + todoId + '?')) {
	        $('#remove-id').val(todoId);
	        $('#remove-form').submit();
	    }
	});
	</script>
</body>
</html>
