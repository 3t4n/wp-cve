<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
class MJTC_breadcrumbs {

    static function MJTC_getBreadcrumbs() {
        if (majesticsupport::$_config['show_breadcrumbs'] != 1)
            return false;
        if (!is_admin()) {
            $editid = MJTC_request::MJTC_getVar('majesticsupportid');
            $isnew = ($editid == null) ? true : false;
            $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>'majesticsupport', 'mjslay'=>'controlpanel')), 'text' => esc_html(__('Control Panel', 'majestic-support')));
            $module = MJTC_request::MJTC_getVar('mjsmod');
            $layout = MJTC_request::MJTC_getVar('mjslay');
            if (isset(majesticsupport::$_data['short_code_header'])) {
                switch (majesticsupport::$_data['short_code_header']){
                    case 'myticket':

                        $module = ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) ? 'agent' : 'ticket';
                        $layout = ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) ? 'staffmyticket' : 'myticket';
                        break;
                    case 'addticket':
                        $module = ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) ? 'agent' : 'ticket';
                        $layout = ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) ? 'staffaddticket' : 'addticket';
                        break;
                    case 'downloads':
                        $module = 'download';
                        $layout = 'downloads';
                        break;
                    case 'faqs':
                        $module = 'faq';
                        $layout = 'faqs';
                        break;
                    case 'announcements':
                        $module = 'announcement';
                        $layout = 'announcements';
                        break;
                    case 'userknowledgebase':
                        $module = 'knowledgebase';
                        $layout = 'userknowledgebase';
                        break;
                }
            }

            if ($module != null) {
                switch ($module) {
                    case 'announcement':
                        switch ($layout) {
                            case 'announcements':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Announcements', 'majestic-support')));
                                break;
                            case 'announcementdetails':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Announcement Detail', 'majestic-support')));
                                break;
                            case 'addannouncement':
                                $layout1 = ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) ? 'staffannouncements' : 'announcements';
                                $text = ($isnew) ? esc_html(__('Add Announcement', 'majestic-support')) : esc_html(__('Edit Announcement', 'majestic-support'));
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => $text);
                                break;
                            case 'staffannouncements':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Announcements', 'majestic-support')));
                                break;
                        }
                        break;
                    case 'department':
                        switch ($layout) {
                            case 'adddepartment':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>'departments')), 'text' => esc_html(__('Departments', 'majestic-support')));
                                $text = ($isnew) ? esc_html(__('Add Department', 'majestic-support')) : esc_html(__('Edit Department', 'majestic-support'));
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => $text);
                                break;
                            case 'departments':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Departments', 'majestic-support')));
                                break;
                        }
                        break;
                    case 'reports':
                        switch ($layout) {
                            case 'staffdetailreport':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Staff report', 'majestic-support')));
                                break;
                            case 'staffreports':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Staff reports', 'majestic-support')));
                                break;
                            case 'departmentreports':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Departments report', 'majestic-support')));
                                break;
                        }
                        break;
                    case 'download':
                        switch ($layout) {
                            case 'adddownload':
                                $layout1 = ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) ? 'staffdownloads' : 'downloads';
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout1)), 'text' => esc_html(__('Downloads', 'majestic-support')));
                                $text = ($isnew) ? esc_html(__('Add Download', 'majestic-support')) : esc_html(__('Edit Download', 'majestic-support'));
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => $text);
                                break;
                            case 'downloads':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Downloads', 'majestic-support')));
                                break;
                            case 'staffdownloads':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Downloads', 'majestic-support')));
                                break;
                        }
                        break;
                    case 'faq':
                        switch ($layout) {
                            case 'addfaq':
                                $layout1 = ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) ? 'stafffaqs' : 'faqs';
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout1)), 'text' => esc_html(__("FAQs", 'majestic-support')));
                                $text = ($isnew) ? esc_html(__('Add FAQ', 'majestic-support')) : esc_html(__('Edit FAQ', 'majestic-support'));
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => $text);
                                break;
                            case 'faqdetails':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('FAQ Detail', 'majestic-support')));
                                break;
                            case 'faqs':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__("FAQs", 'majestic-support')));
                                break;
                            case 'stafffaqs':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__("FAQs", 'majestic-support')));
                                break;
                        }
                        break;
                    case 'feedback':
                        switch ($layout) {
                            case 'feedbacks':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>'feedback', 'mjslay'=>'feedbacks')), 'text' => esc_html(__("Feedbacks", 'majestic-support')));
                                break;
                        }
                        break;
                    case 'majesticsupport':
                        break;
                    case 'knowledgebase':
                        switch ($layout) {
                            case 'addarticle':
                                $text = ($isnew) ? esc_html(__('Add Knowledge Base', 'majestic-support')) : esc_html(__('Edit Knowledge Base', 'majestic-support'));
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => $text);
                                break;
                            case 'addcategory':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>'stafflistcategories')), 'text' => esc_html(__('Categories', 'majestic-support')));
                                $text = ($isnew) ? esc_html(__('Add Category', 'majestic-support')) : esc_html(__('Edit Category', 'majestic-support'));
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => $text);
                                break;
                            case 'articledetails':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Knowledge Base Detail', 'majestic-support')));
                                break;
                            case 'listarticles':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Knowledge Base', 'majestic-support')));
                                break;
                            case 'listcategories':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Categories', 'majestic-support')));
                                break;
                            case 'stafflistarticles':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Knowledge Base', 'majestic-support')));
                                break;
                            case 'stafflistcategories':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Categories', 'majestic-support')));
                                break;
                            case 'userknowledgebase':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Knowledge Base', 'majestic-support')));
                                break;
                            case 'userknowledgebasearticles':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Knowledge Base', 'majestic-support')));
                                break;
                        }
                        break;
                    case 'mail':
                        switch ($layout) {
                            case 'formmessage':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Message', 'majestic-support')));
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Send Message', 'majestic-support')));
                                break;
                            case 'inbox':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Message', 'majestic-support')));
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Inbox', 'majestic-support')));
                                break;
                            case 'outbox':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Message', 'majestic-support')));
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Outbox', 'majestic-support')));
                                break;
                            case 'message':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>'inbox')), 'text' => esc_html(__('Message', 'majestic-support')));
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Message', 'majestic-support')));
                                break;
                        }
                        break;
                    case 'role':
                        switch ($layout) {
                            case 'addrole':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>'roles')), 'text' => esc_html(__('Roles', 'majestic-support')));
                                $text = ($isnew) ? esc_html(__('Add Role', 'majestic-support')) : esc_html(__('Edit Role', 'majestic-support'));
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => $text);
                                break;
                            case 'rolepermission':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>'roles')), 'text' => esc_html(__('Roles', 'majestic-support')));
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Role permissions', 'majestic-support')));
                                break;
                            case 'roles':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Roles', 'majestic-support')));
                                break;
                        }
                        break;
                    case 'agent':
                        switch ($layout) {
                            case 'addstaff':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>'staffs')), 'text' => esc_html(__('Staffs', 'majestic-support')));
                                $text = ($isnew) ? esc_html(__('Add Staff', 'majestic-support')) : esc_html(__('Edit Staff', 'majestic-support'));
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => $text);
                                break;
                            case 'staffpermissions':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Staff Permissions', 'majestic-support')));
                                break;
                            case 'staffs':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module, 'mjslay'=>$layout)), 'text' => esc_html(__('Staffs', 'majestic-support')));
                                break;
                        }
                        break;
                    case 'ticket':
                        // Add default module link
                        switch ($layout) {
                            case 'addticket':
                                $layout1 = ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) ? 'staffmyticket':'myticket';
                                $module1 = ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) ? 'agent':'ticket';
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module1, 'mjslay'=>$layout1)), 'text'=>esc_html(__('My Tickets','majestic-support')));
                                $text = ($isnew) ? esc_html(__('Add Ticket', 'majestic-support')) : esc_html(__('Edit Ticket', 'majestic-support'));
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'addticket')), 'text' => $text);
                                break;
                            case 'myticket':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'myticket')), 'text' => esc_html(__('My Tickets', 'majestic-support')));
                                break;
                            case 'staffaddticket':
                                $layout1 = ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) ? 'staffmyticket':'myticket';
                                $module1 = ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) ? 'agent':'ticket';
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module1, 'mjslay'=>$layout1)), 'text'=>esc_html(__('My Tickets','majestic-support')));
                                $text = ($isnew) ? esc_html(__('Add Ticket', 'majestic-support')) : esc_html(__('Edit Ticket', 'majestic-support'));
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>'agent', 'mjslay'=>'staffaddticket')), 'text' => $text);
                                break;
                            case 'staffmyticket':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>'agent', 'mjslay'=>'staffmyticket')), 'text' => esc_html(__('My Tickets', 'majestic-support')));
                                break;
                            case 'ticketdetail':
                                $layout1 = ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) ? 'staffmyticket' : 'myticket';
                                $module1 = ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) ? 'agent' : 'ticket';
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>$module1, 'mjslay'=>$layout1)), 'text'=>esc_html(__('My Tickets','majestic-support')));
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketdetail')), 'text' => esc_html(__('Ticket Detail', 'majestic-support')));
                                break;
                            case 'ticketstatus':
                                $array[] = array('link' => majesticsupport::makeUrl(array('mjsmod'=>'ticket', 'mjslay'=>'ticketstatus')), 'text' => esc_html(__('Ticket Status', 'majestic-support')));
                                break;
                        }
                        break;
                }
            }
        }

        if (isset($array)) {
            $count = count($array);
            $i = 0;
            $html = '<div class="mjtc-support-breadcrumb-wrp">
                    <ul class="breadcrumb mjtc-support-breadcrumb">';
                        foreach ($array AS $obj) {
                            if ($i == 0) {
                                $html .= '
                                <li>
                                    <a href="' . esc_url($obj['link']) . '">
                                        <img class="homeicon" alt="home icon" src="' . esc_url(MJTC_PLUGIN_URL) . 'includes/images/homeicon-white.png"/>
                                    </a>
                                </li>';
                            } else {
                                if ($i == ($count - 1)) {
                                    $html .= '
                                    <li>
                                        <a href="">
                                            ' . esc_html($obj['text']) . '
                                        </a>
                                    </li>';
                                } else {
                                    $html .= '
                                    <li>
                                        <a href="' . esc_url($obj['link']) . '">
                                            ' . esc_html($obj['text']) . '
                                        </a>
                                    </li>';
                                }
                            }
                        $i++;
                        }
            $html .= ' </ul>
                </div>';
            echo wp_kses($html, MJTC_ALLOWED_TAGS);
        }
    }

}

$msbreadcrumbs = new MJTC_breadcrumbs;
?>
