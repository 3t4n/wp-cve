<?php namespace MSMoMDP\Wp;

use MSMoMDP\Std\Core\Arr;
use MSMoMDP\Std\Core\Path;
use MSMoMDP\Std\Core\Autoloader;
use MSMoMDP\Std\Process\AjaxResp;


class Ajax {

	/*
		//register_task args
		[
			'script-dependency' => ['dep1', 'dep2'],
			'actions' => [
				'action-name1' => [
					'trigger-id' => 'js--ajax-triger....',
					'trigger-action' => 'click',
					'action-args' => 'args-back-to-php'
				],
				'action2-name' => [
					'trigger-id' => 'js--ajax-triger....',
					'trigger-action' => 'click',
					'action-args' => 'args-back-to-php'
				]
			]

		];

		//from js to php
		[
			'action' => 'run_task',

			'args' => [
				'task-id' => '',
				'actions' => [
					'name' => 'args',
					'name2' => 'args2'
				]
			]
		];
	*/

	private $ajaxTasks     = array();
	private $activeScripts = array();
	public function __construct() {
		 add_action( 'wp_ajax_run_task', array( $this, 'run_task' ) );
		add_action( 'wp_ajax_nopriv_run_task', array( $this, 'run_task_nopriv' ) );
	}

	public function register_task( string $taskAndScriptName, string $componentClassName, array $args ) {
		if ( ! empty( $taskAndScriptName ) && ! empty( $componentClassName ) && $args ) {
			Autoloader::Instance()->require_once( array( $componentClassName ) );
			$this->ajaxTasks[ $taskAndScriptName ] = array(
				'component' => array(
					'className' => $componentClassName,
				),
				'args'      => $args,
				'enabled'   => false,
			);
		}
	}

	public function enable_tasks( array $taskIdsList, string $hookAction ) {
		foreach ( $this->ajaxTasks as $taskName => &$taskCfg ) {
			foreach ( $taskIdsList as $taskIdSrc ) {
				if ( $taskName === $taskIdSrc ) {
					$taskCfg['enabled'] = true;
				}
			}
		}
		add_action( $hookAction, array( $this, 'admin_equeue_scripts' ) );
	}

	public function admin_equeue_scripts() {
		foreach ( $this->ajaxTasks as $taskId => $taskCfg ) {
			if ( $taskCfg['enabled'] ) {
				$scriptDir        = Arr::sget( $taskCfg, 'args.script-directory', '' );
				$scriptDependency = Arr::sget( $taskCfg, 'args.script-dependency', array() );
				$scriptPath       = Path::combine_unix( $scriptDir, "${taskId}.bundle.js" );
				wp_register_script( $taskId, $scriptPath, $scriptDependency, false, true );
				wp_localize_script(
					$taskId,
					"${taskId}_globals",
					array(
						'taskId'  => $taskId,
						'ajaxUrl' => admin_url( 'admin-ajax.php' ),
						'nonce'   => wp_create_nonce( "${taskId}_nonce" ),
						'actions' => Arr::sget( $taskCfg, 'args.actions' ),
						'ajaxCb'  => 'run_task',
					)
				);

				wp_enqueue_script( $scriptDependency );
				// foreach ($scriptDependency as $dependency)
				{
				   // wp_enqueue_script( $dependency );
				}
				wp_enqueue_script( $taskId );
			}
		}
	}

	public function run_task() {
		$taskId = Arr::sget( $_POST, 'args.taskId', null );
		if ( $taskId && array_key_exists( $taskId, $this->ajaxTasks ) ) {
			check_ajax_referer( "${taskId}_nonce" );
			$actions = Arr::as_array( Arr::sget( $_POST, 'args.actions' ) );
			foreach ( $actions as $actionArgs ) {
				$actionName = Arr::sget( $actionArgs, 'name' );
				if ( ! empty( $actionName ) ) {
					$cfg           = $this->ajaxTasks[ $taskId ];
					$componentName = Arr::sget( $cfg, 'component.className' );
					if ( $actionName == 'init' || $actionName == 'reconnect_c2c_init' || $actionName == 'reconnect_rm_dup_init' ) { // TODO
						$componentInstance = new $componentName();
					} else {
						/*if (method_exists($componentName, 'reLoadDependencies'))
						{
							call_user_func([$componentName, 'reLoadDependencies']);
						}   */
						$componentInstance = get_transient( 'transient_ajax_' . $componentName );

					}
					if ( $componentInstance ) {

						if ( method_exists( $componentInstance, $actionName ) ) {
							$response = call_user_func( array( $componentInstance, $actionName ), Arr::as_array( $actionArgs ) );
							echo json_encode( $response );
							if ( $response->nextAction ) {
								set_transient( 'transient_ajax_' . $componentName, $componentInstance, 60 );
							} else {
								delete_transient( 'transient_ajax_' . $componentName );
							}
						}
					}
				}
			}
		}
		die();
	}
	public function run_task_nopriv() {
		 echo 'Try dirty job elsewhere!!';
		die();
	}

}
