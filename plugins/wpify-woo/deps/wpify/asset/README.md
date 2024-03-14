# WPify Asset

Abstraction over WordPress Asset.

## Installation

`composer require wpify/asset`

## Usage

```php
use Wpify\Asset\AssetFactory;
use Wpify\Asset\Asset;
use Wpify\Asset\AssetConfig;

$factory = new AssetFactory;

// Enqueues from the URL

$factory->factory( array( 'src' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js?ver=LK12' ) );
$factory->factory( array( 'src' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css' ) );

$args = array( 'dependencies' => array( 'jquery' ) );

// or

$factory->url( 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js?ver=LK12', $args );

// in admin

$factory->admin_url( 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js?ver=LK12', $args );

// on login screen

$factory->login_url( 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js?ver=LK12', $args );


// Enqueues script or style built with `wp-scripts`

$factory->wp_script( plugin_dir_path( __FILE__ ) . 'build/plugin.js', $args );
$factory->admin_wp_script( plugin_dir_path( __FILE__ ) . 'build/plugin.js', $args );
$factory->login_wp_script( plugin_dir_path( __FILE__ ) . 'build/plugin.js', $args );

// Enqueues script or style from theme

$factory->theme( 'style.css', $args );
$factory->admin_theme( 'style.css', $args );
$factory->login_theme( 'style.css', $args );

// Enqueues script or style from parent theme

$factory->parent_theme( 'style.css', $args );
$factory->admin_parent_theme( 'style.css', $args );
$factory->login_parent_theme( 'style.css', $args );

// Create asset manually

$style_config = new AssetConfig( $args );
$style_config->set_src( plugins_url( 'style.css', __FILE__ ) )
             ->set_handle( 'custom-style' )
             ->set_is_admin( false )
             ->set_is_login( false )
             ->set_do_enqueue( '__return_true' )
             ->set_version( '1.0.0' )
             ->set_media( 'all' );
$style = new Asset( $style_config );

$script_config = new AssetConfig( $args );
$script_config->set_src( plugins_url( 'script.js', __FILE__ ) )
              ->set_handle( 'custom-script' )
              ->set_is_admin( false )
              ->set_is_login( false )
              ->set_do_enqueue( '__return_true' )
              ->set_dependencies( array( 'jquery' ) )
              ->set_version( '1.0.0' )
              ->set_in_footer( true ),
              ->set_variables( array( 'some_global_variable' => 'for the script is here!' ) )
              ->set_script_before( 'console.log( "Script before!" );' )
              ->set_script_after( 'console.log( "Script after!" );' )
              ->set_text_domain( 'my-plugin-text-domain' )
              ->set_translations_path( plugin_dir_path( __FILE__ ) . 'languages' );
$script = new Asset( $script_config );
```
