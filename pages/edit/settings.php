<?php
	if ($ruinedstudio == false)
		exit;
	$header = array('title'=>'{Drive}','type'=>'main','robots'=>'noindex,nofollow','author'=>'By Ruined Studio');
	$header['facebook'] = 'go';
?>
<div class="fill_top"></div>
<div class="top_fixed">
 <div class="top_rel"><div class="top_abs">
  <div class="top_navigation">
	<a class="ruins" href="{URL}/drive"><span>Failų tvarkyklė</span></a>
  </div>
 </div></div>	
</div>
<p>
<h1>{Drive}</h1>
</p>
<div class="drive_grid">
<?php
	$SQL = $DB->prepare("SELECT * FROM studio_drive ORDER BY is_folder DESC, title ASC");
	$SQL->execute();
	$result = $SQL->fetchAll();
	if ($result)
		{
		foreach($result as $drive):
		if ($drive['is_folder'] == 1)
			{
				$icon = " style=\"background: url('{THEME}/filetypes/folder.png') center center;\"";
			}
		elseif($drive['extension'])
			{
				$icon = " style=\"background: url('{THEME}/filetypes/".$drive['extension'].".png') center center;\"";
			}
		else
			{
				$icon = "";
			}
?>		<div class="drive_item">
		  <div class="image">
			<div class="icon"<?=$icon?>></div>
		  </div>
		  <span><?=$drive['title']?></span>
		</div>
 <?php	endforeach; } ?>
 <div class="drive_item selected">
		  <div class="image">
			<div class="icon"<?=$icon?>></div>
		  </div>
		  <span><?=$drive['title']?></span>
		</div>
<div class="clear"></div>
</div>