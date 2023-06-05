<?php
			include_once "db_connection.php";
			include_once "global_constant.php";
			
			if(!$connection){
				die( "Connection error : " . mysqli_connect_error() );
			}
			
			$base_url = (defined('BASE_URL')) ? BASE_URL : null;
			$page_no = isset($_GET['page_num']) ? $_GET['page_num'] : null;
			$product_id = (isset($_GET['id'])) ? $_GET['id'] : null;
			$filter_product_name = (isset($_GET['filter_by_product_name'])) ? $_GET['filter_by_product_name'] : null;
			$filter_status = (isset($_GET['filter_by_status'])) ? $_GET['filter_by_status'] : null;
			$coupon_discount = isset($_GET['coupon_discount']) ? $_GET['coupon_discount'] : null;
			
			$view_page_query = "SELECT * FROM product_table_gipl WHERE product_id = '$product_id'";
			$result = mysqli_query($connection, $view_page_query);
			$fetch_data = mysqli_fetch_assoc($result);
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>View Page</title>

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
			<div class="col-12">
				<a href=<?php echo "$base_url/list.php?page_num=$page_no&filter_by_product_name=$filter_product_name&coupon_discount=$coupon_discount"; ?> class="btn btn-outline-success mb-2">Back</a>
			</div>
			<!-- Back button end -->
			
			<table class="table table-primary text-center table-bordered">
				<tbody>
					<tr>
						  <th scope="col">Product Name</th>
						  <td><?php echo (!empty($fetch_data['product_name'])) ? ucfirst($fetch_data['product_name']) : "NA" ?></td>
					</tr>
					
					<tr>
						  <th scope="col">Product Description</th>
						  <td><?php echo (!empty($fetch_data['product_description'])) ? ucfirst($fetch_data['product_description']) : "NA" ?></td>
					</tr>
					
					<tr>
						  <th scope="col">Product MRP</th>
						  <td><?php echo (!empty($fetch_data['product_mrp'])) ? $fetch_data['product_mrp'] : "NA" ?></td>
					</tr>
					
					<tr>
						  <th scope="col">Product Selling Price</th>
						  <td><?php echo (!empty($fetch_data['product_sell_price'])) ? $fetch_data['product_sell_price'] : "NA" ?></td>
					</tr>
					
				</tbody>
			</table>
			
		</div>
	</div>
	
	
	<!-- Bootstrap js cdn link start -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<!-- Bootstrap js cdn link end -->
</body>
</html>
