<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

// Creating the widget 
class JSJOBSjobssearchjobs_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
                // Base ID of your widget
                'JSJOBSjobssearchjobs_widget',
                // Widget name will appear in UI
                __('Job Search', 'js-jobs'),
                // Widget description
                array('description' => __('Search jobs form JS Jobs database', 'js-jobs'),)
        );
    }

    // Creating widget front-end
    // This is where the action happens
    public function widget($args, $instance) {

        $jobtitle = apply_filters('widget_jobtitle', $instance['jobtitle']);
        $title = apply_filters('widget_title', $instance['title']);
        $showtitle = apply_filters('widget_showtitle', $instance['showtitle']);
        $category = apply_filters('widget_category', $instance['category']);
        $jobtype = apply_filters('widget_jobtype', $instance['jobtype']);
        $jobstatus = apply_filters('widget_jobstatus', $instance['jobstatus']);
        $salaryrange = apply_filters('widget_salaryrange', $instance['salaryrange']);
        $shift = apply_filters('widget_shift', $instance['shift']);
        $duration = apply_filters('widget_duration', $instance['duration']);
        $startpublishing = 0;
        $stoppublishing = 0;
        $company = apply_filters('widget_company', $instance['company']);
        $address = apply_filters('widget_address', $instance['address']);
        $columnperrow = apply_filters('widget_columnperrow', $instance['columnperrow']);

        // before and after widget arguments are defined by themes
        echo wp_kses($args['before_widget'], JSJOBS_ALLOWED_TAGS);
        if (!empty($title)){
            echo wp_kses($args['before_title'], JSJOBS_ALLOWED_TAGS) . wp_kses($title . $args['after_title'], JSJOBS_ALLOWED_TAGS);
        }
        // This is where you run the code and display the output
        //Frontend HTML starts

        if (locate_template('js-jobs/widget-searchjobs.php', 1, 0)) {
            $defaulthtml = false;
        }else{
            jsjobs::addStyleSheets();
            $modules_html = JSJOBSincluder::getJSModel('jobsearch')->getSearchJobs_Widget($title, $showtitle, $jobtitle, $category, $jobtype, $jobstatus, $salaryrange, $shift, $duration, $startpublishing, $stoppublishing, $company, $address, $columnperrow);
            echo wp_kses($modules_html, JSJOBS_ALLOWED_TAGS);
        }

        //Frontend HTML ends -------------
        // before and after widget arguments are defined by themes
        echo wp_kses($args['after_widget'], JSJOBS_ALLOWED_TAGS);
    }

    // Widget Backend 
    public function form($instance) {
        $title = (isset($instance['title'])) ? $instance['title'] : __('Search Job', 'js-jobs');
        $showtitle = (isset($instance['showtitle'])) ? $instance['showtitle'] : 1;
        $jobtitle = (isset($instance['jobtitle'])) ? $instance['jobtitle'] : 1;
        $category = (isset($instance['category'])) ? $instance['category'] : 1;

        $jobtype = (isset($instance['jobtype'])) ? $instance['jobtype'] : 1;
        $jobstatus = (isset($instance['jobstatus'])) ? $instance['jobstatus'] : 1;
        $salaryrange = (isset($instance['salaryrange'])) ? $instance['salaryrange'] : 1;
        $shift = (isset($instance['shift'])) ? $instance['shift'] : 1;
        $duration = (isset($instance['duration'])) ? $instance['duration'] : 1;
        $company = (isset($instance['company'])) ? $instance['company'] : 1;
        $address = (isset($instance['address'])) ? $instance['address'] : 1;
        $columnperrow = (isset($instance['columnperrow'])) ? $instance['columnperrow'] : 1;
        ?>
        <!-- widgets admin form options -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo __('Title', 'js-jobs'); ?></label> 
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('showtitle')); ?>"><?php echo __('Show Title', 'js-jobs'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('showtitle')); ?>" name="<?php echo esc_attr($this->get_field_name('showtitle')); ?>">
                <option value="0" <?php if (esc_attr($showtitle) == 0) echo "selected"; ?>><?php echo __('Hide', 'js-jobs'); ?></option>
                <option value="1" <?php if (esc_attr($showtitle) == 1) echo "selected"; ?>><?php echo __('Show', 'js-jobs'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('jobtitle')); ?>"><?php echo __('Title', 'js-jobs'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('jobtitle')); ?>" name="<?php echo esc_attr($this->get_field_name('jobtitle')); ?>">
                <option value="0" <?php if (esc_attr($jobtitle) == 0) echo "selected"; ?>><?php echo __('Hide', 'js-jobs'); ?></option>
                <option value="1" <?php if (esc_attr($jobtitle) == 1) echo "selected"; ?>><?php echo __('Show', 'js-jobs'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('category')); ?>"><?php echo __('Category', 'js-jobs'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('category')); ?>" name="<?php echo esc_attr($this->get_field_name('category')); ?>">
                <option value="0" <?php if (esc_attr($category) == 0) echo "selected"; ?>><?php echo __('Hide', 'js-jobs'); ?></option>
                <option value="1" <?php if (esc_attr($category) == 1) echo "selected"; ?>><?php echo __('Show', 'js-jobs'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('jobtype')); ?>"><?php echo __('Job type', 'js-jobs'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('jobtype')); ?>" name="<?php echo esc_attr($this->get_field_name('jobtype')); ?>">
                <option value="0" <?php if (esc_attr($jobtype) == 0) echo "selected"; ?>><?php echo __('Hide', 'js-jobs'); ?></option>
                <option value="1" <?php if (esc_attr($jobtype) == 1) echo "selected"; ?>><?php echo __('Show', 'js-jobs'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('jobstatus')); ?>"><?php echo __('Job Status', 'js-jobs'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('jobstatus')); ?>" name="<?php echo esc_attr($this->get_field_name('jobstatus')); ?>">
                <option value="0" <?php if (esc_attr($jobstatus) == 0) echo "selected"; ?>><?php echo __('Hide', 'js-jobs'); ?></option>
                <option value="1" <?php if (esc_attr($jobstatus) == 1) echo "selected"; ?>><?php echo __('Show', 'js-jobs'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('salaryrange')); ?>"><?php echo __('Salary Range', 'js-jobs'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('salaryrange')); ?>" name="<?php echo esc_attr($this->get_field_name('salaryrange')); ?>">
                <option value="0" <?php if (esc_attr($salaryrange) == 0) echo "selected"; ?>><?php echo __('Hide', 'js-jobs'); ?></option>
                <option value="1" <?php if (esc_attr($salaryrange) == 1) echo "selected"; ?>><?php echo __('Show', 'js-jobs'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('shift')); ?>"><?php echo __('Shift', 'js-jobs'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('shift')); ?>" name="<?php echo esc_attr($this->get_field_name('shift')); ?>">
                <option value="0" <?php if (esc_attr($shift) == 0) echo "selected"; ?>><?php echo __('Hide', 'js-jobs'); ?></option>
                <option value="1" <?php if (esc_attr($shift) == 1) echo "selected"; ?>><?php echo __('Show', 'js-jobs'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('duration')); ?>"><?php echo __('Duration', 'js-jobs'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('duration')); ?>" name="<?php echo esc_attr($this->get_field_name('duration')); ?>">
                <option value="0" <?php if (esc_attr($duration) == 0) echo "selected"; ?>><?php echo __('Hide', 'js-jobs'); ?></option>
                <option value="1" <?php if (esc_attr($duration) == 1) echo "selected"; ?>><?php echo __('Show', 'js-jobs'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('company')); ?>"><?php echo __('Company', 'js-jobs'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('company')); ?>" name="<?php echo esc_attr($this->get_field_name('company')); ?>">
                <option value="0" <?php if (esc_attr($company) == 0) echo "selected"; ?>><?php echo __('Hide', 'js-jobs'); ?></option>
                <option value="1" <?php if (esc_attr($company) == 1) echo "selected"; ?>><?php echo __('Show', 'js-jobs'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('address')); ?>"><?php echo __('Address', 'js-jobs'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('address')); ?>" name="<?php echo esc_attr($this->get_field_name('address')); ?>">
                <option value="0" <?php if (esc_attr($address) == 0) echo "selected"; ?>><?php echo __('Hide', 'js-jobs'); ?></option>
                <option value="1" <?php if (esc_attr($address) == 1) echo "selected"; ?>><?php echo __('Show', 'js-jobs'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('columnperrow')); ?>"><?php echo __('Column per row', 'js-jobs'); ?></label> 
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('columnperrow')); ?>" name="<?php echo esc_attr($this->get_field_name('columnperrow')); ?>" type="text" value="<?php echo esc_attr($columnperrow); ?>" />
        </p>
        <?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance) {
        $instance = array();

        $instance['title'] = (!empty($new_instance['title'])) ? jsjobslib::jsjobs_strip_tags($new_instance['title']) : '';
        $instance['showtitle'] = (!empty($new_instance['showtitle'])) ? jsjobslib::jsjobs_strip_tags($new_instance['showtitle']) : '';
        $instance['jobtitle'] = (!empty($new_instance['jobtitle'])) ? jsjobslib::jsjobs_strip_tags($new_instance['jobtitle']) : '';
        $instance['category'] = (!empty($new_instance['category'])) ? jsjobslib::jsjobs_strip_tags($new_instance['category']) : '';

        $instance['jobtype'] = (!empty($new_instance['jobtype'])) ? jsjobslib::jsjobs_strip_tags($new_instance['jobtype']) : '';
        $instance['jobstatus'] = (!empty($new_instance['jobstatus'])) ? jsjobslib::jsjobs_strip_tags($new_instance['jobstatus']) : '';
        $instance['salaryrange'] = (!empty($new_instance['salaryrange'])) ? jsjobslib::jsjobs_strip_tags($new_instance['salaryrange']) : '';
        $instance['shift'] = (!empty($new_instance['shift'])) ? jsjobslib::jsjobs_strip_tags($new_instance['shift']) : '';
        $instance['duration'] = (!empty($new_instance['duration'])) ? jsjobslib::jsjobs_strip_tags($new_instance['duration']) : '';
        $instance['company'] = (!empty($new_instance['company'])) ? jsjobslib::jsjobs_strip_tags($new_instance['company']) : '';
        $instance['address'] = (!empty($new_instance['address'])) ? jsjobslib::jsjobs_strip_tags($new_instance['address']) : '';
        $instance['columnperrow'] = (!empty($new_instance['columnperrow'])) ? jsjobslib::jsjobs_strip_tags($new_instance['columnperrow']) : '';

        return $instance;
    }

}

// Class wpb_widget ends here
// Register and load the widget
function JSJOBSjobssearchjobs_load_widget() {
    register_widget('JSJOBSjobssearchjobs_widget');
}

add_action('widgets_init', 'JSJOBSjobssearchjobs_load_widget');
?>
