<?php

namespace AjaxPagination\Admin\Customizer;

class CustomizeDonate extends \WP_Customize_Control
{
    public function render_content()
    {
        ?>
        <br>
        <label>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
        </label>


        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick" />
            <input type="hidden" name="hosted_button_id" value="V3HCPQA8EGUQN" />
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
            <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
        </form>

        <label>
        <span class="customize-control-title"><?php  _e('Development plan:', 'wp-ajax-pagination'); ?></span>
        </label>
        <ul>
            <li>
                <?php  _e('Infinite scroll instead standard WP pagination', 'wp-ajax-pagination'); ?>
            </li>

        </ul>

        <?php
    }

}