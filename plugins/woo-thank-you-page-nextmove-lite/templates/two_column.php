<?php
defined( 'ABSPATH' ) || exit;

/**
 * XLWCTY Template Name: Two Column
 **/
?>
<div class="xlwcty_col2_wrap">
    <div class="xlwcty_wrap xlwcty_circle_show">
        <div class="xlwcty_in_wrap">
            <div class="xlwcty_in_wrap_two_column_top">
				<?php xlwcty_Core()->public->render( 'two_column', 'third' ); ?>
            </div>
            <div class="xlwcty_clearfix">
                <div class="xlwcty_leftArea">
					<?php xlwcty_Core()->public->render( 'two_column', 'first' ); ?>
                </div>
                <div class="xlwcty_rightArea">
					<?php xlwcty_Core()->public->render( 'two_column', 'second' ); ?>
                </div>
            </div>
        </div>
    </div>
</div>
