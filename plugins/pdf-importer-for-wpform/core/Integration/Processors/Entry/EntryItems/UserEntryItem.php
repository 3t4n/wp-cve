<?php


namespace rnpdfimporter\core\Integration\Processors\Entry\EntryItems;


use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\PHPFormatterBase;

class UserEntryItem extends EntryItemBase
{
    public $UserId;


    public function GetText()
    {
        $user=\get_user_by('ID',$this->UserId);
        if($user==false)
            return '';
        return $user->user_nicename;

    }

    public function SetUserId($value)
    {
        $this->UserId=$value;
        return $this;
    }

    protected function InternalGetObjectToSave()
    {
        return (object)array(
            'UserId'=>$this->UserId
        );
    }

    public function InitializeWithOptions($field,$options)
    {
        $this->Field=$field;
        if(isset($options->UserId))
            $this->UserId=$options->UserId;

    }

    public function GetHtml($style = 'standard')
    {
        // TODO: Implement GetHtml() method.
    }
}