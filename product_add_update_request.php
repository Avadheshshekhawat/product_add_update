	<?php
			include_once "db_connection.php";
			include_once "global_constant.php";
			
			$data = array();
							
			if(!$connection){
				$data['connection_status'] = mysqli_connect_error();
			}
			else{
				$data['connection_status'] = "Database connected";
			}
			
			
			
			$base_url = (defined('BASE_URL')) ? BASE_URL : null;
			$product_id = (isset($_GET['id'])) ? $_GET['id'] : null;
			$cart_id = (isset($_GET['cart_id'])) ? $_GET['cart_id'] : null;
			$product_name = (isset($_GET['product_name'])) ? $_GET['product_name'] : null;
			$product_mrp = (isset($_GET['product_mrp'])) ? $_GET['product_mrp'] : null;
			$get_form_action = (isset($_REQUEST['form_action'])) ? $_REQUEST['form_action'] : null;
			$get_form_data = (isset($_REQUEST['form_data'])) ? $_REQUEST['form_data'] : null;
			$coupon_discount = isset($_GET['coupon_discount']) ? $_GET['coupon_discount'] : null;
			$form_data = array();
			parse_str($get_form_data, $form_data);
			
			$date = date("Y-m-d") ? date("Y-m-d") : null;
			$column_values = "";
			$column_names = "";
			$table_name = "";
			$column_name_values = "";
			$message = "";
			$where_condition = "";
			$quantity = "";
			
									
			
			
			$fetch_data_query = "SELECT * FROM product_cart_table_gipl WHERE product_id = $product_id";
			$list =  mysqli_query($connection, $fetch_data_query);
			$fetch_data = mysqli_fetch_assoc($list);
			$prodct_id = $fetch_data['product_id'];
			$quantity = $fetch_data['quantity'];
			
			
			
			//Switch case start
			switch($get_form_action){
				case PRODUCT_ADD_UPDATE:
					product_add_update();
					break;
					
				case ADD_TO_CART:
					add_to_cart();
					break;
					
				case DECREASE_QUANTITY:
					decrease_quantity();
					break;
					
				case INCREASE_QUANTITY:
					increase_quantity();
					break;
					
				case REMOVE_PRODUCT:
					remove_product();
					break;
					
				default:
					$data['default_case'] = "Something went wrong";
					break;
			}
			//Switch case end
		
						
						
						
		
		
						
						
						
						/*
						 * Name: product_add_update()
						 * Description: Used to validate product add update form details
						 * Param:
						 * Return:
						 * Created on: 01-May-2023
						 * Created by: Avadhesh Shekhawat
						 */
						 function product_add_update(){
							global $connection;
							global $data;
							global $form_data;
							global $column_values;
							global $column_names;
							global $table_name;
							global $column_name_values;
							global $message;
							global $where_condition;
							global $date;
							
							
							
							$error_flag = array();
							$product_name = (isset($form_data['product_name'])) ? $form_data['product_name'] : null;
							$product_description = (isset($form_data['product_description'])) ? $form_data['product_description'] : null;
							$product_mrp = (isset($form_data['product_mrp'])) ? $form_data['product_mrp'] : null;
							$product_selling_price = (isset($form_data['product_selling_price'])) ? $form_data['product_selling_price'] : null;
							$product_id = (isset($form_data['product_id'])) ? $form_data['product_id'] : null;
						
							
							
								if(empty($product_name)){
									$error_flag['product_name_error'] = ERROR_MSG['product_name1'];
								}
								else{
									if(!preg_match("/^[A-Za-z0-9 _.-]+$/", $product_name)){
										$error_flag['product_name_error'] = ERROR_MSG['product_name2'];
									}
									else{
										if(preg_match("/^[0-9]*$/", $product_name)){
											$error_flag['product_name_error'] = ERROR_MSG['product_name2'];
										}
										else{
											$product_nam = strtolower($product_name);
										}
									}
								}
								
								
								if(empty($product_description)){
									$error_flag['product_description_error'] = ERROR_MSG['product_description1'];
								}
								else{
									if(preg_match('/^[0-9]*$/', $product_description)){
										$error_flag['product_description_error'] = ERROR_MSG['product_description2'];
									}
									else{
										$product_descripton = strtolower($product_description);
									}
								}
								
								
								if(empty($product_mrp)){
									$error_flag['product_mrp_error'] = ERROR_MSG['product_mrp1'];
								}
								else{
									if(!preg_match('/^[+]?\d+(\.\d+)?$/', $product_mrp)){
										$error_flag['product_mrp_error'] = ERROR_MSG['product_mrp2'];
									}
									else{
										$prodct_mrp = $product_mrp;
									}
								}
								
								
								if(empty($product_selling_price)){
									$error_flag['product_selling_price_error'] = ERROR_MSG['product_selling_price1'];
								}
								else{
									if(!preg_match('/^[+]?\d+(\.\d+)?$/', $product_selling_price)){
										$error_flag['product_selling_price_error'] = ERROR_MSG['product_selling_price2'];
									}
									else{
										if($product_selling_price > $product_mrp){
											$error_flag['product_selling_price_error'] = ERROR_MSG['product_selling_price3'];
										}
										else{
											$prodct_selling_price = $product_selling_price;
										}
									}
								}
								
								
								
										if(empty($error_flag)){
											$data['status'] = "Success";
											$data['error'] = $error_flag;
											
											
											if(empty($product_id)){
													$column_names = "product_name, product_description, product_mrp, product_sell_price, status, is_deleted, created";
													$column_values = "'$product_nam', '$product_descripton', '$prodct_mrp', '$prodct_selling_price', '$active_status', '$not_deleted', '$date'";
													$table_name = "product_table_gipl";
													$message = "Product added successfully";
													insert_product_function($column_names, $column_values, $table_name, $message);
											}
											else{
												$column_name_values = "product_name = '$product_nam', product_description = '$product_descripton', product_mrp = '$prodct_mrp', product_sell_price = '$prodct_selling_price', updated = '$date'";
												$table_name = "product_table_gipl";
												$where_condition = "product_id = '$product_id'";
												$message = "Product updated successfully";
												update_product_function($column_name_values, $table_name, $where_condition, $message);
											}
											
										}
										else{
											$data['status'] = "Error";
											$data['error'] = $error_flag;
										}
								
						 }
						 
						 
						 
						 
						 
						 /*
						 * Name: add_to_cart()
						 * Description: Used to add product to car
						 * Param:
						 * Return:
						 * Created on: 01-May-2023
						 * Created by: Avadhesh Shekhawat
						 */
						 function add_to_cart(){
							global $connection;
							global $data;
							global $column_values;
							global $column_names;
							global $table_name;
							global $column_name_values;
							global $message;
							global $where_condition;
							global $date;
							global $product_id;
							global $product_mrp;
							global $product_name;
							global $cart_id;
							global $prodct_id;
							global $quantity;
									
									
									if($product_id != $prodct_id){
											$column_names = "product_name, product_id, quantity, product_mrp, total_price , status, is_deleted, created";
											$column_values = "'$product_name', '$product_id', '1', '$product_mrp', quantity * product_mrp , '$active_status', '$not_deleted', '$date'";
											$table_name = "product_cart_table_gipl";
											insert_product_function($column_names, $column_values, $table_name, $message);
									}
									else{
										if($quantity == 0){
											$column_name_values = "product_name = '$product_name', product_id = '$product_id', quantity = '1', product_mrp = '$product_mrp', total_price = quantity * product_mrp, status = '$active_status', is_deleted = '$not_deleted', updated = '$date'";
											$table_name = "product_cart_table_gipl";
											$where_condition = "cart_id = '$cart_id'";
											update_product_function($column_name_values, $table_name, $where_condition, $message);
										}
									}
						 }
						 
						 
						 
						 
						 
						 
						 
						 
						 /*
						 * Name: decrease_quantity()
						 * Description: Used to decrease products quantity into cart
						 * Param:
						 * Return:
						 * Created on: 03-May-2023
						 * Created by: Avadhesh Shekhawat
						 */
						 function decrease_quantity(){
								global $connection;
								global $data;
								global $table_name;
								global $column_name_values;
								global $where_condition;
								global $date;
								global $cart_id;
								global $quantity;
								global $product_id;
								
										if($quantity > 1){
											$column_name_values = "quantity = quantity - 1, total_price = quantity * product_mrp, updated = '$date'";
											$table_name = "product_cart_table_gipl";
											$where_condition = "cart_id = '$cart_id'";
											update_product_function($column_name_values, $table_name, $where_condition, $message);
										}
						 }
						 
						 
						 
						 
						 
						 
						 
						 
						 /*
						 * Name: increase_quantity()
						 * Description: Used to increase products quantity into cart
						 * Param:
						 * Return:
						 * Created on: 03-May-2023
						 * Created by: Avadhesh Shekhawat
						 */
						 function increase_quantity(){
								global $connection;
								global $data;
								global $table_name;
								global $column_name_values;
								global $where_condition;
								global $date;
								global $cart_id;
								
								
											$column_name_values = "quantity = quantity + 1, total_price = quantity * product_mrp, updated = '$date'";
											$table_name = "product_cart_table_gipl";
											$where_condition = "cart_id = '$cart_id'";
											update_product_function($column_name_values, $table_name, $where_condition, $message);
						 }
						 
						 
						 
						 
						 
						 
						 
						 /*
						 * Name: remove_product()
						 * Description: Used to remove product from cart
						 * Param:
						 * Return:
						 * Created on: 03-May-2023
						 * Created by: Avadhesh Shekhawat
						 */
						 function remove_product(){
							    global $connection;
								global $data;
								global $table_name;
								global $column_name_values;
								global $where_condition;
								global $date;
								global $cart_id;
								
											$column_name_values = "quantity = 0, total_price = quantity * product_mrp, updated = '$date'";
											$table_name = "product_cart_table_gipl";
											$where_condition = "cart_id = '$cart_id'";
											update_product_function($column_name_values, $table_name, $where_condition, $message);
						 }
						 
						 
						 
						 
						 
						 /*
						 * Name: insert_product_function()
						 * Description: Used to insert product data into database
						 * Param:
						 * Return:
						 * Created on: 01-May-2023
						 * Created by: Avadhesh Shekhawat
						 */
						function insert_product_function($column_names, $column_values, $table_name, $message){
							global $connection;
							global $data;
							global $column_names;
							global $column_values;
							global $table_name;
							global $message;
							
							
											
									$insert_data = "INSERT INTO $table_name ($column_names) VALUES ($column_values)";
									if(mysqli_query($connection, $insert_data)){
										$data['status'] = "Success";
										$data['message'] = $message;
									}
									else{
										$data['status'] = "Error";
										$data['message'] = "Error: " . $insert_data . "<br>" . mysqli_error($connection);
									}
								
									return $data;
						}
						
						
						
						
						
						
						
						
						
						/*
						 * Name: update_product_function()
						 * Description: Used to update product data into database
						 * Param:
						 * Return:
						 * Created on: 01-May-2023
						 * Created by: Avadhesh Shekhawat
						 */
						function update_product_function($column_name_values, $table_name, $where_condition, $message){
							global $connection;
							global $data;
							global $column_name_values;
							global $table_name;
							global $where_condition;
							global $message;
								
									$update_data = "UPDATE $table_name SET $column_name_values WHERE $where_condition";
									if(mysqli_query($connection, $update_data)){
										$data['status'] = "Success";
										$data['message'] = $message;
									}
									else{
										$data['status'] = "Error";
										$data['message'] = "Error: " . $update_data . "<br>" . mysqli_error($connection);
									}
								
									return $data;
						}
						
					
					
						
						echo json_encode($data);
						
						if($get_form_action == ADD_TO_CART){
							header("Location: $base_url/all_active_products.php?coupon_discount=$coupon_discount");
						}
						if($get_form_action == REMOVE_PRODUCT || $get_form_action == DECREASE_QUANTITY || $get_form_action == INCREASE_QUANTITY){
							header("Location: $base_url/view_cart.php?coupon_discount=$coupon_discount");
						}
						exit();
			
	?>
