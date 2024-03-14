<?php
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}
?><table class="table table-striped  jemex-archive-table">
    <thead>

    <tr>
        <th style="width: 3%; "><input id="select" type="checkbox"></th>
        <th style="width: 15%"><?php esc_attr_e('TITLE','order-export-and-more-for-woocommerce'); ?></th>
        <th style="width: 10%"><?php esc_attr_e('METHOD','order-export-and-more-for-woocommerce'); ?></th>
        <th style="width: 23%"><?php esc_attr_e('DELIVERY DETAILS','order-export-and-more-for-woocommerce'); ?></th>
        <th style="width: 10%"><?php esc_attr_e('FREQUENCY','order-export-and-more-for-woocommerce'); ?></th>
        <th style="width: 13%"><?php esc_attr_e('LAST RUN','order-export-and-more-for-woocommerce'); ?></th>
        <th style="width: 13%"><?php esc_attr_e('NEXT RUN','order-export-and-more-for-woocommerce'); ?></th>
        <th style="width: 13%"><?php esc_attr_e('ACTIONS','order-export-and-more-for-woocommerce'); ?></th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <input type="checkbox" name="schedule[]" value="">
            </td>
            <td>
                <a href="#"><?php esc_attr_e('Daily Packing List','order-export-and-more-for-woocommerce'); ?></a>
            </td>
            <td>
                <?php esc_attr_e('Directory','order-export-and-more-for-woocommerce'); ?>
            </td>
            <td>
                <b><?php esc_attr_e('Directory','order-export-and-more-for-woocommerce'); ?>:</b> <?php esc_attr_e('uploads','order-export-and-more-for-woocommerce'); ?>\ <b><?php esc_attr_e('Filename','order-export-and-more-for-woocommerce'); ?>:</b> <?php esc_attr_e('daily-packing-list.csv','order-export-and-more-for-woocommerce'); ?></td>
            <td>
                <?php esc_attr_e('Every Day','order-export-and-more-for-woocommerce'); ?>
            </td>
            <td></td>
            <td>
                <?php esc_attr_e('2020-05-30 00:00:01','order-export-and-more-for-woocommerce'); ?>
            </td>
            <td>
                <a href="#" class="btn btn-default" data-toggle="tooltip" title="" data-original-title="Edit Schedule">
                    <span class="fa fa-edit">
                    </span>
                </a>
                <a href="#" class="btn btn-default delete-schedule-button" data-toggle="tooltip" title="" data-original-title="Delete Schedule">
                    <span class="fa fa-trash">
                    </span>
                </a>
                <a href="#" class="btn btn-default " data-toggle="tooltip" title="" data-original-title="Clone Schedule">
                    <span class="fa fa-clone">
                    </span>
                </a>
            </td>
        </tr>
        <tr>
        <td>
            <input type="checkbox" name="schedule[]" value="">
        </td>
        <td>
            <a href="#"><?php esc_attr_e('Monthly Accountant','order-export-and-more-for-woocommerce'); ?></a>
        </td>
        <td>
            <?php esc_attr_e('Email','order-export-and-more-for-woocommerce'); ?>
        </td>
        <td>
            <b><?php esc_attr_e('To','order-export-and-more-for-woocommerce'); ?>:</b> <?php esc_attr_e('my-accountant@email.com','order-export-and-more-for-woocommerce'); ?> <b><?php esc_attr_e('Subject','order-export-and-more-for-woocommerce'); ?>:</b> <?php esc_attr_e('Monthly sales from my store','order-export-and-more-for-woocommerce'); ?>
        </td>
        <td>
            <?php esc_attr_e('1st day of the month','order-export-and-more-for-woocommerce'); ?>
        </td>
        <td></td>
        <td>
            <?php esc_attr_e('2020-06-01 00:00:01','order-export-and-more-for-woocommerce'); ?>
        </td>
        <td>
            <a href="#" class="btn btn-default" data-toggle="tooltip" title="" data-original-title="Edit Schedule">
                <span class="fa fa-edit">
                </span>
            </a>
            <a href="#" class="btn btn-default delete-schedule-button" data-toggle="tooltip" title="" data-original-title="Delete Schedule">
                <span class="fa fa-trash">
                </span>
            </a>
            <a href="#" class="btn btn-default " data-toggle="tooltip" title="" data-original-title="Clone Schedule">
                <span class="fa fa-clone">
                </span>
            </a>
        </td>
    </tr>
        <tr>
        <td>
            <input type="checkbox" name="schedule[]" value="">
        </td>
        <td>
            <a href="#"><?php esc_attr_e('Daily Dropshipper FTP','order-export-and-more-for-woocommerce'); ?></a>
        </td>
        <td>
            <?php esc_attr_e('FTP','order-export-and-more-for-woocommerce'); ?>
        </td>
        <td>
            <b><?php esc_attr_e('Host','order-export-and-more-for-woocommerce'); ?>:</b> <?php esc_attr_e('ftp.yoursite.com','order-export-and-more-for-woocommerce'); ?> <b><?php esc_attr_e('User','order-export-and-more-for-woocommerce'); ?>:</b> <?php esc_attr_e('ftpuser','order-export-and-more-for-woocommerce'); ?>
        </td>
        <td>
            <?php esc_attr_e('6:00 pm on Mo, Tu, We, Th, Fri, Sat, Sun','order-export-and-more-for-woocommerce'); ?>
        </td>
        <td></td>
        <td><?php esc_attr_e('2020-05-29 18:00:00','order-export-and-more-for-woocommerce'); ?></td>
        <td>
            <a href="#" class="btn btn-default" data-toggle="tooltip" title="" data-original-title="Edit Schedule">
                <span class="fa fa-edit">
                </span>
            </a>
            <a href="#" class="btn btn-default delete-schedule-button" data-toggle="tooltip" title="" data-original-title="Delete Schedule">
                <span class="fa fa-trash">
                </span>
            </a>
            <a href="#" class="btn btn-default " data-toggle="tooltip" title="" data-original-title="Clone Schedule">
                <span class="fa fa-clone">
                </span>
            </a>
        </td>
    </tr>

    </tbody>
</table>
