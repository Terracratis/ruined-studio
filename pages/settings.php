<?php
	if ($ruinedstudio == false)
		exit;
	$header = array('title'=>'Nustatymai','type'=>'main','robots'=>'noindex,nofollow','author'=>'By Ruined Studio');
	if(perm("admin", $acc['rights'])==true)
	{
?>
<script src="{ROOT_STUDIO}/jeditor.min.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
$(function() {
  $(".edit").editable("{ROOT_STUDIO}/editor.php", { 
      indicator : "Išsaugoma...",
      tooltip   : "Užvesk pelę kad pradėtum redagavimą.",
	  onblur	: "submit",
	  style		: "min-width: 100px;"
  });
});
</script>
<? } ?>
<div class="fill_top"></div>
<div class="top_fixed">
 <div class="top_rel"><div class="top_abs">
  <div class="top_navigation">
	<a class="ruins" href="{URL}/settings"><span>Nustatymai</span></a>
	<a class="ruins" href="{URL}/users"><span>Vartotojai</span></a>
	<a class="ruins" href="{URL}/me"><span>Aš</span></a>
	<a class="logout" href="{URL}/logout" title="Atsijungti"></a>
	<a class="website" href="{ROOT}" title="Peržiūrėti svetainę" target="_blank"></a>
  </div>
 </div></div>	
</div>
<h1>Bendrieji nustatymai</h1>
<br/>
<div class="note">
 <div>
 <?=(perm("admin", $acc['rights'])==true ? 'Paspauskite ant teksto kad pradėtumete redagavimą, atlikę pakeitimus spauskite Enter.':'Keitimus gali atlikti tik adminastratorius.')?>
 </div>
</div>
<div class="list">

<div class="list_item">
 <div class="bar w250"><span class="strong">Adresas</span></div>
 <div class="bar_string"><span class="edit" id="settings/address"><?=$settings['address']?></span></div>
 <div class="clear"></div>
</div>
<div class="list_item">
 <div class="bar w250"><span class="l">Telefono numeris</span></div>
 <div class="bar_string"><span class="l edit" id="settings/phone"><?=$settings['phone']?></span></div>
 <div class="clear"></div>
</div>
<div class="list_item">
 <div class="bar w250"><span class="l">El. pašto adresas</span></div>
 <div class="bar_string"><span class="l edit" id="settings/email"><?=$settings['email']?></span></div>
 <div class="clear"></div>
</div>
<div class="list_item">
 <div class="bar w250"><span class="l">Siuntimo kaina už 1 milę (&pound;)</span></div>
 <div class="bar_string"><span class="l edit" id="settings/ship"><?=_money($settings['shipping_price'])?></span></div>
 <div class="clear"></div>
</div>
<div class="list_item">
 <div class="bar w250"><span class="l">Siuntimas nemokamas jei kaina &gt;</span></div>
 <div class="bar_string"><span class="l edit" id="settings/ship_free"><?=_money($settings['shipping_free_if_price'])?></span></div>
 <div class="clear"></div>
</div>
<div class="list_item">
 <div class="bar w250"><span class="l">Siuntimas nemokamas jei miestas</span></div>
 <div class="bar_string"><span class="l edit" id="settings/city"><?=$settings['shipping_free_if_city']?></span></div>
 <div class="clear"></div>
</div>
<div class="list_item">
 <div class="bar w250"><span class="l">Mokesčiai (%)</span></div>
 <div class="bar_string"><span class="l edit" id="settings/tax"><?=_money($settings['tax'])?></span></div>
 <div class="clear"></div>
</div>
</div>
