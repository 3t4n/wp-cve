<?php
/**
 * Plugin Name: Keep Emoticons as Text
 * Plugin URI: https://www.geekgoddess.com/keep-emoticons-as-text-plugin
 * Description: Disables the default WordPress option of converting emoticons to image smilies
 * Version: 1.0.0
 * Author: Jaime Lerner - the Geek Goddess
 * Author URI: https://www.geekgoddess.com
 * License: GPL2
 */

add_filter( 'option_use_smilies', '__return_false' );