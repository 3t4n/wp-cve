<?php

class ContactListPrintable {

  public function register_printable_page() {
    add_submenu_page(
      'edit.php?post_type=' . esc_attr( CONTACT_LIST_CPT ),
      sanitize_text_field( __('Printable list', 'contact-list') ),
      sanitize_text_field( __('Printable list', 'contact-list') ),
      'manage_options',
      'contact-list-printable',
      [ $this, 'register_printable_page_callback' ]
    );
  }

  public function register_printable_page_callback() {
    ?>
    
    <div class="wrap">

      <h1 class="cl-dont-print"><?php echo esc_html__('Printable list', 'contact-list') ?></h1>

      <p class="cl-dont-print">
        <?php echo esc_html__('When you print this page, only the list of contacts is printed.', 'contact-list') ?>
      </p>

      <p class="cl-dont-print">
        <form method="get" class="cl-dont-print">
          <input type="hidden" name="post_type" value="contact" />
          <input type="hidden" name="page" value="contact-list-printable" />
          <input type="hidden" name="search" value="1" />

          <?php

          $groups = get_terms(array(
            'taxonomy'   => 'contact-group',
            'hide_empty' => true,
          ));

          ?>

          <div>
            <h3><?php echo esc_html__('Select category:', 'contact-list') ?></h3>
            <select name="cl_cat" class="<?php echo esc_attr( ContactListHelpers::getSearchDropdownClass() ) ?>">
              <option value=""><?php echo esc_html__('All contacts', 'contact-list') ?></option>
      
              <?php
              foreach ($groups as $g) {
                
                $t_id = intval( $g->term_id );
                $custom_fields = get_option("taxonomy_term_$t_id");
                
                echo '<option value="' . esc_attr( $g->slug ) . '" ' . (isset($_GET['cl_cat']) && $_GET['cl_cat'] == $g->slug ? 'selected="selected"' : '') . '>' . esc_html( $g->name ) . '</option>';
  
              }
              ?>
            </select>
            <hr class="clear" />
          </div>

          <div>
            <h3><?php echo esc_html__('Select view:', 'contact-list') ?></h3>
            <select name="cl_view" class="<?php echo esc_attr( ContactListHelpers::getSearchDropdownClass() ) ?>">
              <option value=""><?php echo esc_attr__('Default list', 'contact-list') ?></option>
              <option value="simple_list" <?php echo (isset($_GET['cl_view']) && $_GET['cl_view'] == 'simple_list') ? 'selected' : '' ?>><?php echo esc_html__('Simple list', 'contact-list') ?></option>
            </select>
            <hr class="clear" />
          </div>
          
          <div>
            <h3><?php echo esc_html__('Change card height:', 'contact-list') ?></h3>
            <input name="card_height" value="<?php echo isset($_GET['card_height']) ? esc_attr( $_GET['card_height'] ) : 340 ?>" style="width: 60px; text-align: right;" /> px
          </div>
          
          <div style="background: #fff; margin-top: 20px; padding: 10px 20px;">
            <input type="submit" class="contact-list-admin-button" style="font-size: 15px; padding: 5px 10px; font-weight: 600; border: 1px solid #bbb;" value="<?php echo esc_attr__('Show contacts', 'contact-list') ?>" style="margin-left: 6px;" />

            <?php if (isset($_GET['search'])): ?>

              <span class="cl-dont-print">
                <button onclick="window.print();" class="contact-list-admin-button" style="font-size: 15px; padding: 5px 20px; font-weight: 600; background: mediumseagreen; border: 1px solid mediumseagreen; color: white; text-shadow: 1px 1px 0 #000;" ><?php echo esc_html__('Print contacts', 'contact-list') ?></button>
              </span>

            <?php endif; ?>

          </div>

        </form>
      </p>

      <hr class="style-one cl-dont-print" />

      <?php
        
        if (isset($_GET['search'])) {

          $html = '';
          
          $html .= '<div class="contact-list-container contact-list-2-cards-on-the-same-row">';

          $meta_query = array('relation' => 'AND');

          $meta_query []= array(
            'last_name_clause' => array(
              'key'              => '_cl_last_name',
              'compare'          => 'EXISTS',
            )
          );

          $order_by = array(
            'menu_order'        => 'ASC',
            'last_name_clause'  => 'ASC',
            'title'             => 'ASC',
          );
            
          if ( ( CONTACT_LIST_ORDER_BY != '_cl_last_name' && !isset($atts['order_by']) ) ) {
          
            $meta_query []= array(
              'custom_clause' => array(
                'key'     => CONTACT_LIST_ORDER_BY,
                'compare' => 'EXISTS',
              )
            );
          
            $order_by = array(
              'menu_order'        => 'ASC',
              'custom_clause'     => 'ASC',
              'title'             => 'ASC',
            );
          
          }


          $tax_query = [];
      
          if (isset($_GET['cl_cat']) && $_GET['cl_cat']) {
              $tax_query = array(
                  'relation' => 'AND',
                  array(
              			'taxonomy' => 'contact-group',
              			'field'	   => 'slug',
              			'terms'		 => sanitize_title( $_GET['cl_cat'] )
              		)
          		);
          }

          $wp_query = new WP_Query(array(
            'post_type'       => 'contact',
            'post_status'     => 'publish',
            'posts_per_page'  => -1,

            'meta_query'      => $meta_query,          
            'tax_query'       => $tax_query,          
            'orderby'         => $order_by

          ));

          if ($wp_query->have_posts()):

            if (isset($_GET['cl_view']) && $_GET['cl_view'] == 'simple_list'):

              $html .= '<div class="contact-list-simple-ajax-results">';
              $html .= ContactListPublicHelpersSimple::contactListSimpleMarkup($wp_query);
              $html .= '</div>';
              
            else:

              $html .= '<div class="contact-list-ajax-results">';
              $html .= ContactListPublicHelpersDefault::contactListMarkup($wp_query);
              $html .= '</div>';
              
            endif;
  
          endif;

          $html .= '</div>';
          
          echo $html;
          
        } else {
          
          echo '<span class="contact-list-printable-list-info">' . esc_html__('First click on the Show contacts -button to get the contacts.', 'contact-list') . '</span>';
          
        }
      ?>

    </div>
    <?php
  }

}
