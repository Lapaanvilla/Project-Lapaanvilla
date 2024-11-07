<?php $this->db->select('OptionValue');
$enable_review = $this->db->get_where('system_option',array('OptionSlug'=>'enable_review'))->first_row();
$show_restaurant_reviews = ($enable_review->OptionValue=='1')?1:0; ?>

<div class="modal-dialog modal-detail modal-dialog-centered modal-xl">
    <div class="modal-content">
        <a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close">
            <img src="<?php base_url() ?>/assets/front/images/icon-close.svg" alt="">
        </a>
        <?php if (!empty($booking_details[0])) {?>
            <div class="row row-cols-1 row-cols-lg-2 g-0">
                <div class="col horizontal-image">
                    <figure class="picture h-100">
                        <?php $image = (file_exists(FCPATH.'uploads/'.$booking_details[0]['image']) && $booking_details[0]['image']!='') ?  image_url. $booking_details[0]['image'] : default_icon_img; ?>
                        <img src="<?php echo $image;?>"> 
                    </figure>
                </div>
                <div class="col">
                    <div class="p-4 p-xl-8">
                        <h2 class="text-capitalize title pb-2 mb-4 mb-xl-6"><?php echo $this->lang->line('booking_details') ?></h2>

                        
                        <h6><?php echo $booking_details[0]['name']; ?></h6>
                        <small class="d-flex">
                            <i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-pin.svg"></i>
                            <?php echo $booking_details[0]['address']; ?>
                        </small>
                        <div class="d-inline-block mt-1">
                            <?php if($show_restaurant_reviews) { 
                            $rating_txt = ($booking_details[0]['restaurant_reviews_count'] > 1)?$this->lang->line('ratings'):$this->lang->line('rating'); 
                            echo ($booking_details[0]['ratings'] > 0)?'<div class="small text-white bg-success  d-flex align-items-center px-2"><i class="icon mt-0"><img src="'. base_url() .'/assets/front/images/icon-star.svg" alt="clock"></i>'.$booking_details[0]['ratings'].' ('.$booking_details[0]['restaurant_reviews_count'].' '.strtolower($rating_txt).')'.'</div>':'<div class="small text-white bg-success px-2">'. $this->lang->line("new") .'</div>'; } ?>
                        </div>
                        <strong class="my-4 d-inline-block w-100"><?php echo $this->lang->line('booking_status') ?> : <?php echo $this->lang->line($booking_details[0]['event_status']).$booking_details[0]['event_cancel_reason'];?></strong>

                        <?php if($booking_details[0]['additional_request'] && $booking_details[0]['additional_request'] != " "){  ?>
                            <p class="mb-4 small"><?php echo $this->lang->line('additional_comment') ?> : <?php echo $booking_details[0]['additional_request'];?></p>
                        <?php } ?>

                        <?php if (!empty($booking_details[0]['package_name'])) { ?>
                            <h6 class="mb-1"><?php echo $this->lang->line('packages') ?></h6>
                            <div class="mb-4 item-package p-2 border bg-body">

                                <div class="d-flex align-items-center">
                                    <figure>
                                        <?php $image = (file_exists(FCPATH.'uploads/'.$booking_details[0]['package_image']) && $booking_details[0]['package_image']!='') ?  image_url. $booking_details[0]['package_image'] : default_icon_img; ?>
                                        <img src="<?php echo $image;?>">
                                    </figure>
                                    <div class="flex-fill">
                                        <h6 class="text-primary"><?php echo $booking_details[0]['package_name']; ?></h6>
                                        <small><?php echo $booking_details[0]['package_detail']; ?></small>
                                        <h6><?php echo currency_symboldisplay($booking_details[0]['package_price'],$booking_details[0]['currency_symbol']); ?></h6>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <ul class="item-detail small row row-cols-1 row-cols-sm-2 d-flex row-grid row-grid-sm">
                           <li class="col d-flex align-items-center">
                                <i class="icon">
                                    <img src="<?php echo base_url();?>assets/front/images/icon-avatar.png">
                                </i>
                                <div class="flex-fill">
                                    <label class="w-100"><?php echo $this->lang->line('no_of_people') ?></label>
                                    <span><?php echo $booking_details[0]['no_of_people']; ?> <?php echo $this->lang->line('people'); ?></span>
                                </div>
                            </li>
                           <li class="col d-flex align-items-center">
                                <i class="icon">
                                    <img src="<?php echo base_url();?>assets/front/images/icon-date.png">
                                </i>
                                <div class="flex-fill">
                                    <label class="w-100"><?php echo $this->lang->line('event_date') ?></label>
                                    <span><?php echo $this->common_model->dateFormat($booking_details[0]['booking_date']);?></span>
                                </div>
                            </li>
                           <li class="col d-flex align-items-center">
                                <i class="icon">
                                    <img src="<?php echo base_url();?>assets/front/images/icon-table.png">
                                </i>
                                <div class="flex-fill">
                                    <label class="w-100"><?php echo $this->lang->line('dining_time') ?></label>
                                    <span><?php echo $this->common_model->timeFormat($booking_details[0]['booking_date']);?></span>
                                </div>
                            </li>
                           <li class="col d-flex align-items-center">
                                <i class="icon">
                                    <img src="<?php echo base_url();?>assets/front/images/icon-date.png">
                                </i>
                                <div class="flex-fill">
                                    <label class="w-100"><?php echo $this->lang->line('booking_date_txt') ?></label>
                                    <span><?php echo $this->common_model->dateFormat($booking_details[0]['created_date']);?></span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>