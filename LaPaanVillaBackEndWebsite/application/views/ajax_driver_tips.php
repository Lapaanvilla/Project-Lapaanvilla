<?php 
if(!($this->session->userdata('tip_percent_val')) && $this->session->userdata('tip_percent_val') > 0) {
	$this->session->set_userdata('tip_amount',0);
}
$driver_tip_arr = get_driver_tip_amount();
$is_custom_tip = 'yes';
$selected_tip = 0; ?>

<label class="small"><?php echo $this->lang->line('driver_tip') ?></label>
<div class="form-group mb-2">
	<div class="row-tip d-flex flex-wrap">
		<?php $oneselectedclass = '';
		foreach($driver_tip_arr as $key_tip=>$value_tip) { 
			$tip_percent_val = (float)$value_tip;
			$calculated_value_tip = ((float)$cart_details['cart_total_price'] * (float)$value_tip)/100;
			$calculated_value_tip = $this->common_model->roundDriverTip((float)$calculated_value_tip);

			$selectedclass = '';
			if($this->session->userdata('tip_percent_val') == (float)$tip_percent_val && $oneselectedclass == ''){
				$is_custom_tip = 'no';
				$selected_tip = $calculated_value_tip;
				$selectedclass = 'btn-primary text-white';
				$oneselectedclass = 'btn-primary text-white';
			}

			if(!($this->session->userdata('tip_amount')>0) && $selectedclass == 'btn-primary text-white'){
				$this->session->set_userdata('tip_amount',$selected_tip);
			} else if(!($this->session->userdata('tip_percent_val')) && $this->session->userdata('tip_amount') > 0) {
				$selected_tip = (float)$this->session->userdata('tip_amount');
			} else if($selected_tip > 0 && $selectedclass == 'btn-primary text-white') {
				$this->session->set_userdata('tip_amount',$selected_tip);
			} ?>
			<div class="w-25 p-1">
				
				<a class="btn btn-xs px-1 border border-primary text-primary w-100 <?php echo $selectedclass; ?>" href="javascript:void(0);" onclick="tip_selected(<?php echo $calculated_value_tip; ?>, this.id);" data-val="<?php echo $tip_percent_val; ?>" id="tip_<?php echo $key_tip?>"><?php echo $value_tip.'%'; ?></a>
			</div>
		<?php } ?>
		<div class="w-25 p-1"><input type="text" oninput="tip_selected(this.value, this.id);" class="form-control text-center form-control-xs px-1" id="custom_tip" value="<?php echo ($selected_tip>0 && $is_custom_tip == 'yes')?$selected_tip:''; ?>" placeholder="0" ></div>
	</div>
	<small id="custom_tip_error" class="error" style="display: none;"><?php echo $this->lang->line('custom_tip_decimal_error') ?></small>
</div>
<div class="d-flex justify-content-center justify-content-sm-end">
	<input type="hidden" name="driver_tip" id="driver_tip" value="<?php echo ($selected_tip>0)?$selected_tip:''; ?>">
	<button type="button" disabled="disabled" id="tip_clear_btn" class="btn btn-xs btn-secondary" onclick="applyTip('clear');"><?php echo $this->lang->line('clear') ?></button>
	<div class="p-1"></div>
	<button type="button" disabled="disabled" id="tip_submit_btn" class="btn btn-xs btn-secondary" onclick="applyTip('apply')"><?php echo $this->lang->line('submit') ?></button>
</div>
<script type="text/javascript">
	var selected_tip = <?php echo $selected_tip; ?>;
	if(selected_tip>0){
		$('#tip_submit_btn').attr('disabled',false);
        $("#tip_clear_btn").attr("disabled", false);
	}
</script>