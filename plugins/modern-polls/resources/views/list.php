<?php
/********************************************************************
 * @plugin     ModernPolls
 * @file       views/list.php
 * @date       11.02.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/

use FelixTzWPModernPolls\Helpers\AppHelper;

if (!current_user_can('manage_polls')) die('Access Denied');
global $wp;
?>

<div class="mpp-body_wrapper">
    <div class="mpp-container">
        <div class="mpp-container_head mpp-border_bottom">
            <h2 class=""><?php _e('Overview', FelixTzWPModernPollsTextdomain); ?></h2>
            <a href="<?php echo esc_attr(wp_unslash($_SERVER['REQUEST_URI'])); ?>.create"
               class="mpp-btn mpp-btn_info"><?php _e('Create', FelixTzWPModernPollsTextdomain); ?></a>
            <div class="mpp-clearfix"></div>
        </div>
        <table class="mpp-table mpp-table_hover">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col"><?php _e('Question', FelixTzWPModernPollsTextdomain); ?></th>
                <th scope="col"><?php _e('Voters', FelixTzWPModernPollsTextdomain); ?></th>
                <th scope="col"><?php _e('Start Datetime', FelixTzWPModernPollsTextdomain); ?></th>
                <th scope="col"><?php _e('End Datetime', FelixTzWPModernPollsTextdomain); ?></th>
                <th scope="col"><?php _e('Status', FelixTzWPModernPollsTextdomain); ?></th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>

            <?php
            foreach ($polls as $poll) {
                $id = (int)$poll->id;
                $question = AppHelper::removeSlashes($poll->question);
                $start = date('d.m.Y', $poll->start) . ' @ ' . date('H:i:s', $poll->start);
                $votes = (int)$poll->totalvotes;
                $active = (int)$poll->active;
                $end = trim($poll->expiry);

                if (empty($end)) {
                    $end = __('No Expiry', FelixTzWPModernPollsTextdomain);
                } else {
                    $end = date('d.m.Y', $end) . ' @ ' . date('H:i:s', $end);
                }
                $status = 'unknown';
                if ($active === 1) {
                    $status = __('Open', FelixTzWPModernPollsTextdomain);
                } elseif ($active === -1) {
                    $status = __('Future', FelixTzWPModernPollsTextdomain);
                } else {
                    $status = __('Closed', FelixTzWPModernPollsTextdomain);
                }

                echo '<tr>';
                echo '  <td>' . $id . '</td>';
                echo '  <td>' . $question . '</td>';
                echo '  <td>' . $votes . '</td>';
                echo '  <td>' . $start . '</td>';
                echo '  <td>' . $end . '</td>';
                echo '  <td>' . $status . '</td>';
                echo '  <td>
                                <form method="post" action="' . esc_attr(wp_unslash($_SERVER['REQUEST_URI'])) . '">
                                <input type="hidden" name="id" value="' . $id . '">
                                <div class="mpp-dangerBtn">                                  
                                       
                                        <button type="submit" class="mpp-btn mpp-btn_danger mpp-tooltip" name="action" value="delete">
                                            <span class="mpp-tooltiptext mpp-tooltip-bottom">' . __('Delete', FelixTzWPModernPollsTextdomain) . '</span>
                                            <i class="mpp-icon-trash"></i>
                                        </button>
                                </div>
                                <div class="mpp-warningBtn">
                                    
                                        <button type="submit" class="mpp-btn mpp-btn_warning mpp-tooltip" name="action" value="edit">
                                            <span class="mpp-tooltiptext mpp-tooltip-bottom">' . __('Edit', FelixTzWPModernPollsTextdomain) . '</span>
                                            <i class="mpp-icon-edit"></i>
                                        </button>
                                    
                                </div>
                                </div>
                                <div class="mpp-infoBtn">
                                
                                    <button type="submit" class="mpp-btn mpp-btn_info mpp-tooltip" name="action" value="view">
                                        <span class="mpp-tooltiptext mpp-tooltip-bottom">' . __('View Results', FelixTzWPModernPollsTextdomain) . '</span>
                                        ' . __('Info', FelixTzWPModernPollsTextdomain) . '
                                    </button>
                                
                                </div>
                                </form>
                            </td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
        <?php
        if (empty($polls)) {
            echo "<p style='text-align: center'>" . __('No Polls. Create one.', FelixTzWPModernPollsTextdomain) . "</p>";
        }
        ?>
    </div>
</div>