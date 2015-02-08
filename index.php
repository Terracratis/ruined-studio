<?php
	include('ruinedstudio.php');
	$instudio = true;
	$DB = _database_connect( $config_mysql['host'], $config_mysql['database'], $config_mysql['username'], $config_mysql['password'] );
	$SQL = $DB->prepare("SELECT * FROM studio_settings WHERE settings = ? LIMIT 1");
	$SQL->execute(array(1));
	$settings = $SQL->fetch();
	ob_start("ober");
	$acc = _loged_in_studio();
	if ($acc['user_id'])
	{
?>
<!DOCTYPE html>
 <html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>{TITLE} {PAGE_TITLE}</title>
	<meta name="viewport" content="width=950, user-scalable=no" /> 
	<link href="{DISPLAY_STUDIO}/ruinedstudio.css" type="text/css" rel="stylesheet" />
	<link href="{DISPLAY_STUDIO}/jquery-ui.css" type="text/css" rel="stylesheet" />
	<script src="{ROOT_STUDIO}/jquery-1.7.1.min.js" type="text/javascript"></script>
	<script src="{ROOT_STUDIO}/jquery.lockfixed.min.js" type="text/javascript"></script>
	<script src="{ROOT_STUDIO}/jquery.rstudio.js" type="text/javascript"></script>
	<script src="{ROOT_STUDIO}/nicEdit.js" type="text/javascript"></script>
	<link rel="shortcut icon" href="{ROOT}/favicon.ico?v=1.0">

<script>
function showAlert(text)
	{
		$('#alert').delay( 300 ).fadeIn().find("span").html(text);
	}
function hideAlert()
	{
		$(this).delay( 1000 ).fadeOut();
	}
function SoundAlarm()
		{
			var snd = new Audio("{ROOT_STUDIO}/drive/alarms/alarm4.wav"); // buffers automatically when created
			snd.play();
		}
function AlarmOrders()
	{
		$.post("{ROOT_STUDIO}/editor.php",{id: "orders-view/alarm"}).done(function(data)
			{
				if(data > 0){
					showAlert("Gauti <strong>"+data+"</strong> nauji užsakymai!");
					SoundAlarm();
					}
			});
	}
function AlarmOrders2()
	{
		$.post("{ROOT_STUDIO}/editor.php",{id: "orders-sview/alarm"}).done(function(data)
			{
				if(data > 0){
					showAlert("Gauti <strong>"+data+"</strong> nauji prenumeratos užsakymai!");
					SoundAlarm();
					}
			});
	}
function CheckOrders()
	{
		$.post("{ROOT_STUDIO}/editor.php",{id: "orders-view/check"}).done(function(data)
			{
				if(data > 0){
					$("#order_count").html(" ("+data+")");
					}
			});
	}
function CheckOrders2()
	{
		$.post("{ROOT_STUDIO}/editor.php",{id: "orders-sview/check"}).done(function(data)
			{
				if(data > 0){
					$("#order_count2").html(" ("+data+")");
					}
			});
	}
$(document).ready(function(){
		
		$('#alert').click(function(){$(this).delay( 500 ).fadeOut();});
		$(".menu").rstudio_sidebar();
		$(window).load(function() {setTimeout(function() { $(".spins").each(function(){$(this).removeClass("make_spin");}); }, 2000);});
		$('a').each(function() {if ($(this).attr('href') == ("http://"+window.location.hostname+window.location.pathname)) {$(this).addClass('active');} else {$(this).removeClass('active');}});
		window_resize();
		function window_resize()
			{
				var height = $(window).height() - ($(".black_menu").height() + $(".logo_zone").height() + $(".end_menu").height());
				if (height<=100) { height = 100; }
				$("#menuend").css("min-height",height);
			}
		$( window ).bind("resize", function(){
				window_resize();
		});
		
		function start_spin()
			{
				$(".spins").each(function(){$(this).addClass("make_spin");});
			}
		
		
		   
	$('.pclose').click(function(){
      $(this).parent().removeClass('slidePageInFromLeft').addClass('slidePageBackLeft');
  });
	jQuery('a.popup').live('click', function(){
		if($('#popup').hasClass('slidePageInFromLeft'))
			{	
				$('#popup').removeClass('slidePageInFromLeft').addClass('slidePageBackLeft');
				setTimeout(function(){$('#popup').addClass('slidePageInFromLeft').removeClass('slidePageBackLeft');},300);
			}
		else
			{
				$('#popup').addClass('slidePageInFromLeft').removeClass('slidePageBackLeft');
			}
				var handler = function(data) {$('title').html($('title', data).html());$('.pcontent').html($('#content', data).html());
					$.address.title(/>([^<]*)<\/title/.exec(data)[1]);
				};
				$.ajax({url: $(this).attr('href'),
					error: function(XMLHttpRequest, textStatus, errorThrown) {handler(XMLHttpRequest.responseText);$('.loader').fadeOut(2000);},
					success: function(data, textStatus, XMLHttpRequest) {handler(data);$('.loader').fadeOut(2000);}});
				return false;
    });
CheckOrders();	
CheckOrders2();	
AlarmOrders();
AlarmOrders2();
setInterval(function() { AlarmOrders();CheckOrders();AlarmOrders2();CheckOrders2(); }, 60000);
			$(".logout").click(function(e)
				{
					e.preventDefault();
					showAlert("Atsijungiama nuo studijos...");
					$.post("{ROOT_STUDIO}/editor.php",{id: "logout"}).done(function(data)
						{
							if (data == "OK")
								{
									showAlert("Atsijungta, perkeliama į svetainę.");
									window.location = "{ROOT}";
								}
							else
								{
									showAlert(data);
								}
						});
				});
});
</script>

</head>
<body>
<div class="background"></div>
	<div id="popup">
	 <span class="pclose"></span>
	 <div class="pcontainer">
		<div class="pcontent body"></div>
	 </div>
	</div>
	<div id="alert"><span>Saving</span></div>
 <div class="rstudio">
 <div class="logo_zone">
	<div class="rstudio_logo">
	 <div class="rstudio_logo_rel">
	  <div class="logo"></div>
	  <div class="gear1 spins make_spin"></div>
	  <div class="gear1_s spins make_spin"></div>
	  <div class="gear2 spins make_spin"></div>
	  <div class="gear2_s spins make_spin"></div>
	 <div class="gear3 spins make_spin"></div>
	  <div class="gear3_s spins make_spin"></div> 
	 </div>
	</div>
 </div>
  <div class="left_zone">
	<div class="menu">
		<div class="menu_top"></div>
		<div class="black_menu">
			<div class="empty"></div>
			<a href="{URL}/" class="ruins"><div class="icon console"></div><span>Santrauka</span></a>
			<a href="{URL}/email" class="ruins"><div class="icon console"></div><span>Paštas</span></a>
			<div class="line"></div>
			<a href="{URL}/products" class="ruins"><div class="icon products"></div><span>Produktų sąrašas</span></a>
			<a href="{URL}/menu" class="ruins"><div class="icon menu"></div><span>Valgiaraštis</span></a>
			<div class="line"></div>
			<a href="{URL}/orders" class="ruins"><div class="icon commerce"></div><span>Užsakymai <font id="order_count"></font></span></a>
			<a href="{URL}/subs" class="ruins"><div class="icon commerce_subs"></div><span>Prenumeratos <font id="order_count2"></font></span></a>
			<!--<a href="{URL}/analytic" class="ruins"><div class="icon analytic"></div><span>Analytikas</span></a>-->
			<div class="line"></div>
			<a href="{URL}/clients" class="ruins"><div class="icon clients"></div><span>Klientai</span></a>
			<a href="{URL}/feedbacks" class="ruins"><div class="icon feedbacks"></div><span>Atsiliepimai</span></a>
			
			<div class="empty"></div>
		</div>
		<div class="dark_menu" id="menuend">
			<div class="empty"></div>
			<a href="{URL}/settings" class="ruins"><div class="icon settings"></div><span>Bendrieji nustatymai</span></a>
			<a href="{URL}/language" class="ruins"><div class="icon community"></div><span>Kalbos nustatymai</span></a>
			<a href="{URL}/about-page" class="ruins"><div class="icon community"></div><span>Apie mus puslapis</span></a>
			<div class="line"></div>
			<div class="empty"></div>
		</div>
		<div class="end_menu">
		 <span class="copyright">&copy; 2014 <a href="http://www.ruinedstudio.com" title="Ruined Studio">Ruined Studio</a> by <a href="http://www.terracratis.com" title="Terra Cratis">Terra Cratis</a></span>
		</div>
	</div>
  </div>
    <div class="right_zone">
	<div class="fill_top2"></div>
		<div class="positioning_rel">
			<div class="positioning_abs">
				<div class="content" id="content">
					<?php include("pages/".$page_id.".php"); ?>
				</div>
		</div>
  </div>
  <div class="clear"></div>
 </div>
</div>
<script>
$(".list_item").rstudio_list_width();
$(window).bind("resize",function(){$(".list_item").rstudio_list_width();});
</script>
</body>
</html>
<?php
	}
	else
	{
		include("pages/login-form.php");
	}
	ob_end_flush();
?>