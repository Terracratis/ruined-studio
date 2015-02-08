<?php
	if ($ruinedstudio == false)
		exit;
	$header = array('title'=>'Produktai','type'=>'main','robots'=>'noindex,nofollow','author'=>'By Ruined Studio');
	if(perm("add_product", $acc['rights'])==true)
	{
	$SQL = $DB->prepare("SELECT * FROM studio_products WHERE is_draft = ? ORDER BY product_id DESC LIMIT 1");
	$SQL->execute(array('1'));
	$draft = $SQL->fetch();
	if (!$draft)
		{
			$SQL = $DB->prepare("INSERT INTO studio_products (is_draft) VALUES (?)");
			$SQL->execute(array('1'));
			$id = $DB->lastInsertId();
			$SQL = $DB->prepare("UPDATE studio_products SET name_key = ?, about_key = ? WHERE is_draft = ? AND product_id = ? LIMIT 1");
			$SQL->execute(array('product_'.$id,'product_'.$id.'about','1',$id));
			$SQL = $DB->prepare("SELECT * FROM studio_products WHERE is_draft = ? AND product_id = ? LIMIT 1");
			$SQL->execute(array('1',$id));
			$draft = $SQL->fetch();
		}
?>
<script type="text/javascript">
$.fn.rstudio_upload = function()
	{
		var options = { 
			target:   '#get',   // target element(s) to be updated with server response 
			beforeSubmit:  beforeSubmit,  // pre-submit callback 
			success:       afterSuccess,  // post-submit callback 
			uploadProgress: OnProgress, //upload progress callback 
			resetForm: true        // reset the form after successful submit 
		}; 
		$(".upload_button").live("click", function()
			{
				var obj = $(this);
				obj.prev("input[type=file]").click() ;
					obj.prev("input[type=file]").change(function()
						{
							var txt = $(this).val();
							obj.children("span").text("Atnaujinama");
							//obj.children("span").text(txt);
							
							$("#MyUploadForm").ajaxSubmit(options);  			
							// always return false to prevent standard browser submit and page navigation 
							return false; 
						});
            });
		function afterSuccess()
			{
				$('#submit-btn').show(); //hide submit button
				$('#loading-img').hide(); //hide submit button
				//$('#progressbox').delay( 1000 ).fadeOut(); //hide progress bar
				$("#output").html("Nuotrauka įkelta.");
				$("#output").delay(3000).html("Keisti nuotrauką");
				var get = $('#get').html();
				var arr = get.split("|");
				if (arr[0] == "OK")
					{
						$(".photo").css({"background-image":"url('{ROOT_STUDIO}/drive/"+arr[1]+"?time')"});
					}
			}

//function to check file size before uploading.
function beforeSubmit(){
    //check whether browser fully supports all File API
   if (window.File && window.FileReader && window.FileList && window.Blob)
	{
		
		if( !$('#FileInput').val()) //check empty input filed
		{
			$("#output").html("Are you kidding me?");
			return false
		}
		
		var fsize = $('#FileInput')[0].files[0].size; //get file size
		var ftype = $('#FileInput')[0].files[0].type; // get file type
		

		//allow file types 
		switch(ftype)
        {
            case 'image/png': 
			case 'image/gif': 
			case 'image/jpeg': 
			case 'image/pjpeg':
                break;
            default:
                $("#output").html("<b>"+ftype+"</b> Nepalaikomas failas!");
				return false
        }
		
		//Allowed file size is less than 5 MB (1048576)
		if(fsize>3145728) 
		{
			$("#output").html("<b>"+bytesToSize(fsize) +"</b> Failas per didelis (max 3MB).");
			return false
		}
				
		//$('#submit-btn').hide(); //hide submit button
		//$('#loading-img').show(); //hide submit button
		$("#output").html("");  
	}
	else
	{
		//Output error to older unsupported browsers that doesn't support HTML5 File API
		$("#output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
		return false;
	}
}

//progress bar function
function OnProgress(event, position, total, percentComplete)
{
    //Progress bar
	//$('#progressbox').show();
    //$('#progressbar').width(percentComplete + '%') //update progressbar percent complete
    $('#output').html('Įkeliama: '+percentComplete + '%'); //update status text
    /*if(percentComplete>50)
        {
            $('#statustxt').css('color','#000'); //change status text to white after 50%
        }*/
}

//function to format bites bit.ly/19yoIPO
function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Bytes';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}
        return this.each(function()
			{
				if($(this).is("input[type=file]"))
                    {
                        $(this).hide().after('<div class="upload_button"><span id="output">Keisti nuotrauką</span><label></label></div>')
                    }
            });
	};
</script>
<div class="fill_top"></div>
<div class="top_fixed">
 <div class="top_rel"><div class="top_abs">
  <div class="top_navigation">
	<a class="ruins" href="{URL}/products"><span>Visi produktai</span></a>
	<a class="ruins" href="{URL}/products-add"><span>Pridėti produktą</span></a>
	<a class="logout" href="{URL}/logout" title="Atsijungti"></a>
	<a class="website" href="{ROOT}" title="Peržiūrėti svetainę" target="_blank"></a>
  </div>
 </div></div>	
</div>
<h1>Pridėti produktą</h1>
<br/>
<div class="note">
 <div>
 Spauskite ant teksto kurį norite redaguoti. Atlikę pakeitimą spauskite Enter klavišą arba pelės mygtukų spustelėkite į tuščią vietą. Visi pakeitimai išsisaugos automatiškai.<br/>
 Užpildę visus duomenis spauskite mygtuką "Išsaugoti" (produktas bus išsaugotas ir galėsite pradėti kurti naują produktą) arba "Išsaugoti ir pridėti į valgiaraštį" (produktas bus išsaugotas ir klientai jį iš karto matys valgiaraštyje, taip pat galėsite pradėti naujo produkto kurimą) esanty apačioje.
 </div>
</div>
<div class="list">
<div class="desc_item opened">
<div class="list_item top cursor">
 <div class="marker"></div>
 <div class="name"><span class="l"><strong>Bendra produkto informacija</strong></span></div>
 <div class="clear"></div>
</div>
<div class="bdesc">
 <div class="photo"<?=($draft['photo_id'] ? "style=\"background-image: url('{ROOT_STUDIO}/drive/".$draft['photo_id']."')\"":'')?>>
	<form action="{ROOT_STUDIO}/uploader.php" method="post" enctype="multipart/form-data" id="MyUploadForm">
	<input name="FileInput" id="FileInput" type="file" />
	<input name="product" id="product" value="<?=$draft['product_id']?>" type="text" hidden="hidden" />
	<span id="get" style="display:none;"></span>
	<img src="images/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
	</form>
 </div>
 <div class="info">
  <table>
   <tr><td class="title">Pardavimo kaina:</td><td class="value"><span class="mark">&pound;</span><span class="edit" id="product-add/price/<?=$draft['product_id']?>"><?=_money($draft['price'])?></span></td></tr>
   <tr><td class="title">Pagaminimo kaina:</td><td class="value"><span class="mark">&pound;</span><span class="edit" id="product-add/price-sell/<?=$draft['product_id']?>"><?=_money($draft['price_to_make'])?></span></td></tr>
   <tr><td></td><td></td></tr>
   <tr><td class="title">Unikalus numeris:</td><td class="value"><span class="mark">#</span><?=$draft['product_id']?></td></tr>
  </table>
 </div>
</div>
</div>
<div class="desc_item">
<div class="list_item top cursor">
 <div class="marker"></div>
 <div class="name"><span class="l"><strong>Anglų kalba</strong></span></div>
 <div class="clear"></div>
</div>
<div class="bdesc">
 <div class="infolang">
  <table>
   <tr><td class="title">Produkto pavadinimas:</td>
	<td class="value edit" id="language-key/en/<?=$draft['name_key']?>"><?=_langByKey($draft['name_key'],"en")?></td></tr>
   <tr><td class="title" style="vertical-align:top;">Produkto aprašymas:</td>
   <td class="value edit_textarea" style="height: 210px;vertical-align:top;" id="language-key/en/<?=$draft['about_key']?>"><?=_langByKey($draft['about_key'],"en")?></td></tr>
  </table>
 </div>
</div>
</div>
<div class="desc_item">
<div class="list_item top cursor">
 <div class="marker"></div>
 <div class="name"><span class="l"><strong>Lietuvių kalba</strong></span></div>
 <div class="clear"></div>
</div>
<div class="bdesc">
 <div class="infolang">
  <table>
   <tr><td class="title">Produkto pavadinimas:</td>
	<td class="value edit" id="language-key/lt/<?=$draft['name_key']?>"><?=_langByKey($draft['name_key'],"lt")?></td></tr>
   <tr><td class="title" style="vertical-align:top;">Produkto aprašymas:</td>
   <td class="value edit_textarea" style="height: 210px;vertical-align:top;" id="language-key/lt/<?=$draft['about_key']?>"><?=_langByKey($draft['about_key'],"lt")?></td></tr>
  </table>
 </div>
</div>
</div>
<div class="desc_item">
<div class="list_item top cursor">
 <div class="marker"></div>
 <div class="name"><span class="l"><strong>Rusų kalba</strong></span></div>
 <div class="clear"></div>
</div>
<div class="bdesc">
 <div class="infolang">
  <table>
   <tr><td class="title">Produkto pavadinimas:</td>
	<td class="value edit" id="language-key/ru/<?=$draft['name_key']?>"><?=_langByKey($draft['name_key'],"ru")?></td></tr>
   <tr><td class="title" style="vertical-align:top;">Produkto aprašymas:</td>
   <td class="value edit_textarea" style="height: 210px;vertical-align:top;" id="language-key/ru/<?=$draft['about_key']?>"><?=_langByKey($draft['about_key'],"ru")?></td></tr>
  </table>
 </div>
</div>
</div>
</div>
<br/>
<span class="button right" id="Save">Išsaugoti</span>
<?=(perm("add_menu", $acc['rights']) ? '<span class="button right" id="SaveToMenu">Išsaugoti ir pridėti į valgiaraštį</span>':'')?>
<div class="clear"></div>
<br/>

<script src="{ROOT_STUDIO}/jeditor.min.js" type="text/javascript"></script>
<script src="{ROOT_STUDIO}/jquery.form.min.js" type="text/javascript"></script>
<script>
$(document).ready(function()
	{
		$(window).load(function(){
			$(".desc_item.opened").css({"height": "315px"});
		});
		$(".desc_item .list_item").click(function(){
		$(".desc_item").each(function(i){$(this).css({"height": "47px"});});
		$(this).parent().css({"height": "315px"});
			});
function rsizer()
			{
				var width = $(".blist_item").width() - ($(".blist_item .photo").width() + 20);
				if (width<=410) { width = 410; }
				$(".blist_item .textarea").css("width",width);
				var width2 = $(".bdesc").width() - ($(".bdesc .photo").width() + 45);
				if (width2<=390) { width2 = 390; }
				$(".bdesc .info").css("width",width2);
				var width3 = $(".bdesc").width() - (25);
				if (width2<=680) { width2 = 680; }
				$(".bdesc .infolang").css("width",width3);
			}
		$( window ).bind("resize", function(){rsizer();});
		rsizer();
		$('input[type=file]').rstudio_upload();
		$("#Save").click(function(){
			showAlert("Išsaugoma.");
			$.post("{ROOT_STUDIO}/editor.php",{id: "product-add/draft-save",value: "<?=$draft['product_id']?>"}).done(function(data){if(data == "OK"){window.location="{URL}/products"}showAlert(data);});
		});
		$("#SaveToMenu").click(function(){
			showAlert("Išsaugoma.");
			$.post("{ROOT_STUDIO}/editor.php",{id: "product-add/draft-menu",value: "<?=$draft['product_id']?>"}).done(function(data){if(data == "OK"){window.location="{URL}/products"}showAlert(data);});
		});
	});
	
$(function() {
  $(".edit").editable("{ROOT_STUDIO}/editor.php", { 
      indicator : "Išsaugoma...",
      tooltip   : "Spausk pelę kad pradėtum redagavimą.",
	  onblur	: "submit",
	  style		: "width: auto;border:none;"
  });
  $(".edit_textarea").editable("{ROOT_STUDIO}/editor.php", { 
	  type		: "textarea",
      indicator : "Išsaugoma...",
      tooltip   : "Spausk pelę kad pradėtum redagavimą.",
	  onblur	: "submit",
	  style		: "width: 100%;height:100%;border:none;"
  });
});

</script>
<? } ?>