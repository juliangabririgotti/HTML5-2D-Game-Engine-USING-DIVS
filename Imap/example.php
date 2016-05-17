<?php

	// include ImapJulian.php
	include'imapJulianClass.php';
	
	// Mysql Connection Info.
	$dbhost = 'sql11.cpt4.host-h.net';
	$dbusername = 'travecyccy_609';
	$dbuserpass = 'gZM0zznk';
	$dbname = 'travecyccy_wordpress';

	/* 
	
	== $emailhostname ==

	- To connect to an IMAP server running on port 143 on the local machine,do the following: 
	{localhost:143}INBOX

	- To connect to a POP3 server on port 110 on the local server, use:
	{localhost:110/pop3}INBOX

	- To connect to an SSL IMAP or POP3 server, add /ssl after the protocol specification: 
	{localhost:993/imap/ssl}INBOX

	- To connect to an SSL IMAP or POP3 server with a self-signed certificate,add /ssl/novalidate-cert after the protocol specification: 
	{localhost:995/pop3/ssl/novalidate-cert}

	- To connect to an NNTP server on port 119 on the local server, use:
	{localhost:119/nntp}comp.test

	- To connect to a remote server replace "localhost" with the name or the IP address of the server you want to connect to. 
	 
	*/
	 
	$emailhostname = '{www21.cpt4.host-h.net:143}INBOX';
	$emailusername = 'julian@travelandtourism.capetown'; // is not always an email address
	$emailpassword = 'Hell0W0rld'; // specific mailbox password
	
	/* 
	=== $KeyWord ===

	ALL - return all messages matching the rest of the criteria
	ANSWERED - match messages with the \\ANSWERED flag set
	BCC "string" - match messages with "string" in the Bcc: field
	BEFORE "date" - match messages with Date: before "date"
	BODY "string" - match messages with "string" in the body of the message
	CC "string" - match messages with "string" in the Cc: field
	DELETED - match deleted messages
	FLAGGED - match messages with the \\FLAGGED (sometimes referred to as Important or Urgent) flag set
	FROM "string" - match messages with "string" in the From: field
	KEYWORD "string" - match messages with "string" as a keyword
	NEW - match new messages
	OLD - match old messages
	ON "date" - match messages with Date: matching "date"
	RECENT - match messages with the \\RECENT flag set
	SEEN - match messages that have been read (the \\SEEN flag is set)
	SINCE "date" - match messages with Date: after "date"
	SUBJECT "string" - match messages with "string" in the Subject:
	TEXT "string" - match messages with text "string"
	TO "string" - match messages with "string" in the To:
	UNANSWERED - match messages that have not been answered
	UNDELETED - match messages that are not deleted
	UNFLAGGED - match messages that are not flagged
	UNKEYWORD "string" - match messages that do not have the keyword "string"
	UNSEEN - match messages which have not been read yet

	*/
	
	//$KeyWord = 'KEYWORD "This is a phrase to lookup in email body"'; // note the embedded quotes are opposite to the outer quotes
	//eg. $KeyWord = 'ALL';
	//or. $KeyWord = 'SUBJECT "HOWTO be Awesome" SINCE "8 August 2008"'
	$KeyWord = 'ALL';
	
	
	/* 
	
	== $Action ==
	
	1 =  Delete Email from account & UNSUBSCRIBE 
	2 =  Do Nothing
	*/
	
	//eg1.
	$Action = 1;
	$ActionDescription = 'spam'; // eg. spam or unregister or any other action string
	
	/*
	 == $OpperationFlag ==
	
	\\Seen, \\Answered, \\Flagged, \\Deleted, and \\Draft & combinations
	eg1. $OpperationFlag = "\\Flag \\Answered"; Flag
	eg2. $OpperationFlag = "AnythingElse or null"; // it will do nothing
	
	*/
	
	$OpperationFlag ="\\Answered \\Flag";
	
	
	// run only function from only class
	ConnectToMailBoxActionClass
	(
		$emailhostname,
		$emailusername,
		$emailpassword,
		$KeyWord,
		$Action,
		$dbhost,
		$dbusername,
		$dbuserpass,
		$dbname,
		$ActionDescription,// eg. spam or unregister or any other action string
		$OpperationFlag  /* \\Seen, \\Answered, \\Flagged, \\Deleted, and \\Draft & combinations */
		
	);
	
	
	//And That's it!
	
	


?>