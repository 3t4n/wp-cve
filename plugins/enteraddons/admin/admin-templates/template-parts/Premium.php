<?php
namespace Enteraddons\Admin;
/**
 * Enteraddons admin
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

trait Premium {

    private $statistic;
    private $proUrl;

    public function premium_tab_content() {

        if( \Enteraddons\Classes\Helper::is_pro_active() ) {
            return;
        }

        ?>
        <div data-tab="premium">
            <div class="container">
                <?php 
                // Statistics
                if( !empty( $this->statistic ) ):
                ?>
                <div class="statistics">
                    <?php
                    // Single Statistics
                    foreach( $this->statistic as $statistic ) {
                        echo '<div class="single-statistic single-pro-info"><p><span style="color: '.esc_attr( $statistic['color_code'] ).';">'.esc_html( $statistic['number'] ).'</span>'.esc_html( $statistic['title'] ).'</p>';

                        if( !empty( $statistic['link'] ) && !empty( $statistic['text'] ) ) {
                            echo '<a target="_blank" class="btn s-btn" href="'.esc_url( $statistic['link'] ).'">'.esc_html( $statistic['text'] ).'</a>';
                        }
                        
                        echo '</div>';
                    }
                    ?>
                </div>
                <?php 
                endif;
                // Button
                if( !empty( $this->proUrl ) ):
                ?>
                <div class="text-center get-premium-btn">
                    <a href="<?php echo esc_url( $this->proUrl ); ?>" target="_blank" class="btn"><?php esc_html_e( 'Get Premium Version', 'enteraddons' ); ?></a>
                </div>
                <?php 
                endif;
                ?>
            </div>
        </div>
        <?php
    }

    public function getStatistic( $statistic ) {

        $this->proUrl = $statistic['pro_link'];
        $this->statistic = $statistic['statistic'];

    }
}