<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table>:not(caption)>*>* {
     padding: 0rem 0rem;
    }
    </style>
<?php 
include 'dbconnect.php';
$_current_date = date("Y-m-01",strtotime("-5 month"));
$id = mysqli_real_escape_string($con,$_GET['id']);
$sql = "SELECT `result_id`, CONCAT(`first_open_number`, `second_close_number`) as r, date_format(`open_date`,'%Y%m%d') date FROM `result` where `result_game_id`=$id";
$result = $con->query($sql);
$results =[];
while($row = mysqli_fetch_assoc($result)){
    $results[] = $row;
}

?>
    <title></title>
  </head>
  <body>
       <style>
       .star_result{
    color:black;
}
 

.B{
        border-style: solid !important;
        border-width: 2px !important;
        border-color:#eb008b!important;
        padding: 2px 10px;
    }
    body {
    background-color: white;
    padding: 3px 1px 3px 0px;
    }
    .B{
        background-color: white;
    }
  
    .logo_image{width: 300px;
    height: 30px;
    margin: 16px 0px;
    }
.bg-mitti{ background-color: white !important;}
table td {
    border: solid 1px #000;
}
thead tr{
    background:#ffc107 !important;
}
.red{
    color:red;
}
  .mydate{
      display:none;
  }
    <?php
  if($exists){
      ?>
      tr td:nth-child(1),tr th:nth-child(1) {
    /*   max-width: 45px;*/
    /*font-size: 14px;*/
    /*display:none;*/

}
      
      <?php
 
  }
  
  ?>
  
  .n11, .n16,
.n22, .n27
,.n33 ,.n38
,.n44 ,.n49
,.n55 ,.n50
,.n66 ,.n61
,.n77 ,.n72
,.n88 ,.n83
,.n99 ,.n94
,.n00 ,.n05,
  .chart-11, .chart-16,
.chart-22, .chart-27
,.chart-33 ,.chart-38
,.chart-44 ,.chart-49
,.chart-55 ,.chart-50
,.chart-66 ,.chart-61
,.chart-77 ,.chart-72
,.chart-88 ,.chart-83
,.chart-99 ,.chart-94
,.chart-00 ,.chart-05,.chart-00
{
    color: red !important;
}
      .pana{
         writing-mode: vertical-rl;
    text-orientation: upright;
    font-size: 12px;
    font-weight: 700;
      }
      .mid{
          
            font-size:16px;
            font-weight:bold;
            position: relative;
                text-decoration: none;
    color: black;
    
      }
      .rtd{
        position: relative; 
        border-radius: 1px solid #ccc;
        vertical-align: middle;
}
      }
      .rdate{
          font-size:12px;
          font-weight:bold;
      }
      table td{
             border: solid 1px #ccc;
    padding: 2px;
      }
      @media only screen and (max-width: 500px) {

   .pana{

    font-size: 11px;
  
      }
      .mid{
          
            font-size:14px;
           
  
      }
      .rtd{
        position: relative; 
        border-radius: 1px solid #ccc;
         padding: 0px;
         vertical-align: bottom;
}
      }
      .rdate{
          font-size:11px;
          font-weight:bold;
      }
      table td{
             border: solid 1px #ccc;
      }

}

  </style>
      <table class="table table-bordered text-center">
          <thead>
              <tr>
                  
                  <tr>
                  
                  <style>
                      
                
                  <?php 
                   $sql = "SELECT `id`, `game_name`, `open_time`, `close_time`, `game_on_off`, `days`, `price` FROM `game` WHERE `id` ='$id'";
                $result_view =   $con->query($sql);
               $row_view = mysqli_fetch_assoc($result_view);

 

                  ?>
                  
                      <?php echo (strpos($row_view["days"],"1") !== false)?"":".mon{ display:none;}" ?>
                  
                  
                      <?php echo (strpos($row_view["days"],"2") !== false)?"":".tue{display:none;}" ?>
                  
                  
                      <?php echo (strpos($row_view["days"],"3") !== false)?"":".wed{display:none;}" ?>
                  
                  
                      <?php echo (strpos($row_view["days"],"4") !== false)?"":".thu{display:none;}" ?>
                  
                  
                      <?php echo (strpos($row_view["days"],"5") !== false)?"":".fri{display:none;}" ?>
                  
                  
                      <?php echo (strpos($row_view["days"],"6") !== false)?"":".sat{display:none;}" ?>
                  
                  
                      <?php echo (strpos($row_view["days"],"0") !== false)?"":".sun{display:none;}"; unset($row_view);unset($result_view); ?>
                  
                      
                 
                    </style>
              <th class="mydate tone d-none" >Date</th>
              <th class="mydays mon">MON</th>
              <th class="mydays tue">TUE</th>
              <th class="mydays wed">WED</th>
              <th class="mydays thu">THU</th>
              <th class="mydays fri">FRI</th>
              <th class="mydays sat">SAT</th>
              <th class="mydays sun">SUN</th>
              </tr>
                 
               
              </tr>
          </thead>
          <tbody>
              <?php 
              $result =   $con->query("SELECT   date_format(open_date,'%Y') as y ,date_format(open_date,'%m') as m ,date_format(open_date,'%d') as d  FROM `result` WHERE `result_game_id`='$id' ORDER BY `open_date` ASC limit 1");
               $row = mysqli_fetch_assoc($result);
               $date = new DateTime();
                   $date->setDate(date('Y'), date('m'),date('d'));
               if( mysqli_num_rows($result) >= 1){
                   $date->setDate($row['y'], $row['m'], $row['d']);
               }
               
                
                // $date->setDate(2018, 1, 1);
                $sub = $date->format('N');
                if($date->format('N') != 1){
                    $sub =  $date->format('N') -1;
                     
                    $date->setDate(date('Y',strtotime(" -$sub days",strtotime($date->format('d-m-Y')))), 
                                    date('m',strtotime(" -$sub days",strtotime($date->format('d-m-Y')))),
                                    date('d',strtotime(" -$sub days",strtotime($date->format('d-m-Y')))));
                    
                }
                $pd=  strtotime(" -$sub days",strtotime($date->format('Y-m-d')));
              
                $now = time(); 
                $diff = $now - $pd;
                $nd =  ceil($diff / (60 * 60 * 24 *7));
               

             
             for($x = 0 ; $x <$nd ; $x++){
             ?>
             <tr>
                 <td id="" class="rdate d-none"><?php
                 echo $date->format('d-m-Y')."<br> TO <br> ".date('d-m-Y',strtotime(" +6 days",strtotime($date->format('d-m-Y'))));
                 ?></td>
                 <td class="rtd mon" id="r<?php echo $date->format('Y-m-d') ?>"><a class="mid jodi mynumber">&nbsp;&nbsp;</a></td>
                 <td class="rtd tue" id="r<?php echo date('Y-m-d',strtotime(" +1 days",strtotime($date->format('Y-m-d')))) ?>"><a class="mid jodi mynumber">&nbsp;&nbsp;</a></td>
                 <td class="rtd wed" id="r<?php echo date('Y-m-d',strtotime(" +2 days",strtotime($date->format('Y-m-d')))) ?>"><a class="mid jodi mynumber">&nbsp;&nbsp;</a></td>
                 <td class="rtd thu" id="r<?php echo date('Y-m-d',strtotime(" +3 days",strtotime($date->format('Y-m-d')))) ?>"><a class="mid jodi mynumber">&nbsp;&nbsp;</a></td>
                 <td class="rtd fri" id="r<?php echo date('Y-m-d',strtotime(" +4 days",strtotime($date->format('Y-m-d')))) ?>"><a class="mid jodi mynumber">&nbsp;&nbsp;</a></td>
                 <td class="rtd sat" id="r<?php echo date('Y-m-d',strtotime(" +5 days",strtotime($date->format('Y-m-d')))) ?>"><a class="mid jodi mynumber">&nbsp;&nbsp;</a></td>
                 <td class="rtd sun" id="r<?php echo date('Y-m-d',strtotime(" +6 days",strtotime($date->format('Y-m-d')))) ?>"><a class="mid jodi mynumber">&nbsp;&nbsp;</a></td>
                 
             </tr>
             <?php 
             $y = date('Y',strtotime(" +7 days",strtotime($date->format('d-m-Y'))));
             $m = date('m',strtotime(" +7 days",strtotime($date->format('d-m-Y'))));
             $d = date('d',strtotime(" +7 days",strtotime($date->format('d-m-Y'))));
             $date->setDate($y, $m, $d);
             
             } ?>
          </tbody>
      </table>
    

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" ></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
   
  </body>
</html>

<?php

$sql_r = "SELECT `first_open_number`as oa, `second_close_number` as ca ,  date_format(`open_date`,'%Y-%m-%d') as d FROM `result` WHERE `result_game_id`='$id'";
$r = $con->query($sql_r);
$data =[];
while($r2 = mysqli_fetch_assoc($r)){
$data[]= $r2;
}





?>
<script>

var data = <?php echo json_encode($data) ?>;

data.forEach(i => {
$("#r"+i.d).html('<a class="mid jodi mynumber n'+i.oa+''+i.ca+'">'+i.oa+''+i.ca+'</a>');

});





</script>