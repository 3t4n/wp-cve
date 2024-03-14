<?php
/**
 * @package Cocoon Media Management
 * @version 1.5.7
 */
/*
Plugin Name: Cocoon Media Management
Plugin URI: http://www.use-cocoon.nl/
Description: Load images from Cocoon.
Version: 1.5.7
Author: Cocoon Software Tech
Author URI: http://www.use-cocoon.nl/
License: GPLv2 or later
Text Domain: use-cocoon.nl
 */
?>
<?php

/**
 * Prevent Direct Access
 */
defined( 'ABSPATH' ) or die( 'Direct Access to This File is Not Allowed.' );
define( 'CMM_VERSION', '1.5.7' );

/**
 * Run when plugin Activated
 */
register_activation_hook( __FILE__, 'pluginActivation' );

function pluginActivation() {
	update_option( 'cmm_stng_domain', '' );
	update_option( 'cmm_stng_username', '' );
	update_option( 'cmm_stng_secret', '' );
	update_option( 'cmm_thumbs_per_page', 24 );
}

/**
 * Run when plugin Deactivated
 */
register_deactivation_hook( __FILE__, 'pluginDeactivation' );

function pluginDeactivation() {
	delete_option( 'cmm_stng_domain' );
	delete_option( 'cmm_stng_username' );
	delete_option( 'cmm_stng_secret' );
	delete_option( 'cmm_thumbs_per_page' );
}


require_once( 'lib/CocoonController.php' );
$cocoonController = new CMM_Cocoon();

add_action( 'admin_enqueue_scripts', 'CMM_AddCocoonScripts' );

function CMM_AddCocoonScripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-autocomplete' );
	wp_enqueue_script( 'cn_main_js', plugin_dir_url( __FILE__ ) . 'js/main.js', array('jquery', 'jquery-ui-autocomplete'), CMM_VERSION );
	wp_localize_script( 'cn_main_js', 'wp_vars', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	) );

	wp_enqueue_style( 'cn_main_css', plugin_dir_url( __FILE__ ) . 'css/main.css', '', CMM_VERSION );
}

add_action( 'admin_menu', 'CMM_CocoonSettings' );

function CMM_CocoonSettings() {
	add_menu_page( __( 'Cocoon Plugin Settings', 'cocoon-media-management' ), __( 'Cocoon Settings', 'cocoon-media-management' ), 'administrator', __FILE__, 'CMM_CocoonSettingsPage', plugins_url( '/img/icon.png', __FILE__ ) );
	add_action( 'admin_init', 'CMM_RegisterCocoonSettings' );
}

function CMM_RegisterCocoonSettings() {
	register_setting( 'cocoon-main-group', 'cmm_stng_domain' );
	register_setting( 'cocoon-main-group', 'cmm_stng_secret' );
	register_setting( 'cocoon-main-group', 'cmm_stng_username' );
}

function CMM_CocoonSettingsPage() { ?>
    <div class="wrap">
        <h1><?php _e( 'Cocoon Settings', 'cocoon-media-management' ); ?></h1>

		<?php if ( ! soapExtensionEnabled() ) {
			echo '<p style="color: red">PHP_Soap extension is not installed, please contact your server administrator</p>';
		}; ?>

        <form method="post" action="options.php">
			<?php settings_fields( 'cocoon-main-group' ); ?>
			<?php do_settings_sections( 'cocoon-main-group' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e( 'Subdomain', 'cocoon-media-management' ); ?></th>
                    <td><input type="text" class="regular-text" name="cmm_stng_domain"
                               value="<?php echo esc_attr( get_option( 'cmm_stng_domain' ) ); ?>"/>
                        <p>
                            <i><?php _e( 'Please	fill in	only your subdomain	name', 'cocoon-media-management' ); ?></i>
                        </p></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e( 'Username', 'cocoon-media-management' ); ?></th>
                    <td><input type="text" class="regular-text" name="cmm_stng_username"
                               value="<?php echo esc_attr( get_option( 'cmm_stng_username' ) ); ?>"/>
                        <p><i><?php _e( 'Your Username', 'cocoon-media-management' ); ?></i></p></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e( 'Secret Key', 'cocoon-media-management' ); ?></th>
                    <td><input type="password" class="regular-text" name="cmm_stng_secret"
                               value="<?php echo esc_attr( get_option( 'cmm_stng_secret' ) ); ?>"/>
                        <p><i><?php _e( 'Your Secret Key', 'cocoon-media-management' ); ?></i></p></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><input type="submit" name="submit" id="submit" class="button button-primary"
                                           value="<?php _e( 'Save Settings', 'cocoon-media-management' ); ?>">
                        <span id="cn-cred-check-loader" style="display: none;">
                            <img width="32" height="32"
                                 src="<?php echo plugin_dir_url( __FILE__ ) . 'img/cn_loader.gif' ?>">
                        </span>
                    </th>
                    <td>
                        <p style="display: none" id="cn-thumb-up"><img width="24" height="24"
                                                                       src="<?php echo plugin_dir_url( __FILE__ ) . 'img/thumb_up.png' ?>"> <?php _e( 'Cocoon account successfully connected.', 'cocoon-media-management' ); ?>
                        </p>
                        <p style="display: none" id="cn-thumb-down"><img width="24" height="24"
                                                                         src="<?php echo plugin_dir_url( __FILE__ ) . 'img/thumb_down.png' ?>"> <?php _e( 'You have entered wrong Cocoon API credentials, please try again.', 'cocoon-media-management' ); ?>
                        </p>
						<?php if ( ! get_option( 'cmm_stng_domain' ) || ! get_option( 'cmm_stng_username' ) || ! get_option( 'cmm_stng_secret' ) ) { ?>
                            <span id="cn-error-msg"><?php _e( 'Please enter your Cocoon API credentials.', 'cocoon-media-management' ); ?></span>
						<?php } ?>
                    </td>
                </tr>
            </table>
        </form>
    </div>
<?php }


/**
 * Make Media Upload Tab
 */

add_filter( 'media_upload_tabs', 'CMM_UploadTab' );

function CMM_UploadTab( $tabs ) {
	$tabs['cocoonmediabank'] = __( 'Cocoon Media Management', 'cocoon-media-management' );

	return $tabs;
}

add_action( 'media_upload_cocoonmediabank', 'CMM_AddNewForm' );

function CMM_AddNewForm() {
	wp_iframe( 'CMM_NewForm' );
}

function CMM_NewForm() {
	if ( ! soapExtensionEnabled() ) {
		wp_die();
	};

	global $cocoonController;

	media_upload_header(); ?>

    <div id="cn-wrap">
        <div id="cn-loader">
            <span></span>
        </div>

        <div id="cn-sidebar">
            <div class="cn-sidebar-wrap">
                <h2><?php _e( 'Available sets', 'cocoon-media-management' ); ?></h2>
                <ul id="cn-sets-list"></ul>
            </div>
        </div>

        <div id="cn-header">
            <div id="cn-search-wrap" style="display: inline-block">
                <input type="text" id="cn-form-search"
                       placeholder="<?php _e( 'Search media items...', 'cocoon-media-management' ); ?>" value="">
            </div>

            <?php $itemsPerPage = get_option( 'cmm_thumbs_per_page' ) ? (int) get_option( 'cmm_thumbs_per_page' ) : 12; ?>
            <div id="cn-items-perpage-wrap">
                <label for="cn-items-perpage"><?php _e( 'Items per page', 'cocoon-media-management' ); ?></label>

                <select id="cn-items-perpage">
                    <option value="12" <?php selected( $itemsPerPage, 12 ); ?>>12</option>
                    <option value="24" <?php selected( $itemsPerPage, 24 ); ?>>24</option>
                    <option value="48" <?php selected( $itemsPerPage, 48 ); ?>>48</option>
                    <option value="60" <?php selected( $itemsPerPage, 60 ); ?>>60</option>
                </select>
            </div>

            <div class="cn-pagination"></div>
        </div>

        <div id="cn-content"></div>

        <div id="cn-bottom">
            <div class="cn-pagination"></div>
        </div>

        <div id="cn-sidebar-right">
            <div class="cn-sidebar-wrap" style="display: none">
                <h2><?php _e( 'Attachment Details', 'cocoon-media-management' ); ?></h2>
                <div class="cn-thumb-details-wrap">
                    <div class="cn-thumb-preview">
                        <img id="cn-form-img" src="">
                    </div>
                    <div class="cn-thumb-details">
                        <div id="cn-form-name"></div>
                        <div id="cn-form-uploaded"></div>
                        <div id="cn-form-size"></div>
                        <div id="cn-form-dim"></div>
                    </div>
                </div>

                <label class="cn-form-label-wrap">
                    <span><?php _e( 'Available Sizes', 'cocoon-media-management' ); ?></span>
                    <select id="cn-form-thumb-types">
						<?php $thumbTypes = $cocoonController->getThumbTypes();
						$convertedArr     = [];
						if ( ! is_soap_fault( $thumbTypes ) ) {
							$convertedArr['original'] = $thumbTypes['original'];

							foreach ( $thumbTypes as $key => $value ) {
								if ( $key !== 'original' ) {
									$convertedArr[ $key ] = $value;
								}
							}

							foreach ( $convertedArr as $key => $value ) {
								if ( $key !== 'original' ) {
									$opText = $key . ' ' . __( 'versie', 'cocoon-media-management' ) . ' (' . __( 'width', 'cocoon-media-management' ) . ' ' . $value['width'] . 'px)';
								} else {
									$opText = $key . ' ' . __( 'versie', 'cocoon-media-management' );
								} ?>
                                <option value="<?php echo $value['path']; ?>"><?php echo $opText; ?></option>
							<?php }
						} ?>
                    </select>
                </label>

                <label class="cn-form-label-wrap">
                    <span><?php _e( 'URL', 'cocoon-media-management' ); ?></span>
                    <input type="text" id="cn-form-url" value="" disabled="disabled">
                </label>

                <label class="cn-form-label-wrap">
                    <span><?php _e( 'Title', 'cocoon-media-management' ); ?></span>
                    <input type="text" id="cn-form-title" value="">
                </label>

                <label class="cn-form-label-wrap">
                    <span><?php _e( 'Caption', 'cocoon-media-management' ); ?></span>
                    <textarea id="cn-form-caption"></textarea>
                </label>

                <label class="cn-form-label-wrap">
                    <span><?php _e( 'Alt Text', 'cocoon-media-management' ); ?></span>
                    <input type="text" id="cn-form-alt" value="">
                </label>

                <label class="cn-form-label-wrap">
                    <span><?php _e( 'Size', 'cocoon-media-management' ); ?></span>
                    <select id="cn-form-thumb-size">
                        <option value="full"></option>
						<?php $imageSizes = CMM_GetImageSizes();
						foreach ( $imageSizes as $imageSize ) { ?>
                            <option value="<?php echo $imageSize['name']; ?>"><?php echo ucfirst( $imageSize['name'] ) . ' - ' . $imageSize['width'] . ' x ' . $imageSize['height']; ?></option>
						<?php } ?>
                    </select>
                </label>
            </div>
        </div>

        <div id="cn-footer">
            <button type="button" class="button button-primary" disabled="disabled"
                    id="cn-form-insert"><?php _e( 'Insert into post', 'cocoon-media-management' ); ?></button>
        </div>
    </div>
	<?php
}

add_action( 'admin_menu', 'CMM_AddCocoonLibraryPage' );

function CMM_AddCocoonLibraryPage() {
    add_submenu_page('upload.php', 'Cocoon Library', 'Cocoon Library', 'edit_posts', 'cocoon-library', 'CMM_RegisterCocoonLibraryPage' );
}

function CMM_RegisterCocoonLibraryPage() {
    if ( ! soapExtensionEnabled() ) {
        wp_die();
    };

    global $cocoonController; ?>

    <div id="cn-lib">
        <div id="cn-wrap">
            <div id="cn-loader">
                <span></span>
            </div>

            <div id="cn-sidebar">
                <div class="cn-sidebar-wrap">
                    <h2><?php _e( 'Available sets', 'cocoon-media-management' ); ?></h2>
                    <ul id="cn-sets-list"></ul>
                </div>
            </div>

            <div id="cn-header">
                <div id="cn-search-wrap" style="display: inline-block">
                    <input type="text" id="cn-form-search"
                           placeholder="<?php _e( 'Search media items...', 'cocoon-media-management' ); ?>" value="">
                </div>

                <?php $itemsPerPage = get_option( 'cmm_thumbs_per_page' ) ? (int) get_option( 'cmm_thumbs_per_page' ) : 12; ?>
                <div id="cn-items-perpage-wrap">
                    <label for="cn-items-perpage"><?php _e( 'Items per page', 'cocoon-media-management' ); ?></label>

                    <select id="cn-items-perpage">
                        <option value="12" <?php selected( $itemsPerPage, 12 ); ?>>12</option>
                        <option value="24" <?php selected( $itemsPerPage, 24 ); ?>>24</option>
                        <option value="48" <?php selected( $itemsPerPage, 48 ); ?>>48</option>
                        <option value="60" <?php selected( $itemsPerPage, 60 ); ?>>60</option>
                    </select>
                </div>

                <div class="cn-pagination"></div>
            </div>

            <div id="cn-content"></div>

            <div id="cn-bottom">
                <div class="cn-pagination"></div>
            </div>

            <div id="cn-sidebar-right">
                <div class="cn-sidebar-wrap" style="display: none">
                    <h2><?php _e( 'Attachment Details', 'cocoon-media-management' ); ?></h2>
                    <div class="cn-thumb-details-wrap">
                        <div class="cn-thumb-preview">
                            <img id="cn-form-img" src="">
                        </div>
                        <div class="cn-thumb-details">
                            <div id="cn-form-name"></div>
                            <div id="cn-form-uploaded"></div>
                            <div id="cn-form-size"></div>
                            <div id="cn-form-dim"></div>
                        </div>
                    </div>

                    <label class="cn-form-label-wrap">
                        <span><?php _e( 'Available Sizes', 'cocoon-media-management' ); ?></span>
                        <select id="cn-form-thumb-types">
                            <?php $thumbTypes = $cocoonController->getThumbTypes();
                            $convertedArr     = [];
                            if ( ! is_soap_fault( $thumbTypes ) ) {
                                $convertedArr['original'] = $thumbTypes['original'];

                                foreach ( $thumbTypes as $key => $value ) {
                                    if ( $key !== 'original' ) {
                                        $convertedArr[ $key ] = $value;
                                    }
                                }

                                foreach ( $convertedArr as $key => $value ) {
                                    if ( $key !== 'original' ) {
                                        $opText = $key . ' ' . __( 'versie', 'cocoon-media-management' ) . ' (' . __( 'width', 'cocoon-media-management' ) . ' ' . $value['width'] . 'px)';
                                    } else {
                                        $opText = $key . ' ' . __( 'versie', 'cocoon-media-management' );
                                    } ?>
                                    <option value="<?php echo $value['path']; ?>"><?php echo $opText; ?></option>
                                <?php }
                            } ?>
                        </select>
                    </label>

                    <label class="cn-form-label-wrap">
                        <span><?php _e( 'URL', 'cocoon-media-management' ); ?></span>
                        <input type="text" id="cn-form-url" value="" disabled="disabled">
                    </label>

                    <label class="cn-form-label-wrap">
                        <span><?php _e( 'Title', 'cocoon-media-management' ); ?></span>
                        <input type="text" id="cn-form-title" value="">
                    </label>

                    <label class="cn-form-label-wrap">
                        <span><?php _e( 'Caption', 'cocoon-media-management' ); ?></span>
                        <textarea id="cn-form-caption"></textarea>
                    </label>

                    <label class="cn-form-label-wrap">
                        <span><?php _e( 'Alt Text', 'cocoon-media-management' ); ?></span>
                        <input type="text" id="cn-form-alt" value="">
                    </label>

                    <label class="cn-form-label-wrap">
                        <span><?php _e( 'Size', 'cocoon-media-management' ); ?></span>
                        <select id="cn-form-thumb-size">
                            <option value="full"></option>
                            <?php $imageSizes = CMM_GetImageSizes();
                            foreach ( $imageSizes as $imageSize ) { ?>
                                <option value="<?php echo $imageSize['name']; ?>"><?php echo ucfirst( $imageSize['name'] ) . ' - ' . $imageSize['width'] . ' x ' . $imageSize['height']; ?></option>
                            <?php } ?>
                        </select>
                    </label>
                </div>
            </div>

            <div id="cn-footer">
                <button type="button" class="button button-primary" disabled="disabled"
                        id="cn-form-upload"><?php _e( 'Upload', 'cocoon-media-management' ); ?></button>
            </div>
        </div>
    </div>
<?php }


function CMM_GetImageSizes() {
	$sizes                        = array();
	$get_intermediate_image_sizes = get_intermediate_image_sizes();
	foreach ( $get_intermediate_image_sizes as $item_size ) {
		if ( in_array( $item_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
			$tmp['name']   = $item_size;
			$tmp['width']  = get_option( $item_size . '_size_w' );
			$tmp['height'] = get_option( $item_size . '_size_h' );

			array_push( $sizes, $tmp );
		}
	}

	return $sizes;
}


add_action( 'wp_ajax_get_files_by_set', 'CMM_GetFilesBySet' );
add_action( 'wp_ajax_nopriv_get_files_by_set', 'CMM_GetFilesBySet' );

function CMM_GetFilesBySet() {
	if ( ! soapExtensionEnabled() ) {
		echo json_encode( array(
			'status'   => 'error',
			'code'     => 1,
			'errorMsg' => 'PHP_Soap extension is not installed, please contact your server administrator'
		) );

		wp_die();
	};

	global $cocoonController;

	ob_start();

	if ( ! $_POST['setId'] ) {
		return json_encode( array( 'status' => 'error', 'statusMsg' => 'Nothing has found!' ) );
	}

	$keyword = $_POST['keyword'] ? sanitize_text_field( $_POST['keyword'] ) : '';

	$setId    = sanitize_text_field( $_POST['setId'] );
	$pageNo   = ( isset( $_POST['pageNo'] ) && $_POST['pageNo'] )
		? (int) sanitize_text_field( $_POST['pageNo'] )
		: 1;
	$pagePer  = $cocoonController->thumbsPerPage;
	$setFiles = [];

	if ( $setId === 'search' ) {
        $tags = $cocoonController->getTags();

		if ( ! is_soap_fault( $tags ) ) {
            $tagId = null;
            foreach($tags as $tag) {
                if($tag['name'] === $keyword) {
                    $tagId = $tag['id'];
                    break;
                }
            }

            if($tagId) {
                $filesByTag = $cocoonController->getFilesByTag($tagId);
                if ( ! is_soap_fault( $filesByTag ) ) {
                    $setFiles = $filesByTag;
                }
            }
		}
	} else {
		$filesBySet = $cocoonController->getFilesBySet( $setId );
        if ( ! is_soap_fault( $filesBySet ) ) {
            $setFiles = $filesBySet;
        }
	}

	$thumbsCount = sizeof( $setFiles );
	$offset      = ( $pageNo - 1 ) * $pagePer;
	$max         = $offset + $pagePer > $thumbsCount ? $thumbsCount : $offset + $pagePer;
	$pages       = ceil( $thumbsCount / $pagePer );

	for ( $i = $offset; $i < $max; $i ++ ) {
		$thumbInfo = $cocoonController->getThumbInfo( $setFiles[ $i ] );

		if ( $thumbInfo['web'] === '' ) {
			$filePath  = "icons/{$thumbInfo['ext']}.svg";
			$cnWebPath = ! file_exists( plugin_dir_path( __FILE__ ) . $filePath )
				? 'icons/other.svg'
				: $filePath;
		} else {
			$cnWebPath = getCachedFile( $thumbInfo['name'], $thumbInfo['ext'], $thumbInfo['web'], 48, 'web' );
		}

		$cnWebUrl = plugins_url( $cnWebPath, __FILE__ );

		if ( ! is_soap_fault( $thumbInfo ) ) { ?>
            <div class="cn-thumb" data-cnid="<?php echo $setFiles[ $i ]['id']; ?>"
                 data-cnpath="<?php echo $thumbInfo['path']; ?>"
                 data-cnext="<?php echo $thumbInfo['ext']; ?>"
                 data-cnname="<?php echo $thumbInfo['name']; ?>"
                 data-cnsize="<?php echo $thumbInfo['size']; ?>"
                 data-cndim="<?php echo $thumbInfo['dim']; ?>"
                 data-cnuploaded="<?php echo $thumbInfo['uploaded']; ?>"
                 data-web="<?php echo $cnWebUrl; ?>"
                 data-cndomain="<?php echo $thumbInfo['domain']; ?>">
                <input class="cn-checkbox" type="checkbox" style="display: none">
                <div class="cn-image" style="background-image: url('<?php echo $cnWebUrl; ?>')"></div>
                <div class="cn-title"><?php echo $setFiles[ $i ]['title']; ?></div>
            </div>
		<?php }
	}

	$html = ob_get_contents();
	ob_end_clean();

	echo json_encode( array( 'data' => $html, 'pagination' => thumbPagination( $pages, $pageNo ) ) );

	wp_die();
}

function thumbPagination( $pages, $pageNo ) {
	$range     = 2;
	$showitems = ( $range * 2 ) + 1;
	ob_start(); ?>

    <ul>
		<?php if ( $pages > 1 ) {
			if ( $pageNo > 2 && $pageNo > $range + 1 && $showitems < $pages ) {
				echo '<li data-page="1" class="cn-page-item">&laquo;</li>';
			}
			if ( $pageNo > 1 && $showitems < $pages ) {
				$pr = $pageNo - 1;
				echo '<li data-page="' . $pr . '" class="cn-page-item">&lsaquo;</li>';
			}
			for ( $i = 1; $i <= $pages; $i ++ ) {
				if ( ! ( $i >= $pageNo + $range + 1 || $i <= $pageNo - $range - 1 ) || $pages <= $showitems ) { ?>
                    <li class="<?php if ( $pageNo === $i ) {
						echo 'active-page';
					} else {
						echo 'cn-page-item';
					} ?>" data-page="<?php echo $i; ?>"><?php echo $i; ?></li>
				<?php }
			}
			if ( $pageNo < $pages && $showitems < $pages ) {
				$po = $pageNo + 1;
				echo '<li data-page="' . $po . '" class="cn-page-item">&rsaquo;</li>';
			}
			if ( $pageNo < $pages - 1 && $pageNo + $range - 1 < $pages && $showitems < $pages ) {
				echo '<li data-page="' . $pages . '" class="cn-page-item">&raquo;</li>';
			}
		} ?>
    </ul>

	<?php $html = ob_get_contents();
	ob_end_clean();

	return $html;
}

function getCachedFile( $file, $ext, $url, $hours = 72, $type ) {
	$cacheDir = plugin_dir_path( __FILE__ ) . 'cache';

	$fileName      = md5( $file . $type . 'some_salt' ) . '.' . $ext;
	$fileCachePath = "{$cacheDir}/{$fileName}";

	$current_time = time();
	$expire_time  = $hours * 60 * 60;
	$file_time    = filemtime( $fileCachePath );
	if ( ! file_exists( $fileCachePath ) || ! ( $current_time - $expire_time < $file_time ) ) {
		wp_mkdir_p( $cacheDir );
		$content = getUrl( $url );
		file_put_contents( $fileCachePath, $content );
	}

	return 'cache/' . $fileName;
}

function getUrl( $url ) {
	$response = wp_remote_get( $url, array(
		'timeout' => 10
	) );
	$body     = is_array( $response ) ? $response['body'] : '';

	return $body;
}


add_action( 'wp_ajax_cn_upload_image', 'CMM_UploadImage' );
add_action( 'wp_ajax_nopriv_cn_upload_image', 'CMM_UploadImage' );

function CMM_UploadImage() {
	global $post;

	if ( ! isset( $_POST['path'] ) || ! isset( $_POST['ext'] ) || ! isset( $_POST['name'] ) ) {
		return json_encode( array( 'status' => 'error', 'statusMsg' => 'Something went wrong!' ) );
	}

	$thumbURL    = sanitize_text_field( $_POST['path'] );
	$fileNameExt = sanitize_text_field( $_POST['ext'] );
	$photoName   = sanitize_text_field( $_POST['name'] );
	$photoSize   = sanitize_text_field( $_POST['size'] );

	$res = wp_remote_get( $thumbURL );

	$attachment = wp_upload_bits( $photoName . '.' . $fileNameExt, null, $res['body'], date( 'Y-m' ) );

	$fileType = wp_check_filetype( basename( $attachment['file'] ), null );

	$postinfo  = array(
		'post_mime_type' => $fileType['type'],
		'post_title'     => $photoName,
		'post_content'   => '',
		'post_status'    => 'inherit',
	);
	$filename  = $attachment['file'];
	$attach_id = wp_insert_attachment( $postinfo, $filename, $post->id );

	$my_image_meta = array( 'ID' => $attach_id );
	$attr          = [];

	if ( $_POST['alt'] ) {
		update_post_meta( $attach_id, '_wp_attachment_image_alt', sanitize_text_field( $_POST['alt'] ) );
	}
	if ( $_POST['title'] ) {
		$my_image_meta['post_title'] = sanitize_text_field( $_POST['title'] );
		$attr['title']               = sanitize_text_field( $_POST['title'] );
	}
	if ( $_POST['caption'] ) {
		$my_image_meta['post_excerpt'] = sanitize_text_field( $_POST['caption'] );
	}
	if ( sizeof( $my_image_meta ) > 1 ) {
		wp_update_post( $my_image_meta );
	}

	$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
	wp_update_attachment_metadata( $attach_id, $attach_data );

	$html = wp_get_attachment_link( $attach_id, $photoSize, false, '', false, $attr );

	echo json_encode( array( 'status' => 'OK', 'data' => $html ) );

	wp_die();
}

add_action( 'wp_ajax_cn_upload_images', 'CMM_UploadImages' );
add_action( 'wp_ajax_nopriv_cn_upload_images', 'CMM_UploadImages' );

function CMM_UploadImages() {
    global $post;

    if ( ! isset( $_POST['path'] ) || ! isset( $_POST['ext'] ) || ! isset( $_POST['name'] ) ) {
        return json_encode( array( 'status' => 'error', 'statusMsg' => 'Something went wrong!' ) );
    }

    $thumbURL    = sanitize_text_field( $_POST['path'] );
    $fileNameExt = sanitize_text_field( $_POST['ext'] );
    $photoName   = sanitize_text_field( $_POST['name'] );

    $res = wp_remote_get( $thumbURL );

    $attachment = wp_upload_bits( $photoName . '.' . $fileNameExt, null, $res['body'], date( 'Y-m' ) );

    $fileType = wp_check_filetype( basename( $attachment['file'] ), null );

    $postinfo  = array(
        'post_mime_type' => $fileType['type'],
        'post_title'     => $photoName,
        'post_content'   => '',
        'post_status'    => 'inherit',
    );
    $filename  = $attachment['file'];
    $attach_id = wp_insert_attachment( $postinfo, $filename, $post->id );

    $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
    wp_update_attachment_metadata( $attach_id, $attach_data );

    echo json_encode( array( 'status' => 'OK', 'data' => 'success' ) );

    wp_die();
}


add_action( 'wp_ajax_check_creds', 'CMM_CheckCreds' );
add_action( 'wp_ajax_nopriv_check_creds', 'CMM_CheckCreds' );

function CMM_CheckCreds() {
	if ( ! soapExtensionEnabled() ) {
		echo json_encode( array(
			'status'   => 'error',
			'code'     => 1,
			'errorMsg' => 'PHP_Soap extension is not installed, please contact your server administrator'
		) );

		wp_die();
	};

	global $cocoonController;

	$result = $cocoonController->getVersion();
	if ( ! is_soap_fault( $result ) ) {
		echo json_encode( array( 'status' => 'OK' ) );
	} else {
		echo json_encode( array( 'status' => 'error', 'code' => 2 ) );
	}

	wp_die();
}

add_action( 'wp_ajax_cn_get_sets', 'CMM_GetSets' );
add_action( 'wp_ajax_nopriv_cn_get_sets', 'CMM_GetSets' );

function CMM_GetSets() {
	if ( ! soapExtensionEnabled() ) {
		echo json_encode( array(
			'status'   => 'error',
			'code'     => 1,
			'errorMsg' => 'PHP_Soap extension is not installed, please contact your server administrator'
		) );

		wp_die();
	};

	global $cocoonController;

	$sets = $cocoonController->getSets();

	if ( is_soap_fault( $sets ) ) {
		wp_die();
	}
	ob_start();
	usort( $sets, sortCmp( 'title' ) );
	foreach ( $sets as $set ) { ?>
        <li>
            <input type="radio"
                   id="<?php echo 'cn' . $set['id']; ?>"
                   class="cn-sets"
                   name="sets"
                   value="<?php echo $set['id']; ?>" <?php if ( $set['file_count'] == 0 ) {
				echo 'disabled';
			} ?>>
            <label for="<?php echo 'cn' . $set['id']; ?>"><?php echo $set['title'] . ' (' . $set['file_count'] . ')'; ?></label>
        </li>
	<?php } ?>
	<?php $html = ob_get_contents();
	ob_end_clean();
	echo $html;

	wp_die();
}

add_action( 'wp_ajax_cn_get_tags', 'CMM_GetTags' );
add_action( 'wp_ajax_nopriv_cn_get_tags', 'CMM_GetTags' );

function CMM_GetTags() {
	global $cocoonController;

	$tags = $cocoonController->getTags();

    if ( ! is_soap_fault( $tags ) ) {
        $data = array();

        foreach ($tags as $tag) {
            array_push($data, $tag['name']);
        }
        echo json_encode( array( 'status' => 'OK', 'data' => $data ) );
    } else {
        echo json_encode( array( 'status' => 'error', 'code' => 2 ) );
    }

	wp_die();
}

add_action( 'wp_ajax_cn_items_perpage', 'CMM_ItemsPerPage' );
add_action( 'wp_ajax_nopriv_cn_items_perpage', 'CMM_ItemsPerPage' );

function CMM_ItemsPerPage() {
    if ( ! isset( $_POST['items'] ) || ! isset( $_POST['items'] ) || ! isset( $_POST['items'] ) ) {
        echo json_encode( array( 'status' => 'error', 'statusMsg' => 'Something went wrong!' ) );
        wp_die();
        return;
    }

    $items = sanitize_text_field( $_POST['items'] );
    update_option( 'cmm_thumbs_per_page', $items );

    echo json_encode( array( 'status' => 'OK' ) );

	wp_die();
}

function sortCmp( $key ) {
	return function ( $a, $b ) use ( $key ) {
		return strcasecmp( $a[ $key ], $b[ $key ] );
	};
}

function getUniqueFiles( $files ) {
	$t        = false;
	$setFiles = [];
	for ( $i = 0; $i < sizeof( $files ); $i ++ ) {
		for ( $j = $i + 1; $j < sizeof( $files ); $j ++ ) {
			if ( $files[ $i ]['id'] === $files[ $j ]['id'] ) {
				$t = true;
				break;
			}
		}
		if ( ! $t ) {
			array_push( $setFiles, $files[ $i ] );
		} else {
			$t = false;
		}
	}

	return $setFiles;
}

function soapExtensionEnabled() {
	return extension_loaded( 'soap' );
}