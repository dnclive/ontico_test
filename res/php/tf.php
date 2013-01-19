<?php
/*
 * tf.php
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
	require_once(dirname(__FILE__)."/../josi_lib/php/dev/tdeb.php");		//библиотека отладки
	require_once(dirname(__FILE__)."/../josi_lib/php/dev/tuti.php");		//библиотека разных полезных функций
	
	function tf_f($args)
	{
		global $allow_f;
		$f=$args["all_param_arr"]["f"];
		
		t_deb_flog(__LINE__, __FILE__, $f, "tf");
		
		//ищем реальное имя функции в доступных
		$exec_f_name="";
		foreach($allow_f as $f_conf)
		{
			t_deb_flog(__LINE__, __FILE__, $f_conf, "tf");
			if (count($f_conf["q_f"])==0&&$f==$f_conf["f"])
			{
				$exec_f_name=$f_conf["f"];
				break;
			}
			else
			{
				if (in_array($f, $f_conf["q_f"]))
				{
					$exec_f_name=$f_conf["f"];
					break;
				}
			}
		}
		// если такой функции нет то выходим
		if (tuti_f_is_empty($exec_f_name))
		{
			echo("function $f not exist, or not allow to call...");
			return;
		}
		
		t_deb_flog(__LINE__, __FILE__, $args, "tf");
		
		//выполняем функцию...
		call_user_func_array($f, array($args));
	}
		
	
	

	
?>
