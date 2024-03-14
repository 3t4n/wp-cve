<?php
/**
 * Handle templates functions
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Set the path to be used in the theme folder.
 * Templates in this folder will override the plugins frontend templates.
 */
function upstream_template_path() {
	return apply_filters( 'upstream_template_path', 'upstream/' );
}

/**
 * Check relevant directories for template parts.
 * Looks in child theme first, then parent theme, then plugin.
 *
 * @param  mixed $part Part.
 */
function upstream_get_template_part( $part ) {
	if ( $part ) {
		$check_dirs = apply_filters(
			'upstream_check_template_directory',
			array(
				trailingslashit( get_stylesheet_directory() ) . upstream_template_path(),
				trailingslashit( get_template_directory() ) . upstream_template_path(),
				UPSTREAM_PLUGIN_DIR . 'templates/',
			)
		);
		foreach ( $check_dirs as $dir ) {
			if ( file_exists( trailingslashit( $dir ) . $part ) ) {
				load_template( $dir . $part );

				return;
			}
		}
	}

	return $part;
}

/**
 * Output list of the client users avatars
 *
 * @param  mixed $id Id.
 * @return void
 */
function upstream_output_client_users( $id = null ) {
	$users               = (array) upstream_project_client_users( $id );
	$is_after_first_item = false;

	if ( count( $users ) > 0 ) : ?>
		<ul class="list-inline">
			<li>
			<?php
			foreach ( $users as $user_id ) {
				if ( upstream_show_users_name() ) {
					if ( $is_after_first_item ) {
						echo ',&nbsp; ';
					}

					$is_after_first_item = true;
				}

				echo wp_kses_post( upstream_user_avatar( $user_id ) );
			}
			?>
			</li>
		</ul>
	<?php else : ?>
		<span class="text-muted"><i><?php echo '(' . esc_html__( 'none', 'upstream' ) . ')'; ?></i></span>
		<?php
	endif;
}

/**
 * Upstream Output Project Owner
 *
 * @param  mixed $id Id.
 * @return void
 */
function upstream_output_project_owner( $id = null ) {
	$users = array( upstream_project_owner_id( $id ) );

	if ( count( $users ) > 0 ) {
		?>
		<ul class="list-inline">
			<li>
				<?php
				$is_after_first_item = false;

				foreach ( $users as $user_id ) {
					if ( upstream_show_users_name() ) {
						if ( $is_after_first_item ) {
							echo ',&nbsp;';
						}

						$is_after_first_item = true;
					}

					echo wp_kses_post( upstream_user_avatar( $user_id ) );
				}
				?>
			</li>
		</ul>
	<?php } else { ?>
		<span class="text-muted"><i><?php echo esc_html( '(' . __( 'none', 'upstream' ) . ')' ); ?></i></span>
		<?php
	}
}

/**
 * Upstream Get Project Owner
 *
 * @param  mixed $id ID.
 */
function upstream_get_project_owner( $id = null ) {
	$users = array( upstream_project_owner_id( $id ) );
	$text  = '';

	if ( count( $users ) > 0 ) {
		$text               .= '<ul class="list-inline"><li>';
		$is_after_first_item = false;

		foreach ( $users as $user_id ) {
			if ( upstream_show_users_name() ) {
				if ( $is_after_first_item ) {
					$text .= ',&nbsp;';
				}

				$is_after_first_item = true;
			}

			$text .= upstream_user_avatar( $user_id );
		}
		$text .= '</li></ul>';
	} else {
		$text .= '<span class="text-muted"><i>' . esc_html( '(' . __( 'none', 'upstream' ) . ')' ) . '</i></span>';
	}
	return array( $users, $text );
}

/**
 * Upstream Output Project Members
 *
 * @param  mixed $id ID.
 * @return void
 */
function upstream_output_project_members( $id = null ) {
	$users = (array) upstream_project_users( $id );

	if ( count( $users ) > 0 ) {
		?>
		<ul class="list-inline">
			<li>
			<?php
			$is_after_first_item = false;

			foreach ( $users as $user_id ) {
				if ( upstream_show_users_name() ) {
					if ( $is_after_first_item ) {
						echo ',&nbsp;';
					}

					$is_after_first_item = true;
				}

				echo wp_kses_post( upstream_user_avatar( $user_id ) );
			}
			?>
			</li>
		</ul>
	<?php } else { ?>
		<span class="text-muted"><i><?php echo esc_html( '(' . __( 'none', 'upstream' ) . ')' ); ?></i></span>
		<?php
	}
}

/**
 * Upstream Get File Preview
 *
 * @param  mixed $attachment_id Attachment Id.
 * @param  mixed $attachment_url Attachment Url.
 * @param  mixed $use_li Use Li.
 */
function upstream_get_file_preview( $attachment_id, $attachment_url, $use_li = true ) {
	$use_li   = (bool) $use_li;
	$filetype = wp_check_filetype( $attachment_url );
	$filename = basename( $attachment_url );

	$output = '';

	if ( $use_li ) {
		$output = '<li>';
	}

	if ( wp_get_attachment_image( $attachment_id, 'thumbnail' ) ) {
		$output .= '<a target="_blank" href="' . esc_url( $attachment_url ) . '">' . wp_get_attachment_image(
			$attachment_id,
			array( 32, 32 ),
			false,
			array(
				'title'          => esc_attr( $filename ),
				'data-toggle'    => 'tooltip',
				'data-placement' => 'top',
				'data-fileid'    => (int) $attachment_id,
				'data-fileurl'   => esc_attr( $attachment_url ),
				'class'          => 'avatar itemfile',
			)
		) . '</a>';
	} else {
		switch ( $filetype['ext'] ) {
			case 'pdf':
				$icon = 'fa-file-pdf-o';
				break;
			case 'csv':
			case 'xls':
			case 'xlsx':
				$icon = 'fa-file-excel-o';
				break;
			case 'doc':
			case 'docx':
				$icon = 'fa-file-word-o';
				break;
			case 'ppt':
			case 'pptx':
			case 'pps':
			case 'ppsx':
			case 'key':
				$icon = 'fa-file-powerpoint-o';
				break;
			case 'zip':
			case 'rar':
			case 'tar':
				$icon = 'fa-file-zip-o';
				break;
			case 'mp3':
			case 'm4a':
			case 'ogg':
			case 'wav':
				$icon = 'fa-file-audio-o';
				break;
			case 'mp4':
			case 'm4v':
			case 'mov':
			case 'wmv':
			case 'avi':
			case 'mpg':
			case 'ogv':
			case '3gp':
			case '3g2':
				$icon = 'fa-file-video-o';
				break;
			default:
				$icon = 'fa-file-text-o';
				break;
		};

		$output = '';
		if ( $use_li ) {
			$output = '<li>';
		}

		$output .= '<a target="_blank" href="' . esc_url( $attachment_url ) . '"><i class="itemfile fa ' . esc_attr( $icon ) . '" data-toggle="tooltip" data-placement="top" data-fileid="' . (int) $attachment_id . '" data-fileurl="' . esc_attr( $attachment_url ) . '" title="' . esc_attr( $filename ) . '"></i></a>';
	}

	if ( $use_li ) {
		$output .= '</li>';
	}

	return $output;
}

/**
 * Upstream Output File List
 *
 * @param  string $img_size Img Size.
 */
function upstream_output_file_list( $img_size = 'thumbnail' ) {
	// Get the list of files.
	$files = get_post_meta( upstream_post_id(), '_upstream_project_files', true );
	$file  = array();

	if ( $files ) {
		foreach ( $files as $i => $filedata ) {
			if ( isset( $filedata['file'] ) && isset( $filedata['file_id'] ) && '' !== $filedata['file'] ) {
				$file[] = upstream_get_file_preview( $filedata['file_id'], $filedata['file'] );
			}
		}
	}

	if ( $file ) {
		// loop through the rows.
		$output = '<ul class="list-inline">';
		foreach ( $file as $li ) {
			if ( isset( $filedata['file'] ) && isset( $filedata['file_id'] ) && '' !== $filedata['file'] ) {
				$output .= $li;
			}
		}
		$output .= '</ul>';
	} else {
		$output = '<p>' . __( 'Currently no files', 'upstream' ) . '</p>';
	}

	return $output;
}

/**
 * Upstream Frontend Output Comment
 *
 * @param  mixed $row Row.
 * @param  mixed $row_index Row Index.
 * @param  mixed $project_id Project Id.
 * @return void
 */
function upstream_frontend_output_comment( $row, $row_index, $project_id ) {
	?>
	<div class="media o-comment" id="comment-<?php echo esc_attr( $row->id ); ?>" data-id="<?php echo esc_attr( $row->id ); ?>">
		<div class="media-left">
			<img class="media-object o-comment__creator_profilepic" src="<?php echo esc_url( $row->created_by->avatar ); ?>"
				alt="<?php echo esc_attr( $row->created_by->name ); ?>" width="40"/>
		</div>
		<div class="media-body">
			<div class="o-comment__header">
				<h5 class="media-heading o-comment__creator_name"><?php echo esc_html( $row->created_by->name ); ?></h5>
				<?php if ( isset( $row->parent ) && null !== $row->parent ) : ?>
					<div
							class="o-comment__replier">
							<?php
							printf(
								'%s <a href="#comment-%s" class="text-info" data-action="comment.go_to_reply">%s</a>',
								esc_html__( 'In reply to', 'upstream' ),
								esc_attr( $row->parent->id ),
								esc_html( $row->parent->created_by->name )
							);
							?>
														</div>
				<?php endif; ?>
				<time datetime="<?php echo esc_attr( $row->created_at->iso_8601 ); ?>" data-delay="500" data-toggle="tooltip"
					data-placement="top" title="<?php echo esc_attr( $row->created_at->formatted ); ?>"
					class="o-comment__created_at"><?php echo esc_html( $row->created_at->human ); ?></time>
			</div>
			<div class="o-comment__content"><?php echo wp_kses_post( $row->comment ); ?></div>
			<div class="o-comment__footer">
		<?php do_action( 'upstream:frontend.project.discussion:comment_footer', $project_id, $row ); ?>
		</div>
		</div>
	</div>
	<?php
}
