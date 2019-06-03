<?php
require_once("sdata-modules.php");
/**
 * @Author: Nokia 1337
 * @Date:   2019-06-01 09:58:00
 * @Last Modified by:   Nokia 1337
 * @Last Modified time: 2019-06-02 20:48:20
*/
class GithubIssue
{
	function __construct()
	{
		$this->sdata  	= new Sdata;
	}
	function search($q){ 
		$url[] 		= array('url' => 'https://api.github.com/search/issues?q='.$q);
		$head[] 	=  array(
			'falsehead' => true,
		);
		$respons 	= $this->sdata->sdata($url,$head);unset($url);unset($head);
		foreach ($respons as $key => $value) {
			$json = json_decode($value['respons'],true);
			$array['data']['total_found'] = $json['total_count'];
			foreach ($json['items'] as $key => $git) {
				preg_match_all('/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i', $git['body'], $email);
				foreach ($email[0] as $key => $emailist) {
					$emails[] = $emailist;
				}
				$array['data'][$git['user']['login']][] = array('url' => $git['html_url'],'email' => $emails);
			}
		}
		return $array;
	}
} 