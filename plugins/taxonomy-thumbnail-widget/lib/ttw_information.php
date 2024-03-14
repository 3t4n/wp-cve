<?php
$body = (object)array('slug' => 'taxonomy-thumbnail-widget');
$post_data = array('action' => 'plugin_information', 'request' => serialize($body));
$return = wp_remote_post('http://api.wordpress.org/plugins/info/1.0/', array('body' => $post_data));
$ttwPlugin = unserialize($return['body']);

// _e( $ttwSlug, 'taxonomymanager' );
$ttwSlug = $ttwPlugin->slug;
$ttwName = $ttwPlugin->name;
$ttwVersion = $ttwPlugin->version;
$ttwRequire = $ttwPlugin->requires;
$ttwTested = $ttwPlugin->tested;
$ttwRating = $ttwPlugin->rating;
$ttwPeoples = $ttwPlugin->num_ratings;
$ttwDownload = $ttwPlugin->downloaded;
$ttwLastUpdate = $ttwPlugin->last_updated;
?>

<h2 align="center"><br/>
    <a href="https://wordpress.org/plugins/<?php _e($ttwSlug, 'taxonomymanager'); ?>"
       title="<?php _e($ttwName, 'taxonomymanager'); ?>" target="_blank"><?php echo $ttwPlugin->name; ?></a></h2>
<ul class="ttwTab">
    <li><a href="javascript:void(0)" class="ttwTablinks" onclick="ttwttwTabs(event, 'pluginDetails')"
           id="ttwDefaultOpen">Plugin Details</a></li>
    <li><a href="javascript:void(0)" class="ttwTablinks" onclick="ttwttwTabs(event, 'authorDetails')">Plugin Author
            Details </a></li>
    <li><a href="javascript:void(0)" class="ttwTablinks" onclick="ttwttwTabs(event, 'pluginDescription')">Plugin
            Description</a></li>
</ul>
<div id="pluginDetails" class="ttwTabcontent">
    <table align="left" cellpadding="4" cellspacing="4" border="0" width="100%">
        <tr>
            <th>Plugin Version :</th>
            <td><?php _e($ttwVersion, 'taxonomymanager'); ?></td>
        </tr>

        <th>Plugin Requires :</th>
        <td><?php _e($ttwRequire, 'taxonomymanager'); ?></td>
        </tr>
        <tr>
            <th>Plugin Tested :</th>
            <td><?php _e($ttwTested, 'taxonomymanager'); ?></td>
        </tr>
        <tr>
            <th>Plugin Rating :</th>
            <td><?php _e($ttwRating, 'taxonomymanager'); ?>
                by
                <?php _e($ttwPeoples, 'taxonomymanager'); ?>
                Peoples
            </td>
        </tr>
        <tr>
            <th>Plugin Downloaded :</th>
            <td><?php _e($ttwDownload, 'taxonomymanager'); ?></td>
        </tr>
        <tr>
            <th>Plugin Last Updated :</th>
            <td><?php _e($ttwLastUpdate, 'taxonomymanager'); ?></td>
        </tr>
    </table>
</div>
<?php
$ttwAuthor = $ttwPlugin->author;
$ttwProfile = $ttwPlugin->author_profile;
?>
<div id="authorDetails" class="ttwTabcontent">
    <table align="left" cellpadding="4" cellspacing="4" border="0" width="100%">
        <tr>
            <th>Plugin Author :</th>
            <td><?php _e($ttwAuthor, 'taxonomymanager'); ?></td>
        </tr>
        <tr>
            <th>Plugin Author Profile :</th>
            <td><a href="<?php _e($ttwProfile, 'taxonomymanager'); ?>" title="sunilkumarthz">sunilkumarthz</a></td>
        </tr>
        <tr>
            <th>Plugin Author EMail :</th>
            <td><a href="mailto:sunilkumarthz@gmail.com" title="sunilkumarthz@gmail.com">sunilkumarthz@gmail.com</a>
            </td>
        </tr>
    </table>
</div>
<div id="pluginDescription" class="ttwTabcontent">
    <div class="plugin-description"><?php echo preg_replace('/(<br>)+$/', '', $ttwPlugin->sections['description']); ?></div>
</div>

