<?php
// if called directly, abort.
if (!defined('WPINC')) { die; }

class raysgrid_Tables {

    public function __construct() {}

    public function rsgd_select()
    {
        
        global $wpdb;
        $gridSetting    = $wpdb->get_results("SELECT * FROM ".RSGD_TBL);
        $noRows         = $wpdb->get_results("SELECT COUNT(*) FROM ".RSGD_TBL);
        $array_rows     = [$noRows];
        $rows           = $array_rows[0][0];
        $general_array  = [];

        array_push($general_array, $gridSetting, $rows);
        return $general_array;

    }

    public function rsgd_selectWithId($id)
    {

        global $wpdb;
        $gridSetting    = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".RSGD_TBL." WHERE  id=%d ", $id));
        $general_array  = [];

        array_push($general_array, $gridSetting);
        return $general_array;
        
    }

    public static function rsgd_AddSQL()
    {
        
        global $wpdb;
        ob_start();
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS ".RSGD_TBL."(";
            $sql .= implode(", ", self::rsgd_forLoop());
        $sql .= ", UNIQUE KEY (id)) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        ob_clean();
    }

    public static function rsgd_forLoop()
    {
        
        $configs = new raysgrid_Config();
        $defult_args = self::rsgd_defult_args();
        $conficArr = $configs->rsgd_configs();
        $itemArray = [];

        foreach ($conficArr as $value) {
            $auto_val = isset($value['auto']) ? $value['auto'] : $defult_args['auto'];
            if ($value['name'] != 'oldalias') {
                $itemArray[] = $value['name'] . ' ' . $value['data_type'] . ' ' . $value['not_null'] . ' ' . $auto_val;
            }
        }
        return $itemArray;
        
    }

    public static function rsgd_defult_args()
    {

	    return [
            "name"          => '',
            "title"         => '',
            "data_type"     => 'text',
            "type"          => 'text',
            "section"       => '',
            "class"         => '',
            "description"   => '',
            "placeholder"   => '',
            "std"           => '',
            "not_null"      => 'NOT NULL',
            "auto"          => '',
            "choices"       => [],
            "parent"        => '',
            "group"         => '',
            "min"           => '',
            "max"           => '',
            'dependency'    => [],
        ];

    }

    public function rsgd_delRow($id)
    {
        
        global $wpdb;
        $where = ['id' => $id];
        $wpdb->delete(RSGD_TBL, $where);
        
    }
    
    public function rsgd_strip_html_tags( $text )
    {

        $text = preg_replace(
            [
                '@<head[^>]*?>.*?</head>@siu',
                '@<object[^>]*?.*?</object>@siu',
                '@<embed[^>]*?.*?</embed>@siu',
                '@<applet[^>]*?.*?</applet>@siu',
                '@<noframes[^>]*?.*?</noframes>@siu',
                '@<noscript[^>]*?.*?</noscript>@siu',
                '@<noembed[^>]*?.*?</noembed>@siu',
                '@</?((address)|(blockquote)|(center)|(del))@iu',
                '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
                '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
                '@</?((table)|(th)|(td)|(caption))@iu',
                '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
                '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
                '@</?((frameset)|(frame)|(iframe))@iu',
            ],
            [
                ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
                "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
                "\n\$0", "\n\$0",
            ],
            $text );
        return strip_tags( $text );
    }

    public function rsgd_insert_update($id)
    {
        
        global $wpdb;

	    if ( isset($_POST['rsgd_data']) && $_POST['rsgd_data'] ) {

		    if ( ( isset( $_POST['rsgd_nonce_fields'] ) || ! wp_verify_nonce( $_POST['rsgd_nonce_fields'], 'rsgd_nonce_fields' ) ) && ! current_user_can( 'edit_others_posts' ) ) {
			    return;
		    }

		    $rsgd_title     = ( isset( $_POST['rsgd_data']['title'] ) ) ? sanitize_text_field( $_POST['rsgd_data']['title'] ) : '';
		    $rsgd_shortcode = ( isset( $_POST['rsgd_data']['shortcode'] ) ) ? sanitize_text_field( $_POST['rsgd_data']['shortcode'] ) : '';
		    $rsgd_alias     = ( isset( $_POST['rsgd_data']['alias'] ) ) ? sanitize_text_field( $_POST['rsgd_data']['alias'] ) : '';
		    $oldalia        = ( isset( $_POST['rsgd_hidden']['oldalias'] ) ) ? sanitize_text_field( $_POST['rsgd_hidden']['oldalias'] ) : '';

		    $exist_alias   = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . RSGD_TBL . " WHERE  alias=%s ", $rsgd_alias ) );
		    $where         = [ 'id' => $id ];
		    $setings_array = [];

		    foreach ( $_POST['rsgd_data'] as $key => $value ) {
			    $setings_array[ $key ] = $this->rsgd_strip_html_tags( stripcslashes( $value ) );
		    }

		    if ( $rsgd_alias == $oldalia && $oldalia != '' ) {

			    $wpdb->update( RSGD_TBL, $setings_array, $where );

		    } else if ( $rsgd_alias != $oldalia && $oldalia != '' ) {

			    if ( empty( $exist_alias ) ) {
				    $wpdb->update( RSGD_TBL, $setings_array, $where );
			    }

		    } else if ( ! empty( $rsgd_title ) && ! empty( $rsgd_alias ) ) {

			    if ( empty( $exist_alias ) ) {
				    $wpdb->insert( RSGD_TBL, $setings_array );
			    }

		    } else if ( ! isset( $rsgd_title ) || ! isset( $rsgd_alias ) || ! isset( $rsgd_shortcode ) ) {
			    exit();
		    }
	    }
    }

    public function rsgd_duplicate_row($table, $id)
    {
        
        global $wpdb;
        $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table} WHERE  id=%d ", $id));
        $config = new raysgrid_Config();
        $confArr = $config->rsgd_configs();
        $itemArray = [];
        foreach ($confArr as $value) {
            if ($value['name'] != 'oldalias') {
                $itemArray[] = [$value['name'] => $result->$value['name']];
            }
        }
        unset($itemArray[0], $itemArray[1], $itemArray[2], $itemArray[3]);
        
        $itemArray[1]   = [ 'title' => $result->title . ' Copy' ];
        $itemArray[2]   = [ 'alias' => $result->alias . '-copy' ];
        $itemArray[3]   = [ 'shortcode' => '['.RSGD_PFX.' alias="' . esc_attr( $result->alias ) . '-copy"]' ];
        $count          = count($itemArray);
        $row_arr        = [];
        
        for ($i = 1; $i <= $count; $i++) {
            foreach ($itemArray[$i] as $key => $val) {
                $row_arr[$key] = stripcslashes($val);
            }
        }
        $wpdb->insert($table, $row_arr);
        
    }

    public function rsgd_export_data()
    {
        
        global $wpdb;
        $gridSetting    = $wpdb->get_results( "SELECT * FROM " . RSGD_TBL );

        if (!empty($gridSetting)) {
            nocache_headers();
            header('Content-Type: text/plain; charset=utf-8');
            header('Content-Disposition: attachment; filename='.RSGD_PFX.'_export-' . date('d-m-Y[h:i:s]') . '.json');
            header("Expires: 0");
            ob_end_clean();
            echo json_encode($gridSetting);
            exit;
        }
        
    }

    public function rsgd_import_file()
    {
        
        global $wpdb;
        $gridSetting    = $wpdb->get_results("SELECT * FROM ".RSGD_TBL);
        $file_name      = $_FILES['importfile']['name'];
        $ext            = explode('.', $file_name);
        $file_extension = end($ext);
        $import_file    = $_FILES['importfile']['tmp_name'];
        $grst_Alias     = [];
        
        if ($file_extension != 'json') {
            wp_die( esc_html__('Please upload a valid .json file', RSGD_SLUG ) );
        }
        
        foreach ($gridSetting as $rsgd_alia) {
            $grst_Alias[] = $rsgd_alia->alias;
        }
        
        $grst_Alias2 = implode(" ", $grst_Alias);
        if ( !empty( $import_file ) ) {
            $configs    = new raysgrid_Config();
            $confArr    = $configs->rsgd_configs();
            $itemArray  = [];
            $jsonData   = (array) json_decode( file_get_contents( $import_file ), true );
            
            foreach ( $jsonData as $row ) {
                if ( strpos($grst_Alias2, $row['alias']) !== false ) {
                    $getAlias = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".RSGD_TBL." WHERE  alias=%s ", $row['alias']));
                    foreach ( $confArr as $key => $value ) {
                        if ($value['name'] == 'id') {
                            $itemArray[$value['name']] = $getAlias->rsgd_id;
                        } elseif ($value['name'] == 'oldalias') {
                           // nothing here...
                        } else {
                            $itemArray[$value['name']] = $row[$value['name']];
                        }
                    }
                    $settingData = $itemArray;
                    $where = [ 'id' => $getAlias->rsgd_id ];
                    $wpdb->update( RSGD_TBL, $settingData, $where );
                } else {
                    unset( $row['oldalias'] );
                    $wpdb->insert( RSGD_TBL, $row );
                }
            }
        }
        die();
        
    }

    public function rsgd_import_export()
    {

    	if ( ( isset( $_POST['rsgd_nonce_fields'] ) || ! wp_verify_nonce( $_POST['rsgd_nonce_fields'], 'rsgd_nonce_fields' ) ) && !current_user_can( 'edit_others_posts' ) ) {
		    return;
	    }

        if ( isset($_POST['export']) ) {
            $this->rsgd_export_data();
        }
        
        $do = isset( $_GET['do'] ) ? $_GET['do'] : '';


	    // We are only allowing images
	    $allowedMimes = [
		    'json' => 'application/json'
	    ];

	    $fileInfo = wp_check_filetype(basename($_FILES['importfile']['name']), $allowedMimes);



	    if ( isset( $_POST['import'] ) ) {
		    if( $_FILES['importfile']['type'] != 'application/json' ){
			    $error = new WP_Error( '001', 'No user information was retrieved.', 'Some information' );
			    wp_send_json_error( $error );
		    } else {
			    $do = 'import';
		    }

        }
        
        if ( $do == 'import' ) {
            $this->rsgd_import_file();
        }
        
        require_once( RSGD_DIR . 'includes/admin/views/import_export.php' );
        
    }
    
}

new raysgrid_Tables();
