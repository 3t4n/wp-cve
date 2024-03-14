<?php
//post page
?>
<div class="wrld-gutenberg-post-container">
    <button class="wrld-accordion"><span class="accordiantext" data-metaname="wrld_gutenberg_block_course_report" data-beacon = "<?php echo get_option( 'wrld_gutenberg_block_course_report', false ) ? 1 : 0 ; ?>"><?php esc_html_e( 'Course Reports', 'learndash-reports-by-wisdmlabs' ); ?> <?php echo get_option( 'wrld_gutenberg_block_course_report', false ) ? '':'<span class="gutenberg-beacon-icon wrld-blink"> </span>'; ?></span>

    </button>
    <div class="wrld-panel">
        <!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Course Progress', 'learndash-reports-by-wisdmlabs' ); ?></h2>
             
        
            </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'The Course Progress Block will allow admins/group leaders/instructors to know how much progress has been made by all learners in a course(s). Switch to the local course specific tab to access the report. Applying local group and course filters will show how many and which learners are falling behind in a particular course.', 'learndash-reports-by-wisdmlabs' ); ?>
                        </li>
                        <li><?php esc_html_e( "Edit page → Click on '+' to expand the left side panel → Go to Blocks tab → Search Course Progress.", 'learndash-reports-by-wisdmlabs' ); ?>
                        </li>
                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/course-progress.png'; ?>"
                        style="width:100%">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/cp-modal.png'; ?>"
                        style="width:100%">

                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->
        <!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Course list table', 'learndash-reports-by-wisdmlabs' ); ?></h2>
             
				
            </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'This report shows the list of courses and accordingly the progression rates, learners, time
                            spent when only the date filters are applied.', 'learndash-reports-by-wisdmlabs' ); ?>
                        </li>
                        <li><?php esc_html_e( "Edit page → Click on '+' to expand the left side panel → Go to Blocks tab → Search Course list
                            table", 'learndash-reports-by-wisdmlabs' ); ?>
                        </li>
                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/cr-clt1.png'; ?>"
                        style="width:100%">
                   


                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left wrld-single-hide"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt wrld-single-hide"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->
        <!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Course completion rate', 'learndash-reports-by-wisdmlabs' ); ?></h2>
            </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'Using the Course Completion Rate block, users will be able to visualize the percentage of learners who have completed a course. Group leaders will be able to see the course completion rate data for their own group using the local group filter. Admins have the flexibility to filter by categories.', 'learndash-reports-by-wisdmlabs' ); ?></li>
                        <li><?php esc_html_e( "Edit page → Click on '+' to expand the left side panel → Go to Blocks tab → Search
                            Course completion rate", 'learndash-reports-by-wisdmlabs' ); ?> 
                        </li>

                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/course-completion.png'; ?>"
                        style="width:100%">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/ccr-modal.png'; ?>"
                        style="width:100%">

                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->
        <!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Time spent on a course', 'learndash-reports-by-wisdmlabs' ); ?></h2>
                </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'The updated Time Spent on Course reports offer improved visualizations to easily understand how the learners are spending time in a course. It includes filters for courses, categories, and groups, with an option to focus on completed or in-progress learners. There are two bar charts: one for Time Spent by all learner in course and one for the time spent by an individual learner in all the enrolled courses, with multiple time period views (minutes, hours, days).With the graphical reporting block an Admin/ Group leader / Instructor can easily understand the following', 'learndash-reports-by-wisdmlabs' ); ?></li>
                        <li><?php esc_html_e( 'The average time spent completing a course and the average time spent by learners currently enrolled in the course.', 'learndash-reports-by-wisdmlabs' ); ?></li>
                        <li><?php esc_html_e( 'Easily understand how much time it takes to complete a course.', 'learndash-reports-by-wisdmlabs' ); ?></li>
                        <li><?php esc_html_e( 'Identify learners who spend very little time on the course or take too long to complete it.', 'learndash-reports-by-wisdmlabs' ); ?></li>
                        <li><?php esc_html_e( 'Visually grasp an overview of the time spent by all learners compared to the average time spent.', 'learndash-reports-by-wisdmlabs' ); ?></li>
                        <li><?php esc_html_e( 'Figure out in which courses a learner is spending most of the time and which courses by being neglected.', 'learndash-reports-by-wisdmlabs' ); ?></li>
                        <li><?php esc_html_e( "Edit page → Click on '+' to expand the left side panel → Go to Blocks tab → Search Time
                            spent on a course", 'learndash-reports-by-wisdmlabs' ); ?> 
                        </li>
                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/time-tracking-revamp.png'; ?>"
                        style="width:100%">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/time-tracking-revamp-2.png'; ?>"
                        style="width:100%">
                   


                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->

        <!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Revenue from courses', 'learndash-reports-by-wisdmlabs' ); ?></h2>
                 </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'Using the Revenue from courses block and the Date Filter block, this report displays the
                            Revenue earned Course-wise for the selected date range.', 'learndash-reports-by-wisdmlabs' ); ?></li>
                        <li><?php esc_html_e( "Edit page → Click on '+' to expand the left side panel → Go to Blocks tab → Search Revenue
                            from Courses.", 'learndash-reports-by-wisdmlabs' ); ?></li>
                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/cr-rfc1.png'; ?>"
                        style="width:100%">
                   

                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left wrld-single-hide"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt wrld-single-hide"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->
    </div>
</div>

<div class="wrld-gutenberg-post-container">
    <button class="wrld-accordion">
    <span class="accordiantext" data-metaname="wrld_gutenberg_block_quiz_report" data-beacon = "<?php echo get_option( 'wrld_gutenberg_block_quiz_report', false ) ? 1 : 0 ; ?>"><?php esc_html_e( 'Quiz Reports', 'learndash-reports-by-wisdmlabs' ); ?> <?php echo get_option( 'wrld_gutenberg_block_quiz_report', false ) ? '':'<span class="gutenberg-beacon-icon wrld-blink"> </span>'; ?></span>
    </button>
    <div class="wrld-panel">
        <!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Quiz completion rate', 'learndash-reports-by-wisdmlabs' ); ?></h2>
             </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'This Gutenberg block shows a bar chart listing all the courses with respect to their %
                            completion rate using the Quiz completion rate block and the Date filter block.', 'learndash-reports-by-wisdmlabs' ); ?></li>
                        <li><?php esc_html_e( 'Additionally, on applying course filter using the Reports Filter block, a pie chart is shown
                            with the percentage completion and non- completion of quizzes for a particular course.', 'learndash-reports-by-wisdmlabs' ); ?>
                        </li>
                        <li><?php esc_html_e( "Edit page → Click on '+' to expand the left side panel → Go to Blocks tab → Search Quiz
                            completion rate", 'learndash-reports-by-wisdmlabs' ); ?> 
                        </li>
                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/qr-qcr1.png'; ?>"
                        style="width:100%">
                   


                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left wrld-single-hide"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt wrld-single-hide"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->

        <!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Quiz completion time', 'learndash-reports-by-wisdmlabs' ); ?></h2>
               
            </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'Using the Quiz completion time block and the Date Filter block, this report displays the avg
                            time learners take to complete the quizzes in each course.', 'learndash-reports-by-wisdmlabs' ); ?></li>
                        <li><?php esc_html_e( 'Additionally, on applying the course filter using the Reports Filter Gutenberg block, the
                            bar graph shows the quiz completion time for all learners for that particular course.', 'learndash-reports-by-wisdmlabs' ); ?>
                        </li>
                        <li> <?php esc_html_e( "Edit page → Click on '+' to expand the left side panel → Go to Blocks tab → Search Quiz
                            completion time", 'learndash-reports-by-wisdmlabs' ); ?>
                        </li>
                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/qr-qct.png'; ?>"
                        style="width:100%">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/qr-qct2.png'; ?>"
                        style="width:100%">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/qr-qct3.png'; ?>"
                        style="width:100%">
                    


                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->

        <!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2> <?php esc_html_e( 'Avg quiz attempts', 'learndash-reports-by-wisdmlabs' ); ?></h2>
            </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'Using the Avg Quiz attempts block, date filter block and the Reports Filter block, the
                            report shows the average attempts made on the quizzes by learners, shows the count of total
                            attempts made on the quizzes. Course selection from the Reports Filter block is required to
                            view this graph.', 'learndash-reports-by-wisdmlabs' ); ?></li>
                        <li><?php esc_html_e( "Edit page → Click on '+' to expand the left side panel → Go to Blocks tab → Search
                            Average Quiz attempts", 'learndash-reports-by-wisdmlabs' ); ?> </li>
                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/aq-a1.png'; ?>"
                        style="width:100%">
                   


                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left wrld-single-hide"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt wrld-single-hide"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->

        <!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Quiz Report View', 'learndash-reports-by-wisdmlabs' ); ?></h2>
                <div class="wrld-new-pro">
                <?php echo '<div class="wrld-feature-post-pro-tag">'._x( 'PRO', 'learndash-reports-by-wisdmlabs' ).'</div>'; ?>
</div>
            </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'The first view allows the user to see all the quiz attempts using the Quiz Report Block -
                            Select Default Quiz report view and the Date Filter block.', 'learndash-reports-by-wisdmlabs' ); ?></li>
                        <li><?php esc_html_e( 'The second view allows the user to customize the Quiz Results and analyze them in a detailed
                            view. Need to select the appropriate filters and the fields (by clicking on the Customize
                            Report Button) and click on Apply Filters to display the reports. USe the Quiz Reports Block
                            and select the customized quiz view and the Date filter block.', 'learndash-reports-by-wisdmlabs' ); ?>
                        </li>
                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/qr-qrv1.png'; ?>"
                        style="width:100%">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/qr-qrv2.png'; ?>"
                        style="width:100%">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/qr-qrv3.png'; ?>"
                        style="width:100%">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/qr-qrv4.png'; ?>"
                        style="width:100%">


                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->
    </div>
</div>


<div class="wrld-gutenberg-post-container">
    <button class="wrld-accordion">
    <span class="accordiantext" data-metaname="wrld_gutenberg_block_learner_report" data-beacon = "<?php echo get_option( 'wrld_gutenberg_block_learner_report', false ) ? 1 : 0 ; ?>"><?php esc_html_e( 'Learner Reports', 'learndash-reports-by-wisdmlabs' ); ?> <?php echo get_option( 'wrld_gutenberg_block_learner_report', false ) ? '':'<span class="gutenberg-beacon-icon wrld-blink"> </span>'; ?></span>

    </button>
    <div class="wrld-panel">
        <!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Course Progress', 'learndash-reports-by-wisdmlabs' ); ?></h2>
            </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'The Course Progress Block will allow group leaders/instructors to understand the progress of enrolled courses for a particular learner. Switch to the local learner tab and apply a learner search filter to access the learner specific course progress report.', 'learndash-reports-by-wisdmlabs' ); ?></li>
                        <li><?php esc_html_e( "Edit page → Click on '+' to expand the left side panel → Go to Blocks tab → Search
                            Course Progress", 'learndash-reports-by-wisdmlabs' ); ?> 
                        </li>
                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/course-progress.png'; ?>"
                        style="width:100%">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/cp-modal.png'; ?>"
                        style="width:100%">
                   


                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="2" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->
        
        <!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Learner pass/fail rate', 'learndash-reports-by-wisdmlabs' ); ?></h2>
            </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'Using the Learner pass/fail rate block, Date filter block, a bar chart containing the
                            percentage of pass students for the courses will be displayed.', 'learndash-reports-by-wisdmlabs' ); ?></li>
                        <li><?php esc_html_e( 'Using the Learner pass/fail rate block, Date filter block, Reports Filter block, a pie chart
                            is shown containing the ratio of the pass/fail percentage for the selected course.', 'learndash-reports-by-wisdmlabs' ); ?> </li>
                        <li><?php esc_html_e( "Edit page → Click on '+' to expand the left side panel → Go to Blocks tab → Search
                            Learner pass/fail rate", 'learndash-reports-by-wisdmlabs' ); ?> 
                        </li>
                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/lr-lpr1.png'; ?>"
                        style="width:100%">
                   


                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left wrld-single-hide"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt wrld-single-hide"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->
        <!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Course List Table', 'learndash-reports-by-wisdmlabs' ); ?></h2>
            </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'Using the Course list block, Date Filter block, Reports Filter block, a tabular report is
                            displayed that shows the course data for the list of users who are enrolled in the
                            course.Select course from the Report Filter block.', 'learndash-reports-by-wisdmlabs' ); ?></li>

                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/lr-clt1.png'; ?>"
                        style="width:100%">
                   


                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left wrld-single-hide"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt wrld-single-hide"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->
        <!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Time spent on a course', 'learndash-reports-by-wisdmlabs' ); ?></h2>
                <div class="wrld-new-pro">
                <?php echo '<div class="wrld-feature-post-pro-tag">'._x( 'PRO', 'learndash-reports-by-wisdmlabs' ).'</div>'; ?>
</div>
            </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'Using the time spent on a course block and the Reports Filter block, we get a pie graph that
                            shows the percentage of time spent in the courses enrolled by a particular learner. We need
                            to select the learner using the Reports Filter block. ', 'learndash-reports-by-wisdmlabs' ); ?></li>
                        <li> <?php esc_html_e( "Edit page → Click on '+' to expand the left side panel → Go to Blocks tab → Search Time
                            spent on a course", 'learndash-reports-by-wisdmlabs' ); ?></li>
                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/lr-tsc1.png'; ?>"
                        style="width:100%">
                   


                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left wrld-single-hide"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt wrld-single-hide"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->
        <!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Student Table', 'learndash-reports-by-wisdmlabs' ); ?></h2>
                <div class="wrld-new-pro">
                <?php echo '<div class="wrld-feature-post-pro-tag">'._x( 'PRO', 'learndash-reports-by-wisdmlabs' ).'</div>'; ?>
            </div>
            </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'Students can view their past quiz attempts and responses and analyze from the detailed quiz
                            results using the Student table block and the Reports Filter block.', 'learndash-reports-by-wisdmlabs' ); ?>
                        </li>
                        <li> <?php esc_html_e( "Edit page → Click on '+' to expand the left side panel → Go to Blocks tab → Search
                            Student Table", 'learndash-reports-by-wisdmlabs' ); ?>
                        </li>
                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/lr-st1.png'; ?>"
                        style="width:100%">
                  


                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left wrld-single-hide"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt wrld-single-hide"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->
        <!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Student Profile', 'learndash-reports-by-wisdmlabs' ); ?></h2>
                <div class="wrld-new-pro">
                <?php echo '<div class="wrld-feature-post-pro-tag">'._x( 'PRO', 'learndash-reports-by-wisdmlabs' ).'</div>'; ?>
</div>
            </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'This block is used in conjunction with the Student Table block.', 'learndash-reports-by-wisdmlabs' ); ?></li>
                        <li><?php esc_html_e( "Edit page → Click on '+' to expand the left side panel → Go to Blocks tab → Search
                            Student Profile", 'learndash-reports-by-wisdmlabs' ); ?> 
                        </li>
                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/lr-sp1.png'; ?>"
                        style="width:100%">
                    


                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left wrld-single-hide"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt wrld-single-hide"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->
    </div>
</div>


<div class="wrld-gutenberg-post-container">
    <button class="wrld-accordion">
    <span class="accordiantext" data-metaname="wrld_gutenberg_block_activity_report" data-beacon = "<?php echo get_option( 'wrld_gutenberg_block_activity_report', false ) ? 1 : 0 ; ?>"><?php esc_html_e( 'Activity Reports', 'learndash-reports-by-wisdmlabs' ); ?> <?php echo get_option( 'wrld_gutenberg_block_activity_report', false ) ? '':'<span class="gutenberg-beacon-icon wrld-blink"> </span>'; ?></span>
    </button>
    <div class="wrld-panel">
        <!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Learner Activity Log', 'learndash-reports-by-wisdmlabs' ); ?></h2>
                <div class="wrld-new-pro">
                <?php echo '<div class="wrld-feature-post-pro-tag">'._x( 'PRO', 'learndash-reports-by-wisdmlabs' ).'</div>'; ?>
</div>
            </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'This tabular report shows the latest activity of all learners for a selected date period
                            using the Learner activity log block.', 'learndash-reports-by-wisdmlabs' ); ?></li>
                        <li><?php esc_html_e( 'We can also specify a defined date period to monitor activity between 2 custom dates using
                            the embedded Date filter inside this block.', 'learndash-reports-by-wisdmlabs' ); ?>
                        </li>
                        <li> <?php esc_html_e( "Edit page → Click on '+' to expand the left side panel → Go to Blocks tab → Search
                            Learner Activity Log", 'learndash-reports-by-wisdmlabs' ); ?>
                        </li>
                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/ar-la1.png'; ?>"
                        style="width:100%">
                  

                    <button  data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left wrld-single-hide"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt wrld-single-hide"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->
        <!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Inactive Users List', 'learndash-reports-by-wisdmlabs' ); ?></h2>
                <div class="wrld-new-pro">
                <?php echo '<div class="wrld-feature-post-pro-tag">'._x( 'PRO', 'learndash-reports-by-wisdmlabs' ).'</div>'; ?>
</div>
            </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'This report shows a tabular list of inactive learners for all courses in a defined date
                            period.', 'learndash-reports-by-wisdmlabs' ); ?></li>
                        <li><?php esc_html_e( 'It also shows the list of inactive users for a particular group and course if we select
                            those filters embedded inside the block.', 'learndash-reports-by-wisdmlabs' ); ?>
                        </li>
                        <li> <?php esc_html_e( "Edit page → Click on '+' to expand the left side panel → Go to Blocks tab → Search
                            Inactive users list.", 'learndash-reports-by-wisdmlabs' ); ?>
                        </li>
                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/ar-iul1.png'; ?>"
                        style="width:100%">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/ar-iul2.png'; ?>"
                        style="width:100%">
                  


                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->
    </div>
</div>

<div class="wrld-gutenberg-post-container">
    <button class="wrld-accordion">
    <span class="accordiantext" data-metaname="wrld_gutenberg_block_quick_stats" data-beacon = "<?php echo get_option( 'wrld_gutenberg_block_quick_stats', false ) ? 1 : 0 ; ?>"><?php esc_html_e( 'Quick Stats', 'learndash-reports-by-wisdmlabs' ); ?> <?php echo get_option( 'wrld_gutenberg_block_quick_stats', false ) ? '':'<span class="gutenberg-beacon-icon wrld-blink"> </span>'; ?></span>
    </button>
    <div class="wrld-panel">
        <!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Total Revenue Earned', 'learndash-reports-by-wisdmlabs' ); ?></h2>
                </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'This block along with the date filter block gives a snapshot of the total revenue earned
                            from all the courses for a particular time period.', 'learndash-reports-by-wisdmlabs' ); ?>
                            
                        </li>
                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/qs-tr.png'; ?>"
                        style="width:100%">


                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left wrld-single-hide"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt wrld-single-hide"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->

		<!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Total Courses', 'learndash-reports-by-wisdmlabs' ); ?></h2>
                
            </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'This block along with the date filter block  gives a snapshot of the total number of courses that are live for a particular time period', 'learndash-reports-by-wisdmlabs' ); ?>
						
                        </li>
                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/qs3.png'; ?>"
                        style="width:100%">


                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left wrld-single-hide"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt wrld-single-hide"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->

		<!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Total Learners', 'learndash-reports-by-wisdmlabs' ); ?></h2>
                
            </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'This block along with the date filter block  gives a snapshot of the total number of learners enrolled in all courses for a particular time period.', 'learndash-reports-by-wisdmlabs' ); ?>
						
                        </li>
                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/qs2.png'; ?>"
                        style="width:100%">
                   
                    <button wrld-single-hide data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left wrld-single-hide"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button wrld-single-hide data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt wrld-single-hide"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->

		<!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Pending Assignments', 'learndash-reports-by-wisdmlabs' ); ?></h2>
                <div class="wrld-new-pro">
                <?php echo '<div class="wrld-feature-post-pro-tag">'._x( 'PRO', 'learndash-reports-by-wisdmlabs' ).'</div>'; ?>
</div>
            </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'This block along with the date filter block  gives a snapshot of the number of pending assignments for all the courses for a particular time period.', 'learndash-reports-by-wisdmlabs' ); ?>
						
                        </li>
                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/qs1.png'; ?>"
                        style="width:100%">


                    <button  data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left wrld-single-hide"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt wrld-single-hide"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->

		<!-- post start -->
        <div class="wrld-feature-post">
            <div class="wrld-feature-post-header">
                <h2><?php esc_html_e( 'Daily Enrollments', 'learndash-reports-by-wisdmlabs' ); ?></h2>
             
            </div>
            <div class="wrld-feature-post-content">
                <div class="wrld-post-info">
                    <ul>
                        <li><?php esc_html_e( 'This Bar graph report displays the total number of daily learner enrollments for the selected date range.', 'learndash-reports-by-wisdmlabs' ); ?>
						
                        </li>
                    </ul>
                </div>
                <div class="wrld-post-images wrld-slider-container">
                    <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/qs-de1.png'; ?>"
                        style="width:100%">
                        <img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);"
                        src="<?php echo WRLD_REPORTS_SITE_URL.'/includes/admin/dashboard/assets/images/tabs/gutenberg/qs-de2.png'; ?>"
                        style="width:100%">
                  
                        

                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-left"
                        onclick="plusDivs(this,-1)">&#10094;</button>
                    <button data-slideindex="1" class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt"
                        onclick="plusDivs(this,1)">&#10095;</button>
                </div>
            </div>
        </div>

        <!-- post end -->
</div>

</div>
	<div class="wrld-post-request">
        <div class="wrld-post-request-text">
            <h2><?php esc_html_e( 'Request a report', 'learndash-reports-by-wisdmlabs' ); ?></h2>
            <p><?php esc_html_e( 'Suggest us any other report/graph that are critical for you.', 'learndash-reports-by-wisdmlabs' ); ?>
            </p>
        </div>
        <div class="wrld-post-request-button">
            <a href="https://form.typeform.com/to/Fqw2CZoC" target="_blank">Fill the form</a>
        </div>
    </div>
	

<!-- The Modal -->
<div id="wrld-modal-gutenberg" class="wrld-modal">

  <!-- Modal content -->
  
  <div class="wrld-modal-content">
    <div class="wrld-modal-img-div">
        <div class="close-btn-modal"><span id="wrld-modal-close" class="wrld-close">&times;</span></div>
		<span><button class="wrld-modal-slider-btn-color wrld-modal-slider-btn-left">&#10094;</button></span>
		<span><button class="wrld-modal-slider-btn-color wrld-modal-slider-btn-padding wrld-modal-slider-btn-left">&nbsp;</button></span>
		<span><img class="wrld-modal-img" id="wrld-modal-img" src="" alt="Post Image" /></span>
        <span><button class="wrld-modal-slider-btn-color wrld-modal-slider-btn-right wrld-modal-slider-btn-nxt"
                        >&#10095;</button></span>
    </div>
  </div>

</div>

