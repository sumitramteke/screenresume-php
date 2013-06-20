<?php
/*
 *    author         : sumit ramteke
 *    date           : 13-06-2013 11:30 PM
 *    description    : it returns json object of those messages which are to be send  
 */
     include ('connection.php');		// Load DB connectivity
     $db = new Connection();
	$db -> dbConnect();
	
	$query = "SELECT msgid, mobile_no, msg".         // fetch all not send messages w.r.t current date time 
	          " FROM tbl_msg_dropbox WHERE send_flag = 0 AND send_dt_time <= NOW()";
	          
	$result = $db -> fetch($query);	// obtain resultset from DB
	
     $json = array();         // empty json object
	while($row = mysql_fetch_array($result)) {        // iteration of resultset
	     //echo $row['msg'];
	     $bus = array(       
                         'msgid' => $row['msgid'],
                         'mob' => $row['mobile_no'],        // insert a single row in array object
                         'msg' => utf8_encode($row["msg"])
          );
          array_push($json, $bus);	          // insert that slave array in master array
	}
	
	$db -> dbDisconnect();
	
	$jsonstring = json_encode($json);       // encode master array as json object
     echo $jsonstring;        // return json object

     die();
?>
