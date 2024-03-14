<?php $plugin_file_url = plugins_url() . '/'; ?>
<div id="gmoshowtime" class="wrap">

<h2>GMO Showtime</h2>

<p class="helplink"><a href="#setup-help"><?php _e('How to Setup', 'gmoshowtime'); ?></a></p>

<div id="gmoplugLeft">

<div id="alpha">

<form id="save-social" method="post" action="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>" style="margin-bottom:3em;">
<?php wp_nonce_field('gmoshowtime', 'gmoshowtime'); ?>

<h3><?php _e('Preview', 'gmoshowtime'); ?></h3>

<div id="mainpreview">
<?php $this->get_preview_contents(); ?>
</div>

<br clear="all">

<h3 style="margin-top: 2em;"><?php _e('Slider Settings', 'gmoshowtime'); ?></h3>

<div id="transitions-settings">

<div id="transitions">
<select name="transition">
<?php

foreach ($this->get_transitions() as $tran) {
?>
<?php if (get_option('gmoshowtime-transition', $this->get_default_transition()) === $tran): ?>
<option value="<?php echo $tran; ?>" selected><?php echo $tran; ?></option>
<?php else: ?>
<option value="<?php echo $tran; ?>"><?php echo $tran; ?></option>
<?php endif; ?>
<!--
<div class="transitions">
    <div class="showtime-transition-preview" data-transition="<?php echo $tran; ?>">
    </div>
    <h4><label>
        <?php if (get_option('gmoshowtime-transition', $this->get_default_transition()) === $tran): ?>
        <input type="radio" name="transition" value="<?php echo $tran; ?>" checked />
        <?php else: ?>
        <input type="radio" name="transition" value="<?php echo $tran; ?>" />
        <?php endif; ?>
        <?php echo $tran; ?>
    </label></h4>
</div>
-->
<?php
}
?>
</select>
</div>

</div><!-- #transitions-settings -->

<h3><?php _e('Gneral Settings', 'gmoshowtime'); ?></h3>

<table class="form-table">
<!--
    <tr>
        <th scope="row">Max Pages</th>
        <td>
            <select name="max-pages">
            <?php for ($i = 1; $i < 5; $i++): ?>
                <?php if (intval(get_option('gmoshowtime-max-pages', 4)) === $i): ?>
                <option value="<?php echo $i; ?>" selected><?php echo $i; ?></option>
                <?php else: ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endif; ?>
            <?php endfor; ?>
            </select>
        </td>
    </tr>
-->
    <tr>
        <th scope="row">Page types</th>
        <td>
            <ul>
            <?php foreach ($this->get_page_types() as $page_type => $meta): ?>
                <li><label>
                <?php if (in_array($page_type, get_option('gmoshowtime-page-types', array_keys($this->get_page_types())))): ?>
                    <input type="checkbox" name="page-types[]" value="<?php echo esc_attr($page_type); ?>" checked>
                <?php else: ?>
                    <input type="checkbox" name="page-types[]" value="<?php echo esc_attr($page_type); ?>">
                <?php endif; ?>
                <?php echo $meta['caption']; ?>
                </label></li>
            <?php endforeach; ?>
            </ul>
        </td>
    </tr>
    <tr>
        <th scope="row">Text Position</th>
        <td>
            <select name="css-class">
            <?php foreach ($this->get_css_classes() as $class => $caption): ?>
                <?php if (get_option('gmoshowtime-css-class', $this->get_default_css_class()) === $class): ?>
                    <option value="<?php echo esc_attr($class); ?>" selected><?php echo esc_html($caption); ?></option>
                <?php else: ?>
                    <option value="<?php echo esc_attr($class); ?>"><?php echo esc_html($caption); ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
            </select>
        </td>
    </tr>

<!--
    <tr>
        <th scope="row">Image Size</th>
        <td>
            <select name="image-size">
                <option value="full">Full-Size</option>
<?php
    $sizes = $this->list_image_sizes();
    $options = array();
    foreach ($sizes as $size => $atts) {
        if (get_option('gmoshowtime-image-size', $this->get_default_image_size()) === $size) {
            $selected = 'selected';
        } else {
            $selected = '';
        }
        $options[] = sprintf(
            '<option value="%1$s" %4$s>%1$s (%2$dpx &times; %3$dpx)</option>',
            $size,
            $atts[0],
            $atts[1],
            $selected
        );
    }

    echo join("\n", $options);
?>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row">Gallery</th>
        <td>
            <label>
                <?php if (intval(get_option('gmoshowtime-apply-gallery', 0)) === 1): ?>
                    <input type="checkbox" name="apply-gallery" value="1" checked />
                <?php else: ?>
                    <input type="checkbox" name="apply-gallery" value="1" />
                <?php endif; ?>
                <?php _e('Apply <a href="http://owlgraphic.com/owlcarousel/">Owl Carousel</a> to the WordPress Gallery.', 'gmoshowtime'); ?>
            </label>
        </td>
    </tr>
-->
    <tr>
        <th scope="row">Maintenance Mode</th>
        <td>
            <label>
                <?php if (intval(get_option('gmoshowtime-maintenance', 1)) === 1): ?>
                    <input type="checkbox" name="maintenance" value="1" checked />
                <?php else: ?>
                    <input type="checkbox" name="maintenance" value="1" />
                <?php endif; ?>
                <a href="<?php echo admin_url('themes.php?page=custom-header'); ?>"><?php _e('Show custom header image instead.', 'gmoshowtime'); ?></a>
            </label>
        </td>
    </tr>
    <tr>
        <th scope="row">Background Color</th>
        <td>
            <div id="background-color-picker"> </div>
            <p><input type="text" id="background-color" name="background-color" value="<?php echo esc_attr( $this->get_background_color() ); ?>" /></p>
        </td>
    </tr>
    <tr>
        <th scope="row">Text Color</th>
        <td>
            <div id="text-color-picker"> </div>
            <p><input type="text" id="text-color" name="text-color" value="<?php echo esc_attr( $this->get_text_color() ); ?>" /></p>
        </td>
    </tr>
</table>

<p style="margin-top: 3em;"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e("Save Changes", "gmoshowtime"); ?>"></p>
</form>

</div><!-- #alpha -->

<div id="beta">
<h3 id="setup-help" style="margin-top: 0;"><?php _e('How to Setup', 'gmoshowtime'); ?></h3>

<h4>1. <?php _e('Place the code in your theme like below.', 'gmoshowtime'); ?></h4>

<pre id="sample-code" readonly>&lt;?php if ( function_exists( &#039;showtime&#039; ) ): ?&gt;
&lt;?php showtime(); ?&gt;
&lt;?php endif; ?&gt;</pre>

<!--
<h4>2. <?php _e('Select `Featured Image` and check `Showtime` in your posts or pages admin.', 'gmoshowtime'); ?></h4>

<p><img src="<?php echo GMOSHOWTIME_URL ?>/img/help1.png" alt=""></p>
-->

</div><!-- #beta -->

<br clear="all" />

</div><!-- #gmoplugLeft -->

<div id="gmoplugRight">
<p class="title">Recommended</p>
<div>
<h3>WordPress Themes</h3>
<a href="https://cloud.z.com/jp/en/wp/themes-plugins/?utm_source=plugins&utm_medium=morethemes&utm_campaign=plugins_morethemes" target="_blank"><img src="<?php echo ($plugin_file_url.'gmo-showtime/images/'.'bnr_themes.png'); ?>" alt="WordPress Themes for Everyone"></a>
<p>Browse our recommended theme collection on Z.com WordPress Hosting website.</p>
<h3>WordPress Plugins</h3>
<a href="https://cloud.z.com/jp/en/wp/themes-plugins/?utm_source=plugins&utm_medium=moreplugins&utm_campaign=plugins_moreplugins#plugins" target="_blank"><img src="<?php echo ($plugin_file_url.'gmo-showtime/images/'.'bnr_plugins.png'); ?>" alt="WordPress Plugins for Everyone"></a>
<p>Browse our recommended plugin collection on Z.com WordPress Hosting website.</p>
<h3>Who We Are</h3>
<a href="https://cloud.z.com/jp/en/?utm_source=plugins&utm_medium=about&utm_campaign=plugins_about" target="_blank" class="logo"><img src="<?php echo ($plugin_file_url.'gmo-showtime/images/'.'logo.png'); ?>" alt="Z.com by GMO"></a>
</div>
</div><!-- #gmoplugRight -->

</div><!-- #gmoshowtime -->
