<?php

class DatabaseConnection
{

    private $database;

    private $databaseName;

    private $host;

    private $userName;

    private $password;

    private $driver;

    protected function openConnection()
    {
        $config = include 'config.php';
        $this->driver = $config['driver'];
        $this->databaseName = $config['database_name'];
        $this->userName = $config['user_name'];
        $this->host = $config['host'];
        $this->password = $config['password'];
        try {
            if ($this->driver == 'sqlite') {
                $this->database = new PDO('sqlite:' . $this->databaseName);
            } elseif ($this->driver == 'sqlsrv') {
                $this->database = new PDO("sqlsrv:Server=" . $this->host . ";Database=" . $this->databaseName, $this->userName, $this->password);
            } elseif ($this->driver == 'mysql') {
                $this->database = new PDO("mysql:host=$this->host;dbname=" . $this->databaseName, $this->userName, $this->password);
            } else {
                $this->database = new PDO('sqlite:' . $this->databaseName);
            }
            $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    protected function Fetch_Data($query, $method, $page)
    {
        try {
            $data = $this->database->query($query);
            return $data->fetchAll();
        } catch (Exception $e) {
            $exc = new FileException();
            $exc->writeexception($e, $page, $method);
        }
    }

    protected function Execute_Data($query, $method, $page)
    {
        try {
            $execute = 0;
            $execute = $this->database->exec($query);
            return $execute;
        } catch (Exception $e) {
            $exc = new FileException();
            $exc->writeexception($e, $page, $method);
        }
    }

    protected function closeConnection()
    {
        $this->database = null;
    }
}

class WebStatistics extends DatabaseConnection
{

    private $tableName = 'Analytics';

    public $id;

    public $googleAnalytics;

    public $positiveGuys;

    public $month;

    public function __construct()
    {
        $this->openConnection();
    }

    public function read()
    {
        /* reading data from database */
        $array = $this->Fetch_Data("select * from " . $this->tableName . " where month='" . $this->month . "'", 'Read Web Statistics', '');
        return $array;
    }

    public function insert()
    {
        /* inserting data into database */
        $query = "Insert Into " . $this->tableName . "(googleAnalytics,positiveGuys) values('" . $this->googleAnalytics . "','" . $this->positiveGuys . "')";
        $execute = $this->Execute_Data($query, 'Insert Web Statistics', '');
        return $execute;
    }

    public function update()
    {
        /* updating data to database */
        $query = "Update " . $this->tableName . " set googleAnalytics='" . $this->googleAnalytics . "',positiveGuys='" . $this->positiveGuys . "' where id='" . $this->id . "'";
        $execute = $this->Execute_Data($query, 'Update Web Statistics', '');
        return $execute;
    }

    public function __destruct()
    {
        $this->closeConnection();
    }
}

class FileException
{

    public function writeexception($e, $page, $methodname)
    {
        try {
            $isLocal = false;
            if ($isLocal == true) {
                $string_to_write = '-----------------';
                file_put_contents('exceptions.txt', "\n" . $string_to_write . PHP_EOL, FILE_APPEND | LOCK_EX);
                $string_to_write = 'Date Time:' . date('Y-m-d H:i:s') . '  Page:' . $page . '  Fn:' . $methodname;
                file_put_contents('exceptions.txt', "\n" . $string_to_write . PHP_EOL, FILE_APPEND | LOCK_EX);
                $string_to_write = 'Exception Trace:' . $e->getTraceAsString();
                file_put_contents('exceptions.txt', "\n" . $string_to_write . PHP_EOL, FILE_APPEND | LOCK_EX);
                $string_to_write = 'Exception Message:' . $e->getMessage();
                file_put_contents('exceptions.txt', "\n" . $string_to_write . PHP_EOL, FILE_APPEND | LOCK_EX);
                $string_to_write = '-----------------';
                file_put_contents('exceptions.txt', "\n" . $string_to_write . PHP_EOL, FILE_APPEND | LOCK_EX);
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
}
?>