<?php

echo ( isset($title) && !empty($title) ) ? $before_title . $title . $after_title : null;

?>
<form role="search" method="get" id="super_searchform" class="searchform" action="<?php echo home_url(); ?>">

    <input type="hidden" name="archive_template" value="<?php echo esc_attr($search_archive_tpl); ?>"/>

    <div class="input-group">
        <input type="text" class="form-control" value="" name="s" id="s" placeholder="<?php echo esc_attr($placeholder); ?>">
        <?php
        if( $user_selectable )
        {
        ?>
            <div class="input-group-btn">
                <select id="post-type-selector" name="post_type">
                    <option value="">In...</option>
                        <?php if ( count($post_types) > 1 ) { ?>
                    <option value="<?php echo esc_attr($in_post_type); ?>">All</option>
                        <?php } ?>
                        <?php foreach(  $post_types as $post_type ) { ?>
                    <option value="<?php echo esc_attr($post_type->name); ?>"><?php echo esc_attr( $post_type->label ); ?></option>
                    <?php } ?>
                </select>
            </div>
        <?php
        }
        else
        {
        ?>
            <span class="input-group-btn">
                <input id="in_post_type" type="hidden" name="post_type" value="<?php echo esc_attr($in_post_type); ?>"/>
                <input id="searchsubmit" class="btn btn-default" type="submit" value="<?php echo esc_attr($button_text); ?>">
            </span>
        <?php
        }
        ?>
    </div>
</form>