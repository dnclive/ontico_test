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
		
		$count=$all_param_arr["count"];
		
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
		
		t_deb_flog(__LINE__, __FILE__, $q, "t_ot");
		tdeb_f_check_out("fill_stud_tab",&$GLOBALS["content"]);
		//t_deb_flog(__LINE__, __FILE__, tdeb_f_check_get("save_2_bd"), "f");
	}
	
	//*** варианты решения подзадачи А
	function f_student_A_sql_1($first_name, $last_name)
	{
		t_deb_flog(__LINE__, __FILE__, $args, "t_ot");
		
		$all_param_arr=$args["all_param_arr"];
		
		//границы выборки
		$limit=$all_param_arr["limit"];
		$limit_0=tuti_f_is_empty($limit)?0:$limit[0];
		$limit_1=tuti_f_is_empty($limit)?100:$limit[1];
		
		tdeb_f_check("fill_stud_tab","запрос");
		
		//*** получаем количество студентов в базе
		$res=t_sql_f(array
		(
			"db"=>$GLOBALS["db_ssn"],
			"sql_query"=>
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
			",
		));
		
		tdeb_f_check("fill_stud_tab","запрос выполнен");
		
		//формируем ответ
		
		$out=array("log"=>"", "res"=>$res);
		tdeb_f_check_out("fill_stud_tab",&$out["log"]);
		
		//преобразуем в json и вкидываем в вывод
		$GLOBALS["content"].=json_encode($out);
		
		t_deb_flog(__LINE__, __FILE__, $res, "t_ot");
	}

	function f_student_A_sql_2($first_name, $last_name)
	{
		t_deb_flog(__LINE__, __FILE__, $args, "t_ot");
		
		$all_param_arr=$args["all_param_arr"];
		
		//границы выборки
		$limit=$all_param_arr["limit"];
		$limit_0=tuti_f_is_empty($limit)?0:$limit[0];
		$limit_1=tuti_f_is_empty($limit)?100:$limit[1];
		
		tdeb_f_check("fill_stud_tab","запрос");
		
		//*** получаем количество студентов в базе
		$res=t_sql_f(array
		(
			"db"=>$GLOBALS["db_ssn"],
			"sql_query"=>
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
			",
		));
		
		tdeb_f_check("fill_stud_tab","запрос выполнен");
		
		//формируем ответ
		
		$out=array("log"=>"", "res"=>$res);
		tdeb_f_check_out("fill_stud_tab",&$out["log"]);
		
		//преобразуем в json и вкидываем в вывод
		$GLOBALS["content"].=json_encode($out);
		
		t_deb_flog(__LINE__, __FILE__, $res, "t_ot");
	}

	function f_student_A_sql_3($first_name, $last_name)
	{
		t_deb_flog(__LINE__, __FILE__, $args, "t_ot");
		
		$all_param_arr=$args["all_param_arr"];
		
		//границы выборки
		$limit=$all_param_arr["limit"];
		$limit_0=tuti_f_is_empty($limit)?0:$limit[0];
		$limit_1=tuti_f_is_empty($limit)?100:$limit[1];
		
		tdeb_f_check("fill_stud_tab","запрос");
		
		//*** получаем количество студентов в базе
		$res=t_sql_f(array
		(
			"db"=>$GLOBALS["db_ssn"],
			"sql_query"=>
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
			",
		));

		tdeb_f_check("fill_stud_tab","запрос выполнен");
		
		//формируем ответ
		
		$out=array("log"=>"", "res"=>$res);
		tdeb_f_check_out("fill_stud_tab",&$out["log"]);
		
		//преобразуем в json и вкидываем в вывод
		$GLOBALS["content"].=json_encode($out);
		
		t_deb_flog(__LINE__, __FILE__, $res, "t_ot");
	}

	//*** варианты решения подзадачи Б
	function f_student_B_sql_1($first_name, $last_name)
	{
		t_deb_flog(__LINE__, __FILE__, $args, "t_ot");
		
		$all_param_arr=$args["all_param_arr"];
		
		//границы выборки
		$limit=$all_param_arr["limit"];
		$limit_0=tuti_f_is_empty($limit)?0:$limit[0];
		$limit_1=tuti_f_is_empty($limit)?100:$limit[1];
		
		tdeb_f_check("fill_stud_tab","запрос");
		
		//*** получаем количество студентов в базе
		$res=t_sql_f(array
		(
			"db"=>$GLOBALS["db_ssn"],
			"sql_query"=>
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
			",
		));
		
		tdeb_f_check("fill_stud_tab","запрос выполнен");
		
		//формируем ответ
		
		$out=array("log"=>"", "res"=>$res);
		tdeb_f_check_out("fill_stud_tab",&$out["log"]);
		
		//преобразуем в json и вкидываем в вывод
		$GLOBALS["content"].=json_encode($out);
		
		t_deb_flog(__LINE__, __FILE__, $res, "t_ot");
	}
	
	//*** варианты решения подзадачи В
	function f_student__sql_1($first_name, $last_name)
	{
		t_deb_flog(__LINE__, __FILE__, $args, "t_ot");
		
		$all_param_arr=$args["all_param_arr"];
		
		//границы выборки
		$limit=$all_param_arr["limit"];
		$limit_0=tuti_f_is_empty($limit)?0:$limit[0];
		$limit_1=tuti_f_is_empty($limit)?100:$limit[1];
		
		tdeb_f_check("fill_stud_tab","запрос");
		
		//*** получаем количество студентов в базе
		$res=t_sql_f(array
		(
			"db"=>$GLOBALS["db_ssn"],
			"sql_query"=>
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
			",
		));
		
		tdeb_f_check("fill_stud_tab","запрос выполнен");
		
		//формируем ответ
		
		$out=array("log"=>"", "res"=>$res);
		tdeb_f_check_out("fill_stud_tab",&$out["log"]);
		
		//преобразуем в json и вкидываем в вывод
		$GLOBALS["content"].=json_encode($out);
		
		t_deb_flog(__LINE__, __FILE__, $res, "t_ot");
	}
	


	function t_ot_get_stud_A($args)
	{
		
	}

?> 
