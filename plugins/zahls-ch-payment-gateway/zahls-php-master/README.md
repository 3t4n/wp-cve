zahls-ch-php
===========

VERSIONING
----------

This client API library uses the API version 1.0.0 of zahls.ch. If you got troubles, make sure you are using the correct library version!

Requirements
------------
We recommend to use PHP version >= 5.4
The following php modules are required: cURL

Getting started with zahls.ch
----------------------------
If you don't already use Composer, then you probably should read the installation guide http://getcomposer.org/download/.
Please include this library via Composer in your composer.json and execute **composer update** to refresh the autoload.php.
For the latest library version you can use the following content of composer.json:

```json
{
    "require": {
        "zahls/zahls": "dev-master"
    }
}
```


For the Version 1.0.0 you can use the following content of composer.json:

```json
{
    "require": {
        "zahls/zahls": "1.0.0"
    }
}
```


1.  Instantiate the zahls.ch class with the following parameters:
    $instance: Your zahls.ch instance name. (e.g. instance name 'demo' you request your zahls.ch instance https://demo.zahls.ch
    $apiSecret: This is your API secret which you can find in your instance's administration.

    ```php
    $zahls = new \Zahls\Zahls($instance, $apiSecret);
    ```
2.  Instantiate the model class with the parameters described in the API-reference:

    ```php
    $subscription = new \Zahls\Models\Request\Subscription();
    $subscription->setId(1);
    ```
3.  Use your desired function:

    ```php
    $response  = $zahls->cancel($subscription);
    $subscriptionId = $response->getId();
    ```

    It recommend to wrap it into a "try/catch" to handle exceptions like this:
    ```php
    try{
        $response  = $zahls->cancel($subscription);
        $subscriptionId = $response->getId();
    }catch(\Zahls\ZahlsException $e){
        //Do something with the error informations below
        $e->getCode();
        $e->getMessage();
    }
    ```


Documentation
--------------

For further information, please refer to the official REST API reference: https://www.zahls.ch
