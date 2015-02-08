<?php
	if ($ruinedstudio == false)
		exit;
	$header = array('title'=>'Produktai','type'=>'main','robots'=>'noindex,nofollow','author'=>'By Ruined Studio');
?>

<div class="fill_top"></div>
<div class="top_fixed">
 <div class="top_rel"><div class="top_abs">
  <div class="top_navigation">
	<a class="ruins" href="{URL}/products"><span>Visi produktai</span></a>
	<?=(perm("add_product", $acc['rights'])==true ? '<a class="ruins" href="{URL}/products-add"><span>Pridėti produktą</span></a>':'')?>
	<a class="logout" href="{URL}/logout" title="Atsijungti"></a>
	<a class="website" href="{ROOT}" title="Peržiūrėti svetainę" target="_blank"></a>
  </div>
 </div></div>	
</div>
<h1>Produktai</h1>
<br/>
<div class="note">
 <div>
 Čia rodomi visi Jūsų turimi produktai, jie nebūtinai gali būti įtraukti į <a href="{URL}/menu">valgiaraštį</a>.<br/>
 Paspadę Aukštyn / Žemyn, pakeisite rodymo poziciją išankstiniame meniu.
 </div>
</div>
<div class="list">
<div class="list_item">
 <div class="marker"></div>
 <div class="bar_string"><span class="strong">Pavadinimas</span></div>
 <div class="bar w70"><span class="strong">Kaina</span></div>
 <div class="bar w100"><span class="strong">Pagaminimas</span></div>
 <div class="bar w75"><span class="strong">Parduota</span></div>
 <div class="bar w40"></div>
 <div class="bar w40"></div>
</div>
<?php
	$SQL = $DB->prepare("SELECT * FROM studio_products WHERE is_draft = 0 ORDER BY position ASC");
	$SQL->execute();
	$result = $SQL->fetchAll();
	if ($result)
		{
		foreach($result as $product):
?>
<div class="list_item cursor">
 <div class="marker"></div>
 <div class="bar_string" onclick="window.location='{URL}/products-edit/<?=$product['product_id']?>'"><span class="long_name" title="<?=_MultiKey($product['name_key'],$set['lang'])?>"><?=_MultiKey($product['name_key'],$set['lang'])?></span></div>
 <div class="bar w70"><span class="center">&pound; <?=_money($product['price'])?></span></div>
 <div class="bar w100"><span class="center">&pound; <?=_money($product['price_to_make'])?></span></div>
 <div class="bar w75"><span class="center"><?=$product['sold']?></span></div>
 
 <div class="bar click_up"><input type="submit" value="&nbsp;" title="Aukštyn" rel="<?=$product['product_id']?>" /></div>
 <div class="bar click_down"><input type="submit" value="&nbsp;" title="Žemyn" rel="<?=$product['product_id']?>" /></div>
 <div class="clear"></div>
</div>
 <?php $marker = "";	endforeach; }
else
	{
?>
<div class="list_item">
 <div class="name"><span class="l">Produktų nerasta.</span></div>
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
	$.post("{ROOT_STUDIO}/editor.php",{id: "menu-item/product-up",value: id}).done(function(data){if(data == "OK"){window.location="{URL}/products"}showAlert(data);});
});
$(".click_down input").click(function(){
	var id = $(this).attr('rel');
	showAlert("Redaguojama.");
	$(this).parent().addClass("click_load");
	$.post("{ROOT_STUDIO}/editor.php",{id: "menu-item/product-down",value: id}).done(function(data){if(data == "OK"){window.location="{URL}/products"}showAlert(data);});
});
</script>