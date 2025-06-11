<?php 

if(isset($_POST["month_list"])){
    
 
    include 'dbconnect.php';;
  date_default_timezone_set('Asia/Kolkata');

$date = date("Y-m-d h:i:s");
$_date2 = date("Y-m-d");

$sql = $_POST["month_list"];
      $result =   $con->query($sql);
        $number = mysqli_num_rows($result);
$year= date("Y");
$month= date("m");
$cdate= date("d");
$day = date("N");
$wdate = $cdate - $day;
$date = date_create();
date_date_set($date, $year, $month, $wdate);

$data = [];


$next = 0;
$next_week = 604800;
$next = 86400;
$n = 86400;
for($x = 0 ; $x <= $number/7; $x++){
    
    $from = strtotime(date_format($date, 'Y-m-d'));
    
  $data[] = array(
    "from"=>date('Y-m-d',strtotime(date_format($date, 'Y-m-d')) + $next),
    "to"=>date('Y-m-d',strtotime(date_format($date, 'Y-m-d'))+ $next+$n*6),
    "mon"=>date('Y-m-d',strtotime(date_format($date, 'Y-m-d'))+$next),
    "tue"=>date('Y-m-d',strtotime(date_format($date, 'Y-m-d'))+ $next+$n),
    "wed"=>date('Y-m-d',strtotime(date_format($date, 'Y-m-d'))+ $next+$n*2),
    "thu"=>date('Y-m-d',strtotime(date_format($date, 'Y-m-d'))+ $next+$n*3),
    "fri"=>date('Y-m-d',strtotime(date_format($date, 'Y-m-d'))+ $next+$n*4),
    "sat"=>date('Y-m-d',strtotime(date_format($date, 'Y-m-d'))+ $next+$n*5),
    "sun"=>date('Y-m-d',strtotime(date_format($date, 'Y-m-d'))+ $next+$n*6)
    
    );
    
    
   
   
   $next -= 86400*7;
  
   
}

echo json_encode($data);
}

if(isset($_POST["month_list2"])){
    
  include 'dbconnect.php';;
  date_default_timezone_set('Asia/Kolkata');

$sql = $_POST["month_list2"];
$result =   $con->query($sql);
$data=[];
while($row = mysqli_fetch_assoc($result)){
    $data[] = $row;
    
}

echo json_encode($data);
mysqli_close($con);
}