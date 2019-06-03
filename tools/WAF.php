<?php
error_reporting(0);
require_once("sdata-modules.php");
/**
 * @Author: Nokia 1337
 * @Date:   2019-06-01 09:58:00
 * @Last Modified by:   Nokia 1337
 * @Last Modified time: 2019-06-03 13:12:33
*/
class WAF
{
	function __construct()
	{
		$this->sdata  = new Sdata;
	}
	function Fingerprint(){
		$array  = array(
			'aeSecure WAF' 									=> 'aeSecure-code', 
			'Amazon WAF' 									=> 'x-amzn', 
			'Anquanbao WAF' 								=> 'anquanbao', 
			'Armor Defense WAF' 							=> 'This request has been blocked by website protection from Armor', 
			'F5 Networks WAF' 								=> 'The requested URL was rejected. Please consult with your administrator.', 
			'Yunjiasu/Baidu WAF' 							=> 'fh1|yunjiasu-nginx', 
			'Barracuda Networks WAF' 						=> 'barra_counter_session', 
			'Better WP Security WAF' 						=> 'better-wp-security', 
			'BIG-IP WAF' 									=> 'bigip|bigipserver', 
			'BinarySEC WAF' 								=> 'x-binarysec-via|x-binarysec-nocache|binarySec', 
			'BlockDos' 										=> 'blockdos.net', 
			'Cisco WAF' 									=> 'ace xml gateway', 
			'CloudFlare WAF ' 								=> 'cloudflare|cloudflare-nginx|__cfduid', 
			'Comodo WAF' 									=> 'protected by comodo waf',
			'IBM WAF' 										=> 'x-backside-transport',
			'dotDefender WAF' 								=> 'x-dotdefender-denied|dotDefender',
			'EdgeCast/Verizon WAF' 							=> 'ecdf',
			'FortiWeb/Fortinet WAF'   						=> 'fortiwafsid',
			'Hyperguard WAF' 								=> 'odsession',
			'Incapsula WAF' 								=> 'incap_ses|visid_incap|incapsula',
			'Microsoft WAF' 								=> 'The ISA Server denied the specified Uniform Resource Locator',
			'Jiasule WAF' 									=> '__jsluid=|jsl_tracking',
			'Knownsec WAF' 									=> 'ks-waf-error',
			'Akamai Technologies WAF' 						=> 'AkamaiGHost',
			'ModSecurity/Trustwave WAF' 					=> 'Mod_Security|NOYB',
			'NetContinuum/Barracuda WAF' 					=> 'NCI__SessionId',
			'Citrix Systems WAF' 							=> 'ns_af=|citrix_ns_id|NSC_',
			'Newdefend WAF' 								=> 'newdefend',
			'NSFOCUS WAF' 									=> 'nsfocus',
			'Palo Alto Networks WAF' 						=> 'has been blocked in accordance with company policy',
			'Armorlogic WAF' 								=> 'profense|PLBSID',
			'AppWall (Radware) WAF' 						=> 'x-sl-compstate',
			'Microsoft (ASP.NET) WAF' 						=> 'has detected a potentially|has detected data in the request that is potentially dangerous',
			'Safe3WAF' 										=> 'Safe3 Web Firewall|Safe3',
			'Safedog WAF' 									=> 'safedog',
			'SEnginx WAF' 									=> 'SENGINX-ROBOT-MITIGATION',
			'TrueShield WAF' 								=> 'SiteLock Incident ID',
			'SonicWALL (Dell) WAF' 							=> 'This request is blocked by the SonicWALL|sonicwall',
			'BeyondTrust WAF' 								=> 'SecureIIS|Web Server Protection',
			'Stingray WAF' 									=> 'X-Mapping-',
			'Sucuri WAF' 									=> 'sucuri|cloudproxy|Sucuri WebSite Firewall - CloudProxy - Access Denied',
			'Wallarm WAF' 									=> 'nginx-wallarm',
			'Varnish FireWall (OWASP)' 						=> 'varnish|x-varnish',
			'UrlScan (Microsoft)' 							=> 'Rejected-By-UrlScan',
			'WebKnight WAF' 								=> 'WebKnight',
			'Yundun WAF' 									=> 'YUNDUN',
			'Yunsuo WAF' 									=> 'yunsuo_session',
			'imperva WAF' 									=> 'imperva',
			'StackPath WAF' 								=> 'X-Dis-Request-Id',
			'Imunify360 WAF' 								=> 'Imunify360',
		);
		return $array;
	}
	function Domain($Domain){
		$Fingerprint = $this->Fingerprint();
		$url[] = array(
			'url' => $Domain, 
		);
		$head[] = array(
			'header' => array(
				"x-originating-IP: 127.0.0.1",
				"x-forwarded-for: 127.0.0.1",
				"x-remote-IP: 127.0.0.1",
				"x-remote-addr: 127.0.0.1",
			), 
		);
		$respons = $this->sdata->sdata($url,$head);
		foreach ($respons as $key => $value) {
			foreach ($Fingerprint as $name => $regex) {
				if(preg_match("/".$regex."/", $value['respons'])){
					$check[] = array(
						'name' 		=> strtoupper($name),
						'httpcode'  => $value['info']['http_code'],
					);
				}
			}
		}
		return $check;
	}
}