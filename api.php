<?php
/**
 * Created by PhpStorm.
 * User: mmd
 * Date: 16.10.14
 * Time: 13:38
 */
class API
{
    private $DB;

    public function __construct ()
    {
        include_once('dbconfig.php');
        $this->DB = $DBH;
    }

    function connectToTMS ()
    {
//        include_once('tms_dbconfig.php');
//        $this->TMS = $TMS_DBH;
    }

   public function handleMobileRequest ()
   {
       $result = array();
       switch ($_SERVER['REQUEST_METHOD']) {
           case 'GET': {
               if (isset($_GET['cmd'])) {
                   $result['cmd'] = $_GET['cmd'];
                   switch ($_GET['cmd']) {
                      case 'grp':
                       {
                           $sql = 'SELECT name FROM groups ORDER BY name';
                           $groupsArray = array();
                           foreach($this->DB->query($sql) as $row){
                               array_push($groupsArray,$row['name']);
                           }
                           $result['groups'] = $groupsArray;
                           $result['result'] = 'true';

                       }
                           break;
                       case 'tt':
                       {
                           $user_group = $_GET['user_group'];
                           $sql = "SELECT timetable.date,
                            timetable.type,
                            timetable.offset,
                            rooms.name as room,
                            lecturers.name,
                            lecturers.surname,
                            lecturers.patronymic,
                            disciplines.name as discipline
                            FROM timetable JOIN disciplines ON timetable.id_discipline=disciplines.id
                            JOIN lecturers ON timetable.id_lecturer=lecturers.id
                            JOIN rooms ON timetable.id_room = rooms.id
                            JOIN groups ON timetable.id_group = groups.id
                            WHERE groups.name LIKE '%$user_group%' ORDER BY timetable.date, timetable.offset";
                           $respArr = array();
                           foreach($this->DB->query($sql) as $row){
                               $tt_array = array();
                               $tt_array['name'] = $row['name'];
                               $tt_array['patronymic'] = $row['patronymic'];
                               $tt_array['type'] = $row['type'];
                               $tt_array['room'] = $row['room'];
                               $tt_array['offset'] = $row['offset'];
                               $tt_array['surname'] = $row['surname'];
                               $tt_array['discipline'] = $row['discipline'];
                               $tt_array['date'] = $row['date'];
                               array_push($respArr,$tt_array);
                           }
                           $result['tt'] = $respArr;
                           $result['result'] = 'true';

                       }
                           break;
                       case 'tt_g':
                       {
                           $room = $_GET['room'];
                           $sql = "SELECT timetable.date,
                            timetable.type,
                            timetable.offset,
                            rooms.name as room,
                            lecturers.name,
                            lecturers.surname,
                            lecturers.patronymic,
                            disciplines.name as discipline
                            FROM timetable JOIN disciplines ON timetable.id_discipline=disciplines.id
                            JOIN lecturers ON timetable.id_lecturer=lecturers.id
                            JOIN rooms ON timetable.id_room = rooms.id
                            JOIN groups ON timetable.id_group = groups.id
                            WHERE rooms.name LIKE '%$room%' ORDER BY timetable.date, timetable.offset";
                           $respArr = array();
                           foreach($this->DB->query($sql) as $row){
                               $tt_array = array();
                               $tt_array['name'] = $row['name'];
                               $tt_array['patronymic'] = $row['patronymic'];
                               $tt_array['type'] = $row['type'];
                               $tt_array['room'] = $row['room'];
                               $tt_array['offset'] = $row['offset'];
                               $tt_array['surname'] = $row['surname'];
                               $tt_array['discipline'] = $row['discipline'];
                               $tt_array['date'] = $row['date'];
                               array_push($respArr,$tt_array);
                           }
                           $result['tt'] = $respArr;
                           $result['result'] = 'true';

                       }
                           break;
                   }
               }
                           else
                               $result['result'] = 'false';
               echo json_encode($result);
           }
               break;
           case
           'POST': {
               if (isset($_POST['cmd'])) {
                   $result['cmd'] = $_POST['cmd'];
                   switch ($_POST['cmd']) {
                       case 'apns':
                       {
                           if ($this->userExists($_POST['user_id']) && isset($_POST['user_apns_token'])) {
                               $user_id = $_POST['user_id'];
                               $data = array($_POST['user_apns_token'],$user_id);
                               $STH = $this->DB->prepare('UPDATE users SET user_apns_token = (?) WHERE user_id = (?)');
                               $STH->execute($data);
                               $result['result'] = 'true';
                           }
                           else
                               $result['result'] = 'false';
                       }
                           break;
                       case 'gcm':
                       {
                           if ($this->userExists($_POST['user_id']) && isset($_POST['user_gcm_token'])) {
                               $user_id = $_POST['user_id'];
                               $data = array($_POST['user_gcm_token'],$user_id);
                               $STH = $this->DB->prepare('UPDATE users SET user_gcm_token = (?) WHERE user_id = (?)');
                               $STH->execute($data);
                               $result['result'] = 'true';
                           }
                           else
                               $result['result'] = 'false';
                       }
                           break;
                       default: { //неизвестная команда
                       $result['result'] = "false";
                       }
                       break;
                   }

                   echo json_encode($result);
               } else
                   http_response_code(400); //bad request

           }
               break;
       }
   }

}

$API = new API();
$API->handleMobileRequest();

?>