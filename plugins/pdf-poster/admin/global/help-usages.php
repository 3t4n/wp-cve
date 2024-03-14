<?php

/*-------------------------------------------------------------------------------*/
// Developer page
/*-------------------------------------------------------------------------------*/
add_action('admin_menu', 'fpdf_support_page');

function fpdf_support_page()
{
    add_submenu_page('edit.php?post_type=pdfposter', 'Help', 'Help', 'manage_options', 'fpdf-support', 'fpdf_support_page_callback');
}

function fpdf_support_page_callback()
{
    ?>
    <div class="bplugins-container">
        <div class="row">
            <div class="bplugins-features">
                <div class="col col-12">
                    <div class="bplugins-feature center">
                        <h1><?php _e("Help & Usages", "pdfp"); ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="bplugins-container">
    <div class="row">
        <div class="bplugins-features">
            <div class="col col-4">
                <div class="bplugins-feature center">
                    <i class="fa fa-life-ring"></i>
                    <h3><?php _e('Need any Assistance?', "pdfp"); ?></h3>
                    <p><?php _e('Our Expert Support Team is always ready to help you out promptly.', "pdfp"); ?></p>
                    <a href="https://bplugins.com/support/" target="_blank" class="button
                    button-primary"><?php _e('Contact Support', "pdfp") ?></a>
                </div>
            </div>
            <div class="col col-4">
                <div class="bplugins-feature center">
                    <i class="fa fa-file-text"></i>
                    <h3><?php _e('Looking for Documentation?', "pdfp") ?></h3>
                    <p><?php echo _e("We have detailed documentation on every aspects of PDF Poster.", "pdfp") ?></p>
                    <a href="https://pdfposter.com/docs" target="_blank" class="button button-primary"><?php _e("Documentation", "pdfp") ?></a>
                </div>
            </div>
            <div class="col col-4">
                <div class="bplugins-feature center">
                    <i class="fa fa-thumbs-up"></i>
                    <h3><?php _e("Like This Plugin?", "pdfp"); ?></h3>
                    <p><?php _e("If you like PDF Poster, please leave us a 5 &#11088; rating.", "pdfp") ?></p>
                    <a href="https://wordpress.org/support/plugin/pdf-poster/reviews/#new-post" target="_blank" class="button
                    button-primary"><?php _e("Rate the Plugin", "pdfp"); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="bplugins-container">
    <div class="row">
        <div class="bplugins-features">
            <div class="col col-12">
                <div class="bplugins-feature center" style="padding:5px;">
                    <h2 style="font-size:22px;"><?php _e("Looking For Demo?", "pdfp"); ?> <a href="https://pdfposter.com/all-demos-in-one-place/" target="_blank"><?php _e("Click Here", "pdfp"); ?></a></h2>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="bplugins-container">
    <div class="row">
        <div class="bplugins-features">
            <div class="col col-12">
                <div class="bplugins-feature center">
                    <h1><?php _e("Video Tutorials", "pdfp"); ?></h1><br/>
                    <div class="embed-container"><iframe width="100%" height="700px" src="https://www.youtube.com/embed/PcYaAw7gX7w" frameborder="0"
                    allowfullscreen></iframe></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
}