<?php
			include_once "db_connection.php";
			include_once "global_constant.php";
			
			$data = array();
			
			if(!$connection){
				$data['connection_status'] = mysqli_connect_error();
			}
	
			
			$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : null;
			$where_condition = "";
			$varable_name = "";
			$table_name = "";
			
			switch($action){
				case COUPON_CODE_CHECK:
					coupon_code_find();
					break;
				
				default:
					$data['default_case'] = "Something went wrong !";
			}
			
			
			
			/*
			 * Name: coupon_code_find()
			 * Description: Used to check wheter coupon code exist or not
			 * Param:
			 * Return:
			 * Created on: 03-May-2023
			 * Created by: Avadhesh Shekhawat
			 */
			 function coupon_code_find(){
				 global $connection;
				 global $where_condition;
				 global $varable_name;
				 global $table_name;
				 
				 $coupon_code = $_REQUEST['coupon_code'];
				 
				 if(!empty($coupon_code)){
					 $where_condition .= "and coupon_code = '$coupon_code'";
					 $varable_name = "coupon code";
					 $table_name = "product_coupon_table_gipl";
				 }
				 
					data_existence_check($where_condition, $table_name, $varable_name);
			 }
			 
			 
			 
			 
			 
			 
			 
			 
			 
			 
			 
			 /*
			 * Name: data_existence_check()
			 * Description: Used to check wheter data exist into database table or not
			 * Param:
			 * Return:
			 * Created on: 03-May-2023
			 * Created by: Avadhesh Shekhawat
			 */
			 function data_existence_check(){
				 global $connection;
				 global $data;
				 global $where_condition;
				 global $varable_name;
				 global $table_name;
				 
				 
					$data_existence_check_query = "SELECT * FROM $table_name WHERE 1 $where_condition";
					$result = mysqli_query($connection, $data_existence_check_query);
					$count = mysqli_num_rows($result);
					$fetch_data = mysqli_fetch_assoc($result);
					$data['coupon_discount'] =  $fetch_data['coupon_discount'];
					
					
					if($count == 1){
						$data['status'] = "Success";
						$data['message'] = "This $varable_name is exist you can use this coupon code";
						$data['count'] = $count;
					}
					if($count < 1){
						$data['status'] = "Error";
						$data['message'] = "Invalid $varable_name please try another coupon code";
						$data['count'] = $count;
					}
					
					return $data;
			 }
			 
			
			echo json_encode($data);
?>
