<?php

/*
 * SendPulse REST API Client
 *
 * Documentation
 * https://login.sendpulse.com/manual/rest-api/
 * https://sendpulse.com/api
 *
 */

// namespace Sendpulse\RestApi;

// use Exception;
// use Sendpulse\RestApi\Storage\FileStorage;
// use Sendpulse\RestApi\Storage\TokenStorageInterface;
// use stdClass;

class AFISendPulseApiClient implements ApiInterface
{

    private $apiUrl = 'https://api.sendpulse.com';

    private $userId;
    private $secret;
    private $token;

    private $refreshToken = 0;
    private $retry = false;

    /**
     * @var null|TokenStorageInterface
     */
    private $tokenStorage;


    /**
     * Sendpulse API constructor
     *
     * @param                       $userId
     * @param                       $secret
     * @param TokenStorageInterface $tokenStorage
     *
     * @throws Exception
     */
    public function __construct($userId, $secret, TokenStorageInterface $tokenStorage = null)
    {
        if ($tokenStorage === null) {
            $tokenStorage = new FileStorage();
        }
        if (empty($userId) || empty($secret)) {
            throw new Exception('Empty ID or SECRET');
        }

        $this->userId = $userId;
        $this->secret = $secret;
        $this->tokenStorage = $tokenStorage;
        $hashName = md5($userId . '::' . $secret);

        /** load token from storage */
        $this->token = $this->tokenStorage->get($hashName);

        if (empty($this->token) && !$this->getToken()) {
            throw new Exception('Could not connect to api, check your ID and SECRET');
        }
    }

    /**
     * Get token and store it
     *
     * @return bool
     */
    private function getToken()
    {
        $data = array(
            'grant_type' => 'client_credentials',
            'client_id' => $this->userId,
            'client_secret' => $this->secret,
        );

        $requestResult = $this->sendRequest('oauth/access_token', 'POST', $data, false);

        if ($requestResult->http_code !== 200) {
            return false;
        }

        $this->refreshToken = 0;
        $this->token = $requestResult->data->access_token;

        $hashName = md5($this->userId . '::' . $this->secret);
        /** Save token to storage */
        $this->tokenStorage->set($hashName, $this->token);

        return true;
    }

    /**
     * Form and send request to API service
     *
     * @param        $path
     * @param string $method
     * @param array $data
     * @param bool $useToken
     *
     * @return stdClass
     */
    protected function sendRequest($path, $method = 'GET', $data = array(), $useToken = true, $record = array())
    {
        $url = $this->apiUrl . '/' . $path;
        $args = array(
            'method' => strtoupper($method)
        );

        if ($useToken && !empty($this->token)) {
            $headers = array(
                'Authorization' => 'Bearer ' . $this->token
            );

            $args['headers'] = $headers;
        }

        if( $data ) {
            $args['body'] = $data;
        }

        $response = wp_remote_request( $url, $args );

        adfoin_add_to_log( $response, $url, $args, $record );

        $headerCode = $response['response']['code'];
        $responseBody = $response['body'];
        $responseHeaders = $response['headers'];

        if ($headerCode === 401 && $this->refreshToken === 0) {
            ++$this->refreshToken;
            $this->getToken();
            $retval = $this->sendRequest($path, $method, $data);
        } else {
            $retval = new stdClass();
            $retval->data = json_decode($responseBody);
            $retval->http_code = $headerCode;
            $retval->headers = $responseHeaders;
            $retval->method = $method . ':' . $url;
            $retval->timestamp = date('Y-m-d h:i:sP');
        }

        return $retval;
    }

    /**
     * Process results
     *
     * @param $data
     *
     * @return stdClass
     */
    protected function handleResult($data)
    {
        if (empty($data->data)) {
            $data->data = new stdClass();
        }
        if ($data->http_code !== 200) {
            $data->data->is_error = true;
            $data->data->http_code = $data->http_code;
            $data->data->headers = $data->headers;
            $data->data->curlErrors = $data->curlErrors;
            $data->data->ip = $data->ip;
            $data->data->method = $data->method;
            $data->data->timestamp = $data->timestamp;
        }

        return $data->data;
    }

    /**
     * Process errors
     *
     * @param null $customMessage
     *
     * @return stdClass
     */
    protected function handleError($customMessage = null)
    {
        $message = new stdClass();
        $message->is_error = true;
        if (null !== $customMessage) {
            $message->message = $customMessage;
        }

        return $message;
    }


    /*
     * API interface implementation
     */


    /**
     * Create address book
     *
     * @param $bookName
     *
     * @return stdClass
     */
    public function createAddressBook($bookName)
    {
        if (empty($bookName)) {
            return $this->handleError('Empty book name');
        }

        $data = array('bookName' => $bookName);
        $requestResult = $this->sendRequest('addressbooks', 'POST', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Edit address book name
     *
     * @param $id
     * @param $newName
     *
     * @return stdClass
     */
    public function editAddressBook($id, $newName)
    {
        if (empty($newName) || empty($id)) {
            return $this->handleError('Empty new name or book id');
        }

        $data = array('name' => $newName);
        $requestResult = $this->sendRequest('addressbooks/' . $id, 'PUT', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Remove address book
     *
     * @param $id
     *
     * @return stdClass
     */
    public function removeAddressBook($id)
    {
        if (empty($id)) {
            return $this->handleError('Empty book id');
        }

        $requestResult = $this->sendRequest('addressbooks/' . $id, 'DELETE');

        return $this->handleResult($requestResult);
    }

    /**
     * Get list of address books
     *
     * @param null $limit
     * @param null $offset
     *
     * @return mixed
     */
    public function listAddressBooks($limit = null, $offset = null)
    {
        $data = array();
        if (null !== $limit) {
            $data['limit'] = $limit;
        }
        if (null !== $offset) {
            $data['offset'] = $offset;
        }

        $requestResult = $this->sendRequest('addressbooks', 'GET', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Get information about book
     *
     * @param $id
     *
     * @return stdClass
     */
    public function getBookInfo($id)
    {
        if (empty($id)) {
            return $this->handleError('Empty book id');
        }

        $requestResult = $this->sendRequest('addressbooks/' . $id);

        return $this->handleResult($requestResult);
    }

    /**
     * Get variables from book
     *
     * @param $id
     *   Address book id.
     *
     * @return stdClass
     */
    public function getBookVariables($id)
    {
        if (empty($id)) {
            return $this->handleError('Empty book id');
        }

        $requestResult = $this->sendRequest('addressbooks/' . $id . '/variables');

        return $this->handleResult($requestResult);
    }

    /**
     * Change varible by user email
     *
     * @param int $bookID
     * @param string $email User email
     * @param array $vars User vars in [key=>value] format
     * @return stdClass
     */
    public function updateEmailVariables(int $bookID, string $email, array $vars)
    {
        if (empty($bookID)) {
            return $this->handleError('Empty book id');
        }

        $data = ['email' => $email, 'variables' => []];
        foreach ($vars as $name => $val) {
            $data['variables'][] = ['name' => $name, 'value' => $val];
        }

        $requestResult = $this->sendRequest('/addressbooks/' . $bookID . '/emails/variable', 'POST', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * List email addresses from book
     *
     * @param $id
     * @param $limit
     * @param $offset
     *
     * @return stdClass
     */
    public function getEmailsFromBook($id, $limit = null, $offset = null)
    {
        if (empty($id)) {
            return $this->handleError('Empty book id');
        }

        $data = array();
        if (null !== $limit) {
            $data['limit'] = $limit;
        }
        if (null !== $offset) {
            $data['offset'] = $offset;
        }

        $requestResult = $this->sendRequest('addressbooks/' . $id . '/emails', 'GET', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Add new emails to address book
     *
     * @param $bookID
     * @param $emails
     * @param $additionalParams
     *
     * @return stdClass
     */
    public function addEmails($bookID, $emails, $additionalParams = [], $record)
    {
        if (empty($bookID) || empty($emails)) {
            return $this->handleError('Empty book id or emails');
        }

        $data = array(
            'emails' => json_encode($emails),
        );

        if ($additionalParams) {
            $data = array_merge($data, $additionalParams);
        }

        $requestResult = $this->sendRequest('addressbooks/' . $bookID . '/emails', 'POST', $data, true, $record);

        return $this->handleResult($requestResult);
    }

    /**
     * Remove email addresses from book
     *
     * @param $bookID
     * @param $emails
     *
     * @return stdClass
     */
    public function removeEmails($bookID, $emails)
    {
        if (empty($bookID) || empty($emails)) {
            return $this->handleError('Empty book id or emails');
        }

        $data = array(
            'emails' => serialize($emails),
        );

        $requestResult = $this->sendRequest('addressbooks/' . $bookID . '/emails', 'DELETE', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Get information about email address from book
     *
     * @param $bookID
     * @param $email
     *
     * @return stdClass
     */
    public function getEmailInfo($bookID, $email)
    {
        if (empty($bookID) || empty($email)) {
            return $this->handleError('Empty book id or email');
        }

        $requestResult = $this->sendRequest('addressbooks/' . $bookID . '/emails/' . $email);

        return $this->handleResult($requestResult);
    }

    /**
     * Get cost of campaign based on address book
     *
     * @param $bookID
     *
     * @return stdClass
     */
    public function campaignCost($bookID)
    {
        if (empty($bookID)) {
            return $this->handleError('Empty book id');
        }

        $requestResult = $this->sendRequest('addressbooks/' . $bookID . '/cost');

        return $this->handleResult($requestResult);
    }

    /**
     * Get list of campaigns
     *
     * @param null $limit
     * @param null $offset
     *
     * @return mixed
     */
    public function listCampaigns($limit = null, $offset = null)
    {
        $data = array();
        if (!empty($limit)) {
            $data['limit'] = $limit;
        }
        if (!empty($offset)) {
            $data['offset'] = $offset;
        }
        $requestResult = $this->sendRequest('campaigns', 'GET', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Get information about campaign
     *
     * @param $id
     *
     * @return stdClass
     */
    public function getCampaignInfo($id)
    {
        if (empty($id)) {
            return $this->handleError('Empty campaign id');
        }

        $requestResult = $this->sendRequest('campaigns/' . $id);

        return $this->handleResult($requestResult);
    }

    /**
     * Get campaign statistic by countries
     *
     * @param $id
     *
     * @return stdClass
     */
    public function campaignStatByCountries($id)
    {
        if (empty($id)) {
            return $this->handleError('Empty campaign id');
        }

        $requestResult = $this->sendRequest('campaigns/' . $id . '/countries');

        return $this->handleResult($requestResult);
    }

    /**
     * Get campaign statistic by referrals
     *
     * @param $id
     *
     * @return stdClass
     */
    public function campaignStatByReferrals($id)
    {
        if (empty($id)) {
            return $this->handleError('Empty campaign id');
        }

        $requestResult = $this->sendRequest('campaigns/' . $id . '/referrals');

        return $this->handleResult($requestResult);
    }

    /**
     * Create new campaign
     *
     * @param $senderName
     * @param $senderEmail
     * @param $subject
     * @param $bodyOrTemplateId
     * @param $bookId
     * @param string $name
     * @param string $attachments
     * @param string $type
     * @param bool $useTemplateId
     * @param string $sendDate
     * @param int|null $segmentId
     * @return mixed
     */
    public function createCampaign(
        $senderName,
        $senderEmail,
        $subject,
        $bodyOrTemplateId,
        $bookId,
        $name = '',
        $attachments = '',
        $type = '',
        $useTemplateId = false,
        $sendDate = '',
        $segmentId = null
    )
    {
        if (empty($senderName) || empty($senderEmail) || empty($subject) || empty($bodyOrTemplateId) || empty($bookId)) {
            return $this->handleError('Not all data.');
        }

        if (!empty($attachments)) {
            $attachments = serialize($attachments);
        }

        if ($useTemplateId) {
            $paramName = 'template_id';
            $paramValue = $bodyOrTemplateId;
        } else {
            $paramName = 'body';
            $paramValue = base64_encode($bodyOrTemplateId);
        }

        $data = array(
            'sender_name' => $senderName,
            'sender_email' => $senderEmail,
            'subject' => $subject,
            $paramName => $paramValue,
            'list_id' => $bookId,
            'name' => $name,
            'attachments' => $attachments,
            'type' => $type,
        );

        if (!empty($sendDate)) {
            $data['send_date'] = $sendDate;
        }

        if (!empty($segmentId)) {
            $data['segment_id'] = $segmentId;
        }

        $requestResult = $this->sendRequest('campaigns', 'POST', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Cancel campaign
     *
     * @param $id
     *
     * @return stdClass
     */
    public function cancelCampaign($id)
    {
        if (empty($id)) {
            return $this->handleError('Empty campaign id');
        }

        $requestResult = $this->sendRequest('campaigns/' . $id, 'DELETE');

        return $this->handleResult($requestResult);
    }

    /**
     * List all senders
     *
     * @return mixed
     */
    public function listSenders()
    {
        $requestResult = $this->sendRequest('senders');

        return $this->handleResult($requestResult);
    }

    /**
     * List SMS senders
     *
     * @return mixed
     */
    public function listSMSSenders()
    {
        $requestResult = $this->sendRequest('sms/senders');

        return $this->handleResult($requestResult);
    }

    /**
     * Add new sender
     *
     * @param $senderName
     * @param $senderEmail
     *
     * @return stdClass
     */
    public function addSender($senderName, $senderEmail)
    {
        if (empty($senderName) || empty($senderEmail)) {
            return $this->handleError('Empty sender name or email');
        }

        $data = array(
            'email' => $senderEmail,
            'name' => $senderName,
        );

        $requestResult = $this->sendRequest('senders', 'POST', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Remove sender
     *
     * @param $email
     *
     * @return stdClass
     */
    public function removeSender($email)
    {
        if (empty($email)) {
            return $this->handleError('Empty email');
        }

        $data = array(
            'email' => $email,
        );

        $requestResult = $this->sendRequest('senders', 'DELETE', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Activate sender using code
     *
     * @param $email
     * @param $code
     *
     * @return stdClass
     */
    public function activateSender($email, $code)
    {
        if (empty($email) || empty($code)) {
            return $this->handleError('Empty email or activation code');
        }

        $data = array(
            'code' => $code,
        );

        $requestResult = $this->sendRequest('senders/' . $email . '/code', 'POST', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Request mail with activation code
     *
     * @param $email
     *
     * @return stdClass
     */
    public function getSenderActivationMail($email)
    {
        if (empty($email)) {
            return $this->handleError('Empty email');
        }

        $requestResult = $this->sendRequest('senders/' . $email . '/code');

        return $this->handleResult($requestResult);
    }

    /**
     * Get global information about email
     *
     * @param $email
     *
     * @return stdClass
     */
    public function getEmailGlobalInfo($email)
    {
        if (empty($email)) {
            return $this->handleError('Empty email');
        }

        $requestResult = $this->sendRequest('emails/' . $email);

        return $this->handleResult($requestResult);
    }

    /**
     * Get global information about list of emails
     *
     * @param array $emails Emails list
     * @return stdClass
     */
    public function getEmailsGlobalInfo($emails)
    {
        if (empty($emails)) {
            return $this->handleError('Empty emails list');
        }

        $requestResult = $this->sendRequest('emails', 'POST', $emails);

        return $this->handleResult($requestResult);
    }

    /**
     * Remove email from all books
     *
     * @param $email
     *
     * @return stdClass
     */
    public function removeEmailFromAllBooks($email)
    {
        if (empty($email)) {
            return $this->handleError('Empty email');
        }

        $requestResult = $this->sendRequest('emails/' . $email, 'DELETE');

        return $this->handleResult($requestResult);
    }

    /**
     * Get email statistic by all campaigns
     *
     * @param $email
     *
     * @return stdClass
     */
    public function emailStatByCampaigns($email)
    {
        if (empty($email)) {
            return $this->handleError('Empty email');
        }

        $requestResult = $this->sendRequest('emails/' . $email . '/campaigns');

        return $this->handleResult($requestResult);
    }

    /**
     * Get all emails from blacklist
     *
     * @return mixed
     */
    public function getBlackList()
    {
        $requestResult = $this->sendRequest('blacklist');

        return $this->handleResult($requestResult);
    }

    /**
     * Add email to blacklist
     *
     * @param        $emails - string with emails, separator - ,
     * @param string $comment
     *
     * @return stdClass
     */
    public function addToBlackList($emails, $comment = '')
    {
        if (empty($emails)) {
            return $this->handleError('Empty email');
        }

        $data = array(
            'emails' => base64_encode($emails),
            'comment' => $comment,
        );

        $requestResult = $this->sendRequest('blacklist', 'POST', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Remove emails from blacklist
     *
     * @param $emails - string with emails, separator - ,
     *
     * @return stdClass
     */
    public function removeFromBlackList($emails)
    {
        if (empty($emails)) {
            return $this->handleError('Empty email');
        }

        $data = array(
            'emails' => base64_encode($emails),
        );

        $requestResult = $this->sendRequest('blacklist', 'DELETE', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Get balance
     *
     * @param string $currency
     *
     * @return mixed
     */
    public function getBalance($currency = '')
    {
        $currency = strtoupper($currency);
        $url = 'balance';
        if (!empty($currency)) {
            $url .= '/' . strtoupper($currency);
        }

        $requestResult = $this->sendRequest($url);

        return $this->handleResult($requestResult);
    }

    /**
     * SMTP: get list of emails
     *
     * @param int $limit
     * @param int $offset
     * @param string $fromDate
     * @param string $toDate
     * @param string $sender
     * @param string $recipient
     * @param string $country
     *
     * @return mixed
     */
    public function smtpListEmails($limit = 0, $offset = 0, $fromDate = '', $toDate = '', $sender = '', $recipient = '', $country = 'off')
    {
        $data = array(
            'limit' => $limit,
            'offset' => $offset,
            'from' => $fromDate,
            'to' => $toDate,
            'sender' => $sender,
            'recipient' => $recipient,
            'country' => $country,
        );

        $requestResult = $this->sendRequest('/smtp/emails', 'GET', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Get information about email by id
     *
     * @param $id
     *
     * @return stdClass
     */
    public function smtpGetEmailInfoById($id)
    {
        if (empty($id)) {
            return $this->handleError('Empty id');
        }

        $requestResult = $this->sendRequest('/smtp/emails/' . $id);

        return $this->handleResult($requestResult);
    }

    /**
     * SMTP: get list of unsubscribed emails
     *
     * @param null $limit
     * @param null $offset
     *
     * @return mixed
     */
    public function smtpListUnsubscribed($limit = null, $offset = null)
    {
        $data = array();
        if (null !== $limit) {
            $data['limit'] = $limit;
        }
        if (null !== $offset) {
            $data['offset'] = $offset;
        }

        $requestResult = $this->sendRequest('smtp/unsubscribe', 'GET', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * SMTP: add emails to unsubscribe list
     *
     * @param $emails
     *
     * @return stdClass
     */
    public function smtpUnsubscribeEmails($emails)
    {
        if (empty($emails)) {
            return $this->handleError('Empty emails');
        }

        $data = array(
            'emails' => serialize($emails),
        );

        $requestResult = $this->sendRequest('/smtp/unsubscribe', 'POST', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * SMTP: remove emails from unsubscribe list
     *
     * @param $emails
     *
     * @return stdClass
     */
    public function smtpRemoveFromUnsubscribe($emails)
    {
        if (empty($emails)) {
            return $this->handleError('Empty emails');
        }

        $data = array(
            'emails' => serialize($emails),
        );

        $requestResult = $this->sendRequest('/smtp/unsubscribe', 'DELETE', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Get list of IP
     *
     * @return mixed
     */
    public function smtpListIP()
    {
        $requestResult = $this->sendRequest('smtp/ips');

        return $this->handleResult($requestResult);
    }

    /**
     * SMTP: get list of allowed domains
     *
     * @return mixed
     */
    public function smtpListAllowedDomains()
    {
        $requestResult = $this->sendRequest('smtp/domains');

        return $this->handleResult($requestResult);
    }

    /**
     * SMTP: add new domain
     *
     * @param $email
     *
     * @return stdClass
     */
    public function smtpAddDomain($email)
    {
        if (empty($email)) {
            return $this->handleError('Empty email');
        }

        $data = array(
            'email' => $email,
        );

        $requestResult = $this->sendRequest('smtp/domains', 'POST', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * SMTP: verify domain
     *
     * @param $email
     *
     * @return stdClass
     */
    public function smtpVerifyDomain($email)
    {
        if (empty($email)) {
            return $this->handleError('Empty email');
        }

        $requestResult = $this->sendRequest('smtp/domains/' . $email);

        return $this->handleResult($requestResult);
    }

    /**
     * SMTP: send mail
     *
     * @param $email
     *
     * @return stdClass
     */
    public function smtpSendMail($email)
    {
        if (empty($email)) {
            return $this->handleError('Empty email data');
        }

        if (isset($email['html'])) {
            $email['html'] = base64_encode($email['html']);
        }

        $data = array(
            'email' => serialize($email),
        );

        $requestResult = $this->sendRequest('smtp/emails', 'POST', $data);

        if ($requestResult->http_code !== 200 && !$this->retry) {
            $this->retry = true;
            sleep(2);

            return $this->smtpSendMail($email);
        }

        return $this->handleResult($requestResult);
    }

    /**
     * Get list of push campaigns
     *
     * @param null $limit
     * @param null $offset
     *
     * @return mixed
     */
    public function pushListCampaigns($limit = null, $offset = null)
    {
        $data = array();
        if (null !== $limit) {
            $data['limit'] = $limit;
        }
        if (null !== $offset) {
            $data['offset'] = $offset;
        }

        $requestResult = $this->sendRequest('push/tasks', 'GET', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Get list of websites
     *
     * @param null $limit
     * @param null $offset
     *
     * @return mixed
     */
    public function pushListWebsites($limit = null, $offset = null)
    {
        $data = array();
        if (null !== $limit) {
            $data['limit'] = $limit;
        }
        if (null !== $offset) {
            $data['offset'] = $offset;
        }

        $requestResult = $this->sendRequest('push/websites', 'GET', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Get amount of websites
     *
     * @return mixed
     */
    public function pushCountWebsites()
    {
        $requestResult = $this->sendRequest('push/websites/total');

        return $this->handleResult($requestResult);
    }

    /**
     * Get list of all variables for website
     *
     * @param $websiteId
     *
     * @return mixed
     */
    public function pushListWebsiteVariables($websiteId)
    {
        $requestResult = $this->sendRequest('push/websites/' . $websiteId . '/variables');

        return $this->handleResult($requestResult);
    }

    /**
     * Get list of subscriptions for the website
     *
     * @param      $websiteID
     *
     * @param null $limit
     * @param null $offset
     *
     * @return mixed
     */
    public function pushListWebsiteSubscriptions($websiteID, $limit = null, $offset = null)
    {
        $data = array();
        if (null !== $limit) {
            $data['limit'] = $limit;
        }
        if (null !== $offset) {
            $data['offset'] = $offset;
        }

        $requestResult = $this->sendRequest('push/websites/' . $websiteID . '/subscriptions', 'GET', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Get amount of subscriptions for the site
     *
     * @param $websiteID
     *
     * @return mixed
     */
    public function pushCountWebsiteSubscriptions($websiteID)
    {
        $requestResult = $this->sendRequest('push/websites/' . $websiteID . '/subscriptions/total');

        return $this->handleResult($requestResult);
    }

    /**
     * Set state for subscription
     *
     * @param $subscriptionID
     * @param $stateValue
     *
     * @return mixed
     */
    public function pushSetSubscriptionState($subscriptionID, $stateValue)
    {
        $data = array(
            'id' => $subscriptionID,
            'state' => $stateValue,
        );

        $requestResult = $this->sendRequest('push/subscriptions/state', 'POST', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Get common website info
     *
     * @param $websiteId
     *
     * @return mixed
     */
    public function pushGetWebsiteInfo($websiteId)
    {
        $requestResult = $this->sendRequest('push/websites/info/' . $websiteId);

        return $this->handleResult($requestResult);
    }


    /**
     * Create new push campaign
     *
     * @param       $taskInfo
     * @param array $additionalParams
     *
     * @return stdClass
     */
    public function createPushTask($taskInfo, array $additionalParams = array())
    {
        $data = $taskInfo;
        if (!isset($data['ttl'])) {
            $data['ttl'] = 0;
        }
        if (empty($data['title']) || empty($data['website_id']) || empty($data['body'])) {
            return $this->handleError('Not all data');
        }
        if ($additionalParams) {
            foreach ($additionalParams as $key => $val) {
                $data[$key] = $val;
            }
        }

        $requestResult = $this->sendRequest('/push/tasks', 'POST', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Get integration code for Push Notifications.
     *
     * @param $websiteID
     *
     * @return stdClass
     */
    public function getPushIntegrationCode($websiteID)
    {
        if (empty($websiteID)) {
            return $this->handleError('Empty website id');
        }

        $requestResult = $this->sendRequest('/push/websites/' . $websiteID . '/code');

        return $this->handleResult($requestResult);
    }

    /**
     * Get stats for push campaign
     *
     * @param $campaignID
     *
     * @return stdClass
     */
    public function getPushCampaignStat($campaignID)
    {
        $requestResult = $this->sendRequest('push/tasks/' . $campaignID);

        return $this->handleResult($requestResult);
    }

    /**
     * @Author Maksym Dzhym m.jim@sendpulse.com
     * @param $eventName
     * @param array $variables
     * @return stdClass
     */
    public function startEventAutomation360($eventName, array $variables)
    {
        if (!$eventName) {
            return $this->handleError('Event name is empty');
        }
        if (!array_key_exists('email', $variables) && !array_key_exists('phone', $variables)) {
            return $this->handleError('Email and phone is empty');
        }

        $requestResult = $this->sendRequest('events/name/' . $eventName, 'POST', $variables);

        return $this->handleResult($requestResult);
    }

    /**
     * Add phones to addressbook
     *
     * @param $bookID
     * @param array $phones
     * @return stdClass
     */
    public function addPhones($bookID, array $phones)
    {
        if (empty($bookID)) {
            return $this->handleError('Empty book id');
        }

        $data = [
            'addressBookId' => $bookID,
            'phones' => json_encode($phones)
        ];

        $requestResult = $this->sendRequest('/sms/numbers', 'POST', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Add phones with variables to addressbook
     *
     * @param $bookID
     * @param array $phones
     * @return stdClass
     */
    public function addPhonesWithVariables($bookID, array $phonesWithVariables)
    {
        if (empty($bookID)) {
            return $this->handleError('Empty book id');
        }

        $data = [
            'addressBookId' => $bookID,
            'phones' => json_encode($phonesWithVariables)
        ];

        $requestResult = $this->sendRequest('/sms/numbers/variables', 'POST', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Update variables for phones
     *
     * @param $bookID
     * @param array $phones
     * @param array $variables
     * @return stdClass
     */
    public function updatePhoneVaribales($bookID, array $phones, array $variables)
    {
        if (empty($bookID)) {
            return $this->handleError('Empty book id');
        }

        $data = [
            'addressBookId' => $bookID,
            'phones' => json_encode($phones),
            'variables' => json_encode($variables)
        ];

        $requestResult = $this->sendRequest('/sms/numbers', 'PUT', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Delete phones from book
     *
     * @param $bookID
     * @param array $phones
     * @return stdClass
     */
    public function deletePhones($bookID, array $phones)
    {
        if (empty($bookID)) {
            return $this->handleError('Empty book id');
        }

        $data = [
            'addressBookId' => $bookID,
            'phones' => json_encode($phones)
        ];

        $requestResult = $this->sendRequest('/sms/numbers', 'DELETE', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * get information about phone number
     *
     * @param $bookID
     * @param $phoneNumber
     * @return stdClass
     */
    public function getPhoneInfo($bookID, $phoneNumber)
    {
        if (empty($bookID)) {
            return $this->handleError('Empty book id');
        }

        $requestResult = $this->sendRequest('/sms/numbers/info/' . $bookID . '/' . $phoneNumber);

        return $this->handleResult($requestResult);
    }

    /**
     * Add phones to blacklist
     *
     * @param $bookID
     * @param array $phones
     * @return stdClass
     */
    public function addPhonesToBlacklist(array $phones)
    {
        $data = [
            'phones' => json_encode($phones)
        ];

        $requestResult = $this->sendRequest('/sms/black_list', 'POST', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Delete phones from blacklist
     *
     * @param array $phones
     * @return stdClass
     */
    public function removePhonesFromBlacklist(array $phones)
    {
        $data = [
            'phones' => json_encode($phones)
        ];

        $requestResult = $this->sendRequest('/sms/black_list', 'DELETE', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Get list of phones from blacklist
     *
     * @return stdClass
     */
    public function getPhonesFromBlacklist()
    {
        $requestResult = $this->sendRequest('/sms/black_list');

        return $this->handleResult($requestResult);
    }

    /**
     * Create sms campaign based on phones in book
     *
     * @param $bookID
     * @param array $params
     * @param array $additionalParams
     * @return stdClass
     */
    public function sendSmsByBook($bookID, array $params, array $additionalParams = [])
    {
        if (empty($bookID)) {
            return $this->handleError('Empty book id');
        }

        $data = [
            'addressBookId' => $bookID
        ];

        $data = array_merge($data, $params);

        if ($additionalParams) {
            $data = array_merge($data, $additionalParams);
        }

        $requestResult = $this->sendRequest('/sms/campaigns', 'POST', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Create sms campaign based on list
     *
     * @param $phones
     * @param array $params
     * @param array $additionalParams
     * @return stdClass
     */
    public function sendSmsByList(array $phones, array $params, array $additionalParams)
    {
        $data = [
            'phones' => json_encode($phones)
        ];

        $data = array_merge($data, $params);

        if ($additionalParams) {
            $data = array_merge($data, $additionalParams);
        }

        $requestResult = $this->sendRequest('/sms/send', 'POST', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * List sms campaigns
     *
     * @param $params
     * @return stdClass
     */
    public function listSmsCampaigns(array $params = null)
    {
        $requestResult = $this->sendRequest('/sms/campaigns/list', 'GET', $params);

        return $this->handleResult($requestResult);
    }

    /**
     * Get info about sms campaign
     *
     * @param $campaignID
     * @return stdClass
     */
    public function getSmsCampaignInfo($campaignID)
    {
        $requestResult = $this->sendRequest('/sms/campaigns/info/' . $campaignID);

        return $this->handleResult($requestResult);
    }

    /**
     * Cancel SMS campaign
     *
     * @param $campaignID
     * @return stdClass
     */
    public function cancelSmsCampaign($campaignID)
    {
        $requestResult = $this->sendRequest('/sms/campaigns/cancel/' . $campaignID, 'PUT');

        return $this->handleResult($requestResult);
    }

    /**
     * Get SMS campaign cost based on book or simple list
     *
     * @param array $params
     * @param array|null $additionalParams
     * @return stdClass
     */
    public function getSmsCampaignCost(array $params, array $additionalParams = null)
    {
        if (!isset($params['addressBookId']) && !isset($params['phones'])) {
            return $this->handleError('You mast pass phones list or addressbook ID');
        }

        if ($additionalParams) {
            $params = array_merge($params, $additionalParams);
        }

        $requestResult = $this->sendRequest('/sms/campaigns/cost', 'GET', $params);

        return $this->handleResult($requestResult);
    }

    /**
     * Delete SMS campaign
     *
     * @param $campaignID
     * @return stdClass
     */
    public function deleteSmsCampaign($campaignID)
    {
        $requestResult = $this->sendRequest('/sms/campaigns', 'DELETE', ['id' => $campaignID]);

        return $this->handleResult($requestResult);
    }
}

interface ApiInterface
{

    /**
     * Create new address book
     *
     * @param $bookName
     */
    public function createAddressBook($bookName);

    /**
     * Edit address book name
     *
     * @param $id
     * @param $newName
     */
    public function editAddressBook($id, $newName);

    /**
     * Remove address book
     *
     * @param $id
     */
    public function removeAddressBook($id);

    /**
     * Get list of address books
     *
     * @param $limit
     * @param $offset
     */
    public function listAddressBooks($limit = null, $offset = null);

    /**
     * Get book info
     *
     * @param $id
     */
    public function getBookInfo($id);

    /**
     * Get book variables.
     *
     * @param $id
     *   Address book id.
     */
    public function getBookVariables($id);

    /**
     * Get list pf emails from book
     *
     * @param $id
     */
    public function getEmailsFromBook($id);

    /**
     * Add new emails to book
     *
     * @param $bookID
     * @param $emails
     */
    public function addEmails($bookID, $emails, $extra, $record);

    /**
     * Remove emails from book
     *
     * @param $bookID
     * @param $emails
     */
    public function removeEmails($bookID, $emails);

    /**
     * Get information about email from book
     *
     * @param $bookID
     * @param $email
     */
    public function getEmailInfo($bookID, $email);

    /**
     * Calculate cost of the campaign based on address book
     *
     * @param $bookID
     */
    public function campaignCost($bookID);

    /**
     * Get list of campaigns
     *
     * @param $limit
     * @param $offset
     */
    public function listCampaigns($limit = null, $offset = null);

    /**
     * Get information about campaign
     *
     * @param $id
     */
    public function getCampaignInfo($id);

    /**
     * Get campaign statistic by countries
     *
     * @param $id
     */
    public function campaignStatByCountries($id);

    /**
     * Get campaign statistic by referrals
     *
     * @param $id
     */
    public function campaignStatByReferrals($id);

    /**
     * Create new campaign
     *
     * @param      $senderName
     * @param      $senderEmail
     * @param      $subject
     * @param      $body
     * @param      $bookId
     * @param null $name
     * @param null $attachments
     * @param null $type
     */
    public function createCampaign(
        $senderName,
        $senderEmail,
        $subject,
        $body,
        $bookId,
        $name = null,
        $attachments = null,
        $type = null
    );

    /**
     * Cancel campaign
     *
     * @param $id
     */
    public function cancelCampaign($id);

    /**
     * Get list of allowed senders
     */
    public function listSenders();

    /**
     * Add new sender
     *
     * @param $senderName
     * @param $senderEmail
     */
    public function addSender($senderName, $senderEmail);

    /**
     * Remove sender
     *
     * @param $email
     */
    public function removeSender($email);

    /**
     * Activate sender using code from mail
     *
     * @param $email
     * @param $code
     */
    public function activateSender($email, $code);

    /**
     * Send mail with activation code on sender email
     *
     * @param $email
     */
    public function getSenderActivationMail($email);

    /**
     * Get global information about email
     *
     * @param $email
     */
    public function getEmailGlobalInfo($email);

    /**
     * Remove email address from all books
     *
     * @param $email
     */
    public function removeEmailFromAllBooks($email);

    /**
     * Get statistic for email by all campaigns
     *
     * @param $email
     */
    public function emailStatByCampaigns($email);

    /**
     * Show emails from blacklist
     */
    public function getBlackList();

    /**
     * Add email address to blacklist
     *
     * @param      $emails
     * @param null $comment
     */
    public function addToBlackList($emails, $comment = null);

    /**
     * Remove email address from blacklist
     *
     * @param $emails
     */
    public function removeFromBlackList($emails);

    /**
     * Return user balance
     *
     * @param string $currency
     */
    public function getBalance($currency = '');

    /**
     * Get list of emails that was sent by SMTP
     *
     * @param int $limit
     * @param int $offset
     * @param string $fromDate
     * @param string $toDate
     * @param string $sender
     * @param string $recipient
     * @param string $country
     */
    public function smtpListEmails(
        $limit = 0,
        $offset = 0,
        $fromDate = '',
        $toDate = '',
        $sender = '',
        $recipient = '',
        $country = 'off'
    );

    /**
     * Get information about email by his id
     *
     * @param $id
     */
    public function smtpGetEmailInfoById($id);

    /**
     * SMTP: get list of unsubscribed emails
     *
     * @param null $limit
     * @param null $offset
     */
    public function smtpListUnsubscribed($limit = null, $offset = null);

    /**
     * Unsubscribe emails using SMTP
     *
     * @param $emails
     */
    public function smtpUnsubscribeEmails($emails);

    /**
     * Remove emails from unsubscribe list using SMTP
     *
     * @param $emails
     */
    public function smtpRemoveFromUnsubscribe($emails);

    /**
     * Get list of allowed IPs using SMTP
     */
    public function smtpListIP();

    /**
     * Get list of allowed domains using SMTP
     */
    public function smtpListAllowedDomains();

    /**
     * Add domain using SMTP
     *
     * @param $email
     */
    public function smtpAddDomain($email);

    /**
     * Send confirm mail to verify new domain
     *
     * @param $email
     */
    public function smtpVerifyDomain($email);

    /**
     * Send mail using SMTP
     *
     * @param $email
     */
    public function smtpSendMail($email);

    /**
     * Get list of all push campaigns
     *
     * @param null $limit
     * @param null $offset
     */
    public function pushListCampaigns($limit = null, $offset = null);

    /**
     * Get list of websites
     *
     * @param null $limit
     * @param null $offset
     */
    public function pushListWebsites($limit = null, $offset = null);

    /**
     * Get amount of websites
     */
    public function pushCountWebsites();

    /**
     * Get list of all variables for the website
     *
     * @param $websiteID
     */
    public function pushListWebsiteVariables($websiteID);

    /**
     * Get list of all subscriptions for the website
     *
     * @param      $websiteID
     * @param null $limit
     * @param null $offset
     *
     * @return
     */
    public function pushListWebsiteSubscriptions($websiteID, $limit = null, $offset = null);

    /**
     * Get amount of subscriptions for the site
     *
     * @param $websiteID
     */
    public function pushCountWebsiteSubscriptions($websiteID);

    /**
     * Set state for subscription
     *
     * @param $subscriptionID
     * @param $stateValue
     */
    public function pushSetSubscriptionState($subscriptionID, $stateValue);

    /**
     * Create new push campaign
     *
     * @param       $taskInfo
     * @param array $additionalParams
     */
    public function createPushTask($taskInfo, array $additionalParams = array());

    /**
     * Get integration code for Push Notifications.
     *
     * @param $websiteID
     */
    public function getPushIntegrationCode($websiteID);

    /**
     * @Author Maksym Dzhym m.jim@sendpulse.com
     * @param $eventName
     * @param array $variables
     * @return \stdClass
     */
    public function startEventAutomation360($eventName, array $variables);

    /**
     * Add phones to addressbook
     *
     * @param $bookID
     * @param array $phones
     * @return mixed
     */
    public function addPhones($bookID, array $phones);

    /**
     * Add phones with variables to addressbook
     *
     * @param $bookID
     * @param array $phones
     * @return mixed
     */
    public function addPhonesWithVariables($bookID, array $phones);


    /**
     * Update phone variables
     *
     * @param $bookID
     * @param $phones
     * @param $variables
     * @return mixed
     */
    public function updatePhoneVaribales($bookID, array $phones, array $variables);

    /**
     * Delete phones from book
     *
     * @param $bookID
     * @param array $phones
     * @return mixed
     */
    public function deletePhones($bookID, array $phones);

    /**
     * Get information about phone
     *
     * @param $bookID
     * @param $phoneNumber
     * @return mixed
     */
    public function getPhoneInfo($bookID, $phoneNumber);

    /**
     * Add phones to blacklist
     *
     * @param $bookID
     * @param array $phones
     * @return mixed
     */
    public function addPhonesToBlacklist(array $phones);

    /**
     * Remove phones from blacklist
     *
     * @param array $phones
     * @return mixed
     */
    public function removePhonesFromBlacklist(array $phones);

    /**
     * Get list of phones from blacklist
     *
     * @return mixed
     */
    public function getPhonesFromBlacklist();

    /**
     * Create sms campaign based on phones in book
     *
     * @param $bookId
     * @param array $params
     * @param array $additionalParams
     * @return mixed
     */
    public function sendSmsByBook($bookId, array $params, array $additionalParams);

    /**
     * Create sms campaign based on list
     *
     * @param $phones
     * @param array $params
     * @param array $additionalParams
     * @return mixed
     */
    public function sendSmsByList(array $phones, array $params, array $additionalParams);

    /**
     * List sms campaigns
     *
     * @param $params
     * @return mixed
     */
    public function listSmsCampaigns(array $params);

    /**
     * get info about SMS campaign
     *
     * @param $campaignID
     * @return mixed
     */
    public function getSmsCampaignInfo($campaignID);

    /**
     * Cancel SMS campaign
     *
     * @param $campaignID
     * @return mixed
     */
    public function cancelSmsCampaign($campaignID);

    /**
     * Get SMS campaign cost based on book or simple list
     *
     * @param $params
     * @param $additionalParams
     * @return mixed
     */
    public function getSmsCampaignCost(array $params, array $additionalParams);

    /**
     * Delete SMS campaign
     *
     * @param $campaignID
     * @return mixed
     */
    public function deleteSmsCampaign($campaignID);

}

interface TokenStorageInterface
{
    /**
     * @param $key string
     * @param $token
     *
     * @return mixed
     */
    public function set($key, $token);

    /**
     * @param $key string
     *
     * @return mixed
     */
    public function get();
}

class FileStorage implements TokenStorageInterface
{

    /**
     * @var string
     */
    protected $storageFolder = '';

    /**
     * File constructor.
     *
     * @param string $storageFolder
     */
    public function __construct($storageFolder = '')
    {
        $this->storageFolder = $storageFolder;
    }

    /**
     * @param $key string
     * @param $token
     *
     * @return void
     */
    public function set($key, $token)
    {

        update_option( 'adfoin_sendpulse_api_token', $token );
        return true;
    }

    /**
     * @param $key string
     *
     * @return mixed
     */
    public function get()
    {
        $api_token = get_option( 'adfoin_sendpulse_api_token' );
        return $api_token;
    }
    
    /**
     * @param  $key string
     * 
     * @return bool
     */
    public function delete($key) 
    {
        $filePath = $this->storageFolder . $key;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }

        return false;
    }
}

add_action( 'wp_ajax_adfoin_get_sendpulse_list', 'adfoin_get_sendpulse_list', 10 );

function adfoin_get_sendpulse_list() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $id     = get_option( 'adfoin_sendpulse_id' ) ? get_option( 'adfoin_sendpulse_id' ) : "";
    $secret = get_option( 'adfoin_sendpulse_secret' ) ? get_option( 'adfoin_sendpulse_secret' ) : "";

    if( !$id || !$secret ) {
        return;
    }

    $SPApiClient  = new AFISendPulseApiClient( $id, $secret, new FileStorage() );
    $addressbooks = $SPApiClient->listAddressBooks();
    $lists        = wp_list_pluck( $addressbooks, 'name', 'id' );

    wp_send_json_success( $lists );
}

function adfoin_sendpulse_actions( $actions ) {

    $actions['sendpulse'] = array(
        'title' => __( 'SendPulse', 'advanced-form-integration' ),
        'tasks' => array(
            'subscribe'   => __( 'Subscribe To Email List', 'advanced-form-integration' )
        )
    );

    return $actions;
}
add_filter( 'adfoin_action_providers', 'adfoin_sendpulse_actions', 10, 1 );

function adfoin_sendpulse_settings_tab( $providers ) {
    $providers['sendpulse'] = __( 'SendPulse', 'advanced-form-integration' );

    return $providers;
}
add_filter( 'adfoin_settings_tabs', 'adfoin_sendpulse_settings_tab', 10, 1 );

function adfoin_sendpulse_settings_view( $current_tab ) {
    if( $current_tab != 'sendpulse' ) {
        return;
    }

    $nonce  = wp_create_nonce( "adfoin_sendpulse_settings" );
    $id     = get_option( 'adfoin_sendpulse_id' ) ? get_option( 'adfoin_sendpulse_id' ) : "";
    $secret = get_option( 'adfoin_sendpulse_secret' ) ? get_option( 'adfoin_sendpulse_secret' ) : "";
    ?>

    <form name="sendpulse_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_sendpulse_api_key">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'ID', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_sendpulse_id"
                           value="<?php echo esc_attr( $id ); ?>" placeholder="<?php _e( 'Please enter ID', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                    <p>
                        Go to Account Settings > API
                    </p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php _e( 'Secret', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_sendpulse_secret"
                           value="<?php echo $secret; ?>" placeholder="<?php _e( 'Please enter Secret', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}
add_action( 'adfoin_settings_view', 'adfoin_sendpulse_settings_view', 10, 1 );

function adfoin_save_sendpulse_api_key() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_sendpulse_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $id     = sanitize_text_field( $_POST['adfoin_sendpulse_id'] );
    $secret = sanitize_text_field( $_POST['adfoin_sendpulse_secret'] );

    // Save tokens
    update_option( "adfoin_sendpulse_id", $id );
    update_option( "adfoin_sendpulse_secret", $secret );

    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=sendpulse" );
}
add_action( 'admin_post_adfoin_save_sendpulse_api_key', 'adfoin_save_sendpulse_api_key', 10, 0 );

function adfoin_sendpulse_action_fields() {
    ?>
    <script type="text/template" id="sendpulse-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'subscribe'">
                <th scope="row">
                    <?php esc_attr_e( 'Contact Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'subscribe'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Email List', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[listId]" v-model="fielddata.listId" required="required">
                        <option value=""> <?php _e( 'Select List...', 'advanced-form-integration' ); ?> </option>
                        <option v-for="(item, index) in fielddata.list" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': listLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
        </table>
    </script>
    <?php
}
add_action( 'adfoin_action_fields', 'adfoin_sendpulse_action_fields', 10, 1 );

add_action( 'adfoin_sendpulse_job_queue', 'adfoin_sendpulse_job_queue', 10, 1 );

function adfoin_sendpulse_job_queue( $data ) {
    adfoin_sendpulse_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to SendPulse API
 */
function adfoin_sendpulse_send_data( $record, $posted_data ) {

    $id     = get_option( 'adfoin_sendpulse_id' ) ? get_option( 'adfoin_sendpulse_id' ) : "";
    $secret = get_option( 'adfoin_sendpulse_secret' ) ? get_option( 'adfoin_sendpulse_secret' ) : "";

    if( !$id || !$secret ) {
        return;
    }

    $record_data = json_decode( $record["data"], true );

    if( array_key_exists( "cl", $record_data["action_data"] ) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if( !adfoin_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }

    $data    = $record_data["field_data"];
    $list_id = $data["listId"];
    $task    = $record["task"];
    $email   = empty( $data["email"] ) ? "" : adfoin_get_parsed_values($data["email"], $posted_data);
    $name    = empty( $data["name"] ) ? "" : adfoin_get_parsed_values($data["name"], $posted_data);
    $phone   = empty( $data["phone"] ) ? "" : adfoin_get_parsed_values($data["phone"], $posted_data);

    if( $task == "subscribe" ) {
        $emails = array(
            array(
                "email" => $email,
                "variables" => array(
                    "name"  => $name ? $name : "",
                    "Phone" => $phone ? $phone : ""
                )
            )
        );

        $SPApiClient = new AFISendPulseApiClient( $id, $secret, new FileStorage() );
        $return = $SPApiClient->addEmails( $list_id, $emails, array() , $record );
    }
}