<?php
session_start();
if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sale Records</title>
	<link rel="stylesheet" type="text/css" href="css/table.css">
</head>
<body>
	<h3 style="text-align: center;">Sale Records</h3>
	
	<div id="tab">
		<form method="POST" enctype="multipart/form-data">
			<center>
				<table border="1" cellspacing="0px" id="disp">
					<tr>
						<th>Product Price</th>
						<th>Sales Price</th>
						<th>Sold Quantities</th>
						<th>Profit</th>
						<th>Percentage(%)</th>
						<th>Action</th>					
					</tr>
					<?php
					include "model.php";
					$id=$_GET['id'];
					$salelist=New Product();
					echo $salelist->salesRecord($id);
					?>
				</table><br>
				
				<button class="btndisp" name="back" >Back</button>
				<button class="btndisp" name="adds" >Add Sale</button>
			
				
			</center>
		</form>
	</body>
	</html>
	<?php
	if(isset($_POST['adds']))
	{
		header('Location:saleadd.php?id='.$id);
	}
	if(isset($_POST['back']))
	{
		header('Location:productlist.php');
	}
        
}else {
    header('Location:index.php');
}
?>
	