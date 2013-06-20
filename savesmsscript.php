<?php
/*
 *
 *
 */
/*
echo "name: ". $_POST['name']."	 resume: ".$_POST['rid']."	 mobile: ".$_POST['mob']
."	 date: ".$_POST['sendDt']."	 message: ".$_POST['msg']."	 hyplink: ".$_POST['hyplink'];
/**/
if(isset($_POST['name']) and isset($_POST['rid']) and isset($_POST['mob']) // check if all fields are set
     and isset($_POST['sendDt']) and isset($_POST['msg']) and isset($_POST['hyplink'])){
     
    if(!empty($_POST['name']) and !empty($_POST['rid']) and !empty($_POST['mob']) // check if fields are not empty
     and !empty($_POST['sendDt']) and !empty($_POST['msg']) and !empty($_POST['hyplink'])){
     
          $name = $_POST['name'];
          $rid = $_POST['rid'];
          $mob = $_POST['mob'];
          $msgBody = $_POST['msg'] . "\n" . $_POST['hyplink'];
          
          // converting string datetime to mysql datetime
          $datetime = mysql_real_escape_string($_POST['sendDt']);
          $datetime = strtotime($datetime);
          $mysqlSendDt = date('Y-m-d H:i:s',$datetime);
          
          // process to insert info into db
          include ('connection.php');		// Load DB connectivity
          $db = new Connection();
		$db -> dbConnect();
		
		// manipulation of field value before insert
		if(! get_magic_quotes_gpc() ) {
			$name = addslashes ($name);
			$rid = addslashes ($rid);
			$mob = addslashes ($mob);
			$msgBody = addslashes ($msgBody);			
		} 
		
		$query = "INSERT INTO tbl_msg_dropbox(name, rid, mobile_no, send_dt_time, msg)".        // all required fields to insert
		          " VALUES ('$name','$rid','$mob'".
		          ",(SELECT CONVERT_TZ(  '$mysqlSendDt',  '+05:30',  '+00:00' ))".           // converting IST to DB based UTC zone
		          ",'$msgBody')";		
          $db -> insertQuery($query);
          $db -> dbDisconnect();
          
          echo "<h2 style='color:green;'>Message has been set successfully.</h2>";        // success - if everything goes well
     } else {
          echo "<h2 style='color:red;'>Some fields are missing, Please try again</h2>";   // failure - theres any empty fields given
     }

} else { // redirecting to error page
	ob_start();
	header("location: index.php"); 
	ob_end_flush();		//now the headers are sent
}
/**/

?>
