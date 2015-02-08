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
<h1>Mano nustatymai</h1>
<br/>
<div class="note">
 <div>
 Paspauskite ant teksto, kad atliktumėte pakeitimą. Pakeitus slaptažodį, turėsite prisijungti iš naujo.
 </div>
</div>
<div class="list">

<div class="list_item">
 <div class="name"><span class="l">Jūsų vardas</span></div>
 <div class="name"><span class="l edit" id="me/name"><?=$acc['name']?></span></div>
 <div class="clear"></div>
</div>
<div class="list_item">
 <div class="name"><span class="l">Prisijungimo ID</span></div>
 <div class="name"><span class="l" id="me/user" title="Keisti negalima"><?=$acc['username']?></span></div>
 <div class="clear"></div>
</div>
<div class="list_item">
 <div class="name"><span class="l">Slaptažodis</span></div>
 <div class="name"><span class="l edit" id="me/pass"><?=str_repeat("*",12)?></span></div>
 <div class="clear"></div>
</div>
</div>
