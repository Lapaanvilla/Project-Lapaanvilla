<?php $driver_tip_arr = get_driver_tip_amount();
$is_custom_tip = 'yes';
$default_driver_tip = get_default_driver_tip_amount();
$default_driver_tip = ($default_driver_tip > 0 && $default_driver_tip != '') ? (float)$default_driver_tip : 0;
$selected_tip = $default_driver_tip; ?>
<script type="text/javascript">
    var field_required ="<?php echo $this->lang->line('field_required');?>";
</script>
    
<div class="form-group mb-4">
    <div class="fw-semibold text-secondary mb-1 text-center"><?php echo $this->lang->line('sod_driver_tip_amount').': '; ?><span id="display_tip" ><?php echo ($selected_tip>0)?currency_symboldisplay($selected_tip,$currency_symbol->currency_symbol):currency_symboldisplay(0,$currency_symbol->currency_symbol);  ?></span></div>
    <div class="row-tip d-flex flex-wrap">
        <?php $oneselectedclass = '';
        foreach($driver_tip_arr as $key_tip=>$value_tip){ 
            $tip_percent_val = $value_tip;
            $calculated_value_tip = ((float)$order_subtotal * (float)$value_tip)/100;
            $calculated_value_tip = $this->common_model->roundDriverTip((float)$calculated_value_tip);

            $selectedclass = '';
            if($selected_tip == (float)$value_tip && $oneselectedclass == ''){
                $is_custom_tip = 'no';
                $selected_tip = $calculated_value_tip;
                $selectedclass = 'tip_selected';
                $oneselectedclass = 'tip_selected';
            } ?>
            <div class="w-25">
                <a class="btn btn-xs px-1 border border-primary text-primary w-100" href="javascript:void(0);" onclick="tip_selected(<?php echo $calculated_value_tip; ?>, this.id,'<?php echo $currency_symbol->currency_symbol; ?>');" id="tip_<?php echo $key_tip?>" data-val="<?php echo $tip_percent_val; ?>" class="<?php echo $selectedclass; ?>" ><?php echo $value_tip.'%'; ?></a>
            </div>
        <?php } ?>
        <div class="w-25 p-1"><input type="text" oninput="tip_selected(this.value, this.id,'<?php echo $currency_symbol->currency_symbol; ?>');" class="form-control text-center form-control-xs px-1" id="custom_tip" value="<?php echo ($selected_tip>0 && $is_custom_tip == 'yes')?$selected_tip:''; ?>" placeholder="0" ></div>
    </div>
    <small id="custom_tip_error" class="error" style="display: none;"><?php echo $this->lang->line('custom_tip_decimal_error') ?></small>
</div>
<div class="form-group mb-4">
    <?php 
    $cnt = 1;
    if(!empty($payment_option)) {?>
        <div class="d-flex flex-wrap justify-content-center">
            <?php foreach($payment_option as $payment_method){ ?>
                <div class="form-check m-1">
                    <input type="radio" name="payment_option" class="form-check-input payment_option" id="payment_option<?php echo $cnt; ?>" value="<?php echo $payment_method->payment_gateway_slug; ?>" />
                    <label class="form-check-label" for="payment_option<?php echo $cnt; ?>"><?php echo $payment_method->payment_name ?></label>
                </div>
            <?php $cnt++; } ?>
        </div>
    <?php } else { ?>
        <div class="alert alert-danger"><?php echo $this->lang->line('tippayment_method_msg'); ?></div>
    <?php } ?>
    <small class="error" id="blankmsg"></small>
</div>

<input type="hidden" name="driver_tip" id="driver_tip" value="<?php echo ($selected_tip>0)?$selected_tip:''; ?>">
<input type="hidden" name="tip_order_id" id="tip_order_id" value="">

<div class="d-flex align-items-center justify-content-center">
    <button type="button" disabled="disabled" id="tip_clear_btn" class="btn btn-sm btn-primary" onclick="applyTipForOrders('clear','<?php echo $currency_symbol->currency_symbol; ?>');"><?php echo $this->lang->line('clear') ?></button>
    <div class="pe-1"></div>
    <button type="button" disabled="disabled" id="tip_submit_btn" class="btn btn-sm btn-primary" onclick="applyTipForOrders('apply','')"><?php echo $this->lang->line('submit') ?></button>
    <div id='paypal-button' style="display: none;"></div>
</div>


<script type="text/javascript">
    $("#paypal-button").empty();
    var selected_tip = <?php echo $selected_tip; ?>;
    if(selected_tip>0){
        $('#tip_submit_btn').attr('disabled',false);
        $("#tip_clear_btn").attr("disabled", false);
    }
    <?php if(empty($payment_option)) { ?>
        $('#tip_submit_btn').attr('disabled',true);
    <?php } ?>

    $('.payment_option').click(function()
    {
        var radioValue = $("input[name='payment_option']:checked").val();
        if(radioValue == "stripe")
        {
            $('#tip_submit_btn').show();
            $('#paypal-button').hide();
            $('#blankmsg').html('');
            $('#paypal-button').html('');
            $('#tip_submit_btn').attr('disabled',false);
        }
        else if(radioValue == "paypal")
        {
            $('#tip_submit_btn').hide();
            $('#paypal-button').show();
            $('#blankmsg').html('');
            $('#tip_submit_btn').attr('disabled',true);            
            mount_paypal_element();
        }        
    });    
</script>