<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author  Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */

namespace Glossary\Integrations;

use Glossary\Engine;

/**
 * All the CMB related code.
 */
class CMB_Metabox extends Engine\Base {

	/**
	 * CMB metabox
	 *
	 * @var object
	 */
	public $cmb_post;

	/**
	 * Initialize class.
	 *
	 * @since 2.0
	 * @return bool
	 */
	public function initialize() {
		parent::initialize();

		if ( empty( $this->settings[ 'posttypes' ] ) ) {
			$this->settings[ 'posttypes' ] = array( 'post' );
		}

		\add_action( 'cmb2_init', array( $this, 'post_override' ) );
		\add_action( 'cmb2_init', array( $this, 'glossary_post_type' ) );

		return true;
	}

	/**
	 * Metabox for post types
	 *
	 * @return void
	 */
	public function post_override() {
		$this->cmb_post = \new_cmb2_box(
			array(
				'id'           => 'glossary_post_metabox',
				'title'        => \__( 'Glossary Post Override', GT_TEXTDOMAIN ),
				'object_types' => $this->settings[ 'posttypes' ],
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true,
			)
		);

		$this->cmb_post->add_field(
			array(
				'name' => \__( 'Disable Glossary for this post', GT_TEXTDOMAIN ),
				'id'   => GT_SETTINGS . '_disable',
				'type' => 'checkbox',
			)
		);
	}

	/**
	 * Metabox for glossary post type
	 *
	 * @return void
	 */
	// phpcs:disable
	public function glossary_post_type() {
		$cmb = \new_cmb2_box(
			array(
				'id'           => 'glossary_metabox',
				'title'        => \__( 'Glossary Auto-Link settings', GT_TEXTDOMAIN ),
				'object_types' => 'glossary',
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true,
			)
		);
		$settings = \gl_get_settings_extra();
		if ( isset( $settings['openai_key'] ) ) {
			$cmb->add_field(
				array(
					'name' => \__( 'Autogenerate the term content with ChatGPT', GT_TEXTDOMAIN ),
					'id'   => GT_SETTINGS . '_openai_Prompt',
					'type' => 'openai_prompt',
				)
			);
	}
		$cmb->add_field(
			array(
				'name' => \__( 'Additional key terms for this definition', GT_TEXTDOMAIN ),
				'desc' => \__(
					'Case-insensitive. To add more than one, separate them with commas',
					GT_TEXTDOMAIN
				),
				'id'   => GT_SETTINGS . '_tag',
				'type' => 'text',
			)
		);
		$cmb->add_field(
			array(
				'name'    => \__( 'What type of link?', GT_TEXTDOMAIN ),
				'id'      => GT_SETTINGS . '_link_type',
				'type'    => 'radio',
				'default' => 'external',
				'options' => array(
					'external' => 'External URL',
					'internal' => 'Internal URL',
				),
			)
		);
		$cmb->add_field(
			array(
				'name'      => \__( 'Link to external URL', GT_TEXTDOMAIN ),
				'desc'      => \__(
					'If this is left blank, the previous options defaults back and key term is linked to internal definition page',
					GT_TEXTDOMAIN
				),
				'id'        => GT_SETTINGS . '_url',
				'type'      => 'text_url',
				'protocols' => array( 'http', 'https' ),
			)
		);
		$cmb->add_field(
			array(
				'name' => \__( 'Internal', GT_TEXTDOMAIN ),
				'desc' => \__( 'Select a post of your site', GT_TEXTDOMAIN ),
				'id'   => GT_SETTINGS . '_cpt',
				'type' => 'post_ajax_search',
				'query_args' => array(
					'post_type' => \apply_filters( $this->default_parameters[ 'filter_prefix' ] . '_posttype_picker', array( 'post' ) )
				)
			)
		);

		if ( empty( $this->settings[ 'open_new_window' ] ) ) {
			$cmb->add_field(
				array(
					'name' => \__( 'Open all the injected links in a new window', GT_TEXTDOMAIN ),
					'id'   => GT_SETTINGS . '_target',
					'type' => 'checkbox',
				)
			);
		}

		$cmb->add_field(
			array(
				'name' => \__( 'Mark this link as "No Follow"', GT_TEXTDOMAIN ),
				'desc' => \__(
					'To learn more about No-Follow links, check <a href="https://support.google.com/webmasters/answer/96569?hl=en">this article</a>',
					GT_TEXTDOMAIN
				),
				'id'   => GT_SETTINGS . '_nofollow',
				'type' => 'checkbox',
			)
		);

		$cmb->add_field(
			array(
				'name' => \__( 'Mark this link as "Sponsored"', GT_TEXTDOMAIN ),
				'desc' => \__(
					'To learn more about Sponsored links, check <a href="https://developers.google.com/search/docs/advanced/guidelines/qualify-outbound-links">this article</a>',
					GT_TEXTDOMAIN
				),
				'id'   => GT_SETTINGS . '_sponsored',
				'type' => 'checkbox',
			)
		);
	}
	// phpcs:enable

}
