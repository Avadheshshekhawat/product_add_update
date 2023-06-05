<?php
	include_once "db_connection.php";
	include_once "global_constant.php";
	
	
	$active_status = (defined('ACTIVE')) ? ACTIVE : null;
	$deactive_status = (defined('DEACTIVE')) ? DEACTIVE : null;
	$not_deleted = (defined('NOT_DELETED')) ? NOT_DELETED : null;
	$deleted = (defined('DELETED')) ? DELETED : null;
	$date = date("Y-m-d") ? date("Y-m-d") : null;
	$base_url = (defined('BASE_URL')) ? BASE_URL : null;
	
	if(!$connection){
		die( "Connection Error : " . mysqli_connect_error() );
	}
	
	
	$product_id = (isset($_GET['id'])) ? $_GET['id'] : null;
	$status = (isset($_GET['status'])) ? $_GET['status'] : null;
	$is_deleted = (isset($_GET['is_deleted'])) ? $_GET['is_deleted'] : null;
	$coupon_discount = isset($_GET['coupon_discount']) ? $_GET['coupon_discount'] : null;
	
	//Update status query start
	if(isset($product_id) && isset($status)){
		$update_status = "UPDATE product_table_gipl SET status = '$status', updated = '$date' WHERE product_id = '$product_id'";
		$status_update = mysqli_query($connection, $update_status);
		
		$status_update_cart_table = "UPDATE product_cart_table_gipl SET quantity = 0, total_price = 0, status = '$status', updated = '$date' WHERE product_id = '$product_id'";
		$result = mysqli_query($connection, $status_update_cart_table);
	}
	//Update status query end
	
	
	//Update is deleted query start
	if(isset($product_id) && isset($is_deleted)){
		$update_is_deleted = "UPDATE product_table_gipl SET is_deleted = '$is_deleted', updated = '$date' WHERE product_id = '$product_id'";
		$is_deleted_update = mysqli_query($connection, $update_is_deleted);
		
		$is_deleted_update_cart_table = "UPDATE product_cart_table_gipl SET quantity = 0, total_price = 0, is_deleted = '$is_deleted', updated = '$date' WHERE product_id = '$product_id'";
		$result1 = mysqli_query($connection, $is_deleted_update_cart_table);
	}
	//Update is deleted query end
	
	
	
		//Filter form query start
		$filter_product_name = isset($_REQUEST['filter_by_product_name']) ? test_input($_REQUEST['filter_by_product_name']) : null;
		$filter_status = isset($_REQUEST['filter_by_status']) ? $_REQUEST['filter_by_status'] : null;
		$where_condition = "";
		
		if(!empty($filter_product_name)){
			$where_condition .= "and product_name LIKE '%$filter_product_name%'";
		}
		if($filter_status == "$active_status"){
			$where_condition .= "and status = '$active_status'";
		}
		if($filter_status == "$deactive_status"){
			$where_condition .= "and status = '$deactive_status'";
		}
	
	
		$page_record_limit = 10;
		$total_records = mysqli_query($connection, "SELECT COUNT(*) from product_table_gipl WHERE 1 $where_condition");
		$count_records = mysqli_fetch_array($total_records);
		$total_no_of_pages = ceil($count_records[0] / $page_record_limit);
		$page_no = isset($_GET['page_num']) ? $_GET['page_num'] : null;
		$offset = ($page_no - 1) * $page_record_limit;
		$previous_page = $page_no - 1;
		$next_page = $page_no + 1;
		//Filter form query end
	
	$fetch_data = "SELECT * FROM product_table_gipl WHERE 1 $where_condition order by product_id DESC limit $offset, $page_record_limit";
	$list = mysqli_query($connection, $fetch_data);
	
	
	
	
		function test_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			$data = strtolower($data);
			return $data;
		}	
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>List Page</title>

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
		<div class="container-fluid">
			<div class="row p-2">
				
				<div class="row d-flex">
								<!-- Filter form start -->
								<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]."?page_num=1&filter_by_product_name=$filter_product_name&filter_by_status=$filter_status&coupon_discount=$coupon_discount");?>" id="filter_form" name="filter_form" class="row mt-2 mb-1 ms-auto me-auto p-2">
									
									<!-- Add product button start -->
									<div class="col-md-2">
										<a href=<?php echo "$base_url/index.php?coupon_discount=$coupon_discount"; ?> class="btn btn-outline-primary">Add Product</a>
									</div>
									<!-- Add product button end -->
									
									<!-- Filter by product name input field start -->
									<div class="col-md-3">
										<input type="text" class="form-control" id="filter_by_product_name" name="filter_by_product_name" value="<?php echo (isset($filter_product_name)) ? $filter_product_name : null ?>" placeholder="Filter by product name">
									</div>
									<!-- Filter by product name input field end -->
									
									
									<!-- Filter by status options start -->
									<div class="col-md-3">
										<select class="form-select" id="filter_by_status" name="filter_by_status">
											<option value=""> -- Filter by status -- </option>
											<option value="0" <?php echo ($filter_status == "0") ? 'selected' : ''; ?>>Active</option>
											<option value="1" <?php echo ($filter_status == "1") ? 'selected' : ''; ?>>Deactive</option>
										</select>
									</div>
									<!-- Filter by status options end -->
									
									<!-- Filter button start -->
									<div class="col-md-4">
										<input type="submit" class="btn btn-outline-success" id="filter_btn" name="filter_btn" value="Filter">
										<a href=<?php echo "$base_url/list.php?page_num=1&coupon_discount=$coupon_discount"; ?> class="btn btn-outline-danger" id="reset_btn">Reset</a>
										<a href=<?php echo "$base_url/all_active_products.php?page_num=1&coupon_discount=$coupon_discount"; ?> class="btn btn-outline-success" id="reset_btn">See All Active Procucts</a>
										<a href=<?php echo "$base_url/view_cart.php?coupon_discount=$coupon_discount"; ?> class="btn btn-outline-success" id="reset_btn">View Cart</a>
									</div>
									<!-- Filter button end -->
									
								</form>
								<!-- Filter form end -->
						
					</div>
					
				
				
				<!-- List view start -->
				<table class="table text-center table-bordered">
					  <thead>
						<tr>
						  <th scope="col">S.N.</th>
						  <th scope="col">Product Name</th>
						  <th scope="col">MRP</th>
						  <th scope="col">Selling Price</th>
						  <th scope="col">Status</th>
						  <th scope="col">Is Deleted</th>
						  <th scope="col">Created</th>
						  <th scope="col">Updated</th>
						  <th scope="col">Action</th>
						</tr>
					  </thead>
					  <tbody>
						  <?php
							if(mysqli_num_rows($list) > 0){
								if($page_no > 1){
								$x = 1 + ($offset);
								}
								else{
									$x = 1;
								}
								while($row1 = mysqli_fetch_assoc($list)){
									$product_id = $row1['product_id'];
									$serial_num = $x;
									$x++;
							?>
							<tr>
								<td><?php echo (!empty($row1['product_id'])) ? $serial_num : "NA"; ?></td>
								<td><?php echo (!empty($row1['product_name'])) ? ucfirst($row1['product_name']) : "NA"; ?></td>
								<td><?php echo (!empty($row1['product_mrp'])) ? $row1['product_mrp'] : "NA"; ?></td>
								<td><?php echo (!empty($row1['product_sell_price'])) ? $row1['product_sell_price'] : "NA"; ?></td>
								
								<td><?php echo ($row1['status'] == $active_status) ? "<span class='text-success'>Active</span>" : "<span class='text-danger'>Deactive</span>"; ?></td>
								<td><?php echo ($row1['is_deleted'] == $not_deleted) ? "<span class='text-success'>Not Deleted</span>" : "<span class='text-danger'>Deleted</span>"; ?></td>	
								<td><?php echo (!empty($row1['created'])) ? $row1['created'] : "NA"; ?></td>	
								<td><?php echo (!empty($row1['updated'])) ? $row1['updated'] : "NA"; ?></td>
								<td>
									<?php
										echo "<a href='$base_url/view.php?page_num=$page_no&filter_by_product_name=$filter_product_name&filter_by_status=$filter_status&id=$product_id&coupon_discount=$coupon_discount' class='btn btn-info'>View</a>";
										echo "<a href='$base_url/index.php?page_num=$page_no&filter_by_product_name=$filter_product_name&filter_by_status=$filter_status&id=$product_id&coupon_discount=$coupon_discount' class='btn btn-warning ms-2'>Edit</a>";
										
										if($row1['status'] == $active_status){
											$user_status = "Active";
										}
										else{
											$user_status = "Deactive";
										}
										
										if($row1['is_deleted'] == $not_deleted){
											$user_is_deleted = "Not_Deleted";
										}
										else{
											$user_is_deleted = "Deleted";
										}
										
										if($row1['status'] == $active_status){
											$status_url = "$base_url/list.php?page_num=$page_no&filter_by_product_name=$filter_product_name&filter_by_status=$filter_status&id=$product_id&status=$deactive_status&coupon_discount=$coupon_discount";
											$status_button_name = "Mark Deactive";
											$status_button_style = "width:150px;";
											$status_button_class = "btn-outline-danger ms-2";
											$status_message = "Are you sure to mark as deactive '" .$row1['product_name']. "' ?";
											$status_model_text_class = "text-danger";
										}
										else{
											$status_url = "$base_url/list.php?page_num=$page_no&filter_by_product_name=$filter_product_name&filter_by_status=$filter_status&id=$product_id&status=$active_status&coupon_discount=$coupon_discount";
											$status_button_name = "Mark Active";
											$status_button_style = "width:150px;";
											$status_button_class = "btn-outline-success ms-2";
											$status_message = "Do you want to mark as active '" .$row1['product_name']. "' ?";
											$status_model_text_class = "text-success";
										}
										
										echo "<input type='button' class='btn $status_button_class' style='$status_button_style' value='$status_button_name' data-bs-toggle='modal' data-bs-target='#confirmation_status$product_id'>";	
										echo "<div class='modal fade' id='confirmation_status$product_id' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' aria-hidden='true'>
											  <div class='modal-dialog modal-dialog-centered modal-lg'>
												<div class='modal-content'>
												  <div class='modal-body h3 $status_model_text_class'>"
													. $status_message .
												  "</div>
												  <div class='modal-footer'>
													<button type='button' class='btn btn-outline-secondary' data-bs-dismiss='modal'>No</button>
													<a href='$status_url' class='btn $status_button_class'>Yes</a>
												  </div>
												</div>
											  </div>
											</div>";
										
										
										
										
										
										if($row1['is_deleted'] == $not_deleted){
											$url = "$base_url/list.php?page_num=$page_no&filter_by_product_name=$filter_product_name&filter_by_status=$filter_status&id=$product_id&is_deleted=$deleted&coupon_discount=$coupon_discount";
											$button_name = "Mark Deleted";
											$button_style = "width:170px;";
											$button_class = "btn-outline-danger ms-2";
											$message = "Are you sure to mark as deleted '" .$row1['product_name']. "' ?";
											$model_text_class = "text-danger";
										}
										else{
											$url = "$base_url/list.php?page_num=$page_no&filter_by_product_name=$filter_product_name&filter_by_status=$filter_status&id=$product_id&is_deleted=$not_deleted&coupon_discount=$coupon_discount";
											$button_name = "Mark Not Deleted";
											$button_style = "width:170px;";
											$button_class = "btn-outline-success ms-2";
											$message = "Do you want to mark as not deleted '" .$row1['product_name']. "' ?";
											$model_text_class = "text-success";
										}
										
										echo "<input type='button' class='btn $button_class' style='$button_style' value='$button_name' data-bs-toggle='modal' data-bs-target='#confirmation_delete$product_id'>";
										echo "<div class='modal fade' id='confirmation_delete$product_id' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' aria-hidden='true'>
											  <div class='modal-dialog modal-dialog-centered modal-lg'>
												<div class='modal-content'>
												  <div class='modal-body h3 $model_text_class'>"
													. $message .
												  "</div>
												  <div class='modal-footer'>
													<button type='button' class='btn btn-outline-secondary' data-bs-dismiss='modal'>No</button>
													<a href='$url' class='btn $button_class'>Yes</a>
												  </div>
												</div>
											  </div>
											</div>";
									?>
								</td>	
							</tr>
							
						<?php
								}
							}
							else{
						?>
								<tr><td colspan=9>No Record Found</td></tr>
						<?php
							}
						  ?>
					  </tbody>
				</table>
				<!-- List view end -->
				
			</div>
		</div>
				
		<?php echo "Showing ". ($offset + 1) . " to " .  ($offset + mysqli_num_rows($list))  . " of $count_records[0] entries"; ?>
		
		<!-- Pagination start -->
        <?php
			echo "<nav>";
			  echo "<ul class='pagination justify-content-center'>";
				$disabled = ($page_no <= 1) ? $disabled = 'disabled' : $disabled = '';
				echo "<li class='page-item $disabled'><a class='page-link' href='$base_url/list.php?page_num=$previous_page&filter_by_product_name=$filter_product_name&filter_by_status=$filter_status&coupon_discount=$coupon_discount'>Previous</a></li>";
					for($i = 1; $i <= $total_no_of_pages; $i++){
						$active = ($page_no == $i) ? $active = 'active' : $active = '';
						echo "<li class='page-item $active'><a class='page-link' name='page_link' href='$base_url/list.php?page_num=$i&filter_by_product_name=$filter_product_name&filter_by_status=$filter_status&coupon_discount=$coupon_discount'>$i</a></li>";
					}
				  $disabled = ($page_no >= $total_no_of_pages) ? $disabled = 'disabled' : $disabled = '';
				echo "<li class='page-item $disabled'><a class='page-link' href='$base_url/list.php?page_num=$next_page&filter_by_product_name=$filter_product_name&filter_by_status=$filter_status&coupon_discount=$coupon_discount'>Next</a></li>";
			  echo "</ul>";
			echo "</nav>";
		?>
        <!-- Pagination end -->
        
        
        
        

	<!-- Bootstrap js cdn link start -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<!-- Bootstrap js cdn link end -->
	
	<!-- External js file link start -->
	<script src="form_process.js"></script>
	<!-- External js file link end -->
</body>
</html>
