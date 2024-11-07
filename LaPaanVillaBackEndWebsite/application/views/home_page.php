<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('header'); 
$this->db->select('OptionValue');
$enable_review = $this->db->get_where('system_option',array('OptionSlug'=>'enable_review'))->first_row();
$show_restaurant_reviews = ($enable_review->OptionValue=='1')?1:0;
?>

<!-- <link rel="stylesheet" href="<?php //echo base_url();?>assets/admin/plugins/data-tables/DT_bootstrap.css" /> -->
<!-- <script type="text/javascript" src="<?php //echo base_url();?>assets/admin/plugins/data-tables/jquery.dataTables.js"></script> -->
<!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/multiselect/sumoselect.min.css"/> -->

<section class="section-banner section-banner-large section-banner-home bg-light position-relative text-center d-flex align-items-center py-8 py-xl-12">
	<div class="container-fluid banner-content">
		<a href="<?php echo base_url(); ?>" class="icon text-secondary mb-6">
			<img src="<?php echo base_url(); ?>assets/front/images/brand-logo.svg" alt="">
		</a>
        <h1 class="h3 mb-4 text-capitalize"><?php echo $this->lang->line('order_fav_rest'); ?></h1>
		<form id="home_search_form" class="search-form">
			<div class="home_auto_location form-group mx-auto d-flex flex-column flex-md-row">
				<select id="order_mode" class="form-control order_mode bg-white sumo" name="order_mode">
					<option value="Delivery"><?php echo $this->lang->line('delivery_word') ?></option>
					<option value="PickUp"><?php echo $this->lang->line('pickup_word') ?></option>
					<?php /* ?><option value="Both"><?php echo $this->lang->line('both') ?></option><?php */ ?>
				</select>
				<div class="p-1 p-md-0"></div>
				<div class="d-flex flex-column flex-sm-row w-100">
					<div class="position-relative w-100">
						<a href="javascript:void(0);" class="icon auto_location" onclick="getLocation('home_page');"><img src="<?php echo base_url(); ?>assets/front/images/icon-pin.svg" alt=""></a>
						<input type="text" name="address" id="address" onFocus="geolocate('home_page')" placeholder = "<?php echo $this->lang->line('enter_address'); ?>" value="" class="form-control form-control-auto bg-white">
						<a href="javascript:void(0);" class="icon icon-clear clear_icon" id="for_address" onclick="clearField('address','home_page',this.id);"><img src="<?php echo base_url(); ?>assets/front/images/icon-close.svg" alt=""></a>
					</div>
					<div class="p-1 p-sm-0"></div>
					<input type="hidden" name="latitude" id="latitude" value="">
					<input type="hidden" name="longitude" id="longitude" value="">
					<?php $err_msg = $this->lang->line('add_valid_location');
					$oktext = $this->lang->line('ok'); ?>
					<input type="button" name="Search" value="<?php echo $this->lang->line('search'); ?>" class="btn btn-secondary rounded-0 px-4 btn-200" onclick="fillInAddress('home_page','<?php echo $err_msg; ?>','<?php echo $oktext; ?>')" id="fillInAddressBtn">
				</div>
			</div>
		</form>
    </div>
</section>

<?php if (!empty($food_type) && !empty($restaurants)) { ?>
	<section class="section-text py-8 py-xl-12 horizontal-image text-center" id="foodtype_quicksearch">
		<div class="container-fluid">
			<h2 class="h2 pb-2 mb-8 title text-center text-xl-start"><?php echo $this->lang->line('quick_search'); ?></h2>
			<div class="row horizontal-image text-center">
				<div class="slider slider-search p-0">
					<?php foreach ($food_type as $ftkey => $ftvalue) { ?>
						<div class="item px-2">
							<a href="javascript:void(0)" class="w-100 bg-white" id="foodtype_<?php echo $ftkey; ?>" onclick="getRestaurantsOnFilter('apply','quicksearch_foodtype','',<?php echo $ftkey; ?>)">
								<?php $food_type_image = (file_exists(FCPATH.'uploads/'.$ftvalue->food_type_image) && $ftvalue->food_type_image != '') ? image_url.$ftvalue->food_type_image : default_img;  ?>
								<input type="hidden" name="quicksearch_foodtype" id="quicksearch_foodtype" value="<?php echo $ftvalue->entity_id; ?>">
								<figure class="picture">
		                            <img src="<?php echo $food_type_image; ?>" alt="<?php echo $ftvalue->name ?>" title="<?php echo $ftvalue->name ?>">
		                        </figure>
		                        <h6 class="py-2"><?php echo $ftvalue->name ?></h6>
		                    </a>
						</div>					
					<?php } ?>				
				</div>
			</div>
		</div>
	</section>
<?php } ?>
<?php if (!empty($coupons) && !empty($restaurants)) { ?>	

<section class="section-text bg-white py-8 py-xl-12 horizontal-image text-center" id="coupon_section">
	<div class="container-fluid">
		<h2 class="h2 pb-2 mb-8 title text-center text-xl-start"><?php echo $this->lang->line('latest_coupons'); ?></h2>
		<div class="row horizontal-image text-center">
			<div class="slider slider-coupon p-0">
				<?php foreach ($coupons as $key => $value) {
					$redirect_flag = (count($value->restaurant_ids) == 1) ? '1':'0';
					$rest_image = (file_exists(FCPATH.'uploads/'.$value->image) && $value->image!='') ? image_url.$value->image : default_img;
					if($redirect_flag == '1') { ?>
						<div class="item px-2">
							<a class="figure picture"  href="<?php echo base_url().'restaurant/restaurant-detail/'.$value->restaurant_slug; ?>">
								<img src="<?php echo $rest_image ?>" alt="coupon">
							</a>
						</div>
					<?php } else { ?>
						<div class="item px-2">
							<a href="javascript:void(0)" class="figure picture">
								<img src="<?php echo $rest_image ?>" alt="coupon">
							</div>
						</a>
					<?php }
				} ?>
			</div>
		</div>
	</div>	
</section>
<?php } ?>

<?php if (!empty($restaurants)) { ?>
	<section class="section-text pt-8 py-md-8 py-xl-12 section-restaurant">
		<div class="container-fluid mb-4">
			<div class="d-flex align-items-center mb-8">
				<h2 class="h2 pb-2 title text-center text-xl-start"><?php echo $this->lang->line('nearby_restaurants') ?></h2>
				<i class="dropdown-custom icon text-secondary d-md-none mb-2" data-target="#dropdown-filter"><img src="<?php echo base_url() ?>/assets/front/images/icon-filter.svg" alt="Filter"></i>
			</div>
			<div id="dropdown-filter" class="dropdown-content d-md-inline-block w-100">
				<?php //restaurant sort/filter section :: start ?>
				<div class="row row-grid form-white">
					
					<?php //sort by distance-rating-freeDeliveryCoupon-availability :: start ?>
					<div class="col-md-6 col-xl-3">
						<div class="form-group position-relative dropdown-absolute">
							<div class="form-control form-control-icon dropdown-custom" data-target="#dropdown-sort"><?php echo $this->lang->line('sort') ?></div>	
							<div class="border bg-white dropdown-content" id="dropdown-sort">
								<div class="px-4">
									<div class="d-flex align-items-center py-4">
										<div class="form-check">
											<input type="radio" name="filter_by" class="form-check-input" value="rating" id="rate">
											<label for="rate" class="form-check-label"><?php echo $this->lang->line('rating') ?></label>
										</div>
										<div class="px-2"></div>
										<div class="form-check" id="distance_sort">
											<input type="radio" name="filter_by" class="form-check-input" value="distance" id="distance">
											<label for="distance" class="form-check-label"><?php echo $this->lang->line('distance') ?></label>
										</div>
									</div>
									<div class="filter-box py-4 border-top border-bottom" id="distance_filter">
										<small class="fw-medium text-secondary mb-1"><?php echo $this->lang->line('by_distance') ?></small>
										<div class="distance-slider">
											<div id="slider-range"></div>
										    <div class="distance-value value01"><span id="slider-range-value1"></span></div>
										    <div class="distance-value value02"><span id="slider-range-value2"></span></div>
										    <input type="hidden" name="minimum_range" id="minimum_range" class="form-control" value="<?php echo $minimum_range; ?>" />
										    <input type="hidden" name="maximum_range" id="maximum_range" class="form-control" value="<?php echo $maximum_range; ?>" />
										</div>
									</div>
									<div class="form-check py-4">
										<input class="form-check-input" type="checkbox" name="offers_free_delivery" id="offers_free_delivery" value="1" >
										<label for="offers_free_delivery" class="form-check-label"><?php echo $this->lang->line('offers_free_delivery') ?></label>
									</div>
									<div class="py-4 border-top">
										<small class="fw-medium text-secondary mb-1"><?php echo $this->lang->line('sort_availability') ?></small>
										<div class="form-check mb-1">
											<input type="checkbox" name="availability_filter" class="form-check-input availability_filter" id="filter_breakfast" value="Breakfast">
											<label class="form-check-label" for="filter_breakfast"><?php echo $this->lang->line('breakfast') ?></label>
										</div>
										<div class="form-check mb-1">
											<input type="checkbox" name="availability_filter" class="form-check-input availability_filter" id="filter_lunch" value="Lunch">
											<label class="form-check-label" for="filter_lunch"><?php echo $this->lang->line('lunch') ?></label>
										</div>
										<div class="form-check mb-1">
											<input type="checkbox" name="availability_filter" class="form-check-input availability_filter" id="filter_dinner" value="Dinner">
											<label class="form-check-label" for="filter_dinner"><?php echo $this->lang->line('dinner') ?></label>
										</div>
									</div>
								</div>
								<div class="filter_btns d-flex align-items-center border-top p-4">
									<button class="btn btn-xs btn-primary w-100 sorting-dropdown-btn" onclick="getRestaurantsOnFilter('apply','','<?php echo addslashes($this->lang->line('sorted_by')); ?>')" ><?php echo addslashes($this->lang->line('apply')) ?></button>
									<span class="p-1"></span>
									<button class="btn btn-xs btn-secondary w-100 clear_btnn sorting-dropdown-btn" onclick="getRestaurantsOnFilter('clear','distance_rating','<?php echo addslashes($this->lang->line('sort')); ?>')" ><?php echo $this->lang->line('reset') ?></button>
								</div>
							</div>
						</div>
					</div>
					<?php //sort by distance-rating-freeDeliveryCoupon-availability :: end ?>
					<?php //filter by category :: start ?>
					<div class="col-md-6 col-xl-3">
						<?php if(!empty($categories)){ ?>
							<select name="category_id[]" multiple="" class="form-control sumo categoryid_sumo category_id" id="category_id" placeholder="<?php echo $this->lang->line('filter').' '.$this->lang->line('by_category'); ?>">
							<?php if(!empty($categories)){
								foreach ($categories as $categorykey => $categoryvalue) { ?>
									<option value="<?php echo $categoryvalue->entity_id ?>"><?php echo ucfirst($categoryvalue->name); ?></option>
								<?php } 
							} ?>
							</select>
						<?php } ?>
					</div>
					<?php //filter by category :: end ?>
					<?php //filter by food_type :: start ?>
					<div class="col-md-6 col-xl-3">
						<?php if(!empty($food_type)){ ?>
							<select name="food_type[]" multiple="" class="form-control sumo food_type" id="food_type" placeholder="<?php echo $this->lang->line('filter').' '.$this->lang->line('by_food_type'); ?>">
							<?php if(!empty($food_type)){
								foreach ($food_type as $key => $value) { ?>
									<option value="<?php echo $value->entity_id ?>"><?php echo ucfirst($value->name); ?></option>
								<?php } 
							} ?>
							</select>
						<?php } ?>
					</div>
					<?php //filter by food_type :: end ?>	

					<div class="col-md-6 col-xl-3">
						<?php //search restaurants :: start ?>
						<div class="d-flex align-items-center position-relative">
							<input class="input-tags form-control form-control-icon" type="text" name="resdishes" placeholder="<?php echo $this->lang->line('search_res') ?>" id="resdishes">
							<a href="javascript:void(0);" class="icon icon-clear" id="for_res_search" onclick="getRestaurantsOnFilter('clear','res_search');"><img src="<?php echo base_url() ?>/assets/front/images/icon-close.svg" alt="close"></a>
							<input type="button" name="SearchResHome" value="<?php echo $this->lang->line('search'); ?>" class="btn btn-search btn-primary" onclick="getRestaurantsOnFilter('apply')" id="search_res_home">
						</div>
						<?php //search restaurants :: end ?>
					</div>				
				</div>
				<?php //restaurant sort/filter section :: end ?>
			</div>
		</div>
		<div class="container-fluid container-md-0 overflow-hidden">
			<div class="row row-grid row-grid-md horizontal-image" id="popular-restaurants">
				<?php if (!empty($restaurants)) {	
					foreach ($restaurants as $key => $value) { ?>
						<div class="col-md-6 col-xl-4 col-xxl-3">
							<a class="box-restaurant d-inline-block w-100 bg-white h-100" href="<?php echo base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug'];?>">
								<figure class="picture">
									<?php  $rest_image = (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='') ? image_url.$value['image'] : default_img; ?>
									<img src="<?php echo $rest_image; ?>" alt="<?php echo $value['name']; ?>">
									

									<?php $closed = ($value['timings']['closing'] == "Closed") ? 'bg-danger':'bg-success'; ?>
									<div class="icon-time small text-white d-inline-block <?php echo $closed; ?>"> <?php echo ($value['timings']['closing'] == "Closed")?$this->lang->line('closed'):$this->lang->line('open'); ?> </div>
										<!-- <?php //echo $value['timings']['closing']; ?> -->

									<div class="icon-left d-flex text-capitalize">
										<?php if ($show_restaurant_reviews) { ?>
											<?php echo ($value['ratings'] > 0)?'':'<div class="small text-white bg-success">'. $this->lang->line("new") .'</div>'; ?> 
										<?php } ?>
									</div>

									<?php if (!empty($value['restaurant_coupons'])) { ?>
										<div class="icon-discount d-flex align-items-center">
											<i class="icon text-warning"><img src="<?php echo base_url() ?>/assets/front/images/icon-discount.svg" alt="clock"></i>
											<div class="slider slider-variable">
												<?php foreach ($value['restaurant_coupons'] as $cpnkey => $cpnvalue) { ?>
													<div><?php echo $cpnvalue->name ?><?php echo (count($value['restaurant_coupons']) > 1) ? ',  &nbsp;': ''; ?></div>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
								</figure>
								<div class="p-4">
									<h6 class="mb-1 transition"><?php echo $value['name']; ?></h6>
									<small class="text-body"><i class="icon"><img src="<?php echo base_url() ?>/assets/front/images/icon-pin.svg" alt="clock"></i><?php echo $value['address']; ?></small>
								</div>
							</a>
						</div>
					<?php } ?>
					<?php if(isset($PaginationLinks) && $PaginationLinks!=''){ ?>
					<div class="col-12">
						<div class="container-gutter-md">
							<div class="pagination pb-8 pb-md-0 pt-6 pt-md-4 pt-xl-8" id="#pagination"><?php echo $PaginationLinks; ?></div>
						</div>
					</div>
					<?php } ?>
				<?php } else { ?>	
					<div class="col-12 screen-blank text-center">
						<div class="container-gutter-md pb-8 pb-md-0">
							<figure class="mb-4">
								<img src="<?php echo no_res_found; ?>">
							</figure>
							<h6><?php echo $this->lang->line('no_such_res_found') ?></h6>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>

<?php }else{ ?>
	<div class="empty_block pb-8 pb-md-0">
		<figure>
			<img src="<?php echo no_res_found; ?>">
		</figure>
		<p class="no-found"><?php echo $this->lang->line('no_such_res_found') ?></p>
	</div>
<?php } ?>
<section class="section-text bg-white pt-8 py-lg-8 py-xl-12 overflow-hidden">
	<div class="container-fluid container-lg-0">
		<div class="box-cta position-relative d-flex flex-column flex-lg-row align-items-center">
			<figure>
				<img src="<?php echo base_url();?>assets/front/images/image-restaurant.png" alt="Restaurant app">
			</figure>
			<div class="container-fluid">
				<div class="box-cta-content text-center text-lg-start mt-lg-10 py-8 py-xl-12">
					<h2 class="h1 mb-2"><?php echo $this->lang->line('welcome_to') ?> <span class="text-primary"><?php echo $this->lang->line('site_title'); ?></span> <?php echo $this->lang->line('res_app') ?></h2>

					<p><?php echo $this->lang->line('home_text1') ?></p>

					<?php 
					//get System Option Data
					$this->db->select('OptionValue');
					$playstore_url = $this->db->get_where('system_option',array('OptionSlug'=>'playstore_url'))->first_row();
					$this->db->select('OptionValue');
					$app_store_url = $this->db->get_where('system_option',array('OptionSlug'=>'app_store_url'))->first_row();
					?>
					<div class="d-flex justify-content-center justify-content-lg-start mt-6">
						<a href="<?php echo ($playstore_url->OptionValue)?$playstore_url->OptionValue:'#'; ?>"><img src="<?php echo base_url();?>assets/front/images/icon-google-play.png" alt="Google play"></a>
						<div class="p-1"></div>
						<a href="<?php echo ($app_store_url->OptionValue)?$app_store_url->OptionValue:'#'; ?>"><img src="<?php echo base_url();?>assets/front/images/icon-app-store.png" alt="App store"></a>
					</div>
				</div>
			</div>
		</div>	
	</div>
</section>
<?php /* ?><section class="driver-app">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-lg-4">
				<div class="driver-app-content">	
					<div class="heading-title-02">
						<h4><?php echo $this->lang->line('download') ?> <span><?php echo $this->lang->line('driver_app') ?></span></h4>
					</div>							
					<p><?php echo $this->lang->line('site_title'); ?> <?php echo $this->lang->line('home_text2') ?></p>
					<?php //get System Option Data
					$this->db->select('OptionValue');
					$driver_playstore_url = $this->db->get_where('system_option',array('OptionSlug'=>'driver_playstore_url'))->first_row();
					$this->db->select('OptionValue');
					$driver_app_store_url = $this->db->get_where('system_option',array('OptionSlug'=>'driver_app_store_url'))->first_row();
					?>
					<div class="app-download">
						<a href="<?php echo ($driver_playstore_url->OptionValue)?$driver_playstore_url->OptionValue:'#'; ?>"><img src="<?php echo base_url();?>assets/front/images/google-play.png" alt="Google play"></a>
						<a href="<?php echo ($driver_app_store_url->OptionValue)?$driver_app_store_url->OptionValue:'#'; ?>"><img src="<?php echo base_url();?>assets/front/images/app-store.png" alt="App store"></a>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-8">
				<div class="driver-app-img wow pulse">
					<picture>
						<source type="image/webp" srcset="<?php echo base_url();?>assets/front/images/driver-app.webp" />
						<source type="image/png" srcset="<?php echo base_url();?>assets/front/images/driver-app.png" />
						<img src="<?php echo base_url();?>assets/front/images/driver-app.png" alt="Restaurant app">
					</picture>
					<img src="">
				</div>
			</div>
		</div>
	</div>
</section><?php */ ?>
<?php if (!empty($restaurants)) { ?>
	<script type="text/javascript" src='<?php echo base_url();?>assets/front/js/range-slider.js'></script>
<?php } ?>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo google_key; ?>&libraries=places"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/multiselect/dashboard/jquery.sumoselect.min.js"></script>
<script>
var selected_food_type = [];
var selected_category_id = [];
$(document).ready(function() {
	initAutocomplete('address');
	$('#distance_sort').hide();
  	$('#distance_filter').hide();
	// auto detect location if even searched once.
	<?php if (!empty($restaurants)) { ?>
		if (SEARCHED_LAT == '' && SEARCHED_LONG == '' && SEARCHED_ADDRESS == '') {
			getLocation('home_page','from_home_page');
		}
		else
		{
			getSearchedLocation(SEARCHED_LAT,SEARCHED_LONG,SEARCHED_ADDRESS,'home_page','from_home_page');
		}
	<?php } ?>
	$(window).keydown(function(event){
		if(event.keyCode == 13) {
		  event.preventDefault();
		  return false;
		}
	});
	var rtl = (SELECTED_LANG == 'ar')?true:false;

	$('.slider-search').slick({
		infinite: true,
        arrows: true,
        rtl:rtl,
        autoplay: true,
        draggable: true,
        slidesToShow: 6,
        slidesToScroll: 1,
        pauseOnHover: true,
        prevArrow:"<a href='javascript:void(0)' class='slick-prev icon'><img src='<?php echo base_url();?>assets/front/images/icon-arrow-left.svg'></a>",
        nextArrow:"<a href='javascript:void(0)' class='slick-next icon'><img src='<?php echo base_url();?>assets/front/images/icon-arrow-right.svg'></a>",
        responsive: [
		{
			breakpoint: 1600,
			settings: {
				slidesToShow: 5
			}
		},
		{
			breakpoint: 1400,
			settings: {
				slidesToShow: 4
			}
		},
		{
			breakpoint: 1200,
			settings: {
				slidesToShow: 3
			}
		},
		{
			breakpoint: 768,
			settings: {
				slidesToShow: 2
			}
		},
		{
			breakpoint: 576,
			settings: {
				slidesToShow: 1
			}
		}]
    });

    $('.slider-coupon').slick({
		infinite: true,
        arrows: true,
        rtl:rtl,
        autoplay: true,
        draggable: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        pauseOnHover: true,
        prevArrow:"<a href='javascript:void(0)' class='slick-prev icon'><img src='<?php echo base_url();?>assets/front/images/icon-arrow-left.svg'></a>",
        nextArrow:"<a href='javascript:void(0)' class='slick-next icon'><img src='<?php echo base_url();?>assets/front/images/icon-arrow-right.svg'></a>",
        responsive: [
			{
				breakpoint: 1200,
				settings: {
					slidesToShow: 3
				}
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 2
				}
			},
			{
				breakpoint: 576,
				settings: {
					slidesToShow: 1
				}
			}]
    });

	//for coupon slider for in restaurant
	var rtl = (SELECTED_LANG == 'ar')?true:false;
	$(".slider-variable").slick({
		arrows: false,
		dots: false,
		infinite: true,
		autoplay: true,
		variableWidth: true,
		arrow: false,
		autoplaySpeed: 0,
		speed: 8000,
		pauseOnHover: false,
		cssEase: 'linear',
		rtl: rtl,
	});
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
	//food type sumo :: start
    $('.sumo').SumoSelect({search: true, selectAll: true, locale: ["<?php echo $this->lang->line('apply'); ?>", "<?php echo $this->lang->line('reset'); ?>", "<?php echo $this->lang->line('select_').' '.$this->lang->line('all');?>"],csvDispCount: 0, okCancelInMulti:true, triggerChangeCombined : true, forceCustomRendering: true, isClickAwayOk: false, searchText: "<?php echo $this->lang->line('by_food_type'); ?>" });
    
    $("html").on('click', '.sumo_food_type .select-all', function(){
    	$('.sumo_food_type .select-all').removeClass('partial');
    	
    	var myObj = $(this).closest('.SumoSelect.open').children()[0];
    	if ($('.sumo_food_type .select-all').hasClass("selected")) {
        	$('.sumo_food_type .select-all').parents(".SumoSelect").find("select>option").prop("selected", true);
            $(myObj)[0].sumo.selectAll();
            $('.sumo_food_type .select-all').parent().find("ul.options>li").addClass("selected");
        } else {
        	$('.sumo_food_type .select-all').parents(".SumoSelect").find("select>option").prop("selected", false);
            $(myObj)[0].sumo.unSelectAll();
            $('select.food_type')[0].sumo.unSelectAll();
            $('.sumo_food_type .select-all').parent().find("ul.options>li").removeClass("selected");
        }
    });
    $("html").on('click', '.sumo_food_type .btnCancel', function(){
        var obj = [];
	    $('.food_type option:selected').each(function(i_count) {
	        obj.push($(this).index());
	    });

	    for (var i = 0; i < obj.length; i++) {
	        $('.food_type')[0].sumo.unSelectItem(obj[i]);
	    }
		selected_food_type = [];
		$('.quick-searches-box').removeClass('selected');
		$('.quick-searches-box').removeClass('borderClass');
		getRestaurantsOnFilter('apply');
    });
    $("html").on('click', '.sumo_food_type .btnOk', function(){
		$('.quick-searches-box').removeClass('selected');
		$('.quick-searches-box').removeClass('borderClass');
		selected_food_type = [];
		$('.food_type option:selected').each(function(j) {
			selected_food_type.push($(this).index());
			var selected_food_type_val = $(this).val();
			$('#foodtype_'+selected_food_type_val).addClass('selected');
			$('#foodtype_'+selected_food_type_val).addClass('borderClass');
		});
		getRestaurantsOnFilter('apply');
    });
    //food type sumo :: end    
    //category sumo :: start
    $('.categoryid_sumo').SumoSelect({search: true, selectAll: true, locale: ["<?php echo $this->lang->line('apply'); ?>", "<?php echo $this->lang->line('reset'); ?>", "<?php echo $this->lang->line('select_').' '.$this->lang->line('all');?>"],csvDispCount: 0, okCancelInMulti:true, triggerChangeCombined : true, forceCustomRendering: true, isClickAwayOk: false, searchText: "<?php echo $this->lang->line('by_category'); ?>" });
    
    $("html").on('click', '.sumo_category_id .select-all', function(){
    	$('.sumo_category_id .select-all').removeClass('partial');
    	
    	var myObj_categoryid = $(this).closest('.SumoSelect.open').children()[0];
    	if ($('.sumo_category_id .select-all').hasClass("selected")) {
        	$('.sumo_category_id .select-all').parents(".SumoSelect").find("select>option").prop("selected", true);
            $(myObj_categoryid)[0].sumo.selectAll();
            $('.sumo_category_id .select-all').parent().find("ul.options>li").addClass("selected");
        } else {
        	$('.sumo_category_id .select-all').parents(".SumoSelect").find("select>option").prop("selected", false);
            $(myObj_categoryid)[0].sumo.unSelectAll();
            $('select.category_id')[0].sumo.unSelectAll();
            $('.sumo_category_id .select-all').parent().find("ul.options>li").removeClass("selected");
        }
    });
    $("html").on('click', '.sumo_category_id .btnCancel', function(){
        var obj_cat_id = [];
	    $('.category_id option:selected').each(function(categoryid_count) {
	        obj_cat_id.push($(this).index());
	    });

	    for (var a = 0; a < obj_cat_id.length; a++) {
	        $('.category_id')[0].sumo.unSelectItem(obj_cat_id[a]);
	    }
		selected_category_id = [];
		getRestaurantsOnFilter('apply');
    });
    $("html").on('click', '.sumo_category_id .btnOk', function(){
    	selected_category_id = [];
		$('.category_id option:selected').each(function(b) {
			selected_category_id.push($(this).index());
		});
		getRestaurantsOnFilter('apply');
    });
    //category sumo :: end
});
// pagination function
function getData(page=0, noRecordDisplay=''){

	var rtl = (SELECTED_LANG == 'ar')?true:false;
	var foodtype_quicksearch = [];
    $('.quick-searches-box.selected').each(function(){
      foodtype_quicksearch.push($(this).find("input[name=quicksearch_foodtype]").val());
    });
    var listed_foodtype = [];
    $('.quick-searches-box').each(function(){
      listed_foodtype.push($(this).find("input[name=quicksearch_foodtype]").val());
    });
	var resdishes = $('#resdishes').val();
    var order_mode = $('#order_mode').val();
    var latitude = $('#latitude').val();
    var longitude = $('#longitude').val();
    var minimum_range = $('#minimum_range').val();
    var maximum_range = $('#maximum_range').val();
    var filter_by = $("input[name='filter_by']:checked").val();
    var offers_free_delivery = $("input[name='offers_free_delivery']:checked").val();
    offers_free_delivery = (offers_free_delivery == '1') ? 1 : 0;
    var availability_filter = [];
    $("input[name='availability_filter']:checkbox:checked").each(function(i){
      availability_filter.push($(this).val());
    });
    var category_id = [];
    $('#category_id option:selected').each(function(i) {
        category_id.push($(this).val());
    });
    var food_type = [];
    $('#food_type option:selected').each(function(i) {
        food_type.push($(this).val());
    });    
	var page = page ? page : 0;
	$.ajax({
		url: "<?php echo base_url().'home/getRestaurantsOnFilter'; ?>/"+page,
		data : {'latitude':latitude,'longitude':longitude,'resdishes':$.trim(resdishes),'minimum_range':minimum_range,'maximum_range':maximum_range,'filter_by': filter_by,'order_mode':order_mode,'page':page, 'food_type': food_type.join(),'foodtype_quicksearch': foodtype_quicksearch.join(),'category_id': category_id.join(),'listed_foodtype':listed_foodtype.join(),'offers_free_delivery':offers_free_delivery,'availability_filter':availability_filter.join()},
		dataType :"json",
		type: "POST",
		success: function(result){
			var rtl = (SELECTED_LANG == 'ar')?true:false;
			$('#popular-restaurants').html(result.popular_restaurants);
			$('#foodtype_quicksearch').html(result.quick_searches);
			$('#coupon_section').html(result.coupon_section_html);
			$('.food_type').empty().append(result.foodtype_dropdown);
        	$('select.food_type')[0].sumo.reload();
			if(foodtype_quicksearch.length != 0 || food_type.length != 0) {
				if(food_type.length != 0) {
					$.each(food_type, function(ftindex, ftvalue) {
						$('select.food_type')[0].sumo.selectItem(ftvalue);
					});
				} else {
					$.each(foodtype_quicksearch, function(ftindex, ftvalue) {
						$('select.food_type')[0].sumo.selectItem(ftvalue);
					});
				}
			}
			if(result.quick_searches != '') {
				$('.slider-search').slick({
					infinite: true,
			        arrows: true,
			        rtl:rtl,
			        autoplay: true,
			        draggable: true,
			        slidesToShow: 6,
			        slidesToScroll: 1,
			        pauseOnHover: true,
			        prevArrow:"<a href='javascript:void(0)' class='slick-prev icon'><img src='<?php echo base_url();?>assets/front/images/icon-arrow-left.svg'></a>",
			        nextArrow:"<a href='javascript:void(0)' class='slick-next icon'><img src='<?php echo base_url();?>assets/front/images/icon-arrow-right.svg'></a>",
			        responsive: [
					{
						breakpoint: 1600,
						settings: {
							slidesToShow: 5
						}
					},
					{
						breakpoint: 1400,
						settings: {
							slidesToShow: 4
						}
					},
					{
						breakpoint: 1200,
						settings: {
							slidesToShow: 3
						}
					},
					{
						breakpoint: 768,
						settings: {
							slidesToShow: 2
						}
					},
					{
						breakpoint: 576,
						settings: {
							slidesToShow: 1
						}
					}]
			    });
			}
			if(result.coupon_section_html != '') {
				$('.slider-coupon').slick({
					infinite: true,
			        arrows: true,
			        rtl:rtl,
			        autoplay: true,
			        draggable: true,
			        slidesToShow: 4,
			        slidesToScroll: 1,
			        pauseOnHover: true,
			        prevArrow:"<a href='javascript:void(0)' class='slick-prev icon'><img src='<?php echo base_url();?>assets/front/images/icon-arrow-left.svg'></a>",
			        nextArrow:"<a href='javascript:void(0)' class='slick-next icon'><img src='<?php echo base_url();?>assets/front/images/icon-arrow-right.svg'></a>",
			        responsive: [
					{
						breakpoint: 1200,
						settings: {
							slidesToShow: 3
						}
					},
					{
						breakpoint: 768,
						settings: {
							slidesToShow: 2
						}
					},
					{
						breakpoint: 576,
						settings: {
							slidesToShow: 1
						}
					}]
			    });
			}
			if($('.slider-variable').length){
				$(".slider-variable").slick({
					arrows: false,
					dots: false,
					infinite: true,
					autoplay: true,
					variableWidth: true,
					arrow: false,
					rtl:rtl,
					autoplaySpeed: 0,
					speed: 8000,
					pauseOnHover: false,
					cssEase: 'linear',
					rtl: rtl,					
				});
			}
			$('html, body').animate({
				scrollTop: $(".popular-restaurants").offset().top
			}, 2000);
		}
	});
}
$('#address').keyup(function(){
	if($('#address').val()!=''){
		$('#for_address').show();
	} else {
		$('#for_address').hide();
	}
	if(event.keyCode == 13){
		$("#fillInAddressBtn").click();
    }
});
$('#resdishes').keyup(function() {
	if($('#resdishes').val()!=''){
		$('#for_res_search').show();
	} else {
		$('#for_res_search').hide();
	}
});
$(document).mouseup(function(e) 
{
    var container = $("#accordion");
    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) 
    {
        $('#sort_heading_txt').addClass('collapsed');
        $('#collapseOne').removeClass('show');
    }
	var sumo_container = $(".filter-checkbox");
	// if the target of the click isn't the container nor a descendant of the container
	if (!sumo_container.is(e.target) && sumo_container.has(e.target).length === 0) 
	{
		var count = 1;
		$('select.food_type').on('sumo:closed', function(sumo) {
			if(count == 1){
				var obj = [];
				$('.food_type option').each(function (){
					if(this.selected) {
						obj.push($(this).index());
					}
				});
				/*$('.food_type option:selected').each(function(i) {
					obj.push($(this).index());
				});
				for (var j = 0; j < obj.length; j++) {
					if(!inArray(obj[j], selected_food_type)){
						$('.food_type')[0].sumo.unSelectItem(obj[j]);
					}
				}
				for (var k = 0; k < selected_food_type.length; k++) {
					$('.food_type')[0].sumo.selectItem(selected_food_type[k]);
				}*/
				count++;
			}
		});		
		$('select.category_id').on('sumo:closed', function(sumo) {
			if(count == 1){
				var obj_category_id = [];
				$('.category_id option').each(function (){
					if(this.selected) {
						obj_category_id.push($(this).index());
					}
				});
				count++;
			}
		});
	}
});
function inArray(needle, haystack) {
	var length = haystack.length;
	for(var l = 0; l < length; l++) {
		if(haystack[l] == needle) return true;
	}
	return false;
}
</script>
<?php $this->load->view('footer'); ?>