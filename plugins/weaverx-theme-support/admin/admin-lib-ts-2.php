<?php
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


function weaverx_form_textarea($value, $media = false): void
{
    $twide = ($value['type'] == 'text') ? '60' : '140';
    $rows = (isset($value['val'])) ? $value['val'] : 1;
    $place = (isset($value['placeholder'])) ? $value['placeholder'] : ' ';
    if ($rows < 1) {
        $rows = 1;
    }
    ?>
    <tr>
        <th scope="row"><?php weaverx_echo_name($value); ?>:&nbsp;</th>
        <td colspan=2>
            <?php weaverx_textarea(weaverx_getopt($value['id']), $value['id'], $rows, $place, 'width:350px;', $class = 'wvrx-edit'); ?>
            <?php
            if ($media) {
                weaverx_media_lib_button($value['id']);
            }
            ?>
            &nbsp;<small><?php echo $value['info']; ?></small>
        </td>

    </tr>
    <?php
}

function weaverx_form_text($value, $media = false): void
{
    $twide = ($value['type'] == 'text') ? '60' : '160';
    ?>
    <tr>
        <th scope="row"
        "><?php weaverx_echo_name($value); ?>:&nbsp;</th>
        <td>
            <input name="<?php weaverx_sapi_main_name($value['id']); ?>" id="<?php echo $value['id']; ?>" type="text"
                   style="width:<?php echo $twide; ?>px;" class="regular-text"
                   value="<?php echo esc_textarea(weaverx_getopt($value['id'])); ?>"/>
            <?php
            if ($media) {
                weaverx_media_lib_button($value['id']);
            }
            ?>
        </td>
        <?php weaverx_form_info($value);
        ?>
    </tr>
    <?php
}

function weaverx_form_val($value, $unit = ''): void
{
    ?>
    <tr>
        <th scope="row"><?php weaverx_echo_name($value); ?>:&nbsp;</th>
        <td>
            <input name="<?php weaverx_sapi_main_name($value['id']); ?>" id="<?php echo $value['id']; ?>" type="text"
                   style="width:50px;" class="regular-text"
                   value="<?php echo esc_textarea(weaverx_getopt($value['id'])); ?>"/> <?php echo $unit; ?>
        </td>
        <?php weaverx_form_info($value);
        ?>
    </tr>
    <?php
}

function weaverx_form_text_xy($value, $x = 'X', $y = 'Y', $units = 'px'): void
{
    $xid = $value['id'] . '_' . $x;
    $yid = $value['id'] . '_' . $y;
    $colon = ($value['name']) ? ':' : '';
    ?>
    <tr>
        <th scope="row"><?php weaverx_echo_name($value);
            echo $colon; ?>&nbsp;
        </th>
        <td>
            <?php echo '<span class="rtl-break">' . $x; ?>
            :<input name="<?php weaverx_sapi_main_name($xid); ?>" id="<?php echo $xid; ?>" type="text"
                    style="width:40px;" class="regular-text"
                    value="<?php weaverx_esc_textarea(weaverx_getopt($xid)); ?>"/> <?php echo $units; ?></span>
            &nbsp;<?php echo '<span class="rtl-break">' . $y; ?>
            :<input name="<?php weaverx_sapi_main_name($yid); ?>" id="<?php echo $yid; ?>" type="text"
                    style="width:40px;" class="regular-text"
                    value="<?php weaverx_esc_textarea(weaverx_getopt($yid)); ?>"/> <?php echo $units; ?></span>
        </td>
        <?php weaverx_form_info($value);
        ?>
    </tr>
    <?php
}

function weaverx_form_checkbox($value): void
{
    ?>
    <tr>
        <th scope="row"><?php weaverx_echo_name($value); ?>:&nbsp;</th>
        <td>
            <input type="checkbox" name="<?php weaverx_sapi_main_name($value['id']); ?>"
                   id="<?php echo $value['id']; ?>"
                <?php checked(weaverx_getopt_checked($value['id'])); ?> >
        </td>
        <?php weaverx_form_info($value);
        ?>
    </tr>
    <?php
}

function weaverx_form_radio($value): void
{
    ?>

    <tr>
        <th scope="row"><?php weaverx_echo_name($value); ?>:&nbsp;</th>
        <td colspan="2">

            <?php
            $cur_val = weaverx_getopt_default($value['id'], 'black');
            foreach ($value['value'] as $option) {
                $desc = $option['val'];
                if ($desc == 'none') {
                    $desc = "None";
                } else {
                    $icon = weaverx_relative_url('assets/css/icons/search-' . $desc . '.png');
                    $desc = '<img style="background-color:#ccc;height:24px; width:24px;" src="' . $icon . '" />';
                }
                ?>
                <input type="radio" name="<?php weaverx_sapi_main_name($value['id']); ?>"
                       value="<?php echo $option['val']; ?>"
                    <?php checked($cur_val, $option['val']); ?> > <?php echo $desc; ?>&nbsp;
            <?php } ?>
            <?php echo '<br /><small style="margin-left:5%;">' . $value['info'] . '</small>'; ?>
        </td>
    </tr>
    <?php
}


function weaverx_form_select_id($value, $show_row = true): void
{
    if ($show_row) { ?>

        <tr>
        <th scope="row"><?php weaverx_echo_name($value); ?>:&nbsp;</th>
        <td>
    <?php } ?>

    <select name="<?php weaverx_sapi_main_name($value['id']); ?>" id="<?php echo $value['id']; ?>">
        <?php
        foreach ($value['value'] as $option) {

            ?>
            <option value="<?php echo $option['val'] ?>" <?php selected((weaverx_getopt($value['id']) == $option['val'])); ?>><?php echo $option['desc']; ?></option>
        <?php } ?>
    </select>
    <?php if ($show_row) { ?>
    </td>
    <?php weaverx_form_info($value); ?>
    </tr>
<?php }
}

function weaverx_form_select_alt_theme($value): void
{

    if (function_exists('weaverx_pp_get_alt_themes'))    // backward compatibility for Weaver Xtreme 3
    {
        $themes = weaverx_pp_get_alt_themes();
    } else {
        $themes = array();
    }
    $list = array();
    $list[] = array('val' => '', 'desc' => '');
    foreach ($themes as $subtheme) {
        $list[] = array('val' => $subtheme, 'desc' => $subtheme);
    }


    $value['value'] = $list;
    weaverx_form_select_id($value);
}

function weaverx_form_select_layout($value): void
{
    $list = array(
        array('val' => 'default', 'desc' => esc_html__('Use Default', 'weaver-xtreme' /*adm*/)),
        array('val' => 'right', 'desc' => esc_html__('Sidebars on Right', 'weaver-xtreme' /*adm*/)),
        array('val' => 'right-top', 'desc' => esc_html__('Sidebars on Right (stack top)', 'weaver-xtreme' /*adm*/)),
        array('val' => 'left', 'desc' => esc_html__('Sidebars on Left', 'weaver-xtreme' /*adm*/)),
        array('val' => 'left-top', 'desc' => esc_html__(' Sidebars on Left (stack top)', 'weaver-xtreme' /*adm*/)),
        array('val' => 'split', 'desc' => esc_html__('Split - Sidebars on Right and Left', 'weaver-xtreme' /*adm*/)),
        array('val' => 'split-top', 'desc' => esc_html__('Split (stack top)', 'weaver-xtreme' /*adm*/)),
        array('val' => 'one-column', 'desc' => esc_html__('No sidebars, content only', 'weaver-xtreme' /*adm*/)),
    );


    $value['value'] = $list;
    weaverx_form_select_id($value);
}


function weaverx_form_link($value): void
{
    $id = $value['id'];

    $link = array('name' => $value['name'], 'id' => $id . '_color', 'type' => 'ctext', 'info' => $value['info']);
    $hover = array('name' => '<small>' . esc_html__('Hover', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => $id . '_hover_color', 'type' => 'ctext', 'info' => esc_html__('Hover Color', 'weaver-xtreme' /*adm*/));

    weaverx_form_ctext($link);
    $id_strong = $id . '_strong';
    $id_em = $id . '_em';
    $id_u = $id . '_u';
    $id_uh = $id . '_u_h';
    ?>
    <tr>
    <td><small style="float:right;"><?php esc_html_e('Link Attributes:', 'weaver-xtreme' /*adm*/); ?></small>
    </td><td colspan="2">

    <small style="margin-left:5em;"><strong><?php esc_html_e('Bold', 'weaver-xtreme' /*adm*/); ?></strong></small>

    <?php weaverx_form_font_bold_italic(array('id' => $id_strong)); ?>

    &nbsp;<small><em><?php esc_html_e('Italic', 'weaver-xtreme' /*adm*/); ?></em></small>
    <?php weaverx_form_font_bold_italic(array('id' => $id_em)); ?>

    &nbsp;<small><u><?php esc_html_e('Link Underline', 'weaver-xtreme' /*adm*/); ?></u></small>
    <input type="checkbox" name="<?php weaverx_sapi_main_name($id_u); ?>" id="<?php echo $id_u; ?>"
        <?php checked(weaverx_getopt_checked($id_u)); ?> >

    &nbsp;|&nbsp;&nbsp;<small><u><?php esc_html_e('Hover Underline', 'weaver-xtreme' /*adm*/); ?></u></small>
    <input type="checkbox" name="<?php weaverx_sapi_main_name($id_uh); ?>" id="<?php echo $id_uh; ?>"
        <?php checked(weaverx_getopt_checked($id_uh)); ?> >

    <?php
    weaverx_form_ctext($hover, true);
    ?>

    <?php
    echo '</td></tr>';
}


function weaverx_form_break($value): void
{
    $lim = isset($value['value']) ? $value['value'] : 1;
    $label = isset($value['name']) ? "<em style='color:blue;'><strong>{$value['name']}</strong></em>" : '&nbsp;';
    for ($n = 1; $n <= $lim; ++$n) {
        echo "<tr><td style='text-align:right;'>$label</td></tr>";
        $label = '&nbsp;';
    }
}

function weaverx_form_note($value): void
{
    ?>
    <tr>
        <th scope="row">&nbsp;</th>
        <td style="float:right;font-weight:bold;"><?php weaverx_echo_name($value); ?>&nbsp;
            <?php
            weaverx_form_help($value);
            ?>
        </td>
        <?php
        weaverx_form_info($value);
        ?>
    </tr>
    <?php
}


function weaverx_form_info($value): void
{
    if ($value['info'] != '') {
        echo('<td style="padding-left: 10px"><small>');
        echo $value['info'];
        echo("</small></td>");
    }
}


function weaverx_form_widget_area($value, $submit = false): void
{
    /* build the rows for area settings
     * Defined Areas:
     *  'container' => '0', 'header' => '0', 'header_html' => '0', 'header_sb' => '0',
        'infobar' => '5px', 'content' => 'T:4px, B:8px', 'post' => '0', 'footer' => '0',
        'footer_sb' => '0', 'footer_html' => '0', 'widget' => '0', 'primary' => '0',
        'secondary' => '0', 'extra' => '0', 'top' => '0', 'bottom' => '0', 'wrapper' => '0'
     */

    // defaults - these are determined by the =Padding section of style-weaverx.css
    $default_tb = array(
        'infobar' => '5px',
        'content' => 'T:4px, B:8px',
        'footer' => '8px',
        'footer_sb' => '8px',
        'primary' => '8px',
        'secondary' => '8px',
        'extra' => '8px',
        'top' => '8px',
        'bottom' => '8px',
    );

    $default_lr = array(
        'infobar' => '5px',
        'content' => '2%',
        'post' => '0',
        'footer' => '8px',
        'footer_sb' => '8px',
        'primary' => '8px',
        'secondary' => '8px',
        'extra' => '8px',
        'top' => '8px',
        'bottom' => '8px',
    );

    $default_margins = array(
        'infobar' => '5px',
        'content' => 'T:0, B:0',
        'footer' => 'T:0, B:0',
        'footer_sb' => 'T:0, B:10',
        'primary' => 'T:0, B:10',
        'widget' => '0, Auto - First: T:0, Last: B:0',
        'secondary' => 'T:0, B:10',
        'extra' => 'T:0, B:10',
        'top' => 'T:10, B:10',
        'bottom' => 'T:10, B:10',
        'wrapper' => 'T:0, B:0',
        'post' => 'T:0, B:15',
    );

    $id = $value['id'];

    $def_tb = '0';
    $def_lr = '0';
    $def_marg = '0';
    if (isset($default_tb[$id])) {
        $def_tb = $default_tb[$id];
    }
    if (isset($default_lr[$id])) {
        $def_lr = $default_lr[$id];
    }
    if (isset($default_margins[$id])) {
        $def_marg = $default_margins[$id];
    }

    $use_percent = array('content', 'post');

    //echo '<table><tr><td>';
    $name = $value['name'];


    $lr_type = (in_array($id, $use_percent)) ? 'text_lr_percent' : 'text_lr';


    $opts = array(

        array(
            'name' => $name,
            'id' => '-welcome-widgets-menus',
            'type' => 'header_area',
            'info' => $value['info'],
        ),

        array(
            'name' => $name,
            'id' => $id,
            'type' => 'titles_area',
            'info' => $name,
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-align-none"></span>' . esc_html__('Padding', 'weaver-xtreme' /*adm*/),
            'id' => $id . '_padding',
            'type' => 'text_tb',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Top/Bottom Inner padding [Default: ', 'weaver-xtreme') . $def_tb . ']',
        ),

        array(
            'name' => '',
            'id' => $id . '_padding',
            'type' => $lr_type,
            'info' => '<em>' . $name . '</em>' . esc_html__(': Left/Right Inner padding [Default: ', 'weaver-xtreme') . $def_lr . ']',
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-align-none"></span>' . esc_html__('Top/Bottom Margins', 'weaver-xtreme'),
            'id' => $id . '_margin',
            'type' => 'text_tb',
            'info' => '<em>' . $name . '</em>' . wp_kses_post(__(': Top/Bottom margins. <em>Side margins auto-generated.</em> [Default: ', 'weaver-xtreme')) . $def_marg . ']',
        ),

    );

    weaverx_form_show_options($opts, false, false);


    $no_lr_margins = array(     // areas that can't allow left-right margin or width specifications
        'primary',
        'secondary',
        'content',
        'post',
        'widget',
    );
    $no_widgets = array(        // areas that don't have widgets
        'widget',
        'content',
        'post',
        'wrapper',
        'container',
        'header',
        'header_html',
        'footer_html',
        'footer',
        'infobar',
    );

    $no_hide = array(
        'wrapper',
        'container',
        'content',
        'widget',
        'post',
    );

    $default_auto = array(
        'top',
        'bottom',
        'footer_sb',
        'header_sb',
    );


    if (in_array($id, $no_lr_margins)) {
        if ($id != 'widget') {
            weaverx_form_checkbox(array(
                'name' => '<span class="i-left dashicons dashicons-align-none"></span>' . esc_html__('Add Side Margin(s)', 'weaver-xtreme' /*adm*/),
                'id' => $id . '_smartmargin',
                'type' => '',
                'info' => '<em>' . $name . '</em>' .
                    esc_html__(': Automatically add left/right "smart" margins for separation of areas (sidebar/content).', 'weaver-xtreme' /*adm*/),
            ));
        }

        weaverx_form_note(array(
            'name' => '<strong>' . esc_html__('Width', 'weaver-xtreme' /*adm*/) . '</strong>',
            'info' => esc_html__('The width of this area is automatically determined by the enclosing area', 'weaver-xtreme' /*adm*/),
        ));
    } elseif ($id != 'wrapper') {

        if (in_array($id, $default_auto)) {
            weaverx_form_val(array(
                'name' => '<span class="i-left" style="font-size:150%;">&harr;</span> ' . esc_html__('Width', 'weaver-xtreme' /*adm*/),
                'id' => $id . '_width_int',
                'type' => '',
                'info' => '<em>' . $name . '</em>' . esc_html__(': Width of Area in % of enclosing area on desktop and small tablet. Hint: use with Center align. Use 0 to force auto width. (Default if blank: auto)', 'weaver-xtreme' /*adm*/),
                'value' => array(),
            ), '%');
        } else {
            weaverx_form_val(array(
                'name' => '<span class="i-left" style="font-size:150%;">&harr;</span> ' . esc_html__('Width', 'weaver-xtreme' /*adm*/),
                'id' => $id . '_width_int',
                'type' => '',
                'info' => '<em>' . $name . '</em>' . esc_html__(': Width of Area in % of enclosing area on desktop and small tablet. Hint: use with Center align. Use 0 to force auto width. (Default if blank: 100%)', 'weaver-xtreme' /*adm*/),
                'value' => array(),
            ), '%');

        }

        weaverx_form_align(array(
                'name' => '<span class="i-left dashicons dashicons-editor-alignleft"></span><small>' . esc_html__('Align Area', 'weaver-xtreme' /*adm*/) . '</small>',
                'id' => $id . '_align',
                'type' => '',
                'info' => '<em>' . $name . '</em>' . esc_html__(': How to align this area (Default: Center)', 'weaver-xtreme'),
            )
        );

        if (in_array($id, array('container', 'header', 'footer'))) {
            weaverx_form_val(array(
                'name' => '<span class="i-left" style="font-size:150%;">&harr;</span> ' . esc_html__('Left/Right Padding', 'weaver-xtreme' /*adm*/),
                'id' => $id . '_padding_LRp',
                'type' => '',
                'info' => '<em>' . $name . '</em>' . esc_html__(': Left/Right Padding in %. Value used only with Full and Wide Align, and overrides Left/Right padding in px options.', 'weaver-xtreme' /*adm*/),
                'value' => array(),
            ), '%');
        }

        if ($id == 'header_html' || $id == 'footer_html') {
            weaverx_form_checkbox(array(
                'name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . esc_html__('Center Content', 'weaver-xtreme' /*adm*/) . '</small>',
                'id' => $id . '_center_content',
                'type' => '',
                'info' => '<em>' . $name . '</em>' .
                    esc_html__(': Center Content within HTML Area content within the area.', 'weaver-xtreme' /*adm*/),
            ));
        }

    }


    if ($id == 'wrapper') {       // setting #wrapper sets theme width.

        weaverx_form_align(array(
                'name' => '<span class="i-left dashicons dashicons-editor-alignleft"></span><small>' . esc_html__('Align Area', 'weaver-xtreme' /*adm*/) . '</small>',
                'id' => $id . '_align',
                'type' => '',
                'info' => '<em>' . $name . '</em>' . esc_html__(': How to align this area (Default: Center)', 'weaver-xtreme' /*adm*/),
            )

        );

        weaverx_form_val(array(
            'name' => '<span class="i-left" style="font-size:150%;">&harr;</span> ' . esc_html__('Left/Right Padding', 'weaver-xtreme' /*adm*/),
            'id' => $id . '_padding_LRp',
            'type' => '',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Left/Right Padding in %. Value used only with Full and Wide Align, and overrides Left/Right padding in px options.', 'weaver-xtreme' /*adm*/),
            'value' => array(),
        ), '%');

        $info = wp_kses_post(__('<em>Change Theme Width.</em> Standard width is 1100px. Use the options on the "Full Width" tab for full width designs, but leave this value set. Widths less than 768px may give unexpected results on mobile devices. Weaver Xtreme can not create a fixed-width site.', 'weaver-xtreme' /*adm*/));

        weaverx_form_val(array(
            'name' => '<span class="i-left" style="font-size:150%;">&harr;</span><em style="color:red;">' . esc_html__('Theme Width', 'weaver-xtreme' /*adm*/) . '</em>',
            'id' => 'theme_width_int',
            'type' => '',
            'info' => $info,
            'value' => array(),
        ), 'px');

        if (version_compare(WEAVERX_VERSION, '4.9.0', '>=')) {
            weaverx_form_checkbox(array(
                'name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . esc_html__('Use "Lazy H" Layout', 'weaver-xtreme') . '</small>',
                'id' => 'lazyh',
                'type' => '',
                'info' => esc_html__('Add styling to support "Lazy H" layout: wide Header and Footer, indented Content. (Like a sideways H.) You will also need to add fullwidth alignment to the header and footer to get this layout. Option added for backward compatibility with previous versions of Weaver Xtreme.', 'weaver-xtreme'),
            ));
        }
    }

    if (in_array($id, array('container', 'header', 'footer'))) {
        $opts_max = array(
            array(
                'name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small>' . esc_html__('Max Width', 'weaver-xtreme' /*adm*/) . '</small>',
                'id' => $id . '_max_width_int',
                'type' => '+val_px',
                'info' => '<em>' . $name . '</em>' . esc_html__(': Set Max Width of Area for Desktop View. Advanced Option. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
                'value' => array(),
            ),
        );

        weaverx_form_show_options($opts_max, false, false);
    }


    if (!in_array($id, $no_widgets)) {

        $opts02 = array(
            array(
                'name' => '<span class="i-left" style="font-size:120%;">&nbsp;&#9783;</span>' . esc_html__('Columns', 'weaver-xtreme' /*adm*/),
                'id' => $id . '_cols_int',
                'type' => 'val_num',
                'info' => '<em>' . $name . '</em>' . esc_html__(': Equal width columns of widgets (Default: 1; max: 8)', 'weaver-xtreme' /*adm*/),
            ),

            array(
                'name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . esc_html__('No Smart Widget Margins', 'weaver-xtreme' /*adm*/) . '</small>',
                'id' => $id . '_no_widget_margins',
                'type' => 'checkbox',
                'info' => '<em>' . $name . '</em>' . esc_html__(': Do not use "smart margins" between widgets on rows.', 'weaver-xtreme' /*adm*/),
            ),

            array(
                'name' => '<span class="i-left" style="font-size:140%;">&nbsp;=</span><small>' . esc_html__('Equal Height Widget Rows', 'weaver-xtreme' /*adm*/) . '</small>',
                'id' => $id . '_eq_widgets',
                'type' => '+checkbox',
                'info' => '<em>' . $name . '</em>' . esc_html__(': Make widgets equal height rows if &gt; 1 column (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
            ),

        );

        weaverx_form_show_options($opts02, false, false);


        $custom_widths = array('header_sb', 'footer_sb', 'primary', 'secondary', 'top', 'bottom');
        if (in_array($id, $custom_widths)) { /* if ( $id == 'header_sb' || $id == 'footer_sb' ) { */ ?>
            <tr>
                <th scope="row"><span class="i-left"
                                      style="font-size:120%;">&nbsp;&#9783;</span><small><?php esc_html_e('Custom Widget Widths:', 'weaver-xtreme' /*adm*/); ?></small>
                </th>
                <td colspan="2" style="padding-left:20px;">
                    <small><?php esc_html_e('You can optionally specify widget widths, including for specific devices. Please read the help entry!', 'weaver-xtreme' /*adm*/); ?>
                        <?php weaverx_help_link('help.html#CustomWidgetWidth', esc_html__('Help on Custom Widget Widths', 'weaver-xtreme' /*adm*/)); ?>
                        <?php esc_html_e('(&#9733;Plus) (&diams;)', 'weaver-xtreme' /*adm*/); ?></small></td>
            </tr>
            <?php
            $opts2 = array(
                array(
                    'name' => '<span class="i-left dashicons dashicons-desktop"></span><small>' . esc_html__('Desktop', 'weaver-xtreme' /*adm*/) . '</small>',
                    'id' => '_' . $id . '_lw_cols_list',
                    'type' => '+textarea',
                    'placeholder' => esc_html__('25,25,50; 60,40; - for example', 'weaver-xtreme' /*adm*/),
                    'info' => esc_html__('List of widths separated by comma. Use semi-colon (;) for end of each row.  (&#9733;Plus) (&diams;)', 'weaver-xtreme' /*adm*/),
                ),
                array(
                    'name' => '<span class="i-left dashicons dashicons-tablet"></span><small>' . esc_html__('Small Tablet', 'weaver-xtreme' /*adm*/) . '</small>',
                    'id' => '_' . $id . '_mw_cols_list',
                    'type' => '+textarea',
                    'info' => esc_html__('List of widget widths. (&#9733;Plus) (&diams;)', 'weaver-xtreme' /*adm*/),
                ),
                array(
                    'name' => '<span class="i-left dashicons dashicons-smartphone"></span><small>' . esc_html__('Phone', 'weaver-xtreme' /*adm*/) . '</small>',
                    'id' => '_' . $id . '_sw_cols_list',
                    'type' => '+textarea',
                    'info' => esc_html__('List of widget widths. (&#9733;Plus) (&diams;)', 'weaver-xtreme' /*adm*/),
                ),
            );

            weaverx_form_show_options($opts2, false, false);
        }
    }

    $opts3 = array(
        array(
            'name' => '<span class="i-left" style="font-size:200%;margin-left:4px;">&#x25a1;</span><small>' . esc_html__('Add Border', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_border',
            'type' => 'checkbox',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Add the "standard" border (as set on Custom tab)', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-admin-page"></span><small>' . esc_html__('Shadow', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_shadow',
            'type' => 'shadows',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Wrap Area with Shadow.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-marker"></span><small>' . esc_html__('Rounded Corners', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_rounded',
            'type' => 'rounded',
            'info' => '<em>' . $name . '</em>' . wp_kses_post(__(': Rounded corners. Needs bg color or borders to show. <em>You might need to set overlapping corners for parent/child areas also!</em>', 'weaver-xtreme' /*adm*/)),
        ),
    );


    weaverx_form_show_options($opts3, false, false);

    if (!in_array($id, $no_hide)) {
        weaverx_form_select_hide(array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Area', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_hide',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Hide area on different display devices', 'weaver-xtreme' /*adm*/),
            'value' => '',
        ));
    }

    // class names
    $opts4 = array(
        array(
            'name' => '<span class="i-left">{ }</span> <small>' . esc_html__('Add Classes', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_add_class',
            'type' => '+widetext',
            'info' => '<em>' . $name . '</em>' . wp_kses_post(__(': Space separated class names to add to this area (<em>Advanced option</em>) (&#9733;Plus)', 'weaver-xtreme' /*adm*/)),
        ),
    );

    weaverx_form_show_options($opts4, false, false);

    if ($submit) {
        weaverx_form_submit('');
    }
    //echo '</td></tr></table>';

}


function weaverx_form_menu_opts($value, $submit = false): void
{
    // build the rows for area
    $wp_logo = weaverx_get_wp_custom_logo_url();


    if ($wp_logo) {
        $wp_logo_html = "<img src='$wp_logo' alt='logo' style='max-height:16px;margin-left:10px;' />";
    } else {
        $wp_logo_html = esc_html__('Not set', 'weaver-xtreme');
    }

    //echo '<table><tr><td>';
    $name = $value['name'];
    $id = $value['id'];


    $opts = array(
        array(
            'name' => $name,
            'id' => '-menu',
            'type' => 'header_area',
            'info' => $value['info'],
        ),
        array('name' => esc_html__('Menu Bar Layout', 'weaver-xtreme'), 'type' => 'break'),

        array(
            'name' => '<span class="i-left dashicons dashicons-editor-alignleft"></span>' . esc_html__('Align Menu', 'weaver-xtreme' /*adm*/),
            'id' => $id . '_align',
            'type' => 'select_id',
            'info' => esc_html__('Align this menu on desktop view. Mobile, accordion, and vertical menus always left aligned.', 'weaver-xtreme' /*adm*/),
            'value' => array(
                array('val' => 'left', 'desc' => 'Align Left'),
                array('val' => 'center', 'desc' => 'Center'),
                array('val' => 'right', 'desc' => 'Align Right'),
                array('val' => 'alignwide', 'desc' => esc_html__('Align Wide', 'weaver-xtreme' /*adm*/)),
                array('val' => 'alignwide left', 'desc' => esc_html__('Align Wide, Items Left', 'weaver-xtreme' /*adm*/)),
                array('val' => 'alignwide center', 'desc' => esc_html__('Align Wide, Items Center', 'weaver-xtreme' /*adm*/)),
                array('val' => 'alignwide right', 'desc' => esc_html__('Align Wide, Items Right', 'weaver-xtreme' /*adm*/)),
                array('val' => 'alignfull', 'desc' => esc_html__('Align Full', 'weaver-xtreme' /*adm*/)),
                array('val' => 'alignfull left', 'desc' => esc_html__('Align Full, Items Left', 'weaver-xtreme' /*adm*/)),
                array('val' => 'alignfull center', 'desc' => esc_html__('Align Full, Items Center', 'weaver-xtreme' /*adm*/)),
                array('val' => 'alignfull right', 'desc' => esc_html__('Align Full, Items Right', 'weaver-xtreme' /*adm*/)),
            ),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Menu', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_hide',
            'type' => 'select_hide',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Hide menu on different display devices', 'weaver-xtreme' /*adm*/),
        ),

    );

    if ($id != 'm_extra') {
        $opts[] = array(
            'name' => '<span class="i-left dashicons dashicons-editor-kitchensink"></span>' . esc_html__('Fixed-Top Menu', 'weaver-xtreme' /*adm*/),
            'id' => $id . '_fixedtop',
            'type' => 'fixedtop',
            'info' => '<em>' . $name . '</em>' . wp_kses_post(__(': Fix menu to top of page. Note: the "Fix to Top on Scroll" does not play well with other "Fixed-Top" areas. Use the <em>Expand/Extend BG Attributes</em> on the Full Width tab to make a full width menu.', 'weaver-xtreme' /*adm*/)),
        );

    }

    if ($id == 'm_primary') {
        $opts[] = array(
            'name' => '<small>' . esc_html__('Move Primary Menu to Top', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_move',
            'type' => 'checkbox',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Move Primary Menu at Top of Header Area (Default: Bottom)', 'weaver-xtreme' /*adm*/),
            'value' => '',
        );


        $opts[] = array(
            'name' => '<span class="i-left dashicons dashicons-heart"></span><small>' . esc_html__('Add Site Logo to Left', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'm_primary_logo_left',
            'type' => 'checkbox',
            'info' => wp_kses_post(__('Add the Site Logo to the primary menu. Add custom CSS for <em>.custom-logo-on-menu</em> to style. (Use Customize &rarr; General Options &rarr; Site Identity to set Site Logo.) Logo: ', 'weaver-xtreme' /*adm*/)) . $wp_logo_html,
        );

        $opts[] = array(
            'name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . esc_html__('Height of Logo on Menu', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'm_primary_logo_height_dec',
            'type' => 'val_em',
            'info' => esc_html__('Set height of Logo on Menu. Will interact with padding. (Default: 2.0em, the standard Menu Bar height.)', 'weaver-xtreme' /*adm*/),
        );

        $opts[] = array(
            'name' => '<small>' . esc_html__('Logo Links to Home', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'm_primary_logo_home_link',
            'type' => 'checkbox',
            'info' => esc_html__('Add a link to home page to logo on menu bar.', 'weaver-xtreme' /*adm*/),
        );

        $opts[] = array(
            'name' => '<small>' . esc_html__('Add Site Title to Left', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'm_primary_site_title_left',
            'type' => 'checkbox',
            'info' => esc_html__('Add Site Title to primary menu left, with link to home page. (Uses Header Title font family, bold, and italic settings. Custom style with .site-title-on-menu.', 'weaver-xtreme' /*adm*/),
        );

        $opts[] = array(
            'name' => '<small>' . esc_html__("Add Search to Right", 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'm_primary_search',
            'type' => '+checkbox',
            'info' => esc_html__('Add slide open search icon to right end of primary menu. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        );

        $opts[] = array(
            'name' => '<small>' . esc_html__('No Home Menu Item', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => 'menu_nohome',
            'type' => 'checkbox',
            'info' => esc_html__('Don\'t automatically add Home menu item for home page (as defined in Settings->Reading)', 'weaver-xtreme' /*adm*/),
        );


    } elseif ($id == 'm_secondary') {
        $opts[] = array(
            'name' => '<small>' . esc_html__('Move Secondary Menu to Bottom', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_move',
            'type' => 'checkbox',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Move Secondary Menu at Bottom of Header Area (Default: Top)', 'weaver-xtreme' /*adm*/),
            'value' => '',
        );
    }

    weaverx_form_show_options($opts, false, false);


    $opts = array(

        array('name' => esc_html__('Menu Bar Colors', 'weaver-xtreme'), 'type' => 'break', 'value' => 1),

        array(
            'name' => esc_html__('Menu Bar', 'weaver-xtreme' /*adm*/),
            'id' => $id,
            'type' => 'titles_menu',    // includes color, font size, font family
            'info' => esc_html__('Entire Menu Bar', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => esc_html__('Item BG', 'weaver-xtreme' /*adm*/),
            'id' => $id . '_link_bgcolor',
            'type' => 'ctext',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Background Color for Menu Bar Items (links)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Dividers between menu items', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_dividers_color',
            'type' => '+color',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Add colored dividers between menu items. Leave blank for none. (&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Hover BG', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_hover_bgcolor',
            'type' => 'ctext',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Hover BG Color (Default: rgba(255,255,255,0.15))', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__('Hover Text Color', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_hover_color',
            'type' => 'color',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Hover Text Color', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => '<small>' . wp_kses_post(__('<em>Mobile</em> Open Submenu Arrow BG -<br /><em>Not used by SmartMenus</em>', 'weaver-xtreme' /*adm*/)) . '</small>',
            'id' => $id . '_clickable_bgcolor',
            'type' => 'ctext',
            'info' => '<em>' . $name . '</em>' . wp_kses_post(__(': Clickable mobile open submenu arrow BG. Contrasting BG color required for proper user interface. <em>Not used by SmartMenus</em>. (Default: rgba(255,255,255,0.2))', 'weaver-xtreme' /*adm*/)),
        ),


        array(
            'name' => esc_html__('Submenu BG', 'weaver-xtreme' /*adm*/),
            'id' => $id . '_sub_bgcolor',
            'type' => 'ctext',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Background Color for submenus', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__('Submenu Text Color', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_sub_color',
            'type' => 'ctext',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Text Color for submenus', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<small>' . esc_html__('Submenu Hover BG', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_sub_hover_bgcolor',
            'type' => 'ctext',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Submenu Hover BG Color (Default: Inherit Top Level)', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<small>' . esc_html__('Submenu Hover Text Color', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_sub_hover_color',
            'type' => 'color',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Submenu Hover Text Color (Default: Inherit Top Level)', 'weaver-xtreme' /*adm*/),
        ),

        array('name' => esc_html__('Menu Bar Style', 'weaver-xtreme'), 'type' => 'break'),

        array(
            'name' => '<span class="i-left" style="font-size:200%;margin-left:4px;">&#x25a1;</span><small>' . esc_html__('Add Border', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_border',
            'type' => 'checkbox',
            'info' => '<em>' . $name . '</em>' . ': Add the "standard" border (as set on Custom tab)',
        ),

        array(
            'name' => '<span class="i-left" style="font-size:200%;margin-left:4px;">&#x25a1;</span><small>' . esc_html__('Add Border to Submenus', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_sub_border',
            'type' => 'checkbox',
            'info' => '<em>' . $name . '</em>' . ': Add the "standard" border to Submenus',
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-admin-page"></span><small>' . esc_html__('Shadow', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_shadow',
            'type' => 'shadows',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Wrap Menu Bar with Shadow.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-marker"></span><small>' . esc_html__('Rounded Corners', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_rounded',
            'type' => 'rounded',
            'info' => '<em>' . $name . '</em>' . wp_kses_post(__(': Add rounded corners to menu. <em>You might need to set overlapping corners Header/Wrapper areas also!</em>', 'weaver-xtreme' /*adm*/)),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-marker"></span><small>' . esc_html__('Rounded Submenu Corners', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_sub_rounded',
            'type' => 'checkbox',
            'info' => '<em>' . $name . '</em>' . ': Add rounded corners to Submenus',
        ),

    );

    weaverx_form_show_options($opts, false, false);


    if ($id == 'm_primary') {
        $right_plus = '';
        $right_text = 'textarea';
        $right_hide = 'select_hide';
    } else {
        $right_plus = '(&#9733;Plus)';
        $right_text = '+textarea';
        $right_hide = '+select_hide';
    }

    $opts2 = array(

        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Arrows', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_hide_arrows',
            'type' => 'checkbox',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Hide Arrows on Desktop Menu', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left">{ }</span> <small>' . esc_html__('Add Classes', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_add_class',
            'type' => '+widetext',
            'info' => '<em>' . $name . '</em>' . wp_kses_post(__(': Space separated class names to add to this area (<em>Advanced option</em>) (&#9733;Plus)', 'weaver-xtreme' /*adm*/)),
        ),

        array('name' => esc_html__('Menu Bar Spacing', 'weaver-xtreme'), 'type' => 'break'),

        array(
            'name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . esc_html__('Menu Top Margin', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_top_margin_dec',
            'type' => 'val_px',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Top margin for menu bar.', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . esc_html__('Menu Bottom Margin', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_bottom_margin_dec',
            'type' => 'val_px',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Bottom margin for menu bar.', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . esc_html__('Desktop Item Vertical Padding', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_menu_pad_dec',
            'type' => 'val_em',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Add vertical padding to Desktop menu bar items and submenus. This option is NOT RECOMMENDED as it does not work with Left and Right HTML areas. (Default: 0.6em)', 'weaver-xtreme' /*adm*/),
        ),

        array(
            'name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . esc_html__('Desktop Menu Bar Padding', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_menu_bar_pad_dec',
            'type' => 'val_em',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Add padding to menu bar top and bottom for Desktop devices. (Default: 0 em)', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small>' . esc_html__('Desktop Menu Spacing. (not on Smart Menus)', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_right_padding_dec',
            'type' => 'val_em',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Add space between desktop menu bar items (Use value &gt; 1.0)', 'weaver-xtreme' /*adm*/),
        ),

        array('name' => esc_html__('Menu Bar Left/Right HTML', 'weaver-xtreme'), 'type' => 'break'),


        array(
            'name' => '<span class="i-left dashicons dashicons-editor-code"></span><small>' . esc_html__('Left HTML', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_html_left',
            'type' => '+textarea',
            'placeholder' => esc_html__('Any HTML, including shortcodes.', 'weaver-xtreme' /*adm*/),
            'info' => esc_html__('Add HTML Left (Works best with Centered Menu)(&#9733;Plus)', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Area', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_hide_left',
            'type' => '+select_hide',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Hide Left HTML', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => '<span class="i-left dashicons dashicons-editor-code"></span><small>' . esc_html__('Right HTML', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_html_right',
            'type' => $right_text,
            'placeholder' => esc_html__('Any HTML, including shortcodes.', 'weaver-xtreme' /*adm*/),
            'info' => esc_html__('Add HTML to Menu on Right (Works best with Centered Menu)', 'weaver-xtreme' /*adm*/) . $right_plus,
        ),


        array(
            'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . esc_html__('Hide Area', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_hide_right',
            'type' => $right_hide,
            'info' => '<em>' . $name . '</em>' . esc_html__(': Hide Right HTML', 'weaver-xtreme' /*adm*/),
        ),


        array(
            'name' => '<small>' . esc_html__('HTML: Text Color', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_html_color',
            'type' => 'ctext',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Text Color for Left/Right Menu Bar HTML', 'weaver-xtreme' /*adm*/),
        ),
        array(
            'name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . esc_html__('HTML: Top Margin', 'weaver-xtreme' /*adm*/) . '</small>',
            'id' => $id . '_html_margin_dec',
            'type' => 'val_em',
            'info' => '<em>' . $name . '</em>' . esc_html__(': Margin above Added Menu HTML (Used to adjust for Desktop menu. Negative values can help.)', 'weaver-xtreme' /*adm*/),
        ),


    );

    weaverx_form_show_options($opts2, false, false);


    if ($submit) {
        weaverx_form_submit('');
    }
}


function weaverx_form_text_props($value, $type = 'titles'): void
{
    // display text properties for an area or title

    $id = $value['id'];
    $name = $value['name'];
    $info = $value['info'];

    $id_colorbg = $id . '_bgcolor';

    $id_color = $id . '_color';
    $id_size = $id . '_font_size';
    $id_family = $id . '_font_family';
    $id_bold = $id . '_bold';
    $id_normal = $id . '_normal';
    $id_italic = $id . '_italic';

    // COLOR BG & COLOR BOX

    if ($id == 'wrapper') {
        echo '<tr><td></td><td colspan="2"><p>';
        echo wp_kses_post(__('<strong>Important note:</strong> The Wrapper Area provides default
<em>background color, text color, and text font properties</em>
for most other areas, including Header, Container, Content, Widgets, and more.',
            'weaver-xtreme' /*adm*/));
        echo "</p></td></tr>\n";
    }

    //echo "\n<!-- *************************** weaverx_form_text_props ID: {$id} ***************************** -->\n";

    weaverx_form_ctext(array(
        'name' => $name . ' BG',
        'id' => $id_colorbg,
        'info' => '<em>' . $info . wp_kses_post(__(':</em> Background Color (use CSS+ to specify custom CSS for area)', 'weaver-xtreme' /*adm*/)),
    ));


    if ($type == 'menu' || $id == 'post_title') {
        weaverx_form_ctext(array(
            'name' => $name . ' ' . esc_html__('Text Color', 'weaver-xtreme' /*adm*/),
            'id' => $id_color,
            'info' => '<em>' . $info . wp_kses_post(__(':</em> Text properties', 'weaver-xtreme' /*adm*/)),
        ));
    } else {
        weaverx_form_color(array(
            'name' => $name . ' ' . esc_html__('Text Color', 'weaver-xtreme' /*adm*/),
            'id' => $id_color,
            'info' => '<em>' . $info . wp_kses_post(__(':</em> Text properties', 'weaver-xtreme' /*adm*/)),
        ));
    }

    // FONT PROPERTIES
    ?>
    <tr>
        <th scope="row"><span class="i-left font-bold font-italic"><span style="font-size:16px;">a</span><span
                        style="font-size:14px;">b</span><span style="font-size:12px;">c</span></span><small>
                <?php echo ($type == 'titles') ? esc_html__('Title', 'weaver-xtreme' /*adm*/) : esc_html__('Text', 'weaver-xtreme' /*adm*/); ?>
                <?php esc_html_e('Font properties:', 'weaver-xtreme' /*adm*/); ?></small>&nbsp;
        </th>
        <td colspan="2">
            <?php
            if ($type != 'content') {
                echo '&nbsp;<span class="rtl-break"><small><em>Size:</em></small>';
                weaverx_form_select_font_size(array('id' => $id_size), false);
                echo '</span>';
            }
            echo '&nbsp;<span class="rtl-break"><small><em>Family:</em></small>';
            weaverx_form_select_font_family(array('id' => $id_family), false);
            echo '</span>'; ?>

            <?php if ($type == 'titles') { ?>
                &nbsp;<span
                        class="rtl-break"><small><?php esc_html_e('Normal Weight', 'weaver-xtreme' /*adm*/); ?></small>
		<input type="checkbox" name="<?php weaverx_sapi_main_name($id_normal); ?>" id="<?php echo $id_normal; ?>"
<?php checked(weaverx_getopt_checked($id_normal)); ?> ></span>

            <?php } else { ?>
                &nbsp;<span
                        class="rtl-break"><small><strong><?php esc_html_e('Bold', 'weaver-xtreme' /*adm*/); ?></strong></small>
<?php
weaverx_form_font_bold_italic(array('id' => $id_bold));

/*		<input type="checkbox" name="<?php weaverx_sapi_main_name($id_bold); ?>" id="<?php echo $id_bold; ?>"
<?php checked(weaverx_getopt_checked( $id_bold )); ?> >
*/
?>
		</span>
            <?php } ?>
            &nbsp;<span class="rtl-break">
		<small><em><?php esc_html_e('Italic', 'weaver-xtreme' /*adm*/); ?></em></small>
<?php
weaverx_form_font_bold_italic(array('id' => $id_italic));
/*		<input type="checkbox" name="<?php weaverx_sapi_main_name($id_italic); ?>" id="<?php echo $id_italic; ?>"
/<?php checked(weaverx_getopt_checked( $id_italic )); ?> >
*/
?>
		</span>
            <?php if (apply_filters('weaverx_xtra_type', '+plus_fonts') == 'inactive') {
                echo '<small>&nbsp;&nbsp; ' . esc_html__('(Add new fonts with <em>Weaver Xtreme Plus</em>)', 'weaver-xtreme' /*adm*/) . '</small>';
            } else {
                echo '<small>&nbsp;&nbsp; ' . esc_html__('(Add new fonts from Custom &amp; Fonts tab.)', 'weaver-xtreme' /*adm*/) . '</small>';
            } ?>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><small><em>
                    <?php
                    if (version_compare(WEAVERX_VERSION, '4.9.0', '>=')) {
                        echo esc_html__('You can set Text Transform, Character and Word Spacing in the Customizer.', 'weaver-xtreme');
                    }
                    ?>
                </em></small></td>
    </tr>
    <?php

}

function weaverx_from_fi_location($value, $is_post = false): void
{
    $value['value'] = array(
        array('val' => 'content-top', 'desc' => esc_html__('With Content - top', 'weaver-xtreme' /*adm*/)),
        array('val' => 'content-bottom', 'desc' => esc_html__('With Content - bottom', 'weaver-xtreme' /*adm*/)),
        array('val' => 'title-before', 'desc' => esc_html__('With Title', 'weaver-xtreme' /*adm*/)),
        array('val' => 'title-banner', 'desc' => esc_html__('Banner above Title', 'weaver-xtreme')),
        array(
            'val' => 'header-image',
            'desc' => $is_post ? esc_html__('Hide on Blog View', 'weaver-xtreme' /*adm*/) :
                esc_html__('Header Image Replacement', 'weaver-xtreme' /*adm*/),
        ),
        array('val' => 'post-before', 'desc' => esc_html__('Before Page/Post, no wrap', 'weaver-xtreme' /*adm*/)),

        array('val' => 'post-bg', 'desc' => esc_html__('As BG Image, Tile', 'weaver-xtreme' /*adm*/)),
        array('val' => 'post-bg-cover', 'desc' => esc_html__('As BG Image, Cover', 'weaver-xtreme' /*adm*/)),
        array('val' => 'post-bg-parallax', 'desc' => esc_html__('As BG Image, Parallax', 'weaver-xtreme' /*adm*/)),
        array('val' => 'post-bg-parallax-full', 'desc' => esc_html__('As BG Image, Parallax Full', 'weaver-xtreme' /*adm*/)),
    );

    weaverx_form_select_id($value);
}


function weaverx_form_align($value): void
{
    $all = array('header_image_align', 'wrapper_align', 'container_align',
        'header_align', 'header_sb_align', 'header_html_align',
        'footer_align', 'footer_sb_align', 'footer_html_align',
        'infobar_align', 'top_align', 'bottom_align'
    );

    // sorry - this is really stupid, but the order of these determines the default value which needs
    // to be align-center for wrapper.

    if (version_compare(WEAVERX_VERSION, '4.9.0', '>=') &&
        in_array($value['id'], $all)) {
        if ($value['id'] == 'wrapper_align') {
            $value['value'] = array(
                array('val' => 'align-center', 'desc' => esc_html__('Center', 'weaver-xtreme' /*adm*/)),
                array('val' => 'float-left', 'desc' => esc_html__('Align Left', 'weaver-xtreme' /*adm*/)),
                array('val' => 'float-right', 'desc' => esc_html__('Align Right', 'weaver-xtreme' /*adm*/)),
                array('val' => 'alignnone', 'desc' => esc_html__('No Alignment', 'weaver-xtreme' /*adm*/)),
                array('val' => 'alignwide', 'desc' => esc_html__('Align Wide', 'weaver-xtreme' /*adm*/)),
                array('val' => 'alignfull', 'desc' => esc_html__('Align Full', 'weaver-xtreme' /*adm*/)),
                array('val' => 'wvrx-fullwidth', 'desc' => esc_html__('Extend BG to Full width', 'weaver-xtreme')),
            );
        } else {
            $value['value'] = array(
                array('val' => 'float-left', 'desc' => esc_html__('Align Left', 'weaver-xtreme' /*adm*/)),
                array('val' => 'align-center', 'desc' => esc_html__('Center', 'weaver-xtreme' /*adm*/)),
                array('val' => 'float-right', 'desc' => esc_html__('Align Right', 'weaver-xtreme' /*adm*/)),
                array('val' => 'alignnone', 'desc' => esc_html__('No Alignment', 'weaver-xtreme' /*adm*/)),
                array('val' => 'alignwide', 'desc' => esc_html__('Align Wide', 'weaver-xtreme' /*adm*/)),
                array('val' => 'alignfull', 'desc' => esc_html__('Align Full', 'weaver-xtreme' /*adm*/)),
                array('val' => 'wvrx-fullwidth', 'desc' => esc_html__('Extend BG to Full width', 'weaver-xtreme')),
            );
        }

    } else {
        if ($value['id'] == 'wrapper_align') {
            $value['value'] = array(
                array('val' => 'align-center', 'desc' => esc_html__('Center', 'weaver-xtreme' /*adm*/)),
                array('val' => 'float-right', 'desc' => esc_html__('Align Right', 'weaver-xtreme' /*adm*/)),
                array('val' => 'float-left', 'desc' => esc_html__('Align Left', 'weaver-xtreme' /*adm*/)),
                array('val' => 'alignnone', 'desc' => esc_html__('No Alignment', 'weaver-xtreme' /*adm*/)),
                array('val' => 'alignwide', 'desc' => esc_html__('Align Wide', 'weaver-xtreme' /*adm*/)),
                array('val' => 'alignfull', 'desc' => esc_html__('Align Full', 'weaver-xtreme' /*adm*/)),
            );
        } else {
            $value['value'] = array(
                array('val' => 'float-left', 'desc' => esc_html__('Align Left', 'weaver-xtreme' /*adm*/)),
                array('val' => 'align-center', 'desc' => esc_html__('Center', 'weaver-xtreme' /*adm*/)),
                array('val' => 'float-right', 'desc' => esc_html__('Align Right', 'weaver-xtreme' /*adm*/)),
                array('val' => 'alignnone', 'desc' => esc_html__('No Alignment', 'weaver-xtreme' /*adm*/)),
                array('val' => 'alignwide', 'desc' => esc_html__('Align Wide', 'weaver-xtreme' /*adm*/)),
                array('val' => 'alignfull', 'desc' => esc_html__('Align Full', 'weaver-xtreme' /*adm*/)),
            );
        }
    }

    weaverx_form_select_id($value);
}

function weaverx_form_align_standard($value): void
{
    $value['value'] = array(
        array('val' => 'float-left', 'desc' => esc_html__('Align Left', 'weaver-xtreme' /*adm*/)),
        array('val' => 'align-center', 'desc' => esc_html__('Center', 'weaver-xtreme' /*adm*/)),
        array('val' => 'float-right', 'desc' => esc_html__('Align Right', 'weaver-xtreme' /*adm*/)),
        array('val' => 'alignnone', 'desc' => esc_html__('No Alignment', 'weaver-xtreme' /*adm*/)),
    );

    weaverx_form_select_id($value);
}

function weaverx_form_fixedtop($value): void
{
    $value['value'] = array(
        array('val' => 'none', 'desc' => esc_html__('Standard Position : Not Fixed', 'weaver-xtreme' /*adm*/)),
        array('val' => 'fixed-top', 'desc' => esc_html__('Fixed to Top', 'weaver-xtreme' /*adm*/)),
        array('val' => 'scroll-fix', 'desc' => esc_html__('Fix to Top on Scroll', 'weaver-xtreme' /*adm*/)),
    );

    weaverx_form_select_id($value);
}

function weaverx_form_fi_align($value): void
{
    $value['value'] = array(
        array('val' => 'fi-alignleft', 'desc' => esc_html__('Align Left', 'weaver-xtreme' /*adm*/)),
        array('val' => 'fi-aligncenter', 'desc' => esc_html__('Center', 'weaver-xtreme' /*adm*/)),
        array('val' => 'fi-alignright', 'desc' => esc_html__('Align Right', 'weaver-xtreme' /*adm*/)),
        array('val' => 'fi-alignnone', 'desc' => esc_html__('No Align', 'weaver-xtreme' /*adm*/)),
    );

    weaverx_form_select_id($value);
}

function weaverx_form_select_hide($value): void
{
    $value['value'] = array(
        array('val' => 'hide-none', 'desc' => esc_html__('Do Not Hide', 'weaver-xtreme' /*adm*/)),
        array('val' => 's-hide', 'desc' => esc_html__('Hide: Phones', 'weaver-xtreme' /*adm*/)),
        array('val' => 'm-hide', 'desc' => esc_html__('Hide: Small Tablets', 'weaver-xtreme' /*adm*/)),
        array('val' => 'm-hide s-hide', 'desc' => esc_html__('Hide: Phones+Tablets', 'weaver-xtreme' /*adm*/)),
        array('val' => 'l-hide', 'desc' => esc_html__('Hide: Desktop', 'weaver-xtreme' /*adm*/)),
        array('val' => 'l-hide m-hide', 'desc' => esc_html__('Hide: Desktop+Tablets', 'weaver-xtreme' /*adm*/)),
        array('val' => 'hide', 'desc' => esc_html__('Hide on All Devices', 'weaver-xtreme' /*adm*/)),
    );

    weaverx_form_select_id($value);
}

function weaverx_form_select_font_size($value, $show_row = true): void
{
    $value['value'] = array(
        array('val' => 'default', 'desc' => esc_html__('Inherit', 'weaver-xtreme' /*adm*/)),
        array('val' => 'm-font-size', 'desc' => esc_html__('Medium Font', 'weaver-xtreme' /*adm*/)),
        array('val' => 'xxs-font-size', 'desc' => esc_html__('XX-Small Font', 'weaver-xtreme' /*adm*/)),
        array('val' => 'xs-font-size', 'desc' => esc_html__('X-Small Font', 'weaver-xtreme' /*adm*/)),
        array('val' => 's-font-size', 'desc' => esc_html__('Small Font', 'weaver-xtreme' /*adm*/)),
        array('val' => 'l-font-size', 'desc' => esc_html__('Large Font', 'weaver-xtreme' /*adm*/)),
        array('val' => 'xl-font-size', 'desc' => esc_html__('X-Large Font', 'weaver-xtreme' /*adm*/)),
        array('val' => 'xxl-font-size', 'desc' => esc_html__('XX-Large Font', 'weaver-xtreme' /*adm*/)),
        array('val' => 'huge-font-size', 'desc' => esc_html__('Huge Font', 'weaver-xtreme' /*adm*/)),
        array('val' => 'customA-font-size', 'desc' => esc_html__('Custom Size A', 'weaver-xtreme' /*adm*/)),
        array('val' => 'customB-font-size', 'desc' => esc_html__('Custom Size B', 'weaver-xtreme' /*adm*/)),
    );
    $value['value'] = apply_filters('weaverx_add_font_size', $value['value']);
    weaverx_form_select_id($value, $show_row);
}


function weaverx_form_select_font_family($value, $show_row = true): void
{
    $value['value'] = array(
        array('val' => 'default', 'desc' => esc_html__('Inherit', 'weaver-xtreme' /*adm*/)),
        array('val' => 'sans-serif', 'desc' => esc_html__('Arial (Sans Serif)', 'weaver-xtreme' /*adm*/)),
        array('val' => 'arialBlack', 'desc' => esc_html__('Arial Black', 'weaver-xtreme' /*adm*/)),
        array('val' => 'arialNarrow', 'desc' => esc_html__('Arial Narrow', 'weaver-xtreme' /*adm*/)),
        array('val' => 'lucidaSans', 'desc' => esc_html__('Lucida Sans', 'weaver-xtreme' /*adm*/)),
        array('val' => 'trebuchetMS', 'desc' => esc_html__('Trebuchet MS', 'weaver-xtreme' /*adm*/)),
        array('val' => 'verdana', 'desc' => esc_html__('Verdana', 'weaver-xtreme' /*adm*/)),

        array('val' => 'serif', 'desc' => esc_html__('Times (Serif)', 'weaver-xtreme' /*adm*/)),
        array('val' => 'cambria', 'desc' => esc_html__('Cambria', 'weaver-xtreme' /*adm*/)),
        array('val' => 'garamond', 'desc' => esc_html__('Garamond', 'weaver-xtreme' /*adm*/)),
        array('val' => 'georgia', 'desc' => esc_html__('Georgia', 'weaver-xtreme' /*adm*/)),
        array('val' => 'lucidaBright', 'desc' => esc_html__('Lucida Bright', 'weaver-xtreme' /*adm*/)),
        array('val' => 'palatino', 'desc' => esc_html__('Palatino', 'weaver-xtreme' /*adm*/)),

        array('val' => 'monospace', 'desc' => esc_html__('Courier (Monospace)', 'weaver-xtreme' /*adm*/)),
        array('val' => 'consolas', 'desc' => esc_html__('Consolas', 'weaver-xtreme' /*adm*/)),

        array('val' => 'papyrus', 'desc' => esc_html__('Papyrus', 'weaver-xtreme' /*adm*/)),
        array('val' => 'comicSans', 'desc' => esc_html__('Comic Sans MS', 'weaver-xtreme' /*adm*/)),
    );
    $value['value'] = apply_filters('weaverx_add_font_family', $value['value']);
    ?>
    <select name="<?php weaverx_sapi_main_name($value['id']); ?>" id="<?php echo $value['id']; ?>">
        <?php
        foreach ($value['value'] as $option) {
            ?>
            <option class="font-<?php echo $option['val']; ?>"
                    value="<?php echo $option['val'] ?>"<?php selected((weaverx_getopt($value['id']) == $option['val'])); ?>><?php echo $option['desc']; ?></option>
        <?php } ?>
    </select>
    <?php
}

function weaverx_form_rounded($value): void
{
    $value['value'] = array(
        array('val' => 'none', 'desc' => esc_html__('None', 'weaver-xtreme' /*adm*/)),
        array('val' => '-all', 'desc' => esc_html__('All Corners', 'weaver-xtreme' /*adm*/)),
        array('val' => '-left', 'desc' => esc_html__('Left Corners', 'weaver-xtreme' /*adm*/)),
        array('val' => '-right', 'desc' => esc_html__('Right Corners', 'weaver-xtreme' /*adm*/)),
        array('val' => '-top', 'desc' => esc_html__('Top Corners', 'weaver-xtreme' /*adm*/)),
        array('val' => '-bottom', 'desc' => esc_html__('Bottom Corners', 'weaver-xtreme' /*adm*/)),
    );

    weaverx_form_select_id($value);
}

function weaverx_form_font_bold_italic($value): void
{
    $value['value'] = array(
        array('val' => '', 'desc' => esc_html__('Inherit', 'weaver-xtreme' /*adm*/)),
        array('val' => 'on', 'desc' => esc_html__('On', 'weaver-xtreme' /*adm*/)),
        array('val' => 'off', 'desc' => esc_html__('Off', 'weaver-xtreme' /*adm*/)),
    );

    weaverx_form_select_id($value, false);
}

function weaverx_form_shadows($value): void
{
    $value['value'] = array(
        array('val' => '-0', 'desc' => esc_html__('No Shadow', 'weaver-xtreme' /*adm*/)), // as in .shadow-0
        array('val' => '-1', 'desc' => esc_html__('All Sides, 1px', 'weaver-xtreme' /*adm*/)),
        array('val' => '-2', 'desc' => esc_html__('All Sides, 2px', 'weaver-xtreme' /*adm*/)),
        array('val' => '-3', 'desc' => esc_html__('All Sides, 3px', 'weaver-xtreme' /*adm*/)),
        array('val' => '-4', 'desc' => esc_html__('All Sides, 4px', 'weaver-xtreme' /*adm*/)),
        array('val' => '-rb', 'desc' => esc_html__('Right + Bottom', 'weaver-xtreme' /*adm*/)),
        array('val' => '-lb', 'desc' => esc_html__('Left + Bottom', 'weaver-xtreme' /*adm*/)),
        array('val' => '-tr', 'desc' => esc_html__('Top + Right', 'weaver-xtreme' /*adm*/)),
        array('val' => '-tl', 'desc' => esc_html__('Top + Left', 'weaver-xtreme' /*adm*/)),
        array('val' => '-custom', 'desc' => esc_html__('Custom Shadow', 'weaver-xtreme' /*adm*/)),
    );
    $value['value'] = apply_filters('weaverx_add_shadows', $value['value']);

    weaverx_form_select_id($value);
}

// custom forms

function weaverx_custom_css($value = ''): void
{

    $css = weaverx_getopt('add_css');

    if (isset($value['id'])) {
        $icon = $value['id'];
    }
    if (!isset($icon) || !$icon) {
        $icon = ' ';
    }

    $dash = '';
    if ($icon[0] == '-') {                      // add a leading icon
        $dash = '<span style="padding:.2em;" class="dashicons dashicons-' . substr($icon, 1) . '"></span>';
    }
    ?>
    <tr class="atw-row-header">
        <td colspan="3">
            <a id="custom-css-rules"></a>
            <span style="color:black;padding:.2em;" class="dashicons dashicons-screenoptions"></span>
            <span style="font-weight:bold; font-size: larger;"><em>
		<?php _e('Custom CSS Rules', 'weaver-xtreme' /*adm*/); ?><?php weaverx_help_link('help.html#CustomCSS', esc_html__('Custom CSS Rules', 'weaver-xtreme' /*adm*/)); ?></em></span>
        </td>
    </tr>
    <tr>
        <td colspan="3">

            <!-- ======== -->
            <p>
                <?php _e('Rules you add here will be the <em>last</em> CSS Rules included by Weaver Xtreme, and thus override all other Weaver Xtreme generated CSS rules.
Specify complete CSS rules, but don\'t add the &lt;style&gt; HTML element. You can prefix your selectors with <code>.is-desktop, .is-mobile, .is-smalltablet, or .is-phone</code>
to create rules for specific devices.
<strong>NOTE:</strong> Because Weaver Xtreme uses classes on many of its elements, you may to need to use
<em>!important</em> with your rules to force the style override.
It is possible that other plugins might generate CSS that comes after these rules.', 'weaver-xtreme' /*adm*/); ?>
            </p>
            <p>
                <?php $customcss = '<a href="' . site_url('/wp-admin/')
                    . 'customize.php?autofocus%5Bcontrol%5D=custom_css">'; ?>
                Click <?php echo($customcss . 'HERE</a>'); ?> to use WordPress Global "Additional CSS" instead'
            </p>
            <?php weaverx_textarea(weaverx_getopt('add_css'), 'add_css', 12, '', 'width:95%;', 'wvrx-edit wvrx-edit-dir'); ?>

        </td>
    </tr>
    <?php
}

