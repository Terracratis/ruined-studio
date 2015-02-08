<?php
	if ($ruinedstudio == false)
		exit;
	$header = array('title'=>'Užsakymai','type'=>'main','robots'=>'noindex,nofollow','author'=>'By Ruined Studio');
?>

<div class="fill_top"></div>
<div class="top_fixed">
 <div class="top_rel"><div class="top_abs">
  <div class="top_navigation">
	<a class="ruins" href="{URL}/orders"><span>Nauji</span></a>
	<a class="ruins" href="{URL}/orders/1/accepted"><span>Priimti</span></a>
	<a class="ruins" href="{URL}/orders/1/sent"><span>Išsiusti</span></a>
	<a class="ruins" href="{URL}/orders/1/delivered"><span>Pristatyti</span></a>
	<a class="logout" href="{URL}/logout" title="Atsijungti"></a>
	<a class="website" href="{ROOT}" title="Peržiūrėti svetainę" target="_blank"></a>
  </div>
 </div></div>	
</div>
<h1>Užsakymų sąrašas</h1>
<br/>
<div class="list">
<div class="list_item top">
 <div class="marker"></div>
 <div class="name"><span class="l"><strong>Pavadinimas</strong></span></div>
 <div class="code"><span class="l"><strong>Užsakymas</strong></span></div>
 <div class="code"><span class="l"><strong>Data ir laikas</strong></span></div>
</div>
<?php
	if ($set_id[1] == 'accepted')
		{
			$SQL = $DB->prepare("SELECT * FROM studio_orders WHERE state = ? ORDER BY order_id DESC");
			$SQL->execute(array('accepted'));
		}
	else if ($set_id[1] == 'sent')
		{
			$SQL = $DB->prepare("SELECT * FROM studio_orders WHERE state = ? ORDER BY date DESC");
			$SQL->execute(array('sent'));
		}
	else if ($set_id[1] == 'delivered')
		{
			$SQL = $DB->prepare("SELECT * FROM studio_orders WHERE state = ? ORDER BY date DESC");
			$SQL->execute(array('delivered'));
		}
	else
		{
			$SQL = $DB->prepare("SELECT * FROM studio_orders WHERE state = ? ORDER BY order_id DESC");
			$SQL->execute(array('new'));
		}
	$result = $SQL->fetchAll();
	if ($result)
		{
		foreach($result as $order):
?>
<div class="list_item cursor" onclick="window.location='{URL}/orders-view/<?=$order['order_id']?>'">
 <div class="marker <?=$marker?>"></div>
 <div class="name"><span class="l">Užsakymas #<? printf("%06d", $order['order_id']);?></span></div>
 <div class="code"><span class="l">&pound; <?=_money($order['price'])?></span></div>
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
