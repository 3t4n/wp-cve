<?php

require_once(plugin_dir_path(__FILE__) . 'functions.php');

function apa_epi_f_generate_html(){

    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
	$urls['year'] = "";
	$urls['date'] = "";
	$urls['title'] = "";
	$urls['words'] = "";
	$urls['url'] = "";
	$urls['category'] = "";
?>
<div class="wrap">
	<h2><?php _e('Export post info', 'export-post-info'); ?></h2>
	<div id="main-container" class="postbox-container metabox-holder" style="width:75%;">
    	<div style="margin:0 8px;">
            <div class="postbox">
                <h3 style="cursor:default;"><span><?php _e('Export post info - Options', 'export-post-info'); ?></span></h3>
                <div class="inside">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><?php _e( 'Random string', 'export-post-info' ); ?></th>
                            <td>
                                <form method="post" action="options.php">
                                    <?php settings_fields( 'export-post-info-settings-group' ); ?>
                                    <?php do_settings_sections( 'export-post-info-settings-group' ); ?>
                                    <label for="epi_random_string_filename">
                                        <input type="text" id="epi_random_string_filename" size="36" name="epi_random_string_filename" value="<?php
                                        if ( empty ( get_option( 'epi_random_string_filename' ) ) ) {
                                            echo date('d') . date('m') . date('Y') . apa_epi_f_generatepseudorandomstring();
                                        } else {
                                            echo esc_html( get_option( 'epi_random_string_filename' ) );
                                        }
                                        ?>" />
                                        <p class="description"><?php _e( 'Please specify a random string to make your file name unique. We create a pseudo-random string for you, but you should change it.', 'export-post-info' ); ?></p>
                                        <p class="description"><?php _e( 'You have to save the string so that the plugin generated the export file for you.', 'export-post-info' ); ?></p>
                                    </label>
                                   <?php submit_button(); ?>
                                </form>
                            </td>
                        </tr>
                    </table>
                    <?php if ( empty ( get_option( 'epi_random_string_filename' ) ) ) { ?>
                    <p><span style="color:red"><strong><?php _e( 'Random string not saved or empty.', 'export-post-info' ); ?></strong></span> <strong><?php _e('Please save it and then the file will be generated!', 'export-post-info' ); ?></strong></p>
                    <?php } else { ?>
                        <p><?php _e( 'You can find the generated CSV file at: ', 'export-post-info' ); ?><a href="<?php $upload_dir = wp_upload_dir(); echo $upload_dir['baseurl'] . '/export-post-info-' . esc_html( get_option( 'epi_random_string_filename' ) ) . '.csv'; ?>"><?php $upload_dir = wp_upload_dir(); echo $upload_dir['baseurl'] . '/export-post-info-' . esc_html( get_option( 'epi_random_string_filename' ) ) . '.csv'; ?></a></p>
                    <?php } ?>
                    <p><?php _e( 'In the CSV file you get following information for each post: Date published, Post title, Word Count, Status, URL and First Category (if YOAST SEO is in use and a primarty category is defined, you get the primary category).', 'export-post-info' ); ?></p>
					<p><?php _e( 'Please note that only posts with status "publish", "future" or "private" are exported.', 'export-post-info' ); ?></p>
                    <p><?php _e( 'This file can be easily imported into Excel to filter the themes already covered in the blog posts.', 'export-post-info' ); ?></p>                
                    <p><strong><?php _e( 'Each time you access this page the export file is generated.', 'export-post-info'); ?></strong> <?php _e('The file is also generated when changing the random string and saving the new one.', 'export-post-info' ); ?></p>
                </div> <!-- .inside -->
            </div> <!-- .postbox -->
		</div> <!-- style margin -->
	</div> <!-- #main-container -->
	<div id="side-container" class="postbox-container metabox-holder" style="width:24%;">
    	<div style="margin:0 8px;">
            <div class="postbox">
                <h3 style="cursor:default;"><span><?php _e('Do you like this Plugin?', 'export-post-info'); ?></span></h3>
                <div class="inside">
                    <p><?php _e('We also need volunteers to translate this and our other plugins into more languages.', 'export-post-info'); ?></p>
                    <p><?php _e('If you wish to help then use our', 'export-post-info'); echo ' <a href="http://apasionados.es/contacto/index.php?desde=wordpress-org-export-post-info-administracionplugin" target="_blank">'; _e('contact form', 'export-post-info'); echo '</a> '; _e('or contact us on Twitter:', 'export-post-info'); echo ' <a href="https://twitter.com/apasionados" target="_blank">@Apasionados</a>.'; ?></p>
                    <h4 align="right"><img src="<?php echo (plugin_dir_url(__FILE__) . 'love_bw.png'); ?>" /> <span style="color:#b5b5b5;"><?php _e('Developed with love by:', 'export-post-info'); ?></span> <a href="https://apasionados.es/" target="_blank">Apasionados.es</a></h4>
                </div> <!-- .inside -->
            </div> <!-- .postbox -->
		</div> <!-- style margin -->
	</div> <!-- #side-container -->
</div> <!-- wrap -->


<?php
	if ( empty ( get_option( 'epi_random_string_filename' ) ) ) {
		echo '<div class="updated"><span style="color:red"><strong>' . __( 'Random string not saved or empty.', 'export-post-info' ) . '</strong></span> ' . __('Please save it and then the file will be generated!', 'export-post-info' ) . '</div>';
	} else {
		apa_epi_f_generate_output();
	}
}

apa_epi_f_generate_html();

