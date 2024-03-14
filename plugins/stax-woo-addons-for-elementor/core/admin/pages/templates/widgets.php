<h2 class="ste-my-0 ste-leading-none ste-text-2xl ste-text-gray-900 ste-font-bold ste-tracking-wide">
	<?php esc_html_e( 'Widgets', 'stax-woo-addons-for-elementor' ); ?>
</h2>

<div class="ste-text-sm ste-text-gray-600 ste-mt-2">
	<?php esc_html_e( 'Choose which widgets should be enabled in Elementor.', 'stax-woo-addons-for-elementor' ); ?>
</div>

<div class="ste-mt-5">
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
		<div class="ste-flex ste-flex-wrap ste--mx-2">
			<?php foreach ( $widgets as $key => $widget ) : ?>
				<div class="ste-my-2 ste-w-full md:ste-w-1/2 lg:ste-w-1/3 xl:ste-w-1/4">
					<div class="ste-mx-2">
						<label for="module-label-<?php echo $key; ?>"
							   class="ste-block ste-rounded ste-bg-gradient-to-r ste-from-ash-300 ste-to-ash-200 ste-p-4">
							<div class="ste-flex ste-justify-between ste-items-center">
								<span class="ste-font-medium ste-text-gray-600 ste-text-sm"><?php echo $widget['name']; ?></span>
								<div class="ste-relative">
									<input type="checkbox" name="<?php echo esc_attr( $widget['slug'] ); ?>"
										id="module-label-<?php echo $key; ?>" class="ste-toggle-input" <?php checked( $widget['status'] ); ?>>
									<div class="ste-toggle-line ste-w-5 ste-h-2 ste-bg-ash-600 ste-rounded-full ste-shadow-inner"></div>
									<div class="ste-toggle-dot ste-absolute ste-w-4 ste-h-4 ste-bg-white ste-rounded-full ste-shadow ste-inset-y-0 ste-left-0"></div>
								</div>
							</div>
						</label>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<input type="hidden" name="action" value="stax_woo_widget_activation">
		<?php wp_nonce_field( 'name_of_my_action', 'stax_woo_widget_activation' ); ?>

        <div class="ste-mt-5">
			<button type="submit"
					class="ste-bg-gradient-to-r ste-from-green-500 ste-to-green-400 ste-text-md ste-text-white ste-py-3 ste-px-6 ste-rounded ste-border-0 ste-shadow-xl hover:ste-shadow-lg ste-cursor-pointer">
				<span class="ste-flex ste-items-center">
					<svg class="ste-fill-current" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg>
					<span class="ste-leading-none ste-font-bold ste-ml-2 ste-uppercase"><?php _e( 'Save', 'stax-woo-addons-for-elementor' ); ?></span>
				</span>
			</button>
		</div>
	</form>
</div>
