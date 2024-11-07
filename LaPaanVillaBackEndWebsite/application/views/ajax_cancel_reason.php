<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4 p-xl-8">
        <a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close" onclick="document.location.href='<?php echo base_url();?>myprofile';">
            <img src="<?php base_url() ?>/assets/front/images/icon-close.svg" alt="">
        </a>

        <h2 class="text-capitalize title pb-2 text-center mb-4 mb-xl-8"><?php echo $this->lang->line('select_cancel_reason') ?></h2>

        <?php if($is_cancel_order == 'yes'){ ?>
            <form action='' id="cancel_reason_form" name="cancel_reason_form" method="post" class="form-horizontal float-form">
                <div class="form-floating">
                    <?php foreach ($cancel_order_reasons as $key => $value) { ?>
                        <div class="form-check mb-2">
                            <input type="radio" name="filter_reason" class="form-check-input" id="filter_<?=$value['entity_id']?>" value="<?=$value['reason']?>"  onclick="removeInput(<?php echo $value['entity_id'] ?>)">
                            <label class="form-check-label" for="filter_<?=$value['entity_id']?>"><?php echo $value['reason']; ?></label>
                        </div>
                    <?php  } ?>
                    <div class="form-check">
                        <input type="radio" checked="checked" name="filter_reason" class="form-check-input" id="all" value="all">
                        <label class="form-check-label" for="all"><?php echo $this->lang->line('other') ?></label>
                        <input type="hidden" name="user_id" id="user_id" value="<?php echo $this->session->userdata('UserID'); ?>">
                    </div>
                </div>
                <div class="form-floating" id="reason-id">
                    <input type="text" class="form-control" name="other_reason" id="other_reason" placeholder="<?php echo $this->lang->line('enter_reason') ?>">
                    <label><?php echo $this->lang->line('enter_reason') ?></label>
                    <span id="reason" class="error"></span>
                </div>
                <div class="form-action">
                    <button type="submit" class="btn btn-primary w-100" onclick="return cancel_order_reason(<?php echo $order_id; ?>,<?php echo $this->session->userdata('UserID'); ?>)"><?php echo $this->lang->line('submit') ?></button>
                </div>
            </form>
        <?php } ?>
    </div>
</div>
<script>
  function removeInput(key){
    $('#reason-id').hide();
    $('#other_reason').val('');
    $('#other_reason').removeClass('error');
    $('.error').hide();
  }
  $('#all').on('click',function(){
    $('#reason-id').show();
  });
</script>