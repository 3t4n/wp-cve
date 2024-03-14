<?php 
// no direct access!
defined('ABSPATH') or die("No direct access");
?>

<div class="dashcontwrapper">
	<div class="dashboard_header_1">
		<div class="dashboard_title">All Widgets</div>
		<div class="gsp_dash_b">
			<div class="dashboard_button add_shortcode_widgets_event_holder">Load Shortcode Widgets</div>
			<div class="dashboard_button add_widget_event_holder">Add new widget</div>
		</div>
	</div>
	<div class="dashboard_tabs_wrapper">
		<div class="dashboard_tab ss_selected">Widgets</div>
	</div>

	<div class="dashboard_content gsp_active_c_tab" data-index="">

		<div  class="items_wrapper gsp_widgets_table items_filter_table" data-order_table="gspeech_ssblocks" data-website_id="">
			<div class="items_wrapper_overlay"></div>
			<div class="items_header">
				<div class="items_count"></div>
				<div class="items_options">
					<div class="search_items_wrapper">
						<span class="search_icon_holder search_icon_pasive">
							<svg aria-hidden="true" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M508.5 468.9L387.1 347.5c-2.3-2.3-5.3-3.5-8.5-3.5h-13.2c31.5-36.5 50.6-84 50.6-136C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c52 0 99.5-19.1 136-50.6v13.2c0 3.2 1.3 6.2 3.5 8.5l121.4 121.4c4.7 4.7 12.3 4.7 17 0l22.6-22.6c4.7-4.7 4.7-12.3 0-17zM208 368c-88.4 0-160-71.6-160-160S119.6 48 208 48s160 71.6 160 160-71.6 160-160 160z"></path></svg>
						</span>
						<input type="text" placeholder="Search" class="search_input" data-search_val="" />
						<span class="reset_search">
							<svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm101.8-262.2L295.6 256l62.2 62.2c4.7 4.7 4.7 12.3 0 17l-22.6 22.6c-4.7 4.7-12.3 4.7-17 0L256 295.6l-62.2 62.2c-4.7 4.7-12.3 4.7-17 0l-22.6-22.6c-4.7-4.7-4.7-12.3 0-17l62.2-62.2-62.2-62.2c-4.7-4.7-4.7-12.3 0-17l22.6-22.6c4.7-4.7 12.3-4.7 17 0l62.2 62.2 62.2-62.2c4.7-4.7 12.3-4.7 17 0l22.6 22.6c4.7 4.7 4.7 12.3 0 17z"></path></svg>
						</span>
					</div>

					<div style="width:150px;" class="items_select_filter_wrapper" data-filter_id="gm.status">
						<div class="items_select_filter">
							<div class="items_select_filter_content"><span>Select status</span><input type="text" class="li_search_input" /></div>
							<div class="items_select_filter_icon_wrapper">
								<div class="items_select_filter_icon_holder">
									<div class="items_select_filter_icon_inner">
										<span class="items_select_filter_icon">
											<svg class="" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"></path></svg>
										</span>
									</div>
								</div>
							</div>

							<div class="items_select_ul_wrapper">
								<div class="items_select_ul_holder">
									<div class="items_select_ul_inner">
										<ul class="items_select_ul">
											<li data-val="-1" class="search_li li_def li_selected"><span>Select status</span></li>
											<li data-val="1" class="search_li"><span>Published</span></li>
											<li data-val="0" class="search_li"><span>Unpublished</span></li>
										</ul>
									</div>
								</div>
							</div>

						</div>
					</div>

					<div style="width:170px;" class="items_select_filter_wrapper" data-filter_id="gm.block_type">
						<div class="items_select_filter">
							<div class="items_select_filter_content"><span>Select Type</span><input type="text" class="li_search_input" /></div>
							<div class="items_select_filter_icon_wrapper">
								<div class="items_select_filter_icon_holder">
									<div class="items_select_filter_icon_inner">
										<span class="items_select_filter_icon">
											<svg class="" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"></path></svg>
										</span>
									</div>
								</div>
							</div>

							<div class="items_select_ul_wrapper">
								<div class="items_select_ul_holder">
									<div class="items_select_ul_inner">
										<ul class="items_select_ul">
											<li data-val="-1" class="search_li li_def li_selected"><span>Select Type</span></li>
											<li data-val="0" class="search_li"><span>Full Player</span></li>
											<li data-val="1" class="search_li"><span>Button Player</span></li>
											<li data-val="2" class="search_li"><span>Circle Player</span></li>
											<li data-val="3" class="search_li"><span>RHT Player</span></li>
											<li data-val="4" class="search_li"><span>Welcome Message</span></li>
											<li data-val="5" class="search_li"><span>Speaking Links</span></li>
										</ul>
									</div>
								</div>
							</div>

						</div>
					</div>

					<div class="itms_pages_limit items_select_filter_wrapper" data-filter_id="limit">
						<div class="items_select_filter">
							<div class="items_select_filter_content"><span>20</span><input type="text" class="li_search_input" /></div>
							<div class="items_select_filter_icon_wrapper">
								<div class="items_select_filter_icon_holder">
									<div class="items_select_filter_icon_inner">
										<span class="items_select_filter_icon">
											<svg class="" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"></path></svg>
										</span>
									</div>
								</div>
							</div>
							<div class="items_select_ul_wrapper">
								<div class="items_select_ul_holder">
									<div class="items_select_ul_inner">
										<ul class="items_select_ul">
											<li data-val="5" class="search_li"><span>5</span></li>
											<li data-val="10" class="search_li"><span>10</span></li>
											<li data-val="20" class="search_li li_selected"><span>20</span></li>
											<li data-val="50" class="search_li"><span>50</span></li>
											<li data-val="100" class="search_li"><span>100</span></li>
											<li data-val="200" class="search_li"><span>200</span></li>
											<li data-val="0" class="search_li"><span>All</span></li>
										</ul>
									</div>
								</div>
							</div>

						</div>
					</div>

				</div>
			</div>
			<div class="items_body">
				<div class="items_body_header">
					<div class="item_row_item_h item_row_item_2">
						<span class="itm_has_ord" data-order_field="gm.name">
							<span class="item_t">Name</span>
							<span class="item_t_order">
								<svg viewBox="0 0 6 12" fill="none" xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision" style="width: 6px; height: 12px;">
									<g style="stroke: none; stroke-width: 0; fill: currentcolor; fill-rule: evenodd; clip-rule: evenodd;">
										<path d="M0 4L3 0L6 4L0 4Z"></path>
										<path d="M6 8L3 12L0 8L6 8Z"></path>
									</g>
								</svg>
							</span>
						</span>
					</div>
					<div class="item_row_item_h item_row_item_3_0">
						<span class="itm_has_ord" data-order_field="gm.block_type">
							<span class="item_t">Type</span>
							<span class="item_t_order">
								<svg viewBox="0 0 6 12" fill="none" xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision" style="width: 6px; height: 12px;">
									<g style="stroke: none; stroke-width: 0; fill: currentcolor; fill-rule: evenodd; clip-rule: evenodd;">
										<path d="M0 4L3 0L6 4L0 4Z"></path>
										<path d="M6 8L3 12L0 8L6 8Z"></path>
									</g>
								</svg>
							</span>
						</span>
					</div>
					<div class="item_row_item_h item_row_item_1">
						<span class="itm_has_ord" data-order_field="gm.lang">
							<span class="item_t">Language</span>
							<span class="item_t_order">
								<svg viewBox="0 0 6 12" fill="none" xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision" style="width: 6px; height: 12px;">
									<g style="stroke: none; stroke-width: 0; fill: currentcolor; fill-rule: evenodd; clip-rule: evenodd;">
										<path d="M0 4L3 0L6 4L0 4Z"></path>
										<path d="M6 8L3 12L0 8L6 8Z"></path>
									</g>
								</svg>
							</span>
						</span>
					</div>
					
					<div class="item_row_item_h item_row_item_3_0">
						<span class="itm_has_ord" data-order_field="gm.voice">
							<span class="item_t">Voice</span>
							<span class="item_t_order">
								<svg viewBox="0 0 6 12" fill="none" xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision" style="width: 6px; height: 12px;">
									<g style="stroke: none; stroke-width: 0; fill: currentcolor; fill-rule: evenodd; clip-rule: evenodd;">
										<path d="M0 4L3 0L6 4L0 4Z"></path>
										<path d="M6 8L3 12L0 8L6 8Z"></path>
									</g>
								</svg>
							</span>
						</span>
					</div>
					<div class="item_row_item_h item_row_item_4_0">
						<span class="itm_has_ord" data-order_field="count_audios">
							<span class="item_t">Audios</span>
							<span class="item_t_order">
								<svg viewBox="0 0 6 12" fill="none" xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision" style="width: 6px; height: 12px;">
									<g style="stroke: none; stroke-width: 0; fill: currentcolor; fill-rule: evenodd; clip-rule: evenodd;">
										<path d="M0 4L3 0L6 4L0 4Z"></path>
										<path d="M6 8L3 12L0 8L6 8Z"></path>
									</g>
								</svg>
							</span>
						</span>
					</div>
					<div class="item_row_item_h item_row_item_4 ">
						<span class="itm_has_ord itm_ord_active item_ord_desc gsp_default_order" data-order_field="gm.id">
							<span class="item_t">Id</span>
							<span class="item_t_order">
								<svg viewBox="0 0 6 12" fill="none" xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision" style="width: 6px; height: 12px;">
									<g style="stroke: none; stroke-width: 0; fill: currentcolor; fill-rule: evenodd; clip-rule: evenodd;">
										<path d="M0 4L3 0L6 4L0 4Z"></path>
										<path d="M6 8L3 12L0 8L6 8Z"></path>
									</g>
								</svg>
							</span>
						</span>
					</div>
				</div>
				<div class="items_inner_wrapper">
				</div>
			</div>
			<div class="items_pagination">
				<div class="items_pagination_itms_holder">
					<div data-val="1" class="itms_p_i itms_p_sel">1</div>
				</div>
			</div>
		</div>

	</div>

	<div class="add_new_widget_popup_wrapper" data-widgets_count="" data-user_plan="">
		<div id="add_new_widget_popup_inner">
			<div class="add_new_title_popup">Create New Widget</div>
			<div class="add_new_content_popup">Each widget has it's settings, depended on widget type!</div>
			<div class="input_holder"><input type="text" placeholder="Title" class="add_new_widget_title" /></div>

			<div class="input_holder">
				<div id="add_widget_type" class="items_select_filter_wrapper" >
					<div class="items_select_filter">
						<div class="items_select_filter_content"><span>Choose Type</span></div>
						<div class="items_select_filter_icon_wrapper">
							<div class="items_select_filter_icon_holder">
								<div class="items_select_filter_icon_inner">
									<span class="items_select_filter_icon">
										<svg class="" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"></path></svg>
									</span>
								</div>
							</div>
						</div>
						<div class="items_select_ul_wrapper">
							<div class="items_select_ul_holder">
								<div class="items_select_ul_inner">
									<ul class="items_select_ul">
										<li data-val="0" class="search_li"><span>Full Player</span></li>
										<li data-val="1" class="search_li"><span>Button Player</span></li>
										<li data-val="2" class="search_li"><span>Circle Player</span></li>
										<li data-val="3" class="search_li"><span>RHT Player</span></li>
										<li data-val="4" class="search_li"><span>Welcome Message</span></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="add_popup_buttons_holder">
				<div class="dashboard_button_2_self_size cancel_new_widget_button">Cancel</div>
				<div class="dashboard_button_1_self_size add_new_widget_button">Create</div>
			</div>

			<div id="close_add_new_widget_wrapper">
				<svg class="" aria-hidden="true" data-fa-processed="" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm101.8-262.2L295.6 256l62.2 62.2c4.7 4.7 4.7 12.3 0 17l-22.6 22.6c-4.7 4.7-12.3 4.7-17 0L256 295.6l-62.2 62.2c-4.7 4.7-12.3 4.7-17 0l-22.6-22.6c-4.7-4.7-4.7-12.3 0-17l62.2-62.2-62.2-62.2c-4.7-4.7-4.7-12.3 0-17l22.6-22.6c4.7-4.7 12.3-4.7 17 0l62.2 62.2 62.2-62.2c4.7-4.7 12.3-4.7 17 0l22.6 22.6c4.7 4.7 4.7 12.3 0 17z"></path></svg>
			</div>

		</div>
	</div>
</div>