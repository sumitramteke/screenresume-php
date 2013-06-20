<html>
	<head>
		<meta charset='UTF-8'>
          <title>Rezoomex Statistics</title>	
	     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     	<link rel="stylesheet" href="css/style.css">
	</head>
<body>
<div id="page-wrap">
<?php
	include('connection.php');		// load DB connectivity
	$db = new Connection();
	$db -> dbConnect();
	$query = "SELECT e.rid,t.name,e.mobile_no,".		// fetch log from DB from tables SMSIDS anD SMS_SENT_LOG of about sending and clicking event
				"CONVERT_TZ(t.date_created,'+0:00', '+5:30') sent_date, CONVERT_TZ(e.date_created,'+0:00', '+5:30') clicked_date".
			" FROM  evalids e, sms_sent_log t".
			" WHERE e.rid=t.smsid ORDER BY  e.date_created DESC";
	// echo $query;
	$result = $db -> fetch($query);	// obtain resultset from DB
	
	//date_default_timezone_set("Asia/Calcutta");
	// echo date_default_timezone_get();
?>
	<!--<table class="hovertable">--><table>
		<tr>
			<th>Resume ID</th>		
			<th>Person Name</th>
			<th>Person Contact</th>		<!-- Fields required to show -->
			<th>Sent Date</th>
			<th>Sent Time</th>
			<th>Clicked Date</th>
			<th>Clicked Time</th>
		</tr>
<?php

	while($row = mysql_fetch_array($result)) {		// iteration of resultset
		$smsid=$row['rid'];
		$name = $row['name'];
		$mob=$row['mobile_no'];
		$sent_date= new DateTime($row['sent_date']);
		$clicked_date= new DateTime($row['clicked_date']);
		
		
?>
		<tr>
			<td><?php echo $smsid; ?></td>
			<td><?php echo $name; ?></td>
			<td><?php echo $mob; ?></td>
			<td><?php echo $sent_date->format('d-M-Y'); ?></td>		<!-- showing the values according to the requirement -->
			<td><?php echo $sent_date->format('g:i A'); ?></td>			
			<td><?php echo $clicked_date->format('d-M-Y'); ?></td>
			<td><?php echo $clicked_date->format('g:i A'); ?></td>
		</tr>
<?php	
	}
	$db -> dbDisconnect();
?>
	</table>
</div>
</body>
</hmtl>
