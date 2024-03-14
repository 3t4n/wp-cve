<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_form_controller
 *
 * @author CMSHelplive
 */
class RM_Form_Controller {

    public $mv_handler;

    function __construct() {
        $this->mv_handler = new RM_Model_View_Handler();
    }

    public function manage($model, $service, $request, $params) {
        if (!isset($request->req['form_name'])) {
            RM_PFBC_Form::clearErrors('rm_form_quick_add');
        }
        
        $new_added_form = 0;
        $sort_by = null;
        //Unset sorting if a form was newly added
        if(!isset($request->req['rm_new_added_form']) || !$request->req['rm_new_added_form']) 
            $sort_by = (isset($request->req['rm_sortby'])) ? $request->req['rm_sortby'] : null;
        else {
            $new_added_form = intval($request->req['rm_new_added_form']);
        }

        $attach_service = new RM_Attachment_Service();
        $search_term = isset($request->req['rm_form_search']) ? $request->req['rm_form_search'] : null;
        $form_filter = isset($request->req['rm_form_filter']) ? $request->req['rm_form_filter'] : null;
        $sort_by = (isset($request->req['rm_sortby'])) ? $request->req['rm_sortby'] : null;
        $descending = (isset($request->req['rm_descending']) && absint($request->req['rm_descending']) == 0) ? false : true;
        $req_page = (isset($request->req['rm_reqpage']) && $request->req['rm_reqpage'] > 0) ? $request->req['rm_reqpage'] : 1;
        $url_params = array(
            'page' => 'rm_form_manage',
            'rm_form_search' => $search_term,
            'rm_form_filter' => $form_filter,
            'rm_sortby' => $sort_by,
            'rm_descending' => $descending,
            'rm_reqpage' => $req_page
        );
        $options=new RM_Options;
        $submission_type=$options->get_value_of('submission_on_card');
        //$items_per_page = 10;
        $items_per_page = get_site_option('rm_forms_entry_depth');
        $items_per_page = empty($items_per_page) ? 10 : absint($items_per_page);
        if($sort_by=="form_submissions"){
            // $forms = $service->get_all(null, ($req_page - 1) * $items_per_page, $items_per_page, '*', null, $descending);
            if(empty($search_term)) {
                $forms = $service->get_all(null, 0, 999999, '*', null, $descending);
            } else {
                $forms = RM_DBManager::search_forms_by_name($search_term, null, $descending);
            }
            
            if($descending) {
                usort($forms, function(stdClass $a, stdClass $b) {
                    $options=new RM_Options;
                    $submission_type=$options->get_value_of('submission_on_card');
                    $form_id='';
                    $afid=(int)$a->form_id;
                    $bfid=(int)$b->form_id;
                    $result1= RM_DBManager::get_results_for_last($submission_type,$afid,null,null ,0,999999,'submission_id', false);
                    $asub= is_array($result1) ? count($result1) : 0;
                    $result2= RM_DBManager::get_results_for_last($submission_type,$bfid,null,null ,0,999999,'submission_id', false);
                    $bsub= is_array($result2) ? count($result2) : 0;
                    
                    if ($asub == $bsub)
                        return 0;
                    else
                        return $asub > $bsub ? -1 : 1;
                });
            } else {
                usort($forms, function(stdClass $a, stdClass $b) {
                    $options=new RM_Options;
                    $submission_type=$options->get_value_of('submission_on_card');
                    $form_id='';
                    $afid=(int)$a->form_id;
                    $bfid=(int)$b->form_id;
                    $result1= RM_DBManager::get_results_for_last($submission_type,$afid,null,null ,0,999999,'submission_id', false);
                    $asub= is_array($result1) ? count($result1) : 0;
                    $result2= RM_DBManager::get_results_for_last($submission_type,$bfid,null,null ,0,999999,'submission_id', false);
                    $bsub= is_array($result2) ? count($result2) : 0;
                    
                    if ($asub == $bsub)
                        return 0;
                    else
                        return $asub < $bsub ? -1 : 1;
                });
            }

           //$forms = array_slice($forms,($req_page - 1) * $items_per_page, $items_per_page);
        } else {
            if(empty($search_term)) {
                $forms = $service->get_all(null, 0, 999999, '*', $sort_by, $descending);
            } else {
                $forms = RM_DBManager::search_forms_by_name($search_term, $sort_by, $descending);
            }
        }
        $i = 0;
        $data = array();
        $reg_forms = 0;
        $multi_page_forms = 0;
        if (is_array($forms) || is_object($forms))
            foreach ($forms as $form) {
                if($form->form_type == 1) {
                    $reg_forms++;
                } elseif($form_filter == 'registration') {
                    continue;
                }
                $form_options = maybe_unserialize($form->form_options);
                if(isset($form_options->form_pages) && is_array($form_options->form_pages) && count($form_options->form_pages) > 1) {
                    $multi_page_forms++;
                } elseif($form_filter == 'multi_page') {
                    continue;
                }

                $data[$i] = new stdClass;
                $data[$i]->form_id = $form->form_id;
                $data[$i]->form_name = $form->form_name;
                $data[$i]->form_type = $form->form_type;
                $data[$i]->form_options = $form_options;
                $data[$i]->created_on = $form->created_on;
                $data[$i]->form_attachments = $attach_service->get_all_form_attachments($form->form_id);
                $filter_submissions = RM_DBManager::get_results_for_last($submission_type, $form->form_id,null,null ,0,99999,'submission_id', true);
                if(!is_array($filter_submissions)){
                    $filter_submissions=array();
                }
                $data[$i]->count = is_array($filter_submissions) ? count($filter_submissions) : 0;
                if(defined('REGMAGIC_ADDON'))
                    $data[$i]->unread_count=  RM_DBManager::get_submission_read_count($form->form_id,0);
                 //get only 3 submissions to show
                 $filter_submissions=RM_DBManager::get_results_for_last($submission_type, $form->form_id,null,null ,0,3,'submission_id', true);

                if ($data[$i]->count > 0) {
                    $data[$i]->submissions = $filter_submissions;
                    $j = 0;
                    foreach ($data[$i]->submissions as $submission)
                         $data[$i]->submissions[$j++]->gravatar = get_avatar($submission->user_email);
                }

                $data[$i]->field_count = $service->count(RM_Fields::get_identifier(), array('form_id' => $form->form_id));
                $data[$i]->last_sub = $service->get(RM_Submissions::get_identifier(), array('form_id' => $form->form_id), array('%d'), 'var', 0, 1, 'submitted_on', 'submitted_on', true);
                //$data[$i]->last_sub = date('H',strtotime($this->service->get(RM_Submissions::get_identifier(), array('form_id' => $data_single->form_id), array('%d'), 'var', 0, 1, 'submitted_on', 'submitted_on', true)));
                $data[$i]->expiry_details = $service->get_form_expiry_stats($form, false);
                $i++;
            }

            $data = array_slice($data, ($req_page - 1) * $items_per_page, $items_per_page);

            if(empty($search_term)) {
                $total_forms = $service->count($model->get_identifier(), 1);
            } else {
                $total_forms = count($forms);
            }

        //New object to consolidate data for view.
        $view_data = new stdClass;
        $view_data->data = $data;
        if($form_filter == 'registration') {
            $view_data->filtered_count = $reg_forms;
        } elseif($form_filter == 'multi_page') {
            $view_data->filtered_count = $multi_page_forms;
        } else {
            $view_data->filtered_count = (int)$total_forms;
        }
        $view_data->old_view = get_option('rm_forms_view_roll_back');
        $view_data->curr_page = $req_page;
        $view_data->total_pages = (int) ($view_data->filtered_count / $items_per_page) + (($view_data->filtered_count % $items_per_page) == 0 ? 0 : 1);
        $view_data->total_forms = (int)$total_forms;
        $view_data->items_per_page = $items_per_page;
        $view_data->reg_forms = $reg_forms;
        $view_data->multi_page_forms = $multi_page_forms;
        $view_data->search_term = $search_term;
        $view_data->form_filter = $form_filter;
        $view_data->rm_slug = $request->req['page'];
        $view_data->sort_by = $sort_by;
        $view_data->descending = $descending;
        $view_data->url_params = $url_params;
        $view_data->done_with_review_banner = $service->get_setting('done_with_review_banner') === 'no' ? false : true;
        $view_data->def_form_id = $service->get_setting('default_form_id');
        $view_data->new_added_form = $new_added_form;
        
        //Include joyride script and style
        wp_enqueue_script('rm_joyride_js', RM_BASE_URL.'admin/js/jquery.joyride-2.1.js');
        wp_enqueue_style('rm_joyride_css', RM_BASE_URL.'admin/css/joyride-2.1.css');
        wp_enqueue_style('style_rm_formcard_menu');
        wp_enqueue_script('script_rm_formcard_menu');
        
        $view_data->autostart_tour = !RM_Utilities::has_taken_tour('form_manager_tour');
        
        $view_data->submission_type=$submission_type;
        
        /*
        $view_data->review_event=$service->get_review_event();
        $view_data->review_message=  RM_UI_Strings::get('REVIEW_MESSAGE_EVENT'.$view_data->review_event);
        $view_data->review_popup_flag=$service->check_event_status($view_data->review_event);
        */
        
        $view_data->should_show_fb_footer = ($options->get_value_of('has_subbed_fb_page') == 'yes') ? false : true;
        $view_data->templates = $service->forms_template_lists('contact');
        $view = $this->mv_handler->setView('form_manager');
        $view->render($view_data);
    }

    public function duplicate($model, $service, $request, $params) {
        $selected = isset($request->req['rm_selected']) ? $request->req['rm_selected'] : null;

        $duplicate = json_decode($selected);
        $ids = $service->duplicate($duplicate);
        //$service->duplicate_form_fields($duplicate, $ids);
        $service->duplicate_form_rows($duplicate, $ids);
        switch($request->req['req_source']){
            case 'form_dashboard':
                RM_Utilities::redirect('?page=rm_form_sett_manage&rm_form_id='.$ids[$selected]);
                
            case 'form_manager':
                $this->manage($model, $service, $request, $params);
        }
            
        return;
    }

    public function remove($model, RM_Services $service, $request, $params) {
        if(current_user_can('manage_options') && wp_verify_nonce($request->req['_wpnonce'], 'rm_form_manager_template')) {
            $selected = isset($request->req['rm_selected']) ? $request->req['rm_selected'] : null;
            $remove = json_decode($selected);
            
            $service->remove($remove);
            $service->remove_form_fields($remove);
            $service->remove_form_rows($remove);
            $service->remove_form_submissions($remove);
            $service->remove_form_payment_logs($remove);
            $service->remove_form_stats($remove);
            $service->remove_form_notes($remove);
            if(is_array($remove)){
                foreach($remove as $form_id){
                    do_action('rm_form_deleted',$form_id);
                }
            }
            $this->manage($model, $service, $request, $params);
        }
    }

    public function quick_add($model, $service, $request, $params) {
        $valid = false;
        if ($this->mv_handler->validateForm("rm_form_quick_add")) {
            $model->set($request->req);

            $valid = $model->validate_model();
        }
        if ($valid) {
            //By default make it registration type
            $model->set_form_type(1);
            $model->set_default_form_user_role('subscriber');

            if (isset($request->req['form_id']))
                $valid = $service->update($request->req['form_id']);
            else
                $service->add_user_form();
        }

        $this->manage($model, $service, $request, $params);
    }

    public function import($model, $service, $request, $params) {
         $data=new stdClass();
        
        if($_FILES){
               $name=get_temp_dir().'RMagic.xml';
               
               if(is_array($_FILES['Forms']['tmp_name']))
               $status= move_uploaded_file(sanitize_text_field($_FILES['Forms']['tmp_name']['0']), $name);
               else
               $status= move_uploaded_file(sanitize_text_field($_FILES['Forms']['tmp_name']), $name);    
          $data->status=$status;
          
           $view = $this->mv_handler->setView("form_upload");
           $view->render($data);
          }

        
        else
        { 
        $view = $this->mv_handler->setView("form_upload");
        $view->render();
        }
      
    }

    public function export($model, $service, $request, $params) {
        $retrieved_nonce = $request->req['_wpnonce'];
        if (!wp_verify_nonce($retrieved_nonce, 'rm_form_manager_template' ) ) die( __('Failed security check','custom-registration-form-builder-with-submission-manager') );
        if (current_user_can('manage_options'))
        {
            $selected = isset($request->req['rm_selected']) ? $request->req['rm_selected'] : null;

            $duplicate = json_decode($selected);
            $forms_data=array();
            if(empty($duplicate))
                $forms_data=$service->get_all('FORMS',0,0);
            else
                sort($duplicate, SORT_NUMERIC);
            foreach($duplicate as $form_selected)
            {
                $where=array(
                    "form_id"=>(int)$form_selected
                );
            $temp  = RM_DBManager::get("FORMS",$where, array("%d"), 'results', $offset = 0, $limit = 9999999, $column = '*', $sort_by = '', $descending = false);
           $forms_data=  array_merge($forms_data,$temp);
            }

         //echo "<pre>",var_dump($forms_data);die;
          $front_user_data=$service->get_all('FRONT_USERS',0,0);
            $paypa_fields_data=$service->get_all('PAYPAL_FIELDS',0,0);

           $xmlDoc = new DOMDocument('1.0');

    //create the root element
              $root = $xmlDoc->appendChild(
              $xmlDoc->createElement("RMagic"));

            if(isset($forms_data))
            {
            foreach($forms_data as $forms)
            {   
                //echo "<pre>", var_dump($xml->startElement("form"));
              $tutTag = $root->appendChild(
                  $xmlDoc->createElement('FORMS'));
               $temp = $tutTag->appendChild(
                  $xmlDoc->createElement('OPTIONS'));
                foreach($forms as $form_attr_name=>$value)
                {
                    $form_attr_name=  htmlspecialchars($form_attr_name);
                    $value=  htmlspecialchars($value);
                   $temp->appendChild(
                   $xmlDoc->createElement($form_attr_name, $value));
                }

                $where=array(
                    "form_id"=>(int)$forms->form_id
                );

             $fields_data  = RM_DBManager::  get("FIELDS",$where, array("%d"), 'results', $offset = 0, $limit = 9999999, $column = '*', $sort_by = '', $descending = false);
             $rows_data  = RM_DBManager::  get("ROWS",$where, array("%d"), 'results', $offset = 0, $limit = 9999999, $column = '*', $sort_by = '', $descending = false);
             $submissions_data  = RM_DBManager::  get("SUBMISSIONS",$where, array("%d"), 'results', $offset = 0, $limit = 9999999, $column = '*', $sort_by = '', $descending = false);
             //$notes_data  = RM_DBManager::  get("NOTES",$where, array("%d"), 'results', $offset = 0, $limit = 9999999, $column = '*', $sort_by = '', $descending = false);
             //$front_user_data  = RM_DBManager::  get("FRONT_USERS",$where, array("%d"), 'results', $offset = 0, $limit = 9999999, $column = '*', $sort_by = '', $descending = false);
             //$paypa_fields_data  = RM_DBManager::  get("PAYPAL_FIELDS",$where, array("%d"), 'results', $offset = 0, $limit = 9999999, $column = '*', $sort_by = '', $descending = false);
             $paypal_log_data  = RM_DBManager::  get("PAYPAL_LOGS",$where, array("%d"), 'results', $offset = 0, $limit = 9999999, $column = '*', $sort_by = '', $descending = false);
             $stats_data  = RM_DBManager::  get("STATS",$where, array("%d"), 'results', $offset = 0, $limit = 9999999, $column = '*', $sort_by = '', $descending = false);
             $submisson_field_data  = RM_DBManager::  get("SUBMISSION_FIELDS",$where, array("%d"), 'results', $offset = 0, $limit = 9999999, $column = '*', $sort_by = '', $descending = false);

             if(isset($fields_data))
            {
            foreach($fields_data as $forms)
            {   
                //echo "<pre>", var_dump($xml->startElement("form"));
              $temp = $tutTag->appendChild(
                  $xmlDoc->createElement('FIELDS'));
                foreach($forms as $form_attr_name=>$value)
                {
                    $form_attr_name=  htmlspecialchars($form_attr_name);
                  $value=  htmlspecialchars($value);
                   $temp->appendChild(
                   $xmlDoc->createElement($form_attr_name, $value));
                }

            }
            }
                if(!empty($rows_data)) {
                    foreach($rows_data as $row) {
                        $temp = $tutTag->appendChild(
                            $xmlDoc->createElement('ROWS')
                        );
                        foreach($row as $row_attr_name => $value) {
                            $row_attr_name = htmlspecialchars($row_attr_name);
                            $value = htmlspecialchars($value);
                            $temp->appendChild(
                                $xmlDoc->createElement($row_attr_name, $value)
                            );
                        }
                    }
                }
             if(isset($submissions_data))
            {
            foreach($submissions_data as $forms)
            {   
                //echo "<pre>", var_dump($xml->startElement("form"));
              $temp = $tutTag->appendChild(
                  $xmlDoc->createElement('SUBMISSIONS'));
                foreach($forms as $form_attr_name=>$value)
                {
                    $form_attr_name=  htmlspecialchars($form_attr_name);
                  $value=  htmlspecialchars($value);
                   $temp->appendChild(
                   $xmlDoc->createElement($form_attr_name, $value));
                }

            }
            }
            if(isset($notes_data))
            {
            foreach($notes_data as $forms)
            {   
                //echo "<pre>", var_dump($xml->startElement("form"));
              $temp = $tutTag->appendChild(
                  $xmlDoc->createElement('NOTES'));
                foreach($forms as $form_attr_name=>$value)
                {
                    $form_attr_name=  htmlspecialchars($form_attr_name);
                  $value=  htmlspecialchars($value);
                   $temp->appendChild(
                   $xmlDoc->createElement($form_attr_name, $value));
                }

            }
            }
             if(isset($paypal_log_data))
            {
            foreach($paypal_log_data as $forms)
            {   
                //echo "<pre>", var_dump($xml->startElement("form"));
              $temp = $tutTag->appendChild(
                  $xmlDoc->createElement('PAYPAL_LOGS'));
                foreach($forms as $form_attr_name=>$value)
                {
                  $form_attr_name=  htmlspecialchars($form_attr_name);
                  $value=  htmlspecialchars($value);
                   $temp->appendChild(
                   $xmlDoc->createElement($form_attr_name, $value));
                }

            }
            }
           if(isset($stats_data))
            {
            foreach($stats_data as $forms)
            {   
                //echo "<pre>", var_dump($xml->startElement("form"));
              $temp = $tutTag->appendChild(
                  $xmlDoc->createElement('STATS'));
                foreach($forms as $form_attr_name=>$value)
                {
                    $form_attr_name=  htmlspecialchars($form_attr_name);
                  $value=  htmlspecialchars($value);
                   $temp->appendChild(
                   $xmlDoc->createElement($form_attr_name, $value));
                }

            }
            }
             if(isset($submisson_field_data))
            {
           foreach($submisson_field_data as $forms)
            {   
                //echo "<pre>", var_dump($xml->startElement("form"));
              $temp = $tutTag->appendChild(
                  $xmlDoc->createElement('SUBMISSION_FIELDS'));
                foreach($forms as $form_attr_name=>$value)
                {
                    $form_attr_name=  htmlspecialchars($form_attr_name);
                  $value=  htmlspecialchars($value);
                   $temp->appendChild(
                   $xmlDoc->createElement($form_attr_name, $value));
                }

            }
            }
            }
            }
            if(isset($front_user_data))
            {
            foreach($front_user_data as $forms)
            {   
                //echo "<pre>", var_dump($xml->startElement("form"));
              $tutTag = $root->appendChild(
                  $xmlDoc->createElement('FRONT_USERS'));
                foreach($forms as $form_attr_name=>$value)
                {
                    $form_attr_name=  htmlspecialchars($form_attr_name);
                  $value=  htmlspecialchars($value);
                   $tutTag->appendChild(
                   $xmlDoc->createElement($form_attr_name, $value));
                }

            }
            }

            if(isset($paypa_fields_data))
            {
            foreach($paypa_fields_data as $forms)
            {   
                //echo "<pre>", var_dump($xml->startElement("form"));
              $tutTag = $root->appendChild(
                  $xmlDoc->createElement('PAYPAL_FIELDS'));
                foreach($forms as $form_attr_name=>$value)
                {
                    $form_attr_name=  htmlspecialchars($form_attr_name);
                  $value=  htmlspecialchars($value);
                   $tutTag->appendChild(
                   $xmlDoc->createElement($form_attr_name, $value));
                }

            }
            }
          /*  foreach($wp_user_data as $forms)
            {   
                //echo "<pre>", var_dump($xml->startElement("form"));
              $tutTag = $root->appendChild(
                  $xmlDoc->createElement('WP_USERS'));
                foreach($forms as $form_attr_name=>$value)
                {
                   $tutTag->appendChild(
                   $xmlDoc->createElement($form_attr_name, $value));
                }

            }
             foreach($wp_user_meta_data as $forms)
            {   
                //echo "<pre>", var_dump($xml->startElement("form"));
              $tutTag = $root->appendChild(
                  $xmlDoc->createElement('WP_USERS_META'));
                foreach($forms as $form_attr_name=>$value)
                {
                   $tutTag->appendChild(
                   $xmlDoc->createElement($form_attr_name, $value));
                }

            }*/

            $xmlDoc->formatOutput = true;
            $name=get_temp_dir().'RMagic.xml';
    // Output content
          $xmlDoc->save($name);

           $service->download_file($name);
        }
    }
    
    public function add_new_form_template($model, $service, $request, $params){
      if (isset($request->req['form_name'], $request->req['form_type'])
                && $request->req['form_name']) {  
          $request->req['form_type'] = ($request->req['form_type'] === 'rm_reg_form') ? RM_REG_FORM : RM_CONTACT_FORM;
          $model->set($request->req);
          if($model->get_form_type() == RM_REG_FORM){
                $model->set_default_form_user_role('subscriber');
          }
          $template = $request->req['template_type'] ? $request->req['template_type'] : 'c1';
          if($request->req['type']=='addon'){
            if(defined('REGMAGIC_ADDON')) {
                $addon_service = new RM_Services_Addon();
                $form_id = $addon_service->create_premium_form($model, $service, $request, $params, $template);
            }
            else{
                RM_Utilities::redirect(admin_url("admin.php?page=rm_field_manage"));
            }
          }
          else{
            $form_id = $service->create_basic_form($template);  
          }

          RM_Utilities::redirect(admin_url("admin.php?page=rm_field_manage&rm_form_id=$form_id"));
      }
    }
    
    public function add_new_form($model, $service, $request, $params) {
        
        if (isset($request->req['form_name'], $request->req['form_type'])
                && $request->req['form_name']) {
            
            $request->req['form_type'] = ($request->req['form_type'] === 'rm_reg_form') ? RM_REG_FORM : RM_CONTACT_FORM;
            
            $model->set($request->req);
            
            if($model->get_form_type() == RM_REG_FORM)
                $model->set_default_form_user_role('subscriber');

            $form_id = $service->add_user_form();
            
            RM_Utilities::redirect(admin_url("admin.php?page=rm_form_manage&rm_new_added_form=$form_id"));
        }
        
        $this->manage($model, $service, $request, $params);
    }
    
    public function manage_cstatus($model, $service, $request, $params) {
        if(defined('REGMAGIC_ADDON')) {
            $addon_controller = new RM_Form_Controller_Addon();
            return $addon_controller->manage_cstatus($model, $service, $request, $params, $this);
        } else {
            $data= new stdClass();
            $data->forms = RM_Utilities::get_forms_dropdown($service);
            if(!empty($request->req['rm_form_id'])){
                $data->form_id= $request->req['rm_form_id'];
            }else{
                if(!empty($data->forms)){
                    $form_ids= array_keys($data->forms);
                    if(is_array($form_ids) && isset($form_ids[0])){
                            $data->form_id= $form_ids[0];
                    }
                }
            }
            if(empty($data->form_id))
                return;
            
            $form_model= new RM_Forms();
            $form_model->load_from_db($data->form_id);
            $form_options= $form_model->get_form_options();
            
            if(isset($request->req['remove_cstatus']) && is_array($request->req['rm_cstatus_index'])){
                $indexes= $request->req['rm_cstatus_index'];
                foreach($indexes as $index){
                    if(isset($form_options->custom_status[$index])){
                        unset($form_options->custom_status[$index]);
                        RM_DBManager::remove_custom_status_from_submissions($data->form_id,$index);
                    }
                }
                $form_model->set_form_options($form_options);
                $form_model->update_into_db();
            }
            $data->custom_status= is_array($form_options->custom_status) ? $form_options->custom_status : array();
            $view= $this->mv_handler->setView('form_cstatus_manager');
            $view->render($data);
        }
    }
    
    public function add_cstatus($model,$service, $request, $params) {
        if(defined('REGMAGIC_ADDON')) {
            $addon_controller = new RM_Form_Controller_Addon();
            return $addon_controller->add_cstatus($model, $service, $request, $params, $this);
        }
        $data= new stdClass(); 
        $data->form_id= $request->req['rm_form_id'];
        if(empty($data->form_id))
            return;
        
        $form_model= new RM_Forms();
        $form_model->load_from_db($data->form_id);
        $form_options= $form_model->get_form_options();
        
        if($_POST){
            $custom_status= array();
            $custom_status['label']= $request->req['cstatus_label'];
            $custom_status['desc']= $request->req['cstatus_desc'];
            $custom_status['color']= $request->req['cstatus_color'];
            $custom_status['cs_show_frontend']= isset($request->req['cstatus_frontend']) ? 1 : 0;
            $custom_status['cs_action_status_en']= 0;
            $custom_status['cs_action_status']= '';
            $custom_status['cs_email_user_en']= 0;
            $custom_status['cs_email_user_subject']= '';
            $custom_status['cs_email_user_body']= '';
            $custom_status['cs_email_admin_en']= 0;
            $custom_status['cs_email_admin_subject']= '';
            $custom_status['cs_email_admin_body']= '';
            $custom_status['cs_action_user_act_en']= 0;
            $custom_status['cs_action_user_act']= '';
            $custom_status['cs_note_en']= 0;
            $custom_status['cs_note_public']= 0;
            $custom_status['cs_note_text']= '';
            $custom_status['cs_blacklist_en']= 0;
            $custom_status['cs_block_email']= 0;
            $custom_status['cs_block_ip']= 0;
            $custom_status['cs_unblock_email']= 0;
            $custom_status['cs_unblock_ip']= 0;
            $custom_status['cs_act_status_specific']= array();
            
            if(empty($form_options->custom_status)){
                $form_options->custom_status= array();
            }
 
            $cstatus_id= isset($request->req['cstatus_id']) ? absint($request->req['cstatus_id']) : 0;
            
            if(!empty($form_options->custom_status) && !isset($request->req['cstatus_id'])){
                $form_options->custom_status[]= $custom_status;
            }
            else if($cstatus_id>=0){
                $form_options->custom_status[$cstatus_id]= $custom_status;
            }
            $form_model->set_form_options($form_options);
            $form_model->update_into_db();
            RM_Utilities::redirect('?page=rm_form_manage_cstatus&rm_form_id='.$data->form_id);
            die;
        }
        $data->new= true;
        if(isset($request->req['cstatus_id'])){
            $cstatus_id= absint($request->req['cstatus_id']);
            $data->new= false;
            if(is_array($form_options->custom_status)){
                foreach($form_options->custom_status as $key=>$custom_status){
                    if($cstatus_id==$key){
                        $data->custom_status= $custom_status;
                        break;
                    }
                }
            }
        }
        else
            $cstatus_id=0;
        
        $data->cstatus_id= $cstatus_id;
        $data->form_options= $form_options;
        $view= $this->mv_handler->setView('cstatus_add');
        $view->render($data);
    }
    
    public function metabundle($model,$service, $request, $params) { 
       if(defined('REGMAGIC_ADDON')) {
           $addon_controller = new RM_Form_Controller_Addon();
           return $addon_controller->metabundle($model,$service, $request, $params, $this);
       }
    }
    public function setup($model, $service, $request, $params){
        global $wp_roles;
        $data = new stdClass;
        $roles = RM_Utilities::user_role_dropdown(true, true);
        $role = array();
        foreach ($roles as $key => $value) {
            if($key) $role[$key] = $value;
        }
        $data->roles=$role;
        $data->reg_template = $service->forms_template_lists('registration');
        $data->cont_template = $service->forms_template_lists('contact');
        $view = $this->mv_handler->setView('form_setup');
        $view->render($data);
    }

    public function add_setup($model, $service, $request, $params){
      if (isset($request->req['form_name'], $request->req['form_type'])
                && $request->req['form_name']) {  
          $request->req['form_type'] = ($request->req['form_type'] === 'rm_reg_form') ? RM_REG_FORM : RM_CONTACT_FORM;
          $model->set($request->req);
          if($model->get_form_type() == RM_REG_FORM){
            if(isset($request->req['default_form_user_role'])){
                $model->set_default_form_user_role($request->req['default_form_user_role']);
            }
            else{
                $model->set_default_form_user_role('subscriber');   
            }
          }
          $template = $request->req['template_type'] ? $request->req['template_type'] : 'c1';
          if($request->req['type']=='addon'){
            if(defined('REGMAGIC_ADDON')) {
                $addon_service = new RM_Services_Addon();
                $form_id = $addon_service->create_premium_form($model, $service, $request, $params, $template);
            }
            else{
                RM_Utilities::redirect(admin_url("admin.php?page=rm_form_setup_finished"));
            }
          }
          else{
            $form_id = $service->create_basic_form($template);  
          }
          
          RM_Utilities::redirect(admin_url("admin.php?page=rm_form_setup_finished&rm_form_id=$form_id"));
          //RM_Utilities::redirect(admin_url("admin.php?page=rm_form_manage&rm_new_added_form=$form_id"));
      }
    }
    public function setup_finished($model, $service, $request, $params){
        $data = new stdClass;
        if(isset($request->req['rm_form_id'])){
            $form_id = $request->req['rm_form_id'];
            $data->form = RM_DBManager::get_row('FORMS', $form_id);
        }

        
        $view = $this->mv_handler->setView('form_setup_finished');
        $view->render($data);       
    }
}