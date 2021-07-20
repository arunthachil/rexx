<?php
if(isset($_POST) && !empty($_POST)){
  ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    require_once("config/db.php");
    $db = new Database();
    $whereQuery = '';
    if (isset($_POST['searchByEmployeeName']) && !empty($_POST['searchByEmployeeName'])) {
      $whereQuery .= $whereQuery != '' ? ' AND ' : ' WHERE ';
      $whereQuery .= "emp.name like '%".$db->escape($_POST['searchByEmployeeName'])."%' ";
    }
    if (isset($_POST['searchByEventName']) && !empty($_POST['searchByEventName'])) {
      $whereQuery .= $whereQuery != '' ? ' AND ' : ' WHERE ';
      $whereQuery .= "e.name like '%".$db->escape($_POST['searchByEventName'])."%' ";
    }
    if (isset($_POST['searchByEventDate']) && !empty($_POST['searchByEventDate'])) {
      $whereQuery .= $whereQuery != '' ? ' AND ' : ' WHERE ';
      $whereQuery .= "date(ev.event_date) = '".date('Y-m-d',strtotime($db->escape($_POST['searchByEventDate'])))."' ";
    }
    $employeeExistQuery = "(SELECT emp.name as employee_name, emp.email as employee_email, e.name as event_name,DATE_FORMAT(ev.event_date,'%D %b %Y') as event_date,ev.event_version as version, round(ev.participation_fee,2) as participation_fee FROM employees as emp join participations as p on p.employee_id = emp.id join event_versions as ev on ev.id = p.event_version_id join events as e on ev.event_id = e.id ".$whereQuery.")  union (SELECT 'Total' as employee_name, '' as employee_email, '' as event_name, '' as event_date, '' as version, round(sum(participation_fee),2) as participation_fee FROM employees as emp join participations as p on p.employee_id = emp.id join event_versions as ev on ev.id = p.event_version_id join events as e on ev.event_id = e.id ".$whereQuery.")";
    $employeeExistResult = $db->query($employeeExistQuery);
    $eventData = [];
    if (!empty($employeeExistResult)) {
      while($row = $employeeExistResult->fetch_array()){
        $eventData[] = $row;
      }
    }
    echo json_encode($eventData);exit;
}
// if(isset($_POST) && !empty($_POST)){
    
//     if ($employeeExistResult->num_rows > 0) {
//         $employeeID = $employeeExistResult->fetch_row()[0];
//     }
//     print_r($_POST);exit;
// }
?>
<link href='https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="index.js"></script>
<div >
<!-- Custom Filter -->
<form name="filterForm" id="filterForm">
<table>
  <tr>
    <td>
      <input type='text' name="searchByEmployeeName" class="filterData" id='searchByEmployeeName' placeholder='Enter Employee Name'>
    </td>
    <td>
      <input type='text' name="searchByEventName" class="filterData" id="searchByEventName" placeholder='Enter Event Name'>
    </td>
    <td>
      <input type='date' name="searchByEventDate" class="filterData" id="searchByEventDate" placeholder='Enter Event Date'>
    </td>
    <td>
      <button type='button' class="filterButton dt-button buttons-default" id='filterButton'>Filter</button>
    </td>
  </tr>
</table>
</form>
<button style="float:right;width:10%" id="insertData">Insert Data</button>
<button style="float:right;width:10%;margin-right:15px" id="createTable">Create Table</button>
<!-- Table -->
<table id='empTable' class='display dataTable'>
  <thead>
    <tr>
      <th>Employee name</th>
      <th>Employee Email</th>
      <th>Event Name</th>
      <th>Event Date</th>
      <th>Event Version</th>
      <th>Participation Fee</th>
    </tr>
  </thead>
  <tbody id="tableBody">
    <tr>
      <td colspan="6">No Data Available</td>
    </tr>
  </tbody>
</table>
</div>