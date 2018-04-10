<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
// header("Access-Control-Allow-Methods: POST");
// header("Access-Control-Allow-Credentials: true");
// header('Content-Type: application/json');

$results = array();
$data = $_POST['variable'];

$WML_AUTH_TOKEN = shell_exec('curl --basic --user 68038a68-f3d4-483e-a8dc-fa7dd2b90879:6b713f51-ab3c-4c25-8fb1-ebf2126d96e6 https://ibm-watson-ml.eu-gb.bluemix.net/v3/identity/token');
$arr = explode(":",$WML_AUTH_TOKEN);
$WML_AUTH_TOKEN = explode('"', $arr[1]);
$WML_AUTH_TOKEN = $WML_AUTH_TOKEN[1];

$lat = $data[0];
$long = $data[1];
$add_acc = $data[2];
$num_mosq = $data[3];

$command = "curl -X POST --header 'Content-Type: application/json' --header 'Accept: application/json' --header".' "Authorization: Bearer '.$WML_AUTH_TOKEN.'" -d '."'{".'"fields": ["Latitude", "Longitude", "AddressAccuracy", "NumMosquitos"],"values": [['.$lat.','.$long.','.$add_acc.','.$num_mosq.']]}'."' https://ibm-watson-ml.eu-gb.bluemix.net/v3/wml_instances/2e3308fb-2307-4ab5-8f03-e99de32cf12a/published_models/e107425c-cd3d-47ce-83dc-2a342ee9a238/deployments/c962b2b3-4845-4c52-915b-d62d6390be04/online";

$result = shell_exec($command);
//echo $result;

$a = explode(":",$result);
$b = explode("[",$a[2]);
$c = explode("]",$b[5]);
$d = explode(",",$c[0]);
$probOfzero = $d[0];
$probOfone  = $d[1];
$e = explode(",",$c[1]);
$f = explode('"',$e[2]);
$predictedLabel = $f[1];

array_push($results,$probOfzero);
array_push($results,$probOfone);
array_push($results,$predictedLabel);

echo json_encode($results);
?>
