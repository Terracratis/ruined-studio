<?php
	if ($ruinedstudio == false)
		exit;
	$header = array('title'=>'Valgiaraštis','type'=>'main','robots'=>'noindex,nofollow','author'=>'By Ruined Studio');
?>
<div class="fill_top"></div>
<div class="top_fixed">
 <div class="top_rel"><div class="top_abs">
  <div class="top_navigation">
	<a class="ruins" href="{URL}/menu"><span>Savaitės meniu</span></a>
	<?=(perm("slideshow", $acc['rights']) ? '<a class="ruins" href="{URL}/menu-slideshow"><span>Skaidrės</span></a>':'')?>
	<?=(perm("add_category", $acc['rights']) ? '<a class="ruins" href="{URL}/menu-categories"><span>Kategorijos</span></a>
	<a class="ruins" href="{URL}/menu-categories-add"><span>Pridėti kategoriją</span></a>':'')?>
	<a class="logout" href="{URL}/logout" title="Atsijungti"></a>
	<a class="website" href="{ROOT}" title="Peržiūrėti svetainę" target="_blank"></a>
  </div>
 </div></div>	
</div>
<h1>Valgiaraštis</h1>
<br/>
<? if(perm("add_menu", $acc['rights'])) { ?>
<input type="text" name="product_id" id="product_id" style="display: none;" />
<input class="product_input" placeholder="Pradėkite vesti produkto pavadinimą, tada pasirinkite iš galimų variantų." type="text" name="product_name" id="product_name" style="width: 350px;" />
<span class="button left" id="button" style="width: 100px;">Pridėti</span>
<div class="clear"></div>
<br/>
<? } ?>
<div class="note">
 <div>
 Raudonas žymeklis nurodo kurie valgiaraščio elementai yra nepaskirti jokiai kategorijai. Kategoriją pakeisite tiesiog paspaudę ant esamos kategorijos pavadinimo.
 </div>
</div>
<div class="list">
<div class="list_item top">
 <div class="marker"></div>
 <div class="bar_string"><span class="strong">Pavadinimas</span></div>
 <div class="bar w200"><span class="strong">Kategorija</span></div>
 <div class="bar w75"><span class="strong">Kaina</span></div>
  <? if(perm("add_menu", $acc['rights'])) { ?><div class="bar w40"></div><? } ?>
</div>
<?php
	$SQL = $DB->prepare("SELECT * FROM dish_menu, studio_products WHERE dish_menu.product_id = studio_products.product_id ORDER BY menu_id DESC");
	$SQL->execute();
	$result = $SQL->fetchAll();
	if ($result)
		{
		foreach($result as $product):
		if ($product['category_id'])
			{
				$SQLe = $DB->prepare("SELECT * FROM dish_categories WHERE is_draft = 0 AND category_id = ? ORDER BY name_key ASC");
				$SQLe->execute(array($product['category_id']));
				$category = $SQLe->fetch();
				if(!$category['category_id']) { $marker = " red"; }
			}
		else
			{
				$marker = " red";
			}
		
?>
<div class="list_item">
 <div class="marker<?=$marker?>"></div>
 <div class="bar_string"><span class="long_name" title="<?=_MultiKey($product['name_key'],$set['lang'])?>"><?=_MultiKey($product['name_key'],$set['lang'])?></span></div>
 <div class="bar w200"><span class="edit_select" id="menu-item/change-category/<?=$product['menu_id']?>"><?=($category['name_key'] ? _MultiKey($category['name_key'], $set['lang']):'Nėra kategorijos')?></span></div>
 <div class="bar w75"><span class="center">&pound; <?=_money($product['price'])?></span></div>
 <? if(perm("add_menu", $acc['rights'])) { ?><div class="bar click_del"><input type="submit" value="&nbsp;" title="Ištrinti" rel="<?=$product['menu_id']?>" /></div><? } ?>
 <div class="code"><span class="l"><?=(perm("add_menu", $acc['rights']) ? "<span class=\"small_button\" onClick=\"Delete('".$product['menu_id']."')\">Pašalinti</span>":'')?></span></div>
 <div class="clear"></div>
</div>
 <?php unset($marker, $category);	endforeach; }
else
	{
?>
<div class="list_item">
 <div class="name"><span class="l">Valgiaraštis tuščias!</span></div>
 <div class="clear"></div>
</div>
<?
	}
?>
</div>
<script src="{ROOT_STUDIO}/jeditor.min.js" type="text/javascript"></script>
<script src="{ROOT_STUDIO}/jquery-ui.min.js" type="text/javascript"></script>
<script>
$(".click_del input").click(function(){
	if (confirm("Tikrai norite pašalinti prekę iš valgiaraščio?"))
			{
				var id = $(this).attr('rel');
				$(this).parent().addClass("click_load");
				showAlert("Trinama.");
				$.post("{ROOT_STUDIO}/editor.php",{id: "menu-item/delete",value: id}).done(function(data){if(data == "OK"){window.location="{URL}/menu"}showAlert(data);});
			}
		});
$(document).ready(function()
	{
		$("#button").click(function() {
			showAlert("Pridedamas produktas.");
			$.post("{ROOT_STUDIO}/editor.php",{id: "menu-item/add",value: $("#product_id").val()}).done(function(data){if(data == "OK"){window.location="{URL}/menu"}showAlert(data);});
		});
		$('.edit_select').editable("{ROOT_STUDIO}/editor.php", {
		 data   : "{<?
		$SQL = $DB->prepare("SELECT * FROM dish_categories WHERE is_draft = 0 ORDER BY name_key ASC");
		$SQL->execute();
		$result = $SQL->fetchAll();
		if ($result)
			{
			foreach($result as $category):
			?>'<?=$category['category_id']?>':'<?=_MultiKey($category['name_key'], $set['lang'])?>',<?
			endforeach; }
?>}",
		 type   : 'select',
		 indicator : "Išsaugoma...",
		 tooltip   : "Spausk pelę kad pradėtum redagavimą.",
		 onblur	: "submit",
	});
	function rsizer()
			{
				var width = $(".content").width() - ($("#button").width() + 42);
				if (width<=580) { width = 580; }
				$(".product_input").css("width",width);
			}
		$( window ).bind("resize", function(){rsizer();});
		rsizer();
	});
function Money(num) {
    var p = num.toFixed(2).split(".");
    return p[0].split("").reverse().reduce(function(acc, num, i, orig) {
        return  num + (i && !(i % 3) ? " " : "") + acc;
    }, "") + "." + p[1];
}
function deMoney(num) {
    return num.replace(" ","");;
}
$(function() {
    var products = [
      <?php
	  $SQL = $DB->prepare("SELECT * FROM studio_products WHERE is_draft = 0 ORDER BY name_key ASC");
		$SQL->execute();
		$result = $SQL->fetchAll();
		if ($result)
			{
			foreach($result as $prd)
			{
				?>
				{
					value: "<?=_MultiKey($prd['name_key'], $set['lang'])?>",
					product_code: "<?=$prd['product_id']?>"
				 },
				<?php
			}
		}
	  ?>
    ];
    $( "#product_name" ).autocomplete({
      minLength: 0,
      source: products,
      focus: function( event, ui ) {
        $( "#product_name" ).val( ui.item.value );
        return false;
      },
      select: function( event, ui ) {
        $( "#product_name" ).val( ui.item.value );
        $( "#product_id" ).val( ui.item.product_code );
        return false;
      }
    })
    .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( "<a>" + item.value + "</a>" )
        .appendTo( ul );
    };
  });
  </script>