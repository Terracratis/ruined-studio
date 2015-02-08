<?php
	if ($ruinedstudio == false)
		exit;
	$header = array('title'=>'Santrauka','type'=>'main','robots'=>'index,follow','author'=>'By Ruined Studio');
	$header['facebook'] = 'go';
?>
<div class="fill_top"></div>
<div class="top_fixed">
 <div class="top_rel"><div class="top_abs">
  <div class="top_navigation">
	<a class="ruins" href="{URL}/"><span>Santrauka</span></a>
	<a class="logout" href="{URL}/logout" title="Atsijungti"></a>
	<a class="website" href="{ROOT}" title="Peržiūrėti svetainę" target="_blank"></a>
  </div>
 </div></div>	
</div>
<div class="summary_zone">
 <div class="summary">
 <table>
  <tr>
<?
	$SQL = $DB->prepare("SELECT SUM(price) FROM studio_orders WHERE state=?");
	$SQL->execute(array("delivered"));
	$price = $SQL->fetch();
?>
	<td class="title top" title="Įplaukos už pardavimus">Pardavimai</td>
	<td class="curr">&pound;</td>
	<td class="value top green"><?=_money($price[0])?></td>
  </tr>
  <tr>
<?
	$SQL = $DB->prepare("SELECT SUM(price_to_make) FROM studio_orders WHERE state=?");
	$SQL->execute(array("delivered"));
	$price_to_make = $SQL->fetch();
?>
	<td class="title">Pagaminimo kaina</td>
	<td class="curr">&pound;</td>
	<td class="value red"><?=_money($price_to_make[0])?></td>
  </tr>
  <tr>
<?
	$SQL = $DB->prepare("SELECT SUM(price_tax) FROM studio_orders WHERE state=?");
	$SQL->execute(array("delivered"));
	$price_tax = $SQL->fetch();
?>
	<td class="title">Mokesčiai (<?=$settings['tax']?> %)</td>
	<td class="curr">&pound;</td>
	<td class="value red"><?=_money($price_tax[0])?></td>
  </tr>
  <tr>
<?
	$SQL = $DB->prepare("SELECT SUM(price_shipping) FROM studio_orders WHERE state=? OR state=?");
	$SQL->execute(array("delivered", "delete"));
	$price_ship = $SQL->fetch();
?>
	<td class="title">Siuntimo išlaidos</td>
	<td class="curr">&pound;</td>
	<td class="value red"><?=_money($price_ship[0])?></td>
  </tr>
  <tr>
<?
	$SQL = $DB->prepare("SELECT SUM(price_to_make) FROM studio_orders WHERE state=?");
	$SQL->execute(array("delete"));
	$price_delete = $SQL->fetch();
?>
	<td class="title" title="Nuostolis dėl klientų">Nuostolis</td>
	<td class="curr">&pound;</td>
	<td class="value red"><?=_money($price_delete[0])?></td>
  </tr>
   <tr>
<?
	$pelnas = $price[0] - $price_to_make[0] - $price_tax[0] - $price_ship[0] - $price_delete[0];
?>
	<td class="title">&sum; Pelnas</td>
	<td class="curr">&pound;</td>
	<? if ($pelnas<=0) { $marker = "red"; } else { $marker = "green"; } ?>
	<td class="value <?=$marker?>"><strong><?=_money($pelnas)?></strong></td>
  </tr>
 </table>
 </div>
 <div class="inbox">
	<span class="title">Aktyvūs užsakymai</span>
	<?php
		$SQL = $DB->prepare("SELECT COUNT(order_id) FROM studio_orders WHERE state=?");
		$SQL->execute(array("new"));
		$count = $SQL->fetch();
	?>
	<span class="message_count"><?=$count[0]?> nepriimti</span>
	<?
		$SQL = $DB->prepare("SELECT * FROM studio_orders WHERE state=? OR state=? OR state=? OR state=? ORDER BY order_id DESC LIMIT 5");
		$SQL->execute(array("new","accepted","sent","not_paid"));
		$result = $SQL->fetchAll();
	if ($result)
		{
		foreach($result as $order) {
		if ($order['state'] == "new") { $marker = 'green'; }
		else if ($order['state'] == "accepted") { $marker = 'blue'; }
		else if ($order['state'] == "sent") { $marker = 'orange'; }
		else if ($order['state'] == "not_paid") { $marker = 'red'; }
		else { $marker = ""; }
	?>
	<a href="{URL}/orders-view/<?=$order['order_id']?>" class="ruins message"><div class="type <?=$marker?>"></div><span>Užsakymas #<? printf("%06d", $order['order_id']);?></span><div class="micon"></div></a>
		<? }} else { ?>
		<a href="" class="ruins message"><div class="type"></div><span>Nėra jokių aktyvių užsakymų</span><div class="micon"></div></a>
		<? } ?>
 </div>
</div>
<script>
$(document).ready(function(){
		$(".background").css({"background-image":"url('{DISPLAY_STUDIO}/images/background_1.jpg')","background-color":"#f0f0f0","opacity":"1"});
		//setTimeout(function(){$(".background").css({"background-image":"url('{DISPLAY_STUDIO}/images/background_3.jpg')","background-color":"#b3d5de","opacity":"1"})},7000);
		rsize();
		function rsize()
			{
				var inbox_width = $(".summary_zone").width() - ($(".summary_zone .summary").width() + 15);
				if (inbox_width<=340) { inbox_width = 340; }
				$(".summary_zone .inbox").css("width",inbox_width);
			}
		$( window ).bind("resize", function(){
				rsize();
		});
	});
</script>