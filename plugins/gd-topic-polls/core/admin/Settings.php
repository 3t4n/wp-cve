<?php

namespace Dev4Press\Plugin\GDPOL\Admin;

use Dev4Press\v43\Core\Options\Element as EL;
use Dev4Press\v43\Core\Options\Settings as BaseSettings;
use Dev4Press\v43\Core\Options\Type;
use Dev4Press\v43\Core\Quick\BBP;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings extends BaseSettings {
	protected function value( $name, $group = 'settings', $default = null ) {
		return gdpol_settings()->get( $name, $group, $default );
	}

	protected function init() {
		$this->settings = array(
			'basic'       => array(
				'basic_enabled' => array(
					'name'     => __( 'Polls Activation', 'gd-topic-polls' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'global_enabled', __( 'Topic Polls', 'gd-topic-polls' ), __( 'This is main switch to enable the support for adding polls in topics.', 'gd-topic-polls' ), Type::BOOLEAN, $this->value( 'global_enabled' ) ),
							),
						),
					),
				),
				'basic_user'    => array(
					'name'     => __( 'Users allowed to create polls', 'gd-topic-polls' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::info( __( 'Info', 'gd-topic-polls' ), sprintf( __( 'Poll creation is allowed to users with the \'gdpol_create_poll\' capability. This capability will be added to user roles you select here. If you use capability method to check who is allowed, you can allow/deny access to individual users, regardless of their role.', 'gd-topic-polls' ), '<strong>gdpol_create_poll</strong>' ) ),
								EL::i( 'settings', 'global_user_roles', __( 'User Roles', 'gd-topic-polls' ), __( 'Users belonging to selected user roles will get capability to create polls.', 'gd-topic-polls' ), Type::CHECKBOXES, $this->value( 'global_user_roles' ) )->data( 'array', BBP::get_user_roles() ),
								EL::i( 'settings', 'global_cap_check', __( 'Method', 'gd-topic-polls' ), __( 'Change the method used to check if the user can create polls. Some plugins may cause issues with capabilities check, so you can try User Roles method if that is the case.', 'gd-topic-polls' ), Type::SELECT, $this->value( 'global_cap_check' ) )->data( 'array', $this->get_capability_methods() ),
							),
						),
					),
				),
				'basic_forums'  => array(
					'name'     => __( 'Disable polls for forums', 'gd-topic-polls' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::info( __( 'Info', 'gd-topic-polls' ), __( 'By default, all polls are enabled in all forums. If you want to disable polls for some of the forums, here you can select such forums here.', 'gd-topic-polls' ) ),
								EL::l( 'settings', 'global_disable_forums', __( 'Select Forums', 'gd-topic-polls' ), __( 'Polls will not be available in the forums selected here.', 'gd-topic-polls' ), Type::CHECKBOXES_HIERARCHY, $this->value( 'global_disable_forums' ), 'array', gdpol_bbpress_forums_list() ),
							),
						),
					),
				),
			),
			'integration' => array(
				'int_topic'  => array(
					'name'     => __( 'Single Topic', 'gd-topic-polls' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'global_auto_embed_poll', __( 'Embed Poll', 'gd-topic-polls' ), __( 'Automatically embed poll on top of the single topic page before the lead topic.', 'gd-topic-polls' ), Type::BOOLEAN, $this->value( 'global_auto_embed_poll' ) ),
							),
						),
					),
				),
				'int_form'   => array(
					'name'     => __( 'Topic Edit Form', 'gd-topic-polls' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'global_auto_embed_form', __( 'Embed Form', 'gd-topic-polls' ), __( 'Automatically embed poll form at the end of the new topic form.', 'gd-topic-polls' ), Type::BOOLEAN, $this->value( 'global_auto_embed_form' ) ),
								EL::i( 'settings', 'global_auto_embed_form_priority', __( 'Priority', 'gd-topic-polls' ), '', Type::ABSINT, $this->value( 'global_auto_embed_form_priority' ) ),
							),
						),
					),
				),
				'int_topics' => array(
					'name'     => __( 'Topics List', 'gd-topic-polls' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'global_auto_embed_icon', __( 'Poll Icon', 'gd-topic-polls' ), __( 'Show poll icon before the topic title in various topics lists (forums, views).', 'gd-topic-polls' ), Type::BOOLEAN, $this->value( 'global_auto_embed_icon' ) ),
							),
						),
					),
				),
			),
			'fields'      => array(
				'fields_description' => array(
					'name'     => __( 'Description', 'gd-topic-polls' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'poll_field_description', __( 'Include description field', 'gd-topic-polls' ), '', Type::BOOLEAN, $this->value( 'poll_field_description' ) ),
							),
						),
					),
				),
				'fields_responses'   => array(
					'name'     => __( 'Responses', 'gd-topic-polls' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'poll_field_responses_allow_html', __( 'Allow HTML in responses', 'gd-topic-polls' ), __( 'By default, each response will be stripped of all HTML. With this option, you can allow HTML in responses. Each response will be filtered using WordPress KSES functions.', 'gd-topic-polls' ), Type::BOOLEAN, $this->value( 'poll_field_responses_allow_html' ) ),
							),
						),
					),
				),
				'fields_show'        => array(
					'name'     => __( 'Show results', 'gd-topic-polls' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'poll_field_show_included', __( 'Include the field', 'gd-topic-polls' ), __( 'If disabled, users will not be able to set this field.', 'gd-topic-polls' ), Type::BOOLEAN, $this->value( 'poll_field_show_included' ) ),
								EL::i( 'settings', 'poll_field_show_default', __( 'Default value', 'gd-topic-polls' ), '', Type::SELECT, $this->value( 'poll_field_show_default' ) )->data( 'array', array(
									'always' => __( 'Include button to show results', 'gd-topic-polls' ),
									'vote'   => __( 'Show results after voting', 'gd-topic-polls' ),
									'closed' => __( 'Show results only after poll is closed', 'gd-topic-polls' ),
								) ),
							),
						),
					),
				),
			),
			'display'     => array(
				'display_calc' => array(
					'name'     => __( 'Poll results calculations', 'gd-topic-polls' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'calculate_multi_method', __( 'Multi vote polls', 'gd-topic-polls' ), __( 'Calculate the percentages for each response for multi-choice polls.', 'gd-topic-polls' ), Type::SELECT, $this->value( 'calculate_multi_method' ) )->data( 'array', array(
									'votes'  => __( 'Based on total number of votes', 'gd-topic-polls' ),
									'voters' => __( 'Based on number of voters', 'gd-topic-polls' ),
								) ),
							),
						),
					),
				),
				'display_sort' => array(
					'name'     => __( 'Poll results sorting', 'gd-topic-polls' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'settings', 'sort_results_by_votes', __( 'Sort results by votes', 'gd-topic-polls' ), __( 'When displaying results, responses will be sorted by the number of votes.', 'gd-topic-polls' ), Type::BOOLEAN, $this->value( 'sort_results_by_votes' ) ),
							),
						),
					),
				),
			),
			'labels'      => array(
				'labels_post_type_poll' => array(
					'name'     => __( 'Poll', 'gd-topic-polls' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'objects', 'label_poll_singular', __( 'Singular', 'gd-topic-polls' ), '', Type::TEXT, $this->value( 'label_poll_singular', 'objects' ) ),
								EL::i( 'objects', 'label_poll_plural', __( 'Plural', 'gd-topic-polls' ), '', Type::TEXT, $this->value( 'label_poll_plural', 'objects' ) ),
							),
						),
					),
				),
			),
		);
	}

	private function get_capability_methods() : array {
		return array(
			'role' => __( 'Check User Role only', 'gd-topic-polls' ),
			'cap'  => __( 'Check the Capability', 'gd-topic-polls' ),
		);
	}
}
