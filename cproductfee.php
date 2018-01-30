<?php
/**
 * Plugin Name:  Codeable Product Fee
 * Plugin URI:   https://codeable.io/
 * Description:  Increases Woocommerce cart total by X% if a certain product is in the cart
 * Version:      1.0.0
 * Author:       David Beja
 * Author URI:   https://codeable.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cproductfee
 *
 * @package codeable-product-fee
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

if ( ! class_exists( 'CProductFee' ) ) :

	define( 'CPRODUCTFEE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	define( 'CPRODUCTFEE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

	/**
	 * Codeable Product Fee Main Class
	 *
	 * @author David Beja
	 */
	class CProductFee {

		/**
		 * Plugin initialization
		 */
		public static function init() {
			register_activation_hook( __FILE__, 'CProductFee::activate' );
			register_deactivation_hook( __FILE__, 'CProductFee::deactivate' );

			if ( is_admin() ) {
				require_once( CPRODUCTFEE_PLUGIN_DIR . 'admin/class-cproductfee-admin.php' );
				$admin = new CProductFee_Admin();
			} else {
				require_once( CPRODUCTFEE_PLUGIN_DIR . 'public/class-cproductfee-public.php' );
				$public = new CProductFee_Public();
			}
		}

		/**
		 * Activate hook
		 */
		public static function activate() {
		}

		/**
		 * Deactivate hook
		 */
		public static function deactivate() {
		}
	}

	CProductFee ::init();

endif;
