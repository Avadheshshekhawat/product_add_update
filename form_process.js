	$(document).ready(function(){
		
		//Global constant declaration start
		const BASE_URL = "http://learning.dev2.gipl.inet/avadhesh_pal/product_add_update";
		const PRODUCT_ADD_UPDATE = "product_add_update";
		const COUPON_CODE_CHECK = "coupon_code";
		//Global constant declaration end
		
		
		//Product add / update script start
		$("#submit_btn").click(function(e){
			e.preventDefault();
			var form_data = $("#product_form").serialize();
			
			
			//Form validation start
			var error_flag = [];
			var product_name = $("#product_name").val().trim();
			var product_description = $("#product_description").val().trim();
			var product_mrp = $("#product_mrp").val().trim();
			var product_selling_price = $("#product_selling_price").val().trim();
			
			
				if(product_name == ''){
					$("#product_name_error").text("Product name is required");
					error_flag.push(1);
				}
				else{
					if(!/^[A-Za-z0-9 _.-]+$/.test(product_name)){
						$("#product_name_error").text("Invalid product name");
						error_flag.push(1);
					}
					else{
						if(/^[0-9]*$/.test(product_name)){
							$("#product_name_error").text("Invalid product name");
							error_flag.push(1);
						}
						else{
							$("#product_name_error").text("");
						}
					}
				}
				
				
				if(product_description == ''){
					$("#product_description_error").text("Product description is required");
					error_flag.push(1);
				}
				else{
					if(/^[0-9]*$/.test(product_description)){
						$("#product_description_error").text("Invalid product description");
						error_flag.push(1);
					}
					else{
						$("#product_description_error").text("");
					}
				}
				
				
				
				if(product_mrp == ''){
					$("#product_mrp_error").text("Product mrp is required");
					error_flag.push(1);
				}
				else{
					if(!/^[+]?\d+(\.\d+)?$/.test(product_mrp)){
						$("#product_mrp_error").text("invalid product mrp");
						error_flag.push(1);
					}
					else{
						$("#product_mrp_error").text("");
					}
				}
				
				
				if(product_selling_price == ''){
					$("#product_selling_price_error").text("Product selling price is required");
					error_flag.push(1);
				}
				else{
					if(!/^[+]?\d+(\.\d+)?$/.test(product_selling_price)){
						$("#product_selling_price_error").text("invalid product selling price");
						error_flag.push(1);
					}
					else{
						if(product_selling_price > product_mrp){
							$("#product_selling_price_error").text("Product selling price should not be more than mrp");
							error_flag.push(1);
						}
						else{
							$("#product_selling_price_error").text("");
						}
					}
				}
			//Form validation end
			
			
			//Ajax request start
				if(error_flag.length < 1){
					
					$.ajax({
						url: BASE_URL + "/product_add_update_request.php",
						type: "POST",
						data: {form_data: form_data, form_action: PRODUCT_ADD_UPDATE},
						contentType: "application/x-www-form-urlencoded",
						dataType: "json",
						beforeSend: function(){
							$("#loder_bg").fadeIn(300);
						},
						success: function(response){
							if(response.status == "Error"){
												
									if(response.error.hasOwnProperty("product_name_error")){
										$("#product_name_error").text(response.error.product_name_error);
									}
									else{
										$("#product_name_error").text("");
									}
												
									if(response.error.hasOwnProperty("product_description_error")){
										$("#product_description_error").text(response.error.product_description_error);
									}
									else{
										$("#product_description_error").text("");
									}
									
									if(response.error.hasOwnProperty("product_mrp_error")){
										$("#product_mrp_error").text(response.error.product_mrp_error);
									}
									else{
										$("#product_mrp_error").text("");
									}
									
									if(response.error.hasOwnProperty("product_selling_price_error")){
										$("#product_selling_price_error").text(response.error.product_selling_price_error);
									}
									else{
										$("#product_selling_price_error").text("");
									}			
							}
							else{
									Swal.fire({
										position: 'top-end',
										icon: 'success',
										title: response.message,
										showConfirmButton: false,
										timer: 1500
									}).then(function(){
										window.location.href = "list.php?page_num=1";
									});
							}
						},
						error: function(xhr){
							Swal.fire({
								title: 'Error!',
								text: "Error: " + xhr.status + " " + xhr.statusText,
								icon: 'error',
								confirmButtonText: 'Ok'
							})
						},
						complete: function(){
							$("#loder_bg").fadeOut(300);
						}
					});
					
				}
			//Ajax request end
		});
		//Product add / update script end
		
		
		
		
		
		
		
		
		
		//Coupon apply script start
		$("#apply_coupon_btn").click(function(e){
			e.preventDefault();
			
			var coupon_code = $("#coupon_code").val().trim();
			
			if(coupon_code == ''){
				$("#coupon_error").text("Please enter coupon code first");
			}
			else{
				$("#coupon_error").text("");
					
					
					//Ajax request start
					$.ajax({
						url: BASE_URL + "/global_function.php",
						type: "POST",
						data: {coupon_code: coupon_code, action: COUPON_CODE_CHECK},
						contentType: "application/x-www-form-urlencoded",
						dataType: "json",
						beforeSend: function(){
							$("#loder_bg").fadeIn(300);
						},
						success: function(response){
							console.log(response);
							if(response.status == "Error"){
								$("#coupon_error").text(response.message);
							}
							else{
								$("#coupon_error").text("");
								window.location.href = "view_cart.php?coupon_discount="+ response.coupon_discount;
							}
						}
					});
					//Ajax request end
					
					
			}
			
		});
		//Coupon apply script end
		
	});
