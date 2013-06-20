<?php

class Connection //create a class for make connection
{   
    public $connectionString;
    public $dataSet;

    protected $serverName;
    protected $userName;
    protected $passCode;
    protected $dbName;
    
    function Connection() {
        $this -> connectionString = NULL;
        $this -> dataSet = NULL;
        
	    	$this -> serverName = 'localhost';
		$this -> userName = 'root';    // specify the sever details for mysql
		$this -> passCode = 'root';//'password!@#';
		$this -> dbName = 'experiments';
    }

    function dbConnect()    {
        $this -> connectionString = mysql_connect($this -> serverName
        		,$this -> userName,$this -> passCode)		// make connection with DB
        		or die ("Unable to connect to Database Server");
        mysql_select_db($this -> dbName,$this -> connectionString)	// provide DB name with connection
        		or die ("Could not select database");
        return $this -> connectionString;
    }

    function dbDisconnect() {
        $this -> connectionString = NULL;
        $this -> dataSet = NULL;
            $this -> dbName = NULL;
            $this -> hostName = NULL;
            $this -> userName = NULL;
            $this -> passCode = NULL;
    }
    
    function fetch($query) {
    	$this -> dataSet = mysql_query($query
    			,$this -> connectionString) 		// execute select query in DB and Obtain Resultset
	    		or die ("Invalid query: " . MYSQL_ERROR());
    	return $this -> dataSet;
    }
    
    function insertQuery($query) {
    	mysql_query($query,$this -> connectionString) 		// execute insert query in DB 
    			or die('Could not enter data: ' . MYSQL_ERROR());
    	return $query;
    }

}

?>
