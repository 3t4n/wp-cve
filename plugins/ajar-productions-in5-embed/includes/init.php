<?php

if (!defined('ABSPATH')) {
  exit;
} // Exit if accessed directly

// Handles the uploaded files
require_once IN5_PLUGIN_PATH . 'includes/fileManagement.php';

// Add the button to Wordpress post editor
function in5_add_media_button() {
	echo '<a href="#" class="in5-media-button button"><img style="height:18px; vertical-align:text-top;" src="' . IN5_PLUGIN_URL . 'assets/img/in5_logo_22px.png" class="icon" draggable="false" alt="">Add in5 Embed</a>';
}

add_action('media_buttons', 'in5_add_media_button', 10);

// Display max upload size
function in5_max_upload() {
	$maxPostSize = ini_get( 'post_max_size' );
	$maxUploadSize = ini_get( 'upload_max_filesize' );
	$maxSize = ( $maxPostSize > $maxUploadSize ) ? $maxUploadSize : $maxPostSize;

	return str_replace( 'M', ' MB', $maxSize );
}

// Add our popup to Wordpress post editor
function in5_add_popup()
{
  ?>
  <script type="text/javascript">
    var pluginDirUrl = '<?php echo IN5_PLUGIN_URL; ?>';
    var fileList = <?php echo (!empty(in5_get_metadata())) ? in5_get_metadata() : '{"files":[]}' ?>;
    // var fileList;
  </script>
  <div class="media-modal wp-core-ui in5-embed-popup">
    <div class="in5-popup-header">
      <button type="button" class="button-link media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Close media panel</span></span></button>
      <div class="in5-popup-title">
        <h1>in5 Embed Library</h1>
      </div>
      <div class="in5-tabs">
        <a href="#" class="tab tab-library active" data-tab="in5-library">Library</a>
        <a href="#" class="tab tab-upload" data-tab="in5-upload">Add new</a>
      </div>
    </div>
    <div class="in5-popup-content">
      <div class="in5-library pane">
        <div class="main">
          <div class="in5-file-list">
            <ul>
              <?php in5_display_file_list(); ?>
            </ul>
          </div>
        </div>
        <div class="side">
          <div class="side-content">
            <h2>Archive details</h2>
            <div class="attachment-info">
              <div class="thumbnail thumbnail-application">
                <img src="<?php echo IN5_PLUGIN_URL . 'assets/img/archive.png'; ?>" class="icon" draggable="false" alt="">
              </div>
              <div class="details">
                <div class="in5-change-filename-wrap">
                  <input type="text" class="in5-filename" value="" readonly>
                  <button type="button" class="button-primary in5-change-filename" disabled>Save
                  </button>
                  <!---<button type="button" class="in5-change-filename" disabled><span class="dashicons dashicons-yes"></span></button>-->
                </div>
                <div class="uploaded"></div>
                <div class="file-size"></div>
                <button type="button" class="in5-delete-file button-link delete-attachment" data-fileid="">Delete Permanently
                </button>
              </div>
            </div>
            <div class="in5-archive-settings">
              <label class="setting url">
                <span class="name">URL</span>
                <input type="text" class="directUrl" value="" readonly="">
              </label>
              <label class="setting url">
                <span class="name">Width</span>
                <input type="text" class="in5-iframe-width" value="">
              </label>
              <label class="setting url">
                <span class="name">Height</span>
                <input type="text" class="in5-iframe-height" value="">
              </label>
              <label class="setting url" title="Adjusts height when width changes (only supported with desktop scaling and/or responsive layouts)">
                <input type="checkbox" name="responsiveH" class="in5-iframe-responsiveH" value="">
                <span class="name">Responsive Height</span>
              </label>
              <h2>Display Button Options</h2>
              <label class="setting no-float">
                <input type="checkbox" name="open_in_new_window" class="open_in_new_window">
                <span class="name">Open in new window</span>
              </label>
              <label class="setting no-float">
                <input type="checkbox" name="allow_fullscreen" class="allow_fullscreen">
                <span class="name">Allow fullscreen</span>
              </label>
              <h2>Other Options</h2>
              <label class="setting no-float">
                <input type="checkbox" name="disable_scrolling" class="disable_scrolling">
                <span class="name">Disable scrolling</span>
              </label>
              <label class="setting no-float">
                <input type="checkbox" name="hide_frame_border" class="hide_frame_border">
                <span class="name">Hide border</span>
              </label>
            </div>
          </div>
          <button type="button" class="in5-insert-button button button-primary button-large" disabled>Insert
            into post
          </button>
        </div>
      </div>
      <div class="in5-upload pane">
        <div class="in5-upload-content">
          <h2 class="title">Drop files anywhere to upload</h2>
          <span>or</span>
          <!-- <br>
            <pre>
                <?php print_r(wp_upload_dir()); ?>
                </pre>
            <br> -->

          <a href="#" class="in5-select-button browser button button-hero">Select an archive</a>
          <a href="#" class="customPath-trigger" data-status="0">Upload to custom folder</a>
          <div class="add_custom_path">
            <br>
            <?php echo str_replace(array('https://', 'http://'), '', get_bloginfo('url')) ?> / <input name="custom_path" id="customPathInput">
            <br>
            <strong class="pathTip" style="display: none;">Tip: try to not use multiple dots and spaces;</strong>
            <br>
            <a href="#" class="defPath">Set to default upload folder</a>
          </div>
          <p style="font-size: 12px">Maximum upload file size: <?php echo in5_max_upload(); ?>.</p>
          <p style="font-size: 12px"><a target="_blank" href="https://ajar.freshdesk.com/support/solutions/articles/26000031692-how-to-increase-your-wordpress-upload-limit-for-the-in5-embed-plugin">How
              to increase this limit</a>.</p>
          <input id="in5-file-upload" type="file" name="files[]" data-url="<?php echo admin_url('admin-ajax.php?action=in5&in5=upload'); ?>" multiple>
        </div>
        <div id="in5-progress-bar">
          <div class="in5-bar-wrap">
            <span>Uploading...</span>
            <div class="bar" style="width: 0%;"></div>
            <div class="bar-grey"></div>
          </div>
        </div>
        <div class="in5-upload-overlay"></div>
      </div>
    </div>
  </div>
  <div class="media-modal-backdrop"></div>
<?php
}

add_action( 'admin_footer', 'in5_add_popup' );

add_action( 'admin_enqueue_scripts', 'in5_enable_file_upload_for_gutenberg' );
function in5_enable_file_upload_for_gutenberg() {
	if ( function_exists( 'is_gutenberg_page' ) && is_gutenberg_page() ) {
		add_action( 'admin_footer', 'in5_add_popup' );
	}
}

function in5_handle_file_upload() {
	// Include the file that handles file upload
	require_once IN5_PLUGIN_PATH . 'includes/handleUpload.php';
}

add_action('admin_init', 'in5_handle_file_upload');
?>