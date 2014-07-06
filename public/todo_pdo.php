<?php

$dbc = new PDO('mysql:host=127.0.0.1;dbname=todo', 'caitlin', 'delinda');

$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = 'CREATE TABLE todo (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	item VARCHAR(30) NOT NULL,
	PRIMARY KEY (id)
	)';

$dbc->exec($query);


?>