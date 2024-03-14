=== WP Live CSS Editor ===
Contributors: funlab, pingram3541
Donate link: http://www.flashdance.es/dontpanic/doku.php?id=wp-live-css-editor
Tags: css, developer, designer, editor
Requires at least: 2.7
Tested up to: 3.6
Stable tag: 13.09

Edit, preview changes in real time and save all your project's CSS stylesheets live in the browser.


== Description ==

A CSS live Editor that allows you to preview realtime CSS changes and then save your changes.
It saves dated backups of each save, so you can go back if it breaks, and you should erase backups once on a while.
It's a port from a Drupal module Live CSS http://drupal.org/project/live_css by guybedford http://drupal.org/user/746802
Now using ACE editor http://ace.ajax.org/
Tested on WordPress 3.6. But needs more testing !


=capabilities.php problem (not bug) SOLVED !=

Ok, now I did the correct thing and created an action to the wp_loaded hook to init the plugin, then,
check whether the user can "erase themes" which is an Admin capability only, so the plugin only works if you are an admin.
No need to modify the capabilities.php nor anything else. 
 
Some users have had problems in former 12.05 release with their site being inaccesible after installing the plugin.
It all seemed to be part of the same problem with the capabilities.php file that is discussed here:
http://wordpress.org/support/topic/fatal-error-call-to-undefined-function-wp_get_current_user-4?replies=32

It works nice and perfect for me, but still needs testing !!!

== Installation ==

Upload the `wp-live-css-editor` folder to the `/wp-content/plugins/` directory
Activate the plugin through the 'Plugins' menu in WordPress

IMPORTANT: Tested on WordPress 3.3.2, now you have to be an administrator to use it. 

capabilities.php problem (not bug) SOLVED !

Ok, now I did the correct thing and created an action to the wp_loaded hook to init the plugin, then,
check whether the user can "erase themes" which is an Admin capability only, so the plugin only works if you are an admin.
No need to modify the capabilities.php nor anything else. 
 
Some users have had problems in former 12.05 release with their site being inaccesible after installing the plugin.
It all seemed to be part of the same problem with the capabilities.php file that is discussed here:
http://wordpress.org/support/topic/fatal-error-call-to-undefined-function-wp_get_current_user-4?replies=32


File structure:

* /wp-content/plugins/wp-live-css-editor/
* /wp-content/plugins/wp-live-css-editor/wp-live-css-editor.php
* /wp-content/plugins/wp-live-css-editor/wp-live-css-editor.js
* /wp-content/plugins/wp-live-css-editor/wp-live-css-editor-css.css
* /wp-content/plugins/wp-live-css-editor/readme.txt
* /wp-content/plugins/wp-live-css-editor/JSON.php
* /wp-content/plugins/wp-live-css-editor/screenshot-1.jpg
* /wp-content/plugins/wp-live-css-editor/ace/


== Frequently Asked Questions ==

= Will it let me edit the theme's CSS, or just any CSS ? Can I choose which are editable ? =

You can edit any and all CSS files that are loaded in the page. As long as they have write permission, and you are logged in as an administrator.


== Screenshots ==

1. The live css editor with the header changed to red

== Changelog ==

= 13.09 =

Big thanks to pingram3541 for his forum posts that corrected some very ugly bugs.
I've been very busy and away from computers for a while. Better late than never.

* Fixed saving issue that wreaked the file by creating ugly and recursive escape chars \\\\\\\\\\\\\\\\\\: http://wordpress.org/support/topic/backslahes-added-css-broken
* Fixed theme 'setting' that should allow better reading. Kept twilight, though, still you can change the theme manually editing both the js and php files: http://wordpress.org/support/topic/background-color-or-themes
* Moved it all down below the wp admin bar so the css selector isn't hidden: http://wordpress.org/support/topic/sidebar-28px-top-for-wpadmin-menu
* Fixed messing around with admin pages: http://wordpress.org/support/topic/editor-left-open-causes-admin-to-break

= 12.06 =

* pluggable.php and capabilities.php load order problem that made the site inaccesible SOLVED using the wp_loaded hook

= 12.05 =

* Updated from guybedford's module http://drupal.org/project/live_css
* Dropped less suport for simplicity
* You have to be logged in as an administrator to see and use the plugin
* Uses ACE editor which solves most of commented bugs and needed features (wild slashes, search in the css editor, undo keyboard shortcuts...)

= 11.05 =

* First Commit ! release 2011-05-15
* Now using wp_enqueue_scripts adn wp_enqueue_styles to correctly inject stylesheets and scripts
* **Not for production sites**, just for design and development time. **Needs roles and permissions settings**, though **only logged in users can actually save changes**, any visitor can see it in action.
* If it doesn't save changes, it's probably a **permission problem** with the CSS file (Or **you are not logged in**)
* Will need to **clean up backups manually** via FTP when you are done, as they might be a lot and counting ! A restore version system would come in handy…
* Seems to be fixed, but for a while the plugin was inflating the CSS files in each save by swarming trillions of slashes before every single and double quote
* Fixed a BUG where relative urls wouldn't find the wp-admin/admin-ajax.php file, thanks to Gary Cao's wonderful tips: http://www.garyc40.com/2010/03/5-tips-for-using-ajax-in-wordpress/#js-global
* Please ! **Backup your CSS files** before using it the first time, just in case !
* No plugin options nor admin page
* A JSON helper for older PHP versions is there, but untested yet.
* A floating window / drawer might be better than narrowing the whole page.


== Upgrade Notice ==

Just the usual, deactivate plugin, replace files, activate.

== Hola mundo ! ==

Este plugin está basado en el módulo de [Drupal Live CSS] (http://drupal.org/project/live_css) de [guybedford] (http://drupal.org/user/746802)

Es un editor de CSS que te permite editar en directo todas las hojas de estilo CSS de tu web, ver los cambios y guardar sobre la marcha.
Está pensada para usarla al diseñar la web, por lo que conviene desactivarlo o desinstalarlo una vez terminada. Cada vez que se guardan los cambios se genera una copia de seguridad fechada, para que puedas volver atrás si hace falta.
Utiliza ACE como editor.

Sólo los administradores pueden usar el plugin.

Importante !!!

Hacer copia de respaldo de los archivos CSS antes de probarlo !
Aunque el plugin genera automáticamente copias de seguridad fechadas en el mismo directorio en que estén los archivos modificados, siempre conviene tener una copia del estado general de la cosa antes de empezar a trastear.
Si no funciona al guardar o hacer la copia de seguridad aparece un mensaje con las rutas del archivo que se intentaba modificar, la de la copia de seguridad y tal vez un mensaje de error. Lo más seguro es que el problema sea de permisos. Los CSS que quieras editar deberían tener permiso de escritura 755.

Instalación

La instalación es la típica de cualquier plugin de wordpress. Desempaquetar el zip en wp-content/plugins, y listo. No hay que tocar los temas ni hacer nada más que activarlo desde el panel de administración.
No tiene opciones ni página propia en el panel de administración.
Probado en WordPress 3.3.2, tienes que ser un administrador para poder usarlo. 

Utiliza ahora el hook wp_loaded que arregla el problema de la versión anterior que se discutía aquí: http://wordpress.org/support/topic/fatal-error-call-to-undefined-function-wp_get_current_user-4?replies=32

Más información

    http://drupal.org/project/live_css	Live CSS
	http://drupal.org/user/746802 		guybedford
    http://drupal.org/user/78427
    http://mozillalabs.com/skywriter/2011/01/18/mozilla-skywriter-has-been-merged-into-ace/
    http://www.ajax.org/
    http://ace.ajax.org/

    http://codex.wordpress.org/AJAX_in_Plugins
    http://briancray.com/2009/08/19/wordpress-head-element/
    http://www.devlounge.net/code/using-ajax-with-your-wordpress-plugin
    http://amiworks.co.in/talk/simplified-ajax-for-wordpress-plugin-developers-using-jquery/

	http://drupal.org/project/cssedit	Realtime CSS Editor
	http://drupal.org/user/78427    	tjholowaychuk
