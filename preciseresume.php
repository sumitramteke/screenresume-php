<html>
	<head>
		<title>Evaluation Sheet</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
     	<link rel="stylesheet" href="css/style.css">
     	<link rel="stylesheet" href="css/evalStyle.css">
     	<!--[if !IE]> if needed to handle 
	     <style></style>
	     <!--<![endif]-->
	</head>
<body>
<?php
	include('connection.php');		// load DB connectivity
	
	if(isset($_GET['r']) and isset($_GET['m'])) { 
	 $resumeID = $_GET['r'];		// load resume ID		
	 $mobEncrypt = $_GET['m'];		// load mobile number if present
	 
	 if(!empty($resumeID)) {		
	 	
	 	$mobNum = !empty($mobEncrypt)		// decryption of mobile number
	 				?join(array_map(function ($n) { return sprintf('%03d', $n)-100; },unpack('C*', $mobEncrypt)))
	 				:null; 
          $mobNum = !empty($mobNum)		// check for valid 10 digit num
          			?(strlen($mobNum) == 10 ? $mobNum : null) 
          			: null;
		
		inserResumeID($resumeID, $mobNum);	// insert entry of clicked resume in database with person contacts		
		showEvaluatedResume($resumeID, $mobNum);			// showing pdf file
		exit(0);
	 } 
	} else { // redirecting to error page
		 ob_start();
		 header("location: index.php"); 
		 ob_end_flush();		//now the headers are sent
	}
	
function inserResumeID($resumeID, $mobNum) {
     // insert log in database
     $db = new Connection();
		$db -> dbConnect();

		// manipulation of field value before insert
		if(! get_magic_quotes_gpc() ) {
			$rid = addslashes ($resumeID);
		} else	{
			$rid = $_GET['r'];
		}

		//$query = "INSERT INTO smsids (smsid) VALUES ('$rid')";
		$query = "INSERT INTO evalids (rid";		
		if(!empty($mobNum) ){
		     $query .= ", mobile_no";		// concatenate mobile_no if present
		};
		$query .= ") VALUES ('$rid'";
		
		if(!empty($mobNum)){
		     $query .= ", '$mobNum'";
		}
		$query .= ")";
		//echo $query;
		
		$db -> insertQuery($query);
		$db -> dbDisconnect();
}
	
function showEvaluatedResume($resumeID, $mobNum){	
	$db = new Connection();
	$db -> dbConnect();

	if(! get_magic_quotes_gpc() ) {
		$rid = addslashes ($resumeID);
		$mob = addslashes ($mobNum);
	} else	{
		$rid = $resumeID;
		$mob = $mobNum;
	}

	$query = "SELECT rid, name, contact, total_exp, relv_exp, curr_comp, desgn,".	// fetch info from DB of specific Resume ID
			" key_skills, project_dtl, comm_skills, curr_ctc, exp_ctc, edu_detail,".
			" stability, loc_pref, notice_period FROM evaluation_detail WHERE rid='$rid'";
	// echo $query;
	$result = $db -> fetch($query);	// obtain resultset from DB
?>
	<table>
	<tr><td colspan="2">Candidate Evaluation Sheet</td></tr>


<?php

	while($row = mysql_fetch_array($result)) {		// fetch the info
			
?>
     <tr><th>Name</th><td><?php echo $row['name']; ?></td></tr>
     <tr><th>Contact Number</th><td><?php echo $row['contact']; ?></td></tr>
     <tr><th>Total Experience</th><td><?php echo $row['total_exp']; ?></td></tr>
     <tr><th>Relevant Experience</th><td><?php echo $row['relv_exp']; ?></td></tr>
     <tr><th>Current Company</th><td><?php echo $row['curr_comp']; ?></td></tr>
     <tr><th>Designation</th><td><?php echo $row['desgn']; ?></td></tr>
     <tr><th>Key Skills</th><td><?php echo $row['key_skills']; ?></td></tr>
<?php
          if(!empty($row['project_dtl'])) {
?>
     <tr><th>Project Details</th><td><?php echo $row['project_dtl']; ?></td></tr>
<?php
          }
?>
     <tr><th>Communication Skills</th><td><?php echo $row['comm_skills']; ?></td></tr>
     <tr><th>Current CTC</th><td><?php echo $row['curr_ctc']; ?></td></tr>
     <tr><th>Expected CTC</th><td><?php echo $row['exp_ctc']; ?></td></tr>
     <tr><th>Education Detail</th><td><?php echo $row['edu_detail']; ?></td></tr>
     <tr><th>Stability</th><td><?php echo $row['stability']; ?></td></tr>
     <tr><th>Location Preference</th><td><?php echo $row['loc_pref']; ?></td></tr>
     <tr><th>Notice Period</th><td><?php echo $row['notice_period']; ?></td></tr>
     <tr><td colspan="2">
          <a href="http://192.168.111.78/screenresume/showresume.php?<?php echo 'r='.$rid.'&m='.$mob; ?>" 
               style="color:#333;font-weight: bold;padding-left: 15%; ">Click here for Full CV</a>
         </td></tr>
		
<?php	
	}         // close while loop
	$db -> dbDisconnect();
?>
	</table>
<?php
}         // close showEvaluatedResume
?>
</body>
</hmtl>
