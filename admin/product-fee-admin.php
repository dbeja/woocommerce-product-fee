<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
* Product Fee Admin Class
**/

if ( !class_exists( 'CProductFee_Admin' ) ) {

	class CProductFee_Admin {

		/*
		* Constructor
		*/
		public function __construct() {
			$this->admin_hooks();
		}

		/*
		* Admin Hooks
		*/
		private function admin_hooks() {
			//add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );
			add_action( 'admin_menu', array( $this, 'register_menus' ) );
			add_filter( 'woocommerce_get_sections_products', array( $this, 'add_product_section' ) );
			add_filter( 'woocommerce_get_settings_products', array( $this, 'add_product_settings' ), 10, 2 );
			add_filter( 'product_type_options', array( $this, 'add_product_fee_option' ) );
		}

		/*
		* Load Admin Scripts
		*/
		public function load_admin_scripts($hook) {
		}


		/*
		* Register Menus
		*/
		public function register_menus() {
			add_menu_page( 'WooCommerce Product Fee', 'WooCommerce Product Fee', 'manage_options', 'woocommerce-product-fee', array( $this, 'page_admin' ), 'dashicons-format-gallery', 3 );
		}

		/*
		* Page Admin
		*/
		public function page_admin() {
			include_once( CPRODUCTFEE_PLUGIN_DIR . 'admin/views/admin.php' );
		}

		/*
		* Add Product Section
		*/
		public function add_product_section( $sections ) {
			$sections['cproductfee'] = __( 'Product Fee', 'cproductfee' );
			return $sections;
		}

		/*
		* Add Product Settings
		*/
		public function add_product_settings( $settings, $current_section ) {
			if ( $current_section == 'cproductfee' ) {
				$settings_slider = array();
		// Add Title to the Settings
		$settings_slider[] = array( 'name' => __( 'WC Slider Settings', 'text-domain' ), 'type' => 'title', 'desc' => __( 'The following options are used to configure WC Slider', 'text-domain' ), 'id' => 'wcslider' );
		// Add first checkbox option
		$settings_slider[] = array(
			'name'     => __( 'Auto-insert into single product page', 'text-domain' ),
			'desc_tip' => __( 'This will automatically insert your slider into the single product page', 'text-domain' ),
			'id'       => 'wcslider_auto_insert',
			'type'     => 'checkbox',
			'css'      => 'min-width:300px;',
			'desc'     => __( 'Enable Auto-Insert', 'text-domain' ),
		);
		return $settings_slider;
	} else {
		return $settings;
	}
		}

		/*
		* Add Product Fee Option
		*/
		public function add_product_fee_option( $product_type_options ) {
			$product_type_options['product_fee'] = array(
		'id'            => 'product_fee',
		'wrapper_class' => 'show_if_simple show_if_variable',
		'label'         => __( 'Product Fee', 'woocommerce' ),
		'description'   => __( 'Gift Cards allow users to put in personalised messages.', 'woocommerce' ),
		'default'       => 'no'
	);
	return $product_type_options;
		}

	}

}
