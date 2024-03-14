<?php 
$bar_type = get_option('xmas_decoration_bar_type');
echo '<pre>' . print_r(get_option('xmas_decoration_body_date_range'), 1) . '</pre>';
?>
<div class="wrap">
    <h1>Xmas Decoration Options</h1>
    <form method="post" action="options.php">
        <?php settings_fields('xmas_decoration_settings'); ?>
        <table class="form-table">
            <tr >
                <th scope="row"><label >Like me work?</label></th>
                <td>
                    <script type='text/javascript' src='https://ko-fi.com/widgets/widget_2.js'></script><script type='text/javascript'>kofiwidget2.init('Support Me on Ko-fi', '#29abe0', 'I3I71FSC5');kofiwidget2.draw();</script> 
                </td>
            </tr>
            <tr >
                <th scope="row"><label for="xmas_decoration_bar_type">Decoration Bar Type</label></th>
                <td>
                    <select name="xmas_decoration_bar_type" id="xmas_decoration_bar_type">
                        <option value="top" <?php echo $bar_type === 'top' ? 'selected' : '' ?> >On top of page</option>
                        <option value="fixed" <?php echo $bar_type === 'fixed' ? 'selected' : '' ?> >Fixed on top</option>
                    </select>
                </td>
            </tr>
            <tr >
                <th scope="row"><label for="xmas_decoration_body_padding_top">Body Padding Top</label></th>
                <td>
                    <input type="text" id="xmas_decoration_body_padding_top" name="xmas_decoration_body_padding_top" value="<?php echo get_option('xmas_decoration_body_padding_top'); ?>" />
                    <p class="description">For some themes, the decoration bar will cover the menu. Use this option to move your menu.</p>
                </td>
            </tr>
            <tr >
                <th scope="row"><label for="xmas_decoration_body_date_range">Date Ranger</label></th>
                <td class="flatpickr">
                    <input 
                        type="text" 
                        id="xmas_decoration_body_date_range" 
                        name="xmas_decoration_body_date_range" 
                        value="<?php echo get_option('xmas_decoration_body_date_range'); ?>"
                        data-input />
                    <button class="button button-clear" type="button" data-clear>Clear</button>
                    <p class="description">Pick time to show decoration. Let it empty if you want decoration always shows.</p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.6/flatpickr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.6/flatpickr.min.css">
<script>
(function() {
    flatpickr('.flatpickr', {
        mode: "range",
        wrap: true,
        dateFormat: "Y-m-d",
    });
})();
</script>
<style>
    #xmas_decoration_body_date_range {
        width: 300px;
    }
</style>