<?php
/**
 * Created by PhpStorm.
 * User: Gena
 * Date: 18.01.2019
 * Time: 12:53
 */

namespace dm;


class dmReview
{
    public $gallery = [];
    public function __construct() {

        if ( is_admin() ) {
            add_action( 'add_meta_boxes_comment', [ $this, 'comment_add_meta_box' ] );
            add_action( 'edit_comment', [ $this, 'comment_edit_function' ] );
        }
    }
    public function comment_add_meta_box() {

        add_meta_box(
            'ads-comment-title',
            __( 'Additional Info', 'adsw' ),
            [ $this, 'comment_meta_box_content' ],
            'comment',
            'normal',
            'high'
        );
    }

    public function comment_meta_box_content( $comment ) {

        $this->gallery = get_comment_meta( $comment->comment_ID, 'images', true );

        if ( empty( $this->gallery ) ) {
            $this->gallery = [];
        }

        ?>
        <table class="form-table editcomment comment_xtra">
            <tbody>
            <tr valign="top"><?php $this->callGallery($comment->comment_ID); ?></tr>
            </tbody>
        </table>

        <?php
    }

    protected function action_callGallery( $data )
    {
        $this->gallery   = get_comment_meta( $data[ 'comment_id' ], 'images', true );

        if ( empty( $this->gallery ) ) {
            $this->gallery = [];
        }
        $items = $this->get_gallery( $this->gallery, 'woocommerce_thumbnail' );

        if( empty( $items ) ){
            return [
                'gallery' => []
            ];
        }

        return [
            'gallery' => array_map(function ($e) {
                return [
                    'value' => isset($e['id']) ? $e['id'] : $e['url'],
                    'url' => $e['woocommerce_thumbnail']
                ];
            }, $items)
        ];
    }

    public function callGallery( $comment_ID ) {

        $tmpl = new dmTemplate();

        $item = sprintf('<div class="col-xs-30 col-sm-30 col-md-20 col-lg-20 image-item">
            <div class="inner-item">
                <input type="hidden" name="gallery[]" value="{{value}}">
                <div class="bg-image">
                    <div class="cover-image"  data-src="{{url}}" style="background-image: url({{url}})"></div>
                    <div class="upload-image-nav">
                        <div class="tile-action">
                            <i data-toggle="move-left" class="img-prev fa fa-arrow-left"></i>
                            <i data-toggle="move-right" class="img-next fa fa-arrow-right"></i>
                        </div>
                        <div class="tile-action right">
                            <i data-toggle="remove" class="img-remove fa fa-trash-o"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>');

        $tmpl->template('tmpl-item-media', $item);

        $itemAddMedia = sprintf('<div id="ads-upload-image" class="col-xs-30 col-sm-30 col-md-20 col-lg-20 image-item">
                                    <div class="inner-item">
                                        <div class="bg-image">
                                            <div class="cover-image"></div>
                                            <div class="upload-image-nav"><span>+ %1$s</span></div>
                                        </div>
                                    </div>', __('Add image', 'adsw'));


        $tmpl->template('ads-tmpl-callGallery',
            sprintf('<div class="box">%1$s<div class="row" id="ads-gallery">{{#each gallery}}%2$s{{/each}}%3$s</div></div>', $tmpl->renderItems(), $item, $itemAddMedia)
        );
        ?>

        <h3><?php _e( 'Gallery', 'adsw' ) ?></h3>

        <script id="tmpl-item-media" type="text/template">
            <div class="image-item">
                <div class="inner-item">
                    <input type="hidden" name="gallery[]" value="{{id}}">
                    <div class="card tile card-image card-black bg-image bg-opaque8">
                        <div class="cover-image" style="background-image: url('{{url}}')"></div>
                        <div class="context has-action-left has-action-right">
                            <div class="tile-action">
                                <a href="javascript:;" data-toggle="move-left"><i class="glyphicon glyphicon-arrow-left"></i></a>
                                <a href="javascript:;" data-toggle="move-right"><i class="glyphicon glyphicon-arrow-right"></i></a>
                            </div>
                            <div class="tile-action right">
                                <a href="javascript:;" data-toggle="remove"><i class="glyphicon glyphicon-remove"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </script>

        <div id="ads-form-callGallery" class="body-panel" data-ads_controller="adsReview" data-ads_action="callGallery" data-ads_target="#ads-form-box-callGallery" data-ads_template="#ads-tmpl-callGallery">
            <?php echo $tmpl->hidden(['name' => 'comment_ID','value' => $comment_ID ]) ?>
            <div id="ads-form-box-callGallery"></div>
        </div>

        <?php
    }

    public function get_gallery( $args, $size = 'medium' ) {

        $args = maybe_unserialize( $args );
        $args = maybe_unserialize( $args );
        if ( ! is_array( $args ) ) {
            return false;
        }

        $foo = [];
        $media = [];

        foreach ( $args as $i => $item ) {

            if ( is_numeric( $item ) ) {
                $media[]           = $item;
                $foo[ $i ][ 'id' ] = intval( $item );
            } else {
                $foo[ $i ][$size] = dm_get_thumb_ali( $item, $size);
                $foo[ $i ][ 'url' ]        = $item;
            }
        }

        $media = dm_get_list_images($media, $size);

        foreach ( $foo as $i => $item ) {

            if ( isset( $item[ 'id' ] ) ) {

                if ( isset( $media[ $item[ 'id' ] ] ) ) {
                    $foo[ $i ][$size] = $media[ $item[ 'id' ] ][$size][ 'url' ];
                    $foo[ $i ][ 'url' ]        = $media[ $item[ 'id' ] ][ 'full' ][ 'url' ];
                    $foo[ $i ][ 'id' ]         = $item[ 'id' ];
                } else {
                    unset( $foo[ $i ] );
                }
            }
        }
        return $foo;
    }


}