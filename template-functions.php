<?php
/**
 * Template Function Overrides
 *
 */
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

/**
 * Checks for settings page to display sales_count on either single product page or shop page
 *
 * @since 1.0.0
 *
 */
if( get_option( 'display_sales_single_product_page', false ) == true ) {
	add_action( 'woocommerce_single_product_summary', 'total_sales_single_product_page', 11 );
}

if( get_option( 'display_sales_shop_page', false ) == true ) {
	add_action( 'woocommerce_after_shop_loop_item_title', 'total_sales_shop_page', 11 );
}

function total_sales_single_product_page( ) {
	if ( class_exists( 'SIP_Social_Proof_Add_On_WC' ) ) {
		if( sip_spwc_settings_get_meta( 'sip_spwc_text_display' ) ) {
			echo '<div class="social-proof">' . nl2br( do_shortcode( sip_spwc_settings_get_meta( 'sip_spwc_text_display' ) ) ) . '</div>';
		} else {
			echo  '<div class="socail-proof">' . nl2br( do_shortcode( get_option( 'show_in_product_page_view_editor' ) ) ) . '</div>';
		}
	} else {
		echo  '<div class="social-proof">' . nl2br( do_shortcode( get_option( 'show_in_product_page_view_editor' ) ) ) . '</div>';
	}
}

function total_sales_shop_page( ) {
	echo  '<div class="social-proof">' . nl2br( do_shortcode( get_option( 'show_in_product_in_list_view_editor' ) ) ) . '</div>';
}

/**
 * Shortcode: Get total sales count
 *
 * @since 1.0.0
 *
 * @param array $atts        A list of arguments passed to the shortcode.
 * @return int $total_sales  Returns total sales of a product
 */
add_shortcode( 'total_sales', 'sip_spwc_get_total_sales' );

function sip_spwc_get_total_sales( $atts ) {

	global $product;
	extract( shortcode_atts(
		array(
			'hide_bellow' 	=> '0',
			'multiplier' 	=> '1',
			'value_bellow'	=> '0',
			'animation' 	=> '',
			'singular' 		=> '',
			'plural' 		=> '',
			'id' 			=> '',
		), $atts )
	);


	if( $id == "" ){
		$id = method_exists( $product, 'get_id' ) ? $product->get_id() : $product->id;
	}
	
	if($animation == true )
		$animation = 'style="display:none" class="sip-count"';

	// $_pf 			= new WC_Product_Factory( );  
  	// $product 	  	= $_pf->get_product( $id );
	$total_sales 	= get_post_meta( $id, 'total_sales', true );

	$output = apply_filters( 'sip_spwc_get_total_sales_action', $atts, $total_sales );

	if( ! is_array( $output ) ) {
		$total_sales = 	$output;

		if ( $total_sales == null ) {
			return;
		}
	}


	if( $total_sales > 0 ) {
		if( $total_sales == 1 ) {
			return nl2br( '<span '. $animation .'>' .$total_sales. '</span> ' . $singular );
		} else {
			return nl2br( '<span '. $animation .'>' .$total_sales. '</span> ' . $plural );			
		}
	}
	return nl2br( '<span '. $animation .'>0</span> ' . $plural );
}

// Register admin settings
add_action( 'admin_init', 'sip_spwc_register_admin_settings' );
function sip_spwc_register_admin_settings( ) { 

	register_setting( 'sip-spwc-settings-group', 'display_sales_single_product_page' );
	register_setting( 'sip-spwc-settings-group', 'display_sales_shop_page' );
	register_setting( 'sip-spwc-settings-group', 'show_in_product_in_list_view_editor' );
	register_setting( 'sip-spwc-settings-group', 'show_in_product_page_view_editor' );
	register_setting( 'sip-spwc-hours-settings-group', 'refresh_stats_every_x_hours' );
}