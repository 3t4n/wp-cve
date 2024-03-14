<?php

/**
 * Implementation of contact resource requests.
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Includes\Client;

use Dotdigital_WordPress_Vendor\Dotdigital\Exception\ResponseValidationException;
use Dotdigital_WordPress_Vendor\Dotdigital\V3\Client;
use Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\Contact;
/**
 * Class Dotdigital_WordPress_Contact
 */
class Dotdigital_WordPress_Contact
{
    /**
     * The client.
     *
     * @var Client $client
     */
    private $dotdigital_client;
    /**
     * Dotdigital_WordPress_Contact constructor.
     */
    public function __construct()
    {
        $this->dotdigital_client = new \Dotdigital_WordPress\Includes\Client\Dotdigital_WordPress_Client(Client::class);
    }
    /**
     * Create or update the contact in Dotdigital
     *
     * We use the import method to create or update the contact in Dotdigital as
     * the create method will throw an error if the contact already exists.
     *
     * @param Contact $contact The contact to create or update.
     * @return void
     * @throws \Http\Client\Exception If the API call fails.
     */
    public function create_or_update(Contact $contact)
    {
        $this->dotdigital_client->get_client()->contacts->patchByIdentifier($contact->getIdentifiers()->getEmail(), $contact);
    }
}
