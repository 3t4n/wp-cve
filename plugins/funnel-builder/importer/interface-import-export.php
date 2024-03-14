<?php

interface WFFN_Import_Export {
	public function import( $module_id, $export_content = '' );

	public function import_template_single( $module_id, $content );

	public function export( $module_id, $slug );
}
