<?php
		include_once "db_connection.php";
		include_once "global_constant.php";
		
		if(!$connection){
			die( "Connection Error : " . mysqli_connect_error() );
		}
		
		$date = date("Y-m-d") ? date("Y-m-d") : null;
		$base_url = (defined('BASE_URL')) ? BASE_URL : null;
		$coupon_discount = isset($_GET['coupon_discount']) ? $_GET['coupon_discount'] : null;
		
		
		//Fetch cart details from cart table start
		$fetch_data = "SELECT * FROM product_cart_table_gipl WHERE 1 and quantity > 0 and status = 0";
		$list = mysqli_query($connection, $fetch_data);
		//Fetch cart details from cart table end
		
		
		//Fetch total price from cart table start
		$total_prict_get_query = "SELECT SUM(total_price) FROM product_cart_table_gipl";
		$result = mysqli_query($connection, $total_prict_get_query);
		$total_price = mysqli_fetch_array($result);
		//Fetch total price from cart table end
		
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cart Page</title>

	<!-- Bootstrap css cdn link start -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<!-- Bootstrap css cdn link end -->
	
	<!-- JQuery cdn link start -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
	<!-- JQuery cdn link end -->
	
	<!-- Sweet alert cdn link start -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="sweetalert2.all.min.js"></script>  
	<!-- Sweet alert cdn link end -->
	
</head>
<body>
	
		<div class="container">
			<div class="row p-2">
				
							<!-- Back button start -->
							<div class="col-6">
								<a class="btn btn-outline-success mt-2 mb-3" href="<?php echo $base_url . '/list.php?page_num=1&coupon_discount='.$coupon_discount; ?>">Back</a>
							</div>
							<!-- Back button end -->
							
							
							
							<!-- Apply coupon code field start -->
							<div class="col-6">
								<form class="row" method="post" id="coupon_form" name="coupon_form">
									<div class="col-8">
										<input type="text" class="form-control mt-2" id="coupon_code" name="coupon_code" value="<?php echo (!empty($coupon_discount)) ? "coupon$coupon_discount" : null; ?>">
										<span class="text-danger p-1" id="coupon_error"></span>
									</div>
									<div class="col-4 mt-2">
										<input type="submit" class="btn btn-outline-success" value="Apply Coupon" id="apply_coupon_btn" name="apply_coupon_btn">
									</div>
								</form>
							</div>
							<!-- Apply coupon code field end -->
				
				
				
				<table class="table text-center table-bordered">
					  <thead>
						<tr>
						  <th scope="col">S.N.</th>
						  <th scope="col">Product Name</th>
						  <th scope="col">MRP</th>
						  <th scope="col">Total Price</th>
						  <th scope="col">Quantity</th>
						</tr>
					  </thead>
					  <tbody>
						  <?php
							if(mysqli_num_rows($list) > 0){
								$x = 1;
								while($row1 = mysqli_fetch_assoc($list)){
									$cart_id = $row1['cart_id'];
									$product_id = $row1['product_id'];
									$serial_num = $x;
									$x++;
							?>
							<tr>
								<td><?php echo (!empty($row1['cart_id'])) ? $serial_num : "NA"; ?></td>
								<td><?php echo (!empty($row1['product_name'])) ? ucfirst($row1['product_name']) : "NA"; ?></td>
								<td><?php echo (!empty($row1['product_mrp'])) ? $row1['product_mrp'] : "NA"; ?></td>
								<td><?php echo (!empty($row1['total_price'])) ? $row1['total_price'] : "NA"; ?></td>
								<td class="d-flex">
									<form method="post" action='<?php echo "$base_url/product_add_update_request.php?cart_id=$cart_id&id=$product_id&coupon_discount=$coupon_discount&form_action=".DECREASE_QUANTITY; ?>' name="decrease_quantity_form" id="decrease_quantity_form">
										<input type="submit" id="cart_decrease<?php echo $cart_id; ?>" name="cart_decrease<?php echo $cart_id; ?>" class="btn btn-outline-danger me-4 minus_btn" value=" - ">
									</form>
									<div class="mt-2">
									<?php echo (!empty($row1['quantity'])) ? $row1['quantity'] : "NA"; ?>
									</div>
									<form method="post" action='<?php echo "$base_url/product_add_update_request.php?cart_id=$cart_id&coupon_discount=$coupon_discount&form_action=".INCREASE_QUANTITY; ?>' name="increase_quantity_form" id="increase_quantity_form">
										<input type="submit" id="cart_increase<?php echo $cart_id; ?>" name="cart_increase<?php echo $cart_id; ?>" class="btn btn-outline-success ms-4 plus_btn" value=" + ">
									</form>
									
										<input type="button" class="btn btn-outline-danger ms-4" value="Remove" data-bs-toggle="modal" data-bs-target="#remove_product<?php echo $cart_id; ?>">
										<div class="modal fade" id="remove_product<?php echo $cart_id; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
											  <div class="modal-dialog modal-dialog-centered modal-lg">
												<div class="modal-content">
												  <div class="modal-body h3 $model_text_class">
													 Are you sure to remove <span class="text-success"><?php echo $row1['product_name']; ?></span> from the cart ?
												  </div>
												  <div class="modal-footer">
													<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">No</button>
													<form method="post" action='<?php echo "$base_url/product_add_update_request.php?cart_id=$cart_id&form_action=".REMOVE_PRODUCT; ?>' name="remove_product_form" id="remove_product_form">
														<input type="submit" id="remove_product<?php echo $cart_id; ?>" name="remove_product<?php echo $cart_id; ?>" class="btn btn-outline-danger" value="Yes">
													</form>
												  </div>
												</div>
											  </div>
											</div>
										
								</td>
							</tr>
							
						<?php
								}
							}
							else{
						?> 
								<tr><td colspan=5>No Record Found</td></tr>
						<?php
							}
						  ?>
						  
									<?php 
											if(!empty($coupon_discount)){
												echo "<tr><th  colspan=3 class='text-end'>Sub Total</th><th colspan=2 class='text-start text-success'>" . $total_price[0] . " /-</th></tr>";
												echo "<tr><th  colspan=3 class='text-end'>Discount Percent</th><th colspan=2 class='text-start text-success'>" . $coupon_discount . " %</th></tr>";
												echo "<tr><th  colspan=3 class='text-end'>Discount Amount</th><th colspan=2 class='text-start text-success'>".$total_price[0] * $coupon_discount / 100 ." /-</th></tr>";
												echo "<tr><th  colspan=3 class='text-end'>Final Amount</th><th colspan=2 class='text-start text-success'>".$total_price[0] * (100 - $coupon_discount) / 100 ." /-</th></tr>";
											}
											else{
												echo "<tr><th  colspan=3 class='text-end'>Sub Total</th><th colspan=2 class='text-start text-success'>" . $total_price[0] . " /-</th></tr>";
											}
									?>
					  </tbody>
				</table>
				
			</div>
		</div>
	
	
	
	
	
	
	<!-- Bootstrap js cdn link start -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<!-- Bootstrap js cdn link end -->
	
	<!-- External js file link start -->
	<script src="form_process.js"></script>
	<!-- External js file link end -->
	
</body>
</html>
