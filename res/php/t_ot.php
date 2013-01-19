<?php
/*
 * t_ot.php
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

	//инклуды
	require_once('magpierss/rss_fetch.inc');	//работа с rss

	function t_ot_fill_student_tab($args)
	{
		
		
		t_deb_flog(__LINE__, __FILE__, $args, "t_ot");
		
		t_deb_flog(__LINE__, __FILE__, $GLOBALS["db_ssn"], "t_ot");
		
		$all_param_arr=$args["all_param_arr"];
		
		$access_token=$all_param_arr["access_token"];
		$q=$all_param_arr["q"];
		$fileds=$all_param_arr["fileds"];
		$count=$all_param_arr["count"];
		$offset=$all_param_arr["offset"];
		
		tdeb_f_check("fill_stud_tab", "старт запроса vk.com");
		
		//*** запрашиваем данные из базы вконтакте
		$res=t_vk_f(array
		(
			"param_arr"=>array
			(
				"access_token"=>$access_token,
				"q"=>$q,
				"fileds"=>$fileds,
				"count"=>$count,
				"offset"=>$offset,
			),
			"f"=>"users.search",
		));
		
		tdeb_f_check("fill_stud_tab","получен результат от vk.com");
		
		//t_deb_flog(__LINE__, __FILE__, $res, "t_ot");
		
		//*** формируем sql запрос добавление людей в базу
		$sql="insert `students` (`name`, `grade`) values \r\n";
		$values="";
		foreach($res["response"] as $item)
		{
			//$sql.="('".$item["first_name"]." ".$item["last_name"]."', ".rand(1,5)."),\r\n";
			$values=tuti_f_str_join($values, ",\r\n", "('".$item["first_name"]." ".$item["last_name"]."', ".rand(1,5).")");
		}
		
		$sql.=$values;
		
		t_deb_flog(__LINE__, __FILE__, $sql, "f");
		
		tdeb_f_check("fill_stud_tab","запрос сформирован");
		
		//*** выполняем сформированный запрос
		$q=t_sql_f(array
		(
			"db"=>$GLOBALS["db_ssn"],
			"sql_query"=>$sql,
		));
		
		tdeb_f_check("fill_stud_tab","запрос выполнен");
		
		t_deb_flog(__LINE__, __FILE__, $q, "f");
		
		$out=array("log"=>"", "res"=>array());
		tdeb_f_check_out("fill_stud_tab",&$out["log"]);
		
		//преобразуем в json и вкидываем в вывод
		$GLOBALS["content"].=json_encode($out);
		
		t_deb_flog(__LINE__, __FILE__, tdeb_f_check_get("save_2_bd"), "f");
		
	}
	
	function t_ot_fill_likes_random($args)
	{
		t_deb_flog(__LINE__, __FILE__, $args, "t_ot");
		
		$all_param_arr=$args["all_param_arr"];
		
		$count=intval($all_param_arr["count"]);
		
		//*** получаем количество студентов в базе
		$res=t_sql_f(array
		(
			"db"=>$GLOBALS["db_ssn"],
			"sql_query"=>"select count(*) as cnt from students",
		));
		
		t_deb_flog(__LINE__, __FILE__, $res, "t_ot");
		
		$stud_count=$res[0]["cnt"];
		
		//*** формируем sql запрос для заполнения лайков случайным образом
		$sql="insert `likes` (`like_id`, `liked_id`) values \r\n";
		$values="";
		$i=0;
		while($i++<$count)
		{
			$values=tuti_f_str_join
			(
				$values, 
				",\r\n", 
				"(".rand(0,$stud_count).", ".rand(0,$stud_count).")"
			);
		}
		
		$sql.=$values;
		
		t_deb_flog(__LINE__, __FILE__, $sql, "f");
		
		tdeb_f_check("fill_stud_tab","запрос сформирован");
		
		//*** выполняем сформированный запрос
		$q=t_sql_f(array
		(
			"db"=>$GLOBALS["db_ssn"],
			"sql_query"=>$sql,
		));
		
		tdeb_f_check("fill_stud_tab","запрос выполнен");
		
		//формируем ответ
		
		$out=array("log"=>"", "res"=>$res);
		tdeb_f_check_out("fill_stud_tab",&$out["log"]);
		
		//преобразуем в json и вкидываем в вывод
		$GLOBALS["content"].=json_encode($out);
		
		t_deb_flog(__LINE__, __FILE__, $q, "t_ot");
		//tdeb_f_check_out("fill_stud_tab",&$GLOBALS["content"]);
		//t_deb_flog(__LINE__, __FILE__, tdeb_f_check_get("save_2_bd"), "f");
	}
	
	function t_ot_f_query($args)
	{
		t_deb_flog(__LINE__, __FILE__, $args, "t_ot");
		
		$all_param_arr=$args["all_param_arr"];	//все входящие параметры
		//формат выдачи json/html
		$format=tuti_f_is_empty($all_param_arr["format"])?"json":$all_param_arr["format"];
		$sql=$args["sql"];						//sql к выполнению
		
		tdeb_f_check("fill_stud_tab","запрос");
		
		//*** выполняем переданный sql
		$res=t_sql_f(array
		(
			"db"=>$GLOBALS["db_ssn"],
			"sql_query"=>$sql,
		));
		
		tdeb_f_check("fill_stud_tab","запрос выполнен");
		
		//формируем ответ
		//если формат json
		if (stripos($format, "json")===0)
		{
			//размещаем все в структуре и возвращаем ответ
			$out=array("log"=>"", "res"=>$res);
			tdeb_f_check_out("fill_stud_tab",&$out["log"]);
			
			//преобразуем в json и вкидываем в вывод
			$GLOBALS["content"].=json_encode($out);
		}
		else
		{
			$out="";
			tdeb_f_check_out("fill_stud_tab",&$out, true);
			$GLOBALS["content"].=$out;
			
			//преобразуем в json и вкидываем в вывод
			
			$GLOBALS["content"].=trdb_mysql_fhtml_table_1($res);
			//$GLOBALS["content"].=json_encode($res);
		}
		
		t_deb_flog(__LINE__, __FILE__, $res, "t_ot");
	}


	//*** варианты решения подзадачи А
	function f_student_A_sql_1($args)
	{
		t_deb_flog(__LINE__, __FILE__, $args, "t_ot");
		
		$all_param_arr=$args["all_param_arr"];
		
		//границы выборки
		$limit=$all_param_arr["limit"];
		$limit_0=tuti_f_is_empty($limit)?0:intval($limit[0]);
		$limit_1=tuti_f_is_empty($limit)?100:intval($limit[1]);
		
		tdeb_f_check("fill_stud_tab","запрос");
		
		$args["sql"]=
		"
			select 
				name, grade 
			 from 
				`students` s
			 where 
			  
				(
					select 
						count(liked_id) 
					from 
						`likes` l
					where 
						l.liked_id=s.id
				)>1 
			 limit $limit_0,$limit_1
		";
		
		//выполняем sql
		t_ot_f_query($args);
	}

	function f_student_A_sql_2($args)
	{
		t_deb_flog(__LINE__, __FILE__, $args, "t_ot");
		
		$all_param_arr=$args["all_param_arr"];
		
		//границы выборки
		$limit=$all_param_arr["limit"];
		$limit_0=tuti_f_is_empty($limit)?0:intval($limit[0]);
		$limit_1=tuti_f_is_empty($limit)?100:intval($limit[1]);
		
		tdeb_f_check("fill_stud_tab","запрос");
		
		$args["sql"]=
		"
			select 
				name, grade 
			 from 
			 `students` s
			 where  
				(
					select 
						count(ls1.liked_id)
					from 
						`likes` ls1 
					where 
					ls1.liked_id=s.id
					and (
						select 
							count(distinct like_id) 
						from 
							`likes` ls2 
						where 
							ls1.liked_id=ls2.liked_id
					)>1 
				)>0
			 limit $limit_0,$limit_1
		";
		
		//выполняем sql
		t_ot_f_query($args);
	}

	function f_student_A_sql_3($args)
	{
		t_deb_flog(__LINE__, __FILE__, $args, "t_ot");
		
		$all_param_arr=$args["all_param_arr"];
		
		//границы выборки
		$limit=$all_param_arr["limit"];
		$limit_0=tuti_f_is_empty($limit)?0:intval($limit[0]);
		$limit_1=tuti_f_is_empty($limit)?100:intval($limit[1]);
		
		tdeb_f_check("fill_stud_tab","запрос");
		
		$args["sql"]=
		"
			select 
				name, grade 
			from 
				`students` 
			where 
				id in 
				(
					select 
						liked_id 
					from 
						`likes` ls1 
					where 
					(
						select 
							count(distinct like_id) 
						from 
							`likes` ls2 
						where 
							ls1.liked_id=ls2.liked_id
					)>1 
				)
			limit $limit_0,$limit_1
		";
		
		//выполняем sql
		t_ot_f_query($args);
	}

	//*** варианты решения подзадачи Б
	
	function f_student_B_sql_1($args)
	{
		t_deb_flog(__LINE__, __FILE__, $args, "t_ot");
		
		$all_param_arr=$args["all_param_arr"];
		
		//границы выборки
		$limit=$all_param_arr["limit"];
		$limit_0=tuti_f_is_empty($limit)?0:intval($limit[0]);
		$limit_1=tuti_f_is_empty($limit)?100:intval($limit[1]);
		
		tdeb_f_check("fill_stud_tab","запрос");
		
		$args["sql"]=
		"
			select 
					name, grade 
				from 
					`students` s
				where 
					(
						select 
							l1.like_id 
						from 
							`likes` l1 
						where 
 						s.id=l1.like_id
 						and
						(
							select 
								count(*)
							from 
								`likes` l2 
							where 
								l1.liked_id=l2.like_id
						)=0
					)>1
				limit $limit_0,$limit_1
		";
		
		//выполняем sql
		t_ot_f_query($args);
	}

	
	//*** варианты решения подзадачи В
	function f_student_C_sql_1($args)
	{
		t_deb_flog(__LINE__, __FILE__, $args, "t_ot");
		
		$all_param_arr=$args["all_param_arr"];
		
		//границы выборки
		$limit=$all_param_arr["limit"];
		$limit_0=tuti_f_is_empty($limit)?0:intval($limit[0]);
		$limit_1=tuti_f_is_empty($limit)?100:intval($limit[1]);
		
		tdeb_f_check("fill_stud_tab","запрос");
		
		$args["sql"]=
		"
			select 
					name, grade 
				from 
					`students` s
				where 
					(
						select 
							count(*)
						from 
							`likes` l1 
						where 
 						s.id=l1.like_id
 						
					)=0
					and 
					(
						select
							count(*)
						from
							`likes` l2
						where
						s.id=l2.liked_id
					)=0
				limit $limit_0,$limit_1
		";
		
		//выполняем sql
		t_ot_f_query($args);
	}
	
	
	//*** виджет новостей
	function f_news_fill($args)
	{
		$all_param_arr=$args["all_param_arr"];
		
		//количество возвращаемых новостей
		$rss_url=$all_param_arr["rss_url"];
		
		t_deb_flog(__LINE__, __FILE__, $rss_url, "t_ot");
		
		$rss = fetch_rss($rss_url);
		
		tdeb_f_check("fill_stud_tab","запрос");
		
		$i=100;
		while($i-->0)
		{
			//*** формируем sql запрос добавление новостей в базу
			$sql="insert `news` (`dtcre`, `name`, `content`, `timestamp`) values \r\n";
			$values="";
			foreach($rss->items as $item)
			{
				t_deb_flog(__LINE__, __FILE__, $item["pubdate"], "t_ot2");
				t_deb_flog(__LINE__, __FILE__, date_timestamp_get(new DateTime($item["pubdate"])), "t_ot2");
				//$sql.="('".$item["first_name"]." ".$item["last_name"]."', ".rand(1,5)."),\r\n";
				$values=tuti_f_str_join($values, ",\r\n", 
						"(FROM_UNIXTIME(".date_timestamp_get(new DateTime($item["pubdate"]))."), ".
							"'".mysql_escape_string($item["title"])."', ".
							"'".mysql_escape_string(json_encode($item))."', ".
							"FROM_UNIXTIME(".$item["date_timestamp"].")".")");
			}
		
		
		
			$sql.=$values;
			
			t_deb_flog(__LINE__, __FILE__, $sql, "t_ot");
			
			tdeb_f_check("fill_stud_tab","запрос сформирован");
			
			//*** выполняем сформированный запрос
			$q=t_sql_f(array
			(
				"db"=>$GLOBALS["db_ssn"],
				"sql_query"=>$sql,
			));
			
			tdeb_f_check("fill_stud_tab","запрос выполнен");
		
		}
		
		t_deb_flog(__LINE__, __FILE__, $q, "f");
		
		$out=array("log"=>"", "res"=>array());
		tdeb_f_check_out("fill_stud_tab",&$out["log"]);
		
		//преобразуем в json и вкидываем в вывод
		$GLOBALS["content"].=json_encode($out);
		
		//print_r($rss);
	}
	
	function f_news_fill_2($args)
	{
		$all_param_arr=$args["all_param_arr"];
		
		//количество возвращаемых новостей
		$rss_url=$all_param_arr["rss_url"];
		
		t_deb_flog(__LINE__, __FILE__, $rss_url, "t_ot");
		
		$rss = fetch_rss($rss_url);
		
		tdeb_f_check("fill_stud_tab","запрос");
		
		set_time_limit(300);
		
		$i=100;
		while($i-->0)
		{
			//*** формируем sql запрос добавление новостей в базу
			$sql="insert `tab_news` (`id`, `dtcre`, `name`, `content`, `timestamp`) values \r\n";
			$values="";
			foreach($rss->items as $item)
			{
				t_deb_flog(__LINE__, __FILE__, $item["pubdate"], "t_ot2");
				t_deb_flog(__LINE__, __FILE__, date_timestamp_get(new DateTime($item["pubdate"])), "t_ot2");
				//t_deb_flog(__LINE__, __FILE__, tstore_fid($GLOBALS["db_ssn"], "tab_news&id"), "t_ot4");
				//$sql.="('".$item["first_name"]." ".$item["last_name"]."', ".rand(1,5)."),\r\n";
				$id=tstore_fid_1($GLOBALS["db_ssn"], "tab_news&id");
				t_deb_flog(__LINE__, __FILE__, $id, "t_ot5");
				$values=tuti_f_str_join($values, ",\r\n", 
						"(".$id.", ".
						"FROM_UNIXTIME(".date_timestamp_get(new DateTime($item["pubdate"]))."), ".
							"'".mysql_escape_string($item["title"])."', ".
							"'".mysql_escape_string(json_encode($item))."', ".
							"FROM_UNIXTIME(".$item["date_timestamp"].")".")");
							
				//t_deb_flog(__LINE__, __FILE__, $values, "t_ot5");
			}
		
		
		
			$sql.=$values;
			
			t_deb_flog(__LINE__, __FILE__, $sql, "t_ot4");
			
			//continue;
			
			tdeb_f_check("fill_stud_tab","запрос сформирован");
			
			//*** выполняем сформированный запрос
			$q=t_sql_f(array
			(
				"db"=>$GLOBALS["db_ssn"],
				"sql_query"=>$sql,
			));
			
			tdeb_f_check("fill_stud_tab","запрос выполнен");
		
		}
		
		t_deb_flog(__LINE__, __FILE__, $q, "f");
		
		$out=array("log"=>"", "res"=>array());
		tdeb_f_check_out("fill_stud_tab",&$out["log"]);
		
		//преобразуем в json и вкидываем в вывод
		$GLOBALS["content"].=json_encode($out);
		
		//print_r($rss);
	}
	
	
	function f_news_1($args)
	{
		t_deb_flog(__LINE__, __FILE__, $args, "t_ot");
		
		$all_param_arr=$args["all_param_arr"];
		
		//количество возвращаемых новостей
		$count=intval($all_param_arr["count"]);
		
		tdeb_f_check("fill_stud_tab","начало");
		
		
		//формат выдачи json/html
		$format=tuti_f_is_empty($all_param_arr["format"])?"json":$all_param_arr["format"];
		
		//текущее количество строк новостей
		$lim_res=t_sql_f(array
		(
			"db"=>$GLOBALS["db_ssn"],
			"sql_query"=>"select count(*) as count from news",
		));
				
		tdeb_f_check("fill_stud_tab","получено количество строк в таблице");
		
		//смещение выборки количество строк- количество вибираемых
		$limit_0=$lim_res[0]["count"]-$count;
		
		if ($limit_0<0) return;
		
		//
		$limit_1=$count;
		
		
		//*** выполняем переданный sql
		$res=t_sql_f(array
		(
			"db"=>$GLOBALS["db_ssn"],
			"sql_query"=>"
							select 
									id, dtcre,name, content
								from 
									`news` n
								limit $limit_0,$limit_1
						",
		));
		
		tdeb_f_check("fill_stud_tab","запрос новостей");
		
		$news=array();
		foreach($res as $item)
		{
			
			array_push($news, json_decode($item["content"]));
		}
		
		t_deb_flog(__LINE__, __FILE__, $news, "t_ot3");
		
		//формируем ответ
		//если формат json
		if (stripos($format, "json")===0)
		{
			//размещаем все в структуре и возвращаем ответ
			$out=array("log"=>"", "res"=>$news);
			tdeb_f_check_out("fill_stud_tab",&$out["log"]);
			
			//преобразуем в json и вкидываем в вывод
			$GLOBALS["content"].=json_encode($out);
		}
		else
		{
			$out="";
			tdeb_f_check_out("fill_stud_tab",&$out, true);
			$GLOBALS["content"].=$out;
			//преобразуем в json и вкидываем в вывод
			$GLOBALS["content"].=json_encode($res);
		}
		
	}
	
	function f_news_2($args)
	{
		t_deb_flog(__LINE__, __FILE__, $args, "t_ot");
		
		$all_param_arr=$args["all_param_arr"];
		
		//количество возвращаемых новостей
		$count=intval($all_param_arr["count"]);
		
		tdeb_f_check("fill_stud_tab","начало");
		
		
		//формат выдачи json/html
		$format=tuti_f_is_empty($all_param_arr["format"])?"json":$all_param_arr["format"];
		
		//текущее количество строк новостей
		$lim_res=t_sql_f(array
		(
			"db"=>$GLOBALS["db_ssn"],
			"sql_query"=>"select count(*) as count from news",
		));
				
		tdeb_f_check("fill_stud_tab","получено количество строк в таблице");
		
		//смещение выборки количество строк- количество вибираемых
		$limit_0=$lim_res[0]["count"]-$count;
		
		if ($limit_0<0) return;
		
		//
		$limit_1=$count;
		
		
		//*** выполняем переданный sql
		$res=t_sql_f(array
		(
			"db"=>$GLOBALS["db_ssn"],
			"sql_query"=>"
							select 
									id, dtcre,name, content
								from 
									`news` n
								order by id desc
							limit 0, $count 
						",
		));
		
		tdeb_f_check("fill_stud_tab","запрос новостей");
		
		$news=array();
		foreach($res as $item)
		{
			
			array_push($news, json_decode($item["content"]));
		}
		
		t_deb_flog(__LINE__, __FILE__, $news, "t_ot3");
		
		//формируем ответ
		//если формат json
		if (stripos($format, "json")===0)
		{
			//размещаем все в структуре и возвращаем ответ
			$out=array("log"=>"", "res"=>$news);
			tdeb_f_check_out("fill_stud_tab",&$out["log"]);
			
			//преобразуем в json и вкидываем в вывод
			$GLOBALS["content"].=json_encode($out);
		}
		else
		{
			$out="";
			tdeb_f_check_out("fill_stud_tab",&$out, true);
			$GLOBALS["content"].=$out;
			//преобразуем в json и вкидываем в вывод
			$GLOBALS["content"].=json_encode($res);
		}
		
	}
	
	function f_news_3($args)
	{
		t_deb_flog(__LINE__, __FILE__, $args, "t_ot");
		
		$all_param_arr=$args["all_param_arr"];
		
		//количество возвращаемых новостей
		$count=intval($all_param_arr["count"]);
		
		tdeb_f_check("fill_stud_tab","начало");
		
		
		//формат выдачи json/html
		$format=tuti_f_is_empty($all_param_arr["format"])?"json":$all_param_arr["format"];
		
		//текущее количество строк новостей
		$lim_res=t_sql_f(array
		(
			"db"=>$GLOBALS["db_ssn"],
			"sql_query"=>"select count(*) as count from news",
		));
				
		tdeb_f_check("fill_stud_tab","получено количество строк в таблице");
		
		//смещение выборки количество строк- количество вибираемых
		$limit_0=$lim_res[0]["count"]-$count;
		
		if ($limit_0<0) return;
		
		//
		$limit_1=$count;
		
		
		//*** выполняем переданный sql
		$res=t_sql_f(array
		(
			"db"=>$GLOBALS["db_ssn"],
			"sql_query"=>"
							select 
									id, dtcre,name, content
								from 
									`news` n
								order by id desc
							limit 0, $count 
						",
		));
		
		tdeb_f_check("fill_stud_tab","запрос новостей");
		
		$news=array();
		foreach($res as $item)
		{
			
			array_push($news, json_decode($item["content"]));
		}
		
		t_deb_flog(__LINE__, __FILE__, $news, "t_ot3");
		
		//формируем ответ
		//если формат json
		if (stripos($format, "json")===0)
		{
			//размещаем все в структуре и возвращаем ответ
			$out=array("log"=>"", "res"=>$news);
			tdeb_f_check_out("fill_stud_tab",&$out["log"]);
			
			//преобразуем в json и вкидываем в вывод
			$GLOBALS["content"].=json_encode($out);
		}
		else
		{
			$out="";
			tdeb_f_check_out("fill_stud_tab",&$out, true);
			$GLOBALS["content"].=$out;
			//преобразуем в json и вкидываем в вывод
			$GLOBALS["content"].=json_encode($res);
		}
		
	}
	

	function f_tab_news_1($args)
	{
		t_deb_flog(__LINE__, __FILE__, $args, "t_ot");
		
		$all_param_arr=$args["all_param_arr"];
		
		//количество возвращаемых новостей
		$count=intval($all_param_arr["count"]);
		
		$tab_name="tab_news";
		
		tdeb_f_check("fill_stud_tab","начало");
		
		
		//формат выдачи json/html
		$format=tuti_f_is_empty($all_param_arr["format"])?"json":$all_param_arr["format"];
		
		//текущее количество строк новостей
		$lim_res=t_sql_f(array
		(
			"db"=>$GLOBALS["db_ssn"],
			"sql_query"=>"select count(*) as count from $tab_name",
		));
				
		tdeb_f_check("fill_stud_tab","получено количество строк в таблице");
		
		//смещение выборки количество строк- количество вибираемых
		$limit_0=$lim_res[0]["count"]-$count;
		
		if ($limit_0<0) return;
		
		//
		$limit_1=$count;
		
		
		//*** выполняем переданный sql
		$res=t_sql_f(array
		(
			"db"=>$GLOBALS["db_ssn"],
			"sql_query"=>"
							select 
									id, dtcre,name, content
								from 
									`$tab_name` n FORCE INDEX (PRIMARY)
								limit $limit_0,$limit_1
						",
		));
		
		tdeb_f_check("fill_stud_tab","запрос новостей");
		
		$news=array();
		foreach($res as $item)
		{
			
			array_push($news, json_decode($item["content"]));
		}
		
		t_deb_flog(__LINE__, __FILE__, $news, "t_ot3");
		
		//формируем ответ
		//если формат json
		if (stripos($format, "json")===0)
		{
			//размещаем все в структуре и возвращаем ответ
			$out=array("log"=>"", "res"=>$news);
			tdeb_f_check_out("fill_stud_tab",&$out["log"]);
			
			//преобразуем в json и вкидываем в вывод
			$GLOBALS["content"].=json_encode($out);
		}
		else
		{
			$out="";
			tdeb_f_check_out("fill_stud_tab",&$out, true);
			$GLOBALS["content"].=$out;
			//преобразуем в json и вкидываем в вывод
			$GLOBALS["content"].=json_encode($res);
		}
		
	}
	
	function f_tab_news_2($args)
	{
		t_deb_flog(__LINE__, __FILE__, $args, "t_ot");
		
		$all_param_arr=$args["all_param_arr"];
		
		//количество возвращаемых новостей
		$count=intval($all_param_arr["count"]);
		
		$tab_name="tab_news";
		
		tdeb_f_check("fill_stud_tab","начало");
		
		
		//формат выдачи json/html
		$format=tuti_f_is_empty($all_param_arr["format"])?"json":$all_param_arr["format"];
		
		
		//*** выполняем переданный sql
		$res=t_sql_f(array
		(
			"db"=>$GLOBALS["db_ssn"],
			"sql_query"=>"
							select 
									id, dtcre,name, content
								from 
									`$tab_name` n
								order by id desc
							limit 0, $count 
						",
		));
		
		tdeb_f_check("fill_stud_tab","запрос новостей");
		
		$news=array();
		foreach($res as $item)
		{
			
			array_push($news, json_decode($item["content"]));
		}
		
		t_deb_flog(__LINE__, __FILE__, $news, "t_ot3");
		
		//формируем ответ
		//если формат json
		if (stripos($format, "json")===0)
		{
			//размещаем все в структуре и возвращаем ответ
			$out=array("log"=>"", "res"=>$news);
			tdeb_f_check_out("fill_stud_tab",&$out["log"]);
			
			//преобразуем в json и вкидываем в вывод
			$GLOBALS["content"].=json_encode($out);
		}
		else
		{
			$out="";
			tdeb_f_check_out("fill_stud_tab",&$out, true);
			$GLOBALS["content"].=$out;
			//преобразуем в json и вкидываем в вывод
			$GLOBALS["content"].=json_encode($res);
		}
		
	}
	
	function f_tab_news_3($args)
	{
		t_deb_flog(__LINE__, __FILE__, $args, "t_ot");
		
		$all_param_arr=$args["all_param_arr"];
		
		//количество возвращаемых новостей
		$count=intval($all_param_arr["count"]);
		
		$tab_name="tab_news";
		
		tdeb_f_check("fill_stud_tab","начало");
		
		
		//формат выдачи json/html
		$format=tuti_f_is_empty($all_param_arr["format"])?"json":$all_param_arr["format"];
		
		//текущее количество строк новостей
		$tab_id_gen=t_sql_f(array
		(
			"db"=>$GLOBALS["db_ssn"],
			"sql_query"=>"select gen_id from tab_id_gen where tab like '$tab_name&id'",
		));
				
		tdeb_f_check("fill_stud_tab","получен последний выделенный id");
		
		$after_id=(int)$tab_id_gen[0]["gen_id"]-(int)$count-1;
		
		t_deb_flog(__LINE__, __FILE__, $after_id, "t_ot_6");
		
		//*** выполняем переданный sql
		$res=t_sql_f(array
		(
			"db"=>$GLOBALS["db_ssn"],
			"sql_query"=>"
							select 
								id, dtcre,name, content
							from 
								`$tab_name` n
							where 
								id>$after_id
						",
		));
		
		tdeb_f_check("fill_stud_tab","запрос новостей");
		
		$news=array();
		foreach($res as $item)
		{
			
			array_push($news, json_decode($item["content"]));
		}
		
		t_deb_flog(__LINE__, __FILE__, $news, "t_ot3");
		
		//формируем ответ
		//если формат json
		if (stripos($format, "json")===0)
		{
			//размещаем все в структуре и возвращаем ответ
			$out=array("log"=>"", "res"=>$news);
			tdeb_f_check_out("fill_stud_tab",&$out["log"]);
			
			//преобразуем в json и вкидываем в вывод
			$GLOBALS["content"].=json_encode($out);
		}
		else
		{
			$out="";
			tdeb_f_check_out("fill_stud_tab",&$out, true);
			$GLOBALS["content"].=$out;
			//преобразуем в json и вкидываем в вывод
			$GLOBALS["content"].=json_encode($res);
		}
		
	}
	
	
	//вывод в html
	 function trdb_mysql_fhtml_table_1(&$q)
	 {
		echo "<table style=\"border:solid\">";
		
		//названия полей шапка
		echo "<tr>";
		$i=0;
		while($i<mysql_num_fields($q))
		{
			$tab_col=mysql_fetch_field($q, $i);
			echo "<td>$tab_col->name</td>";
			$i++;
		}
		echo "</tr>";
		
		// Выводим таблицу:
		$i=0;
		while($tab_row=mysql_fetch_row($q))
		{
			echo "<tr>";
			//$tab_row=array();
			//$tab_row[]=mysql_fetch_array($q);
			//$tab_row = mysql_fetch_array($q);
			//$tab[]=$tab_row;
			foreach($tab_row as $val)
			{
				echo "<td>$val</td>";
				//echo "<td>$f[idtab_pi]</td><td>$f[id_pi]</td><td>$f[dtcre]</td>";
				//echo "<td>$f[deleted]</td><td>$f[kvl]</td><td>$f[f]</td>";
			}
			echo "</tr>";
			$i++;
		}
		echo "</table>";
		
		mysql_data_seek($q, 0);
		
	 }

?> 
