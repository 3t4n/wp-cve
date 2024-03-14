<?php

if ( !class_exists( 'APSC_Debug_Panel' ) ) :

final class APSC_Debug_Panel extends Debug_Bar_Panel
{
	
	public function __construct()
	{
		
		global $APSC;
		
		if( $APSC->Env->is_admin ) {
			
			return false;
			
		}
		
		parent::__construct();
		
	}

	public function init()
	{

		global $APSC;

		$this->title( 'APSC' );

	}
	
	public function render()
	{
		
		global $APSC;
		
?>

		<div id="debug-bar-<?php echo $APSC->main_slug; ?>">
		
			<h2><?php echo $APSC->name; ?></h2>
			
			<table>
				<tr>
					<th><?php _e( 'Archives' ); ?></th>
					<td>
						<?php echo $APSC->Api->is_archive(); ?>
					</td>
				</tr>
				<tr>
					<th><?php _e( 'Archives Type' , $APSC->ltd ); ?></th>
					<td>
						<?php $archive_type = $APSC->Api->get_archive_type(); ?>

						<?php if( $archive_type['id'] == 'home' ) : ?>
						
							<?php _e( 'Home' ); ?>
							
						<?php elseif( $archive_type['id'] == 'search' ) : ?>
						
							<?php _e( 'Search' ); ?>
						
						<?php elseif( $archive_type['id'] == 'date' && !empty( $archive_type['args']['date'] ) ) : ?>
						
							<?php if( $archive_type['args']['date'] == 'yearly' ) : ?>
							
								<?php _e( 'Yearly Archives' , $APSC->ltd ); ?>
							
							<?php elseif( $archive_type['args']['date'] == 'monthly' ) : ?>
							
								<?php _e( 'Monthly Archives' , $APSC->ltd ); ?>
							
							<?php elseif( $archive_type['args']['date'] == 'daily' ) : ?>
							
								<?php _e( 'Daily Archives' , $APSC->ltd ); ?>
							
							<?php endif; ?>
						
						<?php elseif( $archive_type['id'] == 'taxonomies' && !empty( $archive_type['args']['taxonomy'] ) && !empty( $archive_type['args']['term_id'] ) ) : ?>
						
							<?php $taxonomy = get_taxonomy( $archive_type['args']['taxonomy'] ); ?>
							
							<?php if( !empty( $taxonomy->label ) ) : ?>
							
								<strong><?php echo esc_html( $taxonomy->label ); ?></strong>:
								
								<?php $term = get_term( $archive_type['args']['term_id'] , $archive_type['args']['taxonomy'] ); ?>

								<?php if( !empty( $term->name ) ) : ?>
								
									<?php echo esc_html( $term->name ); ?>(<?php echo $archive_type['args']['term_id']; ?>)
								
								<?php endif; ?>
							
							<?php endif; ?>
							
						<?php elseif( $archive_type['id'] == 'custom' ) : ?>
						
							<?php _e( 'Custom' ); ?>
						
						<?php endif; ?>
					</td>
				</tr>
				<?php $archive_settings = $APSC->Api->get_archive_settings( $archive_type['id'] , $archive_type['args'] ); ?>
				<tr>
					<th><?php _e( 'Number of posts per page' , $APSC->ltd ); ?></th>
					<td>
						<?php if( !empty( $archive_settings['posts_per_page'] ) ) : ?>

							<?php echo $archive_settings['posts_per_page']; ?>

							<?php if( $archive_settings['posts_per_page'] == 'set' && !empty( $archive_settings['posts_per_page_num'] ) ) : ?>

								(<?php echo $archive_settings['posts_per_page_num']; ?>)

							<?php endif; ?>

						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><?php _e( 'Sort item' , $APSC->ltd ); ?></th>
					<td>
						<?php if( !empty( $archive_settings['orderby'] ) ) : ?>

							<?php echo $archive_settings['orderby']; ?>

							<?php if( $archive_settings['orderby'] == 'title' && !empty( $archive_settings['ignore_words'] ) ) : ?>

								<ul>

									<?php foreach( $archive_settings['ignore_words'] as $ignore_word ) : ?>

										<li><?php echo $ignore_word; ?></li>

									<?php endforeach; ?>

								</ul>

							<?php elseif( $archive_settings['orderby'] == 'custom_fields' && !empty( $archive_settings['orderby_set'] ) ) : ?>

								[<?php echo $archive_settings['orderby_set']; ?>]

								:is_numeric (<?php echo $APSC->Api->is_custom_field_values_numeric( $archive_settings['orderby_set'] ); ?>)

							<?php endif; ?>

						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><?php _e( 'Order' ); ?></th>
					<td>
						<?php if( !empty( $archive_settings['order'] ) ) : ?>

							<?php echo $archive_settings['order']; ?>

						<?php endif; ?>
					</td>
				</tr>
			</table>
			
		</div>
		
		<style>
        #debug-bar-<?php echo $APSC->main_slug; ?> h2 {
            float: none;
        }
        #debug-bar-<?php echo $APSC->main_slug; ?> h3 {
            font-weight: bold;
        }
        #debug-bar-<?php echo $APSC->main_slug; ?> table {
            width: 100%;
        }
        #debug-bar-<?php echo $APSC->main_slug; ?> table p {
           margin: 0;
        }
        #debug-bar-<?php echo $APSC->main_slug; ?> table th,
        #debug-bar-<?php echo $APSC->main_slug; ?> table td {
            vertical-align: top;
            text-align: left;
        }
        #debug-bar-<?php echo $APSC->main_slug; ?> pre {
            overflow-x: auto;
            white-space: pre-wrap;
            background: #f3f3f3;
            border: 1px solid #dedee3;
            padding: 12px;
            line-height: 1.3em;
        }
        #debug-bar-<?php echo $APSC->main_slug; ?> textarea {
            width: 100%;
            height: 160px;
            font-size: 11px;
        }
        </style>

<?php

	}

}

endif;

