<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/table.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>


        <?php
        //creating database connection
        include 'dbconn.php';

        class Product {

            public $conn;

            public function __construct() {
                $db = New DatabaseConn();
                $this->conn = $db->Connection();
            }

            public function Login($username, $pass) {

                $sql = "SELECT * FROM users WHERE deleted='0' AND username='$username' AND password='$pass'";
                $result = $this->conn->query($sql);

                if (mysqli_num_rows($result) > 0) {
                   $row = mysqli_fetch_assoc($result);
                    return $row;
                } else {
                    return false;
                }
            }

            public function Registration($username, $pass, $email) {

                $sql = "INSERT INTO users (username,password,email,deleted) VALUES ('$username', '$pass', '$email', '0')";
                $result = $this->conn->query($sql);
                if ($result) {
                    return true;
                } else {
                    return false;
                }
            }

            public function forgotUserPass($email) {

                $sql = "SELECT * FROM users WHERE email LIKE '$email'";
                $result = $this->conn->query($sql);

                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $details = 'true/' . $row['username'] . '/' . $row['password'];


                    return $details;
                } else {
                    return false;
                }
            }

            //Product List table  display from ProductList()
            public function ProductList($sort) {


                $sql = "select * from product_master WHERE deleted='0'";
                $order = $_GET['orderby'];
                if ($sort == 'productname') {
                    $sql .= "order by product_name $order";
                }
                if ($sort == 'price') {
                    $sql .= "order by price $order";
                }
                if ($sort == 'availablequantity') {
                    $sql .= "order by available_quantity $order";
                }
                if ($sort == 'catagory') {
                    $sql .= "order by category $order";
                }
                $result = $this->conn->query($sql);
                if (mysqli_num_rows($result) > 0) {
                    $output = " ";
                    while ($row = mysqli_fetch_assoc($result)) {
                        $output .= "<tr>
						<td style=\"text-align:center\">" . $row["product_name"] . "</td>
						<td style=\"text-align:center\">" . $row["price"] . "</td>
						<td style=\"text-align:center\">" . $row["available_quantity"] . "</td>
						<td style=\"text-align:center\" >" . $row["category"] . "</td>
						<td><img src=" . $row["image"] . " width=\"80\" height=\"80\"></td>
						<td><a  style=\"color:#646464;text-decoration:none\" href='edit.php?eid=" . $row['pid'] . "' ><i class=\"fa fa-pencil-square-o\" aria-hidden=\"true\"></i> Edit
						</a><br>
						<a style=\"color:#646464;text-decoration:none\" onClick=\"javascript: return confirm('Are you sure You want to delete!');\" href='delete.php?duid=" . $row['pid'] . "' ><i class=\"fa fa-trash-o\" aria-hidden=\"true\"></i> Delete
						</a><br>
						<a style=\"color:#646464;text-decoration:none\" href='saleslist.php?id=" . $row['pid'] . "'><i class=\"fa fa-balance-scale\" aria-hidden=\"true\"></i> Sales Entry
						</a><br>
						<a  style=\"color:#646464;text-decoration:none;\" href='changedlog.php?id=" . $row['pid'] . "&module=product'><i class=\"fa fa-bars\" aria-hidden=\"true\"></i> Changed Log
						</a></td>
						</tr>";
                    }
                    return $output;
                } else {
                    return "<tr><td style=\"text-align:center\" colspan=\"6\">No Records To Display<td></tr>";
                }
            }

            //Edit Or UPDATE Exisiting Product
            public function editProduct($id, $productname, $price, $availablequantity, $category, $image, $preprice, $prequantity) {
                $sql = "UPDATE product_master SET product_name='" . $productname . "'," . "price='" . $price . "'," . "available_quantity='" . $availablequantity . "'," . "category='" . $category . "'," . "image='" . $image . "' WHERE pid=" . $id . " AND deleted='0'";
                $result = $this->conn->query($sql);
                if ($price != $preprice) {
                    $sql1 = "INSERT INTO product_changed_log(type,prev,current,pid,deleted) VALUES('Price'," . $preprice . "," . $price . "," . $id . ",'0')";
                    $result1 = $this->conn->query($sql1);
                }
                if ($availablequantity != $prequantity) {
                    $sql2 = "INSERT INTO product_changed_log(type,prev,current,pid,deleted) VALUES('Quantity'," . $prequantity . "," . $availablequantity . "," . $id . ",'0')";
                    $result2 = $this->conn->query($sql2);
                }
            }

            public function selectProduct($id) {
                $sql = "select * from product_master where pid=$id AND deleted='0'";
                $result = $this->conn->query($sql);
                return $result;
            }

            //Add Product
            public function addProduct($productname, $price, $availablequantity, $category, $image) {

                $sql = "INSERT INTO product_master(product_name,price,available_quantity,category,image,deleted) VALUES ('" . $productname . "','" . $price . "','" . $availablequantity . "','" . $category . "','" . $image . "','0')";
                $result = $this->conn->query($sql);
                if ($result) {
                    $lastid = $this->conn->insert_id;
                    $sql1 = "INSERT INTO product_changed_log(type,prev,current,pid,deleted) VALUES('Price','0.0'," . $price . "," . $lastid . ",'0')";
                    $result1 = $this->conn->query($sql1);
                    $sql2 = "INSERT INTO product_changed_log(type,prev,current,pid,deleted) VALUES('Quantity','0.0'," . $availablequantity . "," . $lastid . ",'0')";
                    $result2 = $this->conn->query($sql2);
                }
            }

            //Delete Existing Product
            public function deleteProduct($id) {
                $sql = "UPDATE product_master SET deleted='1' where pid=" . $id;
                $sql1 = "UPDATE sale_master SET deleted='1' Where pid=" . $id;
                $sql2 = "UPDATE product_changed_log SET deleted='1' Where pid=" . $id;
                $result = $this->conn->query($sql);
                $result1 = $this->conn->query($sql1);
                $result2 = $this->conn->query($sql2);
            }

            //Sales List For Each Product table  display from salesRecord()
            public function salesRecord($id) {
                $sql = "select S.* from sale_master S where deleted='0' AND S.pid=$id";
                $result = $this->conn->query($sql);
                if (mysqli_num_rows($result) > 0) {
                    $output = " ";
                    while ($row = mysqli_fetch_assoc($result)) {
                        $profit = ($row["sale_price"] - $row['purchase_price']) * $row["sold_quantities"];
                        $percentage = round((($profit / $row['purchase_price']) * 100) / $row["sold_quantities"]);
                        $output .= "<tbody><tr>
						<td style=\"text-align:center\">" . $row['purchase_price'] . "</td>
						<td style=\"text-align:center\">" . $row["sale_price"] . "</td>
						<td style=\"text-align:center\">" . $row["sold_quantities"] . "</td>
						<td style=\"text-align:center\">" . $profit . "</td>
						<td style=\"text-align:center\">" . $percentage . "%" . "</td>
						<td><a style=\"color:#646464;text-decoration:none\" href='saleedit.php?sid=" . $row['sid'] . "&id=" . $row['pid'] . "'><i class=\"fa fa-pencil-square-o\" aria-hidden=\"true\"></i> Edit</a><br>
						<a  style=\"color:#646464;text-decoration:none\" onClick=\"javascript: return confirm('Are you sure You want to delete!');\" href='deletesale.php?sid=" . $row['sid'] . "&id=" . $row['pid'] . "'><i class=\"fa fa-trash-o\" aria-hidden=\"true\"></i> Delete</a><br>
						<a style=\"color:#646464;text-decoration:none\" href='changedlog.php?sid=" . $row['sid'] . "&id=" . $row['pid'] . "&module=sale'><i class=\"fa fa-bars\" aria-hidden=\"true\"></i> Changed Log</a>
						</td>
						</tr></tbody>";
                    }
                    return $output;
                } else {
                    return "<tr><td colspan=6  style=\"text-align:center\">No Records To Display<td></tr>";
                }
            }

            //Add Sale Record
            public function addSale($salesprice, $soldquantities, $pid) {
                $sql1 = "select * from product_master where deleted='0' AND pid=" . $pid;
                $result1 = $this->conn->query($sql1);
                while ($row = mysqli_fetch_assoc($result1)) {
                    $purchaseprice = $row['price'];

                    if ($row['available_quantity'] >= $soldquantities) {
                        $sql = "INSERT INTO sale_master(purchase_price,sale_price,sold_quantities,pid,deleted) VALUES (" . $purchaseprice . "," . $salesprice . "," . $soldquantities . "," . $pid . ",'0')";
                        $sql2 = "UPDATE product_master SET available_quantity='" . ($row['available_quantity'] - $soldquantities) . "' WHERE pid=" . $pid;
                        $result2 = $this->conn->query($sql2);
                        $result = $this->conn->query($sql);

                        if ($result) {
                            $lastid = $this->conn->insert_id;
                            $sql3 = "INSERT INTO sale_changed_log(type,prev,current,sid,deleted) VALUES('Sale Price','0.0'," . $salesprice . "," . $lastid . ",'0')";
                            $result3 = $this->conn->query($sql3);
                            $sql4 = "INSERT INTO sale_changed_log(type,prev,current,sid,deleted) VALUES('Sold Quantity','0.0'," . $soldquantities . "," . $lastid . ",'0')";
                            $result4 = $this->conn->query($sql4);
                        }

                        return true;
                    } else {
                        return false;
                    }
                }
            }

            public function changeLog($id, $module, $sid) {
                if ($module == 'product') {
                    $sql = "select * from product_changed_log where deleted='0' AND pid=" . $id;
                    $result = $this->conn->query($sql);
                    if (mysqli_num_rows($result) > 0) {
                        $output = " ";
                        while ($row = mysqli_fetch_assoc($result)) {
                            $date = date('d-m-Y',strtotime($row["created_date"]));
                            $output .= "<tr>
							<td style=\"text-align:center\">" . $row["type"] . "</td>
							<td style=\"text-align:center\">" . $row["prev"] . "</td>
							<td style=\"text-align:center\">" . $row["current"] . "</td>
							<td style=\"text-align:center\">" . $date . "</td>
							</tr>";
                        }
                        return $output;
                    } else {
                        return "<tr><td style=\"text-align:center\" colspan=\"4\">No Records To Display<td></tr>";
                    }
                } else {
                    $sql = "select * from sale_changed_log where deleted='0' AND  sid=" . $sid;
                    $result = $this->conn->query($sql);
                    if (mysqli_num_rows($result) > 0) {
                        $output = " ";
                        while ($row = mysqli_fetch_assoc($result)) {
                            $date = date('d-m-Y',strtotime($row["created_date"]));
                            $output .= "<tr>
							<td style=\"text-align:center\">" . $row["type"] . "</td>
							<td style=\"text-align:center\">" . $row["prev"] . "</td>
							<td style=\"text-align:center\">" . $row["current"] . "</td>
							<td style=\"text-align:center\">" . $date . "</td>
							</tr>";
                        }
                        return $output;
                    } else {
                        return "<tr><td style=\"text-align:center\" colspan=4>No Records To Display<td></tr>";
                    }
                }
            }

            public function selectSale($id) {
                $sql = "select * from sale_master where deleted='0' AND sid=" . $id;
                $result = $this->conn->query($sql);
                return $result;
            }

            public function editSale($id, $salesprice, $soldquantities, $presaleprice, $prequantity, $pid) {
                $product = $this->selectProduct($pid);
                while ($row = mysqli_fetch_assoc($product)) {
                    $sql = "UPDATE sale_master SET sale_price=" . $salesprice . ",sold_quantities=" . $soldquantities . " WHERE sid=" . $id;
                    $result = $this->conn->query($sql);

                    if ($presaleprice != $salesprice) {
                        $sql4 = "INSERT INTO sale_changed_log(type,prev,current,sid,deleted) VALUES('Sale Price'," . $presaleprice . "," . $salesprice . "," . $id . ",'0')";
                        $result4 = $this->conn->query($sql4);
                    }
                    if ($prequantity != $soldquantities) {
                        $sql5 = "INSERT INTO sale_changed_log(type,prev,current,sid,deleted) VALUES('Sold Quantity'," . $prequantity . "," . $soldquantities . "," . $id . ",'0')";
                        $result5 = $this->conn->query($sql5);
                    }
                    if ($prequantity > $soldquantities) {
                        $tot = $prequantity - $soldquantities;
                        $sql2 = "UPDATE product_master SET available_quantity='" . ($row['available_quantity'] + $tot) . "' WHERE pid=" . $pid;
                    } else {
                        $tot = $soldquantities - $prequantity;
                        $sql2 = "UPDATE product_master SET available_quantity='" . ($row['available_quantity'] - $tot) . "' WHERE pid=" . $pid;
                    }

                    $result2 = $this->conn->query($sql2);
                }
            }

            public function deleteSale($id, $pid) {
                $product = $this->selectProduct($pid);
                $row = mysqli_fetch_assoc($product);
                $sale = $this->selectSale($id);
                $row1 = mysqli_fetch_assoc($sale);
                $sql2 = "UPDATE product_master SET available_quantity='" . ($row['available_quantity'] + $row1['sold_quantities']) . "' WHERE pid=" . $pid;
                $result2 = $this->conn->query($sql2);
                $sql = "UPDATE  sale_master  SET deleted='1' where sid=$id";
                $result = $this->conn->query($sql);
            }

            public function Sorting() {
                $sql = "select * from product_master WHERE deleted='0' order By product_name asc";
                $result = $this->conn->query($sql);
                if (mysqli_num_rows($result) > 0) {
                    $output = " ";
                    while ($row = mysqli_fetch_assoc($result)) {
                        $output .= "<tbody><tr>
						<td style=\"text-align:center\">" . $row["product_name"] . "</td>
						<td style=\"text-align:center\">" . $row["price"] . "</td>
						<td style=\"text-align:center\">" . $row["available_quantity"] . "</td>
						<td style=\"text-align:center\" >" . $row["category"] . "</td>
						<td><img src=" . $row["image"] . " width=\"80\" height=\"80\"></td>
						<td><a  style=\"color:#646464;text-decoration:none\" href='edit.php?eid=" . $row['pid'] . "' ><i class=\"fa fa-pencil-square-o\" aria-hidden=\"true\"></i> Edit
						</a><br>
						<a style=\"color:#646464;text-decoration:none\" onClick=\"javascript: return confirm('Are you sure You want to delete!');\" href='delete.php?duid=" . $row['pid'] . "' ><i class=\"fa fa-trash-o\" aria-hidden=\"true\"></i> Delete
						</a><br>
						<a style=\"color:#646464;text-decoration:none\" href='saleslist.php?id=" . $row['pid'] . "'><i class=\"fa fa-balance-scale\" aria-hidden=\"true\"></i> Sales Entry
						</a><br>
						<a  style=\"color:#646464;text-decoration:none\" href='changedlog.php?id=" . $row['pid'] . "&module=product'><i class=\"fa fa-bars\" aria-hidden=\"true\"></i> Changed Log
						</a></td>
						</tr></tbody>";
                    }
                    return $output;
                } else {
                    return "<tr><td style=\"text-align:center\" colspan=6>No Records To Display<td></tr>";
                }
            }

        }
        ?>
    </body>
</html>