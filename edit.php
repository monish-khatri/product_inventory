<?php
session_start();
if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Product</title>
	<link rel="stylesheet" type="text/css" href="css/table.css">
	
</head>
<body>
	<?php
	if(isset($_GET['eid']))
	{
		include 'model.php';
		$select=New Product();
		$selected=$select->selectProduct($_GET['eid']);
		if (mysqli_num_rows($selected) > 0) 
		{
			$row = mysqli_fetch_assoc($selected);
			$preprice=$row['price'];
			$prequantity=$row['available_quantity'];
			?>
			<form method="POST" enctype="multipart/form-data">
				<h3 style="text-align: center;">Edit Product</h3>
				<div class="div">
					<center>
						<table style="align-content: center;">
							
							<tr>
								<td><label>Product Name    </label></td>
								<td><label>:</label></td>
								<td><input type="text" name="productname" id="productname" value="<?php echo $row["product_name"];?>"></td>
							</tr>
							<tr><td></td>
								<td></td>
								<td><span  id="name1"></span></td>
							</tr>
							<tr>
								<td><label>Price    </label></td>
								<td><label>:</label></td>
								<td><input type="text" name="price" id="price" value="<?php echo $row["price"];?>"></td>
							</tr>
							<tr><td></td>
								<td></td><td><span id="price1"></span></td>
							</tr>
							<tr>
								<td><label>Available Quantity</label></td>
								<td><label>:</label></td>
								<td><input type="text" name="availablequantity" id="availablequantity" value="<?php echo $row["available_quantity"];?>"></td>
							</tr>
							<tr><td></td>
								<td></td><td><span  id="availablequantity1"></span></td>
							</tr>
							<tr>
								<td><label>Category    </label></td>
								<td><label>:</label></td>
								<td><select name="category" >
									<option value="shoes" <?php if($row["category"]=='shoes'){echo "selected";}?>>Shoes</option>
									<option value="sports" <?php if($row["category"]=='sports'){echo  "selected";}?>>Sports</option>
									<option value="sports" <?php if($row["category"]=='electronic'){echo  "selected";}?>>Electronic</option>
								</select> </td>
							</tr>
							<td><label>Image </label></td>
							<td><label>:</label></td>
							<td><img id="photo" src="<?php echo $row["image"];?>" width="80" height="80"><br><input type="file" name="photo"></td>
						</tr>
						<tr><td></td><td></td></tr>
						<tr><td></td><td></td></tr>
						<tr><td><button class="btndisp" name="back" >Back</button></td>

							<td colspan="2" style="text-align: center;" >
								<button class="btndisp" name="update" onclick="return checkValidation()" >Update</button>
							</td>
						</tr>
					</table>
				</center>
			</div>
		</form>
		<?php
	}
	if(isset($_REQUEST["back"]))
	{
		header('Location:productlist.php');
	}

	if(isset($_REQUEST['update']))
	{	
		$target_dir = "images/";
		$imageFileType = strtolower(pathinfo(basename($_FILES["photo"]["name"]),PATHINFO_EXTENSION));
		$target_file = $target_dir .$_POST["productname"].'.'.$imageFileType ;
		$uploadOk = 1;
		$imgsize=$_FILES["photo"]["size"];
		$imgsize=$_FILES["photo"]["size"];
		if ($imgsize>0)
		{
			if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) 
			{
				echo $msg = "Image uploaded successfully";
			}else{
				echo $msg = "Failed to upload image<br>";
			}
		}
		else
		{
			$target_file=$row["image"];
		}
		$productname=$_REQUEST['productname'];
		$price=$_REQUEST['price'];
		$availablequantity=$_REQUEST['availablequantity'];
		$category=$_REQUEST['category'];
		$image=$target_file;
		$edituser=New Product();
		$edituser->editProduct($_GET['eid'],$productname,$price,$availablequantity,$category,$image,$preprice,$prequantity);
		header('Location:productlist.php');
	}
}
?>
<script  src="jquery-3.4.1.js"></script>
<script type="text/javascript">
	function checkValidation()
	{
		var productname=$("#productname").val();
		var price=$("#price").val();
		var availablequantity=$("#availablequantity").val();
		var photo=$("#photo").val();
		var status=false;
		var nameReg = /^[A-Za-z_ ]+$/;
		var priceReg =  /^[1-9]\d*(\.\d+)?$/;
		var quantityReg = /^[0-9]+$/;
		if(productname=="" && price=="" && availablequantity=="")
		{
			alert("All Fields Are Required...");
			return false;
		}
		else
		{
			if(productname=="")
			{
				$("#name1").html(' Enter Product Name');
				$("#productname").css({'border':'solid 2px red','border-radius':'5px'});
				$("#btnid").click(function(){
					$("#productname").focusin();
				});
				return false;
			}
			else if(!nameReg.test(productname))
			{
				$("#name1").html(' Enter valid Product Name');
				$("#productname").css({'border':'solid 2px red','border-radius':'5px'});
				return false;
			}
			else
			{
				$("#productname").css({'border':'solid 2px green','border-radius':'5px'});
				$("#name1").html('');
			}

			if(price=="")
			{
				$("#price1").html(' Enter Product Price');
				$("#price").css({'border':'solid 2px red','border-radius':'5px'});
				status=false;
			}
			else if(!priceReg.test(price))
			{

				$("#price1").html(' Enter Integer value');
				$("#price").css({'border':'solid 2px red','border-radius':'5px'});
				return false;
			}
			else
			{
				$("#price").css({'border':'solid 2px green','border-radius':'5px'});
				$("#price1").html('');
			}

			if(availablequantity=="")
			{
				$("#availablequantity1").html(' Enter Available Quantity');
				$("#availablequantity").css({'border':'solid 2px red','border-radius':'5px'});
				return false;
			}
			else if(!quantityReg.test(availablequantity))
			{

				$("#availablequantity1").html(' Enter Non Decimal value');
				$("#availablequantity").css({'border':'solid 2px red','border-radius':'5px'});
				return false;
			}
			else
			{
				$("#availablequantity").css({'border':'solid 2px green','border-radius':'5px'});
				$("#availablequantity1").html('');
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
