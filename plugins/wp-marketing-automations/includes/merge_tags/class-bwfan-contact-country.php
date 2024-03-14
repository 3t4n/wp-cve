<?php
if ( ! bwfan_is_autonami_pro_active() || version_compare( BWFAN_PRO_VERSION, '2.0.3', '>=' ) ) {
	class BWFAN_Contact_Country extends BWFAN_Merge_Tag {

		private static $instance = null;

		public function __construct() {
			$this->tag_name        = 'contact_country';
			$this->tag_description = __( 'Contact Country', 'autonami-automations-pro' );
			add_shortcode( 'bwfan_contact_country', array( $this, 'parse_shortcode' ) );
			add_shortcode( 'bwfan_customer_country', array( $this, 'parse_shortcode' ) );
			$this->priority = 18;
		}

		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Show the html in popup for the merge tag.
		 */
		public function get_view() {
			$this->get_back_button();
			$this->get_country_format_html();

			if ( $this->support_fallback ) {
				$this->get_fallback();
			}

			$this->get_preview();
			$this->get_copy_button();
		}

		public function get_country_format_html() {
			$templates = array(
				'iso'  => __( 'ISO code 2 digit', 'wp-marketing-automations' ),
				'full' => __( 'Nice name', 'wp-marketing-automations' ),
			);
			?>
            <label for="" class="bwfan-label-title"><?php esc_html_e( 'Format', 'wp-marketing-automations' ); ?></label>
            <select id="" class="bwfan-input-wrapper bwfan-mb-15 bwfan_tag_select" style="padding-left:10px;" name="format" required>
				<?php
				foreach ( $templates as $slug => $name ) {
					echo '<option value="' . esc_attr__( $slug ) . '">' . esc_html__( $name ) . '</option>';
				}
				?>
            </select>
			<?php
		}

		/**
		 * Parse the merge tag and return its value.
		 *
		 * @param $attr
		 *
		 * @return mixed|string|void
		 */
		public function parse_shortcode( $attr ) {
			$get_data = BWFAN_Merge_Tag_Loader::get_data();
			if ( true === $get_data['is_preview'] ) {
				return $this->parse_shortcode_output( $this->get_dummy_preview(), $attr );
			}

			$format = isset( $attr['format'] ) ? $attr['format'] : 'iso';

			/** If Contact ID available */
			$cid     = isset( $get_data['contact_id'] ) ? $get_data['contact_id'] : '';
			$country = $this->get_country( $cid );
			if ( ! empty( $country ) ) {
				$country = $this->get_country_details( $country, $format );

				return $this->parse_shortcode_output( $country, $attr );
			}

			/** If order */
			$order = $this->get_order_object( $get_data );
			if ( ! empty( $order ) ) {
				$country_slug = BWFAN_Woocommerce_Compatibility::get_billing_country_from_order( $order );
				if ( empty( $country_slug ) ) {
					$country_slug = BWFAN_Woocommerce_Compatibility::get_shipping_country_from_order( $order );
				}
				$country = $this->get_country_details( $country_slug, $format );
				if ( ! empty( $country ) ) {
					return $this->parse_shortcode_output( $country, $attr );
				}
			}

			/** If user ID or email */
			$user_id = isset( $get_data['user_id'] ) ? $get_data['user_id'] : '';
			$email   = isset( $get_data['email'] ) ? $get_data['email'] : '';

			$contact = bwf_get_contact( $user_id, $email );
			if ( absint( $contact->get_id() ) > 0 ) {
				$country = $this->get_country_details( $contact->get_country(), $format );

				return $this->parse_shortcode_output( $country, $attr );
			}

			return $this->parse_shortcode_output( '', $attr );
		}

		public function get_country( $cid ) {
			$cid = absint( $cid );
			if ( 0 === $cid ) {
				return '';
			}
			$contact = new WooFunnels_Contact( '', '', '', $cid );
			if ( $contact->get_id() > 0 ) {
				return $contact->get_country();
			}

			return '';
		}

		/**
		 * Get country nice name or 2 digit iso code
		 *
		 * @param $country_slug
		 * @param $format
		 *
		 * @return mixed
		 */
		public function get_country_details( $country_slug, $format ) {
			if ( empty( $country_slug ) ) {
				return '';
			}
			if ( 'iso' === $format ) {
				return $country_slug;
			}
			$countries = bwf_get_countries_data();
			$country   = isset( $countries[ $country_slug ] ) ? $countries[ $country_slug ] : false;
			if ( empty( $country ) ) {
				return $country_slug;
			}

			return $country;
		}

		/**
		 * Show dummy value of the current merge tag.
		 *
		 * @return string
		 */
		public function get_dummy_preview() {
			$contact = $this->get_contact_data();
			$country = 'US';
			/** check for contact instance and the contact id */
			if ( ! $contact instanceof WooFunnels_Contact || 0 === absint( $contact->get_id() ) ) {
				return $country;
			}

			/** if empty */
			if ( empty( $contact->get_country() ) ) {
				return $country;
			}

			$country = $contact->get_country();
			$format  = 'iso';

			return $this->get_country_details( $country, $format );
		}

		/**
		 * Returns merge tag setting schema
		 */
		public function get_setting_schema() {
			return [
				[
					'id'          => 'format',
					'type'        => 'select',
					'options'     => [
						[
							'value' => 'iso',
							'label' => __( 'ISO code 2 digit', 'wp-marketing-automations' ),
						],
						[
							'value' => 'full',
							'label' => __( 'Nice Name', 'wp-marketing-automations' ),
						],
					],
					'label'       => __( 'Format', 'wp-marketing-automations' ),
					"class"       => 'bwfan-input-wrapper',
					"placeholder" => 'Select',
					"required"    => false,
					"description" => ""
				]
			];
		}
	}

	/**
	 * Register this merge tag to a group.
	 */
	BWFAN_Merge_Tag_Loader::register( 'bwf_contact', 'BWFAN_Contact_Country', null, 'Contact' );
}
