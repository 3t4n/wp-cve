<?php

namespace TotalContest\Form;

use TotalContestVendors\TotalCore\Contracts\Http\Request;

class Factory implements \TotalContest\Contracts\Form\Factory
{

    protected $map = [
        'form'             => '\TotalContestVendors\TotalCore\Form\Form',
        'form.participate' => '\TotalContest\Form\ParticipateForm',
        'form.vote'        => '\TotalContest\Form\VoteForm',
        'form.rate'        => '\TotalContest\Form\RateForm',
        'page'             => '\TotalContestVendors\TotalCore\Form\Page',
        'text'             => '\TotalContestVendors\TotalCore\Form\Fields\TextField',
        'captcha'          => '\TotalContestVendors\TotalCore\Form\Fields\CaptchaField',
        'textarea'         => '\TotalContestVendors\TotalCore\Form\Fields\TextareaField',
        'checkbox'         => '\TotalContestVendors\TotalCore\Form\Fields\CheckboxField',
        'radio'            => '\TotalContestVendors\TotalCore\Form\Fields\RadioField',
        'select'           => '\TotalContestVendors\TotalCore\Form\Fields\SelectField',
        'file'             => '\TotalContestVendors\TotalCore\Form\Fields\FileField',
        'category'         => '\TotalContest\Form\Fields\CategoryField',
        'image'            => '\TotalContest\Form\Fields\ImageField',
        'video'            => '\TotalContest\Form\Fields\VideoField',
        'audio'            => '\TotalContest\Form\Fields\AudioField',
        'richtext'         => '\TotalContest\Form\Fields\RichTextField',
        'embed'            => '\TotalContest\Form\Fields\EmbedField',
        'number'           => '\TotalContestVendors\TotalCore\Form\Fields\NumberField'
    ];

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function makeForm()
    {
        return new $this->map['form'];
    }

    public function makeParticipateForm($contest)
    {
        return new $this->map['form.participate']($contest, $this->request, $this);
    }

    public function makeVoteForm($submission)
    {
        return new $this->map['form.vote']($submission, $this->request, $this);
    }

    public function makeRateForm($submission)
    {
        return new $this->map['form.rate']($submission, $this->request, $this);
    }

    public function makePage()
    {
        return new $this->map['page'];
    }

    public function makeCaptchaField()
    {
        return new $this->map['captcha'];
    }

    public function makeTextField()
    {
        return new $this->map['text'];
    }

    public function makeNumberField()
    {
        return new $this->map['number'];
    }

    public function makeTextareaField()
    {
        return new $this->map['textarea'];
    }

    public function makeCheckboxField()
    {
        return new $this->map['checkbox'];
    }

    public function makeRadioField()
    {
        return new $this->map['radio'];
    }

    public function makeSelectField()
    {
        return new $this->map['select'];
    }

    public function makeFileField()
    {
        return new $this->map['file'];
    }

    public function makeImageField()
    {
        return new $this->map['image'];
    }

    public function makeVideoField()
    {
        return new $this->map['video'];
    }

    public function makeAudioField()
    {
        return new $this->map['audio'];
    }

    public function makeCategoryField()
    {
        return new $this->map['category'];
    }

    public function makeRichtextField()
    {
        return new $this->map['richtext'];
    }

    public function makeEmbedField()
    {
        return new $this->map['embed'];
    }

    public function setForm($className)
    {
        $this->map['form'] = (string)$className;
    }

    public function setParticipateForm($className)
    {
        $this->map['form.participate'] = (string)$className;
    }

    public function setVoteForm($className)
    {
        $this->map['form.vote'] = (string)$className;
    }

    public function setRateForm($className)
    {
        $this->map['form.vote'] = (string)$className;
    }

    public function setPage($className)
    {
        $this->map['page'] = (string)$className;
    }

    public function setTextField($className)
    {
        $this->map['text'] = (string)$className;
    }

    public function setNumberField($className)
    {
        $this->map['number'] = (string)$className;
    }

    public function setTextareaField($className)
    {
        $this->map['textarea'] = (string)$className;
    }

    public function setCheckboxField($className)
    {
        $this->map['checkbox'] = (string)$className;
    }

    public function setRadioField($className)
    {
        $this->map['radio'] = (string)$className;
    }

    public function setSelectField($className)
    {
        $this->map['select'] = (string)$className;
    }

    public function setFileField($className)
    {
        $this->map['file'] = (string)$className;
    }

    public function setRichtextField($className)
    {
        $this->map['richtext'] = (string)$className;
    }
}