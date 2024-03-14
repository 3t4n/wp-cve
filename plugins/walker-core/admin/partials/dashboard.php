<div class="walker-core-dashboard">
	<div class="container">
		<h1 class="dashboard-heading"><?php echo __('Welcome to the Walker Core Dashboard', 'walker-core'); ?></h1>
		<div class="dashboard-box">
			<div class="dashboard-info">
				<?php echo __('Walker Core is the companion plugin for the themes of WalkerWP, which provides core functionlity and custom post types for the themes.', 'walker-core');

				echo  ' <a href="' . esc_url('https://walkerwp.com/walker-core/') . '"target="_blank" aria-label="' . esc_attr__('View More About Walker Core', 'walker-core') . '">' . esc_html__('View More About Walker Core', 'walker-core') . '</a>';

				?>
			</div>

			<?php
			$wc_current_theme = wp_get_theme()->get('Name');
			$wc_parent_theme = wp_get_theme(get_template())->get('Name');
			if ($wc_current_theme !== $wc_parent_theme) {
				//echo "The theme '$wc_current_theme' is a child theme of '$wc_parent_theme'.";
				$wc_activated_theme = $wc_parent_theme;
			} else {
				//echo "The theme '$wc_current_theme' is not a child theme.";
				$wc_activated_theme = $wc_current_theme;
			}
			$walker_theme_list = array("Gridchamp", "Walker Charity", "WalkerMag", "WalkerShop", "WalkerPress", "MularX", "BlockVerse");

			if (in_array($wc_activated_theme, $walker_theme_list)) {
				// Parent theme name

			?>
				<div class="walker-dashboard-col-half">
					<?php $theme = wp_get_theme();
					if ('Gridchamp' == $theme->name || 'Gridchamp' == $theme->parent_theme || 'Walker Charity' == $theme->name || 'Walker Charity' == $theme->parent_theme) :
						echo '<h3>' . __('Available Custom Post Types for Pro Features', 'walker-core') . '</h3>';
					endif; ?>
					<ul class="walker-core-features">
						<?php
						if ('Gridchamp' == $theme->name || 'Gridchamp' == $theme->parent_theme || 'Walker Charity' == $theme->name || 'Walker Charity' == $theme->parent_theme) : ?>
							<li>Slider</li>
							<li>Testimonials</li>
							<li>Portfolios</li>
							<li>Teams</li>
							<li>Brands</li>
						<?php endif; ?>
						<?php if ('Gridchamp' == $theme->name || 'Gridchamp' == $theme->parent_theme) : ?>
							<li>FAQs</li>
						<?php endif; ?>

					</ul>
					<?php
					if ('WalkerMag' == $theme->name || 'WalkerMag' == $theme->parent_theme) : ?>
						<?php echo '<h3>' . __('Available Widgets for Pro Features', 'walker-core') . '</h3>'; ?>
						<ul class="walker-core-widgets">
							<li><?php echo '<h4>' . __('WalkerWP Category Post', 'walker-core') . '</h4>'; ?>
								<?php echo '<p>' . __('This widgets helps to add recent post blogs in anywhere in the dyanmic sidebar like footer, sidebar of the themes', 'walker-core') . '</p>'; ?>
							</li>
							<li><?php echo '<h4>' . __('WalkerWP Popular Post', 'walker-core') . '</h4>'; ?>
								<?php echo '<p>' . __('This widgets helps to add social medias icons in anywhere in the dyanmic sidebar like footer, sidebar of the themes', 'walker-core') . '</p>'; ?>
							</li>


						</ul>
					<?php endif;
					if ('WalkerPress' == $theme->name || 'WalkerPress' == $theme->parent_theme) : ?>
						<?php echo '<h3>' . __('Available Widgets for Pro Features', 'walker-core') . '</h3>'; ?>
						<ul class="walker-core-widgets">
							<li><?php echo '<h4>' . __('WalkerPress: Popular Post', 'walker-core') . '</h4>'; ?>
								<?php echo '<p>' . __('This widgets helps to add popular post in anywhere in the dyanmic sidebar like footer, sidebar of the themes', 'walker-core') . '</p>'; ?>
							</li>
							<li><?php echo '<h4>' . __('WalkerPress: Social Media', 'walker-core') . '</h4>'; ?>
								<?php echo '<p>' . __('This widgets helps to add social medias icons in anywhere in the dyanmic sidebar like footer, sidebar of the themes', 'walker-core') . '</p>'; ?>
							</li>
							<li><?php echo '<h4>' . __('WalkerPress: Newsletter', 'walker-core') . '</h4>'; ?>
								<?php echo '<p>' . __('This widgets helps to add newsletter form anywhere in the dyanmic sidebar like footer, sidebar of the themes', 'walker-core') . '</p>'; ?>
							</li>



						</ul>

					<?php
						echo  ' <a href="' . esc_url('https://walkerwp.com/walkerpress') . '"target="_blank" aria-label="' . esc_attr__('View More Premium Features', 'walker-core') . '">' . esc_html__('View More Premium Features', 'walker-core') . '</a>';
					endif;

					if ('WalkerShop' == $theme->name || 'WalkerShop' == $theme->parent_theme) : ?>
						<?php echo '<h2 class="walkershop-features-title">' . __('More Homepage Section/Features for Pro Version', 'walker-core') . '</h2>'; ?>
						<ul class="walker-core-widgets walkershop-features-list">
							<li><?php echo __('Total 18+ Sections (Including free sections)', 'walker-core'); ?></li>
							<li><?php echo __('Multiple Banner/Slider Layouts', 'walker-core'); ?></li>
							<li><?php echo  __('Latest Product Section > Multiple Layout with multiple Column', 'walker-core'); ?></li>
							<li><?php echo __('Top Selling Product Section > Multiple Layout with multiple Column', 'walker-core'); ?></li>
							<li><?php echo __('Top Rating Product Section > Multiple Layout with multiple column', 'walker-core'); ?></li>
							<li><?php echo __('Shop By Category > Category list with multiple layout', 'walker-core'); ?></li>
							<li><?php echo  __('Featured Box Section > Section for display advertisement or Additional context box.', 'walker-core'); ?></li>
							<li><?php echo  __('Featured Products Section > Display featured products with multiple layout and columns', 'walker-core'); ?></li>
							<li><?php echo  __('Featured Products Section > Display featured products with multiple layout and columns', 'walker-core'); ?></li>
							<li><?php echo  __('Flash Sale Products Section > Display onsale products with multiple layout and columns', 'walker-core'); ?></li>
							<li><?php echo  __('Products Showcase Section > Hilight beautiful products with slide', 'walker-core'); ?></li>
							<li><?php echo  __('Special Offer CTA Section > Display additional advertisement/shocase of products.', 'walker-core'); ?></li>
							<li><?php echo  __('Recommended Products Tab Section > Display latest, onsale and popular/top selling products in same section with tab content layout.', 'walker-core'); ?></li>
							<li><?php echo  __('Testimonial Section > Display Testimonials with multiple layout.', 'walker-core'); ?></li>
							<li><?php echo  __('Brands Logo Showcase Section > Display your partners/brands/sponsors logo with multiple layout', 'walker-core'); ?></li>
							<li><?php echo  __('Featured Category Products Tab Section > Display multiple products from multiple categories (upto 5 categories) in same section with tab content layout.', 'walker-core'); ?></li>
							<li><?php echo  __('Newsletter Section > Display beautiful newsletter section with form to gather and track your customers!', 'walker-core'); ?></li>
							<li><?php echo  __('Section Re-orders > Re-order the homepage section easily as your requirement with just drag and drop featrues.', 'walker-core'); ?></li>




						</ul>

					<?php
						echo  ' <a class="walkershop-more-features" href="' . esc_url('https://walkerwp.com/walkershop') . '"target="_blank" aria-label="' . esc_attr__('View More Premium Features', 'walker-core') . '">' . esc_html__('View More Premium Features', 'walker-core') . '</a>';
					endif;



					if ('MularX' == $theme->name || 'MularX' == $theme->parent_theme) : ?>
						<?php echo '<h2 class="walkershop-features-title">' . __('More Homepage Section/Features for Pro Version', 'walker-core') . '</h2>'; ?>
						<ul class="walker-core-widgets walkershop-features-list">
							<li><?php echo __('Total 15 Sections (Including free sections)', 'walker-core'); ?></li>
							<li><?php echo __('Multiple Banner/Slider Layouts', 'walker-core'); ?></li>
							<li><?php echo  __('Latest Product Section > Multiple Layout with multiple Column', 'walker-core'); ?></li>
							<li><?php echo  __('Testimonial Section > Display Testimonials with multiple layout.', 'walker-core'); ?></li>
							<li><?php echo  __('Brands Logo Showcase Section > Display your partners/brands/sponsors logo with multiple layout', 'walker-core'); ?></li>
							<li><?php echo  __('Team Section > Display teams from custom post type', 'walker-core'); ?></li>
							<li><?php echo  __('Portfolio Section > Display portfolios from custom post type', 'walker-core'); ?></li>
							<li><?php echo  __('Number Counter Section > Display number countder section layout', 'walker-core'); ?></li>
							<li><?php echo  __('Section Re-orders > Re-order the homepage section easily as your requirement with just drag and drop featrues.', 'walker-core'); ?></li>

						</ul>

					<?php
						echo  ' <a class="walkershop-more-features" href="' . esc_url('https://walkerwp.com/mularx') . '"target="_blank" aria-label="' . esc_attr__('View More Premium Features', 'walker-core') . '">' . esc_html__('View More Premium Features', 'walker-core') . '</a>';
					endif;

					if ('BlockVerse' == $theme->name || 'BlockVerse' == $theme->parent_theme) :
						echo '<h2 class="blockverse-features-title">' . __('BlockVerse Free Features', 'walker-core') . '</h2>'; ?>
						<ul class="blockverse-features">
							<li><strong> - <?php echo __('Offer 11 Home Sections and Patterns', 'walker-core') ?></strong>
								<ul>
									<li> <?php echo __('Hero section pattern', 'walker-core') ?></li>
									<li> <?php echo __('About section pattern', 'walker-core') ?></li>
									<li> <?php echo __('Service section pattern', 'walker-core') ?></li>
									<li> <?php echo __('Team section pattern', 'walker-core') ?></li>
									<li> <?php echo __('Testimonial section pattern', 'walker-core') ?></li>
									<li> <?php echo __('Brands Logo section pattern', 'walker-core') ?></li>
									<li> <?php echo __('CTA section pattern', 'walker-core') ?></li>
									<li> <?php echo __('Pricing Table section pattern', 'walker-core') ?></li>
									<li> <?php echo __('Project/Portfolio Section Pattern', 'walker-core') ?></li>
									<li> <?php echo __('FAQ section pattern', 'walker-core') ?></li>
									<li> <?php echo __('Blog section pattern', 'walker-core') ?></li>
								</ul>
							</li>

							<li> <strong>- <?php echo __('15+ FSE Templates Ready', 'walker-core') ?></strong>
								<ul>
									<li> <?php echo __('404 Template', 'walker-core') ?></li>
									<li> <?php echo __('Search Template', 'walker-core') ?></li>
									<li> <?php echo __('Sitemap Template', 'walker-core') ?></li>
									<li> <?php echo __('Page Template', 'walker-core') ?></li>
									<li> <?php echo __('Left Sidebar Page Template', 'walker-core') ?></li>
									<li> <?php echo __('Right Sidebar Template', 'walker-core') ?></li>
									<li> <?php echo __('Blank Template', 'walker-core') ?></li>
									<li> <?php echo __('Full Width Page  Template', 'walker-core') ?></li>
									<li> <?php echo __('Single Template', 'walker-core') ?></li>
									<li> <?php echo __('Left Sidebar Single Template', 'walker-core') ?></li>
									<li> <?php echo __('Right Sidebar Single Template', 'walker-core') ?></li>
									<li> <?php echo __('Archive Template', 'walker-core') ?></li>
									<li> <?php echo __('WooCommerce ProductA rchive Template', 'walker-core') ?></li>
									<li> <?php echo __('WooCommerce Cart Page Template', 'walker-core') ?></li>
									<li> <?php echo __('WooCommerce Checkout Page Template', 'walker-core') ?></li>
									<li> <?php echo __('WooCommerce Single Page Template', 'walker-core') ?></li>
								</ul>
							<li>
							<li><strong> - <?php echo __('2 Header Layouts', 'walker-core') ?></strong></li>
							<li> <strong>- <?php echo __('2 Footer Layouts', 'walker-core') ?></strong></li>
							<li><strong> - <?php echo __('12+ Beautiful Fonts Option', 'walker-core') ?></strong></li>
							<li> <strong>- <?php echo __('3 Styles Variations', 'walker-core') ?></strong></li>
						</ul>

					<?php endif;
					?>
				</div>
				<div class="walker-dashboard-col-half right-part">
					<?php if ('Gridchamp' == $theme->name || 'Gridchamp' == $theme->parent_theme || 'WalkerMag' == $theme->name || 'WalkerMag' == $theme->parent_theme) : ?>
						<?php echo '<h3>' . __('Available Widgets for Pro Features', 'walker-core') . '</h3>'; ?>
						<ul class="walker-core-widgets">
							<li><?php echo '<h4>' . __('WalkerWP Recent Blog', 'walker-core') . '</h4>'; ?>
								<?php echo '<p>' . __('This widgets helps to add recent post blogs in anywhere in the dyanmic sidebar like footer, sidebar of the themes', 'walker-core') . '</p>'; ?>
							</li>
							<li><?php echo '<h4>' . __('WalkerWP Social Media', 'walker-core') . '</h4>'; ?>
								<?php echo '<p>' . __('This widgets helps to add social medias icons in anywhere in the dyanmic sidebar like footer, sidebar of the themes', 'walker-core') . '</p>'; ?>
							</li>
							<li><?php echo '<h4>' . __('WalkerWP Address Box', 'walker-core') . '</h4>'; ?>
								<?php echo '<p>' . __('This widgets helps to add Address box information in anywhere in the dyanmic sidebar like footer, sidebar of the themes', 'walker-core') . '</p>'; ?>
							</li>

						</ul>
					<?php endif; ?>
					<?php if ('Walker Charity' == $theme->name || 'Walker Charity' == $theme->parent_theme) : ?>
						<?php echo '<h3>' . __('Available Widgets for Pro Features', 'walker-core') . '</h3>'; ?>
						<ul class="walker-core-widgets">
							<li><?php echo '<h4>' . __('WalkerWP Social Media', 'walker-core') . '</h4>'; ?>
								<?php echo '<p>' . __('This widgets helps to add social medias icons in anywhere in the dyanmic sidebar like footer, sidebar of the themes', 'walker-core') . '</p>'; ?>
							</li>
						</ul>
					<?php endif;
					if ('WalkerPress' == $theme->name || 'WalkerPress' == $theme->parent_theme) : ?>
						<ul class="walker-core-widgets">
							<?php echo '<h3>' . __('Available Widgets for Pro Features', 'walker-core') . '</h3>'; ?>
							<li><?php echo '<h4>' . __('WalkerPress: Single Category', 'walker-core') . '</h4>'; ?>
								<?php echo '<p>' . __('This widgets helps to add single category section with 8 different layouts anywhere in the dyanmic sidebar like footer, sidebar of the themes but specially cretaed for frontpage.', 'walker-core') . '</p>'; ?>
							</li>
							<li><?php echo '<h4>' . __('WalkerPress: Double Category', 'walker-core') . '</h4>'; ?>
								<?php echo '<p>' . __('This widgets helps to add section with two category section with 6 different layouts anywhere in the dyanmic sidebar like footer, sidebar of the themes but specially cretaed for frontpage.', 'walker-core') . '</p>'; ?>
							</li>
							<li><?php echo '<h4>' . __('WalkerPress: Three Category', 'walker-core') . '</h4>'; ?>
								<?php echo '<p>' . __('This widgets helps to add section with three category section with 6 different layouts anywhere in the dyanmic sidebar like footer, sidebar of the themes but specially cretaed for frontpage.', 'walker-core') . '</p>'; ?>
							</li>
						</ul>
					<?php endif;

					if ('WalkerShop' == $theme->name || 'WalkerShop' == $theme->parent_theme) : ?>
						<ul class="walker-core-widgets walkershop-features-list">
							<?php echo '<h2 class="walkershop-features-title">' . __('More Theme Features for Pro Version', 'walker-core') . '</h2>'; ?>
							<li><?php echo __('Multiple Header Layouts (4 Layouts)', 'walker-core'); ?></li>
							<li><?php echo __('Header overlay on banner option', 'walker-core'); ?></li>
							<li><?php echo __('Custom Color Scheme for Headers', 'walker-core'); ?></li>
							<li><?php echo __('Multiple banner/slider layout', 'walker-core'); ?></li>
							<li><?php echo __('Custom Color Scheme and Google font option  for Banner', 'walker-core'); ?></li>
							<li><?php echo __('WooCommerce shop/single page Sidebar options', 'walker-core'); ?></li>
							<li><?php echo __('Post meta advacne setting options', 'walker-core'); ?></li>
							<li><?php echo __('Advanced option for Blog page', 'walker-core'); ?></li>
							<li><?php echo __('Advanced option for single post', 'walker-core'); ?></li>
							<li><?php echo __('Container and sidebar width option', 'walker-core'); ?></li>
							<li><?php echo __('Global button setting options', 'walker-core'); ?></li>
							<li><?php echo __('Multiple layout for social media icons and custom colors option', 'walker-core'); ?></li>
							<li><?php echo __('Breadcrumbs setting options', 'walker-core'); ?></li>
							<li><?php echo __('Multiple footer layouts and custom color scheme options', 'walker-core'); ?></li>
							<li><?php echo __('Copyright text add/remove option', 'walker-core'); ?></li>
						</ul>
					<?php
						echo  ' <a class="walkershop-more-features" href="' . esc_url('https://walkerwp.com/walkershop') . '"target="_blank" aria-label="' . esc_attr__('Upgrade to Pro', 'walker-core') . '">' . esc_html__('Upgrade to Pro', 'walker-core') . '</a>';


					endif;


					if ('MularX' == $theme->name || 'MularX' == $theme->parent_theme) : ?>
						<ul class="walker-core-widgets walkershop-features-list">
							<?php echo '<h2 class="walkershop-features-title">' . __('More Theme Features for Pro Version', 'walker-core') . '</h2>'; ?>
							<li><?php echo __('Multiple Header Layouts (6 Layouts)', 'walker-core'); ?></li>
							<li><?php echo __('Custom Color Scheme for Headers', 'walker-core'); ?></li>
							<li><?php echo __('Multiple banner/slider layout', 'walker-core'); ?></li>
							<li><?php echo __('Custom Color Scheme and Google font option  for Banner', 'walker-core'); ?></li>
							<li><?php echo __('WooCommerce shop/single page Sidebar options', 'walker-core'); ?></li>
							<li><?php echo __('Post meta advacne setting options', 'walker-core'); ?></li>
							<li><?php echo __('Advanced option for Blog page', 'walker-core'); ?></li>
							<li><?php echo __('Advanced option for single post', 'walker-core'); ?></li>
							<li><?php echo __('Container and sidebar width option', 'walker-core'); ?></li>
							<li><?php echo __('Global button setting options', 'walker-core'); ?></li>
							<li><?php echo __('Multiple layout for social media icons and custom colors option', 'walker-core'); ?></li>
							<li><?php echo __('Subheader/Breadcrumbs setting options', 'walker-core'); ?></li>
							<li><?php echo __('Multiple footer layouts and custom color scheme options', 'walker-core'); ?></li>
							<li><?php echo __('Copyright text add/remove option', 'walker-core'); ?></li>
						</ul>
					<?php
						echo  ' <a class="walkershop-more-features" href="' . esc_url('https://walkerwp.com/mularx') . '"target="_blank" aria-label="' . esc_attr__('Upgrade to Pro', 'walker-core') . '">' . esc_html__('Upgrade to Pro', 'walker-core') . '</a>';


					endif;
					if ('BlockVerse' == $theme->name || 'BlockVerse' == $theme->parent_theme) :
						echo '<h2 class="blockverse-features-title">' . __('Premium Features', 'walker-core') . '</h2>'; ?>
						<ul class="blockverse-features premium-features">
							<li><?php echo __('Include all free Features and comes with more 60+ Premium patterns', 'walker-core') ?></strong></li>
							<li><?php echo __('Header Layout - 6', 'walker-core') ?></li>
							<li><?php echo __('Footer Layout - 6', 'walker-core') ?></li>
							<li><?php echo __('Banner Layout - 5', 'walker-core') ?></li>
							<li><?php echo __('Magazine Layout Patterns - 9', 'walker-core') ?></li>
							<li><?php echo __('WooCommerce Product Layout Patterns - 5', 'walker-core') ?></li>
							<li><?php echo __('Latest Blog Patterns - 3 ', 'walker-core') ?></li>
							<li><?php echo __('Testimonials Layout Patterns - 6', 'walker-core') ?></li>
							<li><?php echo __('Teams Layout Pattern - 4', 'walker-core') ?></li>
							<li><?php echo __('FAQ Layout Patterns - 2', 'walker-core') ?></li>
							<li><?php echo __('Stats Counter Layout Patterns - 4', 'walker-core') ?></li>
							<li><?php echo __('Contact Section Patterns - 4', 'walker-core') ?></li>
							<li><?php echo __('Pricing Table Patterns - 3', 'walker-core') ?></li>
							<li><?php echo __('About Layout Patterns - 3', 'walker-core') ?></li>
							<li><?php echo __('Service Layout Patterns - 4', 'walker-core') ?></li>
							<li><?php echo __('CTA Layout Patterns - 2+', 'walker-core') ?></li>
						</ul>
						<a href="https://walkerwp.com/pricing-plans/" class="upgrade-btn button" target="_blank"><?php echo __('Upgrade to Pro', 'blockpress') ?></a>
					<?php endif;
					echo '</div>';
				} else {
					echo '<div class="theme-not-found">';
					echo '<h3>' . __("To ensure proper functionality, please use any of our following themes to use Walker Core.", 'walker-core') . '</h3>';
					?>
					<ul>
						<li><strong><a href="https://walkerwp.com/blockverse/" target="_blank"><?php echo __('BlockVerse - Modern and FSE WordPress Theme', 'walker-core'); ?></a></strong></li>
						<li><?php echo __('Gridchamp', 'walker-core'); ?></li>
						<li><?php echo __('WalkerShop', 'walker-core'); ?></li>
						<li><?php echo __('Walker Charity', 'walker-core'); ?></li>
						<li><a href="https://walkerwp.com/walkerpress/" target="_blank"><?php echo __('WalkerPress - recommended for magazine/news based traditional theme', 'walker-core'); ?></a></li>
						<li><?php echo __('MularX', 'walker-core'); ?></li>
						<li><?php echo __('WalkerMag', 'walker-core'); ?></li>
						<li><?php echo __('WalkerNews', 'walker-core'); ?></li>
						<li><?php echo __('BlodNews', 'walker-core'); ?></li>
						<li><?php echo __('Domestic Services', 'walker-core'); ?></li>
						<li><?php echo __('Trending News', 'walker-core'); ?></li>
						<li><?php echo __('DraftNews', 'walker-core'); ?></li>
						<li><?php echo __('XposeNews', 'walker-core'); ?></li>
						<li><?php echo __('XpoMagazine', 'walker-core'); ?></li>
						<li><?php echo __('Home Care', 'walker-core'); ?></li>
						<li><?php echo __('ShopCommerce', 'walker-core'); ?></li>
						<li><?php echo __('Story News', 'walker-core'); ?></li>
						<li><?php echo __('Business Launcher', 'walker-core'); ?></li>
					</ul>
				</div>
			<?php }
			?>

		</div>
		<div class="faqs">
			<?php echo '<h1>' . __('Few FAQs about this plugin', 'walker-core') . '</h1>'; ?>
			-------------------------
			<ul>
				<li><?php echo '<h2>' . __('Is Walker Core is a free plugin?', 'walker-core') . '</h2>'; ?>
					<p><?php echo __('- Yes, it is a free plugin.', 'walker-core'); ?></p>
				</li>
				<li><?php echo '<h2>' . __('Can i use this plugin for any theme?', 'walker-core') . '</h2>'; ?></h2>
					<p><?php echo __('This is the companion plugin for themes of WalkerWP so we don’t recommended to using with other theme.', 'walker-core'); ?></p>
				</li>
			</ul>
			-------------------------
			<?php echo '<h2>' . __('Thank You For Choosing Us!!!', 'walker-core') . '</h2>'; ?>
		</div>
	</div>
</div>