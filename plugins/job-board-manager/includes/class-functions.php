<?php
if ( ! defined('ABSPATH')) exit;  // if direct access


class class_job_bm_functions{
	
	public function __construct(){


		}

	
	public function job_type_list(){

		$job_type_list = array(
		    'full-time'=>__('Full Time', 'job-board-manager'),
            'freelance'=>__('Freelance', 'job-board-manager'),
            'internship'=>__('Internship', 'job-board-manager'),
            'part-time'=>__('Part Time', 'job-board-manager'),
            'temporary'=>__('Temporary', 'job-board-manager')
        );
		
		$job_type_list = apply_filters('job_bm_job_type', $job_type_list);

		return $job_type_list;

		}
		
		
	public function job_status_list(){

		$job_status_list = array(
		    'open'=>__('Open', 'job-board-manager'),
            'closed'=>__('Closed', 'job-board-manager'),
            'filled'=>__('Filled', 'job-board-manager'),
            're-open'=>__('Re-Open', 'job-board-manager'),
            'expired'=>__('Expired', 'job-board-manager')
        );

		$job_status_list = apply_filters('job_bm_job_status', $job_status_list);

		return $job_status_list;

		}		
		
	
	public function job_level_list(){

		$job_level_list = array(
		    ''=>__('All', 'job-board-manager'),
            'entry_level'=>__('Entry level', 'job-board-manager'),
            'mid_level'=>__('Mid level', 'job-board-manager'),
            'top_level'=>__('Top level', 'job-board-manager')
        );
		$job_level_list = apply_filters('job_bm_job_level', $job_level_list);

		return $job_level_list;

		}	
	
	
	public function salary_type_list(){

		$salary_type_list = array(
		    'negotiable'=>__('Negotiable', 'job-board-manager'),
            'fixed'=>__('Fixed', 'job-board-manager'),
            'min-max'=>__('Salary range', 'job-board-manager')
        );
		$salary_type_list = apply_filters('job_bm_salary_type', $salary_type_list);

		return $salary_type_list;

		}


    public function salary_duration_list(){

        $salary_type_list = array(
            'hour'=>__('Per Hour', 'job-board-manager'),
            'day'=>__('Per Day', 'job-board-manager'),
            'week'=>__('Per Week', 'job-board-manager'),
            'month'=>__('Per Month', 'job-board-manager'),
            'year'=>__('Per Year', 'job-board-manager'),


        );
        $salary_type_list = apply_filters('job_bm_salary_durations', $salary_type_list);

        return $salary_type_list;

    }

	public function salary_range_list(){

		$salary_range_list = array(
		    ''=>__('All', 'job-board-manager'),
            '1000-10000'=>__('1,000-10,000', 'job-board-manager'),
            '10001-20000'=>__('10,001-20,000', 'job-board-manager'),
            '20001-30000'=>__('20,001-30,000', 'job-board-manager'),
            '30001-50000'=>__('30,001-50,000', 'job-board-manager'),
            '50001-80000'=>__('50,001-80,000', 'job-board-manager'),
            '80001-100000'=>__('80,001-1,00,000', 'job-board-manager')
        );
		$salary_range_list = apply_filters('job_bm_salary_range', $salary_range_list);

		return $salary_range_list;

		}	
	
	
	

	public function apply_method_list(){

		$apply_method_list = array(
		    'none'=>__('None', 'job-board-manager'),
            'direct_email'=>sprintf(__('%s By Email', 'job-board-manager'),'<i class="fas fa-envelope-open-text"></i>'),
            'external_website'=>sprintf(__('%s External website', 'job-board-manager'),'<i class="fas fa-external-link-alt"></i>'),

        );

		$apply_method_list = apply_filters('job_bm_application_methods', $apply_method_list);

		return $apply_method_list;

		}
	
	public function job_type_bg_color(){

		$job_type_color = array(
		    'freelance'=>'#46a6ff',
            'full-time'=>'#2ec274',
            'internship'=>'#a066ff',
            'part-time'=>'#ffc24d',
            'temporary'=>'#ff5741',
        );
		$job_type_color = apply_filters('job_bm_job_type_bg_color', $job_type_color);

		return $job_type_color;

		}

    public function job_type_text_color(){

        $job_type_color = array(
            'freelance'=>'#ffffff',
            'full-time'=>'#ffffff',
            'internship'=>'#ffffff',
            'part-time'=>'#ffffff',
            'temporary'=>'#ffffff',
        );
        $job_type_color = apply_filters('job_bm_job_type_text_color', $job_type_color);

        return $job_type_color;

    }



	public function job_status_bg_color(){

		$job_status_color = array(
		    'open'=>'#3ac170',
            'closed'=>'#fa3218',
            'filled'=>'#49a2ed',
            're-open'=>'#2fc2f9',
            'expired'=>'#ff4115',
        );
		$job_status_color = apply_filters('job_bm_job_status_bg_color', $job_status_color);

		return $job_status_color;

		}


    public function job_status_text_color(){

        $job_status_color = array(
            'open'=>'#ffffff',
            'closed'=>'#ffffff',
            'filled'=>'#ffffff',
            're-open'=>'#ffffff',
            'expired'=>'#ffffff',
        );
        $job_status_color = apply_filters('job_bm_job_status_text_color', $job_status_color);

        return $job_status_color;

    }

	
	
	public function reports_tabs(){
		
		
		$reports_tabs = array(
					'job'=>array(
								'title'=>__('Jobs', 'job-board-manager'),
								'html'=>apply_filters('job_bm_report_html_job', ''),
								),
					);
		
		$reports_tabs = apply_filters('job_bm_reports_tabs', $reports_tabs);
		
		return $reports_tabs;
		
		
		
		}	
	
	

	
	
	

	
	
	public function tutorials(){

		$tutorials[] = array(
							'title'=>__('Job Board Manager - How to Install and Settings.', 'job-board-manager'),
							'video_id'=>'avfOO82Kz2g',
							'source'=>'youtube',
							);
							
		$tutorials[] = array(
							'title'=>__('Job Board Manager - Create a job.', 'job-board-manager'),
							'video_id'=>'KZygrlmNrE8',
							'source'=>'youtube',
							);							
							
		
		
		$tutorials = apply_filters('job_bm_tutorials', $tutorials);

		return $tutorials;

		}	
	
	
	public function faq(){



		$faq['core'] = array(
							'title'=>__('Core', 'job-board-manager'),
							'items'=>array(
							
											array(
												'question'=>__('Single job page showing 404 error', 'job-board-manager'),
												'answer_url'=>'https://pickplugins.com/documentation/job-board-manager/faq/single-job-page-showing-404-error/',
					
												),

											array(
												'question'=>__('How can i change slug for Job ?', 'job-board-manager'),
												'answer_url'=>'https://pickplugins.com/documentation/job-board-manager/faq/how-can-i-change-slug-for-job/',
												),												

											array(
												'question'=>__('How to add/remove job types', 'job-board-manager'),
												'answer_url'=>'https://pickplugins.com/documentation/job-board-manager/filter-hooks/job_bm_job_type/',
												),	


											),

								
							);

					
		
		
		$faq = apply_filters('job_bm_faqs', $faq);

		return $faq;

		}		
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function create_pages(){
		
		$page_data = array(
            array(
                'id'=>'job_bm_archive_page_id',
                'title'=>__('Job Archive', 'job-board-manager'),
                'shortcode'=>'[job_bm_archive]',
            ),
            array(
                'id'=>'job_bm_job_submit_page_id',
                'title'=>__('Job Submit', 'job-board-manager'),
                'shortcode'=>'[job_submit_form]',
            ),
            array(
                'id'=>'job_bm_job_edit_page_id',
                'title'=>__('Job Edit', 'job-board-manager'),
                'shortcode'=>'[job_bm_job_edit]',
            ),
            array(
                'id'=>'job_bm_job_login_page_id',
                'title'=>__('Job Dashboard', 'job-board-manager'),
                'shortcode'=>'[job_bm_dashboard] ',
            ),
		);
																					
							
        $page_data = apply_filters( 'job_bm_create_pages', $page_data );

        return $page_data;

	}


		
		
	
	public function job_bm_list_user_role(){
		
		global $wp_roles;
		$roles = $wp_roles->get_names();
		return $roles;
		}	
		
		
	
	}
	
	new class_job_bm_functions();