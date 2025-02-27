<?php
/*
 * Plugin Name: WPFront Notification Bar
 * Plugin URI: http://wpfront.com/notification-bar-pro/ 
 * Description: Easily lets you create a bar on top or bottom to display a notification.
 * Version: 3.4
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Author: Syam Mohan
 * Author URI: http://wpfront.com
 * License: GPL v3 
 * Text Domain: wpfront-notification-bar
*/

/*
  WPFront Notification Bar Plugin
  Copyright (C) 2013, WPFront.com
  Website: wpfront.com 
  Contact: syam@wpfront.com

  WPFront Notification Bar Plugin is distributed under the GNU General Public License, Version 3,
  June 2007. Copyright (C) 2007 Free Software Foundation, Inc., 51 Franklin
  St, Fifth Floor, Boston, MA 02110, USA
  
  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
  ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
  DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
  ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
  ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

if (!defined('ABSPATH')) exit();

if (class_exists('\WPFront\Notification_Bar\WPFront_Notification_Bar') || class_exists('WPFront_Notification_Bar')) {
  return;
}

use WPFront\Notification_Bar\WPFront_Notification_Bar;

require_once("classes/class-wpfront-notification-bar.php");

if (!function_exists('add_action')) {
  return;
}

WPFront_Notification_Bar::Instance()->init(plugin_basename(__FILE__));