<?php 
if ( ! function_exists( 'icycp_consultco_features' ) ) :
  function icycp_consultco_features() {

  $feature_title        = get_theme_mod('feature_title','Usefull Feature');
  $feature_subtitle     = get_theme_mod('feature_subtitle','Features we Provide');
  $feature_description    = get_theme_mod('feature_description','Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim idm est laborum.');
  $features_contents      = get_theme_mod('features_contents',consultco_get_features_default());
  $feature_enable_disable         = get_theme_mod('feature_enable_disable','1');
if($feature_enable_disable == '1') {  
?>
 <section id="features-section" class="bs-section features">

       <div class="overlay">
        <div class="container">
      <?php if(! empty( $feature_title ) || ! empty( $feature_subtitle ) || ! empty( $feature_description )) { ?> 
        <div class="col text-center">
          <div class="bs-heading">
               <?php if ( ! empty( $feature_title ) ) : ?>
                <h3 class="bs-subtitle"><?php echo wp_kses_post($feature_title); ?></h3>
              <?php endif; ?>
              <div class="clearfix"></div>
               <?php if ( ! empty( $feature_subtitle ) ) : ?>   
                <h2 class="bs-title"><?php echo wp_kses_post($feature_subtitle); ?></h2>    
              <?php endif; ?>                    
              <?php if ( ! empty( $feature_description ) ) : ?>   
                <p class="bs-desc"><?php echo wp_kses_post($feature_description); ?></p>    
              <?php endif; ?>
          </div>
        </div>
      <?php } ?>  
            <div class="row text-center">
        <?php
          if ( ! empty( $features_contents ) ) {
          $features_contents = json_decode( $features_contents );
          foreach ( $features_contents as $feature_item ) {
            $consultco_features_title = ! empty( $feature_item->title ) ? apply_filters( 'consultco_translate_single_string', $feature_item->title, 'feature section' ) : '';
            $text = ! empty( $feature_item->text ) ? apply_filters( 'consultco_translate_single_string', $feature_item->text, 'feature section' ) : '';
            $icon = ! empty( $feature_item->icon_value) ? apply_filters( 'consultco_translate_single_string', $feature_item->icon_value,'feature section' ) : '';
        ?>
          <div class="col-md-4">
            <div class="feature_widget center shd mb-4">
                <?php if ( ! empty( $icon ) ) {?>
                  <i class="fas <?php echo esc_html( $icon ); ?> "></i>
                <?php } ?>
              <div class="media-body">
                <?php if ( ! empty( $consultco_features_title ) ) : ?>
                  <h5 class="mt-0"><a href="javascript:void(0)"><?php echo esc_html( $consultco_features_title ); ?></a></h5>
                <?php endif; ?>
                <?php if ( ! empty( $text ) ) : ?>
                  <?php echo esc_html( $text ); ?>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php }}?>
            </div>
        </div>
        </div>
    </section>
  
<?php 
  }} endif; 
  if ( function_exists( 'icycp_consultco_features' ) ) {
    $section_priority = apply_filters( 'icycp_consultco_homepage_section_priority', 13, 'icycp_consultco_features' );
    add_action( 'icycp_consultco_homepage_sections', 'icycp_consultco_features', absint( $section_priority ) );
  }