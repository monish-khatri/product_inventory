<?php
session_start();
if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script src="jquery-3.4.1.js"></script>
</head>
<body>
	<?php
	include 'model.php';
	$sid=$_GET['sid'];
	$id=$_GET["id"];

	//creating an object for Class Product  & Calling its method deleteProduct() to delete existing Sale Record
	$delete=New Product();
	$delete->deleteSale($sid,$id);
	header('Location:saleslist.php?id='.$id);
	?>
</body>
</html>
<?php
} else {
    header('Location:index.php');
}
?>