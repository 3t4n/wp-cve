<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php MJTC_message::MJTC_getMessage(); ?>
<div id="msadmin-wrapper" class="msadmin-add-on-page-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('addonslist'); ?>
        <div id="msadmin-data-wrp">
            <div class="msadmin-add-on-page-wrp">
                <div class="add-on-banner">
                    <img class="add-on-banner-left-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/left-image.png" alt="<?php echo esc_html(__('left image','majestic-support')); ?>"/>
                    <img class="add-on-banner-center-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/logo.png" alt="<?php echo esc_html(__('Logo','majestic-support')); ?>" />
                    <img class="add-on-banner-right-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/right-image.png" alt="<?php echo esc_html(__('right image','majestic-support')); ?>" />
                </div>
                <div class="add-on-page-cnt">
                    <div class="add-on-sec-header">
                        <h1 class="add-on-header-tit"><?php echo esc_html(__('Add-ons For Majestic Support','majestic-support')); ?></h1>
                        <div class="add-on-header-text"><?php echo esc_html(__('Get trusted WordPress add-ons. Guaranteed to work fast, safe to use, beautifully coded, packed with features, and are easy to use.','majestic-support')); ?></div>
                    </div>
                    <div class="add-on-msg">
                        <h3 class="add-on-msg-txt"><?php echo esc_html(__('Save big today with an exclusive membership plan!','majestic-support')); ?></h3>
                        <a title="<?php echo esc_attr(__('Show','majestic-support')); ?>" href="https://majesticsupport.com/pricing/" class="add-on-msg-btn"><i class="fa fa-cart"></i><?php echo esc_html(__('show bundle pack','majestic-support')); ?></a>
                    </div>
                    <div class="add-on-list">
                        <div class="add-on-item agent">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/agent.png" alt="<?php echo esc_html(__('Agent','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Agents','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Add agents and assign roles and permissions to provide assistance and support for customer support tickets.','majestic-support')); ?></div>
                            <?php if(in_array('agent', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_html(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/agents/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item multiform">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/multiform.png" alt="<?php echo esc_html(__('multiform','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Multi Forms','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Create multiple custom ticket forms with different custom fields for each request type that you support.','majestic-support')); ?></div>
                            <?php if(in_array('multiform', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/multiform/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item email-piping">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/email-piping.png" alt="<?php echo esc_html(__('Email Piping','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Email Piping','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Email piping is the process of automatically turning incoming emails into support tickets without manual data entry.','majestic-support')); ?></div>
                            <?php if(in_array('emailpiping', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_html(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/email-piping/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item agentautoassign">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/agent-auto-assign.png" alt="<?php echo esc_html(__('agent auto assign','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Agent Auto Assign','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Make the ticket assignment process automatic and assign the most qualified agent to your tickets with conditions.','majestic-support')); ?></div>
                            <?php if(in_array('agentautoassign', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_html(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/agent-auto-assign/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item private-note">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/private-note.png" alt="<?php echo esc_html(__('private note','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Private Note','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Add private notes to a ticket and share crucial information internally with your agents, this private note is not visible to the customer.','majestic-support')); ?></div>
                            <?php if(in_array('note', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_html(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/internal-note/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item merge-tkt">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/merge-tickets.png" alt="<?php echo esc_html(__('merge tickets','majestic-support')); ?>"/>
                            <div class="add-on-name"><?php echo esc_html(__('Merge Tickets','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Merge one or more tickets into another if you receive two support requests from the same end user about the same issue.','majestic-support')); ?></div>
                            <?php if(in_array('mergeticket', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/merge-ticket/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item help-topic">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/helptopic.png" alt="<?php echo esc_html(__('helptopic','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Help Topics','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('The user can search for and choose the topic for which they need help using help topics','majestic-support')); ?></div>
                            <?php if(in_array('helptopic', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/helptopic/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item close-tkt">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/ticket-auto-close.png" alt="<?php echo esc_html(__('Auto Close Ticket','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Auto Close Ticket','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Manage abandoned tickets, and set rules for tickets over time, and those tickets will be closed automatically after a certain period.','majestic-support')); ?></div>
                            <?php if ( in_array('autoclose',majesticsupport::$_active_addons)) { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/close-ticket/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item kb">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/kb.png" alt="<?php echo esc_html(__('Knowledgebase','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Knowledge Base','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Stop wasting time on repetitive inquiries, build your knowledge base, and organize your solutions into categories for users.','majestic-support')); ?></div>
                            <?php if(in_array('knowledgebase', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/knowledge-base/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item overdue-tkt">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/ticket-overdue.png" alt="<?php echo esc_html(__('Ticket Overdue','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Ticket Overdue','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Define rules or set a specific time interval for making tickets automatically overdue.','majestic-support')); ?></div>
                            <?php if(in_array('overdue', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/overdue/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item time-tracking">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/time-tracking.png" alt="<?php echo esc_html(__('time tracking','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Time Tracking','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Track the amount of time spent on each customer support ticket without losing focus and enhance team productivity.','majestic-support')); ?></div>
                            <?php if(in_array('timetracking', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/time-tracking/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item tkt-histry">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/ticket-history.png" alt="<?php echo esc_html(__('Ticket History','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Ticket History','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Show the whole history of the ticket, including its status and any action taken in response to it.','majestic-support')); ?></div>
                            <?php if(in_array('tickethistory', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_html(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/ticket-history/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item paid-support">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/paid-support.png" alt="<?php echo esc_html(__('Paid Support','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Paid Support','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('The simplest approach to effectively manage payments for premium help is through paid support.','majestic-support')); ?></div>
                            <?php if(in_array('paidsupport', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/paid-support/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item email-piping">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/privatecredentials.png" alt="<?php echo esc_html(__('Private Credentials','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Private Credentials','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Customers will enter sensitive information on tickets, and this add-on is designed to help you keep that information secure.','majestic-support')); ?></div>
                            <?php if(in_array('emailpiping', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_html(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/widget/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item close-tkt-reason">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/close-ticket-reson.png" alt="<?php echo esc_html(__('Ticket close reason','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Ticket Closed Reason','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Empowers users to provide clear and concise explanations for why customer service tickets are being closed.','majestic-support')); ?></div>
                            <?php if(in_array('feedback', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/close-ticket/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item canned-resp">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/canned-responses.png" alt="<?php echo esc_html(__('Premade Responses','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Premade Responses','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Make ready-made responses to the most common questions in the tickets to swiftly address client issues.','majestic-support')); ?></div>
                            <?php if(in_array('cannedresponses', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/canned-responses/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item max-tkt">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/max-ticket.png" alt="<?php echo esc_html(__('max ticket','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Max Tickets','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Admins can limit the number of tickets that users can create as well as the number of tickets that agents can open individually.','majestic-support')); ?></div>
                            <?php if(in_array('maxticket', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/max-ticket/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item feedback">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/feedback.png" alt="<?php echo esc_html(__('Feedback','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Feedback','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('To enhance the quality of your services, ask your customers to complete a survey when a ticket is closed.','majestic-support')); ?></div>
                            <?php if(in_array('feedback', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/feedback/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item tkt-actions">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/ticket-actions.png" alt="<?php echo esc_html(__('ticket actions','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Ticket Actions','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Ticket action options allow you to execute additional actions on tickets.','majestic-support')); ?></div>
                            <?php if(in_array('actions', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/actions/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item export">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/export.png" alt="<?php echo esc_html(__('Export','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Export','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Easily export ticket data and performance report data from your majestic support to an Excel spreadsheet with our efficient and user-friendly solution.','majestic-support')); ?></div>
                            <?php if(in_array('export', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/export/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item email-cc">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/emailcc.png" alt="<?php echo esc_html(__('email cc','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Email CC','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Send the exact copy of the email to the provided email address with Email CC or BCC.','majestic-support')); ?></div>
                            <?php if(in_array('emailcc', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/email-cc/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item user-opt">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/user-options.png" alt="<?php echo esc_html(__('user options','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('User Options','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('User options enable you to define roles for new users when they are registered.','majestic-support')); ?></div>
                            <?php if(in_array('useroptions', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/user-options/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item smtp">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/smtp.png" alt="<?php echo esc_html(__('SMTP','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('SMTP','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('SMTP enables you to add  custom mail protocols to send and receive emails with the support ticket system.','majestic-support')); ?></div>
                            <?php if(in_array('smtp', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_html(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/smtp/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item multilanguagetemplate">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/multilanguageemailtemplates.png" alt="<?php echo esc_html(__('SMTP','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Multi Language Email Templates','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Create multi-language email templates for all default email templates to send customers emails in the language that they prefer.','majestic-support')); ?></div>
                            <?php if(in_array('multilanguageemailtemplates', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/multi-language-email-templates" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item downloads">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/downloads.png" alt="<?php echo esc_html(__('Downloads','majestic-support')); ?>"/>
                            <div class="add-on-name"><?php echo esc_html(__('Downloads','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Downloads can be very helpful for users who are looking to obtain the necessary information to resolve issues.','majestic-support')); ?></div>
                            <?php if(in_array('download', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/downloads/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item announcements">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/announcments.png" alt="<?php echo esc_html(__('Announcements','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Announcements','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Make an unlimited number of announcements related to the support system to encourage customer interaction.','majestic-support')); ?></div>
                            <?php if(in_array('announcement', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_html(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/announcements/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item ban-email">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/ban-email.png" alt="<?php echo esc_html(__('Ban Email','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Ban Email','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('With Ban Email, you can limit a users ability to create new tickets by blocking their email address.','majestic-support')); ?></div>
                            <?php if(in_array('banemail', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_html(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/ban-email/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item faq">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/faq.png" alt="<?php echo esc_html(__('FAQ','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('FAQ','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Provide answers to common user questions with the FAQ extension before they become tickets.','majestic-support')); ?></div>
                            <?php if(in_array('faq', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_html(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/faq/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item admin-widg">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/admin-widgets.png" alt="<?php echo esc_html(__('admin widgets','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Admin Widgets','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Get immediate data on your support operations as soon as you log into your WordPress administration area.','majestic-support')); ?></div>
                            <?php if(in_array('dashboardwidgets', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_html(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/admin-widget/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>
                        
                        <div class="add-on-item desk-notif">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/desktop-notifications.png" alt="<?php echo esc_html(__('desktop notifications','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Desktop Notifications','majestic-support')); ?></div>

                            <div class="add-on-txt"><?php echo esc_html(__('You will be informed of anything that occurs on your support system by the desktop notifications.','majestic-support')); ?></div>
                            <?php if(in_array('notification', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_html(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/desktop-notification/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item help-topic">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/woocommerce.png" alt="<?php echo esc_html(__('woocommerce','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('WooCommerce','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('The integration of the support ticket system for WooCommerce with your e-shop makes your support better and more professional.','majestic-support')); ?></div>
                            <?php if(in_array('helptopic', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_html(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/widget/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item mail-chimp">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/mail-chimp.png" alt="<?php echo esc_html(__('mail chimp','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Mail Chimp','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Manage your email marketing campaigns directly from your majestic support by adding new user accounts to your Mailchimp email list.','majestic-support')); ?></div>
                            <?php if(in_array('mailchimp', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/mail-chimp/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item internal-mail">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/internal-mail.png" alt="<?php echo esc_html(__('internal mail','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Internal Mail','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Send messages internally about support tickets and other issues between agents with internal mail.','majestic-support')); ?></div>
                            <?php if(in_array('mail', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/internal-mail/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item fe-widget">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/frontend-widget.png" alt="<?php echo esc_html(__('frontend widget','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Front-End Widgets','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__("Frontend Widgets include several widgets that can be used to enhance the plugin's functionality in various areas of the website.",'majestic-support')); ?></div>
                            <?php if(in_array('widgets', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_attr(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/widget/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item envato">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/envato.png" alt="<?php echo esc_html(__('envato','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Envato','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__("Clients won't be able to open a new ticket using the Envato Validation plugin if they don't have a valid Envato license.",'majestic-support')); ?></div>
                            <?php if(in_array('envatovalidation', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_html(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/envato/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                        <div class="add-on-item easy-digi-dwnlds">
                            <img class="add-on-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/add-on-list/easy-digital-downloads.png" alt="<?php echo esc_html(__('easy digital download','majestic-support')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Easy Digital Download','majestic-support')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__("Learn about a customer's purchase directly from the support dashboard by integrating Majestic Support with your EDD store.",'majestic-support')); ?></div>
                            <?php if(in_array('easydigitaldownloads', majesticsupport::$_active_addons)){ ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" class="add-on-btn"><?php echo esc_html(__('Installed','majestic-support')); ?></a>
                            <?php } else { ?>
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" href="https://majesticsupport.com/product/easy-digital-download/" class="add-on-btn"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            <?php } ?>
                        </div>

                    </div>
                    <div class="add-on-sec-header">
                        <h1 class="add-on-header-tit"><?php echo esc_html(__('Majestic Support Add-ons Bundle Pack','majestic-support')); ?></h1>
                        <div class="add-on-header-text"><?php echo esc_html(__('Save big today with an exclusive membership plan!','majestic-support')); ?></div>
                    </div>
                    <div class="add-on-bundle-pack-list">
                        <div class="add-on-bundle-pack-item basic">
                            <div class="add-on-bundle-pack-name"><?php echo esc_html(__('Basic','majestic-support')); ?></div>
                            <ul class="add-on-bundle-pack-feat">
                                <li><?php echo esc_html(__('Unlimited Agents','majestic-support')); ?></li>
                                <li><?php echo esc_html(__('Ticket Actions','majestic-support')); ?></li>
                                <li><?php echo esc_html(__('Ticket Auto Close','majestic-support')); ?></li>
                                <li><?php echo esc_html(__('FAQ','majestic-support')); ?></li>
                                <li><?php echo esc_html(__('Helptopic','majestic-support')); ?></li>
                                <li><?php echo esc_html(__('Ticket History','majestic-support')); ?></li>
                                <li><a title="<?php echo esc_attr(__('Show all','majestic-support')); ?>" target="_blank" href="https://majesticsupport.com/pricing/#compare-wrap"><?php echo esc_html(__('Show all','majestic-support')); ?></a></li>
                            </ul>
                            <div class="add-on-bundle-pack-btn">
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" target="_blank" href="https://majesticsupport.com/pricing/"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            </div>
                        </div>
                        <div class="add-on-bundle-pack-item standard">
                            <div class="add-on-bundle-pack-name"><?php echo esc_html(__('Standard','majestic-support')); ?></div>
                            <ul class="add-on-bundle-pack-feat">
                                <li><strong><?php echo esc_html(__('Everything in basic included and ','majestic-support')); ?></strong></li>
                                <li><?php echo esc_html(__('Export','majestic-support')); ?></li>
                                <li><?php echo esc_html(__('Announcements','majestic-support')); ?></li>
                                <li><?php echo esc_html(__('Internal Mail','majestic-support')); ?></li>
                                <li><?php echo esc_html(__('Private Note','majestic-support')); ?></li>
                                <li><?php echo esc_html(__('Premade Response','majestic-support')); ?></li>
                                <li><a title="<?php echo esc_attr(__('Show all','majestic-support')); ?>" target="_blank" href="https://majesticsupport.com/pricing/#compare-wrap"><?php echo esc_html(__('Show all','majestic-support')); ?></a></li>
                            </ul>
                            <div class="add-on-bundle-pack-btn">
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" target="_blank" href="https://majesticsupport.com/pricing/"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            </div>
                        </div>
                        <div class="add-on-bundle-pack-item professional">
                            <div class="add-on-bundle-pack-name"><?php echo esc_html(__('Professional','majestic-support')); ?></div>
                            <ul class="add-on-bundle-pack-feat">
                                <li><strong><?php echo esc_html(__('Everything in standard included and','majestic-support')); ?></strong></li>
                                <li><?php echo esc_html(__('Feedback','majestic-support')); ?></li>
                                <li><?php echo esc_html(__('Knowledge Base','majestic-support')); ?></li>
                                <li><?php echo esc_html(__('Merge Tickets','majestic-support')); ?></li>
                                <li><?php echo esc_html(__('Email Piping','majestic-support')); ?></li>
                                <li><?php echo esc_html(__('Time Tracking','majestic-support')); ?></li>
                                <li><strong><?php echo esc_html(__('All Future Addons','majestic-support')); ?></strong></li>
                                <li><a title="<?php echo esc_attr(__('Show all','majestic-support')); ?>" target="_blank" href="https://majesticsupport.com/pricing/#compare-wrap"><?php echo esc_html(__('Show all','majestic-support')); ?></a></li>
                            </ul>
                            <div class="add-on-bundle-pack-btn">
                                <a title="<?php echo esc_attr(__('buy now','majestic-support')); ?>" target="_blank" href="https://majesticsupport.com/pricing/"><?php echo esc_html(__('buy now','majestic-support')); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
