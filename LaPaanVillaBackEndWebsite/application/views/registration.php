<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('header'); ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php $this->load->view('social_login_css');
//get System Option Data
$this->db->select('OptionValue');
$phone_code = $this->db->get_where('system_option',array('OptionSlug'=>'phone_code'))->first_row();
$phone_code = $phone_code->OptionValue; ?>
<!-- Embed the intl-tel-input plugin -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/intl_tel_input/intlTelInput.css">
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/intl_tel_input/intlTelInput.min.js"></script>
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script> -->
    
    <section class="section-account">
        <div class="row row-cols-1 row-cols-xl-2 g-0 min-vh-100">
            <div class="col align-self-center">
                <div class="small-container px-4 py-8 p-md-8 py-xl-12">
                    <a href="<?php echo base_url();?>" class="icon text-secondary mb-6 mb-xl-8"><img src="<?php echo base_url();?>assets/front/images/brand-logo.svg" alt="Logo"></a>

                    <h1 class="h2 pb-2 mb-6 title"><?php echo $this->lang->line('welcome_to') ?> <?php echo $this->lang->line('site_title'); ?>!</h1>

                    <label class="text-center mb-1 text-capitalize w-100 small text-secondary"><?php echo $this->lang->line('signin_with') ?></label>
                    <form action="<?php echo base_url().'home/registration';?>" id="form_front_registration" name="form_front_registration" method="post" class="form-horizontal form_front_registration float-form form-white" enctype="multipart/form-data" >
                        <!--Social Logins-->
                        <div class="text-center d-flex flex-column flex-sm-row">
                            <!-- facebook -->
                            <a href="<?php echo $authURL; ?>"  class="btn text-nowrap px-4 w-100 btn-facebook"><i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-facebook.svg" alt="Facebook"></i><?php echo $this->lang->line('fb_login') ?></a>
                            <div class="p-1"></div>
                            <a href="<?php echo $google_login_url; ?>"  class="btn text-nowrap px-4 w-100 btn-google"><i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-google.svg" alt="Google"></i><?php echo $this->lang->line('google_login') ?></a>
                        </div>
                        
                        <div class="d-flex align-items-center py-4">
                            <hr class="m-0 w-100" /><span class="px-2 text-uppercase fw-bold text-nowrap"><?php echo $this->lang->line('or') ?></span><hr class="m-0 w-100" />
                        </div>
                        

                        <div class="row row-form">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="first_name" id="first_name" class="form-control" placeholder=" " value="<?php if(isset($_POST["first_name"])){ echo $_POST["first_name"]; } ?>" maxlength='20' >
                                    <label><?php echo $this->lang->line('first_name') ?></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder=" " value="<?php if(isset($_POST["last_name"])){ echo $_POST["last_name"]; } ?>" maxlength='20' >
                                    <label><?php echo $this->lang->line('last_name') ?></label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" name="email" id="email" class="form-control" placeholder=" " value="<?php if(isset($_POST["email"])){ echo $_POST["email"]; }  ?>" maxlength='50' >
                                    <label><?php echo $this->lang->line('email') ?></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating password-icon">
                                    <input type="password" name="password" id="password" class="form-control" placeholder=" " value="<?php if(isset($_POST["password"])){ echo $_POST["password"]; }  ?>">
                                    <label><?php echo $this->lang->line('password') ?></label>
                                    <i id="togglePasswordshow" class="icon icon-input icon-eye"></i>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating phn_num_container">
                                    <input type="hidden" name="phone_code" id="phone_code" class="form-control" value="<?php if(isset($_POST["phone_code"])){ echo $_POST["phone_code"]; }  ?>">
                                    <input type="tel" name="phone_number" id="phone_number" class="form-control" placeholder="" value="<?php if(isset($_POST["phone_number"])){ echo $_POST["phone_number"]; } ?>" maxlength='12' >
                                    <label><?php echo $this->lang->line('phone_number') ?></label>
                                    <div class="phn_err"  style="display: none; color: red;"></div>
                                </div>
                                <div class="form-floating">
                                    <input type="text" name="referral_code" id="referral_code" class="form-control" placeholder=" " value="<?php if(isset($_POST["referral_code"])){ echo $_POST["referral_code"]; } ?>">
                                    <label><?php echo $this->lang->line('enter_referral_code') ?></label>
                                </div>
                                <?php /* 
                                //upload image code hide
                                ?>
                                <div class="form-group edit-profile-img">
                                    <label><?php echo $this->lang->line('add_profile_image') ?></label>     
                                    <div class="edit-img">
                                        <img id="preview" class="display-no"/>
                                        <span class="custom-add-image"><?php echo $this->lang->line('upload_image') ?></span>
                                        <input type="file" name="Image" id="Image" accept="image/*" data-msg-accept="<?php echo $this->lang->line('file_extenstion') ?>" onchange="readURL(this)"/>
                                    </div>
                                    <span class="error display-no" id="errormsg"></span>
                                </div>
                                <?php */ ?>
                                <div class="form-floating">
                                    <div class="g-recaptcha" data-sitekey="<?php echo config_item('GOOGLE_CAPTCHA_SITE_KEY');?>" data-callback="recaptchaCallback"></div>
                                    <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" id="hiddenRecaptcha">
                                </div>
                                <div class="form-floating">
                                    <?php $disclaimer_tnC = "<a class='text-decoration-underline' href='".base_url()."terms-and-conditions'>".$this->lang->line('terms_and_conditions')."</a>";
                                    $disclaimer_privacy = "<a class='text-decoration-underline' href='".base_url()."privacy-policy'>".$this->lang->line('privacy_policy')."</a>";
                                    $disclaimer_text = sprintf($this->lang->line('disclaimer_text'),$disclaimer_tnC,$disclaimer_privacy); ?>

                                    <?php echo $disclaimer_text; ?>
                                </div>
                                <div class="form-floating">
                                    <button type="submit" name="submit_page" id="submit_page" value="Register" class="btn btn-primary w-100"><?php echo $this->lang->line('sign_up') ?></button>
                                </div>
                                <?php /*if(!empty($this->session->flashdata('error_MSG'))) {?>
                                <div class="alert alert-danger">
                                    <?php echo $this->session->flashdata('error_MSG');?>
                                </div>
                                <?php }*/ ?>
                                <?php 
                                if($_SESSION['error_MSG'])
                                { ?>
                                    <div class="alert alert-danger">
                                         <?php echo $_SESSION['error_MSG'];
                                            unset($_SESSION['error_MSG']);
                                         ?>
                                    </div>
                                <?php } ?>
                                <?php /*if(!empty($this->session->flashdata('success_MSG'))) {?>
                                <div class="alert alert-success">
                                    <?php echo $this->session->flashdata('success_MSG');?>
                                </div>
                                <?php }*/ ?>
                                <?php 
                                if($_SESSION['success_MSG'])
                                { ?>
                                    <div class="alert alert-success">
                                         <?php echo $_SESSION['success_MSG'];
                                            unset($_SESSION['success_MSG']);
                                         ?>
                                    </div>
                                <?php } ?>
                                <?php if(!empty($success)){?>
                                <div class="alert alert-success valid-feedback mt-4"><?php echo $success;?></div>
                                <?php } ?>         
                                <?php if(!empty($error)){?>
                                <div class="alert alert-danger mt-4 errormsg"><?php echo $error;?></div>
                                <?php } ?>                                  
                                <?php if(validation_errors()){?>
                                <div class="alert alert-danger errormsg">
                                    <?php echo validation_errors();?>
                                </div>
                                <?php } ?>
                                <span class="text-center d-inline-block w-100"><?php echo $this->lang->line('already_have_account') ?> <a href="<?php echo base_url().'home/login';?>" class="text-decoration-underline"><?php echo $this->lang->line('title_login') ?></a></span>
                            </div>
                        </div>    
                    </form>
                </div>
            </div>
            <div class="min-vh-100 d-none d-xl-inline-block">
                <figure class="w-100 h-100 picture">
                    <img src="<?php echo base_url();?>assets/front/images/banner-login.jpg" alt="">
                </figure>
            </div>
        </div>
    </section>

<!-- verifyOTP modal start -->
<div class="modal fade show" tabindex="-1" role="dialog" id="verify-otp-modal">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close"><img src="<?php echo base_url();?>assets/front/images/icon-close.svg" alt=""></a>

            <div class="row g-0 row-cols-1 row-cols-xl-2">
                <div class="col bg-light py-8 px-4 text-center d-flex align-items-center">
                    <figure>
                        <img src="<?php echo base_url();?>assets/front/images/image-account-modal.png" alt="Forgot Password Image">
                    </figure>
                </div>
                <div class="col p-4 p-xl-8 align-self-center">
                    <div class="alert alert-success display-no" id="verifyotp_success"></div>
                    <div id="verify_otp_section">
                        <h4 class="title pb-2 mb-6 text-capitalize"><?php echo $this->lang->line('verify_otp') ?></h4>
                        <h6 class="mb-1" id="enter_otp_text"><?php echo $this->lang->line('enter_otp') ?></h6>
                        <form id="form_front_verifyotp" name="form_front_verifyotp" method="post" class="form-horizontal float-form"  data-autosubmit="false" autocomplete="off">

                            <div class="form-group mb-4 otp-form user_otp_divmodal digit-group" style="<?php echo ($this->session->userdata('facebook_user') == 'yes' || $this->session->userdata('google_user') == 'yes')?'display: none;': ''; ?>">
                                
                                <div class="d-flex">
                                    <input class="form-control px-0 text-center me-1" type="text" id="digit-1" name="digit-1" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}" data-next="digit-2" class="smsCode" required />
                                    
                                    <input class="form-control px-0 text-center me-1" type="text" id="digit-2" name="digit-2" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}" data-next="digit-3" data-previous="digit-1" class="smsCode" required />
                                    
                                    <input class="form-control px-0 text-center me-1" type="text" id="digit-3" name="digit-3" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}" data-next="digit-4" data-previous="digit-2" class="smsCode" required />
                                    
                                    <input class="form-control px-0 text-center me-1" type="text" id="digit-4" name="digit-4" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}" data-next="digit-5" data-previous="digit-3" class="smsCode" required />
                                    
                                    <input class="form-control px-0 text-center me-1" type="text" id="digit-5" name="digit-5" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}" data-next="digit-6" data-previous="digit-4" class="smsCode" required />
                                    
                                    <input class="form-control px-0 text-center me-1" type="text" id="digit-6" name="digit-6" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}" data-previous="digit-5" class="smsCode" required />
                                </div>

                                <input type="hidden" name="user_otp" id="user_otp">
                                
                                <div class="mt-4">
                                    <span><?php echo $this->lang->line('having_trouble') ?></span>
                                    <button type="button" name="verifyotp_resend" id="verifyotp_resend" value="Submit_resend" class="resend_otp btn-link text-primary mx-1"><?php echo $this->lang->line('resend_otp') ?></button>
                                </div>
                                <div class="otp_error_div"></div>
                            </div>
                            <div class="form-floating w-100 phn_num_container mobile_number_divmodal" style="<?php echo ($this->session->userdata('facebook_user') == 'yes' || $this->session->userdata('google_user') == 'yes')?'display: inline-block;':'display: none;'; ?>" >
                                <input type="hidden" name="phone_code_otp" id="phone_code_otp" class="form-control" value="">
                                <input type="tel" name="mobile_number" id="mobile_number" class="form-control" placeholder="" value="<?php if(isset($_POST["mobile_number"])){ echo $_POST["mobile_number"]; } ?>" maxlength='12' >
                                <label><?php echo $this->lang->line('phone_number') ?></label>
                                <div id="start_with_zero" class="error"></div>
                            </div>
                            <div class="action-button">                                   
                                <button type="submit" name="verifyotp_submit_page" id="verifyotp_submit_page" value="<?php echo ($this->session->userdata('facebook_user') == 'yes' || $this->session->userdata('google_user') == 'yes')?'resend_submit':'Submit'; ?>" class="btn btn-primary w-100"><?php echo $this->lang->line('continue') ?></button>
                            </div>                            
                            <div class="alert alert-danger display-no" id="verifyotp_error"></div>
                            <?php if(validation_errors()){?>
                            <div class="alert alert-danger">
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
<?php if($this->session->userdata('enter_otp')=='yes') {
    echo '<script> $("#verify-otp-modal").modal(\'show\');</script>'; ?>
    <script> //$("#track_order").html($payment['order_id']);</script>
<?php } elseif ($this->session->userdata('enter_otp')=='no') {
    echo '<script> $("#verify-otp-modal").modal(\'hide\');</script>';
} ?>
<?php if($this->session->userdata('facebook_user') == 'yes' || $this->session->userdata('google_user') == 'yes'){
    echo '<script> $("#digit-1").removeAttr(\'required\'); </script>';
    echo '<script> $("#digit-2").removeAttr(\'required\'); </script>';
    echo '<script> $("#digit-3").removeAttr(\'required\'); </script>';
    echo '<script> $("#digit-4").removeAttr(\'required\'); </script>';
    echo '<script> $("#digit-5").removeAttr(\'required\'); </script>';
    echo '<script> $("#digit-6").removeAttr(\'required\'); </script>';
    echo '<script> $("#mobile_number").attr(\'required\', \'true\'); </script>';
    if($this->session->userdata('language_slug')=='en'){
        echo '<script> $("#enter_otp_text").text(\'Please enter your phone number.\');</script>';
    } else {
        echo '<script> $("#enter_otp_text").text(\'Vă rugăm să introduceți numărul dumneavoastră de telefon.\');</script>';
    }
} ?>
<!-- verifyOTP modal end -->
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script src="<?php echo base_url();?>assets/front/js/scripts/admin-management-front.js"></script>
<?php if($this->session->userdata("language_slug")=='fr'){  ?>
    <script type="text/javascript" src="<?php echo base_url()?>assets/admin/pages/scripts/localization/messages_fr.js"> </script>
<?php } elseif ($this->session->userdata("language_slug")=='ar') { ?>
    <script type="text/javascript" src="<?php echo base_url()?>assets/admin/pages/scripts/localization/messages_ar.js"> </script>   
<?php } ?>
<script type="text/javascript">
function recaptchaCallback() {
  $('#hiddenRecaptcha').valid();
};
$(document).ready(function() {
    if($('.errormsg').length==1){
        $('html, body').animate({
            scrollTop: $(".errormsg").offset().top
        }, 500);
    }
});
// submit verify otp form
$("#form_front_verifyotp").on("submit", function(event) { 
  event.preventDefault();
  var otp_entered = $('#digit-1').val() + $('#digit-2').val() + $('#digit-3').val() + $('#digit-4').val() + $('#digit-5').val() + $('#digit-6').val();
  $('#user_otp').val(otp_entered);
  
  jQuery.ajax({
    type : "POST",
    dataType :"json",
    url : BASEURL+'home/verify_otp',
    data : {'user_otp':$('#user_otp').val(),'phone_code_otp': $('#phone_code_otp').val(),'mobile_number': $('#mobile_number').val(), 'verifyotp_submit_page':$('#verifyotp_submit_page').val() },
    beforeSend: function(){
        $('#quotes-main-loader').show();
    },
    success: function(response) { 
      $('#verifyotp_error').hide();
      $('#verifyotp_success').hide();
      $('#quotes-main-loader').hide();
      
      if (response) {
        if (response.verifyotp_error != '') { 
            $('#verifyotp_error').html(response.verifyotp_error);
            $('#verifyotp_success').hide();
            $('#verifyotp_error').show();
            /*if(response.phn_not_exist != '1'){
                $("#verifyotp_resend").css("display", "inline-block");
            }*/
            $('#user_otp').val('');
            $('#digit-1').val('');
            $('#digit-2').val('');
            $('#digit-3').val('');
            $('#digit-4').val('');
            $('#digit-5').val('');
            $('#digit-6').val('');
            //$("#verifyotp_submit_page").css("display", "none");
        }
        if (response.verifyotp_success != '') { 
            if(response.verifyotp_sent=='1'){ //resend otp
              $('#verifyotp_submit_page').val('Submit');
              //$("#user_otp").css("display", "block");
              $(".user_otp_divmodal").css("display", "block");
              $("#mobile_number").removeAttr("required");
              $("#digit-1").attr("required", "true");
              $("#digit-2").attr("required", "true");
              $("#digit-3").attr("required", "true");
              $("#digit-4").attr("required", "true");
              $("#digit-5").attr("required", "true");
              $("#digit-6").attr("required", "true");

              $('#verifyotp_modaltitle').text("<?php echo $this->lang->line('verify_otp') ?>");
              $('#enter_otp_text').text("<?php echo $this->lang->line('enter_otp') ?>");
              
              $("#verifyotp_submit_page").css("display", "block");
              $(".mobile_number_divmodal").css("display", "none");
              $('#mobile_number').val('');
              
            } else { //otp verified
              $('#verify_otp_section').hide();
              $('#verifyotp_success').html(response.verifyotp_success);
              $('#verifyotp_success').show();
              
              $("#name").removeAttr("required");
              $("#email").removeAttr("required");
              $("#phone_number").removeAttr("required");
              $("#password").removeAttr("required");
              var otp_verified = '<input type="hidden" id="otp_verified" name="otp_verified" value="yes"></input>';
              $('#form_front_registration').append(otp_verified);
              //$('#form_front_registration').submit();
              window.setTimeout(function() { 
                //$('#form_front_registration').submit(); 
                document.getElementById("form_front_registration").submit();
              }, 5000);
              //$("#verify-otp-modal").modal('hide');
            }
            $('#verifyotp_success').html(response.verifyotp_success);
            $('#verifyotp_error').hide();
            $('#verifyotp_success').show();
            
        }
      }
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {           
        alert(errorThrown);
    }
  });
});
// submit verify OTP form hidden
$('#verify-otp-modal').on('hidden.bs.modal', function (e) {
  $(this).find("input[type=number]").val('').end();
  $('#form_front_verifyotp').validate({
    errorPlacement: function(error, element) {
        if(element.attr("name") == "digit-1" || element.attr("name") == "digit-2" || element.attr("name") == "digit-3" || element.attr("name") == "digit-4" || element.attr("name") == "digit-5" || element.attr("name") == "digit-6"){
            error.appendTo($('.otp_error_div'));
        } else {
            error.insertAfter(element);
        }
    }
  }).resetForm();
  $('#verifyotp_success').text('');
  $('#verifyotp_error').text('');
  $('#verifyotp_success').hide();
  $('#verifyotp_error').hide();
  $('#verify_otp_section').show();
  $('#user_otp').val('');
});
$("#verifyotp_resend").click(function()
{
    $('#user_otp').val('');
    $('#digit-1').val('');
    $('#digit-2').val('');  
    $('#digit-3').val('');
    $('#digit-4').val('');
    $('#digit-5').val('');
    $('#digit-6').val('');
    $('#verifyotp_success').hide();
    $('#verifyotp_submit_page').val('resend_submit');
    //$("#verifyotp_resend").css("display", "none");
    $("#user_otp").css("display", "none");
    $(".user_otp_divmodal").css("display", "none");
    $("#digit-1").removeAttr("required");
    $("#digit-2").removeAttr("required");
    $("#digit-3").removeAttr("required");
    $("#digit-4").removeAttr("required");
    $("#digit-5").removeAttr("required");
    $("#digit-6").removeAttr("required");
    
    $('#verifyotp_error').text('');
    $('#verifyotp_error').hide();
    $('#verifyotp_modaltitle').text("<?php echo $this->lang->line('resend_otp') ?>");
  if(SELECTED_LANG == 'fr'){
    $('#enter_otp_text').text('Veuillez entrer votre numéro de téléphone.');
  } else if(SELECTED_LANG == 'ar'){
    $('#enter_otp_text').text('يرجى إدخال رقم الهاتف الخاص بك.');
  } else {
    $('#enter_otp_text').text('Please enter your phone number.');
  }
  $("#verifyotp_submit_page").css("display", "block");
  $("#mobile_number").attr("required", "true");
  $(".mobile_number_divmodal").css("display", "inline-block");
});
</script>
<script>
$('.digit-group').find('input').each(function() {
    //restricting to enter more than 10 digits
    if($(this).attr('id') == 'mobile_number' ){
        /*$('input[type=number][max]:not([max=""])').on('input', function(ev) {
            var phn_no_maxlength = $(this).attr('max').length;
            var value = $(this).val();
            if (value && value.length >= phn_no_maxlength) {
              $(this).val(value.substr(0, phn_no_maxlength));
            }
        });*/
    } else {
        $(this).attr('maxlength', 1);
    }
    $(this).on('keyup', function(e) {
        e.preventDefault();
        var initial_input = $(this).val();
        $(this).val(initial_input.replace(/\D/g, ""));
        var final_input_val = $(this).val().substr(0,1);
        $(this).val(final_input_val);
        var input_ascii_code = $(this).val().charCodeAt(0);

        var parent = $($(this).parent());
        if(e.keyCode === 8 || e.keyCode === 37) {
            var prev = parent.find('input#' + $(this).data('previous'));
            if(prev.length) {
                $(prev).select();
            }
        } else if((e.keyCode >= 48 && e.keyCode <= 57) || e.keyCode === 39 || (e.keyCode >= 96 && e.keyCode <= 105) || (input_ascii_code != NaN && input_ascii_code >= 48 && input_ascii_code <= 57)) {
            var next = parent.find('input#' + $(this).data('next'));
            if(next.length) {
                $(next).select();
            } else {
                if(parent.data('autosubmit')) {
                    parent.submit();
                }
            }
        } else  {
            //$(this).val('');
            e.preventDefault();
            return false;
        }
    });
});
</script>
<script type="text/javascript">
/*$(document).on('input','#mobile_number',function(){
    var phone=$('#mobile_number').val();
    if(phone.indexOf('0')!==0){
        $('#start_with_zero').text('<?php //echo $this->lang->line('start_with_zero'); ?>');
        $("#start_with_zero").css("display", "block");
        $('#mobile_number').val('');
    } else {
        if(phone.length<9){
            $('#start_with_zero').text('<?php //echo $this->lang->line('enter_nine_digits'); ?>');
            $("#start_with_zero").css("display", "block");
            $("#verifyotp_submit_page").prop('disabled', true);
        } else if(phone.length>10) {
            $('#start_with_zero').text('<?php //echo $this->lang->line('no_more_than_ten_digits'); ?>');
            $("#start_with_zero").css("display", "block");
            $("#verifyotp_submit_page").prop('disabled', true);
        } else {
            $("#start_with_zero").css("display", "none");
            $("#verifyotp_submit_page").prop('disabled', false);
        }
    }
});*/
function readURL(input){
    var fileInput = document.getElementById('Image');
    var filePath = fileInput.value;
    var extension = filePath.substr((filePath.lastIndexOf('.') + 1)).toLowerCase();
    if(input.files[0].size <= 10506316){ // 10 MB
        if(extension == 'png' || extension == 'jpg' || extension == 'jpeg' || extension == 'gif') {
            if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#preview').attr('src', e.target.result).attr('style','display: inline-block;');
                $('#errormsg').html('').hide();
            }
            reader.readAsDataURL(input.files[0]);
            }
        }
        else{
            $('#preview').attr('src', '').attr('style','display: none;');
            $('#errormsg').html("<?php echo $this->lang->line('file_extenstion'); ?>").show();
            $('#Image').val('');
        }
    }else{
        $('#preview').attr('src', '').attr('style','display: none;');
        $('#errormsg').html("<?php echo $this->lang->line('file_size_msg'); ?>").show();
        $('#Image').val('');
    }
}
</script>
<script type="text/javascript">
//intl-tel-input plugin :: 3june2021
var onedit_iso = '';
<?php if($this->session->userdata('phone_codeval')) {    
    $onedit_iso = $this->common_model->getIsobyPhnCode($this->session->userdata('phone_codeval')); ?>
    onedit_iso = '<?php echo $onedit_iso; ?>'; //saved in session
<?php }
else if($_POST["phone_code"]){    
    $onedit_iso = $this->common_model->getIsobyPhnCode($_POST["phone_code"]); ?>
    onedit_iso = '<?php echo $onedit_iso; ?>'; //saved in session
<?php }
$iso = $this->common_model->country_iso_for_dropdown();
$default_iso = $this->common_model->getDefaultIso(); ?>

var country_iso = <?php echo json_encode($iso); ?>; //all active countries
var default_iso = <?php echo json_encode($default_iso); ?>; //default country
default_iso = (default_iso)?default_iso:'';
var initial_preferred_iso = (onedit_iso)?onedit_iso:default_iso;

//Registration form intel plugin on number :: start
// Initialize the intl-tel-input plugin
const phoneInputField = document.querySelector("#phone_number");
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
//Registration form intel plugin on number :: end

//OTP modal intel plugin on number :: start
// Initialize the intl-tel-input plugin
const phoneInputFieldOTP = document.querySelector("#mobile_number");
const phoneInputOTP = window.intlTelInput(phoneInputFieldOTP, {
    initialCountry: initial_preferred_iso,
    preferredCountries: [initial_preferred_iso],
    onlyCountries: country_iso,
    separateDialCode:true,
    autoPlaceholder:"polite",
    formatOnDisplay:false,
    utilsScript: BASEURL+'assets/admin/plugins/intl_tel_input/utils.js',
        //"https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
});
$(document).on('input','#mobile_number',function(){
    event.preventDefault();
    var mobileNumber = phoneInputOTP.getNumber();
    if (phoneInputOTP.isValidNumber()) {
        $("#start_with_zero").css("display", "none");
        //$("#verifyotp_submit_page").prop('disabled', false);
        $("button[value='resend_submit']").prop('disabled', false);
        var countryDataOTP = phoneInputOTP.getSelectedCountryData();
        var countryCodeOTP = countryDataOTP.dialCode;
        $('#phone_code_otp').val(countryCodeOTP);
        mobileNumber = mobileNumber.replace('+'+countryCodeOTP,'');
        $('#mobile_number').val(mobileNumber);
    } else {
        $('#start_with_zero').text("<?php echo $this->lang->line('enter_valid_phn'); ?>");
        $("#start_with_zero").css("display", "block");
         //$("#verifyotp_submit_page").prop('disabled', true);
        $("button[value='resend_submit']").prop('disabled', true);
    }
    if (event.keyCode == 13) {
        $("#form_front_verifyotp").submit();   
        return false;
    }
});
$(document).on('focusout','#mobile_number',function(){
    event.preventDefault();
    var mobileNumber = phoneInputOTP.getNumber();
    if (phoneInputOTP.isValidNumber()) {
        $("#start_with_zero").css("display", "none");
        //$("#verifyotp_submit_page").prop('disabled', false);
        $("button[value='resend_submit']").prop('disabled', false);
        var countryDataOTP = phoneInputOTP.getSelectedCountryData();
        var countryCodeOTP = countryDataOTP.dialCode;
        $('#phone_code_otp').val(countryCodeOTP);
        mobileNumber = mobileNumber.replace('+'+countryCodeOTP,'');
        $('#mobile_number').val(mobileNumber);
    } else {
        $('#start_with_zero').text("<?php echo $this->lang->line('enter_valid_phn'); ?>");
        $("#start_with_zero").css("display", "block");
         //$("#verifyotp_submit_page").prop('disabled', true);
         $("button[value='resend_submit']").prop('disabled', true);
    }
});
phoneInputFieldOTP.addEventListener("close:countrydropdown",function() {
    var mobileNumber = phoneInputOTP.getNumber();
    if (phoneInputOTP.isValidNumber()) {
        $("#start_with_zero").css("display", "none");
        //$("#verifyotp_submit_page").prop('disabled', false);
        $("button[value='resend_submit']").prop('disabled', false);
        var countryDataOTP = phoneInputOTP.getSelectedCountryData();
        var countryCodeOTP = countryDataOTP.dialCode;
        $('#phone_code_otp').val(countryCodeOTP);
        mobileNumber = mobileNumber.replace('+'+countryCodeOTP,'');
        $('#mobile_number').val(mobileNumber);
    } else {
        $('#start_with_zero').text("<?php echo $this->lang->line('enter_valid_phn'); ?>");
        $("#start_with_zero").css("display", "block");
         //$("#verifyotp_submit_page").prop('disabled', true);
        $("button[value='resend_submit']").prop('disabled', true);
    }
});
//OTP modal intel plugin on number :: end
</script>
<!-- <script type="text/javascript">
    document.querySelector("[type='password']").classList.add("input-password");document.getElementById("toggle-password").classList.remove("d-none");const passwordInput=document.querySelector("[type='password']");const togglePasswordButton=document.getElementById("toggle-password");togglePasswordButton.addEventListener("click",togglePassword);function togglePassword(){if(passwordInput.type==="password"){passwordInput.type="text";togglePasswordButton.setAttribute("aria-label","Hide password.")}else{passwordInput.type="password";togglePasswordButton.setAttribute("aria-label","Show password as plain text. "+"Warning: this will display your password on the screen.")}}
</script> -->
<script type="text/javascript">
    const togglePassword = document.querySelector('#togglePasswordshow');
    const password = document.querySelector('#password');
    togglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye / eye slash icon
    this.classList.toggle('close-eye');
});
</script>
<?php
if(!empty(website_footer_script)){
    echo website_footer_script;
}
?>
</body>
</html>
