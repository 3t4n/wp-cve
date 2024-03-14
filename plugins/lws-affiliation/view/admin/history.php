<style>
    p {
        font-size: 16px;
    }

    h2 {
        font-size: 18px;
    }
</style>
<?php $arr = array('i' => array(), 'a' => array('href' => array(), 'target' => array()));?>
<div class="config-bloc-aff">
    <?php if ($has_api) : ?>
    <p style="margin-top:0px" class="lws_aff_text_p">
        <?php echo wp_kses(__('On this page, you will find your <strong>25 last commissions.</strong>', 'lws-affiliation'), array('strong' => array())); ?>
        <?php echo wp_kses(__('There is a <strong>45 days delay</strong> between the moment a commission is registered and the moment it is accepted: ', 'lws-affiliation'), array('strong' => array())); ?>
        <?php esc_html_e('We must make sure the commission is verified and the customer is not planning to get refunded.', 'lws-affiliation'); ?>
    </p>
    <table id="commission_list" class="styled-table-aff nowrap" style="width:100%; border:none">
        <thead>
            <tr>
                <th class="aff_history_tab aff_tabhead">
                    <?php esc_html_e("Product", "lws-affiliation");?>
                </th>
                <th class="aff_history_tab">
                    <?php esc_html_e("Commission (euro)", "lws-affiliation");?>
                </th>
                <th class="aff_history_tab">
                    <?php esc_html_e("Sale Done At", "lws-affiliation");?>
                </th>
                <th class="aff_history_tab">
                    <?php esc_html_e("Status", "lws-affiliation");?>
                </th>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($last_sales as $commission) :?>
            <tr>
                <td class="aff_history_tab_content aff_tabcontent">
                    <?php echo esc_html($commission['commissions']['product']); ?>
                </td>
                <td class="aff_history_tab_content">
                    <?php echo esc_html($commission['commissions']['commission'] . "€"); ?>
                </td>
                <td class="aff_history_tab_content">
                    <?php echo esc_html(explode(' ', $commission['commissions']['created'])[0]); ?>
                </td>
                <td class="aff_history_tab_content" style="padding-right:30px">
                    <?php if ($commission['commissions']['status'] == 0) : ?>
                    <img style="margin-right: 3px; vertical-align: inherit;"
                        src="<?php echo esc_url(plugins_url('/images/en_cours.svg', dirname(__DIR__)))?>"
                        alt="Logo paid" width="15px" height="15px"></img>
                    <span class="await_approval"><?php echo esc_html_e('Awaiting approval', 'lws-affiliation'); ?>

                    </span>
                    <?php elseif ($commission['commissions']['status'] == 1) : ?>
                    <span class="accepted">
                        <img style="margin-right: 3px; vertical-align: inherit;"
                            src="<?php echo esc_url(plugins_url('/images/check_vert.svg', dirname(__DIR__)))?>"
                            alt="Logo paid" width="15px" height="15px"></img>
                        <?php echo esc_html_e('Commission paid', 'lws-affiliation'); ?>
                    </span>
                    <?php elseif ($commission['commissions']['status'] == 2) : ?>
                    <span class="refused"><?php echo esc_html_e('Refused', 'lws-affiliation'); ?></span>
                    <?php endif ?>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <?php endif ?>
</div>

<script>
    jQuery(document).ready(function() {
        var table = jQuery('#commission_list').DataTable({
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            "order": [
                [0, "desc"]
            ],
            responsive: true,
            order: [
                [3, 'desc']
            ],
            lengthMenu: [
                [5, 10, 25],
                [5, 10, 25],
            ],
            <?php if(get_locale() == 'fr_FR') : ?>
            language: {
                url: "<?php echo(esc_url(plugin_dir_url(dirname(__DIR__)) . 'languages/fr-FR.json'))?>"
            }
            <?php endif ?>
        });
    });
</script>