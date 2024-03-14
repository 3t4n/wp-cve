<?php

class RM_User_Filter extends RM_Filter {
   
    public $total_entries;
    public $total_users;
    public $records;
    public function __construct($request, $service) {
        
        $params = array(
        'rm_interval' => 'rm_interval',
        'rm_status' => 'rm_status',
        'rm_to_search'=>'rm_to_search',
        'rm_sort'=>'rm_sort',
        'rm_user_role'=>'rm_user_role',
        );
        $default_param_values = array('rm_user_role'=>'all','rm_interval' => 'all', 'rm_status' => 'all',
        'rm_to_search' => "", 'rm_sort' => "latest", 'rm_reqpage' => '1');
        
        parent::__constuct($request,$service, $params, $default_param_values);
        $this->set_pagination();
        
    }

    public function get_records() {
       $entries_per_page= get_site_option('rm_user_entry_depth');
       $entries_per_page= empty($entries_per_page) ? 10 : absint($entries_per_page);
        
       $all_users  = $this->service->get_all_user_data($this->pagination->curr_page, $entries_per_page, $this->filters['rm_to_search'], $this->filters['rm_status'], $this->filters['rm_interval'], $this->filters['rm_sort'], $this->filters['rm_user_role']);
       $this->records = $all_users->get_results();
       $this->total_users = $all_users->total_users;
       return $all_users; 
      
    }

     public function set_pagination(){
        $entries_per_page= get_site_option('rm_user_entry_depth');
        $entries_per_page= empty($entries_per_page) ? 10 : absint($entries_per_page);
       
        $total_entries=null;
        $total_entries = $this->total_users; //count($this->service->get_all_user_data(1, 99999999, $this->filters['rm_to_search'], $this->filters['rm_status'], $this->filters['rm_interval'], $this->filters['rm_sort'], $this->filters['rm_user_role']));
        $this->total_entries = $total_entries;
       if (isset($_POST['rm_interval']) || isset($_POST['rm_status']))
            $request->req['rm_reqpage'] = 1;
       
        $req_page = (isset($this->request->req['rm_reqpage']) && $this->request->req['rm_reqpage'] > 0) ? $this->request->req['rm_reqpage'] : 1;
        
        $this->pagination= new RM_Pagination($this->filters,$this->request->req['page'],$total_entries,$req_page,$entries_per_page);
    } 

}
