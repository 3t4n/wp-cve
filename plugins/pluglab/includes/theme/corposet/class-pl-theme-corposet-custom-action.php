<?php

class PL_Theme_Corposet_Custom_Action
{
    public function __construct()
    {
        add_action('corposet_social_icons', array( $this, 'social_icons' ), 10, 1);
        add_action('corposet_contact_icons', array( $this, 'contact_icons' ), 10, 1);
    }

    public function social_icons($class) {        ?>
	<ul class="<?php echo $class; ?>">
						<?php
                            $social_icons = get_theme_mod('corposet_social_icons', pluglab_get_social_icon_default());
                            $social_icons = json_decode($social_icons);
                        if ($social_icons != '') {
                            foreach ($social_icons as $social_item) {
                                $social_icon = ! empty($social_item->icon_value) ? apply_filters('corposet_translate_single_string', $social_item->icon_value, 'Header section') : '';
                                $social_link = ! empty($social_item->link) ? apply_filters('corposet_translate_single_string', $social_item->link, 'Header section') : ''; ?>
									<li><a class="btn-default" href="<?php echo esc_url($social_link); ?>"><i class="fa <?php echo esc_attr($social_icon); ?>"></i></a></li>
									<?php
                            }
                        }
                        ?>

					</ul>
		<?php
    }

    public function contact_icons()
    {
        $top_mail_icon        = get_theme_mod('top_mail_icon', 'fa-send-o');
        $top_header_mail_text = get_theme_mod('top_header_mail_text', 'youremail@gmail.com');
        /*
                * @todo: remove phone number
                */
        $top_phone_icon        = get_theme_mod('top_phone_icon', 'fa fa-phone');
        $top_header_phone_text = get_theme_mod('top_header_phone_text', '134-566-7680'); ?>
                  <ul class="left mail-phone">
                    <li><a href="mailto: <?php echo sanitize_email($top_header_mail_text); ?>"><i class="fa <?php echo $top_mail_icon; ?>"></i> <?php echo $top_header_mail_text; ?></a></li>
                    <li><a href="tel: <?php echo sanitize_email($top_header_phone_text); ?>"><i class="fa <?php echo $top_phone_icon; ?>"></i> <?php echo $top_header_phone_text; ?></a></li>
                  </ul>
                <?php
    }
}
