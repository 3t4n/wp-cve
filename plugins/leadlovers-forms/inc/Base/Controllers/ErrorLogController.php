<?php 
/**
 * @package  LeadloversPlugin
 */
namespace  LeadloversInc\Base\Controllers;

use LeadloversInc\Base\BaseController;

class ErrorLogController extends BaseController
{

    public function register() {
    	add_action( 'init', array( $this, 'init' ) );
		add_action('wp_ajax_leadlovers-save-error-log', array( $this, 'save'));
		add_action('wp_ajax_nopriv_leadlovers-save-error-log', array( $this, 'save'));
		add_action( 'manage_ll-error-logs_posts_columns', array( $this, 'set_custom_columns' ) );
		add_action( 'manage_ll-error-logs_posts_custom_column', array( $this, 'set_custom_columns_data' ), 10, 2 );
		add_filter( 'manage_ll-error-logs_posts_sortable_columns', array( $this, 'set_custom_columns_sortable' ) );
		add_filter('post_row_actions', array( $this, 'remove_quick_edit' ), 10, 2);
	}
	
    function init() {
        $labels = array(
            'name' => _x('Leadlovers - Log de erros', 'll-error-logs')
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'register_meta_box_cb' => 'error-log_meta_box',       
            'supports' => array('title', 'custom-fields'),
            'capability_type' => 'post',
            'show_in_menu' => false,
            'capabilities' => array(
                'create_posts' => false,
            ),
            'map_meta_cap' => true,
          );

          register_post_type( 'll-error-logs' , $args );
    }

	function remove_quick_edit( $actions, $post ) { 
		if($post->post_type == 'll-error-logs') {
			unset($actions['edit']);
			unset($actions['view']);
			unset($actions['inline hide-if-no-js']);
		}
		return $actions;
   }

    function save()
	{
		if (! DOING_AJAX  && ! wp_verify_nonce( $_POST['nonce'], 'leadlovers-save-error-log_nonce' )) {
			return 400;
		}

		$form_id = sanitize_text_field($_POST['form_id']);
        $page = str_replace(get_site_url(), "", sanitize_text_field($_POST['page']));
		$machine_name = sanitize_text_field($_POST['machine_name']);
		$funnel_name = sanitize_text_field($_POST['funnel_name']);
		$sequence_name = sanitize_text_field($_POST['sequence_name']);
        $json = sanitize_text_field($_POST['json']);
        $error = sanitize_text_field($_POST['error']);

		$data = array(
            'form' => 'Integração #' .  $form_id,
            'page' => $page,
			'machine_name' => $machine_name,
			'funnel_name' => $funnel_name,
			'sequence_name' => $sequence_name,
            'json' => $json,
            'error' => $error
		);
		
        $args = array(
            'post_title' => 'Integração #' . $form_id,
            'post_status' => 'publish',
            'post_type' => 'll-error-logs',
            'meta_input' => array(
                '_leadlovers-log-key' => $data
            )
        );
        $postId = wp_insert_post($args);	
        if ($postId) {
            return wp_send_json(array(
                'status' => 200, 
                'data' => $data
            ));
        }

		return wp_send_json(array(
			'status' => 400));
	}

    public function set_custom_columns($columns)
	{
		$columns['page'] = 'Página';
		$columns['machine_name'] = 'Máquina';
		$columns['funnel_name'] = 'Funil';
		$columns['sequence_name'] = 'Sequência';
        $columns['json'] = 'Informações do lead';
		$columns['error'] = 'Erro';

		return $columns;
	}

	public function set_custom_columns_data($column, $post_id)
	{
		$data = get_post_meta( $post_id, '_leadlovers-log-key', true );
		$page = isset($data['page']) ? $data['page'] : '';
		$machine_name = isset($data['machine_name']) ? $data['machine_name'] : '';
		$funnel_name = isset($data['funnel_name']) ? $data['funnel_name'] : '';
		$sequence_name = isset($data['sequence_name']) ? $data['sequence_name'] : '';
        $error = isset($data['error']) ? $data['error'] : '';
        $json = isset($data['json']) ? $data['json'] : '';

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
			case 'json':
				$leadData = json_decode($json);
				if(property_exists($leadData, "email"))
					echo "Email: " . esc_attr($leadData->email) . "<br />";
				if(property_exists($leadData, "name"))
					echo "Nome: " . esc_attr($leadData->name) . "<br />";
				if(property_exists($leadData, "phone"))
					echo "Telefone: " . esc_attr($leadData->phone) . "<br />";
				break;
            case 'error':
                echo "Ocorreu um erro ao salvar o lead";
                break;
		}
	}

	public function set_custom_columns_sortable($columns)
	{
		return $columns;
	}
}
