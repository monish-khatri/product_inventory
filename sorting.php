<?php
include 'model.php';

session_start();
if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {

$sort=$_REQUEST["sort"];

$sorting=New Product();
$sorting->ProductList($sort);
header('Location:productlist.php');
}else {
    header('Location:index.php');
}

?>