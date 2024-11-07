    <?php defined('BASEPATH') or exit('No direct script access allowed');?>
    <?php $this->load->view('header'); 
    $stripe_info = stripe_details(); ?>
    <?php 
    require APPPATH . 'libraries/PaypalExpress.php';
    $paypal_obj = new PaypalExpress;
    $paypal = $paypal_obj->paypal_details();    

    //get System Option Data
    $this->db->select('OptionValue');
    $currency_id = $this->db->get_where('system_option',array('OptionSlug'=>'currency'))->first_row();
    $currency_symbol = $this->common_model->getCurrencySymbol($currency_id->OptionValue); 
    $currency_symboltemp = $currency_symbol;
    $currency_symbol = $currency_symbol->currency_symbol;    
    $this->db->select('OptionValue');
    $enable_review = $this->db->get_where('system_option',array('OptionSlug'=>'enable_review'))->first_row();
    $show_restaurant_reviews = ($enable_review->OptionValue=='1')?1:0;
    ?> 
    <!-- Embed the intl-tel-input plugin :: start -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/intl_tel_input/intlTelInput.css">
    <script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/intl_tel_input/intlTelInput.min.js"></script>
    <!-- Embed the intl-tel-input plugin :: end -->
    <!-- <link href="<?php echo base_url();?>assets/admin/layout/css/custom.css" rel="stylesheet"> -->
    <!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/front/css/stripe/stripe_modal.css"> -->
    <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
    <section class="section-dashboard">
        <?php 
        if(isset($_SESSION['myProfileMSG']))
        { ?>
            <div class="alert alert-success">
                 <?php echo $_SESSION['myProfileMSG'];
                    unset($_SESSION['myProfileMSG']);
                 ?>
            </div>
        <?php } ?>                
        <?php 
        if(isset($_SESSION['success_MSG']))
        { ?>
            <div class="alert alert-success">
                 <?php echo $_SESSION['success_MSG'];
                    unset($_SESSION['success_MSG']);
                 ?>
            </div>
        <?php } ?>                
        <?php 
        if(isset($_SESSION['myProfileMSGerror']))
        { ?>
            <div class="alert alert-danger">
                 <?php echo $_SESSION['myProfileMSGerror'];
                    unset($_SESSION['myProfileMSGerror']);
                 ?>
            </div>
        <?php } ?>

        <div class="d-flex" id="profile_page_content">

            <aside class="bg-white transition d-flex flex-column">
                <div class="aside-backdrop transition d-xl-none"></div>
                <div class="title-dashboard p-4 bg-secondary d-none d-md-inline-block w-100">
                    <h1 class="h5 text-white fw-medium"><?php echo $this->lang->line('my_profile') ?></h1>
                </div>
                <div class="p-4 flex-fill bg-white">
                    <h6><?php echo $profile->first_name . ' ' . $profile->last_name; ?></h6>
                    <ul class="small mb-3 mb-md-4">
                        <?php if (!empty($addresses)) {
                            foreach ($addresses as $key => $value) {
                                if ($value->is_main == 1) {?>
                                    <li><i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-pin.svg" alt=""></i><?php echo $value->address; ?></li>
                                    <?php //echo $value->address . ', ' . $value->city . ', ' . $value->zipcode; ?>
                                <?php break;}
                            }
                        }?>
                        <?php if($profile->mobile_number){ ?>
                            <li><i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-phone.svg" alt=""></i><?php echo '+'.$profile->phone_code.$profile->mobile_number; ?></li>
                        <?php } ?>

                        <?php if($this->session->userdata('UserType') != 'Agent' || $this->session->userdata('UserType') == '') { ?>
                            <!-- earning points changes start -->
                            <?php if(empty($profile->wallet)) {
                                $profile->wallet = 0;
                            } ?>
                            <li><i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-card.svg" alt=""></i><?php echo $this->lang->line('wallet_balance'); echo ': <strong>'; echo currency_symboldisplay($profile->wallet,$currency_symbol); ?></strong></li>
                            <!-- earning points changes end -->
                        <?php } ?>

                    </ul>
                    <div class="d-flex">
                        <button class="btn btn-xs btn-primary px-2" data-toggle="modal" class="edit-profile" data-target="#edit-profile"><?php echo $this->lang->line('edit_profile') ?></button>
                        <?php if($this->session->userdata('UserType') != 'Agent' || $this->session->userdata('UserType') == '') { ?>
                            <div class="ps-1 pt-1"></div>
                            <button class="btn btn-xs btn-secondary px-2" data-toggle="modal" onclick="showDeleteAcc();"><?php echo $this->lang->line('delete_acc') ?></button>
                        <?php } ?>
                    </div>

                    <i class="icon icon-line text-light d-flex mt-4 mb-3 mt-md-6 mb-md-5"><img src="<?php echo base_url();?>/assets/front/images/icon-border-dark.svg" alt="Border"></i>

                    <h6><?php echo $this->lang->line('ordering') ?></h6>
                    <ul id="myTab" class="nav nav-tabs flex-column">
                        <li id="tab_order_history" class="tabs <?php echo ($selected_tab == '')?'active':'';?>" onclick="addActiveClass(this.id)">
                            <a href="#order_history" data-toggle="tab">
                                <i class="icon"><img src="<?php echo base_url();?>/assets/front/images/icon-order.svg" alt=""></i>
                                <?php echo $this->lang->line('order_history') ?>
                            </a>
                        </li>
                        <?php if($this->session->userdata('UserType') != 'Agent' || $this->session->userdata('UserType') == '') { ?>
                        <li id="tab_bookings" class="tabs <?php echo ($selected_tab == 'bookings')?'active':'';?>" onclick="addActiveClass(this.id)">
                            <a href="#bookings" data-toggle="tab">
                                <i class="icon"><img src="<?php echo base_url();?>/assets/front/images/icon-book.svg" alt=""></i>
                                <?php echo $this->lang->line('my_bookings') ?>
                            </a>
                        </li> 
                        <li id="tab_table_bookings" class="tabs <?php echo ($selected_tab == 'table_bookings')?'active':'';?>" onclick="addActiveClass(this.id)">
                            <a href="#table_bookings" data-toggle="tab">
                                <i class="icon"><img src="<?php echo base_url();?>/assets/front/images/icon-table.svg" alt=""></i>
                                <?php echo $this->lang->line('table_bookings') ?>
                            </a>
                        </li>
                        <li id="tab_wallet_history" class="tabs <?php echo ($selected_tab == 'wallet_history')?'active':'';?>" onclick="addActiveClass(this.id)">
                            <a href="#wallet_history" data-toggle="tab">
                                <i class="icon"><img src="<?php echo base_url();?>/assets/front/images/icon-wallet.svg" alt=""></i>
                                <?php echo $this->lang->line('wallet_history') ?>
                            </a>
                        </li>
                        <li id="tab_addresses" class="tabs <?php echo ($selected_tab == 'addresses')?'active':'';?>" onclick="addActiveClass(this.id)">
                            <a href="#addresses" data-toggle="tab">
                                <i class="icon"><img src="<?php echo base_url();?>/assets/front/images/icon-pin.svg" alt=""></i>
                                <?php echo $this->lang->line('my_addresses') ?>
                            </a>
                        </li>
                        <li id="tab_notifications" class="tabs <?php echo ($selected_tab == 'notifications')?'active':'';?>" onclick="addActiveClass(this.id)">
                            <a href="#notifications" data-toggle="tab">
                                <i class="icon"><img src="<?php echo base_url();?>/assets/front/images/icon-notification.svg" alt=""></i>
                                <?php echo $this->lang->line('my_notifications') ?>
                            </a>
                        </li>                                
                        <li id="tab_payment_card" class="tabs <?php echo ($selected_tab == 'payment_card')?'active':'';?>" onclick="addActiveClass(this.id)">
                            <a href="#payment_card" data-toggle="tab">
                                <i class="icon"><img src="<?php echo base_url();?>/assets/front/images/icon-card.svg" alt=""></i>
                                <?php echo $this->lang->line('card_detail') ?>
                            </a>
                        </li>
                        <?php } ?>
                        <li id="tab_bookmark" class="tabs <?php echo ($selected_tab == 'bookmark')?'active':'';?>" onclick="addActiveClass(this.id)">
                            <a href="#bookmarks" data-toggle="tab">
                                <i class="icon"><img src="<?php echo base_url();?>/assets/front/images/icon-heart.svg" alt=""></i>
                                <?php echo $this->lang->line('my_bookmarks'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </aside>
            <div id="myTabContent" class="tab-content flex-fill d-flex flex-column overflow-hidden">
                <div class="tab-pane flex-fill d-flex flex-column fade <?php echo ($selected_tab == "")?"in active show":"";?>" id="order_history">
                    <div class="title-dashboard bg-secondary text-center container-gutter-xl px-xl-8 py-4">
                        <h5 class="text-white fw-medium d-flex align-items-center justify-content-center">
                            <i class="icon icon-small d-xl-none"><img src="<?php echo base_url();?>assets/front/images/icon-accordion.svg" alt=""></i>
                            <?php echo $this->lang->line('order_history') ?>
                        </h5>
                    </div>
                    <ul class="nav nav-tabs border-bottom justify-content-center small d-flex" role="tablist">
                        <li class="nav-item">
                            <a href="#current-orders" class="nav-link active" data-toggle="tab"><?php echo $this->lang->line('current_orders') ?></a>
                        </li>
                        <li class="nav-item">
                            <a href="#past-orders" class="nav-link" data-toggle="tab"><?php echo $this->lang->line('past_orders') ?></a>
                        </li>
                    </ul>

                    <div class="tab-content tab-content-md container-gutter-xl py-md-8 p-xl-8 d-flex flex-column flex-fill overflow-hidden">
                        <?php 
                            if(isset($_SESSION['review_added']))
                            { ?>
                            <div class="alert alert-success" id="review_success">
                                 <?php echo $_SESSION['review_added'];
                                    unset($_SESSION['review_added']);
                                 ?>
                            </div>
                        <?php } ?>

                        <div id="past-orders" class="tab-pane w-100 h-100">
                            <?php if (!empty($past_orders)) { ?>
                                <div class="row row-grid row-grid-md row-cols-1 row-cols-md-2 row-cols-xxl-3 horizontal-image">
                                    <?php if (!empty($past_orders)) {
                                        foreach ($past_orders as $key => $value) {
                                                $past_order_drivertip = 0;
                                                $subtotal = 0;
                                                $delivery_charges = 0;
                                                $total = 0;
                                                $coupon_amount = 0;
                                                if (!empty($value['price'])) {
                                                    foreach ($value['price'] as $pkey => $pvalue) {
                                                        if (isset($pvalue['label_key']) && $pvalue['label_key'] == "Sub Total") {
                                                            $subtotal = $pvalue['value'];
                                                        }
                                                        if (isset($pvalue['label_key']) && $pvalue['label_key'] == "Delivery Charge") {
                                                            $delivery_charges = $pvalue['value'];
                                                        }
                                                        if (isset($pvalue['label_key']) && $pvalue['label_key'] == "Coupon Amount") {
                                                            $coupon_amount = $pvalue['value'];
                                                        }
                                                        if (isset($pvalue['label_key']) && $pvalue['label_key'] == "Total") {
                                                            $total = $pvalue['value'];
                                                        }
                                                        if (isset($pvalue['label_key']) && $pvalue['label_key'] == "Driver Tip") {
                                                            $past_order_drivertip = (float)$pvalue['value'];
                                                        }
                                                    } 
                                                } ?>
                                                <div class="col">
                                                    <div class="box-restaurant d-inline-block w-100 bg-white h-100 d-flex flex-column">
                                                        <figure class="picture">

                                                            <?php $image = (file_exists(FCPATH.'uploads/'.$value['restaurant_image']) && $value['restaurant_image']!='') ?  image_url. $value['restaurant_image'] : default_icon_img;
                                                            $rating_txt = ($value['restaurant_reviews_count'] > 1)?$this->lang->line('ratings'):$this->lang->line('rating'); 
                                                            ?>

                                                            <img src="<?php echo $image; ?>">

                                                            <div class="icon-left d-flex text-capitalize">
                                                                <?php if ($show_restaurant_reviews) { ?>
                                                                    <?php echo ($value['ratings'] > 0)?'<div class="small text-white bg-success  d-flex align-items-center"><i class="icon"><img src="'. base_url() .'/assets/front/images/icon-star.svg" alt="clock"></i>'.$value['ratings'].' ('.$value['restaurant_reviews_count'].' '.strtolower($rating_txt).')'.'</div>':'<div class="small text-white bg-success">'. $this->lang->line("new") .'</div>'; ?>
                                                                <?php } ?>
                                                            </div>
                                                        </figure>
                                                        <div class="d-flex justify-content-between align-items-center px-4 py-2 border-bottom">
                                                            <span class="small">#<?php echo $this->lang->line('orderid') ?> - <span class="fw-medium"><?php echo $value['order_id']; ?></span></span>
                                                            <span class="small">
                                                                <?php echo $this->lang->line('price') ?> : <span class="text-success fw-medium"><?php echo currency_symboldisplay($total,$value['currency_symbol']);?></span>
                                                            </span>
                                                        </div>
                                                        <div class="p-4">
                                                            <a class="h6" href="<?php echo ($value['restaurant_status']=='1')?base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug']:'#';?>"><?php echo $value['restaurant_name']; ?></a>

                                                            <?php if($this->session->userdata('UserType') == 'Agent') { ?>
                                                                <strong><?php echo $this->lang->line('customer') ?> : <span><?php echo $value['user_name']; ?></span></strong>
                                                            <?php } ?>


                                                            <?php if($value['refund_status']!='' && $value['refund_status']!=null){ ?>
                                                                <span class="small mb-2"><?php echo $this->lang->line('refund_status') ?> - <?php echo ucfirst($this->lang->line(str_replace(" ", "_", $value['refund_status']))) ?></span>
                                                            <?php } ?>

                                                            <?php if($value['scheduled_date'] && $value['slot_open_time'] && $value['slot_close_time']) { ?>
                                                                <span class="small mb-2"><?php echo $this->lang->line('order_scheduled_for').$this->common_model->dateFormat($value['scheduled_date']).' ('.$this->common_model->timeFormat($value['slot_open_time']).' - '.$this->common_model->timeFormat($value['slot_close_time']).' )'; ?></span>
                                                            <?php } ?>

                                                            <input type="hidden" id="tip_orderid<?php echo $key ?>" name="tip_orderid<?php echo $key ?>" value="<?php echo $value['order_id']; ?>" />
                                                           

                                                            <ul class="d-flex flex-wrap mt-2">
                                                                <li>
                                                                    <small>
                                                                        <i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-calendar.svg" alt=""></i>
                                                                        <?php echo $this->common_model->datetimeFormat($value['order_date']); ?>
                                                                    </small>
                                                                </li>
                                                                <li> 
                                                                    <?php $order_status_txt = ($value['order_status'] == "complete") ? $this->lang->line('completed') : (($value['order_status'] == "cancel") ? $this->lang->line('cancelled') : $this->lang->line($value['order_status']));
                                                                    ?>
                                                                    <small>
                                                                        <i class="icon icon-small bg-light rounded-circle"><img src="<?php echo base_url();?>assets/front/images/icon-status.svg" alt=""></i>
                                                                        <?php echo ($value['payment_status']=='paid' || $value['payment_status']== NULL)?$order_status_txt:$this->lang->line($value['payment_status']); ?>
                                                                    </small>
                                                                </li>
                                                            </ul>
                                                        </div>

                                                        <div class="d-flex mt-auto border-top text-center bg-light">
                                                            <a class="py-2 px-1 small fw-medium text-secondary flex-fill" href="javascript:void(0)" onclick="order_details(<?php echo $value['order_id']; ?>)"><?php echo $this->lang->line('view_details') ?></a>

                                                            <?php if($past_order_drivertip == 0 && $value['delivery_flag'] == "delivery" && (strtolower($value['order_status'])=='delivered' || strtolower($value['order_status'])=='complete') && $value['refund_status']!='refunded') { ?>

                                                                <a class="py-2 px-1 small fw-medium text-secondary flex-fill" href="javascript:void(0)" id="tipbtn<?php echo $value['order_id'] ?>" data-toggle="modal" onclick="tip_driver(<?php echo $value['order_id']; ?>)"><?php echo $this->lang->line('tip_driver') ?></a>
                                                            <?php } ?>

                                                            <?php if($this->session->userdata('UserType') != 'Agent'){ ?>


                                                                <a class="py-2 px-1 small fw-medium text-secondary flex-fill" href="javascript:void(0)" onclick="reorder_details(<?php echo $value['order_id']; ?>)"><?php echo $this->lang->line('reorder') ?></a>
                                                            
                                                                <?php if (!empty($this->session->userdata('UserID')) && !in_array($value['order_id'], $arrReviewOrderId) && (strtolower($value['order_status'])=='delivered' || strtolower($value['order_status'])=='complete')) { ?>

                                                                    <?php if($show_restaurant_reviews){ ?>

                                                                        <a class="py-2 px-1 small fw-medium text-secondary flex-fill" href="javascript:void(0)" onclick="addReview(<?php echo $value['restaurant_id'] ?>,<?php echo $value['res_content_id'] ?>,<?php echo $value['order_id']; ?>)"><?php echo $this->lang->line('title_admin_reviewadd'); ?></a>
                                                                    <?php } 
                                                                } 
                                                            } ?>
                                                        </div>
                                                    </div>
                                                </div>                                                            
                                        <?php }
                                    }?>
                                </div>
                                <?php if ($past_orders_count>6) { ?>
                                <div id="all_past_orders" style="display: none;">
                                </div>    
                                <div id="more_past_orders" class="w-100 text-center pt-8 pb-8 pb-md-0">
                                    <input type="hidden" name="pord_page_no" id="pord_page_no" value="2">
                                    <button class="btn btn-primary" id="pastorder_button" onclick="moreOrders('past')"><?php echo $this->lang->line('load_more') ?></button>
                                </div>
                                <?php }?>
                            <?php }
                            else { ?>
                                <div class="d-flex justify-content-center align-items-center w-100 h-100">
                                    <div class="screen-blank text-center my-auto margin-child px-4 py-8 py-md-0">
                                        <figure class="mb-4">
                                            <img src="<?php echo no_res_found; ?>">
                                        </figure>
                                        <h6><?php echo $this->lang->line('no_past_orders') ?></h6>
                                    </div>
                                </div>
                            <?php }?>
                        </div>
                        <div id="current-orders" class="tab-pane show active w-100 h-100">
                            <?php if (!empty($in_process_orders)) { ?>
                            <div class="row row-grid row-grid-md row-cols-1 row-cols-md-2 row-cols-xxl-3 horizontal-image">
                                <?php if (!empty($in_process_orders)) {
                                    foreach ($in_process_orders as $key => $value) {
                                            $subtotal = 0;
                                            $delivery_charges = 0;
                                            $total = 0;
                                            $coupon_amount = 0;
                                            if (!empty($value['price'])) {
                                                foreach ($value['price'] as $pkey => $pvalue) {
                                                    if (isset($pvalue['label_key']) && $pvalue['label_key'] == "Sub Total") {
                                                        $subtotal = $pvalue['value'];
                                                    }
                                                    if (isset($pvalue['label_key']) && $pvalue['label_key'] == "Delivery Charge") {
                                                        $delivery_charges = $pvalue['value'];
                                                    }
                                                    if (isset($pvalue['label_key']) && $pvalue['label_key'] == "Coupon Amount") {
                                                        $coupon_amount = $pvalue['value'];
                                                    }
                                                    if (isset($pvalue['label_key']) && $pvalue['label_key'] == "Total") {
                                                        $total = $pvalue['value'];
                                                    }
                                                }
                                            } ?>
                                            <div class="col">
                                                <div class="box-restaurant d-inline-block w-100 bg-white h-100 d-flex flex-column">
                                                    <figure class="picture">
                                                         <?php $image = (file_exists(FCPATH.'uploads/'.$value['restaurant_image']) && $value['restaurant_image']!='') ?  image_url. $value['restaurant_image'] : default_icon_img;
                                                         $rating_txt = ($value['restaurant_reviews_count'] > 1)?$this->lang->line('ratings'):$this->lang->line('rating'); ?>

                                                        <img src="<?php echo $image; ?>">

                                                        <div class="icon-left d-flex text-capitalize">
                                                            <?php if ($show_restaurant_reviews) { ?>
                                                                <?php echo ($value['ratings'] > 0)?'<div class="small text-white bg-success  d-flex align-items-center"><i class="icon"><img src="'. base_url() .'/assets/front/images/icon-star.svg" alt="clock"></i>'.$value['ratings'].' ('.$value['restaurant_reviews_count'].' '.strtolower($rating_txt).')'.'</div>':'<div class="small text-white bg-success">'. $this->lang->line("new") .'</div>'; ?>
                                                            <?php } ?> 
                                                        </div>
                                                    </figure>

                                                    <div class="d-flex justify-content-between align-items-center px-4 py-2 border-bottom">
                                                        <span class="small">#<?php echo $this->lang->line('orderid') ?> - <span class="fw-medium"><?php echo $value['order_id']; ?></span></span>
                                                        <span class="small">
                                                            <?php echo $this->lang->line('price') ?> : <span class="text-success fw-medium"><?php echo currency_symboldisplay($total,$value['currency_symbol']);?></span>
                                                        </span>
                                                    </div>

                                                    <div class="p-4">
                                                        <a class="h6" href="<?php echo ($value['restaurant_status']=='1')?base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug']:'#';?>"><?php echo $value['restaurant_name']; ?></a>

                                                        <?php if($this->session->userdata('UserType') == 'Agent') { ?>
                                                            <strong><?php echo $this->lang->line('customer') ?> : <span><?php echo $value['user_name']; ?></span></strong>
                                                        <?php } ?>

                                                        <?php if($value['refund_status']!='' && $value['refund_status']!=null){ ?>
                                                            <span class="small mb-2"><?php echo $this->lang->line('refund_status') ?> - <?php echo ucfirst($this->lang->line(str_replace(" ", "_", $value['refund_status']))) ?></span>
                                                        <?php } ?>
                                                        <?php if($value['scheduled_date'] && $value['slot_open_time'] && $value['slot_close_time']) { ?>
                                                            <span class="small mb-2"><?php echo $this->lang->line('order_scheduled_for').$this->common_model->dateFormat($value['scheduled_date']).' ('.$this->common_model->timeFormat($value['slot_open_time']).' - '.$this->common_model->timeFormat($value['slot_close_time']).' )'; ?></span>
                                                        <?php } ?>

                                                        <ul class="d-flex flex-wrap">
                                                            <li>
                                                                <small>
                                                                    <i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-calendar.svg" alt=""></i>
                                                                    <?php echo $this->common_model->datetimeFormat($value['order_date']); ?>
                                                                </small>
                                                            </li>
                                                            <li> 
                                                                <?php $order_status_txt = ($value['order_status'] == "complete") ? $this->lang->line('completed') : (($value['order_status'] == "cancel") ? $this->lang->line('cancelled') : $this->lang->line($value['order_status']));
                                                                ?>
                                                                <small>
                                                                    <i class="icon icon-small bg-light rounded-circle"><img src="<?php echo base_url();?>assets/front/images/icon-status.svg" alt=""></i>
                                                                    <?php echo ($value['payment_status']=='paid' || $value['payment_status']== NULL)?$this->lang->line($value['order_status']):$this->lang->line($value['payment_status']); ?>
                                                                </small>
                                                            </li>
                                                        </ul>

                                                        <?php if($value['show_cancel_order'] == '1' && $value['order_status']=='placed') { ?>
                                                            <div class="cancel_timer alert alert-sm alert-danger" id="cancel_timer<?php echo $key ?>"></div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="d-flex mt-auto border-top text-center bg-light">
                                                        <a class="py-2 px-1 small fw-medium text-secondary flex-fill" href="javascript:void(0)" data-toggle="modal" onclick="order_details(<?php echo $value['order_id']; ?>)">
                                                            <?php echo $this->lang->line('view_details') ?>
                                                        </a>

                                                        <?php $newdate = date_format(date_create($value['timer_order_date']),"M d,Y H:i:s"); ?>
                                                        <input type="hidden" name="OrderStatus" id="OrderStatus<?php echo $key ?>" value="<?php echo $value['order_status']; ?>">
                                                        <input type="hidden" name="OrderDate" id="OrderDate<?php echo $key ?>" value="<?php echo $value['order_dateorg']; ?>">


                                                        <?php if($value['show_cancel_order'] == '1' && $value['order_status']=='placed') { ?>
                                                            <a class="py-2 px-1 small fw-medium text-secondary flex-fill" id="cancel_order<?php echo $key; ?>" data-toggle="modal" onclick="cancel_order(<?php echo $value['order_id']; ?>)"><?php echo $this->lang->line('cancel_order') ?></a>
                                                            <input type="hidden" id="orderid<?php echo $key ?>" name="orderid<?php echo $key ?>" value="<?php echo $value['order_id']; ?>" />
                                                        <?php } ?>


                                                        <?php //if ($value['delivery_flag'] == "delivery") { ?>
                                                            <a class="py-2 px-1 small fw-medium text-secondary flex-fill" href="<?php echo base_url().'order/track_order/'.str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($value['order_id'])); ?>" class="btn"><?php echo $this->lang->line('track_order') ?></a>
                                                        <?php //} ?>
                                                    </div>

                                                </div>
                                            </div>
                                    <?php }
                                }?>
                            </div>
                            <?php if ($in_process_orders_count>6){?>
                                <div id="all_current_orders" style="display: none;">  
                                </div>
                                <div id="more_in_process_orders" class="w-100 text-center pt-8 pb-8 pb-md-0">
                                    <input type="hidden" name="cord_page_no" id="cord_page_no" value="2">
                                    <button class="btn btn-primary" onclick="moreOrders('process')"><?php echo $this->lang->line('load_more') ?></button>
                                </div>
                            <?php }?>
                            <?php }
                            else { ?>
                                <div class="d-flex justify-content-center align-items-center w-100 h-100">
                                    <div class="screen-blank text-center my-auto margin-child px-4 py-8 py-md-0">
                                        <figure class="mb-4">
                                            <img src="<?php echo no_res_found; ?>">
                                        </figure>
                                        <h6><?php echo $this->lang->line('no_current_orders') ?></h6>
                                    </div>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                </div>

                <div class="tab-pane flex-fill d-flex flex-column fade <?php echo ($selected_tab == "bookings")?"in active show":"";?>" id="bookings">
                    <div class="title-dashboard bg-secondary text-center container-gutter-xl px-xl-8 py-4">
                        <h5 class="text-white fw-medium d-flex align-items-center justify-content-center">
                            <i class="icon icon-small d-xl-none"><img src="<?php echo base_url();?>assets/front/images/icon-accordion.svg" alt=""></i>
                            <?php echo $this->lang->line('my_bookings') ?>
                        </h5>
                    </div>
                    <ul class="nav nav-tabs border-bottom justify-content-center small d-flex" role="tablist">
                        <li class="nav-item">
                            <a href="#current-bookings" class="nav-link active" data-toggle="tab"><?php echo $this->lang->line('upcoming_bookings') ?></a>
                        </li>
                        <li class="nav-item">
                            <a href="#past-bookings" class="nav-link" data-toggle="tab"><?php echo $this->lang->line('past_bookings') ?></a>
                        </li>
                    </ul>
                    <div class="tab-content tab-content-md container-gutter-xl py-md-8 p-xl-8 d-flex flex-column flex-fill overflow-hidden">
                        <div id="past-bookings" class="tab-pane w-100 h-100">
                            <?php if (!empty($past_events)) { ?>
                                <div class="row row-grid row-grid-md row-cols-1 row-cols-md-2 row-cols-xxl-3 horizontal-image">
                                    <?php if (!empty($past_events)) {
                                        foreach ($past_events as $key => $value) {
                                            if ($key <= 5) { ?>
                                                <div class="col">
                                                    <div class="box-restaurant d-inline-block w-100 bg-white h-100 d-flex flex-column">
                                                        <figure class="picture">
                                                            <?php $image = (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='') ?  image_url. $value['image'] : default_icon_img; 
                                                                    $rating_txt = ($value['restaurant_reviews_count'] > 1)?$this->lang->line('ratings'):$this->lang->line('rating'); ?>

                                                            <img src="<?php echo $image;?>">

                                                            <div class="icon-left d-flex text-capitalize">
                                                            <?php if ($show_restaurant_reviews) { ?> 
                                                            <?php echo ($value['ratings'] > 0)?'<div class="small text-white bg-success  d-flex align-items-center"><i class="icon"><img src="'. base_url() .'/assets/front/images/icon-star.svg" alt="clock"></i>'.$value['ratings'].' ('.$value['restaurant_reviews_count'].' '.strtolower($rating_txt).')'.'</div>':'<div class="small text-white bg-success">'. $this->lang->line("new") .'</div>'; ?>
                                                            <?php } ?>
                                                            </div>
                                                        </figure>
                                                        <div class="p-4 d-flex flex-column flex-fill">
                                                            <a class="h6" href="<?php echo ($value['restaurant_status']=='1')?base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug']:'#';?>"><?php echo $value['name'];?></a>
                                                            <small class="mb-auto"><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-pin.svg" alt=""></i><?php echo $value['address'];?></small>

                                                            

                                                            <ul class="d-flex flex-wrap small mt-2 mb-4">
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-calendar.svg" alt=""></i><?php echo $this->common_model->dateFormat($value['booking_date']);?></li>
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-clock.svg" alt=""></i><?php echo $this->common_model->timeFormat($value['booking_date']);?></li>
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-user.svg" alt=""></i><?php echo $value['no_of_people'];?> <?php echo $this->lang->line('people') ?></li>
                                                            </ul>


                                                            <?php if(!empty($value['package_name'])) { ?>
                                                                <div class="small d-flex justify-content-between align-items-center border-top pt-2 pb-2">
                                                                    <span class="fw-medium"><?php echo $this->lang->line('pkg') ?> :</span>
                                                                    <span><?php echo $value['package_name'];?></span>
                                                                </div>
                                                            <?php } ?>

                                                            <div class="small d-flex justify-content-between align-items-center pt-2 border-top">
                                                                <span class="fw-medium"><?php echo $this->lang->line('booking_status') ?> :</span>
                                                                <span><?php echo $this->lang->line($value['event_status']); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex mt-auto border-top text-center bg-light">
                                                            <a href="javascript:void(0)" class="py-2 small fw-medium text-secondary w-100 mw-100" data-toggle="modal" onclick="booking_details(<?php echo $value['entity_id']; ?>)">
                                                                <?php echo $this->lang->line('view_details') ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } 
                                        }
                                    } ?>
                                </div>
                                <?php if (count($past_events) > 6) { ?>
                                    <div id="all_past_events" style="display: none;">
                                        <div class="row row-grid row-grid-md row-cols-1 row-cols-md-2 row-cols-xxl-3 horizontal-image mt-0">
                                        <?php foreach ($past_events as $key => $value) {
                                            if ($key > 7) { ?>
                                                <div class="col">
                                                    <div class="box-restaurant d-inline-block w-100 bg-white h-100 d-flex flex-column">
                                                        <figure class="picture">            
                                                             <?php $image = (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='') ?  image_url. $value['image'] : default_icon_img; 
                                                             $rating_txt = ($value['restaurant_reviews_count'] > 1)?$this->lang->line('ratings'):$this->lang->line('rating'); ?>

                                                            <img src="<?php echo $image;?>">

                                                            <div class="icon-left d-flex text-capitalize">
                                                                <?php if ($show_restaurant_reviews) { ?> 
                                                                <?php echo ($value['ratings'] > 0)?'<div class="small text-white bg-success  d-flex align-items-center"><i class="icon"><img src="'. base_url() .'/assets/front/images/icon-star.svg" alt="clock"></i>'.$value['ratings'].' ('.$value['restaurant_reviews_count'].' '.strtolower($rating_txt).')'.'</div>':'<div class="small text-white bg-success">'. $this->lang->line("new") .'</div>'; ?>
                                                                <?php } ?>
                                                            </div>
                                                        </figure>
                                                        <div class="p-4 d-flex flex-column flex-fill">
                                                            <a class="h6" href="<?php echo ($value['restaurant_status']=='1')?base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug']:'#';?>"><?php echo $value['name'];?></a>
                                                            <small class="mb-auto"><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-pin.svg" alt=""></i><?php echo $value['address'];?></small>

                                                            
                                                           
                                                            <ul class="d-flex flex-wrap small mt-2 mb-4">
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-calendar.svg" alt=""></i><?php echo $this->common_model->dateFormat($value['booking_date']);?></li>
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-clock.svg" alt=""></i><?php echo $this->common_model->timeFormat($value['booking_date']);?></li>
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-user.svg" alt=""></i><?php echo $value['no_of_people'];?> <?php echo $this->lang->line('people') ?></li>
                                                            </ul>

                                                            <?php if(!empty($value['package_name'])) { ?>
                                                                <div class="small d-flex justify-content-between align-items-center border-top pt-2 pb-2">
                                                                    <span class="fw-medium"><?php echo $this->lang->line('pkg') ?> :</span>
                                                                    <span><?php echo $value['package_name'];?></span>
                                                                </div>
                                                            <?php } ?>

                                                            <div class="small d-flex justify-content-between align-items-center pt-2 border-top">
                                                                <span class="fw-medium"><?php echo $this->lang->line('booking_status') ?> : </span>
                                                                <span><?php echo $this->lang->line($value['event_status']); ?></span>
                                                            </div>
                                                        </div>

                                                        <div class="d-flex mt-auto border-top text-center bg-light">
                                                            <a href="javascript:void(0)" class="py-2 small fw-medium text-secondary w-100 mw-100" data-toggle="modal" onclick="booking_details(<?php echo $value['entity_id']; ?>)">
                                                                <?php echo $this->lang->line('view_details') ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php }
                                        } ?>
                                        </div>
                                    </div>
                                    <div id="more_past_events" class="w-100 text-center pt-8 pb-8 pb-md-0">
                                        <button class="btn btn-primary" onclick="moreEvents('past')"><?php echo $this->lang->line('load_more') ?></button>
                                    </div>
                                <?php } ?>
                            <?php } 
                            else { ?>
                                <div class="d-flex justify-content-center align-items-center w-100 h-100">
                                    <div class="screen-blank text-center my-auto margin-child px-4 py-8 py-md-0">
                                        <figure class="mb-4">
                                            <img src="<?php echo no_res_found; ?>">
                                        </figure>
                                        <h6><?php echo $this->lang->line("no_past_booking_found"); ?></h6>
                                    </div>
                                </div>
                            <?php }?>
                        </div>
                        <div id="current-bookings" class="tab-pane show active w-100 h-100">
                            <?php if (!empty($upcoming_events)) { ?>
                                <div class="row row-grid row-grid-md row-cols-1 row-cols-md-2 row-cols-xxl-3 horizontal-image">
                                    <?php if (!empty($upcoming_events)) {
                                        foreach ($upcoming_events as $key => $value) {
                                            if ($key <= 5) { ?>
                                                <div class="col">
                                                    <div class="box-restaurant d-inline-block w-100 bg-white h-100 d-flex flex-column">
                                                        <figure class="picture">            
                                                            <?php $image = (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='') ?  image_url. $value['image'] : default_icon_img; 
                                                            $rating_txt = ($value['restaurant_reviews_count'] > 1)?$this->lang->line('ratings'):$this->lang->line('rating'); ?>

                                                            <img src="<?php echo $image;?>">

                                                            <div class="icon-left d-flex text-capitalize">
                                                                <?php if ($show_restaurant_reviews) { ?> 
                                                                <?php echo ($value['ratings'] > 0)?'<div class="small text-white bg-success  d-flex align-items-center"><i class="icon"><img src="'. base_url() .'/assets/front/images/icon-star.svg" alt="clock"></i>'.$value['ratings'].' ('.$value['restaurant_reviews_count'].' '.strtolower($rating_txt).')'.'</div>':'<div class="small text-white bg-success">'. $this->lang->line("new") .'</div>'; ?>
                                                                <?php } ?>
                                                            </div>
                                                        </figure>
                                                        <div class="p-4">
                                                            <a class="h6" href="<?php echo ($value['restaurant_status']=='1')?base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug']:'#';?>"><?php echo $value['name'];?></a>
                                                            <small class="mb-auto"><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-pin.svg" alt=""></i><?php echo $value['address'];?></small>
                                                           
                                                            <ul class="d-flex flex-wrap small mt-2 mb-4">
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-calendar.svg" alt=""></i><?php echo $this->common_model->dateFormat($value['booking_date']);?></li>
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-clock.svg" alt=""></i><?php echo $this->common_model->timeFormat($value['booking_date']);?></li>
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-user.svg" alt=""></i><?php echo $value['no_of_people'];?> <?php echo $this->lang->line('people') ?></li>
                                                            </ul>

                                                            <?php if(!empty($value['package_name'])) { ?>
                                                                <div class="small d-flex justify-content-between align-items-center border-top pt-2 pb-2">
                                                                    <span class="fw-medium"><?php echo $this->lang->line('pkg') ?> :</span>
                                                                    <span><?php echo $value['package_name'];?></span>
                                                                </div>
                                                            <?php } ?>

                                                            <div class="small d-flex justify-content-between align-items-center pt-2 border-top">
                                                                <span class="fw-medium"><?php echo $this->lang->line('booking_status') ?> : </span>
                                                                <span><?php echo $this->lang->line($value['event_status']); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex mt-auto border-top text-center bg-light">
                                                            <a href="javascript:void(0)" class="py-2 small fw-medium text-secondary w-100 mw-100"  data-toggle="modal" onclick="booking_details(<?php echo $value['entity_id']; ?>)">
                                                                <?php echo $this->lang->line('view_details') ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php } 
                                        }
                                    } ?>
                                </div>
                                <?php if (count($upcoming_events) > 6) { ?>
                                    <div class=" display-no" id="all_upcoming_events">
                                        <div class="row row-grid row-grid-md row-cols-1 row-cols-md-2 row-cols-xxl-3 horizontal-image mt-0">
                                        <?php foreach ($upcoming_events as $key => $value) {
                                            if ($key > 7) { ?>
                                                <div class="col">
                                                    <div class="box-restaurant d-inline-block w-100 bg-white h-100 d-flex flex-column">
                                                        <figure class="picture">            
                                                            <?php $image = (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='') ?  image_url. $value['image'] : default_icon_img; 
                                                            $rating_txt = ($value['restaurant_reviews_count'] > 1)?$this->lang->line('ratings'):$this->lang->line('rating'); ?>

                                                            <img src="<?php echo $image;?>">

                                                            <div class="icon-left d-flex text-capitalize">
                                                                <?php if ($show_restaurant_reviews) { ?> 
                                                                <?php echo ($value['ratings'] > 0)?'<div class="small text-white bg-success  d-flex align-items-center"><i class="icon"><img src="'. base_url() .'/assets/front/images/icon-star.svg" alt="clock"></i>'.$value['ratings'].' ('.$value['restaurant_reviews_count'].' '.strtolower($rating_txt).')'.'</div>':'<div class="small text-white bg-success">'. $this->lang->line("new") .'</div>'; ?>
                                                                <?php } ?>
                                                            </div>
                                                        </figure>
                                                        <div class="p-4">
                                                            <h6><?php echo $value['name'];?></h6>
                                                            <small class="mb-auto"><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-pin.svg" alt=""></i><?php echo $value['address'];?></small>

                                                            <ul class="d-flex flex-wrap small mt-2 mb-4">
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-calendar.svg" alt=""></i><?php echo $this->common_model->dateFormat($value['booking_date']);?></li>
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-clock.svg" alt=""></i><?php echo $this->common_model->timeFormat($value['booking_date']);?></li>
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-user.svg" alt=""></i><?php echo $value['no_of_people'];?> <?php echo $this->lang->line('people') ?></li>
                                                            </ul>

                                                            <?php if(!empty($value['package_name'])) { ?>
                                                                <div class="small d-flex justify-content-between align-items-center border-top pt-2 pb-2">
                                                                    <span class="fw-medium"><?php echo $this->lang->line('pkg') ?> :</span>
                                                                    <span><?php echo $value['package_name'];?></span>
                                                                </div>
                                                            <?php } ?>

                                                            <div class="small d-flex justify-content-between align-items-center pt-2 border-top">
                                                                <span class="fw-medium"><?php echo $this->lang->line('booking_status') ?> : </span>
                                                                <span><?php echo $this->lang->line($value['event_status']); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex mt-auto border-top text-center bg-light">
                                                            <a href="javascript:void(0)" class="py-2 small fw-medium text-secondary w-100 mw-100"  data-toggle="modal" onclick="booking_details(<?php echo $value['entity_id']; ?>)">
                                                                <?php echo $this->lang->line('view_details') ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php }
                                        } ?>
                                        </div>
                                    </div>
                                    <div id="more_past_events" class="w-100 text-center pt-8 pb-8 pb-md-0">
                                        <button class="btn btn-primary" onclick="moreEvents('past')"><?php echo $this->lang->line('load_more') ?></button>
                                    </div>
                                <?php } ?>
                            <?php }  
                            else { ?>
                                    <div class="d-flex justify-content-center align-items-center w-100 h-100">
                                        <div class="screen-blank text-center my-auto margin-child px-4 py-8 py-md-0">
                                            <figure class="mb-4">
                                                <img src="<?php echo no_res_found; ?>">
                                            </figure>
                                            <h6><?php echo $this->lang->line('no_upcoming_bookings') ?></h6>
                                        </div>
                                    </div>
                            <?php }?>
                        </div>
                    </div>
                </div>

                <div class="tab-pane flex-fill d-flex flex-column fade <?php echo ($selected_tab == "table_bookings")?"in active show":"";?>" id="table_bookings">
                    <div class="title-dashboard bg-secondary text-center container-gutter-xl px-xl-8 py-4">
                        <h5 class="text-white fw-medium d-flex align-items-center justify-content-center">
                            <i class="icon icon-small d-xl-none"><img src="<?php echo base_url();?>assets/front/images/icon-accordion.svg" alt=""></i>
                            <?php echo $this->lang->line('table_bookings') ?>
                        </h5>
                    </div>

                    <ul class="nav nav-tabs border-bottom justify-content-center small d-flex" role="tablist">
                        <li class="nav-item">
                            <a href="#current-table-bookings" class="nav-link active" data-toggle="tab"><?php echo $this->lang->line('upcoming_table_bookings') ?></a>
                        </li>
                        <li class="nav-item">
                            <a href="#past-table-bookings" class="nav-link" data-toggle="tab"><?php echo $this->lang->line('past_table_bookings') ?></a>
                        </li>
                    </ul>
                    <div class="tab-content tab-content-md container-gutter-xl py-md-8 p-xl-8 d-flex flex-column flex-fill overflow-hidden">
                        <div id="past-table-bookings" class="tab-pane w-100 h-100">
                            <?php if (!empty($past_tables)) { ?>
                                <div class="row row-grid row-grid-md row-cols-1 row-cols-md-2 row-cols-xxl-3 horizontal-image">
                                    <?php if (!empty($past_tables)) {
                                        foreach ($past_tables as $key => $value) {
                                            if ($key <= 5) { ?>
                                                <div class="col">
                                                    <div class="box-restaurant d-inline-block w-100 bg-white h-100 d-flex flex-column">
                                                        <figure class="picture">
                                                            <?php $image = (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='') ?  image_url. $value['image'] : default_icon_img; 
                                                                    $rating_txt = ($value['restaurant_reviews_count'] > 1)?$this->lang->line('ratings'):$this->lang->line('rating'); ?>

                                                            <img src="<?php echo $image;?>">

                                                            <div class="icon-left d-flex text-capitalize">
                                                                <?php if ($show_restaurant_reviews) { ?> 
                                                                <?php echo ($value['ratings'] > 0)?'<div class="small text-white bg-success  d-flex align-items-center"><i class="icon"><img src="'. base_url() .'/assets/front/images/icon-star.svg" alt="clock"></i>'.$value['ratings'].' ('.$value['restaurant_reviews_count'].' '.strtolower($rating_txt).')'.'</div>':'<div class="small text-white bg-success">'. $this->lang->line("new") .'</div>'; ?>
                                                                <?php } ?>
                                                            </div>
                                                        </figure>
                                                        <div class="p-4 d-flex flex-column flex-fill">
                                                            <a class="h6" href="<?php echo ($value['restaurant_status']=='1')?base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug']:'#';?>"><?php echo $value['rname'];?></a>
                                                            <small class="mb-auto"><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-pin.svg" alt=""></i><?php echo $value['address'];?></small>
                                                            
                                                            <ul class="d-flex flex-wrap small mt-2 mb-4">
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-calendar.svg" alt=""></i><?php echo $this->common_model->dateFormat($value['booking_date']);?></li>
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-clock.svg" alt=""></i><?php echo $this->common_model->timeFormat($value['start_time'])." - ".$this->common_model->timeFormat($value['end_time']);?></li>
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-user.svg" alt=""></i><?php echo $value['no_of_people'];?> <?php echo $this->lang->line('people') ?></li>
                                                            </ul>


                                                            <div class="small d-flex justify-content-between align-items-center pt-2 border-top">
                                                                <span class="fw-medium"><?php echo $this->lang->line('booking_status') ?> :</span>
                                                                <span><?php echo $this->lang->line($value['booking_status']); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex mt-auto border-top text-center bg-light">
                                                            <a href="javascript:void(0)" class="py-2 small fw-medium text-secondary w-100 mw-100" data-toggle="modal" onclick="table_booking_details(<?php echo $value['entity_id']; ?>)">
                                                                <?php echo $this->lang->line('view_details') ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } 
                                        }
                                    } ?>
                                </div>
                                <?php if (count($past_tables) > 6) { ?>
                                    <div id="all_past_tables" style="display: none;">
                                        <div class="row row-grid row-grid-md row-cols-1 row-cols-md-2 row-cols-xxl-3 horizontal-image mt-0">
                                        <?php foreach ($past_tables as $key => $value) {
                                            if ($key > 7) { ?>
                                                <div class="col">
                                                    <div class="box-restaurant d-inline-block w-100 bg-white h-100 d-flex flex-column">
                                                        <figure class="picture">
                                                            <?php $image = (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='') ?  image_url. $value['image'] : default_icon_img; 
                                                                     $rating_txt = ($value['restaurant_reviews_count'] > 1)?$this->lang->line('ratings'):$this->lang->line('rating'); ?>

                                                            <img src="<?php echo $image;?>">

                                                            <div class="icon-left d-flex text-capitalize">
                                                                <?php if ($show_restaurant_reviews) { ?> 
                                                                <?php echo ($value['ratings'] > 0)?'<div class="small text-white bg-success  d-flex align-items-center"><i class="icon"><img src="'. base_url() .'/assets/front/images/icon-star.svg" alt="clock"></i>'.$value['ratings'].' ('.$value['restaurant_reviews_count'].' '.strtolower($rating_txt).')'.'</div>':'<div class="small text-white bg-success">'. $this->lang->line("new") .'</div>'; ?>
                                                                <?php } ?>
                                                            </div>
                                                        </figure>
                                                        <div class="p-4 d-flex flex-column flex-fill">
                                                            <a class="h6" href="<?php echo ($value['restaurant_status']=='1')?base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug']:'#';?>"><?php echo $value['rname'];?></a>
                                                            <small class="mb-auto"><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-pin.svg" alt=""></i><?php echo $value['address'];?></small>
                                                            
                                                            <ul class="d-flex flex-wrap small mt-2 mb-4">
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-calendar.svg" alt=""></i><?php echo $this->common_model->dateFormat($value['booking_date']);?></li>
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-clock.svg" alt=""></i><?php echo $this->common_model->timeFormat($value['start_time'])." - ".$this->common_model->timeFormat($value['end_time']);?></li>
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-user.svg" alt=""></i><?php echo $value['no_of_people'];?> <?php echo $this->lang->line('people') ?></li>
                                                            </ul>


                                                            <div class="small d-flex justify-content-between align-items-center pt-2 border-top">
                                                                <span class="fw-medium"><?php echo $this->lang->line('booking_status') ?> :</span>
                                                                <span><?php echo $this->lang->line($value['booking_status']); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex mt-auto border-top text-center bg-light">
                                                            <a href="javascript:void(0)" class="py-2 small fw-medium text-secondary w-100 mw-100" data-toggle="modal" onclick="table_booking_details(<?php echo $value['entity_id']; ?>)">
                                                                <?php echo $this->lang->line('view_details') ?>
                                                            </a>
                                                        </div>            
                                                    </div>
                                                </div>
                                            <?php }
                                        } ?>
                                        </div>
                                    </div>
                                    <div id="more_past_events" class="w-100 text-center pt-8 pb-8 pb-md-0">
                                        <button class="btn btn-primary" onclick="moreTables('past')"><?php echo $this->lang->line('load_more') ?></button>
                                    </div>
                                <?php } ?>
                            <?php } 
                            else { ?>
                                <div class="d-flex justify-content-center align-items-center w-100 h-100">
                                    <div class="screen-blank text-center my-auto margin-child px-4 py-8 py-md-0">
                                        <figure class="mb-4">
                                            <img src="<?php echo no_res_found; ?>">
                                        </figure>
                                        <h6><?php echo $this->lang->line("no_past_table_booking_found"); ?></h6>
                                    </div>
                                </div>
                            <?php }?>
                        </div>
                        <div id="current-table-bookings" class="tab-pane w-100 h-100 show active">
                            <?php if (!empty($upcoming_tables)) { ?>
                                <div class="row row-grid row-grid-md row-cols-1 row-cols-md-2 row-cols-xxl-3 horizontal-image">
                                    <?php if (!empty($upcoming_tables)) {
                                        foreach ($upcoming_tables as $key => $value) {
                                            if ($key <= 5) { ?>
                                                <div class="col">
                                                    <div class="box-restaurant d-inline-block w-100 bg-white h-100 d-flex flex-column">
                                                        <figure class="picture">
                                                            <?php $image = (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='') ?  image_url. $value['image'] : default_icon_img; 
                                                                     $rating_txt = ($value['restaurant_reviews_count'] > 1)?$this->lang->line('ratings'):$this->lang->line('rating'); ?>

                                                            <img src="<?php echo $image;?>">

                                                            <div class="icon-left d-flex text-capitalize">
                                                                <?php if ($show_restaurant_reviews) { ?> 
                                                                <?php echo ($value['ratings'] > 0)?'<div class="small text-white bg-success  d-flex align-items-center"><i class="icon"><img src="'. base_url() .'/assets/front/images/icon-star.svg" alt="clock"></i>'.$value['ratings'].' ('.$value['restaurant_reviews_count'].' '.strtolower($rating_txt).')'.'</div>':'<div class="small text-white bg-success">'. $this->lang->line("new") .'</div>'; ?>
                                                                <?php } ?>
                                                            </div>
                                                        </figure>
                                                        <div class="p-4 d-flex flex-column flex-fill">
                                                            <a class="h6" href="<?php echo ($value['restaurant_status']=='1')?base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug']:'#';?>"><?php echo $value['rname'];?></a>
                                                            <small class="mb-auto"><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-pin.svg" alt=""></i><?php echo $value['address'];?></small>
                                                            
                                                            <ul class="d-flex flex-wrap small mt-2 mb-4">
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-calendar.svg" alt=""></i><?php echo $this->common_model->dateFormat($value['booking_date']);?></li>
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-clock.svg" alt=""></i><?php echo $this->common_model->timeFormat($value['start_time'])." - ".$this->common_model->timeFormat($value['end_time']);?></li>
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-user.svg" alt=""></i><?php echo $value['no_of_people'];?> <?php echo $this->lang->line('people') ?></li>
                                                            </ul>


                                                            <div class="small d-flex justify-content-between align-items-center pt-2 border-top">
                                                                <span class="fw-medium"><?php echo $this->lang->line('booking_status') ?> :</span>
                                                                <span><?php echo $this->lang->line($value['booking_status']); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex mt-auto border-top text-center bg-light">
                                                            <a href="javascript:void(0)" class="py-2 small fw-medium text-secondary w-100 mw-100" data-toggle="modal" onclick="table_booking_details(<?php echo $value['entity_id']; ?>)">
                                                                <?php echo $this->lang->line('view_details') ?>
                                                            </a>
                                                        </div>  
                                                    </div>
                                                </div>
                                            <?php } 
                                        }
                                    } ?>
                                </div>
                                <?php if (count($upcoming_tables) > 6) { ?>
                                    <div class=" display-no" id="all_upcoming_tables" style="display: none;">
                                        <div class="row row-grid row-grid-md row-cols-1 row-cols-md-2 row-cols-xxl-3 horizontal-image">
                                        <?php foreach ($upcoming_tables as $key => $value) {
                                            if ($key > 7) { ?>
                                                <div class="col">
                                                    <div class="box-restaurant d-inline-block w-100 bg-white h-100 d-flex flex-column">
                                                        <figure class="picture">
                                                            <?php $image = (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='') ?  image_url. $value['image'] : default_icon_img; 
                                                                     $rating_txt = ($value['restaurant_reviews_count'] > 1)?$this->lang->line('ratings'):$this->lang->line('rating'); ?>

                                                            <img src="<?php echo $image;?>">

                                                            <div class="icon-left d-flex text-capitalize">
                                                                <?php if ($show_restaurant_reviews) { ?> 
                                                                <?php echo ($value['ratings'] > 0)?'<div class="small text-white bg-success  d-flex align-items-center"><i class="icon"><img src="'. base_url() .'/assets/front/images/icon-star.svg" alt="clock"></i>'.$value['ratings'].' ('.$value['restaurant_reviews_count'].' '.strtolower($rating_txt).')'.'</div>':'<div class="small text-white bg-success">'. $this->lang->line("new") .'</div>'; ?>
                                                                <?php } ?>
                                                            </div>
                                                        </figure>
                                                        <div class="p-4 d-flex flex-column flex-fill">
                                                            <a class="h6" href="<?php echo ($value['restaurant_status']=='1')?base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug']:'#';?>"><?php echo $value['rname'];?></a>
                                                            <small class="mb-auto"><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-pin.svg" alt=""></i><?php echo $value['address'];?></small>
                                                            
                                                            <ul class="d-flex flex-wrap small mt-2 mb-4">
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-calendar.svg" alt=""></i><?php echo $this->common_model->dateFormat($value['booking_date']);?></li>
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-clock.svg" alt=""></i><?php echo $this->common_model->timeFormat($value['start_time'])." - ".$this->common_model->timeFormat($value['end_time']);?></li>
                                                                <li><i class="icon icon-small"><img src="<?php echo base_url();?>assets/front/images/icon-user.svg" alt=""></i><?php echo $value['no_of_people'];?> <?php echo $this->lang->line('people') ?></li>
                                                            </ul>

                                                            <div class="small d-flex justify-content-between align-items-center pt-2 border-top">
                                                                <span class="fw-medium"><?php echo $this->lang->line('booking_status') ?> :</span>
                                                                <span><?php echo $this->lang->line($value['booking_status']); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex mt-auto border-top text-center bg-light">
                                                            <a href="javascript:void(0)" class="py-2 small fw-medium text-secondary w-100 mw-100" data-toggle="modal" onclick="table_booking_details(<?php echo $value['entity_id']; ?>)">
                                                                <?php echo $this->lang->line('view_details') ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php }
                                        } ?>
                                        </div>
                                    </div>
                                    <div id="more_past_events" class="w-100 text-center pt-8 pb-8 pb-md-0">
                                        <button class="btn btn-primary" onclick="moreTables('upcoming')"><?php echo $this->lang->line('load_more') ?></button>
                                    </div>
                                <?php } ?>
                            <?php }  
                            else { ?>
                                    <div class="d-flex justify-content-center align-items-center w-100 h-100">
                                        <div class="screen-blank text-center my-auto margin-child px-4 py-8 py-md-0">
                                            <figure class="mb-4">
                                                <img src="<?php echo no_res_found; ?>">
                                            </figure>
                                            <h6><?php echo $this->lang->line('no_upcoming_table_bookings') ?></h6>
                                        </div>
                                    </div>
                            <?php }?>
                        </div>
                    </div>
                </div>

                <div class="tab-pane tab-wallet flex-fill d-flex flex-column fade <?php echo ($selected_tab == "wallet_history")?"in active show":"";?>" id="wallet_history">
                    <div class="title-dashboard bg-secondary text-center container-gutter-xl px-xl-8 py-4">
                        <h5 class="text-white fw-medium d-flex align-items-center justify-content-center">
                            <i class="icon icon-small d-xl-none"><img src="<?php echo base_url();?>assets/front/images/icon-accordion.svg" alt=""></i>
                            <?php echo $this->lang->line('wallet_history') ?>
                        </h5>
                    </div>
                    <div class="tab-content tab-content-sm container-gutter-xl py-sm-8 p-xl-8 d-flex flex-column flex-fill overflow-hidden">
                        <div class="row row-cols-1 row-grid  row-cols-sm-3 row-cols-xl-4 justify-content-center text-center pt-8 pt-sm-0">
                            <div class="col">
                                <i class="icon icon-large">
                                    <img src="<?php base_url() ?>/assets/front/images/icon-share-color.svg" alt="">
                                </i>
                                <h6><?php echo $this->lang->line('share_code') ?></h6>
                            </div>
                            <div class="col">
                                <i class="icon icon-large">
                                    <img src="<?php base_url() ?>/assets/front/images/icon-resume-color.svg" alt="">
                                </i>
                                <h6><?php echo $this->lang->line('once_reg_with_ref') ?></h6>
                            </div>
                            <div class="col">
                                <i class="icon icon-large">
                                    <img src="<?php base_url() ?>/assets/front/images/icon-reward-color.svg" alt="">
                                </i>
                                <h6><?php echo $this->lang->line('get_reward') ?></h6>
                            </div>
                        </div>

                        <div class="text-center d-flex flex-column py-8 py-xl-12">
                            <h2><?php echo $this->lang->line('your_referral_code') ?></h2>

                            <button class="btn btn-copy btn-sm btn-secondary mx-auto mt-2 mb-1" onclick="copyToClipboard()"><?php echo ($profile->referral_code)?$profile->referral_code:$this->lang->line('refcode') ?></button>
                            
                            <input type="hidden" name="ref_code" id="ref_code" value="<?php echo $profile->referral_code; ?>">
                            <a class="small d-inline-block mx-auto text-body" href="javascript:void(0)" onclick="copyToClipboard()"><?php echo $this->lang->line('tap_to_copy') ?></a>
                            <div id="copied-success" class="copied text-success" style="display: none;"><?php echo $this->lang->line('copied') ?></div>
                            <?php /* ?><button class="btn Refer-now-btn"><?php echo $this->lang->line('refer_now') ?></button><?php */ ?>
                        </div>

                        <?php /* if (!empty($this->session->userdata('UserID')) && $this->session->userdata('UserType') == 'User') { ?>
                            <h5><?php echo $this->lang->line('wallet_history') ?></h5>
                            <div class="add-wallet-money-btn">
                                <button class="btn add-wallet-topup-btn" data-toggle="modal" onclick="add_wallet_money()"><?php echo $this->lang->line('add_money') ?></button>
                            </div>
                        <?php } */ ?>

                        <?php if (!empty($wallet_history)) { ?>
                            <?php if (!empty($wallet_history)) { ?>
                                <div class="bg-white p-sm-4 px-4 pt-4 pb-8 border border-item w-100">
                                    <div class="d-flex justify-content-between text-secondary border-bottom pb-4 mb-4">
                                        <label class="fw-medium"><?php echo $this->lang->line('transactions'); ?></label>
                                        <small class="fw-medium"><?php echo $this->lang->line('total_earned'). ': ';?><?php echo currency_symboldisplay($wallet_history['total_money_credited'],$currency_symbol); ?></small>
                                    </div>
                                
                                    <?php foreach ($wallet_history['result'] as $key => $value) { ?>
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <label class="fw-normal small">
                                                <i class="icon icon-small <?php echo ($value->credit=='1')?'text-success':'text-danger'; ?>"><img src="<?php base_url() ?>/assets/front/images/<?php echo ($value->credit=='1')?'icon-credit.svg':'icon-debit.svg'?>" alt=""></i>
                                                <?php echo $this->lang->line($value->reason); ?> <?php echo $value->order_id; ?>
                                            </label>
                                            <small class="<?php echo ($value->credit=='1')?'text-success':'text-danger'; ?>"><?php echo ($value->credit=='1')?'+':'-'; ?><?php echo currency_symboldisplay($value->amount,$currency_symbol); ?></small>
                                        </div>
                                    <?php } ?>

                                </div>
                            <?php } ?>
                        <?php }
                        else { ?>
                            <div class="screen-blank text-center my-auto margin-child px-4 py-8 py-sm-0">
                                <figure class="mb-4">
                                    <img src="<?php echo no_res_found; ?>">
                                </figure>
                                <h6><?php echo $this->lang->line('no_wallet_history_found') ?></h6>
                            </div>
                        <?php }?>
                    </div>
                </div>
                <div class="tab-pane tab-address flex-fill d-flex flex-column fade <?php echo ($selected_tab == "addresses")?"in active show":"";?>" id="addresses">
                    <div class="title-dashboard bg-secondary d-flex justify-align-center container-gutter-xl px-xl-8 py-4">
                        <h5 class="text-white fw-medium d-flex align-items-center justify-content-start">
                            <i class="icon icon-small d-xl-none"><img src="<?php echo base_url();?>assets/front/images/icon-accordion.svg" alt=""></i>
                            <?php echo $this->lang->line('my_addresses') ?>
                        </h5>
                        <a href="javascript:void(0)" class="newcard_add text-nowrap text-white fw-medium text-uppercase small" data-toggle="modal" data-target="#add-address">+ <?php echo $this->lang->line('add_address') ?></a>
                    </div>

                   <div class="tab-content tab-content-sm container-gutter-xl py-md-8 p-xl-8 d-flex flex-column flex-fill overflow-hidden">
                        <?php if (!empty($users_address)) { ?>
                            <div class="row row-grid row-grid-md row-cols-1 row-cols-md-2 row-cols-xxl-3">
                                <?php if (!empty($users_address)) {
                                    foreach ($users_address as $key => $value) { 
                                        $class = ($value->is_main == 1)?"item-active":""; ?>
                                        <div class="col">
                                            <div class="border border-item px-4 pt-4 bg-white w-100 h-100 d-flex flex-column <?php echo $class; ?>">
                                                <div class="d-flex mb-auto">
                                                    <i class="icon pt-1"><img src="<?php echo base_url();?>/assets/front/images/icon-pin.svg" alt="delete"></i>
                                                    <div class="flex-fill px-4">
                                                        <h6 class="mb-1"><?php echo ($value->address_label)?$value->address_label:$this->lang->line('no_additional_info'); ?></h6> 

                                                        <!-- <?php echo ($value->is_main == 1)?"<span class='default-address'>". $this->lang->line('default') ."</span>":""; ?> -->
                                                        <?php //echo $value->address.','.$value->landmark.','.$value->city.','.$value->zipcode.','.$value->search_area; ?>
                                                        <small><?php echo $value->address; ?></small>
                                                    </div>
                                                </div>
                                                
                                                <div class="d-flex align-items-center justify-content-center mt-4 py-2 border-top small">
                                                    <a href="javascript:void(0)" class="text-uppercase fw-medium text-success" onclick="editAddress(<?php echo $value->address_id; ?>);"><?php echo $this->lang->line('edit') ?></a><div class="px-2 py-1"></div>
                                                    <a href="javascript:void(0)" class="text-uppercase fw-medium text-danger" data-toggle="modal" onclick="showDeleteAddress(<?php echo $value->address_id; ?>);"><?php echo $this->lang->line('delete') ?></a><div class="px-2 py-1"></div>
                                                    <?php if($value->is_main!='1'){ ?>
                                                        <a href="javascript:void(0)" class="text-uppercase fw-medium text-secondary" data-toggle="modal" onclick="showMainAddress(<?php echo $value->address_id; ?>);"><?php echo $this->lang->line('set_as_primary') ?></a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>  
                                    <?php }
                                } ?>                    
                            </div>
                        <?php } 
                        else { ?>
                            <div class="screen-blank text-center my-auto margin-child px-4 py-8 py-sm-0">
                                <figure>
                                    <img src="<?php echo no_res_found; ?>">
                                </figure>
                                <h6><?php echo $this->lang->line('no_address_found') ?></h6>
                            </div>
                        <?php }?> 
                    </div>
                </div>
                <div class="tab-pane tab-notification flex-fill d-flex flex-column fade <?php echo ($selected_tab == "notifications")?"in active show":"";?>" id="notifications">
                    <div class="title-dashboard bg-secondary text-center container-gutter-xl px-xl-8 py-4">
                        <h5 class="text-white fw-medium d-flex align-items-center justify-content-center">
                            <i class="icon icon-small d-xl-none"><img src="<?php echo base_url();?>assets/front/images/icon-accordion.svg" alt=""></i>
                            <?php echo $this->lang->line('my_notifications') ?>
                        </h5>
                    </div>

                    <div class="tab-content tab-content-sm container-gutter-xl py-md-8 p-xl-8 d-flex flex-column flex-fill overflow-hidden">
                        <?php if (!empty($users_notifications)) { ?>
                            <?php if (!empty($users_notifications)) {
                                foreach ($users_notifications as $key => $value) {  
                                    if($key<=7) { ?>
                                        <div class="item-notification bg-white box-shadow border p-4 d-flex flex-column flex-sm-row align-items-center mb-2 <?php echo $class; ?>">
                                            <i class="icon"><img src="<?php base_url() ?>/assets/front/images/icon-notification.svg" alt=""></i>
                                            <div class="flex-fill d-flex flex-column w-100">
                                                <h6><?php echo utf8_decode($value->notification_title); ?></h6>
                                                <?php 
                                                $view_more_btn = (strlen($value->notification_description)>250)?'<a class="text-primary fw-medium text-uppercase small text-decoration-underline" href="javascript:void(0)" onclick="showNotification('.$value->entity_id.')">'.$this->lang->line('view_more').'</a>':'';
                                                $notification_description= mb_strimwidth($value->notification_description,0,250,'').' '.$view_more_btn;
                                                 ?>
                                                 <small><?php echo utf8_decode($notification_description); ?></small>
                                            </div>
                                        </div>
                                    <?php } 
                                }
                            } ?>
                        <?php } 
                        else { ?>
                            <div class="screen-blank text-center my-auto margin-child px-4 py-8 py-sm-0">
                                <figure class="mb-4">
                                    <img src="<?php echo no_res_found; ?>">
                                </figure>
                                <h6><?php echo $this->lang->line('no_notifications_found') ?></h6>
                            </div>
                        <?php }?>
                        <?php if (count($users_notifications) > 8) { ?>
                            <div id="all_notifications" style="display: none;">
                                <div class="row orders-box-row">
                                    <?php if (!empty($users_notifications)) {
                                        foreach ($users_notifications as $key => $value) {  
                                            if($key>7) { ?>
                                                <div class="col-xl-6 col-lg-12">
                                                    <div class="my-address-main <?php echo $class; ?>">
                                                        <div class="noti">
                                                            <div class="my-address-list">
                                                                <h6><?php echo $value->notification_title; ?></h6>
                                                                <?php $view_more_btn = (strlen($value->notification_description)>250)?'<div class="ordering-btn"><a class="btn" href="javascript:void(0)" onclick="showNotification('.$value->entity_id.')">'.$this->lang->line('view_more').'</a></div>':'';
                                                                $notification_description= mb_strimwidth($value->notification_description,0,250,'').' '.$view_more_btn;
                                                                 ?>
                                                                <p><?php echo utf8_decode($notification_description); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } 
                                        }
                                    } ?>                    
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div id="load_more_notifications" class="load-more-btn">
                                    <button class="btn" onclick="moreNotifications()"><?php echo $this->lang->line('load_more') ?></button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="tab-pane tab-card flex-fill d-flex flex-column fade <?php echo ($selected_tab == "payment_card")?"in active show":"";?>" id="payment_card">
                    <div class="title-dashboard bg-secondary d-flex justify-align-center container-gutter-xl px-xl-8 py-4">
                        <h5 class="text-white fw-medium d-flex align-items-center justify-content-start">
                            <i class="icon icon-small d-xl-none"><img src="<?php echo base_url();?>assets/front/images/icon-accordion.svg" alt=""></i>
                            <?php echo $this->lang->line('card_detail') ?>
                        </h5>
                        <a href="javascript:void(0)" class="newcard_add text-nowrap text-white fw-medium text-uppercase small" data-toggle="modal" id="add-stripecardid" data-target="#add-stripecard">+ <?php echo $this->lang->line('add_card') ?></a>
                    </div>
                    <div class="tab-content tab-content-sm container-gutter-xl py-md-8 p-xl-8 d-flex flex-column flex-fill overflow-hidden">
                        
                        <?php if (!empty($savecard_detail) && !isset($savecard_detail['error'])) { ?>
                            <?php if($_SESSION['delete_cardmessage']){ ?>
                            <div class="alert alert-danger m-4 mx-sm-0 mb-sm-4 mt-sm-0">
                                <?php echo $_SESSION['delete_cardmessage'];
                                    unset($_SESSION['delete_cardmessage']);
                                 ?>
                            </div>
                            <?php } ?>
                            <?php if (!empty($savecard_detail)) { ?>
                                <?php foreach ($savecard_detail as $key => $value) { 
                                    $default_card_class = ($value['is_default_card'] == '1') ? 'item-active' : '' ; ?>
                                
                                <div class="border border-item mt-0 px-4 py-4 mb-2 d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between bg-white w-100 <?php echo $default_card_class; ?> ">
                                    <input class="form-check-input bg-white opacity-0 absolute-div invisible m-0" type="radio" name="payment-source" card-key="<?php echo $key ?>" value="saved_card_<?php echo $key; ?>" card_fingerprint="<?php echo $value['card_fingerprint'];?>" PaymentMethodid="<?php echo $value['PaymentMethodid'];?>" exp_month="<?php echo $value['exp_month'];?>" exp_year="<?php echo $value['exp_year'];?>" postal_code="<?php echo $value['postal_code'];?>" card_last4="<?php echo $value['card_last4'];?>" <?php if($value['is_default_card'] == '1') { ?> checked="checked" <?php } ?>>
                                    

                                    <span><img src="<?php echo base_url().$value['card_image'];?>"></span>
                                    <div class="flex-fill d-flex flex-column my-2 my-sm-0 mx-sm-2">
                                        <h6><?php echo $value['card_brand_name']; ?></h6>
                                        <small class="fw-normal"><?php echo $this->lang->line('ending_in').' '.$value['card_last4'].', '.$this->lang->line('expires').' '.$value['exp_month'].'/'.$value['exp_year'];?></small>
                                    </div>
                                    <ul class="d-flex btn-action"> 
                                        <li>
                                            <a href="javascript:void(0)" data-tooltip="tooltip" data-placement="bottom" tooltip-title="<?php echo $this->lang->line('edit_card') ?>" class="icon text-success" alt="remove-card" data-toggle="modal" onclick="showeditcard(<?php echo $key ?>);">
                                                <img src="<?php echo base_url();?>/assets/front/images/icon-edit.svg" alt="edit">
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" data-tooltip="tooltip" data-placement="bottom" tooltip-title="<?php echo $this->lang->line('remove_card'); ?>" class="icon text-danger" alt="remove-card" onclick="showDeleteStipe('<?php echo $value['PaymentMethodid'];?>', '<?php echo $value['stripecus_id'];?>');">
                                                <img src="<?php echo base_url();?>/assets/front/images/icon-delete.svg" alt="delete">
                                            </a>
                                        </li>
                                        <?php if($value['is_default_card'] != '1') { ?>
                                            <li>
                                                <a href="javascript:void(0)" data-tooltip="tooltip" data-placement="bottom" tooltip-title="<?php echo $this->lang->line('set_as_default'); ?>" class="icon text-secondary" alt="set-as-default" onclick="set_as_default_stripecard('<?php echo $value['PaymentMethodid'];?>', '<?php echo $value['stripecus_id'];?>');">
                                                    <img src="<?php echo base_url();?>/assets/front/images/icon-card.svg" alt="Border">
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <?php }
                            } ?>
                        <?php }
                        else { ?>
                            <div class="screen-blank text-center my-auto margin-child px-4 py-8 py-sm-0">
                                <figure class="mb-4">
                                    <img src="<?php echo no_res_found; ?>">
                                </figure>
                                <?php if (!empty($savecard_detail) && isset($savecard_detail['error'])) { ?>
                                    <h6><?php echo $savecard_detail['message']; ?></h6>
                                <?php } else{ ?>
                                    <h6><?php echo $this->lang->line('no_card_detail_found') ?></h6>
                                <?php } ?>
                            </div>
                        <?php }?>
                    </div>
                </div>
                <div class="tab-pane flex-fill d-flex flex-column fade <?php echo ($selected_tab == "bookmarks")?"in active show":"";?>" id="bookmarks">
                    <div class="title-dashboard bg-secondary text-center container-gutter-xl px-xl-8 py-4">
                        <h5 class="text-white fw-medium d-flex align-items-center justify-content-center">
                            <i class="icon icon-small d-xl-none"><img src="<?php echo base_url();?>assets/front/images/icon-accordion.svg" alt=""></i>
                            <?php echo $this->lang->line('my_bookmarks') ?>
                        </h5>
                    </div>

                   <div class="tab-content tab-content-md container-gutter-xl py-md-8 p-xl-8 d-flex flex-column flex-fill overflow-hidden">
                        <?php if (!empty($users_bookmarks)) { ?>
                            <div class="row row-grid row-grid-md row-cols-1 row-cols-md-2 row-cols-lg-3 horizontal-image">
                                <?php if (!empty($users_bookmarks)) {
                                    foreach ($users_bookmarks as $key => $value) {  
                                        if($key<=5) { ?>
                                            <div class="col">
                                                <div class="box-restaurant d-inline-block w-100 bg-white h-100 d-flex flex-column">
                                                    <a class="figure picture" href="<?php echo base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug'];?>">
                                                        <?php $rest_image = (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='') ? image_url.$value['image']: default_img;  ?>
                                                        <img src="<?php echo $rest_image ;?>" alt="<?php echo $value['name']; ?>">

                                                        <div class="icon-time small text-white d-inline-block <?php echo ($value['timings']['closing'] == "Closed")?"bg-danger":"bg-success"; ?>"> <?php echo ($value['timings']['closing'] == "Closed")?$this->lang->line('closed'):$this->lang->line('open'); ?> </div>

                                                        <div class="icon-left d-flex text-capitalize">
                                                            <?php if ($show_restaurant_reviews) { ?>
                                                                <?php echo ($value['ratings'] > 0)?'<div class="small text-white bg-success  d-flex align-items-center"><i class="icon icon-small"><img src="'. base_url() .'/assets/front/images/icon-star.svg" alt="clock"></i>'.$value['ratings'].'</div>':'<div class="small text-white bg-success">'. $this->lang->line("new") .'</div>'; ?> 
                                                            <?php } ?>
                                                        </div>
                                                    </a>
                                                    <div class="p-4">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                            <a class="h6 w-auto" href="<?php echo base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug'];?>"><?php echo $value['name']; ?></a>

                                                            <div class="ms-auto mt-1 me-1"></div>
                                                            <a class="icon text-danger m-0" onclick="removeBookmark('<?php echo $value['entity_id'] ?>')" href="javascript:void(0)" data-tooltip="tooltip" data-placement="bottom" tooltip-title="<?php echo $this->lang->line('remove'); ?>"
                                                                >
                                                                
                                                                <img src="<?php base_url() ?>/assets/front/images/icon-delete.svg" alt="clock">
                                                            </a>
                                                        </div>

                                                        <small class="text-body"><i class="icon icon-small"><img src="<?php base_url() ?>/assets/front/images/icon-pin.svg" alt="clock"></i><?php echo $value['address']; ?></small>

                                                        <?php  /*if($value['timings']['closing'] != "Closed") {
                                                            ?>
                                                            <a href="<?php echo base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug'];?>" class="btn"><?php echo $this->lang->line('order') ?></a>
                                                        <?php //}
                                                        */ ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } 
                                    }
                                } ?>                    
                            </div>
                        <?php } 
                        else { ?>
                            <div class="screen-blank text-center my-auto margin-child px-4 py-8 py-md-0">
                                <figure class="mb-4">
                                    <img src="<?php echo no_res_found; ?>">
                                </figure>
                                <h6><?php echo $this->lang->line('no_bookmarks_found') ?></h6>
                            </div>
                        <?php }?>
                        <?php if (!empty($users_bookmarks) && isset($users_bookmarks)) { ?>
                            <?php if (count($users_bookmarks) > 6 ) { ?>
                                <div id="all_bookmarks" style="display: none;">
                                    <div class="row mt-0 row-grid row-grid-md row-cols-1 row-cols-md-2 row-cols-xl-3 horizontal-image">
                                        <?php if (!empty($users_bookmarks)) {
                                            foreach ($users_bookmarks as $key => $value) {  
                                                if($key>5) { ?>
                                                    <div class="col">
                                                        <div class="box-restaurant d-inline-block w-100 bg-white h-100 d-flex flex-column">
                                                            <a class="figure picture" href="<?php echo base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug'];?>">
                                                                <?php $rest_image = (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='') ? image_url.$value['image']: default_img;  ?>
                                                                <img src="<?php echo $rest_image ;?>" alt="<?php echo $value->name; ?>">
                                                                <div class="icon-time small text-white d-inline-block <?php echo ($value['timings']['closing'] == "Closed")?"bg-danger":"bg-success"; ?>"> <?php echo ($value['timings']['closing'] == "Closed")?$this->lang->line('closed'):$this->lang->line('open'); ?> </div>
                                                                
                                                                <div class="icon-left d-flex text-capitalize">
                                                                    <?php if ($show_restaurant_reviews) { ?>
                                                                        <?php echo ($value['ratings'] > 0)?'<div class="small text-white bg-success  d-flex align-items-center"><i class="icon icon-small"><img src="'. base_url() .'/assets/front/images/icon-star.svg" alt="clock"></i>'.$value['ratings'].'</div>':'<div class="small text-white bg-success">'. $this->lang->line("new") .'</div>'; ?> 
                                                                    <?php } ?>
                                                                </div>
                                                                <?php if ($show_restaurant_reviews) { ?>
                                                                <?php echo ($value['ratings'] > 0)?'<strong>'.$value['ratings'].'</strong>':'<strong class="newres">'. $this->lang->line("new") .'</strong>'; ?> 
                                                                <?php } ?>
                                                            </a>
                                                            <div class="p-4">
                                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                                    <a class="h6 w-auto" href="<?php echo base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug'];?>"><?php echo $value['name']; ?></a>

                                                                    <div class="ms-auto mt-1 me-1"></div>
                                                                    <a class="icon text-danger m-0" onclick="removeBookmark('<?php echo $value['entity_id'] ?>')" href="javascript:void(0)" data-tooltip="tooltip" data-placement="bottom" tooltip-title="<?php echo $this->lang->line('remove'); ?>">
                                                                        <img src="<?php base_url() ?>/assets/front/images/icon-delete.svg" alt="clock">
                                                                    </a>
                                                                </div>
                                                                <small class="text-body"><i class="icon icon-small"><img src="<?php base_url() ?>/assets/front/images/icon-pin.svg" alt="clock"></i><?php echo $value['address']; ?></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } 
                                            }
                                        } ?>                    
                                    </div>
                                </div>
                                <div id="load_more_bookmarks" class="w-100 text-center pt-8 pb-8 pb-md-0">
                                    <button class="btn btn-primary" onclick="moreBookmarkedRes()"><?php echo $this->lang->line('load_more') ?></button>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="dialog-confirm" title="Delete this address?" style="display: none;">
      <p><span class="ui-icon ui-icon-alert"></span><?php echo $this->lang->line('delete_module'); ?></p>
    </div>
    <div id="dialog-confirm-setmain" title="Set Main Address?" style="display: none;">
      <p><span class="ui-icon ui-icon-alert"></span><?php echo $this->lang->line('set_main_address_confirm') ?></p>
    </div>

    <?php if(!empty($payment)) {
            echo '<script>$("#order-confirmation").modal(\'show\');</script>'; 
    } ?>
    <div class="modal order-detail-popup" id="reorder-details"></div>
    <div class="modal order-detail-popup" id="booking-details"></div>
    <div class="modal order-detail-popup" id="table-booking-details"></div>
    <div class="modal order-detail-popup" id="order-details"></div>
    <div class="modal cancel-order-popup" id="cancel-order"></div>

    <div class="modal" id="edit-profile" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4 p-xl-8">
                <a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close" onclick="document.location.href='<?php echo base_url();?>myprofile';">
                    <img src="<?php base_url() ?>/assets/front/images/icon-close.svg" alt="">
                </a>
                <h2 class="text-capitalize title pb-2 text-center mb-4 mb-xl-8"><?php echo $this->lang->line('edit_profile') ?></h2>
                <form id="form_my_profile" name="form_my_profile" method="post" class="form-horizontal float-form" enctype="multipart/form-data">
                    
                    <div class="edit-profile-img">
                        <?php /* ?><div class="edit-img">
                             <?php $image = (file_exists(FCPATH.'uploads/'.$profile->image) && $profile->image!='') ?  image_url. $profile->image : default_user_img;?>
                            <img id='old' src="<?php echo $image; ?>">
                            <img id="preview" class="display-no"/>
                            <label>
                                <input type="file" name="image" id="image" accept="image/*" data-msg-accept="<?php echo $this->lang->line('file_extenstion') ?>" onchange="readURL(this)"/>
                                <i class="iicon-icon-37"></i>
                            </label>
                        </div><?php */ ?>
                        <span class="error display-no" id="errormsg"></span>
                    </div>
                    <div class="form-floating">
                        <input type="hidden" name="entity_id" id="entity_id" value="<?php echo $profile->entity_id; ?>">
                        <input type="hidden" name="uploaded_image" id="uploaded_image" value="<?php echo isset($profile->image) ? $profile->image : ''; ?>" />
                        <input type="text" name="first_name" id="first_name" class="form-control" placeholder=" " value="<?php echo $profile->first_name; ?>" maxlength='20'>
                        <label><?php echo $this->lang->line('first_name') ?></label>
                    </div>
                    <div class="form-floating">
                        <input type="text" name="last_name" id="last_name" class="form-control" placeholder=" " value="<?php echo $profile->last_name; ?>" maxlength='20'>
                        <label><?php echo $this->lang->line('last_name') ?></label>
                    </div>
                    <div class="form-floating">
                        <input type="email" name="email" id="email" class="form-control email" placeholder=" " value="<?php echo $profile->email; ?>" maxlength='50'>
                        <label><?php echo $this->lang->line('email') ?></label>
                    </div>
                    <div class="form-floating">
                        <input type="hidden" name="phone_code" id="phone_code" class="form-control" value="<?php echo $profile->phone_code; ?>">
                        <input type="tel" name="phone_number" id="phone_number" class="form-control digits required" readonly placeholder="" value="<?php echo $profile->mobile_number; ?>" maxlength='12'>
                        <?php //echo ($profile->login_type == 'facebook')?'':'readonly'; ?>
                        <label><?php echo $this->lang->line('phone_number') ?></label>
                        <div class="phn_err"></div>
                    </div>
                    <?php if($profile->login_type == 'facebook' || $profile->login_type == 'google') { ?>
                        <input type="hidden" name="password" id="password" class="form-control" placeholder=" " value="">
                        <?php //echo $this->lang->line('password') ?>
                        <input type="hidden" name="confirm_password" id="confirm_password" class="form-control" placeholder=" " value="">
                        <?php //echo $this->lang->line('confirm_pass') ?>
                    <?php } else { ?>    
                        <div class="form-floating">
                            <input type="password" name="password" id="password" class="form-control" placeholder=" ">
                            <label><?php echo $this->lang->line('password') ?></label>
                            <i id="togglePasswordshow" class="icon icon-input icon-eye"></i>
                        </div>
                        <div class="form-floating">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder=" ">
                            <label><?php echo $this->lang->line('confirm_pass') ?></label>
                            <i id="togglePasswordshow" class="icon icon-input icon-eye"></i>
                        </div>
                    <?php } ?>
                    <div class="save-btn">
                        <button type="submit" name="submit_profile" id="submit_profile" value="Save" class="btn btn-primary w-100"><?php echo $this->lang->line('save') ?></button>
                    </div>
                    <div id="error-msg" class="alert alert-danger" style="display: none;"></div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal text-center" id="delete-account" >
        <div class="modal-dialog modal-sm  modal-dialog-centered">
            <div class="modal-content p-4 py-xl-8">
                <a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close" onclick="applyTipForOrders('clear')">
                    <img src="<?php base_url() ?>/assets/front/images/icon-close.svg" alt="">
                </a>
                <div class="mb-3 d-flex flex-column">
                    <h4 class="text-capitalize"><?php echo $this->lang->line('delete_acc') ?>?</h4>
                    <small><?php echo $this->lang->line('delete_module'); ?></small>
                </div>
                <div class="action-btn d-flex justify-content-center">
                    <input type="button" name="delete_account" id="delete_account" value="<?php echo $this->lang->line('delete') ?>" class="btn btn-sm btn-primary" onclick="deleteAccount()">
                    <div class="p-1"></div>
                    <input type="button" name="cancel" id="cancel" value="<?php echo $this->lang->line('cancel') ?>" class="btn btn-sm btn-primary" data-dismiss="modal">
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="add-address">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content p-4 p-xl-8">
                <a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close">
                    <img src="<?php base_url() ?>/assets/front/images/icon-close.svg" alt="">
                </a>
                <h2 class="text-capitalize title pb-2 text-center mb-4 mb-xl-8"><span id="address-form-title"><?php echo $this->lang->line('add') ?></span> <?php echo $this->lang->line('address') ?></h2>

                <div class="row row-cols-1 row-cols-xl-2 row-grid">
                    <div class="col horizontal-image">
                        <figure id="location-map" class="picture h-100">
                            <div id="map_canvas" class="absolute-div"></div>
                        </figure>
                    </div>
                    <div class="col">
                        <form id="form_add_address" name="form_add_address" method="post" enctype="multipart/form-data" >
                            
                            <!--<div class="form-group">
                                <input type="text" name="add_address_area" id="add_address_area"  placeholder=" " onchange="getMarker('');" class="form-control">
                                <label><?php echo $this->lang->line('search_delivery_area') ?></label>
                            </div>-->

                            <div class="form-floating form-pin">
                                <input type="hidden" name="user_entity_id" id="user_entity_id" value="<?php echo $this->session->userdata('UserID'); ?>">
                                <input type="hidden" name="add_entity_id" id="add_entity_id" value="">
                                <input type="hidden" name="latitude" id="latitude" value="">
                                <input type="hidden" name="longitude" id="longitude" value="">
                                <input type="hidden" name="default_latitude" id="default_latitude" value="">
                                <input type="hidden" name="default_longitude" id="default_longitude" value="">
                                <a href="javascript:void(0);" class="icon auto_location" onclick="getLocation('my_profile');">
                                    <img src="<?php base_url() ?>/assets/front/images/icon-pin.svg" alt="">
                                </a>
                                <input type="text" name="address_field" id="address_field" class="form-control form-control-icon-start" onFocus="geolocate('')" placeholder=" " onchange="getMarker(this.value)">
                                <label><?php echo $this->lang->line('your_location') ?></label>
                            </div>
                            <div class="form-floating">
                                <input type="text" name="landmark" id="landmark" class="form-control" placeholder=" ">
                                <label><?php echo $this->lang->line('landmark_txt') ?></label>
                            </div>
                            <div class="form-floating">
                                <input type="text" name="zipcode" id="zipcode" class="form-control" placeholder=" " minlength="5" maxlength="6">
                                <label><?php echo $this->lang->line('postal_code') ?></label>
                            </div>
                            <div class="form-floating">
                                <input type="text" name="city" id="city" class="form-control" placeholder=" ">
                                <label><?php echo $this->lang->line('city') ?></label>
                            </div>
                            <div class="form-floating">
                                <input type="text" name="state" id="state" class="form-control" placeholder=" ">
                                <label><?php echo $this->lang->line('state') ?></label>
                            </div>
                            <div class="form-floating">
                                <input type="text" name="country" id="country" class="form-control" placeholder=" ">
                                <label><?php echo $this->lang->line('country') ?></label>
                            </div>
                            <div class="form-floating">
                                <input type="text" name="address_label" id="address_label" class="form-control" placeholder=" ">
                                <label><?php echo $this->lang->line('city_txt') ?></label>
                            </div>
                            <div class="form-action">
                                <input type="hidden" name="submit_address" id="submit_address" value="Add" class="btn btn-primary">
                                <button type="submit" name="save_address" id="save_address" value="Save" class="btn btn-primary w-100"><?php echo $this->lang->line('save') ?></button>
                            </div>
                            <div id="error-msg" class="alert alert-danger" style="display: none;"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal text-center" id="delete-address">
        <div class="modal-dialog modal-sm  modal-dialog-centered">
            <div class="modal-content p-4 py-xl-8">
                <a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close" onclick="applyTipForOrders('clear')">
                    <img src="<?php base_url() ?>/assets/front/images/icon-close.svg" alt="">
                </a>
                <div class="mb-3 d-flex flex-column">
                    <h4 class="text-capitalize"><?php echo $this->lang->line('delete_address') ?>?</h4>
                    <small><?php echo $this->lang->line('delete_module'); ?></small>
                </div>
                <div class="action-btn d-flex justify-content-center">
                    <input type="hidden" name="delete_address_id" id="delete_address_id" value="">
                    <input type="button" name="delete_address" id="delete_address" value="<?php echo $this->lang->line('delete') ?>" class="btn btn-sm btn-primary" onclick="deleteAddress()">
                    <div class="p-1"></div>
                    <input type="button" name="cancel" id="cancel" value="<?php echo $this->lang->line('cancel') ?>" class="btn btn-sm btn-primary" data-dismiss="modal">
                </div>
            </div>
        </div>
    </div>
    <div class="modal text-center" id="main-address">
        <div class="modal-dialog modal-sm  modal-dialog-centered">
            <div class="modal-content p-4 py-xl-8">
                <a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close" onclick="applyTipForOrders('clear')">
                    <img src="<?php base_url() ?>/assets/front/images/icon-close.svg" alt="">
                </a>
                <div class="mb-3 d-flex flex-column">
                    <h4 class="text-capitalize"><?php echo $this->lang->line('set_main_address') ?>?</h4>
                    <small><?php echo $this->lang->line('set_main_address_confirm') ?></small>
                </div>
                <div class="action-btn d-flex justify-content-center">
                    <input type="hidden" name="main_address_id" id="main_address_id" value="">
                    <input type="button" name="main_address" id="main_address" value="<?php echo $this->lang->line('ok'); ?>" class="btn btn-sm btn-primary" onclick="setMainAddress()">
                    <div class="p-1"></div>
                    <input type="button" name="cancel" id="cancel" value="<?php echo $this->lang->line('cancel') ?>" class="btn btn-sm btn-primary" data-dismiss="modal">
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="cartNotEmpty">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4 p-xl-8">
                <a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close" onclick="applyTipForOrders('clear')">
                        <img src="<?php base_url() ?>/assets/front/images/icon-close.svg" alt="">
                    </a>
                <div class="mb-4 d-flex flex-column">
                    <h4 class="text-capitalize mb-1"><?php echo $this->lang->line('add_to_cart') ?> ?</h4>
                    <small><?php echo $this->lang->line('items_already_in_cart') ?></small>
                </div>
                <form id="custom_cart_restaurant_form">
                    <label class="mb-2"><?php echo $this->lang->line('res_details_text2') ?></label>
                    <div class="form-check mb-2">
                        <input type="hidden" name="rest_restaurant_id" id="rest_restaurant_id" value="">
                        <input type="hidden" name="rest_user_id" id="rest_user_id" value="">
                        <input type="hidden" name="menuDetailsArray" id="menuDetailsArray" value=""> 
                        <input type="radio" checked="checked" class="form-check-input radio_addon" name="addNewItems" id="discardOld" value="discardOld">
                        <label class="form-check-label" for="discardOld"><?php echo $this->lang->line('discard_old') ?></label>
                    </div>
                    <div class="form-check mb-2">
                        <input type="radio" class="form-check-input radio_addon" name="addNewItems" id="keepOld" value="keepOld">
                        <label class="form-check-label" for="keepOld"><?php echo $this->lang->line('keep_old') ?></label>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary mt-5" id="cartrestaurant" onclick="ConfirmCartItemsOnReorder()"><?php echo $this->lang->line('confirm') ?></button>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="reviewModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4 p-xl-8">
                <a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close">
                    <img src="<?php base_url() ?>/assets/front/images/icon-close.svg" alt="">
                </a>
                <h2 class="text-capitalize title pb-2 text-center mb-4 mb-xl-8"><?php echo $this->lang->line('review_ratings') ?></h2>

                <form id="review_form" name="review_form" method="post" class="form-horizontal float-form">
                    <figure class="mb-4 text-center">
                        <img src="<?php echo base_url();?>assets/front/images/image-review.png">
                    </figure>
                    <div class="rating d-flex justify-content-center mb-4">
                        <input type="hidden" name="review_user_id" id="review_user_id" value="<?php echo $this->session->userdata('UserID'); ?>">
                        <input type="hidden" name="review_restaurant_id" id="review_restaurant_id" value="">
                        <input type="hidden" name="review_res_content_id" id="review_res_content_id" value="">
                        <input type="hidden" name="review_order_id" id="review_order_id" value="">
                        
                        <span>
                            <input type="radio" name="rating" id="str5" value="5">
                            <label class="icon" for="str5">
                                <img src="<?php echo base_url();?>assets/front/images/icon-star.svg" class="icon-star">
                                <img src="<?php echo base_url();?>assets/front/images/icon-star-fill.svg" class="icon-star-fill">
                            </label>
                        </span>
                        <span>
                            <input type="radio" name="rating" id="str4" value="4">
                            <label class="icon" for="str4">
                                <img src="<?php echo base_url();?>assets/front/images/icon-star.svg" class="icon-star">
                                <img src="<?php echo base_url();?>assets/front/images/icon-star-fill.svg" class="icon-star-fill">
                            </label>
                        </span>
                        <span class="checked"><input type="radio" name="rating" id="str3" value="3">
                            <label class="icon" for="str3">
                                <img src="<?php echo base_url();?>assets/front/images/icon-star.svg" class="icon-star">
                                <img src="<?php echo base_url();?>assets/front/images/icon-star-fill.svg" class="icon-star-fill">
                            </label>
                        </span>
                        <span>
                            <input type="radio" name="rating" id="str2" value="2">
                            <label class="icon" for="str2">
                                <img src="<?php echo base_url();?>assets/front/images/icon-star.svg" class="icon-star">
                                <img src="<?php echo base_url();?>assets/front/images/icon-star-fill.svg" class="icon-star-fill">
                            </label>
                        </span>
                        <span>
                            <input type="radio" name="rating" id="str1" value="1">
                            <label class="icon" for="str1">
                                <img src="<?php echo base_url();?>assets/front/images/icon-star.svg" class="icon-star">
                                <img src="<?php echo base_url();?>assets/front/images/icon-star-fill.svg" class="icon-star-fill">
                            </label>
                        </span>
                    </div>
                    <div class="form-floating">
                        <input type="text" name="review_text" id="review_text" class="form-control" placeholder="<?php echo $this->lang->line('write_review') ?>">
                        <label for="review_text"><?php echo $this->lang->line('write_review') ?></label>
                    </div>
                    <div class="form-action text-center">
                        <button type="submit" name="submit_review" id="submit_review" class="btn btn-primary"><?php echo $this->lang->line('add_review') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="showNotifi">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4 p-xl-8">
                <a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close" onclick="applyTipForOrders('clear')">
                    <img src="<?php base_url() ?>/assets/front/images/icon-close.svg" alt="">
                </a>
                <h4 class="modal-title mb-2"></h4>
                <div class="notification_description"></div>
            </div>
        </div>
    </div>
    <div class="modal" id="add-stripecard">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4 p-xl-8">
                <a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close" onclick="applyTipForOrders('clear')">
                    <img src="<?php base_url() ?>/assets/front/images/icon-close.svg" alt="">
                </a>
                <h2 class="text-capitalize title pb-2 text-center mb-4 mb-xl-8" id="add_stripetitle"><?php echo $this->lang->line('add_card') ?></h2>
                <form id="form_credit_card" name="form_credit_card" method="post" class="form-horizontal float-form" enctype="multipart/form-data">
                    <div class="form-floating">
                        <input type="hidden" name="is_editcard" id="is_editcard" value="no">
                        <input type="hidden" name="payment_method_id" id="payment_method_id" value="">
                        <input type="text" name="card_number" id="card_number" class="form-control" placeholder=" " value="" maxlength='20'>
                        <label><?php echo $this->lang->line('card_number'); ?></label>
                        <div id="card_number_err" class="error"></div>
                    </div>
                    <div class="form-floating">
                        <div class="row row-grid">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="card_month" id="card_month" onchange="cardFormValidate();" class="form-control select_month_form">                      
                                        <option value=""><?php echo $this->lang->line('select_month'); ?></option>
                                        <option value="01"><?php echo $this->lang->line('january'); ?></option>
                                        <option value="02"><?php echo $this->lang->line('february'); ?></option>
                                        <option value="03"><?php echo $this->lang->line('march'); ?></option>
                                        <option value="04"><?php echo $this->lang->line('april'); ?></option>
                                        <option value="05"><?php echo $this->lang->line('may'); ?></option>
                                        <option value="06"><?php echo $this->lang->line('june'); ?></option>
                                        <option value="07"><?php echo $this->lang->line('july'); ?></option>
                                        <option value="08"><?php echo $this->lang->line('august'); ?></option>
                                        <option value="09"><?php echo $this->lang->line('september'); ?></option>
                                        <option value="10"><?php echo $this->lang->line('october'); ?></option>
                                        <option value="11"><?php echo $this->lang->line('november'); ?></option>
                                        <option value="12"><?php echo $this->lang->line('december'); ?></option>
                                    </select>
                                    <label><?php echo $this->lang->line('select_month'); ?></label>
                                    <div id="card_month_err" class="error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="card_year" id="card_year" onchange="cardFormValidate();" class="form-control select_year_form">
                                        <?php $card_year = date('Y'); $card_yearmax = $card_year+10;
                                        $card_yearstr = $card_year-1; ?>
                                        <option value=""><?php echo $this->lang->line('select_year') ?></option>
                                        <?php for($crdy=$card_yearstr;$crdy<$card_yearmax;$crdy++){ ?>
                                        <option value="<?php echo ($crdy+1);?>"><?php echo ($crdy+1);?></option>                      
                                        <?php } ?>                            
                                    </select>
                                    <label><?php echo $this->lang->line('select_year') ?></label>
                                    <div id="card_year_err" class="error"></div>
                                </div>
                            </div>
                        </div>
                    </div>                
                    <div class="form-floating">
                        <input type="text" name="card_cvv" id="card_cvv" class="form-control" placeholder=" " value="" maxlength='4'>
                        <label><?php echo $this->lang->line('card_cvv') ?></label>
                        <div id="card_cvv_err" class="error"></div>
                    </div>
                    <div class="form-floating">
                        <input type="text" name="card_zip" id="card_zip" class="form-control" placeholder=" " value="" maxlength='6' minlength="5" >
                        <label><?php echo $this->lang->line('card_zip') ?></label>                    
                    </div>
                    <div class="form-floating">
                        <div class="form-check">
                            <input type="checkbox" id="set_as_default_stripecard" name="set_as_default_stripecard" value="yes" class="form-check-input">
                            <label class="form-check-label" for="set_as_default_stripecard"><?php echo $this->lang->line('set_as_default'); ?></label>
                        </div>
                    </div>
                    <div class="form-action">
                        <button type="submit" name="submit_card" id="submit_card" value="Save" class="btn btn-primary w-100"><?php echo $this->lang->line('save') ?></button>
                    </div>
                    <span class="alert alert-danger" id="carderrormsg" style="display: none;"></span>
                </form>
            </div>
        </div>
    </div>
    <div class="modal text-center" id="delete-stripeaccount">
        <div class="modal-dialog modal-sm  modal-dialog-centered">
            <div class="modal-content p-4 py-xl-8">
                <a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close" onclick="applyTipForOrders('clear')">
                    <img src="<?php base_url() ?>/assets/front/images/icon-close.svg" alt="">
                </a>
                <div class="mb-3 d-flex flex-column">
                    <h4 class="text-capitalize"><?php echo $this->lang->line('delete_card') ?>?</h4>
                    <small><?php echo $this->lang->line('delete_module'); ?></small>
                </div>
                <form id="form_card_delete" name="form_card_delete" method="post" class="form-horizontal float-form" enctype="multipart/form-data">
                    <div class="action-btn d-flex justify-content-center">
                        <input type="hidden" name="delete_PaymentMethodid" id="delete_PaymentMethodid" value="">
                        <input type="hidden" name="delete_stripecus_id" id="delete_stripecus_id" value="">
                        
                        <input type="submit" name="delete_cardbtn" id="delete_cardbtn" value="<?php echo $this->lang->line('delete') ?>" class="btn btn-sm btn-primary">
                        <div class="p-1"></div>
                        <input type="button" name="cancel" id="cancel" value="<?php echo $this->lang->line('cancel') ?>" class="btn btn-sm btn-primary" data-dismiss="modal">
                    </div>
                </form> 
            </div>
        </div>
    </div>
    <div class="modal text-center" id="order-confirmation">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4 p-xl-8">
                <a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close" onclick="document.location.href='<?php echo base_url();?>myprofile';">
                    <img src="<?php base_url() ?>/assets/front/images/icon-close.svg" alt="">
                </a>
                <h2 class="text-capitalize title pb-2 text-center mb-4 mb-xl-8"><?php echo $this->lang->line('order_confirmation') ?></h2>
                

                <?php if(isset($payment) && $payment['status'] == 'paid'){ ?>
                    <figure class="mb-4">
                        <img src="<?php echo base_url();?>assets/front/images/image-order-confirm.svg" alt="Booking availability">
                    </figure>
                    <h6 class="mb-1"><?php echo $this->lang->line('thankyou_for_order') ?></h6>
                    <small><?php echo $this->lang->line('order_placed') ?></small>
                    <p><?php echo ($payment['pay_status'])?ucfirst($payment['pay_status']):''; ?><?php echo ($payment['message'] && $payment['pay_status'])?' : ':''; ?><?php echo ($payment['message'])?$payment['message']:''; ?></p>

                    <?php if($payment['earned_points'] && $payment['earned_points']>0){?>
                        <div class="alert alert-success" id="earned_points"><?php echo $this->lang->line('points_earned_from_order').": ".$payment['earned_points'];?></div>
                    <?php } ?>
                    
                    <div class="d-flex mx-auto mt-4">
                        <?php //if($payment['order_delivery'] == 'Delivery'){ ?>                    
                            <span id="track_order"><a href="<?php echo base_url().'order/track_order/'.str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($payment['order_id'])); ?>" class="btn btn-sm px-3 btn-primary"><?php echo $this->lang->line("track_order"); ?></a></span>  

                            <div class="p-1"></div>              
                        <?php //} else { ?>                    
                            <span id="track_order"><a href="<?php echo base_url().'myprofile' ?>" class="btn btn-sm px-3 btn-primary"><?php echo $this->lang->line("view_details"); ?></a></span>
                        <?php //} ?>
                    </div>
                <?php } else { ?>
                    <figure class="mb-4">
                        <img src="<?php echo base_url();?>assets/front/images/image-book.svg" alt="Booking Availability">
                    </figure>
                    <h6 class="mb-1"><?php echo $this->lang->line('sorry_not_placed') ?></h6>
                    <small><?php echo (isset($payment['pay_status']))?$payment['pay_status']:'' ?><?php echo (isset($payment['message']) && !empty($payment['message']))?' : '.$payment['message']:''; ?></small>  
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="modal" id="driver-tip">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4 p-xl-8">
                <a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close" onclick="applyTipForOrders('clear')">
                    <img src="<?php base_url() ?>/assets/front/images/icon-close.svg" alt="">
                </a>
                <h2 class="text-capitalize title pb-2 text-center mb-4 mb-xl-8"><?php echo $this->lang->line('driver_tip') ?></h2>
                <div id="driver-tip-form"></div>

                <div class="alert alert-success display-no" id="drivertip_successmsg"></div>
                <div class="stripediv d-none">
                    <form id="form_user_details" name="form_user_details" method="post" class="form-horizontal" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="item-card" id="listall_card">
                                </div>

                                <div class="d-flex flex-column flex-sm-row mt-4">
                                    <?php $singlepaymentstyle = '';
                                    $singlepaymentdisable = '';
                                    if($this->session->userdata('is_guest_checkout') == 1 || $this->session->userdata('UserType') == 'Agent') {
                                        $singlepaymentstyle = 'style="display: none;" checked="checked"';
                                        $singlepaymentdisable = 'disabled';
                                    } ?> 
                                    <div class="form-check align-self-center d-none d-sm-block">
                                        <input class="form-check-input" type="radio" name="payment-source-btn" <?php echo $singlepaymentstyle; ?> type="radio" name="payment-source-btn" value="newcard" id="new-card-radio" onclick="togglecardbutton(this.value);">
                                        <label for="new-card-radio" class="form-check-label"><?php echo $singlepaymentstyle; ?></label>
                                    </div>
                                    <div id="card-element" class="form-control StripeElement StripeElement--empty"><!--Stripe.js injects the Card Element--></div>
                                    <div class="p-1"></div>
                                    <button class="btn btn-sm btn-primary" id="submit_stripe" <?php echo $singlepaymentdisable; ?>>
                                        <div class="spinner hidden" id="spinner"></div>
                                        <span id="button-text"><?php echo $this->lang->line('pay'); ?></span>
                                    </button>
                                </div>

                                <?php if($this->session->userdata('UserType') == 'User') { ?>
                                    <div id="save_card_checkbox" class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="save_card_checkbox_val" name="save_card_checkbox_val" value="yes">
                                        <label class="form-check-label" for="save_card_checkbox_val"><?php echo $this->lang->line('do_you_want_to_save_card'); ?></label>
                                    </div>
                                <?php } ?>

                                <div id="change-tip-div" class="mt-4">
                                    <label for="change_tip_val">
                                        <input type="hidden" id="payment_option_val" name="payment_option_val" value="">
                                        <a href="javascript:void(0)" onclick="backToSelectTip()"><?php echo $this->lang->line('change_tip_amount') ?></a>
                                    </label>
                                </div>
                                <p id="card-error" class="alert alert-danger"></p>
                                <p class="alert alert-success result-message" style="display: none;"><?php echo $this->lang->line('payment_succeeded'); ?></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="add-wallet-money" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4 p-xl-8">
                <a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close">
                    <img src="<?php base_url() ?>/assets/front/images/icon-close.svg" alt="">
                </a>
                <h2 class="text-capitalize title pb-2 text-center mb-4 mb-xl-8"><?php echo $this->lang->line('add_money') ?></h2>

                <form id="form_wallet_topup" name="form_wallet_topup" method="post" class="form-horizontal" enctype="multipart/form-data">
                    <input type="text" name="topup_amount" id="topup_amount" class="form-control" placeholder="<?php echo $this->lang->line('amount'); ?>">
                    <div id="topup_amount_err" class="error"></div>

                    <div class="old-card-group" id="listall_cards_fortopup">
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="payment_label payment-checkout-new">
                                <div class="radio-btn-list">
                                    <label class="payment_label">
                                        <?php $singlepaymentstyle = '';
                                        $singlepaymentdisable = '';
                                        if($this->session->userdata('is_guest_checkout') == 1 || $this->session->userdata('UserType') == 'Agent') {
                                            $singlepaymentstyle = 'style="display: none;" checked="checked"';
                                            $singlepaymentdisable = 'disabled';
                                        } ?> 
                                        <div class="radio-btn-list">
                                            <label class="payment_label">
                                                <input type="radio" name="payment-source-btn-forwallet" <?php echo $singlepaymentstyle; ?> type="radio" value="newcard" id="new-card-radio-forwallet" onclick="togglecardbutton_forwallet(this.value);">
                                                <span <?php echo $singlepaymentstyle; ?>></span>
                                            </label>
                                        </div>
                                    </label>
                                </div>
                                <div id="card-element-topup"><!--Stripe.js injects the Card Element--></div>
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <button id="submit_stripe_forwallet" <?php echo $singlepaymentdisable; ?>>
                                <div class="spinner hidden" id="spinner_topup"></div>
                                <span id="button-text-wallet"><?php echo $this->lang->line('pay'); ?></span>
                            </button>
                        </div>
                        <?php if($this->session->userdata('UserType') == 'User') { ?>
                            <div class="col-md-12">
                                <div id="save_card_checkbox_forwallet" class="save_card_checkbox_forwallet">
                                    <div class="radio-btn-list">
                                        <label for="save_card_checkbox_val_forwallet">
                                            <input type="checkbox" id="save_card_checkbox_val_forwallet" name="save_card_checkbox_val_forwallet" value="yes">
                                            <span><?php echo $this->lang->line('do_you_want_to_save_card'); ?></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-md-12">
                            <p id="card-error-forwallet" role="alert" style="color:#fa755a;"></p>
                            <p class="result-message hidden"><?php echo $this->lang->line('payment_succeeded'); ?></p>
                        </div>
                    </div>
                </form>
                <div class="alert alert-success display-no" id="wallet_topup_successmsg"></div>
                <div id="error-msg" class="alert alert-danger display-no"></div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo google_key; ?>&libraries=places"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/front/js/scripts/admin-management-front.js"></script>
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
    <script type="text/javascript">
    var map, marker;
    jQuery(document).ready(function() {
        initMap();
        // initAutocomplete('add_address_area');
        initAutocomplete('address_field');
        // auto detect location if even searched once.
        if (SEARCHED_LAT == '' && SEARCHED_LONG == '' && SEARCHED_ADDRESS == '') {
            getLocation('my_profile');
        }
        else
        {
            getSearchedLocation(SEARCHED_LAT,SEARCHED_LONG,SEARCHED_ADDRESS,'my_profile');
        }
        var address = default_country_fromheader;
        var default_latitude = 0;
        var default_longitude = 0;
        if (address !== "undefined" && address !== null ) { 
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode( { 'address': address}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    default_latitude = results[0].geometry.location.lat();
                    default_longitude = results[0].geometry.location.lng();  
                    $("#default_latitude").val(default_latitude);
                    $("#default_longitude").val(default_longitude);
                }
            });
        }
        function initMap(){
            var bounds = new google.maps.LatLngBounds();
            map = new google.maps.Map(document.getElementById('map_canvas'),
            {
                center: new google.maps.LatLng(default_latitude,default_longitude),
                zoom: 16
            });
            geocoder = new google.maps.Geocoder();
            var position = new google.maps.LatLng(default_latitude,default_longitude);
            marker = new google.maps.Marker({
                position: position,
                draggable: true,
                map: map,
            });
            bounds.extend(position);
            infowindow = new google.maps.InfoWindow({
              size: new google.maps.Size(150, 50)
            });
            google.maps.event.addListener(marker, 'dragend', function(evt) {
                geocodePosition(marker.getPosition());
                $('#latitude').val(evt.latLng.lat());
                $('#longitude').val(evt.latLng.lng());
            });
        }
        // google address autocomplete off 
        $('#address_field').on('focus',function(){
            $(this).attr('autocomplete', 'nope');           
        });
    });
    $("#edit-profile").click(function(){
      $('#preview').attr('src', '').attr('style','display: none;');
    });
    function readURL(input)
    {   
        var fileInput = document.getElementById('image');
        var filePath = fileInput.value;
        var fileUrl = window.URL.createObjectURL(fileInput.files[0]);
        var extension = filePath.substr((filePath.lastIndexOf('.') + 1)).toLowerCase();
        if(input.files[0].size <= 10506316){ // 10 MB
            if(extension == 'png' || extension == 'jpg' || extension == 'jpeg' || extension == 'gif') {
                if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#preview').attr('src', e.target.result).attr('style','display: inline-block;');
                    $("#old").hide();
                    $('#errormsg').html('').hide();
                }
                reader.readAsDataURL(input.files[0]);
                }
            }
            else{
                $('#preview').attr('src', '').attr('style','display: none;');
                $('#errormsg').html("<?php echo $this->lang->line('file_extenstion'); ?>").show();
                $('#image').val('');
                $("#old").show();
            }
        }else{
            $('#preview').attr('src', '').attr('style','display: none;');
            $('#errormsg').html("<?php echo $this->lang->line('file_size_msg'); ?>").show();
            $('#image').val('');
            $("#old").show();
        }
    }
    google.maps.event.addDomListener(window, 'load', function() {
        const optionsObj = {
            //componentRestrictions: { country: ["us","in","pk"]},
            fields: ["formatted_address","address_components", "geometry", "icon", "name"],
        };        
        var places = new google.maps.places.Autocomplete(document.getElementById('address_field'), optionsObj);
        //var places = new google.maps.places.Autocomplete(document.getElementById('address_field'),{ types: ['address'] },{ types: ['formatted_address'] });
        google.maps.event.addListener(places, 'place_changed', function() {
            var place = places.getPlace();
            var  value = place.formatted_address.split(",");
            if(place.name == value[0]){
                document.getElementById("address_field").value = place.formatted_address;    
            }else{
                document.getElementById("address_field").value = place.name+', '+place.formatted_address;
            }
            document.getElementById("city").value = '';
            document.getElementById("state").value = '';
            document.getElementById("country").value = '';
            document.getElementById("zipcode").value = '';
            $.each(place.address_components, function( index, value ) {
                $.each(value.types, function( index, types ) {
                    if(types == 'administrative_area_level_2'){
                       document.getElementById("city").value = value.long_name;
                    }
                    if(types == 'administrative_area_level_1'){
                       document.getElementById("state").value = value.long_name;
                    }
                    if(types == 'country'){
                       document.getElementById("country").value = value.long_name;
                    }
                    if(types == 'postal_code'){
                       document.getElementById("zipcode").value = value.long_name;
                    }
                });
            });
        });
    });
    // show delete address popup
    function showDeleteAcc(){
        //$('#delete_address_id').val(address_id);
        $('#delete-account').modal('show');
    }
    // delete address
    function deleteAccount(){
        var user_id = '<?php echo $this->session->userdata('UserID');?>';
        jQuery.ajax({
            type : "POST",
            dataType : "html",
            url : BASEURL+ 'myprofile/ajaxDeleteAccount' ,
            data : {'user_id':user_id},
            beforeSend: function(){
                $('#quotes-main-loader').show();
            },
            success: function(response) {
                //redirect to logout.
                logout();
                //window.location.href = BASEURL+"home/logout";
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $('#quotes-main-loader').hide();
                alert(errorThrown);
            }
        });
    }
    </script>
    <script type="text/javascript">
        function copyToClipboard() {
            var copyText = document.getElementById("ref_code").value;
            // Create a dummy input to copy the string array inside it
            var dummy = document.createElement("input");
            // Add it to the document
            document.body.appendChild(dummy);
            // Set its ID
            dummy.setAttribute("id", "dummy_id");
            // Output the array into it
            document.getElementById("dummy_id").value=copyText;
            // Select it
            dummy.select();
            // Copy its contents
            document.execCommand("copy");
            // Remove it as its not needed anymore
            document.body.removeChild(dummy);
            $('#copied-success').fadeIn(800);
            $('#copied-success').fadeOut(800);
        }
    </script>
    <script type="text/javascript">
    <?php if($profile->login_type != 'facebook' && $profile->login_type != 'google') { ?>
        /*document.querySelector("#password").classList.add("input-password");document.getElementById("toggle-password1").classList.remove("d-none");const passwordInput1=document.querySelector("#password");const togglePasswordButton1=document.getElementById("toggle-password1");togglePasswordButton1.addEventListener("click",togglePassword1);function togglePassword1(){if(passwordInput1.type==="password"){passwordInput1.type="text";togglePasswordButton1.setAttribute("aria-label","Hide password.")}else{passwordInput1.type="password";togglePasswordButton1.setAttribute("aria-label","Show password as plain text. "+"Warning: this will display your password on the screen.")}}
        document.querySelector("#confirm_password").classList.add("input-password");document.getElementById("toggle-password2").classList.remove("d-none");const passwordInput2=document.querySelector("#confirm_password");const togglePasswordButton2=document.getElementById("toggle-password2");togglePasswordButton2.addEventListener("click",togglePassword2);function togglePassword2(){if(passwordInput2.type==="password"){passwordInput2.type="text";togglePasswordButton2.setAttribute("aria-label","Hide password.")}else{passwordInput2.type="password";togglePasswordButton2.setAttribute("aria-label","Show password as plain text. "+"Warning: this will display your password on the screen.")}}*/
    <?php } ?>
    </script>
    <script type="text/javascript">
        //intl-tel-input plugin
        var onedit_iso = '';
        <?php if($profile->phone_code) {
            $onedit_iso = $this->common_model->getIsobyPhnCode($profile->phone_code); ?>
            onedit_iso = '<?php echo $onedit_iso; ?>'; //saved in session
            <?php $iso = $this->common_model->country_iso_for_dropdown();
            $default_iso = $this->common_model->getDefaultIso(); ?>

            var country_iso = <?php echo json_encode($iso); ?>; //all active countries
            var default_iso = <?php echo json_encode($default_iso); ?>; //default country
            default_iso = (default_iso)?default_iso:'';
            var initial_preferred_iso = (onedit_iso)?onedit_iso:default_iso;
            //editprofile form intel plugin on number :: start
            // Initialize the intl-tel-input plugin
            const phoneInputField = document.querySelector("#phone_number");
            const phoneInput = window.intlTelInput(phoneInputField, {
                initialCountry: onedit_iso,
                preferredCountries: [initial_preferred_iso],
                onlyCountries: country_iso,
                separateDialCode:true,
                autoPlaceholder:"polite",
                formatOnDisplay:false,
                utilsScript: BASEURL+'assets/admin/plugins/intl_tel_input/utils.js',
                    //"https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            });
            $(document).on('input','#phone_number',function(){
                event.preventDefault();
                var phoneNumber = phoneInput.getNumber();
                if (phoneInput.isValidNumber()) {
                    var countryData = phoneInput.getSelectedCountryData();
                    var countryCode = countryData.dialCode;
                    $('#phone_code').val(countryCode);
                    phoneNumber = phoneNumber.replace('+'+countryCode,'');
                    $('#phone_number').val(phoneNumber);
                }
            });
            $(document).on('focusout','#phone_number',function(){
                event.preventDefault();
                var phoneNumber = phoneInput.getNumber();
                if (phoneInput.isValidNumber()) {
                    var countryData = phoneInput.getSelectedCountryData();
                    var countryCode = countryData.dialCode;
                    $('#phone_code').val(countryCode);
                    phoneNumber = phoneNumber.replace('+'+countryCode,'');
                    $('#phone_number').val(phoneNumber);
                }
            });
            phoneInputField.addEventListener("close:countrydropdown",function() {
                var phoneNumber = phoneInput.getNumber();
                if (phoneInput.isValidNumber()) {
                    var countryData = phoneInput.getSelectedCountryData();
                    var countryCode = countryData.dialCode;
                    $('#phone_code').val(countryCode);
                    phoneNumber = phoneNumber.replace('+'+countryCode,'');
                    $('#phone_number').val(phoneNumber);
                }
            });
        <?php } ?>
        //editprofile form intel plugin on number :: end

    </script>
    <!-- for review/rating and menu -->
<script type="text/javascript">
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
    $('#menu_link').click(function(e) {
        $("#menu").delay(100).fadeIn(100);
        $("#review").fadeOut(100);
        $('#review_link').removeClass('active');
        $(this).addClass('active');
        e.preventDefault();
    });
    $('#review_link').click(function(e) {
        $("#review").delay(100).fadeIn(100);
        $("#menu").fadeOut(100);
        $('#menu_link').removeClass('active');
        $(this).addClass('active');
        e.preventDefault();
    });
});
/*$(document).ready(function(){ 
    setTimeout(function() {
        $('.cancel_order').css("display", "none")
    }, 60000);
});*/

function showNotification(noti_id)
{
    jQuery.ajax({
    type : "POST",
    dataType : "html",
    url : BASEURL+ 'myprofile/ajaxNotification',
    data : {'noti_id':noti_id }, 
    success: function(response) {            
      $('#showNotifi .notification_description').html(response)
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
        //alert(errorThrown);
    }
  });
  <?php 
    foreach($users_notifications as $key => $value){ ?>
        if('<?php echo $value->entity_id; ?>'== noti_id){
            $('#showNotifi .modal-title').text("<?php echo $value->notification_title; ?>");            
        }
    <?php } ?>    
  $('#showNotifi').modal('show');
}
// cancel order button and timer code
$(document).ready(function(){
    //to hide success message after few seconds
    if($('.alert-success').is(':visible')) {
        setTimeout(function(){
            $(".alert-success").empty();
            $(".alert-success").hide();
        }, 5000);
    }

    var idArr = [];
    $(".cancel_timer").each(function(){
        idArr.push($(this).attr("id"));
    });
    if(idArr.length != 0){
        for (let i = 0; i < idArr.length; i++)
        {
            var order_id = idArr[i].slice(-1);
            var orderidval = $("#orderid"+order_id).val(); 
            var status = $('#OrderStatus'+order_id).val();
            var date = $('#OrderDate'+order_id).val();
            CancelOrder(date,status,order_id,orderidval);
            //TimeCounter(date,status,order_id);
        }        
    }
    // Join array elements and display in alert
});
function CancelOrder(time,status,order_id,orderidval){    
    // Set the date we're counting down to
    var countDownDate = new Date(time).getTime();
    // Update the count down every 1 second
    var x = setInterval(function() {
        // Get today's date and time
        var d1 = new Date();
        var udate = d1.toUTCString();
        var d2 = new Date(d1.getUTCFullYear(),d1.getUTCMonth(),d1.getUTCDate(), d1.getUTCHours(),d1.getUTCMinutes(), d1.getUTCSeconds() );
        var now = d2.getTime();
        var additional_time = <?php echo $cancel_order_timer->OptionValue ?> *1000;
        var new_countDownDate  = countDownDate + additional_time;
        // Find the distance between now and the count down date
        var distance = new_countDownDate - now;
        // If the count down is finished, write some text

        // Time calculations for days, hours, minutes and seconds
        var min = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var sec = Math.floor((distance % (1000 * 60)) / 1000) + min*60;

        //Code for find the current order stauts :: Start
        jQuery.ajax({
            type : "POST",
            dataType : "html",
            url : BASEURL+ 'myprofile/getlatestOrderstaus',
            data : {'order_id':orderidval }, 
            success: function(response) {            
                status = response;

                //Display the result in the element with id="demo"
                if(min>=0 && sec!=0 && status=="placed"){
                    $("#cancel_timer"+order_id).html("<?php echo $this->lang->line("cancel_order_message") ?> "+sec+" <?php echo $this->lang->line("seconds") ?>");
                }            
                if (distance <= 0 || status!="placed") {
                    $('#cancel_order'+order_id).css("display", "none");
                    clearInterval(x);
                    $("#cancel_timer"+order_id).addClass('d-none');
                }              
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(errorThrown);
            }
        });
        //Code for find the current order stauts :: End
        
    }, 1000);
}
$("#add-stripecard").click(function(){
  $('#preview').attr('src', '').attr('style','display: none;');
});

$("#add-stripecardid").click(function(){
  $('#form_credit_card')[0].reset();
  //$('#carderrormsg').addClass('display-no');
  $('#carderrormsg').css('display','none');
  $('#carderrormsg').html('');
  $('#is_editcard').val('no'); 
  $('#payment_method_id').val('');    
  $("#card_number").prop("readonly", false);
  $("#add_stripetitle").html("<?php echo $this->lang->line("add_card"); ?>");  
});
</script>
<script src='<?php echo base_url(); ?>assets/front/js/creditCardValidator.js'></script>
<script type="text/javascript">
$(document).ready(function() {
    //card validation on input fields
    $('#form_credit_card input[type=text]').on('keyup',function(){
        cardFormValidate();
    });
});
$('#add-stripecard').on('hidden.bs.modal', function (e) {
  $('#form_credit_card').validate().resetForm();
  //$('#carderrormsg').addClass('display-no');
  $('#carderrormsg').css('display','none');
  $('#carderrormsg').html('');
  $('#is_editcard').val('no'); 
  $('#payment_method_id').val('');    
  $("#card_number").prop("readonly", false);
  $("#submit_card").attr("disabled", false);
  $("#add_stripetitle").html("<?php echo $this->lang->line("add_card"); ?>");
});
</script>

<!-- Stripe JavaScript library -->
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    $('#driver-tip').on('hidden.bs.modal', function (e) {
        applyTipForOrders('clear');
    });

    //Code for paypal tip payment :: Start    
    function mount_paypal_element()
    {
        var tip_amount = $('#driver_tip').val();
        var payment_option = $("input[name='payment_option']:checked").val();        
        if($('.paypal-button').length <=0)
        {
            paypal.Button.render({
                // Configure environment
                env: '<?php echo ($paypal->enable_live_mode == 1) ? 'production' : 'sandbox'; ?>',
                client: {
                    sandbox: '<?php echo $paypal->sandbox_client_id; ?>',
                    production: '<?php echo $paypal->live_client_id; ?>'
                },
                // Customize button (optional)
                locale: 'en_US',
                style: {
                    size: 'small',
                    color: 'gold',
                    shape: 'pill',
                    label: 'paypal',
                    tagline: false,
                },
                onInit: function(actions)
                {
                    paypalActions = actions;
                    paypalActions.enable();
                },
                validate: function(actions) {                    
                    actions.enable(); // Allow for validation in onClick()
                    paypalActions = actions; // Save for later enable()/disable() calls                    
                },
                onClick: function()
                {
                    $('#driver-tip').modal('hide');
                    paypalActions.enable();                  
                },
                // Set up a payment
                payment: function (data, actions) {
                    var tip_amount = $('#driver_tip').val();                    
                    return actions.payment.create({
                        transactions: [{
                            amount: {
                                total: tip_amount,
                                currency: '<?php echo $currency_symboltemp->currency_code; ?>'
                            }
                        }]
                    });
                },
                // Execute the payment
                onAuthorize: function (data, actions) {
                    return actions.payment.execute()
                    .then(function () {
                        jQuery.ajax({
                            type : "POST",
                            dataType: 'json',
                            url : BASEURL+"myprofile/tip_process?paymentID="+data.paymentID+"&token="+data.paymentToken+"&payerID="+data.payerID,
                            cache: false, 
                            processData: false,
                            contentType: false,
                            beforeSend: function(){
                                $('#quotes-main-loader').show();
                            },   
                            success: function(response)
                            {
                                if(response.transaction_id && response.transaction_id != '')
                                {
                                    //order summry start
                                    var str_paymentIntentid = response.transaction_id;
                                    var tip_order_id = $('#tip_order_id').val();
                                    //var tip_amount = $('#driver_tip').val();
                                    var updateordersummary_data = {
                                        tip_order_id_inp : tip_order_id,
                                        payment_option : payment_option,
                                        tip_amount_inp : tip_amount,
                                        tip_transaction_id : str_paymentIntentid,
                                    };
                                    
                                    fetch(BASEURL+"myprofile/updateOrderSummary", {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json"
                                        },
                                        body: JSON.stringify(updateordersummary_data)
                                    }).then(function(sresult) {
                                        return sresult.json();
                                    }).then(function(sdata) {
                                        
                                        if(sdata.status == 'success')
                                        {
                                            $('#driver-tip').modal('show');
                                            $('#driver-tip-form').addClass('d-none');
                                            $('#quotes-main-loader').hide();
                                            $('#drivertip_successmsg').html("<?php echo $this->lang->line('drivertip_successmsg'); ?>");
                                            $('#drivertip_successmsg').removeClass('display-no');
                                            setTimeout(function() {                                                    
                                                applyTipForOrders('clear');
                                            }, 2000);
                                            setTimeout(function() {
                                                $('#driver-tip').modal('hide');
                                                window.location.href = BASEURL+"myprofile";
                                            }, 3000);
                                        }
                                        else if(sdata.error=='')
                                        {
                                            var refundbox = bootbox.alert({
                                            message: "<?php echo $this->lang->line('refund_err_frontmssg'); ?>",
                                                buttons: {
                                                    ok: {
                                                        label: "<?php echo $this->lang->line('ok'); ?>",
                                                    }
                                                },
                                                callback: function () {
                                                    $('#quotes-main-loader').hide();
                                                    applyTipForOrders('clear');
                                                    $('#driver-tip').modal('hide');
                                                    window.location.href = BASEURL+"myprofile";
                                                }
                                            });
                                            setTimeout(function() {
                                                $('#quotes-main-loader').hide();
                                                refundbox.modal('hide');                                                
                                                applyTipForOrders('clear');
                                                $('#driver-tip').modal('hide');
                                                window.location.href = BASEURL+"myprofile";
                                            }, 10000);
                                        }
                                        else
                                        {
                                            var refundbox = bootbox.alert({
                                            message: "<?php echo $this->lang->line('refund_err_mssg'); ?>",
                                                buttons: {
                                                    ok: {
                                                        label: "<?php echo $this->lang->line('ok'); ?>",
                                                    }
                                                },
                                                callback: function () {
                                                    $('#quotes-main-loader').hide();
                                                    applyTipForOrders('clear');
                                                    $('#driver-tip').modal('hide');
                                                    window.location.href = BASEURL+"myprofile";
                                                }
                                            });
                                            setTimeout(function() {
                                                $('#quotes-main-loader').hide();
                                                refundbox.modal('hide');                                                
                                                applyTipForOrders('clear');
                                                $('#driver-tip').modal('hide');
                                                window.location.href = BASEURL+"myprofile";
                                            }, 10000);
                                        }
                                    });
                                    //order summry end
                                }
                                else
                                {
                                    var refundbox = bootbox.alert({
                                    message: "<?php echo $this->lang->line('refund_err_frontmssg'); ?>",
                                        buttons: {
                                            ok: {
                                                label: "<?php echo $this->lang->line('ok'); ?>",
                                            }
                                        },
                                        callback: function () {
                                            $('#quotes-main-loader').hide();
                                            applyTipForOrders('clear');
                                            $('#driver-tip').modal('hide');
                                            window.location.href = BASEURL+"myprofile";
                                        }
                                    });
                                    setTimeout(function() {
                                        $('#quotes-main-loader').hide();
                                        refundbox.modal('hide');                                        
                                        applyTipForOrders('clear');
                                        $('#driver-tip').modal('hide');
                                        window.location.href = BASEURL+"myprofile";
                                    }, 10000);
                                }
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown) {           
                                //alert(errorThrown);
                                $('#quotes-main-loader').hide();
                                applyTipForOrders('clear');
                                $('#driver-tip').modal('hide');
                                //window.location.href = BASEURL+"myprofile";
                            }
                        });
                        //Redirect to the payment process page                        
                    });
                }
            }, '#paypal-button');
        }
    }
    //Code for paypal tip payment :: End
    var card = '';
    var stripe_info = '<?php echo ($stripe_info->live_publishable_key!= '1')?$stripe_info->live_publishable_key:$stripe_info->test_publishable_key; ?>';
    var stripe = Stripe('<?php echo ($stripe_info->enable_live_mode == '1')?$stripe_info->live_publishable_key:$stripe_info->test_publishable_key; ?>');
    function mount_stripe_element() {
        if(stripe_info!=''){
            var elements = stripe.elements();
            var style = {
                base: {
                    color: "#32325d",
                    fontFamily: 'Arial, sans-serif',
                    fontSmoothing: "antialiased",
                    fontSize: "16px",
                    "::placeholder": {
                        color: "#32325d"
                    }
                },
                invalid: {
                    fontFamily: 'Arial, sans-serif',
                    color: "#fa755a",
                    iconColor: "#fa755a"
                }
            };
            card = elements.create("card", { hidePostalCode: false,style: style });
            // Stripe injects an iframe into the DOM
            card.mount("#card-element");
            if($("input[name='payment-source-btn']:checked").val() == 'newcard' || $("input[name='payment-source-btn']:checked").val() == undefined){
                $("#save_card_checkbox").show();
            } else {
                $("#save_card_checkbox").hide();
            }
            card.on("change", function (event) {
                // Disable the Pay button if there are no card details in the Element
                document.querySelector("#submit_stripe").disabled = event.empty;
                document.querySelector("#card-error").textContent = event.error ? event.error.message : "";
            });
            card.on('focus', function(event) {
                $("#save_card_checkbox").show();
                document.querySelector('#new-card-radio').checked = true;
                document.querySelector("#submit_stripe").disabled = true;
            });
        }
    }
    $(document).ready(function(){        
        if(stripe_info!=''){
            var form = document.getElementById("form_user_details");
            form.addEventListener("submit", function(event) {
                event.preventDefault();
                
                var radiopaymnetValue = $("input[name='payment-source-btn']:checked").val();
                var save_card_checkbox_val = $("input[name='save_card_checkbox_val']:checked").val();                               
                if(radiopaymnetValue=='newcard')
                {
                    loading(true);
                    //create intent and make payment.
                    fetch(BASEURL+"myprofile/createintent_fordrivertip", {
                        method: "POST",
                        dataType : "html",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        //body: JSON.stringify(intent_data)
                    }).then(function(result) {
                            return result.json();
                    }).then(function(data) {
                        if(data.error){
                            // Show error to your customer
                            showError(data.error);
                        } else {
                            payWithCard(stripe, card, data.clientSecret,data.stripecus_id,data.is_savecard,save_card_checkbox_val);
                        }
                    });
                }
                else
                {
                    loading(true);
                    var element_radio = $("input[name='payment-source-btn']:checked");
                    var radio_paymentmethodid = element_radio.attr("paymentmethodid");

                    var savecard_intentcrt = {
                        payment_method : radio_paymentmethodid,
                    };

                    fetch(BASEURL+"myprofile/create_paymentwithcard", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(savecard_intentcrt)
                    }).then(function(pn_result) {
                        return pn_result.json();
                    }).then(function(pn_data) {
                        if(pn_data.error) {
                            // Show error to your customer
                            showError(pn_data.error);
                        }
                        else if (pn_data.paymentconfirm_status =='requires_action') {
                            // Use Stripe.js to handle required card action
                            stripe.confirmCardPayment(
                            pn_data.clientSecret
                            ).then(function(resulthand) {
                              if (resulthand.error) {
                                // Show `result.error.message` in payment form
                                location.reload();
                                loading(false);                                
                                //document.querySelector("#submit_stripe").disabled = true;
                              }
                              else
                              {
                                    var payment_option =$('#payment_option_val').val(); 
                                    // The payment succeeded!
                                    var str_paymentIntentid = pn_data.paymentIntentid;
                                    var tip_order_id = $('#tip_order_id').val();
                                    var tip_amount = $('#driver_tip').val();
                                    var updateordersummary_data = {
                                        tip_order_id_inp : tip_order_id,
                                        payment_option : payment_option,
                                        tip_amount_inp : tip_amount,
                                        tip_transaction_id : str_paymentIntentid,
                                    };
                                        
                                    fetch(BASEURL+"myprofile/updateOrderSummary", {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json"
                                        },
                                        body: JSON.stringify(updateordersummary_data)
                                    }).then(function(sresult) {
                                        return sresult.json();
                                    }).then(function(sdata) {
                                        if(sdata.status == 'success'){
                                            loading(false);
                                            $('#drivertip_successmsg').html("<?php echo $this->lang->line('drivertip_successmsg'); ?>");
                                            $('#drivertip_successmsg').removeClass('display-no');
                                            setTimeout(function() {
                                                card.clear();
                                                applyTipForOrders('clear');
                                            }, 2000);
                                            setTimeout(function() {
                                                $('#driver-tip').modal('hide');
                                                window.location.href = BASEURL+"myprofile";
                                            }, 3000);                                        
                                        }else if(sdata.error=='' && sdata.error_message==''){    
                                            var refundbox = bootbox.alert({
                                            message: "<?php echo $this->lang->line('refund_err_frontmssg'); ?>",
                                                buttons: {
                                                    ok: {
                                                        label: "<?php echo $this->lang->line('ok'); ?>",
                                                    }
                                                },
                                                callback: function () {
                                                    card.clear();
                                                    applyTipForOrders('clear');
                                                    $('#driver-tip').modal('hide');
                                                    window.location.href = BASEURL+"myprofile";
                                                }
                                            });
                                            setTimeout(function() {
                                                refundbox.modal('hide');
                                                card.clear();
                                                applyTipForOrders('clear');
                                                $('#driver-tip').modal('hide');
                                                window.location.href = BASEURL+"myprofile";
                                            }, 10000);
                                        } else{
                                            var refundbox = bootbox.alert({
                                            message: "<?php echo $this->lang->line('refund_err_mssg'); ?>",
                                                buttons: {
                                                    ok: {
                                                        label: "<?php echo $this->lang->line('ok'); ?>",
                                                    }
                                                },
                                                callback: function () {
                                                    card.clear();
                                                    applyTipForOrders('clear');
                                                    $('#driver-tip').modal('hide');
                                                    window.location.href = BASEURL+"myprofile";
                                                }
                                            });
                                            setTimeout(function() {
                                                refundbox.modal('hide');
                                                card.clear();
                                                applyTipForOrders('clear');
                                                $('#driver-tip').modal('hide');
                                                window.location.href = BASEURL+"myprofile";
                                            }, 10000);
                                        }
                                    });
                              } 

                            });
                        }
                        else {
                            // The payment succeeded!
                            var payment_option =$('#payment_option_val').val(); 
                            var str_paymentIntentid = pn_data.paymentIntentid;
                            var tip_order_id = $('#tip_order_id').val();
                            var tip_amount = $('#driver_tip').val();
                            var updateordersummary_data = {
                                tip_order_id_inp : tip_order_id,
                                payment_option : payment_option,
                                tip_amount_inp : tip_amount,
                                tip_transaction_id : str_paymentIntentid,
                            };
                                
                            fetch(BASEURL+"myprofile/updateOrderSummary", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify(updateordersummary_data)
                            }).then(function(sresult) {
                                return sresult.json();
                            }).then(function(sdata) {
                                if(sdata.status == 'success'){
                                    loading(false);
                                    $('#drivertip_successmsg').html("<?php echo $this->lang->line('drivertip_successmsg'); ?>");
                                    $('#drivertip_successmsg').removeClass('display-no');
                                    setTimeout(function() {
                                        card.clear();
                                        applyTipForOrders('clear');
                                    }, 2000);
                                    setTimeout(function() {
                                        $('#driver-tip').modal('hide');
                                        window.location.href = BASEURL+"myprofile";
                                    }, 3000);
                                }else if(sdata.error=='' && sdata.error_message==''){
                                    var refundbox = bootbox.alert({
                                    message: "<?php echo $this->lang->line('refund_err_frontmssg'); ?>",
                                        buttons: {
                                            ok: {
                                                label: "<?php echo $this->lang->line('ok'); ?>",
                                            }
                                        },
                                        callback: function () {
                                            card.clear();
                                            applyTipForOrders('clear');
                                            $('#driver-tip').modal('hide');
                                            window.location.href = BASEURL+"myprofile";
                                        }
                                    });
                                    setTimeout(function() {
                                        refundbox.modal('hide');
                                        card.clear();
                                        applyTipForOrders('clear');
                                        $('#driver-tip').modal('hide');
                                        window.location.href = BASEURL+"myprofile";
                                    }, 10000);
                                } else{
                                    var refundbox = bootbox.alert({
                                    message: "<?php echo $this->lang->line('refund_err_mssg'); ?>",
                                        buttons: {
                                            ok: {
                                                label: "<?php echo $this->lang->line('ok'); ?>",
                                            }
                                        },
                                        callback: function () {
                                            card.clear();
                                            applyTipForOrders('clear');
                                            $('#driver-tip').modal('hide');
                                            window.location.href = BASEURL+"myprofile";
                                        }
                                    });
                                    setTimeout(function() {
                                        refundbox.modal('hide');
                                        card.clear();
                                        applyTipForOrders('clear');
                                        $('#driver-tip').modal('hide');
                                        window.location.href = BASEURL+"myprofile";
                                    }, 10000);
                                }
                            });
                        }
                    });
                }
            });
            // Calls stripe.confirmCardPayment
            // If the card requires authentication Stripe shows a pop-up modal to
            // prompt the user to enter authentication details without leaving your page.
            var payWithCard = function(stripe, card, clientSecret,stripecus_id,is_savecard,save_card_checkbox_val) {
                loading(true);
                stripe
                    .confirmCardPayment(clientSecret, {
                        payment_method: {
                            card: card
                        }
                    })
                    .then(function(result) {
                        if (result.error) {
                            // Show error to your customer
                            showError(result.error.message);
                        }
                        else
                        {
                            var payment_option =$('#payment_option_val').val(); 
                            // The payment succeeded!
                            var str_paymentIntentid = result.paymentIntent.id;
                            var tip_order_id = $('#tip_order_id').val();
                            var tip_amount = $('#driver_tip').val();
                            var updateordersummary_data = {
                                tip_order_id_inp : tip_order_id,
                                payment_option : payment_option,
                                tip_amount_inp : tip_amount,
                                tip_transaction_id : str_paymentIntentid,
                            };
                            
                            fetch(BASEURL+"myprofile/updateOrderSummary", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify(updateordersummary_data)
                            }).then(function(sresult) {
                                return sresult.json();
                            }).then(function(sdata) {
                                if(sdata.status == 'success'){
                                    //save card
                                    var loggedin_usertype = '<?php echo $this->session->userdata('UserType') ?>';
                                    if(loggedin_usertype == 'User' && is_savecard=='yes' && (save_card_checkbox_val=='yes' && save_card_checkbox_val != undefined)) {
                                        save_carddetail(stripecus_id,result.paymentIntent.payment_method);
                                    } else {
                                        loading(false);
                                        $('#drivertip_successmsg').html("<?php echo $this->lang->line('drivertip_successmsg'); ?>");
                                        $('#drivertip_successmsg').removeClass('display-no');
                                        setTimeout(function() {
                                            card.clear();
                                            applyTipForOrders('clear');
                                        }, 2000);
                                        setTimeout(function() {
                                            $('#driver-tip').modal('hide');
                                            window.location.href = BASEURL+"myprofile";
                                        }, 3000);
                                    }
                                } else if(sdata.error=='' && sdata.error_message==''){
                                    var refundbox = bootbox.alert({
                                    message: "<?php echo $this->lang->line('refund_err_frontmssg'); ?>",
                                        buttons: {
                                            ok: {
                                                label: "<?php echo $this->lang->line('ok'); ?>",
                                            }
                                        },
                                        callback: function () {
                                            card.clear();
                                            applyTipForOrders('clear');
                                            $('#driver-tip').modal('hide');
                                            window.location.href = BASEURL+"myprofile";
                                        }
                                    });
                                    setTimeout(function() {
                                        refundbox.modal('hide');
                                        card.clear();
                                        applyTipForOrders('clear');
                                        $('#driver-tip').modal('hide');
                                        window.location.href = BASEURL+"myprofile";
                                    }, 10000);
                                }else{
                                    var refundbox = bootbox.alert({
                                    message: "<?php echo $this->lang->line('refund_err_mssg'); ?>",
                                        buttons: {
                                            ok: {
                                                label: "<?php echo $this->lang->line('ok'); ?>",
                                            }
                                        },
                                        callback: function () {
                                            card.clear();
                                            applyTipForOrders('clear');
                                            $('#driver-tip').modal('hide');
                                            window.location.href = BASEURL+"myprofile";
                                        }
                                    });
                                    setTimeout(function() {
                                        refundbox.modal('hide');
                                        card.clear();
                                        applyTipForOrders('clear');
                                        $('#driver-tip').modal('hide');
                                        window.location.href = BASEURL+"myprofile";
                                    }, 10000);
                                }
                            });
                        }
                    });
            };
            var save_carddetail = function(stripecus_id,payment_method_id) {
                var savecard_data = {
                    stripecus_id : stripecus_id,
                    payment_method : payment_method_id,
                };
                
                fetch(BASEURL+"myprofile/save_carddetail", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    //use for subtotal value :: Not in use
                    body: JSON.stringify(savecard_data)
                }).then(function(sresult) {
                        return sresult.json();
                }).then(function(sdata) {
                    if(sdata.error != '' && sdata.error != undefined){
                        document.querySelector("#card-error").textContent = sdata.error ? sdata.error : "";
                        setTimeout(function() {
                            document.querySelector("#card-error").textContent = "";
                            loading(false);
                            $('#drivertip_successmsg').html("<?php echo $this->lang->line('drivertip_successmsg'); ?>");
                            $('#drivertip_successmsg').removeClass('display-no');
                        }, 4000);
                        setTimeout(function() {
                            card.clear();
                            applyTipForOrders('clear');
                        }, 6000);
                        setTimeout(function() {
                            $('#driver-tip').modal('hide');
                            window.location.href = BASEURL+"myprofile";
                        }, 7000);
                    } else {
                        $('#drivertip_successmsg').html("<?php echo $this->lang->line('drivertip_successmsg'); ?>");
                        $('#drivertip_successmsg').removeClass('display-no');
                        setTimeout(function() {
                            card.clear();
                            applyTipForOrders('clear');
                        }, 2000);
                        setTimeout(function() {
                            $('#driver-tip').modal('hide');
                            window.location.href = BASEURL+"myprofile";
                        }, 3000);
                    }
                });
            };
            var showError = function(errorMsgText) {
                loading(false);
                var errorMsg = document.querySelector("#card-error");
                errorMsg.textContent = errorMsgText;
                setTimeout(function() {
                    errorMsg.textContent = "";
                }, 4000);
            };
            // Show a spinner on payment submission
            var loading = function(isLoading) {
                if (isLoading) {
                    // Disable the button and show a spinner
                    document.querySelector("#submit_stripe").disabled = true;
                    document.querySelector("#spinner").classList.remove("hidden");
                    document.querySelector("#button-text").classList.add("hidden");
                } else {
                    document.querySelector("#submit_stripe").disabled = false;
                    document.querySelector("#spinner").classList.add("hidden");
                    document.querySelector("#button-text").classList.remove("hidden");
                }
            };
        }
    });
    function togglecardbutton(radiovalue) {
        if(radiovalue == "newcard") {
            $("#submit_stripe").prop("disabled",true);
            $("#save_card_checkbox").show();
        } else {
            card.clear();
            $("#submit_stripe").prop("disabled",false);
            $("#save_card_checkbox").hide();
        }
    }
    function backToSelectTip() {
        $('#tip_submit_btn').attr('disabled',false);
        $("#tip_clear_btn").attr("disabled", false);

        $('#driver-tip-form').removeClass('d-none');
        $('.stripediv').addClass('d-none');
    }
</script>
<?php //wallet topup changes :: start ?>
<script type="text/javascript">
    var card_topup = '';
    function mount_stripe_element_for_wallettopup() {
        if(stripe_info!=''){
            var elements = stripe.elements();
            var style = {
                base: {
                    color: "#32325d",
                    fontFamily: 'Arial, sans-serif',
                    fontSmoothing: "antialiased",
                    fontSize: "16px",
                    "::placeholder": {
                        color: "#32325d"
                    }
                },
                invalid: {
                    fontFamily: 'Arial, sans-serif',
                    color: "#fa755a",
                    iconColor: "#fa755a"
                }
            };
            card_topup = elements.create("card", { hidePostalCode: false,style: style });
            // Stripe injects an iframe into the DOM
            card_topup.mount("#card-element-topup");
            if($("input[name='payment-source-btn-forwallet']:checked").val() == 'newcard' || $("input[name='payment-source-btn-forwallet']:checked").val() == undefined){
                $("#save_card_checkbox_forwallet").show();
            } else {
                $("#save_card_checkbox_forwallet").hide();
            }
            card_topup.on("change", function (event) {
                // Disable the Pay button if there are no card details in the Element
                document.querySelector("#submit_stripe_forwallet").disabled = event.empty;
                document.querySelector("#card-error-forwallet").textContent = event.error ? event.error.message : "";
            });
            card_topup.on('focus', function(event) {
                $("#save_card_checkbox_forwallet").show();
                document.querySelector('#new-card-radio-forwallet').checked = true;
                document.querySelector("#submit_stripe_forwallet").disabled = true;
            });
        }
    }

    $(document).ready(function() { 
        if(stripe_info!='') {
            var form = document.getElementById("form_wallet_topup");
            form.addEventListener("submit", function(event) {
                event.preventDefault();
                $("#form_wallet_topup").validate();
                if (!$("#form_wallet_topup").valid()) { 
                    return false;
                } else {
                    var radiopayment_value_forwallet = $("input[name='payment-source-btn-forwallet']:checked").val();
                    var save_card_checkbox_val_forwallet = $("input[name='save_card_checkbox_val_forwallet']:checked").val();
                    var topup_amount = $('#topup_amount').val();
                    if(topup_amount > 0) {
                        $("#topup_amount_err").html('');
                        $("#topup_amount_err").hide();
                        $('#submit_stripe_forwallet').attr('disabled',false);
                        if(radiopayment_value_forwallet == 'newcard') {
                            loading_forwallet(true);
                            var intent_data = {
                                intent_for : 'wallet_topup',
                                topup_amount : topup_amount,
                            };
                            //create intent and make payment.
                            fetch(BASEURL+"myprofile/createintent_fordrivertip", {
                                method: "POST",
                                dataType : "html",
                                headers: {
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify(intent_data)
                            }).then(function(result) {
                                    return result.json();
                            }).then(function(data) {
                                if(data.error){
                                    // Show error to your customer
                                    showErrorForWallet(data.error);
                                } else {
                                    payWithCardForWallet(stripe, card_topup, data.clientSecret,data.stripecus_id,data.is_savecard,save_card_checkbox_val_forwallet,topup_amount);
                                }
                            });
                        }
                        else
                        {
                            loading_forwallet(true);
                            var element_radio = $("input[name='payment-source-btn-forwallet']:checked");
                            var radio_paymentmethodid = element_radio.attr("paymentmethodid");

                            var savecard_intentcrt = {
                                payment_for : 'wallet_topup',
                                topup_amount : topup_amount,
                                payment_method : radio_paymentmethodid,
                            };

                            fetch(BASEURL+"myprofile/create_paymentwithcard", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify(savecard_intentcrt)
                            }).then(function(pn_result) {
                                return pn_result.json();
                            }).then(function(pn_data) {
                                if(pn_data.error) {
                                    // Show error to your customer
                                    showErrorForWallet(pn_data.error);
                                }
                                else if (pn_data.paymentconfirm_status =='requires_action') {
                                    // Use Stripe.js to handle required card action
                                    stripe.confirmCardPayment(
                                    pn_data.clientSecret
                                    ).then(function(resulthand) {
                                      if (resulthand.error) {
                                        // Show `result.error.message` in payment form
                                        location.reload();
                                        loading_forwallet(false);                                
                                        //document.querySelector("#submit_stripe").disabled = true;
                                      } else {
                                            // The payment succeeded!
                                            var str_paymentIntentid = pn_data.paymentIntentid;
                                            var updatewallethistory_data = {
                                                topup_amount : topup_amount,
                                                wallet_transaction_id : str_paymentIntentid,
                                            };
                                            fetch(BASEURL+"myprofile/updateWalletHistory", {
                                                method: "POST",
                                                headers: {
                                                    "Content-Type": "application/json"
                                                },
                                                body: JSON.stringify(updatewallethistory_data)
                                            }).then(function(sresult) {
                                                return sresult.json();
                                            }).then(function(sdata) {
                                                if(sdata.status == 'success') {
                                                    loading_forwallet(false);
                                                    $('#wallet_topup_successmsg').html("<?php echo $this->lang->line('wallet_topup_successmsg'); ?>");
                                                    $('#wallet_topup_successmsg').removeClass('display-no');
                                                    setTimeout(function() {
                                                        card_topup.clear();
                                                    }, 2000);
                                                    setTimeout(function() {
                                                        $('#add-wallet-money').modal('hide');
                                                        window.location.href = BASEURL+"myprofile";
                                                    }, 3000);
                                                }
                                            });
                                      } 

                                    });
                                }
                                else {
                                    // The payment succeeded!
                                    var str_paymentIntentid = pn_data.paymentIntentid;
                                    var updatewallethistory_data = {
                                        topup_amount : topup_amount,
                                        wallet_transaction_id : str_paymentIntentid,
                                    };
                                        
                                    fetch(BASEURL+"myprofile/updateWalletHistory", {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json"
                                        },
                                        body: JSON.stringify(updatewallethistory_data)
                                    }).then(function(sresult) {
                                        return sresult.json();
                                    }).then(function(sdata) {
                                        if(sdata.status == 'success') {
                                            loading_forwallet(false);
                                            $('#wallet_topup_successmsg').html("<?php echo $this->lang->line('wallet_topup_successmsg'); ?>");
                                            $('#wallet_topup_successmsg').removeClass('display-no');
                                            setTimeout(function() {
                                                card_topup.clear();
                                            }, 2000);
                                            setTimeout(function() {
                                                $('#add-wallet-money').modal('hide');
                                                window.location.href = BASEURL+"myprofile";
                                            }, 3000);
                                        }
                                    });
                                }
                            });
                        }
                    } else {
                        $("#topup_amount_err").html("<?php echo $this->lang->line('topup_greaterthan_zero'); ?>");
                        $("#topup_amount_err").show();
                        $('#submit_stripe_forwallet').attr('disabled',true);
                    }
                }
            });
            // Calls stripe.confirmCardPayment
            // If the card requires authentication Stripe shows a pop-up modal to
            // prompt the user to enter authentication details without leaving your page.
            var payWithCardForWallet = function(stripe, card_topup, clientSecret,stripecus_id,is_savecard,save_card_checkbox_val_forwallet,topup_amount) {
                loading_forwallet(true);
                stripe
                    .confirmCardPayment(clientSecret, {
                        payment_method: {
                            card: card_topup
                        }
                    })
                    .then(function(result) {
                        if (result.error) {
                            // Show error to your customer
                            showErrorForWallet(result.error.message);
                        }
                        else
                        {
                            // The payment succeeded!
                            var str_paymentIntentid = result.paymentIntent.id;
                            var updatewallethistory_data = {
                                topup_amount : topup_amount,
                                wallet_transaction_id : str_paymentIntentid,
                            };
                            
                            fetch(BASEURL+"myprofile/updateWalletHistory", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify(updatewallethistory_data)
                            }).then(function(sresult) {
                                return sresult.json();
                            }).then(function(sdata) {
                                if(sdata.status == 'success'){
                                    //save card
                                    var loggedin_usertype = '<?php echo $this->session->userdata('UserType') ?>';
                                    if(loggedin_usertype == 'User' && is_savecard=='yes' && (save_card_checkbox_val_forwallet == 'yes' && save_card_checkbox_val_forwallet != undefined)) {
                                        save_carddetail_fromwallettopup(stripecus_id,result.paymentIntent.payment_method);
                                    } else {
                                        loading_forwallet(false);
                                        $('#wallet_topup_successmsg').html("<?php echo $this->lang->line('wallet_topup_successmsg'); ?>");
                                        $('#wallet_topup_successmsg').removeClass('display-no');
                                        setTimeout(function() {
                                            card_topup.clear();
                                        }, 2000);
                                        setTimeout(function() {
                                            $('#add-wallet-money').modal('hide');
                                            window.location.href = BASEURL+"myprofile";
                                        }, 3000);
                                    }
                                }
                            });
                        }
                    });
            };
            var save_carddetail_fromwallettopup = function(stripecus_id,payment_method_id) {
                var savecard_data = {
                    stripecus_id : stripecus_id,
                    payment_method : payment_method_id,
                };
                
                fetch(BASEURL+"myprofile/save_carddetail", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    //use for subtotal value :: Not in use
                    body: JSON.stringify(savecard_data)
                }).then(function(sresult) {
                        return sresult.json();
                }).then(function(sdata) {
                    if(sdata.error != '' && sdata.error != undefined){
                        document.querySelector("#card-error-forwallet").textContent = sdata.error ? sdata.error : "";
                        setTimeout(function() {
                            document.querySelector("#card-error-forwallet").textContent = "";
                            loading_forwallet(false);
                            $('#wallet_topup_successmsg').html("<?php echo $this->lang->line('wallet_topup_successmsg'); ?>");
                            $('#wallet_topup_successmsg').removeClass('display-no');
                        }, 4000);
                        setTimeout(function() {
                            card_topup.clear();
                        }, 6000);
                        setTimeout(function() {
                            $('#add-wallet-money').modal('hide');
                            window.location.href = BASEURL+"myprofile";
                        }, 7000);
                    } else {
                        $('#wallet_topup_successmsg').html("<?php echo $this->lang->line('wallet_topup_successmsg'); ?>");
                        $('#wallet_topup_successmsg').removeClass('display-no');
                        setTimeout(function() {
                            card_topup.clear();
                        }, 2000);
                        setTimeout(function() {
                            $('#add-wallet-money').modal('hide');
                            window.location.href = BASEURL+"myprofile";
                        }, 3000);
                    }
                });
            };
            var showErrorForWallet = function(errorMsgText) {
                loading_forwallet(false);
                var errorMsg = document.querySelector("#card-error-forwallet");
                errorMsg.textContent = errorMsgText;
                setTimeout(function() {
                    errorMsg.textContent = "";
                }, 4000);
            };
            // Show a spinner on payment submission
            var loading_forwallet = function(isLoading) {
                if (isLoading) {
                    // Disable the button and show a spinner
                    document.querySelector("#submit_stripe_forwallet").disabled = true;
                    document.querySelector("#spinner_topup").classList.remove("hidden");
                    document.querySelector("#button-text-wallet").classList.add("hidden");
                } else {
                    document.querySelector("#submit_stripe_forwallet").disabled = false;
                    document.querySelector("#spinner_topup").classList.add("hidden");
                    document.querySelector("#button-text-wallet").classList.remove("hidden");
                }
            };
        }
    });
    function togglecardbutton_forwallet(radiovalue) {
        if(radiovalue == "newcard") {
            $("#submit_stripe_forwallet").prop("disabled",true);
            $("#save_card_checkbox_forwallet").show();
        } else {
            card_topup.clear();
            $("#submit_stripe_forwallet").prop("disabled",false);
            $("#save_card_checkbox_forwallet").hide();
        }
    }
    jQuery("#form_wallet_topup").validate({  
      rules: {    
        topup_amount: {
          required: true,
          number: true,
          twodecimalpoints:true
        }
      }  
    });
    $('#topup_amount').on('input', function() {
        var topup_amount_val = $('#topup_amount').val();        
        if(topup_amount_val != '') {
            if(topup_amount_val > 0) {
                $("#topup_amount_err").html("");
                $("#topup_amount_err").hide();
                $('#submit_stripe_forwallet').attr('disabled',false);
            } else {
                $("#topup_amount_err").html("<?php echo $this->lang->line('topup_greaterthan_zero'); ?>");
                $("#topup_amount_err").show();
                $('#submit_stripe_forwallet').attr('disabled',true);
            }
        } else {
            $("#topup_amount_err").html("");
            $("#topup_amount_err").hide();
            $('#submit_stripe_forwallet').attr('disabled',false);
        }
    });
    jQuery.validator.addMethod("twodecimalpoints", function(value, element, params) {
        // Convert to String
        const numStr = String(value);
        var no_of_decimal_points = 0;
        // String Contains Decimal
        if (numStr.includes('.')) {
            no_of_decimal_points = numStr.split('.')[1].length;
        }
        if(no_of_decimal_points > 2) {
            return false;
        } else {
            return true;
        }
    }, custom_tip_decimal_error);

</script>
<?php //wallet topup changes :: end ?>
<script type="text/javascript">
    $(document).ready(function(){
        var book_url = window.location.href;
        book_url = book_url.substring(book_url.lastIndexOf('/') + 1).substring(1);
        if(book_url=='bookmarks'){
            $('#tab_bookmark a').click();    
        }
        $("#bookmark_head").click(function(){
            event.preventDefault();
            $('#tab_bookmark a').click();
        });
        /*if(performance.navigation.type == performance.navigation.TYPE_RELOAD) {
            window.location = BASEURL+'myprofile';
        }*/
    });
</script>
<?php $this->load->view('footer'); ?>