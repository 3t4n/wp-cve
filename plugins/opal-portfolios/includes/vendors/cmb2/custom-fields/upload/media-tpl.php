<div class="media-upload-block" data-name="<?php echo $field->args( '_id' ); ?>">
    <div class="property-media">
        <?php ?>
        <div class="media-gallery">
            <div class="<?php echo apply_filters( 'opalrealestate_row_container_class', 'row opal-row' ); ?>">
                <div id="property-thumbs-container">

                    <?php

                    if (isset( $_GET['id'] ) && $_GET['id'] > 0) {

                        $post_id = $_GET['id'];

                        $property_images = get_post_meta( $post_id, OPALREALESTATE_PROPERTY_PREFIX . 'gallery', true );
                        if (!is_array( $property_images )) {
                            $property_images = array();
                        }
                        $featured_image_id = get_post_thumbnail_id( $post_id );

                        $property_images = array_unique( $property_images );
                        if ($featured_image_id) {
                            $property_images[$featured_image_id] = $featured_image_id;
                        }

                        echo '<input type="hidden" class="featured-image-id" name="featured_image_id" value="' . intval( $featured_image_id ) . '">';

                        foreach ($property_images as $image_id => $url) {

                            $is_featured_image = ( $featured_image_id == $image_id );
                            $featured_icon     = ( $is_featured_image ) ? 'fa-star' : 'fa-star-o';

                            echo '<div class="col-sm-2">';
                            echo '<div class="gallery-thumbnail">';
                            echo wp_get_attachment_image( $image_id, 'thumbnail' );
                            echo '<a class="icon icon-delete" data-toggle="tooltip" title="' . __( 'Delete this image', 'ocbee-core' ) . '" data-property-id="' . intval( $post_id ) . '" data-attachment-id="' . intval( $image_id ) . '" href="javascript:;">';
                            echo '<i class="fa fa-trash-o"></i>';
                            echo '</a>';
                            echo '<a class="icon icon-fav icon-featured" data-toggle="tooltip" title="' . __( 'Set featured image', 'ocbee-core' ) . '" data-property-id="' . intval( $post_id ) . '" data-attachment-id="' . intval( $image_id ) . '" href="javascript:;">';
                            echo '<i class="fa ' . esc_attr( $featured_icon ) . '"></i>';
                            echo '</a>';
                            echo '<input type="hidden" class="propperty-image-id" name="propperty_image_ids[]" value="' . intval( $image_id ) . '">';
                            echo '<span style="display: none;" class="icon icon-loader">';
                            echo '<i class="fa fa-spinner fa-spin"></i>';
                            echo '</span>';

                            echo '</div>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php ?>
        <div id="drag-and-drop" class="media-drag-drop">
            <h4><i class="fa fa-cloud-upload"></i><?php esc_html_e( 'Drag and drop images here', 'ocbee-core' ); ?>
            </h4>
            <h4><?php esc_html_e( 'or', 'ocbee-core' ); ?></h4>
            <a id="select-images" href="javascript:;"
               class="btn btn-primary btn-3d"><?php esc_html_e( 'Select Images', 'ocbee-core' ); ?></a>
        </div>
        <div id="plupload-container"></div>
        <div id="errors-log"></div>
    </div>
</div>
