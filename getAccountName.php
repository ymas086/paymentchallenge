<?php
    $url = "https://api.paystack.co/bank/resolve?account_number=".$_GET["account_number"]."&bank_code=".$_GET["bank_code"];
    
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Content-type: application/json", "Authorization: Bearer sk_test_4c4c90d3bd67ef9e750c6c60a3c9c1fbe2354525"));
    //curl_setopt($curl, CURLOPT_POST, true);
    
    $json_response = curl_exec($curl);
                                            
    curl_close($curl);
    
    $response = json_decode($json_response, true);
    
    if($response["status"] == 0){
        echo $response["message"];
    } else
        echo $response["data"]["account_name"];
?>
