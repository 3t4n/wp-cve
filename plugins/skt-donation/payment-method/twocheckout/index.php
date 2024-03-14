<?php
    require_once("lib/Twocheckout.php");
    Twocheckout::privateKey('001896D0-B69E-479F-A800-65FB20D364CF');
    Twocheckout::sellerId('901392675');
   
    Twocheckout::username('SKT123456');
    Twocheckout::password('Admin123');
    // If you want to turn off SSL verification (Please don't do this in your production environment)
    Twocheckout::verifySSL(false);  // this is set to true by default
    // To use your sandbox account set sandbox to true
    Twocheckout::sandbox(true);
    // All methods return an Array by default or you can set the format to 'json' to get a JSON response.
    Twocheckout::format('json');

try {
    $charge = Twocheckout_Charge::auth(array(
        "merchantOrderId" => "123",
        "token" => $_POST['token'],

        "billingAddr" => array(
            "name" => 'sHOEB Tester',
            "addrLine1" => '123 Test St',
            "city" => 'Columbus',
            "state" => 'OH',
            "zipCode" => '43123',
            "country" => 'USA',
            "email" => 'testingtester@2co.com',
            "phoneNumber" => '555-555-5555'
        ),
        "lineItems" => array(array(
            "type" => 'product',
            "price" => "5.00",
            "name" => "Test Product",
            "quantity" => "1",
            "tangible" => "N",
            "startupFee" => "1.00",
            "recurrence" => "1 Month",
            //"item_rec_status" => "live",
            "description" => "This is a test"
        ))
    ), 'array');

    if ($charge['response']['responseCode'] == 'APPROVED') {
        echo "Thanks for your Order!";
        echo$transactionId = $charge['response']['transactionId'];
        echo '</br>';
        echo$orderNumber = $charge['response']['orderNumber'];
    }
} catch (Twocheckout_Error $e) {
    $e->getMessage();
}

?>