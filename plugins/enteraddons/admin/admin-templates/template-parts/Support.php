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

trait Support{

    private $supports;
    
    public function support_tab_content() {
        ?>
            <div data-tab="support">
                <div class="container">
                    <!-- Document -->
                    <div class="document">
                        <?php 
                        if( !empty( $this->supports ) ):
                            foreach( $this->supports as $support ):
                        ?>
                        <!-- Single Box -->
                        <div class="single-box">
                            <?php 
                            if( !empty( $support['icon'] ) ):
                            ?>
                            <div class="icon">
                                <i class="<?php echo esc_attr( $support['icon'] ); ?>"></i>
                            </div>
                            <?php 
                            endif;
                            ?>
                            <div class="content">
                                <?php 
                                if( !empty( $support['title'] ) ){
                                    echo '<h6>'.esc_html( $support['title'] ).'</h6>';
                                }
                                //
                                if( !empty( $support['desc'] ) ) {
                                    echo '<p>'.esc_html( $support['desc'] ).'</p>';
                                }
                                //
                                if( !empty( $support['url'] ) ){
                                    echo '<a href="'.esc_url( $support['url'] ).'" class="btn-text">'.esc_html( 'Start Reading', 'enteraddons' ).' <i class="fa fa-long-arrow-right"></i></a>';
                                }
                                ?>
                            </div>
                        </div>
                        <!-- End Single Box -->
                        <?php
                            endforeach;
                        endif;
                        ?>
                        
                    </div>
                    <!-- End Document -->
                    <?php do_action( 'ea_admin_support_tab_after_content' ); ?>
                </div>
            </div>
        <?php
    }

    public function getSupports( $supports ) {
        $this->supports = $supports;
    }


}
