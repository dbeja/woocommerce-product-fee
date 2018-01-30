<?php
/**
 * Codeable Product Fee Public
 *
 * @package codeable-product-fee
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

if ( ! class_exists( 'CProductFee_Public' ) ) :

	/**
	 * Codeable Product Fee Public Class
	 */
	class CProductFee_Public {

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->public_hooks();
		}

		/**
		 * Public Hooks
		 */
		private function public_hooks() {
			// WooCommerce Cart Fees hook.
			add_action( 'woocommerce_cart_calculate_fees', array( $this, 'add_cart_fee' ) );
		}

		/**
		 * Add Cart Fee
		 */
		public function add_cart_fee() {
			global $woocommerce;

			if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
				return;
			}

			// check if there is any product with product fee activated.
			$found_trigger = false;
			foreach ( $woocommerce->cart->cart_contents as $product ) {
				$fee_trigger = get_post_meta( $product['product_id'], '_cproductfee_trigger', true );
				if ( 'yes' === $fee_trigger ) {
					$found_trigger = true;
					break;
				}
			}

			// Add fee percentage and label to cart.
			if ( $found_trigger ) {
				$fee_percentage = intval( get_option( 'cproductfee_percentage', 10 ) ) / 100;
				$fee_label = get_option( 'cproductfee_label', '10% increase' );

				$surcharge = ( $woocommerce->cart->cart_contents_total + $woocommerce->cart->shipping_total ) * $fee_percentage;

				$woocommerce->cart->add_fee( $fee_label, $surcharge, true, '' );
			}
		}

	}

endif;
