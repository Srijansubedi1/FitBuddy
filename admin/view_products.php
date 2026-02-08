<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$res = $conn->query("SELECT * FROM products");
?>

<h2>All Products</h2>

<table class="table table-bordered">
<tr>
<th>Image</th>
<th>Name</th>
<th>Price</th>
<th>Stock</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($p = $res->fetch_assoc()) { ?>
<tr>
<td><img src="../uploads/<?php echo $p['image']; ?>" width="70"></td>
<td><?php echo $p['name']; ?></td>
<td>Rs. <?php echo $p['price']; ?></td>
<td><?php echo $p['stock']; ?></td>
<td><?php echo $p['status']; ?></td>
<td>
<a href="delete_product.php?id=<?php echo $p['id']; ?>" 
   onclick="return confirm('Delete product?')" 
   class="btn btn-danger btn-sm">
   Delete
</a>
</td>
</tr>
<?php } ?>
</table>

<a href="add_product.php" class="btn btn-primary">Add New Product</a>
