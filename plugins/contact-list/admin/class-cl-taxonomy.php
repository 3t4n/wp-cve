<?php

class ContactListTaxonomy {

  public function create_contact_list_custom_taxonomy() {

    $labels = array(
      'name'              => sanitize_text_field( __('Group', 'contact-list') ),
      'singular_name'     => sanitize_text_field( __('Group', 'contact-list') ),
      'search_items'      => sanitize_text_field( __('Search Groups', 'contact-list') ),
      'all_items'         => sanitize_text_field( __('All Groups', 'contact-list') ),
      'parent_item'       => sanitize_text_field( __('Parent Group', 'contact-list') ),
      'parent_item_colon' => sanitize_text_field( __('Parent Group:', 'contact-list') ),
      'edit_item'         => sanitize_text_field( __('Edit Group', 'contact-list') ),
      'update_item'       => sanitize_text_field( __('Update Group', 'contact-list') ),
      'add_new_item'      => sanitize_text_field( __('Add New Group', 'contact-list') ),
      'menu_name'         => sanitize_text_field( __('Groups', 'contact-list') ),
      'not_found'         => sanitize_text_field( __('No groups found.', 'contact-list') ),
    );

    register_taxonomy('contact-group', array(CONTACT_LIST_CPT), array(
      'hierarchical'      => true,
      'labels'            => $labels,
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
      'public'            => false,
      'rewrite'           => array('slug' => 'groups'),
    ));
  }

  function contact_group_taxonomy_custom_fields($tag) {  

    $t_id = intval( $tag->term_id );
    $term_meta = get_option("taxonomy_term_$t_id");
    ?>  
    
    <tr class="form-field">  

      <th scope="row" valign="top">  

        <label for="term_meta[hide_group]"><?php echo esc_html__('Hide group', 'contact-list'); ?></label>  
        <div style="font-weight: 400; font-style: italic; font-size: 12px; margin-top: 6px;"><?php echo esc_html__('Note: this hides only the group from the front-end views (such as dropdowns and group lists), not the actual contacts that may belong to this group.', 'contact-list') ?></div>

      </th>  

      <td>  

        <input type="checkbox" name="term_meta[hide_group]" id="term_meta[hide_group]" <?php echo isset($term_meta['hide_group']) ? 'checked="checked"' : ''; ?>>

      </td>  

    </tr>  
    
  <?php  
  }  

  function save_taxonomy_custom_fields($term_id) {  

      $t_id = intval( $term_id );  
      $term_meta = get_option("taxonomy_term_$t_id");  

      if (isset($_POST['term_meta'])) {  
        
        $cat_keys = array_keys($_POST['term_meta']);  

        foreach ($cat_keys as $key) {

          if (isset($_POST['term_meta'][$key])) {

            $term_meta[$key] = sanitize_text_field( $_POST['term_meta'][$key] );

          }

        }

      } else {
        $term_meta = array();
      }

      update_option("taxonomy_term_$t_id", $term_meta);  
  }    

  public function theme_columns($theme_columns) {

    $new_columns = array(
      'cb'        => '<input type="checkbox" />',
      'name'      => sanitize_text_field( __('Name') ),
      'shortcode' => sanitize_text_field( __('Shortcode', 'contact-list') ),
      'slug'      => sanitize_text_field( __('Slug') ),
      'posts'     => sanitize_text_field( __('Posts') )
    );

    return $new_columns;
  }

  public function add_contact_group_column_content($content, $column_name, $term_id) {

    $term = get_term($term_id, 'contact-group');

    switch ($column_name) {

      case 'shortcode':

        $content = 
        
          '<div class="contact-list-shortcode-admin-list-container">' . 

          '<button class="contact-list-copy contact-list-copy-paid-only contact-list-copy-admin-list contact-list-copy-admin-list-left" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-g-' . sanitize_title( $term->slug ) . '">' . sanitize_text_field( __('Copy', 'contact-list') ) . '</button>' .

          '<span class="contact-list-shortcode-admin-list contact-list-shortcode-admin-list-right contact-list-shortcode-g-' . sanitize_title( $term->slug ) . '" title="[contact_list_groups group=' . sanitize_title( $term->slug ) . ']">[contact_list_groups group=' . sanitize_title( $term->slug ) . ']</span>' . 

          '<hr class="clear" />' . 

          '</div>' . 

          '<div class="contact-list-shortcode-admin-list-container">' . 

          '<button class="contact-list-copy contact-list-copy-paid-only contact-list-copy-admin-list contact-list-copy-admin-list-left" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-sl-' . sanitize_title( $term->slug ) . '">' . sanitize_text_field( __('Copy', 'contact-list') ) . '</button>' .

          '<span class="contact-list-shortcode-admin-list contact-list-shortcode-admin-list-right contact-list-shortcode-sl-' . sanitize_title( $term->slug ) . '" title="[contact_list_simple group=' . sanitize_title( $term->slug ) . ']">[contact_list_simple group=' . sanitize_title( $term->slug ) . ']</span>' .

          '<hr class="clear" />' . 

          '</div>';

        break;

      default:

        break;

    }

    return $content;

  }

}
