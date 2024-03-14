<?php

/**
 * Class Furgonetka_Admin_View - views for admin pages
 *
 * @package    Furgonetka
 * @author     Furgonetka.pl <woocommerce@furgonetka.pl>
 */
class Furgonetka_Admin_View
{
    /**
     * Render modal
     *
     * @return void
     */
    public function render_modal()
    {
        ?>
        <div class="furgonetka-modal furgonetka-modal-hidden">
            <div class="furgonetka-iframe-container">
                <div class="furgonetka-iframe" id="furgonetka-iframe">
                    <iframe src="about:blank" width="1000" height="900"></iframe>
                    <a href="#" class="furgonetka-iframe-exit" id="furgonetka-iframe-exit">
                        <svg viewport="0 0 12 12" enable-background="new 0 0 12 12" version="1.1" xmlns="http://www.w3.org/2000/svg">
                            <line x1="1" y1="11" x2="11" y2="1" stroke="#fff" stroke-linecap="round" stroke-width="2"></line>
                            <line x1="1" y1="1" x2="11" y2="11" stroke="#fff" stroke-linecap="round" stroke-width="2"></line>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="furgonetka-iframe-backdrop"></div>
        </div>
        <?php
    }
}