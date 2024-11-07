
<div class="card card-xl-0 bg-body aside-cart">
	<div class="card-body container-gutter-xl py-2 py-xl-4 p-xl-4">
		<div class="card-top d-flex align-items-center justify-content-between">
			<h5><?php echo $this->lang->line('your_cart') ?></h5>
			<h5 class="opacity-50 w-auto fw-medium text-nowrap"><?php echo count($cart_details['cart_items']); ?> <?php echo $this->lang->line('items') ?></h5>

			<?php if(count($cart_details['cart_items']) > 0 ){ ?>
				<a class="btn btn-xs d-xl-none btn-secondary text-nowrap px-2"  href="<?php echo base_url() . 'cart'; ?>"><?php echo $this->lang->line('view_cart') ?></a>
			<?php } ?>
		</div>
		<div class="d-none d-xl-inline-block border-top pt-4 mt-4 w-100">
			<?php if (!empty($cart_details['cart_items'])) { 
				$menuids = array(); ?>
				<ul class="item-cart">
					<?php foreach ($cart_details['cart_items'] as $cart_key => $value) { 
						array_push($menuids, $value['menu_id']); ?>
						<li class="d-flex align-items-center justify-content-between">
							<div class="d-flex flex-column overflow-hidden">
								<small class="text-primary fw-medium w-100"><?php echo $value['name']; ?></small>
								<?php if ($value['is_combo_item']) {?>
									<small><?php echo nl2br($value['menu_detail']); ?></small>
								<?php }?>

								<?php if (!empty($value['addons_category_list'])) {?>
									<ul class="small d-flex flex-wrap">
									<?php foreach ($value['addons_category_list'] as $key => $cat_value) { ?>
										<?php /*<li><h6><?php echo $cat_value['addons_category']; ?></h6></li>*/ ?>								
										<?php if (!empty($cat_value['addons_list'])) {?>
											<?php foreach ($cat_value['addons_list'] as $key => $add_value) { ?>
												<li><strong class="fw-medium"><?php echo $add_value['add_ons_name']; ?> : </strong><?php echo currency_symboldisplay(number_format($add_value['add_ons_price'],2),$currency_symbol->currency_symbol); ?></li>
											<?php }?>
										<?php } ?>
									<?php }?>
									</ul>
								<?php } ?>
								
								<small class="fw-bold text-secondary"><?php echo currency_symboldisplay(number_format($value['totalPrice'],2),$currency_symbol->currency_symbol); ?></small>
							</div>
							<div class="number">
								<span class="icon minus" id="minusQuantity" onclick="customItemCount(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'minus',<?php echo $cart_key; ?>)"><img src="<?php echo base_url();?>assets/front/images/icon-minus.svg" alt=""></span>
								<input type="text" class="QtyNumberval" maxlength="3" value="<?php echo $value['quantity']; ?>" onfocusout="EditcustomItemCount(this.value,<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,<?php echo $cart_key; ?>)" />
								<span class="icon plus" id="plusQuantity" onclick="customItemCount(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'plus',<?php echo $cart_key; ?>)"><img src="<?php echo base_url();?>assets/front/images/icon-plus.svg" alt=""></span>
							</div>
						</li>
					<?php } ?>
				</ul>

				<?php //get System Option Data
		            $this->db->select('OptionValue');
		            $min_order_amount = $this->db->get_where('system_option',array('OptionSlug'=>'min_order_amount'))->first_row();
		            $min_order_amount = (float) $min_order_amount->OptionValue; ?>

				<div class="d-flex justify-content-between align-items-center py-4">
					<h6 class="w-auto"><?php echo $this->lang->line('sub_total') ?></h6>
					<h6 class="w-auto"><?php echo currency_symboldisplay(number_format($cart_details['cart_total_price'],2),$currency_symbol->currency_symbol); ?></h6>
				</div>
				
				<a href="javascript:void(0);" class="btn w-100 btn-primary" onclick="checkResStat();"><?php echo $this->lang->line('continue') ?></a>
				<div class="res_closed_err error" style="display: none; color: red;"></div>

				<div class="alert alert-sm alert-primary" style="<?php echo (!in_array('Delivery', $order_mode))?'display: none;':(($cart_details['cart_total_price'] >= $min_order_amount)?'display: none;':'display: block'); ?>">
					<?php $min_order_txt = sprintf($this->lang->line('min_order_msg'),$min_order_amount); ?>
					<?php echo $min_order_txt; ?>
				</div>
			<?php } 
			else { ?>
				<div class="text-center">
					<figure class="mb-4">											
						<img src="<?php echo base_url();?>assets/front/images/image-cart.png">
					</figure>
					<h6><?php echo $this->lang->line('cart_empty') ?></h6>
					<p><?php echo $this->lang->line('add_some_dishes') ?></p>


					<div class="alert alert-sm alert-primary" style="<?php echo (!in_array('Delivery', $order_mode))?'display: none;':'display: block'; ?>">

						<?php 
						$this->db->select('OptionValue');
			            $min_order_amount = $this->db->get_where('system_option',array('OptionSlug'=>'min_order_amount'))->first_row();
			            $min_order_amount = (float) $min_order_amount->OptionValue; 
						$min_order_txt = sprintf($this->lang->line('min_order_msg'),$min_order_amount); ?>
						<?php echo $min_order_txt; ?>
					</div>
				</div>		
			<?php } ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	var count = '<?php echo count($cart_details['cart_items']); ?>'; 
	$('#cart_count').html(count);
	if(count != '0'){
		$('body').addClass("cart_bottom");
		//$("#your_cart").addClass("cart_bottom");
	} else {
		$('body').addClass("cart_bottom");
		//$("#your_cart").removeClass("cart_bottom");
	}
	$('input.QtyNumberval').on('input', function() {		
	    this.value = this.value.replace(/[^0-9]/g,'').replace(/(\..*)\./g, '$1');
	});
	//check restaurant : closed/offline/deactive
	function checkResStat() {
		var restaurant_id = '<?php echo $cart_restaurant ?>';
		var menu_ids = <?php echo json_encode($menuids); ?>;
		var is_scheduling_allowed = <?php echo ($allow_scheduled_delivery == '1') ? 1 : 0; ?>;
		jQuery.ajax({
	        type : "POST",
	        dataType : "json",
	        url : BASEURL+'cart/checkResStat',
	        data : {'restaurant_id':restaurant_id, 'menu_ids':menu_ids, 'is_scheduling_allowed':is_scheduling_allowed},
	        beforeSend: function(){
	            $('#quotes-main-loader').show();
	        },
	        success: function(response) {
	            $('#quotes-main-loader').hide();
	            if(response.status == 'res_unavailable') {
	            	$('.continue_btn').attr("href", 'javascript:void(0)');
	            	var err_box = bootbox.alert({
						message: response.show_message,
						buttons: {
							ok: {
								label: response.oktxt,
							}
						}
					});
					setTimeout(function() {
						err_box.modal('hide');
					}, 10000);
	            	//$('.res_closed_err').text(response.show_message);
	            	//$('.res_closed_err').css("display", "block");
	            	return false;
	            } else {
	            	$('.continue_btn').attr("href", BASEURL+'checkout');
	            	//$('.res_closed_err').css("display", "none");
	            	window.location.href = BASEURL+'checkout';
	            	return true;
	            }
	        },
	        error: function(XMLHttpRequest, textStatus, errorThrown) {
	            alert(errorThrown);
	        }
	    });
	}

	/*--- Svg Inline ---*/
	$('.icon img').inlineSvg();
</script>
