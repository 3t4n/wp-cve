<?php
namespace OACS\SolidPostLikes\Views;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

if ( ! defined( 'WPINC' ) ) { die; }
class SolidPostLikesAdmin
{

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name    = $plugin_name;
        $this->version        = $version;
    }

    public function oacs_load_carbon_fields()
    {
        \Carbon_Fields\Carbon_Fields::boot();
    }

    public function oacs_get_post_types()
    {
        $post_types = get_post_types(array('public' => true), 'objects');

        $option_post_types = ['none' => __('None', 'oaspl')]; // Add a 'none' option
        foreach ($post_types as $post_type) {
            $option_post_types[$post_type->name] = $post_type->label;
        }

        return $option_post_types;
    }

    public function enqueue_styles()
    {

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'admin/css/solid-post-likes-admin.css', array(), $this->version, 'all');

    }

    public function enqueue_scripts()
    {

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'admin/js/solid-post-likes-admin.js', array('jquery'), $this->version, false);

    }

    /* Create a settings page as submenu item for the general WordPress settings and a checkbox*/
    public function oacs_add_plugin_settings_page()
    {

        Container::make('theme_options', __('oacs SPL', 'oaspl'))
            ->where('current_user_role', 'IN', array('administrator'))
            ->set_icon('dashicons-heart')
            ->add_tab(
                __('Likes', 'oaspl'), array(
                    Field::make('checkbox', 'oacs_spl_show_likes', __('Enable Likes', 'oaspl'))
                        ->set_option_value('yes')
                        ->set_help_text(__('Masterswitch. Uncheck this to disable likes completely. Likes can also be toggled per post.', 'oaspl')),
                    Field::make('multiselect', 'oacs_spl_available_posts', __('Enable Likes for the following Post Types', 'oaspl'))
                    ->add_options(array($this, 'oacs_get_post_types'))
                    ->set_help_text(__('You can choose which post types should display likes. <br>If you only want to use the shortcode for displaying likes, select no post types here. <br>Shortcode usage: <code>[oacsspl]</code>', 'oaspl')),
                    Field::make('text', 'oacs_spl_disable_likes', __('Disable Likes for Specific Posts', 'oaspl'))
                        ->set_help_text(__('You may want to disable likes for some posts i.e WooCommerce cart. Enter post ID values separated by a comma, no spaces.', 'oaspl')),
                    Field::make('separator', 'oacs_spl_master_separator', __('', 'oaspl')),
                    Field::make('select', 'oacs_spl_like_position', __('Like Position in Posts and Comments', 'oaspl'))
                        ->set_options(
                            array(
                                __('top', 'oaspl') => 'top',
                                __('bottom', 'oaspl') => 'bottom',
                            )
                        )
                        ->set_help_text(__('Top = Likes display before main post content / comment. <br> Bottom = Likes display after main post content / comment. Note that the main post content is everything before the <code>!--more-</code> tag', 'oaspl' )),
                    Field::make('checkbox', 'oacs_spl_likes_for_comments_setting', __('Enable Comment Likes', 'oaspl'))
                    ->set_option_value('yes')
                    ->set_help_text(__('When active, comments will display regardless of whether post likes are active.', 'oaspl')),
                    Field::make('checkbox', 'oacs_spl_show_counter', __('Show Like Count', 'oaspl'))
                        ->set_option_value('yes')
                        ->set_help_text(__('Displays the amount of likes as number next to the like icon in the frontend.', 'oaspl' )),
                    Field::make('checkbox', 'oacs_spl_hide_counter_when_zero', __('Hide Like Counter of Zero', 'oaspl'))
                    ->set_option_value('yes')
                    ->set_help_text(__('This hides the like counter initially when the like count is 0. Setting the counter from 1 >> 0 will display the zero for confirmation. Reload the page to see that the zero is hidden.', 'oaspl' )),
                    Field::make('checkbox', 'oacs_spl_show_user_profile_likes', __('Show Liked Posts in Backend User Profile', 'oaspl'))
                    ->set_option_value('yes')
                    ->set_help_text(__('Displays all liked post of a user in the backend profile: <code>/wp-admin/profile.php</code> ', 'oaspl' )),
                    Field::make('text', 'oacs_spl_like_text', __('Show Like Text', 'oaspl'))
                        ->set_help_text(__('This text displays after the like counter if the post is currently unliked.<br><b>Format:</b>A-Z,0-9, spaces and <code>_!?-:.,</code> are allowed.', 'oaspl' ))
                        ->set_width(50)
                        ->set_attribute('maxLength', 255)
                        ->set_attribute('placeholder', 'Like')
                        ->set_attribute('pattern', '^[\sa-zA-Z0-9_!?.,:-]*$'),
                    Field::make('text', 'oacs_spl_unlike_text', __('Show Unlike Text', 'oaspl'))
                        ->set_help_text(__('This text displays after the like counter if the post is liked.', 'oaspl' ))
                        ->set_width(50)
                        ->set_attribute('maxLength', 255)
                        ->set_attribute('placeholder', 'Unlike')
                        ->set_attribute('pattern', '^[\sa-zA-Z0-9_!?.,:-]*$'),
                )
            )
            ->add_tab(
                __('Button Style', 'oaspl'), array(
                    Field::make('icon', 'oacs_spl_like_icon', __('Liked Icon', 'oaspl'))
                    ->add_icomoon_options()
                    ->set_width(50),
                    Field::make('color', 'oacs_spl_like_icon_color', __('Like Icon Color', 'oaspl'))
                        ->set_width(25),
                    Field::make('text', 'oacs_spl_like_icon_padding', __('Like Icon Padding', 'oaspl'))
                        ->set_help_text(__(
                            'Use this to manually align icons. <code>10px 12px 14px 16px</code><br> translates to: <br>padding-top: 10px;<br>
				padding-right: 12px;<br>
				padding-bottom: 14px;<br>
				padding-left: 16px;<br>'
                , 'oaspl' ))
                        ->set_attribute('placeholder', '6px 3px 0px 0px')
                        ->set_default_value('6px 3px 0px 0px')
                        ->set_attribute('maxLength', 100)
                        ->set_attribute('pattern', '^[\sa-zA-Z0-9_!?.,:-]*$')
                        ->set_width(50),
                    Field::make('text', 'oacs_spl_like_icon_size', __('Like Icon Size', 'oaspl'))
                        ->set_help_text(__('Enter size in px or em: i.e. <code>1em</code> or <code>12px</code>.', 'oaspl' ))
                        ->set_attribute('placeholder', '1em')
                        ->set_default_value('1em')
                        ->set_width(50)
                        ->set_attribute('pattern', '^[\sa-zA-Z0-9_!?.,:-]*$'),
                    Field::make('separator', 'oacs_spl_unlike_separator', __('', 'oaspl')),
                    Field::make('icon', 'oacs_spl_unlike_icon', __('Unliked Icon', 'oaspl'))
                    ->add_icomoon_options()
                        ->set_width(50),
                    Field::make('color', 'oacs_spl_unlike_icon_color', __('Unlike Icon Color', 'oaspl'))
                        ->set_width(25),
                    Field::make('text', 'oacs_spl_unlike_icon_padding', __('Unlike Icon Padding', 'oaspl'))
                        ->set_help_text(__(
                            'Use this to manually align icons. <code>10px 12px 14px 16px</code><br> translates to: <br>padding-top: 10px;<br>
				padding-right: 12px;<br>
				padding-bottom: 14px;<br>
				padding-left: 16px;<br>'
                , 'oaspl' ))
                        ->set_attribute('maxLength', 100)
                        ->set_attribute('placeholder', '6px 3px 0px 0px')
                        ->set_default_value('6px 3px 0px 0px')
                        ->set_attribute('pattern', '^[\sa-zA-Z0-9_!?.,:-]*$')
                        ->set_width(50),
                    Field::make('text', 'oacs_spl_unlike_icon_size', __('Unlike Icon Size', 'oaspl'))
                        ->set_help_text(__('Enter size in px or em: i.e. <code>1em</code> or <code>12px</code>.', 'oaspl' ))
                        ->set_attribute('placeholder', '1em')
                        ->set_default_value('1em')
                        ->set_attribute('pattern', '^[\sa-zA-Z0-9_!?.,:-]*$')
                        ->set_width(50),
                )
            )

            ->add_tab(
                __('Text Style', 'oaspl' ), array(
                    Field::make('color', 'oacs_spl_counter_color', __('Counter Color', 'oaspl'))
                        ->set_width(25),
                    Field::make('text', 'oacs_spl_counter_padding', __('Counter Padding'), 'oaspl')
                        ->set_help_text(__(
                            'Use this to manually align the counter. <code>10px 12px 14px 16px</code><br> translates to: <br>padding-top: 10px;<br>
				padding-right: 12px;<br>
				padding-bottom: 14px;<br>
				padding-left: 16px;<br>'
                , 'oaspl' ))
                        ->set_attribute('maxLength', 100)
                        ->set_attribute('pattern', '^[\sa-zA-Z0-9_!?.,:-]*$')
                        ->set_width(50),
                    Field::make('text', 'oacs_spl_counter_size', __('Counter Size'), 'oaspl')
                        ->set_help_text(__('Enter size in px or em: i.e. <code>1em</code> or <code>12px</code>.', 'oaspl' ))
                        ->set_attribute('pattern', '^[\sa-zA-Z0-9_!?.,:-]*$')
                        ->set_width(25),
                    Field::make('separator', 'oacs_spl_counter_separator', __(''), 'oaspl'),
                    Field::make('color', 'oacs_spl_like_text_color', __('Like Text Color'), 'oaspl')
                        ->set_width(25),
                    Field::make('text', 'oacs_spl_like_text_padding', __('Like Text Padding'), 'oaspl')
                        ->set_help_text(__(
                            'Use this to manually align the counter. <code>10px 12px 14px 16px</code><br> translates to: <br>padding-top: 10px;<br>
				padding-right: 12px;<br>
				padding-bottom: 14px;<br>
				padding-left: 16px;<br>'
                , 'oaspl' ))
                        ->set_attribute('maxLength', 100)
                        ->set_attribute('pattern', '^[\sa-zA-Z0-9_!?.,:-]*$')
                        ->set_width(50),
                    Field::make('text', 'oacs_spl_like_text_size', __('Like Text Size'), 'oaspl')
                        ->set_help_text(__('Enter size in px or em: i.e. <code>1em</code> or <code>12px</code>.', 'oaspl' ))
                        ->set_attribute('pattern', '^[\sa-zA-Z0-9_!?.,:-]*$')
                        ->set_width(25),
                    Field::make('separator', 'oacs_spl_like_text_separator', __(''), 'oaspl'),
                    Field::make('color', 'oacs_spl_unlike_text_color', __('Unlike Text Color'), 'oaspl')
                        ->set_width(25),
                    Field::make('text', 'oacs_spl_unlike_text_padding', __('Unlike Text Padding'), 'oaspl')
                        ->set_help_text(__(
                            'Use this to manually align the counter. <code>10px 12px 14px 16px</code><br> translates to: <br>padding-top: 10px;<br>
				padding-right: 12px;<br>
				padding-bottom: 14px;<br>
				padding-left: 16px;<br>'
                , 'oaspl' ))
                        ->set_attribute('maxLength', 100)
                        ->set_attribute('pattern', '^[\sa-zA-Z0-9_!?.,:-]*$')
                        ->set_width(50),
                    Field::make('text', 'oacs_spl_unlike_text_size', __('Unlike Text Size'), 'oaspl')
                        ->set_help_text(__('Enter size in px or em: i.e. <code>1em</code> or <code>12px</code>.', 'oaspl' ))
                        ->set_attribute('pattern', '^[\sa-zA-Z0-9_!?.,:-]*$')
                        ->set_width(25),
                )
            )

            ->add_tab(
                __('Developer', 'oaspl' ), array(

                    Field::make( 'html', 'oacs_spl_dev_tab_info_text' )
                    ->set_html( '<h3>For developers:</h3><p>Using these settings is experimental, since every theme is different. Use caution, these features are not covered by our support. Take a backup of your site and database before proceeding.</p>', 'oaspl'),

                    Field::make('text', 'oacs_spl_hook_post_hook', __('Set Like Position via Hook for Posts', 'oaspl'))
                    ->set_help_text(__('You can place the likes by entering a theme action hook manually here instead of using "Top" or "Bottom". <br>If this is empty: <code>the_content</code> is used to append / prepend the likes.', 'oaspl' ))
                    ->set_attribute('maxLength', 255)
                    ->set_attribute('pattern', '^[\sa-zA-Z0-9_!?.,:-]*$')
                    ->set_width(50),
                Field::make('text', 'oacs_spl_hook_woo_hook', __('Set Like Position via Hook for WooCommerce Products', 'oaspl'))
                    ->set_help_text(__('You can place the WooCommerce likes by entering a theme action hook manually here instead of using. <br>If this is empty: <code>woocommerce_single_product_summary</code> is used.', 'oaspl' ))
                    ->set_attribute('maxLength', 255)
                    ->set_attribute('pattern', '^[\sa-zA-Z0-9_!?.,:-]*$')
                    ->set_width(50),

                    Field::make( 'html', 'oacs_spl_dev_tab_split_text' )
                    ->set_html( '<p><b>Set Likes Manually</b></p>', 'oaspl'),

                    Field::make('text', 'oacs_spl_set_like_count_post', __('Target Post ID'), 'oaspl')
                    ->set_help_text(__('Enter one (not multiple) post ID number to target a post.<br><strong>Format</strong>: Number.', 'oaspl'))
                    ->set_attribute('placeholder', '123')
                    ->set_attribute('maxLength', 255)
                    ->set_attribute('pattern', '^[\0-9]*$')
                    ->set_width(50),
                Field::make('text', 'oacs_spl_set_like_count', __('Like Count'), 'oaspl')
                    ->set_help_text(__('Enter desired new like count. <br><strong>Format</strong>: Number.', 'oaspl' ))
                    ->set_attribute('placeholder', '100')
                    ->set_attribute('maxLength', 255)
                    ->set_attribute('pattern', '^[\0-9]*$')
                    ->set_width(50),

                    Field::make( 'html', 'oacs_spl_dev_tab_split_text_cache' )
                    ->set_html( '<p><b>Full Page Caching support</b></p>', 'oaspl'),

                    Field::make('checkbox', 'oacs_spl_cache_support', __('Enable full page caching support'), 'oaspl')
                    ->set_option_value('yes')
                    ->set_help_text(__('Enabling this will run additional Javascript queries on page load for each like button to ensure correct like display with full page cache enabled.', 'oaspl' )),
                )
            )
              ->add_tab(
                __('Deinstallation', 'oaspl' ), array(
                     Field::make('checkbox', 'oacs_spl_deinstall_delete', __('Delete all plugin data on deinstall'), 'oaspl')
                        ->set_option_value('yes')
                        ->set_help_text(__('Enable this to delete all plugin data on plugin removal. Plugin deactivation will keep all data.', 'oaspl' )),
                )
            );
    }

   public function oacs_register_custom_icon_field_provider() {
        $provider_id = 'icomoon';

        \Carbon_Fields\Carbon_Fields::instance()->ioc['icon_field_providers'][ $provider_id ] = function( $container ) {
            return new Custom_Icon_Provider();
        };

        \Carbon_Field_Icon\Icon_Field::add_provider( [ $provider_id ] );
    }
}