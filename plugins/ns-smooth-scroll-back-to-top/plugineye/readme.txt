Insert the SDK in plugin main file.

The following SDK is only an example.
You can create your custom pluginEye SDK at: https://www.plugineye.com/

/** 
 * @author      	PluginEye
 * @copyright   	Copyright (c) 2019, PluginEye.
 * @version         1.1.1
 * @license    		https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License Version 3
 * PLUGINEYE SDK
*/
require_once('plugineye/plugineye-class.php');
$plugineye = array(
    'main_directory_name'       => 'main_directory_name',
    'main_file_name'            => 'main_file_name.php',
    'redirect_after_confirm'    => 'admin.php?page=my_options.php',
    'plugin_id'                 => '1',
    'plugin_token'              => 'NWNmN2MwYjBkZTg3ZTU5NmQmM4N2E4NzhmOTdkMDdhMTYzMjg1YTBhM2VlOThjNmU3YThlMDQ1OWE4Y2I=',
    'plugin_dir_url'            => plugin_dir_url(__FILE__),
    'plugin_dir_path'           => plugin_dir_path(__FILE__)
);
$myclass = new pluginEye($plugineye);
$myclass->pluginEyeStart();


Add 'plugineye' directory in top level plugin directory.

Well done! We are ready to work together!

Thank you by pluginEye team.