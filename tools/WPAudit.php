<?php
require_once("sdata-modules.php");
/**
 * @Author: Nokia 1337
 * @Date:   2019-06-01 09:58:00
 * @Last Modified by:   Nokia 1337
 * @Last Modified time: 2019-06-05 02:59:50
*/
class WPAudit
{
	function __construct()
	{
		$this->sdata  = new Sdata;
	}
	function AllDomain($arrayDomain){
		foreach ($arrayDomain as $key => $domain) {
			$url[] 		= array('url' => $domain,'note' => $domain);
			$head[] 	= array(
				'rto' => 20
			);
		}
		$respons 	= $this->sdata->sdata($url,$head);unset($url);unset($head);
		foreach ($respons as $key => $value) {
			preg_match_all('/<meta name="generator" content="WordPress (.*?)" \/>/m', $value['respons'], $matchesversion);
			if(preg_match("/wp-content/",$value['respons'])){
				preg_match_all('/wp-content\/plugins\/(.*?)\//', $value['respons'], $plugins);
				if(empty($matchesversion[1][0])){
					preg_match_all('/WordPress (.*?)"/m', $value['respons'], $matchesversion);
					$matchesversion[1][0] = $matchesversion[1][0];
				}
				$detected[] = array(
					'domain' 	=> $value[data][note],
					'version' 	=> ($matchesversion[1][0] ? $matchesversion[1][0] : "N/A"), 
					'plugins' 	=> $plugins,
				);

			}
		}
		return $detected;
	}
	function wpvulndbVersion($domain , $version){
		$url[] 		= array('url' => 'https://wpvulndb.com/api/v3/wordpresses/'.str_replace('.', '', $version),'note' => $domain);
		$head[] 	= array(
			'header' => array(
			    "authorization: Token token=5tkTXHD01gOeELlZlZa3pXVeSFP61eol14T6IOBkF2w",
			),
			'rto' => 20,
			'falsehead' => true,
		);
		$respons 	= $this->sdata->sdata($url,$head);unset($url);unset($head);
		foreach ($respons as $key => $value) {
			$json 	= json_decode($value['respons'],true);
			return $json[$version]['vulnerabilities'];
			break;
		}
	}
	function wpvulndbPlugins($arrayPlugins){
		foreach ($arrayPlugins as $key => $value) {
			$url[] 		= array('url' => 'https://wpvulndb.com/api/v3/plugins/'.$value);
			$head[] 	= array(
				'header' => array(
				    "authorization: Token token=5tkTXHD01gOeELlZlZa3pXVeSFP61eol14T6IOBkF2w",
				),
				'rto' => 20,
				'falsehead' => true,
			);
		}
		$respons 	= $this->sdata->sdata($url,$head);unset($url);unset($head);
		foreach ($respons as $key => $value) {
			$json = json_decode($value['respons'],true);
			foreach ($json as $name => $bug) {
				if($bug[friendly_name]){
					$arrayBug[] = array(
						'name' => $bug['friendly_name'],
						'vuln' => $bug['vulnerabilities']
					);
				}
			}
		}
		return $arrayBug;
	}
}