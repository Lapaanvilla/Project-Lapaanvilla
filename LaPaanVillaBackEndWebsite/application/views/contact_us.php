<?php $this->load->view('header'); ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php
if($this->input->post()){
  foreach ($this->input->post() as $key => $value) {
    $$key = @htmlspecialchars($this->input->post($key));
  } 
} 
?>
<!-- Embed the intl-tel-input plugin -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/intl_tel_input/intlTelInput.css">
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/intl_tel_input/intlTelInput.min.js"></script>

<section class="section-text py-8 py-xl-12">
    <div class="container-fluid mb-8">
        <h1 class="h2 pb-2 title text-center text-xl-start"><?php echo $this->lang->line('contact_us') ?></h1>
    </div>
    <div class="container-fluid container-xl-0">
        <div class="row flex-column-reverse flex-xl-row justify-content-between">
            <div class="col-xl-7 col-xxxl-8 horizontal-image">
                <div class="d-flex">
                    <figure class="picture mb-8">
                        <img src="<?php echo ($contact_us[0]->image)?image_url.$contact_us[0]->image:contact_us_img ?>" alt="Contact Us Image">
                    </figure>
                    <div class="ps-xxl-12">
                    </div>
                </div>
                <?php if (!empty($contact_us)) { ?>
                    <div class="text-editor container-gutter-xl"><?php echo $contact_us[0]->description; ?></div>
                <?php } ?>
            </div>
            <div class="col-xl-5 col-xxxl-4">
                <div class="card card-xl-0">
                    <div class="card-body container-gutter-xl py-8 p-xl-8">
                        <div class="text-center d-flex flex-column pb-4 pb-md-8">
                            <h3 class="mb-1"><?php echo $this->lang->line('growing_your_business'); ?></h3>
                            <small><?php echo $this->lang->line('contactus_form_title'); ?></small>
                        </div>
                        <div class="form-body">
                            <form action="<?php echo base_url().'contact-us ';?>" id="form_front_contact_us" name="form_front_contact_us" method="post">
                                <div class="row row-form">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" name="first_name" id="first_name" class="form-control" value="<?php if(isset($first_name)){ echo $first_name;}?>" minlength="2" placeholder=" ">
                                            <label><?php echo $this->lang->line('first_name') ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" name="last_name" id="last_name" class="form-control" value="<?php if(isset($last_name)){ echo $last_name;}?>" minlength="2" placeholder=" ">
                                            <label><?php echo $this->lang->line('last_name') ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="email" name="email" id="email" class="form-control" value="<?php if(isset($email)){ echo $email;}?>" placeholder=" " minlength="2" maxlength='50'>
                                            <label><?php echo $this->lang->line('email') ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" name="res_name" id="res_name" class="form-control" value="<?php if(isset($res_name)){ echo $res_name;}?>" minlength="2" placeholder=" ">
                                            <label><?php echo $this->lang->line('res_name') ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">                         
                                        <?php //New code add as per requirement :: End ?>
                                        <div class="form-floating">
                                            <input type="text" name="res_zip_code" id="res_zip_code" class="form-control" value="<?php if(isset($res_zip_code)){ echo $res_zip_code;}?>" minlength="5" maxlength="6" placeholder=" ">
                                            <label><?php echo $this->lang->line('res_zip_code') ?></label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="hidden" name="res_phone_code" id="res_phone_code" class="form-control" value="<?php if(isset($res_phone_code)){ echo $res_phone_code;}?>">
                                            <input type="tel" name="res_phone_number" id="res_phone_number" class="form-control" value="<?php if(isset($res_phone_number)){ echo $res_phone_number;}?>" minlength="10" maxlength="10" placeholder=" ">
                                            <label><?php echo $this->lang->line('res_phone_number') ?></label>
                                            <div class="phn_err"  style="display: none; color: red;"></div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <?php //New code add as per requirement :: Start?>
                                        <div class="form-floating">
                                            <input type="hidden" name="owners_phone_code" id="owners_phone_code" class="form-control" value="<?php if(isset($owners_phone_code)){ echo $owners_phone_code;}?>">
                                            <input type="tel" name="owners_phone_number" id="owners_phone_number" class="form-control" value="<?php if(isset($owners_phone_number)){ echo $owners_phone_number;}?>" minlength="10" maxlength="10" placeholder=" ">
                                            <label><?php echo $this->lang->line('owners_phone_number') ?></label>
                                            <div class="phn_errown"  style="display: none; color: red;"></div>
                                        </div>   
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea class="form-control" name="message" id="message" minlength="2" placeholder=" "><?php if(isset($message)){ echo $message;}?></textarea>
                                            <label><?php echo $this->lang->line('additional_notes') ?></label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <div class="g-recaptcha" data-sitekey="<?php echo config_item('GOOGLE_CAPTCHA_SITE_KEY');?>" data-callback="recaptchaCallback"></div>
                                            <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" id="hiddenRecaptcha">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="action-button">
                                            <button type="submit" name="submit_page" id="submit_page" value="Submit" class="btn btn-primary w-100"><?php echo $this->lang->line('submit') ?></button>
                                        </div>
                                    </div>
                                </div>
                                <?php /*if(!empty($this->session->flashdata('contactUsMSG'))) {?>
                                <div class="alert alert-success">
                                    <?php echo $this->session->flashdata('contactUsMSG');?>
                                </div>
                                <?php }*/ ?>
                                <?php 
                                if($_SESSION['contactUsMSG'])
                                { ?>
                                    <div class="alert alert-success show_msg">
                                         <?php echo $_SESSION['contactUsMSG'];
                                            unset($_SESSION['contactUsMSG']);
                                         ?>
                                    </div>
                                <?php } ?>
                                <?php if(!empty($success_msg)){?>
                                <div class="alert alert-success show_msg"><?php echo $success_msg;?></div>
                                <?php } ?>            
                                <?php if(!empty($Error)){?>
                                <div class="alert alert-danger show_msg"><?php echo $Error;?></div>
                                <?php } ?>                                  
                                <?php if(validation_errors()){?>
                                <div class="alert alert-danger show_msg">
                                    <?php echo validation_errors();?>
                                </div>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script src="<?php echo base_url();?>assets/front/js/scripts/admin-management-front.js"></script>
<script type="text/javascript">
function recaptchaCallback() {
  $('#hiddenRecaptcha').valid();
};
$(document).ready(function() {
    if($('.show_msg').length==1){
        $('html, body').animate({
            scrollTop: $(".show_msg").offset().top
        }, 500);
    }
});
//intl-tel-input plugin
var onedit_iso = '';
<?php if($this->session->userdata('phone_codeval')) {
    $onedit_iso = $this->common_model->getIsobyPhnCode($this->session->userdata('phone_codeval')); ?>
    onedit_iso = '<?php echo $onedit_iso; ?>'; //saved in session
<?php }
if(isset($res_phone_code)) { // for remember me
    $onedit_iso = $this->common_model->getIsobyPhnCode($res_phone_code); ?>
    onedit_iso = '<?php echo $onedit_iso; ?>';
<?php }
$iso = $this->common_model->country_iso_for_dropdown();
$default_iso = $this->common_model->getDefaultIso(); ?>

var country_iso = <?php echo json_encode($iso); ?>; //all active countries
var default_iso = <?php echo json_encode($default_iso); ?>; //default country
default_iso = (default_iso)?default_iso:'';
var initial_preferred_iso = (onedit_iso)?onedit_iso:default_iso;

//phone number login form :: start
// Initialize the intl-tel-input plugin
const phoneInputField = document.querySelector("#res_phone_number");
const phoneInput = window.intlTelInput(phoneInputField, {
    initialCountry: initial_preferred_iso,
    preferredCountries: [initial_preferred_iso],
    onlyCountries: country_iso,
    separateDialCode:true,
    autoPlaceholder:"polite",
    formatOnDisplay:false,
    utilsScript: BASEURL+'assets/admin/plugins/intl_tel_input/utils.js',
        //"https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
});
$(document).on('input','#res_phone_number',function(){
    event.preventDefault();
    var phoneNumber = phoneInput.getNumber();
    if (phoneInput.isValidNumber()) {
        var countryData = phoneInput.getSelectedCountryData();
        var countryCode = countryData.dialCode;
        $('#res_phone_code').val(countryCode);
        phoneNumber = phoneNumber.replace('+'+countryCode,'');
        $('#res_phone_number').val(phoneNumber);
    }
});
$(document).on('focusout','#res_phone_number',function(){
    event.preventDefault();
    var phoneNumber = phoneInput.getNumber();
    if (phoneInput.isValidNumber()) {
        var countryData = phoneInput.getSelectedCountryData();
        var countryCode = countryData.dialCode;
        $('#res_phone_code').val(countryCode);
        phoneNumber = phoneNumber.replace('+'+countryCode,'');
        $('#res_phone_number').val(phoneNumber);
    }
});
phoneInputField.addEventListener("close:countrydropdown",function() {
    var phoneNumber = phoneInput.getNumber();
    if (phoneInput.isValidNumber()) {
        var countryData = phoneInput.getSelectedCountryData();
        var countryCode = countryData.dialCode;
        $('#res_phone_code').val(countryCode);
        phoneNumber = phoneNumber.replace('+'+countryCode,'');
        $('#res_phone_number').val(phoneNumber);
    }
});

//#######################
// Initialize the intl-tel-input plugin
const phoneInputFieldOwn = document.querySelector("#owners_phone_number");
const phoneInputOwn = window.intlTelInput(phoneInputFieldOwn, {
    initialCountry: initial_preferred_iso,
    preferredCountries: [initial_preferred_iso],
    onlyCountries: country_iso,
    separateDialCode:true,
    autoPlaceholder:"polite",
    formatOnDisplay:false,
    utilsScript: BASEURL+'assets/admin/plugins/intl_tel_input/utils.js',
        //"https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
});
$(document).on('input','#owners_phone_number',function(){
    event.preventDefault();
    var phoneNumberOwn = phoneInputOwn.getNumber();
    if (phoneInputOwn.isValidNumber()) {
        var countryData = phoneInputOwn.getSelectedCountryData();
        var countryCode = countryData.dialCode;
        $('#owners_phone_code').val(countryCode);
        phoneNumberOwn = phoneNumberOwn.replace('+'+countryCode,'');
        $('#owners_phone_number').val(phoneNumberOwn);
    }
});
$(document).on('focusout','#owners_phone_number',function(){
    event.preventDefault();
    var phoneNumberOwn = phoneInputOwn.getNumber();
    if (phoneInputOwn.isValidNumber()) {
        var countryData = phoneInputOwn.getSelectedCountryData();
        var countryCode = countryData.dialCode;
        $('#owners_phone_code').val(countryCode);
        phoneNumberOwn = phoneNumberOwn.replace('+'+countryCode,'');
        $('#owners_phone_number').val(phoneNumberOwn);
    }
});
phoneInputFieldOwn.addEventListener("close:countrydropdown",function() {
    var phoneNumberOwn = phoneInputOwn.getNumber();
    if (phoneInputOwn.isValidNumber()) {
        var countryData = phoneInputOwn.getSelectedCountryData();
        var countryCode = countryData.dialCode;
        $('#owners_phone_code').val(countryCode);
        phoneNumberOwn = phoneNumberOwn.replace('+'+countryCode,'');
        $('#owners_phone_number').val(phoneNumberOwn);
    }
});
</script>
<?php $this->load->view('footer'); ?>
