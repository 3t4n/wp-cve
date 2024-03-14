<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Theme info
$plugin = get_plugin_data(LSOW_PLUGIN_FILE);


if (is_multisite()) {
    $soPageBuilderUrl = network_admin_url('plugin-install.php?tab=plugin-information&plugin=siteorigin-panels&TB_iframe=true&width=640&height=589');
    $soWidgetsBundleUrl = network_admin_url('plugin-install.php?tab=plugin-information&plugin=so-widgets-bundle&TB_iframe=true&width=640&height=589');
    $portfolioPostTypeUrl = network_admin_url('plugin-install.php?tab=plugin-information&plugin=portfolio-post-type&TB_iframe=true&width=640&height=589');
}
else {
    $soPageBuilderUrl = admin_url('plugin-install.php?tab=plugin-information&plugin=siteorigin-panels&TB_iframe=true&width=640&height=589');
    $soWidgetsBundleUrl = admin_url('plugin-install.php?tab=plugin-information&plugin=so-widgets-bundle&TB_iframe=true&width=640&height=589');
    $portfolioPostTypeUrl = admin_url('plugin-install.php?tab=plugin-information&plugin=portfolio-post-type&TB_iframe=true&width=640&height=589');
}

?>

<div class="livemesh-doc">

    <h2 class="notices"></h2>

    <div class="intro-wrap">

        <img class="plugin-image" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/plugin-screenshot.jpg" alt="Livemesh SiteOrigin Widgets">

        <div class="intro">
            <h3><?php printf(__('Getting started with %1$s v%2$s', 'livemesh-so-widgets'), $plugin['Name'], $plugin['Version']); ?></h3>

            <h4><?php printf(__('Thanks for installing %1$s! We truly appreciate the support and the opportunity to share our work with you. Please visit the tabs below to get started on using our plugin to build your site!', 'livemesh-so-widgets'), $plugin['Name']); ?></h4>
        </div>

    </div>

    <div class="panels">
        <ul class="inline-list">
            <li class="current"><a id="help" href="#"><span
                        class="dashicons dashicons-yes"></span> <?php _e('Help File', 'livemesh-so-widgets'); ?></a>
            </li>
            <li><a id="plugins" href="#"><span
                        class="dashicons dashicons-admin-plugins"></span> <?php _e('Plugins', 'livemesh-so-widgets'); ?>
                </a>
            </li>
            <li><a id="support" href="#"><span
                        class="dashicons dashicons-editor-help"></span> <?php _e('FAQ &amp; Support', 'livemesh-so-widgets'); ?>
                </a>
            </li>
            <li><a id="updates" href="#"><span
                        class="dashicons dashicons-update"></span> <?php _e('Latest Updates', 'livemesh-so-widgets'); ?>
                </a>
            </li>
        </ul>

        <div id="panel" class="panel">

            <!-- Help file panel -->
            <div id="help-panel" class="panel-left visible">

                <!-- Grab feed of help file -->

                <!-- Output the feed -->
                <ul id="top" class="toc">
                    <li><a href="#getting-started">Getting Started</a></li>
                    <li><a href="#install-plugins">Installing Recommended/Required Plugins</a></li>
                    <li><a href="#demo-data">Installing Demo Data</a></li>
                    <li><a href="#plugin-widgets">Working with plugin widgets</a></li>
                    <li><a href="#customization">How to customize widgets output</a></li>

                    <li><a href="#heading-widget">Heading Widget</a></li>
                    <li><a href="#services-widget">Services Widget</a></li>
                    <li><a href="#team-members">Team Members</a></li>
                    <li><a href="#statistics-widgets">Statistics Widgets</a></li>
                    <li><a href="#testimonials-widgets">Testimonials Widgets</a></li>
                    <li><a href="#posts-carousel">Posts Carousel</a></li>
                    <li><a href="#carousel-widget">Carousel Widget</a></li>
                    <li><a href="#grid-widget">Posts Grid</a></li>
                    <li><a href="#clients-widget">Clients</a></li>
                    <li><a href="#pricing-table">Pricing Table</a></li>
                    <li><a href="#button-widget">Buttons</a></li>
                    <li><a href="#icon-list">Icon List</a></li>
                    <li><a href="#hero-header">Hero Header</a></li>
                    <li><a href="#tabs-accordions">Tabs and Accordions</a></li>
                    <li><a href="#image-slider">Image Slider – <span class="pro-feature">Pro!</span></a></li>
                    <li><a href="#image-video-gallery">Image/Video Gallery – <span class="pro-feature">Pro!</span></a></li>
                    <li><a href="#image-video-carousel">Image/Video Carousel – <span class="pro-feature">Pro!</span></a></li>
                    <li><a href="#faq-widget">FAQ Widget - <span class="pro-feature">Pro!</span></a></li>
                    <li><a href="#features-widget">Features Widget - <span class="pro-feature">Pro!</span></a></li>

                </ul>
                <h3 id="getting-started">Getting Started<a class="back-to-top" href="#panel"><span
                            class="dashicons dashicons-arrow-up-alt2"></span> Back to top</a></h3>
                <p>Thanks for choosing our plugin for SiteOrigin widgets. This help file aims to provide you with all the information you need to make the best use of this powerful plugin. The aim of the plugin to make the task of building a website effortless and pleasurable. Towards that end, we have built a number of widgets most commonly used across most of the websites of small businesses, corporates, design agencies, freelancers, artists etc.</p>
<p>Do follow the steps below to get started - </p>
                <ol>
                    <li>Install and activate the <strong>required plugin</strong> <a
                            href="https://wordpress.org/plugins/so-widgets-bundle/" rel="nofollow" target="_blank">SiteOrigin
                            Widgets
                            Bundle</a>.
                    </li>
                    <li><strong>Make sure you deactivate the free plugin</strong> <a href="https://wordpress.org/plugins/livemesh-siteorigin-widgets/" rel="nofollow">Livemesh SiteOrigin
                            Widgets</a> upon installing the premium version.
                    </li>
                    <li>Unzip the downloaded livemesh-siteorigin-widgets-pro.zip file and upload to the <code>/wp-content/plugins/</code>
                        directory or upload the plugin zip with the help of Plugins→Installed Plugins→Add New button.<br>
                        Activate the plugin through the 'Plugins' menu in WordPress. If you are viewing this help page
                        in WordPress admin under Livemesh Widgets→Documentation, you have already activated the plugin.
                    </li>
                    <li>Enable all the widgets you need from <strong> <a
                                href="<?php echo admin_url() . 'plugins.php?page=so-widgets-plugins'; ?>"
                                target="_blank"><?php echo __('Plugins → SiteOrigin Widgets', 'livemesh-so-widgets') ?></a></strong>.
                        Alternatively you can
                        visit <strong> <a href="<?php echo admin_url() . 'admin.php?page=livemesh_so_widgets'; ?>"
                                          target="_blank"><?php echo __('Livemesh Widgets→Settings', 'livemesh-so-widgets') ?></a></strong>
                        and check the box 'Activate all plugin widgets' option - that
                        will activate all of the widgets part of the plugin. The widgets can be selectively deactivated
                        later in <strong> <a href="<?php echo admin_url() . 'plugins.php?page=so-widgets-plugins'; ?>"
                                             target="_blank"><?php echo __('Plugins → SiteOrigin Widgets', 'livemesh-so-widgets') ?></a></strong>.
                    </li>
                    <li>If you need page builder functions, install and activate the <strong>optional plugin</strong> <a
                            href="https://wordpress.org/plugins/siteorigin-panels/" rel="nofollow" target="_blank">Page
                            Builder by
                            SiteOrigin</a>. To get most of this plugin, we highly recommend you install the page
                        builder.
                    </li>
                    <li>If you plan to build a portfolio site and plan to use Posts Grid widget for the same, install
                        and activate the optional plugin <a
                            href="https://wordpress.org/plugins/portfolio-post-type/" rel="nofollow" target="_blank">Portfolio
                            Post Type</a>.
                        The portfolio examples of Posts Grid widget is built using custom post type registered by
                        this plugin.
                    </li>
                    <li>Optionally, if you have <a href="https://www.livemeshthemes.com/products/livemesh-siteorigin-widgets-pro/" title="Livemesh SiteOrigin Widgets Pro" target="_blank">premium version</a> of the plugin installed, you can import the sample data
                        that replicates the demo site for you by importing the
                        file sample-data.xml file located in the plugin directory. The import option is available under
                        <strong> <a href="<?php echo admin_url() . 'import.php'; ?>"
                                    target="_blank"><?php echo __('Tools→Import', 'livemesh-so-widgets') ?></a></strong>
                        in WordPress admin.
                    </li>
                </ol>

                <hr>
                <h3 id="install-plugins">Installing Recommended/Required Plugins<a class="back-to-top"
                                                                                   href="#panel"><span
                            class="dashicons dashicons-arrow-up-alt2"></span> Back to top</a></h3>
                <p>Below is a list of recommended plugins to install that will help you get the most out of this plugin.
                    Although many of these plugins are optional, we recommend that you install these popular plugins if
                    you plan to install the demo data and get most out of this plugin. The demo site and the sample data
                    provided with the <a href="https://www.livemeshthemes.com/products/livemesh-siteorigin-widgets-pro/" title="Livemesh SiteOrigin Widgets Pro" target="_blank">premium version</a> of the plugin utilizes all of these plugins including the
                    SiteOrigin Page Builder and the Portfolio Post type plugin.</p>
                <p>These plugins are also listed in the Plugins tab of this help file under Livemesh Widgets →
                    Documentation, and you can install the plugins directly from there.</p>
                <ul>
                    <li><strong>SiteOrigin Widgets Bundle</strong> is a powerful framework for building WordPress
                        widgets with support for advanced forms, unlimited colors and 1500+ icons. Widgets built using
                        this framework can be used in a page builder page or any widgetized area of your site like the
                        sidebar or footer.
                        <p>All of the widgets part of Livemesh SiteOrigin Widgets plugin were created using this
                            framework and hence <strong>this plugin must be installed and activated on the site for this plugin
                            to function</strong>.</p>
                        <p><a href="https://wordpress.org/plugins/so-widgets-bundle/" target="_blank">More about SiteOrigin Widgets
                                Bundle →</a></p></li>
                    <li><strong>SiteOrigin Page Builder</strong> is the most popular page builder plugin for WordPress.
                        It makes it easy to create responsive column based content, using WordPress widgets including
                        those created by Livemesh SiteOrigin widgets plugin. All of the pages of our demo site for
                        the plugin have been built using this page builder. <strong>You should install and activate this plugin
                        if you plan to replicate the plugin demo site by importing the sample data provided</strong>.
                        <p><a href="https://wordpress.org/plugins/siteorigin-panels/" target="_blank">More about SiteOrigin Page Builder
                                →</a></p></li>
                    <li><strong>Portfolio Post Type</strong> is a free plugin that registers a custom post type for
                        portfolio items. It also registers separate portfolio taxonomies for tags and categories. The
                        Portfolio grid instances showcased on our demo site was built using custom post types registered
                        by Portfolio Post Type plugin.
                        <p><a href="https://wordpress.org/plugins/portfolio-post-type/" target="_blank">More about Portfolio Post Type
                                →</a></p>
                    </li>
                </ul>
                <hr>
                <h3 id="demo-data">Installing Demo Data<a class="back-to-top" href="#panel"><span
                            class="dashicons dashicons-arrow-up-alt2"></span> Back to top</a></h3>
                <p>If you have <a href="https://www.livemeshthemes.com/products/livemesh-siteorigin-widgets-pro/" title="Livemesh SiteOrigin Widgets Pro" target="_blank">premium version</a> of the plugin installed, you can install the demo data to replicate the
                    plugin demo site to get a head start on building your site. Installing demo data reduces the
                    learning curve associated with trying out the powerful widgets part of this plugin.</p>
                <p>The sample data imports the pages part of the demo site. Once you are done with playing around the
                    widgets and feel comfortable creating/configuring them, you can delete the unwanted pages/posts that
                    you may not need.</p>
                <p>Prior to installing demo data, make sure you have recommended plugins installed as mentioned above in
                    the <a href="#install-plugins">Recommended Plugins</a> section.</p>
                <p>The demo site <strong>sample-data.xml</strong> file is located in the plugin directory created after unzipping the premium bundle. Once you have access to the sample data file, you can install the demo site by visiting <strong> <a href="<?php echo admin_url() . 'import.php'; ?>" target="_blank"><?php echo __('Tools→Import', 'livemesh-so-widgets') ?></a></strong> and click Choose File. Upload the xml file and follow the steps to
                    import. When the demo data is finished importing, you will have many pages that contain widgets
                    configured in them. </p>


                <hr>
                <h3 id="plugin-widgets">Working with plugin widgets<a class="back-to-top" href="#panel"><span
                            class="dashicons dashicons-arrow-up-alt2"></span> Back to top</a></h3>

                <ul>
                    <li>If you plan to use the <a href="https://wordpress.org/plugins/siteorigin-panels/" target="_blank">SiteOrigin Page Builder</a> to build your site (recommended) and new to its
                        functions, make sure you checkout the <a
                            href="https://siteorigin.com/page-builder/documentation/"
                            title="SiteOrigin Page Builder Documentation" target="_blank">documentation of the page builder</a> before
                        starting to use this plugin.
                    </li>

                    <li>As mentioned earlier, Livemesh SiteOrigin Widgets plugin is built on a powerful widget builder framework of <a href="https://wordpress.org/plugins/so-widgets-bundle/"
                                  title="SiteOrigin Widgets Bundle" target="_blank">SiteOrigin Widgets Bundle</a> plugin. If you need
                        more information about this plugin or need help with it, go through the <a
                            href="https://siteorigin.com/widgets-bundle/"
                            title="SiteOrigin Widgets Bundle documentation" target="_blank">SiteOrigin Widgets Bundle documentation</a>.
                    </li>

                    <li>Once the Livemesh SiteOrigin Widgets plugin is activated, you should see a menu item <strong> <a href="<?php echo admin_url() . 'admin.php?page=livemesh_so_widgets'; ?>"
                                            target="_blank"><?php echo __('Livemesh Widgets', 'livemesh-so-widgets') ?></a></strong> in WordPress admin with three sections - Settings, Documentation and <strong> <a href="<?php echo admin_url() . 'admin.php?page=livemesh_so_widgets_pro_upgrade'; ?>"
                                                                                                                                                                                                                      target="_blank"><?php echo __('Upgrade to Pro', 'livemesh-so-widgets') ?></a></strong>.
                        <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/plugin-settings.png" alt="SiteOrigin Widgets Settings"></p>

                        <p><strong> <a href="<?php echo admin_url() . 'admin.php?page=livemesh_so_widgets'; ?>"
                                       target="_blank"><?php echo __('Livemesh Widgets→Settings', 'livemesh-so-widgets') ?></a></strong> - The settings screen for the plugin is self-documenting with minimal
                        options. If you need the plugin widgets auto activated by default, you can check the option
                            <strong>'Activate all plugin widgets'</strong> option. This will avoid you having to go to  <a href="<?php echo admin_url() . 'plugins.php?page=so-widgets-plugins'; ?>"
                                                                                                              target="_blank"><?php echo __('Plugins → SiteOrigin Widgets', 'livemesh-so-widgets') ?></a> screen (see below) and activate the plugin widgets individually. Once auto activate is enabled via
                        Settings, you can still deactivate widgets by reaching Plugins→SiteOrigin Widgets.</p>

                    </li>

                    <li><strong> <a href="<?php echo admin_url() . 'plugins.php?page=so-widgets-plugins'; ?>"
                                    target="_blank"><?php echo __('Plugins → SiteOrigin Widgets', 'livemesh-so-widgets') ?></a></strong> - This is the admin page for deactivation and
                        activation of all widgets created using the framework built by SiteOrigin Widgets Bundle. If you
                        have the option 'Activate all plugin widgets' option in <a href="<?php echo admin_url() . 'admin.php?page=livemesh_so_widgets'; ?>"
                                                                                   target="_blank"><?php echo __('Livemesh Widgets→Settings', 'livemesh-so-widgets') ?></a> screen above unchecked, you
                        will need to activate each of the widgets defined by the plugin.

                        <p>To look for widgets defined by
                        the Livemesh SiteOrigin Widgets plugin, search for widgets starting with name Livemesh in the
                        SiteOrigin Widgets Bundle screen.</p>
                        <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/livemesh-widgets2.jpg" alt="SiteOrigin Page Builder Widgets"></p>
                    </li>

                    <li>Once a SiteOrigin widget is activated (or auto activated), the widgets are available in
                        <strong><a href="<?php echo admin_url() . 'widgets.php'; ?>"
                           target="_blank"><?php echo __('Appearance→Widgets', 'livemesh-so-widgets') ?></a></strong> screen for drag and drop into widgetized areas defined by the theme
                        activated on your site.
                        <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/livemesh-widgets.jpg" alt="Sidebar Widgets from SiteOrigin"></p>
                    </li>

                    <li>The activated widgets also become available for drag and drop in the SiteOrigin Page builder. In
                        the Page edit window, click on the <strong>'Page Builder'</strong> tab to bring up the page builder controls on
                        the page edit screen.
                        <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/page-builder-screen.png" alt="SiteOrigin Page Builder Screen"></p>
                    </li>

                    <li>To add a Livemesh widget, just click on the <strong>'Add Widget'</strong> button to bring up the 'Add New Widget'
                        popup screen of the page builder. The plugin widgets are grouped under <strong>'Livemesh SiteOrigin
                        Widgets'</strong> tab on the left. Click on a widget listed on the right closes the popup and adds the
                        widget to the page builder.
                        <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/livemesh-widgets3.jpg" alt="Page Builder Widgets from SiteOrigin"></p>
                    </li>

                    <li>Hovering over the widget added to the page builder, you can view the Edit link. Clicking the
                        widget also brings up the edit/configure screen of a widget. <strong>Most of the widget options are
                        self-documented</strong> but additional help is provided in the below sections for each of the Livemesh
                        widgets.

                        <p>Once the data required for configuring a widget is entered, you can preview the changes by
                        clicking on the <strong>'Preview'</strong> button.</p>

                        <p>Click on the <strong>'Done'</strong> button once the required data is provided for the widget and you are done
                        with previewing.</p>
                        <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/widget-edit-screen.png" alt="SiteOrigin Widget Edit Window"></p>

                    </li>

                    <li>After you hit the <strong>Update</strong> or <strong>Publish</strong> button on the page,the widget is then ready for viewing on
                        the frontend page.
                        <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/widget-rendered.png" alt="SiteOrigin Widget Rendered"></p>

                    </li>

                </ul>


                <hr>
                <h3 id="customization">How to customize output generated by widgets<a class="back-to-top" href="#panel"><span
                                class="dashicons dashicons-arrow-up-alt2"></span> Back to top</a></h3>
                <p>The strength of this plugin over many others is that this plugin lets you customize almost any piece of HTML
                    generated by an widget part of the plugin. You can move around or modify the output elements to suit your
                    needs - change HTML tags, change sequence of information (e.g., place image at the bottom of the post in a
                    grid), add new CSS classes of your own, add branding material, delete unneeded output element (e.g. remove
                    post meta information), modify information (e.g., truncate description or excerpt generated) etc. with the
                    help of templates and filters provided by the plugin. </p>
                <p>Following are the two ways to customize the output of an widget part of the plugin.</p>
                <ul>
                    <li><p><strong>Filters</strong> are hooks that are provided at almost every customization opportunity that presents itself
                            as the widget renders the widgets by parsing the settings input by the user in the SiteOrigin editor. <strong>About 200+ filters have been
                                provided</strong> to help users customize almost any information output by the widgets of this plugin.</p>
                        <p>To locate the filters, the users are encouraged to have a look at the PHP code located in the <code>tpl/default.php</code> file of
                            the respective widget folder located in <code>includes/widgets/</code> folder.</p>

                        <p><a href="https://docs.presscustomizr.com/article/26-wordpress-actions-filters-and-hooks-a-guide-for-non-developers"
                              target="_blank">More about Filters - a guide for non-developers →</a></p>
                    </li>
                    <li><p><strong>Templates</strong> let you modify the HTML generated by an widget by placing an appropriately
                            named PHP file in a folder named 'siteorigin-widgets' in your child theme (or parent theme).</p>
                        <p>Following are the names of the files that plugin looks for to customize the <i>respective</i> widget
                            rendering - <code> clients.php, carousel.php, heading.php, odometers.php, piecharts.php, posts-grid.php,
                                posts-carousel.php, pricing-table.php, services.php, stats-bars.php, team-members.php, testimonials.php,
                                testimonials-slider.php, accordion.php, button.php, faq.php, features.php, gallery.php, gallery-carousel.php,
                                icon-list.php, image-slider.php, posts-block.php, services-carousel.php, slider.php, tabs.php. </code></p>
                        <p>Once a particular template file is found in the theme folder, the corresponding widget rendering code is
                            replaced with that in the template PHP file. The template files are provided with <code>$settings</code> variable to
                            help read the widget settings and then display the output. The current output generated by the plugin widget can
                            be seen in the <code>tpl/default.php</code> file of the widgets folder located in <code>includes/widgets/</code> folder.<strong> In
                                most cases, it is recommended to copy over the rendering code from the <code>tpl/default.php</code> file to the template
                                file and modify the same to achieve the desired customization</strong>. A basic knowledge of PHP and HTML is all that is
                            required to customize an widget.</p>
                    <li><strong>Module Templates </strong>(<span class="pro-feature">Pro Feature</span>) are PHP files which can be placed in the <code>siteorigin-widgets/modules/</code> of
                        the child theme to customize the posts grid item information part of posts grid/block widgets output by module files located in <code>includes/blocks/modules/</code> folder
                        of the premium version of the plugin. The template files should be named as <code>module-1.php, module-2.php .... , module-12.php, module-13.php</code> to
                        match the corresponding files located in the <code>includes/blocks/modules</code> folder. Only HTML part of <code>render()</code> function needs to
                        be customized in the module template files. The users are recommended to copy over the rendering code part of this function to the corresponding
                        template file and then customize it to their needs. </code>
                    </li>
                </ul>
                <p>The below sections provide help on each of the widgets built as part of Livemesh SiteOrigin Widgets
                    plugin.</p>

                <hr>
                <h3 id="heading-widget">Heading Widget<a class="back-to-top" href="#panel"> Back to top</a></h3>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/heading-widget2.png" alt="Heading Widget Rendered"></p>

                <p>The heading widget is perhaps the most frequently used widget on a page since it displays a heading
                    at the top of a section.</p>
                <p>It comes in three styles – Style 1, Style 2 and Style 3 which allow variations of headings displayed
                    in various sections.</p>

                <p>The heading consists of the main heading text which is renders as one of the HTML heading tags on the
                    frontend. Additionally, a short text is displayed below the heading and some of the heading styles
                    allow you to input a subtitle which is usually displayed on top of the main heading title.</p>
                <p>You can choose to align the heading left, right or center with center being the default
                    alignment.</p>
                <p>The <a href="https://www.livemeshthemes.com/products/livemesh-siteorigin-widgets-pro/" title="Livemesh SiteOrigin Widgets Pro" target="_blank">premium version</a> of the plugin allows selection of a custom font for the heading title. You may
                    choose one of 500+ custom fonts hosted in the Google Fonts library. By default, the heading font
                    used by the theme is used for main heading title.</p>

                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/heading-widget-edit.png" alt="Heading Widget Edit Window"></p>

                <hr>
                <h3 id="services-widget">Services Widget<a class="back-to-top" href="#panel"> Back to top</a></h3>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/services-widget2.png" alt="Services Widget"></p>

                <p>Many agencies, freelancers, corporates, products/apps require capturing the services provided by the
                    agency or the features of a product. The services widget is designed to help users capture these
                    services or features in a multi-column grid.</p>
                <p>The widget supports about 3 different styles (with 2 additional styles in <a href="https://www.livemeshthemes.com/products/livemesh-siteorigin-widgets-pro/" title="Livemesh SiteOrigin Widgets Pro" target="_blank">premium version</a> of the
                    plugin) and each of these styles can be customized further by choosing the type of icon desired to
                    represent the service – a font icon or an custom image icon. While the choice of font icons is huge
                    in number and perhaps sufficient for most common services, the icon images can help present the
                    unique nature of the services offered.</p>
                <p>Each of the service requires you to input a title for the service/feature and a short description of
                    the service offered or the product feature. Additionally, each service allows you to enter a font
                    icon or an icon image file to represent that service.</p>
                <p>The <a href="https://www.livemeshthemes.com/products/livemesh-siteorigin-widgets-pro/" title="Livemesh SiteOrigin Widgets Pro" target="_blank">premium version</a> of the plugin allows you specify a custom font size, font color and and hover
                    color for the font icon along with providing two additional styles of services/features.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/services-widget-edit.png" alt="Services Widget Edit Window"></p>

                <p>Services widget supports the following options –</p>
                <ul>
                    <li><strong>Columns per row</strong> – Number of services to display per row of services.</li>
                    <li><strong>Icon Custom Size</strong> – If the icon chosen for services is icon font, you can
                        specify a custom size for the font icon in pixels.
                    </li>
                    <li><strong>Icon Custom Color</strong> – Specify a custom color for the font icon.</li>
                    <li><strong>Icon Custom Hover Color</strong> – Specify a custom hover color for the font icon.</li>
                </ul>


                <hr>
                <h3 id="team-members">Team Members<a class="back-to-top" href="#panel"> Back to top</a></h3>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/team-member2.jpg" alt="Team Members Widget"></p>

                <p>This widget provides an easy way to capture the team members of your organization or an agency. The
                    details captured include team member name, position, a short description and the email plus social
                    profile of the individual team members.</p>
                <p>Two different styles are provided with more styles planned in the <a href="https://www.livemeshthemes.com/products/livemesh-siteorigin-widgets-pro/" title="Livemesh SiteOrigin Widgets Pro" target="_blank">premium version</a> of the plugin. Most
                    of the styles display the team members in a multi-column grid. The option to specify the number of
                    columns is provided that helps to control the number of team members displayed per row of the team
                    members.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/team-members-edit.png" alt="Team Members Widget Edit Window"></p>

                <hr>
                <h3 id="statistics-widgets">Statistics Widgets<a class="back-to-top" href="#panel"> Back to top</a></h3>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/statsbars-piecharts.png" alt="Statistics Widgets"></p>

                <p>The plugin features a number of widgets that help display statistical information in the form of
                    odometers, piecharts and stats bars. The <a href="https://www.livemeshthemes.com/products/livemesh-siteorigin-widgets-pro/" title="Livemesh SiteOrigin Widgets Pro" target="_blank">premium version</a> of the plugin also includes a countdown
                    widget that displays a countdown timer for planned events such as product launches or website going
                    live.</p>
                <p>Most of these widgets are designed to animate the display of the statistical information or numbers
                    when the users scroll down to the section containing the widget.</p>
                <p><strong>Odometers</strong></p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/odometers2.png" alt="Odometer Widget"></p>

                <p>This widget displays one or more animated odometer statistics in a multi-column grid. This number
                    statistic requires a start and an end value with a title and icon providing the information about
                    what the number represents – like a download number or number of products sold or customers
                    gained.</p>
                <p>The widget animates from the start value to the end value when the user scrolls down to the section.
                    You can control the number of such odometers displayed per row.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/odometers-edit.png" alt="Odometer Edit Window"></p>

                <p><strong>Stats Bars</strong></p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/statsbars-piecharts.png" alt="Stats Bar Piechart Widgets"></p>

                <p>Stats Bars capture percentage statistics like coverage area, skills gained, survey findings, usage
                    statistics etc. that typically require bar charts to represent them. Each statistical item requires
                    a percentage value, a title describing the number. The user can choose to display the bar charts in
                    multiple or single color with the help of color choice available with each value input.</p>
                <p>The widget animates from the zero to the percentage value set for the item when the user scrolls down
                    to the section containing the widget. The bars are placed one below the other horizontally.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/stats-bars-edit.png" alt="Stats Bar Widget Edit Window"></p>

                <p><strong>Piecharts</strong></p>
                <p>Piecharts provide an alternative way to display percentage stats. When the users scrolls down and the
                    chart becomes visible, the widget animates from zero to percentage value provided for the statistic.
                    A bar of user chosen color moves along a track to display the percentage information. An option to
                    specify the number of charts displayed per row is provided.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/piecharts-edit.png" alt="Piechart Widget Edit Window"></p>

                <p><strong>Countdown&nbsp; - <span class="pro-feature">Pro!</span></strong></p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/countdown.png" alt="Countdown Widget"></p>

                <p>This widget displays a countdown timer for an end date and time specified by the user. This is like a
                    clock ticking to signal the arrival of an event planned for a future date. The user just provides
                    the end date time, a label for the event and widget displays the time remaining in days, hours,
                    minutes and seconds, with the timer updated every second on the page.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/countdown-edit.png" alt="Countdown Widget Edit Window"></p>

                <hr>
                <h3 id="testimonials-widgets">Testimonials Widgets<a class="back-to-top" href="#panel"> Back to top</a></h3>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/testimonials.png" alt="Testimonials Widget"></p>

                <p>The plugin features two widgets for capturing testimonials received for your product or business or
                    services. Most agencies, corporates, small businesses, freelancers and products/apps require
                    testimonials to displayed prominently on the site to help convert visitors to customers. The two
                    widgets provided are elegantly designed to achieve greater conversion rate.</p>
                <p>The testimonials information include details about the person/company endorsing the product/service;
                    details like name, company, website of this person/organization along with an image representing
                    this person/entity.</p>
                <p><strong>Testimonials</strong></p>
                <p>The regular <strong>testimonials widget</strong> displays multiple testimonials in a row with the
                    user having the option to specify the number of items per row. This is useful if you need a large
                    number of testimonials to be visible instantly when the user scrolls down to view the testimonials
                    section.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/testimonials-edit.png" alt="Testimonials Widget Edit Window"></p>

                <p><strong>Testimonials Slider</strong></p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/testimonials2.png" alt="Testimonials Slider Widget"></p>
                <p>The <strong>testimonials slider widget</strong> is useful for display of endorsements/recommendations
                    with large amount of text for each testimonial. The slider displays the testimonials as a slideshow
                    with multiple widget options provided to control/customize this slideshow – options like speed of
                    switching, speed of animation, whether to pause the slideshow on hover, controls needed for manual
                    navigation by the user etc. The slider is completely responsive and touch swipe controls available
                    for easy navigation on smartphones/tablets.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/testimonials-slider-edit.png" alt="Testimonials Slider Edit Window"></p>

                <hr>
                <h3 id="posts-carousel">Posts Carousel<a class="back-to-top" href="#panel"> Back to top</a></h3>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/posts-carousel.jpg" alt="Post Carousel Widget"></p>

                <p>The responsive carousel helps display posts or any custom post types like your portfolio entries with
                    controls available for easy navigation of the items displayed. The widget features a Posts Query
                    window to help choose posts or custom posts to display. This powerful tool has number of fields to
                    control what gets displayed and in what order with an additional field available to provide query
                    arguments explained in the <a href="https://codex.wordpress.org/Class_Reference/WP_Query">codex
                        page</a>.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/posts-carousel-edit3.png" alt="Post Carousel Build Query Tool"></p>

                <p>The Posts Query tool has the following options for filtering posts –</p>
                <ol>
                    <li><strong>Post Type</strong> – Select the custom post type that you need the widget for. By
                        default “All” is selected.
                    </li>
                    <li><strong>Post In</strong> – This field enabled you to specify the post ids of the posts or custom
                        post types you would like to include in your widget. If you do not know the IDs, you can click
                        on the ‘Select Posts’ button to bring up popup that can be used to search and select the
                        specific posts of the post type selected above.
                    </li>
                    <li><strong>Taxonomies</strong> – If you need to filter the posts by specific category or taxonomy
                        terms, you can specific the same here. The field autocompletes the terms you type in here.
                    </li>
                    <li><strong>Date Range</strong> – Specific a date range for filtering the posts – only those posts
                        published during this period will be chosen for display by the widget.
                    </li>
                    <li><strong>Order By</strong> – Lets you decide on how you want the posts to be ordered – by
                        Published Date, by Post ID, by Menu Order etc. and whether you want the ordering by Ascending or
                        Descending.
                    </li>
                    <li><strong>Posts Per Page</strong> – Set the number of posts you wish you display in the widget. If
                        the widget does not support pagination, the number of posts chosen by the limited by the number
                        specified here. This is also the number of posts to display per page when the widget supports
                        pagination as is the case with Posts Grid widget. Choosing the value zero makes the widget
                        all the selected posts.
                    </li>
                    <li><strong>Sticky Posts</strong> – Tell the widget to ignore, exclude or include the sticky posts.
                    </li>
                    <li><strong>Additional</strong> – You may specify any additional query parameters here as documented
                        in the <a href="https://codex.wordpress.org/Class_Reference/WP_Query">codex page</a>. These
                        parameters will be applied in addition to the query parameters generated from the values
                        specified in the above fields.
                    </li>
                </ol>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/posts-carousel-edit.png" alt="Post Carousel Edit Window"></p>

                <p>The posts carousel has numerous other options to control the display of posts or custom post types.
                    Some of these are –</p>
                <ul>
                    <li><strong>Choose Taxonomy to display info</strong> – When the post info is displayed, the specific
                        taxonomy you want the info to use. For example, choosing category will display category
                        information for a posts while choosing ‘post_tag’ would display the tag information for posts.
                    </li>
                    <li><strong>Link images to Posts</strong> – Make the images link to the posts or custom post types
                        they represent.
                    </li>
                    <li><strong>Display post titles</strong> – Checking this box will display post title below the
                        featured image for the posts or custom post type.
                    </li>
                    <li><strong>Display post excerpt/summary</strong> – Display summary information for the posts below
                        the featured image and post title.
                    </li>
                    <li><strong>Post Meta</strong> – Display post meta information like published date, author name,
                        taxonomy information below the posts. The specific taxonomy chosen above under “Choose Taxonomy
                        to display info” will be used for display taxonomy information.
                    </li>
                </ul>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/posts-carousel-edit2.png" alt="Post Carousel Widget Settings"></p>

                <p><strong>Carousel Settings</strong> – This section has options that control how the carousel is
                    displayed. Options include autoplay speed, gutter value between post items in various resolutions,
                    navigation controls for carousel, number of columns or items to display before making the user to
                    scroll for additional items etc.</p>
                <ul>
                    <li><strong>Prev/Next Arrows</strong> – Display navigation for the carousel.</li>
                    <li><strong>Show dot indicators for navigation</strong> – Display control navigation or pagination
                        controls for the carousel.
                    </li>
                    <li><strong>Autoplay</strong> – Display carousel as a slideshow.</li>
                    <li><strong>Autoplay speed in ms</strong> – The time between display of each page of images when
                        Autoplay option is enabled.
                    </li>
                    <li><strong>Autoplay animation speed in ms</strong> – The time taken for animation that moves the
                        carousel to next or previous page of items.
                    </li>
                    <li><strong>Pause on mouse hover</strong> – Pause the slideshow if the user has mouse hovered over
                        the carousel contents.
                    </li>
                    <li><strong>Columns per row</strong> – Number of gallery items visible at any given point of time
                        without scrolling.
                    </li>
                    <li><strong>Columns to scroll</strong> – With each scroll action – using the prev/next arrows or the
                        dotted navigation, specify the number of items to scroll for each invocation of the navigation
                        controls.
                    </li>
                    <li><strong>Gutter</strong> – The spacing in pixels between images/videos in the carousel.</li>
                </ul>

                <hr>
                <h3 id="carousel-widget">Carousel<a class="back-to-top" href="#panel"> Back to top</a></h3>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/generic-carousel.jpg" alt="Generic Carousel Widget"></p>

                <p>Livemesh Carousel is a generic carousel of custom HTML content of your choice. Possibilities are endless – image
                    carousels with textual content describing the images, video carousels, event carousels with link to
                    the events, a carousel of team of volunteers, a collection of books sold on Amazon etc.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/livemesh-carousel-edit.png" alt="Generic Carousel Widget Edit Window"></p>

                <p>If you need a carousel of custom content HTML of your choice, this widget helps achieve the same. For
                    the HTML content, you will need to provide your own custom CSS under Settings for the carousel.
                    While posts carousel helps you display carousel items derived from posts or custom post types, this
                    widget lets you display any well-formed HTML content as items in a carousel. You may use the
                    WordPress visual editor to construct the required content. </p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/livemesh-carousel-edit2.png" alt="Generic Carousel Settings Window"></p>

                <p>The section ‘Carousel Settings’ has options that control how the carousel is displayed. Options
                    include autoplay speed, gutter value between post items in various resolutions, navigation controls
                    for carousel, number of columns or items to display before making the user to scroll for additional
                    items etc. The carousel settings are explained in the help section above for Posts Carousel.</p>
                <hr>
                <h3 id="grid-widget">Posts Grid<a class="back-to-top" href="#panel"> Back to top</a></h3>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/livemesh-grid.jpg" alt="Posts Grid Widget"></p>

                <p>Perhaps the most popular and most important of all widgets part of all widgets part of this plugin,
                    Posts Grid helps you build a multi-column grid of posts or custom post types. The posts displayed
                    are filterable by taxonomy terms.</p>

                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/livemesh-grid2.jpg" alt="Posts Grid Widget"></p>
                <p>Using the Grid widget, you can construct a portfolio of your work/services/products. We recommend you
                    use the popular plugin – <a title="Portfolio Post Type Plugin"
                                                href="https://wordpress.org/plugins/portfolio-post-type/">https://wordpress.org/plugins/portfolio-post-type/</a>
                    for building a collection of portfolio entries. Once the portfolio entries are in place, make sure
                    you select Portfolio Post type under Post Type entry in Build Tools window as explained below.</p>

                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/livemesh-grid-pagination.jpg" alt="Posts Grid Pagination"></p>
                <p>The <a href="https://www.livemeshthemes.com/products/livemesh-siteorigin-widgets-pro/" title="Livemesh SiteOrigin Widgets Pro" target="_blank">premium version</a> of the plugin has support for pagination, lazy load with load more button and
                    lightbox option for images. The additional posts are loaded via AJAX when the user navigates through
                    the pages populated or when the user hits the Load More button.</p>

                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/livemesh-grid-loadmore.jpg" alt="Posts Grid AJAX Load More"></p>
                
                <p>The widget features a Posts Query window to help choose posts or custom posts to display. This
                    powerful tool has number of fields to control what gets displayed and in what order with an
                    additional field available to provide query arguments explained in the <a
                        href="https://codex.wordpress.org/Class_Reference/WP_Query">codex page</a>.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/build-posts-query-tool.jpg" alt="Build Post Query Tool"></p>

                <p>The Posts Query tool has the following options for filtering posts –</p>
                <ol>
                    <li><strong>Post Type</strong> – Select the custom post type that you need the widget for. By
                        default “All” is selected. To construct a portfolio of your work, choose ‘Portfolio’ as your
                        post type if you have the <a title="Portfolio Post Type"
                                                     href="https://wordpress.org/plugins/portfolio-post-type/">Portfolio
                            Post Type</a> activated and portfolio entries created.To construct a grid of blog posts,
                        choose Post as your Post Type.
                    </li>
                    <li><strong>Post In</strong> – This field enabled you to specify the post ids of the posts or custom
                        post types you would like to include in your widget. If you do not know the IDs, you can click
                        on the ‘Select Posts’ button to bring up popup that can be used to search and select the
                        specific posts of the post type selected above.
                    </li>
                    <li><strong>Taxonomies</strong> – If you need to filter the posts by specific category or taxonomy
                        terms, you can specific the same here. The field autocompletes the terms you type in here.
                    </li>
                    <li><strong>Date Range</strong> – Specific a date range for filtering the posts – only those posts
                        published during this period will be chosen for display by the widget.
                    </li>
                    <li><strong>Order By</strong> – Lets you decide on how you want the posts to be ordered – by
                        Published Date, by Post ID, by Menu Order etc. and whether you want the ordering by Ascending or
                        Descending.
                    </li>
                    <li><strong>Posts Per Page</strong> – Set the number of posts you wish you display in the widget. If
                        the widget does not support pagination, the number of posts chosen by the limited by the number
                        specified here.&nbsp;<strong>This is also the number of posts to display per page when the
                            widget supports pagination as is the case with Posts Grid widget.</strong> Choosing the
                        value zero makes the widget all the selected posts.
                    </li>
                    <li><strong>Sticky Posts</strong> – Tell the widget to ignore, exclude or include the sticky posts.
                    </li>
                    <li><strong>Additional</strong> – You may specify any additional query parameters here as documented
                        in the <a href="https://codex.wordpress.org/Class_Reference/WP_Query">codex page</a>. These
                        parameters will be applied in addition to the query parameters generated from the values
                        specified in the above fields.
                    </li>
                </ol>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/livemesh-grid-edit1.jpg" alt="Posts Grid Edit Window"></p>

                <p>The grid widget has numerous other options to control the display of posts or custom post types. Some
                    of these are –</p>

                <ul>
                    <li><strong>Choose Taxonomy to display and filter on</strong> – The terms of this taxonomy chosen
                        will be used for filtering the posts if ‘Filterable’ option is checked. When the post info is
                        displayed, the specific taxonomy you want the info to use. For example, choosing category will
                        make the posts filterable on category while choosing ‘post_tag’ would make the posts filterable
                        by post tags instead of category.
                    </li>
                    <li><strong>Choose a Layout for the grid</strong> – You may choose Masonry or Fit Rows layout for
                        the grid.
                    </li>
                    <li><strong>Pagination options (<span class="pro-feature">Pro!</span>)</strong>– Choose pagination type or choose None if no
                        pagination is desired. <strong>If you choose Paged or Load More option, make sure the ‘Post per
                            page’ field value is set in the Build Query window to control number of posts to display per
                            page.</strong><br>
                        – If the Pagination option chosen is Paged, the grid displays a paginated grid of entries with
                        links to various pages displayed at the bottom of the grid, provided sufficient number of
                        entries of this post type has been created by the user and the Posts Per Page value is set to a
                        lower value than the number of entries created.<br>
                        – If the Pagination option chosen is Load More, the grid displays a Load More button below the
                        grid of posts/portfolio with an option count of remaining posts/post types yet to loaded. When
                        the users hits the Load More button, a number of posts/portfolio entries equal to ‘Posts per
                        Page’ value will be lazy loaded into the widget via AJAX. Upon loading all of the remaining
                        entries, the Load More button is no longer shown.<br>
                        Do check the option ‘Display count of posts yet to be loaded with the Load More button’ to
                        display the remaining post count with the Load More button.
                    </li>
                    <li><strong>Link images to Posts/Portfolio</strong> – Make the post images link to the posts or
                        custom post types they represent.
                    </li>
                    <li><strong>Enable Lightbox Gallery (<span class="pro-feature">Pro!</span>)</strong>– If checked, the images part of the grid entries
                        will have a lightbox option enabled to display a gallery of post images in a popup display.
                    </li>
                    <li><strong>Display post/project titles</strong> – Checking this box will display post/portfolio
                        entry title below the featured image for the posts or custom post type.
                    </li>
                    <li><strong>Display post/portfolio excerpt/summary</strong> – Display summary information for the
                        posts/portfolio items below the featured image and post title.
                    </li>
                    <li><strong>Post Meta</strong> – Display post meta information like published date, author name,
                        taxonomy information below the posts. The specific taxonomy chosen above under “Choose Taxonomy
                        to display and filter on” will be used for display taxonomy information.
                    </li>
                    <li><strong>Columns per row</strong> – The number of posts/portfolio items to display in each row on
                        desktop.
                    </li>
                    <li><strong>Gutter options</strong> – The spacing in pixels between each entry in the grid. If you
                        need a packed layout, specify zero here.
                    </li>
                </ul>


                <hr>
                <h3 id="clients-widget">Clients<a class="back-to-top" href="#panel"> Back to top</a></h3>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/clients-widget.jpg" alt="Clients Widget Edit Window"></p>

                <p>Whether you are freelancer or run a small business, agency or represent a big corporate house, you
                    have a list of clients that you have worked with. This widget lets you create a list of these
                    clients with banner images representing these clients.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/clients-edit.jpg" alt="Clients Widget Edit Window"></p>

                <p>For each of the client, you provide a client name, a banner image for the client and a URL for their
                    website. The client name is shown on user hovering over the banner image and title text is
                    optionally a link pointing to the website of the client, if that link is provided by the user.</p>
                <p>The collection of clients will be displayed in a multi-column grid. The ‘Columns per Row’ option lets
                    you control the number of client entries per row of clients displayed.</p>

                <hr>
                <h3 id="pricing-table">Pricing Table<a class="back-to-top" href="#panel"> Back to top</a></h3>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/pricing-plan.png" alt="Pricing Plan Widget"></p>

                <p>The pricing plans offered by your business can be captured with pricing plan widget. The pricing
                    plans are displayed in a multi-column grid.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/pricing-plan-edit.jpg" alt="Pricing Plan Edit Window"></p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/pricing-plan-edit2.jpg" alt="Pricing Plan Edit Window"></p>

                <p>For each of the pricing plan, provide the following information –</p>
                <ul>
                    <li><strong>Pricing Plan Title</strong> – The title for the pricing plan like Standard, Premium,
                        Developer etc.
                    </li>
                    <li><strong>Tagline Text</strong> – Provide any subtitle or taglines like “Most Popular”, “Best
                        Value”, “Best Selling”, “Most Flexible” etc. that you would like to use for this pricing plan.
                        Usually displayed above the pricing plan title.
                    </li>
                    <li><strong>Image</strong> – Optional image or icon to represent the pricing plan.</li>
                    <li><strong>Price Tag</strong> – This is where you specify the actual price tag for the plan along
                        with the currency. HTML is allowed.
                    </li>
                    <li><strong>Text for Pricing Link/Button</strong> – Specify the text for the link or a button
                        displayed at the bottom of the pricing plan. This link takes the user to the purchase page.
                    </li>
                    <li><strong>URL for the Pricing link/button</strong> – Provide the target URL for the link or the
                        button shown for this pricing plan. This link takes the user to the purchase page. Check the
                        option ‘Open Button URL in a new window’ if you need the link to open the target page in a new
                        tab or window of the browser.
                    </li>
                    <li><strong>Highlight Pricing Plan</strong> – Specify if you want to highlight the pricing plan.
                        This would be most likely plan your user would choose to sign up for.
                    </li>
                    <li><strong>Pricing Columns per row</strong> – The number of pricing plans to display per row of
                        plans. Most businesses choose to fit in all of their plans into a single row.
                    </li>
                </ul>


                <hr>
                <h3 id="button-widget">Buttons<a class="back-to-top" href="#panel"> Back to top</a></h3>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/buttons.png" alt="Buttons Widget"></p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/buttons2.png" alt="Buttons Widget"></p>

                <p>The plugin lets you create buttons of multiple colors that you would use in your site. The supported
                    colors are Orange, Blue, Teal, Cyan, Green, Pink, Black, Red, Transparent and Semi Transparent (for
                    dark backgrounds). You can choose a custom color and custom hover color too for the button to create
                    a button of your chosen color.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/button-edit.jpg" alt="Button Widget Edit Window"></p>

                <p>You may choose to create a button of Default color which is the color derived from the Theme Color
                    set in the plugin options.</p>
                <p>Additional options provided are button size, rounded and alignment – center, right, left and
                    None.</p>
                <p>You can choose to display an icon along with the button text. The icon can be a icon font or an
                    image.</p>
                <p>The widget options are mostly self-explanatory and you can view a live preview of the buttons <a
                        title="Livemesh SiteOrigin Button Widget Demo"
                        href="https://livemeshwp.com/siteorigin-widgets/icon-lists-buttons/">here</a>.</p>


                <hr>
                <h3 id="icon-list">Icon List<a class="back-to-top" href="#panel"> Back to top</a></h3>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/icon-lists.png" alt="Icon Lists Widget"></p>

                <p>The icon list widget is extremely useful for creating a list of icons with optional links to sites or
                    pages that the icons represent. Examples include social media profiles, icon lists representing
                    payment options or download platforms or a quick summary of services.</p>
                <p>Each of the icons part of a list have a title, optional target URL and the icon itself can be a font
                    icon or an custom image. The title for the icon is displayed as a tooltip on mouse hover.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/icon-list-edit.jpg" alt="Icon Lists Widget Edit Window"></p>

                <p>Following options are available –</p>
                <ul>
                    <li><strong>Icon/Image size in pixels</strong> – Custom size of the icons displayed.</li>
                    <li><strong>Icon color</strong> – If the icons chosen are font icons, you may specify a custom color
                        for the icons.
                    </li>
                    <li><strong>Icon hover color</strong> – The color of the font icons on mouse hover.</li>
                    <li><strong>Open the links in new window</strong> – If a target URL is specified for a link, whether
                        the links should open in a new window.
                    </li>
                    <li><strong>Alignment</strong> – The icon list can be chosen to align at the center, left, right of
                        it’s position in a page.
                    </li>
                </ul>
                
                <hr>
                <h3 id="hero-header">Hero Header<a class="back-to-top" href="#panel"> Back to top</a></h3>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/hero-header.jpg" alt="Hero Header Widget"></p>

                <p>Hero Headers are popular way to drive across a message, market your products or work, create a call
                    to action for the user etc. Hero headers are often used at the top of a page. With the Hero Image
                    widget, you can display hero header content with option to set HTML5/YouTube video or parallax image
                    background.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/hero-header-edit.jpg" alt="Hero Header Widget Edit Window"></p>

                <p>The options include –</p>
                <p>Header Type – The widget provides option of a built-in standard header content and a custom one where
                    you provide the HTML for the header and the required CSS to style the HTML of the header content.
                    While standard header should meet the requirements of most sites, the custom one is the one to
                    choose for creating more creative headlines.</p>
                <p>The standard header consists of following –</p>
                <ul>
                    <li><strong>Header text</strong> – The heading title for the hero header, displayed as a Heading tag
                        on the frontend.
                    </li>
                    <li><strong>Heading Font (<span class="pro-feature">Pro!</span>)</strong> – The custom font for the header. The user can
                        choose from one of the 500+ fonts from the popular Google Fonts library.
                    </li>
                    <li><strong>Sub-heading text(Optional)</strong> – A small sized subheading displayed above the main
                        heading.
                    </li>
                    <li><strong>Button text</strong> – If the hero header represents a call to action or simply want to
                        provide to another page with related content, you will need a button to be displayed below the
                        main heading. You can provide the text for the button. Examples include ‘Purchase Now’, ‘Contact
                        Us’ etc.
                    </li>
                    <li><strong>Button URL</strong> – The URL to which the button anchor points to.</li>
                    <li><strong>Open URL in a new window</strong> – Whether to open the link in a new window of the
                        browser.
                    </li>
                </ul>

                <p>The custom header lets you build a custom header of your own and requires input of the following
                    –</p>
                <ul>
                    <li><strong>Custom HTML</strong> – The custom HTML content with the header information for the hero
                        header.
                    </li>
                    <li><strong>Custom CSS</strong> – The custom CSS to be applied to the custom HTML header information
                        provided above. Will be embedded inline with the page.
                    </li>
                </ul>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/hero-header-edit2.jpg" alt="Hero Header Widget Edit Window"></p>
                <p>Further options for Hero Image include –</p>
                <ul>
                    <li><strong>URL for Pointer Down</strong> – This option is used for the hero header displayed at the
                        top of a page. If an internal URL for the pointer down is specified, the hero image will sport a
                        pointer down indicator to help user scroll to the section indicated by this URL.
                    </li>
                    <li><strong>Background Type</strong> – The hero image’s power lies in its ability to display
                        multiple types of background – cover image, parallax image, YouTube video or HTML5 Video as
                        background.&nbsp;For both image as well as video backgrounds, a background image must be
                        specified. This image will be the background image for the hero header and if a video background
                        is specified, will act as a placeholder image for the background until the video is loaded or if
                        the video cannot be autoloaded as in the case of mobile devices.
                    </li>
                    <li><strong>Background Overlay</strong> – Specify a overlay color for the image/video background and
                        the opacity for the overlay applied to the image/video. The overlay is used to enhance the
                        visual impact of the hero header and help display the header content more prominently.
                    </li>
                    <li><strong>Top and Bottom Padding</strong> – The top and bottom padding specified in pixels decide
                        how big the hero image gets on the frontend. This is the padding applied on top and bottom of
                        the header content displayed. You can specific the padding for various device resolutions. The
                        height of the hero header is typically smaller in lower resolutions and in mobile devices.
                    </li>
                </ul>


                <hr>
                <h3 id="tabs-accordions">Tabs and Accordions<a class="back-to-top" href="#panel"> Back to top</a></h3>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/tab-widget.jpg" alt="Tabs Widgets"></p>

                <p>A large of finely designed styles are supported by tabs function of the plugin. Tabs can be of two
                    types – vertical and regular horizontal style tabs. </p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/tabs-widget2.jpg" alt="Tabs Widgets"></p>
                <p>There are a total of 10 tab styles to choose
                    from. There is simply no another plugin or theme that supports so many elegant styles for tabs.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/tabs-edit.jpg" alt="Tabs Widget Edit Window"></p>

                <p>Tabs required two attributes – a tab title and tab content. For styles that support icons, choice of
                    displaying a font icon or an icon image along with the tab title is supported.</p>
                <p>Mobile Resolution – Indicate the device resolution in pixels for displaying the tab in responsive
                    mobile mode. The tabs are designed to work well in all device resolutions without sacrificing
                    usability.</p>
                <p><strong>Accordions</strong></p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/accordions.png" alt="Accordion Widget"></p>

                <p>Accordions support panels that are collapsed by default. The panels can be opened by clicking on
                    panel title bar.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/accordion-edit.jpg" alt="Accordion Widget Edit Window"></p>

                <p>Each of the panels part of an accordion require the user to input a tab title and tab content.</p>
                <p>Option to allow multiple panels to be open is provided.</p>

                <hr>
                <h3 id="image-slider">Image Slider – <span class="pro-feature">Pro!</span><a class="back-to-top" href="#panel"> Back to top</a></h3>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/image-slider-flex.jpg" alt="Image Slider Widget"></p>

                <p>The image slider lets you create a responsive slider of images with a multiple options to customize
                    the function and presentation of the slider. The slider can be used anywhere on a page and can also
                    function as the main slider of the page displayed at the top of the page. The slider supports
                    multitude of options but for most users, the default options provided should suffice.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/image-slider-edit.jpg" alt="Image Slider Widget Edit Window"></p>

                <ul>
                    <li><strong>Class</strong> – Set a unique CSS class for the slider. (optional). This lets you
                        customize the slider content, specially the slider caption content via Custom CSS.
                    </li>
                    <li><strong>Slider Type</strong> – The slider provides you with the choice of four popular slider
                        libraries – Flex Slider, Nivo Slider, Slick Slider and Responsive Slider.
                    </li>
                    <li><strong>Flex Slider</strong> – Perhaps the most popular of all and actively maintained by the
                        open source community. Provides features like touch navigation, thumbnail navigation and many
                        options to customize the slider.
                        <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/image-slider-flex.jpg" alt="Image Flex Slider Widget"></p>
                    </li>
                    <li><strong>Nivo Slider</strong> – Has been a very popular slider for many years now and loved by
                        many for number of beautiful transition effects that is supports.

                        <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/image-slider-nivo.jpg" alt="Nivo Image Slider Widget"></p>
                    </li>
                    <li><strong>Slick Slider</strong> – The most popular open source library for building carousels.
                        Responsive controls like touch swipe controls, desktop mouse dragging makes it a compeling
                        choice.
                    </li>
                    <li><strong>Responsive Slider</strong> – Simplest and most lightweight of all sliders (just 1 KB in
                        size minified and gzipped). If you need a slider that uses minimal resources, this option should
                        be worth trying out.
                        <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/image-slider-responsive.jpg" alt="Responsive Image Slider Widget"></p>
                    </li>
                    <li><strong>Choose Caption Style</strong> – There are two styles of captions – one center aligned
                        and the other left aligned. While center aligned caption is more suited to situation where the
                        slider image is functioning more like a background for the caption that is a call to action or a
                        message to the visitor, the style 2 is useful when images speak for themselves and captions
                        describe the images.
                    </li>
                </ul>
                <p>Each slide for the slider allow for following options –</p>
                <ul>
                    <li><strong>Slide Image</strong> – The image for the slide itself.</li>
                    <li><strong>URL to link to by image and caption heading</strong>. (optional) – Specify the URL to
                        which the slide image and caption heading should link to.
                    </li>
                </ul>
                <p>Slider Caption Details</p>
                <ul>
                    <li><strong>Caption Heading</strong> – The heading title for the caption</li>
                    <li><strong>Caption Sub-heading(Optional)</strong> – Subtitle for the caption.</li>
                    <li><strong>Button text</strong> – The text for the button displayed below the caption.</li>
                    <li><strong>Button URL</strong> – URL for the button.</li>
                    <li><strong>Open URL in a new window</strong> – Specify the button click opens the link in a new
                        browser window.
                    </li>
                    <li><strong>Color</strong> – The color of the button. The supported colors are Orange, Blue, Teal,
                        Cyan, Green, Pink, Black, Red, Transparent and Semi Transparent.
                    </li>
                    <li><strong>Button Size</strong> – Can be large, medium or small.</li>
                    <li><strong>Display rounded button</strong> – Make the button display with rounded edges.</li>
                </ul>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/image-slider-edit2.jpg" alt="Image Slider Settings"></p>

                <p><strong>Slider Settings</strong> – The widget has a number of options available for customizing the
                    slider experience –</p>
                <ul>
                    <li><strong>Animation</strong> – Can be Slide or Fade. Applies when the slider type chosen is Flex
                        Slider or the Slick slider. Nivo supports a number of custom transitions while Responsive slider
                        is fade only.
                    </li>
                    <li><strong>Sliding Direction</strong> – Can be vertical or horizontal. Supported by Flex and Slick
                        sliders.
                    </li>
                    <li><strong>Control navigation</strong> – Create navigation for paging control of each slide.</li>
                    <li><strong>Direction navigation</strong> – Create navigation for previous/next navigation.</li>
                    <li><strong>Thumbnails Navigation</strong> – Use slider image thumbnails for slider navigation.
                        Supported by Flex and Nivo sliders.
                    </li>
                    <li><strong>Randomize slides</strong> – Display slides in random order.</li>
                    <li><strong>Pause on hover</strong> – Pause the slideshow when hovering over slider, then resume
                        when no longer hovering.
                    </li>
                    <li><strong>Pause on action</strong> – Pause the slideshow when interacting with control elements.
                        Supported by Flex Slider only.
                    </li>
                    <li><strong>Loop</strong> – Should the animation loop?</li>
                    <li><strong>Slideshow or Autoplay</strong> – Animate slider automatically without user intervention.
                    </li>
                    <li><strong>Slideshow speed (default – 5000)</strong> Set the speed of the slideshow cycling, in
                        milliseconds when the Slideshow option is checked.
                    </li>
                    <li><strong>Animation speed</strong> – Set the speed of animations like fade or slide, in
                        milliseconds.
                    </li>
                </ul>

                <hr>
                <h3 id="image-video-gallery">Image/Video Gallery – <span class="pro-feature">Pro!</span><a class="back-to-top" href="#panel"> Back to top</a></h3>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/image-gallery-dark.jpg" alt="Image Gallery Widget"></p>

                <p>This powerful widget lets you create a gallery of images or videos displayed in a multi-column grid.
                    An instance of this widget can capture a portfolio of work like that of a photographer or graphic
                    designer/artist.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/video-gallery.jpg" alt="Video Gallery Widget"></p>
                <p>It can be used to create a gallery of videos uploaded to YouTube/Vimeo – useful for video bloggers,
                    video tutorial sites, video marketers, small businesses or websites with a major presence on
                    YouTube/Vimeo. The videos can be played with a single click of the play button on the gallery item
                    as seen in this <a title="Video Gallery"
                                       href="https://livemeshwp.com/siteorigin-widgets/video-gallery/">demo page</a>.
                </p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/image-gallery-edit1.jpg" alt="Image Gallery Widget Edit Window"></p>
                <p>The configuration for creating a video gallery is similar to that of image gallery; a video URL would be required along with image that acts as a placeholder.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/video-gallery-edit.jpg" alt="Video Gallery Widget Edit Window"></p>

                <p>Each of the gallery items capture following information –</p>
                <ul>
                    <li><strong>Item Type</strong> – Can be a Image or YouTube Video or Vimeo Video.</li>
                    <li><strong>Item Label</strong> – The label or name for the gallery item. This label is displayed on
                        mouse hover over the image.
                    </li>
                    <li><strong>Choose Media</strong> – The image for the gallery item. If item type chosen is YouTube
                        or Vimeo video, the image will be used as a placeholder image for video.
                    </li>
                    <li><strong>Item Tag(s)</strong> – One or more comma separated tags for the gallery item. Useful
                        when items are made filterable.
                    </li>
                    <li><strong>Page URL</strong> – The URL of the page to which the image gallery item points to
                        (optional).
                    </li>
                    <li><strong>Video URL</strong> – If the item represents a Vimeo or YouTube video, provide the URL to
                        the video. Any gallery item representing a video is given a play button. Upon clicking the play
                        button, the Vimeo/YouTube video opens up in a lightbox window for playing.
                    </li>
                </ul>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/image-gallery-edit2.jpg" alt="Image Gallery Widget Edit Window"></p>

                <p>The Gallery widget comes with following settings –</p>
                <ul>
                    <li><strong>Filterable</strong> – If the videos or images are tagged, the items can be made
                        filterable on the tags specified by the user just like a Portfolio Grid.
                    </li>
                    <li><strong>Layout for the grid</strong> – Comes with Masonry and FitRows option.</li>
                    <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/image-gallery-pagination.jpg" alt="Image Gallery Widget Pagination"></p>
                    <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/image-gallery-loadmore.jpg" alt="Image Gallery Widget Load More Option"></p>
                    <li><strong>Pagination</strong> – Choose pagination type or choose None if no pagination is desired.
                        Make sure you enter the items per page value in the option ‘Number of items to be displayed per
                        page and on each load more invocation’ field below to control number of items to display per
                        page.
                        <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/video-gallery-pagination.jpg" alt="Video Gallery Widget Pagination"></p>
                    </li>

                    <li><strong>Columns per row</strong> – Specify the number of images/videos to display per row of the
                        grid.
                    </li>
                    <li><strong>Enable Lightbox Gallery</strong> – The lightbox for the image opens up a bigger image in
                        a popup window. You can navigate among the gallery items here.
                    </li>
                    <li><strong>Gutter</strong> – The spacing between columns that contain image/video in the grid. You
                        can control the spacing/gutter at various resolutions like those of tablet/smartphone.
                    </li>
                </ul>

                <hr>
                <h3 id="image-video-carousel">Image/Video Carousel – <span class="pro-feature">Pro!</span><a class="back-to-top" href="#panel"> Back to top</a></h3>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/image-carousel.jpg" alt="Image Carousel Widget"></p>

                <p>You can create a carousel of images/videos (or a combination of both) for showcasing your work or
                    video content uploaded to Vimeo/YouTube. An instance of this widget can capture a portfolio of work
                    like that of a photographer or graphic designer/artist.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/video-carousel.jpg" alt="Video Carousel Widget"></p>
                <p>It can be used to create a carousel of videos uploaded to YouTube/Vimeo – useful for video bloggers,
                    video tutorial sites, video marketers, small businesses or websites with a major presence on
                    YouTube/Vimeo. The videos can be played with a single click of the play button on the gallery item
                    as seen in this <a title="Video Gallery"
                                       href="https://livemeshwp.com/siteorigin-widgets/video-gallery/">demo page</a>.
                </p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/image-carousel-edit.jpg" alt="Image Carousel Widget Edit Window"></p>
                <p>The option for creation of video carousel is similar to that of image carousel - requires input of URL for the Vimeo/YouTube video along with placeholder image. </p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/video-carousel-edit.jpg" alt="Video Carousel Widget Edit Window"></p>

                <p>Each of the gallery items in the carousel capture following information –</p>
                <ol>
                    <li><strong>Item Type</strong> – Can be a Image or YouTube Video or Vimeo Video.</li>
                    <li><strong>Item Label</strong> – The label or name for the gallery item. This label is displayed on
                        mouse hover over the image.
                    </li>
                    <li><strong>Choose Media</strong> – The image for the gallery item. If item type chosen is YouTube
                        or Vimeo video, the image will be used as a placeholder image for video.
                    </li>
                    <li><strong>Item Tag(s)</strong> – One or more comma separated tags for the gallery item. Useful
                        when items are made filterable.
                    </li>
                    <li><strong>Page URL</strong> – The URL of the page to which the image gallery item points to
                        (optional).
                    </li>
                    <li><strong>Video URL</strong> – If the item represents a Vimeo or YouTube video, provide the URL to
                        the video. Any gallery item representing a video is given a play button. Upon clicking the play
                        button, the Vimeo/YouTube video opens up in a lightbox window for playing.
                    </li>
                </ol>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/image-carousel-edit2.jpg" alt="Image/Video Carousel Settings"></p>

                <p>The section ‘Carousel Settings’ has options that control how the carousel is displayed. Options
                    include autoplay speed, gutter value between post items in various resolutions, navigation controls
                    for carousel, number of columns or items to display before making the user to scroll for additional
                    items etc.</p>
                <ul>
                    <li><strong>Enable Lightbox Gallery</strong> – Enable lightbox gallery for images. The lightbox for
                        the image opens up a bigger image in a popup window. You can navigate among the gallery items
                        here.
                    </li>
                    <li><strong>Prev/Next Arrows</strong> – Display navigation for the carousel.</li>
                    <li><strong>Show dot indicators for navigation</strong> – Display control navigation or pagination
                        controls for the carousel.
                    </li>
                    <li><strong>Autoplay</strong> – Display carousel as a slideshow.</li>
                    <li><strong>Autoplay speed in ms</strong> – The time between display of each page of images when
                        Autoplay option is enabled.
                    </li>
                    <li><strong>Autoplay animation speed in ms</strong> – The time taken for animation that moves the
                        carousel to next or previous page of items.
                    </li>
                    <li><strong>Pause on mouse hover</strong> – Pause the slideshow if the user has mouse hovered over
                        the carousel contents.
                    </li>
                    <li><strong>Columns per row</strong> – Number of gallery items visible at any given point of time
                        without scrolling.
                    </li>
                    <li><strong>Columns to scroll</strong> – With each scroll action – using the prev/next arrows or the
                        dotted navigation, specify the number of items to scroll for each invocation of the navigation
                        controls.
                    </li>
                    <li><strong>Gutter</strong> – The spacing in pixels between images/videos in the carousel.</li>
                </ul>

                <hr>
                <h3 id="faq-widget">FAQ- <span class="pro-feature">Pro!</span><a class="back-to-top" href="#panel"> Back to top</a></h3>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/faq-widget.png" alt="FAQ Widget"></p>

                <p>The FAQ makes the task of creating a FAQ for a site effortless. Just enter FAQ items and choose the
                    number of items to show per row of content and you are done.</p>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/faq-edit.png" alt="FAQ Widget Edit Window"></p>

                <p>Each FAQ item requires two input – question and an answer for the question part of the FAQ.</p>
                <p>Do note that the Accordion function of the plugin too can be used to create a nicely formed FAQ for a
                    site.</p>

                <hr>
                <h3 id="features-widget">Features- <span class="pro-feature">Pro!</span><a class="back-to-top" href="#panel"> Back to top</a></h3>
                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/features-widget.jpg" alt="Features Widget"></p>

                <p>Features widget lets you showcase a number of things. Below are some examples although possibilities are many - </p>

                <ul>
                    <li>Features of a product like a mobile app or other types of software.</li>
                    <li>Showcase features provided by an online service or a tool.</li>
                    <li>List a set of services an agency or organization may provide.</li>
                    <li>Describe any type of physical or digital goods you are trying to sell.</li>
                </ul>

                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/features-edit.png" alt="Features Widget Edit Window"></p>

                <p>Any feature part of the features widget requires you input an icon image or a screeshot which
                    represents the feature you are describing. Aside from the icon or screenshot, you will need to
                    provide details like heading title, subtitle and description of the feature.</p>

                <p>The features widget has an option to apply popular tile-based design to the features list (screenshot
                    below). The examples of this is seen in the demo site showcasing the features widget.</p>

                <p><img class="alignnone size-large" src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/features-widget2.jpg" alt="Tiled Features Widget"></p>

                <hr>
                <h3 id="plugin-support">Plugin Support</span><a class="back-to-top" href="#panel"> Back to top</a></h3>
                <p>If you have queries or issues to report related to the plugin, feel free to contact us via our dedicated support portal.</p>

            </div>

            <!-- Updates panel -->
            <div id="plugins-panel" class="panel-left">
                <h4>Required/Recommended Plugins</h4>

                <p>Below is a list of required/recommended plugins to install that will help you get the most out of the plugin. Except for SiteOrigin Widgets Bundle, the rest of the plugins are optional but we recommend you install these plugin if you plan to replicate the plugin demo site by importing the sample data.</p>

                <hr/>

                <h4><?php _e('SiteOrigin Widgets Bundle', 'livemesh-so-widgets'); ?>
                    <?php if (!class_exists('SiteOrigin_Widgets_Bundle')) { ?>
                        <a class="button button-secondary thickbox onclick" href="<?php echo esc_url($soWidgetsBundleUrl); ?>"
                           title="<?php esc_attr_e('Install SiteOrigin Widgets Bundle', 'livemesh-so-widgets'); ?>"><span
                                class="dashicons dashicons-download"></span> <?php _e('Install Now', 'livemesh-so-widgets'); ?></a>
                    <?php }
                    else { ?>
                        <span class="button button-secondary disabled"><span
                                class="dashicons dashicons-yes"></span> <?php _e('Installed', 'livemesh-so-widgets'); ?></span>
                    <?php } ?>
                </h4>

                <p><strong>SiteOrigin Widgets Bundle</strong> is a powerful framework for building WordPress
                    widgets with support for advanced forms, unlimited colors and 1500+ icons. Widgets built using
                    this framework can be used in a page builder page or any widgetized area of your site like the
                    sidebar or footer.</p>
                <p>All of the widgets part of Livemesh SiteOrigin Widgets plugin were created using this
                    framework and hence this plugin must be installed and activated on the site for our plugin
                    to function.</p>

                <hr/>

                <h4><?php _e('SiteOrigin Page Builder', 'livemesh-so-widgets'); ?>
                    <?php if (!defined( 'SITEORIGIN_PANELS_VERSION' ) ) { ?>
                        <a class="button button-secondary thickbox onclick" href="<?php echo esc_url($soPageBuilderUrl); ?>"
                           title="<?php esc_attr_e('Install SiteOrigin Page Builder', 'livemesh-so-widgets'); ?>"><span
                                class="dashicons dashicons-download"></span> <?php _e('Install Now', 'livemesh-so-widgets'); ?></a>
                    <?php }
                    else { ?>
                        <span class="button button-secondary disabled"><span
                                class="dashicons dashicons-yes"></span> <?php _e('Installed', 'livemesh-so-widgets'); ?></span>
                    <?php } ?>
                </h4>

                <p><strong>SiteOrigin Page Builder</strong> is the most popular page builder plugin for WordPress.
                    It makes it easy to create responsive column based content, using WordPress widgets including
                    those created by Livemesh SiteOrigin widgets plugin. All of the pages of our demo site for
                    the plugin have been built using this page builder. You should install and activate this plugin
                    if you plan to replicate the plugin demo site by importing the sample data provided.</p>

                <hr/>

                <h4><?php _e('Portfolio Post Type', 'livemesh-so-widgets'); ?>
                    <?php if (!class_exists('Portfolio_Post_Type')) { ?>
                        <a class="button button-secondary thickbox onclick" href="<?php echo esc_url($portfolioPostTypeUrl); ?>"
                           title="<?php esc_attr_e('Install Portfolio Post Type', 'livemesh-so-widgets'); ?>"><span
                                class="dashicons dashicons-download"></span> <?php _e('Install Now', 'livemesh-so-widgets'); ?></a>
                    <?php }
                    else { ?>
                        <span class="button button-secondary disabled"><span
                                class="dashicons dashicons-yes"></span> <?php _e('Installed', 'livemesh-so-widgets'); ?></span>
                    <?php } ?>
                </h4>

                <p><strong>Portfolio Post Type</strong> is a free plugin that registers a custom post type for
                    portfolio items. It also registers separate portfolio taxonomies for tags and categories. The
                    Portfolio grid instances showcased on our demo site was built using custom post types registered
                    by Portfolio Post Type plugin.</p>
            </div><!-- .panel-left -->

            <!-- Support panel -->
            <div id="support-panel" class="panel-left">
                <ul id="top" class="anchor-nav">
                    <li>
                        <a href="#faq-compatibility"><strong>Does it work with the theme that I am using?</strong></a>
                    </li>
                    <li>
                        <a href="#faq-dark-version"><strong>How to enable the dark version for any widget?</strong></a>
                    </li>

                    <li>
                        <a href="#faq-missing-widget"><strong>Seeing 'Missing Widget' error upon import.</strong></a>
                    </li>
                    <li>
                        <a href="#faq-portfolio-grid"><strong>My portfolio does not show any items.</strong></a>
                    </li>
                </ul>

                <h3 id="faq-compatibility">Does it work with the theme that I am using?</h3>

                <p>Our tests indicate that the widgets work well with most themes that are well coded. You may need some
                    minor custom CSS with themes that hijack the styling for heading tags by using !important
                    keyword.</p>

                <p>The demo site is best recreated with a theme that supports a full width page template without
                    sidebars. The widgets can still be used in the widgetized sidebars of pages of default template.</p>


                <hr/>

                <h3 id="faq-dark-version">How to enable the dark version for any widget?</h3>

                <p>In SiteOrigin page builder, add a row wrapper for the widget, edit row and check the option 'Dark
                    Background?' under 'Row Styles' &gt; Design.</p>

                <p>If not using a page builder, you can wrap the widget with a div of class 'lsow-dark-bg' to invoke
                    dark version. Make sure you set the appropriate dark background for the wrapper div.</p>

                <hr/>

                <h3 id="faq-missing-widget">Seeing 'Missing Widget' error upon import.</h3>

                <p>Please make sure the <a href="https://wordpress.org/plugins/so-widgets-bundle/" title="SiteOrigin Widgets Bundle">SiteOrigin Widgets Bundle</a> plugin is installed/activated and enable the widgets
                    from Plugins &gt; SiteOrigin Widgets in WordPress admin.
                </p>

                <hr/>

                <h3 id="faq-portfolio-grid">My portfolio grid does not show any items.</h3>

                <p>Pls install and activate the <a href="https://wordpress.org/plugins/portfolio-post-type/" title="Portfolio Post Type">Portfolio Post Type plugin</a> to enable custom post type Portfolio.
                </p>

                <hr/>
            </div><!-- .panel-left support -->

            <!-- Updates panel -->
            <div id="updates-panel" class="panel-left">

                <h2>Change Log for the Premium Version</h2>
                <br>
                <h3>2.8</h3>
                <ul>
                    <li>Updated - Freemius SDK 2.4.2 with license activation enhancements, beta programs and other features and bug fixes.</li>
                </ul>
                <h3>2.7.0</h3>
                <ul>
                    <li>Updated - Move to new domain livemeshwp.com</li>
                    <li>Updated - Compatibility with WP 5.5</li>
                </ul>
                <h3>2.6.0</h3>
                <ul>
                    <li>Added - Options to turn off/on title and taxonomy terms displayed on posts carousel thumbnail</li>
                    <li>Added - Open in new window option for post links in posts carousel</li>
                    <li>Added - Ability to add read more link for the posts carousel</li>
                </ul>
                <h3>2.5.9</h3>
                <ul>
                    <li>Tweak - Enabled lazy load for all the widgets except for the grid/gallery addons - Posts Grid, Video and Image Gallery. The Posts Grid and Gallery addons utilize the popular Isotope library for laying out grid elements and Isotope library is not compatible with lazy loading of images.</li>
                    <li>Updated - Freemius SDK 2.3.2 with Opt-In / Out Enhancements, User Change, and More.</li>
                </ul>
                <h3>2.5.8</h3>
                <ul>
                    <li>Updated - Freemius SDK 2.3.1 with white label mode, URL whitelisting and other features and bug fixes.</li>
                </ul>
                <h3>2.5.7</h3>
                <ul>
                    <li>Fixed - The taxonomy chosen dropdown displaying superfluous taxonomies in the dropdown for Posts Grid/Posts Block.</li>
                    <li>Fixed - Cannot display taxonomy terms for the post in block styles 7,8 and 9 of Posts Block.</li>
                </ul>
                <h3>2.5.6</h3>
                <ul>
                    <li>Fixed - Some sites reporting error - call to undefined function get_blog_list().</li>
                    <li>Updated - Freemius to 2.3.0.</li>
                </ul>
                <h3>2.5.5</h3>
                <ul>
                    <li>Added - Offset support for Posts/Portfolio Grid and Posts Block modules.</li>
                    <li>Added - Security improvements to Posts Grid and Gallery modules.</li>
                </ul>
                <h3>2.5.4</h3>
                <ul>
                    <li>Added - Thumbnails support for the lightbox module of Posts Grid, Posts Block, Image/Video Gallery and Gallery Carousel.</li>
                    <li>Updated - Fancybox scripts to 3.5.7 release.</li>
                </ul>
                <h3>2.5.3</h3>
                <ul>
                    <li>Fixed - The posts carousel widget navigation arrows missing.</li>
                </ul>
                <h3>2.5.2</h3>
                <ul>
                    <li>Updated - Freemius library with a security fix. Recommended to update immediately.</li>
                    <li>Fixed - Some themes can raise JS error due to jQuery $ being unavailable</li>
                    <li>Fixed - Unwanted loading of scripts and CSS in pages where livemesh widgets are not used.</li>
                    <li>Fixed - Error in Gallery Carousel due to invalid HTML generated with certain options.</li>
                </ul>
                <h3>2.5.1</h3>
                <ul>
                    <li>Fixed - The posts grid widget won't show up with the free version of the plugin.</li>
                </ul>
                <h3>2.5</h3>
                <ul>

                    <li>Added - Major release of the plugin with extensive support for filters and templates to enable users to customize the output generated by the widgets. You can now create a template file in siteorigin-widgets folder in your child theme to customize the widgets html or use filters to customize output generated by the widgets. </li>
                    <li>Updated - Documentation providing information on how to use templates and filters to customize the plugin widgets.</li>
                    <li>Added - Filters for settings object employed for rendering the widgets.</li>
                    <li>Fixed - Gallery pagination would break when number of items crosses 140.</li>
                    <li>Added - Pagination with dotted navigation for galleries when number of pages exceeds 5.</li>
                    <li>Added - Responsive pagination controls for gallery.</li>
                    <li>Fixed - The fancybox lightbox would not display share, thumbnail, slideshow options for image/video gallery, posts grid and posts blocks.</li>
                    <li>Added - Read More link/button options for posts blocks.</li>
                    <li>Added - Block style 8 in Posts Block now much more responsive.</li>
                </ul>
                <h3>2.3</h3>
                <ul>
                    <li>Fixed - Incompatibility with certain themes due to different versions of waypoints scripts being used.</li>
                    <li>Fixed - Leaving tags empty in the gallery lead to addition of an empty filter in the filter list</li>
                    <li>Fixed - Misplaced HTML5 video background hero header video tags information</li>
                </ul>
                <h3>2.2.1</h3>
                <ul>
                    <li>Fixed - Bug fixes related to Freemius integration.</li>
                </ul>
                <h3>2.2</h3>
                <ul>
                    <li>Added – Freemius integration for easy upgrade, quick support/feedback and opt-in usage tracking with GDPR compliance.</li>
                </ul>
                <h3>2.1.0</h3>
                <ul>
                    <li>Added – New simpler grid system for all elements that use grid.</li>
                    <li>Added – Seamless control of number of columns at all device resolutions for all those elements that involve grid – posts/portfolio grid, posts block, image/video gallery, clients, services, testimonials, team, charts, pricing table, faq etc.</li>
                    <li>Added - Option to preserve shortcodes and HTML tags in excerpt. Option is disabled by default.</li>
                </ul>
                <h3>2.0.1</h3>
                <ul>
                    <li>Fixed – Pagination and Load More for gallery would break when special characters are present in title or description.</li>
                    <li>Fixed - The Load More in gallery would not stop loading in certain situations.</li>
                    <li>Fixed - Duplicate tags filters generated in gallery when tags have spaces around them.</li>
                </ul>
                <h3>2.0.0</h3>
                <ul>
                    <li>Added - Support for multiple url formats for YouTube and Vimeo videos in video gallery</li>
                    <li>Added - Support for inline responsive videos of YouTube, Vimeo and self-hosted MP4/WebM video formats</li>
                    <li>Added - If no custom thumbnail is set, the thumbnails for YouTube/Vimeo videos automatically set from the service provider</li>
                    <li>Fixed - Next/Prev navigation for gallery not working</li>
                    <li>Added – Ability to display description for the image/video element in the gallery and gallery carousel lightbox window.</li>
                    <li>Added – The lightbox for posts grid and posts block now displays post summary and a link to the post in the lightbox.</li>
                    <li>Added – Option to disable display of post summary/excerpt in the lightbox window of posts block or posts grid.</li>
                    <li>Added – Fancybox lightbox integration for grid, posts block, image gallery, video gallery and gallery carousel modules.</li>
                    <li>Added – Advanced features like touch/swipe controls, pinch out/in, double tap, keyboard navigation, full screen, thumbnails, social media sharing, hardware accelerated animations, direct linking now supported with lightbox.</li>
                    <li>Added - Support for creating stunning masonry gallery layouts using flexible widths and heights for images.</li>
                    <li>Added - Ability to specify wide width for images in masonry layout of gallery.</li>
                    <li>Added - Options to enable/disable display of image/video titles and image/video tags in gallery and gallery carousel widgets.</li>
                    <li>Added – HTML5 video support in video gallery and video carousel modules. Support for MP4 and WebM formats.</li>
                    <li>Fixed - Posts Grid excerpt would not display shortcodes or HTML content</li>
                    <li>Fixed - Livemesh Gallery styling controls not taking effect for thumbnail hover and titles</li>
                    <li>Updated - Waypoints script with new API for handling events on scroll</li>
                    <li>Fixed - Some themes can break animations and report script errors for elements like piecharts, odometers, progress bars etc.</li>
                </ul>
                <h3>1.8.4</h3>
                <ul>
                    <li>Improved – License activation page with less confusing interface.</li>
                    <li>Added - The license code entered is now masked upon activation.</li>
                </ul>
                <h3>1.8.3</h3>
                <ul>
                    <li>Fixed – Posts Grid columns can break in certain resolutions in FireFox.</li>
                    <li>Fixed – Styling options for icons not working in Services widget</li>
                    <li>Fixed – Cannot set custom button color using the available customization controls</li>
                    <li>Added – Ability to specify an URL for the service items in services widget</li>
                    <li>Added – Support for shortcodes and HTML tags in excerpts in Posts Grid and Posts Block module</li>
                    <li>Added – Support for JS driven shortcodes or HTML inside tabs</li>
                </ul>
                <h3>1.8.2</h3>
                <ul>
                    <li>Fixed – Image/Video Gallery grid items fail to show up in right size and alignment in certain rare situations.</li>
                </ul>
                <h3>1.8.1</h3>
                <ul>
                    <li>Added - Advanced tab and accordion functions that help directly link to Tabs and Accordion panels from external pages. On page load, the corresponding tab/panel opens upon auto-scrolling to the tab or accordion panel.</li>
                    <li>Added - Smooth scroll to tabs and accordion panels from internal links within a page.</li>
                    <li>Added - Open tab or accordion panels by clicking the internal links within a page.</li>
                </ul>
                <h3>1.8</h3>
                <ul>
                    <li>Upgrade – Simpler grid system based on NEAT 2.1 version</li>
                    <li>Updated - The CSS is now optimized for vendor prefixes with reduced properties and file size.</li>
                    <li>Added - Scroll to the top of the posts block to display new posts during paged navigation (when new posts are not visible).</li>
                </ul>

                <h3>1.7.5</h3>
                <ul>
                    <li>Added – Image size option for all major widgets including grid, gallery and carousels.</li>
                    <li>Added - The lightbox now opens on clicking anywhere on the image if no destination URL is specified for gallery item</li>
                    <li>Added - The link target option for all major widgets like grid, gallery and carousels</li>
                    <li>Fixed – The gallery image was not clickable to the link specified</li>
                    <li>Fixed – The gallery filters would not center when a heading was not specified.</li>
                    <li>Fixed – The gallery filters will not display multi-line on devices of lower resolutions like mobile devices.</li>
                </ul>
                <h3>1.7.1</h3>
                <ul>
                    <li>Refactored – Blocks and Grid JS for modularity and performance.</li>
                    <li>Minor tweaks in portfolio widget for enabling and disabling options based on chosen grid style</li>
                </ul>

                <h3>1.7</h3>
                <ul>
                    <li>Added – Moved the Posts Grid widget to new framework based on reusable blocks. Pls note that the class names have changed to keep it consistent with the new framework.</li>
                    <li>Added – 6 different styles for grid items plus 7 header styles too</li>
                    <li>Added – Next Prev pagination options for Posts Grid widget</li>
                    <li>Added – Ability to handle large number of grid pages by providing dotted navigation</li>
                    <li>Added – AJAX Category or taxonomy filtering for Grid widget. No more empty grid on category/taxonomy filtering</li>
                    <li>Added – Read More option for the grid items</li>
                    <li>Added – Open in new window option for links to posts in the grid</li>
                    <li>Added – Two more header styles to posts blocks</li>
                    <li>Fixed – The grid image was not clickable to the post</li>
                    <li>Fixed – The filters would not center when a heading was not specified.</li>
                    <li>Fixed – The grid filters will not display multi-line on devices of lower resolutions like mobile devices</li>
                </ul>

                <h3>1.6</h3>
                <ul>
                    <li>Added - Brand new Post Blocks Widget with more than a dozen styles and options to present your posts or custom post types</li>
                    <li>Added - AJAX Pagination, Next Prev and Load More options for Post Blocks widget</li>
                    <li>Added - AJAX Category or taxonomy filtering for Post Blocks widget</li>
                    <li>Fixed - The grid layout would break between 800px and 1024px device resolution</li>
                </ul>

                <h4>1.5.1</h4>

                <ul>
                    <li>Fixed - The gutter or spacing between posts carousel items not taking effect with latest update of page builder</li>
                    <li>Fixed - The heading element for the livemesh grid displayed even when no heading title is specified</li>
                    <li>Fixed - Restored the missing instagram icon in team profile widget</li>
                    <li>Tweak - Moved to the_excerpt() from get_the_excerpt() in posts carousel and livemesh grid widgets</li>
                </ul>

                <h4>1.5.0</h4>

                <ul>
                    <li>Fixed - The top and bottom padding attributes for rows were not taking effect with 2.5.x version of page builder</li>
                    <li>Updated - The top and bottom padding attributes for rows deprecated. Pls use native padding settings of page builder which now provides more fine grained padding controls.</li>
                    <li>Updated - We have moved; all URLs now point to our new site https://www.livemeshthemes.com</li>
                    <li>Added - Option to make the accordion panels expanded on initial page load.</li>
                </ul>

                <h4>1.4.9</h4>

                <ul>
                    <li>Fixed - Livemesh grid failing with JS error with Isotope 3 library.</li>
                    <li>Added - Captions for image lightboxes for portfolio grid, image gallery and gallery carousel widgets.</li>
                    <li>Added - Hebrew translation files - thanks Ahrale!</li>
                </ul>

                <h4>1.4.8</h4>

                <ul>
                    <li>Fixed - The summary settings were not being retained across grid pages.</li>
                    <li>Fixed - The grid filter hidden behind the grid items when number filters get big</li>
                    <li>Fixed - Taxonomy filters specified in query window not taking effect in grid</li>
                    <li>Updated - Updated isotope and images loaded plugin scripts..</li>
                </ul>

                <h4>1.4.7</h4>

                <ul>
                    <li>Fixed - The taxonomy filter not reflecting custom taxonomies in grid widget.</li>
                    <li>Fixed - The taxonomy list in posts carousel not displaying custom taxonomies.</li>
                </ul>

                <h4>1.4.6</h4>

                <ul>
                    <li>Added - Feature List widget with tiled features option.</li>
                    <li>Added - Choose from over 40 custom animations for most widgets (except grid, carousels and sliders). The animations display on user scrolling to the widget or when the widget becomes visible in the browser window.</li>
                    <li>Updated - Documentation for the widgets.</li>
                    <li>Fixed - Translation of published date on the grid.</li>
                    <li>Fixed - Shortcodes not being processed in hero header widget.</li>
                </ul>

                <h4>1.4.5</h4>

                <ul>
                    <li>Fixed - Tabs defaulting to zero on mobile width when saving changes.</li>
                </ul>

                <h4>1.4.4</h4>

                <ul>
                    <li>Fixed - Admin notices were being removed due to a missing conditonal statement.</li>
                    <li>Fixed - The loading icon in the Grid pagination was overlapping with the pagination buttons.</li>
                </ul>

                <h4>1.4.3</h4>

                <ul>
                    <li>Fixed - Top padding and bottom padding field for various resolutions.</li>
                </ul>

                <h4>1.4.2</h4>

                <ul>
                    <li>Added - Compatibility with WordPress 4.6.</li>
                </ul>

                <h4>1.4.1</h4>

                <ul>
                    <li>Fixed - Display of titles for widgets</li>
                    <li>Fixed - Check for page id when displaying posts/pages in a grid to avoid infinite loop.</li>
                </ul>

                <h4>1.4</h4>

                <ul>
                    <li>Added - New widgets including Gallery, Gallery Carousel, Image Slider, Custom Content Slider, Countdown and FAQ.</li>
                    <li>Added - AJAX based Pagination and Load More options to Grid and Gallery widgets.</li>
                    <li>Added - Custom Fonts for headings and hero header widgets.</li>
                    <li>Added - New styles and ability to specify custom icon color and icon size for services widgets.</li>
                    <li>Added - Default color for buttons based on theme color.</li>
                    <li>Added - New demo content for call to action and services.</li>
                    <li>Added - Detailed documentation for all widgets</li>
                    <li>Added - Plugin options window for enabling all widgets in one go along with other options.</li>
                    <li>Added - Support for lightweight Portfolio Post Type plugin. Jetpack custom post types module no longer recommended.</li>
                    <li>Fixed - The shortcodes not processed by accordion.</li>
                    <li>Some styling improvements and fixes</li>
                </ul>

                <h4>1.3</h4>

                <ul>
                    <li>New widgets - Flat style buttons and Icon List widget</li>
                    <li>New fields - Datepicker and Timepicker for developing SiteOrigin widgets</li>
                    <li>Fixed - Some themes squeeze the images in a Livemesh grid or Team widget.</li>
                    <li>Fixed - Couple of widgets fail to display the post links.</li>
                    <li>Fixed - The testimonials slider not aligned to the center</li>
                    <li>Some styling improvements and fixes</li>
                </ul>

                <h4>1.2</h4>

                <ul>
                    <li>New widgets - Responsive Tabs and Accordion of variety of styles</li>
                    <li>New services widget style</li>
                    <li>Ability to choose entry meta contents for carousel and grid</li>
                    <li>Ability to set a link to the clients website in clients widget</li>
                    <li>Email icon restored for team profiles widget</li>
                    <li>Fixed some bugs, incompatibilities and design improvements</li>
                </ul>

                <h4>1.1</h4>

                <ul>
                    <li>Changed portfolio widget to a general grid that can accommodate any custom post type. Pls choose
                        Projects post type in the build query in portfolio widgets after updating.
                    </li>
                    <li>Display post meta for blog entries in carousel and grid widgets</li>
                    <li>Display hover information for entries in carousel</li>
                    <li>Fixed some bugs and design improvements</li>
                </ul>

                <h4>1.0</h4>

                <ul>
                    <li>Initial release.</li>
                </ul>
            </div><!-- .panel-left updates -->

            <div class="panel-right">

                <div class="panel-inner">

                    <?php if (lsow_fs()->is_not_paying()): ?>

                        <div class="panel-aside banner">
                            <a href="<?php echo lsow_fs()->get_upgrade_url(); ?>" title="Purchase Now"><img class="dashboard-image"
                                                                                                                           src="https://www.livemeshthemes.com/wp-content/uploads/plugin-doc/livemesh-widgets/dashboard/purchase-banner1.jpg"
                                                                                                                           alt="Sale Banner"></a>
                        </div>

                        <!-- Knowledge base -->
                        <div class="panel-aside">
                            <h4><?php _e('Why upgrade to Premium version?', 'livemesh-so-widgets'); ?></h4>
                            <p><?php _e('Premium version offers multiple benefits - more widgets, advanced features for widgets including those part of the free plugin and priority support through a dedicated support portal.', 'livemesh-so-widgets'); ?></p>

                            <a class="button button-primary"
                               href="https://livemeshwp.com/siteorigin-widgets/widgets-demo/#why-upgrade"
                               title="<?php esc_attr_e('Know More', 'livemesh-so-widgets'); ?>"><?php _e('Know More Details', 'livemesh-so-widgets'); ?></a>
                        </div><!-- .panel-aside knowledge base -->

                    <?php else: ?>

                        <!-- Knowledge base -->
                        <div class="panel-aside">
                            <h4>Need support for the plugin?</h4>
                            <p>The premium version of the plugin entitles you to quick support with replies posted within 24 hours (on week days). </p>

                            <p>Please submit your support query through our <a href="https://livemeshwp.com/siteorigin-widgets/contact-us/" title="Livemesh Contact form">website contact form</a>. This will create a support ticket in our support portal.</p>

                            <a class="button button-primary"
                               href="https://livemeshwp.com/siteorigin-widgets/contact-us/"
                               title="<?php esc_attr_e('Contact Us', 'livemesh-so-widgets'); ?>"><?php _e('Contact Us', 'livemesh-so-widgets'); ?></a>
                        </div><!-- .panel-aside knowledge base -->

                        <!-- Knowledge base -->
                        <div class="panel-aside">
                            <h4>Have questions or want to leave feedback?</h4>
                            <p>If you need to leave your feedback or have a query regarding one of our <a href="https://www.livemeshthemes.com/" title="WordPress Themes and Plugins">WordPress plugins or themes</a>, feel free to leave us a message through our contact form and we will get back to you promptly.</p>

                            <a class="button button-primary"
                               href="https://www.livemeshthemes.com/contact-us/"
                               title="<?php esc_attr_e('Write to Us', 'livemesh-so-widgets'); ?>"><?php _e('Write to Us', 'livemesh-so-widgets'); ?></a>
                        </div><!-- .panel-aside knowledge base -->

                    <?php endif; ?>

                </div><!-- .panel-inner -->
            </div><!-- .panel-right -->
        </div><!-- .panel -->
    </div><!-- .panels -->
</div><!-- .livemesh-doc -->
