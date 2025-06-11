  <?php
  
  include 'dbconnect.php';
   
   function sendAdmin($con,$title,$body){
       

   
   $sql_fmc = "SELECT `device_token` FROM `user` where user_type='user' and admin ='1'";
    
    $result = $con->query($sql_fmc);
   
    $to = [];

    // include 'fmc.php';
     $notif = array("title"=>$title, "body"=>"$body");
    while($row = mysqli_fetch_assoc($result)) {
           $to[] = $row['device_token'];
    }
    print_r($to);
send($to,$notif);
   }
sendAdmin($con,"title","body");

function  send($to,$notif){

    
    $apiKey = "AAAAYw5rm3Q:APA91bFmQsbh4KuNupb5gob-xD6Tyt6WoD1r26FO3koTb82kVYNpkiEV8QWTAYb0F7hNN707BPzIafmcWn2iuByZLse73yuNkjQW3sIppBVoyVA3w2TXGNN7Huh3jk1YUVpWj9VkDcQU";
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

echo $result = curl_exec($ch);


if (curl_errno($ch)) {
     'Error:' . curl_error($ch);
}

curl_close($ch); 
}