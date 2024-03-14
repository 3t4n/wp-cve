<?php
if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly
class Moove_Metabox_Manipulator {

    function __construct(){
        add_action( 'admin_menu', array( &$this, 'moove_meta_box_functions') );
        add_filter( 'rest_prepare_taxonomy', function( $response, $taxonomy ){
            $options = get_option( 'moove_radioselect' );
            if ( ! empty( $options ) ) :
                foreach ( $options as $post_type => $post_settings ) :
                    if ( isset( $post_settings[$taxonomy->name] ) && 'default' !== $post_settings[$taxonomy->name]['select'] ) :          
                        $response->data['visibility']['show_ui'] = false;
                    endif;
                endforeach;
            endif;           
            return $response;
        }, 10, 2 );
    }

    function moove_meta_box_functions(){
        $options = get_option( 'moove_radioselect' );
        if ( ! empty( $options ) ) :
            foreach ( $options as $post_type => $post_settings ) :
                foreach ( $post_settings as $taxonomy => $tax_setting ) :
                    if ( 'default' !== $tax_setting['select'] ) :
                        $_taxonomy = get_taxonomy( $taxonomy );
                        if ( ! is_taxonomy_hierarchical( $taxonomy ) ) :                            
                            if ( isset( $_taxonomy->labels ) ) :
                                remove_meta_box( 'tagsdiv-' . $taxonomy, $post_type, 'normal' );
                                add_meta_box(
                                    'tagsdiv-' . $taxonomy, $_taxonomy->labels->name,
                                    array( &$this, 'moove_create_metabox_radio' ),
                                    $post_type,
                                    'side',
                                    'default',
                                    array(
                                        'tax'       =>  $_taxonomy,
                                        'post_type' =>  $post_type,
                                        'type'      =>  $tax_setting['select'],
                                        'options'   =>  $options,
                                        '__back_compat_meta_box' => false,
                                    )
                                );
                            endif;
                        else:
                            if ( isset( $_taxonomy->labels ) ) :
                                remove_meta_box( $taxonomy . 'div', $post_type, 'normal' );
                                add_meta_box(
                                    $taxonomy . 'div', $_taxonomy->labels->name,
                                    array( &$this, 'moove_create_metabox_radio' ),
                                    $post_type,
                                    'side',
                                    'default',
                                    array(
                                        'tax'       => $_taxonomy,
                                        'post_type' =>  $post_type,
                                        'type'      => $tax_setting['select'],
                                        'options'   => $options,
                                        '__back_compat_meta_box' => false,
                                    )
                                );
                            endif;
                        endif;
                    endif;
                endforeach;
            endforeach;
        endif;
    }
    //Callback to set up metabox
    function moove_create_metabox_radio( $post, $taxonomy ) {
        //Get taxonomy and terms
        $tax                = $taxonomy['args']['tax'];
        $btntype            = $taxonomy['args']['type'];
        $options            = $taxonomy['args']['options'];
        $post_type          = $taxonomy['args']['post_type'];
        $taxonomy           = $tax->name;
        $select_all_button  = '';
        if ( ! is_taxonomy_hierarchical( $taxonomy ) ) :
            $moove_id       = 'tagsdiv-' . $taxonomy;
            $hierarchical   = false;
        else:
            $moove_id       = 'taxonomy-' . $taxonomy ;
            $hierarchical   = true;
        endif;

        if ( 'category' === $taxonomy ) :
            $name           = 'post_'.$taxonomy.'[]';
            $is_category    = true;
        else:
            $name           = 'tax_input[' . $taxonomy . '][]';
            $is_category    = false;
        endif;

        $terms = get_terms( $taxonomy ,array('hide_empty' => 0));

        //Get current and popular terms
        $popular = get_terms( $taxonomy, array( 'orderby' => 'count', 'number' => 10, 'order' => 'DESC', 'hierarchical' => false ) );

        $postterms = wp_get_post_terms( $post->ID, $taxonomy, array( 'fields' => 'all' ));

        $current_terms_array = array();
         if ( 'checkbox' === $btntype && is_array($options) && isset($options[$post_type][$taxonomy]['selectall']) ) :
            if ( 'on' === $options[ $post_type ][ $taxonomy ]['selectall'] ) :
                if ( empty( $postterms ) ||( count( $postterms ) < count( $terms ) ) ) :
                    $select_class   = '';
                else:
                    $select_class   = 'moove-radioselect-deselect';
                endif;
                $select_all_button   =  '<a href="#" class="moove-radioselect-selectall ' . $select_class . ' ">';
                $select_all_button  .=  '<span>'.__('Select All' , 'moove' ).'</span>';
                $select_all_button  .=  '<span>'.__('Deselect All' , 'moove' ).'</span>';
                $select_all_button  .=  '</a><hr>';
            endif;
        endif;
        if ( is_array( $postterms ) ) :
            foreach ( $postterms as $single_term ) {
                $current_terms_array[] = $single_term->term_id;
            }
        endif;
        $_postterms = $postterms;
        $current = ( $postterms ? array_pop( $_postterms ) : false );
        $current = ( $current ? $current->term_id : 0 );
        $taxonomy_details = get_taxonomy( $taxonomy );

        $selected_default = isset( $options[$post_type][$taxonomy]['default'] ) && intval( $options[$post_type][$taxonomy]['default'] ) && !$postterms ? intval( $options[$post_type][$taxonomy]['default'] ) : -1;

        ?>
        <div id="<?php echo $moove_id ?>" data-taxonomy="<?php echo $taxonomy; ?>" class="categorydiv moove_updated_taxonomy_select_switcher moove_radioselect-<?php echo $btntype; ?>">
            <!-- Display tabs-->
            <ul id="<?php echo $taxonomy; ?>-tabs" class="category-tabs">
                <li class="tabs">
                    <a href="#<?php echo $taxonomy; ?>-all"><?php echo $tax->labels->all_items; ?></a>
                </li>
                <li class="hide-if-no-js">
                    <a href="#<?php echo $taxonomy; ?>-pop"><?php _e( 'Most Used' ); ?></a>
                </li>
            </ul>

            <!-- Display popular taxonomy terms -->
            <div id="<?php echo $taxonomy; ?>-pop" class="tabs-panel" style="display: none;">
                <ul id="<?php echo $taxonomy; ?>checklist-pop" data-hierarchical="<?php echo $hierarchical ? 'hierarchical' : 'not-hierarchical'; ?>" class="categorychecklist moove-tax-popular form-no-clear" >
                    <?php   foreach( $popular as $term ){
                        if ( $term || 'radio' === $btntype ) :
                            echo "<li id='popular-$taxonomy-$term->term_id' class='popular-category'><label class='selectit'>";
                            $value = ( ! $hierarchical ) ? $term->name : $term->term_id;
                            $id = "id='in-popular-$taxonomy-$term->term_id'";
                            $checked = checked( $current, $term->term_id, false );
                            if ( $btntype === 'radio' ) :
                               $checked = checked( $current, $term->term_id, false );
                            else :
                                if ( in_array( $term->term_id, $current_terms_array )  ) :
                                    $checked = checked( 1, 1, false );
                                endif;
                            endif;
                            echo "<input type='".$btntype."' {$id} value='$value' name='pop_tax_input[resource_tag_pop][]' />$term->name<br />";
                            echo "</label>";
                            $childrens = get_term_children( $term->term_id, $taxonomy );
                            if ( count( $childrens ) && 'radio' !== $btntype ) : ?>
                                <ul class="children" data-hierarchical="<?php echo $hierarchical ? 'hierarchical' : 'not-hierarchical'; ?>">
                                    <?php foreach ( $childrens as $children ) {
                                        $child = get_term_by( 'id', $children, $taxonomy );
                                        $value = ( ! $hierarchical ) ? $child->name : $child->term_id;
                                        $checked = '';
                                        $id = "id='in-popular-$taxonomy-$child->term_id'";
                                        if ( $btntype === 'radio' ) :
                                           $checked = '';
                                        else :
                                            if ( in_array( $child->term_id, $current_terms_array )  ) :
                                                $checked = '';
                                            endif;
                                        endif;
                                        echo "<li id='popular-$taxonomy-$child->term_id'><label class='selectit'>";
                                        echo "<input type='".$btntype."' {$id}" . $checked . "value='$value' />$child->name<br />";
                                        echo "</label></li>";
                                    } ?>
                                </ul>
                            <?php endif;
                            echo "</li>";
                        endif;
                    }?>
                </ul>
            </div>
            <!-- Display taxonomy terms -->
            <div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
                <?php echo $select_all_button; ?>
                <input type="hidden" name="<?php echo $name ?>" value="0">
                <ul id="<?php echo $taxonomy; ?>checklist" data-hierarchical="<?php echo $hierarchical ? 'hierarchical' : 'not-hierarchical'; ?>" data-wp-lists="list:<?php echo $taxonomy; ?>" class="categorychecklist moove-tax-mainchecklist form-no-clear">
                    <?php
                    $is_checked = false;
                    foreach( $terms as $term ) {
                        if ( $term->parent === 0 || 'radio' === $btntype ) :
                            echo "<li id='$taxonomy-$term->term_id'><label class='selectit'>";
                            $value = ( ! $hierarchical ) ? $term->name : $term->term_id;
                            $checked = checked( $current, $term->term_id, false );
                            $id = "id='in-$taxonomy-$term->term_id'";
                            if ( $btntype === 'radio' ) :
                                if ( ! $postterms && $selected_default !== -1 ) :
                                    $checked = checked( $selected_default, $term->term_id, false );
                                else :
                                    $checked = checked( $current, $term->term_id, false );
                                endif;
                            else :

                                if ( ! $postterms && $selected_default !== -1 ) :
                                    $checked = checked( $selected_default, $term->term_id, false );
                                else :
                                    if ( in_array( $term->term_id, $current_terms_array )  ) :
                                        $checked = checked( 1, 1, false );
                                    endif;
                                endif;

                                
                            endif;
                            echo "<input type='".$btntype."' {$id} name='{$name}'" . $checked . "value='$value' />$term->name<br />";
                            echo "</label>";
                            $childrens = get_term_children( $term->term_id, $taxonomy );
                            if ( count( $childrens ) && 'radio' !== $btntype ) : ?>
                                <ul class="children" data-hierarchical="<?php echo $hierarchical ? 'hierarchical' : 'not-hierarchical'; ?>">
                                    <?php foreach ( $childrens as $children ) {
                                        $child = get_term_by( 'id', $children, $taxonomy );
                                        $value = ( ! $hierarchical ) ? $child->name : $child->term_id;
                                        $checked = checked( $current, $child->term_id, false );
                                        $id = "id='in-$taxonomy-$child->term_id'";
                                        if ( $btntype === 'radio' ) :
                                           $checked = checked( $current, $child->term_id, false );
                                        else :
                                            if ( in_array( $child->term_id, $current_terms_array )  ) :
                                                $checked = checked( 1, 1, false );
                                            endif;
                                        endif;
                                        echo "<li id='$taxonomy-$child->term_id'><label class='selectit'>";
                                        echo "<input type='".$btntype."' {$id} name='{$name}'" . $checked . "value='$value' />$child->name<br />";
                                        echo "</label></li>";
                                    } ?>
                                </ul>
                            <?php endif;
                            echo "</li>";
                            if ( $current == $term->term_id ) :
                                $is_checked = true;
                            endif;
                        endif;
                    }?>
                    <?php if ( $is_checked && ! $is_category ) : ?>
                        <?php if ( apply_filters( 'remove_selection_visible', true ) ) : ?>
                            <?php if ( $btntype === 'radio' ) : ?>
                                <li>
                                    <label class='selectit'>
                                        <input type='radio' name='<?php echo $name; ?>' value='' />
                                            <strong><?php _e( 'Remove selection', 'moove' ) ?></strong>
                                        <br />
                                    </label>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <?php 
                $tax_singular_label = isset( $taxonomy_details->labels->singular_name ) ? $taxonomy_details->labels->singular_name : 'Taxonomy Term';
            ?>

            <?php if ( current_user_can( $taxonomy_details->cap->edit_terms ) ) : ?>
                <div id="<?php echo $taxonomy; ?>-adder" class="wp-hidden-children moove-add-taxonomies">
                    <a id="<?php echo $taxonomy; ?>-add-toggle" href="#<?php echo $taxonomy; ?>-add" class="hide-if-no-js taxonomy-add-new ">
                        <?php
                            /* translators: %s: add new taxonomy label */
                            printf( __( '+ %s' ), $taxonomy_details->labels->add_new_item );
                        ?>
                    </a>
                    <p id="<?php echo $taxonomy; ?>-add" class="category-add wp-hidden-child">
                        <label class="screen-reader-text" for="new<?php echo $taxonomy; ?>"><?php echo $taxonomy_details->labels->add_new_item; ?></label>
                        <input type="text" name="new<?php echo $taxonomy; ?>" id="new<?php echo $taxonomy; ?>" class="form-required form-input-tip" value="<?php echo esc_attr( $taxonomy_details->labels->new_item_name ); ?>" aria-required="true"/>
                        <label class="screen-reader-text" for="new<?php echo $taxonomy; ?>_parent">
                            <?php echo $taxonomy_details->labels->parent_item_colon; ?>
                        </label>
                        <?php
                        if ( is_taxonomy_hierarchical( $taxonomy ) ) :
                            $parent_dropdown_args = array(
                                'taxonomy'         => $taxonomy,
                                'hide_empty'       => 0,
                                'name'             => 'new' . $taxonomy . '_parent',
                                'orderby'          => 'name',
                                'hierarchical'     => 1,
                                'show_option_none' => '&mdash; ' . $taxonomy_details->labels->parent_item . ' &mdash;',
                            );

                            $parent_dropdown_args = apply_filters( 'post_edit_category_parent_dropdown_args', $parent_dropdown_args );

                            wp_dropdown_categories( $parent_dropdown_args );
                        endif;    
                        ?>
                        <input type="button" id="<?php echo $taxonomy; ?>-add-submit" data-wp-lists="add:<?php echo $taxonomy; ?>checklist:<?php echo $taxonomy; ?>-add" class="button category-add-submit" value="<?php echo esc_attr( $taxonomy_details->labels->add_new_item ); ?>" />
                        <?php wp_nonce_field( 'add-' . $taxonomy, '_ajax_nonce-add-' . $taxonomy, false ); ?>
                        <span id="<?php echo $taxonomy; ?>-ajax-response"></span>
                    </p>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
$moove_metabox_manipulator = new Moove_Metabox_Manipulator();