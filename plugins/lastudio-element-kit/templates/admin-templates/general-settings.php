<div class="lastudio-kit-settings-page lastudio-kit-settings-page__general">
  <div class="cx-vui-title cx-vui-title--divider"><?php _e( 'Theme Layout', 'lastudio-kit' ); ?></div>
  <div class="cx-vui-panel">
    <cx-vui-select
      name="single_page_template"
      label="<?php esc_html_e( 'Default Single Page Template', 'lastudio-kit' ); ?>"
      :wrapper-css="[ 'equalwidth' ]"
      size="fullwidth"
      :options-list="pageOptions.single_page_template.options"
      v-model="pageOptions.single_page_template.value">
    </cx-vui-select>
    <cx-vui-select
      name="single_post_template"
      label="<?php esc_html_e( 'Default Single Post Template', 'lastudio-kit' ); ?>"
      :wrapper-css="[ 'equalwidth' ]"
      size="fullwidth"
      :options-list="pageOptions.single_post_template.options"
      v-model="pageOptions.single_post_template.value">
    </cx-vui-select>
  </div>
    <?php do_action( 'lastudio-kit-dashboard/js-page-templates/general-settings/after-theme-layout' ); ?>
  <div class="cx-vui-title cx-vui-title--divider"><?php _e( 'Taxonomy to show in breadcrumbs for content types', 'lastudio-kit' ); ?></div>
  <div class="cx-vui-panel">
    <?php
    $post_types    = get_post_types( array( 'public' => true ), 'objects' );
    $deny_list     = array( 'elementor_library', 'lakit-theme-core', 'e-landing-page' );
    $tax_deny_list = array( 'product_shipping_class' );

    if ( is_array( $post_types ) && ! empty( $post_types ) ) {

      foreach ( $post_types as $post_type ) {
        if ( in_array( $post_type->name, $deny_list ) ) {
          continue;
        }
        $taxonomies = get_object_taxonomies( $post_type->name, 'objects' );

        if ( is_array( $taxonomies ) && ! empty( $taxonomies ) ) {
          $post_type_name = 'breadcrumbs_taxonomy_' . $post_type->name;
          ?>
        <cx-vui-select
          name="<?php echo $post_type_name; ?>"
          label="<?php echo $post_type->label; ?>"
          :wrapper-css="[ 'equalwidth' ]"
          size="fullwidth"
          :options-list="pageOptions['<?php echo $post_type_name; ?>']['options']"
          v-model="pageOptions['<?php echo $post_type_name; ?>']['value']"
        ></cx-vui-select><?php
        }
      }
    }
    ?>
  </div
      <?php do_action( 'lastudio-kit-dashboard/js-page-templates/general-settings/after-breadcrumbs' ); ?>>
  <div id="posts_per_page" class="cx-vui-title cx-vui-title--divider" v-html="'<?php _e( 'Posts per page', 'lastudio-kit' ); ?>'"></div>
  <div class="cx-vui-panel">
    <cx-vui-input
      v-for="( perpage, index ) in pageOptions.posts_per_page_manager.options"
      type="number"
      :name="`postspaer-page-${perpage.name}`"
      :description="perpage.desc"
      :label="perpage.label"
      :wrapper-css="[ 'equalwidth', 'padding-xs', 'align-items-center' ]"
      :size="'default'"
      v-model="pageOptions.posts_per_page_manager.value[perpage.name]"
    ></cx-vui-input>
  </div>
    <?php do_action( 'lastudio-kit-dashboard/js-page-templates/general-settings/after-perpage' ); ?>
  <div class="cx-vui-title cx-vui-title--divider"><?php _e( 'Extra Code', 'lastudio-kit' ); ?></div>
  <div class="cx-vui-panel">
    <cx-vui-textarea
      name="custom_css"
      label="<?php _e( 'Custom CSS', 'lastudio-kit' ); ?>"
      description="<?php echo esc_html__( 'Paste your custom CSS code here.', 'lastudio-kit' ); ?>"
      size="fullwidth"
      :wrapper-css="[ 'lakit-vertical-fullwidth' ]"
      :value="pageOptions.custom_css.value"
      :rows="8"
      @on-input-change="updateSetting( $event.target.value, 'custom_css' )"
    ></cx-vui-textarea>
    <cx-vui-textarea
      name="head_code"
      label="<?php _e( 'Custom Head', 'lastudio-kit' ); ?>"
      description="<?php echo esc_html__( 'Paste your html code here. The code will be added to the <head> of your site.', 'lastudio-kit' ); ?>"
      size="fullwidth"
      :wrapper-css="[ 'lakit-vertical-fullwidth' ]"
      :value="pageOptions.head_code.value"
      :rows="8"
      @on-input-change="updateSetting( $event.target.value, 'head_code' )"
    ></cx-vui-textarea>
    <cx-vui-textarea
      name="footer_code"
      label="<?php _e( 'Custom Footer Code', 'lastudio-kit' ); ?>"
      description="<?php echo esc_html__( 'Paste your HTML code here. The code will be added to the footer of your site.', 'lastudio-kit' ); ?>"
      size="fullwidth"
      :wrapper-css="[ 'lakit-vertical-fullwidth' ]"
      :value="pageOptions.footer_code.value"
      :rows="8"
      @on-input-change="updateSetting( $event.target.value, 'footer_code' )"
    ></cx-vui-textarea>
  </div>
    <?php do_action( 'lastudio-kit-dashboard/js-page-templates/general-settings/after-custom-code' ); ?>
  <?php do_action( 'lastudio-kit-dashboard/js-page-templates/general-settings' ); ?>
</div>
