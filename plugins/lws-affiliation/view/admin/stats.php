<?php $arr = array('i' => array(), 'a' => array('href' => array(), 'target' => array()));?>

<div class="config-bloc-aff-stats">
    <?php if ($has_api) : ?>
    <div class="bloc_general_stats">
        <h1 class="title_bloc_aff_stats"> <?php esc_html_e('General statistics', 'lws-affiliation') ?>
        </h1>
        <div class="sub_bloc_general_stats">
            <div class="stats_bloc stats_bloc_clics">
                <h2 class="aff_stat_bloc_title"><img class="img_aff_stats"
                        src="<?php echo esc_url(plugins_url('/images/click.svg', dirname(__DIR__)))?>"
                        alt="Logo Disconnect" width="20px" height="25px"></img> <?php esc_html_e('Clicks', 'lws-affiliation')?>
                </h2>
                <span class="aff_stat_number"><?php echo number_format($data_global['global_clics'], 0, ',', ' '); ?></span>
                <small class="aff_stat_afternum"> <?php esc_html_e('click(s)', 'lws-affiliation'); ?></small>
            </div>

            <div class="stats_bloc stats_bloc_ventes">
                <h2 class="aff_stat_bloc_title"><img class="img_aff_stats"
                        src="<?php echo esc_url(plugins_url('/images/ventes.svg', dirname(__DIR__)))?>"
                        alt="Logo Disconnect" width="25px" height="25px"></img> <?php esc_html_e('Sales', 'lws-affiliation')?>
                </h2>
                <span class="aff_stat_number"><?php echo number_format($data_global['global_com'], 0, ',', ' '); ?></span>
                <small class="aff_stat_afternum"> <?php esc_html_e('sales(s)', 'lws-affiliation'); ?></small>
            </div>

            <div class="stats_bloc stats_bloc_final stats_bloc_comm">
                <h2 class="aff_stat_bloc_title"><img class="img_aff_stats"
                        src="<?php echo esc_url(plugins_url('/images/commissions.svg', dirname(__DIR__)))?>"
                        alt="Logo Disconnect" width="25px" height="23px"></img> <?php esc_html_e('Commissions', 'lws-affiliation')?>
                </h2>
                <span class="aff_stat_number"><?php if ($data_global['global_sum_com'] != null) {
                    echo esc_html(number_format($data_global['global_sum_com'], 2, ',', ' ') . " €");
                } else {
                    echo "0 €";
                } ; ?>
                </span>
            </div>
        </div>
        <h1 class="title_bloc_aff_stats"> <?php esc_html_e('Monthly statistics', 'lws-affiliation') ?>
        </h1>
        <div class="sub_bloc_general_stats">
            <div class="stats_bloc stats_bloc_clics">
                <h2 class="aff_stat_bloc_title"><img class="img_aff_stats"
                        src="<?php echo esc_url(plugins_url('/images/click.svg', dirname(__DIR__)))?>"
                        alt="Logo Disconnect" width="20px" height="25px"></img> <?php esc_html_e('Clicks', 'lws-affiliation')?>
                </h2>
                <span class="aff_stat_number"><?php echo number_format($data_global['month_clics'], 0, ',', ' '); ?></span>
                <small class="aff_stat_afternum"> <?php esc_html_e('click(s)', 'lws-affiliation'); ?></small>
            </div>

            <div class="stats_bloc stats_bloc_ventes">
                <h2 class="aff_stat_bloc_title"><img class="img_aff_stats"
                        src="<?php echo esc_url(plugins_url('/images/ventes.svg', dirname(__DIR__)))?>"
                        alt="Logo Disconnect" width="25px" height="25px"></img> <?php esc_html_e('Sales', 'lws-affiliation')?>
                </h2>
                <span class="aff_stat_number"><?php echo number_format($data_global['month_com'], 0, ',', ' '); ?></span>
                <small class="aff_stat_afternum"> <?php esc_html_e('sales(s)', 'lws-affiliation'); ?></small>
            </div>

            <div class="stats_bloc stats_bloc_final stats_bloc_comm">
                <h2 class="aff_stat_bloc_title"><img class="img_aff_stats"
                        src="<?php echo esc_url(plugins_url('/images/commissions.svg', dirname(__DIR__)))?>"
                        alt="Logo Disconnect" width="25px" height="23px"></img> <?php esc_html_e('Commissions', 'lws-affiliation')?>
                </h2>
                <span class="aff_stat_number"><?php if ($data_global['month_sum_com'] != null) {
                    echo esc_html(number_format($data_global['month_sum_com'], 2, ',', ' ') . " €");
                } else {
                    echo "0 €";
                } ; ?>
                </span>
            </div>
        </div>
    </div>
    <?php endif ?>
</div>