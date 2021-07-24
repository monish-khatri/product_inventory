<?php
session_start();
if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Sale</title>
	<link rel="stylesheet" type="text/css" href="css/table.css">
</head>
<body>
	<?php
	include 'model.php';
	$select=New Product();
	$selected=$select->selectSale($_GET['sid']);
	$pid=$_GET['id'];
	if (mysqli_num_rows($selected) > 0) 
	{
		while($row = mysqli_fetch_assoc($selected))
		{
			$presaleprice=$row['sale_price'];
			$prequantity=$row['sold_quantities'];
			$id=$row['sid'];
			?>
			<form method="POST">
				<h3 style="text-align: center;">Edit sale</h3>
				<div class="div">
					<center>
						<table style="align-content: center;">
							<tr>
								<td><label>Purchase Price    </label></td>
								<td><label>:</label></td>
								<td><label><?php echo $row['purchase_price'];?></label></td>
							</tr>

							
							<tr>
								<td><label>Sales Price    </label></td>
								<td><label>:</label></td>
								<td><input type="text" name="salesprice" id="salesprice" value="<?php echo $row['sale_price'];?>" ></td>
							</tr>
							<tr><td></td>
								<td></td><td><span id="salesprice1"></span></td>
							</tr>
							<tr>
								<td><label>Sold Quantities   </label></td>
								<td><label>:</label></td>
								<td><input type="text" name="soldquantities" id="soldquantities" value="<?php echo $row['sold_quantities'];?>" ></td>
							</tr>
							<tr><td></td>
								<td></td><td><span id="soldquantities1"></span></td>
							</tr>
							<tr><td></td><td></td></tr>
							<tr><td></td><td></td></tr>
							<tr><td  style="text-align: center;" ><button class="btndisp" name="back" >Back</button></td>
								<td  style="text-align: center;" colspan="2" ><button class="btndisp" onclick="return checkValidation()"  name="editsale" >Update</button></td>
							</tr>
						</table>
					</center>
				</div>
			</form>
			<?php
		}
	}

	if(isset($_POST['editsale']))
	{
		$salesprice=$_POST['salesprice'];
		$soldquantities=$_POST['soldquantities'];

		$editsale=New Product();
		$edit=$editsale->editSale($id,$salesprice,$soldquantities,$presaleprice,$prequantity,$pid);
		
		header('Location:saleslist.php?id='.$_GET['id']);

		
		
	}
	if(isset($_POST['back']))
	{
		header('Location:saleslist.php?id='.$_GET['id']);
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