<div class="modal-dialog modal-dialog-centered modal-md" role="document">
	<div class="modal-content p-4 p-xl-8">
		<a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close"><img src="<?php echo base_url();?>assets/front/images/icon-close.svg" alt=""></a>

		<form id="custom_items_form">
			<h2 class="title pb-2 mb-8"><?php echo ($result[0])?$result[0]['items'][0]['name']:''; ?></h4>
      		
      		<div class="d-inline-block w-100 mb-2">
      			<input type="hidden" name="restaurant_id" id="restaurant_id" value="<?php echo $result[0]['items'][0]['restaurant_id']; ?>">
      			<input type="hidden" name="user_id" id="user_id" value="<?php echo ($this->session->userdata('UserID'))?$this->session->userdata('UserID'):''; ?>">
      			
      			<!-- <div class="item-price-label">
      				<span><?php echo $this->lang->line('item') ?></span>
      				<span><?php echo $this->lang->line('price') ?></span>
      			</div> -->
	      		
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
						      					<input type="checkbox" class="form-check-input check_addons" name="<?php echo $value['addons_category'].'-'.$key; ?>" id="<?php echo $addvalue['add_ons_name'].'-'.$key; ?>" value="1" onchange="getItemPrice(this.id,'<?php echo $addvalue['add_ons_price']; ?>','<?php echo $value['is_multiple']; ?>',<?php echo $result[0]['items'][0]['menu_id']; ?>)" amount="<?php echo $addvalue['add_ons_price']; ?>" add_ons_id="<?php echo $addvalue['add_ons_id']; ?>" addons_category_id="<?php echo $value['addons_category_id']; ?>" add_ons_name="<?php echo $addvalue['add_ons_name']; ?>" addonValue='<?php echo json_encode($addvalue); ?>' addons_category="<?php echo $value['addons_category']; ?>">

						      					<label for="<?php echo $addvalue['add_ons_name'].'-'.$key; ?>" class="form-check-label" id=""><?php echo $addvalue['add_ons_name']; ?></label>
						      				<?php } 
						      				else
					      					{ ?>
					      						<input type="radio" class="form-check-input radio_addons" name="<?php echo $value['addons_category']; ?>" id="<?php echo $addvalue['add_ons_name'].'-'.$key; ?>" value="1" onchange="getItemPrice(this.id,'<?php echo $addvalue['add_ons_price']; ?>','<?php echo $value['is_multiple']; ?>',<?php echo $result[0]['items'][0]['menu_id']; ?>)" amount="<?php echo $addvalue['add_ons_price']; ?>" add_ons_id="<?php echo $addvalue['add_ons_id']; ?>" addons_category_id="<?php echo $value['addons_category_id']; ?>" add_ons_name="<?php echo $addvalue['add_ons_name']; ?>" addonValue='<?php echo json_encode($addvalue); ?>' addons_category="<?php echo $value['addons_category']; ?>">

					      						<label for="<?php echo $addvalue['add_ons_name'].'-'.$key; ?>" class="form-check-label" id=""><?php echo $addvalue['add_ons_name']; ?></label>
					      					<?php } ?>
						      			</div>
						      			<small><?php echo currency_symboldisplay($addvalue['add_ons_price'],$currency_symbol->currency_symbol); ?></small>
						      		</div>
				      			<?php }
				      		} ?>
			      		</div>
	      			<?php }	}
	      		} ?>
      		</div>
	      	<div class="bg-light d-flex flex-wrap flex-sm-nowrap align-items-center py-2 px-4">
	      		<div class="d-flex justify-content-between align-items-center w-100">
	      			<h6><?php echo $this->lang->line('total') ?></h6>

	      			<?php 
					$priceval = $result[0]['items'][0]['price'];
					if($result[0]['items'][0]['offer_price']>0){ 
						$priceval = $result[0]['items'][0]['offer_price'];	
					} ?>
	      			<input type="hidden" name="subTotal_for_cal" id="subTotal_for_cal" value="<?php if($result[0]['items'][0]['offer_price']>0){  echo ($result[0]['items'][0]['offer_price'])?$result[0]['items'][0]['offer_price']:0; } else{  echo ($result[0]['items'][0]['price'])?$result[0]['items'][0]['price']:0; } ?>">

	      			<input type="hidden" name="subTotal" id="subTotal" value="0">

	      			<div class="mx-sm-4">
	      				<?php if($result[0]['items'][0]['offer_price']>0){ ?>
							<strong class="text-secondary text-decoration-line-through"><?php echo $currency_symbol->currency_symbol; ?><span><?php echo $result[0]['items'][0]['price'];  ?></span></strong>
							<strong class="text-secondary price"><?php echo $currency_symbol->currency_symbol; ?><span id="totalPrice"><?php echo $result[0]['items'][0]['offer_price'];  ?></span></strong>

						<?php }else{ ?>
							<strong class="text-secondary price"><?php echo $currency_symbol->currency_symbol; ?><span id="totalPrice"><?php echo $result[0]['items'][0]['price'];  ?></span></strong>
						<?php } ?>

					</div>
				</div>
				<div class="add-cart-item mt-2 mt-sm-0">
					<div class="number mw-100">
						<span class="icon minus" id="minusQuantity" onclick="ItemqtyPlusMinus('<?php echo $result[0]['items'][0]['menu_id']; ?>','minus','<?php echo $priceval;?>')"><img src="<?php echo base_url();?>assets/front/images/icon-minus.svg" alt=""></span>
						<input type="text" value="1" onblur="pricecalwithqty(this.id,this.value,'yes','',<?php echo $priceval;?>);" id="qtyaddtocart-<?php echo $result[0]['items'][0]['menu_id']; ?>" name="qtyaddtocart-<?php echo $result[0]['items'][0]['menu_id']; ?>" class="QtyNumberval" placeholder="" maxlength="3" />
						<span class="icon plus" id="plusQuantity" onclick="ItemqtyPlusMinus('<?php echo $result[0]['items'][0]['menu_id']; ?>','plus','<?php echo $priceval;?>')"><img src="<?php echo base_url();?>assets/front/images/icon-plus.svg" alt=""></span>
					</div>
				</div>
			</div>
  			<!-- onclick="AddToCart('<?php //echo $result[0]['items'][0]['menu_id']; ?>')" -->
  			<?php $mandatory = 0;
				$mandatory_arr = array();
			foreach ($result[0]['items'][0]['addons_category_list'] as $key => $value) {
				if($value['mandatory'] == '1') {
	               array_push($mandatory_arr, $value['addons_category_id']);
		         }
			} 
			$mandatory = (!empty($mandatory_arr))?1:0;
			$mandatory_arr = json_encode($mandatory_arr); ?>
  			<button type="button" class="addtocart btn btn-sm btn-primary w-100 mt-2 addtocart-<?php echo $result[0]['items'][0]['menu_id']; ?>" id="addtocart-<?php echo $result[0]['items'][0]['menu_id']; ?>" onclick="AddAddonsToCart('<?php echo $result[0]['items'][0]['menu_id']; ?>',this.id,'<?php echo $mandatory; ?>',<?php echo htmlspecialchars(json_encode($mandatory_arr)); ?>,'<?php echo (isset($from_checkout)) ? $from_checkout : '' ?>')"><?php echo $this->lang->line('add') ?></button>
	      	
	      	<?php /*if(false && !(empty($mandatory))) { ?>
			    <!-- <small class="error mt-1"><span>* </span><?php echo $this->lang->line('required_field'); ?></small> -->
			<?php }*/ ?>	
  		</form>
	</div>
</div>


<script type="text/javascript">
	//get item price
	var totalPrice = 0;
	var radiototalPrice = 0;
	var checktotalPrice = 0;
	/*flow - on check get max selectio value get it's total check box checked compare if checked greater display error*/
	$('.radio-btn-list .check_addons').change(function() {
		if(this.checked) {
			var selection_length = $(this).closest('.max-selection').attr('data-max-selection');
			if(selection_length != '' && $.isNumeric(selection_length) && selection_length > 0){
				var checkbox_count = $(this).closest('.max-selection').find('.check_addons').filter(':checked').length;
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
	$('input.QtyNumberval').on('input', function() {
	    this.value = this.value.replace(/[^0-9]/g,'').replace(/(\..*)\./g, '$1');
	});
	$('.icon img').inlineSvg();
</script>