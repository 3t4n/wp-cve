=== Easy 3D Viewer ===
Contributors: fuzzoid
Tags: 3D, stl, obj, model, viewer, woocommerce
Requires at least: 3.5
Tested up to: 6.4
Stable tag: 1.8.6.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easy to use WordPress/WooCommerce product 3D viewer.

== Description ==

Simple WordPress/WooCommerce product 3D viewer. 
Has a visual shortcode builder where you can upload the 3D model and adjust settings like color, background, reflection, light sources, shadows, etc. The shortcode can be pasted to any page or a post.
In the WooCommerce admin the plugin adds a new "Product model" box underneath "Product Image".
Supported file types: STL, OBJ/MTL, GLTF/GLB (including textures and animation), ZIP.

== Features ==

* Visual shortcode builder.
* WooCommerce integration.
* WooCommerce products have an option to keep the product image and show "View 3D" button
* Files supported: STL (bin,ascii), OBJ (including MTL support), GLTF (including textures and animations), WRL (PRO version), 3MF (PRO version), ZIP.
* Adjustable scene - background color, shadows, ground mirror.
* Adjustable model - color, shininess, transparency.
* Browser support: works best with WebGL enabled browsers.
* PRO version: WRL support, 3MF support.
* PRO version: Shortcodes support thumbnails.
* PRO version: Can have multiple 3D viewers on the same page (10 maximum).
* PRO version: Can convert models to PNG, animated GIF, WEBM video format to protect models from downloading or improve the customer experience on mobile.
* PRO version: Option to show a WEBM or a GIF instead of 3D model on mobile devices.
* PRO version: Model compression (for faster model loading)
* PRO version: Can load models from 3rd party sites with shortcodes.
* PRO version: Model repair feature (STL, OBJ).
* PRO version: Model polygon reduction feature (STL, OBJ).
* PRO version: Variable product support.


== Installation ==

WordPress usage:

* Make sure you have "Load On" option set as "Everywhere" in General Settings
* In the admin navigate to Woo3DViewer page, Shortcode Builder tab
* Set model
* Click "Generate" button to generate the shortcode 
* Paste the shortcode to any page or a post


WooCommerce usage:

* Make sure you have WooCommerce installed 
* Create a new product or edit an existing one
* Underneath "Product Image" check "Product model" box
* Click "Set model" and upload your model
* Publish the product
* Now you can alter model settings (color, transparency, etc) in "Product model" box 
* Save the product again
* If the model does not show up on the product page or you experience layout issues please go to Shortcode Builder and click "Product Shortcode". In the popup window generate the shortcode having "Compatibility mode enabled". Create a new page and paste the shortcode into the page body.

== Frequently Asked Questions ==

= How do I upload OBJ model with MTL? =

Upload a zip file that contains obj, mtl and texture files.
Example: https://wp3dprinting.com/popcorn.zip

= My "Maximum upload file size" is too small, what should I do? =

Either ask your tech support to increase post_max_size and upload_max_filesize values or use a specific plugin that does the job.

= I'm trying to view a 500mb model, why is it loading so slow? =

The plugin does not have any magic tricks to quickly load and display large models. 
To provide the best experience for your site visitors try to keep models under 10mb by reducing the number of polygons.
The PRO version of the plugin has ZIP compression option which is useful for faster model downloading.

= How do I protect my models by restricting their download? =

Rule of a thumb: if a model is viewable - it's downloadable. 
The only way to protect your models offered by the plugin is to convert them to GIF or WEBM. 
After the conversion the source models should be removed manually through WordPress Media Library.

= How do I translate the plugin? =

The easiest way is to use this plugin https://wordpress.org/plugins/loco-translate/

== Demo ==

http://woo3dviewer.wp3dprinting.com/shortcode-test/

== Screenshots ==

1. Frontend
2. Backend - general settings
3. Backend - shortcode builder settings

== Changelog ==

= 1.8.6.3 =

* Model thumbnail in order details
* Minor bugfixes
* WooCommerce 8.6 tested

= 1.8.5.3 =
* HPOS compatibility
* Shortcode Builder: option to get the WooCommerce product shortcode
* WooCommerce 8.3 tested
* WordPress 6.4 tested

= 1.8.4 =
* General settings: override shopping cart thumbnail option
* General settings: shadow softness option
* Bugfix for 3mf models inside zip archives
* WooCommerce 7.4 tested
* WordPress 6.1 tested
* PRO: Product variations have images

= 1.6.2 =
* Backend Shortcode Builder: option to load the scene from the shortcode
* WooCommerce 6.9 tested

= 1.6.1 =
* Bugfix: disappearing product models
* Better handling of WRL models
* WordPress 6.0 tested
* WooCommerce 6.7 tested

= 1.5.9 =
* Default settings: removed "disable controls" setting
* Default settings: settings to disable zoom, pan and rotation separately
* Default settings: zoom limit settings
* WordPress 5.9 tested
* WooCommerce 6.3 tested

= 1.5.7.9 =
* Shortcode builder: background transparency option
* Backend: http error handling
* Backend: default shadow setting bugfix
* Backend: Rotation speed and rotation direction in Default Settings
* Product page - fixed canvas height on fullscreen exit
* WooCommerce 5.9 tested.
* PRO: Three.js R111
* PRO: 3MF support
* PRO: Improved WRL support

= 1.5.3.8 =
* bugfix

= 1.5.3.7 =
* Wireframe bugfix
* Code cleanup
* Renamed to Easy 3D Viewer

= 1.5.3.6 =
* General settings: default values for shininess and transparency
* Better mirror positioning
* Bugfixes
* WooCommerce 5.6

= 1.5.1.3 =
* WordPress 5.8
* WooCommerce 5.5

= 1.5.1.2 =
* Light source bugfix

= 1.5.1.1 =
* Load Everywhere is on by default.

= 1.5.1 =
* PRO: Bottom Lights (9 checkboxes)
* Transparent background option
* Show Canvas Border option
* WooCommerce: new button to set the main product image
* If a model is missing a popup is shown
* THREE.js renamed
* WooCommerce 5.3 tested

= 1.4.5.8 =
* JS versioning bugfix

= 1.4.5.7 =
* PRO: Shortcodes support thumbnails
* Help popup update: 3 finger horizontal swipe for panning
* General Settings: option to load .css and .js files only on pages with the shortcode
* General Settings: default rotation setting
* GLTF with external resources support (ZIP file with GLTF+image files)
* WRL with external resources support (ZIP file with WRL+image files)
* Warning if other 3D viewers are detected in the admin and in the browser console
* z_offset bugfix
* Toolbar CSS fix

= 1.3.9.4 =
* Bugfix for small models
* WooCommerce 5.1 tested

= 1.3.9.2 =
* Less model cropping on zoom

= 1.3.9.1 =
* New option in general settings - Enable Controls
* "Edit/Preview" product bugfix
* Wireframe bugfix
* WordPress 5.6 tested
* WooCommerce 4.8 tested

= 1.3.8.7 =

* Edit/Preview product model popup bugfixes
* "Set Model" bugfix
* WooCommerce 4.6 tested

= 1.3.8.5 =

* Toolbar controls - wireframe view button
* Fullscreen mode - keep toolbar controls
* Fullscreen mode - product page fix
* Double-click to enter the fullscreen mode
* Loading image centering fix
* WooCommerce 4.5 tested

= 1.3.6.9 =

* Bugfix

= 1.3.6.8 =

* Better texture support for GLTF/GLB models
* Reload the shortcode builder page when a new model is set
* Bugfix for variable products


= 1.3.6.5 =

* Bugfix for textures in zip files with spaces in file names
* WooCommerce 4.2 tested

= 1.3.6.4 =

* Removed "Protect Uploads" plugin recommendation

= 1.3.6.3 =

* "Show Controls" option per shortcode
* WooCommerce products have an option to keep the product image and show "View 3D" button
* Minor CSS adjustments
* WooCommerce 4.1 tested
* PRO: Multiple viewers on the same page (10 maximum)


= 1.3.4.1 =

* Default settings: added Model Color option

= 1.3.3.1 =

* Removed unnecessary functions
* Minor CSS adjustments

= 1.3.3 =

* New option in General Settings: Show controls (enabled by default)
* Bugfixes

= 1.3.2 =

* Shortcode builder zip upload fix

= 1.3.1 =

* Visual shortcode builder
* Can be used without WooCommerce
* GLTF format support
* VRML format support
* Fog option
* Remember camera position option
* Better plane/grid creation according to model size
* "Fit camera" button
* Light sources are configurable
* Product/shortcode individual scene settings

= 1.2.5.4 =

* Ignore folders staring with __MACOS in zip files

= 1.2.5.3 =

* Fixed obj models on mobile browsers

= 1.2.5.2 =

* Use MeshLambertMaterial instead of MeshPhongMaterial on mobile devices

= 1.2.5.1 =

* Updated Three.js to r101
* Mobile view bugfix
* PRO: model repair feature
* PRO: variable product support

= 1.2.4.1 =

* Rotation bugfix

= 1.2.4 =

* Backend: model is previewed in a popup window

= 1.2.3.8 =

* Switched from window.onload to document.ready

= 1.2.3.7 =

* Models displayed rotated bugfix

= 1.2.3.4 =

* Manual rotation option
* MTL bugfix

= 1.2.2.3 =

* Minor bugfixes

= 1.2.2.2 =

* Multi-site bugfix

= 1.2.2.1 =

* Option to preview 3D models in the admin
* Option to disable animation on mobile devices
* Bugfixes
* PRO version: ability to convert models to animated GIF and WEBM video to protect models from downloading

= 1.0.4 =

* Color picker fix.

= 1.0.3 =

* Bugfix.


= 1.0 =

* Initial release.
