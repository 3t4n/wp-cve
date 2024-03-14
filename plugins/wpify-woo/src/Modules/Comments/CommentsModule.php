<?php

namespace WpifyWoo\Modules\Comments;

use WP_Error;
use WpifyWoo\Abstracts\AbstractModule;
use WpifyWooDeps\Wpify\CustomFields\CustomFields;

class CommentsModule extends AbstractModule {
	private CustomFields $custom_fields;

	public function __construct( CustomFields $custom_fields ) {
		parent::__construct();
		$this->custom_fields = $custom_fields;
	}

	/**
	 * @return void
	 */
	public function setup() {
		add_filter( 'wpify_woo_settings_' . $this->id(), array( $this, 'settings' ) );
		add_action( 'woocommerce_review_meta', [ $this, 'display_type' ] );
		$this->register_metabox();
	}

	function id() {
		return 'comments';
	}


	public function register_metabox() {
		$this->custom_fields->create_comment_metabox(
			[
				'id'    => 'wpify_woo_comments',
				'title' => __( 'WPify Woo Details', '' ),
				'items' => array(
					array(
						'type'  => 'group',
						'id'    => '_wpify_woo_details',
						'items' => [
							[
								'type'    => 'select',
								'id'      => 'type',
								'title'   => __( 'Comment type', 'wpify-woo' ),
								'options' => function () {
									return array_map( function ( $item ) {
										return [
											'label' => $item['label'],
											'value' => $item['id'],
										];
									}, $this->get_setting( 'comment_types' ) );
								},
							],
						],
					),
				),

			]
		);
	}

	/**
	 * @return array[]
	 */
	public function settings(): array {
		$settings = array(
			array(
				'id'    => 'comment_types',
				'type'  => 'multi_group',
				'label' => __( 'Comment types', 'wpify-woo' ),
				'items' => [
					[
						'id'    => 'label',
						'label' => __( 'Label', 'wpify-woo' ),
						'type'  => __( 'text', 'wpify-woo' ),
					],
					[
						'id'        => 'id',
						'type'      => 'hidden',
						'generator' => 'uuid',
					],

				],
			),
		);

		return $settings;
	}

	public function name() {
		return __( 'Comments', 'wpify-woo' );
	}

	public function display_type( $comment ) {
		$details = get_comment_meta( $comment->comment_ID, '_wpify_woo_details', true );
		if ( $details && ! empty( $details['type'] ) && ! empty( $this->get_setting( 'comment_types' ) ) ) {
			foreach ( $this->get_setting( 'comment_types' ) as $comment_type ) {
				if ( $comment_type['id'] === $details['type'] ) { ?>
					<p class="meta">
						<em class="woocommerce-review__type">
							<?php esc_html_e( $comment_type['label'] ); ?>
						</em>
					</p>

				<?php }
			}
		}
	}
}
