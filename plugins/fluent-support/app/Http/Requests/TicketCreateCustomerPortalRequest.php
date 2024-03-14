<?php

namespace FluentSupport\App\Http\Requests;

use FluentSupport\Framework\Foundation\RequestGuard;

class TicketCreateCustomerPortalRequest extends RequestGuard
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'title'       => 'required',
            'content'     => 'required'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'Ticket title is required',
            'content.required' => 'Ticket content is required'
        ];
    }

    public function sanitize()
    {
        $data = $this->all();

        $sanitizeRules = [
            'title' => 'sanitize_text_field',
            'content' => 'wp_kses_post',
            'product_id' => 'intval',
            'client_priority' => 'sanitize_text_field'
        ];

        if( $data && is_array($data) ) {
            foreach ($data as $dataKey => $dataItem) {
                $sanitizeFunc = isset($sanitizeRules[$dataKey]) ? $sanitizeRules[$dataKey]: 'sanitize_text_field';
                if(is_array($dataItem)) {
                    $data[$dataKey] = map_deep($dataItem, $sanitizeFunc);
                } else {
                    $data[$dataKey] = $sanitizeFunc($dataItem);
                }
            }
        }

        return $data;
    }
}
