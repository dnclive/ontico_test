/*
        tuti.js
        
        Copyright 2011 Жлобенцев Владимир <dnclive@gmail.com>
        
        This program is free software; you can redistribute it and/or modify
        it under the terms of the GNU General Public License as published by
        the Free Software Foundation; either version 2 of the License, or
        (at your option) any later version.
        
        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        GNU General Public License for more details.
        
        You should have received a copy of the GNU General Public License
        along with this program; if not, write to the Free Software
        Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
        MA 02110-1301, USA.
        * 
        * Разные нужные функции
        * 
*/



//dojo.require('dojox.timing');
//dojo.require('tlib.thash');
//dojo.require('tlib.tkvl');

//dojo.provide("tlib.tuti");

define(
[
	"dojo/_base/declare",
	"dojo/_base/lang",
	"dojo/dom-class",
	"dojo/dom-attr",
], 
function (
	t_declare, 
	t_lang,
	t_class,
	t_attr)
{
	//return //declare(null, 
	var uti={
		
		args:								//аргументы
		{
			//hash,							//массив загруженных шаблонов
			store:null,						//объект доступа к данным сервера
			//imag:null,						//класс функционала магазина
			
			
			
		},
		
		constructor:function(args)			//создатель объекта
		{
			
			var self=this;
			
			//self.args.imag=args.imag;
			
		},
		
		f_is_empty:function(val)
		{
			var self=this;
			//console.log(this);
			if ((val==undefined||val==null)&&val!=0) return true;
			if (val==""&&val.substr!=undefined)
			{
				return true;
			}
			return false;
		},
		
		f_arr_is_empty:function(arr_val)
		{
			var self=this;
			//console.log(this);
			if (self.f_is_empty(arr_val))
			{
				return true;
			}
			if (arr_val.length==0)
			{
				return true;
			}
			return false;
		},
		
		fdot_val:function(item, dot_key)
		{   
			var self=this;
			
			if (item==null) return null;
			
			if (item[dot_key]==null||item[dot_key]==undefined)
			{
				var dot_key_i=dot_key.split(".");
				
				if (dot_key_i.length==1)
				{
					return null;
				}
				
				var dot_key_next=dot_key_i.slice(1).join(".");
				
				if (self.f_is_empty(item[dot_key_i[0]]))
				{
					return null;
				}
				
				return self.fdot_val(item[dot_key_i[0]], dot_key_next);
			}
			
			return item[dot_key];
			
		},
		
		fdot_val_set:function(item, dot_key, val)
		{   
			var self=this;
			
			if (item==null) return null;
			
			if (item[dot_key]==null||item[dot_key]==undefined)
			{
				var dot_key_i=dot_key.split(".");
				
				if (dot_key_i.length==1)
				{
					return null;
				}
				
				var dot_key_next=dot_key_i.slice(1).join(".");
				
				if (self.f_is_empty(item[dot_key_i[0]]))
				{
					return null;
				}
				
				return self.fdot_val(item[dot_key_i[0]], dot_key_next);
			}
			
			return item[dot_key];
			
		},
		


		fdot_val_push:function(item, dot_key)
		{   
			var self=this;
			if (item[dot_key]==null||item[dot_key]==undefined)
			{
				var dot_key_i=dot_key.split(".");
				
				if (dot_key_i.length==1)
				{
					return null;
				}
				
				var dot_key_next=dot_key_i.slice(1).join(".");
				
				if (self.f_is_empty(item[dot_key_i[0]]))
				{
					return null;
				}
				
				return self.fdot_val(item[dot_key_i[0]], dot_key_next);
			}
			
			return item[dot_key];
			
		},
		
		
		//функция ищет в дереве родительских элеметов переданного элемент с указанным классом
		//в том числе если этот элемент текущий
		f_parent_by_class:function(args)
		{
			console.log("josi_form", "f_parent_by_class", args, node);
			var self=this;								//сам
			var node=args.node;							//исходный текущий элемент
			var class_name=args["class_name"];			//необходимый класс
			
			if (self.f_is_empty(class_name))
			{
				return null;
			}
			
			console.log("target_node", "f_parent_by_class", args, node.parentElement);
			
			//console.log(class_name);
			
			console.log("select_item", "f_parent_by_class", args, node);
			
			//console.log("inject_if_empty", "f_parent_by_class", args, node);
			
			//если текущий элемент содержит необходимый класс
			if (t_class.contains(node,class_name))
			{
				console.log(class_name);
				return node;							//возвращаем его
			}
			if (node.nodeName=="HTML"||self.f_is_empty(node.parentElement))
														//если текущий элемент HTML (топовый)
			{											//и мы здесь то больше искать негде
				return null;							//возвращаем Null
			}
			
			
			return self.f_parent_by_class(				//рекурсивно ищем дальше
			{
				node:node.parentElement,
				class_name:class_name,
			}); 
		},
		
		//функция ищет в дереве родительских элеметов переданного, 
		//элемент с указанным атрибутом
		//в том числе если этот элемент текущий
		f_parent_arr_by_class:function(args)
		{
			console.log("josi_form", "f_parent_arr_by_attr", args);
			var self=this;								//сам
			var node=args.node;							//исходный текущий элемент
			var class_name=args["class_name"];			//необходимый класс
			
			var node_arr=[];
			
			//console.log(attr_name, node);
			
			//console.log(t_attr.getNodeProp(node, "nodeName"));
			//if (
			
			//console.log("inject_if_empty", "parent_arr_by_attr", node);
			if(!self.f_is_empty(node))
			{
				if (t_attr.getNodeProp(node, "nodeName")=="HTML")//если текущий элемент HTML (топовый)
				{										//и мы здесь то больше искать негде
					return [];							//возвращаем Null
				}
			}
			else
			{
				return [];								//возвращаем Null
			}
			
			//console.log(123);
			
			
			
			var node_arr=self.f_parent_arr_by_class(	//рекурсивно ищем дальше
			{
				node:node.parentElement,
				class_name:class_name,
			}); 
			
			//if (t_uti.f_is_empty(node_arr))
			//{
			//	node_arr=[];
			//}
			
			console.log("inject_if_empty", "parent_arr_by_attr", node_arr, node);
			
			console.log("popup", "parent_arr_by_class", node_arr, node, class_name);
			
			if (t_class.contains(node,class_name))
			{
				console.log("popup", "parent_arr_by_class","совпадение", node_arr, node, class_name);
				node_arr.unshift(node);							//возвращаем его
			}
			
			
			return node_arr;
		},
		
		
		//функция ищет в дереве родительских элеметов переданного, 
		//элемент с указанным атрибутом
		//в том числе если этот элемент текущий
		f_parent_by_attr:function(args)
		{
			console.log("josi_form", "f_parent_by_attr", args);
			var self=this;								//сам
			var node=args.node;							//исходный текущий элемент
			var attr_name=args["attr_name"];			//необходимый класс
			
			//console.log(attr_name, node);
			
			//console.log(t_attr.getNodeProp(node, "nodeName"));
			//if (
			
			//если текущий элемент содержит необходимый класс
			if (t_attr.has(node,attr_name))
			{
				//console.log(class_name);
				return node;							//возвращаем его
			}
			if(!self.f_is_empty(node))
			{
				if (t_attr.getNodeProp(node, "nodeName")=="HTML")//если текущий элемент HTML (топовый)
				{											//и мы здесь то больше искать негде
					return null;							//возвращаем Null
				}
			}
			//console.log(123);
			
			return self.f_parent_by_attr(				//рекурсивно ищем дальше
			{
				node:node.parentElement,
				attr_name:attr_name,
			}); 
		},
		
		//функция ищет в дереве родительских элеметов переданного, 
		//элемент с указанным атрибутом
		//в том числе если этот элемент текущий
		f_parent_arr_by_attr:function(args)
		{
			console.log("josi_form", "f_parent_arr_by_attr", args);
			var self=this;								//сам
			var node=args.node;							//исходный текущий элемент
			var attr_name=args["attr_name"];			//необходимый класс
			
			var node_arr=[];
			
			//console.log(attr_name, node);
			
			//console.log(t_attr.getNodeProp(node, "nodeName"));
			//if (
			
			//console.log("inject_if_empty", "parent_arr_by_attr", node);
			if(!self.f_is_empty(node))
			{
				if (t_attr.getNodeProp(node, "nodeName")=="HTML")//если текущий элемент HTML (топовый)
				{											//и мы здесь то больше искать негде
					return [];							//возвращаем Null
				}
			}
			else
			{
				return [];							//возвращаем Null
			}
			
			//console.log(123);
			
			var node_arr=self.f_parent_arr_by_attr(				//рекурсивно ищем дальше
			{
				node:node.parentElement,
				attr_name:attr_name,
			}); 
			
			//if (t_uti.f_is_empty(node_arr))
			//{
			//	node_arr=[];
			//}
			
			console.log("inject_if_empty", "parent_arr_by_attr", node_arr);
			
			//если текущий элемент содержит необходимый класс
			if (t_attr.has(node,attr_name))
			{
				//console.log(class_name);
				node_arr.unshift(node);							//возвращаем его
			}
			
			return node_arr;
		},
		
		
		f_str_sep_str:function(str1, sep, str2)
		{
			var self=this;
			if (self.f_is_empty(str1))
			{
				return str2;
			}
			else
			{
				return str1+sep+str2;
			}
		},
		
/*** числа ***/

		f_is_number:function(n) 
		{
			//return !isNaN(parseFloat(n)) && isFinite(n);
			return (n - 0) == n && n.length > 0;
		},
		
/*** массивы ***/

		f_is_array:function(val)
		{
			return typeof(val)=='object'&&(val instanceof Array);
		},
		
/*** работа с функциями ***/

		f_fdone:function(fdone, args)
		{
			var self=this;
			
			if (!self.f_is_empty(fdone))
			{
				fdone(args);
				return true;
			}
			return false;
		},
		
		
		f_mix:function(val1, val2)
		{
			var mix_val={};
			for (var key1 in val1)
			{
				mix_val[key1]=val1[key1];
			}
			for (var key2 in val2)
			{
				mix_val[key2]=val2[key2];
			}
			
			console.log(val1, val2, mix_val);
			
			return mix_val;
		},
	
		f_struct_mix:function(args)
		{
			var self=this;
			var val1=args.val1;
			var val2=args.val2;
			var replace_only=args.replace_only||false;
			
			var mix_val={};
			if (replace_only)
			{
				for (var key1 in val1)
				{
					mix_val[key1]=val2[key1];
				}
			}
			else
			{
				mix_val=self.f_mix(val1, val2);
			}
			
			return mix_val;
		},
	
/*** работа с датами и временем***/

		//возвращает строковую дату dd.MMM.yyyy
		f_dt_short:function(args)
		{
			var dt_val=args.dt_val;
			return String(dt_val.getDate())+"."+
					((dt_val.getMonth()+1)<10?"0"+String(dt_val.getMonth()+1):dt_val.getMonth()+1)+"."+
					String(dt_val.getFullYear());

		},
		
		//то же самое только для kvl аркумент - значение
		f_dt_short_2:function(dt_val)
		{
			return String(dt_val.getDate())+"."+
					((dt_val.getMonth()+1)<10?"0"+String(dt_val.getMonth()+1):dt_val.getMonth()+1)+"."+
					String(dt_val.getFullYear());

		},
		
		//текущее дата и время
		f_now:function(dt_val)
		{
			return new Date();
		},
		
		//возвращает время из переданного Data в формате hh:mm:cc
		f_time:function(dt_val)
		{
			return String(dt_val.getHours())+":"+String(dt_val.getMinutes())+":"+String(dt_val.getSeconds())+":";
		},

/*** работа со строками ***/

		f_str_contain:function(str1, str2)
		{
			if (str1.indexOf(str2)>-1)
			{
				return true;
			}
			return false;
		},
		
		f_str_pos:function(str1, str2)
		{
			var self=this;
			if (!self.f_is_string(str1)||!self.f_is_string(str2))
			{
				return -1;
			}
			return str1.indexOf(str2);
		},
		
		f_is_string:function(val)
		{
			var self=this;
			if (self.f_is_empty(val)) return val;
			
			if (val.substr!=undefined)
			{
				return true;
			}
			return false;
		},

		f_trim:function(str) 
		{
			var self=this;
			if (self.f_is_empty(str)) return str;
			
			str = str.replace(/^\s+/, '');
			for (var i = str.length - 1; i >= 0; i--) {
				if (/\S/.test(str.charAt(i))) {
					str = str.substring(0, i + 1);
					break;
				}
			}
			return str;
		},

		f_explode:function(args)
		{
			//pj_deb_flog3(__LINE__, __FILE__, $args, "tkvl3_explode");
			var self=this;
			var str=args["str"];		//строка
			var sep=args["sep"];		//разделитель
			var lim=args["lim"];		//ограничитель в рамках которого не действует разделитель
			var drop_empty=args["drop_empty"];	//не добавлять в массив пустые элементы
			
			if (self.f_is_empty(str))
			{
				if (drop_empty)
				{
					return [];
				}
				else
				{
					return [""];
				}
			}
			
			var state_1=true;			//состояние поиска разделителя
			var state_2=false;			//состояние экранирования true если предыдущий символ экран
			var state_1_new=true;		//новое состояние 1 (state_1 в будущем такте)
			var state_2_new=false;		//новое состояние 2 (state_2 в будущем такте)
			
			var i=0;
			var str_len=str.length;
			var ch='';
			var ch_prev='';
			var ch_prev_prev='';
			var ch_next='';
			var ch_next_next='';
			
			var str_part_arr=[];
			var str_part="";
			
			
			//перебираем символы строки
			while(i<str_len)
			{
				
				ch=str[i];								//текущий символ
				ch_prev=i>0?str[i-1]:'';				//предыдущий символ
				ch_prev_prev=i>1?str[i-2]:'';			//предыдущий предыдущий символ
				ch_next=i<str_len?str[i+1]:'';			//следующий символ
				ch_next_next=i+1<str_len?str[i+2]:'';	//следующий следующий символ
				
				
				//если сейчас идет поиск разделителя (state_1=true) и текущий символ разделитель
				//то помещаем текущую накопленную строку, текущий очередной элемент в массив
				//и начинаем набор следующего
				if (state_1&&ch==sep&&!state_2)
				{
					//добавляем очередной фрагмент строки если только он не пустой
					//if (str_part.length>0)	//даже если он пустой
					{
						str_part_arr.push(str_part);
						str_part="";
					}
					//если следующий символ пуст достигнут конец строки
					//при этом елси пустые элементы также добавляются drop_empty=false
					//добавляем последний пустой элемент
					if (self.f_is_empty(ch_next)&&!drop_empty)
					{
						str_part_arr.push("");
					}
				}
				
				//если текущий символ ограничитель и идет поиск разделителя (state_1=true) 
				//и предыдущий символ не экран (state_2=true)
				//то выходим из состояния поиска разделителя в следующем такте (начиная со следующего симовла)
				if (ch==lim&&state_1&&!state_2)
				{
					state_1_new=false;
					//state_2_new=true;
				}
				
				//если мы не в поиске разделителя (state_1=false) и текущий символ разделитель
				//и предыдущий символ не экран (state_2=true)
				// возвращаемся в состояние поиска разделителя
				if (!state_1&&ch==lim&&!state_2)
				{
					//console.log("trans", "f_explode", lim, ch, ch_prev, str_part, str);
					state_1_new=true;
				}
				
				//если текущий символ экран \ и мы не в поиске разделителя (state_1=false) (те мы внутри ограничителей)
				//то переходим в состояние экранирования 
				if (ch=="\\")//&&!state_1)	//не учитываем текущее состояние - экран работает всегда и для всех символов
				{
					state_2_new=true;
				}
				
				//если мы в состоянии экранирования предыдущий символ экран '\'
				//то текущий символ не обрабатывается
				if (state_2)
				{
					state_2_new=false;
				}
				
				//если текущий символ не разделитель и состояние в следующем такре не экранирования, те текущий символ не экран
				//собираем текущий символ в текущий элемент
				if (ch!=sep)//&&!state_2_new)
				{
					str_part+=ch;
				}
				
				//если мы не в состоянии поиска разделителя и текущий символ не разделитель и текущий символ не экран
				if (ch==sep&&state_2)
				{
					str_part+=ch;
				}
				
				//устанавливаем текущии состояния для следующего такта
				state_1=state_1_new;
				state_2=state_2_new;
				
				//переходим к следующему символу
				i++;
			}
			
			
			//если в текущей набираемой строке-элементе есть символы
			//размещаем еще один элемент в массиве
			if (str_part.length>0)
			{
				str_part_arr.push(str_part);
			}
			
			return str_part_arr;
			
		},

		f_escape_char_arr:function(args)
		{
			var self=this;
			var escape_char_arr=args.escape_char_arr;
			var str=args.str;
			
			//console.log("f_escape_char_arr","1",self.);
			
			if (self.f_is_empty(escape_char_arr)||self.f_is_empty(str)||self.f_is_empty(str.split))
			{
				return str;
			}
			
			console.log("f_escape_char_arr","1",args, str);
			var i=0;
			while(i<escape_char_arr.length)
			{
				var escape_char=escape_char_arr[i];
				if (escape_char=='"')
				{
					str=str.split(escape_char).join("'");
				}
				else
				{
					str=str.split(escape_char).join("\\"+escape_char);
				}
				//str=str.replace(escape_char_arr[i], "\\"+escape_char_arr[i]);
				i++;
			}
			
			console.log("f_escape_char_arr","2",args, str);
			
			return str;
			
		},


/*** работа с переменными контекстами, смешивание массивов ***/

		f_mix_param:function(args)
		{
			var self=this;
			var struct_arr=args.struct_arr;
			
			var struct_mix=t_lang.mixin({}, struct_arr[0]);
			
			var i=1;
			while(i<struct_arr.length)
			{
				var struct=struct_arr[i];
				for (var key in struct)
				{
					if (!self.f_is_empty(struct[key]))
					{
						if (self.f_str_pos(struct[key],"$")===0)
						{
							var param_key=struct_mix[key].substr(1, struct_mix[key]);
							struct_mix[key]=struct[param_key];
						}
					}
				}
				i++;
			}
			
			return struct_mix;
			
		},
		
		f_kvl_1_mix_val:function(args)
		{
			//console.trace();
			console.log("popup_list", args);
			var self=this;
			var kvl2=args.kvl2;
			var kvl_1_mix=args.kvl_1_mix;
			var mix_key=kvl_1_mix.mix_key;
			var def_val=kvl_1_mix.def_val;
			var kvl_sep_escape=kvl_1_mix.kvl_sep_escape||false;
			var fdone=args.fdone;
			
			
			
			var val=self.fdot_val(kvl_1_mix, mix_key);
			
			console.log("popup_list", args, val);
			
			console.log("tpl_struct_arr","f_kvl_1_mix_val", args, val);
			
			console.log("tpl_5","f_kvl_1_mix_val", args, val, self.f_is_empty(val));
			
			if (self.f_is_empty(val))
			{
				val=def_val;
			}
			
			// если требуется экранировать kvl разделители то делаем это
			if (kvl_sep_escape)
			{
				val=self.f_escape_char_arr(
				{
					escape_char_arr:"&:^><",
					str:val,
				});
			}
			
			self.f_fdone(fdone, {content:val});
			
			return val;
		},
		
		f_drop_sub_obj:function(args)
		{
			console.log("f_srv_f","f_drop_sub_obj",args);
			var self=this;
			var obj=args.obj;
			var new_obj={};
			for (var sub_val_key in obj)
			{
				console.log("f_srv_f","f_drop_sub_obj",args, sub_val_key, obj[sub_val_key], typeof(obj[sub_val_key]));
				if (typeof(obj[sub_val_key])!="Object"&&typeof(obj[sub_val_key])!="object"&&
					typeof(obj[sub_val_key])!="Array"&&typeof(obj[sub_val_key])!="array")
					{
						new_obj[sub_val_key]=obj[sub_val_key];
					}
			}
			console.log("f_srv_f","f_drop_sub_obj",args, new_obj);
			return new_obj;
		},
		
/*** работа с числами ***/

		f_in_range:function(args)
		{
			var self=this;
			var val=args.val;
			var range=args.range;
			
			if (val>=range[0]&&val<=range[1])
			{
				return true;
			}
			return false;
		},
		
		f_in_enum:function(args)
		{
			var self=this;
			var val=args.val;
			var val_arr=args.val_arr;
			return (val_arr.indexOf(val) !== -1);
		},
		
		

		
/*** google analistics ***/

		f_gaq:function(args)
		{
			var self=this;
			
			//var _gaq=_gaq||[];
			
			console.log("gaq", args, _gaq);
			
			require(["tlib/tres", "tlib/tcalc", "tlib/tdot"], function(t_res, t_calc, t_dot)
			{
				console.log("gaq", "struct_res", args, t_res.f_struct_res_arr_get({key:"google._gaq"}));
				
				//var _gaq=t_res.f_struct_res_arr_get({key:"google._gaq"})||[];
				
				
				
				console.log("gaq", args, _gaq);
				
				var kvl_1_mix=args.kvl_1_mix;
				
				var context_key=kvl_1_mix.context_key||"";
				
				var full_key=t_dot.f_full_dot_key(
				{
					context_key:context_key,
					key:kvl_1_mix.struct_res_key,
				});
				
				var command=t_calc.f_calc(
				{
					context_struct_res_key:full_key,
					exp_str:kvl_1_mix.command,
				});
				var category=t_calc.f_calc(
				{
					context_struct_res_key:full_key,
					exp_str:kvl_1_mix.category,
				});
				var action=t_calc.f_calc(
				{
					context_struct_res_key:full_key,
					exp_str:kvl_1_mix.action,
				});
				var opt_label=t_calc.f_calc(
				{
					context_struct_res_key:full_key,
					exp_str:kvl_1_mix.opt_label,
				})||"";
				var opt_value=Number(t_calc.f_calc(
				{
					context_struct_res_key:full_key,
					exp_str:kvl_1_mix.opt_value,
				}))||0;
				
				var fdone=args.fdone;
				
				console.log("gaq", _gaq, full_key, command, category, action, opt_label, opt_value);
				
				if (command=='_trackEvent')
				{
					_gaq.push([command, category, action, opt_label, opt_value]);
				}
				
				self.f_fdone(fdone);
			
			});
		},
		
	};//);
	
	return uti;
});

