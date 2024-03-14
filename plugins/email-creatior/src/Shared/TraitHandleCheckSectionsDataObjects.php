<?php

namespace WilokeEmailCreator\Shared;

use stdClass;

trait TraitHandleCheckSectionsDataObjects
{
	public function handleCheckSectionsDataObjects(array $aRawData): array
	{
		$aDataSection = [];
			foreach ($aRawData as $aSection){
				if (isset($aSection['fieldDefinitions'])){
					if (isset($aSection['fieldDefinitions']['image'])&& empty($aSection['fieldDefinitions']['image'])){
						$aSection['fieldDefinitions']['image'] = new stdClass;
					}
					if (isset($aSection['fieldDefinitions']['button'])&& empty($aSection['fieldDefinitions']['button'])){
						$aSection['fieldDefinitions']['button'] = new stdClass;
					}
				}
				$aDataSection[] = $aSection;
			}
		return $aDataSection;
	}
}
