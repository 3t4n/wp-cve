<?php

namespace OXI_FLIP_BOX_PLUGINS\Page;

/**
 * Description of Welcome
 *
 * @author biplo
 */
class Welcome
{


    public function admin_css()
    {
        wp_enqueue_style('flip-box-admin-welcome', OXI_FLIP_BOX_URL . 'asset/backend/css/admin-welcome.css', false, OXI_FLIP_BOX_PLUGIN_VERSION);
    }
    public function Public_Render()
    {
?>
        <div class="wrap about-wrap">

            <h1>Welcome to Flipbox - Awesomes Flip Boxes Image Overlay</h1>
            <div class="about-text">
                Thank you for choosing Flipbox - Awesomes Flip Boxes Image Overlay - the most friendly WordPress FLip Box Or Image Overlay Plugins. Here's how to get started.
            </div>
            <h2 class="nav-tab-wrapper">
                <a class="nav-tab nav-tab-active">
                    Getting Started
                </a>
            </h2>
            <p class="about-description">
                Use the tips below to get started using Flipbox - Awesomes Flip Boxes Image Overlay. You will be up and running in no time.
            </p>
            <div class="feature-section">
                <h3>Creating Your Flip Box</h3>
                <p>Flipbox - Awesomes Flip Boxes Image Overlay makes it easy to create Flipbox or Flipping Content in WordPress. You can follow the video tutorial on the right or read our how to
                    <a href="https://oxilabdemos.com/flipbox/docs/installations/how-to-install-the-plugin/" target="_blank" rel="noopener">Create your Flipbox Guide</a>.
                </p>
                <p>But in reality, the process is so intuitive that you can just start by going to <a href="<?php echo esc_url(admin_url()); ?>admin.php?page=oxi-flip-box-ultimate-new">Create Flipbox</a>. </p>
                </br>
                </br>
                <iframe width="560" height="315" src="https://www.youtube.com/embed/OaLL0DNUHWA" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
            </div>
            <div class="feature-section">
                <h3>See all Flipbox - Awesomes Flip Boxes Image Overlay Features</h3>
                <p>Flipbox - Awesomes Flip Boxes Image Overlay is both easy to use and extremely powerful. We have tons of helpful features that allows us to give you everything you need on Flipbox.</p>
                <p>1. Awesome Live Preview Panel</p>
                <p>1. Can Customize with Our Settings</p>
                <p>1. Easy to USE & Builtin Integration for popular Page Builder</p>
                <p><a href="https://oxilabdemos.com/flipbox/pricing" target="_blank" rel="noopener" class="iheu-image-features-button button button-primary">See all Features</a></p>

            </div>
            <div class="feature-section">
                <h3>Have any Bugs or Suggestion</h3>
                <p>Your suggestions will make this plugin even better, Even if you get any bugs on Flipbox - Awesomes Flip Boxes Image Overlay so let us to know, We will try to solved within few hours</p>
                <p><a href="https://www.oxilab.org/contact-us" target="_blank" rel="noopener" class="image-features-button button button-primary">Contact Us</a>
                    <a href="https://wordpress.org/support/plugin/image-hover-effects-ultimate-visual-composer" target="_blank" rel="noopener" class="image-features-button button button-primary">Support Forum</a>
                </p>

            </div>

        </div>
<?php
    }
    public function __construct()
    {
        $this->admin_css();
        $this->Public_Render();
    }
}
