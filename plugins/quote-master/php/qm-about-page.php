<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
  * Generates the about page
  *
  * @since 7.0.0
  */
class QM_About_Page
{
    /**
      * Main Construct Function
      *
      * Call functions within class
      *
      * @since 7.0.0
      * @uses QM_About_Page::load_dependencies() Loads required filed
      * @uses QM_About_Page::add_hooks() Adds actions to hooks and filters
      * @return void
      */
    function __construct()
    {
      $this->load_dependencies();
      $this->add_hooks();
    }

    /**
      * Load File Dependencies
      *
      * @since 7.0.0
      * @return void
      */
    public function load_dependencies()
    {
      //Insert code
    }

    /**
      * Add Hooks
      *
      * Adds functions to relavent hooks and filters
      *
      * @since 7.0.0
      * @return void
      */
    public function add_hooks()
    {
        //Insert code
    }

    /**
     * Generates the about page
     *
     * @since 7.0.0
     * @return void
     */
    public static function generate_page()
    {
      global $quote_master;
    	$version = $quote_master->version;
    	wp_enqueue_script( 'jquery' );
      wp_enqueue_script( 'qm_admin_script', plugins_url( '../js/admin.js' , __FILE__ ) );
      ?>
    	<div class="wrap about-wrap">
      	<h1><?php _e('Welcome To Quote Master', 'quote-master'); ?></h1>
      	<div class="about-text"><?php _e('Thank you for updating!', 'quote-master'); ?></div>
      	<h2 class="nav-tab-wrapper">
      		<a href="javascript:qm_setTab(1);" id="tab_1" class="nav-tab nav-tab-active">
      			<?php _e("What's New!", 'quote-master'); ?></a>
      		<a href="javascript:qm_setTab(2);" id="tab_2" class="nav-tab">
      			<?php _e('Changelog', 'quote-master'); ?></a>
      	</h2>
      	<div id="what_new">
          <h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">Ability To Edit Style</h2>
        	<p style="text-align: center;">Now, you can use your own custom style using the new Settings page.</p>
        	<br />
          <h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">Ability To Tweet Quotes</h2>
        	<p style="text-align: center;">You can now allow users to tweet quotes. You can turn on this option from the new Settings page.</p>
        	<br />
          <h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">This Plugin Is Now Translation Ready!</h2>
        	<p style="text-align: center;">For those who wish to assist in translating, you can find the POT in the languages folder. If you do not know what that is, feel free to contact me and I will assist you with it.</p>
        	<br />
          <hr />
        	<h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">For Developers:</h2>
        	<br />
          <h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">We Are On GitHub Now</h2>
        	<p style="text-align: center;">Quote Master is now on GitHub! I would love for you to add suggestions/feedback by creating issues. Feel free to fork and create pull requests too. Be sure to <a href="https://github.com/fpcorso/quote_master">check out the repository</a>.</p>
        	<br />
      	</div>
      	<div id="changelog" style="display: none;">
          <h3><?php echo $version; ?> (April 24, 2015)</h3>
        	<ul>
            <li>Added Ability To Allow Users To Tweet Quote <a href='https://github.com/fpcorso/quote_master/issues/12'>GitHub Issue #12</a></li>
            <li>Added New Settings Page</li>
            <li>Added Ability To Edit Style <a href='https://github.com/fpcorso/quote_master/issues/11'>GitHub Issue #11</a></li>
            <li>Bug Fix: Fixed Quotations On Own Line Bug <a href='https://github.com/fpcorso/quote_master/issues/13'>GitHub Issue #13</a></li>
          </ul>
      	</div>
    	</div>
      <?php
    }
}
$qm_about_page = new QM_About_Page();

?>
