<?php
class Database {

  function __construct() {
    $this->host = 'localhost';
    $this->database = 'eventmanagement';
    $this->username = 'USERNAME';
    $this->password = 'PASSWORD';
    $this->username = 'phpmyadmin';
    $this->password = 'mysql';
    $this->port = '3306';
    $this->open();
  }

  function open() {
    $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database, $this->port);
  }


  function close() {
    $this->connection->close();
  }

  function query($query) {
    return $this->connection->query($query);
  }

  function escape($string) {
    return $this->connection->escape_string($string);
  }

  function insertId() {
    return $this->connection->insert_id;
  }
}
?>