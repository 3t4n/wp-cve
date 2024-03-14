<?php
if (!defined('ABSPATH')) die('Restricted Access');
$c = MJTC_request::MJTC_getVar('page',null,'jsjobs');
$c = MJTC_majesticsupportphplib::MJTC_str_replace('majesticsupport_', '', $c);
$layout = MJTC_request::MJTC_getVar('mjslay');
$ff = MJTC_request::MJTC_getVar('fieldfor');
$for = MJTC_request::MJTC_getVar('for');
$majesticsupport_js ='
    jQuery( function() {
        jQuery( ".accordion" ).accordion({
            heightStyle: "content",
            collapsible: true,
            active: true,
        });
    });
    ';
    wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);

?>
<div id="msadmin-logo">
    <a title="<?php echo esc_attr(majesticsupport::$_config['title']); ?>" class="ms-anchor" href="<?php echo esc_url(admin_url('admin.php?page=majesticsupport'));?>">
        <img alt="<?php echo esc_attr(majesticsupport::$_config['title']); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/logo.png" />
    </a>
    <img id="msadmin-menu-toggle" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/menu.png" />
</div>
<ul class="msadmin-sidebar-menu tree accordion" data-widget="tree" id="accordion">
    <li class="treeview <?php if(($c == 'majesticsupport' && $layout != 'shortcodes') || $c == 'systemerror' || $c == 'slug') echo esc_attr('active'); ?>">
        <a href="admin.php?page=majesticsupport" title="<?php echo esc_attr(__('Dashboard' , 'majestic-support')); ?>">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Dashboard' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/dashboard.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Dashboard' , 'majestic-support')); ?> </span>
            <span class="ms_active"></span>
        </a>
        <ul class="msadmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'majesticsupport' && ($layout == 'controlpanel' || $layout == '')) echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport" title="<?php echo esc_attr(__('Dashboard', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Dashboard', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'majesticsupport' && $layout == 'aboutus') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport&mjslay=aboutus" title="<?php echo esc_attr(__('About Us','majestic-support')); ?>">
                    <?php echo esc_html(__('About Us','majestic-support')); ?>
                </a>
            </li>
            <?php /*?>
            <li class="<?php if($c == 'majesticsupport' && $layout == 'translations') echo esc_attr('active'); ?>">
                <a href="#" title="<?php echo esc_attr(__('Translations','majestic-support')); ?>">
                    <?php echo esc_html(__('Translations','majestic-support')); ?>
                </a>
            </li>
            <?php */?>
            <li class="<?php if($c == 'systemerror') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_systemerror" title="<?php echo esc_attr(__('System Errors', 'majestic-support')); ?>">
                    <?php echo esc_html(__('System Errors', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'slug' && ($layout == 'slug')) echo esc_attr('active'); ?>">
                <a href="admin.php?page=majesticsupport_slug&mjslay=slug" title="<?php echo esc_attr(__('slugs','majestic-support')); ?>">
                    <?php echo esc_html(__('Slugs','majestic-support')); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview <?php if($c == 'ticket' || ($c == 'fieldordering' && $ff == 1 || $c == 'export' || $c == 'multiform') ) echo esc_attr('active'); ?>">
        <a href="admin.php?page=majesticsupport_ticket" title="<?php echo esc_attr(__('Tickets' , 'majestic-support')); ?>">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Tickets' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/tickets.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Tickets' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
        </a>
        <ul class="msadmin-sidebar-submenu treeview-menu">
            <?php 
                $id='';
                $href="?page=majesticsupport_ticket&mjslay=addticket&formid=".esc_attr(MJTC_includer::MJTC_getModel('ticket')->getDefaultMultiFormId());
                if(in_array('multiform', majesticsupport::$_active_addons) && majesticsupport::$_config['show_multiform_popup'] == 1){
                    $id="id=multiformpopup";
                    $href='#';
                }
            ?>
            <li class="<?php if($c == 'ticket' && ($layout == '')) echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_ticket" title="<?php echo esc_attr(__('Tickets', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Tickets', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'ticket' && ($layout == 'addticket')) echo esc_attr('active'); ?>">
                <a <?php echo esc_attr($id); ?> href="<?php echo esc_url($href); ?>" class="?page=majesticsupport_ticket&mjslay=addticket&formid=<?php echo esc_attr(MJTC_includer::MJTC_getModel('ticket')->getDefaultMultiFormId()) ?>" title="<?php echo esc_attr(__('Create Ticket', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Create Ticket', 'majestic-support')); ?>
                </a>
            </li>
            <?php if(!in_array('multiform', majesticsupport::$_active_addons)){ ?>
            <li class="<?php if($c == 'fieldordering') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_fieldordering&fieldfor=1&formid=<?php echo esc_attr(MJTC_includer::MJTC_getModel('ticket')->getDefaultMultiFormId()) ?>" title="<?php echo esc_attr(__('Fields', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Fields', 'majestic-support')); ?>
                </a>
            </li>
            <?php } ?>
            <?php if(in_array('export', majesticsupport::$_active_addons)){ ?>
                <li class="<?php if($c == 'export') echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_export" title="<?php echo esc_attr(__('Export', 'majestic-support')); ?>">
                        <?php echo esc_html(__('Export', 'majestic-support')); ?>
                    </a>
                </li>
            <?php } ?>
            <?php if(in_array('multiform', majesticsupport::$_active_addons)){ ?>
                <li class="<?php if($c == 'multiform') echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_multiform" title="<?php echo esc_attr(__('multiforms', 'majestic-support')); ?>">
                        <?php echo esc_html(__('Multiforms', 'majestic-support')); ?>
                    </a>
                </li>
            <?php }else{ ?>
                <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-multiform/majestic-support-multiform.php');
                    if($plugininfo['availability'] == "1"){
                        $text = $plugininfo['text'];
                        $url = "plugins.php?s=majestic-support-multiform&plugin_status=inactive";
                    }elseif($plugininfo['availability'] == "0"){
                        $text = $plugininfo['text'];
                        $url = "https://majesticsupport.com/product/multiform/";
                    }
                ?>
                <li>
                    <a class="msadmin-sidebar-submenu-grey" href="javascript:void(0);" title="<?php echo esc_attr(__('Multiform', 'majestic-support')); ?>">
                        <?php echo esc_html(__('Multiform', 'majestic-support')); ?>
                    </a>
                    <a class="msadmin-sidebar-active-btn" href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>">
                        <?php echo esc_html($text); ?>
                    </a>
                </li>
            <?php } ?>
            <?php if(in_array('ticketclosereason', majesticsupport::$_active_addons)){ ?>
                <li class="<?php if($c == 'ticketclosereason') echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_ticketclosereason" title="<?php echo esc_attr(__('Ticket Close Reasons', 'majestic-support')); ?>">
                        <?php echo esc_html(__('Ticket Close Reasons', 'majestic-support')); ?>
                    </a>
                </li>
            <?php }else{ ?>
                <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-ticketclosereason/majestic-support-ticketclosereason.php');
                    if($plugininfo['availability'] == "1"){
                        $text = $plugininfo['text'];
                        $url = "plugins.php?s=majestic-support-ticketclosereason&plugin_status=inactive";
                    }elseif($plugininfo['availability'] == "0"){
                        $text = $plugininfo['text'];
                        $url = "https://majesticsupport.com/product/ticketclosereason/";
                    }
                ?>
                <li>
                    <a class="msadmin-sidebar-submenu-grey" href="javascript:void(0);" title="<?php echo esc_attr(__('Ticket Close Reason', 'majestic-support')); ?>">
                        <?php echo esc_html(__('Ticket Close Reason', 'majestic-support')); ?>
                    </a>
                    <a class="msadmin-sidebar-active-btn" href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>">
                        <?php echo esc_html($text); ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </li>
    <li class="treeview <?php if($c == 'smartreply') echo esc_attr('active'); ?>">
        <a href="admin.php?page=majesticsupport_smartreply" title="<?php echo esc_attr(__('Smart Replies' , 'majestic-support')); ?>">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Smart Replies' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/smart-reply.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Smart Replies' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
        </a>
        <ul class="msadmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'smartreply' && ($layout == '')) echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_smartreply" title="<?php echo esc_attr(__('Smart Reply', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Smart Replies', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'smartreply' && ($layout == 'addsmartreply')) echo esc_attr('active'); ?>">
                <a  href="?page=majesticsupport_smartreply&mjslay=addsmartreply" title="<?php echo esc_attr(__('Add Smart Reply', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Add Smart Reply', 'majestic-support')); ?>
                </a>
            </li>
        </ul>
    </li>
    <?php if ( in_array('agent',majesticsupport::$_active_addons)) { ?>
        <li class="treeview <?php if($c == 'agent' || $c == 'agentautoassign') echo esc_attr('active'); ?>">
            <a class="" href="admin.php?page=majesticsupport_agent" title="<?php echo esc_attr(__('Agents' , 'majestic-support')); ?>">
                <img class="ms_menu-icon" alt="<?php echo esc_html(__('Agents' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/staff.png'; ?>"/>
                <span class="ms_text"><?php echo esc_html(__('Agents' , 'majestic-support')); ?></span>
                <span class="ms_active"></span>
            </a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'agent' && ($layout == '')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_agent" title="<?php echo esc_attr(__('Agents' , 'majestic-support')); ?>">
                        <?php echo esc_html(__('Agents', 'majestic-support')); ?>
                    </a>
                </li>
                <li class="<?php if($c == 'agent' && ($layout == 'addstaff')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_agent&mjslay=addstaff" title="<?php echo esc_attr(__('Add Agent' , 'majestic-support')); ?>">
                        <?php echo esc_html(__('Add Agent', 'majestic-support')); ?>
                    </a>
                </li>
                <?php if(in_array('agentautoassign', majesticsupport::$_active_addons)){ ?>
                    <li class="<?php if($c == 'agentautoassign') echo esc_attr('active'); ?>">
                        <a href="?page=majesticsupport_agentautoassign" title="<?php echo esc_attr(__('Agent Auto Assign', 'majestic-support')); ?>">
                            <?php echo esc_html(__('Agent Auto Assign', 'majestic-support')); ?>
                        </a>
                    </li>
                <?php }else{ ?>
                    <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-agentautoassign/majestic-support-agentautoassign.php');
                        if($plugininfo['availability'] == "1"){
                            $text = $plugininfo['text'];
                            $url = "plugins.php?s=majestic-support-agentautoassign&plugin_status=inactive";
                        }elseif($plugininfo['availability'] == "0"){
                            $text = $plugininfo['text'];
                            $url = "https://majesticsupport.com/product/agentautoassign/";
                        }
                    ?>
                    <li>
                        <a class="msadmin-sidebar-submenu-grey" href="javascript:void(0);" title="<?php echo esc_attr(__('Agent Auto Assign', 'majestic-support')); ?>">
                            <?php echo esc_html(__('Auto Assign', 'majestic-support')); ?>
                        </a>
                        <a class="msadmin-sidebar-active-btn" href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>">
                            <?php echo esc_html($text); ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </li>
    <?php } else { ?>
        <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-agent/majestic-support-agent.php');
            if($plugininfo['availability'] == "1"){
                $text = $plugininfo['text'];
                $url = "plugins.php?s=majestic-support-agent&plugin_status=inactive";
            }elseif($plugininfo['availability'] == "0"){
                $text = $plugininfo['text'];
                $url = "https://majesticsupport.com/product/agents/";
            } ?>
        <li class="disabled-menu treeview">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Agents' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu-grey/staff.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Agents' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
            <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'agent' && ($layout == '')) echo esc_attr('active'); ?>">
                    <span>
                        <?php echo esc_html(__('Agents', 'majestic-support')); ?>
                    </span>
                    <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <li class="treeview <?php if($c == 'configuration') echo esc_attr('active'); ?>">
        <a class="" href="?page=majesticsupport_configuration&msconfigid=general" title="<?php echo esc_attr(__('Settings' , 'majestic-support')); ?>">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Settings' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/config.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Settings' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
        </a>
        <ul class="msadmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'configuration' && $layout != 'cronjoburl') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_configuration&msconfigid=general" title="<?php echo esc_attr(__('Settings' , 'majestic-support')); ?>">
                    <?php echo esc_html(__('Settings', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'configuration' && $layout == 'cronjoburl') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_configuration&mjslay=cronjoburl" title="<?php echo esc_attr(__('Cron Job URLs' , 'majestic-support')); ?>">
                    <?php echo esc_html(__('Cron Job URLs', 'majestic-support')); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview <?php if($c == 'premiumplugin' && $layout != 'addonstatus') echo esc_attr('active'); ?>">
        <a class="" href="admin.php?page=majesticsupport_premiumplugin" title="<?php echo esc_attr(__('Premium Addons' , 'majestic-support')); ?>">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Premium Addons' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/ad.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Premium Addons' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
        </a>
        <ul class="msadmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'premiumplugin' && ($layout == 'step1') || ($layout == 'step2') || ($layout == 'step3')) echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_premiumplugin&mjslay=step1" title="<?php echo esc_attr(__('Install Add-ons', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Install Add-ons', 'majestic-support')); ?>
                </a>    
            </li>
            <li class="<?php if($c == 'premiumplugin' && ($layout == 'addonfeatures')) echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_premiumplugin&mjslay=addonfeatures" title="<?php echo esc_attr(__('Add-ons List', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Add-ons List', 'majestic-support')); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview <?php if($c == 'premiumplugin' && $layout == 'addonstatus') echo esc_attr('active'); ?>">
        <a class="" href="admin.php?page=majesticsupport_premiumplugin" title="<?php echo esc_attr(__('Add-ons Status' , 'majestic-support')); ?>">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Add-ons Status' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/addon-status.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Add-ons Status' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
        </a>
        <ul class="msadmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'premiumplugin' && $layout == 'addonstatus') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_premiumplugin&mjslay=addonstatus" title="<?php echo esc_attr(__('Add-ons Status', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Add-ons Status', 'majestic-support')); ?>
                </a>
            </li>
        </ul>
    </li>
    
    <li class="treeview <?php if($c == 'majesticsupport' && $layout == 'shortcodes') echo esc_attr('active'); ?>">
        <a class="" href="?page=majesticsupport_shortcodes" title="<?php echo esc_attr(__('Shortcodes' , 'majestic-support')); ?>">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Shortcodes' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/short-code.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Short Codes' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
        </a>
        <ul class="msadmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'majesticsupport' && $layout == 'shortcodes') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport&mjslay=shortcodes" title="<?php echo esc_attr(__('Short Codes', 'majestic-support'));; ?>">
                    <?php echo esc_html(__('Short Codes', 'majestic-support'));; ?>
                </a>
            </li>

        </ul>
    </li>
    <li class="treeview <?php if($c == 'themes') echo esc_attr('active'); ?>">
        <a class="" href="?page=majesticsupport_themes" title="<?php echo esc_attr(__('Colors' , 'majestic-support')); ?>">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Colors' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/theme.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Colors' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
        </a>
        <ul class="msadmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'themes' && ($layout == 'themes')) echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_themes&mjslay=themes" title="<?php echo esc_attr(__('Colors', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Colors', 'majestic-support')); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview <?php if($c == 'reports') echo esc_attr('active'); ?>">
        <a class="" href="?page=majesticsupport_reports&mjslay=overallreport" title="<?php echo esc_attr(__('Reports' , 'majestic-support')); ?>">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Reports' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/report.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Reports' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
        </a>
        <ul class="msadmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'reports' && ($layout == 'overallreport')) echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_reports&mjslay=overallreport" title="<?php echo esc_attr(__('Overall Statistics','majestic-support')); ?>">
                    <?php echo esc_html(__('Overall Statistics','majestic-support')); ?>
                </a>
            </li>
            <?php if ( in_array('agent',majesticsupport::$_active_addons)) { ?>
                <li class="<?php if($c == 'reports' && ($layout == 'staffreport') || ($layout == 'staffdetailreport')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_reports&mjslay=staffreport" title="<?php echo esc_attr(__('Agent Reports', 'majestic-support')); ?>">
                        <?php echo esc_html(__('Agent Reports', 'majestic-support')); ?>
                    </a>
                </li>
            <?php } ?>
            <li class="<?php if($c == 'reports' && ($layout == 'departmentreport') || ($layout == 'departmentdetailreport')) echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_reports&mjslay=departmentreport" title="<?php echo esc_attr(__('Department Reports','majestic-support')); ?>">
                    <?php echo esc_html(__('Department Reports','majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'reports' && ($layout == 'userreport') || ($layout == 'userdetailreport')) echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_reports&mjslay=userreport" title="<?php echo esc_attr(__('User Reports', 'majestic-support')); ?>">
                    <?php echo esc_html(__('User Reports', 'majestic-support')); ?>
                </a>
            </li>
            <?php if(in_array('feedback', majesticsupport::$_active_addons)){ ?>
                <li class="<?php if($c == 'reports' && ($layout == 'satisfactionreport')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_reports&mjslay=satisfactionreport" title="<?php echo esc_attr(__('Satisfaction Report', 'majestic-support')); ?>">
                        <?php echo esc_html(__('Satisfaction Report', 'majestic-support')); ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </li>
    <?php if(in_array('emailpiping', majesticsupport::$_active_addons)){ ?>
    <li class="treeview <?php if($c == 'emailpiping') echo esc_attr('active'); ?>">
        <a href="?page=majesticsupport_emailpiping" title="<?php echo esc_attr(__('Email Piping' , 'majestic-support')); ?>">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Email Piping' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/email-piping-2.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Email Piping' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
        </a>
        <ul class="msadmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'emailpiping') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailpiping" title="<?php echo esc_attr(__('Email Piping', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Email Piping', 'majestic-support')); ?>
                </a>
            </li>
        </ul>
    </li>
    <?php }else{ ?>
        <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-emailpiping/majestic-support-emailpiping.php');
            if($plugininfo['availability'] == "1"){
                $text = $plugininfo['text'];
                $url = "plugins.php?s=majestic-support-emailpiping&plugin_status=inactive";
            }elseif($plugininfo['availability'] == "0"){
                $text = $plugininfo['text'];
                $url = "https://majesticsupport.com/product/email-piping/";
            } ?>
        <li class="disabled-menu treeview">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Email Piping' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu-grey/email-piping-grey.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Email Piping' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
            <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'emailpiping') echo esc_attr('active'); ?>">
                    <span>
                        <?php echo esc_html(__('Email Piping', 'majestic-support')); ?>
                    </span>
                    <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <li class="treeview <?php if($c == 'priority') echo esc_attr('active'); ?>">
        <a class="" href="admin.php?page=majesticsupport_priority" title="<?php echo esc_attr(__('Priorities' , 'majestic-support')); ?>">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Priorities' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/priorities.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Priorities' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
        </a>
        <ul class="msadmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'priority' && ($layout == '')) echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_priority" title="<?php echo esc_attr(__('Priorities' , 'majestic-support')); ?>">
                    <?php echo esc_html(__('Priorities', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'priority' && ($layout == 'addpriority')) echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_priority&mjslay=addpriority" title="<?php echo esc_attr(__('Add Priority' , 'majestic-support')); ?>">
                    <?php echo esc_html(__('Add Priority', 'majestic-support')); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview <?php if($c == 'gdpr') echo esc_attr('active'); ?>">
        <a class="" href="admin.php?page=majesticsupport_gdpr&mjslay=gdprfields" title="<?php echo esc_attr(__('GDPR' , 'majestic-support')); ?>">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('GDPR' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/gdpr.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('GDPR' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
        </a>
        <ul class="msadmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'gdpr' && ($layout == 'erasedatarequests')) echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_gdpr&mjslay=erasedatarequests" title="<?php echo esc_attr(__('Erase Data Requests', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Erase Data Requests', 'majestic-support')); ?>
                </a>
            </li>
        </ul>
    </li>
    <?php if(in_array('feedback', majesticsupport::$_active_addons)){ ?>
        <li class="treeview <?php if($c == 'feedback'  || ($c == 'fieldordering' && $ff == 2) ) echo esc_attr('active'); ?>">
            <a class="" href="?page=majesticsupport_feedback&mjslay=feedbacks" title="<?php echo esc_attr(__('Feedback' , 'majestic-support')); ?>">
                <img class="ms_menu-icon" alt="<?php echo esc_html(__('Feedback' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/feedback.png'; ?>"/>
                <span class="ms_text"><?php echo esc_html(__('Feedback' , 'majestic-support')); ?></span>
                <span class="ms_active"></span>
            </a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'feedback' && ($layout == 'feedbacks')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_feedback&mjslay=feedbacks" title="<?php echo esc_attr(__('Feedback' , 'majestic-support')); ?>">
                        <?php echo esc_html(__('Feedback', 'majestic-support')); ?>
                    </a>
                </li>
                <li class="<?php if($c == 'fieldordering') echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_fieldordering&fieldfor=2" title="<?php echo esc_attr(__('Feedback Fields' , 'majestic-support')); ?>">
                        <?php echo esc_html(__('Feedback Fields', 'majestic-support')); ?>
                    </a>
                </li>
            </ul>
        </li>
    <?php } else { ?>
        <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-feedback/majestic-support-feedback.php');
            if($plugininfo['availability'] == "1"){
                $text = $plugininfo['text'];
                $url = "plugins.php?s=majestic-support-feedback&plugin_status=inactive";
            }elseif($plugininfo['availability'] == "0"){
                $text = $plugininfo['text'];
                $url = "https://majesticsupport.com/product/feedback/";
            } ?>
        <li class="disabled-menu treeview">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Feedback' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu-grey/feedback.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Feedback' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
            <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'feedback' && ($layout == 'feedbacks')) echo esc_attr('active'); ?>">
                    <span>
                        <?php echo esc_html(__('Feedback', 'majestic-support')); ?>
                    </span>
                    <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <li class="treeview <?php if($c == 'department') echo esc_attr('active'); ?>">
        <a class="" href="admin.php?page=majesticsupport_department" title="<?php echo esc_attr(__('Departments' , 'majestic-support')); ?>">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Departments' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/department.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Departments' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
        </a>
        <ul class="msadmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'department' && ($layout == '')) echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_department" title="<?php echo esc_attr(__('Departments' , 'majestic-support')); ?>">
                    <?php echo esc_html(__('Departments', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'department' && ($layout == 'adddepartment')) echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_department&mjslay=adddepartment" title="<?php echo esc_attr(__('Add Department' , 'majestic-support')); ?>">
                    <?php echo esc_html(__('Add Department', 'majestic-support')); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview <?php if($c == 'email') echo esc_attr('active'); ?>">
        <a class="" href="admin.php?page=majesticsupport_email" title="<?php echo esc_attr(__('System Emails' , 'majestic-support')); ?>">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('System Emails' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/system-email.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('System Emails' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
        </a>
        <ul class="msadmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'email' && ($layout == '')) echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_email" title="<?php echo esc_attr(__('System Emails' , 'majestic-support')); ?>">
                    <?php echo esc_html(__('System Emails', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'email' && ($layout == 'addemail')) echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_email&mjslay=addemail" title="<?php echo esc_attr(__('Add Email' , 'majestic-support')); ?>">
                    <?php echo esc_html(__('Add Email', 'majestic-support')); ?>
                </a>
            </li>
        </ul>
    </li>
    
    <?php if(in_array('knowledgebase', majesticsupport::$_active_addons)){ ?>
        <li class="treeview <?php if($c == 'knowledgebase' && ($layout == 'listcategories' || $layout == 'addcategory')) echo esc_attr('active'); ?>">
            <a class="" href="admin.php?page=majesticsupport_knowledgebase&mjslay=listcategories" title="<?php echo esc_attr(__('Categories','majestic-support')); ?>">
                <img class="ms_menu-icon" alt="<?php echo esc_html(__('Categories','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/category.png'; ?>"/>
                <span class="ms_text"><?php echo esc_html(__('Categories','majestic-support')); ?></span>
                <span class="ms_active"></span>
            </a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'knowledgebase' && ($layout == 'listcategories')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_knowledgebase&mjslay=listcategories" title="<?php echo esc_attr(__('Categories','majestic-support')); ?>">
                        <?php echo esc_html(__('Categories', 'majestic-support')); ?>
                    </a>
                </li>
                <li class="<?php if($c == 'knowledgebase' && ($layout == 'addcategory')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_knowledgebase&mjslay=addcategory" title="<?php echo esc_attr(__('Add Category','majestic-support')); ?>">
                        <?php echo esc_html(__('Add Category', 'majestic-support')); ?>
                    </a>
                </li>
            </ul>
        </li>
        <li class="treeview <?php if($c == 'knowledgebase' && ($layout == 'listarticles' || $layout == 'addarticle')) echo esc_attr('active'); ?>">
            <a class="" href="admin.php?page=majesticsupport_knowledgebase&mjslay=listarticles" title="<?php echo esc_attr(__('Knowledge Base' , 'majestic-support')); ?>">
                <img class="ms_menu-icon" alt="<?php echo esc_html(__('Knowledge Base' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/kb.png'; ?>"/>
                <span class="ms_text"><?php echo esc_html(__('Knowledge Base' , 'majestic-support')); ?></span>
                <span class="ms_active"></span>
            </a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'knowledgebase' && ($layout == 'listarticles')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_knowledgebase&mjslay=listarticles" title="<?php echo esc_attr(__('Knowledge Base' , 'majestic-support')); ?>">
                        <?php echo esc_html(__('Knowledge Base', 'majestic-support')); ?>
                    </a>
                </li>
                <li class="<?php if($c == 'knowledgebase' && ($layout == 'addarticle')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_knowledgebase&mjslay=addarticle" title="<?php echo esc_attr(__('Add Knowledge Base' , 'majestic-support')); ?>">
                        <?php echo esc_html(__('Add Knowledge Base', 'majestic-support')); ?>
                    </a>
                </li>
            </ul>
        </li>
    <?php } else { ?>
        <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-knowledgebase/majestic-support-knowledgebase.php');
            if($plugininfo['availability'] == "1"){
                $text = $plugininfo['text'];
                $url = "plugins.php?s=majestic-support-knowledgebase&plugin_status=inactive";
            }elseif($plugininfo['availability'] == "0"){
                $text = $plugininfo['text'];
                $url = "https://majesticsupport.com/product/knowledge-base/";
            } ?>
        <li class="disabled-menu treeview">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Categories' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu-grey/category.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Categories' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
            <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'knowledgebase' && ($layout == 'listcategories')) echo esc_attr('active'); ?>">
                    <span>
                        <?php echo esc_html(__('Categories', 'majestic-support')); ?>
                    </span>
                    <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                </li>
            </ul>
        </li>
        <li class="disabled-menu treeview">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Knowledge Base' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu-grey/kb.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Knowledge Base' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
            <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'knowledgebase' && ($layout == 'listarticles')) echo esc_attr('active'); ?>">
                    <span href="?page=majesticsupport_knowledgebase&mjslay=listarticles" title="<?php echo esc_attr(__('Knowledge Base' , 'majestic-support')); ?>">
                        <?php echo esc_html(__('Knowledge Base', 'majestic-support')); ?>
                    </span>
                    <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <?php if(in_array('download', majesticsupport::$_active_addons)){ ?>
        <li class="treeview <?php if($c == 'download') echo esc_attr('active'); ?>">
            <a class="" href="admin.php?page=majesticsupport_download" title="<?php echo esc_attr(__('Downloads' , 'majestic-support')); ?>">
                <img class="ms_menu-icon" alt="<?php echo esc_html(__('Downloads' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/download.png'; ?>"/>
                <span class="ms_text"><?php echo esc_html(__('Downloads' , 'majestic-support')); ?></span>
                <span class="ms_active"></span>
            </a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'download' && ($layout == '')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_download" title="<?php echo esc_attr(__('Downloads' , 'majestic-support')); ?>">
                        <?php echo esc_html(__('Downloads', 'majestic-support')); ?>
                    </a>
                </li>
                <li class="<?php if($c == 'download' && ($layout == 'adddownload')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_download&mjslay=adddownload" title="<?php echo esc_attr(__('Add Download' , 'majestic-support')); ?>">
                        <?php echo esc_html(__('Add Download', 'majestic-support')); ?>
                    </a>
                </li>
            </ul>
        </li>
    <?php } else { ?>
        <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-download/majestic-support-download.php');
            if($plugininfo['availability'] == "1"){
                $text = $plugininfo['text'];
                $url = "plugins.php?s=majestic-support-download&plugin_status=inactive";
            }elseif($plugininfo['availability'] == "0"){
                $text = $plugininfo['text'];
                $url = "https://majesticsupport.com/product/download/";
            } ?>
        <li class="disabled-menu treeview">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Download' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu-grey/download.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Download' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
            <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'download' && ($layout == '')) echo esc_attr('active'); ?>">
                    <span>
                        <?php echo esc_html(__('Downloads', 'majestic-support')); ?>
                    </span>
                    <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <?php if(in_array('announcement', majesticsupport::$_active_addons)){ ?>
        <li class="treeview <?php if($c == 'announcement') echo esc_attr('active'); ?>">
            <a class="" href="admin.php?page=majesticsupport_announcement" title="<?php echo esc_attr(__('Announcements' , 'majestic-support')); ?>">
                <img class="ms_menu-icon" alt="<?php echo esc_html(__('Announcements' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/announcements.png'; ?>"/>
                <span class="ms_text"><?php echo esc_html(__('Announcements' , 'majestic-support')); ?></span>
                <span class="ms_active"></span>
            </a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'announcement' && ($layout == '')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_announcement" title="<?php echo esc_attr(__('Announcements' , 'majestic-support')); ?>">
                        <?php echo esc_html(__('Announcements', 'majestic-support')); ?>
                    </a>
                </li>
                <li class="<?php if($c == 'announcement' && ($layout == 'addannouncement')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_announcement&mjslay=addannouncement" title="<?php echo esc_attr(__('Add Announcement' , 'majestic-support')); ?>">
                        <?php echo esc_html(__('Add Announcement', 'majestic-support')); ?>
                    </a>
                </li>
            </ul>
        </li>
    <?php } else { ?>
        <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-announcement/majestic-support-announcement.php');
            if($plugininfo['availability'] == "1"){
                $text = $plugininfo['text'];
                $url = "plugins.php?s=majestic-support-announcement&plugin_status=inactive";
            }elseif($plugininfo['availability'] == "0"){
                $text = $plugininfo['text'];
                $url = "https://majesticsupport.com/product/announcements/";
            } ?>
        <li class="disabled-menu treeview">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Announcements' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu-grey/announcements.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Announcements' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
            <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'announcement' && ($layout == '')) echo esc_attr('active'); ?>">
                    <span>
                        <?php echo esc_html(__('Announcements', 'majestic-support')); ?>
                    </span>
                    <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <?php if(in_array('faq', majesticsupport::$_active_addons)){ ?>
        <li class="treeview <?php if($c == 'faq') echo esc_attr('active'); ?>">
            <a class="" href="admin.php?page=majesticsupport_faq" title="<?php echo esc_attr(__('FAQs' , 'majestic-support')); ?>">
                <img class="ms_menu-icon" alt="<?php echo esc_html(__('FAQs' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/faq.png'; ?>"/>
                <span class="ms_text"><?php echo esc_html(__('FAQs' , 'majestic-support')); ?></span>
                <span class="ms_active"></span>
            </a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'faq' && ($layout == '')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_faq" title="<?php echo esc_attr(__("FAQs" , 'majestic-support')); ?>">
                        <?php echo esc_html(__("FAQs", 'majestic-support')); ?>
                    </a>
                </li>
                <li class="<?php if($c == 'faq' && ($layout == 'addfaq')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_faq&mjslay=addfaq" <?php echo esc_html(__('Add FAQ' , 'majestic-support')); ?>>
                        <?php echo esc_html(__( 'Add FAQ', 'majestic-support')); ?>
                    </a>
                </li>
            </ul>
        </li>
    <?php } else { ?>
        <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-faq/majestic-support-faq.php');
            if($plugininfo['availability'] == "1"){
                $text = $plugininfo['text'];
                $url = "plugins.php?s=majestic-support-faq&plugin_status=inactive";
            }elseif($plugininfo['availability'] == "0"){
                $text = $plugininfo['text'];
                $url = "https://majesticsupport.com/product/faq/";
            } ?>
        <li class="disabled-menu treeview">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('FAQs' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu-grey/faq.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('FAQs' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
            <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'faq' && ($layout == '')) echo esc_attr('active'); ?>">
                    <span>
                        <?php echo esc_html(__("FAQs", 'majestic-support')); ?>
                    </span>
                    <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <?php if(in_array('helptopic', majesticsupport::$_active_addons)){ ?>
        <li class="treeview <?php if($c == 'helptopic') echo esc_attr('active'); ?>">
            <a class="" href="admin.php?page=majesticsupport_helptopic" title="<?php echo esc_attr(__('Help Topics' , 'majestic-support')); ?>">
                <img class="ms_menu-icon" alt="<?php echo esc_html(__('Help Topics' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/help-topic.png'; ?>"/>
                <span class="ms_text"><?php echo esc_html(__('Help Topics' , 'majestic-support')); ?></span>
                <span class="ms_active"></span>
            </a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'helptopic' && ($layout == '')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_helptopic" title="<?php echo esc_attr(__('Help Topics' , 'majestic-support')); ?>">
                        <?php echo esc_html(__('Help Topics', 'majestic-support')); ?>
                    </a>
                </li>
                <li class="<?php if($c == 'helptopic' && ($layout == 'addhelptopic')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_helptopic&mjslay=addhelptopic" tite="<?php echo esc_html(__('Add Help Topic' , 'majestic-support')); ?>">
                        <?php echo esc_html(__('Add Help Topic', 'majestic-support')); ?>
                    </a>
                </li>
            </ul>
        </li>
    <?php } else { ?>
        <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-helptopic/majestic-support-helptopic.php');
            if($plugininfo['availability'] == "1"){
                $text = $plugininfo['text'];
                $url = "plugins.php?s=majestic-support-helptopic&plugin_status=inactive";
            }elseif($plugininfo['availability'] == "0"){
                $text = $plugininfo['text'];
                $url = "https://majesticsupport.com/product/helptopic/";
            } ?>
        <li class="disabled-menu treeview">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Help Topics' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu-grey/help-topic.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Help Topics' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
            <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'helptopic' && ($layout == '')) echo esc_attr('active'); ?>">
                    <span>
                        <?php echo esc_html(__('Help Topics', 'majestic-support')); ?>
                    </span>
                    <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <?php if(in_array('cannedresponses', majesticsupport::$_active_addons)){ ?>
        <li class="treeview <?php if($c == 'cannedresponses') echo esc_attr('active'); ?>">
            <a class="" href="admin.php?page=majesticsupport_cannedresponses" title="<?php echo esc_attr(__('Premade Responses' , 'majestic-support')); ?>">
                <img class="ms_menu-icon" alt="<?php echo esc_html(__('Premade Responses' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/canned-response.png'; ?>"/>
                <span class="ms_text"><?php echo esc_html(__('Premade Responses' , 'majestic-support')); ?></span>
                <span class="ms_active"></span>
            </a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'cannedresponses' && ($layout == '')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_cannedresponses" title="<?php echo esc_attr(__('Premade Responses' , 'majestic-support')); ?>">
                        <?php echo esc_html(__('Premade Responses', 'majestic-support')); ?>
                    </a>
                </li>
                <li class="<?php if($c == 'cannedresponses' && ($layout == 'addpremademessage')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_cannedresponses&mjslay=addpremademessage" title="<?php echo esc_attr(__('Add Premade Response' , 'majestic-support')); ?>">
                        <?php echo esc_html(__('Add Premade Response', 'majestic-support')); ?>
                    </a>
                </li>
            </ul>
        </li>
    <?php } else { ?>
        <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-cannedresponses/majestic-support-cannedresponses.php');
            if($plugininfo['availability'] == "1"){
                $text = $plugininfo['text'];
                $url = "plugins.php?s=majestic-support-cannedresponses&plugin_status=inactive";
            }elseif($plugininfo['availability'] == "0"){
                $text = $plugininfo['text'];
                $url = "https://majesticsupport.com/product/canned-responses/";
            } ?>
        <li class="disabled-menu treeview">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Premade Responses' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu-grey/canned-response.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Premade Responses' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
            <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'cannedresponses' && ($layout == '')) echo esc_attr('active'); ?>">
                    <span>
                        <?php echo esc_html(__('Premade Responses', 'majestic-support')); ?>
                    </span>
                    <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <?php if ( in_array('agent',majesticsupport::$_active_addons)) { ?>
        <li class="treeview <?php if($c == 'role') echo esc_attr('active'); ?>">
            <a class="" href="admin.php?page=majesticsupport_role" title="<?php echo esc_attr(__('Roles' , 'majestic-support')); ?>">
                <img class="ms_menu-icon" alt="<?php echo esc_html(__('Roles' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/role.png'; ?>"/>
                <span class="ms_text"><?php echo esc_html(__('Roles' , 'majestic-support')); ?></span>
                <span class="ms_active"></span>
            </a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'role' && ($layout == '')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_role" title="<?php echo esc_attr(__('Roles' , 'majestic-support')); ?>">
                        <?php echo esc_html(__('Roles', 'majestic-support')); ?>
                    </a>
                </li>
                <li class="<?php if($c == 'role' && ($layout == 'addrole')) echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_role&mjslay=addrole" title="<?php echo esc_attr(__('Add Role' , 'majestic-support')); ?>">
                        <?php echo esc_html(__('Add Role', 'majestic-support')); ?>
                    </a>
                </li>
            </ul>
        </li>
    <?php } else { ?>
        <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-agent/majestic-support-agent.php');
            if($plugininfo['availability'] == "1"){
                $text = $plugininfo['text'];
                $url = "plugins.php?s=majestic-support-agent&plugin_status=inactive";
            }elseif($plugininfo['availability'] == "0"){
                $text = $plugininfo['text'];
                $url = "https://majesticsupport.com/product/agents/";
            } ?>
        <li class="disabled-menu treeview">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Roles' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu-grey/role.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Roles' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
            <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'role' && ($layout == '')) echo esc_attr('active'); ?>">
                    <span>
                        <?php echo esc_html(__('Roles', 'majestic-support')); ?>
                    </span>
                    <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <?php if(in_array('mail', majesticsupport::$_active_addons)){ ?>
        <li class="treeview <?php if($c == 'mail') echo esc_attr('active'); ?>">
            <a class="" href="admin.php?page=majesticsupport_mail" title="<?php echo esc_attr(__('Mail' , 'majestic-support')); ?>">
                <img class="ms_menu-icon" alt="<?php echo esc_html(__('Mail' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/mails.png'; ?>"/>
                <span class="ms_text"><?php echo esc_html(__('Mail' , 'majestic-support')); ?></span>
                <span class="ms_active"></span>
            </a>
           <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'mail') echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_mail" title="<?php echo esc_attr(__('Mail' , 'majestic-support')); ?>">
                        <?php echo esc_html(__('Mail', 'majestic-support')); ?>
                    </a>
                </li>
            </ul>
        </li>
    <?php } else { ?>
        <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-mail/majestic-support-mail.php');
            if($plugininfo['availability'] == "1"){
                $text = $plugininfo['text'];
                $url = "plugins.php?s=majestic-support-mail&plugin_status=inactive";
            }elseif($plugininfo['availability'] == "0"){
                $text = $plugininfo['text'];
                $url = "https://majesticsupport.com/product/internal-mail/";
            } ?>
        <li class="disabled-menu treeview">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Mail' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu-grey/mails.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Mail' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
            <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'mail') echo esc_attr('active'); ?>">
                    <span>
                        <?php echo esc_html(__('Mail', 'majestic-support')); ?>
                    </span>
                    <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <?php if(in_array('banemail', majesticsupport::$_active_addons)){ ?>
        <li class="treeview <?php if($c == 'banemail' || $c == 'banemaillog') echo esc_attr('active'); ?>">
            <a class="" href="admin.php?page=majesticsupport_banemail" title="<?php echo esc_attr(__('Banned Emails' , 'majestic-support')); ?>">
                <img class="ms_menu-icon" alt="<?php echo esc_html(__('Banned Emails' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/ban.png'; ?>"/>
                <span class="ms_text"><?php echo esc_html(__('Banned Emails' , 'majestic-support')); ?></span>
                <span class="ms_active"></span>
            </a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'banemail') echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_banemail" title="<?php echo esc_attr(__('Banned Emails' , 'majestic-support')); ?>">
                        <?php echo esc_html(__('Banned Emails', 'majestic-support')); ?>
                    </a>
                </li>
                <li class="<?php if($c == 'banemaillog') echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_banemaillog" title="<?php echo esc_attr(__('Banned Email Log List', 'majestic-support')); ?>">
                        <?php echo esc_html(__('Banned Email Log List', 'majestic-support')); ?>
                    </a>
                </li>
            </ul>
        </li>
    <?php } else { ?>
        <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-banemail/majestic-support-banemail.php');
            if($plugininfo['availability'] == "1"){
                $text = $plugininfo['text'];
                $url = "plugins.php?s=majestic-support-banemail&plugin_status=inactive";
            }elseif($plugininfo['availability'] == "0"){
                $text = $plugininfo['text'];
                $url = "https://majesticsupport.com/product/ban-email/";
            } ?>
        <li class="disabled-menu treeview">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Ban Emails', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu-grey/ban.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Ban Emails' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
            <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'banemail') echo esc_attr('active'); ?>">
                    <span>
                        <?php echo esc_html(__('Ban Emails', 'majestic-support')); ?>
                    </span>
                    <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <?php if(in_array('emailcc', majesticsupport::$_active_addons)){ ?>
        <li class="treeview <?php if($c == 'emailcc') echo esc_attr('active'); ?>">
            <a class="" href="admin.php?page=majesticsupport_emailcc" title="<?php echo esc_attr(__('Emial CC' , 'majestic-support')); ?>">
                <img class="ms_menu-icon" alt="<?php echo esc_html(__('Emial CC' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/emailcc.png'; ?>"/>
                <span class="ms_text"><?php echo esc_html(__('Email CC' , 'majestic-support')); ?></span>
                <span class="ms_active"></span>
            </a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'emailcc' && $layout != 'addemailcc') echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_emailcc" title="<?php echo esc_attr(__('Emial CC' , 'majestic-support')); ?>">
                        <?php echo esc_html(__('Email CC', 'majestic-support')); ?>
                    </a>
                </li>
                <li class="<?php if($c == 'emailcc' && $layout == 'addemailcc') echo esc_attr('active'); ?>">
                    <a href="?page=majesticsupport_emailcc&mjslay=addemailcc" title="<?php echo esc_attr(__('Add Emial CC', 'majestic-support')); ?>">
                        <?php echo esc_html(__('Add Email CC', 'majestic-support')); ?>
                    </a>
                </li>
            </ul>
        </li>
    <?php } else { ?>
        <?php $plugininfo = mjtc_checkPluginInfo('majestic-support-emailcc/majestic-support-emailcc.php');
            if($plugininfo['availability'] == "1"){
                $text = $plugininfo['text'];
                $url = "plugins.php?s=majestic-support-emailcc&plugin_status=inactive";
            }elseif($plugininfo['availability'] == "0"){
                $text = $plugininfo['text'];
                $url = "https://majesticsupport.com/product/email-cc/";
            } ?>
        <li class="disabled-menu treeview">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Email CC', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu-grey/emailcc.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Email CC' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
            <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
            <ul class="msadmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'emailcc') echo esc_attr('active'); ?>">
                    <span>
                        <?php echo esc_html(__('Email CC', 'majestic-support')); ?>
                    </span>
                    <a href="<?php echo esc_url($url); ?>" class="ms_mjtc-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <li class="treeview <?php if($c == 'emailtemplate') echo esc_attr('active'); ?>">
        <a class="" href="admin.php?page=majesticsupport_emailtemplate" title="<?php echo esc_attr(__('Email Templates' , 'majestic-support')); ?>">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('Email Templates' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/email-template.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('Email Templates' , 'majestic-support')); ?></span>
            <span class="ms_active"></span>
        </a>
        <ul class="msadmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'emailtemplate' && $for == 'tk-nw') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=tk-nw" title="<?php echo esc_attr(__('New Ticket', 'majestic-support')); ?>">
                    <?php echo esc_html(__('New Ticket', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'sntk-tk') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=sntk-tk" title="<?php echo esc_attr(__('Agent Ticket', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Agent Ticket', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'ew-sm') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=ew-sm" title="<?php echo esc_attr(__('New Agent', 'majestic-support')); ?>">
                    <?php echo esc_html(__('New Agent', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'rs-tk') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=rs-tk" title="<?php echo esc_attr(__('Reassign Ticket', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Reassign Ticket', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'cl-tk') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=cl-tk" title="<?php echo esc_attr(__('Close Ticket', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Close Ticket', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'dl-tk') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=dl-tk" title="<?php echo esc_attr(__('Delete Ticket', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Delete Ticket', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'mo-tk') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=mo-tk" title="<?php echo esc_attr(__('Mark Overdue', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Mark Overdue', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'be-tk') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=be-tk" title="<?php echo esc_attr(__('Ban Email', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Ban Email', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'be-trtk') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=be-trtk" title="<?php echo esc_attr(__('Ban email try to create ticket', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Ban email try to create ticket', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'dt-tk') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=dt-tk" title="<?php echo esc_attr(__('Department Transfer', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Department Transfer', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'ebct-tk') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=ebct-tk" title="<?php echo esc_attr(__('Ban Email and Close Ticket', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Ban Email and Close Ticket', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'ube-tk') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=ube-tk" title="<?php echo esc_attr(__('Unban Email', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Unban Email', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'rsp-tk') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=rsp-tk" title="<?php echo esc_attr(__('Response Ticket', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Response Ticket', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'rpy-tk') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=rpy-tk" title="<?php echo esc_attr(__('Reply Ticket', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Reply Ticket', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'tk-ew-ad') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=tk-ew-ad" title="<?php echo esc_attr(__('New Ticket Admin Alert', 'majestic-support')); ?>">
                    <?php echo esc_html(__('New Ticket Admin Alert', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'lk-tk') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=lk-tk" title="<?php echo esc_attr(__('Lock Ticket', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Lock Ticket', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'ulk-tk') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=ulk-tk" title="<?php echo esc_attr(__('Unlock Ticket', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Unlock Ticket', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'minp-tk') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=minp-tk" title="<?php echo esc_attr(__('In Progress Ticket', 'majestic-support')); ?>">
                    <?php echo esc_html(__('In Progress Ticket', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'pc-tk') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=pc-tk" title="<?php echo esc_attr(__('Ticket priority is changed by', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Ticket priority is changed by', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'ml-ew') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=ml-ew" title="<?php echo esc_attr(__('New Mail Received', 'majestic-support')); ?>">
                    <?php echo esc_html(__('New Mail Received', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'ml-rp') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=ml-rp" title="<?php echo esc_attr(__('New Mail Message Received', 'majestic-support')); ?>">
                    <?php echo esc_html(__('New Mail Message Received', 'majestic-support')); ?>
                </a>
            <li class="<?php if($c == 'emailtemplate' && $for == 'fd-bk') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=fd-bk" title="<?php echo esc_attr(__('Feedback Email To User', 'majestic-support')); ?>">
                    <?php echo esc_html(__('Feedback Email To User', 'majestic-support')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'emailtemplate' && $for == 'no-rp') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport_emailtemplate&for=no-rp" title="<?php echo esc_attr(__('User Reply On Closed Ticket', 'majestic-support')); ?>">
                    <?php echo esc_html(__('User Reply On Closed Ticket', 'majestic-support')); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview <?php if($c == 'majesticsupport' && $layout == 'help') echo esc_attr('active'); ?>">
        <a href="?page=majesticsupport&mjslay=help" title="<?php echo esc_attr(__('help','majestic-support')); ?>">
            <img class="ms_menu-icon" alt="<?php echo esc_html(__('help' , 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/left-icons/menu/help.png'; ?>"/>
            <span class="ms_text"><?php echo esc_html(__('help','majestic-support')); ?></span>
            <span class="ms_active"></span>
        </a>
        <ul class="msadmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'majesticsupport' && $layout == 'help') echo esc_attr('active'); ?>">
                <a href="?page=majesticsupport&mjslay=help" title="<?php echo esc_attr(__('help','majestic-support')); ?>">
                    <?php echo esc_html(__('help','majestic-support')); ?>
                </a>
            </li>
        </ul>
    </li>
</ul>
<?php if(in_array('multiform', majesticsupport::$_active_addons)){ ?>
    <div id="multiformpopupblack" style="display:none;"></div>
    <div id="multiformpopup" class="" style="display:none;"><!-- Select User Popup -->
        <div class="ms-multiformpopup-header">
            <div class="multiformpopup-header-text">
                <?php echo esc_html(__('Select Form','majestic-support')); ?>
            </div>
            <div class="multiformpopup-header-close-img">
                <img src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/close-icon-white.png">
            </div>
        </div>
        <div id="records">
            <div id="records-inner">
                <div class="mjtc-staff-searc-desc">
                    <?php echo esc_html(__('No Record Found','majestic-support')); ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php
$majesticsupport_js ="
    var cookielist = document.cookie.split(';');
    for (var i=0; i<cookielist.length; i++) {
        if (cookielist[i].trim() == 'ms_collapse_admin_menu=1') {
            jQuery('#msadmin-wrapper').addClass('menu-collasped-active');
            break;
        }
    }

    jQuery(document).ready(function(){

        var pageWrapper = jQuery('#msadmin-wrapper');
        var sideMenuArea = jQuery('#msadmin-leftmenu');

        jQuery('#msadmin-menu-toggle').on('click', function () {

            if (pageWrapper.hasClass('menu-collasped-active')) {
                pageWrapper.removeClass('menu-collasped-active');
                document.cookie = 'ms_collapse_admin_menu=0; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
            }else{
                pageWrapper.addClass('menu-collasped-active');
                document.cookie = 'ms_collapse_admin_menu=1; expires=Sat, 01 Jan 2050 00:00:00 UTC; path=/';
            }

        });

        // to set anchor link active on menu collpapsed
        jQuery('.msadmin-leftmenu .msadmin-sidebar-menu li.treeview a').on('click', function() {
            if (!(pageWrapper.hasClass('menu-collasped-active'))) {
                window.location.href = jQuery(this).attr('href');
            }
        })
    });
";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>
<?php
$majesticsupport_js ="
    jQuery(document).ready(function ($) {

        jQuery('a#multiformpopup').click(function (e) {
            e.preventDefault();
            var url = jQuery('a#multiformpopup').prop('class');
            jQuery('div#multiformpopupblack').show();
            var ajaxurl ='".esc_url(admin_url('admin-ajax.php'))."';
            jQuery.post(ajaxurl, {action: 'mjsupport_ajax', mjsmod: 'multiform', task: 'getmultiformlistajax', url:url, '_wpnonce':'". esc_attr(wp_create_nonce("get-multi-form-list-ajax"))."'}, function (data) {
                if(data){
                    jQuery('div#records').html('');
                    jQuery('div#records').html(data);
                    setUserLink();
                }
            });
            jQuery('div#multiformpopup').slideDown('slow');
        });

        jQuery('div#multiformpopupblack , div.multiformpopup-header-close-img').click(function (e) {
            jQuery('div#multiformpopup').slideUp('slow', function () {
                jQuery('div#multiformpopupblack').hide();
            });
        });
    });

    function MJTC_makeFormSelected(divelement){
        jQuery('div.mjtc-support-multiform-row').removeClass('selected');
        jQuery(divelement).addClass('selected');  
    }
    function MJTC_makeMultiFormUrl(id){
        var oldUrl = jQuery('a.mjtc-multiformpopup-link').attr('id'); // Get current url
        var newUrl = oldUrl+'&formid='+id; // Create new url
        window.location.href = newUrl;
    }
";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>
