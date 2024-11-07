<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('header'); ?>
<section class="section-banner section-banner-recipe bg-light position-relative text-center d-flex align-items-center py-8 py-xl-12">
	<div class="container-fluid banner-content">
        <h1 class="text-white mb-4"><?php echo $this->lang->line('recipe_text1') ?></h1>
		<form id="recipe_search_form" class="inner-pages-form">
			<div class="form-group mx-auto d-flex flex-column flex-sm-row overflow-hidden search-restaurant">
				<?php $err_msg = $this->lang->line('add_valid_message');
				$oktext = $this->lang->line('ok'); ?>
				<input class="form-control border-0" type="text" name="recipe" id="recipe" required placeholder="<?php echo $this->lang->line('search_recipe') ?>" value=""><div class="p-1 p-sm-0"></div>
				<input type="button" name="Search" id="searchRecipesBtn" value="<?php echo $this->lang->line('search'); ?>" class="btn btn-secondary rounded-0 btn-200" onclick="searchRecipes('recipe','<?php echo $err_msg; ?>','<?php echo $oktext; ?>')">
			</div>
		</form>
    </div>
</section>
<section class="section-text pt-8 pb-8 py-sm-8 py-xl-12" id="order-food-section">
    <div class="container-fluid mb-8" >
    	<h1 class="h2 pb-2 title text-center text-sm-start"><?php echo $this->lang->line('popular_recipe') ?></h2>
    </div>
    <div class="container-fluid container-sm-0">
		<div class="row row-grid row-grid-sm horizontal-image text-center" id="sort_recipies">
			<?php if (!empty($recipies)) {
				foreach ($recipies as $key => $value) { ?>
					<div class="col-12 col-sm-6 col-lg-4 col-xl-3">
						<a href="<?php echo base_url().'recipe/recipe-detail/'.$value['slug'];?>" class="d-inline-block w-100 bg-white">
							<figure class="picture">
								
								<?php  $rest_image = (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='') ? image_url.$value['image'] : default_img;  ?>
								<img src="<?php echo $rest_image ; ?>" alt="<?php echo $value['name']; ?>" title="<?php echo $value['name']; ?>">
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
						<figure class="mb-8">
							<img src="<?php echo no_res_found; ?>">
						</figure>
						<h6><?php echo $this->lang->line('no_recipe_found') ?></h6>
					</div>
				</figure>
			<?php }?>
		</div>
	</div>
</section>
<script type="text/javascript">
	// pagination function
	function getData(page=0, noRecordDisplay=''){
		var recipe = $('#recipe').val();
		var page = page ? page : 0;
		$.ajax({
			url: "<?php echo base_url().'recipe/ajax_recipies'; ?>/"+page,
			data: {'recipe':recipe,'page':page},
			type: "POST",
			success: function(result){
				$('#sort_recipies').html(result);
				$('html, body').animate({
			        scrollTop: $("#order-food-section").offset().top
			    }, 800);
			}
		});
	}

//search recipe 
$('#recipe').keyup(function(){
	if(event.keyCode == 13){
		$("#searchRecipesBtn").click();
    }
});
</script>
<?php $this->load->view('footer'); ?>