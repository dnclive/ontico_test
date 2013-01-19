<?php
/*
 * t_sql.php
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
	require_once(dirname(__FILE__)."/../josi_lib/php/dev/trdb_mysql.php");		//библиотека mysql

	function t_sql_f($args)
	{
		$db=$args["db"];
		$sql_query=$args["sql_query"];
		
		pj_deb_flog3(__LINE__, __FILE__, "trdb_mysql_fexec", "trdb_mysql_fexec");
		
		if (!mysql_connect(	$db["db_server"],
						$db["db_user"],
						$db["db_pass"]))
		{
			echo("ошбика подключения к серверу БД ".$db['db_server'].mysql_error());
			pj_deb_flog3(__LINE__, __FILE__, "ошбика подключения к серверу БД {$sql_query['db_server']}", 
											"trdb_mysql_fexec");
		}
		
		
		if (!mysql_select_db($db["db_name"]))
		{
			echo("ошбика подключения к БД {$db['db_name']} ".mysql_error());
			pj_deb_flog3(__LINE__, __FILE__, "ошбика подключения к БД {$sql_query['db_name']}", 
											"trdb_mysql_fexec");
		}
		
		//устанавливаем кодировку взаимодействия
		mysql_query("set names utf8");
		
		t_deb_flog(__LINE__, __FILE__, $sql_query, "t_sql");
		
		try 
		{  
			$q=mysql_query($sql_query); 
			//$q=mysql_unbuffered_query($sql_query); 
			
		} catch (Exception $ex) 
		{ 
				//echo $ex; 
				echo("\r\n\r\n Ошбика выполнения запроса. ".mysql_error()."\r\n".$sql_query."\r\n\r\n");
		}  
		
		
		
		if (!$q)
		{
			echo("\r\n\r\n Ошбика выполнения запроса. ".mysql_error()."\r\n".$sql_query."\r\n\r\n");
		}
		
		mysql_close();
		
		$q_res=trdb_mysql_ftab_2_arr($q);
		
		//mysql_data_seek($q, 0);
		
		return $q_res;
		
	}

	function t_sql_f_truncate_tab($args)
	{
		$db=$args["db"];
		$tab_name=$args["tab_name"];
		
		//очень не люблю удаляющие коменды
		//***** ДАННАЯ КОМАНДА УДАЛЯЕТ БЕЗВОЗВРАТНО ВСЕ ДАННЫЕ ТАБЛИЦЫ, ПОДУМАЙ!!!
		return;
		
		//*** удаляем все данные из таблицы student
		$q=t_sql_f(array
		(
			"db"=>$db,
			"sql_query"=>"delete from `students`",
		));
		
		t_deb_flog(__LINE__, __FILE__, $q, "f");
		
	}
	
	function tstore_fid_1($db, $tab)
	{
		pj_deb_flog3(__LINE__, __FILE__, $tab, "tkvl3");
		pj_deb_flog3(__LINE__, __FILE__, $db, "tkvl3");
		pj_deb_flog3(__LINE__, __FILE__, "select * from tab_id_gen where tab like '$tab'", "tkvl3");
		
		//print_r("123");
		//print_r($db);
		
		/*
		//пытаемся получить строку генератора указанной таблицы для указанного id
		$q=trdb_mysql_fexec_9(array(
										"db"=>$db,
										"sql_query"=>"select * from tab_id_gen where tab like '$tab'"
									));
		
		//если не возвращен ресурс то запрос не выполнился (нет сервера, базы, таблицы и тд)
		//возвращаем null
		if (is_null($q)) return null;
		//pj_deb_flog3(__LINE__, __FILE__, is_null($q), "tkvl3");
		
		t_deb_flog(__LINE__, __FILE__, trdb_mysql_ftab_2_arr($q), "t_ot5");
		
		//если нет генератора для данного id таблицы  то создаем его
		if (mysql_num_rows($q)===0&&!$GLOBALS["trdb_mysql_connection_error"])	
		{
			$new_id_gen=tstore_fid($db, "tab_id_gen&id");
			$q=trdb_mysql_fexec_9(array(
									"db"=>$db,
									"sql_query"=>"insert tab_id_gen (id, tab, gen_id) ".
													" values($new_id_gen, '$tab', 0)"
								));
		}
		*/
		//теперь в любом случаее есть строка нужного нам генератора
		//выбираем ее и запоминаем текущий id
		
		$q_arr=t_sql_f(array
		(
			"db"=>$db,
			"sql_query"=>"select * from tab_id_gen where tab like '$tab'"
		));
		
		//$q_arr=trdb_mysql_ftab_2_arr($q);
		
		//t_deb_flog(__LINE__, __FILE__, $q_arr, "t_ot5");
		
		//текущий id - возвращается наверх
		$id=$q_arr[0]["gen_id"];
		
		pj_deb_flog3(__LINE__, __FILE__, "update tab_id_gen set gen_id=".($id+1)." where tab like '$tab'", "tkvl3");
		
		pj_deb_flog3(__LINE__, __FILE__, $id, "tkvl3");
		
		//обновляем текущий id+1
		$q=t_sql_f(array
		(
			"db"=>$db,
			"sql_query"=>"update tab_id_gen set gen_id=".($id+1)." where tab like '$tab'"
		));
		
		pj_deb_flog3(__LINE__, __FILE__, $q_arr, "tkvl3");
		
		
		
		return $id;
		
	}

?>
