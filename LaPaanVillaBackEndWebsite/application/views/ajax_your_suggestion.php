<?php if(!empty($menu_item_suggestion)){ ?>
    <div class="card-body container-gutter-xl py-0 px-xl-4">
        <h5 class="py-4"><?php echo $this->lang->line('people_also_like'); ?></h5>
        <div class="border-top py-4">
            <div class="row horizontal-image  row-grid">
                <?php foreach($menu_item_suggestion as $key => $value){
                    $addons = ($value['is_customize']==1)?'addons':''; 
                    $page = ($this->session->userdata('is_guest_checkout') == 1) ? 'checkout_as_guest':'checkout'; ?>
                    <div class="col-md-4 col-sm-6"> 
                        <a class="figure picture mb-2" id="addtocart-<?php echo $value['menu_id']; ?>" href="javascript:void(0)" onclick="checkCartRestaurantDetails(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'<?php echo $value['timings']['closing']; ?>','<?php echo $addons; ?>',this.id,'no', '<?php echo $page; ?>')">
                            <?php $rest_image = (file_exists(FCPATH.'uploads/'.$value['image']) && $value['image']!='') ? image_url.$value['image'] : default_icon_img; ?>
                            <img src="<?php echo $rest_image; ?>">
                        </a>
                        <h6><?php echo $value['name']; ?></h6>
                
                        <?php if(!empty($value['offer_price'])){ ?>
                            <h6 class="opacity-75 text-decoration-line-through"><?php echo currency_symboldisplay($value['price'],$currency_symbol->currency_symbol); ?></h6>
                            <h6 class="opacity-75"><?php echo currency_symboldisplay($value['offer_price'],$currency_symbol->currency_symbol); ?></h6>
                        <?php } else { ?>
                            <h6 class="opacity-75"><?php echo currency_symboldisplay($value['price'],$currency_symbol->currency_symbol); ?></h6>
                        <?php } ?>
                    
                        <?php if ($addons == '') { ?>
                            <div class="mt-4" id="cart_item_<?php echo $value['menu_id']; ?>">                            
                                <button class="btn btn-sm btn-primary w-100 add addtocart-<?php echo $value['menu_id']; ?>" id="addtocart-<?php echo $value['menu_id']; ?>" onclick="checkCartRestaurant(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'',this.id,'<?php echo $page; ?>')"><?php echo $this->lang->line('add'); ?></button>
                            </div>
                        <?php } else {?>
                            <div class="mt-4" id="cart_item_<?php echo $value['menu_id']; ?>">                            
                                <button class="btn btn-sm btn-primary w-100 add addtocart-<?php echo $value['menu_id']; ?>" id="addtocart-<?php echo $value['menu_id']; ?>" onclick="checkCartRestaurant(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'addons',this.id,'<?php echo $page; ?>')"> <?php echo $this->lang->line('add'); ?> </button>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

<?php } ?>