<form name="tidekey_<?= $tab?>_form" method="post" action="<?=$_SERVER['PHP_SELF']?>?page=tidekey&tab=<?= $tab?>&message=1">
    <?php if (function_exists ('wp_nonce_field') ) wp_nonce_field('tidekey_'.$tab.'_form');?>
    <input type="hidden" name="action" value="tidekey_<?= $tab?>"/>
        <h4 class="tidekey-title"><?php _e('Post Types','tidekey')?></h4>
        <table class="tidekey-table card pressthis">
            <?php foreach(get_post_types(array('public' => true ), 'objects') as $pt):?>
                <tr>
                    <th scope="row"><?= $pt->labels->name?></th>
                    <td colspan="2"><table style="width:100%;">
                        <tr>
                            <th scope="row"><?php _e("Archive $settings_tabs[$tab]",'tidekey')?></th>
                            <td>
                                <?php foreach($langs as $code => $lang):?>
                                    <label><?= $lang['translated_name']?></label>
                                    <input type="text" name="tidekey_<?= $tab?>[pt][<?= $pt->name?>][archive][<?= $code?>]" value="<?= ${"tidekey_$tab"}['pt'][$pt->name]['archive'][$code]?>">
                                <?php endforeach;?>
                                <br><small><b><?php _e('Allowed tags','tidekey')?></b>: %TITLE%, %POSTS_COUNT%</small>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" style="vertical-align: top;"><?php _e("Single $settings_tabs[$tab]",'tidekey')?></th>
                            <td>
                                <?php foreach($langs as $code => $lang):?>
                                    <label><?= $lang['translated_name']?></label>
                                    <input type="text" name="tidekey_<?= $tab?>[pt][<?= $pt->name?>][single][<?= $code?>]" value="<?= ${"tidekey_$tab"}['pt'][$pt->name]['single'][$code]?>">
                                <?php endforeach;?>
                                <br><small><b><?php _e('Allowed tags','tidekey')?></b>: <?= implode(', ',array_merge($tidekey_templates, array('%TITLE%')))?></small>
                                <?php if('on' == $tidekey_options['ind'][$pt->name]):?>
                                    <div class="singlemeta">
                                        <table>
                                            <tr><th>ID</th><th>Title</th><th>Meta <?= $settings_tabs[$tab]?></th></tr>
                                            <?php foreach(tidekey_get_rows($pt->name) as $row):?>
                                            <tr>
                                                <td><?= $row->ID?></td>
                                                <td><a href="<?= get_permalink($row->ID)?>"><?= $row->post_title?></a></td>
                                                <td><input type="text" name="tidekey_<?= $tab?>[pt][<?= $pt->name?>][ind_<?= $row->ID?>][<?= $code?>]" value="<?= ${"tidekey_$tab"}['pt'][$pt->name]['ind_'.$row->ID][$code]?>"></td>
                                            <tr>
                                            <?php endforeach;?>
                                        </table>
                                    </div>
                                <?php endif;?>
                            </td>
                        </tr>
                    </table></td>
                </tr>
            <?php endforeach;?>
        </table>
        <h4 class="tidekey-title"><?php _e('Taxonomies Terms','tidekey')?></h4>
        <table class="tidekey-table card pressthis">
            <?php foreach(get_taxonomies(array('public' => true),'objects') as $tax):?>
                <tr>
                    <th scope="row"><?= $tax->labels->name?></th>
                    <td colspan="2">
                        <?php foreach($langs as $code => $lang):?>
                            <label><?= $lang['translated_name']?></label>
                            <input type="text" name="tidekey_<?= $tab?>[tax][<?= $tax->name?>][<?= $code?>]" value="<?= ${"tidekey_$tab"}['tax'][$tax->name][$code]?>">
                        <?php endforeach;?>
                        <br><small><b><?php _e('Allowed tags','tidekey')?></b>: %TITLE%, %POSTS_COUNT%</small>
                    </td>
                </tr>
            <?php endforeach;?>
        </table>
        <h4 class="tidekey-title"><?php _e('Other','tidekey')?></h4>
        <table class="tidekey-table card pressthis">
            <tr>
                <th scope="row"><?php _e('Home Page','tidekey')?></th>
                <td colspan="2">
                    <?php foreach($langs as $code => $lang):?>
                        <label><?= $lang['translated_name']?></label>
                        <input type="text" name="tidekey_<?= $tab?>[other][home][<?= $code?>]" value="<?= ${"tidekey_$tab"}['other']['home'][$code]?>">
                    <?php endforeach;?>
                </td>
            </tr>
        </table>
    <p><input type="submit" name="tidekey_<?= $tab?>_btn" value="<?php _e('Update','tidekey')?>" class="button success" /></p>
</form>