<?php
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

<?php if (!empty($nearbyRestaurants)) {
	foreach ($nearbyRestaurants as $key => $value) { ?>
		<div class="col-md-6 col-xl-4 col-xxl-3">
			<a class="box-restaurant d-inline-block w-100 bg-white h-100" href="<?php echo base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug'];?>">
				<figure class="picture">
					<?php  $rest_image = (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='') ? image_url.$value['image'] : default_img; ?>
					<img src="<?php echo $rest_image ;?>" alt="<?php echo $value['name']; ?>">
					
					<div class="icon-time small text-white d-inline-block <?php echo ($value['timings']['closing'] == "Closed")?'bg-danger':'bg-success'; ?>"> <?php echo ($value['timings']['closing'] == "Closed")?$this->lang->line('closed'):$this->lang->line('open'); ?> </div>
					<!-- <?php //echo $value['timings']['closing']; ?> -->

					<div class="icon-left d-flex text-capitalize">
						<?php if(isset($value['distance'])) { ?>
							<div class="icon-distance small text-white d-flex align-items-center bg-secondary"><i class="icon"><img src="<?php echo base_url() ?>/assets/front/images/icon-pin.svg" alt="clock"></i><?php echo round($value['distance'],2); ?> <?php echo $distance_inVal; ?></div>
						<?php } ?>

						<?php if ($show_restaurant_reviews) { 
							$rating_txt = ($value['restaurant_reviews_count'] > 1)?$this->lang->line('ratings'):$this->lang->line('rating'); ?>

							<?php echo ($value['ratings'] > 0)?'<div class="small text-white bg-success  d-flex align-items-center"><i class="icon"><img src="'. base_url() .'/assets/front/images/icon-star.svg" alt="clock"></i>'.$value['ratings'].' ('.$value['restaurant_reviews_count'].' '.strtolower($rating_txt).')'.'</div>':'<div class="small text-white bg-success">'. $this->lang->line("new") .'</div>'; ?> 
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
			<figure class="my-4">
				<img src="<?php echo no_res_found; ?>">
			</figure>
			<h6><?php echo $this->lang->line('no_such_res_found') ?></h6>
		</div>
	</div>
<?php } ?>
<script type="text/javascript">
	jQuery(document).ready(function($) {	
		var rtl = (SELECTED_LANG == 'ar')?true:false;
		$(".slider-variable").slick({
			arrows: false,
			dots: false,
			infinite: false,
			autoplay: true,
			variableWidth: true,
			arrow: false,
			autoplaySpeed: 0,
			speed: 8000,
			pauseOnHover: false,
			cssEase: 'linear',
			rtl: rtl,
		});

		/*--- Svg Inline ---*/
		$('.icon img').inlineSvg();
	});
</script>