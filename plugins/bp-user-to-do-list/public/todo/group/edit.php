<?php
/**
 * Exit if accessed directly.
 *
 * @package bp-user-todo-list
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $bp,$bptodo;
// echo 'thid id' . $todo_guid;
$group_id           = isset( $bp->groups->current_group->id ) ? $bp->groups->current_group->id : $todo_guid;
$profile_menu_label = $bptodo->profile_menu_label;
$profile_menu_slug  = $bptodo->profile_menu_slug;

$groups_link = bp_get_group_url( $bp->groups->current_group );
$admin_link  = trailingslashit( $groups_link . $profile_menu_slug );

$bptodo_req = '';
if ( 'yes' == $bptodo->req_duedate ) {
	$bptodo_req = 'required';
}

$displayed_uid = bp_displayed_user_id();

// List of Todo Items.
$args  = array(
	'post_type'      => 'bp-todo',
	'post_status'    => 'publish',
	'author'         => get_current_user_id(),
	'posts_per_page' => -1,
	'meta_key'       => 'todo_group_id',
	'meta_value'     => $group_id,
);
$todos = get_posts( $args );

$form_post_link = add_query_arg(
	'args',
	$todos[0]->ID,
	$admin_link . '/list'
);

$group_members = groups_get_group_members(
	array(
		'group_id'            => $group_id,
		'exclude_admins_mods' => false,
	)
);
if ( isset( $group_members['members'] ) ) {
	foreach ( $group_members['members'] as $member ) {
		$group_members_ids[] = $member->ID;
	}
}

$todo_cats = get_terms(
	array(
		'taxonomy'   => 'todo_category',
		'orderby'    => 'name',
		'hide_empty' => false,
	)
);

$todo = get_post( $todo_id );

$todo_cat    = wp_get_object_terms( $todo_id, 'todo_category' );
$todo_cat_id = 0;
if ( ! empty( $todo_cat ) && is_array( $todo_cat ) ) {
	$todo_cat_id = $todo_cat[0]->term_id;
}
$todo_due_date = get_post_meta( $todo_id, 'todo_due_date', true );
$todo_priority = get_post_meta( $todo_id, 'todo_priority', true );

if ( $todo_priority == '' ) {
	$todo_priority = 'normal';
}

$todo_group_id = get_post_meta( $todo_id, 'todo_group_id', true );

$primary_todo_id = get_post_meta( $todo_id, 'todo_primary_id', true );


?>

<form action="" method="post" id="bptodo_edit_form_popup">
	<table class="bptodo-add-todo-tbl">
		<?php do_action( 'bp_group_todo_add_field_before_edit_default_listing', $displayed_uid, $form_post_link ); ?>
		<tr>
			<td>
				<?php esc_html_e( 'Category', 'wb-todo' ); ?>
			</td>
			<td>
				<div>
					<select name="todo_cat" id="bp_todo_categories" required>
						<option value=""><?php esc_html_e( '--Select--', 'wb-todo' ); ?></option>
						<?php if ( isset( $todo_cats ) ) { ?>
							<?php foreach ( $todo_cats as $todo_cat ) { ?>
						<option
								<?php
								if ( $todo_cat_id === $todo_cat->term_id ) {
									echo 'selected="selected"';}
								?>
value="<?php echo esc_html( $todo_cat->name ); ?>">
								<?php echo esc_html( $todo_cat->name ); ?>
						</option>
						<?php } ?>
						<?php } ?>
					</select>
					<?php if ( 'yes' === $bptodo->allow_user_add_category ) { ?>
					<a href="javascript:void(0);" class="add-todo-category"><span class="add-cat-icon"></span></a>
					<?php } ?>
				</div>
				<?php if ( 'yes' === $bptodo->allow_user_add_category ) { ?>
				<div class="add-todo-cat-row">
					<?php /* Translators: Display the plural label name */ ?>
					<input type="text" id="todo-category-name" placeholder="<?php echo sprintf( esc_html__( '%1$s category', 'wb-todo' ), esc_html( $profile_menu_label ) ); ?>">
					<?php $add_cat_nonce = wp_create_nonce( 'bptodo-add-todo-category' ); ?>
					<input type="hidden" id="bptodo-add-category-nonce" value="<?php echo esc_attr( $add_cat_nonce ); ?>">
					<button type="button" id="add-todo-cat" class="btn button"><?php esc_html_e( 'Add', 'wb-todo' ); ?></button>
				</div>
				<?php } ?>
			</td>
		</tr>

		<tr>
			<td>
				<?php esc_html_e( 'Title', 'wb-todo' ); ?>
			</td>
			<td>
				<input value="<?php echo esc_html( $todo->post_title ); ?>" type="text" placeholder="<?php esc_html_e( 'Title', 'wb-todo' ); ?>" name="todo_title" required class="bptodo-text-input">
			</td>
		</tr>

		<tr>
			<td>
				<?php esc_html_e( 'Summary', 'wb-todo' ); ?>
			</td>
			<td>
				<?php
				$settings = array(
					'media_buttons' => false,
					'editor_height' => 200,
					'quicktags'     => false,
				);
				wp_editor( $todo->post_content, 'bptodo-summary-input', $settings );
				?>
			</td>
		</tr>

		<tr>
			<td>
				<?php esc_html_e( 'Due Date', 'wb-todo' ); ?>
			</td>
			<td>
				<input type="date" placeholder="<?php esc_html_e( 'Due Date', 'wb-todo' ); ?>" class="todo_due_date bptodo-text-input" id="datepick" name="todo_due_date" value="<?php echo esc_html( $todo_due_date ); ?>"<?php echo esc_attr( $bptodo_req ); ?>>
			</td>
		</tr>

		<tr>
			<td>
				<?php esc_html_e( 'Priority', 'wb-todo' ); ?>
			</td>
			<td>
				<select name="todo_priority" id="bp_todo_priority" >
					<option value=""><?php esc_html_e( '--Select--', 'wb-todo' ); ?></option>
					<option value="critical" <?php selected( $todo_priority, 'critical' ); ?>><?php esc_html_e( 'Critical', 'wb-todo' ); ?></option>
					<option value="high" <?php selected( $todo_priority, 'high' ); ?>><?php esc_html_e( 'High', 'wb-todo' ); ?></option>
					<option value="normal" <?php selected( $todo_priority, 'normal' ); ?>><?php esc_html_e( 'Normal', 'wb-todo' ); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
			</td>
			<td>
				<input type="hidden" name="todo_group_id" value="<?php esc_attr( $todo_group_id ); ?>">
			</td>
		</tr>
		<?php $change_edit_btn = apply_filters( 'bp_group_todo_change_todo_edit_button', $change = false ); ?>
		<?php if ( ! $change_edit_btn ) { ?>
			<tr>
				<td></td>
				<td>
					<?php wp_nonce_field( 'wp-bp-todo', 'save_update_group_todo_data_nonce' ); ?>
					<input type="hidden" name="hidden_todo_id" value="<?php echo esc_attr( $todo_id ); ?>">
					<input type="hidden" name="primary_todo_id" value="<?php echo esc_attr( $primary_todo_id ); ?>">
					<input type="hidden" name="group_id" value="<?php echo esc_attr( $group_id ); ?>">
					<?php /* Translators: Display the plural label name */ ?>
					<input type="submit" id="group_todo_update" name="group_todo_update" value="<?php echo sprintf( esc_html__( 'Update %s', 'wb-todo' ), esc_attr( $profile_menu_label ) ); ?>">
				</td>
			</tr>
			<?php
		} else {
			do_action( 'bp_group_todo_change_todo_edit_button_action', $profile_menu_label );
		}
		?>
		<?php do_action( 'bp_group_todo_add_field_after_edit_default_listing', $displayed_uid, $form_post_link ); ?>
	</table>
</form>
