<?php

interface WFACP_Import_Export {
	public function import( $aero_id,  $slug );

	public function export( $aero_id,  $slug );
}