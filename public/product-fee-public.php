<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
* Product Fee Admin Class
**/

if ( !class_exists( 'CProductFee_Public' ) ) {

	class CProductFee_Public {

		/*
		* Constructor
		*/
		public function __construct() {
			$this->public_hooks();
		}

		/*
		* Public Hooks
		*/
		private function public_hooks() {
			add_action( 'woocommerce_cart_calculate_fees', array( $this, 'add_cart_fee') );
		}

		/*
		* Add Cart Fee
		*/
		public function add_cart_fee() {
			global $woocommerce;

			if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
				return;
			}

			$percentage = 0.1;
			$surcharge = ( $woocommerce->cart->cart_contents_total + $woocommerce->cart->shipping_total ) * $percentage;

			$woocommerce->cart->add_fee( '10% Fee', $surcharge, true, '' );

			foreach( $woocommerce->cart->cart_contents as $prod_in_cart ) {
				$prod = print_r($prod_in_cart, true);
				error_log($prod);
			}
		}

	}

}
