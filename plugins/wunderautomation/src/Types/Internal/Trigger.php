<?php

namespace WunderAuto\Types\Internal;

/**
 * Class Trigger
 */
class Trigger extends BaseInternalType
{
    /**
     * @var string
     */
    public $trigger = '';

    /**
     * @var \stdClass
     */
    public $value;

    /**
     * @param \stdClass|array<int, mixed>|null $state
     */
    public function __construct($state = null)
    {
        $this->value = (object)[];
        parent::__construct($state);

        $this->trigger = sanitize_text_field(str_replace('|', '\\', $this->trigger));
        // Generic triggers
        $this->sanitizeObjectProp($this->value, 'onlyOnce', 'bool');

        // Webhooks
        $this->sanitizeObjectProp($this->value, 'code', 'key');
        $this->sanitizeObjectProp($this->value, 'useBasicAuth', 'bool');
        $this->sanitizeObjectProp($this->value, 'basicAuthUser', 'text');
        $this->sanitizeObjectProp($this->value, 'basicAuthPass', 'text');
        $this->sanitizeObjectProp($this->value, 'useHeaderKey', 'bool');
        $this->sanitizeObjectProp($this->value, 'headerAPIKey', 'text');
        $this->sanitizeObjectProp($this->value, 'headerAPISecret', 'text');
        $this->sanitizeObjectProp($this->value, 'useHMACSignedPayload', 'bool');
        $this->sanitizeObjectProp($this->value, 'HMACSignatureHeader', 'text');
        $this->sanitizeObjectProp($this->value, 'detectPostMeta', 'bool');
        $this->sanitizeObjectArray(
            $this->value,
            'objects',
            ['type' => 'text', 'parameter' => 'text', 'name' => 'key', 'required' => 'bool']
        );
    }

    /**
     * Handle json
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize()
    {
        $return = [];
        foreach (get_object_vars($this) as $property => $value) {
            switch ($property) {
                case 'trigger':
                    $return[$property] = BaseInternalType::$wpPostMetaMode ?
                        $this->postMetaTrigger() :
                        $value;
                    break;
                case 'map':
                    break;
                default:
                    $return[$property] = $value;
            }
        }

        return $return;
    }

    /**
     * Return the trigger class escaped for storing in wp postmeta
     *
     * @return string
     */
    public function postMetaTrigger()
    {
        return str_replace('\\', '|', $this->trigger);
    }
}
