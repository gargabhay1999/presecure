<?php 
// function to geocode address, it will return false if unable to geocode address
$address = $_POST['variable'];
	$results = array();

		$address = urlencode($address);
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyAAxXbF0r42T9gKEK5hvM8drCo2rvTEoSI";

		$resp_json = file_get_contents($url);

		$resp = json_decode($resp_json, true);

		if($resp['status']=='OK'){
			$lati = isset($resp['results'][0]['geometry']['location']['lat']) ? $resp['results'][0]['geometry']['location']['lat'] : "";
			$longi = isset($resp['results'][0]['geometry']['location']['lng']) ? $resp['results'][0]['geometry']['location']['lng'] : "";
			$formatted_address = isset($resp['results'][0]['formatted_address']) ? $resp['results'][0]['formatted_address'] : "";
			if($lati && $longi && $formatted_address){
				$data_arr = array($lati, $longi, $formatted_address);
				array_push($results,$data_arr);
			}
			else{
				return false;
			}

		}
		else{
			echo "<strong>ERROR: {$resp['status']}</strong>";
			return false;
		}
	echo json_encode($results);
?>