<?php
	$file = $_GET['file'];
	if(!file_exists($file))exit;
	
	$md_file_name = md5($file);
	$file_hash = hash_file('md5', $file);
	$path_session = 'private/cache/session/'.$md_file_name;
	$path_cache = 'private/cache/'.$md_file_name;
	
	function fload($name)
	{
		$f = fopen($name, "rb");
		$content = fread($f, filesize($name));
		fclose($f);
		return $content;
	}
	function fw($name,$data)
	{
		$f = fopen($name, "w");
		fwrite($f, $data);
		fclose($f);
	}
	
	if(file_exists($path_session)&&(fload($path_session)==$file_hash))
	{
		readfile($path_cache);
		exit;
	}
	
	$data = fload($file);
	
	$post = array(
		'data'=>$data
	);
	$myCurl = curl_init();
	curl_setopt_array($myCurl, array(
		CURLOPT_URL => 'http://cresoft.ru/compiler/index.php',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => http_build_query($post)
	));
	echo $response = curl_exec($myCurl);
	curl_close($myCurl);
	
	
	fw($path_cache,$response);
	fw($path_session,$file_hash);