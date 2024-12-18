<?php

class DBController extends mysqli {
    private $host, $schema, $username, $password;
    public static $instance;

    function __construct() {
        $this->host = "localhost";
        $this->schema = "GQM";
        $this->username = "";
        $this->password = "";

        parent::__construct($this->host, $this->username, $this->password, $this->schema);

        if ($this->connect_error) {
            throw new Exception("Connection to the MySQL server failed: " . $this->connect_error . ".");
        }
    }

    public static function getInstance() {
        if (!self::$instance instanceof self) {
            try {
                self::$instance = new self;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        self::$instance->query("SET CHARACTER SET 'utf8'");

        return self::$instance;
    }

    public static function destroyInstance() {
        if (self::$instance instanceof self) {
            self::$instance->close();

            self::$instance = null;
        }
    }
}
