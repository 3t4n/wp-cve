<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
        .hide {
            display: none;
        }

        .show {
            display: inline;
        }
    </style>
</head>
<body>

<div id="main">
    <table class="form-table">
        <tbody>
        <!-- Dropdown menu -->
        <tr>
            <th>
                <label id="kl" for="shortcode-dropdown">Filter</label>
            </th>
            <td>
                <input type="text" id="form-filter" placeholder="search form">
            </td>
        </tr>
        <tr>
            <th>
                <label id="kl" for="shortcode-dropdown">Select Form</label>
            </th>
            <td>
                <p id="kosong"><i>Failed to load content. Please check your Username and Token API.</i></p>
                <p id="loading"><i>Loading content...</i></p>
                <div id="isi">
                    <select name="shortcode-dropdown" id="shortcode-dropdown" class="widefat"></select>
                </div>
            </td>
        </tr>
        <tr>
            <th>
                <label id="kl" for="text">Title</label>
            </th>
            <td>
                <input type="text" id="form-title" placeholder="Subscribe Here"/>
            </td>
        </tr>
        <tr>
            <th>
                <label id="kl" for="text">With Name</label>
            </th>
            <td>
                <select id="with-name">
                    <option value="0" selected>No</option>
                    <option value="1">Yes</option>
                </select>
            </td>
        </tr>

        <!-- Insert button -->
        <tr>
            <td>
                <p>
                    <button id="insert-shortcode" class="button-primary">Insert Form</button>
                </p>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<script>
    var delay = (function () {
        var timer = 0;
        return function (callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();

    function prepare_data() {
        var url = 'admin.php?page=kirimemail-wordpress-form&get_form=1';
        jQuery.ajax({
            type: 'post',
            url: url,
            data: {search: jQuery('#form-filter').val()},
            success: function (result) {
                if (result != '') {
                    jQuery('#loading, #kosong').hide();
                    jQuery('#isi').show();
                    jQuery('#shortcode-dropdown').html(result);
                } else {
                    jQuery('#kosong').show();
                    jQuery('#isi, #loading').hide();
                }
            }
        });
    }

    jQuery(function () {
        var uri = '';
        var url = 'admin.php?page=kirimemail-wordpress-form&get_form=1';
        var shortcode = '';
        var title = 'Subscribe Here';

        jQuery('#isi, #kosong').hide();
        jQuery('#loading').show();
        jQuery('#form-filter').on('input paste', function () {
            delay(prepare_data, 1000);
        });
        prepare_data();

        jQuery('#insert-shortcode').on('click', function () {
            uri = jQuery('#shortcode-dropdown').val();
            title = jQuery('#form-title').val();
            with_name = jQuery('#with-name').val();
            shortcode = '[keform url="' + uri + '" title="' + title + '" with_name="' + with_name + '"]';
            if (uri.length > 0) {
                tinyMCE.activeEditor.execCommand('mceReplaceContent', false, shortcode);
            }
            tb_remove();
        });

    });
</script>
</body>
</html>
