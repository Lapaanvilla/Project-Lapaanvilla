<?php if (!empty($cart_details['cart_items'])) { ?>
	<div class="col-xl-8">
		<div class="card card-xl-0">
			<div class="card-body container-gutter-xl py-4 p-xl-4">
				<h5 class="border-bottom pb-4 mb-4"><?php echo $this->lang->line('your_items') ?></h5>
				<div class="table-responsive table-custom table-cart">
					<table class="w-100">
						<tbody>
							<?php if (!empty($cart_details['cart_items'])) {
								$menuids = array();
								foreach ($cart_details['cart_items'] as $cart_key => $value) { 
									array_push($menuids, $value['menu_id']); ?>
									<tr>
										<?php /* ?><td class="item-img-main"><div><i class="iicon-icon-15 <?php echo ($value['is_veg'] == 1)?'veg':'non-veg'; ?>"></i></div></td><?php */ ?>

										<td>
											<h6 class="fw-medium"><?php echo $value['name']; ?></h6>
											
											<?php if ($value['is_combo_item']) {?>
												<small><?php echo nl2br($value['menu_detail']); ?></small>
											<?php }?>
											<?php if (!empty($value['addons_category_list'])) {?>
												<ul class="small d-flex flex-wrap">
													<?php foreach ($value['addons_category_list'] as $key => $cat_value) { ?>
														<?php /*<li><h6><?php echo $cat_value['addons_category']; ?></h6></li>*/ ?>
														<?php if (!empty($cat_value['addons_list'])) {
															foreach ($cat_value['addons_list'] as $key => $add_value) { ?>
																<li><strong class="fw-medium"><?php echo $add_value['add_ons_name']; ?> : </strong> <?php echo currency_symboldisplay(number_format($add_value['add_ons_price'],2),$currency_symbol->currency_symbol); ?></li>
															<?php }
														} ?>
													<?php }
												} ?>
											</ul>
										</td>
										<td><strong class="text-secondary fw-medium"><?php echo currency_symboldisplay(number_format($value['totalPrice'],2),$currency_symbol->currency_symbol); ?></strong></td>
										<td>
											<div class="number">
												<span class="icon minus" id="minusQuantity" onclick="customCartItemCount(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'minus',<?php echo $cart_key; ?>)"><img src="<?php echo base_url();?>assets/front/images/icon-minus.svg" alt=""></span>
												<input type="text" class="QtyNumberval" maxlength="3" value="<?php echo $value['quantity']; ?>" onfocusout="EditCartItemCount(this.value,<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,<?php echo $cart_key; ?>)" />
												<span class="icon plus" id="plusQuantity" onclick="customCartItemCount(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'plus',<?php echo $cart_key; ?>)"><img src="<?php echo base_url();?>assets/front/images/icon-plus.svg" alt=""></span>
											</div>
										</td>
										<td colspan="4"><div class="item-comment-input"><input type="text" name="item_comment_<?php echo $value['menu_id']; ?>" id="item_comment_<?php echo $value['menu_id'].'_'.$cart_key; ?>" placeholder="<?php echo $this->lang->line('add_item_comment'); ?>" value="<?php echo $value['comment'];?>" class="form-control form-control-xs" onblur="customCartItemCount(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'updatecomment',<?php echo $cart_key; ?>)" maxlength="250"></div></td>
										<td><a href="javascript:void(0)" class="icon icon-delete text-danger" alt="<?php echo $this->lang->line('delete'); ?>" title="<?php echo $this->lang->line('remove_item_txt'); ?>" onclick="customCartItemCount(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'remove',<?php echo $cart_key; ?>)"><img src="<?php echo base_url();?>assets/front/images/icon-delete.svg" alt=""></a></td>
									</tr>
								<?php } 
							} 
							else { ?>
								<div class="screen-blank text-center">
									<figure class="mb-4">
										<img src="<?php echo base_url();?>assets/front/images/image-cart.svg">
									</figure>
									<h6><?php echo $this->lang->line('cart_empty') ?></h6>
									<p><?php echo $this->lang->line('add_some_dishes') ?></p>
									<a href="<?php echo base_url();?>" class="btn btn-primary"><?php echo $this->lang->line('return_to_home') ?></a>
								</div>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<?php if(!empty($cart_details['cart_items']) && !empty($restaurant_data->restaurant_slug) && $restaurant_data->status == 1 && $restaurant_data->enable_hours == 1 && $restaurant_data->timings['off'] == "open" && $restaurant_data->timings['closing'] == "Open"){ ?>
					<div class="d-flex justify-content-end mt-3 w-100"><a class="btn btn-sm btn-secondary" href="<?php echo base_url().'restaurant/restaurant-detail/'.$restaurant_data->restaurant_slug;?>"><?php echo $this->lang->line('want_to_add_more_items') ?></a></div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php if (!empty($cart_details['cart_items'])) { ?>
		<div class="col-xl-4">
			<div class="card card-xl-0">
				<div class="card-body container-gutter-xl py-4 p-xl-4">
					<h5 class="border-bottom pb-4 mb-4"><?php echo $this->lang->line('order_summary') ?></h5>

					<div class="alert alert-sm alert-secondary mt-0"><?php echo $this->lang->line('order').' '.$this->lang->line('from') ?> : <?php echo $restaurant_name; ?></div>
					
					<div class="table-responsive table-custom small w-100">
						<table class="w-100">
							<tbody>
								<tr>
									<td><?php echo $this->lang->line('no_of_items') ?></td>
									<td class="text-end"><strong><?php echo count($cart_details['cart_items']); ?></strong></td>
								</tr>
								<tr>
									<td><?php echo $this->lang->line('sub_total') ?></td>
									<td class="text-end"><strong><?php echo currency_symboldisplay(number_format($cart_details['cart_total_price'],2),$currency_symbol->currency_symbol); ?></strong></td>
								</tr>
								<?php if($this->cart_model->getDeliveryCharges() > 0) { ?>
									<tr>
										<td><?php echo $this->lang->line('delivery_charges') ?></td>
										<?php $delivery_charges = $this->cart_model->getDeliveryCharges(); ?>
										<td class="text-end"><strong><?php echo currency_symboldisplay(number_format($delivery_charges,2),$currency_symbol->currency_symbol); ?></strong></td>
									</tr>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<td><?php echo $this->lang->line('to_pay') ?></td>
									<?php $to_pay = $cart_details['cart_total_price'] + $delivery_charges; ?>
									<td class="text-end"><strong><?php echo currency_symboldisplay(number_format($to_pay,2),$currency_symbol->currency_symbol); ?></strong></td>
								</tr>
							</tfoot>
						</table>
					</div>
					<div class="d-flex justify-content-end mt-3 w-100">
						<a href="javascript:void(0);" class="btn btn-sm w-100 btn-primary continue_btn" onclick="checkResStat();"><?php echo $this->lang->line('continue') ?></a>
					</div>
					<div class="res_closed_err error" style="display: none;"></div>
				</div>
			</div>
		</div>
	<?php } ?>
<?php } 
else { ?>
	<div class="col-12 screen-blank text-center pb-8 pb-xl-0">
		<figure class="mb-4">
			<img src="<?php echo base_url();?>assets/front/images/image-cart.svg">
		</figure>
		<h6><?php echo $this->lang->line('cart_empty') ?></h6>
		<p><?php echo $this->lang->line('add_some_dishes') ?></p>
		<a href="<?php echo base_url();?>" class="btn btn-primary"><?php echo $this->lang->line('return_to_home') ?></a>
	</div>
<?php } ?>
<script type="text/javascript">
	var count = '<?php echo count($cart_details['cart_items']); ?>'; 
	$('#cart_count').html(count);
	
	$('input.QtyNumberval').on('input', function() {		
	    this.value = this.value.replace(/[^0-9]/g,'').replace(/(\..*)\./g, '$1');
	});
	//check restaurant : closed/offline/deactive
	function checkResStat() {
		var restaurant_id = '<?php echo $cart_restaurant; ?>';
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