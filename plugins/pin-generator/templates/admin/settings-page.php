<div class="wrap">
    <h1><?php esc_html_e(get_admin_page_title()); ?></h1>

    <form method="post" action="options.php">
        
     <!-- Display necessary hidden fields for settings -->
     <?php settings_fields( 'pin_generator_options' ); ?>

    <!-- Display the settings sections for the page -->
    <?php do_settings_sections( 'pin-generator-settings' ); ?>
    
    <!-- Default Submit Button -->
    <?php submit_button(); ?>
    </form>

    <?php
        // Template demo images
    $standard = PIN_GENERATOR_PLUGIN_URL . "assets/templateImages/standard.jpg";
    $portraitFriendly = PIN_GENERATOR_PLUGIN_URL . "assets/templateImages/portraitFriendly.jpg";
    $landscapeFriendly = PIN_GENERATOR_PLUGIN_URL . "assets/templateImages/landscapeFriendly.jpg";
    $fullImageWithBanner = PIN_GENERATOR_PLUGIN_URL . "assets/templateImages/fullImageWithBanner.jpg";
    $imageOnly = PIN_GENERATOR_PLUGIN_URL . "assets/templateImages/imageOnly.jpg";
    $imageOnlyWithFrame = PIN_GENERATOR_PLUGIN_URL . "assets/templateImages/imageOnlyWithFrame.jpg";
    $circleImageWithBanner = PIN_GENERATOR_PLUGIN_URL . "assets/templateImages/circleImageWithBanner.jpg";

    $centerWave = PIN_GENERATOR_PLUGIN_URL . "assets/templateImages/centerWave.jpg";
    $textBlob = PIN_GENERATOR_PLUGIN_URL . "assets/templateImages/textBlob.jpg";
    $colorBlobs = PIN_GENERATOR_PLUGIN_URL . "assets/templateImages/colorBlobs.jpg";
    $waveySplit = PIN_GENERATOR_PLUGIN_URL . "assets/templateImages/waveySplit.jpg";

    $bigTextWithLandscapeImage = PIN_GENERATOR_PLUGIN_URL . "assets/templateImages/bigTextWithLandscapeImage.jpg";
    $textOverlay = PIN_GENERATOR_PLUGIN_URL . "assets/templateImages/textOverlay.jpg";
    $textSideRight = PIN_GENERATOR_PLUGIN_URL . "assets/templateImages/textSideRight.jpg";
    $bigTopText = PIN_GENERATOR_PLUGIN_URL . "assets/templateImages/bigTopText.jpg";


    $html ='<h3>Standard templates</h3>';
    $html .='<div class="templateImages">';
    $html .='<div class="single-template-div"><img class="templateDemoImage" src="' . esc_attr($standard) . '"/><p class="pg-template-text">Standard Template</p></div>';
    $html .='<div class="single-template-div"><img class="templateDemoImage" src="' . esc_attr($portraitFriendly) . '"/><p class="pg-template-text">Portrait Friendly</p></div>';
    $html .='<div class="single-template-div"><img class="templateDemoImage" src="' . esc_attr($landscapeFriendly) . '"/><p class="pg-template-text">Landscape Friendly</p></div>';
    $html .='<div class="single-template-div"><img class="templateDemoImage" src="' . esc_attr($fullImageWithBanner) . '"/><p class="pg-template-text">Full Image With Banner</p></div>';
    $html .='<div class="single-template-div"><img class="templateDemoImage" src="' . esc_attr($imageOnly) . '"/><p class="pg-template-text">Image Only</p></div>';
    $html .='<div class="single-template-div"><img class="templateDemoImage" src="' . esc_attr($imageOnlyWithFrame) . '"/><p class="pg-template-text">Blank With Frame</p></div>';
    $html .='<div class="single-template-div"><img class="templateDemoImage" src="' . esc_attr($circleImageWithBanner) . '"/><p class="pg-template-text">Circle Image With Banner</p></div>';
    $html .='</div>';

    $html .='<h3>Rounded templates</h3>';
    $html .='<div class="templateImages">';
    $html .='<div class="single-template-div"><img class="templateDemoImage" src="' . esc_attr($centerWave) . '"/><p class="pg-template-text">Center Wave</p></div>';
    $html .='<div class="single-template-div"><img class="templateDemoImage" src="' . esc_attr($textBlob) . '"/><p class="pg-template-text">Text Blob</p></div>';
    $html .='<div class="single-template-div"><img class="templateDemoImage" src="' . esc_attr($colorBlobs) . '"/><p class="pg-template-text">Color Blobs</p></div>';
    $html .='<div class="single-template-div"><img class="templateDemoImage" src="' . esc_attr($waveySplit) . '"/><p class="pg-template-text">Wavey Split</p></div>';
    $html .='</div>';

    $html .='<h3>Square templates</h3>';
    $html .='<div class="templateImages">';
    $html .='<div class="single-template-div"><img class="templateDemoImage" src="' . esc_attr($bigTextWithLandscapeImage) . '"/><p class="pg-template-text">Square Text With Landscape Image</p></div>';
    $html .='<div class="single-template-div"><img class="templateDemoImage" src="' . esc_attr($textOverlay) . '"/><p class="pg-template-text">Text Overlay</p></div>';
    $html .='<div class="single-template-div"><img class="templateDemoImage" src="' . esc_attr($textSideRight) . '"/><p class="pg-template-text">Text Side Right</p></div>';
    $html .='<div class="single-template-div"><img class="templateDemoImage" src="' . esc_attr($bigTopText) . '"/><p class="pg-template-text">Big Top Text</p></div>';
    $html .='</div>';

    // Escape output
    $arr = array(   
        'div' => array(
            'class' => array()
        ), 
        'h3' => array(), 
        'p' => array(
            'class' => array(),
        ),
        'img' => array(
            'class' => array(),
            'src' => array(),
        )
    );

    echo wp_kses($html, $arr);
    ?>
    
</div>