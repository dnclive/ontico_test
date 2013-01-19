<?php
/*
 * f.php
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
 
	//профилирование
	/*
	if (extension_loaded('xhprof')) 
	{
		include_once '/usr/share/php5-xhprof/xhprof_lib/utils/xhprof_lib.php';
		
		include_once '/usr/share/php5-xhprof/xhprof_lib/utils/xhprof_runs.php';

		xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
	}
	/**/
 
	//инклуды
	require_once(dirname(__FILE__)."/../josi_lib/php/dev/tdeb.php");		//библиотека отладки
	require_once(dirname(__FILE__)."/../josi_lib/php/dev/tuti.php");		//библиотека разных полезных функций
	require_once(dirname(__FILE__)."/../josi_lib/php/dev/tstore.php");		//работа с хранилищем данных
	require_once("t_sql.php");							//библиотека работы с mysql
	require_once("t_vkcom.php");						//библиотека для работы с vk.com
	require_once("tf.php");								//библиотека для работы с функциями
	
	require_once("t_ot.php");							//реализация задания 1 ОНТИКО
		
		
	//phpinfo();

	//возвращаемый запросом текст
	$content="";

	//собираем параметры переденные в запросе включая строку запроса, cookie, и тело запроса если это POST
	$args=t_uti_f_param_arr(array
	(
		"query_str"=>$_SERVER['QUERY_STRING'],
		"cookie_str"=>$GLOBALS["_SERVER"]["HTTP_COOKIE"],
		"body_str"=>file_get_contents('php://input'),
	));
	
	//включение отладки по требованию для указанной группы отладки
	if (!tuti_f_is_empty($args["all_param_arr"]["debug_group"]))
	{
		print_r($args["all_param_arr"]["debug_group"]);
		$GLOBALS["pj_deb_debug"]=true;
		$GLOBALS["pj_deb_group"]=$args["all_param_arr"]["debug_group"];
	}
	
	t_deb_flog(__LINE__, __FILE__, $args, "f");
	
	tf_f($args);
	
	echo($content);
	
	//профилирование 
	/*
	if (extension_loaded('xhprof')) 
	{
		$profiler_namespace = 'josi_store';  // namespace for your application
		$output_url = "https://localhost/"; // keep the trailing slash
		$xhprof_data = xhprof_disable();
		$xhprof_runs = new XHProfRuns_Default();
		$run_id = $xhprof_runs->save_run($xhprof_data, $profiler_namespace);
	 
		// url to the XHProf UI libraries (change the host name and path)
		$profiler_url = sprintf($output_url . 'xhprof_html/index.php?run=%s&source=%s', $run_id, $profiler_namespace);
		$styles = ' style="display: block; position: absolute; left: 5px; bottom: 5px; background: red; padding: 8px; z-index: 10000; color: #fff;"';
		echo '<a href="'. $profiler_url .'" target="_blank" ' . $styles . '>Profiler output</a>';
	}*/
	
?>
