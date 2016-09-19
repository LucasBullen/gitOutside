<?php
  error_reporting(E_ALL); ini_set('display_errors', '1');
  $dbh = null;
  $CONSUMER_KEY = 'mmLVht1-g298aTsgU2oPUw';
  $CONSUMER_SECRET = 'iVuj0D5jnRuwXmradkhE6EoYBxc';
  $TOKEN = 'Az9oQdcqm_ch8ZG-KjZ-hq82FXSBq_I9';
  $TOKEN_SECRET = 'KWJw7eCaMEojK1uBn5T0cpOqQaI';
  $API_HOST = 'api.yelp.com';
  $DEFAULT_TERM = 'dinner';
  $DEFAULT_LOCATION = 'San Francisco, CA';
  $SEARCH_LIMIT = 3;
  $SEARCH_PATH = '/v2/search/';
  $BUSINESS_PATH = '/v2/business/';
  if($_SERVER['REQUEST_METHOD']=='GET'){
    if (isset($_GET["function"])) {
      $dbh = connectToDB();
      $requiredParam = array();
      $response = "";
      switch ($_GET["function"]) {
        case 'createUser':
          $requiredParam = array("name","age");
          if(checkParam($requiredParam)){
            $response = createUser($_GET["name"],$_GET["age"]);
          }
          break;
        case 'createEvent':
          $requiredParam = array("sport","start","end","parkID","userID","parkName","city");
          if(checkParam($requiredParam)){
            $response = createEvent($_GET["sport"],$_GET["start"],$_GET["end"],$_GET["parkID"],$_GET["userID"],$_GET["parkName"],$_GET["city"]);
          }
          break;
        case 'setAttendance':
          $requiredParam = array("userID","eventID","status");
          if(checkParam($requiredParam)){
            $response = setAttendance($_GET["userID"],$_GET["eventID"],$_GET["status"]);
          }
          break;
        case 'getParks':
          $requiredParam = array("city");
          if(checkParam($requiredParam)){
            $response = getParks($_GET["city"]);
          }
          break;
        case 'getEvents':
          $requiredParam = array("city");
          if(checkParam($requiredParam)){
            $response = getEvents($_GET["city"]);
          }
          break;
        case 'getUserByID':
          $requiredParam = array("userID");
          if(checkParam($requiredParam)){
            $response = getUserByID($_GET["userID"]);
          }
          break;
        case 'getEventByID':
          $requiredParam = array("eventID");
          if(checkParam($requiredParam)){
            $response = getEventByID($_GET["eventID"]);
          }
          break;
        case 'getRestaurants':
          $requiredParam = array("city");
          if(checkParam($requiredParam)){
            $response = getRestaurants($_GET["city"]);
          }
          break;
        default:
          break;
      }
      echo json_encode($response);
    }
  }
function connectToDB(){
  try {
    return new PDO('pgsql:host=localhost;port=26257;dbname=otterpolo;sslmode=disable',
      'root', null, array(
        PDO::ATTR_ERRMODE          => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => true,
    ));

    // print "Account balances:\r\n";
    // foreach ($dbh->query('SELECT id, balance FROM accounts') as $row) {
    //     print $row['id'] . ': ' . $row['balance'] . "\r\n";
    // }
  } catch (Exception $e) {
      $error = $e->getMessage() . "\r\n";
      $json = array(
          'status' => 500,
          'error' => $error
      );
      $jsonstring = json_encode($json);
      echo $jsonstring;
  }
}

function createUser($name, $age){
  global $dbh;
  $status = 200;
  $id = '';
  
  try { 
    $statement = $dbh->prepare("INSERT INTO users(name, age)
    VALUES(?, ".$age.")");
    $statement->execute(array($name));
    $user = array();
    foreach ($dbh->query("SELECT id FROM users WHERE name = '".$name."' AND age = ".$age) as $row) {
      $user = $row;
    }
  } catch(PDOExecption $e) { 
    $dbh->rollback(); 
    $status = 500; 
  } 
  $json = array(
    'status' => $status,
    'id' => $user['id']
  );
  return $json;
}

function createEvent($sport, $start, $end, $parkID, $userID, $parkName, $city){
  global $dbh;
  $status = 200;
  $id = null;
  
  try { 
    $statement = $dbh->prepare("INSERT INTO events(sport, start_time, end_time)
    VALUES(?, ?, ?)");
    $statement->execute(array($sport, $start, $end));

    $event = array();
    foreach ($dbh->query("SELECT id FROM events WHERE sport = '".$sport."' AND start_time = '".$start."' AND end_time = '".$end."'") as $row) {
      $event['id'] = $row['id'];
    }
    //if park use, else make and use

    $parks = array();
    foreach ($dbh->query("SELECT id FROM parks WHERE cadian_id = ".$parkID) as $row) {
      array_push($parks, $row);
    }
    if (count($parks)<=0) {
      $statement = $dbh->prepare("INSERT INTO parks(cadian_id, name, city)
      VALUES(".$parkID.", ?, ?)");
      $statement->execute(array($parkName, $city));
      foreach ($dbh->query("SELECT id FROM parks WHERE cadian_id = ".$parkID) as $row) {
        array_push($parks, $row);
      }
    }

    $statement = $dbh->prepare("INSERT INTO park_event_rel(parkID, eventID)
    VALUES(".$parks[0]['id'].", ".$event['id'].")");
    $statement->execute();

    $statement = $dbh->prepare("INSERT INTO user_event_rel(userID, eventID)
    VALUES(".$userID.", ".$event['id'].")");
    $statement->execute();

  } catch(PDOExecption $e) { 
    $dbh->rollback(); 
    $status = 500; 
  } 
  $json = array(
    'status' => $status,
    'id' => $event['id']
  );
  return $json;
}

function setAttendance($userID,$eventID,$status){
  global $dbh;
  $status_fnc = 200;
  
  try { 
    if ($status == 0) {
      $statement = $dbh->prepare("DELETE FROM user_event_rel WHERE userID = ".$userID." AND eventID = ".$eventID);
      $statement->execute();
      $status_fnc = 201;
    }else{
      $rows = 0;
      foreach ($dbh->query('SELECT * FROM user_event_rel WHERE userID = '.$userID.' AND eventID = '.$eventID) as $row) {
        $rows += 1;
      }
      if ($rows == 0) {
        $statement = $dbh->prepare("INSERT INTO user_event_rel(userID, eventID)
        VALUES(".$userID.", ".$eventID.")");

        $statement->execute();
      }
    }
  } catch(PDOExecption $e) { 
    $dbh->rollback(); 
    $status_fnc = 500; 
  } 
  $json = array(
    'status' => $status_fnc
  );
  return $json;
}

function getEvents($city){
  global $dbh;
  $status = 200;
  $events = array();
  foreach ($dbh->query("SELECT id, cadian_id, name FROM parks WHERE city = '".$city."'") as $row) {
    foreach ($dbh->query("SELECT eventID FROM park_event_rel WHERE parkID = ".$row['id']) as $rel) {
      $guests = 0;
      foreach ($dbh->query("SELECT * FROM user_event_rel WHERE eventID = ".$rel['eventID']) as $guest) {
        $guests +=1;
      }
      foreach ($dbh->query("SELECT id, sport, start_time, end_time FROM events WHERE id = ".$rel['eventID']) as $event) {
        $info = array('id' => (string)$event['id'], 'sport' => $event['sport'], 'start_time' => $event['start_time'], 'end_time' => $event['end_time'], 'cadian_id' => $row['cadian_id'], 'users' => $guests, 'parkName' => $row['name'], 'eventID' => (string)$rel['eventID']);
        array_push($events, $info);
      }
    }
  }

  $json = array(
    'status' => $status,
    'events' => $events
  );
  return $json;
}

function getParks($city){
  global $dbh;
  $status = 200;
  $parks = array();
  foreach ($dbh->query("SELECT id, cadian_id, name, city FROM parks WHERE city = '".$city."'") as $row) {
    $park = array('id' => $row['id'], 'cadian_id' => $row['cadian_id'], 'name' => $row['name'], 'city' => $row['city']);
    array_push($parks, $park);
  }

  $json = array(
    'status' => $status,
    'parks' => $parks
  );
  return $json;
}

function getUserByID($id){
  global $dbh;
  $status = 200;

  foreach ($dbh->query('SELECT id, name FROM users WHERE id = '.$id) as $row) {
      $json = array(
        'status' => $status,
        'id' => $row['id'],
        'name' => $row['name']
      );
      return $json;
  }
  $json = array(
    'status' => 500
  );
  return $json;
}

function getEventByID($id){
  global $dbh;
  $status = 200;

  foreach ($dbh->query('SELECT id, sport, start_time, end_time FROM events WHERE id = '.$id) as $row) {
      $json = array(
        'status' => $status,
        'id' => $row['id'],
        'sport' => $row['sport'],
        'start_time' => $row['start_time'],
        'end_time' => $row['end_time']
      );
      return $json;
  }
  $json = array(
    'status' => 500
  );
  return $json;
}

function getRestaurants($city){
  $status = 200;
  require_once('yelp.php');
  $businesses = query_api("food", $city);
  $json = array(
    'status' => $status,
    'restaurants' => $businesses
  );
  return $json;
}

function checkParam($params){
  foreach ($params as $param) {
    if (!isset($_GET[$param])) {
      $error =  "Error: missing ".$param." parameter";
      $json = array(
          'status' => 500,
          'error' => $error
      );
      $jsonstring = json_encode($json);
      echo $jsonstring;
      return False;
    }
  }
  return True;
}
?>
