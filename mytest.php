<?php
include 'dbconnect.php';

$sql ="SELECT `id`, `type`, `name`, `value` FROM `setting` ";
$result = $con->query($sql);

while($row = mysqli_fetch_assoc($result)){
    $data[]=$row;
}
echo json_encode($data);