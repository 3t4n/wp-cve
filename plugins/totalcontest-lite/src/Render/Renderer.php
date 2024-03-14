<?php

namespace TotalContest\Render;

use TotalContest\Contracts\Contest\Model;
use TotalContest\Contracts\Submission\Model as SubmissionModel;
use TotalContest\Modules\Template;
use TotalContestVendors\TotalCore\Contracts\Foundation\Environment;
use TotalContestVendors\TotalCore\Contracts\Modules\Repository as ModulesRepository;
use TotalContestVendors\TotalCore\Helpers\Html;
use TotalContestVendors\TotalCore\Helpers\Misc;
use TotalContestVendors\TotalCore\Helpers\Strings;

/**
 * Class Renderer
 * @package TotalContest\Render
 */
class Renderer {
	/**
	 * @var Model
	 */
	protected $contest;
	/**
	 * @var \TotalContest\Submission\Model
	 */
	protected $submission;
	/**
	 * @var ModulesRepository
	 */
	protected $modulesRepository;
	/**
	 * @var Template
	 */
	protected $templateInstance;
	/**
	 * @var Environment
	 */
	protected $env;
	/**
	 * @var \WP_Filesystem_Base
	 */
	protected $filesystem;

	public function __construct( ModulesRepository $modulesRepository, \WP_Filesystem_Base $filesystem, Environment $env ) {
		$this->modulesRepository = $modulesRepository;
		$this->env               = $env;
		$this->filesystem        = $filesystem;
	}

	/**
	 * Render shortcut.
	 *
	 * @return string
	 */
	public function __toString() {
		return (string) $this->render();
	}

	/**
	 * @param $templateId
	 *
	 * @return Template|\TotalContest\Modules\Templates\Basic\Template
	 */
	public function loadTemplate( $templateId ) {
		if ( $this->templateInstance === null ):

			// Theme template
			$themeTemplateFile = get_template_directory() . '/totalcontest/Template.php';
			if ( file_exists( $themeTemplateFile ) ):
				include_once $themeTemplateFile;

				$themeTemplateClass = '\\TotalContest\\Modules\\Templates\\ThemeTemplate\\Template';
				if ( class_exists( $themeTemplateClass ) ):
					$this->templateInstance = new $themeTemplateClass;
				endif;
			// Regular template
			else:
				$module = $this->modulesRepository->get( $templateId );

				if ( $module && class_exists( $module['class'] ) ):
					$this->templateInstance = new $module['class'];
				else:
					$this->templateInstance = new \TotalContest\Modules\Templates\Basic\Template();
				endif;
			endif;
		endif;

		return $this->templateInstance;
	}


	/**
	 * @return string
	 */
	public function render() {
		TotalContest( 'utils.purge.cache' );

		$template = $this->loadTemplate( $this->contest->getTemplateId() );
		/**
		 * Filters the template used for contest rendering.
		 *
		 * @param Template $template Template object.
		 * @param Model $contest Contest model object.
		 * @param SubmissionModel $submission Submission model object.
		 * @param Renderer $render Renderer object.
		 *
		 * @return Template
		 * @since 2.0.0
		 */
		$template = apply_filters( 'totalcontest/filters/render/template', $template, $this->contest, $this->submission, $this );

		$screen       = $this->submission ? $this->submission->getScreen() : $this->contest->getScreen();
		$templateVars = [ 'contest' => $this->contest, 'submission' => $this->submission, 'screen' => $screen, 'template' => $template ];

		if ( $screen === 'contest.content' ):
			$customPage = $this->contest->getCustomPage();

			if ( ! empty( $customPage['content'] ) ):
				$customPage['content'] = wpautop( do_shortcode( $customPage['content'] ) );
			endif;

			$templateVars['customPage'] = $customPage;
		endif;

		if ( $screen === 'contest.landing' ):
			$templateVars['content'] = $this->contest->getSettingsItem( 'pages.landing.content' );

			if ( ! empty( $templateVars['content'] ) ):
				$templateVars['content'] = wpautop( do_shortcode( $templateVars['content'] ) );
			endif;
		endif;

		if ( $screen === 'contest.thankyou' ):
			$templateVars['content'] = $this->contest->getSettingsItem( 'pages.thankyou.submission.content' );

			if ( ! empty( $templateVars['content'] ) ):
				$templateVars['content'] = wpautop( do_shortcode( $templateVars['content'] ) );
			endif;
		endif;

		if ( $screen === 'contest.participate' ):
			$templateVars['form'] = $this->contest->getForm();
			! defined( 'DONOTCACHEPAGE' ) && define( 'DONOTCACHEPAGE', true );
		endif;

		if ( $screen === 'submission.thankyou' ):
			$templateVars['voteCasted'] = true;
			$templateVars['content']    = $this->contest->getSettingsItem( 'pages.thankyou.voting.content' );

			if ( ! empty( $templateVars['content'] ) ):
				$templateVars['content'] = wpautop( do_shortcode( $templateVars['content'] ) );
			endif;

			$screen = 'submission.view';
		endif;

		if ( $screen === 'submission.view' ):
			if ( $this->contest->getSettingsItem( 'design.behaviours.modal', false ) && Misc::isDoingAjax() ) {
				$this->contest->setMenuVisibility( false );
			}
			if ( ! $this->submission->hasVoted() && $this->submission->isAcceptingVotes() ):
				$templateVars['form'] = $this->submission->getForm();
			else:
				$templateVars['message'] = $this->submission->getErrorMessage();
			endif;
			! defined( 'DONOTCACHEPAGE' ) && define( 'DONOTCACHEPAGE', true );
		endif;

		/**
		 * Filters the contest screen when rendering.
		 *
		 * @param string $screen Contest screen name.
		 * @param Model $contest Contest model object.
		 * @param SubmissionModel $submission Submission model object.
		 * @param Renderer $render Renderer object.
		 *
		 * @return string
		 * @since 2.0.0
		 */
		$screen = apply_filters( 'totalcontest/filters/render/screen', $screen, $this->contest, $this->submission, $this );

		$templateVars['screen'] = $screen;

		/**
		 * Filters template variables passed to views.
		 *
		 * @param array $templateVars Template variables.
		 * @param Model $contest Contest model object.
		 * @param SubmissionModel $submission Submission model object.
		 * @param Renderer $render Renderer object.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$templateVars = apply_filters( 'totalcontest/filters/render/templateVars', $templateVars, $this->contest, $this );

		$cssClasses   = [];
		$cssClasses[] = is_rtl() ? 'is-rtl' : 'is-ltr';

		if ( function_exists( 'is_embed' ) && is_embed() ):
			$cssClasses[] = 'is-embed';
		endif;

		if ( is_preview() ):
			$cssClasses[] = 'is-preview';
		endif;

		if ( is_user_logged_in() ):
			$cssClasses[] = 'is-logged-in';
		endif;

		/**
		 * Filters css classes of contest container.
		 *
		 * @param array $cssClasses Css classes.
		 * @param Model $contest Contest model object.
		 * @param SubmissionModel $submission Submission model object.
		 * @param Renderer $render Renderer object.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$cssClasses = apply_filters( 'totalcontest/filters/render/classes', $cssClasses, $this->contest, $this );


		/**
		 * Filters template markup
		 *
		 * @param string $view View.
		 * @param Model $contest Contest model object.
		 * @param Renderer $render Renderer object.
		 *
		 * @return string
		 * @since 2.0.0
		 */
		$view = apply_filters( 'totalcontest/filters/render/view', $template->getView( $screen, $templateVars ), $this->contest, $this );

		/**
		 * Filters template markup
		 *
		 * @param string $markup Contest wrapper markup.
		 * @param Model $contest Contest model object.
		 * @param SubmissionModel $submission Submission model object.
		 * @param Renderer $render Renderer object.
		 *
		 * @return string
		 * @since 2.0.0
		 */
		$markup = apply_filters(
			'totalcontest/filters/render/markup',
			'<div id="totalcontest" class="totalcontest-wrapper totalcontest-uid-{{uid}} {{container.classes}}" totalcontest="{{contest.id}}" totalcontest-submission-id="{{submission.id||\'\'}}" totalcontest-uid="{{uid||\'none\'}}" totalcontest-screen="{{contest.screen}}" totalcontest-ajax-url="{{ajax}}">{{before}}{{config}}{{css}}{{js}}<div id="totalcontest-contest-{{contest.id}}" class="totalcontest-container">{{view}}</div>{{after}}</div>',
			$this->contest,
			$this->submission,
			$this
		);

		$before = '';
		$after  = '';

		if ( TotalContest()->option( 'general.showCredits.enabled' ) ):
			$link = add_query_arg(
				[
					'utm_source'   => 'powered-by',
					'utm_medium'   => 'footer',
					'utm_campaign' => 'totalcontest',
				],
				$this->env['links.website']
			);

			$after .= new Html(
				'div',
				[
					'class' => 'totalcontest-credits',
					'style' => 'font-family: sans-serif; font-size: 9px; text-transform: uppercase;text-align: center; padding: 10px 0;'
				],
				sprintf(
					esc_html__( 'Powered by %s', 'totalcontest' ),
					'TotalContest'
				)
			);
		endif;

		$ajaxUrlArgs = [];

		if($this->submission){
			$ajaxUrlArgs['submissionId'] = $this->submission->getId();
		}

		$rendered = Strings::template( $markup,
			apply_filters(
				'totalcontest/filters/render/vars',
				[
					'container'  => [
						'classes' => implode( ' ', $cssClasses ),
					],
					'uid'        => $this->contest->getPresetUid(),
					'contest'    => [
						'id'     => $this->contest->getId(),
						'screen' => $screen,
					],
					'submission' => [
						'id'     => $this->submission ? $this->submission->getId() : null,
						'screen' => $this->submission ? $this->submission->getScreen() : null,
					],
					'css'        => $this->getCss(),
					'js'         => $this->getJs(),
					'config'     => $this->getConfig(),
					'view'       => $view,
					'ajax'       => $this->contest->getAjaxUrl($ajaxUrlArgs),
					'before'     => $before,
					'after'      => $after,
				],
				$this->contest,
				$this->submission,
				$screen,
				$this
			)
		);

		/**
		 * Filters the rendered output.
		 *
		 * @param string $rendered Rendered contest.
		 * @param string $screen Current screen.
		 * @param Model $contest Contest model object.
		 * @param SubmissionModel $submission Submission model object.
		 * @param Renderer $render Renderer object.
		 *
		 * @return string
		 * @since 2.0.0
		 */
		return apply_filters( 'totalcontest/filters/render/output', $rendered, $screen, $this->contest, $this->submission, $this );
	}

	/**
	 * @return string
	 */
	public function getConfig() {
		$config = [
			'ajaxEndpoint' => add_query_arg( [ 'action' => 'totalcontest' ], admin_url( 'admin-ajax.php' ) ),
			'behaviours'   => $this->contest->getSettingsItem( 'design.behaviours', [] ) + [ 'async' => ! Misc::isDoingAjax() && defined( 'TC_ASYNC' ) && TC_ASYNC ],
			'effects'      => $this->contest->getSettingsItem( 'design.effects', [] ),
		];

		/**
		 * Filters contest config that will passed to frontend controller.
		 *
		 * @param array $config Config variables.
		 * @param Model $contest Contest model object.
		 * @param SubmissionModel $submission Submission model object.
		 * @param Renderer $render Renderer object.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$config = apply_filters( 'totalcontest/filters/render/config', $config, $this->contest, $this->submission, $this );

		return sprintf( '<script type="text/totalcontest-config" totalcontest-config="%1$d">%2$s</script>', $this->contest->getId(), json_encode( $config, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
	}

	/**
	 * Get JS.
	 */
	public function getJs() {
		wp_enqueue_script( 'jquery-validation', $this->env['url'] . 'assets/dist/scripts/vendor/jquery.validate.min.js', [ 'jquery' ], ( Misc::isDevelopmentMode() ? time() : $this->env['version'] ) );
		wp_enqueue_script( 'totalcontest-frontend', $this->env['url'] . 'assets/dist/scripts/frontend.js', [ 'jquery-validation' ], ( Misc::isDevelopmentMode() ? time() : $this->env['version'] ) );
		wp_localize_script( 'jquery-validation', 'jqValidationMessages', [
			'required'    => esc_html__( '{{label}} must be filled.', 'totalcontest' ),
			'email'       => esc_html__( '{{label}} must be a valid email address.', 'totalcontest' ),
			'url'         => esc_html__( '{{label}} must be a valid URL.', 'totalcontest' ),
			'number'      => esc_html__( '{{label}} must be a number.', 'totalcontest' ),
			'maxlength'   => esc_html__( '{{label}} must be less than %d characters.', 'totalcontest' ),
			'minlength'   => esc_html__( '{{label}} must be at least %d characters.', 'totalcontest' ),
			'maxfilesize' => esc_html__( '{{label}} file size must be less than %s.', 'totalcontest' ),
			'minfilesize' => esc_html__( '{{label}} file size must be at least %s.', 'totalcontest' ),
			'formats'     => esc_html__( 'Only files with these extensions are allowed: %s.', 'totalcontest' ),
			'left'        => esc_html__( '%d Characters left', 'totalcontest' ),
			'max'   => esc_html__( '{{label}} must be less than %d.', 'totalcontest' ),
			'min'   => esc_html__( '{{label}} must be at least %d.', 'totalcontest' ),
		] );

		/**
		 * Filters contest JS.
		 *
		 * @param string $js JS code.
		 * @param Model $contest Contest model object.
		 * @param SubmissionModel $submission Submission model object.
		 * @param Renderer $render Renderer object.
		 *
		 * @return string
		 * @since 2.0.0
		 */
		return apply_filters( 'totalcontest/filters/render/js', '', $this->contest, $this->submission, $this );
	}

	/**
	 * Get CSS.
	 *
	 * @return string
	 */
	public function getCss() {
		$presetUid     = $this->contest->getPresetUid();
		$cachedCssFile = "css/{$presetUid}.css";

		$css = sprintf(
			'<link rel="stylesheet" id="totalcontest-contest-%s-css"  href="%s" type="text/css" media="all" />',
			$presetUid,
			$this->env['cache']['url'] . $cachedCssFile
		);

		$inlineCss = TotalContest()->option( 'advanced.inlineCss' );
		if ( $inlineCss || Misc::isDevelopmentMode() || ! $this->filesystem->is_readable( $this->env['cache']['path'] . $cachedCssFile ) ):
			TotalContest( 'utils.create.cache' );
			$compileArgs = $this->contest->getSettingsItem( 'design' ) + [ 'uid' => $this->contest->getPresetUid() ];

			/**
			 * Filters the arguments passed for CSS compiling.
			 *
			 * @param array $args Arguments.
			 * @param Renderer $renderer Renderer.
			 * @param Model $contest Contest model.
			 *
			 * @return array
			 * @since 2.0.0
			 */
			$compileArgs = apply_filters( 'totalcontest/filters/render/css-args', $compileArgs, $this, $this->contest );
			$compiledCss = $this->templateInstance->getCompiledCss( $compileArgs ) . $this->contest->getSettingsItem( 'design.css' );

			if ( ! $inlineCss && $this->filesystem->is_writable( "{$this->env['cache']['path']}css/" ) ):
				$this->filesystem->put_contents( "{$this->env['cache']['path']}$cachedCssFile", $compiledCss );
			else:
				$css = "<style>{$compiledCss}</style>";
			endif;
		endif;

		/**
		 * Filters contest CSS.
		 *
		 * @param string $css CSS Code.
		 * @param Model $contest Contest model object.
		 * @param SubmissionModel $submission Submission model object.
		 * @param Renderer $render Renderer object.
		 *
		 * @return string
		 * @since 2.0.0
		 */
		$css = apply_filters( 'totalcontest/filters/render/css', $css, $this->contest, $this->submission, $this );

		return $css;
	}

	/**
	 * @return Model
	 */
	public function getContest() {
		return $this->contest;
	}

	/**
	 * @param Model $contest
	 */
	public function setContest( $contest ) {
		$this->contest = $contest;
	}

	/**
	 * @return \TotalContest\Submission\Model
	 */
	public function getSubmission() {
		return $this->submission;
	}

	/**
	 * @param \TotalContest\Submission\Model $submission
	 */
	public function setSubmission( $submission ) {
		$this->submission = $submission;
	}
}
