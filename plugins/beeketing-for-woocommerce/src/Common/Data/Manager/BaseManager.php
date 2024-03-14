<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 9:36 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Manager;

use Symfony\Component\HttpFoundation\Request;

abstract class BaseManager
{
    const DEFAULT_PAGE = 1;
    const DEFAULT_ITEMS_PER_PAGE = 100;

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function buildGetArgument(Request $request)
    {
        return $this->buildArgumentFromRequest($request, [
            'resource_id',
        ]);
    }

    /**
     * Build argument from list of fields
     * @param Request $request
     * @param array $fields
     * @param array $defaults
     * @return array
     * @throws \Exception
     */
    public function buildArgumentFromRequest(Request $request, array $fields, $defaults = [])
    {
        $arg = [];
        $defaultKeys = array_keys($defaults);

        foreach ($fields as $field) {
            $value = $request->get($field);

            // Throw error if field is missing
            if ($value === null) {
                if (in_array($field, $defaultKeys)) {
                    $value = $defaults[$field];
                } else {
                    throw new \Exception(sprintf('Missing field %s from request', $field));
                }
            }

            $arg[$field] = $value;
        }

        return $arg;
    }

    /**
     * index for response get many method
     * @return string
     */
    abstract public function getResponseKeyGetMany();
}
