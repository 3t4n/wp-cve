<?php

use  Categorify\Helper ;
class Categorify_Sidebar
{
    public function __construct()
    {
        add_filter( 'restrict_manage_posts', array( $this, 'categorifyRestrictManagePosts' ) );
        add_filter(
            'posts_clauses',
            array( $this, 'categorifyPostsClauses' ),
            10,
            2
        );
        add_action( 'admin_enqueue_scripts', array( $this, 'categorifyEnqueueStyles' ) );
        // load style files
        add_action( 'admin_enqueue_scripts', array( $this, 'categorifyEnqueueScripts' ) );
        // load js files
        add_action( 'init', array( $this, 'categorifyAddFolderToAttachments' ) );
        // register CATEGORIFY taxonomy
        add_action( 'admin_footer-upload.php', array( $this, 'categorifyInitSidebar' ) );
        // get interface
        add_action( 'wp_ajax_categorifyAjaxAddCategory', array( $this, 'categorifyAjaxAddCategory' ) );
        // ajax: add new category
        add_action( 'wp_ajax_categorifyAjaxDeleteCategory', array( $this, 'categorifyAjaxDeleteCategory' ) );
        // ajax: delete existing category
        add_action( 'wp_ajax_categorifyAjaxClearCategory', array( $this, 'categorifyAjaxClearCategory' ) );
        // ajax: delete existing category
        add_action( 'wp_ajax_categorifyAjaxRenameCategory', array( $this, 'categorifyAjaxRenameCategory' ) );
        // ajax: rename existing category
        add_action( 'wp_ajax_categorifyAjaxUpdateSidebarWidth', array( $this, 'categorifyAjaxUpdateSidebarWidth' ) );
        // ajax: update sidebar width
        add_action( 'wp_ajax_categorifyAjaxMoveMultipleMedia', array( $this, 'categorifyAjaxMoveMultipleMedia' ) );
        // ajax: move multiple media
        add_action( 'wp_ajax_categorifyAjaxGetTermsByMedia', array( $this, 'categorifyAjaxGetTermsByMedia' ) );
        // ajax: get terms by media for single media
        add_action( 'wp_ajax_categorifyAjaxMoveSingleMedia', array( $this, 'categorifyAjaxMoveSingleMedia' ) );
        // ajax: move singe media
        add_action( 'wp_ajax_categorifyAjaxCheckDeletingMedia', array( $this, 'categorifyAjaxCheckDeletingMedia' ) );
        // ajax: check deleting media
        add_action( 'wp_ajax_categorifyAjaxMoveCategory', array( $this, 'categorifyAjaxMoveCategory' ) );
        // move category
        add_action( 'wp_ajax_categorifyAjaxUpdateFolderPosition', array( $this, 'categorifyAjaxUpdateFolderPosition' ) );
        // update folder position
        add_option( 'categorify_sidebar_width', 280 );
        // add option for sidebar width
        add_filter( 'pre-upload-ui', array( $this, 'categorifyPreUploadUserInterface' ) );
        // upload uploader category to "Add new"
        add_filter(
            'wp_kses_allowed_html',
            array( $this, 'categorify_allowed_html' ),
            20,
            2
        );
        add_action( 'admin_notices', [ $this, 'pro_version_notice' ] );
        //Support Elementor
        
        if ( defined( 'ELEMENTOR_VERSION' ) ) {
            add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'categorifyScripts' ] );
            add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'categorifyStyles' ] );
        }
    
    }
    
    public function categorify_allowed_html( $allowed, $context )
    {
        if ( is_array( $context ) ) {
            return $allowed;
        }
        $common_attributes = array(
            'id'     => true,
            'class'  => true,
            'style'  => true,
            'data-*' => true,
        );
        if ( $context === 'post' ) {
            $allowed = array(
                'input'  => array_merge( $common_attributes, array(
                    'type'         => array(),
                    'name'         => array(),
                    'value'        => array(),
                    'placeholder'  => array(),
                    'autocomplete' => array(),
            ) ),
                'select' => array_merge( $common_attributes, array(
                    'class' => array(),
            ) ),
                'option' => array(
                    'value' => array(),
            ),
                'img'    => array_merge( $common_attributes, array(
                    'src'    => true,
                    'alt'    => true,
                    'title'  => true,
                    'width'  => true,
                    'height' => true,
            ) ),
                'ul'     => $common_attributes,
                'li'     => $common_attributes,
                'div'    => $common_attributes,
                'a'      => $common_attributes,
                'span'   => $common_attributes,
                'svg'    => $common_attributes,
                'h3'     => $common_attributes,
                'p'     => $common_attributes,
            );
        }
        return $allowed;
    }
    
    public function pro_version_notice()
    {
        global  $pagenow ;
        if ( $pagenow == 'upload.php' ) {
            echo  '<div class="notice notice-warning is-dismissible">
					 <p>' . esc_html__( 'Categorify Premium has more handy features. You could search for categories. Resizable sidebar. It also enables the categories\' panel on the media pop-up window.', CATEGORIFY_TEXT_DOMAIN ) . ' <a href="https://frenify.com/project/categorify-media-library-categories/" target="_blank">Categorify Premium</a></p>
				 </div>' ;
        }
    }
    
    public function categorifyEnqueueStyles()
    {
        $this->categorifyStyles();
    }
    
    public function categorifyStyles()
    {
        wp_enqueue_style(
            'iaoalert',
            CATEGORIFY_ASSETS_URL . 'css/iaoalert.css',
            array(),
            CATEGORIFY_PLUGIN_NAME,
            'all'
        );
        wp_enqueue_style(
            'categorify-admin',
            CATEGORIFY_ASSETS_URL . 'css/core.css',
            array(),
            CATEGORIFY_PLUGIN_NAME,
            'all'
        );
        wp_enqueue_style(
            'categorify-front',
            CATEGORIFY_ASSETS_URL . 'css/front.css',
            array(),
            CATEGORIFY_PLUGIN_NAME,
            'all'
        );
    }
    
    public function categorifyEnqueueScripts()
    {
        $this->categorifyScripts();
    }
    
    public function categorifyScripts()
    {
        $allFilesText = esc_html__( 'All Files', CATEGORIFY_TEXT_DOMAIN );
        $uncategorizedText = esc_html__( 'Uncategorized', CATEGORIFY_TEXT_DOMAIN );
        $taxonomy = apply_filters( 'categorify_taxonomy', CATEGORIFY_TAXONOMY );
        $dropdownOptions = array(
            'taxonomy'     => $taxonomy,
            'hide_empty'   => false,
            'hierarchical' => true,
            'orderby'      => 'name',
            'show_count'   => true,
            'walker'       => new Categorify_Walker_Category_Mediagridfilter(),
            'value'        => 'id',
            'echo'         => false,
        );
        $attachmentTerms = wp_dropdown_categories( $dropdownOptions );
        $attachmentTerms = preg_replace( array( "/<select([^>]*)>/", "/<\\/select>/" ), "", $attachmentTerms );
        wp_register_script( 'inline-script-handle-header', '' );
        wp_enqueue_script( 'inline-script-handle-header' );
        wp_add_inline_script( 'inline-script-handle-header', '/* <![CDATA[ */ var categorifyFolders = [{"folderID":"all","folderName":"' . esc_html( $allFilesText ) . '"}, {"folderID":"-1","folderName":"' . esc_html( $uncategorizedText ) . '"},' . wp_kses( substr( $attachmentTerms, 2 ), 'post' ) . ']; /* ]]> */' );
        wp_enqueue_script( 'jquery-ui-draggable' );
        wp_enqueue_script( 'jquery-ui-droppable' );
        wp_register_script(
            'iaoalert',
            CATEGORIFY_ASSETS_URL . 'js/third-party-plugins/iaoalert.js',
            [ 'jquery' ],
            CATEGORIFY_PLUGIN_NAME,
            false
        );
        wp_register_script(
            'nicescroll',
            CATEGORIFY_ASSETS_URL . 'js/third-party-plugins/nicescroll.js',
            [ 'jquery' ],
            CATEGORIFY_PLUGIN_NAME,
            false
        );
        wp_register_script(
            'categorify-resizable',
            CATEGORIFY_ASSETS_URL . 'js/resizable.js',
            [ 'jquery' ],
            CATEGORIFY_PLUGIN_NAME,
            false
        );
        wp_register_script(
            'categorify-core',
            CATEGORIFY_ASSETS_URL . 'js/core.js',
            [ 'jquery' ],
            CATEGORIFY_PLUGIN_NAME,
            true
        );
        wp_register_script(
            'categorify-filter',
            CATEGORIFY_ASSETS_URL . 'js/filter.js',
            [ 'jquery' ],
            CATEGORIFY_PLUGIN_NAME,
            false
        );
        wp_register_script(
            'categorify-select-filter',
            CATEGORIFY_ASSETS_URL . '/js/select-filter.js',
            [ 'media-views' ],
            CATEGORIFY_PLUGIN_NAME,
            true
        );
        wp_register_script(
            'categorify-upload',
            CATEGORIFY_ASSETS_URL . 'js/upload.js',
            [ 'jquery' ],
            CATEGORIFY_PLUGIN_NAME,
            false
        );
        $isPremium = 0;
        wp_localize_script( 'categorify-core', 'categorifyConfig', [
            'plugin'                     => CATEGORIFY_PLUGIN_NAME,
            'pluginURL'                  => CATEGORIFY_URL,
            'nonce'                      => wp_create_nonce( 'categorify-security' ),
            'uploadURL'                  => admin_url( 'upload.php' ),
            'ajaxUrl'                    => admin_url( 'admin-ajax.php' ),
            'adminURL'                   => admin_url(),
            'moveOneFile'                => esc_html__( 'Move 1 file', CATEGORIFY_TEXT_DOMAIN ),
            'move'                       => esc_html__( 'Move', CATEGORIFY_TEXT_DOMAIN ),
            'files'                      => esc_html__( 'files', CATEGORIFY_TEXT_DOMAIN ),
            'newFolderText'              => esc_html__( 'New Category', CATEGORIFY_TEXT_DOMAIN ),
            'clearMediaText'             => esc_html__( 'Clear', CATEGORIFY_TEXT_DOMAIN ),
            'renameText'                 => esc_html__( 'Rename', CATEGORIFY_TEXT_DOMAIN ),
            'deleteText'                 => esc_html__( 'Delete', CATEGORIFY_TEXT_DOMAIN ),
            'clearText'                  => esc_html__( 'Clear Category', CATEGORIFY_TEXT_DOMAIN ),
            'cancelText'                 => esc_html__( 'Cancel', CATEGORIFY_TEXT_DOMAIN ),
            'confirmText'                => esc_html__( 'Confirm', CATEGORIFY_TEXT_DOMAIN ),
            'areYouSure'                 => esc_html__( 'Are you sure?', CATEGORIFY_TEXT_DOMAIN ),
            'willBeMovedToUncategorized' => esc_html__( 'All files inside this category gets moved to "Uncategorized" category. Your files are safe.', CATEGORIFY_TEXT_DOMAIN ),
            'hasSubFolder'               => esc_html__( 'This category contains subcategories. You should delete the subcategories first!', CATEGORIFY_TEXT_DOMAIN ),
            'finishRename'               => esc_html__( 'You are editing another category. Please, complete it first', CATEGORIFY_TEXT_DOMAIN ),
            'slugError'                  => esc_html__( 'Unfortunately, you already have a category with that name.', CATEGORIFY_TEXT_DOMAIN ),
            'enterName'                  => esc_html__( 'Please, enter a category name!', CATEGORIFY_TEXT_DOMAIN ),
            'item'                       => esc_html__( 'item', CATEGORIFY_TEXT_DOMAIN ),
            'items'                      => esc_html__( 'items', CATEGORIFY_TEXT_DOMAIN ),
            'currentFolder'              => $this->getCurrentFolder(),
            'noItemDOM'                  => $this->noItemForListMode(),
            'categorifyAllTitle'         => esc_html__( 'All Categories', CATEGORIFY_TEXT_DOMAIN ),
            'isPremium'                  => $isPremium,
        ] );
        wp_localize_script( 'categorify-filter', 'categorifyConfig2', [
            'pluginURL'   => CATEGORIFY_URL,
            'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
            'nonce'       => wp_create_nonce( 'categorify-security' ),
            'moveOneFile' => esc_html__( 'Move 1 file', CATEGORIFY_TEXT_DOMAIN ),
            'move'        => esc_html__( 'Move', CATEGORIFY_TEXT_DOMAIN ),
            'files'       => esc_html__( 'files', CATEGORIFY_TEXT_DOMAIN ),
        ] );
        wp_localize_script( 'categorify-select-filter', 'categorifyConfig', [
            'categorifyFolder'   => CATEGORIFY_TAXONOMY,
            'categorifyAllTitle' => esc_html__( 'All categories', CATEGORIFY_TEXT_DOMAIN ),
            'uploadURL'          => admin_url( 'upload.php' ),
            'assetsURL'          => CATEGORIFY_ASSETS_URL,
            'isPremium'          => $isPremium,
        ] );
        wp_localize_script( 'categorify-upload', 'categorifyConfig', [
            'nonce' => wp_create_nonce( 'categorify-security' ),
        ] );
        wp_enqueue_script( 'iaoalert' );
        wp_enqueue_script( 'nicescroll' );
        wp_enqueue_script( 'categorify-resizable' );
        wp_enqueue_script( 'categorify-core' );
        wp_enqueue_script( 'categorify-filter' );
        wp_enqueue_script( 'categorify-select-filter' );
        wp_enqueue_script( 'categorify-upload' );
    }
    
    public function noItemForListMode()
    {
        return '<tr class="no-items"><td class="colspanchange" colspan="6">' . esc_html__( 'No files found.', CATEGORIFY_TEXT_DOMAIN ) . '</td></tr>';
    }
    
    public function getCurrentFolder()
    {
        if ( isset( $_GET['cc_categorify_folder'] ) ) {
            return sanitize_text_field( $_GET['cc_categorify_folder'] );
        }
        return '';
    }
    
    public function categorifyRestrictManagePosts()
    {
        $scr = get_current_screen();
        if ( $scr->base !== 'upload' ) {
            return;
        }
        echo  '<select id="mediao-attachment-filters" class="wpmediacategory-filter attachment-filters" name="cc_categorify_folder"></select>' ;
    }
    
    public function getSidebarWidth()
    {
        $sidebarWidth = (int) get_option( 'categorify_sidebar_width', 380 );
        if ( $sidebarWidth < 250 || $sidebarWidth > 750 ) {
            $sidebarWidth = 380;
        }
        return $sidebarWidth;
    }
    
    public function categorifyInitSidebar()
    {
        $output = '';
        $helper = new Helper();
        $sidebarWidth = $this->getSidebarWidth() . 'px;';
        $output .= '<div class="cc_categorify_temporary">';
        $output .= '<div id="categorify_sidebar" class="cc_categorify_sidebar" style="width:' . $sidebarWidth . '">';
        $output .= '<div class="cc_categorify_sidebar_in" style="width:' . $sidebarWidth . '">';
        $output .= $helper->getSidebarHeader();
        $output .= $helper->getSidebarContent();
        $output .= '<input type="hidden" id="categorify_hidden_terms">';
        $output .= '</div>';
        $output .= '<div class="cc_categorify_sidebar_bg" style="width:' . $sidebarWidth . '"></div>';
        $output .= '</div>';
        $output .= $this->splitter();
        $output .= '</div>';
        echo  wp_kses( $output, 'post' ) ;
    }
    
    public function splitter()
    {
        $html = '<div class="categorify_splitter"></div>';
        return $html;
    }
    
    public function categorifyPreUploadUserInterface()
    {
        $helper = new Helper();
        $terms = $helper->categorifyTermTreeArray( CATEGORIFY_TAXONOMY, 0 );
        $otherOptions = $helper->categorifyTermTreeOption( $terms );
        $text = esc_html__( "New files go to chosen category", CATEGORIFY_TEXT_DOMAIN );
        $output = '';
        // top section
        $output .= '<p class="cc_upload_paragraph attachments-category">';
        $output .= $text;
        $output .= '</p>';
        // select section
        $output .= '<p class="cc_upload_paragraph">';
        $output .= '<select name="ccFolder" class="categorify-upload-category-filter">';
        $output .= '<option value="-1">1. ' . esc_html__( 'Uncategorized', CATEGORIFY_TEXT_DOMAIN ) . '</option>';
        $output .= $otherOptions;
        $output .= '</select>';
        $output .= '</p>';
        // echo result
        echo  wp_kses( $output, 'post' ) ;
    }

    public function checkUserRole() {
        $user_id = get_current_user_id();
        if ($user_id) {
            $user_info = get_userdata($user_id); // Get user data
            // Check if the user has the 'administrator' role
            if (in_array('administrator', $user_info->roles)) {
                return true; // User is an administrator
            } else {
                return false; // User is not an administrator
            }
        } else {
            return false; // User ID not found
        }
    }
    
    public function categorifyAjaxAddCategory()
    {

        check_ajax_referer( 'categorify-security', 'security' );

        // Check user role
        $action = $this->checkUserRole();

        // Stop execution if user is not an administrator
        if (!$action) {
            wp_die('You do not have permission to perform this action.');
        }

        $categoryName = sanitize_text_field( $_POST["categoryName"] );
        $parent = sanitize_text_field( $_POST["parent"] );
        // check category name
        $name = self::categorifyCheckMetaName( $categoryName, $parent );
        $newTerm = wp_insert_term( $name, CATEGORIFY_TAXONOMY, array(
            'name'   => $name,
            'parent' => $parent,
        ) );
        
        if ( is_wp_error( $newTerm ) ) {
            echo  'error' ;
        } else {
            add_term_meta( $newTerm["term_id"], 'folder_position', 9999 );
            $buffyArray = array(
                'termID'   => $newTerm["term_id"],
                'termName' => $name,
            );
            die( json_encode( $buffyArray ) );
        }
    
    }
    
    public function categorifyAjaxDeleteCategory()
    {
        check_ajax_referer( 'categorify-security', 'security' );

        // Check user role
        $action = $this->checkUserRole();

        // Stop execution if user is not an administrator
        if (!$action) {
            wp_die('You do not have permission to perform this action.');
        }

        $categoryID = sanitize_text_field( $_POST["categoryID"] );
        $selectedTerm = get_term( $categoryID, CATEGORIFY_TAXONOMY );
        $count = ( $selectedTerm->count ? $selectedTerm->count : 0 );
        $deleteTerm = wp_delete_term( $categoryID, CATEGORIFY_TAXONOMY );
        
        if ( is_wp_error( $deleteTerm ) ) {
            $error = 'yes';
        } else {
            $error = 'no';
        }
        
        $buffyArray = array(
            'error' => $error,
            'count' => $count,
        );
        die( json_encode( $buffyArray ) );
    }
    
    public function categorifyAjaxClearCategory()
    {
        global  $wpdb ;
        check_ajax_referer( 'categorify-security', 'security' );

        // Check user role
        $action = $this->checkUserRole();

        // Stop execution if user is not an administrator
        if (!$action) {
            wp_die('You do not have permission to perform this action.');
        }
        $categoryID = sanitize_text_field( $_POST["categoryID"] );
        $selectedTerm = get_term( $categoryID, CATEGORIFY_TAXONOMY );
        $count = ( $selectedTerm->count ? $selectedTerm->count : 0 );
        $wpdb->query( $wpdb->prepare(
            "UPDATE {$wpdb->prefix}term_taxonomy SET count=%d WHERE term_id=%d AND taxonomy=%s",
            0,
            $categoryID,
            CATEGORIFY_TAXONOMY
        ) );
        $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}term_relationships WHERE term_taxonomy_id=%d", $categoryID ) );
        $buffyArray = array(
            'error' => 'no',
            'count' => $count,
        );
        die( json_encode( $buffyArray ) );
    }
    
    public function categorifyAjaxRenameCategory()
    {
        check_ajax_referer( 'categorify-security', 'security' );

        // Check user role
        $action = $this->checkUserRole();

        // Stop execution if user is not an administrator
        if (!$action) {
            wp_die('You do not have permission to perform this action.');
        }
        $categoryID = sanitize_text_field( $_POST["categoryID"] );
        $categoryTitle = sanitize_text_field( $_POST["categoryTitle"] );
        $newSlug = $this->categorifySlugGenerator( $categoryTitle, $categoryID );
        $renameCategory = wp_update_term( $categoryID, CATEGORIFY_TAXONOMY, array(
            'name' => $categoryTitle,
            'slug' => $newSlug,
        ) );
        
        if ( is_wp_error( $renameCategory ) ) {
            $error = 'yes';
        } else {
            $error = 'no';
        }
        
        $buffyArray = array(
            'error' => $error,
            'title' => $categoryTitle,
        );
        die( json_encode( $buffyArray ) );
    }
    
    public function categorifyAjaxUpdateSidebarWidth()
    {
        $width = sanitize_text_field( $_POST['width'] );
        $error = 'yes';
        if ( update_option( 'categorify_sidebar_width', $width ) ) {
            $error = 'no';
        }
        $buffyArray = array(
            'error' => $error,
        );
        die( json_encode( $buffyArray ) );
    }
    
    public function recursive_sanitize_text_field( $array_or_string )
    {
        
        if ( is_string( $array_or_string ) ) {
            $array_or_string = sanitize_text_field( $array_or_string );
        } elseif ( is_array( $array_or_string ) ) {
            foreach ( $array_or_string as $key => &$value ) {
                
                if ( is_array( $value ) ) {
                    $value = recursive_sanitize_text_field( $value );
                } else {
                    $value = sanitize_text_field( $value );
                }
            
            }
        }
        
        return $array_or_string;
    }
    
    public function categorifyAjaxMoveMultipleMedia()
    {

        check_ajax_referer( 'categorify-security', 'security' );

        // Check user role
        $action = $this->checkUserRole();

        // Stop execution if user is not an administrator
        if (!$action) {
            wp_die('You do not have permission to perform this action.');
        }

        $IDs = $this->recursive_sanitize_text_field( $_POST['IDs'] );
        $folderID = sanitize_text_field( $_POST['folderID'] );
        $result = array();
        foreach ( $IDs as $ID ) {
            $termList = wp_get_post_terms( sanitize_text_field( $ID ), CATEGORIFY_TAXONOMY, array(
                'fields' => 'ids',
            ) );
            $from = -1;
            if ( count( $termList ) ) {
                $from = $termList[0];
            }
            $obj = (object) array(
                'id'   => $ID,
                'from' => $from,
                'to'   => $folderID,
            );
            $result[] = $obj;
            wp_set_object_terms(
                $ID,
                intval( $folderID ),
                CATEGORIFY_TAXONOMY,
                false
            );
        }
        $buffyArray = array(
            'result' => $result,
        );
        die( json_encode( $buffyArray ) );
    }
    
    public function categorifyAjaxGetTermsByMedia()
    {
        check_ajax_referer( 'categorify-security', 'security' );

        // Check user role
        $action = $this->checkUserRole();

        // Stop execution if user is not an administrator
        if (!$action) {
            wp_die('You do not have permission to perform this action.');
        }

        $error = 'no';
        $nonce = sanitize_text_field( $_POST['security'] );
        $terms = array();
        if ( !wp_verify_nonce( $nonce, 'categorify-security' ) ) {
            $error = 'yes';
        }
        
        if ( !isset( $_POST['ID'] ) ) {
            $error = 'yes';
        } else {
            $ID = (int) sanitize_text_field( $_POST['ID'] );
            $terms = get_the_terms( $ID, CATEGORIFY_TAXONOMY );
        }
        
        $buffyArray = array(
            'terms' => $terms,
            'error' => $error,
            'id'    => $ID,
        );
        die( json_encode( $buffyArray ) );
    }
    
    public function categorifyAjaxMoveSingleMedia()
    {

        check_ajax_referer( 'categorify-security', 'security' );

        // Check user role
        $action = $this->checkUserRole();

        // Stop execution if user is not an administrator
        if (!$action) {
            wp_die('You do not have permission to perform this action.');
        }

        $error = 'no';
        
        if ( !isset( $_POST['mediaID'] ) ) {
            $error = 'yes';
        } else {
            $mediaID = absint( sanitize_text_field( $_POST['mediaID'] ) );
            
            if ( empty($_POST['attachments']) || empty($_POST['attachments'][$mediaID]) ) {
                $error = 'yes';
            } else {
                $attachment_data = $_POST['attachments'][$mediaID];
                $post = get_post( $mediaID, ARRAY_A );
                
                if ( 'attachment' != $post['post_type'] ) {
                    $error = 'yes';
                } else {
                    $post = apply_filters( 'attachment_fields_to_save', $post, $attachment_data );
                    
                    if ( isset( $post['errors'] ) ) {
                        $errors = $post['errors'];
                        unset( $post['errors'] );
                    }
                    
                    wp_update_post( $post );
                    wp_set_object_terms(
                        $mediaID,
                        intval( sanitize_text_field( $_POST['folderID'] ) ),
                        CATEGORIFY_TAXONOMY,
                        false
                    );
                    if ( !($attachment = wp_prepare_attachment_for_js( $mediaID )) ) {
                        $error = 'yes';
                    }
                }
            
            }
        
        }
        
        $buffyArray = array(
            'attachment' => $attachment,
            'error'      => $error,
        );
        die( json_encode( $buffyArray ) );
    }
    
    public function categorifySlugGenerator( $categoryName, $ID )
    {
        global  $wpdb ;
        $categoryName = strtolower( $categoryName );
        $newSlug = preg_replace( '/[^A-Za-z0-9-]+/', '-', $categoryName );
        $count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}terms WHERE slug='" . $newSlug . "' AND term_id<>" . $ID );
        
        if ( $count > 0 ) {
            $newSlug = $newSlug . '1';
            $newSlug = $this->categorifySlugGenerator( $newSlug, $ID );
        }
        
        return $newSlug;
    }
    
    public function categorifyAjaxUpdateFolderPosition()
    {
        check_ajax_referer( 'categorify-security', 'security' );

        // Check user role
        $action = $this->checkUserRole();

        // Stop execution if user is not an administrator
        if (!$action) {
            wp_die('You do not have permission to perform this action.');
        }
        $results = sanitize_text_field( $_POST["data"] );
        $results = explode( '#', $results );
        foreach ( $results as $result ) {
            $result = explode( ',', $result );
            update_term_meta( $result[0], 'folder_position', $result[1] );
        }
        die;
    }
    
    public function categorifyAjaxMoveCategory()
    {
        
        check_ajax_referer( 'categorify-security', 'security' );

        // Check user role
        $action = $this->checkUserRole();

        // Stop execution if user is not an administrator
        if (!$action) {
            wp_die('You do not have permission to perform this action.');
        }

        $current = sanitize_text_field( $_POST["current"] );
        $parent = sanitize_text_field( $_POST["parent"] );
        $checkError = wp_update_term( $current, CATEGORIFY_TAXONOMY, array(
            'parent' => $parent,
        ) );
        
        if ( is_wp_error( $checkError ) ) {
            $error = 'yes';
        } else {
            $error = 'no';
        }
        
        $buffyArray = array(
            'error' => $error,
        );
        die( json_encode( $buffyArray ) );
    }
    
    public static function categorifyCheckMetaName( $name, $parent )
    {
        if ( !$parent ) {
            $parent = 0;
        }
        $terms = get_terms( CATEGORIFY_TAXONOMY, array(
            'parent'     => $parent,
            'hide_empty' => false,
        ) );
        $check = true;
        
        if ( count( $terms ) ) {
            foreach ( $terms as $term ) {
                
                if ( $term->name === $name ) {
                    $check = false;
                    break;
                }
            
            }
        } else {
            return $name;
        }
        
        if ( $check ) {
            return $name;
        }
        $arr = explode( '_', $name );
        
        if ( $arr && count( $arr ) > 1 ) {
            $suffix = array_values( array_slice( $arr, -1 ) )[0];
            array_pop( $arr );
            $originName = implode( $arr );
            if ( intval( $suffix ) ) {
                $name = $originName . '_' . (intval( $suffix ) + 1);
            }
        } else {
            $name = $name . '_1';
        }
        
        $name = self::categorifyCheckMetaName( $name, $parent );
        return $name;
    }
    
    public function categorifyAddFolderToAttachments()
    {
        register_taxonomy( CATEGORIFY_TAXONOMY, array( "attachment" ), array(
            "hierarchical"          => true,
            "labels"                => array(),
            'show_ui'               => true,
            'show_in_menu'          => false,
            'show_in_nav_menus'     => false,
            'show_in_quick_edit'    => false,
            'update_count_callback' => '_update_generic_term_count',
            'show_admin_column'     => false,
            "rewrite"               => false,
        ) );
    }
    
    public function categorifyPostsClauses( $clauses, $query )
    {
        global  $wpdb ;
        $folderIDs = array();
        
        if ( isset( $_GET['cc_categorify_folder'] ) ) {
            $folder = sanitize_text_field( $_GET['cc_categorify_folder'] );
            
            if ( !empty($folder) != '' ) {
                $folder = (int) $folder;
                $wpdbPrefix = $wpdb->prefix;
                
                if ( $folder > 0 ) {
                    $clauses['where'] .= ' AND (' . $wpdbPrefix . 'term_relationships.term_taxonomy_id = ' . $folder . ')';
                    $clauses['join'] .= ' LEFT JOIN ' . $wpdbPrefix . 'term_relationships ON (' . $wpdbPrefix . 'posts.ID = ' . $wpdbPrefix . 'term_relationships.object_id)';
                } else {
                    $folders = get_terms( CATEGORIFY_TAXONOMY, array(
                        'hide_empty' => false,
                    ) );
                    if ( !empty($folders) ) {
                        foreach ( $folders as $k => $folder ) {
                            $folderIDs[] = $folder->term_id;
                        }
                    }
                    
                    if ( !empty(array_filter( $folderIDs )) ) {
                        $folderIDs = esc_sql( implode( ', ', array_filter( $folderIDs ) ) );
                        $extraQuery = "SELECT `ID` FROM " . $wpdbPrefix . "posts LEFT JOIN " . $wpdbPrefix . "term_relationships ON (" . $wpdbPrefix . "posts.ID = " . $wpdbPrefix . "term_relationships.object_id) WHERE (" . $wpdbPrefix . "term_relationships.term_taxonomy_id IN (" . $folderIDs . "))";
                        $clauses['where'] .= " AND (" . $wpdbPrefix . "posts.ID NOT IN (" . $extraQuery . ")) ";
                    }
                
                }
            
            }
        
        }
        
        return $clauses;
    }
    
    public function categorifyAjaxCheckDeletingMedia()
    {

        check_ajax_referer( 'categorify-security', 'security' );

        // Check user role
        $action = $this->checkUserRole();

        // Stop execution if user is not an administrator
        if (!$action) {
            wp_die('You do not have permission to perform this action.');
        }

        $attachmentID = '';
        $error = 'no';
        $terms = array();
        $ajaxNonce = sanitize_text_field( $_POST['security'] );
        if ( !wp_verify_nonce( $ajaxNonce, 'categorify-security' ) ) {
            $error = 'yes';
        }
        if ( !isset( $_POST['attachmentID'] ) ) {
            $error = 'yes';
        }
        
        if ( $error == 'no' ) {
            $attachmentID = absint( sanitize_text_field( $_POST['attachmentID'] ) );
            $terms = get_the_terms( $attachmentID, CATEGORIFY_TAXONOMY );
        }
        
        $buffyArray = array(
            'error' => $error,
            'terms' => $terms,
        );
        die( json_encode( $buffyArray ) );
    }

}
new Categorify_Sidebar();
// Custom Category Walker
class Categorify_Walker_Category_Mediagridfilter extends \Walker_CategoryDropdown
{
    function start_el(
        &$output,
        $category,
        $depth = 0,
        $args = array(),
        $id = 0
    )
    {
        $space = str_repeat( '&nbsp;', $depth * 3 );
        
        if ( isset( $category->name ) ) {
            $folderName = $category->name;
            $folderID = $category->term_id;
            $folderName = apply_filters( 'list_cats', $folderName, $category );
            $output .= ',{"folderID":"' . $folderID . '",';
            $output .= '"folderName":"' . $space . $folderName . '"}';
        }
    
    }

}