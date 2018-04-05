<?php 

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( get_option('es_offer_christmas_done_email_subscribers') == 'no' ) return;

?>

<style type="text/css">
	.es_offer {
		width: 90%;
		height: auto;
		margin: 1em auto;
		text-align: center;
		background-color: #00003a;
		font-size: 1.2em;
		letter-spacing: 3px;
		line-height: 1.2em;
		padding: 2em;
		background-image: url('<?php echo ES_URL ?>images/christmas.png');
		background-repeat: no-repeat;
		background-size: contain;
		background-position: left;
	}
	.es_offer_heading {
		color: #64ddc1;
		padding: 1em 0;
		line-height: 1.2em;
	}
	.es_main_heading {
		font-size: 3em;
		color: #FFFFFF;
		font-weight: 600;
		margin-bottom: 0.6em;
		line-height: 1.2em;
		position: relative;
	}
	.es_text {
		font-size: 0.9em;
	}
	.es_left_text {
		padding: 0.6em 5.4em 0.6em;
		color: #FFFFFF;
	}
	.es_right_text {
		color: #FFFFFF;
		font-weight: 600;
		max-width: 50%;
		padding: 10px 56px;
		width: auto;
		margin: 0;
		display: inline-block;
		text-decoration: none;
		background: #b70f0f;
	}
	.es_right_text:hover, .es_right_text:active {
		color: inherit; 
	}
	.es_offer_content {
		margin-left: 15%;
	}
</style>

<div class="es_offer">
	<div style="float:right;"><img src="<?php echo ES_URL ?>images/icegram-logo-16bit-gray-30.png"/></div>
		<div  class="es_offer_content">
			<div class="es_offer_heading">It's time to be merry!</div>
			<div class="es_main_heading">Grab FLAT 20% OFF Storewide</div>
			<div class="es_text">
				<div class="es_left_text" style="font-size:1.1em;">Offer applicable on all premium plans of <span style="color:#64ddc1;font-weight:bold">Email Subscribers, Icegram & Rainmaker</span></div>
				<a href="?dismiss_admin_notice=1&option_name=es_offer_christmas_done" target="_blank" class="es_right_text">Start Shopping</a>
				<div class="es_left_text">Offer ends on 26th December, 2017 - so hurry.. </div>
			</div>
		</div>
</div>