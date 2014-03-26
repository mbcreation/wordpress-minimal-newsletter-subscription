<?php

/**
 * Plugin Name: Minimal Newsletter Subscription
 * Description: Collect emails
 * Version: 0.1
 * Author: MB Création
 * Author URI: http://www.mbcreation.net
 *
 */

// Required Classes

require_once('class.front.php');
require_once('class.back.php');
require_once('class.widget.php');

// Loader
function Minimal_Newsletter_Subscription_Loader(){

	load_plugin_textdomain('minewsub', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');

	$GLOBALS['Minimal_Newsletter_Subscription_Plugin_Front'] = new Minimal_Newsletter_Subscription_Plugin_Front();
	$GLOBALS['Minimal_Newsletter_Subscription_Plugin_Front']->update_db_check();
	
	if(is_admin())
	{
		$GLOBALS['Minimal_Newsletter_Subscription_Plugin_Back'] = new Minimal_Newsletter_Subscription_Plugin_Back();
	}
} //WooCommerce_Google_Address_Loader

add_action( 'plugins_loaded' , 'Minimal_Newsletter_Subscription_Loader');