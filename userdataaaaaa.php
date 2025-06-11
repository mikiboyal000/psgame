<?php 
header('Content-Type: application/json');
if(isset($_REQUEST['tk'])){
    if($_REQUEST['tk'] == "sad54hjk5j3k2h54j3h5jkgk3jh45gkhj23g54hj"){
date_default_timezone_set('Asia/Kolkata');
$date_time = date("Y-m-d H:i:s");
$date_date = date("Y-m-d");
$time = date("H:i:s");
$date = Date("Y-m-d" ,strtotime(" -5 hour"));
$date_bet = Date("Y-m-d");

        
 // Start if Token Tag

include 'dbconnect.php';
//Create new User
// register new user query
if(isset($_POST['phone']) && isset($_POST['uname']) && isset($_POST['pass'])   ){
    date_default_timezone_set('Asia/Kolkata');
     $uname = $con -> real_escape_string($_POST['uname']);
     $phone = $con -> real_escape_string($_POST['phone']);
     $pass = $con -> real_escape_string($_POST['pass']);
    
  
// 2020-02-19 19:06:32
$date = date("Y-m-d H:i:s");
/* Change database details according to your database */

mysqli_autocommit($con, false);

$flag = true;
// start insert query for user
  $query1 = "INSERT INTO `user`( `usrname`, `phone`, `password`,`created_at`) VALUES ('$uname','$phone','$pass','$date')";


$result = mysqli_query($con, $query1);

if (!$result) {
	$flag = false;
 
}
// end insert query for user
 $sql = "SELECT `user_id` FROM `user` WHERE `usrname` = '$uname'";
$result = $con->query($sql);
$row = mysqli_fetch_assoc($result);
$id = $row['user_id'];
        // start add money to wallet
 $query2 = "INSERT INTO `wallet`( `money`, `user_user_id`,update_at) VALUES ('0','$id','$date')";
$result = mysqli_query($con, $query2);

if (!$result) {
	$flag = false;
 
}
// end add money wallet
if ($flag) {
       $data["status"]=true;
   "yes";
    mysqli_commit($con);
   
} else {
    $data["status"]=false;
      "no";
	mysqli_rollback($con);
  
} 

mysqli_close($con);
    //  end Total query all 3 
    
   
    
}
// End Create new USer
//Create new User with Ref
// register new user with Ref query
if(isset($_POST['phone']) && isset($_POST['username']) && isset($_POST['password']) &&  isset($_POST['ref'])   ){
    date_default_timezone_set('Asia/Kolkata');
  
     $uname = $con -> real_escape_string($_POST['username']);
     $phone = $con -> real_escape_string($_POST['phone']);
     $pass = $con -> real_escape_string($_POST['password']);
     $ref = $con -> real_escape_string($_POST['ref']);
    $data = [];
  
// 2020-02-19 19:06:32
$date = date("Y-m-d H:i:s");
/* Change database details according to your database */

mysqli_autocommit($con, false);

$flag = true;
// start insert query for user
  $query1 = "INSERT INTO `user`( `usrname`, `phone`, `password`,`created_at`,ref) VALUES ('$uname','$phone','$pass','$date','$ref')";


$result = mysqli_query($con, $query1);

if (!$result) {
	$flag = false;
 $data['msg']=$con->error;
}
// end insert query for user
 $sql = "SELECT `user_id` FROM `user` WHERE `usrname` = '$uname'";
$result = $con->query($sql);
$row = mysqli_fetch_assoc($result);
$id = $row['user_id'];
        // start add money to wallet
 $query2 = "INSERT INTO `wallet`( `money`, `user_user_id`,update_at) VALUES ('0','$id','$date')";
$result = mysqli_query($con, $query2);
   $sql_fmc = "SELECT `device_token` FROM `user` where user_type='user' and admin ='1'";
    
    $result = $con->query($sql_fmc);
   
    $to = [];

     include 'fmc.php';
     $notif = array("title"=>"Register New User", "body"=>"$uname, $phone");
    while($row = mysqli_fetch_assoc($result)) {
          $to[] = $row['device_token'];
    }
send($to,$notif);

if (!$result) {
	$flag = false;
 
}
// end add money wallet
if ($flag) {
        $data["status"]=true;
        
 "yes";
    mysqli_commit($con);
   
} else {
     $data["status"]=false;
  "no";
	mysqli_rollback($con);
  
} 
echo json_encode($data);

mysqli_close($con);
    //  end Total query all 3 
    
   
    
}
// End Create new USer with Ref


// get All Game Result On Main Page
if(isset($_GET['game_result'])){
    $result = $con->query("SELECT `id`, `game_name`, `open_time`, `close_time` ,time_format(`open_time`, '%h:%i %p') as ot, time_format(`close_time`, '%h:%i %p') as ct , `game_on_off`, `days`, `price`, concat('***-**-***') as result FROM `game` ORDER BY `game`.`open_time` ASC");
$game = [];
$game2 = [];
$k = true;
$ids = "";
while($row = mysqli_fetch_assoc($result)){
    $game[$row['id']] = $row;
    
    
}
$result = $con->query("SELECT `result_id`, `first_number`, `first_open_number`, `second_close_number`, `second_number`, `open_date`, `close_date`, `result_game_id`, date_format(open_date,'%Y-%m-%d') FROM `result`WHERE date_format(open_date,'%Y-%m-%d')='$date'");
while($row = mysqli_fetch_assoc($result)){
    if($row['second_number'] =="")
    $game[$row['result_game_id']]['result'] = $row['first_number']."-".$row['first_open_number'];
    
    else
    $game[$row['result_game_id']]['result'] = $row['first_number']."-".$row['first_open_number'].$row['second_close_number']."-".$row['second_number'];
    
    
}
foreach ($game as $key => $value){
    $game2[] =  $value;
    
}


echo json_encode($game2);

}

// Get Contribution 
if(isset($_POST['gamecontribution']) && isset($_POST['userid']) ){
    $uid= mysqli_real_escape_string($con,$_POST['userid']);
    $json = [];
    $json['todayContribution'] = 0;
    $json['todayActiveUSer'] = 0;
    $json['totalInvitedUser'] = 0;
    $json['bonus'] = 0;
    $sql = "SELECT `total_contribution` FROM `wallet` WHERE `user_user_id`='$uid'";
    $result = $con->query($sql);
    while($row= mysqli_fetch_assoc($result) ){
        $json['bonus']= $row['total_contribution'];
    }
    $sql = "SELECT COUNT(`ref`) as totaluser FROM `user` WHERE `ref`='$uid'";
    $result = $con->query($sql);
    while($row= mysqli_fetch_assoc($result) ){
        $json['totalInvitedUser']= $row['totaluser'];
    }
     $sql = "SELECT COUNT(`bid_id`) as activeusers,  IFNULL(sum(bid_amount),'0.00') as bidamount  FROM `bid` WHERE `user_id` in (select user.user_id from user WHERE user.ref = '$uid') and date='$date' ";
    $result = $con->query($sql);
    while($row= mysqli_fetch_assoc($result) ){
        $json['todayActiveUSer']= $row['activeusers'];
        $json['todayContribution']= $row['bidamount'];
    }
    
    echo json_encode($json);
    mysqli_close($con);

}
// End Get Contribution 
if(isset($_GET['get_game_type'])){
    $result = $con->query("SELECT `gt_id` as id , `name`, `fname` FROM `game_type` ");
    $result2 = $con->query("SELECT `id`, `game_name`, `open_time`, `close_time`, `game_on_off`, `days`, `price` FROM `game` ");
$game = [];

while($row = mysqli_fetch_assoc($result)){
    $game["GameTypeModel"][] = $row;
}
while($row = mysqli_fetch_assoc($result2)){
    $game["Game"][] = $row;
}



echo json_encode($game);

}
if(isset($_GET['game_result_with_dt'])){
    $result = $con->query("SELECT `id`, `game_name`, `open_time`, `close_time` ,time_format(`open_time`, '%h:%i %p') as ot, time_format(`close_time`, '%h:%i %p') as ct , `game_on_off`, `days`, `price`, concat('***-**-***') as result FROM `game` ORDER BY `game`.`open_time` ASC");
$game = [];
$game2 = [];
$game2["date_time"]=$date_time;
$game2["date"]=$date_date;
$game2["time"]=$time;
$k = true;
$ids = "";
while($row = mysqli_fetch_assoc($result)){
    $game[$row['id']] = $row;
    
    
}
$result = $con->query("SELECT `result_id`, `first_number`, `first_open_number`, `second_close_number`, `second_number`, `open_date`, `close_date`, `result_game_id`, date_format(open_date,'%Y-%m-%d') FROM `result`WHERE date_format(open_date,'%Y-%m-%d')='$date'");
while($row = mysqli_fetch_assoc($result)){
    if($row['second_number'] =="")
    $game[$row['result_game_id']]['result'] = $row['first_number']."-".$row['first_open_number'];
    
    else
    $game[$row['result_game_id']]['result'] = $row['first_number']."-".$row['first_open_number'].$row['second_close_number']."-".$row['second_number'];
    
    
}
foreach ($game as $key => $value){
    $game2["GameResultModel"][] =  $value;
    
}


echo json_encode($game2);

}
// End get All Game Result On Main Page
// GET Last two Result
if(isset($_GET['game_result_with_dt_last_two'])){
    $result = $con->query("SELECT `result_id`, `first_number`, `first_open_number`, `second_close_number`, `second_number`, `open_date`, `close_date`, `result_game_id` ,game.game_name FROM `result` INNER JOIN game on game.id = result_game_id ORDER BY `result`.`close_date` DESC LIMIT 2");
while($row = mysqli_fetch_assoc($result)){
    $r="";
    if($row['second_number'] =="")
   $r= $row['first_number']."-".$row['first_open_number'];
    
    else
    $r = $row['first_number']."-".$row['first_open_number'].$row['second_close_number']."-".$row['second_number'];
    
    $game[]= array("gamename"=> $row['game_name'],'result'=>$r);
    
}



echo json_encode($game);

}
// End get All Game Result On Main Page
// Login App
// if(isset($_POST['appuser']) && isset($_POST['apppass'])) {
//     $username = mysqli_real_escape_string($con ,$_POST['appuser']);
//     $password = mysqli_real_escape_string($con ,$_POST['apppass']);
//     $sql = "SELECT `user_id`,  `status`, `play`,usrname,  `notice1`, `pnotice`, `password`, `user_type`, `phonepe`, `paytm`, `gpay`, `bank_name`, `account_number`, `ifsc`,wallet.money as money FROM `user` INNER JOIN wallet on wallet.user_user_id = user_id WHERE usrname ='$username' and password='$password' and user_type='user' ";
//     $result = $con->query($sql);
    
//     if(mysqli_num_rows($result) == 1){
//         $user["status"] = true;
//         $row = mysqli_fetch_assoc($result);
//         $user["data"]=$row;
//         echo json_encode($user);
//     }
//     else{
//         $user["status"] = false; 
//         echo json_encode($user);
//     }
     
// }
 //End  Login App  
// Login App With FMC TOKEN
if(isset($_POST['appuser']) && isset($_POST['apppass']) && isset($_POST['fmctoken'])) {
    $username = mysqli_real_escape_string($con ,$_POST['appuser']);
    $password = mysqli_real_escape_string($con ,$_POST['apppass']);
    $fmctoken = mysqli_real_escape_string($con ,$_POST['fmctoken']);
    $sql = "SELECT `user_id`,  `status`, `play`,usrname,phone,  `notice1`, `pnotice`, `password`, `user_type`, `phonepe`, `paytm`, `gpay`, `bank_name`, `account_number`, `ifsc`,wallet.money as money FROM `user` INNER JOIN wallet on wallet.user_user_id = user_id WHERE (usrname ='$username' or  phone ='$username')  and password='$password' and user_type='user' ";
    $result = $con->query($sql);
    
    if(mysqli_num_rows($result) == 1){
        $user["status"] = true;
        $row = mysqli_fetch_assoc($result);
        $user["data"]=$row;
        $sql ="UPDATE `user` SET `device_token` ='$fmctoken' WHERE `user_id`=".$row["user_id"];
        $con->query($sql);
        echo json_encode($user);
    }
    else{
        $user["status"] = false; 
        echo json_encode($user);
    }
     
}
 //End  Login App With FMC TOKEN  
// CheckBalance and update in play screen App
if(isset($_POST['appuserid']) && isset($_POST['myblanace'])) {
    $id = mysqli_real_escape_string($con ,$_POST['appuserid']);
    
    $sql = "SELECT `wallet_id`, `money`, `user_user_id`, `update_at` FROM `wallet` WHERE `user_user_id` ='$id' ";
    $result = $con->query($sql);
    
    if(mysqli_num_rows($result) == 1){
        $user["status"] = true;
        $row = mysqli_fetch_assoc($result);
        $user["money"]=$row['money'];
        echo json_encode($user);
    }
    else{
        $user["status"] = false; 
        echo json_encode($user);
    }
     
}
 //End  CheckBalance and update in play screen App 
// CheckBalance and update in play screen App
if(isset($_POST['funduserid']) && isset($_POST['amount'])) {
$fund_user_id = mysqli_real_escape_string($con ,$_POST['funduserid']);
     $amount = mysqli_real_escape_string($con ,$_POST['amount']);
     
  $username = "";
     $sql = "SELECT `usrname`, `phone`  FROM `user` WHERE `user_id`=$fund_user_id";
      $result = $con->query($sql);
      while($row = mysqli_fetch_assoc($result)){
          $username = $row['usrname'];
      }
    
   $sql = "SELECT `wallet_id`, `money`, `user_user_id`, `update_at` FROM `wallet` WHERE `user_user_id` ='$fund_user_id' ";
  //echo "<br>";
    $result = $con->query($sql);
  $msg = "";
    if(mysqli_num_rows($result) == 1){
     
     
        $user["status"] = true;
        $row = mysqli_fetch_assoc($result);
      $mymoney = $row['money'];
     
    
         
         
          $total = $mymoney + $amount; 
          $uid = "upi".date("Ymdhsi").$amount.$fund_user_id;
            $sql = "INSERT INTO `transaction`(`user_id`, `c_amount`, `credit`, `debit`, `final_amount`, `date`, `created_at`, `comment`, `uids`) 
                            VALUES ('$fund_user_id','$mymoney','$amount','0','$total','$date','$date_time','Add Money by UPI ','$uid')";
                              
        if($con->query($sql) === TRUE){
            
         $sql_money = "UPDATE `wallet` SET `money`=money + $amount,`update_at`='$date_time' WHERE `wallet_id` =".$row['wallet_id'];
          if($con->query($sql_money) == TRUE){
                $msg=  "yes";
                // Send Notification 
$title = "Add Point";
$body = "$username Point :$amount";
sendAdmin($con,$title,$body);
// Send Notification End
      }else{
          $msg=  "no ".$con->error; 
      }

        
        
        } 
        else{
        echo "Trasaction Updated Failed".$con->error;
        }
      
        
        
        
    }
    else{
         $msg= "no";
        
    }
    
     echo $msg;
}
 //End  CheckBalance and update in play screen App 
// Check Play Condition means its time for open or close perticular game using gameid 
if( isset($_GET['gettimeupdate'])) {
    $game_id = mysqli_real_escape_string($con ,$_GET['gettimeupdate']);
    
     $sql = "SELECT `id`, `game_name`, `open_time`, `close_time`, `game_on_off`, `days`, `price` FROM `game` WHERE `id`='$game_id' ";
    $result = $con->query($sql);
    $user= [];
    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);
        
          $time = strtotime($date_time);
         $open= strtotime($date_date." ".$row['open_time']);
         $close= strtotime($date_date." ".$row['close_time']);
         $open_min_left = ($open - $time)/60;
         $close_min_left = ($close - $time)/60;
         if($open_min_left > 0){
             $user["playstatus"] = true;
             $user ["playoc"] = "open"; 
         }
         else if($close_min_left > 0){
             $user["playstatus"] = true;
             $user ["playoc"] = "close"; 
         }
         else{
             $user["playstatus"] = false;
         }
      
        echo json_encode($user);
    }
    else{
        $user["playstatus"]  = false; 
        echo json_encode($user);
    }
     
}
 //End  // Check Play Condition means its time for open or close perticular game using gameid  
// Play  Bet 
if( isset($_POST['playbet'])) {
   
$data =json_decode($_POST['data'],true);;
    $values = "";
      $game_id = $data[0]['gameid'];
      $open_close = $data[0]['openclose'];
     mysqli_autocommit($con, false);

$flag = true;



$total_bid_amount = 0;


     for($x = 0 ; $x < sizeof($data);$x++){
         if($x != 0){
             $values .= ",";
         }
         $bid_amount = $data[$x]['price'];
         $total_bid_amount = $total_bid_amount+$bid_amount;
         $game_id = $data[$x]['gameid'];
         $user_id = $data[$x]['userid'];
       
         $status = $data[$x]['status'];
         $bid_game_number = $data[$x]['number'];
         $open_close = $data[$x]['openclose'];
         $game_type = $data[$x]['gametype'];
         $mydate = $date_bet;
                      $values .= "('$bid_amount','$game_id','$user_id','$status','$bid_game_number','$open_close','$game_type','$mydate','$date_time')";
     }
     if($total_bid_amount>0){
        $sql1 = "INSERT INTO `bid`(`bid_amount`, `game_id`, `user_id`, `status`, `bid_game_number`, `open_close`, `game_type`, `date`,createe_at) VALUES $values";
  $user_id = $data[0]['userid'];
   $check_balance_sql = "SELECT `wallet_id`, `money`, `user_user_id`, user.ref FROM `wallet` inner join user on user.user_id = user_user_id  WHERE `user_user_id` ='$user_id'";
  
  $mymoneyresult = $con->query($check_balance_sql);
  
  while($myrow = mysqli_fetch_assoc($mymoneyresult)) {
  $my_balance = (int)$myrow['money'];
  $ref_id = (int)$myrow['ref'];
  
  if($my_balance >=  $total_bid_amount){
  if($con->query($sql1) === TRUE){
 
  }
  else{
      $flag = false;
     
       $con->error;
  }
  $point = $my_balance - $total_bid_amount;
  $uids = date("YmdHms").rand(10,100);
    $sql_t = "INSERT INTO `transaction`
            (`user_id`, `c_amount`, `credit`, `debit`, `final_amount`, `date`, `created_at`,comment,uids) 
            VALUES 
            ('$user_id','$my_balance','0','$total_bid_amount',' $point','$mydate','$date_time','bid placed',$uids)";
     if($con->query($sql_t) === TRUE){
      
     
  }
  else{
      $flag = false;
        
  }    
  
  
  
   $sql2 ="UPDATE `wallet` SET `money`= $point ,update_at='$date_time' WHERE `user_user_id`='$user_id'";
  if($con->query($sql2) === TRUE){
      
       
    //   Money trasfer to Refree Account 
     if( $ref_id >0){
       //  money trasfer to refrance if 
       //Check Balance of refrance id
       $check_balance_sql_ref = "SELECT `wallet_id`, `money`, `user_user_id` FROM `wallet` INNER JOIN user on user.user_id = user_user_id WHERE `user_user_id` ='$ref_id'";
  
  $mymoneyresult_ref = $con->query($check_balance_sql_ref);
  
  $myrow_ref = mysqli_fetch_assoc($mymoneyresult_ref);
  $my_balance_ref = (int)$myrow_ref['money'];
 
       //Check Balance of refrance id End
       $total_ref= $my_balance_ref +$total_bid_amount/20;
          $sql_t_ref = "INSERT INTO `transaction`
            (`user_id`, `c_amount`, `credit`, `debit`, `final_amount`, `date`, `created_at`,comment,uids) 
            VALUES 
            ('$ref_id','$my_balance_ref',$total_bid_amount/20 ,0,'$total_ref','$mydate','$date_time','Bonus point for bid placed','bonus_$uids')";
     if($con->query($sql_t_ref) === TRUE){
        //  money trasfer to refrance if 
           $sql2 ="UPDATE `wallet` SET `money`=money+$total_bid_amount/20 ,`total_contribution`=total_contribution+$total_bid_amount  ,update_at='$date_time' WHERE `user_user_id`='$ref_id'";
  if($con->query($sql2) === TRUE){}
  else{ echo "REfrnace error".$con->error;}
        
        //  money trasfer to refrance if end
     } }
    //   Money trasfer to Refree Account End 
  
  }
  else{
      $flag = false;
       echo $con->error;
        
      
  }
  } }
  
  if ($flag) {
     
    
    mysqli_commit($con);
   
} else {
    
	mysqli_rollback($con);
	  $con->error;
  
} 
if($flag){
     echo "yes";
    
}
else{
    echo "no";
     echo $con->error;
}
}else{
    echo "Amount is -tive";
}

mysqli_close($con);
    
    
}
 //End  Play Bet  
 
 
 //Play full hand half sangam 
 
 // user half and full sangam
if(isset($_POST['bet_is_done_half_and_full_sangam'])  ){
    
    $data =json_decode($_POST['bet_is_done_half_and_full_sangam'],true);
    $values = "";
      $game_id = $data[0]['gameid'];
      $open_close = $data[0]['openclose'];
     mysqli_autocommit($con, false);
$total_bid_amount =0;

$flag = true;
     for($x = 0 ; $x < sizeof($data);$x++){
         if($x != 0){
             $values .= ",";
         }

         $bid_amount = $data[$x]['bidamount'];
          $total_bid_amount = $total_bid_amount+$bid_amount;
         $game_id = $data[$x]['gameid'];
         $user_id = $data[$x]['userid'];
         $status = $data[$x]['status'];
         $fn = $data[$x]['fn'];
         $fno = $data[$x]['fno'];
         $snc = $data[$x]['snc'];
         $sn = $data[$x]['sn'];
         $open_close =$data[$x]['openclose'];
         $game_type = $data[$x]['gametype'];
         $mydate = $date_bet;
         
         $values .= "('$bid_amount','$game_id','$user_id','$status','$fn' ,'$fno' ,'$snc' ,'$sn','$open_close','$game_type','$mydate','$date_time')";
     
        //  bid_amount`, `game_id`, `user_id`, `status`, `fn`, `fno`, `snc`, `sn`, `open_close`, `game_type`, `date`, `createe_at
     }
     
     if($total_bid_amount>0){
        $sql1 = "INSERT INTO `bid` (`bid_amount`, `game_id`, `user_id`, `status`, `fn`, `fno`, `snc`, `sn`, `open_close`, `game_type`, `date`, createe_at) VALUES $values";
  $user_id = $data[0]['userid'];
   $check_balance_sql = "SELECT `wallet_id`, `money`, `user_user_id`,user.ref FROM `wallet` inner join user on user.user_id = user_user_id WHERE `user_user_id` ='$user_id'";
  
  $mymoneyresult = $con->query($check_balance_sql);
  
  while($myrow = mysqli_fetch_assoc($mymoneyresult)) {
  $my_balance = (int)$myrow['money'];
  $ref_id = (int)$myrow['ref'];
  if($my_balance >=  $total_bid_amount){
  if($con->query($sql1) === TRUE){
     
  }
  else{
      $flag = false;
      echo $con->error;
  }
  $point = $my_balance - $total_bid_amount;
  $uids = date("YmdHms").rand(10,100);
   $sql_t = "INSERT INTO `transaction`
            (`user_id`, `c_amount`, `credit`, `debit`, `final_amount`, `date`, `created_at`,comment,uids) 
            VALUES 
            ('$user_id','$my_balance','0','$total_bid_amount',' $point','$mydate','$date_time','bid placed',$uids)";
     if($con->query($sql_t) === TRUE){
     
  }
  else{
      $flag = false;
       echo $con->error;
  }    
  
  
  
   $sql2 ="UPDATE `wallet` SET `money`='$point' ,update_at='$date_time' WHERE `user_user_id`='$user_id'";
  if($con->query($sql2) === TRUE){
      
    //   Money trasfer to Refree Account 
     if( $ref_id >0){
       //  money trasfer to refrance if 
       //Check Balance of refrance id
       $check_balance_sql_ref = "SELECT `wallet_id`, `money`, `user_user_id` FROM `wallet` INNER JOIN user on user.user_id = user_user_id WHERE `user_user_id` ='$ref_id'";
  
  $mymoneyresult_ref = $con->query($check_balance_sql_ref);
  
  $myrow_ref = mysqli_fetch_assoc($mymoneyresult_ref);
  $my_balance_ref = (int)$myrow_ref['money'];
 
       //Check Balance of refrance id End
       $total_ref= $my_balance_ref +$total_bid_amount/20;
          $sql_t_ref = "INSERT INTO `transaction`
            (`user_id`, `c_amount`, `credit`, `debit`, `final_amount`, `date`, `created_at`,comment,uids) 
            VALUES 
            ('$ref_id','$my_balance_ref',$total_bid_amount/20 ,0,'$total_ref','$mydate','$date_time','Bonus point for bid placed','bonus_$uids')";
     if($con->query($sql_t_ref) === TRUE){
        //  money trasfer to refrance if 
           $sql2 ="UPDATE `wallet` SET `money`=money+$total_bid_amount/20 ,`total_contribution`=total_contribution+$total_bid_amount  ,update_at='$date_time' WHERE `user_user_id`='$ref_id'";
  if($con->query($sql2) === TRUE){}
  else{ echo "REfrnace error".$con->error;}
        
        //  money trasfer to refrance if end
     } }
    //   Money trasfer to Refree Account End 
     
  }
  else{
      $flag = false;
       echo $con->error;
      
  }
  } }
  
  if ($flag) {
     
    
    mysqli_commit($con);
   
} else {
    
	mysqli_rollback($con);
	 echo $con->error;
  
} 
if($flag){
    echo "yes";
    
}
else{
    echo "no";
     echo $con->error;
}
}else{
    echo "Amount is -tive";
}

mysqli_close($con);
    
    
}

// payment accepted
 //End Play full hand half sangam 
 
 
 
 // User Genral Select Query
if(isset($_POST['my_genral_query']) && isset($_POST['gktoken'])) {
    
    if($_POST['gktoken'] == "thisusersaddjhas345bm345") {
         $myselect = $_POST['my_genral_query'];
 
    $sql = $myselect;
    $result = $con->query($sql);
    $myresults = [];
  while($row= mysqli_fetch_assoc($result)){
      $myresults[] = $row;
  }
  
 echo  json_encode($myresults);
    
}
     
}
 //End  User Genral Select Query 
 // User Genral Select Query
if(isset($_POST['my_genral_query_insert_update']) && isset($_POST['gktoken'])  && isset($_POST['success']) && isset($_POST['failure'])) {
    
    if($_POST['gktoken'] == "thisusersaddjhas345bm345") {
         $myselect = $_POST['my_genral_query_insert_update'];
 
    $sql = $myselect;
   if($con->query($sql) ===TRUE){
       echo $_POST['success'];
   }
else{
    echo $_POST['failure'];
}
  
  
 
    
}
     
}
 //End  User Genral Select Query 


   
        
       
// End if Token Tag 
    }
    else{
    echo "Failed 2";
}
}
else{
    echo "Failed 1";
}

    

   function sendAdmin($con,$title,$body){
       

   
   $sql_fmc = "SELECT `device_token` FROM `user` where user_type='user' and admin ='1'";
    
    $result = $con->query($sql_fmc);
   
    $to = [];

     include 'fmc.php';
     $notif = array("title"=>$title, "body"=>"$body");
    while($row = mysqli_fetch_assoc($result)) {
           $to[] = $row['device_token'];
    }
send($to,$notif);
   }

    






?>