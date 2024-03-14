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

if (defined('WPINC'))
{
	// do not proceed in case of WordPress
	return;
}

// Software version
define('VIKAPPOINTMENTS_SOFTWARE_VERSION', '1.7.4');

// Base path
define('VAPBASE', JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_vikappointments');
define('VAPBASE_URI', JUri::root() . 'components/com_vikappointments/');

// Admin path
define('VAPADMIN', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_vikappointments');
define('VAPADMIN_URI', JUri::root() . 'administrator/components/com_vikappointments/');

// Helpers path
define('VAPHELPERS', VAPBASE . DIRECTORY_SEPARATOR . 'helpers');

// Mail Attachments path
define('VAPMAIL_ATTACHMENTS', VAPHELPERS . DIRECTORY_SEPARATOR . 'mail_attach');

// Mail Templates path
define('VAPMAIL_TEMPLATES', VAPHELPERS . DIRECTORY_SEPARATOR . 'mail_tmpls');

// Libraries path
define('VAPLIB', VAPBASE . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'libraries');

// Modules
define('VAPMODULES', JPATH_SITE . DIRECTORY_SEPARATOR . 'modules');
define('VAPMODULES_URI', JUri::root() . 'modules/');

// Assets URI
define('VAPASSETS_URI', JUri::root() . 'components/com_vikappointments/assets/');
define('VAPASSETS_ADMIN_URI', JUri::root() . 'administrator/components/com_vikappointments/assets/');

// Customers Uploads path
define('VAPCUSTOMERS_UPLOADS', VAPBASE . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'cust_tmp');

// Customers Uploads URI
define('VAPCUSTOMERS_UPLOADS_URI', VAPASSETS_URI . 'cust_tmp/');

// Customers Uploads path
define('VAPCUSTOMERS_AVATAR', VAPBASE . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'customers');

// Customers Uploads URI
define('VAPCUSTOMERS_AVATAR_URI', VAPASSETS_URI . 'customers/');

// Customers Documents path
define('VAPCUSTOMERS_DOCUMENTS', VAPCUSTOMERS_AVATAR . DIRECTORY_SEPARATOR . 'documents');

// Customers Documents URI
define('VAPCUSTOMERS_DOCUMENTS_URI', VAPASSETS_URI . 'customers/documents/');

// Media path
define('VAPMEDIA', VAPBASE . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'media');

// Media small path
define('VAPMEDIA_SMALL', VAPBASE . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'media@small');

// Media URI
define('VAPMEDIA_URI', JUri::root() . 'components/com_vikappointments/assets/media/');

// Media small URI
define('VAPMEDIA_SMALL_URI', JUri::root() . 'components/com_vikappointments/assets/media@small/');

// Invoice path
define('VAPINVOICE', VAPHELPERS . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . 'archive');

// Invoice URI
define('VAPINVOICE_URI', JUri::root() . 'components/com_vikappointments/helpers/pdf/archive/');

// Customizer path
define('VAP_CSS_CUSTOMIZER', VAPBASE . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'customizer');

// Customizer URI
define('VAP_CSS_CUSTOMIZER_URI', VAPASSETS_URI . 'css/customizer/');
