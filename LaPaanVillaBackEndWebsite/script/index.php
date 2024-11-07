<?php 
	include ('header.php');
    $extensions= array('OpenSSL','mbstring','mod_rewrite','curl','dom','gd','hash','iconv','pcre','pdo_mysql','simplexml','ctype','filter','zip','xml','gd2');
    $loadedextensions = get_loaded_extensions();
    $exists=array_intersect($loadedextensions,$extensions);
    $result=array_diff($extensions,$loadedextensions);
    
    if(array_search('gd', $exists)){
        if (($key = array_search('gd2', $result)) !== false) {
            unset($result[$key]);
        }
    }
    if(array_search('gd2', $exists)){
        if (($key = array_search('gd', $result)) !== false) {
            unset($result[$key]);
        }
    }
    if(array_search('simplexml', $exists)){
        if (($key = array_search('xml', $result)) !== false) {
            unset($result[$key]);
        }
    }
    if(array_search('xml', $exists)){
        if (($key = array_search('simplexml', $result)) !== false) {
            unset($result[$key]);
        }
    }
    
    if(phpversion()>='5.6'){
        $fail = 0;
    }
    else{
        $fail=1;
    }
    ?>

<div class="container main--bg">
    <div class="row">
        <div class="col-md-2">&nbsp;</div>
        <div class="col-md-5" style="text-align: left;">
            <h3 style="text-align: left;margin: 10px 0;"><span style="text-align: center; display: inline-block; width: 45px; height: 45px; line-height: 45px; background: #fcb316; color: #fff; font-size: 25px; border-radius: 50%; margin-right: 20px;">1</span>PHP Configuration</h3>
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
        <div class="col-md-2">&nbsp;</div>
        <div class="col-md-8">
            <div class="alert alert-success fade in">
                <?php
                if(phpversion()>='5.6') {
                    echo "PHP version is " . phpversion() . "<br/>";
                }
                foreach ($exists as $available){
                    echo "PHP extension ".$available." is loaded"." &#9989;<br/>";
                }
                ?>
            </div>
            <div class="alert alert-danger fade in">
                <?php
                if(phpversion()<'5.6') {
                    echo "PHP version is " . phpversion() . "&#10060;   It must be greater than 5.6 <br/>";
                }
                foreach ($result as $disable){
                    echo "PHP extension ".$disable." is not loaded"." &#10060;<br/>";
                }
                ?>
            </div>
        </div>
        <div class="col-md-2">&nbsp;</div>
    </div>
    <div class="row">
        <div class="col-md-12" style="height: 50px;">&nbsp;</div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <ul class="pager">
                <li class="next"><a href="mail_test.php">Next</a></li>
            </ul>
        </div>
    </div>
</div>
<?php include('footer.php'); echo phpinfo(); ?>