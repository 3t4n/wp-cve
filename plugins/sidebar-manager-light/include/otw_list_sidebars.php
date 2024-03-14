<?php
/** List with all available otw sitebars
  *
  *
  */
global $_wp_column_headers;

$_wp_column_headers['toplevel_page_otw-sbm'] = array(
	'title' => esc_html__( 'Title' ),
	'description' => esc_html__( 'Description' ),
	'status' => esc_html__( 'Status', 'otw_sml' )

);

$otw_sidebar_list = get_option( 'otw_sidebars' );

$message = '';
$massages = array();
$messages[1] = 'Sidebar saved.';
$messages[2] = 'Sidebar deleted.';
$messages[3] = 'Sidebar activated.';
$messages[4] = 'Sidebar deactivated.';

if( otw_get('message',false) && isset( $messages[ otw_get('message','') ] ) ){
	$message .= $messages[ otw_get('message','') ];
}

$filtered_otw_sidebar_list = array();

if( is_array( $otw_sidebar_list ) && count( $otw_sidebar_list ) ){
	foreach( $otw_sidebar_list as $sidebar_key => $sidebar_item ){
		if( $sidebar_item['replace'] != '' ){
			$filtered_otw_sidebar_list[ $sidebar_key ] = $sidebar_item;
		}
	}
}

?>
<?php if ( $message ) : ?>
<div id="message" class="updated"><p><?php echo esc_html( $message ); ?></p></div>
<?php endif; ?>
<div class="wrap">
	<div id="icon-edit" class="icon32"><br/></div>
	<h2>
		<?php esc_html_e('Available Custom Sidebars') ?>
		<a class="button add-new-h2" href="<?php echo admin_url( 'admin.php?page=otw-sml-add'); ?>">Add New</a>
	</h2>
	
	<form class="search-form" action="" method="get">
	</form>
	
	<br class="clear" />
	<?php if( is_array( $filtered_otw_sidebar_list ) && count( $filtered_otw_sidebar_list ) ){?>
	<table class="widefat fixed" cellspacing="0">
		<thead>
			<tr>
				<?php foreach( $_wp_column_headers['toplevel_page_otw-sbm'] as $key => $name ){?>
					<th><?php echo esc_html( $name )?></th>
				<?php }?>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<?php foreach( $_wp_column_headers['toplevel_page_otw-sbm'] as $key => $name ){?>
					<th><?php echo esc_html( $name )?></th>
				<?php }?>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach( $filtered_otw_sidebar_list as $sidebar_item ){?>
				<tr>
					<?php foreach( $_wp_column_headers['toplevel_page_otw-sbm'] as $column_name => $column_title ){
						
						$edit_link = admin_url( 'admin.php?page=otw-sml&amp;action=edit&amp;sidebar='.$sidebar_item['id'] );
						$delete_link = admin_url( 'admin.php?page=otw-sml-action&amp;sidebar='.$sidebar_item['id'].'&amp;action=delete' );
						
						switch($column_name) {
							
							case 'cb':
									echo '<th scope="row" class="check-column"><input type="checkbox" name="itemcheck[]" value="'. esc_attr($sidebar_item['id']) .'" /></th>';
								break;
							case 'title':
									echo '<td><strong><a href="'.esc_attr( $edit_link ).'" title="'.esc_attr(sprintf(__('Edit &#8220;%s&#8221;'), $sidebar_item['title'])).'">'.$sidebar_item['title'].'</a></strong><br />';
									
									echo '<div class="row-actions">';
									echo '<a href="'.esc_attr( $edit_link ).'">' . esc_html__('Edit') . '</a>';
									echo ' | <a href="'.esc_attr( $delete_link ).'">' . esc_html__('Delete'). '</a>';
									echo '</div>';
									
									echo '</td>';
								break;
							case 'description':
									echo '<td>'.$sidebar_item['description'].'</td>';
								break;
							case 'status':
									switch( $sidebar_item['status'] ){
										case 'active':
												echo '<td class="sidebar_active">'.esc_html__( 'Active', 'otw_sml' ).'</td>';
											break;
										case 'inactive':
												echo '<td class="sidebar_inactive">'.esc_html__( 'Inactive', 'otw_sml' ).'</td>';
											break;
										default:
												echo '<td>'.esc_html__( 'Unknown', 'otw_sml' ).'</td>';
											break;
									}
								break;
							
						}
					}?>
				</tr>
			<?php }?>
		</tbody>
	</table>
	<?php }else{ ?>
		<p><?php esc_html_e('No custom sidebars found.')?></p>
	<?php } ?>
</div>
