<?php

namespace FluentSupport\App\Http\Requests;

use FluentSupport\Framework\Foundation\RequestGuard;

class TicketRequest extends RequestGuard
{
    /**
     * @return Array
     */
    public function rules()
    {
        $rules = [
            'ticket.title'       => 'required',
            'ticket.content'     => 'required'
        ];

        //If create customer checkbox is checked
        if($this->get('ticket.create_customer') == 'yes'){
            $rules['newCustomer.email'] = 'required';
            //If Create WP user checkbox is checked
            if($this->get('ticket.create_wp_user') == 'yes'){
                $rules['newCustomer.username'] = 'required';
                $rules['newCustomer.password'] = 'required';
            }
        }

        return apply_filters('fluent_support/ticket_create_validation_rules', $rules);
    }

    /**
     * @return Array
     */
    public function messages()
    {
        $messages = [
            'ticket.title.required' => 'Ticket title is required',
            'ticket.content.required' => 'Ticket content is required',
            'newCustomer.email.required' => 'Customer email is required',
            'newCustomer.username.required' => 'Customer username is required wp user',
            'newCustomer.password.required' => 'Customer password is required for wp user'
        ];

        return apply_filters('fluent_support/ticket_create_validation_messages', $messages);
    }


    public function sanitize()
    {
        $data = $this->all();
        $ticketData = $data["ticket"];

        $sanitizeRules = [
            'customer_id' => 'intval',
            'mailbox_id' => 'intval',
            'title' => 'sanitize_text_field',
            'content' => 'wp_kses_post',
            'product_id' => 'intval',
            'client_priority' => 'sanitize_text_field',
            'create_customer' => 'sanitize_text_field',
            'create_wp_user' => 'sanitize_text_field',
            'first_name' => 'sanitize_text_field',
            'last_name' => 'sanitize_text_field',
            'email' => 'sanitize_text_field',
            'username' => 'sanitize_text_field',
            'password' => 'sanitize_text_field',
        ];

        if( $ticketData && is_array($ticketData) ) {
            foreach ($ticketData as $dataKey => $dataItem) {
                $sanitizeFunc = isset($sanitizeRules[$dataKey]) ? $sanitizeRules[$dataKey]: 'sanitize_text_field';
                if(is_array($dataItem)) {
                    $ticketData[$dataKey] = map_deep($dataItem, $sanitizeFunc);
                } else {
                    $ticketData[$dataKey] = $sanitizeFunc($dataItem);
                }
            }

            $data["ticket"] = $ticketData;
        }

        $newCustomer = isset($data['newCustomer'])? $data['newCustomer']: [];

        if(is_array($newCustomer) && !empty($newCustomer)) {
            foreach ($newCustomer as $dataKey => $dataItem) {
                $sanitizeFunc = isset($sanitizeRules[$dataKey]) ? $sanitizeRules[$dataKey]: 'sanitize_text_field';

                if(is_array($dataItem)) {
                    $newCustomer[$dataKey] = map_deep($dataItem, $sanitizeFunc);
                } else {
                    $newCustomer[$dataKey] = $sanitizeFunc($dataItem);
                }

            }

            $data['newCustomer'] = $newCustomer;
        }

        return $data;
    }
}
