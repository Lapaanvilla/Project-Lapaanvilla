<?php if (!empty($recipies)) {
	foreach ($recipies as $key => $value) { ?>
		<div class="col-12 col-sm-6 col-lg-4 col-xl-3">
			<a href="<?php echo base_url().'recipe/recipe-detail/'.$value['slug'];?>" class="d-inline-block w-100 bg-white">
				<figure class="picture">
					<img src="<?php echo (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='' )?image_url.$value['image']:default_img; ?>" alt="<?php echo $value['name']; ?>" title="<?php echo $value['name']; ?>">
				</figure>
				<h6 <?php echo ($value['is_veg'] == 1)?'veg':'non-veg'; ?> class="p-4 text-capitalize"><?php echo $value['name']; ?></h6>
			</a>
		</div>
	<?php } ?>
	<?php if(isset($PaginationLinks) && $PaginationLinks!=''){ ?>
	<div class="col-12">
		<div class="container-gutter-sm">
			<div class="pagination pt-6 pt-sm-4 pt-xl-8" id="#pagination"><?php echo $PaginationLinks; ?></div>
		</div>
	</div>
	<?php } ?>
<?php } 
else { ?>
	<div class="col-12 screen-blank text-center">
		<div class="container-gutter-sm">
			<figure class="my-4">
				<img src="<?php echo no_res_found; ?>">
			</figure>
			<h6><?php echo $this->lang->line('no_recipe_found') ?></h6>
		</div>
	</div>
<?php }?>