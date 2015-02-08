<?php
	if ($ruinedstudio == false)
		exit;
	$header = array('title'=>'Klientų atsiliepimai','type'=>'main','robots'=>'noindex,nofollow','author'=>'By Ruined Studio');
?>
<div class="fill_top"></div>
<div class="top_fixed">
 <div class="top_rel"><div class="top_abs">
  <div class="top_navigation">
	<a class="ruins" href="{URL}/feedbacks"><span>Klientų atsiliepimai</span></a>
	<a class="logout" href="{URL}/logout" title="Atsijungti"></a>
	<a class="website" href="{ROOT}" title="Peržiūrėti svetainę" target="_blank"></a>
  </div>
 </div></div>	
</div>
<h1>Klientų atsiliepimai</h1>
<br/>
<div class="note">
 <div>
 Raudonas žymeklis rodomas prie tų atsiliepimų kurie nėra rodami interneto svetainėje viešai.
 </div>
</div>
<div class="list">
<div class="list_item top">
 <div class="marker"></div>
 <div class="name"><span class="l"><strong>Vardas</strong></span></div>
 <div class="vatcode"><span class="l"><strong>Data</strong></span></div>
 <div class="code"><span class="l"><strong>Veiksmai</strong></span></div>
</div>
<?php
	$SQL = $DB->prepare("SELECT * FROM studio_feedbacks ORDER BY feedback_id DESC");
	$SQL->execute();
	$result = $SQL->fetchAll();
	if ($result)
		{
		foreach($result as $product):
		if ($product['active'] == 0)
			{
				$marker = " red";
			}
		
?>
<div class="list_item">
 <div class="marker<?=$marker?>"></div>
 <div class="name"><span class="l"><?=$product['name']?></span></div>
 <div class="vatcode"><span class="l"><?=gmdate("Y m d H:i", $product['date'])?></span></div>
 <div class="code"><span class="l"><span class="small_button" onClick="Delete('<?=$product['feedback_id']?>')">Ištrinti</span></span></div>
 <div class="code"><span class="l"><?=($product['active'] == 0 ? "<span class=\"small_button\" onClick=\"Activate('".$product['feedback_id']."')\">Rodyti</span>":"")?></span></div>
 <div class="clear"></div>
</div>
<div style="padding:10px;background:#fff;width:100%;">sdsds<?=$product['text']?></div>
 <?php unset($marker, $category);	endforeach; }
else
	{
?>
<div class="list_item">
 <div class="name"><span class="l">Atsiliepimų nėra!</span></div>
 <div class="clear"></div>
</div>
<?
	}
?>
</div>
<script src="{ROOT_STUDIO}/jeditor.min.js" type="text/javascript"></script>
<script>
function Delete(id)
	{
		if (confirm("Ar tikrai norite pašalinti?"))
				{
					showAlert("Trinama.");
					$.post("{ROOT_STUDIO}/editor.php",{id: "feeds/delete",value: id}).done(function(data){if(data == "OK"){window.location="{URL}/feedbacks"}showAlert(data);});
				}
	}
function Activate(id)
	{
		if (confirm("Ar tikrai norite paviešinti?"))
				{
					showAlert("Viešinama.");
					$.post("{ROOT_STUDIO}/editor.php",{id: "feeds/add",value: id}).done(function(data){if(data == "OK"){window.location="{URL}/feedbacks"}showAlert(data);});
				}
	}
</script>