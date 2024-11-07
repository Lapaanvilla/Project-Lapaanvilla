<?php $menu_ids = array();
if (!empty($menu_arr)) {
	$menu_ids = array_column($menu_arr, 'menu_id');
}
//get System Option Data
/*$this->db->select('OptionValue');
$currency_id = $this->db->get_where('system_option',array('OptionSlug'=>'currency'))->first_row();
$currency_symbol = $this->common_model->getCurrencySymbol($currency_id->OptionValue);
$currency_symbol = $currency_symbol->currency_symbol;*/
//if (!empty($restaurant_details['menu_items']) && !empty($restaurant_details['categories'])) {
if (!empty($restaurant_details['menu_items'])) {
	if (!empty($restaurant_details['categories'])) {?>
		<div class="slider-tag bg-body px-xl-4 py-xl-2 border my-xl-4">
			<div class="slider-overlay d-xl-none"></div>
			<!-- <button id="pnAdvancerLeft" class="pn-Advancer pn-Advancer_Left move left" type="button"><i class="iicon-icon-16"></i></button> -->
			<nav class="cat-loop">
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

<div class="is_close">	<?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed")?'<span id="closedres">'.$this->lang->line('not_accepting_orders').'</span>':''; ?>
</div>
<?php 
	if (!empty($restaurant_details['menu_items'])) {
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
											<img src="<?php echo $rest_image ;?>" alt="<?php echo $value['name']; ?>">

											<div class="label-sticker"><span><?php echo $this->lang->line('popular') ?></span></div> 
										<?php */ ?>

										<?php $rest_image = (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='') ? image_url.$value['image'] : default_icon_img;
										if ($value['check_add_ons'] == 1) { ?>
											<a class="picture h-100" href="javascript:void(0);" id="addtocart-<?php echo $value['entity_id']; ?>" onclick="checkCartRestaurantDetails(<?php echo $value['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'<?php echo $restaurant_details['restaurant'][0]['timings']['closing']; ?>','addons',this.id,'no')"> <img src="<?php echo $rest_image; ?>" alt="<?php echo $value['name']; ?>"> </a>
											<span><?php echo $this->lang->line('popular') ?></span>
										<?php } else {?>
											<a class="picture h-100" href="javascript:void(0);" id="addtocart-<?php echo $value['entity_id']; ?>" onclick="checkCartRestaurantDetails(<?php echo $value['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'<?php echo $restaurant_details['restaurant'][0]['timings']['closing']; ?>','',this.id,'no')" > <img src="<?php echo $rest_image; ?>" alt="<?php echo $value['name']; ?>"> </a>

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
											$food_type_name = '';
											foreach ($restaurant_details['restaurant'][0]['resfood_type'] as $key => $val) {
													if($val->food_type_id == $value['food_type']){
																$food_type_name = $val->food_type_name;break;
															}
													} if(!empty($food_type_name)){ ?>

											<li><strong><?php echo $this->lang->line('food_type')." : </strong>" ?><?php echo $food_type_name; ?></li> <?php } ?>
											<li><strong><?php echo $this->lang->line('availability')." : </strong>" ?><?php echo $value['availability']; ?></li>
										</ul>
										<!-- menu details on item name click :: end -->

										<small class="d-flex w-100"><?php echo $value['menu_detail']; ?></small>
										
										<strong class="text-secondary <?php if($value['offer_price']>0){ ?> text-decoration-line-through <?php } ?>">
											<?php echo ($value['check_add_ons'] != 1)?currency_symboldisplay($value['price'],$restaurant_details['restaurant'][0]['currency_symbol']):(($value['price'])?currency_symboldisplay($value['price'],$restaurant_details['restaurant'][0]['currency_symbol']):''); ?></strong>
										<?php if($value['offer_price']>0){ ?>
										<strong class="text-secondary">
										<?php echo ($value['check_add_ons'] != 1)?currency_symboldisplay($value['offer_price'],$restaurant_details['restaurant'][0]['currency_symbol']):(($value['offer_price'])?currency_symboldisplay($value['offer_price'],$restaurant_details['restaurant'][0]['currency_symbol']):''); ?>
										</strong>
										<?php } ?>
									</div>

									<?php if ($restaurant_details['restaurant'][0]['timings']['closing'] != "Closed") {
										if ($value['check_add_ons'] == 1) {
											if($value['stock'] == 1 || $restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1'){ ?>
												<div class="add-btn d-flex flex-column text-center" id="cart_item_<?php echo $value['entity_id']; ?>">
													<?php $add = (in_array($value['entity_id'], $menu_ids))?'Added':'Add'; ?>
													<button class="btn btn-xs px-2 btn-secondary <?php echo strtolower($add); ?> addtocart-<?php echo $value['entity_id']; ?>" id="addtocart-<?php echo $value['entity_id']; ?>" <?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed")?'disabled':''; ?>  onclick="checkCartRestaurant(<?php echo $value['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'addons',this.id)" order-for-later="<?php echo ($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $value['stock'] == 0) ? '1' : '0'; ?>" > <?php echo (in_array($value['entity_id'], $menu_ids))?$this->lang->line('added'):(($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $value['stock'] == 0) ? $this->lang->line('order_for_later') : $this->lang->line('add')); ?> </button>

													<small class="text-success"><?php echo $this->lang->line('customizable') ?></small>

													<?php if($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $value['stock'] == 0) { ?>
														<small class="text-danger"><?php echo $this->lang->line('out_stock') ?></small>
													<?php } ?>
												</div>
											<?php }else{ ?>
												<div class="add-btn d-flex flex-column text-center">
													<small class="text-danger"><?php echo $this->lang->line('out_stock') ?></small>
												</div>
											<?php } 	
										} else {
											if($value['stock'] == 1 || $restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1'){ ?>
												<div class="add-btn d-flex flex-column text-center" id="cart_item_<?php echo $value['entity_id']; ?>">
													<?php $add = (in_array($value['entity_id'], $menu_ids))?'Added':'Add'; ?>
													<button class="btn btn-xs px-2 btn-secondary <?php echo strtolower($add); ?> addtocart-<?php echo $value['entity_id']; ?>" id="addtocart-<?php echo $value['entity_id']; ?>" onclick="checkCartRestaurant(<?php echo $value['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'',this.id)" <?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed")?'disabled':''; ?> order-for-later="<?php echo ($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $value['stock'] == 0) ? '1' : '0'; ?>" > <?php echo (in_array($value['entity_id'], $menu_ids))?$this->lang->line('added'):(($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $value['stock'] == 0) ? $this->lang->line('order_for_later') : $this->lang->line('add')); ?> </button>
													
													<?php if($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $value['stock'] == 0) { ?>
														<small class="text-danger"><?php echo $this->lang->line('out_stock') ?></small>
													<?php } ?>
												</div>
											<?php }else{ ?>
												<div class="add-btn d-flex flex-column text-center">
													<small class="text-danger"><?php echo $this->lang->line('out_stock') ?></small>
												</div>
											<?php }
									} } ?>
								</div>
							<?php }
						}?>
					</div>
				</div>
			</div>
		<?php }?>
	<?php }?>
	<?php if (!empty($restaurant_details['categories'])) {
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
							if(count($restaurant_details[$value['name']])==1){
								$margin_text = 'style="margin-bottom:60px !important;"';
							}
						}
						?>
						<?php if ($restaurant_details[$value['name']]) {
							foreach ($restaurant_details[$value['name']] as $key => $mvalue) {?>
								<div class="item-menu d-flex align-items-md-center flex-wrap flex-md-nowrap">
									<figure class="picture">
										<?php /* ?><img src="<?php echo ($mvalue['image']) ? base_url().'uploads/'.$mvalue['image'] : default_img; ?>"><?php */ ?>

										<?php if ($mvalue['check_add_ons'] == 1) {?>
											<a href="javascript:void(0);" class="picture h-100" id="addtocart-<?php echo $mvalue['entity_id']; ?>" onclick="checkCartRestaurantDetails(<?php echo $mvalue['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'<?php echo $restaurant_details['restaurant'][0]['timings']['closing']; ?>','addons',this.id,'no')"> <img src="<?php echo (file_exists(FCPATH.'uploads/'.$mvalue['image']) && $mvalue['image']!='') ? image_url.$mvalue['image'] : default_icon_img; ?>"> </a>
										<?php } else {?>
											<a href="javascript:void(0);" class="picture h-100" id="addtocart-<?php echo $mvalue['entity_id']; ?>" onclick="checkCartRestaurantDetails(<?php echo $mvalue['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'<?php echo $restaurant_details['restaurant'][0]['timings']['closing']; ?>','',this.id,'no')" > <img src="<?php echo (file_exists(FCPATH.'uploads/'.$mvalue['image']) && $mvalue['image']!='') ? image_url.$mvalue['image'] : default_icon_img; ?>"> </a>
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
										$mfood_type_name = '';
										foreach ($restaurant_details['restaurant'][0]['resfood_type'] as $key => $mval) {
											if($mval->food_type_id == $mvalue['food_type']){
														$mfood_type_name = $mval->food_type_name;break;
												}
										} ?>


										<ul class="small d-flex flex-wrap">
											<?php 
											if(!empty($mfood_type_name)){ ?>
											<li><strong><?php echo $this->lang->line('food_type')." : " ?></strong><?php echo $mfood_type_name; ?></li> <?php } ?>
											<li><strong><?php echo $this->lang->line('availability')." : " ?></strong><?php echo $mvalue['availability']; ?></li>
										</ul>

										<!-- menu details on item name click :: end -->
										<small class="d-flex w-100"><?php echo $mvalue['menu_detail']; ?></small>

										<strong class="text-secondary <?php if($mvalue['offer_price']>0){ ?>text-decoration-line-through<?php } ?>">
											<?php echo ($mvalue['check_add_ons'] != 1)?currency_symboldisplay($mvalue['price'],$restaurant_details['restaurant'][0]['currency_symbol']):(($mvalue['price'])?currency_symboldisplay($mvalue['price'],$restaurant_details['restaurant'][0]['currency_symbol']):''); ?></strong>
										<?php if($mvalue['offer_price']>0){ ?>
											<strong class="text-secondary "><?php echo ($mvalue['check_add_ons'] != 1)?currency_symboldisplay($mvalue['offer_price'],$restaurant_details['restaurant'][0]['currency_symbol']):(($mvalue['offer_price'])?currency_symboldisplay($mvalue['offer_price'],$restaurant_details['restaurant'][0]['currency_symbol']):''); ?></strong>
										<?php } ?>

									</div>
									<?php if ($restaurant_details['restaurant'][0]['timings']['closing'] != "Closed") {
										if ($mvalue['check_add_ons'] == 1) {
											if($mvalue['stock'] == 1 || $restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1'){ ?>
												<div class="add-btn d-flex flex-column text-center" id="cart_item_<?php echo $mvalue['entity_id']; ?>">
													<?php $add = (in_array($mvalue['entity_id'], $menu_ids))?'Added':'Add'; ?>
													<button class="btn btn-xs px-2 btn-secondary <?php echo strtolower($add); ?> addtocart-<?php echo $mvalue['entity_id']; ?>" id="addtocart-<?php echo $mvalue['entity_id']; ?>" <?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed")?'disabled':''; ?>  onclick="checkCartRestaurant(<?php echo $mvalue['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'addons',this.id)" order-for-later="<?php echo ($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $mvalue['stock'] == 0) ? '1' : '0'; ?>" > <?php echo (in_array($mvalue['entity_id'], $menu_ids))?$this->lang->line('added'):(($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $mvalue['stock'] == 0) ? $this->lang->line('order_for_later') : $this->lang->line('add')); ?> </button>

													<small class="text-success"><?php echo $this->lang->line('customizable') ?></small>>
													<?php if($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $mvalue['stock'] == 0) { ?>
														<small class="text-danger"><?php echo $this->lang->line('out_stock') ?></small>
													<?php } ?>
												</div>
											<?php }else{ ?>
												<div class="add-btn d-flex flex-column text-center">
													<small class="text-danger"><?php echo $this->lang->line('out_stock') ?></small>
												</div>
											<?php }	
										} else { 
											if($mvalue['stock'] == 1 || $restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1'){ ?>
												<div class="add-btn d-flex flex-column text-center" id="cart_item_<?php echo $mvalue['entity_id']; ?>">
													<?php $add = (in_array($mvalue['entity_id'], $menu_ids))?'Added':'Add'; ?>
													<button class="btn btn-xs px-2 btn-secondary <?php echo strtolower($add); ?> addtocart-<?php echo $mvalue['entity_id']; ?>" id="addtocart-<?php echo $mvalue['entity_id']; ?>" onclick="checkCartRestaurant(<?php echo $mvalue['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'',this.id)" <?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed")?'disabled':''; ?> order-for-later="<?php echo ($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $mvalue['stock'] == 0) ? '1' : '0'; ?>" > <?php echo (in_array($mvalue['entity_id'], $menu_ids))?$this->lang->line('added'):(($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $mvalue['stock'] == 0) ? $this->lang->line('order_for_later') : $this->lang->line('add')); ?> </button>
													<?php if($restaurant_details['restaurant'][0]['allow_scheduled_delivery'] == '1' && $mvalue['stock'] == 0) { ?>
														<small class="text-danger"><?php echo $this->lang->line('out_stock') ?></small>
													<?php } ?>
												</div>
											<?php }else{ ?>
												<div class="add-btn d-flex flex-column text-center">
													<small class="text-danger"><?php echo $this->lang->line('out_stock') ?></small>
												</div>
											<?php } 
									} } ?>
								</div>
							<?php }
						}?>
					</div>
				</div>	
			</div>		
		<?php }
	}
	if(empty($restaurant_details['menu_items'])){ ?>
		<div class="text-center py-4">
			<figure class="mb-4">											
				<img src="<?php echo base_url();?>assets/front/images/image-cart.png">
			</figure>
			<h6><?php echo $this->lang->line('no_results_found') ?></h6>
		</div>
	<?php }
}
else
{ ?>
	<div class="text-center py-4">
		<figure class="mb-4">											
			<img src="<?php echo base_url();?>assets/front/images/image-cart.png">
		</figure>
		<h6><?php echo $this->lang->line('no_results_found') ?></h6>
	</div>
<?php } ?>
<script>
    var doc = document,
      slideList = doc.querySelectorAll('.slider-checkbox-main > div'),
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
  
  sections.each(function() {
    var top = $(this).offset().top,
        bottom = top + $(this).outerHeight();
    var curCat = $(this).attr('id');
    if (cur_pos >= top && cur_pos <= bottom)
    {
      nav.find('a').removeClass('active');
      sections.removeClass('active');      
      $(this).addClass('active');
      nav.find('a[href="#'+$(this).attr('id')+'"]').addClass('active');

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

/*nav.find('a').on('click', function () {
	var totalheight = $('.slider-checkbox-main').outerHeight()+$('.header-area').outerHeight();
  	var $el = $(this)
    , id = $el.attr('href');

  $('html, body').animate({
    scrollTop: $(id).offset().top - totalheight
  }, 500);
  
  return false;
});*/
/*$('.accordion-item > a ').click(function(){
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
	var totalheight = $('.slider-tag').outerHeight()+$('header').outerHeight() + 60;
	var $el = $(this)
	, id = $el.attr('href');
	$('html, body').animate({
	scrollTop: $(id).offset().top - totalheight
	}, 500);
	return false;
});
//New code for scroll item :: end

</script>