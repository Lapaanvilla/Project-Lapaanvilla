<?php defined('BASEPATH') or exit('No direct script access allowed');?>
<?php 
$menu_ids = array();
if (!empty($menu_arr)) {
	$menu_ids = array_column($menu_arr, 'menu_id');
} 
//get System Option Data
/*$this->db->select('OptionValue');
$currency_id = $this->db->get_where('system_option',array('OptionSlug'=>'currency'))->first_row();
$currency_symbol = $this->common_model->getCurrencySymbol($currency_id->OptionValue);
$currency_symbol = $currency_symbol->currency_symbol;*/
//get System Option Data
$this->db->select('OptionValue');
$enable_review = $this->db->get_where('system_option',array('OptionSlug'=>'enable_review'))->first_row();
$show_restaurant_reviews = ($enable_review->OptionValue=='1')?1:0;
?>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/front/css/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/intl_tel_input/intlTelInput.css">
	<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/intl_tel_input/intlTelInput.min.js"></script>
	<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/multiselect/sumoselect.min.css"/>

<?php $rest_background_image = (file_exists(FCPATH.'uploads/'.$restaurant_details['restaurant'][0]['background_image']) && $restaurant_details['restaurant'][0]['background_image']!='') ? image_url.$restaurant_details['restaurant'][0]['background_image'] : ''; ?>
<?php $this->load->view('header'); ?>

<section class="section-banner <?php echo ($rest_background_image != '')?'':'section-banner-restaurant-detail' ?> bg-light position-relative text-center d-flex align-items-center" <?php echo ($rest_background_image != '')?'style="background-image: url('.$rest_background_image.');"':''; ?>>
</section>
<section class="section-text pb-lg-8 pb-xl-12">
	<div class="container-fluid">
		<div class="box box-large d-flex flex-column flex-lg-row align-items-center">
			<figure class="picture">
				<?php  $rest_image = (file_exists(FCPATH.'uploads/'.$restaurant_details['restaurant'][0]['image']) && $restaurant_details['restaurant'][0]['image']!='') ? image_url.$restaurant_details['restaurant'][0]['image'] : default_icon_img;  ?>
				<img src="<?php echo $rest_image ; ?>" >
			</figure>
			<div class="flex-fill d-flex flex-column rest-detail-content">
				<h1 class="h6 mb-1 text-capitalize"><?php echo $restaurant_details['restaurant'][0]['name']; ?></h1>
				<small class="mb-1 mb-md-2 w-100"><i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-pin.svg" alt=""></i><?php echo $restaurant_details['restaurant'][0]['address']; ?></small>
				<ul class="small d-flex text-capitalize">
					<?php if ($show_restaurant_reviews) {
						$rating_txt = ($restaurant_reviews_count > 1)?$this->lang->line('ratings'):$this->lang->line('rating'); ?>
					<li><i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-star.svg" alt=""></i><?php echo ($restaurant_details['restaurant'][0]['ratings'] > 0)?$restaurant_details['restaurant'][0]['ratings'].' ('.$restaurant_reviews_count.' '.strtolower($rating_txt).')':'<strong class="newres">'. $this->lang->line("new") .'</strong>'; ?></li>
					<?php } ?>
					<li class="rtl-num-cod dropdown-absolute position-relative res_time_li" id="res_time_li"><i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-clock.svg" alt=""></i>
						<?php 
							$courrent_day = strtolower($restaurant_details['restaurant'][0]['timings']['current_day']);
						?>

						<span class="dropdown-custom" data-target="#dropdown-time">
						<?php echo (!empty($restaurant_details['restaurant'][0]['timings']['open']) && !empty($restaurant_details['restaurant'][0]['timings']['close']))?$this->lang->line($courrent_day).' : '.$this->common_model->timeFormat($restaurant_details['restaurant'][0]['timings']['open']) . '-' . $this->common_model->timeFormat($restaurant_details['restaurant'][0]['timings']['close']) : $this->lang->line("close_txt"); ?></span>
						
						<?php if(!empty($restaurant_details['restaurant'][0]['week_timings'])){ ?>
							<div id="dropdown-time" class="border bg-body dropdown-content p-4">
								<?php foreach ($restaurant_details['restaurant'][0]['week_timings'] as $week_key => $week_value) { ?>
									<div class="d-flex justify-content-between align-items-center small w-100 text-nowrap mb-1"><label class="fw-medium text-secondary"><?php echo $this->lang->line(strtolower($week_key)); ?></label><?php echo (!empty($week_value['open']) && !empty($week_value['close']))?': '.$this->common_model->timeFormat($week_value['open']).' - '.$this->common_model->timeFormat($week_value['close']) : ': '.$this->lang->line('close_txt'); ?></div>
								<?php } ?>
							</div>
						<?php } ?>
					</li>
					<li class="rtl-num-cod"><a href="tel:<?php echo $restaurant_details['restaurant'][0]['phone_number']; ?>"><i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-phone.svg" alt=""></i><?php echo $restaurant_details['restaurant'][0]['phone_number']; ?></a></li>
					<li><a href="http://maps.google.com/?q=<?php echo $restaurant_details['restaurant'][0]['latitude']; ?>,<?php echo  $restaurant_details['restaurant'][0]['longitude']; ?>" target="_blank"><i class="icon" id="map_direction"><img src="<?php echo base_url();?>assets/front/images/icon-location.svg" alt=""></i><?php echo $this->lang->line('map')." ".$this->lang->line('directions'); ?></a></li>

				</ul>

					<?php if($this->session->userdata('UserID')){ ?>
						<a class="bg-white icon-bookmark icon" href="javascript:void(0)" onclick="addBookmark('<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>')" tooltip-title="<?php echo ($get_bookmark==0)? $this->lang->line('add_bookmark'):$this->lang->line('bookmarked'); ?>" data-placement="bottom">
							<?php if ($get_bookmark == 0) {?>
								<img src="<?php echo base_url();?>assets/front/images/icon-heart.svg" alt="">
							<?php } else {?>
								<img src="<?php echo base_url();?>assets/front/images/icon-heart-fill.svg" alt="">
							<?php }?>
						</a>
					<?php } ?>							

				<?php $closed = ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed")?'bg-danger':'bg-success'; ?>
				<span class="icon-time small text-white d-inline-block <?php echo $closed; ?>"><?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed")?$this->lang->line('closed'):$this->lang->line('open'); ?></span>
			</div>			
		</div>
	</div>
	<div class="container-fluid container-lg-0">
		<div class="pt-8 pt-xl-12">
			<ul class="nav nav-restaurant nav-tabs flex-row flex-nowrap text-center container-gutter-lg" id="restaurantTab" role="tablist" class="bg-white">
				<li class="nav-item flex-fill" role="presentation">
					<a href="javascript:void(0)" class="nav-link active" id="menu-tab" data-toggle="tab" data-target="#menu" role="tab" aria-controls="menu" aria-selected="true"><?php echo $this->lang->line('order').' '.$this->lang->line('online'); ?></a>
			  	</li>
			  	<li class="nav-item flex-fill" role="presentation">
			  		<a href="javascript:void(0)" class="nav-link" id="about-tab" data-toggle="tab" data-target="#aboutus" role="tab" aria-controls="aboutus" aria-selected="false"><?php echo $this->lang->line('about_us'); ?></a>
			  	</li>
			  	<?php if ($show_restaurant_reviews) { ?>
				  	<li class="nav-item flex-fill" role="presentation">
				  		<a href="javascript:void(0)" class="nav-link" id="review-tab" data-toggle="tab" data-target="#review" role="tab" aria-controls="review" aria-selected="false"><?php echo $this->lang->line('review_ratings'); ?></a>
				  	</li>
				<?php } ?>

				<?php if($restaurant_details['restaurant'][0]['allow_event_booking'] == 1 || $restaurant_details['restaurant'][0]['enable_table_booking'] == 1){ ?>
					<li class="nav-item flex-fill" role="presentation">
				  		<a href="javascript:void(0)" class="nav-link" id="reservation-tab" data-toggle="tab" data-target="#online_reservation" role="tab" aria-controls="online_reservation" aria-selected="false"><?php echo $this->lang->line('online_reservation'); ?></a>
				  	</li>
				<?php } ?>
			</ul>
			<div class="tab-content bg-white border py-8 p-lg-8 container-gutter-lg" id="myTabContent">
				<div class="tab-pane fade show active" id="menu" role="tabpanel" aria-labelledby="menu-tab">
					<?php if($restaurant_coupons){ ?>
						<div class="pb-8 pb-xl-12">
							<h2 class="h2 pb-2 mb-8 title text-center text-xl-start"><?php echo $this->lang->line('latest_coupons'); ?></h2>
							<div class="row horizontal-image text-center">
								<div class="slider slider-coupon p-0">
									<?php foreach ($restaurant_coupons as $key => $value) { ?>
										
										<!-- <p><?php echo $this->lang->line('coupon_code')." - ".$value->name; ?></p> -->
										<!-- <p><?php echo $value->amount; ?><?php echo ($value->amount_type == 'Percentage') ? "% " : " " ?><?php echo strtoupper($this->lang->line('off'));?></p> -->
										<!-- <p><?php echo $this->lang->line('valid_till')." ".date("l jS \of F Y", strtotime($value->end_date)); ?></p> -->
										<!-- <p><?php echo $this->lang->line('min_order_amount')." - ".$value->max_amount; ?></p> -->
										
										<?php  $cpn_img = (file_exists(FCPATH.'uploads/'.$value->image) && $value->image!='') ? image_url.$value->image : default_img;  ?>
										<div class="item px-2">
											<figure class="figure picture">
												<img src="<?php echo $cpn_img ?>" alt="<?php echo  $value->name ?>" title="<?php echo  $value->name ?>">
											</figure>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
					<?php } ?>
					
					<h2 class="h2 pb-2 mb-8 title text-center text-xl-start"><?php echo $this->lang->line('order_food_from') ?> <?php echo $restaurant_details['restaurant'][0]['name']; ?></h2>
					
					<div class="row row-grid">
						<div class="col-xl-8">
							<span class="btn btn-xs btn-primary border-white item-browse d-flex d-xl-none align-items-center"><i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-browse.svg" alt=""></i>Browse Menu</span>
							<div class="position-relative form-control-search d-flex">
								<i class="icon icon-search"><img src="<?php echo base_url();?>assets/front/images/icon-search.svg" alt=""></i>
								<button class="icon icon-cancel" title="Reset" name="Reset" alt="Reset" id="search_dish_reset_btn" disabled><img src="<?php echo base_url();?>assets/front/images/icon-close.svg" alt=""></button>									
								<input class="form-control form-control-icon" type="text" name="search_dish" placeholder="<?php echo $this->lang->line('search_dishes') ?>" id="search_dish">
								<input type="hidden" name="srestaurant_id" id="srestaurant_id" value="<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>">
								<!-- <input type="button" name="Search" value="<?php echo $this->lang->line('search') ?>" class="btn" onclick="searchMenuDishes(<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>)"  id="Search_btn" disabled> -->

								

								<!-- <div class="p-1"></div>
								<button class="btn btn-primary icon px-4 dropdown-custom" data-target="#dropdown-filter"  title="Reset"><img src="<?php echo base_url();?>assets/front/images/icon-filter.svg" alt=""></button> -->

							</div>
							
							<div class="border bg-body d-inline-block w-100 p-4 border-top-0 mb-4 mb-xl-0">
								<?php
					        	$resfood_type = $restaurant_details['restaurant'][0]['resfood_type'];
					        	if(!empty($resfood_type)>0 && count($resfood_type)>0)
					        	{ ?>
					        		<div class="pb-4">
						        		<small class="fw-medium text-secondary mb-1"><?php echo $this->lang->line('sort_by_food_type') ?></small>

						        		<div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 row-grid row-grid-xl">
								        	<?php for($fdt=0;$fdt<count($resfood_type);$fdt++) { ?>
								        		<div class="col">
									        		<div class="form-check">
														<input type="radio" <?php if(count($resfood_type)==1) {?> checked <?php } ?> name="filter_food" class="form-check-input" id="filter_<?=$resfood_type[$fdt]->food_type_id?>" value="<?=$resfood_type[$fdt]->food_type_id?>" onclick="menuFilter('<?php echo $restaurant_details['restaurant'][0]['content_id']; ?>',this.value,'yes','no')">
														<label class="form-check-label" for="filter_<?=$resfood_type[$fdt]->food_type_id?>"><?php echo ucfirst($resfood_type[$fdt]->food_type_name); ?></label>
													</div>
								        		</div>
									        <?php } 
									        if(count($resfood_type)>1) { ?>
									        	<div class="col">
										        	<div class="form-check">
														<input type="radio" checked="checked" name="filter_food" class="form-check-input" id="all" value="all" onclick="menuFilter('<?php echo $restaurant_details['restaurant'][0]['content_id']; ?>',this.value)">
														<label class="form-check-label" for="all"><?php echo $this->lang->line('view_all') ?></label>
													</div>
												</div>
											<?php } ?>
						        		</div>
					        		</div>
						        <?php  } ?>									        	
					        	
					        	<?php /*
					        	<div class="col-md-4">
									<div class="custom-control custom-checkbox">
									    <input type="radio" checked="checked" name="filter_price" class="custom-control-input" id="filter_high_price" value="filter_high_price" onclick="menuFilter(<?php echo $restaurant_details['restaurant'][0]['content_id']; ?>,this.value)">
									    <label class="custom-control-label" for="filter_high_price"><?php echo $this->lang->line('sort_by_price_low') ?></label>
								  	</div>
									<div class="custom-control custom-checkbox">
										<input type="radio" name="filter_price" class="custom-control-input" id="filter_low_price" value="filter_low_price" onclick="menuFilter(<?php echo $restaurant_details['restaurant'][0]['content_id']; ?>,this.value)">
										<label class="custom-control-label" for="filter_low_price"><?php echo $this->lang->line('sort_by_price_high') ?></label>
									</div>
								</div>
								*/?>

								<?php //New code add for availability :: Start ?>

								<small class="fw-medium text-secondary mb-1"><?php echo $this->lang->line('sort_availability') ?></small>
								<div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 row-grid row-grid-xl">
				        			<div class="col">
						        		<div class="form-check">
											<input type="radio"  name="filter_availibility" class="form-check-input" id="filter_breakfast" value="Breakfast" onclick="menuFilter('<?php echo $restaurant_details['restaurant'][0]['content_id']; ?>',this.value,'no','yes')">
											<label class="form-check-label" for="filter_breakfast"><?php echo $this->lang->line('breakfast') ?></label>
										</div>
									</div>
									<div class="col">
										<div class="form-check">
											<input type="radio"  name="filter_availibility" class="form-check-input" id="filter_lunch" value="Lunch" onclick="menuFilter('<?php echo $restaurant_details['restaurant'][0]['content_id']; ?>',this.value,'no','yes')">
											<label class="form-check-label" for="filter_lunch"><?php echo $this->lang->line('lunch') ?></label>
										</div>
									</div>
									<div class="col">
										<div class="form-check">
											<input type="radio" name="filter_availibility" class="form-check-input" id="filter_dinner" value="Dinner" onclick="menuFilter('<?php echo $restaurant_details['restaurant'][0]['content_id']; ?>',this.value,'no','yes')">
											<label class="form-check-label" for="filter_dinner"><?php echo $this->lang->line('dinner') ?></label>
										</div>
									</div>
					        		<div class="col">
							        	<div class="form-check">
											<input type="radio" checked="checked" name="filter_availibility" class="form-check-input" id="all_availibility" value="all" onclick="menuFilter('<?php echo $restaurant_details['restaurant'][0]['content_id']; ?>',this.value)">
											<label class="form-check-label" for="all_availibility"><?php echo $this->lang->line('view_all') ?></label>
										</div>
									</div>
								</div>
					        	<?php //New code add for availability :: End ?>	
				        	</div>
						
							<div id="details_content">
								<?php if (!empty($restaurant_details['menu_items']) || !empty($restaurant_details['categories'])) { ?>
					    			<div class="accordion-restaurant" id="res_detail_content">
					    				<?php if (!empty($restaurant_details['categories'])) {?>
											<div class="slider-tag bg-body px-xl-4 py-xl-2 border my-xl-4">
												<div class="slider-overlay d-xl-none"></div>
												<!-- <button id="pnAdvancerLeft" class="pn-Advancer pn-Advancer_Left move left" type="button"><i class="iicon-icon-16"></i></button>									 -->
												<nav class="slider-loop">
													<ul class="autoWidth-non-loop" id="autoWidth-non-loop">	
														<?php if (!empty($restaurant_details['menu_items'])) {
													        $popular_count = 0;
													        foreach ($restaurant_details['menu_items'] as $key => $value) {
													            if ($value['popular_item'] == 1) {
													                $popular_count = $popular_count + 1;
													            }
													        }
													    }
													    $ccc=1;
													    if ($popular_count > 0) { ?>
													    	<li class="item" id="categorytop<?php echo $ccc; ?>"><a href="#popular_menu_item" class="btn btn-xs px-4 text-secondary fw-medium active"><?php echo $this->lang->line('popular_items'); ?></a></li>
													    <?php $ccc=2; 
														} ?>
													    <?php										    
													    foreach ($restaurant_details['categories'] as $key => $value) {?>
													    	<li class="item" id="categorytop<?php echo $ccc; ?>"><a href="#category-<?php echo $value['category_id']; ?>" <?php if($ccc==1){?> class="btn btn-xs px-4 text-secondary fw-medium active" <?php } else { ?> class="btn btn-xs px-4 text-secondary fw-medium"<?php  } ?>><?php echo $value['name']; ?></a></li> 
										    			<?php
										    			$ccc++;
										    			 }?>
													</ul>
												</nav>
												<!-- <button id="pnAdvancerRight" class="pn-Advancer pn-Advancer_Right move right" type="button"><i class="iicon-icon-17"></i></button> -->
											</div>
							    		<?php }?>
							    		<div class="is_close"><?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed")?'<span id="closedres">'.$this->lang->line('not_accepting_orders').'</span>':''; ?></div>
										<?php if (!empty($restaurant_details['menu_items'])) {
									        $popular_count = 0;
									        foreach ($restaurant_details['menu_items'] as $key => $value) {
									            if ($value['popular_item'] == 1) {
									                $popular_count = $popular_count + 1;
									            }
									        }
									        if ($popular_count > 0) { ?>
									        	<div class="accordion-item mb-1">
									        		<a href="#popular_menu_item" class="btn btn-sm btn-secondary w-100 px-4 text-start d-flex align-items-center justify-content-between" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="popular_menu_item">
									        			<?php echo $this->lang->line('popular_items') ?>
									        			<i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-accordion.svg" alt=""></i>
									        		</a>

												    <div id="popular_menu_item" class="accordion-collapse collapse show">
												    	<div class="accordion-body pt-4 pb-3 pb-sm-7 pb-xl-11">
												        
															<?php foreach ($restaurant_details['menu_items'] as $key => $value) {
						                						if ($value['popular_item'] == 1) { ?>
						                							<div class="item-menu d-flex align-items-md-center flex-wrap flex-md-nowrap">
																		<figure class="picture">
																			<?php /* $rest_image = (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='') ? image_url.$value['image'] : default_img; ?>
																			<img src="<?php echo $rest_image; ?>">
																			<span><?php echo $this->lang->line('popular') ?></span>
																			<?php */ ?>

																			<?php $rest_image = (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='') ? image_url.$value['image'] : default_icon_img;
																			
																			if ($value['check_add_ons'] == 1) { ?>
																				<a class="picture h-100" href="javascript:void(0);" id="addtocart-<?php echo $value['entity_id']; ?>" onclick="checkCartRestaurantDetails(<?php echo $value['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'<?php echo $restaurant_details['restaurant'][0]['timings']['closing']; ?>','addons',this.id,'no')"> 
																					<img src="<?php echo $rest_image; ?>"> 
																				</a>
																				<span><?php echo $this->lang->line('popular') ?></span>
																			<?php } else {?>
																				<a class="picture h-100" href="javascript:void(0);" id="addtocart-<?php echo $value['entity_id']; ?>" onclick="checkCartRestaurantDetails(<?php echo $value['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'<?php echo $restaurant_details['restaurant'][0]['timings']['closing']; ?>','',this.id,'no')" > <img src="<?php echo $rest_image; ?>"> </a>

																				<span><?php echo $this->lang->line('popular') ?></span>
																			<?php } ?>
																		</figure>
																		<div class="item-menu-text flex-fill px-md-4">
																			<!-- <h4><?php //echo $value['name']; ?></h4> -->
																			<!-- menu details on item name click :: start -->
																			<?php if ($value['check_add_ons'] == 1) { ?>
																				<a class="h6 w-auto" href="javascript:void(0);" id="addtocart-<?php echo $value['entity_id']; ?>" onclick="checkCartRestaurantDetails(<?php echo $value['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'<?php echo $restaurant_details['restaurant'][0]['timings']['closing']; ?>','addons',this.id,'no')"><?php echo $value['name']; ?></a>
																			<?php } else {?>
																				<a class="h6 w-auto" href="javascript:void(0);" id="addtocart-<?php echo $value['entity_id']; ?>" onclick="checkCartRestaurantDetails(<?php echo $value['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'<?php echo $restaurant_details['restaurant'][0]['timings']['closing']; ?>','',this.id,'no')" ><?php echo $value['name']; ?></a>
																			<?php } ?>

																			
																			
																			<ul class="small d-flex flex-wrap">
																				<?php
																				$food_type_name ='';
																				 foreach ($restaurant_details['restaurant'][0]['resfood_type'] as $key => $val) {
																					if($val->food_type_id == $value['food_type']){
																						$food_type_name = $val->food_type_name;break;
																					}
																				} 
																				if(!empty($food_type_name)){ ?>
																					<li><strong><?php echo $this->lang->line('food_type')." : </strong>" ?><?php echo $food_type_name; ?></li>
																				 <?php } ?>

																					<li><strong><?php echo $this->lang->line('availability')." : </strong>" ?><?php echo $value['availability']; ?></li>
																			</ul>
																			<!-- menu details on item name click :: end -->

																			<small class="d-flex w-100"><?php echo $value['menu_detail']; ?></small>

																			<strong class="text-secondary <?php if($value['offer_price']>0){ ?> text-decoration-line-through <?php } ?>">
																				<?php echo ($value['check_add_ons'] != 1)?currency_symboldisplay(number_format($value['price'],2),$restaurant_details['restaurant'][0]['currency_symbol']):(($value['price'])?currency_symboldisplay(number_format($value['price'],2),$restaurant_details['restaurant'][0]['currency_symbol']):''); ?>
																			</strong>
																			<?php if($value['offer_price']>0){ ?>
																				<strong class="text-secondary"><?php echo ($value['check_add_ons'] != 1)?currency_symboldisplay(number_format($value['offer_price'],2),$restaurant_details['restaurant'][0]['currency_symbol']):(($value['offer_price'])?currency_symboldisplay(number_format($value['offer_price'],2),$restaurant_details['restaurant'][0]['currency_symbol']):''); ?>
																			</strong>
																			<?php } ?>

																		</div>

																		<?php if ($restaurant_details['restaurant'][0]['timings']['closing'] != "Closed") {
																			if ($value['check_add_ons'] == 1) {?>
																				<div class="add-btn d-flex flex-column text-center">
																					<?php if($value['stock'] == 1 || $restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1') { ?>
																						<?php $add = (in_array($value['entity_id'], $menu_ids))?'Added':'Add'; ?>
																						<button class="btn btn-xs px-2 btn-secondary <?php echo strtolower($add); ?> addtocart-<?php echo $value['entity_id']; ?>" id="addtocart-<?php echo $value['entity_id']; ?>" <?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed")?'disabled':''; ?>  onclick="checkCartRestaurant(<?php echo $value['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'addons',this.id)" order-for-later="<?php echo ($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $value['stock'] == 0) ? '1' : '0'; ?>" > <?php echo (in_array($value['entity_id'], $menu_ids))?$this->lang->line('added'):(($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $value['stock'] == 0) ? $this->lang->line('order_for_later') : $this->lang->line('add')); ?> </button>

																						<small class="text-success"><?php echo $this->lang->line('customizable') ?></small>
																						<?php if($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $value['stock'] == 0) { ?>
																							<small class="text-danger"><?php echo $this->lang->line('out_stock') ?></small>
																							
																						<?php } ?>
																					<?php }
																					else{ ?>
																						<small class="text-danger"><?php echo $this->lang->line('out_stock') ?></small>
																					<?php } ?>
																				</div>
																			<?php } else { ?>
																				<div class="add-btn d-flex flex-column text-center">
																					<?php if($value['stock'] == 1 || $restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1'){ ?>
																						<?php $add = (in_array($value['entity_id'], $menu_ids))?'Added':'Add'; ?>
																						<button class="btn btn-xs px-2 btn-secondary <?php echo strtolower($add); ?> addtocart-<?php echo $value['entity_id']; ?>" id="addtocart-<?php echo $value['entity_id']; ?>" onclick="checkCartRestaurant(<?php echo $value['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'',this.id)" <?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed")?'disabled':''; ?> order-for-later="<?php echo ($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $value['stock'] == 0) ? '1' : '0'; ?>" > <?php echo (in_array($value['entity_id'], $menu_ids))?$this->lang->line('added'):(($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $value['stock'] == 0) ? $this->lang->line('order_for_later') : $this->lang->line('add')); ?> </button>
																						<?php if($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $value['stock'] == 0) { ?>
																							<small class="text-danger"><?php echo $this->lang->line('out_stock') ?></small>
																						<?php } ?>
																					<?php }else{ ?>
																						<small class="text-danger"><?php echo $this->lang->line('out_stock') ?></small>
																					<?php } ?>	
																				</div>
																			<?php } ?>
																		<?php } ?>
																	</div>
																<?php }
						            						}?>
						            					</div>
													</div>
												</div>
											<?php }?>
										<?php }?>
										<?php if (!empty($restaurant_details['categories'])) {
											$tottalcnt = count($restaurant_details['categories']);
									        foreach ($restaurant_details['categories'] as $key => $value) { ?>

									        	<div class="accordion-item mb-1">
									        		<a href="#category-<?php echo $value['category_id']; ?>" class="btn btn-sm btn-secondary w-100 px-4 text-start d-flex align-items-center justify-content-between" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="category-<?php echo $value['category_id']; ?>"><?php echo $value['name']; ?>
									        			<i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-accordion.svg" alt=""></i>
									        		</a>

									        		<div id="category-<?php echo $value['category_id']; ?>" class="accordion-collapse collapse show">
									        			<div class="accordion-body pt-4 pb-3 pb-sm-7 pb-xl-11">
															<?php 
															$margin_text = '';
															if($restaurant_details[$value['name']]) {
																if(count($restaurant_details[$value['name']])==1){ // && $tottalcnt==($key+1)
																	$margin_text = 'style="margin-bottom:60px !important;"';
																}
															}
															?>
															<?php if ($restaurant_details[$value['name']]) {
						                						foreach ($restaurant_details[$value['name']] as $key => $mvalue) {?>
																	<div class="item-menu d-flex align-items-md-center flex-wrap flex-md-nowrap">
																		<figure class="picture">
																			<?php /* $rest_image = (file_exists(FCPATH.'uploads/'.$mvalue['image']) && $mvalue['image']!='') ? image_url.$mvalue['image'] : default_img; ?>
																				<img src="<?php echo $rest_image; ?>">
																			<?php */ ?>
																			
																			<?php $rest_image = (file_exists(FCPATH.'uploads/'.$mvalue['image']) && $mvalue['image']!='') ? image_url.$mvalue['image'] : default_icon_img;
																			
																			if ($mvalue['check_add_ons'] == 1) {?>
																				<a href="javascript:void(0);" class="picture h-100" id="addtocart-<?php echo $mvalue['entity_id']; ?>" onclick="checkCartRestaurantDetails(<?php echo $mvalue['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'<?php echo $restaurant_details['restaurant'][0]['timings']['closing']; ?>','addons',this.id,'no')">
																					<img src="<?php echo $rest_image; ?>"> 
																				</a>
																			<?php } else {?>
																				<a href="javascript:void(0);" class="picture h-100" id="addtocart-<?php echo $mvalue['entity_id']; ?>" onclick="checkCartRestaurantDetails(<?php echo $mvalue['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'<?php echo $restaurant_details['restaurant'][0]['timings']['closing']; ?>','',this.id,'no')" >
																					<img src="<?php echo $rest_image; ?>">
																				</a>
																			<?php }  ?>
																		</figure>
																		<div class="item-menu-text flex-fill px-md-4">
																			<!-- <h4><?php //echo $mvalue['name']; ?></h4> -->
																			<!-- menu details on item name click :: start -->
																			<?php if ($mvalue['check_add_ons'] == 1) {?>
																				<a class="h6 w-auto" href="javascript:void(0);" id="addtocart-<?php echo $mvalue['entity_id']; ?>" onclick="checkCartRestaurantDetails(<?php echo $mvalue['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'<?php echo $restaurant_details['restaurant'][0]['timings']['closing']; ?>','addons',this.id,'no')"><?php echo $mvalue['name']; ?></a>
																			<?php } else {?>
																				<a class="h6 w-auto" href="javascript:void(0);" id="addtocart-<?php echo $mvalue['entity_id']; ?>" onclick="checkCartRestaurantDetails(<?php echo $mvalue['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'<?php echo $restaurant_details['restaurant'][0]['timings']['closing']; ?>','',this.id,'no')" ><?php echo $mvalue['name']; ?></a>
																			<?php }  ?>

																			<?php 
																			$mfood_type_name ='';
																			foreach ($restaurant_details['restaurant'][0]['resfood_type'] as $key => $mval){
																				if($mval->food_type_id == $mvalue['food_type']){
																					$mfood_type_name = $mval->food_type_name;
																					break;
																				}
																			}?> 
																			
																			<ul class="small d-flex flex-wrap">
																				<?php if(!empty($mfood_type_name)){ ?>
																					<li><strong><?php echo $this->lang->line('food_type')." : " ?></strong><?php echo $mfood_type_name; ?></li> 
																				<?php } ?>
																				<li><strong><?php echo $this->lang->line('availability')." : " ?></strong><?php echo $mvalue['availability']; ?></li>
																			</ul>

																			<!-- menu details on item name click :: end -->
																			<small class="d-flex w-100"><?php echo $mvalue['menu_detail']; ?></small>

																			<strong class="text-secondary <?php if($mvalue['offer_price']>0){ ?>text-decoration-line-through<?php } ?>"><?php echo ($mvalue['check_add_ons'] != 1)?currency_symboldisplay(number_format($mvalue['price'],2),$restaurant_details['restaurant'][0]['currency_symbol']):(($mvalue['price'])?currency_symboldisplay(number_format($mvalue['price'],2),$restaurant_details['restaurant'][0]['currency_symbol']):''); ?></strong>
																			<?php if($mvalue['offer_price']>0){ ?>
																				<strong class="text-secondary "><?php echo ($mvalue['check_add_ons'] != 1)?currency_symboldisplay(number_format(str_replace(",","",$mvalue['offer_price']),2),$restaurant_details['restaurant'][0]['currency_symbol']):(($mvalue['offer_price'])?currency_symboldisplay(number_format(str_replace(",","",$mvalue['offer_price']),2),$restaurant_details['restaurant'][0]['currency_symbol']):''); ?></strong>
																			<?php } ?>


																		</div>
																		<?php if ($restaurant_details['restaurant'][0]['timings']['closing'] != "Closed") {
																			if ($mvalue['check_add_ons'] == 1) {
																				if($mvalue['stock'] == 1 || $restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1'){ ?>
																					<?php $add = (in_array($mvalue['entity_id'], $menu_ids))?'Added':'Add'; ?>
																					<div class="add-btn d-flex flex-column text-center">
																						<button class="btn btn-xs px-2 btn-secondary <?php echo strtolower($add); ?> addtocart-<?php echo $mvalue['entity_id']; ?>" id="addtocart-<?php echo $mvalue['entity_id']; ?>" <?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed")?'disabled':''; ?> onclick="checkCartRestaurant(<?php echo $mvalue['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'addons',this.id)" order-for-later="<?php echo ($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $mvalue['stock'] == 0) ? '1' : '0'; ?>" > <?php echo (in_array($mvalue['entity_id'], $menu_ids))?$this->lang->line('added'):(($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $mvalue['stock'] == 0) ? $this->lang->line('order_for_later') : $this->lang->line('add')); ?> </button>

																						<small class="text-success"><?php echo $this->lang->line('customizable') ?></small>

																						<?php if($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $mvalue['stock'] == 0) { ?>
																							<small class="text-danger"><?php echo $this->lang->line('out_stock') ?></small>
																						<?php } ?>
																					</div>
																				<?php }else{ ?>
																					<div class="add-btn d-flex flex-column text-center">
																						<small class="text-danger"><?php echo $this->lang->line('out_stock') ?></small>
																					</div>
																				<?php } ?>
																			<?php } else {
																				if($mvalue['stock'] == 1 || $restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1') { ?>
																					<div class="add-btn d-flex flex-column text-center">
																						<?php $add = (in_array($mvalue['entity_id'], $menu_ids))?'Added':'Add'; ?>
																						<button class="btn btn-xs px-2 btn-secondary <?php echo strtolower($add); ?> addtocart-<?php echo $mvalue['entity_id']; ?>" id="addtocart-<?php echo $mvalue['entity_id']; ?>" onclick="checkCartRestaurant(<?php echo $mvalue['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'',this.id)" <?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed")?'disabled':''; ?> order-for-later="<?php echo ($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $mvalue['stock'] == 0) ? '1' : '0'; ?>" > <?php echo (in_array($mvalue['entity_id'], $menu_ids))?$this->lang->line('added'):(($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $mvalue['stock'] == 0) ? $this->lang->line('order_for_later') : $this->lang->line('add')); ?> </button>
																						<?php if($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $mvalue['stock'] == 0) { ?>
																							<small class="text-danger"><?php echo $this->lang->line('out_stock') ?></small>
																						<?php } ?>
																					</div>
																				<?php } else { ?>
																					<div class="add-btn">
																						<small class="text-danger"><?php echo $this->lang->line('out_stock') ?></small>
																					</div>
																				<?php } ?>
																			<?php } 
																		} ?>
																	</div>
																<?php }
						            						}?>
														</div>
													</div>
												</div>
											<?php }
				    					} ?>
				    				</div>
								<?php } 
								else {?>
									<div class="text-center py-4">
										<figure class="mb-4">											
											<img src="<?php echo base_url();?>assets/front/images/image-cart.png">
										</figure>
										<h6><?php echo $this->lang->line('no_results_found') ?></h6>
									</div>
								<?php }?>
							</div>
						</div>
						<div class="col-xl-4" id="your_cart">
							<div class="card card-xl-0 bg-body aside-cart">
								<div class="card-body container-gutter-xl py-2 py-xl-4 p-xl-4">
									<div class="card-top d-flex align-items-center justify-content-between">
										<h5><?php echo $this->lang->line('your_cart') ?></h5>
										<h5 class="opacity-50 w-auto fw-medium text-nowrap"><?php echo count($cart_details['cart_items']); ?> <?php echo $this->lang->line('items') ?></h5>

										<?php if(count($cart_details['cart_items']) > 0 ){ ?>
											<a class="btn d-xl-none btn-xs px-2 btn-secondary text-nowrap"  href="<?php echo base_url() . 'cart'; ?>"><?php echo $this->lang->line('view_cart') ?></a>
										<?php } ?>
									</div>
									<div class="d-none d-xl-inline-block border-top pt-4 mt-4 w-100">
										
									
										<?php if (!empty($cart_details['cart_items'])) { ?>
											<ul class="item-cart">
											    <?php foreach ($cart_details['cart_items'] as $cart_key => $value) { ?>
											    	<li class="d-flex align-items-center justify-content-between">
														<div class="d-flex flex-column overflow-hidden">
															<small class="text-primary fw-medium w-100"><?php echo $value['name']; ?></small>

															<?php if ($value['is_combo_item']) {?>
																<small><?php echo nl2br($value['menu_detail']); ?></small>
															<?php }?>

															<?php if (!empty($value['addons_category_list'])) {?>
																<ul class="small d-flex flex-wrap">
						    									<?php foreach ($value['addons_category_list'] as $key => $cat_value) { ?>
																	<?php /* <li><h6><?php echo $cat_value['addons_category']; ?></h6></li> */ ?>
																	
																	<?php if (!empty($cat_value['addons_list'])) {?>
																		<?php foreach ($cat_value['addons_list'] as $key => $add_value) {?>
																			<li><strong class="fw-medium"><?php echo $add_value['add_ons_name']; ?> : </strong><?php echo currency_symboldisplay(number_format($add_value['add_ons_price'],2),$restaurant_details['restaurant'][0]['currency_symbol']); ?></li>
																		<?php }?>
																		
																	<?php }?>
																<?php }?>
																</ul>
															<?php }?>

															<small class="fw-bold text-secondary"><?php echo currency_symboldisplay(number_format($value['totalPrice'],2), $restaurant_details['restaurant'][0]['currency_symbol']); ?></small>
														</div>
														<div class="number">
															<span class="icon minus" id="minusQuantity" onclick="customItemCount(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'minus',<?php echo $cart_key; ?>)"><img src="<?php echo base_url();?>assets/front/images/icon-minus.svg" alt=""></span>
															<input type="text" class="QtyNumberval" maxlength="3" value="<?php echo $value['quantity']; ?>" onfocusout="EditcustomItemCount(this.value,<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,<?php echo $cart_key; ?>)" />
															<span class="icon plus" id="plusQuantity" onclick="customItemCount(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'plus',<?php echo $cart_key; ?>)"><img src="<?php echo base_url();?>assets/front/images/icon-plus.svg" alt=""></span>
														</div>
											    	</li>
												<?php }?>
											</ul>
											<div class="d-flex justify-content-between align-items-center py-4">
												<h6 class="w-auto"><?php echo $this->lang->line('sub_total') ?></h6>
												<h6 class="w-auto"><?php echo currency_symboldisplay(number_format($cart_details['cart_total_price'],2),$restaurant_details['restaurant'][0]['currency_symbol']); ?></h6>
											</div>
											<a href="javascript:void(0);" class="btn btn-primary w-100" onclick="checkResStat();"><?php echo $this->lang->line('continue') ?></a>
											<div class="res_closed_err error" style="display: none;"></div>
											
											<?php //get System Option Data
							                    $this->db->select('OptionValue');
							                    $min_order_amount = $this->db->get_where('system_option',array('OptionSlug'=>'min_order_amount'))->first_row();
							                    $min_order_amount = (float) $min_order_amount->OptionValue;
												$min_order_txt = sprintf($this->lang->line('min_order_msg'),$min_order_amount); ?>
											
											<div class="alert alert-sm alert-primary" style="<?php echo (!in_array('Delivery', $restaurant_details['restaurant'][0]['order_mode']))?'display: none;':(($cart_details['cart_total_price'] >= $min_order_amount)?'display: none;':'display: block;'); ?>">
												<?php echo $min_order_txt; ?>
											</div>
										<?php } else { ?>
											<div class="text-center">
												<figure class="mb-4">											
													<img src="<?php echo base_url();?>assets/front/images/image-cart.png">
												</figure>
												<h6><?php echo $this->lang->line('cart_empty') ?></h6>
												<p><?php echo $this->lang->line('add_some_dishes') ?></p>
											
												<?php 
												$class_text = 'display: none;';
												if($restaurant_details && !empty($restaurant_details['restaurant']))
												{
													$class_text = (!in_array('Delivery', $restaurant_details['restaurant'][0]['order_mode']))?'display: none;':'display: block';
												}
												?>
												<div class="alert alert-sm alert-primary" style="<?php echo $class_text; ?>">
													<?php //get System Option Data
									                    $this->db->select('OptionValue');
									                    $min_order_amount = $this->db->get_where('system_option',array('OptionSlug'=>'min_order_amount'))->first_row();
									                    $min_order_amount = (float) $min_order_amount->OptionValue;
														$min_order_txt = sprintf($this->lang->line('min_order_msg'),$min_order_amount); 
													?><?php echo $min_order_txt; ?>
												</div>
											</div>
										<?php } ?>	
									</div>	
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade text-center text-lg-start" id="aboutus" role="tabpanel" aria-labelledby="about-tab">
					<?php if(!empty($restaurant_details['restaurant'][0]['about_restaurant']) || !is_null($restaurant_details['restaurant'][0]['about_restaurant'])){ ?>
						<div class="pb-4 pb-md-6">
							<h2 class="h2 pb-2 mb-6 title text-center text-lg-start"><?php echo $this->lang->line('about_restaurant') ?></h2>
							<div class="text-editor"><?php echo $restaurant_details['restaurant'][0]['about_restaurant']; ?></div>
						</div>
					<?php } ?>

					<h6><?php echo $this->lang->line('report_res_msg1'); ?></h6>
					<small class="d-inline-block w-100 mb-2"><?php echo $this->lang->line('report_res_msg2'); ?></small>
					<a href="javascript:void(0)" class="btn btn-xs btn-primary" id="report_restaurant"><?php echo $this->lang->line('report_now'); ?></a>
				</div>
				<div class="tab-pane tab-review fade" id="review" role="tabpanel" aria-labelledby="review-tab">
					<h2 class="h2 pb-2 mb-8 title text-center"><?php echo $this->lang->line('review_ratings') ?></h2>
					<?php if ($show_restaurant_reviews) { ?>

						<div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-center">
							<div class="text-rate d-flex flex-column pb-4 pb-sm-0 px-sm-8">
								<?php if ($show_restaurant_reviews) { ?>
									<div class="btn btn-xs btn-primary d-flex align-items-center mx-auto"><i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-star.svg" alt=""></i><?php echo $restaurant_details['restaurant'][0]['ratings']; ?></div>
								<?php } ?>
								<h5 class="text-center my-2"><?php echo (!empty($restaurant_reviews_count))?$restaurant_reviews_count:0; ?> <?php echo (!empty($restaurant_reviews))?(($restaurant_reviews_count > 1)?$this->lang->line('reviews'):$this->lang->line('review')):$this->lang->line('review'); ?></h5>
								<div class="d-flex justify-content-center">
									<?php for ($i=1; $i < 6; $i++) { 
										$activeClass = ''; 
										$rating_img = 'assets/front/images/icon-star.svg';
										if ($i <= $restaurant_details['restaurant'][0]['ratings']) {
										$activeClass = 'active';
										$rating_img = 'assets/front/images/icon-star-fill.svg';
										?>
									<?php } ?>
									<div class="px-1">
										<i class="icon <?php echo $activeClass; ?>"><img src="<?php echo base_url().$rating_img ; ?>" alt=""></i>
									</div>
									<?php } ?>
								</div>
								
							</div>
							<div class="d-flex justify-content-center justify-column-sm-start">
								<ul class="d-flex flex-column pt-4 pt-sm-0 px-sm-8 small">
									<?php for ($i=5; $i > 0 ; $i--) { ?>
									<li class="d-flex align-items-center">
										<span><i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-star.svg" alt=""></i><?php echo $i; ?></span>
										<div class="progress">		
											<?php 
											$noOfReviews = $this->restaurant_model->getReviewsNumber($restaurant_details['restaurant'][0]['content_id'],$i);

											$percentage=0;
											if($restaurant_reviews_count>0){
												$percentage = ($restaurant_details['restaurant'][0]['is_rating_from_res_form'] == '1' && $noOfReviews == 1) ? '50' : ($noOfReviews * 100) / $restaurant_reviews_count;	
											} ?>
											<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $percentage.'%'; ?>">									
											</div>								  
										</div> 
										<span><?php echo ($restaurant_details['restaurant'][0]['is_rating_from_res_form'] == '1' && $noOfReviews == 1) ? $restaurant_reviews_count : $noOfReviews; ?></span>
									</li>
									<?php } ?>
								</ul>
							</div>
							
						</div>

						<div id="limited-reviews">
							<?php if (!empty($restaurant_reviews) && $show_restaurant_reviews) {?>
								<div class="pt-8">
								<?php foreach ($restaurant_reviews as $key => $value) { 
									if ($key <= (review_count-1)) { ?>
										<div class="item-review d-flex p-4 bg-light mb-2">
											<?php /* ?>
											<figure class="picture rounded-circle">
												<img src="<?php echo (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='') ? image_url.$value['image']:default_icon_img; ?>">
											</figure>
											<?php */ ?>
											<div class="text-review flex-fill">
												<h6 class="mb-1"><?php echo $value['first_name'].' '.$value['last_name']; ?></h6>
												<ul class="d-flex flex-wrap small">
													<li class="fw-medium text-primary"><i class="icon <?php echo $activeClass; ?>"><img src="<?php echo base_url();?>assets/front/images/icon-star.svg" alt=""></i> (<?php echo number_format($value['rating'],1); ?>)</li>
													<li class="fw-medium"><i class="icon <?php echo $activeClass; ?>"><img src="<?php echo base_url();?>assets/front/images/icon-calendar.svg" alt=""></i> <?php echo $this->common_model->dateFormat($value['created_date']); ?></li>
												</ul>
												<blockquote><?php echo ucfirst($value['review']); ?></blockquote>
											</div>
										</div>
									<?php }
								}?>
								</div>
							<?php } else { 
								if($restaurant_details['restaurant'][0]['is_rating_from_res_form'] != '1') { ?>
									<div class="pt-8">
										<h6><?php echo $this->lang->line('no_review_found') ?></h6>
									</div>
								<?php } ?>
							<?php } ?>
						</div>
						<div id="all_reviews" class="d-none" >
							<?php /* if (!empty($restaurant_reviews)) {
								foreach ($restaurant_reviews as $key => $value) {
									if ($key > (review_count-1)) { ?>
										<div class="review-list">
											<div class="review-img">
												<div class="user-images">
													<img src="<?php echo (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='') ? image_url.$value['image']:default_icon_img; ?>">
												</div>
											</div>
											<div class="review-content">
												<p>"<?php echo ucfirst($value['review']); ?>"</p>
												<div class="user-name-date">
													<div class="review-star">
														<span><i class="iicon-icon-05"></i><?php echo number_format($value['rating'],1); ?></span>
													</div>
													<div class="review-date">
														<h3><?php echo $value['first_name'].' '.$value['last_name']; ?></h3>
														<span><?php echo $this->common_model->dateFormat($value['created_date']); ?></span>
													</div>
												</div>
											</div>
										</div>
									<?php }
								}
							} */ ?>
						</div>

						<?php if (!empty($restaurant_reviews) && $restaurant_reviews_count > review_count && $show_restaurant_reviews) { ?>
							<div class="d-flex justify-content-center mt-2">
								<input type="hidden" name="page_no" id="page_no" value="2">
								<input type="hidden" name="res_content_id_val" id="res_content_id_val" value="<?php echo $restaurant_details['restaurant'][0]['content_id']; ?>">
								<button id="review_button" class="btn btn-sm btn-primary" onclick="showAllReviews()"><?php echo $this->lang->line('load_more') ?></button>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
				<div class="tab-pane fade" id="online_reservation" role="tabpanel" aria-labelledby="reservation-tab">
					<?php if($this->session->userdata('UserID')) { ?>

						<?php if($restaurant_details['restaurant'][0]['allow_event_booking'] == 1){ ?>
							<?php if($restaurant_details['restaurant'][0]['enable_table_booking'] == 1){ ?>
							<div class="nav d-flex justify-content-center mb-8" id="bookTab" role="tablist">
									<a href="javascript:void(0);" class="btn btn-sm px-4 border border-primary text-primary active" id="event_link" id="event-tab" data-toggle="tab" data-target="#event_section" type="button" role="tab" aria-controls="event" aria-selected="true"><?php echo $this->lang->line('book_event'); ?></a>
									<a href="javascript:void(0);" class="btn btn-sm px-4 border-primary text-primary" id="table_link" data-toggle="tab" data-target="#table_section" type="button" role="tab" aria-controls="table" aria-selected="false"><?php echo $this->lang->line('book_table'); ?></button></a>
							</div>
							<?php } ?>
						<?php } ?>
						<div class="tab-content" id="myTabContent">
							<?php if($restaurant_details['restaurant'][0]['allow_event_booking'] == 1){ ?>
								<div class="tab-pane fade show active" id="event_section" role="tabpanel" aria-labelledby="event-tab">
									<h2 class="h2 pb-2 mb-8 title text-center"><?php echo $this->lang->line('book_your_event') ?></h2>
									<form id="check_event_availability" class="form-horizontal" name="check_event_availability" method="post">
										<input type="hidden" name="event_restaurant_id" id="event_restaurant_id" value="<?php echo $restaurant_details['restaurant'][0]['restaurant_content_id']; ?>">
										<input type="hidden" name="event_user_id" id="event_user_id" value="<?php echo $this->session->userdata('UserID'); ?>">
										<input type="hidden" name="event_name" id="event_name" value="<?php echo $this->session->userdata('userFirstname').' '.$this->session->userdata('userLastname'); ?>">
										
										<div class="row row-grid">
											<div class="col-lg-6">
												<div class="form-floating">
													
													<input type='text' class="form-control" name="date_time" id='datetimepicker1' placeholder="<?php echo $this->lang->line('pick_date') ?>"  readonly="readonly" value = "<?php echo (!empty($this->session->userdata('UserID')) && $this->session->userdata('is_user_login') == 1 && !empty($this->session->userdata('date_time')))? $this->session->userdata('date_time'): '' ?>" >
													<label for="datetimepicker1"><?php echo $this->lang->line('pick_date') ?><span class="required">*</span></label>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-floating">
													<?php $message = $this->lang->line('max_people');
														  $event_capacity = sprintf($message,$restaurant_details['restaurant'][0]['event_minimum_capacity'],$restaurant_details['restaurant'][0]['capacity'])
													 ?>
													<input type="number" name="no_of_people" id="no_of_people" placeholder="0" class="form-control" autocomplete="off" value="<?php echo (!empty($this->session->userdata('UserID')) && $this->session->userdata('is_user_login') == 1 && !empty($this->session->userdata('no_of_people')))? $this->session->userdata('no_of_people'): ' ' ?>">
													<label for="no_of_people"><?php echo $this->lang->line('how_many_people') ?><sup>*</sup></label>
													<small><?php echo $event_capacity ?></small>
												</div>
											</div>
											<?php if (!empty($restaurant_details['packages']) && count($restaurant_details['packages']) > 0) {
											?>
												<div class="col-sm-12">
													<div class="form-floating">
														<select name="package_id" class="form-control" id="package_id" onchange="getPackageInfo(this.value,'<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>')" placeholder="<?php echo $this->lang->line('package') ?>">
															<option value=""><?php echo $this->lang->line('select') ?></option>
															<?php foreach ($restaurant_details['packages'] as $key => $value) { ?>
																<option value="<?php echo $value['content_id']; ?>"><?php echo $value['name']; ?></option>
															<?php } ?>
														</select>
														<label class="control-label" for="package_id"><?php echo $this->lang->line('package') ?></label>
													</div>
													<div class="package-content" id="package_section" data-id="" style="display: none;">
														<div class="detail-list-box" id="package_detaildiv">
															
														</div>
													</div>
												</div>
											<?php } ?>

											<div class="col-lg-12">
												<div class="form-floating">
													<textarea class="form-control" placeholder="<?php echo $this->lang->line('additional_comment') ?>" name="user_comment" id="user_comment" rows="5"><?php echo (!empty($this->session->userdata('UserID')) && $this->session->userdata('is_user_login') == 1 && !empty($this->session->userdata('event_user_request'))) ? ($this->session->userdata('event_user_request')) : ''; ?></textarea>
													<label for="user_comment"><?php echo $this->lang->line('additional_comment') ?></label>
													<small id="max_people"><?php echo $this->lang->line('max_allowed') ?></small>
												</div>
												<div class="form-action">
					                                <button type="submit" name="submit_page" id="submit_page" value="Check Availability" class="btn btn-primary load_more_btn"><?php echo $this->lang->line('check_avail') ?></button>
												</div>
											</div>
										</div>
										<!-- <div class="row">
											<div class="col-12">
			                                    
			                                </div>
			                                <div class="col-md-3">
			                                    <div class="form-floating">
			                                       	<input type="text" name="first_name" id="event_first_name" class= "form-control" value="<?php echo ($this->session->userdata('userFirstname'))?($this->session->userdata('userFirstname')):"" ?>" placeholder="<?php echo $this->lang->line('enter_first_name'); ?>">
			                                    </div>  
			                                </div>
			                                <div class="col-md-3">
			                                    <div class="form-floating">
			                                        <input type="text" name="last_name" id="event_last_name" class= "form-control" value="<?php echo ($this->session->userdata('userLastname'))?($this->session->userdata('userLastname')):"" ?>"  placeholder="<?php echo $this->lang->line('enter_last_name'); ?>">
			                                    </div>
			                                </div>
			                                <div class="col-md-3">
			                                    <div class="form-floating table_class">
			                                    	<input type="hidden" name="phone_code" id="event_phone_code" class="form-control" value="">
			                                        <input type="tel" name="phone_number_inp" id="event_phone_number_inp" class="form-control" value="<?php echo ($this->session->userdata('userPhone'))?($this->session->userdata('userPhone')):"" ?>" maxlength="14" placeholder="<?php echo $this->lang->line('enter_mobile_number'); ?>">
			                                    <div id="event_phone_number_error"></div>
			                                  	</div>
			                               	</div>
			                                <div class="col-md-3">  
			                                    <div class="form-floating">
			                                        <input type="text" name="email" id="event_email" class= "form-control" value="<?php echo ($this->session->userdata('userEmail'))?($this->session->userdata('userEmail')):"" ?>" placeholder="<?php echo $this->lang->line('enter_email_address'); ?>">  
			                                        <div id="event_email_error"></div>
			                                    </div>
			                                </div>
										</div> -->
									</form>
								</div>
							<?php } ?>

							<?php if($restaurant_details['restaurant'][0]['enable_table_booking'] == 1){ ?>
								<div class="tab-pane fade <?php if($restaurant_details['restaurant'][0]['allow_event_booking'] != 1){ ?> show active <?php } ?>" id="table_section" role="tabpanel" aria-labelledby="table-tab">
									<h2 class="h2 pb-2 mb-8 title text-center"><?php echo $this->lang->line('book_table') ?></h2>
									<form id="check_table_availability" class="form-horizontal" name="check_table_availability" method="post">
										<input type="hidden" name="table_restaurant_id" id="table_restaurant_id" value="<?php echo $restaurant_details['restaurant'][0]['restaurant_content_id']; ?>">
										<input type="hidden" name="table_user_id" id="table_user_id" value="<?php echo $this->session->userdata('UserID'); ?>">
										<input type="hidden" name="table_name" id="table_name" value="<?php echo $this->session->userdata('userFirstname').' '.$this->session->userdata('userLastname'); ?>">
										<div class="row row-grid">
											<div class="col-md-4">
												<div class="form-floating">
													<select name="datepicker" onchange="addSlot()" class="form-control" id="datepicker">
			                                            <option value=""><?php echo $this->lang->line('select_day') ?></option>
			                                            <?php foreach ($restaurant_details['timearr'] as $key => $value) { ?>
			                                            	<option value="<?php echo $value ?>" <?php echo (!empty($this->session->userdata('UserID')) && $this->session->userdata('is_user_login') == 1 && ($this->session->userdata('booking_date') == $value)) ? 'selected' : ' '; ?>><?php echo $value ?></option>
			                                            <?php } ?>
			                                        </select>
			                                        <label for="datepicker" class="control-label"><?php echo $this->lang->line('what_day') ?><span class="required">*</span></label>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-floating">
													<select name="starttime" id="starttime" onchange="addEndTimeSlot('is_start')" class="form-control">
													    <?php foreach ($restaurant_details['timeslots'] as $key => $value) { ?>
													        <option value="<?php echo $value ?>" <?php echo (!empty($this->session->userdata('UserID')) && $this->session->userdata('is_user_login') == 1 && ($this->session->userdata('start_time') == $value)) ? 'selected' : ' '; ?>><?php echo $value;?></option>
													    <?php } ?>
													</select>
													<label for="starttime"><?php echo $this->lang->line('start_time') ?><span class="required">*</span></label>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-floating">
													
													<select name="endtime" id="endtime" onchange="addEndTimeSlot('is_end')" class="form-control">
														<?php $arry_size = sizeof($restaurant_details['timeslots']); ?>
													    <?php foreach ($restaurant_details['timeslots'] as $key => $value) {
													    $selected = (!empty($this->session->userdata('UserID')) && $this->session->userdata('is_user_login') == 1 && ($this->session->userdata('end_time') == $value)) ? ('selected') : (($this->session->userdata('is_user_login') != 1)&&($key==$arry_size-1) ? 'selected' : "");

													    ?>
													        <option value="<?php echo $value ?>" <?php echo $selected; ?>><?php echo $value;?></option>
													    <?php } ?>
													</select>
													<label for="endtime"><?php echo $this->lang->line('end_time') ?><span class="required">*</span></label>
												</div>
											</div>
											<div class="col-lg-12">
												<div class="form-floating">
													
													<?php $message = $this->lang->line('max_people');
														  $table_capacity = sprintf($message,$restaurant_details['restaurant'][0]['table_minimum_capacity'],$restaurant_details['restaurant'][0]['table_booking_capacity'])
													 ?>
													
													<input type="number" name="no_of_people" id="no_of_people" class="form-control" autocomplete="off" placeholder="<?php echo $this->lang->line('how_many_people') ?>" value="<?php echo (!empty($this->session->userdata('UserID')) && $this->session->userdata('is_user_login') == 1 && !empty($this->session->userdata('no_of_people')))? $this->session->userdata('no_of_people'): ' ' ?>">
													<label for="no_of_people" class="control-label"><?php echo $this->lang->line('how_many_people') ?><span class="required">*</span></label>
													<small class="max-event-people"><?php echo $table_capacity ?></small>
												</div>
											</div>
											<!-- <div class="col-12">
			                                    <label for="event_first_name"><?php echo $this->lang->line('personal_details') ?><span class="required">*</span></label>
			                                </div> -->
			                                <div class="col-md-6">
			                                    <div class="form-floating">
			                                       	<input type="text" name="first_name" id="event_first_name" class= "form-control" value="<?php echo ($this->session->userdata('userFirstname'))?($this->session->userdata('userFirstname')):"" ?>" maxlength="20" placeholder="<?php echo $this->lang->line('enter_first_name'); ?>">
			                                       	<label for="event_first_name"><?php echo $this->lang->line('first_name') ?></label>
			                                    </div>  
			                                </div>
			                                <div class="col-md-6">
			                                    <div class="form-floating">
			                                        <input type="text" name="last_name" id="event_last_name" class= "form-control" value="<?php echo ($this->session->userdata('userLastname'))?($this->session->userdata('userLastname')):"" ?>" maxlength="20" placeholder="<?php echo $this->lang->line('enter_last_name'); ?>">
			                                        <label for="event_last_name"><?php echo $this->lang->line('last_name') ?></label>
			                                    </div>
			                                </div>
			                                <div class="col-md-6">
			                                    <div class="form-floating table_class">
			                                    	<input type="hidden" name="phone_code" id="phone_code" class="form-control" value="">
			                                        <input type="tel" name="phone_number_inp" id="phone_number_inp" class="form-control" value="<?php echo ($this->session->userdata('userPhone'))?($this->session->userdata('userPhone')):"" ?>" maxlength="12" placeholder="<?php echo $this->lang->line('enter_mobile_number'); ?>">
			                                    	<div id="event_phone_number_error"></div>
			                                    	<label for="phone_code"><?php echo $this->lang->line('phone_number') ?></label>
			                                  	</div>
			                               	</div>
			                                <div class="col-md-6">  
			                                    <div class="form-floating">
			                                        <input type="email" name="email" id="event_email" class="form-control" value="<?php echo ($this->session->userdata('userEmail'))?($this->session->userdata('userEmail')):"" ?>" placeholder="<?php echo $this->lang->line('enter_email_address'); ?>" maxlength="50">  
			                                        <div id="event_email_error"></div>
			                                        <label for="event_email"><?php echo $this->lang->line('email') ?></label>
			                                    </div>
			                                </div>
											<div class="col-lg-12">
												<div class="form-floating">
													<textarea class="form-control" name="user_comment" id="user_comment" rows="5" placeholder="<?php echo $this->lang->line('additional_comment') ?>"><?php echo (!empty($this->session->userdata('UserID')) && $this->session->userdata('is_user_login') == 1 && !empty($this->session->userdata('user_request'))) ? ($this->session->userdata('user_request')) : ''; ?></textarea>
													<label for="user_comment"><?php echo $this->lang->line('additional_comment') ?></label>
													<small id="max_people"><?php echo $this->lang->line('max_allowed') ?></small>
												</div>
												<div class="alert alert-warning active">
													<?php echo $this->lang->line('table_booking_note') ?>
												</div>
												<div class="form-action">
					                                <button type="submit" name="submit_page" id="submit_page" value="Check Availability" class="btn btn-primary load_more_btn"><?php echo $this->lang->line('check_avail') ?></button>
												</div>
											</div>
										</div>
									</form>
								</div>
							<?php } ?>
						</div>

					<?php }else{ ?>
						<p><?php echo $this->lang->line('please');?> <a href="<?php echo base_url();?>home/login" class="text-decoration-underline"><?php echo $this->lang->line('title_login') ?></a> <?php echo $this->lang->line('to_continue_text');?></p>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php if (!empty($restaurant_details['categories'])) {?>
	<!-- <script type="text/javascript" src="<?php //echo base_url(); ?>assets/front/js/tab-slider.js"></script> -->
	
<?php }?>

<div class="modal modal-variation product-detail" id="menuDetailModal"></div>
<div class="modal modal-variation product-detail" id="addonsMenuDetailModal"></div>
<div class="modal" id="myModal"></div>

<div class="modal" id="myconfirmModal">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content p-4 p-xl-8">
			<a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close"><img src="<?php echo base_url();?>assets/front/images/icon-close.svg" alt=""></a>
			
			<div class="title pb-2 mb-6 d-flex flex-column">
	        	<h4 class="text-capitalize mb-1"><?php echo $this->lang->line('add_to_cart') ?> ?</h4>
	        	<small><?php echo $this->lang->line('menu_already_added') ?></small>
        	</div>
        	<form id="custom_items_form">
	      		<label class="mb-2"><?php echo $this->lang->line('want_to_add_new_item') ?></label>
	      		<div class="form-check mb-2">
      				<input type="hidden" name="con_entity_id" id="con_entity_id" value="">
      				<input type="hidden" name="con_restaurant_id" id="con_restaurant_id" value="">
      				<input type="hidden" name="con_item_id" id="con_item_id" value="">
      				<input type="radio" class="form-check-input radio_addon" checked name="addedToCart" id="addnewitem" value="addnewitem">
      				<label class="form-check-label" for="addnewitem"><?php echo $this->lang->line('as_new_item') ?></label>
      			</div>
      			<div class="form-check mb-2">
      				<input type="radio" class="form-check-input radio_addon" name="addedToCart" id="increaseitem" value="increaseitem">
      				<label class="form-check-label" for="increaseitem"><?php echo $this->lang->line('increase_quantity') ?></label>
      			</div>
	      		<button type="button" class="btn btn-sm btn-primary mt-5" id="addtocart" onclick="ConfirmCartAdd()"><?php echo $this->lang->line('add_to_cart') ?></button>
		    </form>
		</div>
	</div>
</div>
<div class="modal" id="anotherRestModal" >
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
      			<div class="form-check mb-2">
      				<input type="radio" class="form-check-input radio_addon" name="addNewRestaurant" id="keepOld" value="keepOld">
      				<label class="form-check-label" for="keepOld"><?php echo $this->lang->line('keep_old') ?></label>
      			</div>
	      		<button type="button" class="btn btn-sm btn-primary mt-5" id="cartrestaurant" onclick="ConfirmCartRestaurant()"><?php echo $this->lang->line('confirm') ?></button>
			</form>
		</div>
	</div>
</div>
<div class="modal" id="myconfirmModalDetails" >
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
      				<input type="radio" class="form-check-input radio_addon" name="addedToCart1" id="addnewitem1" value="addnewitem">
      				<label class="form-check-label" for="addnewitem1"><?php echo $this->lang->line('as_new_item') ?></label>
	      		</div>
	      		<div class="form-check">
      				<input type="radio" class="form-check-input radio_addon" name="addedToCart1" id="increaseitem1" value="increaseitem1">
      				<label class="form-check-label" for="increaseitem1"><?php echo $this->lang->line('increase_quantity') ?></label>
	      		</div>
	      		<button type="button" class="btn btn-sm btn-primary mt-5" id="addtocart1" onclick="ConfirmCartAddDetails()"><?php echo $this->lang->line('add_to_cart') ?></button>
      		</form>
	    </div>
	</div>
</div>
<div class="modal" id="reportRestaurantModal" >
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content p-4 p-xl-8">
			<a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close"><img src="<?php echo base_url();?>assets/front/images/icon-close.svg" alt=""></a>
			<h2 class="h2 pb-2 mb-8 title text-center"><?php echo $this->lang->line('report_error') ?></h2>

			<form id="report_res_form">
				<div class="box-item border px-4 pt-5 pb-4 mb-2">
					<label><?php echo $this->lang->line('whats_wrong'); ?>?</label>
				
					<div class="form-check mb-1">
						<input type="checkbox" name="report_topic[]" class="form-check-input" id="phone_number" value="phone_number">
						<label class="form-check-label" for="phone_number"><?php echo $this->lang->line('phone_no') ?></label>
					</div>
					<div class="form-check mb-1">
						<input type="checkbox" name="report_topic[]" class="form-check-input" id="res_address" value="address">
						<label class="form-check-label" for="res_address"><?php echo $this->lang->line('address') ?></label>
					</div>
					<div class="form-check mb-1">
						<input type="checkbox" name="report_topic[]" class="form-check-input" id="menu_check" value="menu">
						<label class="form-check-label" for="menu_check"><?php echo $this->lang->line('menu') ?></label>
					</div>
					<div class="form-check mb-1">
						<input type="checkbox" name="report_topic[]" class="form-check-input" id="report_other" value="other">
						<label class="form-check-label" for="report_other"><?php echo $this->lang->line('other') ?></label>
					</div>
				</div>
				<div class="form-floating">
	                <input type="email" name="email_address" id="email_address" class="form-control" placeholder="<?php echo $this->lang->line('email') ?> (<?php echo $this->lang->line('required') ?>)">
	                <label><?php echo $this->lang->line('email') ?> (<?php echo $this->lang->line('required') ?>)</label>
	                <div class="email_address_error"></div>
	            </div>
	            <div class="form-floating">
	                <textarea class="form-control" name="message" id="message" placeholder="<?php echo $this->lang->line('message') ?> (<?php echo $this->lang->line('required') ?>)"></textarea>
	                <label><?php echo $this->lang->line('message') ?> (<?php echo $this->lang->line('required') ?>)</label>
	                <div class="message_error"></div>
	            </div>
	            <div class="action-button">
	                <button type="submit" name="submit_report" id="submit_report" value="Submit" class="btn btn-primary w-100"><?php echo $this->lang->line('submit') ?></button>
	            </div>
			</form>
		</div>
	</div>
</div>
<div class="modal text-center" id="table-booking-available" >
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content p-4 p-xl-8">
			<a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close"><img src="<?php echo base_url();?>assets/front/images/icon-close.svg" alt=""></a>
			<h2 class="h2 pb-2 mb-8 title text-center"><?php echo $this->lang->line('booking_availability') ?></h2>

			<figure class="mb-4">
				<img src="<?php echo base_url();?>assets/front/images/image-book.svg" alt="<?php echo $this->lang->line('booking_availability') ?>">
			</figure>
			<h6 class="mb-1"><?php echo $this->lang->line('booking_available')?></h6>
			
			<input type="hidden" id="comment" name="comment" value="">
      		<?php if (!empty($this->session->userdata('UserID')) && ($this->session->userdata('is_user_login') == 1)) { ?>
      			<small><?php echo $this->lang->line('proceed_further') ?></small>
      			<div class="d-flex mx-auto mt-4">
	      			<button class="btn btn-sm btn-primary" data-dismiss="modal" data-toggle="modal" onclick="confirmTableBooking()"><?php echo $this->lang->line('request') ?></button>
	      			<div class="p-1"></div>
	      			<button class="btn btn-sm btn-danger" data-dismiss="modal" data-toggle="modal"><?php echo $this->lang->line('cancel') ?></button>
      			</div>
      		<?php } 
      		else { ?>
      			<small><?php echo $this->lang->line('please') ?> <a class="text-decoration-underline" href="<?php echo base_url();?>home/login"><?php echo $this->lang->line('title_login') ?></a> <?php echo $this->lang->line('book_avail_text') ?></small>
      		<?php }?>
		</div>
	</div>
</div>
<div class="modal text-center" id="booking-available" >
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content p-4 p-xl-8">
			<a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close"><img src="<?php echo base_url();?>assets/front/images/icon-close.svg" alt=""></a>
			<h2 class="h2 pb-2 mb-8 title text-center"><?php echo $this->lang->line('booking_availability') ?></h2>

			<figure class="mb-4">
				<img src="<?php echo base_url();?>assets/front/images/image-book.svg" alt="<?php echo $this->lang->line('booking_availability') ?>">
			</figure>
			<h6 class="mb-1"><?php echo $this->lang->line('booking_available')?></h6>
			

			<input type="hidden" id="comment" name="comment" value="">
      		<?php if (!empty($this->session->userdata('UserID')) && ($this->session->userdata('is_user_login') == 1)) { ?>
      			<small><?php echo $this->lang->line('proceed_further') ?></small>

      			<div class="d-flex mx-auto mt-4">
	      			<button class="btn btn-sm btn-primary" data-dismiss="modal" data-toggle="modal" onclick="confirmBooking()"><?php echo $this->lang->line('request') ?></button>
	      			<div class="p-1"></div>
	      			<button class="btn btn-sm btn-danger" data-dismiss="modal" data-toggle="modal"><?php echo $this->lang->line('cancel') ?></button>
      			</div>
      		<?php } 
      		else { ?>
      			<small><?php echo $this->lang->line('please') ?> <a class="text-decoration-underline" href="<?php echo base_url();?>home/login"><?php echo $this->lang->line('title_login') ?></a> <?php echo $this->lang->line('book_avail_text') ?></small>
      		<?php }?>
		</div>
	</div>
</div>
<div class="modal text-center" id="booking-not-available">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content p-4 p-xl-8">
			<a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close"><img src="<?php echo base_url();?>assets/front/images/icon-close.svg" alt=""></a>
			<h2 class="h2 pb-2 mb-8 title text-center"><?php echo $this->lang->line('booking_availability') ?></h2>

			<figure class="mb-4">
				<img src="<?php echo base_url();?>assets/front/images/image-book.svg" alt="<?php echo $this->lang->line('booking_availability') ?>">
			</figure>
			<h6 class="mb-1"><?php echo $this->lang->line('booking_not_available') ?></h6>
			<small><?php echo $this->lang->line('no_bookings_avail') ?></small>
      		<button class="btn btn-sm btn-danger mx-auto mt-4" data-dismiss="modal"><?php echo $this->lang->line('cancel') ?></button>
		</div>
	</div>
</div>
<div class="modal text-center" id="table-booking-confirmation">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content p-4 p-xl-8">
			<a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close"><img src="<?php echo base_url();?>assets/front/images/icon-close.svg" alt=""></a>
			<h2 class="h2 pb-2 mb-8 title text-center"><?php echo $this->lang->line('booking_confirmation') ?></h2>
      		
      		<figure class="mb-4">
				<img src="<?php echo base_url();?>assets/front/images/image-book-event.svg" alt="<?php echo $this->lang->line('booking_availability') ?>">
			</figure>
			<h6><?php echo $this->lang->line('table_booking_confirmed_text1') ?></h6>
			<!-- <small class="mt-1"><?php //echo $this->lang->line('booking_confirmed_text2') ?></small> -->
      		<a href="<?php echo base_url().'myprofile/view-my-tablebookings'; ?>" class="btn btn-sm btn-primary mx-auto mt-4"><?php echo $this->lang->line('view_tablebookings') ?></a>
		</div>
	</div>
</div>
<div class="modal text-center" id="booking-confirmation">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content p-4 p-xl-8">
			<a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close"><img src="<?php echo base_url();?>assets/front/images/icon-close.svg" alt=""></a>
			<h2 class="h2 pb-2 mb-8 title text-center"><?php echo $this->lang->line('booking_confirmation') ?></h2>
      		
      		<figure class="mb-4">
				<img src="<?php echo base_url();?>assets/front/images/image-book-event.svg" alt="<?php echo $this->lang->line('booking_availability') ?>">
			</figure>
			<h6><?php echo $this->lang->line('booking_confirmed_text1') ?></h6>
			<!-- <small class="mt-1"><?php //echo $this->lang->line('booking_confirmed_text2') ?></small> -->
      		<a href="<?php echo base_url().'myprofile/view-my-bookings'; ?>" class="btn btn-sm btn-primary mx-auto mt-4"><?php echo $this->lang->line('view_bookings') ?></a>
		</div>
	</div>
</div>
<div class="modal text-center" id="booking-not-available-capicity" >
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content p-4 p-xl-8">
			<a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close"><img src="<?php echo base_url();?>assets/front/images/icon-close.svg" alt=""></a>
			<h2 class="h2 pb-2 mb-8 title text-center"><?php echo $this->lang->line('booking_availability') ?></h2>

			<figure class="mb-4">
				<img src="<?php echo base_url();?>assets/front/images/image-book.svg" alt="<?php echo $this->lang->line('booking_availability') ?>">
			</figure>
			<h6 class="mb-1"><?php echo $this->lang->line('booking_not_available') ?></h6>
			<small id="less" class="display-yes"><?php echo $this->lang->line('less_bookings_avail_capacity') ?> <span></span>.</small>
      		<small id="more" class="display-no"><?php echo $this->lang->line('no_bookings_avail_capacity') ?> <span></span>.</small>
      		<span id="start_time_less" class="d-none"></span>
      		<button class="btn btn-sm btn-danger mx-auto mt-4" data-dismiss="modal"><?php echo $this->lang->line('cancel') ?></button>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/front/js/scripts/admin-management-front.js?v3"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/moment.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/multiselect/dashboard/jquery.sumoselect.min.js"></script>
<script src="<?php echo base_url(); ?>assets/front/js/bootstrap-tagsinput.js"></script>
<!-- for review/rating and menu -->
<script type="text/javascript">
$('#booking-confirmation').on('hidden.bs.modal', function () {
	window.location.href = BASEURL+"myprofile/view-my-bookings";
});
$('#table-booking-confirmation').on('hidden.bs.modal', function () {
	window.location.href = BASEURL+"myprofile/view-my-tablebookings";
});
$(function () {
    var dateToday = new Date();
    dateToday.setMinutes( dateToday.getMinutes() + 15 );
    var maxDate = new Date();
    maxDate.setMonth(maxDate.getMonth() + 3, 0);
	maxDate = new Date(maxDate);
    $('#datetimepicker1').datetimepicker({ 
		minDate: dateToday,
		ignoreReadonly: true,
		useCurrent: false,
		defaultDate: dateToday,
		maxDate: maxDate,
		toolbarPlacement: 'top',
   });
});
jQuery(document).ready(function() {
    $('.sumo').SumoSelect({search: true, searchText: "<?php echo $this->lang->line('search'); ?>"+ ' ' + "<?php echo $this->lang->line('here'); ?>...", selectAll: true , placeholder : "<?php echo $this->lang->line('select_').' '.$this->lang->line('here'); ?>" });

	/*$('.accordion-item > a ').click(function(){
		event.preventDefault();

		if($(this).hasClass('active')){
			$(this).removeClass('active');
		}
		else{
			$(this).addClass('active');
		}
	});*/
	
	$('nav.slider-loop').find('a').on('click', function () {
		var collapse_c = $('.accordion-item > a[href*='+$(this).attr('href').substring(1)+']');
		if(collapse_c.hasClass('collapsed')){
			$(collapse_c).click();
		}
		//var totalheight = $('.slider-tag').outerHeight() + $('header').outerHeight() + 60;
		var totalheight = $('.slider-tag').outerHeight() + $('header').outerHeight() + 45;
		var $el = $(this)
		, id = $el.attr('href');
		//for mobile :Start
		if ($(window).outerWidth() <= 1199) {
			$('html, body').animate({
				scrollTop: $(id).offset().top -107
			}, 500);
		}else{
			$('html, body').animate({
			scrollTop: $(id).offset().top - totalheight
			}, 500);
		}	
		//for mobile : end
		return false;
	});

	$('body').addClass("cart_bottom");

	var count = '<?php echo count($cart_details['cart_items']); ?>'; 
	$('#cart_count').html(count);
	if(count != '0'){
		$('body').addClass("cart_bottom");
		//$("#your_cart").addClass("cart_bottom");
	} else {
		$('body').addClass("cart_bottom");
		//$("#your_cart").removeClass("cart_bottom");
	}
	$(window).keydown(function(event){
		if(event.keyCode == 13) {
		  event.preventDefault();
		  return false;
		}
	});	
});
//restricting to enter more than 4 digits in input type number
//$(document).on('input','#no_of_people',function(){
$('#no_of_people').keydown(function(event){
	this.value = this.value.replace(/[^0-9]/g,'').replace(/(\..*)\./g, '$1');
	if(event.keyCode == 190 || event.keyCode == 110) {
		return false;
	}
	$('input[type=number][max]:not([max=""])').on('input', function(ev) {
        var people_maxlength = $(this).attr('max').length;
        var value = $(this).val();
        if (value && value.length >= people_maxlength) {
          $(this).val(value.substr(0, people_maxlength));
        }
    });
});

$(function() {
    // Check Radio-box
    $(".rating input:radio").filter('[value=3]').prop('checked', true);
    $('.rating input').click(function () {
        $(".rating span").removeClass('checked');
        $(this).parent().addClass('checked');
    });
    $('input:radio').change(
      function(){
        var userRating = this.value;
    }); 
    
	//Coupon slider
	var rtl = (SELECTED_LANG == 'ar')?true:false;
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
});
//check restaurant : closed/offline/deactive
function checkResStat() {
	var restaurant_id = '<?php echo $cart_restaurant; ?>';
	var is_scheduling_allowed = <?php echo ($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1') ? 1 : 0; ?>;
	var menu_ids = <?php echo json_encode($menu_ids); ?>;
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
</script>
<script src="<?php echo base_url();?>assets/front/js/tiny-slider.js"/>


<script type="text/javascript">
/*$(document).on('ready', function() {
	$(".footer-area").addClass("cart_footer");
});*/
</script>
    <script>
    var doc = document,
      slideList = doc.querySelectorAll('.slider-tag > div'),
      toggleHandle = doc.querySelector('.nav-toggle-handle'),
      divider = window.innerHeight / 2,
      scrollTimer,
      resizeTimer; 
     if (window.addEventListener) {
     window.addEventListener('scroll', function () {
      clearTimeout(scrollTimer);
      scrollTimer = setTimeout(function () {
        [].forEach.call(slideList, function (el) {
          var rect = el.getBoundingClientRect();         
        });
      }, 100);
    });
    window.addEventListener('resize', function () {
    clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        divider = window.innerHeight / 2;
      }, 100);
    });    
    }
    var mobile = 'false',
      isTestPage = false,
      isDemoPage = true,
      classIn = 'jello',
      classOut = 'rollOut',
      speed = 400,
      doc = document,
      win = window,
      ww = win.innerWidth || doc.documentElement.clientWidth || doc.body.clientWidth,
      fw = getFW(ww),
      initFns = {},
      sliders = new Object(),
      edgepadding = 50,
      gutter = 10;
    function getFW (width) {
    var sm = 400, md = 900, lg = 1400;
    return width < sm ? 150 : width >= sm && width < md ? 200 : width >= md && width < lg ? 300 : 400;
    }
    window.addEventListener('resize', function() { fw = getFW(ww); });
    </script>
    <script>
    // <script type="module">
    // import { tns } from '../src/tiny-slider.js';
    var options = {
	    'autoWidth-non-loop': {
	      autoWidth: true,
	      loop: false,
	      mouseDrag: true,
	      nav: false,
	    }
    
    };
    for (var i in options) {
    var item = options[i];
    item.container = '#' + i;
    item.swipeAngle = false;
    if (!item.speed) { item.speed = speed; }
    if (doc.querySelector(item.container)) {
      sliders[i] = tns(options[i]);
    // test responsive pages
    } else if (i.indexOf('responsive') >= 0) {
      if (isTestPage && initFns[i]) { initFns[i](); }
    }
}
//New code for scroll item :: Start
var sections = $('.accordion-collapse')
  , nav = $('nav.slider-loop')
  , nav_height = nav.outerHeight();
var lastScrollTop = 0;
var lastCat = "";
// var $dots = $('.owl-carousel');
$(window).on('scroll', function () {
  var totalheight = $('.slider-tag').outerHeight()+$('header').outerHeight();
  var cur_pos = $(this).scrollTop()+totalheight;
  var curScroll = $(this).scrollTop();
  //sections.each(function() {
  $('.accordion-item > a').each(function() {
    var top = $(this).offset().top,
        bottom = top + $(this).outerHeight();
    var curCat = $(this).attr('href').substring(1);
    if (cur_pos >= top && cur_pos <= bottom)
    {
      nav.find('a').removeClass('active');
      //sections.removeClass('active');      
      $('.accordion-item > a').removeClass('active');      
      $(this).addClass('active');
      nav.find('a[href="'+$(this).attr('href')+'"]').addClass('active');
    if(curCat != lastCat && lastCat !=""){
      if (curScroll > lastScrollTop){
          //scroll down
          sliders['autoWidth-non-loop'].goTo('next');
      } else {
          //scroll up          
          sliders['autoWidth-non-loop'].goTo('prev');
      }
      lastScrollTop = curScroll;
    }
    lastCat = curCat;      
    }
  });
});
/*$("#details_content").find('a').on('click', function () {
	var clickid= this.id;
	clickid.toLowerCase();
	if(clickid.indexOf('addtocart')==-1){

		var totalheight = $('.slider-tag').outerHeight()+$('header').outerHeight();
  		var $el = $(this)
    	, id = $el.attr('href');
  		$('html, body').animate({
    		scrollTop: $(id).offset().top - totalheight
  		}, 500);
  	}
  
  return false;
});*/
//when search box is empty
$('#search_dish').keyup(function(){
	$('#Search_btn').prop('disabled', false);
	$('#search_dish_reset_btn').prop('disabled', false);
	if(event.keyCode == 13){
		$("#Search_btn").click();
    }
	if($(this).val()==''){
		$('#Search_btn').prop('disabled', true);
		$('#search_dish_reset_btn').prop('disabled', true);
	}
});
$('#search_dish_reset_btn').on('click', function() {
	$('#search_dish').val("");
	var restaurant_id = '<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>';
	if(restaurant_id){
		searchMenuDishes(restaurant_id);
		$('#Search_btn').prop('disabled', true);
		$('#search_dish_reset_btn').prop('disabled', true);
	}
});
//New code for scroll item :: end
$('input.QtyNumberval').on('input', function() {		
    this.value = this.value.replace(/[^0-9]/g,'').replace(/(\..*)\./g, '$1');
});
$('#report_restaurant').click(function(){
	$('#reportRestaurantModal').modal('show');
});
$('#report_res_form').validate({
	rules: { 
        email_address: {
        	required: true
        },
        message :{
        	required: true
       }
    },
    errorPlacement: function(error, element){
	    if( element.attr("name") == "message"){
	      $('.message_error').html(error); 
	    }
	    else if( element.attr("name") == "email_address"){
	      $('.email_address_error').html(error); 
	    }
	    else 
	    {
	      error.insertAfter(element);
	    }
	}
});
$('#report_res_form').on("submit", function(event){
	var report_topic_checkbox = new Array();
	$("input[name='report_topic[]']:checked").each(function() {
   		report_topic_checkbox.push($(this).val());
	});

	var email_address = $("input[name='email_address']").val();
	var message = $("textarea#message").val();
	if($('#report_res_form').valid()){
		event.preventDefault();
		jQuery.ajax({
		    type : "POST",
		    dataType : "json",
		    url : BASEURL+ 'restaurant/restaurant_error_report',
		    data : {"report_topic":report_topic_checkbox,"email_address":email_address,"message":message},
		    beforeSend: function(){
		    	$('#report_error_message').html('');
		        $('#quotes-main-loader').show();
		    },
		    success: function(response) {
		    	if(response.error == 1){
		    		$("#reportRestaurantModal .modal-content").scrollTop(0);
		    		$('#quotes-main-loader').hide();
		    		var error_message = $('<div class="alert alert-danger" role="alert">'+response.message+'</div>');
		    		$('#report_error_message').html(error_message);
		    	}
		    	if(response.success == 1){
		    		$("#reportRestaurantModal .modal-content").scrollTop(0);
		    		/*$('#report_res_form').find("input,textarea,select").val('').end().find("input[type=checkbox], input[type=radio]").prop("checked", "").end();*/
		    		document.getElementById("report_res_form").reset();
		    		$('#quotes-main-loader').hide();
		    		var success_message = $('<div class="alert alert-success" role="alert"><p>'+response.message+'</p></div>');
		    		$('#report_error_message').html(success_message);
		    		setTimeout(function(){ $('#reportRestaurantModal').modal('hide'); }, 5000);
		    	}
		    	if(response.success == 0){
		    		$("#reportRestaurantModal .modal-content").scrollTop(0);
		    		/*$('#report_res_form').find("input,textarea,select").val('').end().find("input[type=checkbox], input[type=radio]").prop("checked", "").end();*/
		    		document.getElementById("report_res_form").reset();
		    		$('#quotes-main-loader').hide();
		    		var fail_message = $('<div class="alert alert-danger" role="alert"><p>'+response.message+'</p></div>');
		    		$('#report_error_message').html(fail_message);
		    		setTimeout(function(){ $('#reportRestaurantModal').modal('hide'); }, 5000);
		    	}
		    },
		    error: function(XMLHttpRequest, textStatus, errorThrown) {
		        alert(errorThrown);
		    }
		});
	}
});
$('#reportRestaurantModal').on('hidden.bs.modal', function () {
	//$(this).find("input,textarea,select").val('').end().find("input[type=checkbox], input[type=radio]").prop("checked", "").end();
	document.getElementById("report_res_form").reset();
	$('#report_res_form').validate().resetForm();
  	$('#report_error_message').html('');
});
</script>
<script type="text/javascript">
	//New code for multiple select :: start
	$(".filter_food").on("click",function(){
		var idArr = [];
		$(".filter_food_all").attr("checked", false);
		$('.filter_food:checked').each(function() {
	        idArr.push($(this).val());
	    });
	    menuFilter('<?php echo $restaurant_details['restaurant'][0]['content_id']; ?>',idArr,'yes','no')
	});
	$(".filter_food_all").on("click",function(){
		$(".filter_food").attr("checked", false);
		var idArr = $(".filter_food_all").val();
	    menuFilter('<?php echo $restaurant_details['restaurant'][0]['content_id']; ?>',idArr)
	});
	$(".filter_availibility").on("click",function(){
		var idArr = [];
		$(".filter_availibility_all").attr("checked", false);
		$('.filter_availibility:checked').each(function() {
	        idArr.push($(this).val());
	    });
	    menuFilter('<?php echo $restaurant_details['restaurant'][0]['content_id']; ?>',idArr,'no','yes')
	});
	$(".filter_availibility_all").on("click",function(){
		$(".filter_availibility").attr("checked", false);
		var idArr = $(".filter_availibility_all").val();
	    menuFilter('<?php echo $restaurant_details['restaurant'][0]['content_id']; ?>',idArr)
	});
</script>
<script type="text/javascript">
window.addEventListener('click', function(e){   
  if (!document.getElementById('res_time_li').contains(e.target)){
    // Clicked outside the box
    if($(".timings-list").hasClass("toggle_open")){
	    $(".timings-list").addClass("toggle_close");
		$(".timings-list").removeClass("toggle_open");
		$(".timings-list-grp").slideUp("slow");
	}
  } 
});
</script>
<script type="text/javascript">
//intl-tel-input plugin
var onedit_iso = '';
<?php if($this->session->userdata('userPhone_code')) {
    $onedit_iso = $this->common_model->getIsobyPhnCode($this->session->userdata('userPhone_code')); ?>
    onedit_iso = '<?php echo $onedit_iso; ?>'; //saved in session
<?php }
$iso = $this->common_model->country_iso_for_dropdown();
$default_iso = $this->common_model->getDefaultIso(); ?>

var country_iso = <?php echo json_encode($iso); ?>; //all active countries
var default_iso = <?php echo json_encode($default_iso); ?>; //default country
default_iso = (default_iso)?default_iso:'';
var initial_preferred_iso = (onedit_iso)?onedit_iso:default_iso;
//phone number login form :: start
// Initialize the intl-tel-input plugin
<?php if($restaurant_details['restaurant'][0]['enable_table_booking'] == 1 && $this->session->userdata('UserID')){ ?>
const phoneInputField = document.querySelector("#phone_number_inp");
const phoneInput = window.intlTelInput(phoneInputField, {
    initialCountry: initial_preferred_iso,
    preferredCountries: [initial_preferred_iso],
    onlyCountries: country_iso,
    separateDialCode:true,
    autoPlaceholder:"polite",
    formatOnDisplay:false,
    utilsScript: BASEURL+'assets/admin/plugins/intl_tel_input/utils.js',
});
$(document).on('input','#phone_number_inp',function(){
    event.preventDefault();
    var phoneNumber = phoneInput.getNumber();
    if (phoneInput.isValidNumber()) {
        var countryData = phoneInput.getSelectedCountryData();
        var countryCode = countryData.dialCode;
        $('#phone_code').val(countryCode);
        phoneNumber = phoneNumber.replace('+'+countryCode,'');
        $('#phone_number_inp').val(phoneNumber);
    }
});
$(document).on('focusout','#phone_number_inp',function(){
    event.preventDefault();
    var phoneNumber = phoneInput.getNumber();
    if (phoneInput.isValidNumber()) {
        var countryData = phoneInput.getSelectedCountryData();
        var countryCode = countryData.dialCode;
        $('#phone_code').val(countryCode);
        phoneNumber = phoneNumber.replace('+'+countryCode,'');
        $('#phone_number_inp').val(phoneNumber);
    }
});
phoneInputField.addEventListener("close:countrydropdown",function() {
    var phoneNumber = phoneInput.getNumber();
    if (phoneInput.isValidNumber()) {
        var countryData = phoneInput.getSelectedCountryData();
        var countryCode = countryData.dialCode;
        $('#phone_code').val(countryCode);
        phoneNumber = phoneNumber.replace('+'+countryCode,'');
        $('#phone_number_inp').val(phoneNumber);
    }
});
<?php } ?>
function addEndTimeSlot(start_end_flag) {
	var restaurant_id = $('#table_restaurant_id').val();
	var event_date = $('#datepicker').val();
	if(start_end_flag=='is_start'){
		var start_time = $('#starttime').val();
		var end_time = '<?php echo $restaurant_details['restaurant'][0]['timings']['close'] ?>';
	} else {
		var start_time = '<?php echo $restaurant_details['restaurant'][0]['timings']['open'] ?>';
		var end_time = $('#endtime').val();
	}
	var selected_start_time = $('#starttime').val();
	var selected_end_time = $('#endtime').val();
	$.ajax({
		type: "POST",
		dataType: "html",
		url: BASEURL+'restaurant/getTimeSlot',
		data: {'start_time':start_time,'end_time':end_time, 'restaurant_id':restaurant_id ,'event_date' : event_date, 'is_date_changed' :1, 'start_end_flag':start_end_flag, 'selected_start_time': selected_start_time, 'selected_end_time':selected_end_time },
		beforeSend: function(){
			$('#quotes-main-loader').show();
		},
		success: function(response) { 
			$('#quotes-main-loader').hide();
			var arr = JSON.parse(response);
			//time_slot
			if(start_end_flag=='is_start'){
				$('#endtime').empty().append(arr.end_time_html);
				$('#endtime')[0].sumo.reload();
			} else {
				$('#starttime').empty().append(arr.end_time_html);
				$('#starttime')[0].sumo.reload();
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {           
			alert(errorThrown);
		}
	});
}
function addSlot() {
	var restaurant_id = $('#table_restaurant_id').val();
	var event_date = $('#datepicker').val();
	$.ajax({
		type: "POST",
		dataType: "html",
		url: BASEURL+'restaurant/getTimeSlot',
		data: {'start_time':$('#starttime').val(),'end_time':'<?php echo $restaurant_details['restaurant'][0]['timings']['close'] ?>', 'restaurant_id':restaurant_id,'event_date' : event_date },
		beforeSend: function(){
			$('#quotes-main-loader').show();
		},
		success: function(response) { 
			$('#quotes-main-loader').hide();
			var arr = JSON.parse(response);
			$('#starttime').empty().append(arr.start_time_html);
			$('#starttime')[0].sumo.reload();

			$('#endtime').empty().append(arr.end_time_html);
			$('#endtime')[0].sumo.reload();
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {           
			alert(errorThrown);
		}
	});
}
function getPackageInfo(value,restaurant_id)
{
	if(value!='' && value!=undefined)
	{
		jQuery.ajax({
	        type : "POST",
	        dataType : "json",
	        url : BASEURL+'restaurant/show_restaurantpackage',
	        data : {'content_id':value,'restaurant_id':restaurant_id},
	        beforeSend: function(){
	            $('#quotes-main-loader').show();
	            $('#package_detaildiv').html('');
	        },
	        success: function(response) {
	        	$('#package_detaildiv').html(response.package_html);
	        	$('#package_section').show();
	        	$('#quotes-main-loader').hide();
	        },
	        error: function(XMLHttpRequest, textStatus, errorThrown) {
	            //alert(errorThrown);
	        }
	    });
	}
	else
	{
		$('#package_section').hide();
	}
}
function restaurantShare(){
	$('.social-icon-grp').slideToggle();
}
$(document).click(function(event) {
  if($(event.target).closest(".share-icons").length === 0 && $('.social-icon-grp').is(":visible")) {
  	$('.social-icon-grp').slideUp();
  }
	$('#restaurantTab .nav-item .nav-link').on('click',function(){	  	
		if($(this).attr('id')=='menu-tab'){
			$('body').addClass('restaurant_menu');
		}else{
			$('body').removeClass('restaurant_menu');
		}
	});
});
</script>
<?php $this->load->view('footer');?>