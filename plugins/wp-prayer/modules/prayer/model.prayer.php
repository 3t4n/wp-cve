<?php
/**
 * Class: WPE_Model_Prayer
 * @author Flipper Code <hello@flippercode.com>
 * @package Maps
 * @version 3.0.0
 */
if ( ! class_exists('WPE_Model_Prayer')) {
    /**
     * Prayer model for CRUD operation.
     * @package Maps
     * @author Flipper Code <hello@flippercode.com>
     */
    class WPE_Model_Prayer extends FlipperCode_WPE_Model_Base
    {
        /**
         * Validations on location properies.
         * @var array
         */
        //public $validations = array(
            //'prayer_title' => array( 'req' => 'Please enter title.'),
            //'prayer_messages' => array('req' => 'Please enter message.'),
        //);

        /**
         * Intialize location object.
         */
        public function __construct()
        {
            $this->table = WPE_TBL_PRAYER;
            $this->table_users = WPE_TBL_PRAYER_USERS;
            $this->unique = 'prayer_id';
        }

        /**
         * Admin menu for CRUD Operation
         * @return array Admin meny navigation(s).
         */
        public function navigation()
        {
            return array(
                'wpe_form_prayer' => __('Add Prayer', WPE_TEXT_DOMAIN),
                'wpe_manage_prayer' => __('Manage Prayers', WPE_TEXT_DOMAIN),
            );
        }

        /**
         * Install table associated with Prayer entity.
         * @return string SQL query to install map_prayers table.
         */
        public function install()
        {
            global $wpdb;
            $map_prayer = 'CREATE TABLE '.$wpdb->prefix.'prayer_users (
							pu_id int(11) NOT NULL AUTO_INCREMENT,
							manage_pr_disp boolean NOT NULL DEFAULT false,
							prayer_id int(11) NOT NULL DEFAULT 0,
							user_id int(11) NOT NULL DEFAULT 0,
							user_ip varchar(255) DEFAULT NULL,
							prayer_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
							PRIMARY KEY (pu_id)
							) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;';


            return $map_prayer;
        }

        /**
         * Get Prayer(s)
         * @param  array $where Conditional statement.
         * @return array         Array of Prayer object(s).
         */
        public function fetch($where = array(), $sortBy = '', $ascending = true, $limit = '', $offset = '')
        {
            $objects = $this->get($this->table, $where, $sortBy, $ascending, $limit, $offset);
            if (isset($objects)) {
                return $objects;
            }
        }

        /**
         * Add or Edit Operation.
         */
        public function save()
        {	$nonce = wp_create_nonce( 'wpgmp-nonce3' );
            $entityID = '';
            if (isset($_REQUEST['_wpnonce'])) {
                $nonce = sanitize_text_field($_REQUEST['_wpnonce']);
            }
            if ( !isset( $nonce ) || ! wp_verify_nonce($nonce, 'wpgmp-nonce3')) {
                die('Cheating...');
            }

            if (isset($_POST['request_type']) && $_POST['request_type'] == 'prayer_request') {
                $this->validations['prayer_title'] = array('req' => __('Title',WPE_TEXT_DOMAIN));
            }
			$this->validations['prayer_messages'] = array('req' => __('Prayer Request',WPE_TEXT_DOMAIN));
            $this->verify($_POST);
            if (is_array($this->errors) and ! empty($this->errors)) {
                $this->throw_errors();
            }
            if (isset($_POST['entityID'])) {
                $entityID = intval(sanitize_text_field($_POST['entityID']));
            }
            if (isset($_POST['prayer_messages'])) {
                $data['prayer_messages'] = sanitize_textarea_field(stripslashes_deep( wp_encode_emoji($_POST['prayer_messages'])));
            }
            if (isset($_POST['prayer_title'])) {$data['prayer_title'] = sanitize_text_field($_POST['prayer_title']);} else {$data['prayer_title'] = $_SERVER['REMOTE_ADDR'];}
            $data['prayer_author'] = get_current_user_id();

            $data['prayer_status'] = 'approved';
            $data['prayer_time'] = date('Y-m-d H:i:s');
            $lxt_options = get_option('_wpe_prayer_engine_settings');
            $lxt_options = unserialize($lxt_options);

            if ( ! empty($lxt_options) && array_key_exists('wpe_disapprove_prayer_default', $lxt_options)) {

                $data['prayer_status'] = (filter_var($lxt_options['wpe_disapprove_prayer_default'],
                    FILTER_VALIDATE_BOOLEAN)) ? 'pending' : 'approved';
            }
            
            if (isset($_POST['prayer_notify']) && ! empty($_POST['prayer_notify'])) {$data['prayer_lastname'] ='*';} else {$data['prayer_lastname'] ='';}
            $data['request_type'] = sanitize_text_field($_POST['request_type']);

            if ($entityID > 0) {
                $where[$this->unique] = $entityID;
            } else {
                $where = '';
            }
            $result = FlipperCode_Database::insert_or_update($this->table, $data, $where);
            if (false === $result) {
                $response['error'] = __('Something went wrong. Please try again.', WPE_TEXT_DOMAIN);
            } elseif ($entityID > 0) {
                $response['success'] = __('Prayer updated successfully', WPE_TEXT_DOMAIN);
            } else {
                $response['success'] = __('Prayer added successfully.', WPE_TEXT_DOMAIN);
            }

            return $response;
        }

        /**
         * Delete location object by id.
         */
        public function delete()
        {
            if (isset($_GET['prayer_id'])) {
                $id = intval(sanitize_text_field($_GET['prayer_id']));
                $connection = FlipperCode_Database::connect();
                $this->query = $connection->prepare("DELETE FROM $this->table WHERE $this->unique='%d'", $id);

                return FlipperCode_Database::non_query($this->query, $connection);

            }
        }

        /**
         * Approve / Dispprove Prayer Status.
         */
        public function change_prayer_status($id, $status)
        {
            //$connection = FlipperCode_Database::connect();
            //$this->query = $connection->prepare("UPDATE $this->table SET prayer_status = '$status' WHERE $this->unique IN ($id);");
            //$result = FlipperCode_Database::non_query( $this->query, $connection );
            // return $result;

            //Todo:fixed approve/disapprove issue
            global $wpdb;
            $result = $wpdb->query(
                $wpdb->prepare("UPDATE $this->table SET prayer_status = '%s' WHERE $this->unique = '%d' ",
                    $status, $id
                )
            );

            return $result > 0;
        }

        /**
         * Save users who prayed for specific prayer
         */
        public function save_prayer_users($prayer_id, $user_id, $user_ip)
        {
            $where = '';
            $data['prayer_id'] = $prayer_id;
            $data['user_id'] = $user_id;
            $data['user_ip'] = $user_ip;
            $result = FlipperCode_Database::insert_or_update($this->table_users, $data, $where);

            return $result;
        }
    }
}
