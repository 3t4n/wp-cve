<?php
// Version: 1.0
// File: class.phpopenaichat.php
// Name: PHP OpenAI API Chat Completions Class
// Description: A php class to use the OpenAI API chat completions to chat with a GPT-3.5 or a GPT-4 model.
//              Have a conversation with a GPT-3.5 or GPT-4 model.
// Using: Curl, PHP 8.0
// Author: https://www.phpclasses.org/browse/author/144301.html
// License: BSD License
// Features:
// - Not designed for public/production use. Use at your own risk. If using for public usage, please check the security of input better.
// - Send messages to the model and get a response.
// - Estimate the token count of the messages to send to the model.
// - Set the model to use (gpt-3.5-turbo or gpt-4).
// - Set the max_tokens to use.
// - Set the temperature to use.
// - Set the frequency_penalty to use.
// - Set the presence_penalty to use.
// - Script attempts to estimate the token count of the messages to send to the model, and subtracts from max_tokens.

/*
    Detailed description:
    This is a class to use the OpenAI API chat completions to chat with a GPT-3.5 or a GPT-4 model.
    Have a conversation with a GPT-3.5 or GPT-4 model.
    Uses the OpenAI API chat completions to chat with a GPT-3.5 or a GPT-4 model.
*/

/* 
    Instructions for use:
    1. Create an OpenAI account at https://beta.openai.com/
    2. Create an API key at https://beta.openai.com/account/api-keys
    3. Make a variable to store your API key in, let's say $apiKey
    4. Create a new PHPOpenAIChat object, passing in your API key, like this:
        $openAIChat = new PHPOpenAIChat($apiKey);

    5. Send a message to the model and get a response, like this:
        $messages = []; # create a conversation
        // set agent
        $messages = $openAIChat->set_agent($messages, "You are an assistant cat that can speak english and is named Henry.");
        // add prompt to messages conversation
        $messages = $openAIChat->add_prompt_to_messages($messages, "What is your name?"); 
        $response = $openAIChat->sendMessage($messages);
        print_r($response); # show entire returned array
        // print text
        echo "<br />response text: " . $openAIChat->get_response_text($response) ."<br />";

    6. You can also set the model to use (gpt-3.5-turbo or gpt-4), the max_tokens to use, the temperature to use, the frequency_penalty to use, and the presence_penalty to use.
    7. You can also estimate the token count of the messages to send to the model, and subtracts from max_tokens.
    8. You should be able to get the text from the response by using $response['choices'][0]['message']['content']
    9. You can append the text from the response to the messages to send to the model
    10. You can also use the add_prompt_to_messages() function to add a prompt to the current conversation
    11. Then you can repeat the cycle again by sending the messages to the model and getting another response.
*/

            
// TODO: A more pleasant example UI.

// Begin class PHPOpenAIChat
class PHPOpenAIChat {
    private $apiKey;
    private $apiUrl = "https://api.openai.com/v1/chat/completions";
    private $max_tokens = 4096;

    public $model = 'gpt-3.5-turbo'; // gpt-3.5-turbo, gpt-4
    public $temperature = 1.0;
    public $freq_penalty = 0.0;
    public $pres_penalty = 0.0;


    // constructor: set the api key
    public function __construct($apiKey) { $this->apiKey = $apiKey; }

    // send a message to the model and get a response
    public function sendMessage($messages)
    {
        // get token size of messages and subtract from max_tokens
        $temp_max_tokens = $this->max_tokens - $this->estimate_openai_token_count_messages($messages);
        $data =
        [
            # 'model' => 'gpt-3.5-turbo',
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => $this->temperature,
            'max_tokens' => $temp_max_tokens,
            'frequency_penalty' => $this->freq_penalty,
            'presence_penalty' => $this->pres_penalty,
        ];

        $headers = [ 'Content-Type: application/json', 'Authorization: Bearer ' . $this->apiKey, ];

        $ch = curl_init($this->apiUrl);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);
        
        return json_decode($response, true);
    } // end sendMessage()

    // estimate the token count of the messages to send to the model
    public function estimate_openai_token_count_messages($messages) {
        // count all characters in the messages (characters in both keys and values)
        // and divide by 3.5 to get an estimate
        // ONLY AN ESTIMATE!
        $token_count = 0;
        foreach ($messages as $message) {
            foreach ($message as $key => $value) {
                // add to token count
                $token_count += strlen($key);
                $token_count += strlen($value);
            }
        }
        return ceil($token_count / 3.2);
    } // end estimate_openai_token_count_messages()

    // append response to messages ( This is the part that is sent to the model from the model )
    public function append_response_to_messages($messages, $string) {
        $messages[] = [
            "role" => "assistant",
            "content" => $string
        ];
        return $messages;
    } // end append_response_to_messages()

    // add prompt to messages ( This is the part that is sent to the model from the user )
    public function add_prompt_to_messages($messages, $prompt) {
        $messages[] = [
            "role" => "user",
            "content" => $prompt
        ];
        return $messages;
    } // end add_prompt_to_messages()

    /* agent functions */
    public function get_agent($messages) {
        foreach ($messages as $message) {
            if ($message['role'] == 'system') {
                return $message['content'];
            }
        }
        return '';
    } // end get_agent()

    public function set_agent($messages, $agent) {
        # first check if role:system exists and then set it to the new agent, otherwise just create the agent
        # and prepend it to the messages array
        $agent_set = false;
        $msg_count = 0;

        foreach ($messages as $message) {
            if ($message['role'] == 'system') {
                $messages[$msg_count]['content'] = $agent;
                $agent_set = true;
                return $messages;
            }

            $msg_count++;
        }

        if (!$agent_set) {
            array_unshift($messages, [
                "role" => "system",
                "content" => $agent
            ]);
        }

        return $messages;
    } // end set_agent()
    /* end agent functions */

    /* begin response functions */
    public function get_response_text($response) {
        if(!isset($response['choices'][0]['message']['content'])) 
            return '';
        
        return $response['choices'][0]['message']['content'];
    } // end get_response_text()
    /* end response functions */


    public function set_model($model) {
        $this->model = $model;
    } // end set_model()

    public function set_temperature($temperature) {
        $this->temperature = $temperature;
    } // end set_temperature()

    public function set_freq_penalty($freq_penalty) {
        $this->freq_penalty = $freq_penalty;
    } // end set_freq_penalty()

    public function set_pres_penalty($pres_penalty) {
        $this->pres_penalty = $pres_penalty;
    } // end set_pres_penalty()

    public function set_max_tokens($max_tokens) {
        $this->max_tokens = $max_tokens;
    } // end set_max_tokens()


} // end class PHPOpenAIChat

?>