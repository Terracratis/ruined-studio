<?php
	if ($ruinedstudio == false)
		exit;
	$header = array('title'=>'Būtina prisijungti','type'=>'main','robots'=>'noindex,nofollow','author'=>'By Ruined Studio');
?>
<!DOCTYPE html>
 <html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>{TITLE} {PAGE_TITLE}</title>
	<meta name="viewport" content="width=900, user-scalable=no" /> 
	<link href="{DISPLAY_STUDIO}/ruinedstudio.css" type="text/css" rel="stylesheet" />
	<script src="{ROOT_STUDIO}/jquery-1.7.1.min.js" type="text/javascript"></script>
</head>
<body>	
<div class="background"></div>
<div id="alert"><span>Saving</span></div>
<div class="rstudio">
	<div class="login-form-page">
		<div class="login-form-middle">
		 <div class="login-form">
		  <form action="{URL_THIS}" method="post" id="login">
		   <input class="login-input" id="username" type="text" placeholder="Vartotojo vardas" maxlength="25"/>
		   <input class="login-input" id="password" type="password" placeholder="Slaptažodis" maxlength="25"/>
		   <input class="login-button" id="submit_login" type="submit" value="Prisijungti" />
		  </form>
		 </div>
		</div>
	</div>
</div>
<script>

function showAlert(text)
	{
		$('#alert').delay( 300 ).fadeIn().find("span").html(text);
	}
function hideAlert()
	{
		$(this).delay( 1000 ).fadeOut();
	}
	$(document).ready(function()
		{
			$(".background").css({"background-image":"url('{DISPLAY_STUDIO}/images/background_1.jpg')","background-color":"#f0f0f0","opacity":"1"});
			$("#login").submit(function(e)
				{
					e.preventDefault();
					var username = $("#username").val();
					var password = $("#password").val();
					if (!username || !password)
						{
							showAlert("Įveskite prisijungimo duomenis.");
						}
					else
						{
							showAlert("Tikrinami duomenys...");
							$.post("{ROOT_STUDIO}/editor.php",{id: "login/"+username+"/"+password}).done(function(data)
								{
									if (data == "OK")
										{
											showAlert("Prisijungta, perkeliama į studiją.");
											window.location = "{URL_THIS}";
										}
									else
										{
											showAlert(data);
										}
								});
						}
				});
		});
</script>
</body>
</html>