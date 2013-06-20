<?php
/*
 *   author         : sumit ramteke
 *   date           : 13-06-2013 12:20 PM
 *   description    : It set 'send_flag' value as '0' whose messages are send successfully. It accepts 'msgid' as request parameter
 */

/*
     echo "msgid = ".$_GET['msgid'];
/**/
     if(isset($_GET['msgid']) and !empty($_GET['msgid'])) {
          
          include ('connection.php');		// Load DB connectivity
          $db = new Connection();
		$db -> dbConnect();
		
		// manipulation of field value before insert
		if(! get_magic_quotes_gpc() ) {
			$msgid = addslashes ($_GET['msgid']);
	     } else {
	          $msgid = $_GET['msgid'];
	     }
	      
	     $query="UPDATE tbl_msg_dropbox SET send_flag=1 WHERE msgid='$msgid'";
	     $db -> insertQuery($query);        // set flag as 1 in database
	     
	     $query = "SELECT rid, mobile_no, name". 
	               " FROM tbl_msg_dropbox WHERE msgid = '$msgid' AND send_flag=1";
	     $result = $db -> fetch($query);	// obtain resultset from DB of that msgid
	     
	     while($row = mysql_fetch_array($result)) {        // iteration of resultset
	          $rid = $row['rid'];
	          $mob = $row['mobile_no'];
	          $encodeName = urlencode($row['name']);
	     }
	     $db -> dbDisconnect();
	     // redirect info for logging message sent time to DB
	     // echo "savemsgsentlog.php?r=$rid&m=$mob&n=$encodeName";
	     ob_start();
	     header("location: savemsgsentlog.php?r=$rid&m=$mob&n=$encodeName"); 
	     ob_end_flush();		//now the headers are sent		
     
     } else { // redirecting to error page
	     ob_start();
	     header("location: index.php"); 
	     ob_end_flush();		//now the headers are sent
     }
/**/
?>
