<?php
	if ($ruinedstudio == false)
		exit;
	$header = array('title'=>'Vartotojai','type'=>'main','robots'=>'noindex,nofollow','author'=>'By Ruined Studio');
	
?>

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
<h1>Vartotojai</h1>
<br/>
<a href="{URL}/users-add" class="add_user"><span>Sukurti naują</span></a>
<div class="list">
<div class="list_item top">
 <div class="marker"></div>
 <div class="name"><span class="l"><strong>ID</strong></span></div>
 <div class="name"><span class="l"><strong>Vardas</strong></span></div>
</div>
<?php
	$SQL = $DB->prepare("SELECT * FROM studio_users ORDER BY user_id ASC");
	$SQL->execute();
	$result = $SQL->fetchAll();
	if ($result)
		{
		foreach($result as $product):
?>
<div class="list_item cursor" onclick="window.location='{URL}/users-edit/<?=$product['user_id']?>';">
 <div class="marker"></div>
 <div class="name"><span class="l"><?=$product['username']?></span></div>
 <div class="name"><span class="l"><?=$product['name']?></span></div>
 <div class="clear"></div>
</div>
 <?php $marker = "";	endforeach; }
else
	{
?>
<div class="list_item">
 <div class="name"><span class="l">Vartotojų nerasta.</span></div>
 <div class="clear"></div>
</div>
<?
	}
?>
</div>