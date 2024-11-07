<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('header'); ?>
<!-- <link rel="stylesheet" href="<?php //echo base_url();?>assets/admin/plugins/data-tables/DT_bootstrap.css" />
<script type="text/javascript" src="<?php //echo base_url();?>assets/admin/plugins/data-tables/jquery.dataTables.js"></script> -->
<?php //$minimum_range = 0; $maximum_range = 50000; 

$this->db->select('OptionValue');
$enable_review = $this->db->get_where('system_option',array('OptionSlug'=>'enable_review'))->first_row();
$show_restaurant_reviews = ($enable_review->OptionValue=='1')?1:0;

$distance_inarr = $this->db->get_where('system_option',array('OptionSlug'=>'distance_in'))->first_row();
$distance_inVal = $this->lang->line('in_km');
if($distance_inarr && !empty($distance_inarr))
{
    if($distance_inarr->OptionValue==0){
        $distance_inVal = $this->lang->line('in_mile');
    }
}
?>
<script type="text/javascript">
	var distance_inVal = '<?php echo $distance_inVal; ?>';
</script>
<section class="section-text pt-16 py-md-8 py-xl-12 section-restaurant">
	<div class="container-fluid mb-8 pt-28 pt-md-15 pt-xl-19">
		<h2 class="h2 pb-2 title text-center"><?php echo $this->lang->line('select_fav_res') ?></h2>
	</div>
	<div class="container-fluid container-md-0 overflow-hidden">
		<div class="row row-grid">
			<div class="col-xl-4 col-xxl-3" id="accordion">
				<div class="mb-2">
					<a href="javascript:void(0)" class="btn btn-sm btn-secondary w-100 px-4 text-start d-flex align-items-center justify-content-between collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
						<?php echo $this->lang->line('sort') ?>
						<i class="icon"><img src="<?php echo base_url(); ?>assets/front/images/icon-accordion.svg" alt=""></i>
					</a>
					<div id="collapseOne" class="collapse" aria-labelledby="headingOne" >
						<?php if ($show_restaurant_reviews) { ?>
						<div class="bg-white p-4">
							<div class="form-check mb-2">
								<input class="form-check-input" type="radio" name="filter_by" id="rate" value="rating" onchange="getFavouriteResturants()">
								<label class="form-check-label" for="rate"><?php echo $this->lang->line('rating') ?></label>
							</div>
							<div class="form-check mb-2" id="distance_sort">
								<input class="form-check-input" type="radio" name="filter_by" id="distance" value="distance" onchange="getFavouriteResturants()">
								<label class="form-check-label" for="distance"><?php echo $this->lang->line('distance') ?></label>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="mb-2">	
					<a href="javascript:void(0)" class="btn btn-sm btn-secondary w-100 px-4 text-start d-flex align-items-center justify-content-between collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
						<?php echo $this->lang->line('filter') ?>
						<i class="icon"><img src="<?php echo base_url(); ?>assets/front/images/icon-accordion.svg" alt=""></i>
					</a>
					<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" >
						<div class="bg-white p-4">
							<div class="filter-box pb-4 border-bottom" id="distance_filter">
								<small class="fw-medium text-secondary mb-1"><?php echo $this->lang->line('by_distance') ?></small>
								<div class="distance-slider">
									<div id="slider-range"></div>
								    <div class="distance-value value01"><span id="slider-range-value1"></span></div>
								    <div class="distance-value value02"><span id="slider-range-value2"></span></div>
								    <input type="hidden" name="minimum_range" id="minimum_range" class="form-control" value="<?php echo $minimum_range; ?>" />
								    <input type="hidden" name="maximum_range" id="maximum_range" class="form-control" value="<?php echo $maximum_range; ?>" />
								</div>
							</div>
							<?php if(!empty($food_type)){ ?>
							<div class=" py-4">
								<small class="fw-medium text-secondary mb-1"><?php echo $this->lang->line('by_food_type') ?></small>
								<?php for($fdt=0;$fdt<count($food_type);$fdt++)	{ ?>
								<div class="form-check mb-2">
									<input type="checkbox" class="form-check-input" name="food_type[]" id="food_veg_<?=$food_type[$fdt]->entity_id?>" value="<?=$food_type[$fdt]->entity_id?>" onchange="getFavouriteResturants()">
									<label class="form-check-label" for="food_veg_<?=$food_type[$fdt]->entity_id?>"><?php echo ucfirst($food_type[$fdt]->name); ?></span></label>

									<span><?php /* ?><i class="iicon-icon-15 <?php if($food_type[$fdt]->is_veg == '1') {echo 'veg';} else { echo 'non-veg';} ?>"></i><?php */ ?>
								</div>
								<?php } ?>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-8 col-xxl-9">
				<div class="row row-grid row-grid-md horizontal-image" id="order_from_restaurants">
					<?php if (!empty($restaurants)) {
						foreach ($restaurants as $key => $value) { ?>
							<div class="col-md-6 col-xl-6 col-xxl-4">
								<div class="box-restaurant d-inline-block w-100 bg-white h-100 d-flex flex-column">
									<a href="<?php echo base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug'];?>" class="figure picture">
										<?php  $rest_image = (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='') ? image_url.$value['image'] : default_img;  ?>
										<img src="<?php echo $rest_image ;?>" alt="<?php echo $value['name']; ?>">
										
										<div class="icon-time small text-white d-inline-block <?php echo ($value['timings']['closing'] == "Closed")?'bg-danger':'bg-success'; ?>"> <?php echo ($value['timings']['closing'] == "Closed")?$this->lang->line('closed'):$this->lang->line('open'); ?> </div>

										<div class="icon-left d-flex text-capitalize">
											<?php if(isset($value['distance'])) { ?>
												<div class="icon-distance small text-white d-flex align-items-center bg-secondary"><i class="icon"><img src="<?php echo base_url() ?>/assets/front/images/icon-pin.svg" alt="clock"></i><?php echo round($value['distance'],2); ?> <?php echo $distance_inVal; ?></div>
											<?php } ?>

											<?php if ($show_restaurant_reviews) { 
												$rating_txt = ($value['restaurant_reviews_count'] > 1)?$this->lang->line('ratings'):$this->lang->line('rating'); ?>
												<?php echo ($value['ratings'] > 0)?'<div class="small text-white bg-success  d-flex align-items-center"><i class="icon"><img src="'. base_url() .'/assets/front/images/icon-star.svg" alt="clock"></i>'.$value['ratings'].' ('.$value['restaurant_reviews_count'].' '.strtolower($rating_txt).')'.'</div>':'<div class="small text-white bg-success">'. $this->lang->line("new") .'</div>'; ?> 
											<?php } ?>
										</div>
									</a>
									<div class="p-4 d-flex flex-column flex-fill">
										<a class="h6 w-auto mb-1 transition" href="<?php echo base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug'];?>"><?php echo $value['name']; ?></a>
										<small class="text-body mb-auto"><i class="icon"><img src="<?php echo base_url() ?>/assets/front/images/icon-pin.svg" alt="clock"></i><?php echo $value['address']; ?></small>

										<?php  if($value['timings']['closing'] != "Closed") {
													?>
											<a href="<?php echo base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug'];?>" class="btn mt-4 btn-sm btn-primary w-100"><?php echo $this->lang->line('order') ?></a>
										<?php } ?>
									</div>
								</div>
							</div>
						<?php } ?>
						<?php if(isset($PaginationLinks) && $PaginationLinks!=''){ ?>
						<div class="col-12">
							<div class="container-gutter-md">
								<div class="pagination pb-8 pb-md-0 pt-6 pt-md-4 pt-xl-8" id="#pagination"><?php echo $PaginationLinks; ?></div>
							</div>
						</div>
						<?php } ?>
					<?php } 
					else { ?>
						<div class="col-12 screen-blank text-center">
							<div class="container-gutter-md pb-8 pb-md-0">
								<figure class="my-4">
									<img src="<?php echo no_res_found; ?>">
								</figure>
								<h6><?php echo $this->lang->line('no_such_res_found') ?></h6>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript" src='<?php echo base_url();?>assets/front/js/range-slider.js?v1'></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo google_key;?>&libraries=places"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/multiselect/dashboard/jquery.sumoselect.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	initAutocomplete('address');
	$('#distance_sort').hide();
  	$('#distance_filter').hide();
  	if($('#address').val()!=''){
		$('#for_address').show();
	} else {
		$('#for_address').hide();
	}
	if($('#resdishes').val()!=''){
		$('#for_res_search').show();
	} else {
		$('#for_res_search').hide();
	}
	// auto detect location if even searched once.
	if (SEARCHED_LAT == '' && SEARCHED_LONG == '' && SEARCHED_ADDRESS == '') {
		getLocation('order_food');
	}
	else
	{
		getSearchedLocation(SEARCHED_LAT,SEARCHED_LONG,SEARCHED_ADDRESS,'order_food');
	}

	$('#resdishes').keyup(function(){
		if($('#resdishes').val()!=''){
			$('#for_res_search').show();
		} else {
			$('#for_res_search').hide();
		}
		if(event.keyCode == 13){
			$("#fillInAddressBtn").click();
	    }
	});
	$('#address').keyup(function(){
		if($('#address').val()!=''){
			$('#for_address').show();
		} else {
			$('#for_address').hide();
		}
	});
});

// pagination function
function getData(page=0, noRecordDisplay=''){
	var food_veg = ($('#food_veg').is(":checked"))?1:0;
	var food_non_veg = ($('#food_non_veg').is(":checked"))?1:0;
	var resdishes = $('#resdishes').val();
	var order_mode = $('#order_mode').val();
	var latitude = $('#latitude').val();
	var longitude = $('#longitude').val();
	var minimum_range = $('#minimum_range').val();
	var maximum_range = $('#maximum_range').val();
	var filter_by = $("input[name='filter_by']:checked").val();
	var page = page ? page : 0;
	var food_type = [];
    $('.food_typecls:checked').each(function(i, e) {
    	food_type.push($(this).val());
  	});
	$.ajax({
		url: "<?php echo base_url().'restaurant/ajax_restaurants'; ?>/"+page,
		data : {'latitude':latitude,'longitude':longitude,'resdishes':resdishes,'page':page,'minimum_range':minimum_range,'maximum_range':maximum_range,'food_veg':food_veg,'food_non_veg':food_non_veg,'food_type': food_type.join(),'order_mode':order_mode,'filter_by':filter_by},
		type: "POST",
		success: function(result){
			$('#order_from_restaurants').html(result);
			/*$('html, body').animate({
		        scrollTop: $("#order_from_restaurants").offset().top
		    }, 800);*/
		}
	});
}
$(document).ready(function() {
	$('.sumo.order_mode').SumoSelect({ selectAll: true, locale: ["<?php echo $this->lang->line('apply'); ?>", "<?php echo $this->lang->line('reset'); ?>", "<?php echo $this->lang->line('select_').' '.$this->lang->line('all');?>"],csvDispCount: 0, okCancelInMulti:true, triggerChangeCombined : true, forceCustomRendering: true, isClickAwayOk: false, searchText: "<?php echo $this->lang->line('by_food_type'); ?>" });
});	
</script>
<?php $this->load->view('footer'); ?>
