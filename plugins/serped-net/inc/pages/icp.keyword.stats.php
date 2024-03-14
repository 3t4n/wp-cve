<?php
include 'input.php';
global $wpdb;
$keyword_stats = $wpdb->prefix . "icp_keyword_stats";
$links = $wpdb->prefix . "icp_project_links";
$keywords = $wpdb->prefix . "icp_project_keywords";
$projects = $wpdb->prefix . "icp_projects";
$posts = $wpdb->prefix . "posts";
$globalStats = "SELECT k.keyword keyword, COUNT( k.keyword ) count FROM $keyword_stats ks, $keywords k WHERE ks.keyword_id = k.id GROUP BY k.keyword";
$projectStats = "SELECT p.project_name name, COUNT( ks.id ) count FROM $keyword_stats ks, $projects p WHERE ks.project_id = p.id GROUP BY ks.project_id";
$postStats = "SELECT p.post_type type, p.post_title title, COUNT( ks.id ) count FROM $keyword_stats ks, $posts p WHERE ks.post_id = p.ID GROUP BY ks.post_id";
$keywordStatsLinks = "SELECT k.keyword keyword, pl.url, COUNT( ks.link_id ) count_links FROM $keyword_stats ks, $keywords k, $links pl WHERE ks.keyword_id = k.id AND ks.link_id = pl.id GROUP BY k.keyword, ks.link_id";
$keywordStatsPosts = "SELECT k.keyword keyword, p.post_type type, p.post_title, p.ID pid, COUNT( p.ID ) count_posts FROM $keyword_stats ks, $keywords k, $posts p WHERE ks.keyword_id = k.id AND ks.post_id = p.ID GROUP BY k.keyword, ks.post_id";
$keywordStatsProject = "SELECT k.keyword keyword, p.project_name,  COUNT( p.id ) count_projects FROM $keyword_stats ks, $keywords k, $projects p WHERE ks.keyword_id = k.id AND ks.project_id = p.id GROUP BY k.keyword, ks.project_id";
$global = $wpdb->get_results($globalStats);
$projects = $wpdb->get_results($projectStats);
$postsStats = $wpdb->get_results($postStats);
$keywordProjects = $wpdb->get_results($keywordStatsProject);
$keywordLinks = $wpdb->get_results($keywordStatsLinks);
$keywordPosts = $wpdb->get_results($keywordStatsPosts);
?>
<div class="wrap">
    <div style="max-width: 40%">
        <div id="icp_content" style="margin-top: 10px;">
            <table cellpadding="5" cellspacing="0" width="100%">
                <tr>
                    <td style="padding: 0px">
                        Here is where you can view the keyword statistics of your link projects.
                    </td>
                    <td align="right" style="padding: 0px">
                        <form id="csvFrm" method="post" action="<?php echo plugins_url('icp.keyword.stats.generate.php', __FILE__); ?>" >
                            <input type="hidden" name="download" value="<?php echo get_home_path(); ?>" />
                            <input type="button" onclick="document.getElementById('csvFrm').submit()" value="Download CSV File" />
                        </form>
                    </td>
                </tr>
            </table>
        </div>    
    </div>
    <div class="" style="max-width: 40%">
        <div id="icp_content" style="margin-top: 10px;">
            <h2>Global Keyword Count</h2><br />
            <?php
            if (count($global) == 0) {
                echo '<p>No data available</p>';
            } else {
                ?>
                <table cellpadding="5" cellspacing="0" width="100%"> 
                    <tr style="background-color:#e0e0e0;">
                        <th style="border-right:1px #fff solid;">Keyword</th>
                        <th>Keyword Count</th>
                    </tr>
                    <?php
                    $i = 0;
                    foreach ($global as $keyword) {
                        $bgc = ($i % 2 == 0) ? '#fff' : '#f4f4f4';
                        ?>
                        <tr style="background-color:<?= $bgc ?>">
                            <td align="center"><?php echo $keyword->keyword; ?></td>
                            <td style="text-align: center;" ><?php echo $keyword->count; ?></td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                </table>
            <?php } ?>
            <div style="clear:both"></div>
        </div>
        <div id="icp_content" style="margin-top: 10px;">
            <h2>Project Keyword Count</h2><br />
            <?php
            if (count($projects) == 0) {
                echo '<p>No data available</p>';
            } else {
                ?>
                <table cellpadding="5" cellspacing="0" width="100%"> 
                    <tr style="background-color:#e0e0e0;">
                        <th style="border-right:1px #fff solid;">Project Name</th>
                        <th>Keyword Count</th>
                    </tr>
                    <?php
                    $i = 0;
                    foreach ($projects as $project) {
                        $bgc = ($i % 2 == 0) ? '#fff' : '#f4f4f4';
                        ?>
                        <tr style="background-color:<?= $bgc ?>">
                            <td><?php echo $project->name; ?></td>
                            <td style="text-align: center;" ><?php echo $project->count; ?></td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                </table>
            <?php } ?>
            <div style="clear:both"></div>
        </div>
        <div id="icp_content" style="margin-top: 10px;">
            <h2>Post Keyword Count</h2><br />
            <?php
            if (count($postsStats) == 0) {
                echo '<p>No data available</p>';
            } else {
                ?>
                <table cellpadding="5" cellspacing="0" width="100%"> 
                    <tr style="background-color:#e0e0e0;">
                        <th style="border-right:1px #fff solid;">Post Title</th>
                        <th>Keyword Count</th>
                    </tr>
                    <?php
                    $i = 0;
                    foreach ($postsStats as $post) {
                        $bgc = ($i % 2 == 0) ? '#fff' : '#f4f4f4';
                        ?>
                        <tr style="background-color:<?= $bgc ?>">
                            <td><?php echo $post->title . " (" . $post->type . ")"; ?></td>
                            <td style="text-align: center;" ><?php echo $post->count; ?></td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                </table>
            <?php } ?>
            <div style="clear:both"></div>
        </div>
        <div id="icp_content" style="margin-top: 10px;">
            <h2>Keyword Statistics</h2><br />
            <?php
            if (count($postsStats) == 0 && count($keywordProjects) == 0 && count($keywordLinks) == 0 && count($keywordPosts) == 0) {
                echo '<p>No data available</p>';
            } else {
                ?>
                <p><strong>Keyword - Project</strong></p>
                <table cellpadding="5" cellspacing="0" width="100%"> 
                    <tr style="background-color:#e0e0e0;">
                        <th width="30%" style="border-right:1px #fff solid;">Keyword</th>
                        <th width="50%" style="border-right:1px #fff solid;">Project Name</th>
                        <th  width="20%">Project Count</th>
                    </tr>
                    <?php
                    $i = 0;
                    foreach ($keywordProjects as $kp) {
                        $bgc = ($i % 2 == 0) ? '#fff' : '#f4f4f4';
                        ?>
                        <tr style="background-color:<?= $bgc ?>">
                            <td align="center"><?php echo $kp->keyword; ?>
                                <div align="center"></div></td>
                            <td><?php echo $kp->project_name; ?></td>
                            <td style="text-align: center;" ><?php echo $kp->count_projects; ?></td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                </table>
                <p><strong>Keyword - Link</strong></p>
                <table cellpadding="5" cellspacing="0" width="100%"> 
                    <tr style="background-color:#e0e0e0;">
                        <th  width="30%" style="border-right:1px #fff solid;">Keyword</th>
                        <th  width="50%" style="border-right:1px #fff solid;">Link</th>
                        <th  width="20%">Link Count</th>
                    </tr>
                    <?php
                    $i = 0;
                    foreach ($keywordLinks as $kl) {
                        $bgc = ($i % 2 == 0) ? '#fff' : '#f4f4f4';
                        ?>
                        <tr style="background-color:<?= $bgc ?>">
                            <td align="center"><?php echo $kl->keyword; ?></td>
                            <td><?php echo $kl->url; ?></td>
                            <td style="text-align: center;" ><?php echo $kl->count_links; ?></td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                </table>
                <p><strong>Keyword - Post Title</strong></p>
                <table cellpadding="5" cellspacing="0" width="100%"> 
                    <tr style="background-color:#e0e0e0;">
                        <th  width="30%" style="border-right:1px #fff solid;">Keyword</th>
                        <th  width="50%" style="border-right:1px #fff solid;">Post Title</th>
                        <th  width="20%">Post Count</th>
                    </tr>
                    <?php
                    $i = 0;
                    foreach ($keywordPosts as $kp) {
                        $bgc = ($i % 2 == 0) ? '#fff' : '#f4f4f4';
                        ?>
                        <tr style="background-color:<?= $bgc ?>">
                            <td align="center"><?php echo $kp->keyword; ?></td>
                            <td><a href="<?= get_permalink($kp->pid) ?>" target="_blank"><?php echo $kp->post_title . " (" . $kp->type . ")"; ?></a></td>
                            <td style="text-align: center;" ><?php echo $kp->count_posts; ?></td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                </table>
            <?php } ?>
            <div style="clear:both"></div>
        </div>
    </div>
</div>
