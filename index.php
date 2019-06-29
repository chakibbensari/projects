<!DOCTYPE html>
<html lang="en">

  <head>
    <title>Test Class</title>
  </head>

  <body>
		<?php
		  error_reporting(E_ERROR | E_WARNING);	
		  include_once('TestClass.php');
		  $obj = new TestClass();
		  $obj->host = 'localhost';
		  $obj->username = 'root';
		  $obj->password = '';
		  $obj->table = 'firsttest';
		  $obj->connect();

		  if ( $_POST )
			  if($_POST['action'] == 'creation'){
				$obj->add($_POST);
			  } else if ($_POST['action'] == 'modification'){
				  $obj->updateData($_POST);
			  }
		  if ($_GET['admin'] == 1 && $_GET['title'] && $_GET['body']){
			echo $obj->display_edit_page($_GET['title'], $_GET['body']);
		  } else if ($_GET['title']){
			echo $obj->remove($_GET['title']);
		  } else if($_GET){
			echo ( $_GET['admin'] == 1 ) ? $obj->display_admin() : $obj->display_public();
		  } else {
			echo $obj->display_public();
		  }

		?>
  </body>

</html>