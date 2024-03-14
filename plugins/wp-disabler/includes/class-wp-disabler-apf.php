<?php 
// Extend the class
class APF_Tabs extends AdminPageFramework {
    /**
     * The set-up method which is triggered automatically with the 'wp_loaded' hook.
     *
     * Here we define the setup() method to set how many pages, page titles and icons etc.
     */
    public function setUp() {
        // Create the root menu
        $this->setRootMenuPage(
            'Wp Disabler',    // specify the name of the page group
            'dashicons-shield'   // menu icon
        );   
                           
        // Add the sub menu item
        $this->addSubMenuItems(   
            array(
                'title'         => 'Wp Disaber',        // page title
                'page_slug'     => 'wp_disabler',    // page slug
                'screen_icon'   => ''     // page screen icon for WP 3.7.x or below
            ),
            array(
                'title'         => 'Style',        // page title
                'page_slug'     => 'style',    // page slug
                'screen_icon'   => ''     // page screen icon for WP 3.7.x or below
            )         
        );  
        
        // Add in-page tabs
        $this->addInPageTabs(
            'wp_disabler',    // set the target page slug so that the 'page_slug' key can be omitted from the next continuing in-page tab arrays.
            array(
                'tab_slug'  =>    'event',    // avoid hyphen(dash), dots, and white spaces
                'title'     =>    __( 'Event Activity', 'wp-disabler' ),
            ),        
            array(
                'tab_slug'  =>    'admin',
                'title'     =>    __( 'Admin Section', 'wp-disabler' ),
            )
        );    
 
        $this->setPageHeadingTabsVisibility( false );    // disables the page heading tabs by passing false.
        $this->setInPageTabTag( 'h2' );        // sets the tag used for in-page tabs
    }

    /**
     * One of the pre-defined methods which is triggered when the registered page loads.
     *
     * Here we add form fields.
     * @callback        action      load_{page slug}
     */  
    public function load_wp_disabler_event( $oAdminPage ) {
        $this->addSettingFields(
            array(    // Single text field
                'field_id'      => 'right_click_disable',
                'type'          => 'checkbox',
                'title'         => __('Disable Right Click', 'wp-disabler'),
                'description'   => 'Check the field and disable to copy',
                'default'       => 'checked',  
            ),
            array(    // Text Area
                'field_id'      => 'right_click_disable_message',
                'type'          => 'textarea',
                'title'         => __('Right Click Disable Message', 'wp-disabler'),
                'description'   => 'Type a text string here.',
                'default'       => 'Oops!! Right Click is Disabled.',
            ),
            array(    // Single text field
                'field_id'      => 'control_c_disable',
                'type'          => 'checkbox',
                'title'         => __('Disable Cntl + C', 'wp-disabler'),
                'description'   => 'Check the field and disable to copy',
                'default'       => 'checked',
            ),
            array(    // Text Area
                'field_id'      => 'control_c_disable_message',
                'type'          => 'textarea',
                'title'         => __('Cntl + C Disable Message', 'wp-disabler'),
                'description'   => 'Type a text string here.',
                'default'       => 'Oops!! Copy is Disabled.',

            ),
            array(    // Single text field
                'field_id'      => 'inspect_element_disable',
                'type'          => 'checkbox',
                'title'         => __('Disable Inspect Element', 'wp-disabler'),
                'description'   => 'Check the field and disable to Inspect Element',
                'default'       => 'checked',
            ),
            array(    // Text Area
                'field_id'      => 'inspect_element_disable_message',
                'type'          => 'textarea',
                'title'         => __('Inspect Element Disable Message', 'wp-disabler'),
                'description'   => 'Type a text string here.',
                'default'       => 'Oops!! Inspect Element is Disabled.',
            ),
            array(    // Single text field
                'field_id'      => 'view_source_code_disable',
                'type'          => 'checkbox',
                'title'         => __('Disable View Source Code', 'wp-disabler'),
                'description'   => 'Check the field and disable to View Source Code',
                'default'       => 'checked',
            ),
            array(    // Single text field
                'field_id'      => 'selection_disable',
                'type'          => 'checkbox',
                'title'         => __('Disable Selection', 'wp-disabler'),
                'description'   => 'Check the field and disable selection to your content',
                'default'       => 'checked',
            ),
            array(    // Text Area
                'field_id'      => 'selection_disable_message',
                'type'          => 'textarea',
                'title'         => __('Selection Disable Message', 'wp-disabler'),
                'description'   => 'Type a text string here.',
                'default'       => 'Oops!! Selection is Disabled.',
            ),
            array( // Submit button
                'field_id'      => 'submit_button',
                'type'          => 'submit',
            )
        );
    }

    /**
     * One of the predefined callback method.
     * 
     * @remark      content_{page slug}_{tab slug}
     */    
    public function load_wp_disabler_admin( $oAdminPage ) {
        $this->addSettingFields(
            array(    // Single text field
                'field_id'      => 'disable_adminbar',
                'type'          => 'checkbox',
                'title'         => __('Disable Admin Bar', 'wp-disabler'),
                'description'   => 'Check the field and disable admin bar',
            ),
            array(    // Single text field
                'field_id'      => 'disable_feed',
                'type'          => 'checkbox',
                'title'         => __('Disable RSS Feeds', 'wp-disabler'),
                'description'   => 'Check the field and disable All RSS Feeds',
            ),
            array(    // Single text field
                'field_id'      => 'disable_ctp_update',
                'type'          => 'checkbox',
                'title'         => __('Disable Core, Theme, Plugin Update Notifications', 'wp-disabler'),
                'description'   => 'Check the field and disable Core, Theme, Plugin Update Notification',
                'default'       => false,
            ),
            array(    // Single text field
                'field_id'      => 'disable_rest_api',
                'type'          => 'checkbox',
                'title'         => __('Disable Rest API', 'wp-disabler'),
                'description'   => 'Check the field and disable site rest api',
                'default'       => false,
            ),
            array( // Submit button
                'field_id'      => 'submit_button',
                'type'          => 'submit',
            )
        );
    }
    

    public function load_style( $oAdminPage ) {
        $this->addSettingFields(
            array(
                'field_id'      => 'message_backgound_color',
                'title'         => __('Message Backgound color', 'wp-disabler'),
                'type'          => 'color',
                'default'       =>  '#dd3333', 
            ),
            array(
                'field_id'      => 'toast_vertical_positon',
                'title'         => __('Message Vertical Position', 'wp-disabler'),
                'type'          => 'radio',
                'label'         => array(
                    't' => 'Top',
                    'c' => 'Center',
                    'b' => 'Bottom'
                ),
                'default'       =>  'c', 
            ),
            array(
                'field_id'      => 'toast_horizontal_positon',
                'title'         => __('Message Horizontal Position', 'wp-disabler'),
                'type'          => 'radio',
                'label'         => array(
                    'l' => 'Left',
                    'c' => 'Center',
                    'r' => 'Right'
                ),
                'default'       =>  'c', 
            ),
            array( // Submit button
                'field_id'      => 'submit_button',
                'type'          => 'submit',
            )
        );
    }
    
}
// Instantiate the class object.
new APF_Tabs;