<?php

/**
 * AI Help Sidebar
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_AI_Help_Sidebar {

	/**
	 * Display AI Help Sidebar
	 */
	public static function display_ai_help_sidebar() {

		// render AI Help Sidebar only if the current user has access to it
		$required_capability = EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] );
		if ( ! current_user_can( $required_capability ) ) {
			return;
		}   ?>

		<!-- AI Help Sidebar -->
		<div class="ephd-ai-help-sidebar">

			<div class="ephd-ai-help-sidebar__header">
				<div class="ephd-ai-help-sidebar__header-title">
					<span><?php esc_html_e( 'AI Help', 'help-dialog' ); ?></span>
					<span class="ephd__feature-experimental-tag"><?php esc_html_e( 'Experimental', 'help-dialog' ); ?></span>
				</div>
				<div class="ephd-close-notice ephdfa ephdfa-window-close ephd-ai-help-sidebar-btn-close"></div>
			</div>

			<div class="ephd-ai-help-sidebar__body">

				<!-- Main Screen -->
				<div class="ephd-ai-help-sidebar__main">
					<div class="ephd-ai-help-sidebar__main-intro"><?php esc_html_e( 'Welcome to AI-powered writing assistance for your FAQs. Whether you need to generate new FAQs or improve existing ones, we\'ve got you covered with the tools below.', 'help-dialog' ); ?>

                        <h4><?php esc_html_e( 'Learn About Using AI (Based on OpenAI Technology)', 'help-dialog') . ':'; ?></h4>
                        <ul>
                            <li> <a href="https://platform.openai.com/docs/guides/production-best-practices" target="_blank" class="ephd-ai-help-sidebar__main-link"><?php esc_html_e( 'AI Production Setup and Best Practices', 'help-dialog' ); ?></a></li>
                            <li><a href="https://openai.com/api/pricing/" target="_blank" class="ephd-ai-help-sidebar__main-link"><?php esc_html_e( 'Important: Using AI assistance will occur cost based on "Davinci" model and subject to your specific usage.', 'help-dialog' ); ?></a></li>
                        </ul>
                    </div>
                    
					<div class="ephd-ai-help-sidebar__actions">
						<div class="ephd-ai-help-sidebar__actions-question">
							<div class="ephd-ai-help-sidebar__actions-title"><?php esc_html_e( 'Question', 'help-dialog' ); ?><p>How Can AI help you to write your question?</p></div>   <?php
							EPHD_HTML_Elements::submit_button_v2( __( 'Fix Spelling and Grammar', 'help-dialog' ), 'ephd_fix_spelling_and_grammar', 'ephd-ai-help-sidebar__action-wrap', '', false, false, 'ephd-ai__question-fix-spelling-and-grammar-btn' );
							EPHD_HTML_Elements::submit_button_v2( __( 'Generate 5 Variations', 'help-dialog' ), 'ephd_create_text_based_on_input', 'ephd-ai-help-sidebar__action-wrap', '', false, false, 'ephd-ai__question-create-five-alternatives-btn' );  ?>
						</div>
						<div class="ephd-ai-help-sidebar__actions-answer">
							<div class="ephd-ai-help-sidebar__actions-title"><?php esc_html_e( 'Answer', 'help-dialog' ); ?><p>How Can AI help you to write your answer?</p></div>    <?php
							EPHD_HTML_Elements::submit_button_v2( __( 'Fix Spelling and Grammar', 'help-dialog' ), 'ephd_fix_spelling_and_grammar', 'ephd-ai-help-sidebar__action-wrap', '', false, false, 'ephd-ai__answer-fix-spelling-and-grammar-btn' );
							EPHD_HTML_Elements::submit_button_v2( __( 'Generate 5 Variations', 'help-dialog' ), 'ephd_create_text_based_on_input', 'ephd-ai-help-sidebar__action-wrap', '', false, false, 'ephd-ai__answer-create-five-alternatives-btn' );
							EPHD_HTML_Elements::submit_button_v2( __( 'Generate Answer To My Question', 'help-dialog' ), 'ephd_create_text_based_on_input', 'ephd-ai-help-sidebar__action-wrap', '', false, false, 'ephd-ai__create-answer-based-on-question-btn' );  ?>
						</div>
					</div>
				</div>

				<!-- Alternatives Screen -->
				<div class="ephd-ai-help-sidebar__alternatives">
					<div class="ephd-ai-help-sidebar__alternatives-back-btn">
						<div class="ephd-ai-help-sidebar__alternatives-back-btn__icon ephdfa ephdfa-arrow-left"></div>
						<div class="ephd-ai-help-sidebar__alternatives-back-btn__text"><?php esc_html_e( 'Back', 'help-dialog' ); ?></div>
					</div>

					<div class="ephd-ai-help-sidebar__alternatives-title"><?php esc_html_e( 'Create 5 Alternatives', 'help-dialog' ); ?></div>
					<div class="ephd-ai-help-sidebar__alternatives-usage">
						<div class="ephd-ai-help-sidebar__alternatives-usage-tokens"><span class="ephd-ai-help-sidebar__alternatives-usage-tokens__label"><?php esc_html_e( 'Spent Tokens: ', 'help-dialog' ); ?></span><span class="ephd-ai-help-sidebar__alternatives-usage-tokens__value"></span></div>
					</div>

					<div class="ephd-ai-help-sidebar__alternatives-input"><span class="ephd-ai-help-sidebar__alternatives-input__label"><?php esc_html_e( 'Input: ', 'help-dialog' ); ?></span><span class="ephd-ai-help-sidebar__alternatives-input__value"></span></div>

					<div class="ephd-ai-help-sidebar__alternatives-intro"><?php esc_html_e( 'Click on your preferred text below to update the FAQ box on the left:', 'help-dialog' ); ?></div>

					<div class="ephd-ai-help-sidebar__alternatives-list"></div>
				</div>

			</div>

		</div>  <?php
	}

	private static function notification_box_middle()
	{
	}
}
