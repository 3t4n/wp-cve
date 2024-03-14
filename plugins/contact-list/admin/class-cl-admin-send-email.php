<?php

class ContactListAdminSendEmail
{
    public function register_send_email_page()
    {
        add_submenu_page(
            'edit.php?post_type=' . CONTACT_LIST_CPT,
            sanitize_text_field( __( 'Send email to contacts', 'contact-list' ) ),
            sanitize_text_field( __( 'Send email', 'contact-list' ) ),
            'manage_options',
            'contact-list-send-email',
            [ $this, 'register_send_email_page_callback' ]
        );
    }
    
    public function register_send_email_page_callback()
    {
        $term_id = ( isset( $_GET['group_id'] ) ? intval( $_GET['group_id'] ) : 0 );
        $tax_query = [];
        if ( $term_id ) {
            $tax_query = array( array(
                'taxonomy'         => 'contact-group',
                'field'            => 'term_id',
                'terms'            => $term_id,
                'include_children' => true,
            ) );
        }
        $wpb_all_query = new WP_Query( array(
            'post_type'      => CONTACT_LIST_CPT,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'tax_query'      => $tax_query,
        ) );
        $recipient_emails = [];
        if ( $wpb_all_query->have_posts() ) {
            while ( $wpb_all_query->have_posts() ) {
                $wpb_all_query->the_post();
                $c = get_post_custom();
                if ( isset( $c['_cl_email'][0] ) && is_email( $c['_cl_email'][0] ) ) {
                    $recipient_emails[] = sanitize_email( $c['_cl_email'][0] );
                }
            }
        }
        wp_reset_postdata();
        ?>
    
    <div class="wrap">

      <form method="post" class="send_email_form" action="" target="send_email">

        <?php 
        $is_premium = 0;
        ?>

        <h1><?php 
        echo  esc_html__( 'Send email to contacts', 'contact-list' ) ;
        ?></h1>

        <?php 
        
        if ( ContactListHelpers::isPremium() == 0 ) {
            ?>  
          <br />
          <?php 
            echo  ContactListHelpers::proFeatureMarkup() ;
            ?>
          <br />
        <?php 
        }
        
        ?>

        <div>
          
          <span class="restrict-recipients-title"><?php 
        echo  esc_html__( 'Restrict recipients to specific group', 'contact-list' ) ;
        ?>:</span>

          <?php 
        $taxonomies = get_terms( array(
            'taxonomy'   => 'contact-group',
            'hide_empty' => false,
        ) );
        $output = '';
        
        if ( !empty($taxonomies) ) {
            foreach ( $taxonomies as $category ) {
                
                if ( $category->parent == 0 ) {
                    $output .= '<label class="contact-category"><input type="radio" name="_cl_groups[]" value="' . intval( $category->term_id ) . '" onclick="document.location.href=\'./edit.php?post_type=' . esc_js( CONTACT_LIST_CPT ) . '&page=contact-list-send-email&group_id=\' + this.value;" ' . (( isset( $_GET['group_id'] ) && $_GET['group_id'] == $category->term_id ? 'checked' : '' )) . ' /> <span class="contact-list-checkbox-title">' . esc_html( $category->name ) . '</span></label>';
                    foreach ( $taxonomies as $subcategory ) {
                        if ( $subcategory->parent == $category->term_id ) {
                            $output .= '<label class="contact-subcategory"><input type="radio" name="_cl_groups[]" value="' . esc_attr( $subcategory->term_id ) . '" onclick="document.location.href=\'./edit.php?post_type=' . esc_js( CONTACT_LIST_CPT ) . '&page=contact-list-send-email&group_id=\' + this.value;" ' . (( isset( $_GET['group_id'] ) && $_GET['group_id'] == $subcategory->term_id ? 'checked' : '' )) . ' /> <span class="contact-list-checkbox-title">' . esc_html( $subcategory->name ) . '</span></label>';
                        }
                    }
                }
            
            }
            echo  '<div class="contact-list-restrict-to-groups">' ;
            echo  $output ;
            echo  '</div>' ;
        } else {
            echo  '<div class="contact-list-admin-no-groups-found">' ;
            echo  esc_html__( 'No groups found.', 'contact-list' ) . ' ' ;
            $url = get_admin_url() . 'edit-tags.php?taxonomy=contact-group&post_type=contact';
            $text = sprintf( wp_kses(
                /* translators: %s: link to group management */
                __( 'You may add groups from <a href="%s">group management</a>.', 'contact-list' ),
                array(
                    'a' => array(
                    'href' => array(),
                ),
                )
            ), esc_url( $url ) );
            echo  $text ;
            echo  '</div>' ;
        }
        
        ?>

        </div>

        <span class="recipients-title"><?php 
        echo  esc_html__( 'Recipients', 'contact-list' ) ;
        ?> (<?php 
        echo  esc_html__( 'total of', 'contact-list' ) ;
        ?> <?php 
        echo  sizeof( $recipient_emails ) ;
        ?> <?php 
        echo  esc_html__( 'contacts with email addresses', 'contact-list' ) ;
        ?>):</span>


        <?php 
        
        if ( sizeof( $recipient_emails ) > 0 ) {
            ?>

          <div><?php 
            echo  implode( ', ', $recipient_emails ) ;
            ?></div>
          <input name="recipient_emails" type="hidden" value="<?php 
            echo  esc_attr( implode( ',', $recipient_emails ) ) ;
            ?>" />

        <?php 
        } else {
            ?>

          <div class="contact-list-admin-no-contacts-found">

            <?php 
            echo  ContactListHelpers::getText( 'text_sr_no_contacts_found', __( 'No contacts found.', 'contact-list' ) ) ;
            ?>

            <?php 
            $url = esc_url_raw( get_admin_url() . 'edit.php?post_type=' . CONTACT_LIST_CPT );
            echo  sprintf( wp_kses(
                /* translators: %s: link to contact management */
                __( 'You may add contacts or assign them to groups from <a href="%s">contact management</a>.', 'contact-list' ),
                array(
                    'a' => array(
                    'href' => array(),
                ),
                )
            ), esc_url( $url ) ) ;
            ?>

          </div>

        <?php 
        }
        
        ?>

        <hr class="style-one" />

        <label>
          <span><?php 
        echo  esc_html__( 'Subject', 'contact-list' ) ;
        ?></span>
          <input name="subject" type="text" value="" required />
        </label>

        <?php 
        $user_id = intval( get_current_user_id() );
        ?>
        <?php 
        $user = get_userdata( $user_id );
        ?>
        
        <label>
          <span><?php 
        echo  esc_html__( 'Sender name', 'contact-list' ) ;
        ?></span>
          <input name="sender_name" type="text" value="" required />
        </label>

        <label>
          <span><?php 
        echo  esc_html__( 'Sender email', 'contact-list' ) ;
        ?></span>
          <input name="sender_email" type="email" value="" required />
        </label>
  
        <label>
          <span><?php 
        echo  esc_html__( 'Message', 'contact-list' ) ;
        ?></span>
          <textarea name="body" required></textarea>
        </label>

        <div class="send_email_target_div"></div>

        <input type="submit" value="<?php 
        echo  esc_attr__( 'Send', 'contact-list' ) ;
        ?>" <?php 
        if ( sizeof( $recipient_emails ) == 0 || ContactListHelpers::isPremium() == 0 ) {
            echo  'disabled' ;
        }
        ?> />
        <hr class="style-one" />
          
      </form>

    </div>
    
    <?php 
    }

}