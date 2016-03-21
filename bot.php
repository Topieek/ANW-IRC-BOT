<?php
	define("N", "\n");
	define("VERSION", "v1.00");
	define("MADE_BY", "topiek");
	
	date_default_timezone_set('UTC');
	
	print "  _____ _____   _____   ____        _   " . N;
	print " |_   _|  __ \ / ____| |  _ \      | |  " . N;
	print "   | | | |__) | |      | |_) | ___ | |_ " . N;
	print "   | | |  _  /| |      |  _ < / _ \| __|" . N;
	print "  _| |_| | \ \| |____  | |_) | (_) | |_ " . N;
	print " |_____|_|  \_\\_____|  |____/ \___/ \__|" . N;
	print N;
	print "----- IRC Bot " . VERSION . " -----" . N;
	print "----- Made by: " . MADE_BY . "-----" . N;
	
	$host = "irc.mibbit.com";
	$port = 6667;
	$nickname = "NSW-Bot";
	$channel = "#NSW";
	
	set_time_limit(0);  
	$socket = fsockopen($host, $port) or die();
	fputs($socket,"USER $nickname 0 $nickname :BOT\n");  
	fputs($socket,"NICK $nickname\n");
	
	function say($msg) {
		global $socket;
		global $channel;
		fputs($socket, "PRIVMSG " . $channel . " :" . $msg . "\n");
	}
	
	while(1) {  
	  
		// get the data from server  
		$data = fgets($socket, 128);
		   
		$ex = explode(" ", $data);
		if($ex[0] == "PING") {
			fputs($socket, "PONG " . $ex[1]);
		}
		
		if(isset($ex[1]) && $ex[1] == "MODE") {
			fputs($socket, "PRIVMSG NickServ IDENTIFY PASSWORD\n");
			fputs($socket, "JOIN " . $channel . N);
		}
		
		if(isset($ex[1]) && $ex[1] == "PRIVMSG") {
			if(isset($ex[3])) {
				$command = preg_replace('~[\r\n]+~', '', $ex[3]); // Command ends with /n; We don't want this!
				for($i = 4; $i < count($ex); $i++) {
					$ex[$i] = preg_replace('~[\r\n]+~', '', $ex[$i]); // Command ends with /n; We don't want this!
				}
				
				/* -------------------------------------- */
				/* ---------------- SAY ----------------- */
				/* -------------------------------------- */
				
				if($command == ":!say") {
					print("!say called" . N);
					$msg = "";
					for($i = 4; $i < count($ex); $i++) {
						$msg .= $ex[$i] . " ";
					}
					say($msg);
				}
				
				/* -------------------------------------- */
				/* ---------------- TIME ---------------- */
				/* -------------------------------------- */
				
				if($command == ":!tijd" OR $command == ":!time") {
					$command = str_replace(":", "", $command);
					print($command . " called". N);
					$months = array("", "Januari", "Februari", "Maart", "April", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "December");
					$month = idate('m');
					$day = date('d');
					$year = date('Y');
					$hour = date('H');
					$minute = date('i');
					$second = date('s');
					$msg = "Het is vandaag: " . $day . " " . $months[$month] . " " . $year . " - " . $hour . ":" . $minute . ":" . $second;
					say($msg);
				}
				
				/* -------------------------------------- */
				/* ---------------- HELP ---------------- */
				/* -------------------------------------- */
				
				if($command == ":!help") {
					print("!help called" . N);
					if(!isset($ex[4])) {
						say("!help [commando] || Laat zien hoe een commando gebruikt wordt. ([command] zonder !)");
					} else {
						switch($ex[4]) {
							case "tijd":
								say("!tijd || Laat de huidige tijd en datum zien");
								break;
							case "time":
								say("!tijd || Laat de huidige tijd en datum zien");
								break;
							case "say":
								say ("!say || Laat de bot iets zeggen");
								break;
							case "lijst":
								say("!lijst || Laat alle beschikbare commando's zien");
								break;
							case "list":
								say("!list || Laat alle beschikbare commando's zien");
								break;
							case "cmd":
								say("!cmd || Laat alle beschikbare commando's zien");
								break;
							case "cmds":
								say("!cmds || Laat alle beschikbare commando's zien");
								break;
							case "vt":
								say("!vt [gewilde aankomsttijd] [transporttijd] || Bereken wanneer je je aanval moet versturen. Tijd is hh:mm:ss");
								break;
							default:
								say("!" . $ex[4] . " niet gevonden");
								break;
						}
					}
				}
				
				/* -------------------------------------- */
				/* ----------------- VT ----------------- */
				/* -------------------------------------- */
				
				if($command == ":!vt") {
					print("!vt called" . N);
					
					if(!isset($ex[4]) OR !isset($ex[5])) { say("Gebruik: !vt [gewilde aankomsttijd] [transporttijd] (hh:mm:ss)"); } else { $p1 = $ex[4]; $p2 = $ex[5]; }
					
					$nTijd = strtotime($p1) - strtotime($p2);
					$sTijd = date("H:i:s", $nTijd);
					say("Je aanval moet om: " . $sTijd . " verstuurd worden, en zal dan om " . $p1 . " aankomen.");
				}
				
				/* -------------------------------------- */
				/* ---------------- LIJST --------------- */
				/* -------------------------------------- */
				
				if($command == ":!lijst" OR $command == ":!list" OR $command == ":!cmd" OR $command == ":!cmds") {
					$command = str_replace(":", "", $command);
					print($command . " called". N);
					say("!tijd, !time, !lijst, !list, !cmd, !cmds, !say, !help, !vt");
				}
			}
		}
		
	} 
?>
