<?php

namespace TotalContest;

use TotalContest\Admin\Plugins\UninstallFeedback;
use TotalContestVendors\TotalCore\Contracts\Form\Field;
use TotalContestVendors\TotalCore\Helpers\Misc;

/**
 * TotalContest Plugin.
 *
 * @package TotalContest
 */
class Plugin extends \TotalContestVendors\TotalCore\Foundation\Plugin {

	public function registerProviders() {
		// Bootstrap
		$this->container->share( 'bootstrap', function () {
			return new Bootstrap();
		} );

		$this->container->share( 'admin.privacy', function () {
			return new Admin\Privacy\Policy( $this->container->get( 'env' ),
			                                 $this->container->get( 'log.repository' ) );
		} );

		// Schema migration
		$this->container->share( 'migrations.schema', function () {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			return new Migrations\Schema\Migrator( $this->container->get( 'env' ),
			                                       $this->container->get( 'database' ) );
		} );

		// Contests migration
		$this->container->share( 'migrations.totalcontest', function () {
			return new Migrations\Contest\TotalContest\Migrator( $this->container->get( 'env' ) );
		} );

		$this->container->share( 'migrations.migrators', function () {
			return [
				'totalcontest-1' => $this->container->get( 'migrations.totalcontest' ),
			];
		} );

		// Custom implementation of modules repository
		$this->container->share( 'modules.repository', function () {
			return new Modules\Repository( $this->container->get( 'env' ),
			                               $this->container->get( 'admin.activation' ),
			                               $this->container->get( 'admin.account' ) );
		} );

		// ID3
		$this->container->share( 'id3', function () {
			include_once ABSPATH . WPINC . '/ID3/getid3.php';

			return new \getID3();
		} );

		// Contest post type
		$this->container->share( 'contests.cpt', function () {
			return new Contest\PostType();
		} );

		// Contest post defaults
		$this->container->share( 'contests.defaults', function () {
			return apply_filters( 'totalcontest/filters/contest/defaults',
			                      [
				                      'id'            => get_the_ID(),
				                      'contest'       => [
					                      'form'        => [ 'fields' => [], ],
					                      'vote'        => [ 'fields' => [], ],
					                      'submissions' => [
						                      
						                      
											  'requiresApproval' => false,
											   'title' => esc_html__( 'Submission {{id}}', 'totalcontest' ),
						                      'subtitle'                                                        => esc_html__( '{{votes}} Votes | {{views}} Views',
						                                                                                                       'totalcontest' ),
						                      'content'                                                         => '',
						                      'preview'                                                         => [
							                      'source'  => '',
							                      'default' => '',
						                      ],
						                      'perPage'                                                         => 9,
						                      'blocks'                                                          => [
							                      'enabled'     => true,
							                      'submissions' => [
								                      [
									                      'uid'      => Misc::generateUid(),
									                      'type'     => 'image',
									                      'source'   => '',
									                      'fallback' => '',
								                      ],
								                      [
									                      'uid'         => Misc::generateUid(),
									                      'type'        => 'title',
									                      'expressions' => [
										                      [
											                      'uid'    => Misc::generateUid(),
											                      'type'   => 'val',
											                      'source' => esc_html__( 'Submission',
											                                              'totalcontest' ) . ' #',
										                      ],
										                      [
											                      'uid'    => Misc::generateUid(),
											                      'type'   => 'var',
											                      'source' => 'id',
										                      ],
									                      ],
								                      ],
								                      [
									                      'uid'         => Misc::generateUid(),
									                      'type'        => 'subtitle',
									                      'expressions' => [
										                      [
											                      'uid'    => Misc::generateUid(),
											                      'type'   => 'var',
											                      'source' => 'votesWithLabel',
										                      ],
										                      [
											                      'uid'    => Misc::generateUid(),
											                      'type'   => 'val',
											                      'source' => ' | ',
										                      ],
										                      [
											                      'uid'    => Misc::generateUid(),
											                      'type'   => 'var',
											                      'source' => 'viewsWithLabel',
										                      ],
									                      ],
								                      ],
							                      ],
							                      'submission'  => [
								                      [
									                      'uid'      => Misc::generateUid(),
									                      'type'     => 'image',
									                      'source'   => '',
									                      'fallback' => '',
								                      ],
							                      ],
						                      ],
					                      ],
					                      'limitations' => null,
					                      'frequency'   => [
						                      'cookies' => [ 'enabled' => true, ],
						                      'ip'      => [ 'enabled' => false, ],
						                      'user'    => [ 'enabled' => false, ],
						                      'count'   => 1,
						                      'timeout' => 3600,
					                      ],
				                      ],
				                      'menu'          => [
					                      'items'   => [
						                      [ 'id' => 'home', 'label' => '' ],
						                      [ 'id' => 'participate', 'label' => '' ],
						                      [ 'id' => 'submissions', 'label' => '' ],
						                      [ 'id' => 'pages', 'label' => '' ],
					                      ],
					                      'default' => 'participate',
				                      ],
				                      'pages'         => [
					                      'landing'     => [ 'title' => '', 'content' => '', ],
					                      'participate' => [ 'title' => '', 'content' => '', ],
					                      'submissions' => [ 'title' => '', 'content' => '', ],
					                      'thankyou'    => [
						                      'voting'     => [
							                      'content' => esc_html__( 'Your vote has been casted. Thank you!',
							                                               'totalcontest' ),
						                      ],
						                      'submission' => [
							                      'content' => esc_html__( 'Thank you for participating in this contest!',
							                                               'totalcontest' ),
						                      ],
					                      ],
					                      'default'     => 'participate',
					                      'other'       => [],
				                      ],
				                      'design'        => [
					                      'template'   => 'basic-template',
					                      'text'       => [
						                      'fontFamily' => 'inherit',
						                      'fontWeight' => 'inherit',
						                      'fontSize'   => 'inherit',
						                      'lineHeight' => 'inherit',
						                      'align'      => 'inherit',
						                      'transform'  => 'none',
					                      ],
					                      'colors'     => [
						                      'primary'           => '#1e73be',
						                      'primaryContrast'   => '#ffffff',
						                      'primaryDark'       => '#07457C',
						                      'secondary'         => '#81d742',
						                      'secondaryContrast' => '#ffffff',
						                      'secondaryDark'     => '#489B0D',
						                      'accent'            => '#dd9933',
						                      'accentContrast'    => '#ffffff',
						                      'accentDark'        => '#925802',
						                      'dark'              => '#333333',
						                      'white'             => '#ffffff',
						                      'gray'              => '#dddddd',
						                      'grayDark'          => '#aaaaaa',
						                      'grayLight'         => '#eeeeee',
						                      'grayLighter'       => '#fafafa',
					                      ],
					                      'custom'     => [],
					                      'layout'     => [
						                      'type'     => 'grid',
						                      'maxWidth' => '100%',
						                      'columns'  => 3,
						                      'gutter'   => '1rem',
					                      ],
					                      'behaviours' => [
						                      'ajax'     => true,
						                      'scrollUp' => true,
					                      ],
					                      'effects'    => [
						                      'transition' => 'fade',
						                      'duration'   => '500',
					                      ],
					                      'css'        => '',
				                      ],
				                      'share'         => [ 'websites' => null ],
				                      'vote'          => [
					                      'type'        => 'count',
					                      'scale'       => 5,
					                      'limitations' => null,
					                      'frequency'   => [
						                      'cookies'     => [ 'enabled' => true, ],
						                      'ip'          => [ 'enabled' => false, ],
						                      'user'        => [ 'enabled' => false, ],
						                      'count'       => 1,
						                      'perItem'     => 1,
						                      'perCategory' => 0,
						                      'timeout'     => 3600,
					                      ],
					                      'criteria'    => [
						                      [ 'name' => esc_html__( 'Rating', 'totalcontest' ) ],
					                      ],
				                      ],
				                      'notifications' => [
					                      'email'      => [ 'recipient' => (string) get_option( 'admin_email' ) ],
					                      'submission' => [ 'new' => true ],
				                      ],
				                      'meta'          => [
					                      'schema' => '1.1',
				                      ],
			                      ] );
		} );

		// Contest post type
		$this->container->share( 'submissions.category', function () {
			return new Submission\Category();
		} );

		// Contest repository
		$this->container->share( 'contests.repository', function () {
			return new Contest\Repository( $this->container->get( 'http.request' ),
			                               $this->container->get( 'form.factory' ) );
		} );

		// Contest controller
		$this->container->share( 'contests.controller', function () {
			return new Contest\Controller( $this->container->get( 'http.request' ),
			                               $this->container->get( 'contests.repository' ) );
		} );

		// Contest renderer
		$this->container->share( 'contests.renderer', function () {
			return new Render\Renderer( $this->container->get( 'modules.repository' ),
			                            $this->container->get( 'filesystem' ),
			                            $this->container->get( 'env' ) );
		} );

		// Contest commands
		$this->container->add( 'contests.commands.create.submission', function ( $contest ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';

			return new Contest\Commands\CreateSubmission( $contest,
			                                              $this->container->get( 'http.request' ),
			                                              $this->container->get( 'log.repository' ),
			                                              $this->container->get( 'embed' ) );
		} );
		$this->container->add( 'contests.commands.send.notification', function ( $contest, $submission ) {
			return new Contest\Commands\SendNotification( $contest,
			                                              $submission,
			                                              $this->container->get( 'http.request' ) );
		} );

		// Submission post type
		$this->container->share( 'submissions.cpt', function () {
			return new Submission\PostType();
		} );

		// Submission post type
		$this->container->share( 'submissions.repository', function () {
			return new Submission\Repository( $this->container->get( 'http.request' ),
			                                  $this->container->get( 'contests.repository' ),
			                                  $this->container->get( 'form.factory' ),
			                                  $this->container->get( 'database' ) );
		} );

		// Submission controller
		$this->container->share( 'submissions.controller', function () {
			return new Submission\Controller( $this->container->get( 'http.request' ),
			                                  $this->container->get( 'submissions.repository' ) );
		} );

		// Submission commands
		$this->container->add( 'submissions.commands.vote', function ( $submission ) {
			return new Submission\Commands\CountVote( $submission,
			                                          $this->container->get( 'http.request' ),
			                                          $this->container->get( 'log.repository' ) );
		} );
		$this->container->add( 'submissions.commands.view', function ( $submission ) {
			return new Submission\Commands\CountView( $submission, $this->container->get( 'http.request' ) );
		} );

		// Log facade
		$this->container->share( 'log.repository', function () {
			return new Log\Repository( $this->container->get( 'env' ),
			                           $this->container->get( 'http.request' ),
			                           $this->container->get( 'database' ) );
		} );

		// Factory
		$this->container->share( 'form.factory', function () {
			return new Form\Factory( $this->container->get( 'http.request' ) );
		} );

		// Participate
		$this->container->share( 'form.contest.participate', function () {
			return function ( $contest ) {
				return new Form\ParticipateForm( $contest,
				                                 $this->container->get( 'http.request' ),
				                                 $this->container->get( 'form.factory' ) );
			};
		} );

		/**
		 * ===============================================
		 * SHORTCODES
		 * ===============================================
		 */
		$this->container->add( 'contests.shortcode', function ( $attributes, $content = null ) {
			return new Shortcode\Contest( $attributes, $content );
		} );

		$this->container->add( 'contests.shortcodes.participate', function ( $attributes, $content = null ) {
			return new Shortcode\Participate( $attributes, $content );
		} );

		$this->container->add( 'contests.shortcode.submissions', function ( $attributes, $content = null ) {
			return new Shortcode\Submissions( $attributes, $content );
		} );

		$this->container->add( 'contests.shortcode.page', function ( $attributes, $content = null ) {
			return new Shortcode\Page( $attributes, $content );
		} );

		$this->container->add( 'submissions.shortcode', function ( $attributes, $content = null ) {
			return new Shortcode\Submission( $attributes, $content );
		} );

		$this->container->add( 'countdown.shortcode',
			function ( $attributes, $content = null ) {
				return new Shortcode\Countdown( $attributes, $content );
			} );

		$this->container->add( 'content.shortcode.image', function ( $attributes, $content = null ) {
			return new Shortcode\Image( $attributes, $content );
		} );

		$this->container->add( 'content.shortcode.video', function ( $attributes, $content = null ) {
			return new Shortcode\Video( $attributes, $content );
		} );

		$this->container->add( 'content.shortcode.audio', function ( $attributes, $content = null ) {
			return new Shortcode\Audio( $attributes, $content );
		} );

		$this->container->add( 'content.shortcode.text', function ( $attributes, $content = null ) {
			return new Shortcode\Text( $attributes, $content );
		} );

		$this->container->add( 'content.shortcode.file', function ( $attributes, $content = null ) {
			return new Shortcode\File( $attributes, $content );
		} );

		/**
		 * ===============================================
		 * ADMIN SIDE
		 * ===============================================
		 */

		// Admin bootstrap
		$this->container->share( 'admin.bootstrap', function () {
			return new Admin\Bootstrap( $this->container->get( 'http.request' ), $this->container->get( 'env' ) );
		} );

		// Admin ajax
		$this->container->share( 'admin.ajax', function () {
			return new Admin\Ajax\Bootstrap();
		} );

		$this->container->share( 'admin.ajax.dashboard', function () {
			return new Admin\Ajax\Dashboard( $this->container->get( 'http.request' ),
			                                 $this->container->get( 'admin.activation' ),
			                                 $this->container->get( 'admin.account' ),
			                                 $this->container->get( 'contests.repository' ) );
		} );

		$this->container->share( 'admin.ajax.modules', function () {
			return new Admin\Ajax\Modules( $this->container->get( 'http.request' ),
			                               $this->container->get( 'modules.manager' ) );
		} );

		$this->container->share( 'admin.ajax.log', function () {
			return new Admin\Ajax\Log( $this->container->get( 'http.request' ),
			                           $this->container->get( 'log.repository' ) );
		} );

		$this->container->share( 'admin.ajax.options', function () {
			return new Admin\Ajax\Options( $this->container->get( 'http.request' ),
			                               $this->container->get( 'migrations.migrators' ) );
		} );

		$this->container->share( 'admin.ajax.contests', function () {
			return new Admin\Ajax\Contests( $this->container->get( 'http.request' ) );
		} );

		// Admin ajax (templates)
		$this->container->share( 'admin.ajax.templates', function () {
			return new Admin\Ajax\Templates( $this->container->get( 'http.request' ),
			                                 $this->container->get( 'modules.repository' ) );
		} );

		// Admin editor
		$this->container->share( 'admin.contest.editor', function () {
			return new Admin\Contest\Editor( $this->container->get( 'env' ),
			                                 $this->container->get( 'filesystem' ),
			                                 $this->container->get( 'modules.repository' ),
			                                 $this->container->get( 'contests.repository' ) );
		} );

		// Admin submission editor
		$this->container->share( 'admin.submission.editor', function () {
			return new Admin\Submission\Editor( $this->container->get( 'http.request' ),
			                                    $this->container->get( 'submissions.repository' ),
			                                    $this->container->get( 'form.factory' ),
			                                    $this->container->get( 'env' ) );
		} );

		// Admin listing
		$this->container->share( 'admin.contest.listing', function () {
			return new Admin\Contest\Listing( $this->container->get( 'log.repository' ),
			                                  $this->container->get( 'submissions.repository' ) );
		} );

		// Admin listing
		$this->container->share( 'admin.submission.listing', function () {
			return new Admin\Submission\Listing( $this->container->get( 'http.request' ),
			                                     $this->container->get( 'contests.repository' ),
			                                     $this->container->get( 'submissions.repository' ) );
		} );

		// Admin pages

		// Onboarding
		$this->container->share( 'admin.pages.onboarding', function () {
			return new Admin\Onboarding\Page( $this->container->get( 'http.request' ), $this->container->get( 'env' ) );
		} );

		$this->container->share( 'admin.pages.dashboard', function () {
			return new Admin\Dashboard\Page( $this->container->get( 'http.request' ),
			                                 $this->container->get( 'env' ),
			                                 $this->container->get( 'admin.activation' ) );
		} );

		$this->container->share( 'admin.pages.log', function () {
			return new Admin\Log\Page( $this->container->get( 'http.request' ), $this->container->get( 'env' ) );
		} );

		$this->container->share( 'admin.pages.modules', function () {
			return new Admin\Modules\Page( $this->container->get( 'http.request' ), $this->container->get( 'env' ) );
		} );

		// Templates
		$this->container->share( 'admin.pages.templates', function () {
			return new Admin\Modules\Templates\Page( $this->container->get( 'http.request' ),
			                                         $this->container->get( 'env' ) );
		} );

		// Extensions
		$this->container->share( 'admin.pages.extensions', function () {
			return new Admin\Modules\Extensions\Page( $this->container->get( 'http.request' ),
			                                          $this->container->get( 'env' ) );
		} );

		$this->container->share( 'admin.pages.options', function () {
			return new Admin\Options\Page( $this->container->get( 'http.request' ),
			                               $this->container->get( 'env' ),
			                               $this->container->get( 'migrations.migrators' ) );
		} );

		// Structured data
		$this->container->share( 'decorators.structuredData', function () {
			return new Decorators\StructuredData();
		} );

		// Helpers
		$this->container->share( 'url', function () {
			$helper   = new Helpers\Url();
			$_REQUEST = $helper->extractParameters( $_REQUEST );
			$_GET     = $helper->extractParameters( $_GET );
			$_POST    = $helper->extractParameters( $_POST );

			return $helper;
		} );

		// Utils
		$this->container->add( 'utils.create.cache', function () {
			wp_mkdir_p( $this->application->env( 'cache.path' ) . 'css/' );
		} );

		$this->container->add( 'utils.create.exports', function () {
			wp_mkdir_p( $this->application->env( 'exports.path' ) );

			if ( ! file_exists( $this->application->env( 'exports.path' ) . 'index.html' ) ) {
				$this->container->get( 'filesystem' )
				                ->touch( $this->application->env( 'exports.path' ) . 'index.html' );
			}
		} );

		$this->container->add( 'utils.purge.cache', function () {
			$this->container->get( 'filesystem' )
			                ->rmdir( $this->application->env( 'cache.path' ), true );
		} );

		$this->container->add( 'utils.purge.store', function () {
			delete_transient( $this->application->env( 'slug' ) . '_modules_store_response' );
		} );

		// Validators
		$this->container->share( 'validators.unique', function () {
			return function ( Field $field, $args = [] ) {
				$value = $field->getValue();
				/**
				 * @var \wpdb $database
				 */
				$database = $this->container->get( 'database' );
				$search   = sprintf( '%%"%s":"%s"%%', $field->getName(), $field->getValue() );
				$sql      = "SELECT count(id) FROM {$database->posts} WHERE post_parent = %d AND post_content LIKE %s";
				$query    = $database->prepare( $sql, $args['contestId'], $search );

				if ( ! empty( $value ) && $database->get_var( $query ) ):
					return esc_html__( '{{label}} has been used before.', 'totalcontest' );
				endif;

				return true;
			};
		} );


		// Uninstall feedback
		$this->container->add( 'uninstall', new UninstallFeedback() );

		
		// Upgrade
		$this->container->share( 'admin.pages.upgrade-to-pro', function () {
			return new Admin\Upgrade\Page( $this->container->get( 'http.request' ), $this->container->get( 'env' ) );
		} );

		$this->container->add( 'upgrade-to-pro', function () {
			$url     = esc_attr( TotalContest()->env( 'links.upgrade-to-pro' ) );
			$tooltip = esc_html__( 'This feature is available in Pro version.', 'totalcontest' );

			echo "<a href=\"{$url}\" target=\"_blank\" class=\"totalcontest-pro-badge\" tooltip=\"${tooltip}\">Pro</a>";
		} );
		
	}

	/**
	 * Register widgets.
	 */
	public function registerWidgets() {
		register_widget( '\TotalContest\Widgets\Contest' );
		register_widget( '\TotalContest\Widgets\Submission' );
		register_widget( '\TotalContest\Widgets\Countdown' );
	}

	/**
	 * Register shortcodes.
	 */
	public function registerShortCodes() {
		add_shortcode( 'totalcontest', function ( $attributes, $content = null ) {
			return (string) $this->container->get( 'contests.shortcode', [ $attributes, $content ] );
		} );

		add_shortcode( 'totalcontest-contest-participate', function ( $attributes, $content = null ) {
			return (string) $this->container->get( 'contests.shortcodes.participate', [ $attributes, $content ] );
		} );

		add_shortcode( 'totalcontest-contest-submissions', function ( $attributes, $content = null ) {
			return (string) $this->container->get( 'contests.shortcode.submissions', [ $attributes, $content ] );
		} );

		add_shortcode( 'totalcontest-contest-page', function ( $attributes, $content = null ) {
			return (string) $this->container->get( 'contests.shortcode.page', [ $attributes, $content ] );
		} );

		add_shortcode( 'totalcontest-submission', function ( $attributes, $content = null ) {
			return (string) $this->container->get( 'submissions.shortcode', [ $attributes, $content ] );
		} );

		add_shortcode( 'totalcontest-countdown', function ( $attributes, $content = null ) {
			return (string) $this->container->get( 'countdown.shortcode', [ $attributes, $content ] );
		} );

		add_shortcode( 'totalcontest-image', function ( $attributes, $content = null ) {
			return (string) $this->container->get( 'content.shortcode.image', [ $attributes, $content ] );
		} );

		add_shortcode( 'totalcontest-video', function ( $attributes, $content = null ) {
			return (string) $this->container->get( 'content.shortcode.video', [ $attributes, $content ] );
		} );

		add_shortcode( 'totalcontest-audio', function ( $attributes, $content = null ) {
			return (string) $this->container->get( 'content.shortcode.audio', [ $attributes, $content ] );
		} );

		add_shortcode( 'totalcontest-text', function ( $attributes, $content = null ) {
			return (string) $this->container->get( 'content.shortcode.text', [ $attributes, $content ] );
		} );

		add_shortcode( 'totalcontest-file', function ( $attributes, $content = null ) {
			return (string) $this->container->get( 'content.shortcode.file', [ $attributes, $content ] );
		} );
	}

	/**
	 * Register Taxonomies.
	 */
	public function registerTaxonomies() {
		$this->container->get( 'submissions.category' );
	}

	/**
	 * Register CPTs.
	 */
	public function registerCustomPostTypes() {
		$this->container->get( 'contests.cpt' );
		$this->container->get( 'submissions.cpt' );
	}

	/**
	 * Load textdomain.
	 */
	public function loadTextDomain() {
		$locale         = get_locale();
		$localeFallback = substr( $locale, 0, 2 );
		$mofile         = "totalcontest-{$locale}.mo";
		$mofileFallback = "totalcontest-{$localeFallback}.mo";
		$path           = $this->application->env( 'path' );

		$loaded = load_textdomain( 'totalcontest', "{$path}languages/{$mofile}" );
		if ( ! $loaded ):
			$loaded = load_textdomain( 'totalcontest', "{$path}languages/{$mofileFallback}" );
		endif;

		if ( ! is_admin() || Misc::isDoingAjax() ):
			// Customized expressions
			$expressions = (array) $this->application->option( 'expressions', [] );

			if ( ! empty( $expressions ) ):
				if ( isset( $GLOBALS['l10n']['totalcontest'] ) ):
					$domain = $GLOBALS['l10n']['totalcontest'];
				else:
					$domain = $GLOBALS['l10n']['totalcontest'] = new \MO();
				endif;

				if ($domain instanceof \NOOP_Translations) {
					$domain = $GLOBALS['l10n']['totalcontest'] = new \MO();
				}

				foreach ( $expressions as $expression => $expressionContent ):
					if ( empty( $expressionContent['translations'] ) || empty( $expressionContent['translations'][0] ) ):
						continue;
					endif;
					if ( empty( $domain->entries[ $expression ] ) ):
						$entry = new \Translation_Entry( [
							                                 'singular'     => $expression,
							                                 'translations' => $expressionContent['translations'],
						                                 ] );
						$domain->add_entry( $entry );
					else:
						$domain->entries[ $expression ]->translations = $expressionContent['translations'];
					endif;
				endforeach;
			endif;
		endif;
	}

	/**
	 * On activation.
	 */
	public function onActivation( $networkWide ) {
		$sites = is_multisite() && $networkWide ? get_sites() : [ get_current_blog_id() ];

		foreach ( $sites as $site ):
			if ( is_multisite() ):
				switch_to_blog( $site->blog_id );
			endif;

			// Migrate the database
			$this->container->get( 'migrations.schema' )
			                ->migrate();

			// Register post types  & flush rewrite rules
			$this->registerCustomPostTypes();
			add_action( 'init', 'flush_rewrite_rules', 99 );
			wp_schedule_single_event( time(), 'totalcontest/actions/urls/flush' );

			// Purge previous cache
			$this->container->get( 'utils.purge.cache' );
			$this->container->get( 'utils.purge.store' );

			// Create cache directories
			$this->container->get( 'utils.create.cache' );
			$this->container->get( 'utils.create.exports' );

			// Reactivate current license, if any
			$this->container->get( 'admin.activation' )
			                ->reactivateLicense();

			// Trigger action
			do_action( 'totalcontest/actions/activated' );

			if ( is_multisite() ):
				restore_current_blog();
			endif;
		endforeach;
	}

	/**
	 * On deactivation.
	 */
	public function onDeactivation( $networkWide ) {
		// Flush rewrite rules
		flush_rewrite_rules();

		// Flush cache
		wp_cache_flush();

		// Trigger action
		do_action( 'totalcontest/actions/deactivated' );

		$this->container->get( 'scheduler' )->unregister();
	}

	/**
	 * On uninstall.
	 */
	public static function onUninstall() {
		// Flush rewrite rules
		flush_rewrite_rules();

		$sites = is_multisite() ? get_sites() : [ get_current_blog_id() ];

		foreach ( $sites as $site ):
			if ( is_multisite() ):
				switch_to_blog( $site->blog_id );
			endif;

			$userConsent = TotalContest()->option( 'advanced.uninstallAll' );
			if ( $userConsent ):
				// Delete tables
				$tables = array_keys( TotalContest()->env( 'db.tables' ) );
				foreach ( $tables as $tableName ):
					$table = TotalContest()->env( "db.tables.{$tableName}" );
					$query = "DROP TABLE IF EXISTS {$table}";
					TotalContest( 'database' )->query( $query );
				endforeach;
				// Delete contests & submissions
				$query = new \WP_Query( [
					                        'post_type'   => [ 'contest', 'contest_submission' ],
					                        'post_status' => 'any',
					                        'fields'      => 'ids',
				                        ] );
				$posts = $query->get_posts();
				foreach ( $posts as $id ) {
					wp_delete_post( $id, true );
				}
				// Delete taxonomies
				$terms = get_terms( 'submission_category', [ 'hide_empty' => false, 'fields' => 'ids' ] );
				foreach ( $terms as $id ):
					wp_delete_term( $id, 'submission_category' );
				endforeach;
				// Delete files
				TotalContest( 'utils.purge.cache' );
				TotalContest( 'utils.purge.store' );
				// Delete options
				TotalContest( 'options' )->deleteOptions();
			endif;

			if ( is_multisite() ):
				restore_current_blog();
			endif;
		endforeach;


		// Trigger action
		do_action( 'totalcontest/actions/uninstalled', $userConsent );
	}

	/**
	 * Bootstrap
	 */
	public function bootstrap() {
		/**
		 * Fires before bootstrapping TotalContest.
		 *
		 * @param  Plugin  $this  Plugin instance.
		 *
		 * @since 2.0.0
		 * @order 3
		 */
		do_action( 'totalcontest/actions/before/bootstrap', $this );

		$this->container->get( 'url' );
		$this->container->get( 'bootstrap' );

		/**
		 * Fires after bootstrapping TotalContest.
		 *
		 * @param  Plugin  $this  Plugin instance.
		 *
		 * @since 2.0.0
		 * @order 5
		 */
		do_action( 'totalcontest/actions/after/bootstrap', $this );

		$this->container->get( 'scheduler' )->register();
	}

	/**
	 * Bootstrap AJAX.
	 */
	public function bootstrapAjax() {
		/**
		 * Fires before bootstrapping AJAX handler.
		 *
		 * @param  Plugin  $this  Plugin instance.
		 *
		 * @since 2.0.0
		 * @order 6
		 */
		do_action( 'totalcontest/actions/before/bootstrap-ajax', $this );

		$this->container->get( 'admin.ajax' );

		/**
		 * Fires after bootstrapping AJAX handler.
		 *
		 * @param  Plugin  $this  Plugin instance.
		 *
		 * @since 2.0.0
		 * @order 8
		 */
		do_action( 'totalcontest/actions/after/bootstrap-ajax', $this );
	}

	/**
	 * Bootstrap admin.
	 */
	public function bootstrapAdmin() {
		/**
		 * Fires before bootstrapping admin.
		 *
		 * @param  Plugin  $this  Plugin instance.
		 *
		 * @since 2.0.0
		 * @order 9
		 */
		do_action( 'totalcontest/actions/before/bootstrap-admin', $this );

		$this->container->get( 'admin.bootstrap' );

		/**
		 * Fires after bootstrapping admin.
		 *
		 * @param  Plugin  $this  Plugin instance.
		 *
		 * @since 2.0.0
		 * @order 11
		 */
		do_action( 'totalcontest/actions/after/bootstrap-admin', $this );
	}

	/**
	 * Bootstrap extensions.
	 */
	public function bootstrapExtensions() {
		/**
		 * Fires before bootstrapping extensions.
		 *
		 * @param  Plugin  $this  Plugin instance.
		 *
		 * @since 2.0.0
		 * @order 1
		 */
		do_action( 'totalcontest/actions/before/bootstrap-extensions', $this );

		$activatedExtension = $this->container->get( 'modules.repository' )
		                                      ->getActiveWhere( [ 'type' => 'extension' ] );
		/**
		 * Filters the list of activated extensions.
		 *
		 * @param  array  $activatedExtension  Array of extensions information.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$activatedExtension = apply_filters( 'totalcontest/filters/extensions/activated', $activatedExtension );

		// @TODO: Improve error reporting for this part.
		try {
			foreach ( $activatedExtension as $extension ):
				if ( $this->container->get( 'filesystem' )
				                     ->exists( $extension['dirName'] . '/Extension.php' ) && class_exists( $extension['class'] ) ):
					( new $extension['class'] )->run();
				else:
					throw new \RuntimeException( "Please check that \"{$extension['id']}\" extension file uses the correct namespace." );
				endif;
			endforeach;
		} catch ( \Exception $exception ) {
			if ( Misc::isDevelopmentMode() ):
				trigger_error( $exception->getMessage(), E_USER_WARNING );
			else:
				TotalContest( 'modules.repository' )->setInactive( $extension['id'] );
			endif;
		}

		/**
		 * Fires after bootstrapping extensions.
		 *
		 * @param  Plugin  $this  Plugin instance.
		 *
		 * @since 2.0.0
		 * @order 2
		 */
		do_action( 'totalcontest/actions/after/bootstrap-extensions', $this );
	}

	public function objectsCount() {
		return [
			'contests'    => TotalContest( 'contests.repository' )->count(),
			'submissions' => TotalContest( 'submissions.repository' )->count( [] ),
			'log'         => TotalContest( 'log.repository' )->count( [] ),
			'votes'       => TotalContest( 'submissions.repository' )->countVotes(),
		];
	}
}
