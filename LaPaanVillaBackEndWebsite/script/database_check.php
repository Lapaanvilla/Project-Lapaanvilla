<?php include ('header.php');
if(isset($_POST['SubmitButton'])){ // Check if form was submitted
    $host = $_POST['host']; // Get input text
    $username = $_POST['user']; // Get input text
    $password = $_POST['password']; // Get input text
    // Create connection
    $conn = @new mysqli($host, $username, $password);
// Check connection
    if ($conn->connect_error) {
        $alert = 1;
    }
    else {
        $alert = 0;

    }
}
?>
<div class="container main--bg">
    <div class="row">
        <div class="col-md-2">&nbsp;</div>
        <div class="col-md-5" style="text-align: left;">
            <h3 style="text-align: left;margin: 10px 0;"><span style="text-align: center; display: inline-block; width: 45px; height: 45px; line-height: 45px; background: #fcb316; color: #fff; font-size: 25px; border-radius: 50%; margin-right: 20px;">3</span>Database Connection</h3>
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
        <div class="col-sm-3">&nbsp;</div>
        <div class="col-sm-6">
            <?php if(isset($alert)){
                if($alert==0){
                    ?>
                    <div class="alert alert-success fade in">
                        <?php
                        echo "Connected successfully";
                        ?>
                    </div>
                    <?php
                }
                else{
                    ?>
                    <div class="alert alert-danger fade in">
                        <?php
                        echo "Connection failed: " . $conn->connect_error;
                        ?>
                    </div>
                    <?php
                }

            }
            ?>
        </div>
        <div class="col-sm-3">&nbsp;</div>
    </div>
    <div class="row">
        <div class="col-sm-2">&nbsp;</div>
        <div class="col-sm-8">
            <form role="form"  id="databaseform" name="databaseform" method="post" action="#">
                <div class="form-group row text-right">
                    <label for="host" class="col-sm-2 col-form-label">Host</label>
                    <div class="col-sm-10">
                        <input value="<?php if(isset($_POST['host'])){ echo $_POST['host']; }?>" name="host" type="text" size="40" class="form-control"  placeholder="Host" required/>
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
                <div class="form-group row">
                    <div class="col-sm-12 text-right">
                        <button type="submit"  class="btn btn-primary" id="SubmitButton" name="SubmitButton">Test Database</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-2">&nbsp;</div>
    </div>
    <div class="row">
        <div class="col-md-12" style="height: 50px;">&nbsp;</div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <ul class="pager">
                <li class="previous"><a href="mail_test.php">Previous</a></li>
            </ul>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>