<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
	$ci = new CI_Controller();
	$ci =& get_instance();
	$ci->load->helper('url');
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
		<title>404 Page Not Found</title>
		<link href="<?php echo base_url();?>assets/front/css/styles.css" rel="stylesheet" type="text/css"/>

		<style type="text/css">
			h1 {
				font-size: 10vw;
				line-height: 1;
			}

			@media (max-width: 767px) {
				h1 {
					font-size: 76px;
					line-height: 1;
				}
			}
		</style>
	</head>
	<body>
		<section class="min-vh-100 min-vw-100 d-flex align-items-center justify-content-center text-center px-4">
			<figure class="picture absolute-div">
				<img src="<?php echo base_url(); ?>/assets/front/images/background-error.jpg">
			</figure>
			<div class="small-container bg-white py-10 px-4 px-md-10 px-xl-20 position-relative d-inline-block">
				<h1>404</h1>
				<h3 class="text-body">Opps ! Something went wrong...</h3>
				<a class="btn btn-primary mt-4 mt-md-6 mt-xl-8" href="<?php echo base_url(); ?>">Go Back to Home</a>
			</div>
		</section>
	</body>
</html>