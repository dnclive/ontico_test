<?php
/*
 *      config.php
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


//local
$db_ssn=array
(
	"db_server"=>"localhost",
	"db_user"=>"root",
	"db_pass"=>"123",
	"db_name"=>"db_ssn",
);

/*
//jino
$db_ssn=array
(
	"db_server"=>"localhost",
	"db_user"=>"045222217_ontiko",
	"db_pass"=>"u05JrCUfFN",
	"db_name"=>"kibicom_ontiko",
);
*/
//массив доступных к вызову функций
//f - имя функции в коде
//q_f - массив псевдонимов функции, которые могут быть указаны в запросе
// и при этом будет вызвана f, если q_f пуст можно указать в запросе f

$allow_f=array
(
	array("f"=>"t_ot_fill_student_tab",			"q_f"=>array()),
	array("f"=>"t_ot_fill_likes_random",		"q_f"=>array()),
	array("f"=>"f_student_A_sql_1",				"q_f"=>array()),
	array("f"=>"f_student_A_sql_2",				"q_f"=>array()),
	array("f"=>"f_student_A_sql_3",				"q_f"=>array()),
	array("f"=>"f_student_B_sql_1",				"q_f"=>array()),
	array("f"=>"f_student_C_sql_1",				"q_f"=>array()),
	array("f"=>"f_news_1",						"q_f"=>array()),
	array("f"=>"f_news_2",						"q_f"=>array()),
	array("f"=>"f_news_3",						"q_f"=>array()),
	array("f"=>"f_tab_news_1",					"q_f"=>array()),
	array("f"=>"f_tab_news_2",					"q_f"=>array()),
	array("f"=>"f_tab_news_3",					"q_f"=>array()),
	array("f"=>"f_news_fill",					"q_f"=>array()),
	array("f"=>"f_news_fill_2",					"q_f"=>array()),
);

?>
