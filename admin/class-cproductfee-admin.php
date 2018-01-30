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
			// Add a section on WooCommerce settings
			add_filter( 'woocommerce_get_sections_products', array( $this, 'add_product_section' ) );
			// Add settings to that section
			add_filter( 'woocommerce_get_settings_products', array( $this, 'add_product_settings' ), 10, 2 );
			// Add product option to ativate the 10% fee
			add_filter( 'product_type_options', array( $this, 'add_product_fee_option' ) );
			// Save product option
			add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_fee_option' ) );
		}

		/*
		* Add Product Section
		*/
		public function add_product_section( $sections ) {
			$sections['cproductfee'] = __( 'Product Fee', CPRODUCTFEE_TEXT_DOMAIN );
			return $sections;
		}

		/*
		* Add Product Settings
		*/
		public function add_product_settings( $settings, $current_section ) {
			if ( 'cproductfee' == $current_section ) {
				$new_settings = array();

				// Section begin
				$new_settings[] = array(
					'id' => 'cproductfee_settings',
					'name' => __( 'Product Fee', CPRODUCTFEE_TEXT_DOMAIN ),
					'type' => 'title',
					'desc' => __( 'Configure product fee options', CPRODUCTFEE_TEXT_DOMAIN )
				);

				// Percentage fee
				$new_settings[] = array(
					'id' => 'cproductfee_percentage',
					'name' => __( 'Fee percentage', CPRODUCTFEE_TEXT_DOMAIN ),
					'type' => 'text'
				);

				// Fee label
				$new_settings[] = array(
					'id' => 'cproductfee_label',
					'title' => __( 'Fee label', CPRODUCTFEE_TEXT_DOMAIN ),
					'type' => 'text'
				);

				// Section end
				$new_settings[] = array(
					'id' => 'cproductfee_settings',
					'type' => 'sectionend',
				);

				return $new_settings;
			} else {
				return $settings;
			}
		}

		/*
		* Add Product Fee Option
		*/
		public function add_product_fee_option( $product_type_options ) {
			$fee = get_option( 'cproductfee_percentage' );
			$product_type_options['cproductfee_trigger'] = array(
				'id'            => '_cproductfee_trigger',
				'wrapper_class' => 'show_if_simple show_if_variable',
				'label'         => __( $fee . '% increase', CPRODUCTFEE_TEXT_DOMAIN ),
				'description'   => __( 'Increase ' . $fee . '% on cart.', CPRODUCTFEE_TEXT_DOMAIN ),
				'default'       => 'no'
			);
			return $product_type_options;
		}

		/*
		* Save Product Fee Option
		*/
		public function save_product_fee_option( $post_id ) {
			$fee_trigger = isset( $_POST['_cproductfee_trigger'] ) ? 'yes' : 'no';
			update_post_meta( $post_id, '_cproductfee_trigger', esc_attr( $fee_trigger ) );
		}

	}

}
