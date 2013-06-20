<?php		
	if(isset($_GET['r'])) { 
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
		showResume($resumeID);			// showing pdf file
		exit(0);
	 } 
	} else { // redirecting to error page
		 ob_start();
		 header("location: index.php"); 
		 ob_end_flush();		//now the headers are sent
	}
	
	
	
	/*function inserResumeID($resumeID, $mobNum) {	
		// insert id in database
		include ('connection.php');		
		$db = new Connection();
		$db -> dbConnect();

		if(! get_magic_quotes_gpc() ) {
			$rid = addslashes ($resumeID);
		} else	{
			$rid = $_GET['r'];
		}

		$query = "INSERT INTO smsids (smsid";
		if(!empty($mobNum) ){
		     $query .= ", mobile_no";
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
	
	*/
	
	function inserResumeID($resumeID, $mobNum) {			
		include ('connection.php');		// Load DB connectivity
		
		$db = new Connection();
		$db -> dbConnect();

		// manipulation of field value before insert
		if(! get_magic_quotes_gpc() ) {
			$rid = addslashes ($resumeID);
		} else	{
			$rid = $_GET['r'];
		}

		//$query = "INSERT INTO smsids (smsid) VALUES ('$rid')";
		$query = "INSERT INTO smsids (smsid";		
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
	
	function showResume($resumeID) {
		$pdfname = 'CV'.$resumeID.'.pdf';		// initialize respective pdf file from archives;

		$filename = 'archives/'.$pdfname;		// setup filepath
		$today = date("dmYHis");		// obtain current timestamp for labeling with file (incase for download) 

		// setting response header
		header("Pragma: no-cache"); 
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header('Content-disposition: attachment; filename=resume_'.$today.'.pdf');
		header("Content-Type: application/pdf");
		header("Content-Transfer-Encoding: binary");
		
		readfile($filename);		// show file
	}
?>
