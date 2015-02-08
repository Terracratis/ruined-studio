<?php
	if ($ruinedstudio == false)
		exit;
	$header = array('title'=>'Nustatymai','type'=>'main','robots'=>'noindex,nofollow','author'=>'By Ruined Studio');
	
	if (!$set_id[0]) { $set_id[0]='lt';} 
	if ($set_id[1] == 'save')
		{
			$SQL = $DB->prepare("UPDATE studio_language SET translation=? WHERE type=? AND language = ? LIMIT 1");
			$t = $_POST['code'];
			$SQL->execute(array($t,'about',$set_id[0]));
		}
	$SQL = $DB->prepare("SELECT * FROM studio_language WHERE lang_key = ? AND language = ? AND type = ? LIMIT 1");
	$SQL->execute(array('about',$set_id[0], 'about'));
	$text = $SQL->fetch();
?>
<script src="{ROOT_STUDIO}/jeditor.min.js" type="text/javascript"></script>
<script type="text/javascript">
	bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>
<div class="fill_top"></div>
<div class="top_fixed">
 <div class="top_rel"><div class="top_abs">
  <div class="top_navigation">
	<a class="ruins" href="{URL}/about-page"><span>LT</span></a>
	<a class="ruins" href="{URL}/about-page/en"><span>EN</span></a>
	<a class="ruins" href="{URL}/about-page/ru"><span>RU</span></a>
	<a class="logout" href="{URL}/logout" title="Atsijungti"></a>
	<a class="website" href="{ROOT}" title="Peržiūrėti svetainę" target="_blank"></a>
  </div>
 </div></div>	
</div>
<h1>Apie mane puslapis</h1>
<br/>
<div class="note">
 <div>
 Baigę redagavimą spauskite Išsaugoti.
 </div>
</div>
<form action="{URL}/about-page/<?=$set_id[0]?>/save" method="post">
<textarea name="code" style="width:100%;height:250px;"><?=$text['translation']?></textarea>
<input type="submit" value="Išsaugoti"/>
</form>
