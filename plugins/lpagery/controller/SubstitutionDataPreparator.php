<?php

class LPagerySubstitutionDataPreparator
{

    /**
     * @param $data1
     *
     * @return array
     * @throws Exception
     */
    public static function prepare_data($data)
    {
        if (empty($data)) {
            throw new Exception("DATA EMPTY");
        }
        $data = stripslashes($data);
        if (empty($data)) {
            throw new Exception("DATA EMPTY");
        }
        $json_decode = json_decode($data, true);
        if (!is_array($json_decode)) {
            throw new Exception("INVALID DATA " . $data);
        }

        if (!is_array($json_decode[0] ?? null)) {
            $json_decode = array_map('urldecode', $json_decode);
            $json_decode = array_map(self::class . '::lpagery_sanitize_text', $json_decode);
        } else {
            $json_decode = array_map(function ($element) {
                $return_value = array_map('urldecode', $element);
                $return_value = array_map(self::class . '::lpagery_sanitize_text', $return_value);

				return $return_value;
			}, $json_decode );
		}
		return $json_decode;
	}

    private static function lpagery_sanitize_text($value)
    {
        if ($value == null) {
            return null;
        }
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return sanitize_url($value);
        } elseif (self::is_HTML($value)) {
            return !current_user_can("unfiltered_html") ? wp_kses_post($value) : $value;
        } else {
            return sanitize_text_field($value);
        }
    }

    private static function is_HTML($string)
    {
        if ($string != strip_tags($string)) {
            return true;
        } else {
            return false;
        }
    }

    private static function moveElementToIndex(array &$array, $key, $index)
    {
        if (!array_key_exists($key, $array)) {
            return;
        }

        $value = $array[$key];
        unset($array[$key]);

        $array = array_slice($array, 0, $index, true) +
            [$key => $value] +
            array_slice($array, $index, null, true);
    }

}
