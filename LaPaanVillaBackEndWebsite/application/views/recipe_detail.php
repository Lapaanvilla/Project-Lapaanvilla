<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('header'); ?>
<?php if(empty($recipe_details)) {
    redirect(base_url().'recipe');
} ?>

<section class="section-banner section-banner-recipe-detail bg-light position-relative text-center d-flex align-items-center">
</section>
<section class="section-text pb-8 pb-xl-12">
	<div class="container-fluid">
		<div class="box d-flex flex-column flex-md-row align-items-center">
			<figure class="picture">
				<?php  $rest_image = (file_exists(FCPATH.'uploads/'.$recipe_details[0]['image']) && $recipe_details[0]['image']) ? image_url.$recipe_details[0]['image'] : default_icon_img;  ?>
				<img src="<?php echo $rest_image ; ?>" alt="<?php echo ($recipe_details[0]['name'])?$recipe_details[0]['name']:''; ?>" title="<?php echo ($recipe_details[0]['name'])?$recipe_details[0]['name']:''; ?>">
			</figure>
			<div class="flex-fill">
				<h1 class="h6 mb-1 text-capitalize"><?php echo ($recipe_details[0]['name'])?$recipe_details[0]['name']:''; ?></h1>
				<small class="mb-2 w-100 p-0"><?php echo ($recipe_details[0]['detail'])?$recipe_details[0]['detail']:''; ?></small>
				<ul class="small">
					<li><i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-clock.svg" alt="clock"></i><?php echo $this->lang->line('cooking_time') ?> : <?php echo ($recipe_details[0]['recipe_time'])?$recipe_details[0]['recipe_time']:''; ?> <?php echo $this->lang->line('minutes') ?></li>
				</ul>
			</div>			
		</div>
	</div>
	<div class="container-fluid mb-8 pt-8 pt-xl-12" >
    	<h2 class="h2 pb-2 title text-center text-xl-start"><?php echo $this->lang->line('recipe_text2') ?></h2>
    </div>
    <div class="container-fluid container-xl-0">
		<div class="row row-grid row-grid-xl">
			<div class="col-xl-8">
				<div class="card card-xl-0">
					<div class="card-body container-gutter-xl py-4 p-xl-4">
						<h5 class="border-bottom pb-4 mb-4"><?php echo $this->lang->line('directions') ?></h5>
						<div class="text-editor">
							<?php echo ($recipe_details[0]['recipe_detail'])?$recipe_details[0]['recipe_detail']:''; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-4">
				<div class="card card-xl-0">
					<div class="card-body container-gutter-xl py-4 p-xl-4">
						<h5 class="border-bottom pb-4 mb-4"><?php echo $this->lang->line('ingredients') ?></h5>
						<div class="text-editor"><?php echo ($recipe_details[0]['ingredients'])?$recipe_details[0]['ingredients']:''; ?></div>
					</div>
				</div>


				<div class="btn-inline d-flex flex-column flex-sm-row mt-8 mt-xl-4 container-gutter-xl">
					<?php if (!empty($menu_details)) { 
					$recipe_page = $this->uri->segment(1);

					if($menu_details[0]->check_add_ons !=1){	?>
						<button id="addtocart-<?php echo $menu_details[0]->entity_id ?>"onclick="checkCartRestaurantDetails('<?php echo $menu_details[0]->entity_id ?>','<?php echo $menu_details[0]->restaurant_id ?>','<?php echo $menu_details[0]->timings['closing']; ?>','',this.id,'yes','<?php echo $recipe_page; ?>')" class="btn w-100 btn-sm btn-primary text-nowrap recipe-view-menu"><?php echo $this->lang->line('view_menu'); ?></button>
					<?php }else{
						?>
						<button id="addtocart-<?php echo $menu_details[0]->entity_id ?>"onclick="checkCartRestaurantDetails('<?php echo $menu_details[0]->entity_id ?>','<?php echo $menu_details[0]->restaurant_id ?>','<?php echo $menu_details[0]->timings['closing']; ?>','addons',this.id,'yes','<?php echo $recipe_page; ?>')" class="btn w-100 btn-sm btn-primary text-nowrap recipe-view-menu"><?php echo $this->lang->line('view_menu'); ?></button>
					<?php }	} ?>
					
					<?php if(!empty($recipe_details[0]['youtube_video'])){ ?>
						<a href="javascript:void(0)" data-toggle="modal" data-target="#videoModal" class="btn btn-sm btn-primary w-100 d-flex align-items-center justify-content-center"><i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-play.svg" alt="play"></i><?php echo $this->lang->line('video') ?></a>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="modal modal-video fade show" tabindex="-1" role="dialog" id="videoModal">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content horizontal-image bg-transparent">
			<a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close"><img src="<?php echo base_url();?>assets/front/images/icon-close.svg" alt=""></a>
			<div class="figure picture">
				<?php if(!empty($recipe_details[0]['youtube_video'])){ ?>
					<iframe width="760" height="415" src="<?php echo 'https://www.youtube.com/embed/'.$recipe_details[0]['youtube_video'] ?>"frameborder="0" allowfullscreen></iframe>
				<?php } ?>
			</div>
		</div>
	</div>
</div>


<div class="modal fade show" tabindex="-1" role="dialog" id="anotherRestModal">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content p-4 p-xl-8">
        	<a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close"><img src="<?php echo base_url();?>assets/front/images/icon-close.svg" alt=""></a>

        	<div class="title pb-2 mb-6 d-flex flex-column">
	        	<h4 class="text-capitalize mb-1"><?php echo $this->lang->line('add_to_cart') ?> ?</h4>
	        	<small><?php echo $this->lang->line('res_details_text1') ?></small>
        	</div>

        	<form id="custom_cart_restaurant_form">
        		<label class="mb-2"><?php echo $this->lang->line('res_details_text2') ?></label>
        		<div class="form-check mb-2">
      				<input type="hidden" name="rest_entity_id" id="rest_entity_id" value="">
      				<input type="hidden" name="rest_restaurant_id" id="rest_restaurant_id" value="">
      				<input type="hidden" name="is_addon" id="rest_is_addon" value="">
      				<input type="hidden" name="item_id" id="item_id" value="">
      				<input type="radio" checked="checked" class="form-check-input radio_addon" name="addNewRestaurant" id="discardOld" value="discardOld">
      				<label class="form-check-label" for="discardOld"><?php echo $this->lang->line('discard_old') ?></label>
	      		</div>
	      		<div class="form-check">
	      			<input type="radio" class="radio_addon form-check-input" name="addNewRestaurant" id="keepOld" value="keepOld">
	      			<label class="form-check-label" for="keepOld"><?php echo $this->lang->line('keep_old') ?></label>
	      		</div>
	      		<button type="button" class="cartrestaurant btn btn-sm btn-primary mt-5" id="cartrestaurant" onclick="ConfirmCartRestaurant('<?php echo (isset($recipe_page)) ? $recipe_page : ''; ?>')"><?php echo $this->lang->line('confirm') ?></button>
	      	</form>
	    </div>
	</div>
</div>
<div class="modal modal-main" id="myconfirmModalDetails">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content p-4 p-xl-8">
			<a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close"><img src="<?php echo base_url();?>assets/front/images/icon-close.svg" alt=""></a>

			<div class="title pb-2 mb-6 d-flex flex-column">
	        	<h4 class="text-capitalize mb-1"><?php echo $this->lang->line('add_to_cart') ?> ?</h4>
	        	<small><?php echo $this->lang->line('menu_already_added') ?></small>
        	</div>

        	<form id="custom_items_form1">
        		<label class="mb-2"><?php echo $this->lang->line('want_to_add_new_item') ?></label>
        		<div class="form-check mb-2">
      				<input type="hidden" name="con_entity_id1" id="con_entity_id1" value="">
      				<input type="hidden" name="is_closed1" id="is_closed1" value="">
      				<input type="hidden" name="con_restaurant_id1" id="con_restaurant_id1" value="">
      				<input type="hidden" name="con_item_id1" id="con_item_id1" value="">
      				<input type="hidden" name="con_item_mandatory" id="con_item_mandatory" value="">

      				<input type="radio" class="form-check-input radio_addon" checked name="addedToCart1" id="addnewitem1" value="addnewitem">
      				<label class="form-check-label" for="addnewitem1"><?php echo $this->lang->line('as_new_item') ?></label>
	      		</div>
	      		<div class="form-check">
	      			<input type="radio" class="radio_addon form-check-input" name="addedToCart1" id="increaseitem1" value="increaseitem1">
	      			<label class="form-check-label" for="increaseitem1"><?php echo $this->lang->line('increase_quantity') ?></label>
	      		</div>
	      		<button type="button" class="addtocart btn btn-sm btn-primary mt-5" id="addtocart1" onclick="ConfirmCartAddDetails('<?php echo (isset($recipe_page)) ? $recipe_page : '' ?>')"><?php echo $this->lang->line('add_to_cart') ?></button>
	      	</form>
	    </div>
	</div>
</div>
	
<div class="modal modal-main modal-product product-detail" id="menuDetailModal"></div>
<div class="modal modal-main modal-product product-detail" id="addonsMenuDetailModal"></div>
<?php $this->load->view('footer'); ?>
