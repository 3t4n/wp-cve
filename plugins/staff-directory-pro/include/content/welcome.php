<?php
// Company Directory Welcome Page template

ob_start();
$learn_more_url = 'https://goldplugins.com/special-offers/upgrade-to-company-directory-pro/?utm_source=company_directory_free&utm_campaign=welcome_screen_upgrade&utm_content=col_1_learn_more';
$settings_url = menu_page_url('staff_dir-settings', false);
$pro_registration_url =  menu_page_url('company-directory-license-information', false);
$utm_str = '?utm_source=company_directory_free&utm_campaign=welcome_screen_help_links';
$new_post_link = admin_url('post-new.php?post_type=staff-member&guided_tour=1');
?>


<p class="aloha_intro"><strong>Thank you for installing Company Directory!</strong> This page is here to help you get up and running. If you're already familiar with Company Directory, you can skip it and <a href="<?php echo esc_url($settings_url); ?>">continue to the Basic Settings page</a>.</p>
<p class="aloha_tip"><strong>Tip:</strong> You can always access this page via the <strong>Company Directory &raquo; About Plugin</strong> menu.</p>

<br>
<h1 id="getting_started">Getting Started With Company Directory</h1>
<p id="jump_links" class="aloha_jump_links">
Jump To: <a href="#add_staff_members">Adding Staff Members</a> | 
<a href="#display_on_website">Displaying Staff on Your Website</a> | 
<a href="#add_search_box">Adding A Staff Search Box</a>
</p>
<br>
<br>

<h3 id="add_staff_members">Adding Staff Members</h3>
<p>With <?php echo esc_html($plugin_title);?> its easy to add and manage your Staff Members. On the left side of your screen, you'll see a new menu, <strong>Staff Members</strong> - here you can <a href="<?php echo admin_url('post-new.php?post_type=staff-member'); ?>">add new Staff Members</a>, <a href="<?php echo admin_url('edit.php?post_type=staff-member'); ?>">manage and update existing Staff Members</a>, and <a href="<?php echo admin_url('edit-tags.php?taxonomy=staff-member-category&post_type=staff-member'); ?>">use the Staff Categories to group them into departments</a>.</p>

<p>For more information on adding a new Staff member, <a href="https://goldplugins.com/documentation/company-directory-documentation/company-directory-instructions/<?php echo esc_url($utm_str); ?>#add_a_new_staff_member" target="_blank">see our documentation</a>.
</p>
<br>
<h4>Create Your First Staff Member Now</h4>
<p>Click the button below to create your first Staff Member. It will only take a moment, and its easy to understand.</p>
<br>
<a href="<?php echo esc_url($new_post_link); ?>" class="button">Create A New Staff Member &raquo;</a></p>
<br>
<a href="#getting_started">Back To Top</a>
<br>
<br>
	
<h3 id="display_on_website">Displaying Your Staff Directory on Your Website</h3>
<p>Once you've added your Staff Members, you'll want to display them on your website. We have several easy ways to choose from.</p>

<h4>Option 1) Add the shortcode to any page or post</h4>
<p>Simply add the shortcode <span class="aloha_code">&#91;staff_list]</span> to any page or post on your website to display your Staff Directory.</p>
<p>To display an individual member's bio, add the shortcode <span class="aloha_code">&#91;staff_member id="1234"]</span> to any page or post. You'll want to copy the corresponding shortcode for each Staff Member from their Edit Staff Member page.</p>

<h4>Option 2) Use The Editor Buttons</h4>
<p>On any Add/Edit Post or Page screen, you'll now find a new menu - <strong>Staff</strong> - right above the post editor. You can use this menu to add a Staff Directory or individual Staff Member's bio to any post or page. When you select a menu item, you'll be able to select which category to use, which fields to display, and from many more options.</p>

<h4>Option 3) Display Your Staff Directory with a Widget</h4>
<p>When you visit the Widgets screen (found under the Appearance &raquo; Widgets menu), you'll find new widgets that you can add to your sidebars (or any other widgetized area).</p>

<p>The Company Directory - Staff List widget will let you customize and display a list of your staff. You can choose which members to show, and what fields to include, along with many more options.</p>

<p>The Company Directory - Single Staff Member widget will highlight a single Staff Member. You can choose what fields to show along with many more options.</p>

<h4>Further Reading</h4>
<p> For more information on displaying your staff members, please <a href="https://goldplugins.com/documentation/company-directory-documentation/company-directory-instructions/<?php echo esc_url($utm_str); ?>#outputting_staff_members">see our documentation</a>.</p>
<br>
<a href="#getting_started">Back To Top</a>
<br>
<br>

<h3 id="add_search_box">Adding A Search Page</h3>
<p>Adding a staff search page is especially useful if you have a large staff. Company Directory makes it easy to add a Search Staff widget to any page of your website through several means.</p>

<h4>Option 1) Add the [search_staff_members] shortcode to any page or post</h4>
<p>Simply add the shortcode <span class="aloha_code">&#91;search_staff_members]</span> to any page or post on your website to display the Search Staff box anywhere on your website. The search box will appear right where you've placed the code, so feel free to add any text around it.</p>

<h4>Option 2) Use The Editor Buttons</h4>
<p>On any Add/Edit Post or Page screen, you'll now find a new menu - <strong>Staff</strong> - right above the post editor. Click this menu and then select Search Staff Members to add a Staff Search box to any post or page. Before you insert the search box into the page, you'll be able to customize the search box.</p>

<h4>Option 3) Add The Staff Search Widget</h4>
<p>When you visit the Widgets screen (found under the Appearance &raquo; Widgets menu), you'll find the Company Directory - Search Staff Members widget. Add this widget to any sidebar or other widgetized area to display a search box for your staff directory.</p>

<h4>Further Reading</h4>
<p> For more information on Company Directory, please <a href="https://goldplugins.com/documentation/company-directory-documentation/<?php echo esc_url($utm_str); ?>">see our documentation</a>.</p>
<br>
<a href="#getting_started">Back To Top</a>
<br>
<br>

<hr>
<br>
<h1>Helpful Links</h1>
<div class="three_col">
	<div class="col">
		<?php if ($is_pro): ?>
			<h3>Company Directory Pro: Active</h3>
			<p class="plugin_activated">Company Directory Pro is licensed and active.</p>
			<a href="<?php echo esc_url($pro_registration_url); ?>">Registration Settings</a>
		<?php else: ?>
			<h3>Upgrade To Pro</h3>
			<p>Company Directory Pro is the Professional, fully-functional version of Company Directory, which features technical support and access to all Pro&nbsp;features.</p>
			<a class="button" href="<?php echo esc_url($learn_more_url); ?>">Click Here To Learn More</a>		
		<?php endif; ?>
	</div>
	<div class="col">
		<h3>Getting Started</h3>
		<ul>
			<li><a href="<?php echo esc_url($new_post_link); ?>">Click Here To Add Your First Staff Member</a></li>
			<li><a href="https://goldplugins.com/documentation/company-directory-documentation/company-directory-instructions/<?php echo esc_url($utm_str); ?>">Getting Started With Company Directory</a></li>
			<li><a href="https://goldplugins.com/documentation/company-directory-documentation/company-directory-pro-examples/<?php echo esc_url($utm_str); ?>"><?php echo wp_kses($plugin_title, 'strip'); ?> Examples</a></li>
			<li><a href="https://goldplugins.com/documentation/company-directory-documentation/company-directory-faqs/<?php echo esc_url($utm_str); ?>">Frequently Asked Questions (FAQs)</a></li>
			<li><a href="https://goldplugins.com/contact/<?php echo esc_url($utm_str); ?>">Contact Technical Support</a></li>
		</ul>
	</div>
	<div class="col">
		<h3>Further Reading</h3>
		<ul>
			<li><a href="https://goldplugins.com/documentation/company-directory-documentation/<?php echo esc_url($utm_str); ?>">Company Directory Documentation</a></li>
			<li><a href="https://wordpress.org/support/plugin/staff-directory-pro/<?php echo esc_url($utm_str); ?>">WordPress Support Forum</a></li>
			<li><a href="https://goldplugins.com/documentation/company-directory-documentation/company-directory-pro-changelog/<?php echo esc_url($utm_str); ?>">Recent Changes</a></li>
			<li><a href="https://goldplugins.com/<?php echo esc_url($utm_str); ?>">Gold Plugins Website</a></li>
		</ul>
	</div>
</div>

<div class="continue_to_settings">
	<p><a href="<?php echo esc_url($settings_url); ?>">Continue to Basic Settings &raquo;</a></p>
</div>

<?php 
$content =  ob_get_contents();
ob_end_clean();
return $content;
