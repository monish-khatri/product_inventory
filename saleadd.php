<?php
session_start();
if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add Sales Entry</title>
	<link rel="stylesheet" type="text/css" href="css/table.css">
	
</head>
<body>
	<?php
	include 'model.php';
	$select=New Product();
	$selected=$select->selectProduct($_GET['id']);
	
	if (mysqli_num_rows($selected) > 0) 
	{
		while($row = mysqli_fetch_assoc($selected))
		{
			
			?>
			<form method="POST" enctype="multipart/form-data">
				<h3 style="text-align: center;">Add sale</h3>
				<div class="div">
					<center>
						<table style="align-content: left;">
							<tr>
								<td><label>Product Name</label></td>
								<td><label>:</label></td>
								<td><label><?php echo $row['product_name'];?></label></td>

							</tr>
							<tr>
								<td><label>Purchase Price </label></td>
								<td><label>:</label></td>
								<td><label><?php echo $row['price'];?></label></td>
							</tr>
							<tr>
								<td><label>Available Quantity </label></td>
								<td><label>:</label></td>
								<td><label><?php echo $row['available_quantity'];?></label></td>
							</tr>

							<tr>
								<td><label>Sales Price    </label></td>
								<td><label>:</label></td>
								<td><input placeholder="Sale Price" type="text" id="salesprice" name="salesprice" ></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td><span id="salesprice1"></span></td>
							</tr>
							<tr>
								<td><label>Sold Quantities   </label></td>
								<td><label>:</label></td>
								<td><input placeholder="Sold Quantities" type="text" id="soldquantities" name="soldquantities"  ></td>
							</tr>

							<tr>
								<td></td>
								<td></td>
								<td><span id="soldquantities1"></span></td>
							</tr>
							<tr><td></td><td></td></tr>
							<tr><td></td><td></td></tr>
							<tr><td  style="text-align: center;" ><button class="btndisp" name="back" >Back</button></td>
								<td  style="text-align: center;" colspan="2"><button id="btnid" class="btndisp" name="addsale" onclick="return checkValidation()" >Add Sale</button></td>
							</tr>
						</table>
					</center>
				</div>
			</form>
			<?php
		}
	}
	$id=$_GET['id'];
	if(isset($_POST['back']))
	{
		header('Location:saleslist.php?id='.$id);
	}
	if(isset($_POST['addsale']))
	{
		$salesprice=$_REQUEST['salesprice'];
		$soldquantities=$_REQUEST['soldquantities'];
		$addsale=New Product();
		$submit=$addsale->addSale($salesprice,$soldquantities,$id);
		if($submit==true)
		{
			header('Location:saleslist.php?id='.$id);
		}
		else
		{
			echo "<script>alert('You Dont Have That Much Of Quantity');</script>";
		}
	}
	?>
	<script  src="jquery-3.4.1.js"></script>
	<script type="text/javascript">
		//validation for add product form
		function checkValidation() 
		{

			var salesprice=$("#salesprice").val();
			var soldquantities=$("#soldquantities").val();
			var status=true;

			var priceReg =  /^[1-9]\d*(\.\d+)?$/;
			var quantityReg = /^[0-9]+$/;
			if(salesprice=="" && soldquantities=="")
			{
				$("#salesprice1").html(' Enter Sold Price');
				$("#salesprice,#soldquantities").css({'border':'solid 2px red','border-radius':'5px'});
				$("#soldquantities1").html(' Enter Sold Quantities');
				
				return false;
			}
			else
			{

				if(salesprice=="")
				{
					$("#salesprice1").html(' Enter Sold Price');
					$("#salesprice").css({'border':'solid 2px red','border-radius':'5px'});
					status=false;
				}
				else if(!priceReg.test(salesprice))
				{
					
					$("#salesprice1").html('Enter Integer value');
					$("#salesprice").css({'border':'solid 2px red','border-radius':'5px'});
					return false;
				}
				else
				{
					$("#salesprice").css({'border':'solid 2px green','border-radius':'5px'});
					$("#salesprice1").html('');
				}

				if(soldquantities=="")
				{
					$("#soldquantities1").html('Enter Sold Quantities');
					$("#soldquantities").css({'border':'solid 2px red','border-radius':'5px'});
					return false;
				}
				else if(!quantityReg.test(soldquantities))
				{
					
					$("#soldquantities1").html(' Enter Numeric & Non-Decimal value');
					$("#soldquantities").css({'border':'solid 2px red','border-radius':'5px'});
					return false;
				}
				else
				{
					$("#soldquantities").css({'border':'solid 2px green','border-radius':'5px'});
					$("#soldquantities1").html('');
				}
				
				return true;
			}
		}

	</script>
</body>
</html>
<?php
}else {
    header('Location:index.php');
}
?>

