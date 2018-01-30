<?php
/**
 * Codeable Product Fee Admin
 *
 * @package codeable-product-fee
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

if ( ! class_exists( 'CProductFee_Admin' ) ) :

	/**
	 * Codeable Product Fee Admin Class
	 */
	class CProductFee_Admin {

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->admin_hooks();
		}

		/**
		 * Admin Hooks
		 */
		private function admin_hooks() {
			// Add a section on WooCommerce settings.
			add_filter( 'woocommerce_get_sections_products', array( $this, 'add_product_section' ) );
			// Add settings to that section.
			add_filter( 'woocommerce_get_settings_products', array( $this, 'add_product_settings' ), 10, 2 );
			// Add product option to ativate the 10% fee.
			add_filter( 'product_type_options', array( $this, 'add_product_fee_option' ) );
			// Save product option.
			add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_fee_option' ) );
		}

		/**
		 * Add Product Section
		 *
		 * @param array $sections WooCommerce product sections.
		 */
		public function add_product_section( $sections ) {
			$sections['cproductfee'] = __( 'Product Fee', 'cproductfee' );
			return $sections;
		}

		/**
		 * Add Product Settings
		 *
		 * @param array  $settings WooCommerce product settings.
		 * @param string $current_section WooCommerce product current section.
		 */
		public function add_product_settings( $settings, $current_section ) {
			if ( 'cproductfee' === $current_section ) {
				$new_settings = array();

				// Section begin.
				$new_settings[] = array(
					'id' => 'cproductfee_settings',
					'name' => __( 'Product Fee', 'cproductfee' ),
					'type' => 'title',
					'desc' => __( 'Configure product fee options', 'cproductfee' ),
				);

				// Percentage fee.
				$new_settings[] = array(
					'id' => 'cproductfee_percentage',
					'name' => __( 'Fee percentage', 'cproductfee' ),
					'type' => 'text',
					'desc' => '%',
					'default' => 10,
					'css' => 'width: 50px;',
				);

				// Fee label.
				$new_settings[] = array(
					'id' => 'cproductfee_label',
					'title' => __( 'Fee label', 'cproductfee' ),
					'type' => 'text','cproductfee',
					'default' => '10% increase',
				);

				// Section end.
				$new_settings[] = array(
					'id' => 'cproductfee_settings',
					'type' => 'sectionend',
				);

				return $new_settings;
			} else {
				return $settings;
			}
		}

		/**
		 * Add Product Fee Option
		 *
		 * @param array $product_type_options WooCommerce Product Type options array.
		 */
		public function add_product_fee_option( $product_type_options ) {
			// get current product fee percentage from settings.
			$fee = get_option( 'cproductfee_percentage', 10 );

			// add new option for product fee trigger.
			/* translators: %d is the product fee percentage */
			$label = sprintf( esc_html__( '%d%% increase', 'cproductfee' ), esc_attr( $fee ) );
			/* translators: %d is the product fee percentage */
			$description = sprintf( esc_html__( 'Increase %d%% on cart.', 'cproductfee' ), esc_attr( $fee ) );
			$product_type_options['cproductfee_trigger'] = array(
				'id'            => '_cproductfee_trigger',
				'wrapper_class' => 'show_if_simple show_if_variable',
				'label'         => $label,
				'description'   => $description,
				'default'       => 'no',
			);

			return $product_type_options;
		}

		/**
		 * Save Product Fee Option
		 *
		 * @param int $post_id Product id to save post meta.
		 */
		public function save_product_fee_option( $post_id ) {
			if ( ! ( isset( $_POST['woocommerce_meta_nonce'], $_POST['_cproductfee_trigger'] ) || wp_verify_nonce( sanitize_key( $_POST['woocommerce_meta_nonce'] ), 'woocommerce_save_data' ) ) ) {
				return false;
			}
			$fee_trigger = isset( $_POST['_cproductfee_trigger'] ) ? 'yes' : 'no';
			update_post_meta( $post_id, '_cproductfee_trigger', esc_attr( $fee_trigger ) );
		}

	}

endif;
