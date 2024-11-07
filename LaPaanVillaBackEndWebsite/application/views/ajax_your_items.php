<?php if (!empty($cart_details['cart_items'])) { ?>
	<div class="card-body container-gutter-xl px-xl-4 py-0">
		<a class="h5 py-4 d-flex align-items-center justify-content-between" href="javascript:void(0)" data-toggle="collapse" data-target="#collapseOne"><?php echo $this->lang->line('your_items') ?>
			<i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-accordion.svg" alt=""></i>
		</a>
		
	    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExampleOne">
	    	<div class="accordion-body border-top py-4">
	    		<div class="table-responsive table-custom table-cart w-100 pb-1">
	                <table class="w-100">
						<tbody>
							<?php if (!empty($cart_details['cart_items'])) { ?>
								<input type="hidden" name="total_cart_items" id="total_cart_items" value="<?php echo count($cart_details['cart_items']); ?>">
								<?php foreach ($cart_details['cart_items'] as $cart_key => $value) { ?>
									<tr>
										<?php /* ?><td class="item-img-main"><div><i class="iicon-icon-15 <?php echo ($value['is_veg'] == 1)?'veg':'non-veg'; ?>"></i></div></td><?php */ ?>
										<td class="item-name">
											<h6><?php echo $value['name']; ?></h6>
											<?php if ($value['is_combo_item']) {?>
												<small><?php echo nl2br($value['menu_detail']); ?></small>
											<?php }?>
											<?php if (!empty($value['addons_category_list'])) {?>
												<ul class="small d-flex flex-wrap">
												<?php foreach ($value['addons_category_list'] as $key => $cat_value) { ?>
													<?php /*<li><h6><?php echo $cat_value['addons_category']; ?></h6></li>*/ ?>
													<?php if (!empty($cat_value['addons_list'])) {
														foreach ($cat_value['addons_list'] as $key => $add_value) { ?>
															<li>><strong class="fw-medium"><?php echo $add_value['add_ons_name']; ?> : </strong> <?php echo currency_symboldisplay(number_format($add_value['add_ons_price'],2),$currency_symbol->currency_symbol); ?></li>
														<?php }
													} ?>
												<?php }
											} ?>
										</td>
										<td><strong class="text-secondary fw-medium"><?php echo currency_symboldisplay(number_format($value['totalPrice'],2),$currency_symbol->currency_symbol); ?></strong></td>
										<td>
											<div class="number">
												<span class="icon minus" id="minusQuantity" onclick="customCheckoutItemCount(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'minus',<?php echo $cart_key; ?>)"><img src="<?php echo base_url();?>assets/front/images/icon-minus.svg" alt=""></span>
												<input type="text" class="QtyNumberval" maxlength="3" name="item_count_check" id="item_count_check_<?php echo $cart_key; ?>" value="<?php echo $value['quantity']; ?>" onfocusout="EditCheckoutItemCount(this.value,<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,<?php echo $cart_key; ?>)" />
												<span class="icon plus" id="plusQuantity" onclick="customCheckoutItemCount(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'plus',<?php echo $cart_key; ?>)"><img src="<?php echo base_url();?>assets/front/images/icon-plus.svg" alt=""></span>
											</div>
											</div>
										</td>
										<td>
											<div class="item-comment-input"><input type="text" name="item_comment_<?php echo $value['menu_id']; ?>" id="item_comment_<?php echo $value['menu_id'].'_'.$cart_key; ?>" value="<?php echo $value['comment'];?>" placeholder="<?php echo $this->lang->line('add_item_comment'); ?>" class="form-control form-control-xs" onblur="customCheckoutItemCount(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'updatecomment',<?php echo $cart_key; ?>)" maxlength="250"></div>
										</td>
										<td><a href="javascript:void(0)" class="icon icon-delete text-danger" alt="<?php echo $this->lang->line('delete'); ?>" title="<?php echo $this->lang->line('remove_item_txt'); ?>" onclick="customCheckoutItemCount(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'remove',<?php echo $cart_key; ?>, '<?php echo $this->lang->line('delete_module'); ?>', '<?php echo $this->lang->line('ok'); ?>' , '<?php echo $this->lang->line('cancel'); ?>' )"><img src="<?php echo base_url();?>assets/front/images/icon-delete.svg" alt=""></a></td>
									</tr>
								<?php } 
							} 
							else
							{ ?>
								<div class="screen-blank text-center">
									<figure class="mb-4">
										<img src="<?php echo base_url();?>assets/front/images/image-cart.png">
									</figure>
									<h6><?php echo $this->lang->line('cart_empty') ?></h6>
									<p><?php echo $this->lang->line('add_some_dishes') ?></p>
								</div>	
							<?php }?>
						</tbody>
					</table>
				</div>
				<?php if(!empty($cart_details['cart_items']) && $current_page=='Checkout' && !empty($restaurant_data->restaurant_slug) && $restaurant_data->status == 1 && $restaurant_data->enable_hours == 1 && $restaurant_data->timings['off'] == "open" && $restaurant_data->timings['closing'] == "Open"){ ?>
					<div class="d-flex justify-content-end mt-3 w-100">
						<a class="btn btn-sm btn-secondary" href="<?php echo base_url().'restaurant/restaurant-detail/'.$restaurant_data->restaurant_slug;?>"><?php echo $this->lang->line('want_to_add_more_items') ?></a>
					</div>
				<?php } ?>
	        </div>
	    </div>
	</div>
<?php } else { ?>
	<div class="card-body px-4">
	    <a class="h5 py-4" href="javascript:void(0)" data-toggle="collapse" data-target="#collapseOne"><?php echo $this->lang->line('your_items') ?></a>
	    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExampleOne">
	    	<div class="accordion-body border-top py-4">
	    		<div class="screen-blank text-center">
					<figure class="mb-4">
						<img src="<?php echo base_url();?>assets/front/images/image-cart.png">
					</figure>
					<h6><?php echo $this->lang->line('cart_empty') ?></h6>
					<p><?php echo $this->lang->line('add_some_dishes') ?></p>
				</div>
	    	</div>
	    </div>
	</div>
<?php } ?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		var count = '<?php echo count($cart_details['cart_items']); ?>'; 
		$('#cart_count').html(count);
		$('input.QtyNumberval').on('input', function() {		
		    this.value = this.value.replace(/[^0-9]/g,'').replace(/(\..*)\./g, '$1');
		});

		/*--- Svg Inline ---*/
		$('.icon img').inlineSvg();
	});
</script>