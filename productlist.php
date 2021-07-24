<?php
include "model.php";
session_start();
if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
    if (isset($_REQUEST['add'])) {
        header('Location:add.php');
    }
    if (isset($_REQUEST['logout'])) {
        header('Location:logout.php');
    }
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <title>Product List</title>
            <link rel="stylesheet" type="text/css" href="css/table.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        </head>
        <body>
            <h3 style="text-align: center;">Product List</h3>
            <div id="tab">
                <form method="POST" enctype="multipart/form-data">
                    <center>
                        <table class="sortable" border="1" cellspacing="0px" id="disp">
                            <thead>
                                <tr>
                                    <th>
                                        <a style="text-decoration:none; color: #fff;" href="productlist.php?sort=productname&orderby=<?php echo ($_REQUEST["sort"] == "productname" && $_REQUEST["orderby"] == "asc") ? "desc" : "asc"; ?>">Product Name</a>
                                    </th>

                                    <th>
                                        <a style="text-decoration:none; color: #fff;" href="productlist.php?sort=price&orderby=<?php echo ($_REQUEST["sort"] == "price" && $_REQUEST["orderby"] == "asc") ? "desc" : "asc"; ?>">Price</a>
                                    </th>
                                    <th>
                                        <a style="text-decoration:none; color: #fff;" href="productlist.php?sort=availablequantity&orderby=<?php echo ($_REQUEST["sort"] == "availablequantity" && $_REQUEST["orderby"] == "asc") ? "desc" : "asc"; ?>">Available Quantity</a>
                                    </th>
                                    <th><a style="text-decoration:none; color: #fff;" 	href="productlist.php?sort=catagory&orderby=<?php echo ($_REQUEST["sort"] == "catagory" && $_REQUEST["orderby"] == "asc") ? "desc" : "asc"; ?>">Category</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr> 
                            </thead>
    <?php
    $sort = $_REQUEST['sort'];

    //creating object for class 'Product' & Calling Its Method "ProductList" to fetch all Product in Table Format
    $productlist = New Product();
    echo $productlist->ProductList($sort);
    ?>
                        </table><br>
                        <button name="add" class="btndisp">Add Product</button>
                        <button name="logout" class="btndisp">Logout</button>
                        <!-- <input type="submit" name="add" value="Add Product"> -->

                    </center>
                </form>
            </div>

        </body>
    </html>
    <?php
} else {
    header('Location:index.php');
}
?>
