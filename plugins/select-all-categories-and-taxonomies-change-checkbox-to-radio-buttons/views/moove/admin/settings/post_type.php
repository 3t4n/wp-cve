<table class="moove-taxonomy-table">
    <tbody>
        <?php foreach ( json_decode( $data['taxonomies'] ) as $taxonomy) : ?>
            
            <tr class="moove-taxonomy-type-select" data-tax="<?php echo $taxonomy->name; ?>">
                <td>
                    <h4> <?php echo $taxonomy->labels->name; ?> </h4>
                </td>
                <td class="moove-tax-select-cnt">
                    <div class="moove-taxonomy-cnt">
                        <select name="moove_radioselect[<?php echo sanitize_title( $data['post_type'] );?>][<?php echo $taxonomy->name ?>][select]" id="<?php echo $taxonomy->name ;?>">

                            <option value="default" <?php echo isset( $data['options'][ sanitize_title( $data['post_type'] ) ][ $taxonomy->name ]['select']) && $data['options'][ sanitize_title( $data['post_type'] ) ][ $taxonomy->name ]['select'] == 'default' ? ' selected="selected"':''?>>
                                <?php _e( 'Default' , 'moove' ); ?>
                            </option>

                            <option value="radio" <?php echo isset( $data['options'][ sanitize_title( $data['post_type'] ) ][ $taxonomy->name ]['select']) && $data['options'][ sanitize_title( $data['post_type'] ) ][ $taxonomy->name ]['select'] == 'radio' ? ' selected="selected"':''?>>
                                <?php _e( 'Radio button' , 'moove' ); ?>
                            </option>

                            <option value="checkbox" <?php echo isset( $data['options'][ sanitize_title( $data['post_type'] ) ][ $taxonomy->name ]['select']) && $data['options'][ sanitize_title( $data['post_type'] ) ][ $taxonomy->name ]['select'] == 'checkbox' ? ' selected="selected"':''?>>
                                <?php _e( 'Checkbox' , 'moove' ); ?>
                            </option>

                        </select>
                        <?php
                            $checkbox_class = 'moove-hidden';
                            if ( isset( $data['options'][ sanitize_title( $data['post_type'] ) ][ $taxonomy->name ]['select']) ) :
                                if ( $data['options'][ sanitize_title( $data['post_type'] ) ][ $taxonomy->name ]['select'] === 'checkbox' ) :
                                    $checkbox_class = '';
                                endif;
                            endif;
                        ?>

                        <label for="moove_radioselect[<?php echo sanitize_title( $data['post_type'] );?>][<?php echo $taxonomy->name ?>][selectall]" class="moove_select_all_btn <?php echo $checkbox_class; ?>">
                            <?php _e( 'Include <span>"Select All"</span> button', 'moove' ); ?>
                            <input
                                type="checkbox"
                                value="on"
                                name="moove_radioselect[<?php echo sanitize_title( $data['post_type'] );?>][<?php echo $taxonomy->name; ?>][selectall]"
                                id="moove_radioselect[<?php echo sanitize_title( $data['post_type'] );?>][<?php echo $taxonomy->name; ?>][selectall]"
                                <?php echo isset( $data['options'][ sanitize_title( $data['post_type'] ) ][ $taxonomy->name ]['selectall']) && $data['options'][ sanitize_title( $data['post_type'] ) ][ $taxonomy->name ]['selectall'] === 'on' ? ' checked="checked"':''?>>
                        </label>                        
                    </div>
                    <!-- moove-taxonomy-cnt -->
                </td>

            </tr>
             <?php 
                $terms = get_terms( array(
                    'taxonomy'      => $taxonomy->name,
                    'hide_empty'    => false,
                ) );

            ?>
            <?php if ( $terms && is_array( $terms ) ) : ?>        
                <tr class="default-taxonomy-select <?php echo isset( $data['options'][ sanitize_title( $data['post_type'] ) ][ $taxonomy->name ]['select']) && $data['options'][ sanitize_title( $data['post_type'] ) ][ $taxonomy->name ]['select'] === 'default' ? ' hidden-tb-row"':''?>" data-tax="<?php echo $taxonomy->name; ?>">
                    <td>
                        <h4>Default </h4>
                    </td>
                    <td class="moove-tax-select-cnt">
                        <div class="moove-taxonomy-cnt">
                            <select name="moove_radioselect[<?php echo sanitize_title( $data['post_type'] );?>][<?php echo $taxonomy->name ?>][default]" id="default_<?php echo $taxonomy->name ;?>">
                                <option value="-1" >
                                    <?php _e( 'Not Selected' , 'moove' ); ?>
                                </option>

                               <?php foreach ( $terms as $term ) : ?>
                                   <option value="<?php echo $term->term_id; ?>" <?php echo isset( $data['options'][ sanitize_title( $data['post_type'] ) ][ $taxonomy->name ]['default']) && intval( $data['options'][ sanitize_title( $data['post_type'] ) ][ $taxonomy->name ]['default'] ) === $term->term_id ? ' selected="selected"':''?>>
                                    <?php echo $term->name; ?>
                                </option>
                               <?php endforeach; ?>
        
                                


                            </select>
                                           
                        </div>
                        <!-- moove-taxonomy-cnt -->
                    </td>

                </tr>
            <?php endif; ?>

            <tr>
                <td colspan="2"><hr /></td>
            </tr>
            
        <?php endforeach; ?>
    </tbody>
</table>
