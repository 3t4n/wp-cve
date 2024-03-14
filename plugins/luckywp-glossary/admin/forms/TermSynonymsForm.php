<?php

namespace luckywp\glossary\admin\forms;

use luckywp\glossary\core\base\Model;
use luckywp\glossary\core\Core;
use luckywp\glossary\plugin\Term;

class TermSynonymsForm extends Model
{

    /**
     * @var string
     */
    public $synonyms;

    /**
     * @param Term $term
     * @param array $config
     */
    public function __construct($term, array $config = [])
    {
        $this->synonyms = implode(', ', $term->synonyms);
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['synonyms', 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     * @return array
     */
    public function getSynonyms()
    {
        return Term::synonymsStringToArray($this->synonyms);
    }

    public function nonceField()
    {
        wp_nonce_field('lwpgls_synonyms_save', '_lwpgls_synonyms');
    }

    public static function verifyNonce()
    {
        return wp_verify_nonce(Core::$plugin->request->post('_lwpgls_synonyms'), 'lwpgls_synonyms_save');
    }
}
