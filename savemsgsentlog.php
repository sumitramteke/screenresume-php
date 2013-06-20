<?php		
	if(isset($_GET['r']) && isset($_GET['m']) && isset($_GET['n'])) { 
		 $resumeID = $_GET['r'];		// load resume ID		
		 $mobNum = $_GET['m'];		// load mobile number
		 $name = urldecode($_GET['n']);		// load clicking person name	 
		 
		 if(!empty($resumeID) && !empty($mobNum) && !empty($name)) {	
			saveMsgLog($resumeID, $mobNum, $name);	// insert entry of sent message log in database
			exit(0);
		 }
	} else { // redirecting to error page
		 ob_start();
		 header("location: index.php"); 
		 ob_end_flush();		//now the headers are sent
	}
	
	function saveMsgLog($resumeID, $mobNum, $name) {	
		include ('connection.php');		// Load DB connectivity
		
		$db = new Connection();
		$db -> dbConnect();

		// manipulation of field value before insert
		if(! get_magic_quotes_gpc() ) {
			$rid = addslashes ($resumeID);
			$mob = addslashes ($mobNum);
			$nm = addslashes ($name);			
		} else	{
			$rid = $resumeID;
			$mob = $mobNum;
			$nm = $name;
		}

		$query = "INSERT INTO sms_sent_log (mobile_no, name, smsid) VALUES ('$mobNum','$name','$resumeID')";
		//echo $query;
		
		$db -> insertQuery($query);
		$db -> dbDisconnect();
	}
?>
