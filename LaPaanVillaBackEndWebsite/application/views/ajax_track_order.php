<?php if ((($this->session->userdata('is_user_login') == 1 && !empty($this->session->userdata('UserID'))) || $is_guest_track_order =='1') && !empty($latestOrder)) { ?>
	<h1 class="h2 pb-2 mb-8 title text-center text-xl-start"><?php echo $this->lang->line('track_order') ?></h1>

	<div class="row row-grid-xl row-grid">
		<div class="col-12">
			<div class="border bg-white container-gutter-xl py-8 p-xl-8 d-flex flex-column">
				<h3><?php echo $this->lang->line('hey') ?> <?php echo ($this->session->userdata('UserType') == 'Agent')?$this->session->userdata('userFirstname').' '.$this->session->userdata('userLastname'):$latestOrder->user_name; ?>!</h3>
				<small><?php echo $this->lang->line('order_msg') ?></small>
				
				<?php if($this->session->userdata('UserType') == 'Agent') { ?>
					<div class="pt-2">
						<!-- <?php echo $this->lang->line('customer_details') ?>
						
						<?php echo $this->lang->line('customer') ?>
						<?php echo ($latestOrder->user_name)?>

						<?php echo $this->lang->line('phone_number') ?> -->

						<small class="fw-medium w-100 mb-1"><i class="icon icon-small"><img src="<?php echo base_url(); ?>assets/front/images/icon-phone.svg"></i> + <?php echo ($latestOrder->user_mobile_number)?></small>
						
						<?php if(!empty($latestOrder->user_email)){ ?>
							<!-- <?php echo $this->lang->line('email') ?> -->
							<small class="fw-medium w-100"><i class="icon icon-small"><img src="<?php echo base_url(); ?>assets/front/images/icon-mail.svg"></i> <?php echo ($latestOrder->user_email)?></small>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php if($latestOrder->delivery_method != 'relay' && $latestOrder->order_delivery != 'PickUp') { ?>
			<div class="col-xl-4 horizontal-image">
				<?php if(($latestOrder->delivery_method == 'doordash') && $latestOrder->delivery_tracking_url) { ?>
					<a href="<?php echo $latestOrder->delivery_tracking_url; ?>" class="btn" target='_blank' ><?php echo $this->lang->line('clickto_track_order') ?></a>
				<?php } else { ?>
	                <figure class="picture h-100" id="location-map">
	                    <div class="absolute-div" id="map_canvas"></div>
	                </figure>
				<?php } ?>
			</div>
		<?php } ?>
		<?php if($latestOrder->delivery_method != 'relay' && $latestOrder->order_delivery != 'PickUp') { ?>
		<div class="col-xl-4">
			<div class="card card-xl-0 h-100">
				<div class="card-body container-gutter-xl py-4 p-xl-4">
					<h5 class="border-bottom pb-4 mb-4"><?php echo $this->lang->line('orderid') ?> : #<?php echo $latestOrder->master_order_id; ?></h5>
					

					<?php if($latestOrder->order_delivery != 'PickUp' || ($latestOrder->scheduled_date && $latestOrder->slot_open_time && $latestOrder->slot_close_time)) { ?>
						
						<?php if($latestOrder->scheduled_date && $latestOrder->slot_open_time && $latestOrder->slot_close_time) { ?>
							<div class="alert alert-success"><?php echo $this->lang->line('order_scheduled_for').$this->common_model->dateFormat($latestOrder->scheduled_date).' ('.$this->common_model->timeFormat($latestOrder->slot_open_time).' - '.$this->common_model->timeFormat($latestOrder->slot_close_time).' )'; ?></div>
						<?php } ?>
						
						<?php if($latestOrder->order_delivery != 'PickUp') { ?>

							<div class="p-4 bg-body mb-4">
								<div class="d-flex flex-column">
									<?php if($latestOrder->delivery_method != 'relay'){  
										if(!empty($thirdparty_driver_details)){ ?>
											<label><?php echo ($thirdparty_driver_details['first_name']) ? $thirdparty_driver_details['first_name'] . $this->lang->line('order_msg2'):$this->lang->line('order_msg3'); ?></label>
										<?php }else{ ?>
											<label><?php echo ($latestOrder->driver_id) ? $latestOrder->first_name . $this->lang->line('order_msg2') : $this->lang->line('order_msg3'); ?></label>
										<?php } ?>

										<?php if (!empty($latestOrder->driver_temperature)) { ?>
											<small><?php echo $this->lang->line('driver_temperature') ?>  : <?php echo $latestOrder->driver_temperature; ?> </small>
										<?php } 
									} ?>
								</div>

								<?php /* ?>
									<div class="detail-list">
										<label><?php echo $this->lang->line('cash_to_collect') ?></label>
										<p><?php echo currency_symboldisplay($latestOrder->total_rate,$latestOrder->currency_symbol); ?></p>
									</div>
								<?php */ ?>
								
								<?php if($latestOrder->delivery_method != 'relay'){  ?>
									<?php if(!empty($thirdparty_driver_details) && !empty($thirdparty_driver_details['dasher_phone_number_for_customer']) && !empty($thirdparty_driver_details['first_name'])){ ?>
										
										<?php $mobile_number = $thirdparty_driver_details['dasher_phone_number_for_customer']; ?>
										<button class="btn btn-sm btn-primary mt-4 d-flex align-items-center"><i class="icon"><img src="<?php echo base_url(); ?>assets/front/images/icon-phone.svg" alt=""></i><?php echo $mobile_number; ?></button>
									
									<?php }else if($latestOrder->driver_id) {?>
										<?php $mobile_number = !empty($latestOrder->phone_code)?('+'.$latestOrder->phone_code.$latestOrder->mobile_number):($latestOrder->mobile_number); ?>
										<button class="btn btn-sm btn-primary mt-4 d-flex align-items-center"><i class="icon"><img src="<?php echo base_url(); ?>assets/front/images/icon-phone.svg" alt=""></i><?php echo $mobile_number; ?></button>
									<?php } 
								} ?>
							</div>

							<div class="card-status d-flex align-items-center">
								<figure class="picture">
									<?php $image = (file_exists(FCPATH.'uploads/'.$latestOrder->image) && $latestOrder->image!='') ? image_url.$latestOrder->image : default_img; ?>
									<img src="<?php echo $image; ?>">
								</figure>
								<div class="flex-fill">
									<label class="text-secondary fw-medium"><?php echo $this->lang->line('delivery_address') ?></label>
									<small class="d-flex"><i class="icon icon-small"><img src="<?php echo base_url(); ?>assets/front/images/icon-pin.svg" alt=""></i><?php echo $latestOrder->user_address; ?> </small>
								</div>
							</div>

							
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php if($latestOrder->delivery_method != 'relay' && $latestOrder->order_delivery != 'PickUp') { ?>
			<div class="col-xl-4">
		<?php } else { ?>	
			<div class="col-12">
		<?php } ?>
			<div class="card card-xl-0 h-100">
				<div class="card-body container-gutter-xl py-4 p-xl-4">
					<h5 class="border-bottom pb-4 mb-4"><?php echo $this->lang->line('order_status') ?></h5>
					
					<ul class="item-status <?php echo $latestOrder->order_status;?>">
						<?php $active = ($latestOrder->placed) ? "active" : "";?>							
						<li class="d-flex align-items-center <?php echo $active; ?> <?php echo ($latestOrder->order_status=="placed") ? "current_order_status" : "";?>">
							<i class="icon">
								<img src="<?php echo base_url(); ?>assets/front/images/icon-order-placed.svg">
							</i>
							<div class="flex-fill d-flex flex-column">
								<label class="small text-secondary"><?php echo $this->lang->line('order_placed') ?></label>
								<small><?php echo ($latestOrder->placed) ? date("d M Y G:i A", strtotime($latestOrder->placed)) : ''; ?></small>
							</div>
						</li>
						<?php //$active = ($latestOrder->accepted_by_restaurant) ? "active" : "";?>
						<?php $active = ($latestOrder->accepted_by_restaurant || $latestOrder->order_status=="accepted" || $latestOrder->accept_order_time) ? "active" : "";?>
						<li class="d-flex align-items-center <?php echo $active; ?> <?php echo ($latestOrder->order_status=="accepted") ? "current_order_status" : "";?>">
							<i class="icon">
								<img src="<?php echo base_url(); ?>assets/front/images/icon-order-accept.svg">
							</i>
							<div class="flex-fill d-flex flex-column">
								<label class="small text-secondary"><?php echo $this->lang->line('order_accepted_status') ?></label>
								<small><?php echo (isset($latestOrder) && !empty($latestOrder->accepted_by_restaurant)) ? date("d M Y G:i A", strtotime($latestOrder->accepted_by_restaurant)) : ((isset($latestOrder) && !empty($latestOrder->accept_order_time)) ? date("d M Y G:i A", strtotime($latestOrder->accept_order_time)) : '') ; ?></small>
							</div>
						</li>
						<?php /* $active = ($latestOrder->preparing) ? "active" : "";?>
						<!-- 
							<li class="d-flex align-items-center <?php echo $active; ?>">
								<i class="icon">
									<img src="<?php echo base_url(); ?>assets/front/images/icon-order-prepare.svg">
								</i>
								<div class="flex-fill d-flex flex-column">
									<label class="small text-secondary"><?php echo $this->lang->line('preparing') ?></label>
									<small><?php echo ($latestOrder->preparing) ? date("d M Y G:i A", strtotime($latestOrder->preparing)) : ''; ?></small>

								</div>
							</li> --> 
						<?php */ ?>
						<?php if($latestOrder->order_delivery == 'Delivery') { 
							$active = ($latestOrder->onGoing) ? "active" : "";?>
							<li class="d-flex align-items-center <?php echo $active; ?> <?php echo ($latestOrder->order_status=="onGoing" || $latestOrder->order_status=="ready") ? "current_order_status" : "";?>">
								<i class="icon">
									<img src="<?php echo base_url(); ?>assets/front/images/icon-order-way.svg">
								</i>
								<div class="flex-fill d-flex flex-column">
									<label class="small text-secondary"><?php echo $this->lang->line('on_the_way') ?></label>
									<small><?php echo ($latestOrder->onGoing) ? date("d M Y G:i A", strtotime($latestOrder->onGoing)) : ''; ?></small>
								</div>
							</li>
							<?php $active = ($latestOrder->delivered) ? "active" : "";?>
							<li class="d-flex align-items-center <?php echo $active; ?> <?php echo ($latestOrder->order_status=="delivered" || $latestOrder->order_status=="complete") ? "current_order_status" : "";?>">
								<i class="icon">
									<img src="<?php echo base_url(); ?>assets/front/images/icon-order-deliver.svg">
								</i>
								<div class="flex-fill d-flex flex-column">
									<label class="small text-secondary"><?php echo $this->lang->line('order_delivered_status') ?></label>
									<small><?php echo ($latestOrder->delivered) ? date("d M Y G:i A", strtotime($latestOrder->delivered)) : ''; ?></small>
								</div>
							</li>
						<?php } else {
							$active = ($latestOrder->order_ready) ? "active" : ""; ?>
							<li class="d-flex align-items-center <?php echo $active; ?> <?php echo ($latestOrder->order_status=="onGoing" || $latestOrder->order_status=="ready") ? "current_order_status" : "";?>">
								<i class="icon">
									<img src="<?php echo base_url(); ?>assets/front/images/icon-order-way.svg">
								</i>
								<div class="flex-fill d-flex flex-column">
									<label class="small text-secondary"><?php echo $this->lang->line('order_ready') ?></label>
									<small><?php echo ($latestOrder->order_ready) ? date("d M Y G:i A", strtotime($latestOrder->order_ready)) : ''; ?></small>
								</div>
							</li>
							<?php $active = ($latestOrder->completed) ? "active" : "";?>
							<li class="d-flex align-items-center <?php echo $active; ?> <?php echo ($latestOrder->order_status=="delivered" || $latestOrder->order_status=="complete") ? "current_order_status" : "";?>">
								<i class="icon">
									<img src="<?php echo base_url(); ?>assets/front/images/icon-order-deliver.svg">
								</i>
								<div class="flex-fill d-flex flex-column">
									<label class="small text-secondary"><?php echo $this->lang->line('order_completed_status') ?></label>
									<small><?php echo ($latestOrder->completed) ? date("d M Y G:i A", strtotime($latestOrder->completed)) : ''; ?></small>
								</div>
							</li>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
		<?php if (!empty($latestOrder->items)) { ?>
			<div class="col-12">
				<div class="border bg-white container-gutter-xl py-4 p-xl-4">
					<h5><?php echo $this->lang->line('item_details') ?></h5>
					<small><?php echo $this->lang->line('orderid') ?> : #<?php echo $latestOrder->master_order_id; ?></small>
					<div class="table-responsive small w-100 mt-4">
			            <table class="table table-track bg-white table-striped table-bordered table-hover w-100">
			                <thead>
			                    <tr role="row" class="text-nowrap">
			                        <th><?php echo $this->lang->line('s_no')?></th>
			                        <th><?php echo $this->lang->line('item_name')?></th>
			                        <th><?php echo $this->lang->line('quantity')?></th>
			                        <th><?php echo $this->lang->line('total_rate')?></th>
			                    </tr>
			                </thead>
			                <tbody>
			                    <?php foreach ($latestOrder->items as $key => $value) { ?>
			                        <tr role="row">
			                            <td><?php echo $key+1; ?></td>
			                            <td>
			                            	<?php echo $value['name']; ?>
		                                    <?php if (!empty($value['addons_category_list'])) {?>
		                                    	<ul class="item-list small d-flex flex-wrap">
		                                        <?php foreach ($value['addons_category_list'] as $key => $cat_value) { ?>
	                                                <?php if (!empty($cat_value['addons_list'])) {
	                                                    foreach ($cat_value['addons_list'] as $key => $add_value) { ?>
	                                                        <li><strong class="fw-medium"><?php echo $add_value['add_ons_name']; ?>  <?php echo currency_symboldisplay(number_format((float)$add_value['add_ons_price'],2,'.',''),$latestOrder->currency_symbol); ?></li>
	                                                    <?php }
	                                                } ?>
		                                        <?php } ?>
		                                        </ul>
		                                    <?php } ?>
			                                <?php if(!empty($value['comment'])) { ?>
			                                    <div><strong><?php echo $this->lang->line('item_comment')?>:</strong> <?php echo $value['comment']; ?></div>
			                                <?php } ?>
			                            </td>
			                            <td><?php echo $value['quantity']; ?></td>
			                            <td><?php echo currency_symboldisplay(number_format((float)$value['itemTotal'],2,'.',''),$latestOrder->currency_symbol); ?></td>
			                        </tr>
			                    <?php } ?>
								<tr>
									<th colspan="3"><?php echo $this->lang->line('sub_total')?></th>
									<td><?php echo currency_symboldisplay(number_format((float)$latestOrder->subtotal,2,'.',''),$latestOrder->currency_symbol); ?></td>
								</tr>
			                </tbody>
			            </table>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>	
	
<?php } else if ((($this->session->userdata('is_user_login') == 1 && !empty($this->session->userdata('UserID'))) || $is_guest_track_order =='1') && empty($latestOrder)) {?>
	<div class="text-center">
		<h2><?php echo $this->lang->line('hey_there') ?></h2>
		<small><?php echo $this->lang->line('login_to_track') ?></small>
	</div>
<?php } else {?>
	<div class="text-center">
		<h2><?php echo $this->lang->line('hey_there') ?></h2>
		<small><?php echo $this->lang->line('login_to_track') ?></small>
	</div>
<?php }?>
<script type="text/javascript">
<?php if(($latestOrder->delivery_method == 'doordash' && $latestOrder->delivery_tracking_url) || $latestOrder->delivery_method == 'relay' || $latestOrder->order_delivery == 'PickUp'){ 
} else { ?>
	initMap();
    function initMap(){
        map = new google.maps.Map(document.getElementById('map_canvas'),
        {
            center: {
              lat: 20.055,
              lng: 20.968
            },
            zoom: 2
        });
		var directionsService = new google.maps.DirectionsService;
        var infowindow = new google.maps.InfoWindow();
        //var directionsDisplay = new google.maps.DirectionsRenderer;
        var directionsDisplay = new google.maps.DirectionsRenderer({
		    polylineOptions: {
		      strokeColor: "#17161a"
		    }
		  });
        directionsDisplay.setOptions( { suppressMarkers: true } );
        directionsDisplay.setMap(map);
        var bounds = new google.maps.LatLngBounds();
        var waypoints = Array();
        <?php if (!empty($latestOrder->user_latitude) && !empty($latestOrder->user_longitude)): ?>
	        //users location
	        var position = {lat: <?php echo $latestOrder->user_latitude; ?>,lng: <?php echo $latestOrder->user_longitude; ?>};
	        var icon = '<?php echo base_url(); ?>'+'assets/front/images/user-home.png';
	        marker = new google.maps.Marker({
	            position: position,
	            map: map,
				animation: google.maps.Animation.DROP,
				icon: icon
	        });
	        google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
					<?php $user_content = $latestOrder->user_first_name . "<br>" . $latestOrder->user_address; ?>
					infowindow.setContent('<?php echo addslashes($user_content); ?>');
					infowindow.open(map, marker);
				}
			})(marker));
	        bounds.extend(marker.position);
	        waypoints.push({
	            location: marker.position,
	            stopover: true
	        });
        <?php endif ?>
        <?php if (!empty($latestOrder->resLat) && !empty($latestOrder->resLong)): ?>
	        // restaurant location
	        var position = {lat: <?php echo $latestOrder->resLat; ?>,lng: <?php echo $latestOrder->resLong; ?>};
	        var icon = '<?php echo base_url(); ?>'+'assets/front/images/restaurant.png';
	        marker = new google.maps.Marker({
	            position: position,
	            map: map,
				animation: google.maps.Animation.DROP,
				icon: icon
	        });
	        google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
					<?php $res_content = $latestOrder->name . "<br>" . $latestOrder->address; ?>
					infowindow.setContent('<?php echo addslashes($res_content); ?>');
					infowindow.open(map, marker);
				}
			})(marker));
	        bounds.extend(marker.position);
	        waypoints.push({
	            location: marker.position,
	            stopover: true
	        });

        <?php endif ?>
        <?php if (!empty($latestOrder->latitude) && !empty($latestOrder->longitude)): ?>
	        // driver location
	        var position = {lat: <?php echo $latestOrder->latitude; ?>,lng: <?php echo $latestOrder->longitude; ?>};
	        var icon = '<?php echo base_url(); ?>'+'assets/front/images/driver.png';
	        marker = new google.maps.Marker({
	            position: position,
	            map: map,
				animation: google.maps.Animation.DROP,
				icon: icon
	        });
	        bounds.extend(marker.position);
	        waypoints.push({
	            location: marker.position,
	            stopover: true
	        });

        <?php endif ?>
        map.fitBounds(bounds);
        var locationCount = waypoints.length;
        if(locationCount > 0) {
            var start = waypoints[0].location;
            var end = waypoints[locationCount-1].location;
	        directionsService.route({
				origin: start,
				destination: end,
				waypoints: waypoints,
				optimizeWaypoints: true,
				travelMode: google.maps.TravelMode.DRIVING
			}, function(response, status) {
			if (status === 'OK') {
				directionsDisplay.setDirections(response);
			} else {
				//window.alert('Problem in showing direction due to ' + status);
				/*var box = bootbox.alert({
		            message: "<?php //echo $this->lang->line('unable_to_show_direction'); ?>",
		            timeOut : 3000,
		            buttons: {
		                ok: {
		                    label: '<?php //echo $this->lang->line('ok'); ?>',
		                }
		            }
		        });
		        setTimeout(function() {
				    box.modal('hide');
				}, 10000);*/
			}
			});
        }
    }
<?php } ?>
</script>