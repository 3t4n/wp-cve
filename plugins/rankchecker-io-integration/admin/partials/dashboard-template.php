<?php
$domain   = Rankchecker_Api::get_instance()->get_domain( get_option( 'rc_domain_id' ) );
$keywords = Rankchecker_Api::get_instance()->get_domain_keywords( get_option( 'rc_domain_id' ) );

function get_time_ago( $time ) {
    $time_difference = time() - $time;

    if( $time_difference < 1 ) {
        return 'less than 1 second ago';
    }

    $condition = array( 12 * 30 * 24 * 60 * 60 =>  'year',
        30 * 24 * 60 * 60       =>  'month',
        24 * 60 * 60            =>  'day',
        60 * 60                 =>  'hour',
        60                      =>  'minute',
        1                       =>  'second'
    );

    foreach( $condition as $secs => $str ) {
        $d = $time_difference / $secs;

        if ( $d >= 1 ) {
            $t = round( $d );
            return $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ago';
        }
    }

    return '';
}

?>
<div class="wrap">

    <div class="rc-logo">
        <img src="/wp-content/plugins/rankchecker/assets/img/logo.png" alt="Rankchecker Logo">
    </div>

    <h1>Rankchecker Dashboard</h1>

	<?php if ( empty( $keywords ) ) : ?>
        <div>
            <h3>Your keywords list empty</h3>
            <div class="rc-actions">
                <a href="https://rankchecker.io/user/domains" class="rc-btn">Add Keywords</a>
            </div>
        </div>
	<?php elseif ( is_wp_error( $keywords ) && $keywords->get_error_code() === 401 ) : ?>
        <div style="margin-top: 50px; text-align: center;">
            <h3>Your site not connected</h3>
            <div class="rc-actions">
                <a href="/wp-admin/admin.php?page=rankchecker_settings"  class="rc-btn">
                    Connect your site first
                </a>
            </div>
        </div>
	<?php else: ?>
        <div style="margin-top: 25px;">

            <div class="rc-actions">
                <a class="rc-btn" href="https://rankchecker.io/user/domains">Add/Edit Keywords</a>
            </div>

            <div class="rc-table-wrapper">
                <table class="rc-table wp-list-table widefat fixed striped table-view-lis">
                    <thead>
                    <tr>
                        <th>Keyword</th>
                        <th>Rank</th>
                        <th>Country</th>
                        <th>Device Type</th>
                        <th>Updated At</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ( $keywords as $keyword ) : ?>
                        <tr>
                            <td class="rc-keyword"><span><?= $keyword[ 'keyword' ] ?></span></td>
                            <td><?= $keyword[ 'rank' ] ? ( $keyword[ 'rank' ] > 100 ? 'Not in top 100' : $keyword[ 'rank' ] ) : 'N/A' ?></td>
                            <td class="rc-flag">
                                <img title="<?= $keyword[ 'search_engine' ][ 'engine' ] ?>" alt="<?= $keyword[ 'search_engine' ][ 'engine' ] ?>"
                                     src="<?= RANKCHECKER_DIR_URL ?>assets/img/flags/<?= $keyword[ 'search_engine' ][ 'country_code' ] ?>.svg">
                            </td>
                            <td class="rc-device rc-device--<?= $keyword[ 'device_type' ] ?>">
                                <img title="<?= ucfirst( $keyword[ 'device_type' ] ) ?>" alt="<?= $keyword[ 'device_type' ] ?>"
                                     src="<?= RANKCHECKER_DIR_URL ?>assets/img/<?= $keyword[ 'device_type' ] ?>.svg">
                            </td>
                            <td><?= get_time_ago(strtotime( $keyword[ 'updated_at' ] )) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="rc-actions rc-actions--center">
                <a class="rc-btn" href="https://rankchecker.io/user/domains">View More</a>
            </div>
        </div>
	<?php endif; ?>

</div>

<script>
    jQuery(document).ready(function ($) {

    });
</script>