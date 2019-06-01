<?php
require_once("sdata-modules.php");
/**
 * @Author: Eka Syahwan
 * @Date:   2017-12-11 17:01:26
 * @Last Modified by:   Nokia 1337
 * @Last Modified time: 2019-06-01 07:30:43
*/

echo "\n\n ╦═╗┌─┐┌─┐┌─┐┌─┐┌─┐┬ ┬ \r\n";
echo " ╠╦╝├┤ │  └─┐├┤ │  ├─┤ \r\n";
echo " ╩╚═└─┘└─┘└─┘└─┘└─┘┴ ┴ \r\n";
echo " Recsech - Recon And Research (BETA) \r\n\n";

if(empty($argv[1])){
	die('use command : '.$argv[0]." domain.com\r\n");
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

function useProxy($sdata){
	echo color("yellow","[+] Looking for a proxy ... ");
	$proxy = $sdata->proxy();
	shuffle($proxy);
	foreach ($proxy as $key => $value) {
		$url[] = array(
			'url' => 'http://api.hackertarget.com/whois/?q=ekasyahwan.com', 
		);
		$head[] = array(
			'rto' => 5,
			'proxy' => $proxy, 
		);
		$result = $sdata->sdata($url,$head);unset($url);unset($head);
		if($result[0]['info']['http_code'] == 200 && preg_match("/Domain Name/", $result[0]['respons'])){
			$proxys = $value;
			break;
		}
	}
	echo color("green","Done!\r\n");
	return $proxys;
}

$proxy = useProxy($sdata); // use proxy

echo color("yellow","[+] Whois Domain : ".$argv[1])."\r\n";

if($proxy['ip']){
	$url[] = array(
		'url' => 'http://api.hackertarget.com/whois/?q='.$argv[1], 
	);
	$head[] = array(
		'proxy' => $proxy, 
	);
	$result = $sdata->sdata($url,$head);unset($url);unset($head);
}else{
	$url[] = array(
		'url' => 'http://api.hackertarget.com/whois/?q='.$argv[1], 
	);
	$result = $sdata->sdata($url);unset($url);
}

foreach ($result as $key => $respons) {
$Whois = '
    Domain    : '.$argv[1].'
    Whois     : 

    '.color("nevy",$respons[respons]);
	    
	echo $Whois."\r\n\n";	
}

echo color("yellow","[+] Search for all emails by domain : ")."\r\n";
$url[] = array(
	'url' => 'https://api.hunter.io/v2/domain-search?domain='.$argv[1].'&api_key=61150cc37813ef999eba7556f301b88e98b12061', 
);
$result = $sdata->sdata($url);
foreach ($result as $key => $respons) {
	$json = json_decode($respons['respons'],true);
	foreach ($json['data']['emails'] as $key => $email) {
		echo "    [".($key+1)."/".count($json['data']['emails'])."] ".color("green",$email['value'])."\r\n";
		$data['email'][] = $email;
	}
}

echo color("yellow","[+] Search for all (sub) domains : ")."\r\n";

$url[] = array(
	'url' => 'https://findsubdomains.com/subdomains-of/'.$argv[1], 
);
$result = $sdata->sdata($url);unset($url);
foreach ($result as $key => $respons) {
	preg_match_all('/<a href="javascript:void\(0\);" class="desktop-hidden">(.*?)<\/a>/m', $respons['respons'], $matches);
	foreach ($matches[1] as $key => $domain) {
		if($domain != '{{:domain}}'){
			echo "    [".($key+1)."/".count($matches[1])."] ".color("green",$domain)."\r\n";
			$data['domain'][] = $domain;
		}
	}
}
echo color("yellow","[+] Search for all Dnslookup : ")."\r\n";
foreach ($data['domain'] as $key => $domain) {

	if($proxy['ip']){
		$url[] = array(
			'url' => 'http://api.hackertarget.com/dnslookup/?q='.$domain, 
			'note' => $domain,
		);
		$head[] = array(
			'proxy' => $proxy, 
		);
		$result = $sdata->sdata($url,$head);unset($url);unset($head);
	}else{
		$url[] = array(
			'url' => 'http://api.hackertarget.com/dnslookup/?q='.$domain, 
			'note' => $domain,
		);
		$result = $sdata->sdata($url);unset($url);
	}

	foreach ($result as $key => $respons) {
$Dnslookup = '
    Domain    : '.color("green",$respons[data]['note']).'
    Dnslookup : 

    '.color("nevy",$respons[respons]);
	    
	echo $Dnslookup;
	}
}

echo color("yellow","[+] Get domain information : ");

foreach ($data['domain'] as $key => $domain) {
	$url[] = array(
		'url' => 'http://ip-api.com/json/'.$domain, 
		'note' => $domain,
	);
}
$result = $sdata->sdata($url);unset($url);
echo color("green","Done!\r\n");
foreach ($result as $key => $respons) {
	$json = json_decode($respons['respons'],true);	
	$datatemp[] 	= array('url' => 'https://api.shodan.io/labs/honeyscore/'.$json['query'].'?key=z3cBefrV3bmRx2rNZ0E1opuZxXNPrbIR' , 'note' => array(
		'domain' => $respons[data][note],
		'ipinfo' => $json,
	));
}
foreach ($datatemp as $key => $value) {
	$url[] = array(
		'url' => $value['url'], 
	);
	$result = $sdata->sdata($url);unset($url);
	foreach ($result as $key => $datane) {
		if($datane['respons'] == 0){
			$honeypot = color("red",'Not a honeypot');
		}else if($datane['respons'] == '1.0'){
			$honeypot = color("green",'is a honeypot');
		}else{
			$honeypot = color("yellow",'Possible honeypot');
		}
	}
	$DomainInfo = '
    Domain    : '.color("green",$value['note']['domain']).'
    IP        : '.color("green",$value['note']['ipinfo']['query']." (".$value['note']['ipinfo']['isp'].")").'
    HoneySpot : '.$honeypot.' 
	';
	echo $DomainInfo;
}