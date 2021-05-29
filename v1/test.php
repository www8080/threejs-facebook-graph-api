<?php
// https://graph.facebook.com/v10.0/101200052181927/friends?access_token=783263342388998|dkQ1iavY-DR9E25zXOlKmU_6wpk&pretty=1&limit=100&debug=all&fields=id,name,gender,first_name,birthday
ini_set('display_errors', 1);
error_reporting(E_ALL);

//GET Facebook Graph API Response--------------------------------------------------------------------------------------------------
$debug_mode   = 'all';
$app_ver      = 'v10.0';
$access_token = '783263342388998|dkQ1iavY-DR9E25zXOlKmU_6wpk';
$fields       = 'id,name,gender,first_name,birthday';
$user_id      = '101200052181927';
$pretty       = 1;
$limit        = 100;
$api_url      = 'https://graph.facebook.com/'.$app_ver.'/'.$user_id.'/friends?access_token='.$access_token.'&pretty='.$pretty.'&limit='.$limit.'&debug='.$debug_mode.'&fields='.$fields;

//Initiate CURL request
try {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $api_url);
    $result = curl_exec($ch);
    curl_close($ch);
}
catch(Exception $e){
    throw new Exception("Error",0,$e);
    exit;
}

if($result) {
  $obj      = json_decode($result, true);
  //$obj_arr  = array_values(json_decode($obj, true));
  //echo $obj->data[1]->first_name; 
  //dd($obj);

  // foreach ($obj as $key => $friend) {
  //   echo $friend['data']['first_name'];
  // }

  foreach($obj['data'] as $friend) {
    $friend_age    = @ageCalculator($friend['birthday']) ?? 'null';
    $friend_gender = ($friend['gender'] == "male") ? 'M' : 'F';
    $friend_arr[]  = $friend['first_name'] . "," . $friend_age . "," . $friend_gender;

    echo $friend['first_name'] . "," . $friend_age . "," . $friend_gender . "<br>";
  }
  $friend_arr_js   = json_encode($friend_arr);

  //dd($friend_arr);
  dd($friend_arr_js);

  echo "<br>Total Friend: " . sizeof($obj['data']);

}

//END Facebook Graph API Response--------------------------------------------------------------------------------------------------


//Functions -----------------------------------------------------------------------------------------------------------------------
function ageCalculator($dob){
    if(!empty($dob)){
        $birthdate = new DateTime($dob);
        $today   = new DateTime('today');
        $age = $birthdate->diff($today)->y;
        return $age;
    }else{
        return 0;
    }
}
//Debug------------------------
//$dob = '01/01/2002';
//echo "Age: " . ageCalculator($dob);
// echo "Age: ". date_diff(date_create('01/07/2002'), date_create('today'))->y;
//END Debug--------------------

 function dd()
  {
      echo '<pre>';
      array_map(function($x) {var_dump($x);}, func_get_args());
      die;
  }

//END Function ----------------------------------------------------------------------------------------------------------------------


//Using JDK -------------------------------------------------------------------------------------------------------------------------
// //define('FACEBOOK_SDK_V4_SRC_DIR', __DIR__ . '../vendor/facebook-sdk-v5/');
// require_once  '../vendor/php-graph-sdk/src/Facebook/autoload.php';

// $fb = new \Facebook\Facebook([
//   'app_id' => '{783263342388998}',
//   'app_secret' => '{2a475f11d357f7752f34c10f7107ccb0}',
//   'default_graph_version' => 'v10.0',
//   'default_access_token' => '{783263342388998|dkQ1iavY-DR9E25zXOlKmU_6wpk}', // optional
//   'enable_beta_mode' => false,
//   'http_client_handler' => 'curl',
// ]);


// try {
//   // Get the \Facebook\GraphNodes\GraphUser object for the current user.
//   // If you provided a 'default_access_token', the '{access-token}' is optional.
//   $response = $fb->get('/me', '{783263342388998|dkQ1iavY-DR9E25zXOlKmU_6wpk}');
// } catch(\Facebook\Exceptions\FacebookResponseException $e) {
//   // When Graph returns an error
//   echo 'Graph returned an error: ' . $e->getMessage();
//   dd($e);
//   exit;
// } catch(\Facebook\Exceptions\FacebookSDKException $e) {
//   // When validation fails or other local issues
//   echo 'Facebook SDK returned an error: ' . $e->getMessage();
//   exit;
// }

// $me = $response->getGraphUser();
// echo 'Logged in as ' . $me->getName();
// //dd($me->getName());
//END Using JDK ---------------------------------------------------------------------------------------------------------------------
?>