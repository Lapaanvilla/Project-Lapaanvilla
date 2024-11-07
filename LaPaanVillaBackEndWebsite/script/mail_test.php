<?php
if(isset($_POST['sendemail'])) {
        ini_set('display_errors', 1);
        require 'sendmail/PHPMailerAutoload.php';
        $mail = new PHPMailer;
        $mail->isSMTP();
        //$mail->Mailer="mail";
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';
        if($_POST['smtp_secure']=='ssl') {
            $mail->Host = 'ssl://' . $_POST['host'];
        }
        else {
            $mail->Host = $_POST['host'];
        }
        $mail->Port = $_POST['port'];//587 // 465;
    if($_POST['smtp_secure']=='tls') {
        $mail->SMTPSecure = $_POST['smtp_secure'];
    }
        $mail->SMTPAuth = true;
        $mail->Username = $_POST['user'];
        $mail->Password = $_POST['password'];
// Send email for Contact us
        $mail->setFrom($_POST['from']);
        $toemail = explode(',',$_POST['toemail']);
        foreach ($toemail as $to) {
            $mail->addAddress($to);
        }
        $mail->Subject = $_POST['subject'];
        $mail->msgHTML($_POST['message']);

        if($mail->Send()){
            $alert=0;
        }
        else{
            $alert=1;
        }
}
?>
<?php include ('header.php'); ?>


<div class="container main--bg" style="position: inherit; left: inherit; top: inherit; background: #f7f5f6; padding: 30px 50px; border-radius: 10px; border: 1px solid #e4e2e3; -webkit-transform: inherit !important; -moz-transform: inherit; transform: inherit !important;">

    <div class="row">
        <div class="col-md-2">&nbsp;</div>
        <div class="col-md-5" style="text-align: left;">
            <h3 style="text-align: left;margin: 10px 0;"><span style="text-align: center; display: inline-block; width: 45px; height: 45px; line-height: 45px; background: #fcb316; color: #fff; font-size: 25px; border-radius: 50%; margin-right: 20px;">2</span>SMTP Configuration</h3>
        </div>
        <div class="col-md-3" style="text-align: right;">
            <img src="images/logo.svg" width="225px" height="61px">
        </div>
        <div class="col-md-2">&nbsp;</div>
    </div>

    <div class="row">
        <div class="col-md-12" style="height: 50px;">&nbsp;</div>
    </div>

    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <?php if(isset($alert)){
                if($alert==0){
                    ?>
                    <div class="alert alert-success fade in">
                        <?php
                        echo "Mail sent successfully";
                        ?>
                    </div>
                    <?php
                }
                else{
                    ?>
                    <div class="alert alert-danger fade in">
                        <?php
                        echo "Mail not sent" . $mail->ErrorInfo;
                        ?>
                    </div>
                    <?php
                }

            }
            ?>
        </div>
        <div class="col-sm-3"></div>
    </div>
    <div class="row">
        <div class="col-md-2">&nbsp;</div>
        <div class="col-md-8">
            <form role="form"  id="mailform" name="mailform" method="post" action="mail_test.php">

                <div class="form-group row text-right">
                    <label for="host" class="col-sm-2 col-form-label">Host</label>
                    <div class="col-sm-10">
                <input value="<?php if(isset($_POST['host'])){ echo $_POST['host']; }?>" name="host" type="text" size="40" class="form-control"  placeholder="Host" required/>
                    </div>
                </div>
                <div class="form-group row text-right">
                    <label for="port" class="col-sm-2 col-form-label">Port</label>
                    <div class="col-sm-10">
                        <input value="<?php if(isset($_POST['port'])){ echo $_POST['port']; }?>" name="port" type="text" size="40" class="form-control"  placeholder="Port"  required/>
                    </div>
                </div>
                <div class="form-group row text-right">
                    <label for="user" class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-10">
                        <input value="<?php if(isset($_POST['user'])){ echo $_POST['user']; }?>" name="user" type="text" size="40" class="form-control"  placeholder="Username" required/>
                    </div>
                </div>
                <div class="form-group row text-right">
                    <label for="password" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input value="<?php if(isset($_POST['password'])){ echo $_POST['password']; }?>" name="password" type="text" size="40" class="form-control"  placeholder="Password" required/>
                    </div>
                </div>
                <div class="form-group row text-right">
                    <label for="smtp_secure" class="col-sm-2 col-form-label">SMTP Secure</label>
                    <div class="col-sm-10">
                        <select name="smtp_secure" class="form-control" required>
                            <option value="ssl" <?php if(isset($_POST['smtp_secure'])){ if($_POST['smtp_secure']=='ssl'){ echo "selected"; } }?>>SSL</option>
                            <option value="tls" <?php if(isset($_POST['smtp_secure'])){ if($_POST['smtp_secure']=='tls'){ echo "selected"; } }?>>TLS</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row text-right">
                    <label for="to" class="col-sm-2 col-form-label">To</label>
                    <div class="col-sm-10">
                        <input value="<?php if(isset($_POST['toemail'])){ echo $_POST['toemail']; }?>" name="toemail" type="text" size="40" class="form-control"  placeholder="To" required/>
                    </div>
                </div>
                <div class="form-group row text-right">
                    <label for="from" class="col-sm-2 col-form-label">From</label>
                    <div class="col-sm-10">
                        <input value="<?php if(isset($_POST['from'])){ echo $_POST['from']; }?>" name="from" type="text" size="40" class="form-control"  placeholder="From" required/>
                    </div>
                </div>
                <div class="form-group row text-right">
                    <label for="subject" class="col-sm-2 col-form-label">Subject</label>
                    <div class="col-sm-10">
                        <input value="<?php if(isset($_POST['subject'])){ echo $_POST['subject']; }?>" name="subject" type="text" size="40" class="form-control"  placeholder="Subject" required/>
                    </div>
                </div>

                <div class="form-group row text-right">
                    <label for="auto" class="col-sm-2 col-form-label">Message</label>
                    <div class="col-sm-10">
                        <textarea name="message" rows="4" cols="40" class="form-control" required>
                        <?php
                            if(isset($_POST['message'])){
                                echo $_POST['message'];
                            }
                            else{
                                echo "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed tempor incididunt ut labore et dolore magna aliqua . ";
                                }
                                ?>
                        </textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12 text-right">
            	    <button type="submit"  class="btn btn-primary" id="sendemail" name="sendemail">Test SMTP</button>
                    </div>
                </div>
            </form>

        </div>
        <div class="col-md-2">&nbsp;</div>
    </div>
    <div class="row">
        <div class="col-md-12" style="height: 50px;">&nbsp;</div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <ul class="pager">
                <li class="previous"><a href="index.php">Previous</a></li>
                <li class="next"><a href="database_check.php">Next</a></li>
            </ul>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>