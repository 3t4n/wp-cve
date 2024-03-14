<?php

class ContactListImportExport
{
    public function register_import_page()
    {
        add_submenu_page(
            'edit.php?post_type=' . esc_attr( CONTACT_LIST_CPT ),
            sanitize_text_field( __( 'Import contacts', 'contact-list' ) ),
            sanitize_text_field( __( 'Import contacts', 'contact-list' ) ),
            'manage_options',
            'contact-list-import',
            [ $this, 'register_import_page_callback' ]
        );
    }
    
    public function register_export_page()
    {
        add_submenu_page(
            'edit.php?post_type=' . esc_attr( CONTACT_LIST_CPT ),
            sanitize_text_field( __( 'Export contacts', 'contact-list' ) ),
            sanitize_text_field( __( 'Export contacts', 'contact-list' ) ),
            'manage_options',
            'contact-list-export',
            [ $this, 'register_export_page_callback' ]
        );
    }
    
    public function register_import_page_callback()
    {
        $s = get_option( 'contact_list_settings' );
        ?>
    
    <div class="wrap">

        <h1><?php 
        echo  esc_html__( 'Import contacts', 'contact-list' ) ;
        ?></h1>

        <?php 
        ?>

        <?php 
        
        if ( ContactListHelpers::isPremium() == 0 ) {
            ?>

          <p>
            <?php 
            echo  esc_html__( 'You may import contacts from csv file using this form. There should be one contact per row, columns separated by comma.', 'contact-list' ) ;
            ?>
          </p>
          
          <hr class="style-one" />

          <?php 
            echo  ContactListHelpers::proFeatureMarkup() ;
            ?>

          <hr class="style-one" />
          
          <p>
            <strong><?php 
            echo  esc_html__( 'The columns should be in this order:', 'contact-list' ) ;
            ?></strong>
            <ol>
              <li><?php 
            echo  esc_html__( 'First name', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Last name', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Job title', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Email', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Phone', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'LinkedIn URL', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'X URL', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Facebook URL', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Address line 1', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Address line 2', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Address line 3', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Address line 4', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Custom field 1', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Custom field 2', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Custom field 3', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Custom field 4', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Custom field 5', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Custom field 6', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Groups', 'contact-list' ) ;
            ?><i><br /><?php 
            echo  esc_html__( 'Group names separated by the character "|", like so: Cats|Dogs|Parrots', 'contact-list' ) ;
            ?></i></li>
              <li><?php 
            echo  esc_html__( 'Country', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'State', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'City', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'ZIP Code', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Instagram URL', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Phone 2', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Phone 3', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Prefix', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Middle name', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Suffix', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Image ID (if already exists in media library, not required)', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Filename of image (if already exists media library, can be used without the Image ID)', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Custom URL 1', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Custom URL 2', 'contact-list' ) ;
            ?></li>
            </ol>
          </p>

        <?php 
        }
        
        ?>

    </div>
    <?php 
    }
    
    public function register_export_page_callback()
    {
        ?>
    
    <div class="wrap">

        <h1><?php 
        echo  esc_html__( 'Export contacts', 'contact-list' ) ;
        ?></h1>

        <?php 
        ?>

        <?php 
        
        if ( ContactListHelpers::isPremium() == 0 ) {
            ?>

          <p>
            <?php 
            echo  esc_html__( 'You may export contacts to a csv file. There will be one contact per row, columns separated by comma.', 'contact-list' ) ;
            ?>
          </p>
          
          <hr class="style-one" />

          <?php 
            echo  ContactListHelpers::proFeatureMarkup() ;
            ?>

          <hr class="style-one" />
          
          <p>
            <strong><?php 
            echo  esc_html__( 'The columns are in this order:', 'contact-list' ) ;
            ?></strong>
            <ol>
              <li><?php 
            echo  esc_html__( 'First name', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Last name', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Job title', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Email', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Phone', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'LinkedIn URL', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'X URL', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Facebook URL', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Address line 1', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Address line 2', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Address line 3', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Address line 4', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Custom field 1', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Custom field 2', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Custom field 3', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Custom field 4', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Custom field 5', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Custom field 6', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Groups', 'contact-list' ) ;
            ?><i><br /><?php 
            echo  esc_html__( 'Group names separated by the character "|", like so: Cats|Dogs|Parrots', 'contact-list' ) ;
            ?></i></li>
              <li><?php 
            echo  esc_html__( 'Country', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'State', 'contact-list' ) ;
            ?></span></li>
              <li><?php 
            echo  esc_html__( 'City', 'contact-list' ) ;
            ?></span></li>
              <li><?php 
            echo  esc_html__( 'ZIP Code', 'contact-list' ) ;
            ?></span></li>
              <li><?php 
            echo  esc_html__( 'Instagram URL', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Phone 2', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Phone 3', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Prefix', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Middle name', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Suffix', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Image ID (from media library)', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Filename of image', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Custom URL 1', 'contact-list' ) ;
            ?></li>
              <li><?php 
            echo  esc_html__( 'Custom URL 2', 'contact-list' ) ;
            ?></li>
            </ol>
          </p>

        <?php 
        }
        
        ?>

    </div>
    <?php 
    }

}