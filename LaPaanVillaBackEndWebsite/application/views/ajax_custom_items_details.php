<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
	<div class="modal-content">
		<a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close"><img src="<?php echo base_url();?>assets/front/images/icon-close.svg" alt=""></a>
		<div class="row g-0">
			<div class="col-xl-6 horizontal-image">
				<figure class="picture h-100">
		    		<img src="<?php echo (file_exists(FCPATH.'uploads/'.$result[0]['items'][0]['image']) &&  $result[0]['items'][0]['image']!='')?image_url.$result[0]['items'][0]['image']:default_icon_img; ?>">
		    	</figure>
			</div>
			<div class="col-xl-6  p-4 p-xl-8">
				<form id="custom_items_form1">
					<!-- <h4 class="modal-title"><?php echo $this->lang->line('menu_details') ?></h4>
					<h5 id="product_title"><?php echo ($result[0])?$result[0]['items'][0]['name']:''; ?></h5> -->

					<h2 class="title pb-2 mb-6"><?php echo ($result[0])?$result[0]['items'][0]['name']:$this->lang->line('menu_details'); ?></h2>
					<p id="product_title"></p>

					<div class="mb-5 mb-md-6">
						<label class="text-secondary mb-1 w-100"><?php echo $this->lang->line('description') ?></label>
						<p class="small" id="product_description1"><?php echo $result[0]['items'][0]['menu_detail']; ?></p>
					</div>
	      			
	      			<?php /* ?><div style="font-weight:600;"><?php echo $this->lang->line('ingredients') ?></div><br><div><?php echo $result[0]['items'][0]['ingredients']; ?></div><br><?php */ ?>
		      		
		      		<input type="hidden" name="restaurant_id" id="restaurant_id" value="<?php echo $result[0]['items'][0]['restaurant_id']; ?>">
		      		<input type="hidden" name="is_closed" id="is_closed" value="<?php echo $is_closed; ?>">
		      		<input type="hidden" name="is_addon" id="is_addon" value="<?php echo "addon"; ?>">
		      		<input type="hidden" name="user_id" id="user_id" value="<?php echo ($this->session->userdata('UserID'))?$this->session->userdata('UserID'):''; ?>">

		      		<!-- <div class="item-price-label">
      					<span><?php echo $this->lang->line('item') ?></span>
      					<span><?php echo $this->lang->line('price') ?></span>
      				</div> -->

      				<div class="d-inline-block w-100 mb-2">
			      		<?php if (!empty($result[0]['items'][0]['addons_category_list'])) {
			      			foreach ($result[0]['items'][0]['addons_category_list'] as $key => $value) { ?>
			      				<?php if(!empty($value['addons_category_id'])){ ?>
			      				<?php $select_note = ($value['is_multiple'] == 1 && !is_null($value['display_limit'])) ? ' ('.$this->lang->line('select_any').' '.$value['display_limit'].')' : ''; ?>
			      				<div class="box-item border px-4 pt-5 pb-4 mb-3" <?php echo ($value['is_multiple'] == 1 && !is_null($value['display_limit'])) ? 'data-max-selection ="'.$value['display_limit'].'"' : ''; ?>>

			      					<label><?php echo $value['addons_category']; ?> <?php if($value['mandatory'] == '1'){ ?><span style="color: red;">*</span><?php } ?><span><?php echo $select_note; ?></span></label>

						      		<?php if (!empty($value['addons_list'])) {
						      			foreach ($value['addons_list'] as $key => $addvalue) { ?>
						      				<div class="d-flex align-items-start justify-content-between mb-1">
									      		<div class="form-check">
								      				<?php if ($value['is_multiple'] == 1) { ?>
								      					<input type="checkbox" class="form-check-input check_addons1" name="<?php echo $value['addons_category'].'-'.$key; ?>" id="<?php echo $addvalue['add_ons_name'].'-'.$key; ?>" value="1" onchange="getaddonsItemPrice(this.id,'<?php echo $addvalue['add_ons_price']; ?>','<?php echo $value['is_multiple']; ?>',<?php echo $result[0]['items'][0]['menu_id']; ?>)" amount1="<?php echo $addvalue['add_ons_price']; ?>" add_ons_id="<?php echo $addvalue['add_ons_id']; ?>" addons_category_id="<?php echo $value['addons_category_id']; ?>" add_ons_name="<?php echo $addvalue['add_ons_name']; ?>" addonValue='<?php echo json_encode($addvalue); ?>' addons_category="<?php echo $value['addons_category']; ?>"  addons_category_id1="<?php echo $value['addons_category_id']; ?>" >

								      					<label for="<?php echo $addvalue['add_ons_name'].'-'.$key; ?>" class="form-check-label" id=""><?php echo $addvalue['add_ons_name']; ?></label>
								      				<?php } 
								      				else
							      					{ ?>
							      						<input type="radio" class="form-check-input radio_addons1" name="<?php echo $value['addons_category']; ?>" id="<?php echo $addvalue['add_ons_name'].'-'.$key; ?>" value="1" onchange="getaddonsItemPrice(this.id,'<?php echo $addvalue['add_ons_price']; ?>','<?php echo $value['is_multiple']; ?>',<?php echo $result[0]['items'][0]['menu_id']; ?>)" amount1="<?php echo $addvalue['add_ons_price']; ?>" add_ons_id="<?php echo $addvalue['add_ons_id']; ?>" addons_category_id="<?php echo $value['addons_category_id']; ?>" add_ons_name="<?php echo $addvalue['add_ons_name']; ?>" addonValue='<?php echo json_encode($addvalue); ?>' addons_category="<?php echo $value['addons_category']; ?>" addons_category_id1="<?php echo $value['addons_category_id']; ?>" >

							      						<label for="<?php echo $addvalue['add_ons_name'].'-'.$key; ?>" class="form-check-label" id=""><?php echo $addvalue['add_ons_name']; ?></label>
							      					<?php } ?>
									      		</div>
									      		<small><?php echo $currency_symbol->currency_symbol; ?><?php echo $addvalue['add_ons_price']; ?></small>
						      				</div>
						      			<?php }
						      		} ?>
					      		</div>
			      			<?php }	}
			      		} ?>
      				</div>
			      	<div class="bg-light d-flex flex-wrap flex-sm-nowrap align-items-center py-2 px-4">
			      		<?php 
						$priceval = $result[0]['items'][0]['price'];
						if($result[0]['items'][0]['offer_price']>0){ 
							$priceval = $result[0]['items'][0]['offer_price'];	
						} ?>

						<div class="d-flex justify-content-between align-items-center w-100">
			      			<h6><?php echo $this->lang->line('total') ?></h6>
			      			<input type="hidden" name="subTotal_for_cal" id="subTotal_for_cal" value="<?php if($result[0]['items'][0]['offer_price']>0){  echo ($result[0]['items'][0]['offer_price'])?$result[0]['items'][0]['offer_price']:0; } else{  echo ($result[0]['items'][0]['price'])?$result[0]['items'][0]['price']:0; } ?>">
							<input type="hidden" name="subTotal1" id="subTotal1" value="0">

							<div class="mx-sm-4">
								<?php if($result[0]['items'][0]['offer_price']>0){ ?>
									<strong class="text-secondary <?php if($result[0]['items'][0]['offer_price']>0){ ?> text-decoration-line-through <?php } ?>" id="price"><?php echo $currency_symbol->currency_symbol; ?><?php echo $result[0]['items'][0]['price'];  ?></strong>

									<strong class="text-secondary price"><?php echo $currency_symbol->currency_symbol; ?><span id="totalPrice1"><?php echo $result[0]['items'][0]['offer_price'];  ?></span></strong>
								<?php }else{ ?>
									<strong class="text-secondary price"><?php echo $currency_symbol->currency_symbol; ?><span id="totalPrice1"><?php echo $result[0]['items'][0]['price'];  ?></span></strong>
								<?php } ?>
							</div>
						</div>


						<div class="add-cart-item mt-2 mt-sm-0">
							<div class="number mw-100">
								<span class="icon minus" id="minusQuantity" onclick="ItemqtyPlusMinus('<?php echo $result[0]['items'][0]['menu_id']; ?>','minus','<?php echo $priceval;?>')"><img src="<?php echo base_url();?>assets/front/images/icon-minus.svg" alt=""></span>
								<input type="text" value="1" id="qtyaddtocart-<?php echo $result[0]['items'][0]['menu_id']; ?>" onblur="pricecalwithqty(this.id,this.value,'yes','yes',<?php echo $priceval;?>);" name="qtyaddtocart-<?php echo $result[0]['items'][0]['menu_id']; ?>" class="QtyNumberval" placeholder="" maxlength="3" />
								<span class="icon plus" id="plusQuantity" onclick="ItemqtyPlusMinus('<?php echo $result[0]['items'][0]['menu_id']; ?>','plus','<?php echo $priceval;?>')"><img src="<?php echo base_url();?>assets/front/images/icon-plus.svg" alt=""></span>
							</div>
						</div>

		      			<?php if($is_closed!='Closed' && $result[0]['items'][0]['restaurant_status'] == 1){ ?>
		      			<?php $mandatory = 0;
		      			$mandatory_arr = array();
						foreach ($result[0]['items'][0]['addons_category_list'] as $key => $value) {
							if($value['mandatory'] == '1') {
					           array_push($mandatory_arr, $value['addons_category_id']);
						    }
						} 
						$mandatory = (!empty($mandatory_arr))?1:0;
						$mandatory_arr = json_encode($mandatory_arr); ?>
			      	</div>

			      	<?php if($result[0]['items'][0]['stock'] == 1 || $result[0]['items'][0]['allow_scheduled_delivery'] == '1') { ?>
      				<button type="button" class="addtocart btn btn-sm btn-primary w-100 mt-2 addtocart-<?php echo $result[0]['items'][0]['menu_id']; ?>" id="addtocart-<?php echo $result[0]['items'][0]['menu_id']; ?>" <?php echo ($is_closed=='Closed')?'disabled':""; ?> onclick="checkaddonsRestaurantinCart('<?php echo $result[0]['items'][0]['menu_id']; ?>','addons',this.id,'<?php echo $is_closed ?>',<?php echo $mandatory; ?>,<?php echo htmlspecialchars(json_encode($mandatory_arr)); ?>,'<?php echo $recipe_page ?>')" <?php echo ($is_closed=='Closed')?'disabled':""; ?> order-for-later="<?php echo ($result[0]['items'][0]['allow_scheduled_delivery'] == '1' && $result[0]['items'][0]['stock'] == 0) ? '1' : '0'; ?>" ><?php echo ($cart_rest == 1) ? $this->lang->line('added') : (($result[0]['items'][0]['allow_scheduled_delivery'] == '1' && $result[0]['items'][0]['stock'] == 0) ? $this->lang->line('order_for_later') : $this->lang->line('add')); ?></button>

      				<?php if($result[0]['items'][0]['allow_scheduled_delivery'] == '1' && $result[0]['items'][0]['stock'] == 0) { ?>
						<small class="error mt-1"><?php echo $this->lang->line('out_stock'); ?></small>
					<?php } ?>

	      			<?php }else{ ?>
	      				<small class="error mt-1"><?php echo $this->lang->line('out_stock'); ?></small>
	      			<?php } } ?>
			      	<?php /*if(!(empty($mandatory))) { ?>
			      		<!-- <small class="error mt-1"><sup>*</sup> <?php echo $this->lang->line('required_field'); ?> </small> -->
			      	<?php }*/ ?>	
      			</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	//get item price
	var totalPrice_addons = 0;
	var radiototalPrice_addons = 0;
	var checktotalPrice_addons = 0;
	/*flow - on check get max selectio value get it's total check box checked compare if checked greater display error*/
	$('.form-check .check_addons1').change(function() {
		if(this.checked) {
			var selection_length = $(this).closest('.max-selection').attr('data-max-selection');
			if(selection_length != '' && $.isNumeric(selection_length) && selection_length > 0){
				var checkbox_count = $(this).closest('.max-selection').find('.check_addons1').filter(':checked').length;
				if(checkbox_count > selection_length){
					$(this).prop("checked", false);
					var category_name = $(this).attr('addons_category');
					if(SELECTED_LANG == 'en') {
						bootbox.alert({
							message: "Please select any "+ selection_length + " from " + category_name + ".",
							buttons: {
								ok: {
									label: "Ok",
								}
							}
						});
					} else if(SELECTED_LANG == 'fr') {
						bootbox.alert({
							message: "Veuillez sélectionner n'importe quel "+selection_length+" from " +category_name +".",
							buttons: {
								ok: {
									label: "D'accord",
								}
							}
						});
					} else {
						bootbox.alert({
							message: "الرجاء تحديد أي"+selection_length+" from "+category_name+".",
							buttons: {
								ok: {
									label: "نعم",
								}
							}
						});
					}
				}
			}
		}
	});

	$('.icon img').inlineSvg();
</script>