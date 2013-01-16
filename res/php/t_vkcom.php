<?php
/*
 * t_vkcom.php
 * 
 * Copyright 2013 dnclive <dnclive@ubunlive3>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 */

	function t_vkcom_get_token($args)
	{
		//$content=file_get_contents("https://oauth.vk.com/authorize?client_id=1&redirect_uri=https://oauth.vk.com/blank.html&scope=12&response_type=token");
		//$r = new HttpRequest("https://oauth.vk.com/authorize?client_id=1&redirect_uri=https://oauth.vk.com/blank.html&scope=12&response_type=token", HttpRequest::METH_GET);
		
		//$r = new httpRequest("http://vk.com", HttpRequest::METH_GET);
		
		$r = new HttpRequest("https://login.vk.com/?act=login&soft=1&utf8=1&_origin=https://oauth.vk.com&ip_h=4b9ae2872e4de0b33b&to=aHR0cHM6Ly9vYXV0aC52ay5jb20vb2F1dGgvYXV0aG9yaXplP2NsaWVudF9pZD0xJnJlZGlyZWN0X3VyaT1odHRwcyUzQSUyRiUyRm9hdXRoLnZrLmNvbSUyRmJsYW5rLmh0bWwmcmVzcG9uc2VfdHlwZT10b2tlbiZzY29wZT0xMiZzdGF0ZT0mZGlzcGxheT1wYWdl&email=123&pass=123", HttpRequest::METH_GET);
		
		//$r = new HttpRequest("https://oauth.vk.com/oauth/authorize?client_id=1&redirect_uri=https%3A%2F%2Foauth.vk.com%2Fblank.html&response_type=token&scope=12&state=&display=page&m=4&email=", HttpRequest::METH_GET);
		
		while (1)
		{
			$response = $r->send();
			if ($response->getResponseCode() != 301 && $response->getResponseCode() != 302) break;
			$r->setUrl($response->getHeader("Location"));
		} 
		
		//$r->send(); 
		
		//print_r($r);
		
		print_r($r->getResponseBody());
		
		return;
		
		/*
		$ssl_options = array('verifypeer' => TRUE, 
                     'verifyhost' => 1, 
                     'cert'       => '/cert/mycert.pem', 
                     'certtype'   => 'PEM', 
                     'cainfo'     => '/cert/ca.crt',
                     'version'    => 3,
                     'certpasswd' => 'pazzword'
                    );
        */
		//$r->setSslOptions($ssl_options);
		
		//$r->setHeaders(array('User-Agent' => 'Mozilla/1.22 (compatible; MSIE 5.01; PalmOS 3.0) EudoraWeb 2')); 
		
		/**/
		
		$url = 'http://www.example.com';
		$url='https://oauth.vk.com/authorize?client_id=1&redirect_uri=https://oauth.vk.com/blank.html&scope=12&response_type=token';
		//$url="http://vk.com";
		$credentials = 'user@example.com:password';
		$header_array = array('Expect' => '',
						'From' => 'User A');
		$ssl_array = array('version' => SSL_VERSION_SSLv3);
		$options = array(//headers => $header_array,
						//httpauth => $credentials,
						//httpauthtype => HTTP_AUTH_BASIC,
					protocol => HTTP_VERSION_1_1,
					ssl => $ssl_array);

		//create the httprequest object               
		$httpRequest_OBJ = new httpRequest($url, HTTP_METH_GET, $options);
		//add the content type
		$httpRequest_OBJ->setContentType = 'Content-Type: text/xml';
		//add the raw post data
		$httpRequest_OBJ->setRawPostData ($theData);
		//send the http request
		//$result = $httpRequest_OBJ->send();
		
		try 
		{  
			$httpRequest_OBJ->send();  
		} catch (HttpException $ex) {  
			if (isset($ex->innerException)){  
				echo $ex->innerException->getMessage();  
				exit;  
			} else {  
				echo $ex;  
				exit;  
			}  
		}  
		
		print_r($httpRequest_OBJ);
		
		//$r = new httpRequest("http://dnclive.ru", HttpRequest::METH_GET);
		
		//$r->
		
		//$r->send();
		
		//$r = http_get("https://oauth.vk.com/authorize?client_id=1&redirect_uri=https://oauth.vk.com/blank.html&scope=12&response_type=token");
		
		//print_r($r);
	}

	function t_vk_f($args)
	{
		
		$param_arr=$args["param_arr"];	//массив параметров
		$f=$args["f"];					//метод api который хотим выполнить
		
		t_deb_flog(__LINE__, __FILE__, $param_arr, "t_vkcom");
		
		//собираем строку параметров
		$param_str=tuti_f_param_str(array
		(
			"param_arr"=>$param_arr,
			"drop_empty"=>true,
		));
		
		t_deb_flog(__LINE__, __FILE__, $param_str, "t_vkcom");
		
		//выполняем
		$res=file_get_contents("https://api.vk.com/method/$f?$param_str");//,false, $context);
		
		t_deb_flog(__LINE__, __FILE__, $res, "t_vkcom");
		
		t_deb_flog(__LINE__, __FILE__, get_headers("https://api.vk.com/method/$f?$param_str"), "t_vkcom");

		//если что-то пришло
		if ($res!="")
		{
			//преобразуем в структуру из json текста
			$res=json_decode($res,true);
			
			//возвращаем
			return $res;
		}
		
		
		
		//иначе пустой результат
		return array();
		
	}
	
	//собираем строку параметров
	function tuti_f_param_str($args)
	{
		$param_arr=$args["param_arr"];
		$drop_empty=$args["drop_empty"];
		
		return array_reduce	//сворачивает массив вида ["key:val","key1:val1"...] в строку key:val&key1:val1...
		(
			array_map	//формируем массив вида ["key:val","key1:val1"...]
			(
				function($key,$val) /*use($drop_empty)*///у jino версия php не поддерживает замыканий
				{
					if (tuti_f_is_empty($val)/*&&$drop_empty*/)
					{
						return "";
					}
					
					return $key."=".$val;
				},
				array_keys($param_arr),
				$param_arr
			),
			function($str, $kv)
			{
				//return implode("&",array($str, $kv));
				return tuti_f_implode(array
				(
					"sep"=>"&",
					"str_arr"=>array($str, $kv),
					"drop_empty"=>true,
				));
			}
		);
		
	}
	
	//собираем строку параметров
	function tuti_f_implode($args)
	{
		$sep=$args["sep"];
		$str_arr=$args["str_arr"];
		$drop_empty=$args["drop_empty"]&&true;
		
		if ($drop_empty)
		{
			$str_arr=array_filter($str_arr,function ($str)
			{
				//t_deb_flog(__LINE__, __FILE__, $str, "t_vkcom");
				//t_deb_flog(__LINE__, __FILE__, !tuti_f_is_empty($str), "t_vkcom");
				return !tuti_f_is_empty($str);
			});
		}
		
		//t_deb_flog(__LINE__, __FILE__, $str_arr, "t_vkcom");
		
		return implode($sep,$str_arr);
		
	}
?>
