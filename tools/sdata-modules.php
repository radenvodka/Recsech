<?php
/**
 * @Author: Eka Syahwan
 * @Date:   2017-11-06 22:54:36
 * @Last Modified by:   Nokia 1337
 * @Last Modified time: 2019-06-02 20:32:18
 */
class Sdata
{
	public function proxy(){
		$url[] = array('url' => 'https://free-proxy-list.net');
		$respons = $this->sdata($url);
		preg_match_all('/<td class=\'hm\'>(.*?)<\/td><\/tr><tr><td>(.*?)<\/td><td>(.*?)<\/td>/m', $respons[0]['respons'], $matches);
		foreach ($matches[2] as $key => $ip) {
			$array[] = array('ip' => $ip , 'port' => $matches[3][$key]);
		}
		return $array;
	}
	public function useProxy(){
		echo color("yellow","[+] Looking for a proxy ... ");
		$proxy = $this->proxy();
		$l = 0;
		while (TRUE) {
			shuffle($proxy);
			$proxycheck = array_chunk($proxy, 5);
			foreach ($proxycheck as $key => $value) {
				foreach ($value as $key => $listProxy) {
					$url[] = array(
						'url' 	=> 'https://api.wappalyzer.com/lookup-basic/beta/?url=https://ekasyahwan.com', 
						'note' 	=> $listProxy,
					);
					$head[] = array(
						'rto' 	=> 3,
						'proxy' => $listProxy, 
					);
				}
				$result = $this->sdata($url,$head);unset($url);unset($head);
				foreach ($result as $key => $res) {
					if(!preg_match("/Requests|Internal/", $res['respons']) && $res[info][http_code] == 200){
						$wootProxy = $res[data][note];
						break;
					}
				}
				if($wootProxy){
					break;
				}
			}
			if($wootProxy){
				break;
			}
		}
		echo color("green","Woot! Found : ".$wootProxy['ip'].":".$wootProxy['port']."\r\n");
		return $wootProxy;
	}
	public function sdata($url = null , $custom = null){
		mkdir('cookies'); // pleas don't remove
		$ch 	 	= array();
		$mh 		= curl_multi_init();
		$total 		= count($url);
		$allrespons = array();
		for ($i = 0; $i < $total; $i++) {
			if($url[$i]['cookies']){
				$cookies		= $url[$i]['cookies'];
			}else{
				$cookies 		= 'cookies/shc-'.md5($this->cookies())."-".time().'.txt'; 
			}
			$ch[$i] 			= curl_init();
			$threads[$ch[$i]] 	= array(
				'proses_id' => $i,
				'url' 		=> $url[$i]['url'],
				'cookies' 	=> $cookies, 
				'note' 		=> $url[$i]['note'],
			);
		    curl_setopt($ch[$i], CURLOPT_URL, $url[$i]['url']);
			if($custom[$i]['gzip']){
				curl_setopt($ch[$i], CURLOPT_ENCODING , "gzip");
			}
			if($custom[$i]['falsehead']){
		    	curl_setopt($ch[$i], CURLOPT_HEADER, false);
			}else{
				curl_setopt($ch[$i], CURLOPT_HEADER, true);
			}
		    curl_setopt($ch[$i], CURLOPT_COOKIEJAR,  $cookies);
      		curl_setopt($ch[$i], CURLOPT_COOKIEFILE, $cookies);
		    if($custom[$i]['rto']){
		    	curl_setopt($ch[$i], CURLOPT_TIMEOUT, $custom[$i]['rto']);
		    }else{
		    	curl_setopt($ch[$i], CURLOPT_TIMEOUT, 60);
		    }
		    if($custom[$i]['header']){
		    	curl_setopt($ch[$i], CURLOPT_HTTPHEADER, $custom[$i]['header']);
		    }
		    if($custom[$i]['post']){
		    	if(is_array($custom[$i]['post'])){
		    		$query = http_build_query($custom[$i]['post']);
		    	}else{
		    		$query = $custom[$i]['post'];
		    	}
		    	curl_setopt($ch[$i], CURLOPT_POST, true);
		    	curl_setopt($ch[$i], CURLOPT_POSTFIELDS, $query);
		    }
		    if($custom[$i]['proxy']){
		    	curl_setopt($ch[$i], CURLOPT_PROXY, 	$custom[$i]['proxy']['ip']);
		    	curl_setopt($ch[$i], CURLOPT_PROXYPORT, $custom[$i]['proxy']['port']);
		    	if( $custom[$i]['proxy']['type'] ){
		    		curl_setopt($ch[$i], CURLOPT_PROXYTYPE, $custom[$i]['proxy']['type']);
		    	}
		    }
		    curl_setopt($ch[$i], CURLOPT_VERBOSE, false);
		    curl_setopt($ch[$i], CURLOPT_CONNECTTIMEOUT , 0);
		    curl_setopt($ch[$i], CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch[$i], CURLOPT_FOLLOWLOCATION, true);
		    curl_setopt($ch[$i], CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($ch[$i], CURLOPT_SSL_VERIFYHOST, false); 
        	if($custom[$i]['uagent']){
		    	curl_setopt($ch[$i], CURLOPT_USERAGENT, $custom[$i]['uagent']);
		    }else{
				curl_setopt($ch[$i], CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36");
		    }
	    	curl_multi_add_handle($mh, $ch[$i]);
		}
		$active = null;
		do {
		    $mrc = curl_multi_exec($mh, $active);
		    while($info = curl_multi_info_read($mh))
		    {	
		    	$threads_data	= $threads[$info['handle']];
		    	$result 		= curl_multi_getcontent($info['handle']);
		       	$info 			= curl_getinfo($info['handle']);
		       	$allrespons[] 	= array(
		       		'id' 		=> $threads_data['proses_id'],
		       		'data' 		=> $threads_data, 
		       		'respons' 	=> $result,
		       		'headers' 	=> $headers,
		       		'info' 		=> array(
		       			'url' 		=> $info['url'],
		       			'http_code' => $info['http_code'], 
		       		),
		       	);
		        curl_multi_remove_handle($mh, $info['handle']);
		    }
		    usleep(100);
		} while ($active);
		curl_multi_close($mh);

		usort($allrespons, function($a, $b) {
		    return $a['id'] <=> $b['id'];
		});

		return $allrespons;
	}
	public function cookies($length = 60) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString.time().rand(10000000,99999999);
	}
	public function session_remove($arrayrespons){
		foreach ($arrayrespons as $key => $value) {
			unlink($value['data']['cookies']);
		}
	}
	public function aasort (&$array, $key) {
	    $sorter=array();
	    $ret=array();
	    reset($array);
	    foreach ($array as $ii => $va) {
	        $sorter[$ii]=$va[$key];
	    }
	    asort($sorter);
	    foreach ($sorter as $ii => $va) {
	        $ret[$ii]=$array[$ii];
	    }
	    $array=$ret;
	}

}
$sdata = new Sdata();
