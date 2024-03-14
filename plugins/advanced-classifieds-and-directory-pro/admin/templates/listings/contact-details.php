<?php

/**
 * Metabox: Contact Details.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp">
  <div class="acadp-flex acadp-flex-col acadp-gap-4">
    <table id="acadp-contact-details" class="acadp-form-table form-table widefat">
      <tbody>
        <tr class="acadp-form-group-address">
          <th scope="row">
            <label for="acadp-form-control-address">
              <?php esc_html_e( 'Address', 'advanced-classifieds-and-directory-pro' ); ?>
            </label>
          </th>
          <td>
            <textarea name="address" id="acadp-form-control-address" class="acadp-form-control acadp-form-control-map acadp-form-textarea widefat" rows="6"><?php 
              if ( isset( $post_meta['address'] ) ) echo esc_textarea( $post_meta['address'][0] ); 
            ?></textarea>
          </td>
        </tr>
        <tr class="acadp-form-group-acadp_location">
          <th scope="row">
            <label for="acadp-form-control-acadp_location">
              <?php esc_html_e( 'Location', 'advanced-classifieds-and-directory-pro' ); ?>
            </label>
          </th>
          <td>
            <?php
            $locations_args = array(
              'post_id'     => $post->ID,
              'placeholder' => '— ' . esc_html__( 'Select location', 'advanced-classifieds-and-directory-pro' ) . ' —',
              'taxonomy'    => 'acadp_locations',
              'parent'      => max( 0, (int) $general_settings['base_location'] ),
              'name' 	      => 'acadp_location',   
              'id'          => 'acadp-form-control-location',
              'class'       => 'acadp-form-control acadp-form-control-map widefat postform',
              'selected'    => (int) $location
            );

            $locations_args = apply_filters( 'acadp_listing_form_locations_dropdown_args', $locations_args );
            echo apply_filters( 'acadp_listing_form_locations_dropdown_html', acadp_get_terms_dropdown_html( $locations_args ), $locations_args );
            ?>
          </td>
        </tr>
        <tr class="acadp-form-group-zipcode">
          <th scope="row">
            <label for="acadp-form-control-zipcode">
              <?php esc_html_e( 'Zip Code', 'advanced-classifieds-and-directory-pro' ); ?>
            </label>
          </th>
          <td>
            <input type="text" name="zipcode" id="acadp-form-control-zipcode" class="acadp-form-control acadp-form-control-map acadp-form-input widefat" value="<?php if ( isset( $post_meta['zipcode'] ) ) echo esc_attr( $post_meta['zipcode'][0] ); ?>" />
          </td>
        </tr>
        <tr class="acadp-form-group-phone">
          <th scope="row">
            <label for="acadp-form-control-phone">
              <?php esc_html_e( 'Phone', 'advanced-classifieds-and-directory-pro' ); ?>
            </label>
          </th>
          <td>
            <input type="tel" name="phone" id="acadp-form-control-phone" class="acadp-form-control acadp-form-input widefat" value="<?php if ( isset( $post_meta['phone'] ) ) echo esc_attr( $post_meta['phone'][0] ); ?>" />
          </td>
        </tr>   
        <tr class="acadp-form-group-email">
          <th scope="row">
            <label for="acadp-form-control-email">
              <?php esc_html_e( 'Email', 'advanced-classifieds-and-directory-pro' ); ?>
            </label>
          </th>
          <td>
            <input type="email" name="email" id="acadp-form-control-email" class="acadp-form-control acadp-form-input widefat" value="<?php if ( isset( $post_meta['email'] ) ) echo esc_attr( $post_meta['email'][0] ); ?>" />
          </td>
        </tr> 
        <tr class="acadp-form-group-website">
          <th scope="row">
            <label for="acadp-form-control-website">
              <?php esc_html_e( 'Website', 'advanced-classifieds-and-directory-pro' ); ?>
            </label>
          </th>
          <td>
            <input type="url" name="website" id="acadp-form-control-website" class="acadp-form-control acadp-form-input widefat" value="<?php if ( isset( $post_meta['website'] ) ) echo esc_url( $post_meta['website'][0] ); ?>" />
          </td>
        </tr> 
      </tbody>
    </table>

    <?php if ( ! empty( $general_settings['has_map'] ) ) : ?>
      <div class="acadp-map widefat acadp-aspect-video">
        <div class="marker" data-latitude="<?php echo esc_attr( $latitude ); ?>" data-longitude="<?php echo esc_attr( $longitude ); ?>"></div>    
      </div>    
        
      <label class="acadp-flex acadp-gap-1.5 acadp-items-center">
        <input type="hidden" name="latitude" id="acadp-form-control-latitude" value="<?php echo esc_attr( $latitude ); ?>" />
        <input type="hidden" name="longitude" id="acadp-form-control-longitude" value="<?php echo esc_attr( $longitude ); ?>" />
        <input type="checkbox" name="hide_map" id="acadp-form-control-hide_map" class="acadp-form-control acadp-form-checkbox" value="1" <?php if ( isset( $post_meta['hide_map'] ) ) checked( $post_meta['hide_map'][0], 1 ); ?>>
        <?php esc_html_e( "Don't show the Map", 'advanced-classifieds-and-directory-pro' ); ?>
      </label>
    <?php endif; ?>
  </div>
</div>