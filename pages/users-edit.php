<?php
	if ($ruinedstudio == false)
		exit;
	$header = array('title'=>'Teisių keitimas','type'=>'main','robots'=>'noindex,nofollow','author'=>'By Ruined Studio');
	$SQL = $DB->prepare("SELECT * FROM studio_users WHERE user_id = ? LIMIT 1");
	$SQL->execute(array($set_id[0]));
	$usr = $SQL->fetch();
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
<h1>Teisių keitimas</h1>
<?
	if ($set_id[1] == "save" && $usr['user_id']>0)
		{
			$pass = _get('pass');
			$rights = $_POST['rights'];
			$SQL = $DB->prepare("SELECT * FROM studio_users WHERE username = ? LIMIT 1");
			$SQL->execute(array($user));
			$result = $SQL->fetch();
			if (!empty($pass) && strlen($pass)<6) { $err.= "Trumpas slaptažodis, min 6 simb.<br/>"; }
			if(perm("admin", $acc['rights']) == false && in_array("moderator", $rights) == true
			|| perm("admin", $acc['rights']) == false && perm("moderator", $acc['rights']) == false
			|| perm("moderator", $acc['rights'])==true && in_array("moderator", $rights) == true
			|| perm("admin", $acc['rights']) == false && perm("moderator", $usr['rights']) == true
			|| in_array("admin", $rights) == true) { $err.="Nepakankamos teisės.";}
			
			if (!$err)
				{
					$rights = json_encode($rights);
					if (!empty($pass))
					{
						$pass = sha1($pass);
						$SQL = $DB->prepare("UPDATE studio_users SET password = ?, rights = ? WHERE user_id = ? LIMIT 1");
						$SQL->execute(array($pass, $rights, $usr['user_id']));
					}
					else
					{
						$SQL = $DB->prepare("UPDATE studio_users SET rights = ? WHERE user_id = ? LIMIT 1");
						$SQL->execute(array($rights, $usr['user_id']));
					}
					
					echo "<script>window.location='{URL}/users';</script>";
				}
			else
				{
					echo $err;
				}
		}
?>
<br/>
<form action="{URL}/users-edit/<?=$set_id[0]?>/save" method="post">
<div class="list">

<div class="list_item">
 <div class="marker"></div>
 <div class="bar w250"><span class="strong">Vardas ar pavadinimas:</span></div>
 <div class="bar_string"><span><?=$usr['name']?></span></div>
 <div class="clear"></div>
</div>
<div class="list_item">
 <div class="marker"></div>
 <div class="bar w250"><span class="strong">Unikalus vartotojo ID:</span></div>
 <div class="bar_string"><span><?=$usr['username']?></span></div>
 <div class="clear"></div>
</div>
<div class="list_item">
 <div class="marker"></div>
 <div class="bar w250"><span class="strong">Vartotojo slaptažodis:</span></div>
 <div class="bar w250"><span><input type="text" value="" maxlength="16" name="pass" id="pass"/></span></div>
 <div class="bar_string"><span class="long_name" title="Jei nenorite keisti, palikite tuščia.">Jei nenorite keisti, palikite tuščia.</span></div>
 <div class="clear"></div>
</div><br/>
<input type="checkbox" value="moderator" name="rights[]" id="a1"<?=(in_array("moderator",json_decode($usr['rights']))==true ? ' checked':'')?>/> <label for="a1" title="Gali kurti, redaguoti ir trinti vartotojus.Išskyrus adminastratorių ir kitus moderatorius.">Moderatorius</label>
 <input type="checkbox" value="orders" name="rights[]" id="a2"<?=(in_array("orders",json_decode($usr['rights']))==true ? ' checked':'')?>/> <label for="a2">Užsakymai</label>
 <input type="checkbox" value="slideshow" name="rights[]" id="a3"<?=(in_array("slideshow",json_decode($usr['rights']))==true ? ' checked':'')?>/> <label for="a3">Skaidrės</label>
 <input type="checkbox" value="add_menu" name="rights[]" id="a4"<?=(in_array("add_menu",json_decode($usr['rights']))==true ? ' checked':'')?>/> <label for="a4">Savaitės menių</label>
 <input type="checkbox" value="add_products" name="rights[]" id="a5"<?=(in_array("add_products",json_decode($usr['rights']))==true ? ' checked':'')?>/> <label for="a5">Produktai</label>
 <input type="checkbox" value="add_category" name="rights[]" id="a6"<?=(in_array("add_category",json_decode($usr['rights']))==true ? ' checked':'')?>/> <label for="a6">Kategorijos</label>
 <input type="checkbox" value="language" name="rights[]" id="a7"<?=(in_array("language",json_decode($usr['rights']))==true ? ' checked':'')?>/> <label for="a7">Kalbų redagavimas</label>

</div>
<input type="submit" class="button" value="Išsaugoti" style="border: none;"/>
</form>
