<?php
/**
 * Admin Support Page
*/

class PWC_Admin_Page {
    /**
     * Contructor
    */
    public function __construct(){
        add_action( 'admin_menu', [ $this, 'pwc_plugin_admin_page' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'pwc_admin_page_assets' ] );
    }

    // Admin Assets
    public function pwc_admin_page_assets($screen) {
        if( 'tools_page_post-words-count' == $screen ) {
            wp_enqueue_style( 'admin-asset', plugins_url('assets/css/admin.css', __FILE__ ) );
        }
    }

    // Admin Page
    public function pwc_plugin_admin_page(){
        add_submenu_page( 'tools.php', __('Post Words Counter','post-words-count'), __('Post Words Counter','post-words-count'), 'manage_options', 'post-words-count', [ $this, 'pwc_admin_page_content_callback' ] );
    }
    public function pwc_admin_page_content_callback(){
        ?>
            <div class="admin_page_container">
                <div class="plugin_head">
                    <div class="head_container">
                        <h1 class="plugin_title"> <?php echo esc_html("Post Words Counter and Thumbnail Checker", "post-words-count"); ?> </h1>
                        <h4 class="plugin_subtitle"><?php echo esc_html("A Simple Plugin to Check out Your Posts Thumbanil Existance and Display Total Post Words.", "post-words-count"); ?></h4>
                        <div class="support_btn">
                            <a href="https://www.patreon.com/dev_zak" target="_blank" rel="nofollow noreferrer" style="background: #EA4335"><?php echo esc_html("Donate Me", "post-words-count"); ?></a>
                            <a href="https://makegutenblock.com/contact" target="_blank" rel="nofollow noreferrer" style="background: #D37F00"><?php echo esc_html("Get Support", "post-words-count"); ?></a>
                            <a href="https://wordpress.org/plugins/post-words-count/#reviews" target="_blank" rel="nofollow noreferrer" style="background: #0174A2"><?php echo esc_html("Rate Plugin", "post-words-count"); ?></a>
                        </div>
                    </div>
                </div>
                <div class="plugin_body">
                    <div class="doc_video_area">
                        <div class="doc_video">
                          <img src="<?php echo plugin_dir_url(__FILE__); ?>./assets/img/screenshot-1.png">
                        </div>
                    </div>
                    <div class="support_area">
                        <div class="single_support">
                            <h4 class="support_title"><?php echo esc_html("Freelance Work", "post-words-count"); ?></h4>
                            <div class="support_btn">
                                <a href="https://www.fiverr.com/users/devs_zak/" target="_blank" rel="nofollow noreferrer" style="background: #1DBF73"><?php echo esc_html("@Fiverr", "post-words-count"); ?></a>
                                <a href="https://www.upwork.com/freelancers/~010af183b3205dc627" target="_blank" rel="nofollow noreferrer" style="background: #14A800"><?php echo esc_html("@UpWork", "post-words-count"); ?></a>
                            </div>
                        </div>
                        <div class="single_support">
                            <h4 class="support_title"><?php echo esc_html("Get Support", "post-words-count"); ?></h4>
                            <div class="support_btn">
                                <a href="https://makegutenblock.com/contact" target="_blank" rel="nofollow noreferrer" style="background: #002B42"><?php echo esc_html("Contact", "post-words-count"); ?></a>
                                <a href="mailto:zbinsaifullah@gmail.com" style="background: #EA4335"><?php echo esc_html("Send Mail", "post-words-count"); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
    }
}
 new PWC_Admin_Page();
