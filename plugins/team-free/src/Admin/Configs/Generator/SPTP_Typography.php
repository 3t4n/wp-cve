<?php
/**
 * Typography tab.
 *
 * @since      2.0.0
 * @version    2.0.0
 *
 * @package    WP_Team
 * @subpackage WP_Team/admin
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

namespace ShapedPlugin\WPTeam\Admin\Configs\Generator;

use ShapedPlugin\WPTeam\Admin\Framework\Classes\SPF_TEAM;
// Cannot access directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * This class is responsible for Typography tab in Team page.
 *
 * @since      2.0.0
 */
class SPTP_Typography {

	/**
	 * Typography Settings.
	 *
	 * @since 2.0.0
	 * @param string $prefix _sptp_generator.
	 */
	public static function section( $prefix ) {
		SPF_TEAM::createSection(
			$prefix,
			array(
				'title'  => __( 'Typography', 'team-free' ),
				'icon'   => 'fa fa-font',
				'fields' => array(
					array(
						'type'    => 'notice',
						'style'   => 'normal',
						'content' => 'Want to customize everything (typography, color, and margin) easily? <a href="https://getwpteam.com/pricing/?ref=1" target="_blank"><b>Upgrade to Pro!</b></a>
						P.S. Note: The color and margin options work in the lite version.',
					),
					array(
						'id'       => 'team_title_font_load',
						'type'     => 'switcher',
						'title'    => __( 'Load Team Section Title Font', 'team-free' ),
						'subtitle' => __( 'On/Off google font for team section title.', 'team-free' ),
						'default'  => false,
						'class'    => 'sptp_typography_pro',
					),
					array(
						'id'            => 'typo_team_title',
						'type'          => 'typography',
						'title'         => __( 'Team Section Title', 'team-free' ),
						'subset'        => false,
						'class'         => 'sptp-advanced',
						'preview'       => 'always',
						'margin_top'    => true,
						'margin_bottom' => true,
						'default'       => array(
							'color'          => '#333333',
							'font-family'    => 'Open Sans',
							'font-size'      => '24',
							'font-weight'    => '600',
							'line-height'    => '28',
							'letter-spacing' => '1',
							'text-align'     => 'center',
							'text-transform' => 'capitalize',
							'margin-top'     => '',
							'margin-bottom'  => '25',
							'unit'           => 'px',
							'type'           => 'google',
						),
						'preview_text'  => __( 'Our Team Members', 'team-free' ),
					),
					array(
						'id'       => 'member_name_font_load',
						'type'     => 'switcher',
						'title'    => __( 'Load Member Name Font', 'team-free' ),
						'subtitle' => __( 'On/Off google font for member name.', 'team-free' ),
						'default'  => false,
						'class'    => 'sptp_typography_pro',
					),
					array(
						'id'            => 'typo_member_name',
						'type'          => 'typography',
						'title'         => __( 'Member Name', 'team-free' ),
						'subset'        => false,
						'class'         => 'sptp-advanced',
						'preview'       => 'always',
						'margin_top'    => true,
						'margin_bottom' => false,
						'default'       => array(
							'color'          => '#333333',
							'font-family'    => 'Open Sans',
							'font-size'      => '18',
							'font-weight'    => '600',
							'line-height'    => '24',
							'letter-spacing' => '1',
							'text-align'     => 'center',
							'text-transform' => 'default',
							'margin-top'     => '16',
							'margin-bottom'  => '',
							'unit'           => 'px',
							'type'           => 'google',
						),
						'preview_text'  => __( 'John Doe', 'team-free' ),
					),
					array(
						'id'       => 'member_position_font_load',
						'type'     => 'switcher',
						'title'    => __( 'Load Member Position/Job Title Font', 'team-free' ),
						'subtitle' => __( 'On/Off google font for member position.', 'team-free' ),
						'default'  => false,
						'class'    => 'sptp_typography_pro',
					),
					array(
						'id'            => 'typo_member_position',
						'type'          => 'typography',
						'title'         => __( 'Position/Job Title', 'team-free' ),
						'subset'        => false,
						'class'         => 'sptp-advanced',
						'preview'       => 'always',
						'margin_top'    => true,
						'margin_bottom' => false,
						'default'       => array(
							'color'          => '#333333',
							'font-family'    => 'Open Sans',
							'font-size'      => '15',
							'line-height'    => '24',
							'text-align'     => 'center',
							'text-transform' => 'default',
							'letter-spacing' => '1',
							'margin-top'     => '12',
							'margin-bottom'  => '',
							'unit'           => 'px',
							'type'           => 'google',
						),
						'preview_text'  => __( 'Manager', 'team-free' ),
					),
					array(
						'id'       => 'member_description_font_load',
						'type'     => 'switcher',
						'title'    => __( 'Load Member Description Font', 'team-free' ),
						'subtitle' => __( 'On/Off google font for the member description.', 'team-free' ),
						'default'  => false,
						'class'    => 'sptp_typography_pro',
					),
					array(
						'id'            => 'typo_desc_bio',
						'type'          => 'typography',
						'title'         => __( 'Short Bio', 'team-free' ),
						'subset'        => false,
						'class'         => 'sptp-advanced',
						'preview'       => 'always',
						'margin_top'    => true,
						'margin_bottom' => false,
						'default'       => array(
							'color'          => '#333333',
							'font-family'    => 'Open Sans',
							'font-size'      => '14',
							'font-weight'    => '300',
							'line-height'    => '22',
							'text-align'     => 'center',
							'text-transform' => 'default',
							'letter-spacing' => '1',
							'margin-top'     => '16',
							'margin-bottom'  => '15',
							'unit'           => 'px',
							'type'           => 'google',
						),
						'preview_text'  => __( 'Hi, This is John Doe from New York city. He loves creating web applications based on WordPress.', 'team-free' ),
					),
					array(
						'id'       => 'member_details_font_load',
						'type'     => 'switcher',
						'title'    => __( 'Load Member Additional Infomation Font', 'team-free' ),
						'subtitle' => __( 'On/Off google font for the member additionl infomaion.', 'team-free' ),
						'default'  => false,
						'class'    => 'sptp_typography_pro',
					),
					array(
						'id'            => 'additional_info',
						'type'          => 'typography',
						'title'         => __( 'Member Additional Information', 'team-free' ),
						'subset'        => false,
						'class'         => 'sptp-advanced sptp-color-disabled',
						'preview'       => 'always',
						'margin_top'    => true,
						'margin_bottom' => false,
						'default'       => array(
							'color'          => '#333333',
							'font-family'    => 'Open Sans',
							'font-size'      => '15',
							'line-height'    => '20',
							'text-align'     => 'center',
							'text-transform' => 'default',
							'letter-spacing' => '1',
							'margin-top'     => '',
							'margin-bottom'  => '',
							'unit'           => 'px',
							'type'           => 'google',
						),
						'preview_text'  => __( 'john@shapedplugin.com', 'team-free' ),
					),
					array(
						'id'       => 'member_skills_font_load',
						'type'     => 'switcher',
						'title'    => __( 'Load Member Skills Font', 'team-free' ),
						'subtitle' => __( 'On/Off google font for the member skills.', 'team-free' ),
						'default'  => false,
						'class'    => 'sptp_typography_pro',
					),
					array(
						'id'            => 'typo_skills',
						'type'          => 'typography',
						'title'         => __( 'Member Skills', 'team-free' ),
						'subset'        => false,
						'preview'       => 'always',
						'font_style'    => false,
						'class'         => 'sptp-advanced sptp-color-disabled',
						'margin_top'    => true,
						'margin_bottom' => false,
						'default'       => array(
							'color'          => '#333333',
							'font-family'    => 'Open Sans',
							'font-weight'    => '400',
							'font-size'      => '13',
							'line-height'    => '24',
							'text-align'     => 'left',
							'text-transform' => 'default',
							'letter-spacing' => '1',
							'margin-top'     => '15',
							'margin-bottom'  => '12',
							'unit'           => 'px',
							'type'           => 'google',
						),
						'preview_text'  => __( 'WordPress', 'team-free' ),
					),
				),
			)
		);
	}
}
