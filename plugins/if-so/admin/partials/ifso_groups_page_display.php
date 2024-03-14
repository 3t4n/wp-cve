<?php
$groups_service = IfSo\PublicFace\Services\GroupsService\GroupsService::get_instance();
$groups_list = $groups_service->get_groups();
$extra_tabs = apply_filters('ifso_groups_page_display_extra_tabs',[]);

function generate_version_symbol($version_number) {
    //This function appears in multiple places - move to a utility class - DRY
    $version_number += 65;
    $num_of_characters_in_abc = 26;
    $base_ascii = 64;
    $version_number = intval($version_number) - $base_ascii;

    $postfix = '';
    if ($version_number > $num_of_characters_in_abc) {
        $postfix = intval($version_number / $num_of_characters_in_abc) + 1;
        $version_number %= $num_of_characters_in_abc;
        if ($version_number == 0) {
            $version_number = $num_of_characters_in_abc;
            $postfix -= 1;
        }
    }

    $version_number += $base_ascii;
    return chr($version_number) . strval($postfix);
}
?>
<style type="text/css">
    #ifso-all-groups-table thead th {
        font-weight: 600;
        vertical-align: baseline;
        padding-top: 14px;
        padding-bottom: 16px;
        line-height: 1.1;
    }
    #ifso-all-groups-table thead th > span {
        font-size: 12px;
        font-weight: 400;
    }
    .ass_instructions {
        padding: 10px 20px 20px;
        margin: 80px 0 10px 0;
        border: 1px solid rgb(195, 196, 199);
    }
    .ass_instructions ul {
        padding-top: 10px;
    }

    /* shortcodes table copy code css - START */
    .shortcode-cell-code {
        padding-right: 20px;
        padding-left: 20px;
    }
    .shortcode-cell-code code {
        display: inline-block;
        position: relative;
        padding: 10px 20px 6px 10px;
        width: 100%;
        box-sizing: border-box;
    }
    .shortcode-cell-code code span {
        display: inline-block;
    }

    copyAudienceShortcodeButton {
        position: absolute;
        top: 0;
        right: 0;
        font-size: 15px;
        padding: 4px 4px 2px 4px;
        background: #D5D5D5;
        line-height: 1;
        color: black;
        cursor: pointer;
    }
    copyAudienceShortcodeButton:hover {
        background-color: #D0D0D0;
    }
    copyAudienceShortcodeButton::before {
        content: "";
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        left: -13px;
        width: 0;
        height: 0;
        border-top: 4px solid transparent;
        border-bottom: 4px solid transparent;
        border-left: 8px solid #888888;
    }
    copyAudienceShortcodeButton::after {
        content: "Copied!";
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        right: calc(100% + 13px);
        background-color: #888888;
        color: white;
        font-size: 12px;
        padding: 2px 4px;
        border-radius: 3px;
        white-space: nowrap;
    }
    copyAudienceShortcodeButton::before,
    copyAudienceShortcodeButton::after {
        opacity: 0;
        visibility: hidden;
        transition: visibility 0.1s linear, opacity 0.1s linear;
    }
    copyAudienceShortcodeButton.active::before,
    copyAudienceShortcodeButton.active::after {
        visibility: visible;
        opacity: 1;
    }
    .softnodisplay{
        display:none;
    }
    /* shortcodes table copy code css - END */
</style>

<div class="wrap">
    <h2>
        <?php
        _e('If-So Dynamic Content | Audiences');
        ?>
    </h2>
    <div class="ifso-audience-tabs-select-wrapper">
        <ul class="ifso-license-tabs-header">
            <li class="ifso-tab default-tab" data-tab="ifso-audiences-main-tab"><?php _e('Audiences', 'if-so');?></li>
            <?php
                if(!empty($extra_tabs)){
                    foreach ($extra_tabs as $tab){
                        if(!empty($tab['name']))
                            $prettyname = !empty($tab['prettyname']) ? $tab['prettyname'] : $tab['name'];
                            echo wp_kses_post("<li class='ifso-tab' data-tab='ifso-audiences-{$tab['name']}-tab'>{$prettyname}</li>");
                    }
                }
            ?>
        </ul>
    </div>
    <div class="ifso-audiences-main-tab">
        <form class="add_new_group" method="post"  action="<?php echo admin_url('admin-ajax.php'); ?>" >
            <h2 class="add_new_group_title">Create a New Audience</h2>
            <input name="group_name" type="text" required placeholder="<?php _e('Audience Name', 'if-so');?>">
            <input type="hidden" name="ifso_groups_action" value="add_group">
            <input type="hidden" name="action" value="ifso_groups_req">
            <?php wp_nonce_field( "ifso-groups-action-nonce"); ?>
            <button class="button button-primary" type="submit"><?php _e('Create', 'if-so'); ?></button>
        </form>

        <h2 class="ifso-all-groups-table-title">Your Audiences</h2>
        <table id="ifso-all-groups-table" class="widefat striped">
            <thead>
            <tr>
                <th><?php _e('Audience name', 'if-so');?></th>
                <th><?php _e('Triggers impacting user addition/removal from an audience', 'if-so');?></th>
                <th>
                    <?php _e('Add to audience shortcode', 'if-so');?><br>
                    <span><?php _e('Use type="remove" to remove a user from the audience', 'if-so');?></span>
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(isset($groups_list) && is_array($groups_list) && !empty($groups_list)){
                foreach ($groups_list as $group){
                    $occurences = '';
                    foreach($groups_service->scanTriggersForGroupOccurence($group) as $occ){
                        $versionsText='';
                        if(isset($occ['versions']) && is_array($occ['versions'])){
                            foreach($occ['versions'] as $version=>$action){
                                $versionName = generate_version_symbol($version);
                                $versionsText .= "Version {$versionName} ({$action}), ";
                            }
                            $versionsText = substr($versionsText, 0, -2);
                        }
                        $link = "<a href={$occ['link']} target='_blank '>{$occ['title']}</a>";
                        $versions = "<span>{$versionsText}</span>";
                        $occurences .= $link . '<br>&nbsp;&nbsp;&nbsp;&nbsp;' . $versions .  '<br>';
                    }

                    $addShortcode = "[ifso-audience type=\"add\" audience=\"{$group}\"]";
                    $shortcodeCellHTML = <<<EOD
                            <code>
                                <span>[ifso-audience type="add" audience="$group"]</span>
                                <copyAudienceShortcodeButton onclick="
                                    let shortcode = this.parentElement.querySelector(':scope > span').textContent
                                    navigator.clipboard.writeText(shortcode).then(() => {
                                        this.classList.add('active')
                                        setTimeout(() => { this.classList.remove('active') }, 2000)
                                    }).catch(err => {
                                        console.error('Failed to copy: ', err)
                                    })
                                ">🗎</copyAudienceShortcodeButton>
                            </code>
                        EOD;

                    $delme = admin_url('admin-ajax.php?action=ifso_groups_req&ifso_groups_action=remove_group&group_name=' . urlencode($group) . '&_wpnonce=' . wp_create_nonce('ifso-groups-action-nonce'));
                    echo "<tr>
                                <td> {$group}</td>
                                <td>{$occurences}</td>
                                <td class=\"shortcode-cell-code\">{$shortcodeCellHTML}</td>
                                <td><a class='delete' href='{$delme}'>Delete Audience</a></td>
                            </tr>";
                }
            }
            ?>
            <tbody>
        </table>
        <?php
        if(!isset($groups_list) || empty($groups_list))
            echo "<p style='text-align:center;font-style:italic;font-size: 18px;color:#a00;'>You haven't created any audiences yet</p>";
        ?>
        <div class="ass_instructions">
            <h3>How to Use the Audience Self-selection Form?</h3>
            <ol>
                <li>Enter an audience name and click the 'Create' button.</li>
                <li>Add or remove users from the audience using one of the following methods:
                    <ul>
                        <li>- When a condition is met</li>
                        <li>- Through a shortcode</li>
                        <li>- Via a self-selection form</li>
                    </ul>
                </li>
            </ol>
            <a href="https://www.if-so.com/help/documentation/segments/?utm_source=Plugin&utm_medium=audiencePage&utm_campaign=creatingAtrigger" target="_blank">Learn about Audiences</a>
        </div>
    </div>
    <?php
        if(!empty($extra_tabs)){
            foreach($extra_tabs as $tab){
                if(!empty($tab['name']) && $tab['content'])
                    echo "<div class='ifso-audiences-{$tab['name']}-tab softnodisplay'>{$tab['content']}</div>";
            }
        }
    ?>
</div>