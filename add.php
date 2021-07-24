<?php
session_start();
if (isset($_REQUEST['back'])) {
    header('Location:productlist.php');
}
if (isset($_REQUEST['addproduct'])) {
    include 'model.php';
    $target_dir = "images/"; //location to store image
    $imageFileType = strtolower(pathinfo(basename($_FILES["photo"]["name"]), PATHINFO_EXTENSION)); //getting image type
    $target_file = $target_dir . uniqid(str_replace(' ', '', $_POST["productname"]) . '_') . '.' . $imageFileType; //storing image with productname to avoid conflicts
    $uploadOk = 1;
    $imgsize = $_FILES["photo"]["size"]; //getting image size
    if ($imgsize > 0) {
        //        move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) { //moving file to defined loaction
            $msg = "Image uploaded successfully";
        } else {
            $msg = "Failed to upload image";
        }
    }
    $productname = $_REQUEST['productname'];
    $price = $_REQUEST['price'];
    $availablequantity = $_REQUEST['availablequantity'];
    $category = $_REQUEST['category'];
    $image = $target_file;

    //creating an object for Class Product  & Calling its method addProduct() for add a product
    $addproduct = New Product();
    $addproduct->addProduct($productname, $price, $availablequantity, $category, $image);

    header('Location:productlist.php');
}

if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <title>Add Product</title>
            <link rel="stylesheet" type="text/css" href="css/table.css">

        </head>
        <body >
            <form  method="POST" enctype="multipart/form-data" >
                <div class="div">
                    <h3 style="text-align: center;">Add Product</h3>

                    <center>
                        <table style="align-content: center;">
                            <tr>
                                <td><label>Product Name </label></td>
                                <td><label><span>*</span>:</label></td>
                                <td><input placeholder="Product Name" type="text" name="productname" id="productname" ></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td colspan="3"><span id="name1"></span></td>
                            </tr>
                            <tr>
                                <td><label>Price </label></td>
                                <td><label><span>*</span>:</label></td>
                                <td><input placeholder="Product Price" type="text" name="price" id="price"></td>
                            </tr>
                            <tr><td></td>
                                <td></td>
                                <td><span id="price1"></span></td>
                            </tr>
                            <tr>
                                <td><label>Available Quantity</label></td>
                                <td><label><span>*</span>:</label></td>
                                <td><input placeholder="Total Quantity" type="text" name="availablequantity" id="availablequantity"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td><span id="availablequantity1"></span></td>
                            </tr>
                            <tr>
                                <td><label>Category</label></td>
                                <td><label><span>*</span>:</label></td>
                                <td>
                                    <select placeholder="Category" name="category" id="category">
                                        <option value="" selected="selected">Select</option>
                                        <option value="shoes">Shoes</option>
                                        <option value="sports">Sports</option>
                                        <option value="electronic">Electronic</option>
                                    </select>
                            </tr>
                            <td><label>Image</label></td>
                            <td><label><span>*</span>:</label></td>
                            <td><input placeholder="Image" type="file" name="photo" id="photo" ></td>
                            </tr>

                            <tr>
                                <td></td>
                                <td></td>
                                <td><span id="photo1"></span></td>
                            </tr>
                            <tr><td></td><td></td></tr>
                            <tr><td></td><td></td></tr>
                            <tr><td style="text-align:center;"><button id="back" class="btndisp" name="back" onclick="window.location.assign('productlist.php')" >Back</button></td>
                                <td colspan="2" style="text-align:center;"><button id="btnid" class="btndisp" name="addproduct" onclick="return checkValidation()" >Add Product</button></td>
                            </tr>
                        </table>
                    </center>
                </div>
            </form>

            <script  src="jquery-3.4.1.js"></script>
            <script type="text/javascript">
                                    //validation for add product form
                                    function checkValidation()
                                    {

                                        var productname = $("#productname").val();
                                        var price = $("#price").val();
                                        var availablequantity = $("#availablequantity").val();
                                        var photo = $("#photo").val();
                                        var status = true;

                                        var nameReg = /^[A-Za-z_ ]+$/;
                                        var priceReg = /^[1-9]\d*(\.\d+)?$/;
                                        var quantityReg = /^[0-9]+$/;
                                        if (productname == "" && price == "" && availablequantity == "" && photo == "")
                                        {
                                            alert("All Field Are Required");
                                            return false;
                                        } else
                                        {
                                            if (productname == "")
                                            {
                                                $("#name1").html(' Enter Product Name');
                                                $("#productname").css({'border': 'solid 2px red', 'border-radius': '5px'});
                                                $("#btnid").click(function () {
                                                    $("#productname").focusin();
                                                });
                                                return false;
                                            } else if (!nameReg.test(productname))
                                            {
                                                $("#name1").html(' Enter valid Product Name');
                                                $("#productname").css({'border': 'solid 2px red', 'border-radius': '5px'});
                                                return false;
                                            } else
                                            {
                                                $("#productname").css({'border': 'solid 2px green', 'border-radius': '5px'});
                                                $("#name1").html('');
                                            }

                                            if (price == "")
                                            {
                                                $("#price1").html(' Enter Product Price');
                                                $("#price").css({'border': 'solid 2px red', 'border-radius': '5px'});
                                                status = false;
                                            } else if (!priceReg.test(price))
                                            {

                                                $("#price1").html(' Enter Integer value');
                                                $("#price").css({'border': 'solid 2px red', 'border-radius': '5px'});
                                                return false;
                                            } else
                                            {
                                                $("#price").css({'border': 'solid 2px green', 'border-radius': '5px'});
                                                $("#price1").html('');
                                            }

                                            if (availablequantity == "")
                                            {
                                                $("#availablequantity1").html(' Enter Available Quantity');
                                                $("#availablequantity").css({'border': 'solid 2px red', 'border-radius': '5px'});
                                                return false;
                                            } else if (!quantityReg.test(availablequantity))
                                            {

                                                $("#availablequantity1").html(' Enter Non Decimal value');
                                                $("#availablequantity").css({'border': 'solid 2px red', 'border-radius': '5px'});
                                                return false;
                                            } else
                                            {
                                                $("#availablequantity").css({'border': 'solid 2px green', 'border-radius': '5px'});
                                                $("#availablequantity1").html('');
                                            }
                                            if (photo == "")
                                            {
                                                $("#photo1").html(' select Image ');
                                                $("#photo").css({'border': 'solid 2px red', 'border-radius': '5px'});
                                                return false;
                                            } else
                                            {
                                                $("#photo").css({'border': 'solid 2px green', 'border-radius': '5px'});
                                                $("#photo1").html('');
                                            }
                                            return true;
                                        }
                                    }

            </script>


        </body>
    </html>
    <?php
} else {
    header('Location:index.php');
}
?>