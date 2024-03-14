<?php
//post page
?>
<div class="wrld-whatsnew-post-container" id="wrld-post-img">
    <?php
    $version_data = array();
        $version_data = get_transient('wrld-latest-whatsnew-data') ?? [];
        foreach ($version_data as $key => $version) {
            if ( version_compare( $version->version_number, WRLD_PLUGIN_VERSION, '>' ) ) {
      ?>
    <div class="wrld-post-latest post-section-heading <?php echo $key == 0 ? '' : 'wrld-version-margin' 
     ?> ">
        <?php echo $key == 0 ? '<h2>'. __('Latest Version','learndash-reports-by-wisdmlabs').'</h2><br/>' : ''; ?>
        <h2 class="wrld-version"><?php echo __( 'Version' , 'learndash-reports-by-wisdmlabs' ); ?> 
            <?php echo $version->version_number;  ?></h2>
        <span><?php $date=date_create($version->release_datetime);
echo date_format($date,"d M, Y");?></span>
        <?php 
        foreach ($version->feature as $j_key => $feature_post) { 
        $img_array = array();
        foreach($feature_post->images as $feature_img){
          array_push($img_array,$feature_img->url);
        }
        $is_old_version = false;

         $title = $feature_post->title;
         $is_new = $feature_post->is_new;
         $is_pro = $feature_post->is_pro;
         $doc_config_link=$feature_post->config_doc_link;
         $is_pro_user = false;
         $is_latest = true;
         $post_description = $feature_post->description; 
         include 'single-post-feature.php'; 
        }
        ?>
    </div>
    <?php } ?>
    <?php } ?>


    <div class="wrld-post-request">
        <div class="wrld-post-request-text">
            <h2><?php echo __( 'Request a report', 'learndash-reports-by-wisdmlabs' ); ?></h2>
            <p><?php echo __( 'Suggest us any other report/graph that are critical for you.', 'learndash-reports-by-wisdmlabs' ); ?>
            </p>
        </div>
        <div class="wrld-post-request-button">
            <a href="https://form.typeform.com/to/Fqw2CZoC" target="_blank"><?php echo __( 'Fill the form' , 'learndash-reports-by-wisdmlabs' ); ?></a>
        </div>
    </div>
    <div class="clear-height-fix">
        
    </div>
    <div class="wrld-post-previous post-section-heading">
    <h2><?php echo __( 'Previous Versions ', 'learndash-reports-by-wisdmlabs' ); ?></h2><br/>
        <h2 class="wrld-version"><?php echo __( 'Version ', 'learndash-reports-by-wisdmlabs' ); ?>1.8.0</h2>
        <span>21 Sept, 2023</span>
        <?php 
         $img_array = array();
         array_push($img_array,WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/time-tracking-revamp.png', WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/time-tracking-revamp-2.png');
         $title = __( 'Time Spent on  Course - Revamped Graphical Report', 'learndash-reports-by-wisdmlabs' );
         $is_new = true;
         $is_old_version = false;
         $is_pro = true;
         $doc_config_link="https://wisdmlabs.com/docs/article/wisdm-learndash-reports/lr-getting-started/time-tracking-module-settings/";
         $is_pro_user = false;
         $version_data = [];
         $is_latest = true;
         
         $post_description =  __( 'The updated Time Spent on Course reports offer improved visualizations to easily understand how the learners are spending time in a course. It includes filters for courses, categories, and groups, with an option to focus on completed or in-progress learners. There are two bar charts: one for Time Spent by all learner in course and one for the time spent by an individual learner in all the enrolled courses, with multiple time period views (minutes, hours, days).With the graphical reporting block an Admin/ Group leader / Instructor can easily understand the following', 'learndash-reports-by-wisdmlabs' ) . '<br/>
         <ol class="wrld-desc-css wrld-post-desc">
         <li>'. __( 'The average time spent completing a course and the average time spent by learners currently enrolled in the course.', 'learndash-reports-by-wisdmlabs' ).'</li>
         <li>'. __( 'Easily understand how much time it takes to complete a course.', 'learndash-reports-by-wisdmlabs' ).'
          <li>'. __( 'Identify learners who spend very little time on the course or take too long to complete it.', 'learndash-reports-by-wisdmlabs' ).'</li>
          <li>
           '. __( 'Visually grasp an overview of the time spent by all learners compared to the average time spent.', 'learndash-reports-by-wisdmlabs' ).'</li>
           <li>
           '. __( 'Figure out in which courses a learner is spending most of the time and which courses by being neglected.', 'learndash-reports-by-wisdmlabs' ).'</li>
          </ol>
         '; 
        include 'single-post-feature.php'; 
         ?>
    </div>
    <div class="wrld-post-previous post-section-heading">
        <h2 class="wrld-version"><?php echo __( 'Version ', 'learndash-reports-by-wisdmlabs' ); ?>1.8.0</h2>
        <span>21 Sept, 2023</span>
        <?php
        $img_array = array();
        
         $title = __( 'RTL compatibility and Multilingual compatibility with WPML', 'learndash-reports-by-wisdmlabs' );
         $is_new = true;
         $is_old_version = false;
         $is_pro = false;
         $version_data = [];
         $is_pro_user = false;
         $is_latest = true;
         $doc_config_link = '';
         $post_description = sprintf(__( 'Wisdm Reports Dashboard is now fully compatible with RTL (Right-to-Left) languages, ensuring a seamless experience for users who prefer and need RTL language support. Enjoy a more inclusive and accessible experience with Wisdm Reports Dashboard. Along with the we have made the Reports Dashboard compatible with %s WPML Plugin %s making it easier to provide Multilingual support for the dashboard.', 'learndash-reports-by-wisdmlabs' ), '<a href="https://wpml.org/">', '</a>');
         include 'single-post-feature.php'; 
         ?>
    </div>

    <div class="wrld-post-previous post-section-heading">
        <h2 class="wrld-version"><?php echo __( 'Version ', 'learndash-reports-by-wisdmlabs' ); ?>1.7.0</h2>
        <span>27 Apr, 2023</span>
        <?php
        $img_array = array();
        array_push($img_array,WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/course-completion.png',WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/course-progress.png', WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/ccr-modal.png', WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/cp-modal.png');
         $title = __( 'Course Completion Rate and Course Progress Graphs', 'learndash-reports-by-wisdmlabs' );
         $is_new = false;
         $is_old_version = true;
         $is_pro = false;
         $version_data = [];
         $is_pro_user = false;
         $is_latest = false;
         $post_description = __( 'The revamped Course Completion Rate block will allow group leaders/admins to understand how many learners have completed the course(s).
The Course Progress Graph helps understand how much progress has been made by all learners in a course and a list of learners who are falling behind in a particular course. It also allows group leaders/instructors to understand how much progress has been made by a learner in enrolled courses.
Additionally, these reports are available as Gutenberg blocks and can be used anywhere on the site.', 'learndash-reports-by-wisdmlabs' );
         include 'single-post-feature.php'; 
         ?>
    </div>

    <div class="wrld-post-previous post-section-heading">
        <h2 class="wrld-version"><?php echo __( 'Version ', 'learndash-reports-by-wisdmlabs' ); ?>1.6.1</h2>
        <span>28 Mar, 2023</span>
        <?php
        $img_array = array();
        
         $title = __( 'Performance Optimization', 'learndash-reports-by-wisdmlabs' );
         $is_new = false;
         $is_old_version = true;
         $is_pro = false;
         $version_data = [];
         $is_pro_user = false;
         $is_latest = false;
         $post_description = __( 'Get blazingly fast reports with our improved plugin in this latest update. Now, the time taken to load the Wisdm Reports Dashboard, or for that matter, any of the Wisdm Reports blocks for tracking learner performance will be 6x faster, saving you close to 7 minutes on an average! Duly tested on sites and systems with a wide array of configurations and specifications.', 'learndash-reports-by-wisdmlabs' );
         include 'single-post-feature.php'; 
         ?>
    </div>

    <div class="wrld-post-previous post-section-heading wrld-version-margin">
        <h2 class="wrld-version"><?php echo __( 'Version ', 'learndash-reports-by-wisdmlabs' ); ?>1.6.0</h2>
        <span>12 Jan, 2023</span>
        <?php
        $img_array = array();
        array_push($img_array,WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/img_160p.png');
         $title = __( 'Student Quiz Report Gutenberg block and My Quiz Result Page', 'learndash-reports-by-wisdmlabs' );
         $is_new = false;
         $is_old_version = true;
         $is_pro = true;
         $doc_config_link="https://wisdmlabs.com/docs/article/wisdm-learndash-reports/lr-getting-started/how-to-set-up-the-student-quiz-results-page/";
         $is_pro_user = false;
         $version_data = [];
         $is_latest = false;
         $post_description = __( 'My Quiz Result page allows students to easily get a detailed breakdown of their past quiz attempts so that they can analyze and improve their performance accordingly. Additionally, these reports are available as Student Quiz Report Gutenberg blocks and can be used anywhere on the site', 'learndash-reports-by-wisdmlabs' );
         include 'single-post-feature.php'; 
         ?>
    </div>

    <div class="wrld-post-previous post-section-heading wrld-version-margin">
        <h2 class="wrld-version"><?php echo __( 'Version ', 'learndash-reports-by-wisdmlabs' ); ?> 1.5.0 </h2>
        <span>14 Dec, 2022</span>
        <?php 
        $img_array = array();
        array_push($img_array,WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/img_150p.png',WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/img2_150p.png');
         $title = __( 'Learner Activity Log & Inactive Users list block', 'learndash-reports-by-wisdmlabs' );
         $is_new = false;
         $is_old_version = true;
         $is_pro = true;
         $doc_config_link="https://wisdmlabs.com/docs/article/wisdm-learndash-reports/features/learner-activity-blocks/
         ";
         $is_pro_user = false;
         $version_data = [];
         $is_latest = false;
         
         $post_description =  __( 'View the latest activity on the website related to the Courses and Course Progression, Quiz attempts by the learner/s using the Learner Activity Log.
         Inactive Users list will show which users are inactive during a specific time-frame and when they were last active', 'learndash-reports-by-wisdmlabs' );
         include 'single-post-feature.php'; 
         ?>
    </div>

    <div class="wrld-post-previous post-section-heading wrld-version-margin">
    
        <h2 class="wrld-version"><?php echo __( 'Version ', 'learndash-reports-by-wisdmlabs' ); ?> 1.4.1</h2>
        <span>08 Nov, 2022</span>
        <?php 
         $img_array = array();
         array_push($img_array,WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/img_141f.png');
         $title = __( 'Bulk Export Quiz attempts from Wordpress backend for Admins/Group Leaders', 'learndash-reports-by-wisdmlabs' );
         $is_new = false;
         $is_old_version = true;
         $is_pro = true;
         $doc_config_link="";
         $is_pro_user = false;
         $version_data = [];
         $is_latest = false;
         $post_description =  __( 'Admins can now easily download the Quiz attempts in bulk from the Quiz statistics area on the WP dashboard (WP dashboard > Learndash LMS > Quizzes > Quiz > Statistics )', 'learndash-reports-by-wisdmlabs' );
         include 'single-post-feature.php'; 
         ?>
    </div>

    <div class="wrld-post-previous post-section-heading wrld-version-margin">
        <h2 class="wrld-version"><?php echo __( 'Version ', 'learndash-reports-by-wisdmlabs' ); ?> 1.4.0</h2>
        <span>27 Sept, 2022</span>
        <?php 
         $img_array = array();
         array_push($img_array,WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/img3_140p.png');
         $title = __( 'Bulk Export Quiz attempts in Frontend Report', 'learndash-reports-by-wisdmlabs' );
         $is_new = false;
         $is_old_version = true;
         $is_pro = true;
         $doc_config_link="";
         $is_pro_user = false;
         $version_data = [];
         $is_latest = false;
         $post_description = __( "We have introduced the Quiz attempts bulk export for users to analyze the learners answers for a quiz or the quiz results of all the learners for a Quiz", 'learndash-reports-by-wisdmlabs' );
         include 'single-post-feature.php'; 
         ?>
    </div>

    <div class="wrld-post-previous post-section-heading wrld-version-margin">
        
        <?php 
         $img_array = array();
         array_push($img_array,WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/img_140f_gf.png',);
         $is_new = false;
         $is_old_version = true;
         $is_pro = true;
         $doc_config_link="";
         $is_pro_user = false;
         $version_data = [];
         $is_latest = false;
         $post_description = __( 'We have introduced a Group Filter for the admin on the Reports Dashboard in the "Filters" Gutenberg Block so that admin can view the reports group wise.', 'learndash-reports-by-wisdmlabs' ); 
         include 'single-post-feature.php'; 
         ?>
    </div>

    <div class="wrld-post-previous post-section-heading wrld-version-margin">
       
        <?php 
         $img_array = array();
         array_push($img_array,WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/img_140p.png',WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/img2_140p.png');
         $title = __( 'Time Tracking Module', 'learndash-reports-by-wisdmlabs' );
         $is_new = false;
         $is_old_version = true;
         $is_pro = true;
         $doc_config_link="https://wisdmlabs.com/docs/article/wisdm-learndash-reports/lr-getting-started/time-tracking-module-settings/";
         $is_pro_user = false;
         $version_data = [];
         $is_latest = false;
         
         $post_description =  __( 'This setting enables the user to track the “actual” time spent by learners by discarding the following time duration from the total time the learner spends on the courses:', 'learndash-reports-by-wisdmlabs' ) . '<br/>
         <ul class="wrld-desc-css wrld-post-desc">
         <li>'. __( 'The learner - “opens another tab and leaves the current tab', 'learndash-reports-by-wisdmlabs' ).'</li>
         <li>'. __( 'Or the learner is “Idle” (Idle Time) on the current tab such as:', 'learndash-reports-by-wisdmlabs' ).'
       <br/>
         <ul>
          <li>'. __( 'the learner does not move the cursor', 'learndash-reports-by-wisdmlabs' ).'</li>
          <li>
           '. __( 'the learner does not perform any keyboard strokes', 'learndash-reports-by-wisdmlabs' ).'</li>
          </ul>
         </li>

         </ul>
         
          
         
         '; 
         include 'single-post-feature.php'; 
         ?>
    </div>

    
</div>
<div class="wrld-changelog-container">
        <a href="https://wisdmlabs.com/docs/article/wisdm-learndash-reports/changelog-reportings/changelog-reporting/" target="_blank"><?php echo __( 'Changelog', 'learndash-reports-by-wisdmlabs' );?></a>
</div>


<!-- The Modal -->
<div id="wrld-modal-whatsnew" class="wrld-modal">

  <!-- Modal content -->
  
  <div class="wrld-modal-content">
    <div class="wrld-modal-img-div">
        <div class="close-btn-modal"><span id="wrld-modal-close" class="wrld-close">&times;</span></div>
		<span><button class="wrld-modal-slider-btn-color wrld-modal-slider-btn-left">&#10094;</button></span>
		<span><button class="wrld-modal-slider-btn-color wrld-modal-slider-btn-padding wrld-modal-slider-btn-left">&nbsp;</button></span>
		<span><img class="wrld-modal-img" id="wrld-modal-img" src="" alt="Post Image" /></span>
        <span><button  class="wrld-modal-slider-btn-color wrld-modal-slider-btn-right wrld-modal-slider-btn-nxt">&#10095;</button></span>
    </div>
  </div>

</div>
<script>
var slideIndex = 1;
showDivs(slideIndex,1);

function plusDivs(ele,n) {
  var c_ind= jQuery(ele).attr('data-slideindex');
  var c_index = parseInt(c_ind) + n; 
  jQuery(ele).attr('data-slideindex',c_index);
  showDivs(c_index , ele);
}

function showDivs(n,ele) {

   if(ele == 1){
    var x =document.getElementsByClassName("mySlides");
   }
   else{
    const parent = ele.parentNode;
    var x = [].slice.call(parent.children).filter(function (child) {
    
    return child.tagName.toLowerCase() !== "button";
});
   }
   if(ele == 1){
    var i;

if (n > x.length) {
    slideIndex = 1
}
if (n < 1) {
    slideIndex = x.length
}
for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";
}
x[slideIndex - 1].style.display = "block";
   }else{
  var i;
   
  if (n > x.length) {
    jQuery(ele).attr('data-slideindex',1);}
  if (n < 1) {jQuery(ele).attr('data-slideindex',x.length);}

  var myindex= jQuery(ele).attr('data-slideindex'); 
  myindex = parseInt(myindex);
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  x[myindex-1].style.display = "block"; 
} 
}

jQuery(document).ready(function() {
       jQuery('.wrld-slider-btn-left').each(function(i, obj) {
        obj.click();
           console.log(obj);
        });
    });


function changeSlider(){
    jQuery('.wrld-slider-btn-nxt').each(function(i, obj) {
        jQuery(obj).trigger('click');
            
    });
  
};

setInterval(() => {
   changeSlider();
}, 2000);



jQuery(".wrld-slider-container").hover(function(){
    jQuery(this).find('.wrld-slider-btn-right').removeClass("wrld-slider-btn-nxt");
  }, function(){
    jQuery(this).find('.wrld-slider-btn-right').addClass("wrld-slider-btn-nxt");
});

//whats new tab modal script
const body = document.querySelector("body");

function wrldShowmodal(ele,parent) {
	let index = 0;
	let $currentSelect = parent;
	console.log($currentSelect);
	const hasWrldSingleHideClass = Array.from(parent.children).some(child => child.classList.contains('wrld-single-hide'));

	if (hasWrldSingleHideClass) {
		//Hide buttons
		jQuery('.wrld-modal-slider-btn-color').css('display', 'none');
		jQuery('.wrld-modal-slider-btn-padding').css('display', 'inline-block');

	} else {
		//show buttons
		jQuery('.wrld-modal-slider-btn-color').css('display', 'inline-block');
		jQuery('.wrld-modal-slider-btn-padding').css('display', 'none');
	}
    jQuery('#wrld-modal-whatsnew').css('display', 'block');

    jQuery('#wrld-modal-img').attr('src', ele.src);
    body.style.overflow = "hidden";

	const images = parent.querySelectorAll('img');
	jQuery('.wrld-modal-slider-btn-right').on('click', function () {
		const modalImg = document.querySelector('.wrld-modal-img');
		index++;
		if (images.length == index) {
			index = 0;
		}
		modalImg.src = images[index].src;
	});

	jQuery('.wrld-modal-slider-btn-left').on('click', function () {
		const modalImg = document.querySelector('.wrld-modal-img');
		index--;
		if (0 > index) {
			index = images.length - 1;
		}
		modalImg.src = images[index].src;
	});

}

      jQuery('#wrld-modal-close').on('click',function(){
        jQuery('#wrld-modal-whatsnew').css('display','none');
        body.style.overflow = "auto";
     });

	 jQuery('.wrld-modal').on('click', function (e) {
		if (!jQuery(e.target).hasClass('wrld-modal-img') && !jQuery(e.target).hasClass('wrld-modal-slider-btn-color')) {
			jQuery('#wrld-modal-whatsnew').css('display', 'none');
			body.style.overflow = "auto";
		}
	});

//sroll logic
var targetOffset = 1300;
jQuery( document ).ready(function() {
  targetOffset = jQuery(".wrld-post-request").offset().top;
});


var $w = jQuery(window).scroll(function(){
    console.log(targetOffset);
    console.log($w.scrollTop());
    if ( (targetOffset - $w.scrollTop() ) < 0 ) {   
        //alert("gone");
       console.log("hidden");
       jQuery(".wrld-post-request").addClass("wrld-fixed-req");
    } else {
      jQuery(".wrld-post-request").removeClass("wrld-fixed-req");
    }
});
</script>