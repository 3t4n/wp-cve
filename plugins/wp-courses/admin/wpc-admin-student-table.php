<?php

global $wpdb;

$paged = isset($_GET['paged']) ? (int) $_GET['paged'] : 0;
$number = isset($_GET['student_count']) ? (int) $_GET['student_count'] : 10;
$search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
$table_name = $wpdb->prefix . 'users';

// search in $args is too exact, so searching with sql and passing ids to $args
$sql = "SELECT ID FROM {$table_name} WHERE user_login LIKE '%{$search}%' OR user_email LIKE '%{$search}%'";
$results = $wpdb->get_results($sql);

$student_ids = array();

// push ids to array so we can pass a simple array of user ids to get_users $args
if(!empty($results)){
	foreach($results as $result){
		array_push($student_ids, $result->ID);
	}
}

$args = array(
	'offset' 			=> $paged ? ($paged - 1) * $number : 0,
	'number' 			=> $number,
	'orderby'			=> 'display_name',
	'order'				=> 'ASC'
);

if(isset($_GET['s'])){
	// push argument 'include'
	$student_search = esc_textarea($_GET['s']);
	$args['include'] = $student_ids;
	$total_users = count($student_ids);
} else {
	$student_search = '';
	$total_users = count_users();
	$total_users = $total_users['total_users'];
}

$users = get_users( $args ); ?>


<h2 class="wpc-admin-box-header"><?php esc_html_e("All Students", "wp-courses"); ?></h2>
<div class="wpc-admin-box-content">
	<div class="tablenav top">
		<form method="get" action="">
			<p class="search-box">
				<input type="search" value="<?php echo $student_search; ?>" name="s" id="user-search-input"/>	
				<input type="submit" class="wpc-admin-button" value="Search Users">
			</p>
			<input type="hidden" name="page" value="manage_students"/>
			<input type="hidden" name="student_count" value="<?php echo (int) $number; ?>"/>
		</form>

		<form method="get" action="">
			<label for="wpc-student-count-select">Number of items per page: </label>
			<select id="wpc-student-count-select" name="student_count">
				<?php
					$values = array(10, 25, 50, 100);
					foreach($values as $value){
							echo '<option value="' . esc_attr($value) . '" ' .  selected(esc_attr($value), $number) . '>' . esc_html($value) . '</option>';
					}
				?>
			</select>
			<input type="hidden" name="page" value="manage_students"/>
			<input type="hidden" name="s" value="<?php echo esc_textarea($search); ?>"/>
			<input type="submit" class="wpc-admin-button" value="Submit" style="position: relative; top: 2px;">
		</form>

		<div class="wpc-admin-pagination">
			<?php
				if($total_users > $number){
				  	$pl_args = array(
				    	'base'     		=> add_query_arg('paged','%#%'),
				   		'format'   		=> '',
				    	'total'    		=> ceil($total_users / $number),
				    	'current'  		=> max(1, $paged),
				    	'prev_text'     => '<< ' . esc_html__('Prev', 'wp-courses'),
						'next_text'     => esc_html__('Next', 'wp-courses') . ' >>',
				  	);
					echo wp_kses(paginate_links($pl_args), array('span' => array('aria-current' => array(), 'class' => array()), 'a' => array('class' => array(), 'href' => array())));
				}
			?>
		</div>
	</div>

	<table class="widefat fixed" cellspacing="0">
		<thead>
			<tr>
				<th class="manage-column column-columnname" scope="col"><?php esc_html_e('Student', 'wp-courses'); ?></th>
				<th class="manage-column column-columnname" scope="col"><?php esc_html_e('Email', 'wp-courses'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
				$count = 0;

				foreach($users as $user) {
					$class = $count % 2 == 0 ? ' alternate' : '';

					echo '<tr class="' . esc_attr($class) . '">';
						echo '<td class="column-columnname"><a href="?page=manage_students&student_id=' . (int) $user->ID . '"><strong>' . esc_html($user->display_name) . '</strong></a></td>';
						echo '<td class="column-columnname">' . esc_html($user->user_email) . '</td>';
					echo '</tr>';

					$count++;
				}
			?>

		<tbody>
		<tfoot>
			<tr>
				<th class="manage-column column-columnname" scope="col"><?php esc_html_e('Student', 'wp-courses'); ?></th>
				<th class="manage-column column-columnname" scope="col"><?php esc_html_e('Email', 'wp-courses'); ?></th>
			</tr>
		</tfoot>
	</table>

	<?php
		if($total_users > $number){ ?>
			<div class="tablenav top">
				<div class="wpc-admin-pagination">
					<?php echo wp_kses(paginate_links($pl_args), array('span' => array('aria-current' => array(), 'class' => array()), 'a' => array('class' => array(), 'href' => array()))); ?>
				</div>
			</div>
	<?php } ?>
</div>