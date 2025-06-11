<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'dbconnect.php';

date_default_timezone_set('Asia/Kolkata');
$date_time = date("Y-m-d H:i:s");
$date_date = date("Y-m-d");
$time = date("H:i:s");
$date = Date("Y-m-d" ,strtotime(" 0 hour"));
$date_bet = Date("Y-m-d");

 $user_id =4;
    $date = '2023-02-09';
     echo $sql = "SELECT  `c_amount`, `credit`, `debit`, if(credit>debit,'Credit','Debit') as cd,if(credit>debit,credit,debit) as amount,`final_amount`,  
    date_format(t.created_at,'%d-%m-%Y %h:%i %p') as time,date_format(ifnull(b.date,sb.date),'%d-%m-%Y') as bid_date, `transaction_mode`, `trans_add_withdraw`, `comment`,
    ifnull(b.open_close,sb.time) as market ,ifnull(b.game_type_full,sb.game_type_full) as game_type_full,ifnull(g.game_name,sg.name) as game_name,
    
    ifnull( if(bid_game_number is not null, (CASE 

    WHEN ((Length(ifnull(fn,'')) >0 ) AND (Length(ifnull(sn,'')) >0)) 
    THEN CONCAT(b.fn , '-',b.sn) 

    WHEN (Length(ifnull(fn,''))>0 AND Length(ifnull(snc,''))>0) 
    THEN CONCAT(b.fn , '-',b.snc) 

    WHEN (Length(ifnull(fno,''))>0 AND Length(ifnull(sn,''))>0) 
    THEN CONCAT(b.fno , '-',b.sn) 

    ELSE bid_game_number END),null),sb.bid_number) as bid_game_number,game_mode
    FROM `transaction` as t 
Left JOIN bid as b on b.bid_id=t.bid_id
LEFT join game as g on g.id=b.game_id
left join startline_bid as sb on sb.id=starline_bid_id
left join starline_game as sg on sg.id=sb.game_id
where t.user_id=$user_id and t.date='$date' order by t.id desc";
$result = $con->query($sql);
$json =[];
while($row =  mysqli_fetch_assoc($result)){
    $json[]=$row;
}
echo json_encode($json);
CREATE USER 'username'@'host' IDENTIFIED WITH authentication_plugin BY 'password';

 