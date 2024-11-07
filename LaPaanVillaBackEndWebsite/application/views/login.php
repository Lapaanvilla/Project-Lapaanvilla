<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('header');
parse_str(get_cookie('adminAuth'), $adminCook); // get Cookies
$this->load->view('social_login_css'); ?>
    
    <!-- Embed the intl-tel-input plugin -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/intl_tel_input/intlTelInput.css">
    <script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/intl_tel_input/intlTelInput.min.js"></script>

    <section class="section-account">
        <div class="row row-cols-1 row-cols-xl-2 g-0 min-vh-100">
            <div class="col align-self-center">
                <div class="small-container px-4 py-8 p-md-8 py-xl-12">
                    <a href="<?php echo base_url();?>" class="icon text-secondary mb-6 mb-xl-8"><img src="<?php echo base_url();?>assets/front/images/brand-logo.svg" alt="Logo"></a>
                    <h1 class="h2 pb-2 mb-6 title"><?php echo $this->lang->line('lets_get_started') ?></h1>
                
                    <form class="form-white" action="<?php echo base_url().'home/login';?>" id="form_front_login" name="form_front_login" method="post">
                        <!-- social media login - start :: 11_march_2021 -->

                        <label class="text-center mb-1 text-capitalize w-100 small text-secondary"><?php echo $this->lang->line('signin_with') ?></label>
                        <div class="text-center d-flex flex-column flex-sm-row">
                            <a href="<?php echo $authURL; ?>"  class="btn text-nowrap px-4 w-100 btn-facebook"><i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-facebook.svg" alt="Facebook"></i><?php echo $this->lang->line('fb_login') ?></a>
                            <div class="p-1"></div>
                            <a href="<?php echo $google_login_url; ?>"  class="btn text-nowrap px-4 w-100 btn-google"><i class="icon"><img src="<?php echo base_url();?>assets/front/images/icon-google.svg" alt="Google"></i><?php echo $this->lang->line('google_login') ?></a>
                        </div>
                        
                        <div class="d-flex align-items-center py-4">
                            <hr class="m-0 w-100" /><span class="px-2 text-uppercase fw-bold text-nowrap"><?php echo $this->lang->line('or') ?></span><hr class="m-0 w-100" />
                        </div>
                        <ul class="nav nav-tabs border border-primary d-flex flex-nowrap text-center bg-white login-select text-nowrap mb-4" id="myTab" role="tablist">
                            <input type="hidden" name="frm_page" id="frm_page" value="loginpage">

                            <li class="nav-item w-100" role="presentation">
                                <label for="phone_number" class="radiophn nav-link active <?php echo ($this->session->userdata('login_with')=='phone_number')?'btn-outline-primary':''; ?>" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                                    <input type="radio" name="login_with" checked="checked" value="phone_number" id="phone_number">
                                    <?php echo $this->lang->line('phone_number') ?>
                                </label>
                            </li>
                            <li class="nav-item w-100" role="presentation">
                                <label for="email" class="radioemail nav-link <?php echo ($this->session->userdata('login_with')=='email')?'btn-outline-primary':''; ?>" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">
                                    <input type="radio" name="login_with" value="email" id="email">
                                    <?php echo $this->lang->line('email') ?>
                                </label>
                            </li>
                        </ul>
                        <div class="tab-content mb-4" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="form-floating">
                                    <input type="hidden" name="phone_code" id="phone_code" class="form-control" value="<?php if(isset($adminCook['phone_code'])) { echo $adminCook['phone_code']; } ?>">
                                    <input type="tel" name="phone_number_inp" id="phone_number_inp" class="form-control" placeholder="" value="<?php if(isset($adminCook['usr'])) { echo $adminCook['usr']; } ?>" maxlength='12'>
                                    <label><?php echo $this->lang->line('phone_number') ?></label>

                                    <div class="phn_err error"  style="display: none;"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="form-floating">
                                    <input type="email" name="email_inp" id="email_inp" class="form-control" placeholder=" " value="<?php if(isset($adminCook['usr'])) { echo $adminCook['usr']; } ?>" maxlength='50'>
                                    <label><?php echo $this->lang->line('email') ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-floating">
                            <input type="password" name="password" id="password" class="form-control" placeholder=" " value="<?php  if(isset($adminCook['hash'])) { echo $adminCook['hash']; } ?>" >
                            <label><?php echo $this->lang->line('password') ?></label>
                            <i id="togglePasswordshow" class="icon icon-input icon-eye"></i>
                        </div>
                        <div class="form-floating d-flex flex-column flex-sm-row justify-content-sm-between align-sm-items-center">
                            <div class="form-check mb-2 mb-sm-0">
                                <input type="checkbox" name="rememberMe" id="rememberMe" class="form-check-input" value="1" <?php echo ($adminCook)?"checked":""?> />
                                <label class="form-check-label" for="rememberMe"><?php echo $this->lang->line('remember') ?></label>
                            </div>
                             <a href="javascript:void(0)" class="text-decoration-underline" data-toggle="modal" data-target="#forgot-pass-modal"><?php echo $this->lang->line('forgot_pass') ?></a> 
                        </div>

                        <div class="form-floating">
                            <button type="submit" name="submit_page" id="submit_page" value="Login" class="btn btn-primary w-100"><?php echo $this->lang->line('title_login') ?></button>
                            <!-- <input type="submit" name="submit_page" id="submit_page" value="Login" class="btn btn-primary"> -->
                            <!-- title_login -->
                        </div>
                        <?php /*if(!empty($this->session->flashdata('error_MSG'))) {?>
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('error_MSG');?>
                        </div>
                        <?php }*/ ?>
                        <?php 
                        if($_SESSION['error_MSG'])
                        { ?>
                            <div class="alert alert-danger errormsg">
                                <?php echo $_SESSION['error_MSG'];
                                    unset($_SESSION['error_MSG']);
                                ?>
                            </div>
                        <?php } ?>
                        <?php if(validation_errors()){?>
                        <div class="alert alert-danger">
                            <?php echo validation_errors();?>
                        </div>
                        <?php } ?>
                        <span class="text-center d-inline-block w-100"><?php echo $this->lang->line('dont_have_account') ?> <a href="<?php echo base_url().'home/registration';?>" class="text-decoration-underline"><?php echo $this->lang->line('sign_up') ?></a></span>
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
                        <div id="verify_otp_section">
                            <h4 class="title pb-2 mb-6 text-capitalize"><?php echo $this->lang->line('verify_otp') ?></h4>
                            <h6 class="mb-1" id="enter_otp_text"><?php echo $this->lang->line('enter_otp') ?></h6>
                            <form id="form_front_verifyotp" name="form_front_verifyotp" method="post" class="form-horizontal float-form"  data-autosubmit="false" autocomplete="off">
                                <div class="form-group mb-4 otp-form user_otp_divmodal digit-group" style="<?php echo ($this->session->userdata('facebook_user') == 'yes' || $this->session->userdata('google_user') == 'yes')?'display: none;': ''; ?>">
                                    
                                    <div class="d-flex">
                                        <input class="form-control px-0 text-center" type="text" id="digit-1" name="digit-1" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}" data-next="digit-2" class="smsCode" required />
                                        <div class="me-1"></div>
                                        <input class="form-control px-0 text-center" type="text" id="digit-2" name="digit-2" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}" data-next="digit-3" data-previous="digit-1" class="smsCode" required />
                                        <div class="me-1"></div>
                                        <input class="form-control px-0 text-center" type="text" id="digit-3" name="digit-3" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}" data-next="digit-4" data-previous="digit-2" class="smsCode" required />
                                        <div class="me-1"></div>
                                        <input class="form-control px-0 text-center" type="text" id="digit-4" name="digit-4" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}" data-next="digit-5" data-previous="digit-3" class="smsCode" required />
                                        <div class="me-1"></div>
                                        <input class="form-control px-0 text-center" type="text" id="digit-5" name="digit-5" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}" data-next="digit-6" data-previous="digit-4" class="smsCode" required />
                                        <div class="me-1"></div>
                                        <input class="form-control px-0 text-center" type="text" id="digit-6" name="digit-6" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}" data-previous="digit-5" class="smsCode" required />
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
                                    <input type="hidden" name="is_forgot_pwd" id="is_forgot_pwd" class="form-control" value="0">
                                    <input type="hidden" name="forgot_pwd_userid" id="forgot_pwd_userid" class="form-control" value="0">
                                    <input type="tel" name="mobile_number" id="mobile_number" class="form-control" placeholder="" value="<?php if(isset($_POST["mobile_number"])){ echo $_POST["mobile_number"]; } ?>" maxlength='12'>
                                    <label><?php echo $this->lang->line('phone_number') ?></label>
                                    <div id="start_with_zero" class="error" style="display: none;"></div>
                                </div>

                                <div class="action-button">                                    
                                    <button type="submit" name="verifyotp_submit_page" id="verifyotp_submit_page" value="<?php echo ($this->session->userdata('facebook_user') == 'yes' || $this->session->userdata('google_user') == 'yes')?'resend_submit':'Submit'; ?>" class="btn btn-primary w-100"><?php echo $this->lang->line('continue') ?></button>
                                </div>
                                <div class="alert alert-success mt-4" id="verifyotp_success" style="display: none;"></div>
                                <div class="alert alert-danger mt-4" id="verifyotp_error" style="display: none;"></div>
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

    <div class="modal fade show" tabindex="-1" role="dialog" id="forgot-pass-modal">
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
                        <div id="forgot_password_section">
                            <h4 class="title pb-2 mb-6 text-capitalize"><?php echo $this->lang->line('forgot_password') ?></h4>
                            <h6 class="mb-1"><?php echo $this->lang->line('enter_your_phn_no') ?></h6>
                            <!-- action="<?php //echo base_url().'home/forgot_password';?>" -->
                            <form id="form_front_forgotpass" name="form_front_forgotpass" method="post" class="form-horizontal float-form">
                                <div class="form-floating">
                                    <!-- <input type="email" name="email_forgot" id="email_forgot" class="form-control" placeholder=" " maxlength='50'> -->
                                    <input type="hidden" name="phone_code_first" id="phone_code_first" class="form-control" value="">
                                    <input type="tel" name="mobile_number_first" id="mobile_number_first" class="form-control" placeholder="" value="<?php if(isset($_POST["mobile_number"])){ echo $_POST["mobile_number"]; } ?>" maxlength='12'> 
                                    <div id="start_with_zero_first" class="error"></div>
                                    <label><?php echo $this->lang->line('phone_number') ?></label>
                                </div>
                                <div class="action-button">
                                    <button type="submit" name="forgot_submit_page" id="forgot_submit_page" disabled value="Submit" class="btn btn-primary w-100"><?php echo $this->lang->line('submit') ?></button>
                                </div>
                                <div class="alert alert-danger mt-4" id="forgot_error" style="display: none;"></div>
                                <?php if(validation_errors()){?>
                                <div class="alert alert-danger">
                                    <?php echo validation_errors();?>
                                </div>
                                <?php } ?>
                                 <div class="alert alert-success mt-4" id="forgot_success" style="display: none;"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade show" tabindex="-1" role="dialog" id="change-pass-modal">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <a href="javascript:void(0)" class="btn-close icon" data-dismiss="modal" aria-label="Close"><img src="<?php echo base_url();?>assets/front/images/icon-close.svg" alt=""></a>

                <div class="row g-0 row-cols-1 row-cols-xl-2">
                    <div class="col bg-light py-8 px-4 text-center d-flex align-items-center">
                        <figure>
                            <img src="<?php echo base_url();?>assets/front/images/image-account-modal.png" alt="Forgot Password Image">
                        </figure>
                    </div>
                    <div class="col p-4 p-xl-8 align-self-cener">
                        <div id="change_pass_section">
                            <h4 class="title pb-2 mb-6 text-capitalize"><?php echo $this->lang->line('change_pass') ?></h4>
                            <h6 class="mb-1"><?php echo $this->lang->line('enter_pwd') ?></h6>
                            <!-- action="<?php //echo base_url().'home/forgot_password';?>" -->
                            <form id="form_front_change_pass" name="form_front_change_pass" method="post" class="form-horizontal float-form">
                                <div class="form-floating">
                                    <input type="password" name="password_forgot_pwd" id="password_forgot_pwd" class="form-control" placeholder=" ">
                                    <label><?php echo $this->lang->line('password') ?></label>
                                    <i id="togglePasswordshow" class="icon icon-input icon-eye"></i>
                                    <div><input type="hidden" name="change_pass_userid" id="change_pass_userid" value="0"></div>
                                </div>
                                <div class="form-floating">
                                    <input type="password" name="confirm_password_forgot_pwd" id="confirm_password_forgot_pwd" class="form-control" placeholder=" ">
                                    <i id="togglePasswordshow" class="icon icon-input icon-eye"></i>
                                    <label><?php echo $this->lang->line('confirm_pass') ?></label>
                                </div>
                                <div class="action-button">
                                    <button type="submit" name="change_pass_submit_page" id="change_pass_submit_page" value="Submit" class="btn btn-primary w-100">
                                        <?php echo $this->lang->line('submit') ?>
                                    </button>
                                </div>
                                <div class="alert alert-danger mt-4" id="change_pass_error" style="display: none;"></div>
                                <div class="alert alert-success mt-4" id="change_pass_success" style="display: none;"></div>
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

    <div class="wait-loader" id="quotes-main-loader" style="display: none;"><img  src="<?php echo base_url();?>assets/admin/img/ajax-loader.gif" align="absmiddle"  ></div>

    <?php if($this->session->userdata('enter_otp')=='yes' && ($this->input->get('frm_page')) && $this->input->get('frm_page')=='loginpage') { ?>
        <script> $("#verify-otp-modal").modal('show');</script>
    <?php } elseif ($this->session->userdata('enter_otp')=='no') {?>
        <script> $("#verify-otp-modal").modal('hide');</script>
    <?php } ?>

    <script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/jquery.validate.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/js/scripts/admin-management-front.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/front/js/scripts/front-validations.js"></script>
    
    <?php if($this->session->userdata("language_slug")=='fr'){  ?>
        <script type="text/javascript" src="<?php echo base_url()?>assets/admin/pages/scripts/localization/messages_fr.js"> </script>
    <?php } elseif ($this->session->userdata("language_slug")=='ar') { ?>
        <script type="text/javascript" src="<?php echo base_url()?>assets/admin/pages/scripts/localization/messages_ar.js"> </script>   
    <?php } ?>

    <script type="text/javascript">
        $(document).ready(function() {
            if($('.errormsg').length==1){
                $('html, body').animate({
                    scrollTop: $(".errormsg").offset().top
                }, 500);
            }
        });
    </script>
    <script type="text/javascript">
        // submit verify otp form
        $("#form_front_verifyotp").on("submit", function (event) {
            event.preventDefault();
            var otp_entered = $("#digit-1").val() + $("#digit-2").val() + $("#digit-3").val() + $("#digit-4").val() + $("#digit-5").val() + $("#digit-6").val();
            $("#user_otp").val(otp_entered);
            var is_forgot_pwd = "";
            var forgot_pwd_userid = "";
            if ($("#is_forgot_pwd").val() == "1") {
                is_forgot_pwd = $("#is_forgot_pwd").val();
                forgot_pwd_userid = $("#forgot_pwd_userid").val();
            }
            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: BASEURL + "home/verify_otp",
                data: {
                    user_otp: $("#user_otp").val(),
                    phone_code_otp: $("#phone_code_otp").val(),
                    mobile_number: $("#mobile_number").val(),
                    verifyotp_submit_page: $("#verifyotp_submit_page").val(),
                    is_forgot_pwd: is_forgot_pwd,
                    forgot_pwd_userid: forgot_pwd_userid,
                },
                beforeSend: function () {
                    $("#quotes-main-loader").show();
                },
                success: function (response) {
                    $("#verifyotp_error").hide();
                    $("#verifyotp_success").hide();
                    $("#quotes-main-loader").hide();
                    if (response) {
                        if (response.verifyotp_error != "") {
                            $("#verifyotp_error").html(response.verifyotp_error);
                            $("#verifyotp_success").hide();
                            $("#verifyotp_error").show();
                            /*if(response.phn_not_exist != '1'){
                        $("#verifyotp_resend").css("display", "inline-block");
                    }*/
                            $("#user_otp").val("");
                            $("#digit-1").val("");
                            $("#digit-2").val("");
                            $("#digit-3").val("");
                            $("#digit-4").val("");
                            $("#digit-5").val("");
                            $("#digit-6").val("");
                            //$("#verifyotp_submit_page").css("display", "none");
                        }
                        if (response.verifyotp_success != "") {
                            if (response.verifyotp_sent == "1") {
                                //resend otp
                                $("#verifyotp_submit_page").val("Submit");
                                //$("#user_otp").css("display", "block");
                                $(".user_otp_divmodal").css("display", "block");
                                $("#mobile_number").removeAttr("required");
                                $("#digit-1").attr("required", "true");
                                $("#digit-2").attr("required", "true");
                                $("#digit-3").attr("required", "true");
                                $("#digit-4").attr("required", "true");
                                $("#digit-5").attr("required", "true");
                                $("#digit-6").attr("required", "true");

                                $("#verifyotp_modaltitle").text("<?php echo $this->lang->line('verify_otp') ?>");
                                $("#enter_otp_text").text("<?php echo $this->lang->line('enter_otp') ?>");

                                $("#verifyotp_submit_page").css("display", "block");
                                $(".mobile_number_divmodal").css("display", "none");
                                $("#mobile_number").val("");
                            } else {
                                //otp verified
                                if (response.is_forgot_pwd == "1") {
                                    $("#verify-otp-modal").modal("hide");
                                    $("#change_pass_userid").val(forgot_pwd_userid);
                                    $("#change-pass-modal").modal("show");
                                    return;
                                } else {
                                    $("#verify_otp_section").hide();
                                    $("#verifyotp_success").html(response.verifyotp_success);
                                    $("#verifyotp_success").show();
                                    $("#name").removeAttr("required");
                                    $("#email").removeAttr("required");
                                    $("#phone_number").removeAttr("required");
                                    $("#password").removeAttr("required");
                                    location.reload();
                                    //$('#form_front_registration').submit();
                                    window.setTimeout(function () {
                                        $("#form_front_login").submit();
                                    }, 5000);
                                    //$("#verify-otp-modal").modal('hide');
                                }
                            }
                            $("#verifyotp_success").html(response.verifyotp_success);
                            $("#verifyotp_error").hide();
                            $("#verifyotp_success").show();
                        }
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                },
            });
        });
        // submit verify OTP form hidden

        $("#verify-otp-modal").on("hidden.bs.modal", function (e) {
            $(this).find("input[type=number]").val("").end();
            $("#form_front_verifyotp")
                .validate({
                    errorPlacement: function (error, element) {
                        if (
                            element.attr("name") == "digit-1" ||
                            element.attr("name") == "digit-2" ||
                            element.attr("name") == "digit-3" ||
                            element.attr("name") == "digit-4" ||
                            element.attr("name") == "digit-5" ||
                            element.attr("name") == "digit-6"
                        ) {
                            error.appendTo($(".otp_error_div"));
                        } else {
                            error.insertAfter(element);
                        }
                    },
                })
                .resetForm();
            $("#verifyotp_success").text("");
            $("#verifyotp_error").text("");
            $("#verifyotp_success").hide();
            $("#verifyotp_error").hide();
            $("#verify_otp_section").show();
            $("#user_otp").val("");
        });

        $("#verifyotp_resend").click(function () {
            $("#user_otp").val("");
            $("#digit-1").val("");
            $("#digit-2").val("");
            $("#digit-3").val("");
            $("#digit-4").val("");
            $("#digit-5").val("");
            $("#digit-6").val("");
            $("#verifyotp_success").hide();
            $("#verifyotp_submit_page").val("resend_submit");
            //$("#verifyotp_resend").css("display", "none");
            $("#user_otp").css("display", "none");
            $(".user_otp_divmodal").css("display", "none");
            $("#digit-1").removeAttr("required");
            $("#digit-2").removeAttr("required");
            $("#digit-3").removeAttr("required");
            $("#digit-4").removeAttr("required");
            $("#digit-5").removeAttr("required");
            $("#digit-6").removeAttr("required");
            $("#verifyotp_error").text("");
            $("#verifyotp_error").hide();
            $("#verifyotp_modaltitle").text("<?php echo $this->lang->line('resend_otp') ?>");
            if (SELECTED_LANG == "fr") {
                $("#enter_otp_text").text("Veuillez entrer votre numéro de téléphone.");
            } else if (SELECTED_LANG == "ar") {
                $("#enter_otp_text").text("يرجى إدخال رقم الهاتف الخاص بك.");
            } else {
                $("#enter_otp_text").text("Please enter your mobile number.");
            }
            $("#verifyotp_submit_page").css("display", "block");
            $("#mobile_number").attr("required", "true");
            $(".mobile_number_divmodal").css("display", "inline-block");
        });

        $(".digit-group").find("input").each(function () {
            //restricting to enter more than 10 digits
            if ($(this).attr("id") == "mobile_number") {
                /*$('input[type=number][max]:not([max=""])').on('input', function(ev) {
                var phn_no_maxlength = $(this).attr('max').length;
                var value = $(this).val();
                if (value && value.length >= phn_no_maxlength) {
                  $(this).val(value.substr(0, phn_no_maxlength));
                }
            });*/
            } else {
                $(this).attr("maxlength", 1);
            }

            $(this).on("keyup", function (e) {
                e.preventDefault();
                var initial_input = $(this).val();
                $(this).val(initial_input.replace(/\D/g, ""));
                var final_input_val = $(this).val().substr(0, 1);
                $(this).val(final_input_val);
                var input_ascii_code = $(this).val().charCodeAt(0);

                var parent = $($(this).parent());
                if (e.keyCode === 8 || e.keyCode === 37) {
                    var prev = parent.find("input#" + $(this).data("previous"));
                    if (prev.length) {
                        $(prev).select();
                    }
                } else if ((e.keyCode >= 48 && e.keyCode <= 57) || e.keyCode === 39 || (e.keyCode >= 96 && e.keyCode <= 105) || (input_ascii_code != NaN && input_ascii_code >= 48 && input_ascii_code <= 57)) {
                    var next = parent.find("input#" + $(this).data("next"));
                    if (next.length) {
                        $(next).select();
                    } else {
                        if (parent.data("autosubmit")) {
                            parent.submit();
                        }
                    }
                } else {
                    //$(this).val('');
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
    
    <!-- <script type="text/javascript">
        document.querySelector("[type='password']").classList.add("input-password");document.getElementById("toggle-password").classList.remove("d-none");const passwordInput=document.querySelector("[type='password']");const togglePasswordButton=document.getElementById("toggle-password");togglePasswordButton.addEventListener("click",togglePassword);function togglePassword(){if(passwordInput.type==="password"){passwordInput.type="text";togglePasswordButton.setAttribute("aria-label","Hide password.")}else{passwordInput.type="password";togglePasswordButton.setAttribute("aria-label","Show password as plain text. "+"Warning: this will display your password on the screen.")}}
    </script> -->
    
    <?php
        if(!empty(website_footer_script)){
            echo website_footer_script;
        }
    ?>

    <script type="text/javascript">
        //intl-tel-input plugin
        var onedit_iso = '';
        <?php if($this->session->userdata('phone_codeval')) {
            $onedit_iso = $this->common_model->getIsobyPhnCode($this->session->userdata('phone_codeval')); ?>
            onedit_iso = '<?php echo $onedit_iso; ?>'; //saved in session
        <?php }

        if(isset($adminCook['phone_code'])) { // for remember me
            $onedit_iso = $this->common_model->getIsobyPhnCode($adminCook['phone_code']); ?>
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
        const phoneInputField = document.querySelector("#phone_number_inp");
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
        
        $(document).on('input','#phone_number_inp',function(){
            event.preventDefault();
            var phoneNumber = phoneInput.getNumber();
            if (phoneInput.isValidNumber()) {
                var countryData = phoneInput.getSelectedCountryData();
                var countryCode = countryData.dialCode;
                $('#phone_code').val(countryCode);
                phoneNumber = phoneNumber.replace('+'+countryCode,'');
                $('#phone_number_inp').val(phoneNumber);
            }
            if (event.keyCode == 13) {
                $("#form_front_verifyotp").submit();   
                return false;
            }
        });
        
        $(document).on('focusout','#phone_number_inp',function(){
            event.preventDefault();
            var phoneNumber = phoneInput.getNumber();
            if (phoneInput.isValidNumber()) {
                var countryData = phoneInput.getSelectedCountryData();
                var countryCode = countryData.dialCode;
                $('#phone_code').val(countryCode);
                phoneNumber = phoneNumber.replace('+'+countryCode,'');
                $('#phone_number_inp').val(phoneNumber);
            }
        });
        
        phoneInputField.addEventListener("close:countrydropdown",function() {
            var phoneNumber = phoneInput.getNumber();
            if (phoneInput.isValidNumber()) {
                var countryData = phoneInput.getSelectedCountryData();
                var countryCode = countryData.dialCode;
                $('#phone_code').val(countryCode);
                phoneNumber = phoneNumber.replace('+'+countryCode,'');
                $('#phone_number_inp').val(phoneNumber);
            }
        });
        //phone number login form :: end

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

        //#########################################
        // Initialize the intl-tel-input plugin
        const phoneInputFieldfirst = document.querySelector("#mobile_number_first");
        const phoneInputfirst = window.intlTelInput(phoneInputFieldfirst, {
            initialCountry: initial_preferred_iso,
            preferredCountries: [initial_preferred_iso],
            onlyCountries: country_iso,
            separateDialCode:true,
            formatOnDisplay:false,
            autoPlaceholder:"polite",
            utilsScript: BASEURL+'assets/admin/plugins/intl_tel_input/utils.js'
                //"https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });

        $(document).on('input','#mobile_number_first',function(){
            event.preventDefault();
            var phoneNumber = phoneInputfirst.getNumber();
            if (phoneInputfirst.isValidNumber()) {
                $("#start_with_zero_first").css("display", "none");
                $("#start_with_zero_first").val('');
                $("#forgot_submit_page").prop('disabled', false);
                var countryData = phoneInputfirst.getSelectedCountryData();
                var countryCode = countryData.dialCode;
                $('#phone_code_first').val(countryCode);
                phoneNumber = phoneNumber.replace('+'+countryCode,'');
                $('#mobile_number_first').val(phoneNumber);
            }
            else
            {
                $('#start_with_zero_first').text("<?php echo $this->lang->line('enter_valid_phn'); ?>");
                $("#start_with_zero_first").css("display", "block");
                $("#forgot_submit_page").prop('disabled', true);
            }

        });

        $(document).on('focusout','#mobile_number_first',function(){
            event.preventDefault();
            var phoneNumber = phoneInputfirst.getNumber();
            if (phoneInputfirst.isValidNumber()) {
                $("#start_with_zero_first").css("display", "none");
                $("#start_with_zero_first").val('');
                $("#forgot_submit_page").prop('disabled', false);
                var countryData = phoneInputfirst.getSelectedCountryData();
                var countryCode = countryData.dialCode;
                $('#phone_code_first').val(countryCode);
                phoneNumber = phoneNumber.replace('+'+countryCode,'');
                $('#mobile_number_first').val(phoneNumber);
            }
            else {
                $('#start_with_zero_first').text("<?php echo $this->lang->line('enter_valid_phn'); ?>");
                $("#start_with_zero_first").css("display", "block");
                $("#forgot_submit_page").prop('disabled', true);
            }
        });
        
        phoneInputFieldfirst.addEventListener("close:countrydropdown",function() {    
            var phoneNumber = phoneInputfirst.getNumber();
            if (phoneInputfirst.isValidNumber()) {
                $("#start_with_zero_first").css("display", "none");
                $("#start_with_zero_first").val('');
                $("#forgot_submit_page").prop('disabled', false);
                var countryData = phoneInputfirst.getSelectedCountryData();
                var countryCode = countryData.dialCode;
                $('#phone_code_first').val(countryCode);
                phoneNumber = phoneNumber.replace('+'+countryCode,'');
                $('#mobile_number_first').val(phoneNumber);
            }else {
                $('#start_with_zero_first').text("<?php echo $this->lang->line('enter_valid_phn'); ?>");
                $("#start_with_zero_first").css("display", "block");
                $("#forgot_submit_page").prop('disabled', true);
            }
        });
        
        $('#forgot-pass-modal').on('hidden.bs.modal', function (e) {  
          $('#mobile_number_first').val('');
          $("#start_with_zero_first").css("display", "none");
          $("#start_with_zero_first").val('');
          $("#forgot_submit_page").prop('disabled', true);
        });
        //#########################################

       
        
        $(document).ready(function() {
            var sess_login_with = '<?php echo $this->session->userdata('login_with'); ?>';
            $('#form_front_login').validate().resetForm();
            if(sess_login_with == 'email'){
                $("#phone_number_inp").val('');
                $("#phone_number").removeAttr("checked");
                
                $("#email").attr("checked","checked");
            }else {
                $("#email_inp").val('');
                $("#email").removeAttr("checked");
            
                $("#phone_number").attr("checked","checked");
            }

             // radio btns js :: start
               // radio btns js :: start
                $(".nav-link").click(function(){
                    event.preventDefault();
                    console.log("hello");

                    $('#' + $(this).attr("for")).prop('checked', true);

                  $('#form_front_login').validate().resetForm();
                  if($("input[name=login_with]:checked").val() == "phone_number" ){
                    $("#email_inp").val('');
                    $("#email").removeAttr("checked");
            
                    $("#phone_number").attr("checked","checked");
                    
                  }else if($("input[name=login_with]:checked").val() == "email" ){
                    $("#phone_number_inp").val('');
                    $("#phone_number").removeAttr("checked");
            
                    $("#email").attr("checked","checked");
                  }
                });
        });

        // radio btns js :: end
         $('#submit_page').click(function(){
            if($("#home-tab").hasClass('active')){
                if($('#phone_number_inp').val()==''){
                    $('#phone_number_inp').focus();
                }
            }
            else {
                if($('#email_inp').val()==''){
                    $('#email_inp').focus();
                }
            }
        });

        /*document.querySelector("#password_forgot_pwd").classList.add("input-password");
        document.getElementById("toggle-password1").classList.remove("d-none");
        const passwordInput1=document.querySelector("#password_forgot_pwd");
        const togglePasswordButton1=document.getElementById("toggle-password1");
        togglePasswordButton1.addEventListener("click",togglePassword1);
        function togglePassword1(){
            if(passwordInput1.type==="password"){
                passwordInput1.type="text";
                togglePasswordButton1.setAttribute("aria-label","Hide password.")
            } else {
                passwordInput1.type="password";
                togglePasswordButton1.setAttribute("aria-label","Show password as plain text. "+"Warning: this will display your password on the screen.")
            }
        }
        document.querySelector("#confirm_password_forgot_pwd").classList.add("input-password");
        document.getElementById("toggle-password2").classList.remove("d-none");
        const passwordInput2=document.querySelector("#confirm_password_forgot_pwd");
        const togglePasswordButton2=document.getElementById("toggle-password2");
        togglePasswordButton2.addEventListener("click",togglePassword2);
        function togglePassword2(){
            if(passwordInput2.type==="password"){
                passwordInput2.type="text";
                togglePasswordButton2.setAttribute("aria-label","Hide password.")
            } else {
                passwordInput2.type="password";
                togglePasswordButton2.setAttribute("aria-label","Show password as plain text. "+"Warning: this will display your password on the screen.")
            }
        }*/
        //change password
        $("#form_front_change_pass").on("submit", function(event) { 
          event.preventDefault();
          if($('#form_front_change_pass').valid()){
              jQuery.ajax({
                type : "POST",
                dataType :"json",
                url : BASEURL+'home/change_password',
                data : {'password':$('#password_forgot_pwd').val(),'confirm_password': $('#confirm_password_forgot_pwd').val(), 'change_pass_submit_page': $('#change_pass_submit_page').val(), 'change_pass_userid': $("#change_pass_userid").val()},
                beforeSend: function(){
                    $('#quotes-main-loader').show();
                },
                success: function(response) {
                    $('#quotes-main-loader').hide();
                    $('#change_pass_section').hide();
                    $('#change_pass_success').text(response.change_pass_success);
                    $('#change_pass_success').show();
                    setTimeout(function(){
                        location.reload();
                        $("#change-pass-modal").modal('hide');
                    }, 1000);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {           
                    alert(errorThrown);
                }
              });
            }
        });

    </script>
</body>
</html>