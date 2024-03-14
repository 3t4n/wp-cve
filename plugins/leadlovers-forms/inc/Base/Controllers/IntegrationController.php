<?php 
/**
 * @package  LeadloversPlugin
 */
namespace  LeadloversInc\Base\Controllers;

use LeadloversInc\Base\BaseController;

class IntegrationController extends BaseController
{

    public function register() {
    	add_action( 'init', array( $this, 'init' ) );
		add_action('wp_ajax_leadlovers-save-integration', array( $this, 'save'));
		add_action('wp_ajax_nopriv_leadlovers-save-integration', array( $this, 'save'));
		add_action('wp_ajax_leadlovers-get-integrations', array( $this, 'get_all_per_page'));
		add_action('wp_ajax_nopriv_leadlovers-get-integrations', array( $this, 'get_all_per_page'));
		add_action( 'manage_ll-integrations_posts_columns', array( $this, 'set_custom_columns' ) );
		add_action( 'manage_ll-integrations_posts_custom_column', array( $this, 'set_custom_columns_data' ), 10, 2 );
		add_filter( 'manage_ll-integrations_posts_sortable_columns', array( $this, 'set_custom_columns_sortable' ) );
		add_filter('post_row_actions', array( $this, 'remove_quick_edit' ), 10, 2);
	}
	
    function init() {
        $labels = array(
            'name' => _x('Leadlovers - Minhas integrações', 'll-integrations')
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'register_meta_box_cb' => 'integrations_meta_box',       
            'supports' => array('title', 'custom-fields'),
            'capability_type' => 'post',
            'show_in_menu' => false,
            'capabilities' => array(
                'create_posts' => false,
            ),
            'map_meta_cap' => true,
          );

          register_post_type( 'll-integrations' , $args );
    }

	function remove_quick_edit( $actions, $post ) { 
		if($post->post_type == 'll-integrations') {
			unset($actions['edit']);
			unset($actions['view']);
			unset($actions['inline hide-if-no-js']);
		}
		return $actions;
   }

    function save()
	{
		if (! DOING_AJAX  && ! wp_verify_nonce( $_POST['nonce'], 'leadlovers-save-integration_nonce' )) {
			return 400;
		}

		$form_id = sanitize_text_field($_POST['form_id']);
        $page = str_replace(get_site_url(), "", sanitize_text_field($_POST['page']));
		$machine_id = sanitize_text_field($_POST['machine_id']);
		$machine_name = sanitize_text_field($_POST['machine_name']);
		$funnel_id = sanitize_text_field($_POST['funnel_id']);
		$funnel_name = sanitize_text_field($_POST['funnel_name']);
		$sequence_id = sanitize_text_field($_POST['sequence_id']);
		$sequence_name = sanitize_text_field($_POST['sequence_name']);
        $tags = sanitize_text_field($_POST['tags']);
        $mapped_fields = sanitize_text_field($_POST['mapped_fields']);
        $active =$_POST['active'] === 'true' ? 1 : 0;

		$data = array(
            'form_id' => $form_id,
            'page' => $page,
            'machine_id' => $machine_id,
			'machine_name' => $machine_name,
            'funnel_id' => $funnel_id,
			'funnel_name' => $funnel_name,
			'sequence_id' => $sequence_id,
			'sequence_name' => $sequence_name,
            'tags' => $tags,
            'mapped_fields' => $mapped_fields,
            'active' => $active
		);
		
		$existentForm = get_posts(array(
			'numberposts' => 1,
			'post_type' => 'll-integrations',
			'meta_query'	=> array(
				'relation'		=> 'AND',
				array(
					'key'	 	=> '_leadlovers-integration-key',
					'value'	  	=> 's:7:"form_id";s:' . strlen($form_id) . ':"' . $form_id . '";',
					'compare' 	=> 'LIKE',
				),
				array(
					'key'	 	=> '_leadlovers-integration-key',
					'value'	  	=> 's:4:"page";s:' . strlen($page) . ':"' . $page . '";',
					'compare' 	=> 'LIKE',
				),
			),
		))[0];

		if ($existentForm != null) {
			$args = array(
				'ID' => $existentForm->ID,
				'post_type' => 'll-integrations',
				'meta_input' => array(
					'_leadlovers-integration-key' => $data
				)
			);
			wp_update_post($args);
			return wp_send_json(array(
				'status' => 200, 
				'data' => $data
			));
		} else {
			$args = array(
				'post_title' => 'Integração #' . $form_id,
				'post_status' => 'publish',
				'post_type' => 'll-integrations',
				'meta_input' => array(
					'_leadlovers-integration-key' => $data
				)
			);
			$postId = wp_insert_post($args);	
			if ($postId) {
				return wp_send_json(array(
					'status' => 200, 
					'data' => $data
				));
			}
		}

		return wp_send_json(array(
			'status' => 400));
	}

	function get_all_per_page() {
		if (! DOING_AJAX  && ! wp_verify_nonce( $_POST['nonce'], 'leadlovers-get-integrations_nonce' )) {
			return wp_send_json(array(
				'status' => 400));
		}

		$page = str_replace(get_site_url(), "", sanitize_text_field($_POST['page']));

		$integrations = get_posts(array(
			'numberposts' => -1,
			'post_type' => 'll-integrations',
			'meta_query'	=> array(
				'relation'		=> 'AND',
				array(
					'key'	 	=> '_leadlovers-integration-key',
					'value'	  	=> 's:4:"page";s:' . strlen($page) . ':"' . $page . '";',
					'compare' 	=> 'LIKE',
				),
			)
		));
		$list = [];
		foreach($integrations as $integration) {
			array_push($list, get_post_meta($integration->ID, '_leadlovers-integration-key', false)[0]);
		}
	   	return wp_send_json(array(
			   'status' => 200,
			    'data' => $list));
	}

    public function set_custom_columns($columns)
	{
		$columns['page'] = 'Página do formulário';
		$columns['machine_name'] = 'Máquina';
		$columns['funnel_name'] = 'Funil';
		$columns['sequence_name'] = 'Sequência';
		$columns['active'] = 'Ativo?';

		return $columns;
	}

	public function set_custom_columns_data($column, $post_id)
	{
		$data = get_post_meta( $post_id, '_leadlovers-integration-key', true );
		$page = isset($data['page']) ? $data['page'] : '';
		$machine_name = isset($data['machine_name']) ? $data['machine_name'] : '';
		$funnel_name = isset($data['funnel_name']) ? $data['funnel_name'] : '';
		$sequence_name = isset($data['sequence_name']) ? $data['sequence_name'] : '';
		$active = isset($data['active']) && $data['active'] === 1 ? 'Sim' : 'Não';

		switch($column) {
			case 'page':
				echo esc_attr($page);
				break;
			case 'machine_name':
				echo esc_attr($machine_name);
				break;
			case 'funnel_name':
				echo esc_attr($funnel_name);
				break;
			case 'sequence_name':
				echo esc_attr($sequence_name);
				break;
			case 'active':
				echo esc_attr($active);
				break;
		}
	}

	public function set_custom_columns_sortable($columns)
	{
		$columns['active'] = 'active';

		return $columns;
	}
}
