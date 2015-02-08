<?php
	if ($ruinedstudio == false)
		exit;
	$header = array('title'=>'Skaidrės','type'=>'main','robots'=>'noindex,nofollow','author'=>'By Ruined Studio');
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
<h1>Skaidrės</h1>
<br/>
<div class="note">
 <div>
	Šioje skiltyje pridėkite tuos produktus iš savaitės valgiaraščio, kuriuos norite pareklamuoti. Žalias žymeklis nurodo, kuris produktas šiuo metu rodomas tarp skaidrių. 
 </div>
</div>
<div class="list">
<div class="list_item top">
 <div class="marker"></div>
 <div class="bar_string"><span class="long_name strong">Pavadinimas</span></div>
 <div class="bar w75"><span class="strong">Kaina</span></div>
 <div class="bar w40"></div>
</div>
<?php
	$SQL = $DB->prepare("SELECT * FROM dish_menu, studio_products WHERE dish_menu.product_id = studio_products.product_id ORDER BY menu_id DESC");
	$SQL->execute();
	$result = $SQL->fetchAll();
	if ($result)
		{
		foreach($result as $product):
		if ($product['in_slides'] == 1)
			{
				$marker = " green";
			}
		else
			{
				$marker = "";
			}
		
?>
<div class="list_item">
 <div class="marker<?=$marker?>"></div>
 <div class="bar_string"><span class="long_name" title="<?=_MultiKey($product['name_key'],$set['lang'])?>"><?=_MultiKey($product['name_key'],$set['lang'])?></span></div>
 <div class="bar w75"><span class="center">&pound; <?=_money($product['price'])?></span></div>
 <? if($product['in_slides'] == 1) { ?>
 <div class="bar click_del"><input type="submit" value="&nbsp;" title="Pašalinti" rel="<?=$product['menu_id']?>" /></div>
 <? } else { ?>
 <div class="bar click_acc"><input type="submit" value="&nbsp;" title="Pridėti" rel="<?=$product['menu_id']?>" /></div>
 <? } ?>
 <div class="clear"></div>
</div>
 <?php unset($marker);	endforeach; }
else
	{
?>
<div class="list_item">
 <div class="name"><span class="l">Valgiaraštis tuščias!</span></div>
 <div class="clear"></div>
</div>
<?
	}
?>
</div>
 <script>
$(".click_del input").click(function(){
	if (confirm("Neberodyti prekės skaidrėse?"))
				{
	var id = $(this).attr('rel');
	showAlert("Šalinama.");
	$(this).parent().addClass("click_load");
	$.post("{ROOT_STUDIO}/editor.php",{id: "menu-item/delete-slide",value: id}).done(function(data){if(data == "OK"){window.location="{URL}/menu-slideshow"}showAlert(data);});
	}
});
$(".click_acc input").click(function(){
	var id = $(this).attr('rel');
	showAlert("Pridedama.");
	$(this).parent().addClass("click_load");
	$.post("{ROOT_STUDIO}/editor.php",{id: "menu-item/add-slide",value: id}).done(function(data){if(data == "OK"){window.location="{URL}/menu-slideshow"}showAlert(data);});
});
</script>