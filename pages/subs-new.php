<?php
	if ($ruinedstudio == false)
		exit;
	$header = array('title'=>'Užsakymų prenumeratos','type'=>'main','robots'=>'noindex,nofollow','author'=>'By Ruined Studio');
?>

<div class="fill_top"></div>
<div class="top_fixed">
 <div class="top_rel"><div class="top_abs">
  <div class="top_navigation">
	<a class="ruins" href="{URL}/subs-new"><span>Nauji užsakymai</span></a>
	<a class="ruins" href="{URL}/subs"><span>Prenumeratos</span></a>
	<a class="logout" href="{URL}/logout" title="Atsijungti"></a>
	<a class="website" href="{ROOT}" title="Peržiūrėti svetainę" target="_blank"></a>
  </div>
 </div></div>	
</div>
<h1>Prenumeratų užsakymų sąrašas</h1>
<br/>
<div class="list">
<div class="list_item top">
 <div class="marker"></div>
 <div class="name"><span class="l"><strong>Pavadinimas</strong></span></div>
 <div class="code"><span class="l"><strong></strong></span></div>
 <div class="code"><span class="l"><strong>Data ir laikas</strong></span></div>
</div>
<?php
	$SQL = $DB->prepare("SELECT * FROM studio_orders_sub WHERE state = ? ORDER BY order_id DESC");
	$SQL->execute(array('new'));
	$result = $SQL->fetchAll();
	if ($result)
		{
		foreach($result as $order):
?>
<div class="list_item cursor" onclick="window.location='{URL}/subs-view/<?=$order['order_id']?>'">
 <div class="marker <?=$marker?>"></div>
 <div class="name"><span class="l">Užsakymas #S<? printf("%05d", $order['order_id']);?></span></div>
 <div class="code"><span class="l"></span></div>
 <div class="code"><span class="l"><?=gmdate("Y.m.d H:i", $order['date'])?></span></div>
 <div class="clear"></div>
</div>
 <?php $marker = "";	endforeach; }
else
	{
?>
<div class="list_item">
 <div class="name"><span class="l">Sąrašas tuščias.</span></div>
 <div class="clear"></div>
</div>
<?
	}
?>
</div>
