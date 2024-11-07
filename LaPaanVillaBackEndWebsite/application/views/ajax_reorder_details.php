<div class="modal-dialog modal-detail modal-dialog-centered modal-lg">
    <div class="modal-content p-4 p-xl-8">
        <a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close">
            <img src="<?php base_url() ?>/assets/front/images/icon-close.svg" alt="">
        </a>

        <h2 class="text-capitalize title text-center pb-2 mb-4 mb-xl-6"><?php echo $this->lang->line('order_details') ?></h2>

        <div class="item-package border p-2 d-flex flex-sm-row flex-column align-items-sm-center mb-4 mb-xl-8">
            <figure class="picture mb-sm-0 mb-2">
                <?php $image = (file_exists(FCPATH.'uploads/'.$order_details[0]['restaurant_image']) && $order_details[0]['restaurant_image']!='')?(image_url.$order_details[0]['restaurant_image']):(default_icon_img); ?>
                        <img src="<?php echo $image;?>">  
            </figure>
            <div class="flex-fill">
                <div class="d-inline-block">
                    <?php $rating_txt = ($order_details[0]['restaurant_reviews_count'] > 1)?$this->lang->line('ratings'):$this->lang->line('rating'); ?>
                    <?php echo ($order_details[0]['ratings'] > 0)?'<div class="small text-white bg-success  d-flex align-items-center px-2"><i class="icon mt-0"><img src="'. base_url() .'/assets/front/images/icon-star.svg" alt="clock"></i>'.$order_details[0]['ratings'].' ('.$order_details[0]['restaurant_reviews_count'].' '.strtolower($rating_txt).')'.'</div>':'<div class="small text-white bg-success px-2">'. $this->lang->line("new") .'</div>'; ?>
                </div>
                <h6><?php echo $order_details[0]['restaurant_name'];?></h6>

                <small class="d-flex">
                    <i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-pin.svg"></i>
                    <?php echo $order_details[0]['restaurant_address']; ?>
                </small>
                <?php if($order_details[0]['timings']['closing'] == "Closed"){ ?>
                    <small class="text-danger"><?php echo $this->lang->line('closed'); ?></small>
                <?php } ?>
            </div>
        </div>
        
        <div class="mb-4">
            <h6><?php echo $this->lang->line('order_items') ?> <span class="text-primary">#<?php echo $this->lang->line('orderid') ?> - <?php echo $order_details[0]['order_id']; ?></span></h6>

            <div class="table-responsive small w-100 mb-4 mb-xl-8 mt-1">
                <table class="table table-track bg-white table-striped table-bordered table-hover w-100">
                    <?php $menuids = array();
                    if (!empty($order_details[0]['items'])) {
                        foreach ($order_details[0]['items'] as $key => $item_value) {
                            $is_veg = ($item_value['is_veg'] == 1)?'veg':'non-veg'; 
                            $menu_arr = array();
                            $menu_arr['menu_id'] = $item_value['menu_id'];
                            $menu_arr['menu_qty'] = $item_value['quantity'];
                            $menu_arr['comment'] = $item_value['comment'];
                            $menu_arr['is_addon'] = ($item_value['is_customize']==1)?'1':'0';
                            $menu_arr['addonValue'] = (!empty($item_value['addons_category_list']))?json_encode($item_value['addons_category_list']):'';
                            $menu_arr['itemTotal'] = $item_value['itemTotal'];
                            array_push($menuids, $menu_arr); ?>
                            <tr>
                                <td>
                                    <label><?php echo $item_value['name']; ?></label>
                                    <?php if (!empty($item_value['addons_category_list'])) {?>
                                        <div class="text-editor w-100">
                                            <ul>
                                                <?php foreach ($item_value['addons_category_list'] as $key => $cat_value) { ?>
                                                    <?php /* ?><li><h6><?php echo $cat_value['addons_category']; ?></h6></li><?php */ ?>
                                                    <?php if (!empty($cat_value['addons_list'])) {
                                                        foreach ($cat_value['addons_list'] as $add_key => $add_value) { ?>
                                                            <li><?php echo $add_value['add_ons_name']; ?>  <?php echo $order_details[0]['currency_symbol']; ?><?php echo $add_value['add_ons_price']; ?></li>
                                                        <?php }
                                                    } ?>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                    <?php
                                    if(!empty($item_value['comment'])){
                                        ?><p><strong><?php echo $this->lang->line('item_comment')?>:</strong> <?php echo $item_value['comment']; ?></p><?php
                                    }
                                    ?>
                                </td>
                                <td class="right-price" id="subtotal" value="<?php echo $order_details[0]['currency_symbol']; ?><?php echo $item_value['itemTotal']; ?>">
                                    <?php echo $order_details[0]['currency_symbol']; ?> <?php echo number_format_unchanged_precision($item_value['itemTotal']); ?>
                                </td>
                            </tr>
                        <?php } 
                    } ?>
                </table>
            </div>

            <?php $subtotal = 0;
            $delivery_charges = 0;
            $total = 0;
            $coupon_amount = 0;
            $tax_amount = 0;
            if (!empty($order_details[0]['price'])) {
                foreach ($order_details[0]['price'] as $pkey => $pvalue) {
                    if (!empty($pvalue['label_key']) && $pvalue['label_key'] == "Sub Total") {
                        $subtotal = $pvalue['value'];
                    }
                }
            } ?>
        </div>
        <form>
            <input type="hidden" name="restaurant_id" id="restaurant_id" value="<?php echo $order_details[0]['restaurant_id']; ?>">
            <input type="hidden" name="user_id" id="user_id" value="<?php echo ($this->session->userdata('UserID'))?$this->session->userdata('UserID'):''; ?>">
            
            <input type="hidden" name="subTotal_for_cal" id="subTotal_for_cal" value="<?php echo $subtotal; ?>">
            <input type="hidden" id="totalPrice" value="<?php echo $total; ?>">
        </form>
        <div class="d-flex justify-content-between border p-4 bg-body mb-4">
            <label><?php echo $this->lang->line('sub_total') ?></label>
            <label><?php echo $order_details[0]['currency_symbol']; ?> <?php echo $subtotal; ?></label>
        </div>
        <div class="continue-btn">
            <?php if($order_details[0]['restaurant_status'] != "1" || $order_details[0]['enable_hours'] != '1' || $order_details[0]['timings']['off'] == "close" ) { ?>
                <div class="alert alert-danger"><?php echo $this->lang->line('resto_not_accepting_orders'); ?></div>

            <?php } else {
                if($order_details[0]['timings']['closing'] != "Closed") { ?>
                    
                    <button class="btn btn-primary w-100 addtocart" id="addtocart" onclick="checkCartOnReorder(<?php echo $order_details[0]['restaurant_id']; ?>,<?php echo htmlspecialchars(json_encode($menuids)); ?>)"> <?php echo $this->lang->line('continue'); ?> </button>

                <?php } else { ?>
                    <div class="alert alert-danger"><?php echo $this->lang->line('restaurant_closemsg'); ?></div>
                <?php }
            } ?>
        </div>
    </div>
</div>
