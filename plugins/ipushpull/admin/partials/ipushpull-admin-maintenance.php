<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.ipushpull.com/wordpress
 * @since      2.0.0
 *
 * @package    Ipushpull
 * @subpackage Ipushpull/admin/partials
 */

$re = '/\[ipushpull_page (.*?)\]/';

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="ipp-maintenance">

<!--    <form action="--><?php //echo $action ?><!--" method="post">-->
<!--        <p style="float: right"><button type="submit" class="button">Convert ALL to UUID</button></p>-->
<!--        <input type="hidden" value="--><?php //echo $check ?><!--" name="check">-->
<!--        <input type="hidden" value="1" name="all">-->
<!--    </form>-->

    <h3>Posts with embedded pages</h3>
    <table>
        <thead>
        <tr>
            <th>Title</th>
            <th>Date</th>
            <th>Content</th>
            <!-- <th style="width: 200px">Convert</th> -->
        </tr>
        </thead>
        <?php foreach ($posts as $post) { ?>
            <tr>
                <td>
                    <a href="<?php echo $post->guid ?>" target="_blank">
                        <?php echo $post->post_title ?>
                    </a>
                </td>
                <td style="white-space: nowrap;">
                    <?php echo $post->post_date ?>
                </td>
                <td id="post-<?php echo $post->ID ?>">
                    <?php preg_match_all($re, $post->post_content, $matches, PREG_SET_ORDER, 0); ?>
                    <?php $embed_codes = []; if($matches) foreach($matches as $match) { $embed_codes[] = $match[0] ?>
                        <div><code><?php echo $match[0] ?></code></div>
                    <?php } ?>
                </td>
                <!-- <td align="right">

                    <form action="<?php echo $action ?>" method="post">
                        <button type="submit" class="button" onclick="jQuery('#which-<?php echo $post->ID ?>').val('page')">Use Page/Folder</button>
                        <button type="submit" class="button" onclick="jQuery('#which-<?php echo $post->ID ?>').val('uuid')">Use UUID</button>
                        <input type="hidden" value="<?php echo $check ?>" name="check">
                        <input type="hidden" value="<?php echo $post->ID ?>" name="id">
                        <input type="hidden" value="uuid" name="which" id="which-<?php echo $post->ID ?>">
                    </form>

                </td> -->
            </tr>
        <?php } ?>
    </table>
</div>

<script>
    // jQuery('form').submit(function(){
    //     var scope = jQuery(this);
    //     var values = scope.serializeArray();
    //     var obj = {};
    //     for (i = 0; i < values.length; i++) {
    //         obj[values[i].name] = values[i].value;
    //     }
    //     obj.action = 'ipushpull_post';
    //     obj.check = '<?php echo $check ?>';

    //     // update post embed code
    //     jQuery.post(ajaxurl, obj, function(res){
    //         var data = JSON.parse(res);
    //         if (res.error) {
    //             alert(res.message);
    //             return;
    //         }
    //         var code = [];
    //         data.forEach(function(row){
    //             code.push('<div><code>'+(row.html || 'Error: ' + row.data.detail)+'</code></div>');
    //         });
    //         jQuery('#post-' + obj.id).html(code.join(''));
    //         console.log(res);
    //     });

    //     return false;
    // })
</script>
