<?php $this->load->view('header'); ?>

<section class="section-banner bg-light position-relative text-center d-flex align-items-center" style="background-image: url('<?php echo ($privacy_policy[0]->image)?image_url.$privacy_policy[0]->image:cms_banner_img?>');">
    <div class="container-fluid banner-content">
        <h1 class="text-white"><?php echo $this->lang->line('privacy_policy') ?></h1>
    </div>
</section>
<section class="section-text py-8 py-xl-12">
    <div class="container-fluid text-editor">
        <?php if (!empty($privacy_policy)) { ?>
            <?php echo $privacy_policy[0]->description; ?>
        <?php } ?>
    </div>
</section>

<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script src="<?php echo base_url();?>assets/front/js/scripts/admin-management-front.js"></script>
<?php $this->load->view('footer'); ?>
