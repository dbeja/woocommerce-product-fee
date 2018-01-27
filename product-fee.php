<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/*
Plugin Name:  Codeable Product Fee
Plugin URI:   https://codeable.io/
Description:  Increases Woocommerce cart total by X% if a certain product is in the cart
Version:      0.1
Author:       David Beja
Author URI:   https://codeable.io/
*/

if ( !class_exists( 'CProductFee' ) ) {
	define('CPRODUCTFEE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	define('CPRODUCTFEE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

	class CProductFee {

		// Init
		public static function init() {
			register_activation_hook( __FILE__, 'CProductFee::activate' );
			register_deactivation_hook( __FILE__, 'CProductFee::deactivate' );

			if( is_admin() ) {
				require_once( CPRODUCTFEE_PLUGIN_DIR . 'admin/product-fee-admin.php' );
				$admin = new CProductFee_Admin();
			} else {
				require_once( CPRODUCTFEE_PLUGIN_DIR . 'public/product-fee-public.php' );
				$public = new CProductFee_Public();
			}
		}

		// Activate Hook
		public static function activate() {
		}

		// Deactivate Hook
		public static function deactivate() {
		}
	}

	CProductFee ::init();
}
