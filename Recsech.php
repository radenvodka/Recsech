<?php
/**
 * @Author: Eka Syahwan
 * @Date:   2017-12-11 17:01:26
 * @Last Modified by:   Nokia 1337
 * @Last Modified time: 2019-06-02 16:47:34
*/
require_once("tools/sdata-modules.php");
require_once("tools/crt.php");
require_once("tools/Honeyscore.php");
require_once("tools/DomainTakeOver.php");
require_once("tools/TechDetected.php");
require_once("tools/EmailFinder.php");
require_once("tools/HTTPHeaders.php");
require_once("tools/Update.php");
require_once("tools/PortScanning.php");

$sdata = new Sdata;

$Recsech = new Recsech;

echo "\n\n ╦═╗┌─┐┌─┐┌─┐┌─┐┌─┐┬ ┬ \r\n";
echo " ╠╦╝├┤ │  └─┐├┤ │  ├─┤ \r\n";
echo " ╩╚═└─┘└─┘└─┘└─┘└─┘┴ ┴ \r\n";
echo " Recsech - Recon And Research (".$Recsech->version().") \r\n\n";

if(empty($argv[1])){
	die(' use command : '.$argv[0]." domain.com\r\n");
}

function color($color = "default" , $text){
	$arrayColor = array(
		'grey' 		=> '1;30',
		'red' 		=> '1;31',
		'green' 	=> '1;32',
		'yellow' 	=> '1;33',
		'blue' 		=> '1;34',
		'purple' 	=> '1;35',
		'nevy' 		=> '1;36',
		'white' 	=> '1;0',
	);	
	return "\033[".$arrayColor[$color]."m".$text."\033[0m";
}
function stuck($msg){
    echo color("purple",$msg);
    $answer =  rtrim( fgets( STDIN ));
    return $answer;
}

$time_start = microtime(true); 
function secondsToTime($seconds) {
  $hours = floor($seconds / (60 * 60));
  $divisor_for_minutes = $seconds % (60 * 60);
  $minutes = floor($divisor_for_minutes / 60);
  $divisor_for_seconds = $divisor_for_minutes % 60;
  $seconds = ceil($divisor_for_seconds);
  $obj = array(
      "h" => (int) $hours,
      "m" => (int) $minutes,
      "s" => (int) $seconds,
   );
  return $obj;
}

$Recsech->Update(); 

echo color("grey","[i] Start scanning at ".date("d/m/Y h:i:m")."\r\n");
$answr = stuck("[+] SCAN ONLY *.".$argv[1]." [Y/n] ");
echo color("purple","[i] Collect domain information ".$argv[1]."\r\n");


$Cert 		= new Cert($argv[1]);
$DomainList = $Cert->check($answr,$argv[1]); $DomainList = array_unique($DomainList);


$hit = 1;
foreach ($DomainList as $key => $domain) {
	echo "    [".($hit)."/".count($DomainList)."] ".color("green",$domain)."\r\n";
	$hit++;
}

$HTTPHeaders = new HTTPHeaders;

echo color("yellow","[+] HTTP Headers for Securing : \r\n");
$hit = 1;

foreach ($DomainList as $key => $domains) {
	$HTTPS 	= $HTTPHeaders->Domain($domains);
	echo "    Domain : ".color("nevy",$domains)." \r\n";
	foreach ($HTTPS as $key => $value) {
		if($value['header']){
			echo "           + ".color("green","[".$value['httpcode']."] ".$value['name'])." \r\n";
		}else{
			echo "           + ".color("red","[".$value['httpcode']."] ".$value['name'])." \r\n";
		}
		if($value['httpcode'] == 0){
			$DomainInactive[] = $domains;
		}
	}
	$hit++;
} 

echo color("yellow","[+] Inactive Domain : \r\n");
$hit = 1;
$DomainInactive = array_unique($DomainInactive);
if(count($DomainInactive) < 1){
	echo "    + N/A\r\n";
}else{
	foreach ($DomainInactive as $key => $domains) {
		echo "    + ".color("red",$domains)."\r\n";
		$hit++;
	}
}


$Honeyscore = new Honeyscore;

echo color("yellow","[+] Check Honeypot on all domains : \r\n");
$hit = 1;

foreach ($DomainList as $key => $domains) {
	$Honey = $Honeyscore->Domain($domains);
	echo "    + ".color("nevy",$Honey[ip])." ".color("green",$Honey[domain])." ".$Honey[score]."\r\n";
	$ipListServer[] = $Honey['ip'];
	$sortByIP2domain['data'][$Honey['ip']][] = $Honey['domain'];
	$hit++;
}


echo color("yellow","[+] IP Based Domain Information :\r\n");
$hit = 1;
foreach ($sortByIP2domain as $ip => $listDomain) {
	foreach ($listDomain as $ipNya => $arrayDomain) {
				echo "    + ".color("nevy",$ipNya);
		foreach ($arrayDomain as $key => $dom) {
			if($key == 0){
				echo color("green"," ".$dom)." \r\n";
			}else{
				echo "                     ".color("green",$dom)." \r\n";
			}
		}
	}
	$hit++;
}


$PORTScanning = new PORTScanning;

echo color("yellow","[+] Check Open Port (Port Scanning) : \r\n");
$hit = 1;
$ipListServer = array_unique($ipListServer);
foreach ($ipListServer as $key => $ipNya) {
		echo "    IP SERVER : ".color("green",$ipNya)." \r\n";
	$PORTScannings = $PORTScanning->IP($ipNya);
	foreach ($PORTScannings as $key => $value) {
		echo "              + ".color("nevy",$value[port])." ".color("green",$value[state])." ".$value[service]."\r\n";
	}
	$hit++;
}

echo color("yellow","[+] Domain Email @".$argv[1]." : \r\n");

$EmailFinder = new EmailFinder;
$getMAil  	 = $EmailFinder->Domain($argv[1]);
$hit = 1;
foreach ($getMAil as $keys => $email) {
	echo "    + ".color("green",$email)." \r\n"; 
}


$DomainTakeOver = new DomainTakeOver;

echo color("yellow","[+] Check Subdomain takeover : \r\n");
$hit = 1;
foreach ($DomainList as $keys => $domains) {
	$DomainTakeOvers = $DomainTakeOver->Domain($domains);
	foreach ($DomainTakeOvers as $key => $notice) {
		echo "    + ".color("green",$notice[domain])." ".$notice[status]."\r\n"; 
		$hit++;
	}
}

$TechDetected = new TechDetected;
echo color("yellow","[+] Check Technologies : \r\n");
$hit = 1;
foreach ($DomainList as $keys => $domains) {
	echo "    [".($hit)."/".count($DomainList)."] ".color("green",$domains)." \r\n"; 
	$TechDetecteds = $TechDetected->Domain($domains);
	
	foreach ($TechDetecteds as $key => $value) {
		echo "      + ".color("green",$value['name'])." \r\n"; 
	}
	
	$hit++;
}

$checkMe  = secondsToTime(ceil((microtime(true) - $time_start)));
echo color("grey","\n\n[i] Scanning is complete in ".$checkMe['h']." hour ".$checkMe['m']." minutes ".$checkMe['s']." seconds\r\n");