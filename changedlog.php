<?php 
include "model.php";
session_start();
if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
?>
<!DOCTYPE html>
<html>
<head>
	<title>Changed Log</title>
	<link rel="stylesheet" type="text/css" href="css/table.css">
</head>
<body>
	<h3 style="text-align: center;">Changed Log</h3>
	<div id="tab">
		<form method="POST" enctype="multipart/form-data">
			<center>
				<table border="1" cellspacing="0px" id="disp">
					<tr>
						<th>Type</th>
						<th>Previous</th>
						<th>Current </th>
						<th>Date</th>
					</tr>
					<?php
					$id=$_GET['id'];
					$module=$_GET['module'];
					$sid=$_GET['sid'];
					
						//creating object for class 'Product' & Calling Its Method "changeLog()" to fetch all Changed Price List in Detail
					$changed=New Product();
					if($module=='product')
					{
						echo $changed->changeLog($id,$module,$sid);
					}
					else
					{
						echo $changed->changeLog($id,$module,$sid);
					}
					
					?>
				</table>
				<br>
				<button class="btndisp" name="back" >Back</button>
				
			</center>
		</form>
	</body>
	</html>
	<?php
	if(isset($_POST['back']))
	{
		if($module=='product')
		{
			header('Location:productlist.php');
		}
		else
		{
			header('Location:saleslist.php?id='.$id);
		}
		
	}
         
} else {
    header('Location:index.php');
}
?>
	