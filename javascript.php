<?php
	include(dirname(__FILE__).DIRECTORY_SEPARATOR."ruinedstudio.php");
?>
/* Handler */
$( document ).ready(function() {
//alert($( window ).width());
		/*$(function()
			{
				var win_size = $( window ).width();
				if (win_size<=768)
					{
						$('.ruinedstudio').css({'width': '768px'});
					}
				else if (win_size>=2560)
					{
						$('.ruinedstudio').css({'width': '2560px'});
					}
				else
					{
						$('.ruinedstudio').css({'width': win_size+'px'});
					}
			});*/
		function stop_spin()
			{
				setTimeout(function() { $(".spins").each(function(){$(this).removeClass("make_spin");}); }, 2000);
				setTimeout(function() { $(".spins").each(function(){$("#loaderzone").removeClass("make_loader");}); }, 2000);
			}
		function start_spin()
			{
				$(".spins").each(function(){$(this).addClass("make_spin");});
				$("#loaderzone").addClass("make_loader");
			}
		$(window).load(function() {
			stop_spin();
		});        
		   var init = true, 
                state = window.history.pushState !== undefined;
            
            // Handles response
            var handler = function(data) {
                $('title').html($('title', data).html());
                $('#content').html($('#content', data).html());
                $.address.title(/>([^<]*)<\/title/.exec(data)[1]);
            };
            
            $.address.state('<?=$config_page['url']?>').init(function() {

                // Initializes the plugin
                $('a.ruins').address();
                
            }).change(function(event) {
				if($('#popup').hasClass('slidePageInFromLeft')){$('#popup').removeClass('slidePageInFromLeft').addClass('slidePageBackLeft');}
				start_spin();
				
                // Selects the proper navigation link
                $('a').each(function() {
                    if ($(this).attr('href') == ($.address.state() + event.path)) {
                        $(this).addClass('active').focus();
                    } else {
                        $(this).removeClass('active');
                    }
                });
                
                if (state && init) {
                    init = false;
                } else {
					$.ajax({
                        url: $.address.state() + event.path,
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            handler(XMLHttpRequest.responseText);
							stop_spin();
                        },
                        success: function(data, textStatus, XMLHttpRequest) {
                            handler(data);
							 stop_spin();
                        }
                    });
                }
            });
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
  });