<?php
/*
	* Plugin Name: 		User Referral ( Free )
	* Plugin URI: 		https://softclever.com/downloads/
	* Description: 		A powerful referral system plugin for WordPress that allows users to earn points and rewards for referring new visitors and signups to your website.
	
	* Author: 			Md Maruf Adnan Sami
	* Author URI: 		https://facebook.com/RealboyAdnan
	* Version: 			1.0
	
	* Text Domain: 		user-referral-free
	* Copyright: 		(c) 2018 SoftClever Limited
*/

// Link all the settings //
require_once plugin_dir_path(__FILE__)."user-options.php";
require_once plugin_dir_path(__FILE__)."user-hooks.php";

// Get settings link 
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'scur_add_settings_link' );

// Register activation hook
register_activation_hook(__FILE__, 'scur_plugin_activate_action');

// Plugin Data delete hook //
register_deactivation_hook(__FILE__, 'scur_delete_data_on_deactivated');
?>