<?php
/**
 * REST API Upsell controller
 *
 * Handles requests to the /upsells endpoint.
 */

defined('ABSPATH') || exit;

/**
 * REST API Upsell controller class.
 */
class WC_REST_Upsell_Controller
{
    /**
     * Endpoint namespace.
     *
     * @var string
     */
    protected string $namespace = 'wc/v3';

    /**
     * Route base.
     *
     * @var string
     */
    protected string $rest_base = 'upsells';

    /**
     * Register the routes for upsells.
     */
    public function register_routes(): void
    {
        register_rest_route( $this->namespace, '/' . $this->rest_base, array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( $this, 'get_items' ),
                'permission_callback' => '__return_true',
            ),
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( $this, 'create_item' ),
                'permission_callback' => '__return_true',
            ),
        ));

        register_rest_route(
            $this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', array(
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the resource.', 'mypos'),
                        'type' => 'integer',
                    ),
                ),
                array(
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => array($this, 'get_item'),
                    'permission_callback' => '__return_true',
                ),
                array(
                    'methods' => WP_REST_Server::EDITABLE,
                    'callback' => array($this, 'update_item'),
                    'permission_callback' => '__return_true',
                ),
                array(
                    'methods' => WP_REST_Server::DELETABLE,
                    'callback' => array($this, 'delete_item'),
                    'permission_callback' => '__return_true',
                ),
            )
        );
    }

    public function get_items(): WP_Error|WP_REST_Response|WP_HTTP_Response
    {
        global $wpdb;

        $upsells = $wpdb->get_results("SELECT * FROM wp_mypos_upsells ORDER BY date_created DESC ");

        foreach ($upsells as $upsell) {
            $upsell->base_products = unserialize($upsell->base_products);
            $upsell->recommended_products = unserialize($upsell->recommended_products);
        }

        return rest_ensure_response($upsells);
    }

    public function get_item(WP_REST_Request $request ): WP_Error|WP_REST_Response|WP_HTTP_Response
    {
        global $wpdb;

        if (empty($request->get_param('id'))) {
            return new WP_Error( 'missing_id', __('Missing ID.', 'mypos'));
        }

        $upsell = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_mypos_upsells WHERE id = %s", $request->get_param('id')));

        if (!$upsell) {
            return new WP_Error( 'doesnt_exist', __('Upsell does not exist.', 'mypos'));
        }

        return rest_ensure_response($upsell);
    }

    public function create_item(WP_REST_Request $request): WP_Error|WP_REST_Response|WP_HTTP_Response
    {
        global $wpdb;

        $data = $this->validateData($request->get_params());

        $existingUpsell = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_mypos_upsells WHERE name = %s", $data['name']));
        if ($existingUpsell) {
            return new WP_Error( 'already_exists', __('Upsell with name (' . $data['name'] . ') already exists.', 'mypos'));
        }

        $data['date_created'] = date('Y-m-d H:i:s');

        $result = $wpdb->insert('wp_mypos_upsells', $data);

        if ($result) {
            $upsell = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_mypos_upsells WHERE id = %s", $wpdb->insert_id));

            return rest_ensure_response($upsell);
        } else {
            return new WP_Error( 'cannot_create', __('The resource cannot be created.', 'mypos'));
        }
    }

    public function update_item(WP_REST_Request $request): WP_Error|WP_REST_Response|WP_HTTP_Response
    {
        global $wpdb;

        if (empty($request->get_param('id'))) {
            return new WP_Error( 'missing_id', __('Missing ID.', 'mypos'));
        }

        $upsell = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_mypos_upsells WHERE id = %s", $request->get_param('id')));

        if (!$upsell) {
            return new WP_Error( 'doesnt_exist', __('Upsell does not exist.', 'mypos'));
        }

        $data = $this->validateData($request->get_params());

        $upsellDuplicate = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_mypos_upsells WHERE name = %s", $data['name']));
        if ($upsellDuplicate->id !== $upsell->id) {
            return new WP_Error( 'already_exists', __('Another upsell with name (' . $data['name'] . ') already exists.', 'mypos'));
        }

        $data['date_updated'] = date('Y-m-d H:i:s');

        $result = $wpdb->update('wp_mypos_upsells', $data, ['id'=> (int)$upsell->id]);

        if ($result) {
            $upsell = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_mypos_upsells WHERE id = %s", $upsell->id));

            return rest_ensure_response($upsell);
        } else {
            return new WP_Error( 'cannot_update', __('The resource cannot be updated.', 'mypos'));
        }
    }

    public function delete_item(WP_REST_Request $request ) {
        global $wpdb;

        if (empty($request->get_param('id'))) {
            return new WP_Error( 'missing_id', __('Missing ID.', 'mypos'));
        }

        $upsell = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_mypos_upsells WHERE id = %s", $request->get_param('id')));

        if (!$upsell) {
            return new WP_Error( 'doesnt_exist', __('Upsell does not exist.', 'mypos'));
        }

        $result = $wpdb->delete('wp_mypos_upsells', ['id'=> (int)$upsell->id]);

        if ($result) {
            return rest_ensure_response($upsell);
        } else {
            return new WP_Error( 'cannot_update', __('The resource cannot be updated.', 'mypos'));
        }
    }

    public function validateData($data)
    {
        $required = ["name", "base_products", "recommended_products"];

        foreach ($required as $value) {
            if (!array_key_exists($value, $data) || empty($data[$value])) {
                return new WP_Error( 'missing_data', __('Missing ' . $value . ' .', 'mypos'));
            }

            if (is_array($data[$value])) {
                $validated[$value] = maybe_serialize(array_map('intval', $data[$value]));
            } else {
                $validated[$value] = $data[$value];
            }
        }

        return $validated;
    }
}

