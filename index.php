<?php
		include_once "db_connection.php";
		include_once "global_constant.php";
		
		if(!$connection){
			die( "Connection error : " . mysqli_connect_error() );
		}
		
		
		
		$base_url = (defined('BASE_URL')) ? BASE_URL : null;
		$page_no = isset($_GET['page_num']) ? $_GET['page_num'] : null;
		$filter_product_name = (isset($_GET['filter_by_product_name'])) ? $_GET['filter_by_product_name'] : null;
		$filter_status = (isset($_GET['filter_by_status'])) ? $_GET['filter_by_status'] : null;
		$product_id = (isset($_GET['id'])) ? $_GET['id'] : null;
		$coupon_discount = isset($_GET['coupon_discount']) ? $_GET['coupon_discount'] : null;
		
		
		
		$fetch_data_query = "SELECT * FROM product_table_gipl WHERE product_id = '$product_id'";
		$result = mysqli_query($connection, $fetch_data_query);
		$fetch_data = mysqli_fetch_assoc($result);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Product form</title>
	
		<!-- Bootstrap css cdn link start -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<!-- Bootstrap css cdn link end -->
		
		<!-- External css file link start -->
		<link rel="stylesheet" href="style.css">
		<!-- External css file link end -->

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
			<div class="row">
				
					<!-- Product form start -->
					<form class="row mt-3 mb-3 border p-3 bg-light rounded" action="" id="product_form" name="product_form" method="post">
							
							<!-- Form heading start -->
							<div class="col-11 text-center">
								<span class="h2"><?php echo (empty($product_id)) ? "Add" : "Update"; ?> Product</span>
							</div>
							<!-- Form heading end -->
							
							<!-- Product id hidden field start -->
							<input type="hidden" class="col-12 form-control form-control-lg" id="product_id" name="product_id" value="<?php echo (isset($product_id)) ? $product_id : null; ?>">
							<!-- Product id hidden field end -->
							
							
							<!-- Back button start -->
							<div class="col-1">
								<a class="btn btn-outline-success" href="<?php echo $base_url . '/list.php?page_num=1&filter_by_product_name='.$filter_product_name.'&id='.$product_id.'&coupon_discount='.$coupon_discount; ?>">Back</a>
							</div>
							<!-- Back button end -->
							
							<!-- Product title field start -->
							<div class="col-12 mt-2">
								<label for="product_name">Product Name
									<svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" class="bi bi-star-fill star" fill="#c16e7c" viewBox="0 0 16 16">
										<path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
									</svg> :
								</label>
								<input type="text" class="form-control form-control-lg mt-2" id="product_name" name="product_name" value="<?php echo (!empty($fetch_data['product_name'])) ? ucfirst($fetch_data['product_name']) : null; ?>">
								<span id="product_name_error" class="text-danger p-1"></span>
							</div>
							<!-- Product title field end -->
							
							
							<!-- Product description field start -->
							<div class="col-12 mt-2">
								<label for="product_description">Product Description
									<svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" class="bi bi-star-fill star" fill="#c16e7c" viewBox="0 0 16 16">
										<path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
									</svg> :
								</label>
								<textarea class="form-control col-12" id="product_description" name="product_description" rows="3"><?php echo (!empty($fetch_data['product_description'])) ? ucfirst($fetch_data['product_description']) : null; ?></textarea>
								<span id="product_description_error" class="text-danger p-1"></span>
							</div>
							<!-- Product description field end -->
							
							
							<!-- Product MRP field start -->
							<div class="col-md-6 mt-2">
								<label for="product_mrp">Product MRP
									<svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" class="bi bi-star-fill star" fill="#c16e7c" viewBox="0 0 16 16">
										<path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
									</svg> :
								</label>
								<input type="text" class="form-control form-control-lg mt-2" id="product_mrp" name="product_mrp" value="<?php echo (!empty($fetch_data['product_mrp'])) ? ucfirst($fetch_data['product_mrp']) : null; ?>">
								<span id="product_mrp_error" class="text-danger p-1"></span>
							</div>
							<!-- Product MRP field end -->
							
							
							<!-- Product selling price field start -->
							<div class="col-md-6 mt-2">
								<label for="product_selling_price">Product Selling Price
									<svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" class="bi bi-star-fill star" fill="#c16e7c" viewBox="0 0 16 16">
										<path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
									</svg> :
								</label>
								<input type="text" class="form-control form-control-lg mt-2" id="product_selling_price" name="product_selling_price" value="<?php echo (!empty($fetch_data['product_sell_price'])) ? ucfirst($fetch_data['product_sell_price']) : null; ?>">
								<span id="product_selling_price_error" class="text-danger p-1"></span>
							</div>
							<!-- Product selling price field end -->
							
							
							<!-- Buttons field start -->
							<div class="col-md-12 mt-2">
								
								<input type="submit" class="btn btn-outline-success" id="submit_btn" name="submit_btn" value="Submit">
								<input type="reset" class="btn btn-outline-danger" id="reset_btn" name="reset_btn" value="Reset">
								
							</div>
							<!-- Buttons field end -->
							
					</form>
					<!-- Product form end -->
				
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
