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
		$f=$args["all_param_arr"]["f"];
		
		t_deb_flog(__LINE__, __FILE__, $args, "tf");
		
		call_user_func_array($f, array($args));
	}
		
	
	

	
?>
