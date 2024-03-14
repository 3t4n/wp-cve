<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

// Define _JEXEC constant in order to avoid any (compatibility) errors
defined('_JEXEC') or define('_JEXEC', 1);

// Software version
define('VIKAPPOINTMENTS_SOFTWARE_VERSION', '1.2.11');

// Software debugging flag
define('VIKAPPOINTMENTS_DEBUG', false);

// Base path
define('VIKAPPOINTMENTS_BASE', dirname(__FILE__));

// Libraries path
define('VIKAPPOINTMENTS_LIBRARIES', VIKAPPOINTMENTS_BASE . DIRECTORY_SEPARATOR . 'libraries');

// Languages path
define('VIKAPPOINTMENTS_LANG', basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'languages');

// Core Media URI
define('VIKAPPOINTMENTS_CORE_MEDIA_URI', plugin_dir_url(__FILE__) . 'media/');

// Assets URI
define('VAPASSETS_URI', plugin_dir_url(__FILE__) . 'site/assets/');
define('VAPASSETS_ADMIN_URI', plugin_dir_url(__FILE__) . 'admin/assets/');

// URI Constants for admin and site sections (with trailing slash)
define('VAP_BASE_URI', plugin_dir_url(__FILE__));
define('VAP_ADMIN_URI', VAP_BASE_URI . 'admin/');
define('VAP_SITE_URI', VAP_BASE_URI . 'site/');
define('VAP_MODULES_URI', VAP_BASE_URI . 'modules/');

// Path Constants for admin and site sections (with NO trailing directory separator)
define('VAPADMIN', VIKAPPOINTMENTS_BASE . DIRECTORY_SEPARATOR . 'admin');
define('VAPADMIN_URI', VAP_ADMIN_URI);
define('VAPBASE', VIKAPPOINTMENTS_BASE . DIRECTORY_SEPARATOR . 'site');
define('VAPBASE_URI', VAP_SITE_URI);

define('VAPMODULES', VIKAPPOINTMENTS_BASE . DIRECTORY_SEPARATOR . 'modules');
define('VAPMODULES_URI', VAP_MODULES_URI);

// Helpers path
define('VAPHELPERS', VAPBASE . DIRECTORY_SEPARATOR . 'helpers');

// Mail Attachments path
define('VAPMAIL_ATTACHMENTS', VAPHELPERS . DIRECTORY_SEPARATOR . 'mail_attach');

// Mail Templates path
define('VAPMAIL_TEMPLATES', VAPHELPERS . DIRECTORY_SEPARATOR . 'mail_tmpls');

// Libraries path
define('VAPLIB', VAPHELPERS . DIRECTORY_SEPARATOR . 'libraries');

// Upload path
$upload = wp_upload_dir();

define('VAP_UPLOAD_DIR_PATH', $upload['basedir'] . DIRECTORY_SEPARATOR . 'vikappointments');
define('VAP_UPLOAD_DIR_URI', $upload['baseurl'] . '/vikappointments/');

// Customers uploads path
define('VAPCUSTOMERS_UPLOADS', VAP_UPLOAD_DIR_PATH . DIRECTORY_SEPARATOR . 'customers' . DIRECTORY_SEPARATOR . 'tmp');

// Customers uploads URI
define('VAPCUSTOMERS_UPLOADS_URI', VAP_UPLOAD_DIR_URI . 'customers/tmp/');

// Customers avatar path
define('VAPCUSTOMERS_AVATAR', VAP_UPLOAD_DIR_PATH . DIRECTORY_SEPARATOR . 'customers' . DIRECTORY_SEPARATOR . 'avatar');

// Customers avatar URI
define('VAPCUSTOMERS_AVATAR_URI', VAP_UPLOAD_DIR_URI . 'customers/avatar/');

// Customers Documents path
define('VAPCUSTOMERS_DOCUMENTS', VAP_UPLOAD_DIR_PATH . DIRECTORY_SEPARATOR . 'customers' . DIRECTORY_SEPARATOR . 'documents');

// Customers Documents URI
define('VAPCUSTOMERS_DOCUMENTS_URI', VAP_UPLOAD_DIR_URI . 'customers/documents/');

// Media path
define('VAPMEDIA', VAP_UPLOAD_DIR_PATH . DIRECTORY_SEPARATOR . 'media');

// Media small path
define('VAPMEDIA_SMALL', VAP_UPLOAD_DIR_PATH . DIRECTORY_SEPARATOR . 'media@small');

// Media URI
define('VAPMEDIA_URI', VAP_UPLOAD_DIR_URI . 'media/');

// Media small URI
define('VAPMEDIA_SMALL_URI', VAP_UPLOAD_DIR_URI . 'media@small/');

// Invoice path
define('VAPINVOICE', VAP_UPLOAD_DIR_PATH . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . 'archive');

// Invoice URI
define('VAPINVOICE_URI', VAP_UPLOAD_DIR_URI . 'pdf/archive/');

// Customizer path
define('VAP_CSS_CUSTOMIZER', VAPBASE . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'customizer');

// Customizer URI
define('VAP_CSS_CUSTOMIZER_URI', VAPASSETS_URI . 'css/customizer/');

// Joomla BC
defined('JPATH_SITE') or define('JPATH_SITE', 'JPATH_SITE');
defined('JPATH_ADMINISTRATOR') or define('JPATH_ADMINISTRATOR', 'JPATH_ADMINISTRATOR');

/**
 * Site pre-processing flag.
 * When this flag is enabled, the plugin will try to dispatch the
 * site controller within the "init" action. This is made by 
 * fetching the shortcode assigned to the current URI.
 *
 * By disabling this flag, the site controller will be dispatched 
 * with the headers already sent.
 */
define('VIKAPPOINTMENTS_SITE_PREPROCESS', true);
