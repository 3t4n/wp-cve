<?php

namespace Reuse\Builder;

class RestAPISupport
{
    public function __construct()
    {
        add_action('init', array($this, 'custom_taxonomy_post_types_api_support'), 25);
        add_action('rest_api_init', array($this, 'portal_add_user_field'), 10, 2);
    }

    public function custom_taxonomy_post_types_api_support()
    {
        global $wp_post_types, $wp_taxonomies, $wpdb;

        $args = array(
            'post_type' => 'reuseb_post_type',
            'posts_per_page' => -1,
        );

        $post_types = get_posts($args);

        foreach ($post_types as $key => $post_type) {
            $rest_api_support = false;
            $post_helpers = get_post_meta($post_type->ID, '_reuse_builder_post_types_data', true);
            $post_types_data = json_decode($post_helpers, true);
            $post_type_name = str_replace(' ', '_', strtolower($post_type->post_title));
            if (!empty($post_types_data['reuseb_meta_support_rest_api'])) {
                $rest_api_support = $post_types_data['reuseb_meta_support_rest_api'] === 'true' ? true : false;
            }
            if ($rest_api_support) {
                register_rest_field($post_type_name, 'metadata', array(
                    'get_callback' => function ($data) {
						$meta = get_post_meta($data['id'], '', '');
						$processed_data = [];
						foreach ($meta as $key => $value) {
							$processed_data[$key] = maybe_unserialize($value[0]);
						}
                        return $processed_data;
                    },
                ));
            }
        }
        $rest_support_data = json_decode(stripslashes_deep(get_option('reuseb_settings', true)), true);
        $rest_supported_post_types = isset($rest_support_data['rest_enable_post_types']) ? explode(',', $rest_support_data['rest_enable_post_types']) : [];
        foreach ($rest_supported_post_types as $key => $post_type_name) {
            if (isset($wp_post_types[$post_type_name])) {
                $wp_post_types[$post_type_name]->show_in_rest = true;
                $wp_post_types[$post_type_name]->rest_base = $post_type_name;
                $wp_post_types[$post_type_name]->rest_controller_class = 'WP_REST_Posts_Controller';
            }
        }
        $rest_supported_post_type_meta = isset($rest_support_data['rest_enable_post_types_meta']) ? explode(',', $rest_support_data['rest_enable_post_types_meta']) : [];
        if (empty($rest_supported_post_type_meta)) {
            $rest_supported_post_type_meta = array('post');
        }
        $post_type_placeholder = implode(', ', array_fill(0, count($rest_supported_post_type_meta), '%s'));
        $query = $wpdb->prepare("SELECT meta_key FROM {$wpdb->postmeta} as meta LEFT JOIN {$wpdb->posts} as posts ON meta.post_id = posts.ID
                    WHERE posts.post_type IN ($post_type_placeholder)", $rest_supported_post_type_meta);
        $results = $wpdb->get_results($query);
        foreach ($results as $childkey => $value) {
            register_rest_field('post', $value->meta_key, array(
                'update_callback' => function ($meta_value, $post, $meta_key) {
                    $postId = $post->ID;
                    update_post_meta($postId, $meta_key, $meta_value);
                },
            ));
        }
        foreach ($rest_supported_post_type_meta as $key => $post_type_name) {
            register_rest_field($post_type_name, 'metadata', array(
                'get_callback' => function ($data) {
                    $meta = get_post_meta($data['id'], '', '');
					$processed_data = [];
					foreach ($meta as $key => $value) {
						$processed_data[$key] = maybe_unserialize($value[0]);
					}
					return $processed_data;
				},
            ));
        }
        //be sure to set this to the name of your taxonomy!
        $rest_supported_taxonomies = isset($rest_support_data['rest_enable_taxonomy']) ? explode(',', $rest_support_data['rest_enable_taxonomy']) : [];
        foreach ($rest_supported_taxonomies as $key => $taxonomy_name) {
            if (isset($wp_taxonomies[$taxonomy_name])) {
                $wp_taxonomies[$taxonomy_name]->show_in_rest = true;
                // Optionally customize the rest_base or controller class
                $wp_taxonomies[$taxonomy_name]->rest_base = $taxonomy_name;
                $wp_taxonomies[$taxonomy_name]->rest_controller_class = 'WP_REST_Terms_Controller';
            }
        }
        $rest_supported_taxonomies = isset($rest_support_data['rest_enable_term_meta']) ? explode(',', $rest_support_data['rest_enable_term_meta']) : [];
        foreach ($rest_supported_taxonomies as $key => $taxonomy_name) {
            register_rest_field(
                $taxonomy_name,
                'metadata',
                array(
                    'get_callback' => function ($object, $field_name, $request) {
						$termmeta = get_term_meta($object['id'], '', true);
						$processed_data = [];
						foreach ($termmeta as $key => $value) {
							$processed_data[$key] = maybe_unserialize($value[0]);
						}
                        return $processed_data;
                    },
                    'update_callback' => null,
                    'schema' => null,
                )
            );
        }
    }

    public function portal_add_user_field()
    {
        register_rest_field(
            'user',
            'metadata',
            array(
                'get_callback' => function ($user, $field_name, $request) {
					$meta = get_user_meta($user['id'], '', true);
					$processed_data = [];
						foreach ($meta as $key => $value) {
							$processed_data[$key] = maybe_unserialize($value[0]);
						}
					$processed_data = $this->process_user_meta($processed_data);
                    return $processed_data;
                },
                'update_callback' => function ($meta_value) {
                    $havemetafield = get_user_meta(1, 'metadata', false);
                    if ($havemetafield) {
                        $ret = update_user_meta(1, 'metadata', $meta_value);

                        return true;
                    } else {
                        $ret = add_user_meta(1, 'metadata', $meta_value, true);

                        return true;
                    }
                },
                'schema' => null,
            )
        );
	}
	
	public function process_user_meta($processed_data)
	{
		unset($processed_data['admin_color']);
		unset($processed_data['rich_editing']);
		unset($processed_data['comment_shortcuts']);
		unset($processed_data['wp_user-settings-time']);
		unset($processed_data['metaboxhidden_post']);
		unset($processed_data['closedpostboxes_post']);
		unset($processed_data['community-events-location']);
		unset($processed_data['wp_dashboard_quick_press_last_post_id']);
		unset($processed_data['wp_user-settings']);
		unset($processed_data['session_tokens']);
		unset($processed_data['show_welcome_panel']);
		unset($processed_data['dismissed_wp_pointers']);
		unset($processed_data['wp_user_level']);
		unset($processed_data['locale']);
		unset($processed_data['wp_capabilities']);
		unset($processed_data['show_admin_bar_front']);
		unset($processed_data['use_ssl']);
		unset($processed_data['comment_shortcuts']);
		unset($processed_data['rich_editing']);
		unset($processed_data['admin_color']);
		unset($processed_data['syntax_highlighting']);
		unset($processed_data['wp_fireauth']);

		return $processed_data;
	}
}
