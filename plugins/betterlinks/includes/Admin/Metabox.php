<?php

namespace BetterLinks\Admin;

class Metabox {

    private $is_pro_enabled;
    public static function init() {
        $self = new self();
        $self->is_pro_enabled = apply_filters('betterlinks/pro_enabled', false);
        add_action('add_meta_boxes', [$self, 'add_auto_create_shortlink_teaser'], 10, 2);
        add_action('add_meta_boxes', [$self, 'add_affiliate_disclosure_teaser'], 10, 2);
    }

    public function add_affiliate_disclosure_teaser( $post_type, $post ) {
        if( !$this->is_pro_enabled && !$this->is_using_gutenberg_block() && in_array( $post_type, ['post', 'page'])) {
            add_meta_box('betterlinks-affiliate-disclosure-teaser', __('BetterLinks Affiliate Disclosure<span class="pro-badge">Pro</span>', 'betterlinks'), [$this, 'affiliate_disclosure_teaser'], $post_type, 'side', 'core');
        }
    }

    public function affiliate_disclosure_teaser($post) {
        ?>
         <div>
            <p><?php esc_html_e('This will allow you to add an Affiliate Link Disclosure in this '. $post->post_type, 'betterlinks'); ?></p>
            <div class="betterlinks-affiliate-link-disclosure">
            <div class="betterlinks-form-group betterlinks-form-flex">
                <label>
                    <?php esc_html_e('Affiliate Disclosure Position', 'betterlinks') ?>
                    <span class="pro-badge">
                        <?php esc_html_e('Pro', 'betterlinks') ?>
                    </span>
                </label>
                <select name="betterlinks_affiliate_disclosure_position" style="width: 100%;cursor: not-allowed;" disabled>
                    <option value="">Select Position</option>
                </select>
            </div>
            <div class="betterlinks-form-group betterlinks-form-flex">
                <label>
                    <?php esc_html_e('Affiliate Disclosure Text', 'betterlinks') ?>
                    <span class="pro-badge">
                        <?php esc_html_e('Pro', 'betterlinks') ?>
                    </span>
                </label>
                <textarea name="affiliate_disclosure_text" style="width: 100%;" disabled></textarea>
            </div>
            </div>
            <div>
            </div>
        </div>
        <style>
            .betterlinks-form-group {
                margin-bottom:1rem;
            }
            .betterlinks-form-group input,
            .betterlinks-form-group textarea {
                cursor: not-allowed;
            }
            .betterlinks-form-flex {
                display: flex;
                flex-direction: column;
            }
            span.pro-badge {
                background: linear-gradient(202deg,#2961ff 0%,#003be2 100%);
                color: #fff;
                border-radius: 2px;
                padding: 3px 6px;
                font-size: 10px;
                margin-left: 3px;
                line-height: 1;
                text-transform: uppercase;
                display: inline-flex;
                align-items: center;
                border-radius: 2px;
                transform: translateY(-10px);
            }
        </style>
        <?php
    }

    public function add_auto_create_shortlink_teaser( $post_type, $post ) {
        if( !$this->is_pro_enabled && !$this->is_using_gutenberg_block() && in_array( $post_type, ['post', 'page', 'product'])) {
            add_meta_box('betterlinks-auto-create-shortlink-teaser', __('BetterLinks Auto-Create Links<span class="pro-badge">Pro</span>', 'betterlinks'), [$this, 'auto_create_shortlink_teaser'], $post_type, 'side', 'core');
        }
    }

    public function auto_create_shortlink_teaser() {
        ?>  
        <div>
            <p><?php esc_html_e( 'A BetterLink for this post will be generated on publish', 'betterlinks' ) ?></p>
            <div class="betterlinks_auto_create_link_form">
                <div class="betterlinks-form-group">
                    <label>
                        <?php echo site_url() . '/' ?>
                        <span class="pro-badge">
                            <?php esc_html_e('Pro', 'betterlinks') ?>
                        </span>
                    </label>
                    <div style="display: flex; align-items: center;justify-content: space-between;">
                        <input 
                            type="text" 
                            name="betterlinks_auto_create_shortlinks" 
                            id="betterlinks_auto_create_shortlinks"
                            disabled
                        />
                    </div>
                </div>
                <div class="betterlinks-form-group betterlinks-form-flex">
                    <label>
                        <?php esc_html_e('BetterLinks Category', 'betterlinks') ?>
                        <span class="pro-badge">
                            <?php esc_html_e('Pro', 'betterlinks') ?>
                        </span>
                    </label>
                    <select name="betterlinks_auto_link_category" disabled style="cursor: not-allowed;">
                        <option value="">Select Category</option>
                    </select>
                </div>
                <div class="betterlinks-form-group betterlinks-form-flex">
                    <label>
                        <?php esc_html_e('Redirect Type', 'betterlinks') ?>
                        <span class="pro-badge">
                            <?php esc_html_e('Pro', 'betterlinks') ?>
                        </span>
                    </label>
                    <select name="betterlinks_auto_link_redirect_type" disabled style="cursor: not-allowed;">
                        <option value="">Select Type</option>
                    </select>
                </div>
            </div>
        </div>
        <style>
            .betterlinks-form-group {
                margin-bottom:1rem;
            }
            .betterlinks-form-group input {
                cursor: not-allowed;
            }
            .betterlinks-form-flex {
                display: flex;
                flex-direction: column;
            }
            #betterlinks_auto_create_shortlinks{
                flex-grow: 1;
            }
            span.pro-badge {
                background: linear-gradient(202deg,#2961ff 0%,#003be2 100%);
                color: #fff;
                border-radius: 2px;
                padding: 3px 6px;
                font-size: 10px;
                margin-left: 3px;
                line-height: 1;
                text-transform: uppercase;
                display: inline-flex;
                align-items: center;
                border-radius: 2px;
                transform: translateY(-10px);
            }
        </style>
        <?php
    }

    public function is_using_gutenberg_block() {
        $current_screen = get_current_screen();
        $is_using_block_editor = $current_screen->is_block_editor || (function_exists( 'is_gutenberg_page' ) && is_gutenberg_page());
        return $is_using_block_editor;
    }
}