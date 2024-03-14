<?php

class ContactListShortcodes
{
    public function register_shortcodes_page()
    {
        add_submenu_page(
            'edit.php?post_type=' . esc_attr( CONTACT_LIST_CPT ),
            sanitize_text_field( __( 'Available shortcodes for Contact List', 'contact-list' ) ),
            sanitize_text_field( __( 'Shortcodes', 'contact-list' ) ),
            'manage_options',
            'contact-list-shortcodes',
            [ $this, 'register_shortcodes_page_callback' ]
        );
    }
    
    public function register_shortcodes_page_callback()
    {
        ?>

    <?php 
        $num = 1000;
        ?>

    <?php 
        $plan_info_markup_all_plans = '<a href="' . esc_url_raw( get_admin_url() ) . 'options-general.php?page=contact-list-pricing" class="contact-list-pro-only-inline">' . sanitize_text_field( __( 'Paid plans', 'contact-list' ) ) . '</a>';
        $plan_info_markup_professional = '<a href="' . esc_url_raw( get_admin_url() ) . 'options-general.php?page=contact-list-pricing" class="contact-list-pro-only-inline">Professional</a>';
        $plan_info_markup_allowed_tags = array(
            'a' => array(
            'href'  => array(),
            'class' => array(),
        ),
        );
        ?>

    <div class="wrap">

      <h1><?php 
        echo  esc_html__( 'Available shortcodes for Contact List', 'contact-list' ) ;
        ?></h1>

      <div class="contact-list-examples">

        <?php 
        
        if ( contact_list_fs()->can_use_premium_code() ) {
            ?>

          <p><?php 
            echo  esc_html__( 'For support using the plugin please contact us at', 'contact-list' ) ;
            ?> <a href="https://www.contactlistpro.com/support/?utm_source=plugin-shortcodes" target="_blank">contactlistpro.com/support/</a>.</p>
          
        <?php 
        } else {
            ?>

          <p>
          <?php 
            $url = 'https://wordpress.org/support/plugin/contact-list/';
            echo  sprintf( wp_kses(
                /* translators: %s: link to the support forum */
                __( 'For support using the plugin please contact us at <a href="%s" target="_blank">the support forum</a>.', 'contact-list' ),
                array(
                    'a' => array(
                    'href'   => array(),
                    'target' => array(),
                ),
                )
            ), esc_url( $url ) ) ;
            ?>
          </p>
        
        <?php 
        }
        
        ?>

        <div class="contact-list-all-paid-plans">
        
          <?php 
        
        if ( ContactListHelpers::isPremium() == 1 ) {
            ?>
        
            <span class="contact-list-pro-only-inline-inactive" style="margin-left: 0; margin-right: 1px;"><?php 
            echo  esc_html__( 'Paid plans', 'contact-list' ) ;
            ?></span>
        
            <?php 
            echo  esc_html__( 'means that the shortcode / parameter exists in all paid plans.', 'contact-list' ) ;
            ?>
        
          <?php 
        } else {
            ?>
        
            <a href="<?php 
            echo  esc_url( get_admin_url() ) ;
            ?>options-general.php?page=contact-list-pricing" class="contact-list-pro-only-inline" style="margin-left: 0; margin-right: 1px;"><?php 
            echo  esc_html__( 'Paid plans', 'contact-list' ) ;
            ?></a>
        
            <?php 
            echo  wp_kses( __( 'means that the shortcode / parameter exists in all <strong>paid</strong> plans.', 'contact-list' ), array(
                'strong' => array(),
            ) ) ;
            ?>
        
          <?php 
        }
        
        ?>
        
        </div>

      </div>

      <div class="contact-list-admin-section contact-list-admin-section-shortcodes">

        <h2><?php 
        echo  esc_html__( 'Default contact list', 'contact-list' ) ;
        ?></h2>
  
        <ol>
          <li><?php 
        echo  esc_html__( 'Insert the shortcode', 'contact-list' ) ;
        ?>
            <span class="contact-list-shortcode contact-list-shortcode-1" data-tooltip-class="contact-list-shortcode-1">[contact_list]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-1"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
            <?php 
        echo  esc_html__( 'to the content editor of any page you wish the contact list to appear.', 'contact-list' ) ;
        ?>
          </li>
          <li><?php 
        echo  esc_html__( 'Additional parameters', 'contact-list' ) ;
        ?>
              <ul>
                  <li><?php 
        echo  esc_html__( 'Hide search form:', 'contact-list' ) ;
        ?>
                    <span class="contact-list-shortcode contact-list-shortcode-2">[contact_list hide_search=1]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-2"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
                  </li>
                  <li><?php 
        echo  esc_html__( 'Order by first name:', 'contact-list' ) ;
        ?>
                    <span class="contact-list-shortcode contact-list-shortcode-14">[contact_list order_by=first_name]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-14"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
                  </li>
                  <li><?php 
        echo  esc_html__( 'Order by last name:', 'contact-list' ) ;
        ?>
                    <span class="contact-list-shortcode contact-list-shortcode-15">[contact_list order_by=last_name]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-15"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
                  </li>
                  <li><?php 
        echo  esc_html__( 'Layout "2 columns"', 'contact-list' ) ;
        ?>:
                    <span class="contact-list-shortcode contact-list-shortcode-3">[contact_list layout=2-columns]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-3"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
                  </li>
                  <li><?php 
        echo  esc_html__( 'Layout "3 columns"', 'contact-list' ) ;
        ?> (<?php 
        echo  esc_html__( 'without contact images', 'contact-list' ) ;
        ?>):
                    <span class="contact-list-shortcode contact-list-shortcode-4">[contact_list layout=3-columns]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-4"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
                  </li>
                  <li><?php 
        echo  esc_html__( 'Layout "4 columns"', 'contact-list' ) ;
        ?> (<?php 
        echo  esc_html__( 'without contact images', 'contact-list' ) ;
        ?>):
                    <span class="contact-list-shortcode contact-list-shortcode-5">[contact_list layout=4-columns]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-5"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
                  </li>

                  <li>
                  
                    <?php 
        echo  esc_html__( 'Hide contacts first (contacts are shown when searched or any filter is selected):', 'contact-list' ) ;
        ?>
                  
                    <?php 
        echo  wp_kses( $plan_info_markup_all_plans, $plan_info_markup_allowed_tags ) ;
        ?>
                  
                    <span class="contact-list-shortcode contact-list-shortcode-5-1222">[contact_list hide_contacts_first=1]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-5-1222"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
                  
                  </li>

                  <li>
                  
                    <?php 
        echo  esc_html__( 'Hide contacts first and show only a single default contact:', 'contact-list' ) ;
        ?> 
                    
                    <?php 
        echo  wp_kses( $plan_info_markup_all_plans, $plan_info_markup_allowed_tags ) ;
        ?>
                  
                    <span class="contact-list-shortcode contact-list-shortcode-5-1333">[contact_list hide_contacts_first=1 default_contact_id=12345]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-5-1333"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
                  
                  </li>

                  <li>
                  
                    <?php 
        echo  esc_html__( 'Make the search require a button click, for input field search only:', 'contact-list' ) ;
        ?> <span class="cl-new-feature-inline"> <?php 
        echo  esc_html__( 'New', 'contact-list' ) ;
        ?></span>
                  
                    <?php 
        echo  wp_kses( $plan_info_markup_all_plans, $plan_info_markup_allowed_tags ) ;
        ?>
                  
                    <span class="contact-list-shortcode contact-list-shortcode-5-1333-155-1">[contact_list hide_filters=1 disable_instant_search=1 search_button_text="Search"]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-5-1333-155-1"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
                  
                  </li>

                  <li>
                  
                    <?php 
        echo  esc_html__( 'Make the search require a button click, for search filters only:', 'contact-list' ) ;
        ?> <span class="cl-new-feature-inline"> <?php 
        echo  esc_html__( 'New', 'contact-list' ) ;
        ?></span>
                  
                    <?php 
        echo  wp_kses( $plan_info_markup_all_plans, $plan_info_markup_allowed_tags ) ;
        ?>
                  
                    <span class="contact-list-shortcode contact-list-shortcode-5-1333-155-2">[contact_list hide_search=1 disable_instant_search=1 search_button_text="Search"]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-5-1333-155-2"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
                  
                  </li>

                  <li>
                
                    <?php 
        echo  esc_html__( 'Exclude groups (from search filter and actual contacts):', 'contact-list' ) ;
        ?>
    
                    <?php 
        echo  wp_kses( $plan_info_markup_all_plans, $plan_info_markup_allowed_tags ) ;
        ?>
    
                    <span class="contact-list-shortcode contact-list-shortcode-5-1">[contact_list exclude_groups="group-slug-1,group-slug-2,group-slug-3"]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-5-1"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
    
                  </li>

                  <?php 
        
        if ( contact_list_fs()->is_free_plan() || contact_list_fs()->is_plan_or_trial( 'pro' ) || contact_list_fs()->is_plan_or_trial( 'business' ) ) {
            ?>

                    <li><?php 
            echo  esc_html__( 'Shows contacts in the defined group, and the group filter contains only that group and it\'s subgroups', 'contact-list' ) ;
            ?>:
                    
                      <?php 
            echo  wp_kses( $plan_info_markup_professional, $plan_info_markup_allowed_tags ) ;
            ?>
                      
                      <span class="contact-list-shortcode contact-list-shortcode-000">[contact_list group=GROUP_SLUG]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-000"><?php 
            echo  esc_html__( 'Copy', 'contact-list' ) ;
            ?></button>
                    </li>

                    <li><?php 
            echo  esc_html__( 'Filter by custom field values, any number of fields', 'contact-list' ) ;
            ?>: 
                    
                      <?php 
            echo  wp_kses( $plan_info_markup_professional, $plan_info_markup_allowed_tags ) ;
            ?>
                    
                      <span class="contact-list-shortcode contact-list-shortcode-001">[contact_list custom_field_1="Value 1" custom_field_2="Value 2"]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-001"><?php 
            echo  esc_html__( 'Copy', 'contact-list' ) ;
            ?></button>
                    </li>
                    
                  <?php 
        }
        
        ?>
                    
                  <li><?php 
        echo  esc_html__( 'Multiple parameters:', 'contact-list' ) ;
        ?>
                    <span class="contact-list-shortcode contact-list-shortcode-6">[contact_list layout=2-columns hide_search=1]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-6"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
                  </li>
              </ul>
          </li>
        </ol>
      
      </div>

      <div class="contact-list-admin-section contact-list-admin-section-shortcodes">

        <h2><?php 
        echo  esc_html__( 'Simple contact list', 'contact-list' ) ;
        ?></h2>
  
        <ol>
          <li>
            
            <?php 
        echo  esc_html__( 'Insert the shortcode', 'contact-list' ) ;
        ?>

            <span class="contact-list-shortcode contact-list-shortcode-7">[contact_list_simple]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-7"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
            <?php 
        echo  esc_html__( 'to the content editor of any page you wish the contact list to appear.', 'contact-list' ) ;
        ?>

            <ul>
              <li>
                
                <?php 
        echo  esc_html__( 'Show filters:', 'contact-list' ) ;
        ?>

                <?php 
        echo  wp_kses( $plan_info_markup_all_plans, $plan_info_markup_allowed_tags ) ;
        ?>

                <span class="contact-list-shortcode contact-list-shortcode-18">[contact_list_simple show_filters=1]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-18"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>

              </li>
              <li>
                
                <?php 
        echo  esc_html__( 'Use ajax query for input field search (beta):', 'contact-list' ) ;
        ?>
              
                <?php 
        echo  wp_kses( $plan_info_markup_all_plans, $plan_info_markup_allowed_tags ) ;
        ?>
              
                <span class="contact-list-shortcode contact-list-shortcode-188888">[contact_list_simple ajax=1]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-188888"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
              
              </li>
              <li>
                
                <?php 
        echo  esc_html__( 'Show contacts from a specific group:', 'contact-list' ) ;
        ?>

                <?php 
        echo  wp_kses( $plan_info_markup_all_plans, $plan_info_markup_allowed_tags ) ;
        ?>

                <span class="contact-list-shortcode contact-list-shortcode-99">[contact_list_simple group=GROUP_SLUG]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-99"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>

              </li>
              <li>
                
                <?php 
        echo  esc_html__( 'Limit contacts per page (activates pagination):', 'contact-list' ) ;
        ?>

                <?php 
        echo  wp_kses( $plan_info_markup_all_plans, $plan_info_markup_allowed_tags ) ;
        ?>

                <span class="contact-list-shortcode contact-list-shortcode-199">[contact_list_simple contacts_per_page=20]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-199"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>

              </li>
              
              <li>
              
                <?php 
        echo  esc_html__( 'Exclude groups:', 'contact-list' ) ;
        ?>
              
                <?php 
        echo  wp_kses( $plan_info_markup_all_plans, $plan_info_markup_allowed_tags ) ;
        ?>
              
                <span class="contact-list-shortcode contact-list-shortcode-5-1-123">[contact_list_simple exclude_groups="group-slug-1,group-slug-2,group-slug-3"]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-5-1-123"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
              
              </li>


              <?php 
        
        if ( contact_list_fs()->is_free_plan() || contact_list_fs()->is_plan_or_trial( 'pro' ) || contact_list_fs()->is_plan_or_trial( 'business' ) ) {
            ?>

                <li><?php 
            echo  esc_html__( 'Filter by custom field values, any number of fields', 'contact-list' ) ;
            ?>: 

                  <?php 
            echo  wp_kses( $plan_info_markup_professional, $plan_info_markup_allowed_tags ) ;
            ?>

                  <span class="contact-list-shortcode contact-list-shortcode-002">[contact_list_simple custom_field_1="Value 1" custom_field_2="Value 2"]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-002"><?php 
            echo  esc_html__( 'Copy', 'contact-list' ) ;
            ?></button>
                </li>

                <li><?php 
            echo  esc_html__( 'Show these fields (check the fields from plugin settings, Simple list tab)', 'contact-list' ) ;
            ?>:
                
                  <?php 
            echo  wp_kses( $plan_info_markup_professional, $plan_info_markup_allowed_tags ) ;
            ?>
                
                  <span class="contact-list-shortcode contact-list-shortcode-0002">[contact_list_simple fields="full_name phone city category"]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-0002"><?php 
            echo  esc_html__( 'Copy', 'contact-list' ) ;
            ?></button>
                </li>
                
              <?php 
        }
        
        ?>

            </ul>
          </li>
        </ol>
        
      </div>

      <?php 
        
        if ( contact_list_fs()->is_free_plan() || contact_list_fs()->is_plan_or_trial( 'pro' ) || contact_list_fs()->is_plan_or_trial( 'business' ) ) {
            ?>

        <div class="contact-list-admin-section contact-list-admin-section-shortcodes">      
                      
          <h2>
            <?php 
            echo  esc_html__( 'Enable front-end editor for all contacts' ) ;
            ?>
        
            <?php 
            echo  wp_kses( $plan_info_markup_professional, $plan_info_markup_allowed_tags ) ;
            ?>
        
          </h2>
        
          <p><?php 
            echo  esc_html__( 'Enable front-end edit using the following shortcodes (user roles must also be activated from the plugin settings):', 'contact-list' ) ;
            ?></p>
        
          <ul>  
           <li>
              <span class="contact-list-shortcode contact-list-shortcode-003" data-tooltip-class="contact-list-shortcode-003">[contact_list edit=1]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-003"><?php 
            echo  esc_html__( 'Copy', 'contact-list' ) ;
            ?></button></span>
           </li>
           <li>
              <span class="contact-list-shortcode contact-list-shortcode-004" data-tooltip-class="contact-list-shortcode-004">[contact_list_simple edit=1]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-004"><?php 
            echo  esc_html__( 'Copy', 'contact-list' ) ;
            ?></button></span>
            </li>
          </ul>
        </div>
        
      <?php 
        }
        
        ?>

      <div class="contact-list-admin-section contact-list-admin-section-shortcodes">

        <h2>

          <?php 
        echo  esc_html__( 'Parameters for all shortcodes', 'contact-list' ) ;
        ?>

          <?php 
        echo  wp_kses( $plan_info_markup_all_plans, $plan_info_markup_allowed_tags ) ;
        ?>

        </h2>
  
        <ol>
          <li>
            <?php 
        echo  esc_html__( 'Card height in pixels', 'contact-list' ) ;
        ?>
            <ul>
              <li>card_height=200</li>
            </ul>
          </li>
          <li>
            <?php 
        echo  esc_html__( 'Exclude certain contacts (comma separated list of contact id\'s)', 'contact-list' ) ;
        ?>
            <ul>
              <li>exclude="123,456,789"</li>
            </ul>
          </li>
        </ol>
        
      </div>

      <div class="contact-list-admin-section contact-list-admin-section-shortcodes">
      
        <h2>

          <?php 
        echo  esc_html__( 'Contacts with groups', 'contact-list' ) ;
        ?>

          <?php 
        echo  wp_kses( $plan_info_markup_all_plans, $plan_info_markup_allowed_tags ) ;
        ?>

        </h2>

        <ol>
          <li>
            
            <?php 
        echo  esc_html__( 'Insert the shortcode', 'contact-list' ) ;
        ?>
            
            <span class="contact-list-shortcode contact-list-shortcode-8">[contact_list_groups]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-8"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>        

            <?php 
        echo  esc_html__( 'to the content editor of any page you wish the group list to appear. When a user selects a group, then a list of contacts belonging to that group is displayed. Also, if there are subgroups under that group, those will be displayed.', 'contact-list' ) ;
        ?>
          
          </li>
          <li>
            
            <?php 
        echo  esc_html__( 'Additional parameters', 'contact-list' ) ;
        ?>
              
            <ul>
                <li><?php 
        echo  esc_html__( 'Order by first name:', 'contact-list' ) ;
        ?>
                  <span class="contact-list-shortcode contact-list-shortcode-16">[contact_list_groups order_by=first_name]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-16"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
                </li>
                <li><?php 
        echo  esc_html__( 'Order by last name:', 'contact-list' ) ;
        ?>
                  <span class="contact-list-shortcode contact-list-shortcode-17">[contact_list_groups order_by=last_name]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-17"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
                </li>
                <li><?php 
        echo  esc_html__( 'Hide breadcrumbs:', 'contact-list' ) ;
        ?>
                  <span class="contact-list-shortcode contact-list-shortcode-188">[contact_list_groups hide_breadcrumbs=1]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-188"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
                </li>
                <li><?php 
        echo  esc_html__( 'Hide group title:', 'contact-list' ) ;
        ?>
                  <span class="contact-list-shortcode contact-list-shortcode-1888">[contact_list_groups group="sample-group" hide_group_title=1]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-1888"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
                </li>

            </ul>

          </li>
        </ol>

      </div>

      <div class="contact-list-admin-section contact-list-admin-section-shortcodes">

        <h2>

          <?php 
        echo  esc_html__( 'Contacts from specific group', 'contact-list' ) ;
        ?>

          <?php 
        echo  wp_kses( $plan_info_markup_all_plans, $plan_info_markup_allowed_tags ) ;
        ?>

        </h2>
        <ol>
          <li><?php 
        echo  esc_html__( 'Insert the shortcode', 'contact-list' ) ;
        ?>
            <span class="contact-list-shortcode contact-list-shortcode-9">[contact_list_groups group=GROUP_SLUG]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-9"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>        
            <?php 
        echo  esc_html__( 'to the content editor of any page you wish the contact list to appear. Replace GROUP_SLUG with the appropriate slug that can be found from group management.', 'contact-list' ) ;
        ?>
          </li>
          <li><?php 
        echo  esc_html__( 'Additional parameters', 'contact-list' ) ;
        ?>
            <ul>
              <li><?php 
        echo  esc_html__( 'Show filters', 'contact-list' ) ;
        ?>: <span class="contact-list-shortcode contact-list-shortcode-13">[contact_list_groups group=GROUP_SLUG show_filters=1]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-13"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button></li>
              <li><?php 
        echo  esc_html__( 'Show contacts that belong to all of these groups (comma separated group slugs)', 'contact-list' ) ;
        ?>: <span class="contact-list-shortcode contact-list-shortcode-139">[contact_list_groups groups__and="group-slug-1,group-slug-2"]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-139"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button></li>
              <li><?php 
        echo  esc_html__( 'Show contacts that belong to any of these groups (comma separated group slugs)', 'contact-list' ) ;
        ?>:
               <span class="contact-list-shortcode contact-list-shortcode-139">[contact_list_groups groups__or="group-slug-1,group-slug-2"]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-139"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button></li>
            </ul>
          </li>
        </ol>

      </div>

      <div class="contact-list-admin-section contact-list-admin-section-shortcodes">

        <h2>

          <?php 
        echo  esc_html__( 'Single contact', 'contact-list' ) ;
        ?>

        </h2>
        <ol>
          <li><?php 
        echo  esc_html__( 'Insert the shortcode', 'contact-list' ) ;
        ?>
            <span class="contact-list-shortcode contact-list-shortcode-10">[contact_list contact=CONTACT_ID]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-10"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
            <?php 
        echo  esc_html__( 'to the content editor of any page you wish the contact to appear. Replace CONTACT_ID with the appropriate id that can be found from contact management. There\'s a column "ID" in the All Contacts -page, which contains the numeric value.', 'contact-list' ) ;
        ?></li>
        </ol>
        
      </div>

      <div class="contact-list-admin-section contact-list-admin-section-shortcodes">

        <h2>

          <?php 
        echo  esc_html__( 'Allow visitors to add new contacts', 'contact-list' ) ;
        ?>

          <?php 
        echo  wp_kses( $plan_info_markup_all_plans, $plan_info_markup_allowed_tags ) ;
        ?>

        </h2>
        <ol>
          <li><?php 
        echo  esc_html__( 'Insert the shortcode', 'contact-list' ) ;
        ?> 
            <span class="contact-list-shortcode contact-list-shortcode-11">[contact_list_form]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-11"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
            <?php 
        echo  esc_html__( 'to the page you wish the form to appear on.', 'contact-list' ) ;
        ?></li>
          <li><?php 
        echo  esc_html__( 'When a user submits the form, a new contact is saved to the contacts. The status of that contact is "Pending Review" and a site administrator must publish/edit/delete the contact.', 'contact-list' ) ;
        ?></li>
        </ol>
        
      </div>

      <div class="contact-list-admin-section contact-list-admin-section-shortcodes">

        <h2>

          <?php 
        echo  esc_html__( 'Only a search form that searches from all contacts', 'contact-list' ) ;
        ?>

          <?php 
        echo  wp_kses( $plan_info_markup_all_plans, $plan_info_markup_allowed_tags ) ;
        ?>

        </h2>
        <ol>

          <li><?php 
        echo  esc_html__( 'Insert the shortcode', 'contact-list' ) ;
        ?> 
            <span class="contact-list-shortcode contact-list-shortcode-12">[contact_list_search]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-12"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
            <?php 
        echo  esc_html__( 'to the page you wish the view to appear on.', 'contact-list' ) ;
        ?></li>

          <li><?php 
        echo  esc_html__( 'Additional parameters', 'contact-list' ) ;
        ?>
            <ul>
              <li><?php 
        echo  esc_html__( 'Limit contacts to a specific group', 'contact-list' ) ;
        ?>: <span class="contact-list-shortcode contact-list-shortcode-13324">[contact_list_search group=GROUP_SLUG]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-13324"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button></li>
            </ul>
          </li>

        </ol>
        
      </div>
      
      <div class="contact-list-admin-section contact-list-admin-section-shortcodes">
      
        <h2>

          <?php 
        echo  esc_html__( 'Send email to a group', 'contact-list' ) ;
        ?>

          <?php 
        echo  wp_kses( $plan_info_markup_all_plans, $plan_info_markup_allowed_tags ) ;
        ?>

        </h2>
        
        <?php 
        $num++;
        ?>
        <ol>
          <li><?php 
        echo  esc_html__( 'Insert the shortcode', 'contact-list' ) ;
        ?> 
            <span class="contact-list-shortcode contact-list-shortcode-<?php 
        echo  $num ;
        ?>">[contact_list_send_email group=GROUP_SLUG]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-<?php 
        echo  $num ;
        ?>"><?php 
        echo  esc_html__( 'Copy', 'contact-list' ) ;
        ?></button>
            <?php 
        echo  esc_html__( 'to the page you wish the view to appear on.', 'contact-list' ) ;
        ?></li>
        </ol>
        
      </div>

    </div>
    <?php 
    }

}