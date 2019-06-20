#!/usr/bin/env php
<?php
/**
 * @Author: Eka Syahwan
 * @Date:   2017-12-11 17:01:26
 * @Last Modified by:   Nokia 1337
 * @Last Modified time: 2019-06-05 03:03:10
*/
if(empty($argv[1])){
	error_reporting(0);
}else if($argv[1] == 'debug'){
	error_reporting(E_ALL);
}else{
	error_reporting(0);
}
require_once("lang/en.php");
require_once("tools/sdata-modules.php");
require_once("tools/crt.php");
require_once("tools/Honeyscore.php");
require_once("tools/DomainTakeOver.php");
require_once("tools/TechDetected.php");
require_once("tools/EmailFinder.php");
require_once("tools/HTTPHeaders.php");
require_once("tools/Update.php");
require_once("tools/PortScanning.php");
require_once("tools/GithubIssue.php");
require_once("tools/WAF.php");
require_once("tools/WPAudit.php");

$sdata = new Sdata;

$Recsech = new Recsech;

echo "\n     ───────────────────────────────────────\r\n";
echo "         ╦═╗┌─┐┌─┐".color("green","┌─┐┌─┐┌─┐")."┬ ┬ (".$Recsech->version().")\r\n";
echo "         ╠╦╝├┤ │  ".color("green","└─┐├┤ │  ")."├─┤ \r\n";
echo "         ╩╚═└─┘└─┘".color("green","└─┘└─┘└─┘")."┴ ┴ \r\n";
echo "     ───────────────────────────────────────\r\n";
echo "     Github : https://github.com/radenvodka \r\n";
echo "     ───────────────────────────────────────\r\n";
echo "     ~ Web ".color("red","Reconnaissance")." Tools ~\r\n\n";


if(empty($argv[1])){
	die('   use command : php '.color("red",$argv[0])." ".color("green","domain.com\r\n"));
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

echo color("grey",$lang['0']." ".date("d/m/Y h:i:m")."\r\n");
$answr = stuck($lang['1']." *.".$argv[1]." [Y/n] ");
echo color("grey",$lang['2'].color("green",$argv[1])."\r\n");


echo color("yellow",$lang[15]." \r\n\n");

$Cert 		= new Cert($argv[1]);
$DomainList = $Cert->check($answr,$argv[1]); $DomainList = array_unique($DomainList);

echo "\n";

$hit = 1;
foreach ($DomainList as $key => $domain) {
	echo "    [".($hit)."/".count($DomainList)."] ".color("green",$domain)."\r\n";
	$hit++;
}

echo "\n";

$WAF = new WAF;
echo color("yellow",$lang['14']." \r\n");
$hit = 1;

foreach ($DomainList as $key => $domains) {
	echo "\n";
	$WAFME = $WAF->Domain($domains);
		echo "    Domain : ".color("nevy",$domains)." \r\n";
	foreach ($WAFME as $key => $value) {
		echo "           + ".color("green","[".$value['httpcode']."] ").color("yellow"," ".$value['name']."")." \r\n";
	}
	$hit++;
} 

echo "\n";


$WPAudit 	= new WPAudit;

	echo color("yellow","[+] Wordpress Audit : \r\n");

$WPDetected = $WPAudit->AllDomain($DomainList);

if(count($WPDetected) > 0){
	foreach ($WPDetected as $key => $wpversion) {
		echo "\n    Domain : ".color("green",$wpversion['domain'])." ".color("nevy",$wpversion['version'])." \r\n\n";
		$VersionCheck = $WPAudit->wpvulndbVersion($wpversion['domain'] , $wpversion['version']);
		echo "           + Version Vulnerability : \r\n\n";
		if(count($VersionCheck) > 0){
			foreach ($VersionCheck as $key => $r) {
				echo "             - ".color("red",$r['title'])." \r\n";
			}
		}else{
				echo "             - ".color("red",'N/A')." \r\n";
		}
				echo "\n            + Plugins Vulnerability : \r\n";
		if(count($wpversion['plugins'][1]) > 0){
			$PluginsWP = $WPAudit->wpvulndbPlugins( $wpversion['plugins'][1] );
			foreach ($PluginsWP as $key => $value) {
				foreach ($value['vuln'] as $key => $vulnane) {
						echo "\n            + ".color("red",$vulnane['title'])."\r\n";
					foreach ($vulnane['references']['url'] as $key => $urls) {
						echo "             * ". color("yellow",$urls)."\r\n";
					}
				}
			}
			echo "\n";
		}else{
			echo "             - ".color("red",'N/A')." \r\n";
		}
	}
}else{
	echo "    + ".color("red",'N/A')." \r\n"; 
}

echo "\n";



$HTTPHeaders = new HTTPHeaders;

echo color("yellow",$lang['3']." \r\n\n");
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

echo "\n";

echo color("yellow",$lang[4]."\r\n");
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

$GithubIssue = new GithubIssue;

echo color("yellow",$lang[5]."\r\n\n");
$hit = 1;

foreach ($DomainList as $key => $domains) {
		echo "    Domain : ".color("nevy",$domains);
	$arrayGIT 	 = $GithubIssue->search($domains);
	foreach ($arrayGIT as $key => $result) {
		if($result['total_found'] > 0){
			echo "\r\n\n           # ".color("yellow",$result['total_found'])." Issues On Github (".$domains.")\r\n\n";
			foreach ($result as $username => $listIssue) {
				if($username != 'total_found'){
					echo "           + ".color("yellow",'Github')."     : ".color("green",$username)." \r\n";
					echo "           + ".color("yellow","Link Issue")." : ";
					foreach ($listIssue as $key => $link) {
						if($key == 0){
							echo color("purple",$link['url'])." \r\n";
						}else{
							echo "                        - ".color("purple",$link['url'])." \r\n";
						}
						foreach ($link['email'] as $key => $tempEmail) {
							$gitLeakemail[] = $tempEmail;
						}
					}
				}
			}
			echo "\n";
		}else{
			echo " ".color("red","N/A")."\r\n";
		}
		if(count($gitLeakemail) > 0){
		       echo "          -[ ".color("yellow",$lang[6])." ]-\r\n\n";
			foreach ($gitLeakemail as $key => $EmaiLs) {
				echo "            - ".color("green",$EmaiLs)." \r\n";
			}
			echo "\n";
			unset($gitLeakemail);
		}
	}
	$hit++;
} 

echo "\n";

$Honeyscore = new Honeyscore;

echo color("yellow",$lang[7]."\r\n\n");
$hit = 1;

foreach ($DomainList as $key => $domains) {
	$Honey = $Honeyscore->Domain($domains);
	echo "    + ".color("nevy",$Honey[ip])." ".color("green",$Honey[domain])." ".$Honey[score]."\r\n";
	$ipListServer[] = $Honey['ip'];
	$sortByIP2domain['data'][$Honey['ip']][] = $Honey['domain'];
	$hit++;
}
echo "\n";

echo color("yellow",$lang[8]."\r\n\n");
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

echo "\n";

$PORTScanning = new PORTScanning;

echo color("yellow",$lang[9]."\r\n\n");
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

echo "\n";

echo color("yellow",$lang[10]." @".$argv[1]." : \r\n\n");

$EmailFinder = new EmailFinder;
$getMAil  	 = $EmailFinder->Domain($argv[1]);
$hit = 1;
foreach ($getMAil as $keys => $email) {
	echo "    + ".color("green",$email)." \r\n"; 
}

echo "\n";

$DomainTakeOver = new DomainTakeOver;

echo color("yellow",$lang[11]."\r\n\n");
$hit = 1;
foreach ($DomainList as $keys => $domains) {
	$DomainTakeOvers = $DomainTakeOver->Domain($domains);
	foreach ($DomainTakeOvers as $key => $notice) {
		echo "    + ".color("green",$notice[domain])." ".$notice[status]."\r\n"; 
		$hit++;
	}
}

echo "\n";

$TechDetected = new TechDetected;
echo color("yellow",$lang[12]."\r\n\n");
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

$lang['13'] = str_replace('{h}', $checkMe['h'] , $lang['13']);
$lang['13'] = str_replace('{m}', $checkMe['m'] , $lang['13']);
$lang['13'] = str_replace('{s}', $checkMe['s'] , $lang['13']);
echo color("grey","\n\n".$lang[13]."\r\n");