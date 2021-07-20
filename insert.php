<?php
require_once("config/db.php");
$db = new Database();

// since its a small json file we could read the same using file_get_contnets

$eventJson = file_get_contents("Events.json");
// Convert to array 
$eventArray = json_decode($eventJson, true);
if (!empty($eventArray)) {
    foreach ($eventArray as $eventData) {
        $employeeExistQuery = "SELECT id FROM employees WHERE email = '".$db->escape($eventData['employee_mail'])."'";
        $employeeExistResult = $db->query($employeeExistQuery);
        if ($employeeExistResult->num_rows > 0) {
            $employeeID = $employeeExistResult->fetch_row()[0];
        } else {
            
            $insertEmployeeQuery = "INSERT INTO employees(name, email) VALUES ('".$db->escape($eventData['employee_name'])."', '".$db->escape($eventData['employee_mail'])."')";
            if($db->query($insertEmployeeQuery) === TRUE){
                $employeeID = $db->insertId();

            }
        }

        $eventExistQuery = "SELECT id FROM events WHERE event_id = ".$db->escape($eventData['event_id']);
        $eventExistResult = $db->query($eventExistQuery);
        if ($eventExistResult->num_rows > 0) {
            $eventId = $eventExistResult->fetch_row()[0];
        } else {
            $insertEventQuery = "INSERT INTO events(name, event_id) VALUES ('".$db->escape($eventData['event_name'])."', ".$db->escape($eventData['event_id']).")";
            if($db->query($insertEventQuery) === TRUE){
                $eventId = $db->insertId();

            }
        }
        if (isset($eventId)) {
            $eventVersionExistQuery = "SELECT id FROM event_versions WHERE event_id = ".$eventId." AND event_version = '".$db->escape($eventData['version'])."' AND event_date = '".$db->escape($eventData['event_date'])."'";
            $eventVersionExistResult = $db->query($eventVersionExistQuery);
            if ($eventVersionExistResult->num_rows > 0) {
                $eventVersionId = $eventVersionExistResult->fetch_row()[0];
            } else {
                echo $insertEventVersionQuery = "INSERT INTO event_versions (event_id, participation_fee, event_version, event_date) VALUES ('".$eventId."', '".$db->escape($eventData['participation_fee'])."', '".$db->escape($eventData['version'])."', '".$db->escape($eventData['event_date'])."')";
                if($db->query($insertEventVersionQuery) === TRUE){
                    $eventVersionId = $db->insertId();
                }
            }

            if (isset($eventVersionId) && isset($employeeID)) {
                echo $eventParticipationExistQuery = "SELECT id FROM participations WHERE event_version_id = ".$eventVersionId." AND employee_id = '".$employeeID."'";
                $eventParticipationExistResult = $db->query($eventParticipationExistQuery);
                if ($eventParticipationExistResult->num_rows > 0) {
                    $eventParticipationId = $eventParticipationExistResult->fetch_row()[0];
                } else {
                    $insertEventParticipationQuery = "INSERT INTO participations (event_version_id, employee_id) VALUES ('".$eventVersionId."', '".$employeeID."')";
                    if($db->query($insertEventParticipationQuery) === TRUE){
                        $eventParticipationId = $db->insertId();
                    }
                }
            }

        }
    }

}

echo " Data inserted successfully";
// print_r($eventArray); // pr
?>