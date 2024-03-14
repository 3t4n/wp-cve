<?php

class RM_Submission_Filter extends RM_Filter {
    public $form_id;
    public $total_entries;

    public function __construct($request, $service) {
        $params = array(
        'rm_field_to_search' => 'rm_field_to_search',
        'rm_value_to_search' => 'rm_value_to_search',
        'rm_interval' => 'rm_interval',
        'rm_fromdate' => 'rm_fromdate',
        'rm_dateupto' => 'rm_dateupto',
        'filter_tags'=>'filter_tags',
        'custom_status_ind'=>'custom_status_ind',
        'sort_order'=>'sort_order',
    );
        
        $default_param_values = array('rm_interval' => 'all', 'rm_field_to_search' => null,
        'rm_value_to_search' => null, 'rm_fromdate' => null,
        'rm_dateupto' => null,'filter_tags'=>null,'custom_status_ind'=>null,'sort_order'=>'DESC');
        
        parent::__constuct($request,$service, $params, $default_param_values);
        
        $this->set_form($service);
        if ((isset($this->params['rm_field_to_search']) && (int) $this->params['rm_field_to_search']) || isset($this->params['filter_tags'])) {
            $this->searched = true;
        } 
       
        $this->total_entries = $this->set_pagination();
    }

    public function set_form($service) {
        if (isset($this->request->req['rm_form_id'])) {
            $this->form_id = $this->request->req['rm_form_id'];
        } else {
            //$this->form_id = $service->get('FORMS', 1, array('%d'), 'var', 0, 15, $column = 'form_id', null, true);
            //$this->form_id = RM_Utilities::get_latest_active_form();
            //if(empty($this->form_id)) {
                //$this->form_id = $service->get('FORMS', 1, array('%d'), 'var', 0, 15, $column = 'form_id', null, true);
            //}
            $saved_form = get_option('rm_inbox_default_form');
            $this->form_id = empty($saved_form) ? null : $saved_form;
            $this->form_id = apply_filters('rm_aps_form_filter', $this->form_id);
        }
    }

    public function get_form() {
        return $this->form_id;
    }

    public function get_records() {
        $this->records =  RM_DBManager::get_submissions($this,$this->form_id);
        return $this->records;
    }
    
    public function set_pagination(){
        $total_entries=null;
        
        $req_page = null;
        if (isset($this->request->req['rm_search_initiated']))
            $req_page = 1; //reset pagination in case a new search is initiated.
        else
            $req_page = (isset($this->request->req['rm_reqpage']) && $this->request->req['rm_reqpage'] > 0) ? $this->request->req['rm_reqpage'] : 1;
        
        $this->filters['rm_form_id']= $this->form_id;
        $entries_per_page = get_site_option('rm_inbox_entry_depth');
        $entries_per_page = empty($entries_per_page) ? 10 : absint($entries_per_page);
        $this->pagination = new RM_Pagination($this->filters,$this->request->req['page'],0,$req_page,$entries_per_page);
        $total_entries = RM_DBManager::get_submissions($this,$this->form_id,"*",'submission_id',true,'results',false);
        $total_entries = empty($total_entries) ? array() : $total_entries;
        
        $this->pagination->set_total_entries(count($total_entries));
        
        if(str_contains((string)$this->filters['filter_tags'],'Unread')) {
            $this->filters['filter_tags'] = str_replace("Unread","Read",$this->filters['filter_tags']);
            $this->pagination->filters['filter_tags'] = str_replace("Unread","Read",$this->pagination->filters['filter_tags']);
            $counter_entries = intval(RM_DBManager::get_submissions($this,$this->form_id,"COUNT(*)",'submission_id',true,'var',false));
            $this->filters['filter_tags'] = str_replace("Read","Unread",$this->filters['filter_tags']);
            $this->pagination->filters['filter_tags'] = str_replace("Read","Unread",$this->pagination->filters['filter_tags']);

            return array(
                'all' => count($total_entries) + $counter_entries,
                'read' => $counter_entries,
                'unread' => count($total_entries),
            );
        } elseif(str_contains((string)$this->filters['filter_tags'],'Read')) {
            $this->filters['filter_tags'] = str_replace("Read","Unread",$this->filters['filter_tags']);
            $this->pagination->filters['filter_tags'] = str_replace("Read","Unread",$this->pagination->filters['filter_tags']);
            $counter_entries = intval(RM_DBManager::get_submissions($this,$this->form_id,"COUNT(*)",'submission_id',true,'var',false));
            $this->filters['filter_tags'] = str_replace("Unread","Read",$this->filters['filter_tags']);
            $this->pagination->filters['filter_tags'] = str_replace("Unread","Read",$this->pagination->filters['filter_tags']);

            return array(
                'all' => count($total_entries) + $counter_entries,
                'read' => count($total_entries),
                'unread' => $counter_entries,
            );
        } else {
            $read_entries = 0;
            $unread_entries = 0;
            foreach($total_entries as $entry) {
                if(absint($entry->is_read) == 1) {
                    $read_entries++;
                } else {
                    $unread_entries++;
                }
            }
            
            return array(
                'all' => count($total_entries),
                'read' => $read_entries,
                'unread' => $unread_entries,
            );
        }
    } 
    
}
