<?php

/**
 *
 * @link              https://shopitpress.com
 * @since             1.0.0
 * @package           sip_social_proof_woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       SIP Social Proof for WooCommerce
 * Plugin URI:        https://shopitpress.com/plugins/sip-social-proof-woocommerce/
 * Description:       Display real time proof of your sales and customers.
 * Version:           1.0.9
 * Author:            ShopitPress <hello@shopitpress.com>
 * Author URI:        https://shopitpress.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Copyright: 		  � 2015 ShopitPress(email: hello@shopitpress.com)
 * Text Domain:       sip-social-proof
 * Domain Path:       /languages
 * Requires: PHP5, WooCommerce Plugin
 * WC requires at least: 2.6.0
 * WC tested up to: 5.6.1
 * Tested up to: 6.0
 * Last updated on: 29 March, 2017
**/

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define( 'SIP_SPWC_PLUGIN_NAME', 'SIP Social Proof for WooCommerce' );
define( 'SIP_SPWC_PLUGIN_SLUG', 'sip-social-proof-woocommerce' );
define( 'SIP_SPWC_PLUGIN_VERSION', '1.0.9' );
define( 'SIP_SPWC_PLUGIN_PURCHASE_URL', 'https://shopitpress.com/plugins/sip-social-proof-woocommerce/' );
define( 'SIP_SPWC_BASENAME', plugin_basename( __FILE__ ) );
define( 'SIP_SPWC_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'SIP_SPWC_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

/**
 * Check if woocommerce plugin is installed or not
 *
 * @since 1.0.0
 *
 * @return bool true/false  Returns true if woocommerce is installed and active
 */

function spwc_activate () {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin = plugin_basename( __FILE__ );

	if( !class_exists( 'WooCommerce' ) ) {
		deactivate_plugins( $plugin );
		add_action( 'admin_notices', 'sip_spwc_admin_notice_error' );
	}
}
add_action( 'plugins_loaded', 'spwc_activate' );

function sip_spwc_admin_notice_error() {
	$class = 'notice notice-error';
	$message = __( 'SIP Social Proof for WooCommerce requires <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a> plugin to be active!', 'sip-social-proof-woocommerce' );

	printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
}

require_once( SIP_SPWC_DIR . 'admin/sip-social-proof-admin.php' );

//add CSS & JS scripts
add_action( 'wp_enqueue_scripts', 'sip_spwc_scripts' );
function sip_spwc_scripts() {
	wp_enqueue_script( 'script-name-p', plugin_dir_url( __FILE__ ) .  'assets/js/app.js', array(), '1.0.0', true );
	wp_register_style( 'sip-spwc-style', plugin_dir_url( __FILE__ ) .'assets/css/style.css', array(), '1.0.0');
	wp_enqueue_style( 'sip-spwc-style' );
}

register_activation_hook( __FILE__, 'every_x_hours_activate' );

function every_x_hours_activate() {

	$option_name = 'refresh_stats_every_x_hours' ;

	if ( get_option( $option_name ) == false ) {

		add_option( $option_name, '1' );

	}
}

// Check if SIP_Social_Proof_WC class exists or not
// if exist then load the plugin template page
if ( ! class_exists( 'SIP_Social_Proof_WC' ) ) {

  load_plugin_textdomain( 'sip-spwc-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	/**
	 * Main plugin class.
	 *
	 * @since 1.0.0
	 *
	 * @package sip_social_proof_woocommerce
	 * @author	ShopitPress
	 */
	class SIP_Social_Proof_WC
	{

		public function __construct() {

			// called just before the woocommerce template functions are included
			add_action( 'init', array( &$this, 'sip_spwc_template_functions' ), 20 );
			register_deactivation_hook( __FILE__, array( 'SIP_Social_Proof_WC_Admin' , 'sip_spwc_deactivate' ) );
		}

		// include template functions file
		public function sip_spwc_template_functions() {
			include( 'template-functions.php' );
		}

	}//END class SIP_Social_Proof_WC

}  //END if

// finally instantiate our plugin class and add it to the set of globals
$GLOBALS['sip_social_proof_wc'] = new SIP_Social_Proof_WC ();