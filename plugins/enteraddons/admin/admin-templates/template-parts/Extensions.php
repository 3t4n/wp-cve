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
trait Extensions {

    public function extensions_tab_content() {
        
        $extensions = $this->getExtensions();
        ?>
            <div data-tab="extensions">
                <div class="container">
                    <!-- Element Wrapper -->
                    <div class="elements-wrap">
                        <?php 
                        // Elements
                        if( !empty( $extensions ) ):
                            $is_pro_active = \Enteraddons\Classes\Helper::is_pro_active();
                            $activation = get_option(ENTERADDONS_OPTION_KEY);
                                                        
                        foreach( $extensions as $element ):

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
                            if( isset( $activation['extensions'] ) && is_array( $activation['extensions'] ) ) {
                                if( in_array( $elementName , $activation['extensions'] ) ) {
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
                                    <input type="checkbox" class="onoffswitch-checkbox" id="<?php echo esc_attr( $elementName ); ?>" <?php echo esc_attr( $disabled.' '.$checked ); ?> name="enteraddons_extensions[]" value="<?php echo esc_attr( $elementName ); ?>">
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
                    <!-- End Element Wrapper -->
                </div>
            </div>
        <?php
    }

    public function getExtensions() {

        $obj =  new \Enteraddons\Inc\Extensions_List();
        $extensions = $obj->getAllElements();
        return $extensions;
    }

}
?>