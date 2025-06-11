<?php 
include 'dbconnect.php';
$game = [];

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
 