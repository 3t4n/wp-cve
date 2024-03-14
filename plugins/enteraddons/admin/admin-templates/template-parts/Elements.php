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

trait Elements {

    public function elements_tab_content() {

        $elements = $this->getElements();
        ?>
            <div data-tab="elements">
                <div class="container">
                    <!-- Element Wrapper -->

                    <div class="filter-btn-wrapper">
                        <div class="filter-btn-group">
                            <span data-btn="enable" class="filter-btn-item enable-btn"><?php esc_html_e( 'Enable All', 'enteraddons' ); ?></span>
                            <span data-btn="disable" class="filter-btn-item disable-btn"><?php esc_html_e( 'Disable All', 'enteraddons' ); ?></span>
                        </div>
                        <div class="filter-btn-group">
                            <span data-type-btn="free" class="filter-btn-item free-btn"><?php esc_html_e( 'Free', 'enteraddons' ); ?></span>
                            <span data-type-btn="pro" class="filter-btn-item pro-btn"><?php esc_html_e( 'Pro', 'enteraddons' ); ?></span>
                        </div>
                        <div class="filter-btn-search">
                            <input data-search type="text" placeholder="<?php esc_attr_e( 'Search Widget...', 'enteraddons' ); ?>" />
                        </div>
                    </div>

                    <div class="elements-wrap">
                        <?php 
                        // Elements
                        if( !empty( $elements ) ):
                            $is_pro_active = \Enteraddons\Classes\Helper::is_pro_active();
                            $activation = get_option(ENTERADDONS_OPTION_KEY);
                            
                        foreach( $elements as $element ):
                            // init $checkedItem
                            $checkedItem = "";

                            // 
                            $elementName = $element['name'];

                            $disabled = ''; 
                            $checked = '';

                            $itemWrapperClass ='lite-item ';
                            
                            //
                            if( !empty( $element['is_pro'] ) ) {
                                $itemWrapperClass ='pro-item ';
                            }

                            if( empty( $element['is_pro'] ) || $is_pro_active  ) {
                                $itemWrapperClass .='activeable-element ';
                            }

                            if( !empty( $element['is_pro'] ) && !$is_pro_active  ) {
                                $disabled = "disabled";
                                $itemWrapperClass .='pro-item-demo ';
                                $checked = "";
                            }

                            //
                            if( isset( $activation['widgets'] ) && is_array( $activation['widgets'] ) ) {
                                if( in_array( $elementName , $activation['widgets'] ) ) {
                                    $checkedItem = "active";
                                    $checked = "checked";
                                } else {
                                    $checkedItem = "";
                                    $checked = "";
                                }
                            }

                        ?>
                        <div class="single-element <?php echo esc_attr( $itemWrapperClass.$checkedItem ); ?>" data-filter-item data-filter-name="<?php echo esc_html( strtolower($element['label']) ); ?>">
                            <div class="single-element-inner">
                                <?php
                                $text = esc_html__( 'Free', 'enteraddons' );
                                $typeClass = 'free-element';
                                if( !empty( $element['is_pro'] ) ) {
                                    $text = esc_html__( 'Pro', 'enteraddons' );
                                    $typeClass = 'pro-element';
                                }
                                
                                echo '<span class="element-ribbon '.esc_attr( $typeClass ).'">'.esc_html( $text ).'</span>';
                                
                                ?>
                                <div class="custom-checkbox">
                                    <input type="checkbox" class="onoffswitch-checkbox" id="<?php echo esc_attr( $elementName ); ?>"  name="enteraddons_widgets[]" value="<?php echo esc_attr( $elementName ); ?>" <?php echo esc_attr( $disabled.' '.$checked ); ?>>
                                    <div class="checkmark"></div>
                                </div>
                                <?php 
                                if( !empty( $element['icon'] ) ){
                                    echo '<div class="icon"><i class="'.esc_attr( $element['icon'] ).'"></i></div>';
                                }
                                //
                                if( !empty( $element['label'] ) ) {
                                    echo '<div class="content"><h6>'.esc_html( $element['label'] ).'</h6></div>';
                                }
                                ?>
                            </div>
                        </div>
                        <?php 
                            endforeach;
                        endif;
                        ?>                        
                    </div>
                </div>
            </div>
        <?php
    }

    public function getElements() {
        
        $obj =  new \Enteraddons\Inc\Widgets_List();
        $widgets = $obj->getAllElements();
        return $widgets;

    }
}
?>