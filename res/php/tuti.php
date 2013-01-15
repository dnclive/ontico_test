<?php
/*
 *      tuti.php
 *      
 *      Copyright 2011 dnclive <dnclive@ubunlive3>
 *      
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *      
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *      
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */

function fmix($context, $arr)
{
	if (tuti_f_is_empty($context))
	{
		$context=Array();
	}
	if (tuti_f_is_empty($arr))
	{
		$arr=Array();
	}
	return array_merge(Array(), $context, $arr);
}

function tuti_f_str_join($str1, $str2, $str3)
{
	if (tuti_f_is_empty($str1))
	{
		return $str3;
	}
	if (tuti_f_is_empty($str3))
	{
		return $str1;
	}
	if (tuti_f_is_empty($str1)&&tuti_f_is_empty($str3))
	{
		return "";
	}
	return $str1.$str2.$str3;
}

function turl_ffull_url()
{
	//pj_deb_flog3(__LINE__, __FILE__, $flat_arr, "tkvl3");
	//$GLOBALS["content"].=$_SERVER["SERVER_PORT"];
	
	if ($_SERVER["SERVER_PORT"]==443)
	{
		$full_url="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
	else
	{
		//$full_url="http://".$_SERVER['HTTP_HOST'].":".$_SERVER["SERVER_PORT"].$_SERVER['REQUEST_URI'];
		$full_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
	
	//$GLOBALS["content"].=$full_url;
	
	pj_deb_flog3(__LINE__, __FILE__, $full_url, "tuti");
	
	return $full_url;
}

function tuti_fdot_flat_arr_to_arr($args)
{
	$flat_arr=$args["flat_arr"];
	$arr=array();
	foreach($flat_arr as $flay_key=>$val)
	{
		$flat_key_i=explode(".",$flay_key);
		$curr_arr=&$arr;
		$last_fkii=null;
		$i=0;
		//while($i<count($flat_key_i))
		foreach($flat_key_i as $fkii)
		{
			$last_fkii=$fkii;
			if ($i==count($flat_key_i)-1)
			{
				break;
			}
			//if (!array_key_exists($fkii, $curr_arr))
			//{
			//	$curr_arr=&$curr_arr[$fkii];
			//	$i++;
			//	continue;
			//}
			pj_deb_flog3(__LINE__, __FILE__, $fkii, "tuti");
			if ($curr_arr[$fkii]==null)
			{
				$curr_arr[$fkii]=array();
				
			}
			$curr_arr=&$curr_arr[$fkii];
			$i++;
		}
		$curr_arr[$last_fkii]=$val;
		//pj_deb_flog3(__LINE__, __FILE__, $curr_arr, "tkvl3");
		//pj_deb_flog3(__LINE__, __FILE__, $flat_arr, "tkvl3");
	}
	pj_deb_flog3(__LINE__, __FILE__, $arr, "tuti");
	return $arr;
}

function tuti_fquery_params($args)
{
	$query_str=$args["query_str"];
	//$params=explode("&", $query_str);
	$params=tuti_fexplode(Array("sep"=>"&","str"=>$query_str, "lim"=>"'"));
	pj_deb_flog3(__LINE__, __FILE__, $params, "tkvl3_explode");
	
	pj_deb_flog3(__LINE__, __FILE__, $params, "tkvl3");
	
	$query_params_arr=array();
	foreach($params as $parami)
	{
		//$param_key_val=explode("=",$parami);
		$param_key_val=tuti_fexplode(Array("sep"=>"=","str"=>$parami, "lim"=>"'"));
		pj_deb_flog3(__LINE__, __FILE__, $param_key_val, "tkvl3_explode");
		pj_deb_flog3(__LINE__, __FILE__, $param_key_val, "tuti");
		if (count($param_key_val)>1)
		{
			$query_params_arr[$param_key_val[0]]=urldecode(tkvl_fkvl_val($param_key_val[1]));
			pj_deb_flog3(__LINE__, __FILE__, tkvl_fkvl_val($param_key_val[1]), "tkvl3_explode");
		}
		else
		{
			$query_params_arr[$param_key_val[0]]=null;
		}
	}
	pj_deb_flog3(__LINE__, __FILE__, $query_params_arr, "tkvl3");
	return $query_params_arr;
}

function tuti_fcookie_params($args)
{
	pj_deb_flog3(__LINE__, __FILE__, $args, "tuti");
	$query_str=$args["cookie_str"];
	$params=explode(";", $query_str);
	
	pj_deb_flog3(__LINE__, __FILE__, $params, "tuti");
	
	$query_params_arr=array();
	foreach($params as $parami)
	{
		$param_key_val=explode("=",trim($parami));
		pj_deb_flog3(__LINE__, __FILE__, $param_key_val, "tuti");
		if (count($param_key_val)>1)
		{
			$query_params_arr[$param_key_val[0]]=urldecode($param_key_val[1]);
		}
		else
		{
			$query_params_arr[$param_key_val[0]]=null;
		}
	}
	pj_deb_flog3(__LINE__, __FILE__, $query_params_arr, "tuti");
	return $query_params_arr;
}


function tuti_fin_params($args)
{
	//разбираем строку запроса
	$query_str=$args["query_str"];
	//$params=explode("&", $query_str);
	$params=tuti_fexplode(Array("sep"=>"&","str"=>$query_str, "lim"=>"'"));
	pj_deb_flog3(__LINE__, __FILE__, $params, "tuti");
	
	$query_params_arr=array();
	foreach($params as $parami)
	{
		//$param_key_val=explode("=",$parami);
		$param_key_val=tuti_fexplode(Array("sep"=>"=","str"=>$parami, "lim"=>"'"));
		pj_deb_flog3(__LINE__, __FILE__, $param_key_val, "tuti");
		if (count($param_key_val)>1)
		{
			$query_params_arr[$param_key_val[0]]=urldecode(tkvl_fkvl_val($param_key_val[1]));
		}
		else
		{
			$query_params_arr[$param_key_val[0]]=null;
		}
	}
	pj_deb_flog3(__LINE__, __FILE__, $query_params_arr, "tuti");
	
	//разбираем строку cookie
	$query_str=$args["cookie_str"];
	$params=explode(";", $query_str);
	
	pj_deb_flog3(__LINE__, __FILE__, $params, "tuti");
	
	//$query_params_arr=array();
	foreach($params as $parami)
	{
		$param_key_val=explode("=",trim($parami));
		pj_deb_flog3(__LINE__, __FILE__, $param_key_val, "tuti");
		if (count($param_key_val)>1)
		{
			$query_params_arr[$param_key_val[0]]=urldecode($param_key_val[1]);
		}
		else
		{
			$query_params_arr[$param_key_val[0]]=null;
		}
	}
	
	
	
	pj_deb_flog3(__LINE__, __FILE__, $query_params_arr, "tuti");
	return $query_params_arr;
}

//смешивает два массива по принципу
//берем первый массив и добавляем элементы из второго
//только те которых нет в первом, даже если в первом есть 
//элемент со значением null он не будет заменен
function tuti_fmix_1($args)
{
	
}

function tuti_f_redirect_to_url($url)
{
	header("Location: $url");
}

function tuti_redirect_to_page($url)
{
	$redirect_url="";
	$_SERVER=$GLOBALS["_SERVER"];
	if ($_SERVER["SERVER_PORT"]=="443")
	{
		$redirect_url.="https://";
	}
	else
	//else if ($_SERVER["SERVER_PORT"]=="80")
	{
		$redirect_url.="http://";
	}
	
	$redirect_url.=$_SERVER["HTTP_HOST"];
	
	if ($_SERVER["SERVER_PORT"]!="80"&&$_SERVER["SERVER_PORT"]!="443")
	{
		//$redirect_url.=":".$_SERVER["SERVER_PORT"];
	}
	
	$redirect_url.="/".$url;
	
	pj_deb_flog3(__LINE__, __FILE__, $redirect_url, "tuti");
	
	header("Location: $redirect_url");
	
	return $redirect_url;
	
	
}

function tuti_fresource_root_href()
{
	$redirect_url="";
	$_SERVER=$GLOBALS["_SERVER"];
	if ($_SERVER["SERVER_PORT"]=="443")
	{
		$redirect_url.="https://";
	}
	//else if ($_SERVER["SERVER_PORT"]=="80")
	else
	{
		$redirect_url.="http://";
	}
	
	$redirect_url.=$_SERVER["HTTP_HOST"];
	
	//print_r($_SERVER["HTTP_HOST"]);
	
	if ($_SERVER["SERVER_PORT"]!="80"&&$_SERVER["SERVER_PORT"]!="443")
	{
		//$redirect_url.=":".$_SERVER["SERVER_PORT"];
	}
	
	pj_deb_flog3(__LINE__, __FILE__, $_SERVER, "tuti");
	
	//print_r($redirect_url);
	
	return $redirect_url;//."/";
	
	
	
	//$redirect_url.="/".$url;
	
	//pj_deb_flog3(__LINE__, __FILE__, $redirect_url, "tuti");
	
	//header("Location: $redirect_url");
	
	//return $redirect_url;
	
	
}

function tuti_f_request_uri()
{
	return tuti_fresource_root_href().$GLOBALS["_SERVER"]["REQUEST_URI"];
}

function tuti_fcolumn_in_arr($args)
{
	$tab=$args["tab"];
	$column_name=$args["column_name"];
	
	foreach($tab as $row)
	{
		$arr[]=$row[$column_name];
	}
	
	return $arr;
}

function tuti_f_is_empty($val)
{
	//проверка ключей
	if (is_null($val)||$val==="") return true;
	
	//елси прошли сюда то все впорядке добавляем ключ
	return false;
}

function tuti_fexplode($args)
{
	//pj_deb_flog3(__LINE__, __FILE__, $args, "tkvl3_explode");
	$str=$args["str"];		//строка
	$sep=$args["sep"];		//разделитель
	$lim=$args["lim"];		//ограничитель в рамках которого не действует разделитель
	
	$state_1=true;
	$state_2=false;
	$state_1_new=true;
	$state_2_new=false;
	
	$i=0;
	$str_len=strlen($str);
	$ch='';
	$ch_prev='';
	$ch_prev_prev='';
	$ch_next='';
	$ch_next_next='';
	
	$str_part_arr=Array();
	$str_part="";
	
	//pj_deb_flog3(__LINE__, __FILE__, $str_len, "tkvl3_explode");
	
	while($i<$str_len)
	{
		
		$ch=$str[$i];
		$ch_prev=$i>0?$str[$i-1]:'';
		$ch_prev_prev=$i>1?$str[$i-2]:'';
		$ch_next=$i<$str_len?$str[$i+1]:'';
		$ch_next_next=$i+1<$str_len?$str[$i+2]:'';
		
		//pj_deb_flog3(__LINE__, __FILE__, $ch, "tkvl3_explode");
		//pj_deb_flog3(__LINE__, __FILE__, $str_part, "tkvl3_explode");
		//pj_deb_flog3(__LINE__, __FILE__, $str_part_arr, "tkvl3_explode");
		
		
		if ($state_1&&$ch==$sep)
		{
			//pj_deb_flog3(__LINE__, __FILE__, $sep, "tkvl3_explode");
			if (strlen($str_part)>0)
			{
				array_push($str_part_arr, $str_part);
				$str_part="";
			}
		}
		if ($ch==$lim&&$state_1)//&&!$state_2)
		{
			//pj_deb_flog3(__LINE__, __FILE__, $str_part, "tkvl3_explode");
			$state_1_new=false;
			//$state_2_new=true;
		}
		if (!$state_1&&$ch==$lim)//&&!$state_2)
		{
			//pj_deb_flog3(__LINE__, __FILE__, $str_part, "tkvl3_explode");
			$state_1_new=true;
		}
		//if ($ch=="\\"&&!$state_1)
		//{
		//	$state_2_new=true;
		//}
		//if ($state_2)
		//{
		//	$state_2_new=false;
		//}
		
		if ($ch!=$sep)//&&!$state_2_new)
		{
			//pj_deb_flog3(__LINE__, __FILE__, "df".$str_part, "tkvl3_explode");
			$str_part.=$ch;
		}
		if (!$state_1&&$ch==$sep)//&&!$state_2_new)
		{
			//pj_deb_flog3(__LINE__, __FILE__, "123".$str_part, "tkvl3_explode");
			$str_part.=$ch;
		}
		
		$state_1=$state_1_new;
		$state_2=$state_2_new;
		
		$i++;
	}
	
	//pj_deb_flog3(__LINE__, __FILE__, $str_part, "tkvl3_explode");
	//pj_deb_flog3(__LINE__, __FILE__, $str_part_arr, "tkvl3_explode");
	
	if (strlen($str_part)>0)
	{
		array_push($str_part_arr, $str_part);
	}
	//pj_deb_flog3(__LINE__, __FILE__, $str_part_arr, "tkvl3_explode");
	
	return $str_part_arr;
	
}

function file_get_contents_utf8($fn) 
{
	$content = file_get_contents($fn);
	return mb_convert_encoding($content, 'UTF-8',
			mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
}

?>
