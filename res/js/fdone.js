
/*
        fdone.js
        
        Copyright 2012 Жлобенцев Владимир <dnclive@gmail.com>
        
        
        
*/

define(
[
	"XMLHttpRequest",
	"tuti"
], 
function (t_xhr_null, t_uti)
{

	return 
	{
		args:
		{
			xhr:new XMLHttpRequest;,
			
		},
		
		function f_xhr(args) 
		{
			var self=this;
			var kvl_1_mix=args.kvl_1_mix;
			var method=kvl_1_mix.method;
			var url=kvl_1_mix.url;
			var async=kvl_1_mix.async&&true;
			var xhr=self.args.xhr;
			
			var fdone=args.fdone;
			
			
			xhr.open("GET", "server.php", true);
			xhr.onreadystatechange	= function() 
			{
				if (this.readyState == XMLHttpRequest.DONE) 
				{
					t_uri.f_fdone(fdone, 
				}
			}
			xhr.send(null);
		},
		
		

	};
	
	
});


