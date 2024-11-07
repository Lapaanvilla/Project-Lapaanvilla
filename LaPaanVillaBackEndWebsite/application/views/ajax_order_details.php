<?php  $this->db->select('OptionValue');
$enable_review = $this->db->get_where('system_option',array('OptionSlug'=>'enable_review'))->first_row();
$show_restaurant_reviews = ($enable_review->OptionValue=='1')?1:0; ?>

<div class="modal-dialog modal-detail modal-dialog-centered modal-lg">
    <div class="modal-content p-4 p-xl-8">
        <a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close">
            <img src="<?php base_url() ?>/assets/front/images/icon-close.svg" alt="">
        </a>

        <h2 class="text-capitalize title text-center pb-2 mb-4 mb-xl-6"><?php echo $this->lang->line('order_details') ?></h2>

        <div class="item-package border p-2 d-flex flex-sm-row flex-column align-items-sm-center mb-4 mb-xl-8">
            <figure class="picture mb-sm-0 mb-2">
                <?php $image = (file_exists(FCPATH.'uploads/'.$order_details[0]['restaurant_image']) && $order_details[0]['restaurant_image']!='') ?  image_url. $order_details[0]['restaurant_image'] : default_icon_img; ?>
                <img src="<?php echo $image;?>">
            </figure>
            <div class="flex-fill">
                <?php $rating_txt = ($order_details[0]['restaurant_reviews_count'] > 1)?$this->lang->line('ratings'):$this->lang->line('rating'); ?>               
                <h6><?php echo $order_details[0]['restaurant_name']; ?></h6>
                <small class="d-flex">
                    <i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-pin.svg"></i>
                    <?php echo $order_details[0]['restaurant_address']; ?> </p>
                </small>
                <div class="d-inline-block mt-1">
                    <?php if($show_restaurant_reviews){ echo ($order_details[0]['ratings'] > 0)?'<div class="small text-white bg-success  d-flex align-items-center px-2"><i class="icon mt-0"><img src="'. base_url() .'/assets/front/images/icon-star.svg" alt="clock"></i>'.$order_details[0]['ratings'].' ('.$order_details[0]['restaurant_reviews_count'].' '.strtolower($rating_txt).')'.'</div>':'<div class="small text-white bg-success px-2">'. $this->lang->line("new") .'</div>'; } ?>
                </div>
            </div>
        </div>
        
        <h6><?php echo $this->lang->line('orderid') ?> : # <?php echo $order_details[0]['order_id']; ?></h6>
        <?php if($order_details[0]['scheduled_date'] && $order_details[0]['slot_open_time'] && $order_details[0]['slot_close_time']) { ?>
            <small><?php echo $this->lang->line('order_scheduled_for').$this->common_model->dateFormat($order_details[0]['scheduled_date']).' ('.$this->common_model->timeFormat($order_details[0]['slot_open_time']).' - '.$this->common_model->timeFormat($order_details[0]['slot_close_time']).' )'; ?></small>
        <?php } ?>
        <div class="table-responsive small w-100 mb-4 mb-xl-8 mt-1">
            <table class="table table-track bg-white table-striped table-bordered table-hover w-100">
                <tbody>                        
                    <?php if(($order_details[0]['order_status'] == 'cancel' && $order_details[0]['cancel_reason'] != '') || ($order_details[0]['order_status'] == 'rejected' && $order_details[0]['reject_reason'] != '')) { 
                        $reason = ($order_details[0]['order_status'] == 'rejected') ? $order_details[0]['reject_reason'] : $order_details[0]['cancel_reason'];

                        $order_status_txt = ($order_details[0]['order_status'] == "cancel") ? $this->lang->line('cancelled') : $this->lang->line($order_details[0]['order_status']); ?>
                        <tr>
                            <td><?php echo $this->lang->line('status');?></td>
                            <td><strong><?php echo $order_status_txt; ?></strong></td>
                        </tr>
                        <tr>
                            <td><?php echo ($order_details[0]['order_status'] == 'cancel')?$this->lang->line('cancel_reason'):(($order_details[0]['order_status'] == 'rejected')?$this->lang->line('reject_reason'):'');?></td>
                            <td><strong><?php echo $reason; ?></strong></td>
                        </tr>
                    <?php } ?>

                    <tr>
                        <td><?php echo $this->lang->line('order_mode') ?></td>
                        <td><strong><?php echo $this->lang->line($order_details[0]['delivery_flag']); ?></strong></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line('payment_method') ?></td>
                        <?php $payment_option_detail = $this->common_model->get_payment_method_detail(strtolower($order_details[0]['payment_method']));

                        $lang_slug = $this->session->userdata('language_slug');
                        $payment_method = '';
                        if($lang_slug == 'en'){
                            $payment_method = $payment_option_detail->display_name_en;
                        } else if($lang_slug == 'fr'){
                            $payment_method = $payment_option_detail->display_name_fr;
                        } else if($lang_slug == 'ar'){
                            $payment_method = $payment_option_detail->display_name_ar;
                        } ?>
                        <td><strong><?php echo $payment_method; ?></strong></td>
                    </tr>
                    <?php if(strtolower($order_details[0]['payment_method']) != 'cod') { ?>
                        <tr>
                            <td><?php echo $this->lang->line('transaction_id') ?></td>
                            <td><strong><?php echo ($order_details[0]['transaction_id'])?$order_details[0]['transaction_id']:'-'; ?> </strong></td>
                        </tr>
                    <?php } ?>
                    <?php if($order_details[0]['payment_status'] == 'unpaid' || $order_details[0]['payment_status']=='processing' || $order_details[0]['payment_status'] =='pending') { ?>
                        <tr>
                            <td><?php echo $this->lang->line('payment_status') ?></td>
                            <td><strong><?php echo $this->lang->line($order_details[0]['payment_status']); ?> </strong></td>
                        </tr>
                    <?php } ?>
                    <?php if($order_details[0]['refund_status'] != '' && $order_details[0]['transaction_id'] != '') { ?>
                        <tr>
                            <td><?php echo $this->lang->line('refund_status') ?></td>
                            <td><strong><?php echo ($order_details[0]['refund_status']) ? ucfirst($this->lang->line(str_replace(" ", "_", $order_details[0]['refund_status']))) : '-'; ?> </strong></td>
                        </tr>
                    <?php } ?>
                    <?php if($order_details[0]['tips_transaction_id']!='' && $order_details[0]['tips_refund_status'] != '') { ?>
                        <tr>
                            <td><?php echo $this->lang->line('tip_refund_status') ?></td>
                            <td><strong><?php echo ($order_details[0]['tips_refund_status']) ? ucfirst($this->lang->line(str_replace(" ", "_", $order_details[0]['tips_refund_status']))) : '-'; ?> </strong></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <?php if($this->session->userdata('UserType') == 'Agent') { ?>
            <div class="mb-4 mb-xl-8">
                <h6><?php echo $this->lang->line('customer_details') ?></h6>
                <table class="table table-track bg-white table-striped table-bordered table-hover w-100">
                    <tbody>
                        <tr>
                            <td><?php echo $this->lang->line('customer') ?></td>
                            <td><strong><?php echo ($order_details[0]['user_name'])?> </strong></td>
                        </tr>
                        <tr>
                            <td><?php echo $this->lang->line('phone_number') ?></td>
                            <td><strong>+<?php echo ($order_details[0]['user_mobile_number'])?> </strong></td>
                        </tr>
                        <?php if(!empty($order_details[0]['user_details']['email']) && $order_details[0]['user_details']['email'] != NULL) { ?>
                            <tr>
                                <td><?php echo $this->lang->line('email') ?></td>
                                <td><strong><?php echo ($order_details[0]['user_details']['email'])?> </strong></td>
                            </tr>
                        <?php  } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>

        <div class="mb-4 mb-xl-8">
            <h6><?php echo $this->lang->line('order_items') ?> <?php echo (count($order_details[0]['items']) && count($order_details[0]['items'])>0)?'('.count($order_details[0]['items']):'( '. 0; ?> <?php echo (count($order_details[0]['items']) && count($order_details[0]['items'])>0)?((count($order_details[0]['items'])>1)?$this->lang->line('items').')':$this->lang->line('item').')'):$this->lang->line('item').')'; ?></h6>
            
            <div class="table-responsive small w-100 mb-4 mb-xl-8 mt-1">
                <table class="table table-track bg-white table-striped table-bordered table-hover w-100">
                    <?php if (!empty($order_details[0]['items'])) {
                        foreach ($order_details[0]['items'] as $key => $item_value) {
                            $qty_text = '';
                            if($item_value['quantity']>1){
                               $qty_text = '(X '.$item_value['quantity'].')'; 
                            }
                            $is_veg = ($item_value['is_veg'] == 1)?'veg':'non-veg'; ?>
                            <tr>
                                <td>
                                    <label><?php echo $item_value['name']; ?> <?php echo $qty_text;?></label>
                                    <?php if (!empty($item_value['addons_category_list'])) {?>
                                        <div class="text-editor w-100">
                                            <ul>
                                                <?php foreach ($item_value['addons_category_list'] as $key => $cat_value) { ?>
                                                    <?php /* ?><li><h6><?php echo $cat_value['addons_category']; ?></h6></li><?php */ ?>
                                                    <?php if (!empty($cat_value['addons_list'])) {
                                                        foreach ($cat_value['addons_list'] as $key => $add_value) { ?>

                                                            <li><?php echo $add_value['add_ons_name']; ?>  <?php echo currency_symboldisplay(number_format($add_value['add_ons_price'],2),$order_details[0]['currency_symbol']); ?></li>
                                                        <?php }
                                                    } ?>
                                                <?php }?>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                    <?php
                                        if(!empty($item_value['comment'])){
                                        ?>
                                            <p><strong><?php echo $this->lang->line('item_comment')?>:</strong> <?php echo $item_value['comment']; ?></p><?php
                                        }
                                    ?>
                                </td>
                                <td><?php echo currency_symboldisplay(number_format($item_value['itemTotal'],2),$order_details[0]['currency_symbol']); ?></td>
                            </tr>
                        <?php }
                    } ?>
                </table>
            </div>
        </div>

        <?php $subtotal = 0;
        $delivery_charges = 0;
        $total = 0;
        $coupon_amount = 0;
        $tax_amount = 0;
        if (!empty($order_details[0]['price'])) {
            foreach ($order_details[0]['price'] as $pkey => $pvalue) {
                if (isset($pvalue['label_key']) && $pvalue['label_key'] == "Sub Total") {
                    $subtotal = $pvalue['value'];
                }
                if (isset($pvalue['label_key']) && $pvalue['label_key'] == "Delivery Charge") {
                    $delivery_charges = $pvalue['value'];
                }
                if (isset($pvalue['label_key']) && $pvalue['label_key'] == "Coupon Amount") {
                    $coupon_amount = $pvalue['value'];
                }
                if (isset($pvalue['label_key']) && $pvalue['label_key'] == "Service Tax") {
                    $tax_amount = $pvalue['value'];
                }
                if (isset($pvalue['label_key']) && $pvalue['label_key'] == "Service Fee") {
                    $service_fee = $pvalue['value'];
                }
                if (isset($pvalue['label_key']) && $pvalue['label_key'] == "Credit Card Fee") {
                    $creditcard_fee = $pvalue['value'];
                }
                if (isset($pvalue['label_key']) && $pvalue['label_key'] == "Wallet Discount") {
                    $used_earning = $pvalue['value'];
                }
                if (isset($pvalue['label_key']) && $pvalue['label_key'] == "Driver Tip") {
                    $driver_tip = $pvalue['value'];
                }
                if (isset($pvalue['label_key']) && $pvalue['label_key'] == "Total") {
                    $total = $pvalue['value'];
                }
            }
        } ?>

        <div class="small w-100 mb-4 mb-xl-8 mt-1">
            <table class="table table-track bg-white table-striped table-bordered table-hover w-100">
                <tbody>
                    <tr>
                        <td><?php echo $this->lang->line('sub_total') ?></td>
                        <td><strong><?php echo currency_symboldisplay($subtotal,$order_details[0]['currency_symbol']); ?></strong></td>
                    </tr>
                    <!-- service tax start -->
                    <?php $taxes_fees = 0;
                    if($tax_amount != '' && $tax_amount != NULL && $tax_amount > 0) { /* ?>
                        <tr>
                            <td><?php echo $this->lang->line('service_tax'); ?> <?php echo ($order_details[0]['tax_type']=="Percentage")?'('.(($tax_amount)?$tax_amount:0).')':''; ?> <?php //echo ($tax_amount)?$tax_amount:0; ?></td>
                            <td>
                            <?php */
                            $tax_amountdis = 0;
                            if($order_details[0]['tax_type'] == 'Percentage'){
                                $tax_amountdis = ($subtotal * $tax_amount) / 100;
                            }else{
                                $tax_amountdis = $tax_amount; 
                            }
                            //$tax_amountdis = number_format($tax_amountdis, 2, '.', '');
                            /*$tax_amountdis = round($tax_amountdis,2);*/
                            $tax_amountdis = number_format(round($tax_amountdis,2),2);
                            $taxes_fees = $taxes_fees + $tax_amountdis; /* ?>
                            <strong>+<?php echo currency_symboldisplay($tax_amountdis,$order_details[0]['currency_symbol']);?></strong></td>
                        </tr>
                    <?php */ } ?>
                    <!-- service tax end -->
                    <!-- service fee start -->
                    <?php if(!empty($service_fee) && !is_null($service_fee) && $service_fee > 0) { /* ?>
                    <tr>
                        <td><?php echo $this->lang->line('service_fee'); ?> <?php echo ($order_details[0]['service_fee_type']=="Percentage")?'('.(($service_fee)?$service_fee:0).')':''; ?></td>
                        <td>
                        <?php */
                        $service_feedis = 0;
                        if($order_details[0]['service_fee_type'] == 'Percentage'){
                            $service_feedis = ($subtotal * $service_fee) / 100;
                        }else{
                            $service_feedis = $service_fee; 
                        }
                        /*$service_feedis = round($service_feedis,2);*/
                        $service_feedis = number_format(round($service_feedis,2),2);
                        $taxes_fees = $taxes_fees + $service_feedis; /* ?>
                        <strong>+<?php echo currency_symboldisplay($service_feedis,$order_details[0]['currency_symbol']);?></strong></td>
                    </tr>
                    <?php */ } ?>
                    <!-- service fee end -->

                    <!-- creditcard fee start -->
                    <?php if(!empty($creditcard_fee) && !is_null($creditcard_fee) && $creditcard_fee > 0) { /* ?>
                    <tr>
                        <td><?php echo $this->lang->line('creditcard_fee'); ?> <?php echo ($order_details[0]['creditcard_fee_type']=="Percentage")?'('.(($creditcard_fee)?$creditcard_fee:0).')':''; ?></td>
                        <td>
                        <?php */
                        $creditcard_feedis = 0;
                        if($order_details[0]['creditcard_fee_type'] == 'Percentage'){
                            $creditcard_feedis = ($subtotal * $creditcard_fee) / 100;
                        }else{
                            $creditcard_feedis = $creditcard_fee; 
                        }                        
                        $creditcard_feedis = number_format(round($creditcard_feedis,2),2);
                        $taxes_fees = $taxes_fees + $creditcard_feedis; /* ?>
                        <strong>+<?php echo currency_symboldisplay($creditcard_feedis,$order_details[0]['currency_symbol']);?></strong></td>
                    </tr>
                    <?php */ } ?>
                    <!-- creditcard fee end -->
                    <?php if ($order_details[0]['delivery_flag'] == "delivery") { 
                        if ($delivery_charges > 0) { ?>
                            <tr>
                                <td><?php echo $this->lang->line('delivery_charges') ?></td>
                                <td><strong>+<?php echo currency_symboldisplay($delivery_charges,$order_details[0]['currency_symbol']); ?></strong></td>
                            </tr> 
                            <?php
                        }
                    } ?>
                    <?php 
                    if($coupon_array && !empty($coupon_array)){
                        foreach ($coupon_array as $cp_key => $cp_value) { ?>
                        <tr>
                            <td><?php echo $this->lang->line('coupon_amount') ?>(<?php echo $cp_value['coupon_name']?>)</td>
                            <td><strong>-<?php echo currency_symboldisplay($cp_value['coupon_discount'],$order_details[0]['currency_symbol']); ?></strong></td>
                        </tr>    
                        <?php }
                    } 
                    else if ($coupon_amount > 0) { ?>
                        <tr>
                            <td><?php echo $this->lang->line('coupon_amount') ?></td>
                            <td><strong>-<?php echo currency_symboldisplay($coupon_amount,$order_details[0]['currency_symbol']); ?></strong></td>
                        </tr>
                    <?php } ?>
                    <?php if (isset($used_earning) && $used_earning > 0) { ?>
                        <tr>
                            <td><?php echo $this->lang->line('wallet_money_used_web') ?></td>
                            <td><strong>-<?php echo currency_symboldisplay($used_earning,$order_details[0]['currency_symbol']); ?></strong></td>
                        </tr>
                    <?php } ?>
                    <?php if (isset($driver_tip) && $driver_tip > 0) { 
                        $driver_tip = (float)round($driver_tip,2);
                        $tip_percentage = $order_details[0]['tip_percentage'];
                        $tip_percent_txt = ($tip_percentage)?' ('. $tip_percentage.'%)':''; ?>
                        <tr>
                            <td><?php echo $this->lang->line('driver_tip').$tip_percent_txt; ?></td>
                            <td><strong>+<?php echo currency_symboldisplay(number_format($driver_tip,2),$order_details[0]['currency_symbol']); ?></strong></td>
                        </tr>
                    <?php } ?>
                    <!-- taxes and fees :: start -->
                    <tr>
                        <td>
                            <?php echo $this->lang->line('taxes_fees'); ?>

                            <div class="custom-tooltip">
                                <i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-tooltip.svg" alt=""></i>
                                <div class="tooltip-text">
                                    <ul>
                                        <li id="servicetax_infodiv"><span class="custom_service"><?php echo $this->lang->line('service_tax'); ?> <span id="servicetaxtype_info"><?php echo ($order_details[0]['tax_type']=="Percentage")?'('.(($tax_amount)?$tax_amount:0).')':''; ?></span></span> : <span class="service_price" id="servicetax_info">&nbsp;<?php echo currency_symboldisplay($tax_amountdis,$order_details[0]['currency_symbol']); ?></span></li>

                                        <?php if($service_fee && $service_fee>0) { ?>
                                        <li id="servicefee_infodiv"><span class="custom_service"><?php echo $this->lang->line('service_fee'); ?> <span id="servicefeetype_info"><?php echo ($order_details[0]['service_fee_type']=="Percentage")?'('.(($service_fee)?$service_fee:0).')':''; ?></span></span> : <span class="service_price" id="servicefee_info">&nbsp;<?php echo currency_symboldisplay($service_feedis,$order_details[0]['currency_symbol']);?></span></li>
                                        <?php } ?>

                                        <?php if($creditcard_fee && $creditcard_fee>0) { ?>
                                        <li id="creditcardfee_infodiv"><span class="custom_service"><?php echo $this->lang->line('creditcard_fee'); ?> <span id="creditcardfeetype_info"><?php echo ($order_details[0]['creditcard_fee_type']=="Percentage")?'('.(($creditcard_fee)?$creditcard_fee:0).')':''; ?></span></span> : <span class="service_price" id="creditcardfee_info">&nbsp;<?php echo currency_symboldisplay($creditcard_feedis,$order_details[0]['currency_symbol']); ?></span></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </td>
                        <td><strong>+<?php echo currency_symboldisplay($taxes_fees,$order_details[0]['currency_symbol']);?></strong>
                        </td>
                    </tr>
                    <!-- taxes and fees :: end -->
                </tbody>
                <tfoot>
                    <tr>
                        <td><?php echo $this->lang->line('total_paid') ?></td>
                        <td><strong><?php echo currency_symboldisplay($total,$order_details[0]['currency_symbol']); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <?php if(!is_null($order_details[0]['extra_comment']) && !empty($order_details[0]['extra_comment']) && false) { ?>
            <div class="mb-4 mb-xl-8 d-flex flex-column">
                <h6><?php echo $this->lang->line('view_comment')?></h6>
                <small><?php echo $order_details[0]['extra_comment']; ?></small>
            </div>
        <?php } ?>
        <?php if(!is_null($order_details[0]['delivery_instructions']) && !empty($order_details[0]['delivery_instructions'])) { ?>
            <div class="mb-4 mb-xl-8 d-flex flex-column">
                <h6><?php echo $this->lang->line('delivery_instructions')?></h6>
                <small><?php echo $order_details[0]['delivery_instructions']; ?></small>
            </div>
        <?php } ?>
    </div>
</div>