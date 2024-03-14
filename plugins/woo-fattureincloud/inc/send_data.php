<?php

$token_key = get_option('wfic_api_key_fattureincloud');

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $wfic_datatosend_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $data_tosend_wfic_postfields,
  CURLOPT_HTTPHEADER => array(
    "authorization: Bearer ".$token_key,
    "content-type: application/json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

$response_value = (json_decode($response, true));

if (!empty($response_value ['error'] ) || $err) {

 error_log(print_r($response_value , true));

  ?>
   
  <div id="message" class="notice notice-error is-dismissible">
  <p>
          
        <?php
      
        $fic_risposta = (json_decode($response, true));
        
        echo "Documento non creato => ";
        echo $fic_risposta['error']['message'];
      
        error_log("Documento non creato => ".$fic_risposta['error']['message']);
      
        $wfic_datatosend_url = "https://api-v2.fattureincloud.it/c/".$company_ID."/issued_documents/totals";
      
        $curl = curl_init();
      
        curl_setopt_array($curl, array(
          CURLOPT_URL => $wfic_datatosend_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $data_tosend_wfic_postfields,
          CURLOPT_HTTPHEADER => array(
            "authorization: Bearer ".$token_key,
            "content-type: application/json"
          ),
        ));
      
        $response = curl_exec($curl);
        $err = curl_error($curl);
      
        curl_close($curl);
      
        $response_value = (json_decode($response, true));

        error_log(print_r($response_value , true));

        if (is_admin()) { 

        ?>  <div id="message" class="notice notice-error is-dismissible">
          <p>Ecco i dati necessari per comprendere le incongruenze, controllare la differenza tra <b>amount_gross</b> (tolare lordo) e <b>payment_sum</b> (totale pagato) => 
         
          <?php echo "<pre>"; print_r($response_value); echo "</pre>"; ?>

        </div>

          <?php

        }


  ?>
  </p>
</div>



<?php  

} else {


error_log("Invio riuscito ");

  ?>
  


<div id="message" class="notice notice-success is-dismissible">
    <p><b>Invio Riuscito!</b></p>
    <?php
  /*
    echo "<pre>";
    print_r(json_decode($response, true));
    echo "</pre>"; 
  */  
    ?>
  </div>




<?php 
}
