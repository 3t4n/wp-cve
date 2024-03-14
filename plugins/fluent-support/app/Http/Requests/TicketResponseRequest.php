<?php

namespace FluentSupport\App\Http\Requests;

use FluentSupport\Framework\Foundation\RequestGuard;

class TicketResponseRequest extends RequestGuard
{
    /**
     * @return Array
     */
    public function rules()
    {
        return [
            'content' => 'required'
        ];
    }

    /**
     * @return Array
     */
    public function messages()
    {
        return [
            'content.required' => 'Reply content is required'
        ];
    }

    public function sanitize()
    {
        $data = $this->all();

        $sanitizeRules = [
            'content' => 'wp_kses_post',
            'conversation_type' => 'sanitize_text_field',
            'close_ticket' => 'sanitize_text_field',
            'ticket_id' => 'intval'
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

            return $data;
        }

        return sanitize_text_field($data);
    }
}
