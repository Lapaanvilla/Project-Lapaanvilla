<?php $this->load->view('header'); ?>

<section class="section-banner bg-light position-relative text-center d-flex align-items-center"  style="background-image: url('<?php echo base_url();?>assets/front/images/banner-faq.jpg');">
    <div class="container-fluid banner-content">
        <h1 class="text-white"><?php echo $this->lang->line('faqs') ?></h1>
    </div>
</section>
<section class="section-text py-8 py-xl-12">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <figure class="position-sticky text-center" style="top: 80px;">
                    <img src="<?php echo base_url();?>assets/front/images/icon-faq.svg">
                </figure>
            </div>
            <div class="col-lg-6 mt-4 mt-md-8 mt-lg-0">
                <?php 
                    if(!empty($result)){
                        foreach($result as $category){
                            ?>
                            <div class="accordion mb-4 mb-md-8" id="accordion_<?php echo $category->entity_id ?>">
                                <h2 class="mb-1 mb-md-2"><?php echo $category->name; ?></h2>
                                <?php $i = 1;
                                    foreach($category->faqs as $faq){ 
                                        ?>

                                        <div class="accordion-item">
                                            <a href="javascript:void(0)" class="accordion-button collapsed" data-toggle="collapse" data-target="#collapse<?php echo $category->entity_id ?><?php echo $i ?>" aria-expanded="true"><?php echo $faq->question; ?></a>
                                            <div class="accordion-collapse collapse" id="collapse<?php echo $category->entity_id ?><?php echo $i ?>">
                                                <div class="accordion-body text-editor">
                                                    <?php echo $faq->answer; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php $i++; } 
                                ?>
                            </div>
                            <?php 
                        } 
                    } else {
                        ?>
                            <h2><?php echo $this->lang->line('coming_soon'); ?></h2>
                        <?php 
                    } 
                ?>
            </div>
        </div>
    </div>
</section>
<?php $this->load->view('footer'); ?>
