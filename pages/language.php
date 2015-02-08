<?php
	if ($ruinedstudio == false)
		exit;
	$header = array('title'=>'Kalbos','type'=>'main','robots'=>'noindex,nofollow','author'=>'By Ruined Studio');
?>

<div class="fill_top"></div>
<div class="top_fixed">
 <div class="top_rel"><div class="top_abs">
  <div class="top_navigation">
	<a class="ruins" href="{URL}/language"><span>Svėtainės</span></a>
	<a class="logout" href="{URL}/logout" title="Atsijungti"></a>
	<a class="website" href="{ROOT}" title="Peržiūrėti svetainę" target="_blank"></a>
  </div>
 </div></div>	
</div>
<h1>Kalbos raktai ir vertimai</h1>
<br/>
 <?=(perm("language", $acc['rights'])==false ? '<div class="note">
 <div>Keitimus gali atlikti tik adminastratorius.</div>
</div>
<br/>':'')?>
<div class="list">
<?php
	$SQL = $DB->prepare("SELECT DISTINCT(lang_key) FROM studio_language WHERE type=? ORDER BY lang_key ASC");
	$SQL->execute(array('web'));
	$result = $SQL->fetchAll();
	if ($result)
		{
		foreach($result as $language):
?>
<div class="desc_item">
<div class="list_item cursor">
 <div class="marker <?=$marker?>"></div>
 <div class="long"><span class="l"><?=$language['lang_key']?></span></div>
 <div class="clear"></div>
</div>
<div class="desc">
<table><tr>
<td style="width:50px;font-weight:bold;padding:5px;">LT</td><td style="padding:5px;" class="value edit" id="language-web/lt/<?=$language['lang_key']?>"><?=_langByKey($language['lang_key'],"lt")?></td>
</tr><tr>
<td style="width:50px;font-weight:bold;padding:5px;">EN</td><td style="padding:5px;" class="value edit" id="language-web/en/<?=$language['lang_key']?>"><?=_langByKey($language['lang_key'],"en")?></td>
</tr><tr>
<td style="width:50px;font-weight:bold;padding:5px;">RU</td><td style="padding:5px;" class="value edit" id="language-web/ru/<?=$language['lang_key']?>"><?=_langByKey($language['lang_key'],"ru")?></td>
</tr></table>
</div>
</div>
 <?php $marker = "";	endforeach; }
else
	{
?>
<div class="list_item">
 <div class="name"><span class="l">Klientų nerasta.</span></div>
 <div class="clear"></div>
</div>
<?
	}
?>
</div>
<script src="{ROOT_STUDIO}/jeditor.min.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
$(window).load(function(){
		$(".desc_item .list_item").click(function(){
		$(".desc_item").each(function(i){$(this).css({"height": "47px"});});
		$(this).parent().css({"height": "135px"});
		initialize($(this).parent().find(".desc").find(".address").html(), $(this).parent().find(".desc").find(".map_canvas").get(0));
	});
});
<? if (perm("admin",$acc['rights'])) { ?>
$(function() {
  $(".edit").editable("{ROOT_STUDIO}/editor.php", { 
      indicator : "Išsaugoma...",
      tooltip   : "Spausk pelę kad pradėtum redagavimą.",
	  onblur	: "submit",
	  style		: "width: auto;border:none;"
  });
});	
<? } ?>
});
</script>