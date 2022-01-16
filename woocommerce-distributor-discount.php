<?php
/**
 * Plugin Name: WooCommerce Distributor Discount
 * Plugin URI: https://sightfactory.com
 * Description: Applies discounts based on user type
 * Version: 1.0.0
 * Author: Sightfactory
 * Author URI: https://sightfactory.com/
 * Developer: Sightfactory
 * Developer URI: https://sightfactory.com/
 * Text Domain: woocommerce-extension
 * Domain Path: /languages
 * WC requires at least: 4.0
 * WC tested up to: 4.1.1
 *
 */
 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function apply_discount_by_role( WC_Cart $cart ){
	if ( is_admin() && ! defined( 'DOING_AJAX' ) )
	return;
	
	// check that user is logged in and we are on the checkout page
	if(is_checkout() && is_user_logged_in()) {
		
	$user_id = get_current_user_id();
	$user = get_userdata( $user_id );
	
	$discount_allowed = get_user_meta( $user_id,'discount_status',true );
	if(!$discount_allowed) {
		return;
	}
	
	// Get all the user roles as an array.
	$user_roles = $user->roles;
	// Check if the role you're interested in, is present in the array.
		if ( in_array( 'distributor_1', $user_roles, true ) && $discount_allowed ) {
	
		// Calculate the amount to reduce
			$discount = $cart->subtotal * 0.45;
			$cart->add_fee( 'Distributor Discount - Tier 1 (45%)', -$discount,false,'');
		}
		else if ( in_array( 'distributor_2', $user_roles, true ) && $discount_allowed ) {
			$discount = $cart->subtotal * 0.3;			
			$cart->add_fee( 'Distributor Discount - Tier 2 (30%)', -$discount,false,'');

		}
	}

}
	// Hook before calculate fees
	
	
add_action('woocommerce_cart_calculate_fees' , 'apply_discount_by_role', 10, 1);
	


?>