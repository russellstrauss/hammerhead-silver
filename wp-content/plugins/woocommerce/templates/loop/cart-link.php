<?php
/**
 * Cart Link
 *
 * Shows text: Showing x - x of x results.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/result-count.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wp_query;

if ( ! woocommerce_products_will_display() )
	return;
?>

<div class="checkout">
	<a href="<?php echo get_permalink( wc_get_page_id( 'cart' ) ); ?>">
		<span>checkout <?php echo sprintf ( _n( '(%d)', '(%d)', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?></span>
		<img src="<?php echo get_template_directory_uri(); ?>/assets/img/bling-shark.svg" alt="cart-checkout" />
	</a>
</div>

<div class="mobile-checkout">
	<a href="<?php echo get_permalink( wc_get_page_id( 'cart' ) ); ?>">
		<img src="<?php echo get_template_directory_uri(); ?>/assets/img/shopping-cart.svg" alt="checkout" />
		<div class="count">
			<?php 
				if (WC()->cart->get_cart_contents_count() > 0) {
					echo sprintf ( _n( '%d', '%d', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() );
				}
			?>
		</div>
	</a>
</div>