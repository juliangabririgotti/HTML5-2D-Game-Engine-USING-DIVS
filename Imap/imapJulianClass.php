<?php

function ConnectToMailBoxActionClass($hostname,$username,$password,$KeyWord,$Action,$dbhost,$dbusername,$dbuserpass,$dbname, $ActionDescription, $OpperationFlag)
{
	
	
	$myFile = "CronItteration.txt";
	$fh = fopen($myFile, 'r');
	$theData = fread($fh, filesize($myFile));
	fclose($fh);
	echo 'Script Run:'. $theData; echo 'Times';
	$theData;
	
	$myFile = "CronItteration.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$Number = $theData + 1;
	$stringData = $Number;
	fwrite($fh, $stringData);
	fclose($fh);
	
	// connect to the mysql database server.
	$dbConnection = mysql_connect ($dbhost, $dbusername, $dbuserpass);
	echo "success in database connection.";

	// select the specific database name we want to access.
	if (!mysql_select_db($dbname)) die(mysql_error());
	echo "success in database selection.";



		// add a table to the selected database
	$Query2 = "
					CREATE TABLE IF NOT EXISTS `email_book` 
					(
						TheEmailAddress VARCHAR(25),
						ItIsFrom VARCHAR(15),
						Subject VARCHAR(25),						
						TimeStamp VARCHAR(15),
						IsItReadUnread VARCHAR(15),
						Body LONGTEXT,
						FlagsAndMore VARCHAR(15),
						OppAction VARCHAR(25),
						IsItDeleted VARCHAR(25),
						CronLog VARCHAR(25),
						ActionDescription VARCHAR(25),
						ReferenceID VARCHAR(25)
					)
					
			";
	if (mysql_query($Query2))
	{
		echo "TABLE name email_book created or already exists";
	}
	else 
	{
		 echo "Error in CREATE TABLE.";
	}
	
	
	


	if($hostname !='' & $username !='' & $password !='' & $KeyWord != '' & $Action !='' & $dbhost != '' & $dbusername != '' & $dbuserpass != '' & $dbname != '' &  $ActionDescription != '')
	{
		/* try to connect */
		$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to mail server: ' . imap_last_error());
		
		//echo $inbox;
		
		/* grab emails */
		$emails = imap_search($inbox,$KeyWord);
		
		//echo'test';

		//DELETES MESSAGES BY UID (UNIQUE IDENTIFIER)
		//imap_delete($inbox, 1, FT_UID);

		/* if emails are returned, cycle through each... */
		if($emails) 
		{

			//echo emails;
			//echo $inbox;
		
			/* begin output var */
			$output = '';

			/* put the newest emails on top */
			rsort($emails);

			//echo "UID: ".$messagetuid = imap_uid($inbox, 1);
		  
			/* for every email... */
			foreach($emails as $email_number) 
			{
				//$email_number=$emails[0];
				
				// GETS EMAIL ORDER NUMBER
				//echo "email number: ". $email_number ;echo'<br>';
				
				
				/* FLAGS MESSAGES FOR DELETION BY EMAIL ORDER NUMBER */
				if($Action == 1)
				{
					imap_delete($inbox, $email_number);
					$Deleted = "Yes";
					
				
				}
				else
				{
				
					$Deleted = "No";
				
				}
				//-------------------------
				
				
				//The flags which you can set are \Seen, \Answered, \Flagged, \Deleted, and \Draft 
				//$status = imap_setflag_full($mbox, "2:5", "\\Seen \\Flagged");
				// http://php.net/manual/en/function.imap-setflag-full.php
				
				if($OpperationFlag !="")
				{
					$status = imap_setflag_full($inbox, "1,". $email_number, $OpperationFlag);
					echo gettype($status) . "\n";
					echo $status . "\n";
				}
				
				//print_r($emails);
				/* get information specific to this email */
				$overview = imap_fetch_overview($inbox,$email_number,0);
				
				/*
				()Root Message Part (multipart/related)
				(1) The text parts of the message (multipart/alternative)
				(1.1) Plain text version (text/plain)
				(1.2) HTML version (text/html)
				(2) The background stationary (image/gif) 
				(FT_PEEK) leave messages unread and return (multipart/related) does not return body
				*/
				
				$Message = imap_fetchbody($inbox,$email_number,1);

				/* output the email header information */
				$output.= 'seen / read: <div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
				$output.= 'subject: <span class="subject">'.$overview[0]->subject.'</span> ';
				$output.= 'from: <span class="from">'.$overview[0]->from.'</span>';
				$output.= 'date: <span class="date">on '.$overview[0]->date.'</span>';
				$output.= '</div><br><br>';

				
				
				
				
				/* output the email body */
				$output.= 'message: <div class="body">'.$Message.'</div><br><br>';
				
				//echo $output;
			
				$Seen = ($overview[0]->seen ? 'read' : 'unread');
				
				$Subject = $overview[0]->subject ;
				
				/*
				
				$SubjectArray = explode( 'Ref:', $Subject );
				echo $SubjectReady = $SubjectArray[1] ;
				$SubjectFinal = explode( ']', $SubjectReady );
				$SubjectPerfect = $SubjectFinal[0];
				if(is_string($SubjectPerfect)){ echo 'yes'; } 
				
				*/
				
				$SubjectPerfect = filter_var($Subject, FILTER_SANITIZE_NUMBER_INT);
				
				//if(is_int($MailIDKey)){ echo 'yes'; }
				
				$From = $overview[0]->from ; 
				$Date = $overview[0]->date ; 
				
				// Header info construct email
				$header = imap_headerinfo($inbox, $email_number); echo'<br>';echo'<br>';
				$Email = $header->from[0]->mailbox . "@" . $header->from[0]->host;
						
				echo "Good, No Results exist for this record, Ready To Attempt Insert...<br>";
				
				if($SubjectPerfect =='')
				{
				
					$SubjectPerfect = 'NO DATA FOUND';
				
				}
				
				$Query3 = 
				"
					INSERT INTO email_book 
						(
							`TheEmailAddress`,
							`ItIsFrom`,
							`Subject`,							
							`TimeStamp`,
							`IsItReadUnread`,
							`Body`,
							`FlagsAndMore`,
							`OppAction`,
							`IsItDeleted`,
							`CronLog`,
							`ActionDescription`,
							`ReferenceID`
							
						) 
					VALUES 
						(
							'".$Email.				"',
							'".$From.				"',
							'".$Subject.			"',							
							'".$Date.				"',			
							'".$Seen.				"',
							'".$Message.			"',
							'".$OpperationFlag.		"',
							'".$KeyWord.			"',
							'".$Deleted.			"',
							'".$theData.			"',
							'".$ActionDescription.	"',
							'".$SubjectPerfect.     "'
						)
				";
						
				if (mysql_query($Query3))
				{
						echo "Action Log Created in MySQL Database, Table Name email_book...<br>";
				}
				else 
				{
						echo "Error Table Data Log not created in Table email_book...<br>";
				}
				
				
				
			}
			
		}
		
		/* close the connection */
		imap_close($inbox);
		
	}
	else
    {

		echo 'Either the connection failed or not all the function parameters are filled in properly: Regs, Julian';

	}	
}
?>