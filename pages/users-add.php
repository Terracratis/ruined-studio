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
<h1>Vartotojo kūrimas</h1>
<br/>
<?
	if ($set_id[0] == "save")
		{
			$user = _get('user');
			$pass = _get('pass');
			$name = _get('name');
			$rights = $_POST['rights'];
			$SQL = $DB->prepare("SELECT * FROM studio_users WHERE username = ? LIMIT 1");
			$SQL->execute(array($user));
			$result = $SQL->fetch();
			if ($result) { $err.= "Vartotojo ID jau yra.<br/>"; }
			if (strlen($pass)<6) { $err.= "Trumpas slaptažodis, min 6 simb.<br/>"; }
			if (strlen($user)<4) { $err.= "Trumpas vartotojo ID, min 4 simb.<br/>"; }
			if (strlen($name)<4) { $err.= "Trumpas vardas (pavadinimas), min 4 simb.<br/>"; }
			if(perm("admin", $acc['rights']) == false && in_array("moderator", $rights) == true
			|| perm("admin", $acc['rights']) == false && perm("moderator", $acc['rights']) == false
			|| perm("moderator", $acc['rights'])==true && in_array("moderator", $rights) == true
			|| in_array("admin", $rights) == true) { $err.="Nepakankamos teisės.";}
			
			if (!$err)
				{
					$pass = sha1($pass);
					$rights = json_encode($rights);
					$SQL = $DB->prepare("INSERT INTO studio_users (username, password, name, rights) VALUES (?,?,?,?)");
					$SQL->execute(array($user,$pass,$name,$rights));
					echo "<script>window.location='{URL}/users';</script>";
				}
			else
				{
					echo $err;
				}
		}
?>
<form action="{URL}/users-add/save" method="post">
<div class="list">
<div class="list_item">
 <div class="marker"></div>
 <div class="bar w250"><span class="strong">Unikalus vartotojo ID:</span></div>
 <div class="bar_string"><input type="text" value="" maxlength="16" name="user" id="user"/></div>
 <div class="clear"></div>
</div>
<div class="list_item">
 <div class="marker"></div>
  <div class="bar w250"><span class="strong">Vartotojo slaptažodis:</span></div>
 <div class="bar_string"><input type="text" value="" maxlength="16" name="pass" id="pass"/></div>
 <div class="clear"></div>
</div>
<div class="list_item">
 <div class="marker"></div>
 <div class="bar w250"><span class="strong">Vardas ar pavadinimas:</span></div>
 <div class="bar_string"><input type="text" value="" maxlength="20" name="name" id="name"/></div>
 <div class="clear"></div>
</div>
<br/>
 <input type="checkbox" value="moderator" name="rights[]" id="a1"/> <label for="a1" title="Gali kurti, redaguoti ir trinti vartotojus.Išskyrus adminastratorių ir kitus moderatorius.">Moderatorius</label>
<input type="checkbox" value="orders" name="rights[]" id="a2" checked/> <label for="a2">Užsakymai</label>
<input type="checkbox" value="slideshow" name="rights[]" id="a3" checked/> <label for="a3">Skaidrės</label>
 <input type="checkbox" value="add_menu" name="rights[]" id="a4"/> <label for="a4">Savaitės menių</label>
 <input type="checkbox" value="add_products" name="rights[]" id="a5"/> <label for="a5">Produktai</label>
 <input type="checkbox" value="add_category" name="rights[]" id="a6"/> <label for="a6">Kategorijos</label>
 <input type="checkbox" value="language" name="rights[]" id="a7"/> <label for="a7">Kalbų redagavimas</label>

</div>
<input type="submit" class="button" value="Išsaugoti" style="border: none;"/>
</form>