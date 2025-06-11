<?php 
header('Content-Type: application/json');
if(isset($_REQUEST['tk'])){
    if($_REQUEST['tk'] == "sad54hjk5j3k2h54j3h5jkgk3jh45gkhj23g54hz"){
date_default_timezone_set('Asia/Kolkata');
$date_time = date("Y-m-d H:i:s");
$date_date = date("Y-m-d");
$time = date("H:i:s");
$date = Date("Y-m-d" ,strtotime(" -5 hour"));
$date_bet = Date("Y-m-d");

        
 // Start if Token Tag

include 'dbconnect.php';

if(isset($_POST['user_id']) && isset($_POST['checkuser']) ) {
    $user_id = mysqli_real_escape_string($con ,$_POST['user_id']);
   $data = [];
      $sql = "SELECT `user_id`,  `status`  FROM `user` where  user_id='$user_id' ";
    $result = $con->query($sql);
    $data['sql'] = $sql;
    $data["status"] = false;
    $data["data"][] = "Data";
    
       
        $row = mysqli_fetch_assoc($result);
        if($row['status'] == "active"){
            $data["status"] = true;
        }
        else{
            $data["status"] = false;
        }
       
    echo json_encode($data);
     
}
 //End  Login App With FMC TOKEN  
// End if Token Tag 
    }
    else{
    echo "Failed 2";
}
}
else{
    echo "Failed 1";
}

    




?>