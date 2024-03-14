<?php
defined( 'ABSPATH' ) || exit;

get_header();
do_action( 'sakolawp_before_main_content' );
//global variables
global $wpdb;
$running_year = get_option('running_year');
$current_id = get_current_user_id();
$user_info = get_user_meta($current_id);

$first_name = $user_info["first_name"][0];
$last_name = $user_info["last_name"][0];

$user_name = $first_name .' '. $last_name;

if(empty($first_name)) {
	$user_info = get_userdata($current_id);
	$user_name = $user_info->display_name;
} ?>
<div class="dashboard-inner skwp-content-inner skwp-row skwp-clearfix">
	<div class="dash-item skwp-column skwp-column-1">
		<div class="welcome-wrap">
			<h1 class="skwp-hello"><?php echo esc_html__('Hello,', 'sakolawp'); ?> <?php echo esc_html($user_name); ?></h1>
			<span><?php echo esc_html__('Welcome Back', 'sakolawp'); ?></span>
			<div class="img-account-hello"></div>
		</div>
	</div>
	
	<div class="dash-item skwp-column skwp-column-1">
		<div class="news-wrap">
			<div class="skwp-dash-widget-title">
				<h3><?php echo esc_html__('News & Events', 'sakolawp'); ?></h3>
			</div>
			<div class="tab-nav-btn nav nav-tabs" id="nav-tab">
				<a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#skwp-home-news"><?php echo esc_html__('News', 'sakolawp'); ?></a>
				<a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#skwp-home-event"><?php echo esc_html__('Event', 'sakolawp'); ?></a>
			</div>
			<div class="loop-news skwp-dash-loop skwp-row skwp-clearfix tab-content" id="nav-tabContent">
				<div class="tab-pane fade show active" id="skwp-home-news">
				<?php 
				$sakolawp_news_args = array(
					'post_type'			=> 'sakolawp-news',
					'posts_per_page'	=> 3,
					'ignore_sticky_posts' => true,
				);
				$sakolawp_news = new WP_Query($sakolawp_news_args);
				if ($sakolawp_news->have_posts()) : ?>
					<?php while($sakolawp_news->have_posts()) : $sakolawp_news->the_post(); 
					$img_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');

					$category_name = array();
					$category_slugs = array();
					$category_terms = get_the_terms($post->ID, 'news-category');
					if(!empty($category_terms)){
						if(!is_wp_error( $category_terms )){
						$category_slugs = array();
							foreach($category_terms as $term){
								$category_name[] = $term->name;
								$category_slugs[] = $term->slug;
							}
					$porto_comas =  join( ", ", $category_name );
					$porto_space =  join( " ", $category_slugs );
						}
					}
					else {
						$porto_comas =  "";
						$porto_space =  "";
					} ?>
					<div class="skwp-column skwp-column-3">
						<div class="loop-wrapper">
							<div class="image-news">
								<?php if(has_post_thumbnail()) { ?>
									<?php the_post_thumbnail(); ?>
									<div class="sakolawp-overlay"></div>
								<?php } ?>
							 </div>

							<div class="wrapper-isi-loop">
								<?php if(!empty($porto_comas)) { ?>
								<div class="category-news skwp-news-meta">
									<?php echo esc_html($porto_comas); ?>
								</div>
								<?php } ?>

								<div class="date-excerpt skwp-news-meta">
									<span class="thedate"><?php echo get_the_date('d'); ?></span>
									<span class="month"><?php echo get_the_date('M'); ?></span>
									<span class="year"><?php echo get_the_date('Y'); ?></span>
								</div>
								
								<div class="title-news">
									<h4 class="title-name">
										<a href="<?php the_permalink(); ?>">
											<?php the_title(); ?>
										</a>
									</h4>
								</div>
								<div class="news-excerpt">
									<?php $excerpt = get_the_excerpt();
									$excerpt = substr($excerpt, 0, 30);
									$result = substr($excerpt, 0, strrpos($excerpt, ' '));
									echo esc_html($result); ?>
								</div>
								<div class="read-article">
									<a href="<?php the_permalink(); ?>">
										<?php esc_html_e('Read More', 'sakolawp'); ?>
									</a>
								</div>
								
							</div>
						</div>
					</div>
					<?php endwhile; ?>

					<?php else : ?>
						<?php esc_html_e('There is no post yet.', 'sakolawp' ); ?>
					<?php endif; ?>
				</div>

				<div class="tab-pane fade" id="skwp-home-event">
				<?php 
				$sakolawp_event_args = array(
					'post_type'			=> 'sakolawp-event',
					'posts_per_page'	=> 3,
					'ignore_sticky_posts' => true,
				);
				$sakolawp_event = new WP_Query($sakolawp_event_args);
				if ($sakolawp_event->have_posts()) : ?>
					
					<?php while($sakolawp_event->have_posts()) : $sakolawp_event->the_post(); 
					$img_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');

					$category_name = array();
					$category_slugs = array();
					$category_terms = get_the_terms($post->ID, 'event-category');
					if(!empty($category_terms)){
						if(!is_wp_error( $category_terms )){
						$category_slugs = array();
							foreach($category_terms as $term){
								$category_name[] = $term->name;
								$category_slugs[] = $term->slug;
							}
					$porto_comas =  join( ", ", $category_name );
					$porto_space =  join( " ", $category_slugs );
						}
					}
					else {
						$porto_comas =  "";
						$porto_space =  "";
					}
					$sakolawp_date_event = esc_html( get_post_meta( $post->ID, 'sakolawp_event_date', true ) );
					$sakolawp_hour_event = esc_html( get_post_meta( $post->ID, 'sakolawp_event_date_clock', true ) ); ?>
					<div class="skwp-column skwp-column-3">
						<div class="loop-wrapper">
							<div class="image-news">
								<?php if(has_post_thumbnail()) {
									if(!empty($sakolawp_date_event)) { ?>
										<div class="event-meta-time has-thumb skwp-clearfix">
											<div class="skwp-row">
												<div class="event-meta-txt skwp-column skwp-column-3">
													<?php esc_html_e('Event Start', 'sakolawp' ); ?>
												</div>
												<div class="event-meta-detail skwp-column skwp-column-2of3">
													<div class="event-meta-date">
														<?php echo esc_html( $sakolawp_date_event ); ?>
													</div>
													<div class="event-meta-hour">
														<?php echo esc_html( $sakolawp_hour_event ); ?>
													</div>
												</div>
											</div>
										</div>
									<?php }

									the_post_thumbnail(); ?>
									<div class="sakolawp-overlay"></div>
								<?php } ?>
							 </div>

							<div class="wrapper-isi-loop">
								<?php if(!has_post_thumbnail()) {
									if(!empty($sakolawp_date_event)) {  ?>
										<div class="event-meta-time skwp-clearfix">
											<div class="event-meta-txt">
												<?php esc_html_e('Event Start', 'sakolawp' ); ?>
											</div>
											<div class="event-meta-detail">
												<div class="event-meta-date">
													<?php echo esc_html( $sakolawp_date_event ); ?>
												</div>
												<div class="event-meta-hour">
													<?php echo esc_html( $sakolawp_hour_event ); ?>
												</div>
											</div>
										</div>
								<?php }
								} ?>

								<?php if(!empty($porto_comas)) { ?>
								<div class="category-news skwp-news-meta">
									<?php echo esc_html($porto_comas); ?>
								</div>
								<?php } ?>

								<div class="date-excerpt skwp-news-meta">
									<span class="thedate"><?php echo get_the_date('d'); ?></span>
									<span class="month"><?php echo get_the_date('M'); ?></span>
									<span class="year"><?php echo get_the_date('Y'); ?></span>
								</div>
								
								<div class="title-news">
									<h4 class="title-name">
										<a href="<?php the_permalink(); ?>">
											<?php the_title(); ?>
										</a>
									</h4>
								</div>
								<div class="news-excerpt">
									<?php $excerpt = get_the_excerpt();
									$excerpt = substr($excerpt, 0, 30);
									$result = substr($excerpt, 0, strrpos($excerpt, ' '));
									echo esc_html($result); ?>
								</div>
								<div class="read-article">
									<a href="<?php the_permalink(); ?>">
										<?php esc_html_e('Read More', 'sakolawp'); ?>
									</a>
								</div>
								
							</div>
						</div>
					</div>

				<?php endwhile; ?>

				<?php else : ?>
					<?php esc_html_e('There is no post yet.', 'sakolawp' ); ?>
				<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="dash-item skwp-column skwp-column-1">
		<div class="news-wrap">
			<div class="skwp-dash-widget-title">
				<h3><?php echo esc_html__('Online Exams', 'sakolawp'); ?></h3>
			</div>
			<div class="loop-news skwp-dash-loop skwp-row skwp-clearfix">
				<?php $enroll = $wpdb->get_row( "SELECT class_id, section_id FROM {$wpdb->prefix}sakolawp_enroll WHERE student_id = $current_id");
				$student_id = $current_id;
				if(!empty($enroll)) : ?>
				
				<table id="tableini" class="table dataTable exams-table">
					<thead>
						<tr>
							<th><?php esc_html_e('Title', 'sakolawp'); ?></th>
							<th><?php esc_html_e('Subject', 'sakolawp'); ?></th>
							<th><?php esc_html_e('Date Start', 'sakolawp'); ?></th>
							<th><?php esc_html_e('Date End', 'sakolawp'); ?></th>
							<th><?php esc_html_e('Status', 'sakolawp'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$today1 = strtotime(date("m/d/Y"));
						$today2 = strtotime(date("d-m-Y"));

						$count    = 1;
						$class_id = $enroll->class_id;
						$section_id = $enroll->section_id;

						$exam = $wpdb->get_results( "SELECT exam_code, availablefrom, availableto, clock_start, clock_end, title, subject_id FROM {$wpdb->prefix}sakolawp_exams WHERE class_id = $class_id AND section_id = $section_id LIMIT 5", ARRAY_A );

						foreach ($exam as $row):
							$exam_code = $row['exam_code'];
							$student_answer_db = $wpdb->get_row( "SELECT answered FROM {$wpdb->prefix}sakolawp_student_answer WHERE exam_code = '$exam_code' AND student_id = $current_id", ARRAY_A );
							$terjawab = $student_answer_db['answered'];

							$exam_ques = $wpdb->get_results( "SELECT exam_code FROM {$wpdb->prefix}sakolawp_questions WHERE exam_code = '$exam_code'" );  
							$query = $wpdb->num_rows;
							?>
								<?php $dbstart = $row['availablefrom'].' '.$row['clock_start'];?>
								<?php $dbend = $row['availableto'].' '.$row['clock_end'];

							$today = current_time('m/d/Y H:i');
							$dbstart1 = strtotime($today);
							$dbstart = strtotime($dbstart);
							$dbend = strtotime($dbend);  ?>

							<tr>
								<td class="tes">
									<?php echo esc_html($row['title']);?>
								</td>
								<td>
									<?php 
									$subject_id = $row["subject_id"];
									$subject_name = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_subject WHERE subject_id = '$subject_id'", ARRAY_A );
									echo esc_html($subject_name['name']); ?>
								</td>
								<td>
									<a class="btn btn-rounded btn-sm btn-success skwp-btn">
										<?php echo esc_html($row['availablefrom']);?>
										<?php echo esc_html($row['clock_start']);?>
									</a>
								</td>
								<td>
									<a class="btn btn-rounded btn-sm btn-danger skwp-btn">
										<?php echo esc_html($row['availableto']);?>
										<?php echo esc_html($row['clock_end']);?>
									</a>
								</td>
								<td class="simak-onlinee">
									<?php if($dbstart1 < $dbstart && $terjawab != 'answered'):?>
									<a class="btn nc btn-rounded btn-sm btn-warning skwp-btn"><?php esc_html_e('Not Started', 'sakolawp'); ?></a>
									<?php endif;?>
									<?php if($dbstart1 >= $dbend &&  $terjawab != 'answered'):?>
									<a class="btn nc btn-rounded btn-sm btn-danger skwp-btn"><?php esc_html_e('Exam Done', 'sakolawp'); ?></a>
									<?php endif;?>
									<?php if($terjawab != 'answered' && $query > 0 && $dbstart1 >= $dbstart && $dbstart1 < $dbend):?><a class="btn btn-rounded btn-sm btn-success skwp-btn" href="<?php echo add_query_arg( 'exam_code', esc_html($row['exam_code']), home_url( 'examroom' ) );?>"><?php esc_html_e('Take Exam', 'sakolawp'); ?></a>
									<?php endif;?>
									<?php if($query <= 0 && $dbstart1 < $dbend && $dbstart1 >= $dbstart):?>
									<a class="btn nc btn-rounded btn-sm btn-info skwp-btn"><?php esc_html_e('Not Started', 'sakolawp'); ?></a>
									<?php endif;?>
									<?php if($terjawab == 'answered'):?>
									<a class="btn btn-rounded btn-sm btn-primary skwp-btn" href="<?php echo add_query_arg( array('exam_code' => esc_html($exam_code), 'student_id' => intval($student_id)), home_url( 'view_exam_result' ) );?>" ><?php esc_html_e('View Result', 'sakolawp'); ?></a>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<?php else : ?>
					<?php esc_html_e('There is no exam yet.', 'sakolawp' ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?php 

do_action( 'sakolawp_after_main_content' );
get_footer();