(function($){

	"use strict";
  
	(function(d, s, id) {
    		var js, fjs = d.getElementsByTagName(s)[0];
    		if (d.getElementById(id)) return;
    		js = d.createElement(s); js.id = id;
    		js.src = "https://connect.facebook.net/en_US/sdk.js";
    		fjs.parentNode.insertBefore(js, fjs);
  	}(document, 'script', 'facebook-jssdk'));

  	window.fbAsyncInit = function() {

    		FB.init({
      			appId: facebook_appid,
      			cookie: true,
      			xfbml: true,
      			version: 'v7.0'
    		});
  
	};

	function fb_change_status(response,type) {

		if(response.status == 'connected') {
			check_login_fb();
		} else {
			alert(response.status);
		}

	}

  	function checkLoginStatse() {
    
		FB.getLoginStatus(function(response) {
      			fb_change_status(response,2);
    		});
  
	}

	$(document).on('click', '.login_fb', function() {

		FB.login(function(response) {
  			check_login_fb();
		}, {scope: 'public_profile,email'});

	});

	function check_login_fb() {
    	
		FB.api('/me?fields=email,name', function(response) {

			var name = response.name;
			var email = response.email;
			var fb_userid = response.id;
      			
			$.post('_core/request.php', { reason: 'fb_login', name: name, email: email, fb_userid: fb_userid }, function(get) {

				if(get.error == '0') {
					window.location.reload(1);
				} else {
					alert('Error');
				}

			},'json');

     		});
  
	}

})(jQuery);