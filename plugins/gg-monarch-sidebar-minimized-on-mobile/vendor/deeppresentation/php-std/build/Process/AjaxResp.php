<?php namespace MSMoMDP\Std\Process;

use MSMoMDP\Std\Core\Arr;

/*
$response = [
	'success' => true,
	'notices' => [
		[
		'title' => 'Test tiiiiitle',
		'desc' => 'oifudhaifudhaifhla',
		'type' => 'success'
		],
		[
			'title' => 'Test tiiiiitle 2 ',
			'desc' => 'oifudhaifudhaifhladsfsdfsfdsdfs',
			'type' => 'error'
		]
	]
]
*/

class AjaxResp {

	public $success    = true;
	public $rawData    = '';
	public $notices    = array();
	public $nextAction = null;
	public function __construct() {
	}

	public function set_next_action( string $name, $args = null ) {
		$this->nextAction = array(
			'name' => $name,
			'args' => 'args',
		);
	}

	public function importNotices( AjaxResp $resToImport ) {
		$this->add_notices( $resToImport->notices );
	}

	public function add_notices( array $notices ) {
		foreach ( $notices as $notice ) {
			$title = Arr::sget( $notice, 'title', null );
			if ( $title ) {
				$this->add_notice(
					$title,
					Arr::sget( $notice, 'type', 'info' ),
					Arr::sget( $notice, 'desc', '' )
				);
			}
		}
	}

	public function add_notice( string $title, string $type = 'info', string $desc = '' ) {
		$this->notices[] = array(
			'type'  => $type,
			'title' => $title,
			'desc'  => $desc,
		);
	}

	public function append_to_last_notice( string $title, string $type = 'info', string $desc = '' ) {
		$noticeCnt = count( $this->notices );
		if ( $noticeCnt > 0 ) {
			$this->notices[ $noticeCnt - 1 ]['title'] .= ( empty( $this->notices[ $noticeCnt - 1 ]['title'] ) ) ? "${title}" : " ${title}";
			$this->notices[ $noticeCnt - 1 ]['desc']  .= ( empty( $this->notices[ $noticeCnt - 1 ]['desc'] ) ) ? "${desc}" : " ${desc}";
			$this->notices[ $noticeCnt - 1 ]['type']   = $type;
		} else {
			$this->add_notice( $title, $type, $desc );
		}
	}
}
