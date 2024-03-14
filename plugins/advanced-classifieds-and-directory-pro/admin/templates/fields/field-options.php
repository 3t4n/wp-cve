<?php

/**
 * Metabox: Display Options.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp">
  <table id="acadp-field-display-options" class="acadp-form-table form-table widefat">
    <tbody>
      <tr class="acadp-form-group-associate">
        <th scope="row">
          <label for="acadp-form-control-associate">
            <?php esc_html_e( 'Assigned to', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>
        </th>
        <td>       
          <select name="associate" id="acadp-form-control-type_search" class="acadp-form-control acadp-form-select widefat">
            <?php
            $selected = isset( $post_meta['associate'] ) ? $post_meta['associate'][0] : 'categories';

            $options = array(
              'form'       => sprintf(
                '%s (%s)',
                __( 'Form', 'advanced-classifieds-and-directory-pro' ),
                __( 'All Categories', 'advanced-classifieds-and-directory-pro' )
              ),
              'categories' => sprintf(
                '%s (%s)',
                __( 'Categories', 'advanced-classifieds-and-directory-pro' ),
                __( 'Selective', 'advanced-classifieds-and-directory-pro' )
              ),
            );           
        
            foreach ( $options as $key => $value ) {
              printf( 
                '<option value="%s"%s>%s</option>', 
                $key, 
                selected( $selected, $key, false ), 
                esc_html( $value )
              );
            }
            ?>
          </select>
        </td>
      </tr>
      <tr class="acadp-form-group-listings_archive">
        <th scope="row">
          <label for="acadp-form-control-listings_archive">
            <?php esc_html_e( 'Show this field data in the listings archive pages?', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>
        </th>
        <td>
          <?php $listings_archive = isset( $post_meta['listings_archive'] ) ? $post_meta['listings_archive'][0] : 0; ?>
          
          <ul id="acadp-form-control-listings_archive" class="acadp-flex acadp-gap-2 acadp-items-center acadp-m-0 acadp-p-0 acadp-list-none">
            <li class="acadp-m-0 acadp-p-0 acadp-list-none">
              <label class="acadp-flex acadp-gap-1.5 acadp-items-center">
                <input type="radio" name="listings_archive" class="acadp-form-control acadp-form-radio" value="1" <?php echo checked( $listings_archive, 1, false ); ?>><?php esc_html_e( 'Yes', 'advanced-classifieds-and-directory-pro' ); ?>
              </label>
            </li>
            <li class="acadp-m-0 acadp-p-0 acadp-list-none">
              <label class="acadp-flex acadp-gap-1.5 acadp-items-center">
                <input type="radio" name="listings_archive" class="acadp-form-control acadp-form-radio" value="0" <?php echo checked( $listings_archive, 0, false ); ?>><?php esc_html_e( 'No', 'advanced-classifieds-and-directory-pro' ); ?>
              </label>
            </li>
          </ul>
        </td>
      </tr>
      <tr class="acadp-form-group-searchable">
        <th scope="row">
          <label for="acadp-form-control-searchable">
            <?php esc_html_e( 'Include this field in the search form?', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>
        </th>
        <td>
          <?php $searchable = isset( $post_meta['searchable'] ) ? $post_meta['searchable'][0] : 0; ?>
          
          <ul id="acadp-form-control-searchable" class="acadp-flex acadp-gap-2 acadp-items-center acadp-m-0 acadp-p-0 acadp-list-none">
            <li class="acadp-m-0 acadp-p-0 acadp-list-none">
              <label class="acadp-flex acadp-gap-1.5 acadp-items-center">
                <input type="radio" name="searchable" class="acadp-form-control acadp-form-radio" value="1" <?php echo checked( $searchable, 1, false ); ?>><?php esc_html_e( 'Yes', 'advanced-classifieds-and-directory-pro' ); ?>
              </label>
            </li>
            <li class="acadp-m-0 acadp-p-0 acadp-list-none">
              <label class="acadp-flex acadp-gap-1.5 acadp-items-center">
                <input type="radio" name="searchable" class="acadp-form-control acadp-form-radio" value="0" <?php echo checked( $searchable, 0, false ); ?>><?php esc_html_e( 'No', 'advanced-classifieds-and-directory-pro' ); ?>
              </label>
            </li>
          </ul>
        </td>
      </tr>
      <tr class="acadp-form-group-type_search acadp-conditional-fields acadp-field-type-date acadp-field-type-datetime" hidden>
        <th scope="row">
          <label for="acadp-form-control-type_search">
            <?php esc_html_e( 'Field Type (Search Form)', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>

          <p class="description">
            <?php esc_html_e( 'Set a different field type in the search form', 'advanced-classifieds-and-directory-pro' ); ?>
          </p>
        </th>
        <td>
          <select name="type_search" id="acadp-form-control-type_search" class="acadp-form-control acadp-form-select widefat">
            <?php
            $selected = isset( $post_meta['type_search'] ) ? $post_meta['type_search'][0] : 'date';

            $options = array(
              'date'      => __( 'Date Picker', 'advanced-classifieds-and-directory-pro' ),
              'daterange' => __( 'Date Range', 'advanced-classifieds-and-directory-pro' )
            );           
        
            foreach ( $options as $key => $value ) {
              printf( 
                '<option value="%s"%s>%s</option>', 
                $key, 
                selected( $selected, $key, false ), 
                esc_html( $value )
              );
            }
            ?>
          </select>
        </td>
      </tr>      
      <tr class="acadp-form-group-order">
        <th scope="row">
          <label for="acadp-form-control-order">
            <?php esc_html_e( 'Order No.', 'advanced-classifieds-and-directory-pro' ); ?>
          </label>

          <p class="description">
            <?php esc_html_e( 'Fields are created in order from lowest to highest', 'advanced-classifieds-and-directory-pro' ); ?>
          </p>
        </th>
        <td>
          <input type="text" name="order" id="acadp-form-control-order" class="acadp-form-control acadp-form-input widefat" placeholder="0" value="<?php if ( isset( $post_meta['order'] ) ) echo esc_attr( $post_meta['order'][0] ); ?>" />
        </td>
      </tr>
    </tbody>
  </table>
</div>