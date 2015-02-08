<?php
	if ($ruinedstudio == false)
		exit;
	$header = array('title'=>'Kategorijų redagavimas','type'=>'main','robots'=>'noindex,nofollow','author'=>'By Ruined Studio');
	$SQL = $DB->prepare("SELECT * FROM dish_categories WHERE is_draft = 0 AND category_id = ? LIMIT 1");
	$SQL->execute(array($set_id[0]));
	$draft = $SQL->fetch();
?>
<div class="fill_top"></div>
<div class="top_fixed">
 <div class="top_rel"><div class="top_abs">
  <div class="top_navigation">
	<a class="ruins" href="{URL}/menu"><span>Savaitės meniu</span></a>
	<a class="ruins" href="{URL}/menu-slideshow"><span>Skaidrės</span></a>
	<a class="ruins" href="{URL}/menu-categories"><span>Kategorijos</span></a>
	<a class="ruins" href="{URL}/menu-categories-add"><span>Pridėti kategoriją</span></a>
	<a class="logout" href="{URL}/logout" title="Atsijungti"></a>
	<a class="website" href="{ROOT}" title="Peržiūrėti svetainę" target="_blank"></a>
  </div>
 </div></div>	
</div>
<h1>Kategorijos redagavimas</h1>
<br/>
<div class="list">
<div class="desc_item opened">
<div class="list_item top cursor">
 <div class="marker"></div>
 <div class="name"><span class="l"><strong>Anglų kalba</strong></span></div>
 <div class="clear"></div>
</div>
<div class="bdesc">
 <div class="infolang">
  <table>
   <tr><td class="title">Kategorijos pavadinimas:</td>
	<td class="value edit" id="language-key/en/<?=$draft['name_key']?>"><?=_langByKey($draft['name_key'],"en")?></td></tr>
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
   <tr><td class="title">Kategorijos pavadinimas:</td>
	<td class="value edit" id="language-key/lt/<?=$draft['name_key']?>"><?=_langByKey($draft['name_key'],"lt")?></td></tr>
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
   <tr><td class="title">Kategorijos pavadinimas:</td>
	<td class="value edit" id="language-key/ru/<?=$draft['name_key']?>"><?=_langByKey($draft['name_key'],"ru")?></td></tr>
     </table>
 </div>
</div>
</div>

</div>
<br/>
<span class="button right" id="Delete">Pašalinti</span>
<div class="clear"></div>
<br/>

<script src="{ROOT_STUDIO}/jeditor.min.js" type="text/javascript"></script>
<script src="{ROOT_STUDIO}/jquery.form.min.js" type="text/javascript"></script>
<script>
$(document).ready(function()
	{
		$(window).load(function(){
			$(".desc_item.opened").css({"height": "95px"});
		});
		$(".desc_item .list_item").click(function(){
		$(".desc_item").each(function(i){$(this).css({"height": "47px"});});
		$(this).parent().css({"height": "95px"});
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
		$("#Delete").click(function(){
			if (confirm("Tikrai norite ištrinti valgiaraščio kategoriją?"))
				{
					showAlert("Trinama.");
					$.post("{ROOT_STUDIO}/editor.php",{id: "menu-item/category-delete",value: "<?=$draft['category_id']?>"}).done(function(data){if(data == "OK"){window.location="{URL}/menu-categories"}showAlert(data);});
				}
		});
	});
	
$(function() {
  $(".edit").editable("{ROOT_STUDIO}/editor.php", { 
      indicator : "Išsaugoma...",
      tooltip   : "Spausk pelę kad pradėtum redagavimą.",
	  onblur	: "submit",
	  style		: "width: auto;border:none;"
  });
});

</script>