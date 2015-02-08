<?php
	if ($ruinedstudio == false)
		exit;
	$header = array('title'=>'Valgiaraščio kategorijos','type'=>'main','robots'=>'noindex,nofollow','author'=>'By Ruined Studio');
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
<h1>Valgiaraščio kategorijos</h1>
<br/>
<div class="list">
<div class="list_item top">
 <div class="marker"></div>
 <div class="bar_string"><span class="strong">Pavadinimas</span></div>
</div>
<?php
	$SQL = $DB->prepare("SELECT * FROM dish_categories WHERE is_draft = 0 ORDER BY position ASC");
	$SQL->execute();
	$result = $SQL->fetchAll();
	if ($result)
		{
		foreach($result as $product):
?>
<div class="list_item cursor">
 <div class="marker"></div>
 <div class="bar_string" onclick="window.location='{URL}/menu-categories-edit/<?=$product['category_id']?>'"><span class="long_name" title="<?=_MultiKey($product['name_key'],$set['lang'])?>"><?=_MultiKey($product['name_key'],$set['lang'])?></span></div>

 <div class="bar click_up"><input type="submit" value="&nbsp;" title="Aukštyn" rel="<?=$product['category_id']?>" /></div>
 <div class="bar click_down"><input type="submit" value="&nbsp;" title="Žemyn" rel="<?=$product['category_id']?>" /></div>
 <div class="bar click_del"><input type="submit" value="&nbsp;" title="Ištrinti" rel="<?=$product['category_id']?>" /></div>
 <div class="clear"></div>
</div>
 <?php $marker = "";	endforeach; }
else
	{
?>
<div class="list_item">
 <div class="name"><span class="l">Kategorijų nerasta.</span></div>
 <div class="clear"></div>
</div>
<?
	}
?>
</div>
<script>
$(".click_up input").click(function(){
	var id = $(this).attr('rel');
	showAlert("Redaguojama.");
	$(this).parent().addClass("click_load");
	$.post("{ROOT_STUDIO}/editor.php",{id: "menu-item/category-up",value: id}).done(function(data){if(data == "OK"){window.location="{URL}/menu-categories"}showAlert(data);});
});
$(".click_down input").click(function(){
	var id = $(this).attr('rel');
	showAlert("Redaguojama.");
	$(this).parent().addClass("click_load");
	$.post("{ROOT_STUDIO}/editor.php",{id: "menu-item/category-down",value: id}).done(function(data){if(data == "OK"){window.location="{URL}/menu-categories"}showAlert(data);});
});
$(".click_del input").click(function(){
	if (confirm("Tikrai norite pašalinti kategoriją?"))
	{
		var id = $(this).attr('rel');
		showAlert("Trinama.");
		$(this).parent().addClass("click_load");
		$.post("{ROOT_STUDIO}/editor.php",{id: "menu-item/category-delete",value: id}).done(function(data){if(data == "OK"){window.location="{URL}/menu-categories"}showAlert(data);});
	}
});
	</script>