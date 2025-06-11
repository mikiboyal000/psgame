<?php

include 'dbconnect.php';
  date_default_timezone_set('Asia/Kolkata');

$date = date("Y-m-d H:i:s");
$_date2 = date("Y-m-d",strtotime("-5 hour"));
$mydate = date("Y-m-d",strtotime("-5 hour"));

if(isset($_REQUEST['ttime'])){
    echo date("F d, Y H:i:s");
}
// Check connection
if (mysqli_connect_errno())
  {
      
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
  


// register new user query
if(isset($_POST['phone']) && isset($_POST['uname']) && isset($_POST['pass']) && isset($_POST['cpass'])  ){
    date_default_timezone_set('Asia/Kolkata');
     $uname = $con -> real_escape_string($_POST['uname']);
     $phone = $con -> real_escape_string($_POST['phone']);
     $pass = $con -> real_escape_string($_POST['pass']);
     $cpass = $con -> real_escape_string($_POST['cpass']);
     
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
  echo "yes";
    mysqli_commit($con);
   
} else {
    echo  "no";
	mysqli_rollback($con);
  
} 

mysqli_close($con);
    //  end Total query all 3 
    
   
    
}
 
if(isset($_POST['uname']) && isset($_POST['check']) ){
     $uname = $con -> real_escape_string($_POST['uname']);
      $sql = "SELECT  `usrname` FROM `user` WHERE `usrname` = '$uname'";
      $result = $con->query($sql);
      $msg = "no";
      while($row = mysqli_fetch_assoc($result)){
       $msg = "yes";
      }
      echo $msg;
}
// Get all game list
if(isset($_POST['game_list']) && isset($_POST['getgame']) ){
     
      $sql = "SELECT `id`, `game_name`, `open_time`, `close_time` FROM `game` ";
      $result = $con->query($sql);
      $msg = [];
      while($row = mysqli_fetch_assoc($result)){
       
       echo "<option value='".$row['id']."'> ".$row['game_name']."</option>";
      }
 
}
// End all game list 

// Get all game list in JSON
if(isset($_POST['game_list_json'])  ){
     
      $sql = "SELECT `id`, `game_name`, TIME_FORMAT(`open_time`, '%h:%i %p') as `ot` ,open_time,TIME_FORMAT(`close_time`, '%h:%i %p') as `ct`,close_time   FROM `game` order by open_time";
      $result = $con->query($sql);
      $msg = [];
      while($row = mysqli_fetch_assoc($result)){
       
      $msg[] = $row;
      }
 echo json_encode($msg);
}
// End all game list in Json


// Get all game result list
if(isset($_POST['game_result_today_list_json'])  ){
     
    $sql = "SELECT `result_id`, CONCAT( first_number, '-', first_open_number,'',second_close_number,'-',second_number) AS fullnumber ,  `open_time`, `close_time`, `result_game_id`,game.game_name, DATE_FORMAT(`open_date`, '%Y-%m-%d') , game.id , first_number, first_open_number,second_close_number,second_number FROM `result` INNER JOIN game ON game.id = result_game_id WHERE CURRENT_DATE() = DATE_FORMAT(`open_date`, '%Y-%m-%d') ORDER BY result.result_id DESC";
      $result = $con->query($sql);
      $msg = [];
      while($row = mysqli_fetch_assoc($result)){
       
      $msg[] = $row;
      }
 echo json_encode($msg);
}
// End all game result list

// Delete game result
if(isset($_POST['result_id'])  && isset($_POST['delete'])){
     $id = $_POST['result_id'];
    $sql = "DELETE FROM `result` WHERE `result_id`= '$id'";
      if($con->query($sql)){
          echo "result deleted";
      }
      else{ echo "not deleted";}
 
}
// End Delete game result

// Get game Post
if(isset($_POST['game_resut_post'])  ){
     
     $data = $_POST['game_resut_post'];
     $first_number =  $data['fn'];
     $first_open_number = $data['fopen'];
     $second_close_number = $data['sclose'];
     $second_number = $data['sn'];
     $result_game_id  = $data['select_game'];
     $result_game_name  = $data['game_name'];
     $mydate = $data['date'];
    $sql = "INSERT INTO `result`( `first_number`, `first_open_number`, `second_close_number`, `second_number`, `open_date`, `close_date`, `result_game_id`) VALUES ('$first_number','$first_open_number','$second_close_number','$second_number' , '$mydate','$mydate','$result_game_id')";

    if($con->query($sql) === TRUE){
        echo "game Posted";
    winLooseFunctionOpen($first_open_number , "singleank" ,"open",$result_game_id );
      winLooseFunctionOpen($first_number , "SinglePana" ,"open",$result_game_id );
    winLooseFunctionOpen($first_number , "DoublePana" ,"open",$result_game_id );
    winLooseFunctionOpen($first_number , "TripalPana" ,"open",$result_game_id );
    $sql_fmc = "SELECT `device_token` FROM `user` where user_type='user' and device_token <>''";
    
    $result = $con->query($sql_fmc);
    $ids = true;
    $list = "";
    $to = [];
    $f = true;
     include 'fmc.php';
     $notif = array("title"=>"$result_game_name", "body"=>"$first_number-$first_open_number");
    while($row = mysqli_fetch_assoc($result)) {
       
          $to[] = $row['device_token'];


    }
 
    
send($to,$notif);
    
      
    }
    else{ echo "contact to developer";}
}                               
// End game Post 

// CREATE ADD NEW GAME 
if(isset($_POST['add_new_game_post'])  ){
     
     $data = $_POST['add_new_game_post'];
     $game_name =  $data['game_name'];
     $open_time = $data['open_time'].":00";
     $close_time = $data['close_time'].":00";
     $sql = "INSERT INTO `game`( `game_name`, `open_time`, `close_time`) VALUES ('$game_name','$open_time','$close_time')";

    if($con->query($sql) === TRUE){
        echo "$game_name Created";
    }
    else{ echo "$game_name not created contact to developer";}
}
// CREATE ADD NEW GAME  END

// Update Or Edit Deatil  NEW GAME 
if(isset($_POST['update_game_detail'])  ){
     
     $data = $_POST['update_game_detail'];
     $game_name =  $data['game_name'];
     $game_id =  $data['game_id'];
     $open_time = $data['open_time'];
     $close_time = $data['close_time'];
     $game_on_off = $data['game_on_off'];
     
     
     $sql = "UPDATE `game` SET `game_name`='$game_name',`open_time`='$open_time',`close_time`='$close_time', game_on_off ='$game_on_off' WHERE `id`='$game_id'";

    if($con->query($sql) === TRUE){
        echo "$game_name Updated success";
    }
    else{ echo "$game_name udapted failed ".$con->error;}
}
// Update Or Edit Deatil  NEW GAME  END

// Update2 Or Edit Deatil  NEW GAME 
if(isset($_POST['update_game_detail2'])  ){
     
     $data = $_POST['update_game_detail2'];
     $game_name =  $data['game_name'];
     $game_id =  $data['game_id'];
     $open_time = $data['open_time'];
     $close_time = $data['close_time'];
     $game_on_off = $data['game_on_off'];
     $days = $data['days'];
     
     
     $sql = "UPDATE `game` SET `game_name`='$game_name',`open_time`='$open_time',`close_time`='$close_time', game_on_off ='$game_on_off',days ='$days' WHERE `id`='$game_id'";

    if($con->query($sql) === TRUE){
        echo "$game_name Updated success";
    }
    else{ echo "$game_name udapted failed ".$con->error;}
}
// Update2 Or Edit Deatil  NEW GAME  END

// Post full game game Post
if(isset($_POST['game_update_save_data'])  ){
     
     $data = $_POST['game_update_save_data'];
     $first_number =  $data['fn'];
     $first_open_number = $data['fopen'];
     $second_close_number = $data['sclose'];
     $second_number = $data['sn'];
     $mydate = $data['date'];
     $jodi = $first_open_number.$second_close_number;
     $id  = $data['id'];
     $game_id  = $data['game_id'];
     $game_name  = $data['game_name'];
     $hf_open = "fn='$first_number' and snc = '$second_close_number'";
     $hf_close = "fno='$first_open_number' and sn = '$second_number'";
     $full = "fn='$first_number' and sn = '$second_number'";
     
        $sql = "UPDATE `result` SET `first_number`='$first_number',`first_open_number`='$first_open_number',`second_close_number`='$second_close_number',`second_number`='$second_number',`close_date`='$mydate' WHERE `result_id` = '$id' ";

    if($con->query($sql) === TRUE){
        echo "Full game Posted".$date2;
        
     
      winLooseFunctionOpen($second_close_number , "singleank" ,"close",$game_id );
      winLooseFunctionOpen($jodi , "jodi" ,"open" ,$game_id);
   
      winLooseFunctionOpen($second_number , "SinglePana" ,"close",$game_id );
     
      winLooseFunctionOpen($second_number , "DoublePana" ,"close" ,$game_id);
      
      winLooseFunctionOpen($second_number , "TripalPana" ,"close",$game_id );
      winLooseFunctionhalfsangam($hf_open , "HalfSangam" , "open",$game_id );
      winLooseFunctionhalfsangam($hf_close , "HalfSangam", "close",$game_id  );
      winLooseFunctionhalfsangam($full , "FullSangam" , "open",$game_id );
      
      $sql_fmc = "SELECT `device_token` FROM `user` where user_type='user' ";
    
    $result = $con->query($sql_fmc);
    $ids = true;
    $list = "";
     $to = array();
     include 'fmc.php';
     $notif = array("title"=>"$game_name", "body"=>"$first_number-$first_open_number$second_close_number-$second_number");
    while($row = mysqli_fetch_assoc($result)) {
         
          array_push($to,$row['device_token']);


    }
   send($to,$notif);
    
 
       
    }
    else{ echo "contact to developer";}
}                               
// End Post full game game Post

// win loose function Area Start 
function winLooseFunctionOpen($number , $game_type , $open_close,$game_id){
   include 'dbconnect.php';
  date_default_timezone_set('Asia/Kolkata');
    $date2 = date("Y-m-d", strtotime(' -5 hour'));
    $flag = true;
    
    $sql_update = "UPDATE `bid` INNER join game_type on bid.game_type= game_type.name 
                    SET 
                    `win_amount` = bid_amount*game_type.price,
                    status = 'win'
                    where 
                        game_type='$game_type' 
                    and game_id='$game_id' 
                    and bid_game_number='$number' 
                    and open_close ='$open_close' 
                    and status='pending' 
                    and date ='$date2'";
       if($con->query($sql_update) === TRUE){          
    
 $sql = "SELECT `bid_id`, `bid_amount`, `game_id`, `user_id`, `status`, `bid_game_number`,
            date , win_amount
            FROM `bid` 
            where game_type='$game_type' and game_id='$game_id' and bid_game_number='$number' and open_close ='$open_close' and status='win' and date ='$date2'";
   
    $esult = $con->query($sql);
     $data= [];
    $row_size = mysqli_num_rows($esult);
     while($row = mysqli_fetch_assoc($esult)){
        $data[] = $row;
     }
     
     for($x =0;$x < $row_size ; $x++){
       
         $bid= $data[$x]['bid_id'];
         $uid= $data[$x]['user_id'];
  
         $win = $data[$x]['win_amount'];
      $sql_amount = "SELECT `wallet_id`, `money` FROM `wallet` WHERE `user_user_id` = '$uid'" ;
      $result = $con->query($sql_amount);
      $row = mysqli_fetch_assoc($result);
      $amount = $row['money'];
      $money =  $amount + $win;
      $loose = "-".$bid_amount;
      $date_full = date("Y-m-d H:i:s");
       // $sql_update_balance= "UPDATE `wallet` SET `money`= money + $win , update_at='$date_full'   WHERE  `user_user_id` = '$uid'";
      if(true){
           
     
          
          //  transaction
          $date = date("Y-m-d H:i:s");

$my_balance = $amount;
$credit = $win;
$point = $win + $amount;
  $sql_t = "INSERT INTO `transaction`
            (`user_id`, `c_amount`, `credit`, `debit`, `final_amount`, `date`, `created_at`,comment, uids) 
            VALUES 
            ('$uid','$my_balance','$credit','0',' $point','$date2','$date' ,'win amount game_type=$game_type game id=$game_id , bid_number=$number, open-close=$open_close ','win_bid_$bid' )";
     if($con->query($sql_t) === TRUE){
            $sql_update_balance= "UPDATE `wallet` SET `money`= money + $win , update_at='$date_full'   WHERE  `user_user_id` = '$uid'";
      if( $con->query($sql_update_balance) === TRUE){}
      else{  $flag = false;}
     
  }
  else{
      $flag = false;
      
  }

// transation 
          
      }
         
     }
     if($flag){
       $sql_update_loose = "UPDATE `bid` SET `status`='loose'  
              WHERE game_type='$game_type' and game_id='$game_id' and status='pending' and open_close ='$open_close' and date='$date2' ";
              if($con->query($sql_update_loose) === TRUE){
                 
              } }   
       }
       else{
           echo "not updated";}
       
    
}



function winLooseFunctionhalfsangam($sql_number , $game_type,$oc,$game_id ){
     include 'dbconnect.php';
  date_default_timezone_set('Asia/Kolkata');
    $date2 = date("Y-m-d",strtotime(' -5 hour'));
    $flag = true;
     
    $sql_update = "UPDATE `bid` INNER join game_type on bid.game_type= game_type.name 
                    SET 
                    `win_amount` = bid_amount*game_type.price,
                    status = 'win'
                    where game_type='$game_type' 
                    and game_id='$game_id' 
                    and $sql_number  
                    and date='$date2' 
                    and open_close = '$oc' and status='pending'";
       if($con->query($sql_update) === TRUE) {
    //  `fn`, `fno`, `snc`, `sn`
    $sql = "SELECT `bid_id`, `bid_amount`, `game_id`, `user_id`, `status`, `bid_game_number`,
            date,win_amount
            FROM `bid` 
            where game_type='$game_type' and game_id='$game_id' and $sql_number  and date='$date2' and open_close = '$oc' and status='win'
  ";
     $esult = $con->query($sql);
     $data= [];
    
    $row_size = mysqli_num_rows($esult);
     while($row = mysqli_fetch_assoc($esult)){
        $data[] = $row;
     }
     
     for($x =0;$x < $row_size ; $x++){
    
        
         $bid= $data[$x]['bid_id'];
         $uid= $data[$x]['user_id'];
        
        
         $bid_amount= $data[$x]['bid_amount'];
     
         $win = $data[$x]['win_amount'];
       $sql_amount = "SELECT `wallet_id`, `money` FROM `wallet` WHERE `user_user_id` = '$uid'" ;
       $result = $con->query($sql_amount);
       $row = mysqli_fetch_assoc($result);
       $amount = $row['money'];
       $money =  $amount + $win;
       $loose = "-".$bid_amount;
       $date_full = date("Y-m-d H:i:s");
        // $sql_update_balance= "UPDATE `wallet` SET `money`=money + $win ,update_at='$date_full'  WHERE  `user_user_id` = '$uid'";
       if( true){
    
              //  transaction
          $date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d",strtotime(' -5 hour'));
$my_balance = $amount;
$credit = $win;
$point = $win + $amount;
$n = mysqli_real_escape_string($con,$sql_number);
  $sql_t = "INSERT INTO `transaction`
            (`user_id`, `c_amount`, `credit`, `debit`, `final_amount`, `date`, `created_at`,comment,uids) 
            VALUES 
            ('$uid','$my_balance','$credit','0',' $point','$date2','$date','win amount game_type=$game_type game id=$game_id , bid_number=$n, open-close=$oc ', 'win_bid_$bid')";
     if($con->query($sql_t) === TRUE){
         $sql_update_balance= "UPDATE `wallet` SET `money`=money + $win ,update_at='$date_full'  WHERE  `user_user_id` = '$uid'";
       if( $con->query($sql_update_balance) === TRUE){}
       else{
          $flag = false;  
       }
     
  }
  else{
      $flag = false;
      
  }

// transation
       }
         
     }
     if($flag){
     $sql_update_loose = "UPDATE `bid` SET `status`='loose'  
               WHERE game_type='$game_type' and game_id='$game_id' and status='pending' and date='$date2' and open_close = '$oc'";
               if($con->query($sql_update_loose) === TRUE){
                
               }}
       }
       else{
           echo "not updated";}
}


// win loose function Area End

// Get game Post
if(isset($_POST['get_game_esult_half'])  ){
     
 $sql = "SELECT `result_id`, `first_number`, `first_open_number`, `second_close_number`, `second_number`, `open_date`,DATE_FORMAT(open_date, '%d-%m-%Y') as date , `close_date`, game.game_name FROM `result` INNER join game ON game.id = result_game_id WHERE `second_close_number` = '' or second_number = '' order BY result_id DESC";
$result = $con->query($sql);
$data= [];
while($row = mysqli_fetch_assoc($result)){
    $data[]=$row;
 }
    echo json_encode($data);
}                               
// End game Post

// deletgame 
if(isset($_POST['delete_game']) && isset($_POST['game_id'])  ){
     $game_id = $_POST['game_id'];
 $sql = "DELETE FROM `game` WHERE `id`='$game_id' ";
if($con->query($sql) === TRUE){
    echo "game deletd";
}
else{
    echo "game not deletd";
}

}


// Get get wallate update
if(isset($_POST['get_wallet']) && isset($_POST['user_id']) ){
     $user_id = $con -> real_escape_string($_POST['user_id']);
       $sql = "SELECT `money` FROM `wallet` WHERE `user_user_id`='$user_id'  ";
      $result = $con->query($sql);
      $row = mysqli_fetch_assoc($result);
       
       echo $row['money'];
      
 
}
// End get wallate update 
// Get get Json
if( isset($_POST['send_json'])  ){
    
      $send_json = $_POST['send_json'];
      $size = sizeof($send_json);
    $game_id = $send_json[$size-1]['game_id'];
    $user_id = $send_json[$size-1]['user_id'];
    $bid_type = $send_json[$size-1]['bid_type'];
    $total_amount = $send_json[$size-1]['total_amount'];
    $status = "pending";
    $msg = "";
    
    for($x = 0 ; $x < ($size-1) ; $x++){
        if($x !=0)$values .= ",";
        $amount = $send_json[$x]['amount'];
        $bid_game_number = $send_json[$x]['number'];
        $values .= "('$amount','$game_id','$user_id','$status','$bid_game_number', '$bid_type','$date')  ";
    }
mysqli_autocommit($con, false);

$flag = true;
    $sql = "INSERT INTO `bid`( `bid_amount`, `game_id`, `user_id`, `status`, `bid_game_number`, `bid_type`, `createe_at`) VALUES $values";
       
      if($con->query($sql) === FALSE){
          $flag = false;
      }
      $date_full = date("Y-m-d H:i:s");
      $sql2 = "UPDATE `wallet` SET `money`='$total_amount' ,update_at='$date_full' WHERE `user_user_id` =$user_id ";
      if($con->query($sql2) === FALSE){
          $flag = false;
      }
      
      if ($flag) {
    $msg = "Game successfully Played";
    mysqli_commit($con);
   
} else {
    $msg = "Bet is not done please Try again and or Conact to admin".$con->error;
	mysqli_rollback($con);
  
} 
echo $msg;
mysqli_close($con);
 
}
// End get Json 
 
if(isset($_POST['number']) && isset($_POST['check_number']) ){
     $number = $con -> real_escape_string($_POST['number']);
      $sql = "SELECT `phone` FROM `user` WHERE `phone` =  '$number'";
      $result = $con->query($sql);
      $msg = "no";
      while($row = mysqli_fetch_assoc($result)){
       $msg = "yes";
      }
      echo $msg;
}

// login Query 
if(isset($_POST['uname']) && isset($_POST['pass']) && isset($_POST['login'])){
      $uname = $con -> real_escape_string($_POST['uname']);
      $pass = $con -> real_escape_string($_POST['pass']);
 $sql = "SELECT `user_id`, `usrname`, `phone` , `user_type`, `status`, `play`, `created_at`, wallet.money FROM `user` INNER JOIN wallet ON user_id = wallet.user_user_id WHERE  `usrname` = '$uname' and  `password` = '$pass'";
$result = $con->query($sql);
$user = [];
while($row = mysqli_fetch_assoc($result)){
    $user[] = $row;
}

    echo json_encode($user);
   
}

// get_all user all information
if(isset($_POST['get_all_info_user_id'])){
      $user_id = $con -> real_escape_string($_POST['get_all_info_user_id']);
     
  $sql = "SELECT `user_id`, `usrname`, `phone`, `password`, `user_type`, `phonepe`, `paytm`, `gpay`, `bank_name`, `account_number`, `ifsc`, `status`, `play`, `created_at` FROM `user` WHERE `user_id` = '$user_id'";
$result = $con->query($sql);

$row = mysqli_fetch_assoc($result);
 


    echo  json_encode($row);
   
}

// update user information 
if(isset($_POST['uudate_user_deatil_all'])  ){
     $sql = $_POST['uudate_user_deatil_all'];
   if($con->query($sql) === TRUE){
       echo "updated infromation";
   }
   else{
       echo "not updated please do it again";
   }
}

// get Top 3 result
if(isset($_POST['get_top_three_result'])  ){
     $sql = "SELECT CONCAT(`first_number`,'-',`first_open_number`,`second_close_number`,'-',`second_number`) as fullnumber , game.game_name FROM `result` INNER JOIN game ON game.id = result_game_id ORDER BY `result`.`result_id` DESC LIMIT 3";
   $result = $con->query($sql);
  $data = [];
  while($row = mysqli_fetch_assoc($result)){
      $data[] = $row;
  }
  echo json_encode($data);

}

// add withdraw fund
if(isset($_POST['user_fund_add_or_withdraw'])  ){
    $data = $_POST['user_fund_add_or_withdraw'];
    $money = $data['money'];
    $user_id = $data['user_id'];
    $req = $data['req'];
    $req2 = $data['req2'];
    $msg = $data['msg'];
    $vai = "";
    $phone = "";
    $vai = $data['vai'];
    $phone = $data['phone'];
     $sql = "INSERT INTO 
            `wallet_request`
            (`w_user_id`, `r_money`, `r_type` , `r_type2`, `status`, `created_at`, `update_at` ,`payment_type`, `payment_link`) 
            VALUES 
            ('$user_id','$money','$req','$req2','pending','$date','$date','$vai','$phone' )";
 if($con->query($sql) === TRUE){
     echo $msg." send it successful";
 }
 
 
  else{ echo $msg." not sent please contact to admin"; }

}

// add withdraw history
if(isset($_POST['user_fund_history'])  ){
   
    $sql = $_POST['user_fund_history'];
        $result = $con->query($sql);
 $data = [];
 while($row = mysqli_fetch_assoc($result)){
     $data[] = $row;
 }
echo json_encode($data);

}


// genral sql query for inserting data
if(isset($_POST['user_genral_sql']) && isset($_POST['success']) && isset($_POST['failed'])  ){
   $sql = $_POST['user_genral_sql'];

if(preg_match('(drop|delete)', $data) === 1) { 
    echo "Query not success";
} 
else{
   if($con->query($sql) == TRUE){
     echo $_POST['success'];
 }
else{echo $_POST['failed'];}  
} 
      


}

// genral sql query for inserting data
if(isset($_POST['user_genral_sql_notice'])   ){
   
    $sql = $_POST['user_genral_sql_notice'];
      
 if($con->query($sql) == TRUE){
      $r=   $con->query("select id from live_notice order by id desc limit 1");
   $r2 = mysqli_fetch_assoc($r);
   $id = $r2['id'];
   $con->query("update user set pnotice = concat(pnotice,'--$id')");
 }
else{echo $_POST['failed'];}
}
if(isset($_POST['user_genral_sql_notice2'])   ){
   
    $sql = $_POST['user_genral_sql_notice2'];
    $list = $_POST['list'];
      
      
 if($con->query($sql) == TRUE){
      $r=   $con->query("select id from live_notice order by id desc limit 1");
   $r2 = mysqli_fetch_assoc($r);
   $id = $r2['id'];
   $con->query("update user set pnotice = concat(pnotice,'--$id') where user_id in ($list)");
 }
else{echo $_POST['failed'];}
}
if(isset($_POST['user_genral_sql_active'])   ){
   
    $sql = $_POST['user_genral_sql_active'];
    $list = $_POST['list'];
      
      
 if($con->query($sql) == TRUE){
      $r=   $con->query("select id from live_notice order by id desc limit 1");
   $r2 = mysqli_fetch_assoc($r);
   $id = $r2['id'];
   $con->query("update user set pnotice = concat(pnotice,'--$id') where status ='active' ");
 }
else{echo $_POST['failed'];}
}

// genral2 sql query for inserting data
if( isset($_POST['money_detail']) && isset($_POST['success']) && isset($_POST['failed'])  ){
   
 
     
    
     mysqli_autocommit($con, false);
// sql1 = "UPDATE `wallet` SET `money`='"+money+"' WHERE user_user_id = '"+id+"'"; 
// sql2 = "INSERT INTO `wallet_other` (`wo_user_id`, `amount_type`, `amount`,msg, `created_at`) VALUES ('"+id+"','add_money','"+amount+"','"+msg+"','"+created_at+"')";
$flag = true;
    
    
    $data = $_POST['money_detail'];
    $user_id = $data['user_id'];
    $amount = $data['amount'];
    $msg = $data['msg'];
      $sql3 ="SELECT money FROM `wallet`  WHERE `user_user_id`='$user_id'";
  $result = $con->query($sql3);
  $row  = mysqli_fetch_assoc($result);
 
//  transaction
$my_balance = $row['money'];
$credit = $amount;
$point = $amount + $row['money'];
$uids = date('Ymdhis').$user_id;
  $sql_t = "INSERT INTO `transaction`
            (`user_id`, `c_amount`, `credit`, `debit`, `final_amount`, `date`, `created_at`,comment,uids) 
            VALUES 
            ('$user_id','$my_balance','$credit','0',' $point','$mydate','$date','Add money manully - $msg',$uids)";
     if($con->query($sql_t) === TRUE){
     
  }
  else{
      $flag = false;
      
  }

// transation 
    
    
    $date_full = date("Y-m-d H:i:s");
      $sql1  = "UPDATE `wallet` SET `money`='$point' ,update_at='$date_full' WHERE user_user_id = '$user_id'";
  if($con->query($sql1) === TRUE){
     
  }
  else{
      $flag = false;
      
  }
 
  
  $sql2 = "INSERT INTO `wallet_other` (`wo_user_id`, `amount_type`, `amount`,msg, `created_at`) VALUES ('$user_id','add_money','$amount','$msg','$date')";

  if($con->query($sql2) === TRUE){
     
  }
  else{
      $flag = false;
      
  }
  if ($flag) {
     
    
    mysqli_commit($con);
   
} else {
    
	mysqli_rollback($con);
  
} 
if($flag){
    echo $_POST['success'];
}
else{
    echo $_POST['failed'];
}
mysqli_close($con);
}
// withdarw money manual
if( isset($_POST['money_detail2']) && isset($_POST['success']) && isset($_POST['failed'])  ){
   
 
     
    
     mysqli_autocommit($con, false);
// sql1 = "UPDATE `wallet` SET `money`='"+money+"' WHERE user_user_id = '"+id+"'"; 
// sql2 = "INSERT INTO `wallet_other` (`wo_user_id`, `amount_type`, `amount`,msg, `created_at`) VALUES ('"+id+"','add_money','"+amount+"','"+msg+"','"+created_at+"')";
$flag = true;
    
    
    $data = $_POST['money_detail2'];
    $user_id = $data['user_id'];
    $amount = $data['amount'];
    $msg = $data['msg'];
      $sql3 ="SELECT money FROM `wallet`  WHERE `user_user_id`='$user_id'";
  $result = $con->query($sql3);
  $row  = mysqli_fetch_assoc($result);
 
//  transaction
$my_balance = $row['money'];
$debit = $amount;
$point =  $row['money'] - $amount;
if($point > 0) {
    $uids = date("Ymdhis")."".$user_id;
  $sql_t = "INSERT INTO `transaction`
            (`user_id`, `c_amount`, `credit`, `debit`, `final_amount`, `date`, `created_at`,comment,uids) 
            VALUES 
            ('$user_id','$my_balance','0','$debit',' $point','$mydate','$date','Withdraw money manully - $msg',$uids)";
     if($con->query($sql_t) === TRUE){
     
  }
  else{
      $flag = false;
      
  }

// transation 
    
    $date_full = date("Y-m-d H:i:s");
    
      $sql1  = "UPDATE `wallet` SET `money`='$point' , update_at='$date_full' WHERE user_user_id = '$user_id'";
  if($con->query($sql1) === TRUE){
     
  }
  else{
      $flag = false;
      
  }
 
  
  $sql2 = "INSERT INTO `wallet_other` (`wo_user_id`, `amount_type`, `amount`,msg, `created_at`) VALUES ('$user_id','withdraw_money','$amount','$msg','$date')";

  if($con->query($sql2) === TRUE){
     
  }
  else{
      $flag = false;
      
  }
  if ($flag) {
     
    
    mysqli_commit($con);
   
} else {
    
	mysqli_rollback($con);
  
} 
if($flag){
    echo $_POST['success'];
}
else{
    echo $_POST['failed'];
}
}else{
    echo $_POST['failed'];
}
mysqli_close($con);
}

// user bet is done 
if(isset($_POST['bet_is_done'])  ){
    $point = $_POST['point'];
     $data = $_POST['bet_is_done'];
     $values = "";
      $game_id = $data[0]['game_id'];
      $open_close = $data[0]['open_close'];
     mysqli_autocommit($con, false);

$flag = true;



$total_bid_amount = 0;

     for($x = 0 ; $x < sizeof($data);$x++){
         if($x != 0){
             $values .= ",";
         }
         $bid_amount = $data[$x]['bid_amount'];
         $total_bid_amount = $total_bid_amount+$bid_amount;
         $game_id = $data[$x]['game_id'];
         $user_id = $data[$x]['user_id'];
       
         $status = $data[$x]['status'];
         $bid_game_number = $data[$x]['bid_game_number'];
         $open_close = $data[$x]['open_close'];
         $game_type = $data[$x]['game_type'];
         $mydate = $data[$x]['date'];
         $values .= "('$bid_amount','$game_id','$user_id','$status','$bid_game_number','$open_close','$game_type','$mydate','$date')";
     }
     if($total_bid_amount>0){
      $sql1 = "INSERT INTO `bid`(`bid_amount`, `game_id`, `user_id`, `status`, `bid_game_number`, `open_close`, `game_type`, `date`,createe_at) VALUES $values";
  $user_id = $data[0]['user_id'];
  $check_balance_sql = "SELECT `wallet_id`, `money`, `user_user_id` FROM `wallet` WHERE `user_user_id` ='$user_id'";
  
  $mymoneyresult = $con->query($check_balance_sql);
  
  while($myrow = mysqli_fetch_assoc($mymoneyresult)) {
  $my_balance = (int)$myrow['money'];
  if($my_balance >=  $total_bid_amount){
  if($con->query($sql1) === TRUE){
     
  }
  else{
      $flag = false;
  }
  $point = $my_balance - $total_bid_amount;
  $uids = date("YmdHms").rand(10,100);
  $sql_t = "INSERT INTO `transaction`
            (`user_id`, `c_amount`, `credit`, `debit`, `final_amount`, `date`, `created_at`,comment,uids) 
            VALUES 
            ('$user_id','$my_balance','0','$total_bid_amount',' $point','$mydate','$date','bid placed',$uids)";
     if($con->query($sql_t) === TRUE){
     
  }
  else{
      $flag = false;
      
  }    
  
  $date_full = date("Y-m-d H:i:s");
  $sql2 ="UPDATE `wallet` SET `money`='$point' ,update_at='$date_full' WHERE `user_user_id`='$user_id'";
  if($con->query($sql2) === TRUE){
     
  }
  else{
      $flag = false;
      
  }
  } }
  
  if ($flag) {
     
    
    mysqli_commit($con);
   
} else {
    
	mysqli_rollback($con);
  
} 
if($flag){
    echo "yes";
}
else{
    echo "no";
}
}else{
    echo "Amount is -tive";
}
mysqli_close($con);
}


// user half and full sangam
if(isset($_POST['bet_is_done_half_and_full_sangam'])  ){
    
    $point = $_POST['point'];
     $data = $_POST['bet_is_done_half_and_full_sangam'];
     $values = "";
     mysqli_autocommit($con, false);

$flag = true;
     for($x = 0 ; $x < sizeof($data);$x++){
         if($x != 0){
             $values .= ",";
         }
         $bid_amount = $data[$x]['bid_amount'];
         $game_id = $data[$x]['game_id'];
         $user_id = $data[$x]['user_id'];
         $status = $data[$x]['status'];
         $fn = "";
         $fn = $data[$x]['fn'];
         $fno = "";
         $fno = $data[$x]['fno'];
         $snc="";
         $snc = $data[$x]['snc'];
         $sn= "";
         $sn = $data[$x]['sn'];
         $open_close ="open";;
         $game_type = $data[$x]['game_type'];
         $mydate = $data[$x]['date'];
         
         $values .= "('$bid_amount','$game_id','$user_id','$status','$fn' ,'$fno' ,'$snc' ,'$sn','$open_close','$game_type','$mydate','$date')";
     }
      $sql1 = "INSERT INTO `bid`
      (`bid_amount`, `game_id`, `user_id`, `status`, `fn`, `fno`, `snc`, `sn`, `open_close`, `game_type`, `date`, `createe_at`) 
      VALUES  $values";
  if($con->query($sql1) === TRUE){
     
  }
  else{
      $flag = false;
      
  }
  $user_id = $data[0]['user_id'];
  $date_full = date("Y-m-d H:i:s");
  $sql2 ="UPDATE `wallet` SET `money`='$point',update_at='$date_full' WHERE `user_user_id`='$user_id '";
  if($con->query($sql2) === TRUE){
     
  }
  else{
      $flag = false;
      
  }
  if ($flag) {
     
    
    mysqli_commit($con);
   
} else {
    
	mysqli_rollback($con);
  
} 
if($flag){
    echo "yes";
}
else{
    echo "no";
}
mysqli_close($con);
}

// payment accepted


if(isset($_POST['accept_request']) && isset($_POST['amount']) && isset($_POST['wr_id'])  ){
    $amount = $_POST['amount'];
    $wr_id = $_POST['wr_id'];
    $user_id = $_POST['user_id'];
     
    
     mysqli_autocommit($con, false);

$flag = true;
    
      $sql1 = "UPDATE `wallet_request` SET `status`='accepted' ,`update_at`='$date' WHERE `wr_id` ='$wr_id' ";
  if($con->query($sql1) === TRUE){
     
  }
  else{
      $flag = false;
      
  }
  $sql3 ="SELECT money FROM `wallet`  WHERE `user_user_id`='$user_id'";
  $result = $con->query($sql3);
  $row  = mysqli_fetch_assoc($result);
 
//  transaction
$my_balance = $row['money'];
$credit = $amount;
$point = $amount + $row['money'];
$uids = date("Ymdhis");
  $sql_t = "INSERT INTO `transaction`
            (`user_id`, `c_amount`, `credit`, `debit`, `final_amount`, `date`, `created_at`,comment,uids) 
            VALUES 
            ('$user_id','$my_balance','$credit','0',' $point','$mydate','$date','Add money request accepted',$uids)";
     if($con->query($sql_t) === TRUE){
     
  }
  else{
      $flag = false;
      
  }

// transation 
  $amount =  $amount + $row['money'];
  $date_full = date("Y-m-d H:i:s");
  $sql2 ="UPDATE `wallet` SET `money`='$amount' , update_at='$date_full' WHERE `user_user_id`='$user_id '";
  if($con->query($sql2) === TRUE){
     
  }
  else{
      $flag = false;
      
  }
  if ($flag) {
     
    
    mysqli_commit($con);
   
} else {
    
	mysqli_rollback($con);
  
} 
if($flag){
    echo "user requested accepted pointed added to user account";
}
else{
    echo "Failed";
}
mysqli_close($con);
}

// payment rejected


if(isset($_POST['rejected_request'])  && isset($_POST['wr_id'])  ){
    $amount = $_POST['amount'];
    $wr_id = $_POST['wr_id'];
    $user_id = $_POST['user_id'];
 
    
     $sql1 = "UPDATE `wallet_request` SET `status`='rejected' ,`update_at`='$date' WHERE `wr_id` ='$wr_id' ";
  if($con->query($sql1) === TRUE){
     echo "user request rejected success";
  }
  else{
      echo "user request rejected failed";
      
  }

mysqli_close($con);
}

// widthdraw 

// payment accepted


if(isset($_POST['withdraw_accept_request']) && isset($_POST['amount']) && isset($_POST['wr_id'])  ){
    $amount = $_POST['amount'];
    $wr_id = $_POST['wr_id'];
    $user_id = $_POST['user_id'];
     
    
     mysqli_autocommit($con, false);

$flag = true;
    
      $sql1 = "UPDATE `wallet_request` SET `status`='accepted' ,`update_at`='$date' WHERE `wr_id` ='$wr_id' ";
  if($con->query($sql1) === TRUE){
     
  }
  else{
      $flag = false;
      
  }
  $sql3 ="SELECT money FROM `wallet`  WHERE `user_user_id`='$user_id '";
  $result = $con->query($sql3);
  $row  = mysqli_fetch_assoc($result);
 $famount =   $row['money'] - $amount ;
 if($famount <= 0) {
      $flag = false;
 }
 
 
//  transaction
$my_balance = $row['money'];
$debit = $amount;
$uids = date("Ymdhis");

  $sql_t = "INSERT INTO `transaction`
            (`user_id`, `c_amount`, `credit`, `debit`, `final_amount`, `date`, `created_at`,comment,uids) 
            VALUES 
            ('$user_id','$my_balance','0','$debit',' $famount','$mydate','$date','withdaw request accepted',$uids)";
     if($con->query($sql_t) === TRUE){
     
  }
  else{
      $flag = false;
      
  }

// transation 
  $date_full = date("Y-m-d H:i:s");
  $sql2 ="UPDATE `wallet` SET `money`='$famount', update_at='$date_full' WHERE `user_user_id`='$user_id '";
  if($con->query($sql2) === TRUE){
     
  }
  else{
      $flag = false;
      
  }
  if ($flag) {
     
    
    mysqli_commit($con);
   
} else {
    
	mysqli_rollback($con);
  
} 
if($flag){
    echo "user requested accepted pointed added to user account";
}
else{
    echo "Failed";
}
mysqli_close($con);
}
?>