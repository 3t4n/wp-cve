<?php
/**
 * Admin View: Page - Activity Logs Home
 */

use CODNetwork\Repositories\CodNetworkRepository;

if (!defined('ABSPATH')) {
    exit;
}

?>
<div class="container">
    <div class="row">
        <div class="col-md-12 mt-4">
            <h2 class="h6">General Setting</h2>
            <hr>
            <form method="POST">
                <table class="form-table">
                    <tbody>
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc">Enable Logs Activity</th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span>Enable Logs Activity</span></legend>
                                <label for="logs_activity">
                                    <?php
                                    echo sprintf('<input name="logs_activity" id="logs_activity" type="checkbox" value="%s" %s> Logs Activity </label>', $status ? 'true' : 'false', $status ? 'checked' : '');
                                    ?>
                                    <p class="description">Make Activity log and monitoring system</p>
                            </fieldset>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary btn-outline-primary" name="submit">Save changes</button>
            </form>
        </div>
    </div>
</div>
