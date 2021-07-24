<?php
session_start();
if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	
</head>
<body>
	<?php
	include 'model.php';
	//creating an object for Class Product  & Calling its method selectProduct() to select existing product for unlinling its image
	$select=New Product();
	$selected=$select->selectProduct($_GET["duid"]);
	if (mysqli_num_rows($selected) > 0) 
	{
		while($row = mysqli_fetch_assoc($selected))
		{
			unlink($row['Image']); //deleting an image of delete product
		}
	}
	//creating an object for Class Product  & Calling its method deleteProduct() to delete existing product
	$delete=New Product();
	$delete->deleteProduct($_GET["duid"]);
	header('Location:productlist.php');
	?>
</body>
</html>
<?php
} else {
    header('Location:index.php');
}
?>