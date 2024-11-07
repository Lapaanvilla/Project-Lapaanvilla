		</main>
		<div class="wait-loader" style="display:none" id="quotes-main-loader"><img  src="<?php echo base_url() ?>assets/admin/img/ajax-loader.gif" align="absmiddle"  ></div>
		<?php //$minimum_range = 0; $maximum_range = 50000; 
		$distance_inarr = $this->db->get_where('system_option',array('OptionSlug'=>'distance_in'))->first_row();
		$distance_inVal = $this->lang->line('in_km');
		if($distance_inarr && !empty($distance_inarr))
		{
		    if($distance_inarr->OptionValue==0){
		        $distance_inVal = $this->lang->line('in_mile');
		    }
		} ?>
		<script type="text/javascript">
			var distance_inVal = '<?php echo $distance_inVal; ?>';
		</script>

		<footer class="bg-secondary text-center">
			<div class="container-fluid">
				<div class=" pt-8 pb-6 py-md-8 py-xl-12">
					<a href="<?php echo base_url(); ?>" class="icon icon-logo text-white"><img src="<?php echo base_url(); ?>assets/front/images/brand-logo-white.svg" alt=""></a>

					<?php //get System Option Data
						$this->db->select('OptionValue');
						$facebook = $this->db->get_where('system_option',array('OptionSlug'=>'facebook'))->first_row();
						$this->db->select('OptionValue');
						$twitter = $this->db->get_where('system_option',array('OptionSlug'=>'twitter'))->first_row();
						$this->db->select('OptionValue');
						$linkedin = $this->db->get_where('system_option',array('OptionSlug'=>'linkedin'))->first_row();
						$this->db->select('OptionValue');
						$instagram = $this->db->get_where('system_option',array('OptionSlug'=>'instagram'))->first_row();
					?>

					<ul class="icon-social pt-6 pt-md-7 mb-6 d-flex flex-wrap justify-content-center">
						<li>
							<a href="<?php echo $facebook->OptionValue; ?>" alt="<?php echo $this->lang->line('facebook'); ?>" title="<?php echo $this->lang->line('facebook'); ?>" target="_blank" class="icon"><img src="<?php echo base_url(); ?>assets/front/images/icon-facebook.svg" alt=""></a>
						</li>
						<li>
							<a href="<?php echo $instagram->OptionValue; ?>" alt="<?php echo $this->lang->line('instagram'); ?>" title="<?php echo $this->lang->line('instagram'); ?>" target="_blank" class="icon"><img src="<?php echo base_url(); ?>assets/front/images/icon-instagram.svg" alt=""></a>
						</li>
						<li>
							<a href="<?php echo $twitter->OptionValue; ?>" alt="<?php echo $this->lang->line('twitter'); ?>" title="<?php echo $this->lang->line('twitter'); ?>" target="_blank" class="icon"><img src="<?php echo base_url(); ?>assets/front/images/icon-twitter.svg" alt=""></a>
						</li>
						<li>
							<a href="<?php echo $linkedin->OptionValue; ?>" alt="<?php echo $this->lang->line('linkedin'); ?>" title="<?php echo $this->lang->line('linkedin'); ?>" target="_blank" class="icon"><img src="<?php echo base_url(); ?>assets/front/images/icon-linkedin.svg" alt=""></a>
						</li>
					</ul>
					<ul class="icon-link pt-6 pt-md-0 d-flex flex-column flex-md-row flex-wrap justify-content-center">
						<li class="<?php echo ($current_page == 'HomePage') ? 'current_page_item' : ''; ?>"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('home') ?></a></li>
						<?php $lang_slug = $this->session->userdata('language_slug');
						$cmsPages = $this->common_model->getCmsPages($lang_slug); 
						if (!empty($cmsPages)) {
							foreach ($cmsPages as $key => $value) { 
								if($value->CMSSlug == "legal-notice") { ?>
									<li class="<?php echo ($current_page == 'LegalNotice') ? 'current_page_item' : ''; ?>"><a href="<?php echo base_url() . 'legal-notice'; ?>"><?php echo $this->lang->line('legal_notice') ?></a></li>
								<?php }
								else if($value->CMSSlug == "terms-and-conditions") { ?>
									<li class="<?php echo ($current_page == 'TermsAndConditions') ? 'current_page_item' : ''; ?>"><a href="<?php echo base_url() . 'terms-and-conditions'; ?>"> <?php echo $this->lang->line('terms_and_conditions')?> </a></li>
								<?php }
								else if($value->CMSSlug == "privacy-policy") { ?>
									<li class="<?php echo ($current_page == 'PrivacyPolicy') ? 'current_page_item' : ''; ?>"><a href="<?php echo base_url() . 'privacy-policy'; ?>"> <?php echo $this->lang->line('privacy_policy')?> </a></li>
								<?php }
								else if ($value->CMSSlug == "about-us") { ?>
									<li class="<?php echo ($current_page == 'AboutUs') ? 'current_page_item' : ''; ?>"><a href="<?php echo base_url() . 'about-us'; ?>"><?php echo $this->lang->line('about_us') ?></a></li>
								<?php }
								else if($value->CMSSlug == "contact-us") { ?>
									<li class="<?php echo ($current_page == 'ContactUs') ? 'current_page_item' : ''; ?>"><a href="<?php echo base_url() . 'contact-us'; ?>"><?php echo $this->lang->line('contact_us') ?></a></li>
								<?php }
							}
						} ?>
						<li class="<?php echo ($current_page == 'faqs') ? 'current_page_item' : ''; ?>"><a href="<?php echo base_url() . 'faqs'; ?>"><?php echo $this->lang->line('faqs') ?></a>
						</li>
					</ul>
				</div>
				<span class="d-inline-block text-white py-4 border-top border-white w-100"><?php echo $this->lang->line('copyright');?>&copy; <?php echo date('Y');?> <?php echo $this->lang->line('reserved');?> <a target="_blank" href="<?php echo base_url(); ?>"><?php echo $this->lang->line('site_footer'); ?></a></span>

			</div>
		</footer>
		
		<a href="javascript:void(0)" id="btn" class="btn-scroll rounded-circle icon" title="Go to top"><img src="<?php echo base_url(); ?>assets/front/images/icon-arrow-up.svg" alt=""></a>

		<?php if($this->session->userdata("language_slug")=='fr'){  ?>
			<script type="text/javascript" src="<?php echo base_url()?>assets/admin/pages/scripts/localization/messages_fr.js"> </script>
		<?php } elseif ($this->session->userdata("language_slug")=='ar') {
		 ?>
			<script type="text/javascript" src="<?php echo base_url()?>assets/admin/pages/scripts/localization/messages_ar.js"> </script>	
		<?php } ?>
		<script src="<?php echo base_url();?>assets/admin/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/front/js/scripts/front-validations.js"></script>
		<script type='text/javascript' src='<?php echo base_url();?>assets/front/js/js.cookie.min.js' id='js-cookie-js'></script>
		<script>
			var cookie_policy = "<?php echo $this->lang->line('cookie_policy');?>";
			var cookie_policytxt = "<?php echo $this->lang->line('cookie_policytxt');?>";
		</script>

		<script>cookieLaw={dId:"cookie-law-div",bId:"cookie-law-button",iId:"cookie-law-item",show:function(e){if(localStorage.getItem(cookieLaw.iId))return!1;var o=document.createElement("div"),i=document.createElement("p"),t=document.createElement("button");i.innerHTML=e.msg,t.id=cookieLaw.bId,t.innerHTML=e.ok,o.id=cookieLaw.dId,o.appendChild(t),o.appendChild(i),document.body.insertBefore(o,document.body.lastChild),t.addEventListener("click",cookieLaw.hide,!1)},hide:function(){document.getElementById(cookieLaw.dId).outerHTML="",localStorage.setItem(cookieLaw.iId,"1")}},cookieLaw.show({msg:cookie_policytxt+" <a href='<?php echo base_url() ?>cookie-policy' target='_blank'>"+cookie_policy+"</a>",ok:"x"});</script>
	
		<?php if($current_page != "Checkout" && !empty($this->session->userdata('is_user_login')) && $this->session->userdata('is_user_login') == 1) { ?>
			<script type="text/javascript">
			$(document).on('ready', function() { 
				// set interval to get notification
			    var i = setInterval(function(){
				    jQuery.ajax({
				      type : "POST",
				      dataType : "html",
				      async: false,
				      url : BASEURL+'home/getNotifications',
				      success: function(response) {
				        $('#notifications_list').html(response);
				      },
				        error: function(XMLHttpRequest, textStatus, errorThrown) {
				      }
				    });
			    },10000);
			});
			</script>
		<?php }
			if(!empty(website_footer_script)){
				echo website_footer_script;
			}
		?>
		<script type="text/javascript">
			// Added for Use passive listeners to improve scrolling performance
			jQuery.event.special.touchstart = {
			    setup: function( _, ns, handle ) {
			        this.addEventListener('touchstart', handle, { passive: !ns.includes('noPreventDefault') });
			    }
			};
			//distance slider
			<?php if($current_page == "HomePage" || $current_page == "OrderFood") { ?>
				$('#order_mode').on("change", function () {
					var rangeSlider = document.getElementById('slider-range');
					var range_values = rangeSlider.noUiSlider.get();
					var moneyFormat = wNumb({
						decimals: 0,
						thousand: ',',
						prefix: ''
					});
					if($("#order_mode").val() == 'PickUp') {
						if(range_values[1] != maximum_range_pickup_for_slider) {
							rangeSlider.noUiSlider.updateOptions({
								range: {
									'min': 0,
									'max': maximum_range_pickup_for_slider
								}
							});
						}
						var new_range_values = rangeSlider.noUiSlider.get();
						$('#minimum_range').val(new_range_values[0]);
						$('#maximum_range').val(new_range_values[1]);
						document.getElementById('slider-range-value1').innerHTML = new_range_values[0]+' '+distance_inVal;
						document.getElementById('slider-range-value2').innerHTML = new_range_values[1]+' '+distance_inVal;
						document.getElementsByName('max-value').value = moneyFormat.from(maximum_range_pickup_for_slider);
					} else {
						if(range_values[1] != maximum_range_for_slider) {
							rangeSlider.noUiSlider.updateOptions({
								range: {
									'min': 0,
									'max': maximum_range_for_slider
								}
							});
						}
						var new_range_values = rangeSlider.noUiSlider.get();
						$('#minimum_range').val(new_range_values[0]);
						$('#maximum_range').val(new_range_values[1]);
						document.getElementById('slider-range-value1').innerHTML = new_range_values[0]+' '+distance_inVal;
						document.getElementById('slider-range-value2').innerHTML = new_range_values[1]+' '+distance_inVal;
						document.getElementsByName('max-value').value = moneyFormat.from(maximum_range_for_slider);
					}
					document.getElementsByName('min-value').value = moneyFormat.from(0);
					$(".noUi-origin.noUi-background").css('left','100%');
					$(".noUi-origin.noUi-connect").css('left','0%');
					$( ".value01" ).insertAfter( ".noUi-handle-lower" );
					$( ".value02" ).insertAfter( ".noUi-handle-upper" );
				});
			<?php } ?>
		</script>
	</body>
</html>