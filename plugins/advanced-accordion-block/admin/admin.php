<?php
/**
 * Admin Support Page
*/

class AAB_Admin_Page {
    /**
     * Contructor
    */
    public function __construct(){
        add_action( 'admin_menu', [ $this, 'aab_plugin_admin_page' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'aab_admin_page_assets' ] );
    }

    // Admin Assets
    public function aab_admin_page_assets() {
		$page = $_GET['page'] ?? '';
		if ( $page == 'advanced-accordion-block' ) {
			wp_enqueue_style('aab-admin-css', plugins_url('assets/css/dashboard-app.css', __FILE__));
		}
    }

    // Admin Page
    public function aab_plugin_admin_page(){
        add_submenu_page( 'options-general.php', 'Accordion Block', 'Accordion Block', 'manage_options', 'advanced-accordion-block', [ $this, 'aab_admin_page_content_callback' ] );
    }

    public function aab_admin_page_content_callback(){
        ?>
		<main class="py-[2.43rem]">
			<div class="max-w-3xl mx-auto px-6 lg:max-w-7xl">
				<h1 class="sr-only">Page title</h1>
				<div class="grid grid-cols-1 gap-4 items-start lg:grid-cols-3 lg:gap-5 xl:gap-10">
					<div class="grid grid-cols-1 gap-4 lg:col-span-2 h-full">
						<section aria-labelledby="section-1-title h-full">
							<h2 class="sr-only" id="section-1-title"> Section title</h2>
							<div class="rounded-md bg-white overflow-hidden shadow-sm flex flex-col justify-center h-full">
								<div class="p-12 pt-[2.2rem]">
									<h2 class="text-slate-800 text-[1.8rem] leading-[2.4rem] pb-3 font-medium text-left"> Welcome to Advanced Accordion Block!</h2>
									<p class="text-slate-500 pb-7">Advanced Accordion Gutenberg Block is Built with Gutenberg Native Components that allows you to build a FAQs Section or Accordion easily on your site post and page using Gutenberg Editor.</p>
									<div class="mr-[80px] aab-video-container">
										<iframe width="712" height="380" src="https://www.youtube.com/embed/gZ_zL6RwQhA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
									</div>
									<span class="relative z-0 inline-flex pt-6 justify-start w-full">
										<a href="<?php echo admin_url('post-new.php?post_type=page'); ?>" target="_blank" class="aab-btn btn-fill inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-aab focus-visible:bg-aab-hover hover:bg-aab-hover focus:outline-none mr-4">
											Create New Page
										</a>
										<a href="http://spider-themes.net/" target="_blank" class="aab-btn inline-flex items-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-500 bg-white focus-visible:bg-slate-50 hover:bg-slate-50 focus:outline-none">
											Visit Our Website
										</a>
									</span>
								</div>
							</div>
						</section>
					</div>
					<div class="space-y-4 flex h-full flex-col justify-between">
						<section aria-labelledby="section-2-title">
							<h2 class="sr-only" id="section-2-title">Section title</h2>
							<div class="box-border rounded-md bg-white shadow-sm overflow-hidden transition hover:shadow-hover">
								<div class="p-8 pr-10">
									<h3 class="text-slate-800 text-xl font-medium pb-2"> Key Features: </h3>
									<p class="text-slate-500 text-sm pb-2 pr-2">
										Advanced Accordion Gutenberg Block has unlimited Styling with Custom Margin, Padding, Border Radius, Color, Background, Tag Selection, etc.
									</p>
									<ul>
										<li>Highly Customizable</li>
										<li>Built with Gutenberg Native Components</li>
										<li>Unlimited Nested Accordion</li>
										<li>SEO Friendly</li>
										<li>All Types of Content Supported</li>
										<li>Responsive & Retina Ready</li>
										<li>Super Fast, Slick</li>
										<li>5 Pairs of Icons</li>
										<li>Make Active Accordion on Page Load</li>
									</ul>
								</div>
							</div>
						</section>
						<section aria-labelledby="section-2-title">
							<h2 class="sr-only" id="section-2-title">Section title</h2>
							<div class="box-border rounded-md bg-white shadow-sm overflow-hidden transition hover:shadow-hover">
								<div class="p-8 pr-10">
									<h3 class="text-slate-800 text-xl font-medium pb-2">Get 5-star Support</h3>
									<p class="text-slate-500 text-sm pb-2 pr-2">Need some help? Our awesome support team is here to help you with any question you have.</p>
									<a class="text-base text-aab focus:text-aab focus-visible:text-aab-hover active:text-aab-hover hover:text-aab-hover underline" href="https://wordpress.org/support/plugin/advanced-accordion-block/" target="_blank" rel="noreferrer">Get Support</a>
									<a class="text-base text-aab focus:text-aab focus-visible:text-aab-hover active:text-aab-hover hover:text-aab-hover no-underline" href="https://wordpress.org/support/plugin/advanced-accordion-block/" target="_blank" rel="noreferrer"> → </a>
								</div>
							</div>
						</section>
					</div>
				</div>
			</div>
		</main>
        <?php
    }
}
 new AAB_Admin_Page();
