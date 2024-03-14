<?php


namespace WilokeEmailCreator\Templates\Services\PostMeta;

class AddPostMetaService extends PostMetaService {
	public function addPostMeta( array $aRawData ): array {
		$this->setIsUpdate( false );
		$this->setRawData( $aRawData );

		return $this->performSaveData();
	}
}
