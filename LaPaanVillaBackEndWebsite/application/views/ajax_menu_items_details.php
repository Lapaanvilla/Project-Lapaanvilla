<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
	<div class="modal-content">
		<a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close"><img src="<?php echo base_url();?>assets/front/images/icon-close.svg" alt=""></a>
		<div class="row g-0">
			<div class="col-xl-6 square-image">
				<figure class="picture h-100">
		    		<img src="<?php echo (file_exists(FCPATH.'uploads/'.$result[0]['items'][0]['image']) && $result[0]['items'][0]['image']!='') ? image_url.$result[0]['items'][0]['image'] : default_icon_img; ?>">
		    	</figure>
			</div>
			<div class="col-xl-6  p-4 p-xl-8 align-self-center">
				<form>
					<!-- <h4 class="modal-title"><?php echo $this->lang->line('menu_details') ?></h4>
					<h5 id="product_title"><?php echo ($result[0])?$result[0]['items'][0]['name']:''; ?></h5> -->

					<h2 class="title pb-2 mb-6"><?php echo ($result[0])?$result[0]['items'][0]['name']:$this->lang->line('menu_details'); ?></h2>
					<p id="product_title"></p>

					<div class="mb-5 mb-md-6">
						<label class="text-secondary mb-1 w-100"><?php echo $this->lang->line('description') ?></label>
						<p class="small" id="product_description"><?php
							$menu_detail = $result[0]['items'][0]['menu_detail'];
							if($result[0]['items'][0]['is_combo_item']=='1')
							{
								$menu_detail = str_replace("\n", "<br>", $result[0]['items'][0]['menu_detail']);
							}

						 	echo $menu_detail; ?>
						 </p>
					</div>

					<?php /* ?><div style="font-weight:600;"><?php echo $this->lang->line('ingredients') ?></div><br><div><?php echo $result[0]['items'][0]['ingredients']; ?></div><br><?php */ ?>

					<div class="bg-light d-flex flex-wrap flex-sm-nowrap align-items-center py-2 px-4">
						<div class="d-flex justify-content-between align-items-center w-100">
							<h6><?php echo $this->lang->line('total') ?></h6>
							<div class="mx-sm-4">
								<!-- <input type="text" value="" id="qtyaddtocart-<?php echo $result[0]['items'][0]['menu_id']; ?>" onblur="pricecalwithqty(this.id,this.value,'yes');" name="qtyaddtocart-<?php echo $result[0]['items'][0]['menu_id']; ?>" class="form-control QtyNumberval" style="width:75px; margin-bottom: 5px; margin-right:10px;" placeholder="<?php echo $this->lang->line('quantity') ?>" maxlength="3" /> -->

								<?php if($result[0]['items'][0]['offer_price']>0){ ?>
								<strong class="text-secondary <?php if($result[0]['items'][0]['offer_price']>0){ ?> text-decoration-line-through" <?php } ?> id="price"><?php echo $currency_symbol->currency_symbol; ?><?php echo $result[0]['items'][0]['price']; ?></strong>								
								<strong class="text-secondary price">
								<?php echo $currency_symbol->currency_symbol; ?><span id="totalPrice"><?php echo $result[0]['items'][0]['offer_price']; ?></span>
								<?php //echo ($result[0]['items'][0]['check_add_ons'] != 1)?currency_symboldisplay($result[0]['items'][0]['offer_price'],$currency_symbol->currency_symbol):(($result[0]['items'][0]['offer_price'])?currency_symboldisplay($result[0]['items'][0]['offer_price'],$currency_symbol->currency_symbol):''); ?>
								</strong>

								<?php } else{ ?>
									<strong class="text-secondary <?php if($result[0]['items'][0]['offer_price']>0){ ?> text-decoration-line-through" <?php } ?> id="price"><?php echo $currency_symbol->currency_symbol; ?><span id="totalPrice"><?php echo $result[0]['items'][0]['price']; ?></span></strong>
								<?php } ?>


								<?php 
								$priceval = $result[0]['items'][0]['price'];
								if($result[0]['items'][0]['offer_price']>0){ 
									$priceval = $result[0]['items'][0]['offer_price'];	
								} ?>

							</div>
						</div>
						<div class="add-cart-item mt-2 mt-sm-0">
							<div class="number mw-100">
								<span class="icon minus" id="minusQuantity" onclick="ItemqtyPlusMinus('<?php echo $result[0]['items'][0]['menu_id']; ?>','minus','<?php echo $priceval;?>')"><img src="<?php echo base_url();?>assets/front/images/icon-minus.svg" alt=""></span>
								<input type="text" value="1" id="qtyaddtocart-<?php echo $result[0]['items'][0]['menu_id']; ?>" onblur="pricecalwithqty(this.id,this.value,'no','',<?php echo $priceval;?>);" name="qtyaddtocart-<?php echo $result[0]['items'][0]['menu_id']; ?>" class="QtyNumberval" placeholder="" maxlength="3" />
								<span class="icon plus" id="plusQuantity" onclick="ItemqtyPlusMinus('<?php echo $result[0]['items'][0]['menu_id']; ?>','plus','<?php echo $priceval;?>')"><img src="<?php echo base_url();?>assets/front/images/icon-plus.svg" alt=""></span>
							</div>
						</div>
					</div>

					<?php if($is_closed!='Closed' && $result[0]['items'][0]['restaurant_status'] == 1){ ?>
						<?php if($result[0]['items'][0]['stock'] == 1 || $result[0]['items'][0]['allow_scheduled_delivery'] == '1') { ?>
						<button type="button" class="addtocart btn btn-sm btn-primary w-100 mt-2 addtocart-<?php echo $result[0]['items'][0]['menu_id']; ?>" id="addtocart-<?php echo $result[0]['items'][0]['menu_id']; ?>" onclick="checkRestaurantinCart('<?php echo $result[0]['items'][0]['menu_id']; ?>','<?php echo $result[0]['items'][0]['restaurant_id']; ?>','',this.id,'','<?php echo $recipe_page ; ?>')" order-for-later="<?php echo ($result[0]['items'][0]['allow_scheduled_delivery'] == '1' && $result[0]['items'][0]['stock'] == 0) ? '1' : '0'; ?>" > <?php echo ($cart_rest == 1) ? $this->lang->line('added') : (($result[0]['items'][0]['allow_scheduled_delivery'] == '1' && $result[0]['items'][0]['stock'] == 0) ? $this->lang->line('order_for_later') : $this->lang->line('add')) ?> </button>
						
						<?php if($result[0]['items'][0]['allow_scheduled_delivery'] == '1' && $result[0]['items'][0]['stock'] == 0) { ?>
							<small class="error mt-1"><?php echo $this->lang->line('out_stock'); ?></small>
						<?php } ?>
						<?php }else{ ?>
		      					<small class="error mt-1"><?php echo $this->lang->line('out_stock'); ?></small>
		      			<?php } } ?>

					<?php if(!empty($recipe_name)){ ?>
						<a  class="btn btn btn-sm btn-primary w-100 mt-2 ViewRecipe" href="<?php echo base_url().'recipe/recipe-detail/'.$recipe_name[0]->slug ?>" ><?php echo $this->lang->line('view_recipe'); ?></a>
					<?php } ?>
				</form>	
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('.icon img').inlineSvg();
</script>