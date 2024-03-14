<div id="<?php echo esc_attr( $options['id'] ); ?>" class="<?php echo esc_attr( $options['class'] ); ?>">
	<?php if($staff_loop->have_posts()): while($staff_loop->have_posts()): $staff_loop->the_post(); ?>
		<?php
			extract ( cd_get_staff_metadata(get_the_ID(), $options) );
		?>
		<div class="staff-member">		
			<?php $staff_member_photo_tag = cd_get_staff_member_photo(get_the_ID(), 'thumbnail', true)?>
			<?php if ( $show_photo && !empty($staff_member_photo_tag) ): ?>
				<div class="staff-photo">
					<a href="<?php esc_url( the_permalink() ); ?>"><?php echo wp_kses( $staff_member_photo_tag, 'post' ); ?></a>
				</div>
			<?php endif; ?>
			
			<div class="staff-member-right">
				<?php if ($show_name): ?>
				<h3 class="staff-member-name">
					<a href="<?php esc_url( the_permalink() ); ?>"><?php wp_kses( the_title(), 'post' ); ?></a>
				</h3>
				<?php endif; ?>				

				<?php if ($show_title): ?>
					<p class="staff-member-title"><?php echo wp_kses( $my_title, 'post' ); ?></p>
				<?php endif; ?>
		
				<?php if ( $show_department && $my_department): ?>
					<p class="staff-member-department"><?php echo wp_kses( $my_department, 'strip' ); ?></p>
				<?php endif; ?>

				<?php if ($show_bio): ?>
				<div class="staff-member-bio"><?php wp_kses_post( the_content() ); ?></div>
				<?php endif; ?>				

				<?php if ($show_address && $my_address): ?>
				<div class="staff-member-address">
					<h4>Mailing Address</h4>
					<p class="addr">
						<?php echo wp_kses( nl2br( $my_address), 'post' ); ?>
					</p>
				</div>
				<?php endif; ?>

				<?php if ( ($show_phone && $my_phone) || ($show_email && $my_email) || ($show_website && $my_website) ): ?>
				<div class="staff-member-contacts">
					<h4>Contact</h4>
					<?php if ($show_phone && $my_phone): ?><p class="staff-member-phone"><strong>Phone:</strong> <?php echo wp_kses( $my_phone, 'strip' ); ?></p><?php endif; ?>
					<?php if ($show_email && $my_email): ?><p class="staff-member-email"><strong>Email:</strong> <a href="mailto:<?php echo wp_kses( $my_email, 'strip' ); ?>"><?php echo htmlspecialchars($my_email); ?></a></p><?php endif; ?>
					<?php if ($show_website && $my_website): ?><p class="staff-member-website"><strong>Website:</strong> <a href="<?php echo esc_url($my_website); ?>"><?php echo wp_kses( $my_website, 'strip' ); ?></a></p><?php endif; ?>
				</div>
				<?php endif; ?>
			</div>			
		</div>
	<?php endwhile; ?>	

	<?php if ( !empty($staff_loop->query_vars['paged']) ): ?>
	<div class="staff-directory-pagination">                               
		<?php
		echo paginate_links( array(
			'base' => $pagination_link_template,
			'format' => '?staff_page=%#%',
			'current' => max( 1, $current_page ),
			'total' => $staff_loop->max_num_pages
		) );
		?>
	</div>  
	<?php endif; // pagination ?>

	<?php endif; // have_posts() ?>
	
	<?php wp_reset_query(); ?>
</div>