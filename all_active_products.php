<?php
			include_once "db_connection.php";
			include_once "global_constant.php";
			
			if(!$connection){
				die( "Connection error : " . mysqli_connect_error() );
			}
			
			
			$base_url = (defined('BASE_URL')) ? BASE_URL : null;
			$product_id = (isset($_GET['id'])) ? $_GET['id'] : null;
			$coupon_discount = isset($_GET['coupon_discount']) ? $_GET['coupon_discount'] : null;
			
			//Fetch products query start
			$view_page_query = "SELECT * FROM product_table_gipl WHERE status = 0 and is_deleted = 0";
			$result = mysqli_query($connection, $view_page_query);
			//Fetch products query end
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Active Products</title>

	<!-- Bootstrap css cdn link start -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<!-- Bootstrap css cdn link end -->
	
	<!-- JQuery cdn link start -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
	<!-- JQuery cdn link end -->
</head>
<body>
		
		<div class="container">
			<div class="row">
				
				<!-- Back button start -->
					<div class="col-1">
						<a class="btn btn-outline-success" href="<?php echo $base_url . '/list.php?page_num=1&filter_by_product_name='.$filter_product_name.'&id='.$product_id.'&coupon_discount='.$coupon_discount; ?>">Back</a>
					</div>
				<!-- Back button end -->
				
				 <?php
					if(mysqli_num_rows($result) > 0){
						
						while($row = mysqli_fetch_assoc($result)){
							$product_id = $row['product_id'];
							$product_name = $row['product_name'];
							$product_mrp = $row['product_mrp'];
							
							
							//Fetch product cart table to get cart id start
							$fetch_data_query = "SELECT cart_id, quantity FROM product_cart_table_gipl WHERE product_id = '$product_id'";
							$list =  mysqli_query($connection, $fetch_data_query);
							$fetch_data = mysqli_fetch_assoc($list);
							$cart_id = $fetch_data['cart_id'];
							$quantity = $fetch_data['quantity'];
							if(empty($quantity)){
								$button_name = "Add to cart";
								$disabled = '';
								$button_class = "btn-outline-success";
							}
							else{
								$button_name = "Added to cart";
								$disabled = 'disabled';
								$button_class = "btn-warning";
							}
							//Fetch product cart table to get cart id end
				?>			
						
						<div class="col-3 border p-4 mt-5">
							<div>Product Name = <?php echo ucfirst($row['product_name']) ?></div>
							<div>Product Description = <?php echo ucfirst($row['product_description']) ?></div>
							<div>Product MRP = <?php echo $row['product_mrp'] ?></div>
							<div>Product Sell Price = <?php echo $row['product_sell_price'] ?></div>
							<div>
								<form method="post" action='<?php echo "$base_url/product_add_update_request.php?id=$product_id&cart_id=$cart_id&product_mrp=$product_mrp&product_name=$product_name&coupon_discount=$coupon_discount&form_action=".ADD_TO_CART; ?>' name="add_to_cart_form" id="add_to_cart_form">
									<input type="submit" class="btn <?php echo $button_class; ?>" id="add_to_cart<?php echo $product_id; ?>" name="add_to_cart" value="<?php echo $button_name; ?>" <?php echo $disabled; ?> >
								</form>
							</div>
						</div>
							
				<?php		
						}
						
					}
				 ?>						
				
			</div>
		</div>
		
		
</body>
</html>
