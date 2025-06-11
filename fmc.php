

<?php

function  send($to,$notif){

    
    $apiKey = "AAAAHizWc2o:APA91bFIZCIzwI7CRP90AHgrJcbCNrQmAAk1kSuIlK78umsRyMx-N283bLDSqUWll_Qu-9JbFHoCNYHCmxOu2hRLHj4HL2ZEiLFbgsMaRN3HFQlvQcFs1j_vt7-CZNAU2T2Y1vZ8oDh";
$ch = curl_init();
$filds = json_encode(array("registration_ids" => $to,"notification"=>$notif));
curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $filds);

$headers = array();
$headers[] = 'Authorization: key = '.$apiKey;
$headers[] = 'Content-Type: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);


if (curl_errno($ch)) {
     'Error:' . curl_error($ch);
}

curl_close($ch); 
}



