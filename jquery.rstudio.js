// function fixes/releases sidebar from content.
$.fn.rstudio_sidebar = function() {
		var elem = $(this);
		var div_pos = $(this).scrollTop() + $(this).height();
		$(window).scroll(function() {
		if(div_pos <= $(window).scrollTop()+$(window).height()) {
			elem.addClass("fixed_at_bottom");
		}
		else
		{
			elem.removeClass("fixed_at_bottom");
		}
});
};
// for getting browser window width, helps for responsive design.
$.fn.rstudio_width = function() {
		var width = $(window).width();
};
// width fix
$.fn.rstudio_dboard_width = function() {
		$(this).each(function() {
			var all_width = $(this).width();
			var width = 0;
			$(this).find(".barb").each(function(){
				if ($(this).is(":visible") == true)
					{
						width += $(this).width() + 15;
					}
			});
			var c_width = all_width-width;
			$(this).find(".barb_string").css({'width':c_width});
		});
};
$.fn.rstudio_list_width = function() {
		$(this).each(function() {
			var all_width = $(this).width();
			var width = 0;
			$(this).find(".bar").each(function(){
				if ($(this).is(":visible") == true)
					{
						width += $(this).width() + 10;
					}
			});
			var c_width = all_width-width;
			$(this).find(".bar_string").css({'width':c_width});
			if ($(this).find(".bar_string .long_name").length)
				{
					var element = $(this).find(".bar_string .long_name");
					var string = element.attr('title');
					if (typeof string !== typeof undefined && string !== false)
					{
						if (string.length)
							{
								var cal_width = (c_width - 10)/8.7;
								if (cal_width < string.length)
									{
										element.html(string.substr(0, cal_width)+"...");
									}
							}
					}
				}
		});
};
// substring
$.fn.rstudio_stringer = function() {
		$(this).each(function()
			{
				var string = $(this).attr('title');
				var width = $(".vat_name").width();
				if (width == 320 && string.length >= 41)
					{
						$(this).html(string.substr(0, 38)+"...");
					}
				else if (width == 175 && string.length >= 23)
					{
						$(this).html(string.substr(0, 19)+"...");
					}
			});
};


$.fn.myTextEditor = function(options){
        // extend the option with the default ones
        var settings = $.extend({
            width : "400px",
            height : "200px",
          fonts : ["Arial","Comic Sans MS","Courier New","Monotype Corsiva","Tahoma","Times"]
        },options);
        return this.each(function(){
            var $this = $(this).hide();
       // create a container div on the fly
       var containerDiv = $("<div/>",{
           css : {
               width : settings.width ,
               height : settings.height,
               border : "1px solid #ccc"
           }
       });
       $this.after(containerDiv); 
       var editor = $("<iframe/>",{
           frameborder : "0" 
       }).appendTo(containerDiv).get(0);
       // opening and closing the editor is a workaround to solve issue in Firefox
       editor.contentWindow.document.open();
       editor.contentWindow.document.close();
       editor.contentWindow.document.designMode="on";
       var buttonPane = $("<div/>",{
          "class" : "editor-btns",
          css : {
              width : settings.width,
              height : "25px"
          }
       }).prependTo(containerDiv);
       var btnBold = $("<a/>",{
        href : "#",
        text : "B",        
        data : {
            commandName : "bold"
        },
        click : execCommand 
        }).appendTo(buttonPane );
          var btnItalic = $("<a/>",{
        href : "#",
        text : "I",
        data : {
            commandName : "italic"
        },
        click : execCommand 
        }).appendTo(buttonPane );
          var btnUnderline = $("<a/>",{
        href : "#",
        text : "U",
        data : {
            commandName : "underline"
        },
        click : execCommand 
        }).appendTo(buttonPane );
          var selectFont = $("<select/>",{
            data : {
              commandName : "FontName"
            },
            change : execCommand
          }).appendTo(buttonPane );  
          $.each(settings.fonts,function(i,v){
            $("<option/>",{
              value : v,
              text : v
            }).appendTo(selectFont);
            
          });  
    function execCommand (e) {
            $(this).toggleClass("selected");
            var contentWindow = editor.contentWindow;
            contentWindow.focus();
            contentWindow.document.execCommand($(this).data("commandName"), false, this.value || "");
            contentWindow.focus();
            return false;
    }
    });
};
			/*var init = true, 
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
                        $(this).addClass('active');
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
            });*/
			/*
			$.fn.myTextEditora = function(options){
        // extend the option with the default ones
        var settings = $.extend({
            width : "400px",
            height : "200px",
          fonts : ["Arial","Comic Sans MS","Courier New","Monotype Corsiva","Tahoma","Times"]
        },options);
        return this.each(function(){
            var $this = $(this).hide();
       // create a container div on the fly
       var containerDiv = $("<div/>",{
           css : {
               width : settings.width ,
               height : settings.height,
               border : "1px solid #ccc"
           }
       });
       $this.after(containerDiv); 
       var editor = $("<iframe/>",{
           frameborder : "0" 
       }).appendTo(containerDiv).get(0);
       // opening and closing the editor is a workaround to solve issue in Firefox
       editor.contentWindow.document.open();
       editor.contentWindow.document.close();
       editor.contentWindow.document.designMode="on";
       var buttonPane = $("<div/>",{
          "class" : "editor-btns",
          css : {
              width : settings.width,
              height : "25px"
          }
       }).prependTo(containerDiv);
       var btnBold = $("<a/>",{
        href : "#",
        text : "B",        
        data : {
            commandName : "bold"
        },
        click : execCommand 
        }).appendTo(buttonPane );
          var btnItalic = $("<a/>",{
        href : "#",
        text : "I",
        data : {
            commandName : "italic"
        },
        click : execCommand 
        }).appendTo(buttonPane );
          var btnUnderline = $("<a/>",{
        href : "#",
        text : "U",
        data : {
            commandName : "underline"
        },
        click : execCommand 
        }).appendTo(buttonPane );
          var selectFont = $("<select/>",{
            data : {
              commandName : "FontName"
            },
            change : execCommand
          }).appendTo(buttonPane );  
          $.each(settings.fonts,function(i,v){
            $("<option/>",{
              value : v,
              text : v
            }).appendTo(selectFont);
            
          });  
    function execCommand (e) {
            $(this).toggleClass("selected");
            var contentWindow = editor.contentWindow;
            contentWindow.focus();
            contentWindow.document.execCommand($(this).data("commandName"), false, this.value || "");
            contentWindow.focus();
            return false;
    }
    });
};*/
   