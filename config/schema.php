<?php
require_once("db.php");
$db = new Database();

$employee_sql = "CREATE TABLE employees(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(30) NOT NULL,
    email VARCHAR(70) NOT NULL UNIQUE
)";
$db->query($employee_sql);

$event_sql = "CREATE TABLE events(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    name VARCHAR(30) NOT NULL
)";
$db->query($event_sql);

$event_versions_sql = "CREATE TABLE event_versions(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    participation_fee FLOAT NOT NULL,
    event_version TEXT NOT NULL,
    event_date DATETIME NOT NULL,
    FOREIGN KEY (event_id) REFERENCES events(id)
)";
$db->query($event_versions_sql);

$participation_sql = "CREATE TABLE participations(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    event_version_id INT NOT NULL,
    employee_id INT NOT NULL,
    FOREIGN KEY (event_version_id) REFERENCES event_versions(id),
    FOREIGN KEY (employee_id) REFERENCES employees(id)
)";
$db->query($participation_sql);
$db->close();
?>