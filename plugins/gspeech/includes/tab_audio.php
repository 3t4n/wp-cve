<?php 
// no direct access!
defined('ABSPATH') or die("No direct access");
?>

<div class="dashcontwrapper ss_plan_info_holder">
	<div id="dashboard_header_3">
		<div class="dashboard_title_inner">Audio Data</div>
	</div>
	<div class="dashboard_tabs_wrapper" style="" class="">
		<div class="dashboard_tab ss_tab_link_general ss_selected" data-tab_ident="general">General</div>
	</div>

	<div class="dashboard_content" class="ss_dash_widg_set">

		<div class="inner_options_wrapper">

			<div class="ss_options_group_1 ss_tab_wrapper ss_tab_general ss_tab_active">

				<div id="ss_audio_data" style="display: none" data-lang="" data-voice="" data-speed="" data-pitch="" data-name_hash=""></div>

				<div id="gsp_player_loading">Loading player...</div>
				<div id="ss_dash_audio_player"></div>

				<div class="opts_block">
					<div class="opts_block_label title_holder_vertical">
						<span>Content (Length: <span id="ss_audio_c_l"></span>)</span>
						<span class="questions_icon">
							<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
						</span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Content:</span> The audio content.</span>
					</div>
					<textarea id="ss_audio_content" readonly="readonly"></textarea>
				</div>
				<div class="opts_block">
					<div class="opts_block_label title_holder_vertical">
						<span>Url</span>
						<span class="questions_icon">
							<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
						</span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Url:</span> The url, where audio file was generated.</span>
					</div>
					<div class="ss_audio_url_holder"><a href="" target="_blank"></a></div>
				</div>
			</div>

			<div class="opts_title">Charts</div>

			<div id="ss_audio_plays_wrapper"></div>
			<div id="ss_audio_countries_wrapper"></div>
			<div id="ss_audio_cities_wrapper"></div>
			<div id="ss_audio_devices_wrapper"></div>
			<div class="ss_graphs_wrapper">
				<div class="graph_container2_wrapper"><div class="ss_graph_wrapper" id="graph_container2"></div><div class="ss_chart_link_hider"></div></div>
				<div class="graph_container4_wrapper"><div class="ss_graph_wrapper" id="graph_container4"></div><div class="ss_chart_link_hider"></div></div>
				<div class="graph_container3_wrapper"><div class="ss_graph_wrapper" id="graph_container3"></div><div class="ss_chart_link_hider"></div></div>
			</div>
			<div class="graph_container1_wrapper"><div class="ss_graph_wrapper" id="graph_container1"></div><div class="ss_chart_link_hider"></div></div>

		</div>
	</div>	
	
</div>
