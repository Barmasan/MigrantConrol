<?php require_once("functions/connection.php"); ?>
<?php include("functions/logs.php")?>
<?php session_start();
	$manager_id = $_SESSION['manager_id'];
	add_log($manager_id, "Вышел");
	unset($_SESSION['manager_id']);
	session_destroy();
	header("location:index.php");
?>
