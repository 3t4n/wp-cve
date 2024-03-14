<?php

namespace WilokeEmailCreator\Shared;

use WilokeEmailCreator\Illuminate\Prefix\AutoPrefix;

trait TraitHandleGeneralSettings
{
	public function handleSaveAutomatically($value): bool
	{
		return update_option(AutoPrefix::namePrefix('automatically'), $value);
	}

	public function getAutomatically()
	{
		return get_option(AutoPrefix::namePrefix('automatically'))??'deactive';
	}
	public function getBrandContentSection(){
		//$aData['logo']=WILOKE_EMAIL_CREATOR_IMAGE_URL.'images/logo/logoWiloke.png';
		return json_decode(file_get_contents(WILOKE_EMAIL_CREATOR_PATH.'src/DataFactory/DataImport/Configs/InfoAuthor.json'),true);
	}
}
