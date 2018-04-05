<?php
//Load Mailchimp
require_once('mailchimp.php');
//Load Campaign Monitor
require_once('campaignmonitor.php');
//Load HubSpot
require_once('hubspot.php');

$active_plugins = (array) get_option('active_plugins', array());
if (is_multisite()) {
        $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
}
//Email Subscribers
if ( in_array('email-subscribers/email-subscribers.php', $active_plugins) || array_key_exists('email-subscribers/email-subscribers.php', $active_plugins)) {
	require_once('email_subscribers.php');
}

//MailPoet
if(( in_array('wysija-newsletters/index.php', $active_plugins) || array_key_exists('wysija-newsletters/index.php', $active_plugins) )){
	require_once('mailpoet.php');

}
