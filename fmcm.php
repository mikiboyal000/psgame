<?php 
include 'dbconnect.php';

 $sql_fmc = "SELECT `device_token` FROM `user` where user_type='user' and device_token <>''";
    
    $result = $con->query($sql_fmc);
    $ids = true;
    $list = "";
    $to = [];
    $f = true;
$n = $_GET['n'];
     $notif = array("title"=>"Dishawer", "body"=>"test msg back $n");
    while($row = mysqli_fetch_assoc($result)) {
      // print_r($row);
       //   $to[] = "dWV50WUkSdKqzd3-59ROvs:APA91bF76S-T7Y_vf9jTx1PAu6WcxICTUAUO4jc71-tBWdsj-bOsqvfLEs9mHvxTl_L9_SYg0ZPibpGVP0Mmyp23ytJp5Up4_fu3olEIK-j8LFUtFvRR1GGs8gwjvJ_202l8Kihwsvk-";


    }
 
     $to[] = "fNXSePItROiYAJex_lNfZX:APA91bE8g76r47b-A5oYyEfmXCv3q0yFMjXtYNzPxuFfAVwMfhGEONEwTUv5fLZ1Fy7glBhMrLeUHaTfUSSGDv4aFIItMEuzLZSAA242tLeM6gS7GdmJB4DlG-WnEAMpaBSQQryHFSLB";
send($to,$notif);
function  send($to,$notif){

    
    $apiKey = "AAAAEizesJI:APA91bHAYkV07JkcrgjRG24LN0Z1AAocExBYpwOjyVZIp12LH1b6Aecu6KdWC0PmFMsHi0f_tWOb-nGqRBwMQ9zDlADYvlJAcfotnZIFo29plXSSuJxIGHnviMY_fZfoHeeMh4c4r3qB";
  //  $apiKey = "	AAAA52r0qvY:APA91bESeHtqP2-D1l2ZtxFCRxSDCmtBY98UHFLukkzn27dQaynjvNXmBMrJCzEw_3YkTB039gY9asgsEgY6AQVHCYWynTnymHQJXsaHt0pDC2_U2rnZ1MyVMaadaW3WOGOfWRKKhAOi";
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
print_r($result);

if (curl_errno($ch)) {
    echo  'MY Error:' . curl_error($ch);
}

curl_close($ch); 
}
?>