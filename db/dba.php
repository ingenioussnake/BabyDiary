<?php
    class DBA {
        private $db = "baby_diary";
        private $user = "baby";
        private $pwd = "leed";
        private $location = "localhost";
        private $connection = null;

        public function connect () {
            $this->connection = new mysqli($this->location, $this->user, $this->pwd, $this->db);
            if ($this->connection->connect_errno) {
                echo "Failed to connect to MySQL: (" . $this->connection->connect_errno . ") " . $this->connection->connect_error;
            }
            $this->exec("set names 'utf8'");
            $this->exec("set character set 'utf8'");
            return $this->connection;
        }

        public function disconnect () {
            $this->connection->close();
            $this->connection = null;
        }

        public function exec ($stmt) {
            $result = $this->connection->query($stmt);
            if (!$result) {
                echo "Failed to run query: (" . $this->connection->errno . ") " . $this->connection->error;
            }
            return $result;
        }

        public function query ($stmt, $cb=null) {
            $result = $this->connection->query($stmt);
            if (!$result) {
                echo "Failed to run query: (" . $this->connection->errno . ") " . $this->connection->error;
                return null;
            }
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                if (!is_null($cb)) {
                    $row = $cb($row);
                }
                array_push($rows, $row);
            }
            return $rows;
        }

        public function insert_id () {
            return $this->connection->insert_id;
        }
    }
?>