<?php
	if ($ruinedstudio == false)
		exit;
	$header = array('title'=>'Užsakymo peržiūrėjimas','type'=>'main','robots'=>'noindex,nofollow','author'=>'By Ruined Studio');
	$SQL = $DB->prepare("SELECT * FROM studio_orders WHERE order_id = ? LIMIT 1");
	$SQL->execute(array($set_id[0]));
	$result = $SQL->fetch();
?>
<div class="fill_top"></div>
<div class="top_fixed">
 <div class="top_rel"><div class="top_abs">
  <div class="top_navigation">
	<a class="ruins" href="{URL}/orders"><span>Nauji užsakymai</span></a>
	<a class="ruins" href="{URL}/orders/1/end"><span>Priimti užsakymai</span></a>
	<a class="ruins" href="{URL}/orders/1/send"><span>Išsiusti</span></a>
	<a class="logout" href="{URL}/logout" title="Atsijungti"></a>
	<a class="website" href="{ROOT}" title="Peržiūrėti svetainę" target="_blank"></a>
  </div>
 </div></div>	
</div>
<h1>Užsakymo peržiūra</h1>
<br/>
<?
if ($result['state']=='new') {
$state = "Naujas";
?>
<span class="button" onclick="Check('Accept','<?=$result['order_id']?>')">Priimti</span>
<span class="button" onclick="Check('Reject','<?=$result['order_id']?>')">Atmesti</span>
<span class="button" onclick="Check('Sent','<?=$result['order_id']?>')">Išsiusta</span>
<span class="button" onclick="Check('Delivered','<?=$result['order_id']?>')">Pristatyta</span>
<span class="button" onclick="Check('NotPaid','<?=$result['order_id']?>')">Klientas nesumokėjo / nepriėmė</span>
<div class="note">
 <div>
	Spauskite <strong>Priimti</strong>, norėdami pažymėti, kad užsakymą pradėsite daryti. <b>Atmesti</b> - jei užsakymo negaminsite visai. <b>Išsiustą</b> - kuomet užsakymas buvo pagamintas ir perduotas kurjeriui, kad pristatytų klientui. <b>Pristatyta</b> - kuomet kurjeris pristatė produkta ir už produktą buvo sumokėta. <strong>Klientas nesumokėjo / nepriėmė</strong> - tada kai produktas buvo pagamintas, tačiau klientas jo atsisakė, nesumokėjo, neatsiėmė iš kurjerio (visais atvejais kuomet patyrėte nuostolį).
	<br/>
	<strong>!</strong> Priimtų užsakymų nebebus galima atmesti, todėl jei užsakymas jums neaiškus, galite žymėti <strong>Išsiusta</strong> ir <strong>Pristatyta</strong>, nepažymėjus jog jis buvo priimtas.
 </div>
</div>
<?
} else if ($result['state']=='accepted') {
$state = "Priimtas";
?>
<span class="button" onclick="Check('Sent','<?=$result['order_id']?>')">Išsiusta</span>
<span class="button" onclick="Check('Delivered','<?=$result['order_id']?>')">Pristatyta</span>
<span class="button" onclick="Check('NotPaid','<?=$result['order_id']?>')">Klientas nesumokėjo / nepriėmė</span>
<div class="note">
 <div>
	Pažymėkite <b>Išsiustą</b> kuomet užsakymas buvo pagamintas ir perduotas kurjeriui, kad pristatytų klientui. <b>Pristatyta</b> - kuomet kurjeris pristatė produkta ir už produktą buvo sumokėta. <strong>Klientas nesumokėjo / nepriėmė</strong> - tada kai produktas buvo pagamintas, tačiau klientas jo atsisakė, nesumokėjo, neatsiėmė iš kurjerio (visais atvejais kuomet patyrėte nuostolį).<br/>
 </div>
</div>
<?
} else if ($result['state']=='sent') {
$state = "Išsiųsta";
?>
<span class="button" onclick="Check('Delivered','<?=$result['order_id']?>')">Pristatyta</span>
<span class="button" onclick="Check('NotPaid','<?=$result['order_id']?>')">Klientas nesumokėjo / nepriėmė</span>
<div class="note">
 <div>
	Pažymėkite <b>Pristatyta</b> kuomet kurjeris pristatė produkta ir už produktą buvo sumokėta. <strong>Klientas nesumokėjo / nepriėmė</strong> - tada kai produktas buvo pagamintas, tačiau klientas jo atsisakė, nesumokėjo, neatsiėmė iš kurjerio (visais atvejais kuomet patyrėte nuostolį).<br/>
 </div>
</div>
<?
} else if ($result['state']=='delivered') {
$state = "Pristatyta";
?>
<div class="note">
 <div>
	Šis užsakymas buvo pristatytas klientui. Negalima atlikti jokių papildomų veiksmų.
 </div>
</div>
<?
} else if ($result['state']=='not_paid') {
$state = "Nesumokėjo";
?>
<span class="button" onclick="Check('Sent','<?=$result['order_id']?>')">Persiųsti</span>
<span class="button" onclick="Check('Delete','<?=$result['order_id']?>')">Išmesta</span>
<div class="note">
 <div>
	Jei užsakymas negalėjo būti pristatytas klientui, tačiau jei jį išsiuntėte kitam klientui spauskite <strong>Persiųsti</strong>, jei iš šio užsakymo jau nebegalima uždirbti ir patyrete tik nuostoli spauskite <strong>Išmesti</strong>.
 </div>
</div>
<?
} else if ($result['state']=='delete') {
$state = "Išmestas";
?>
<div class="note">
 <div>
	Dėl šio užsakymo buvo patirtas nuostolis.
 </div>
</div>
<?
}
$SQL = $DB -> prepare( "SELECT * FROM studio_commerce_clients WHERE commerce_client=? LIMIT 1");
$SQL -> execute (array($result['client_id']));
$clnt = $SQL -> fetch();
$dist = _getDistanceAndTime($settings['address'],$clnt['street_number']." ".$clnt['address'], "driving", "imperial");

?>
<div class="list">
<div class="desc_item" style="height: 290px;">
<div class="list_item cursor">
 <div class="marker"></div>
 <div class="name"><span class="l">Užsakymas #<? printf("%06d", $result['order_id']);?></span></div>
 <div class="code"></div>
 <div class="code"><span class="l"><b><?=$state?></b></span></div>
 <div class="code"><span class="l"><?=gmdate("Y.m.d H:i", $result['date'])?></span></div>
 <div class="clear"></div>
</div>
<div class="desc">
 <div class="map_canvas"></div>
 <span class="address_title">Adresas:</span>
 <span class="address"><?=$clnt['street_number']?> <?=$clnt['address']?></span>
 <span class="distance_title">Atstumas:</span>
 <span class="distance"><?=$dist['distance']." (".$dist['time'].")";?></span>
 <span class="phone_title">Telefonas:</span>
 <span class="phone"><?=$clnt['phone']?></span>
 <span class="email_title">El. paštas:</span>
 <span class="email"><?=$clnt['email']?></span>
 <span class="notes_title">Pastabos:</span>
 <span class="notes"><?=($clnt['flat_number'] ? '<b>Buto/kambario nr: '.$clnt['flat_number'].'</b><br/>':'')?><?=$result['notes']?></span>
</div>
</div>

<div class="list_item top">
 <div class="marker"></div>
 <div class="name"><span class="l"><strong>Produktas</strong></span></div>
 <div class="code"></div>
 <div class="code"><span class="l"><strong>Kiekis</strong></span></div>
 <div class="code"><span class="l"><strong>Suma</strong></span></div>
</div>
<?php
	$SQL = $DB->prepare("SELECT studio_orders_list.price, name_key, count FROM studio_orders_list, studio_products WHERE order_id = ? AND studio_products.product_id = studio_orders_list.product_id ORDER BY item_id ASC");
	$SQL->execute(array($result['order_id']));
	$item = $SQL->fetchAll();
	if ($item)
		{
		foreach($item as $order):
?>
<div class="list_item">
 <div class="marker blue"></div>
 <div class="name"><span class="l"><?=_MultiKey($order['name_key'], $set['lang'])?></span></div>
 <div class="code"></div>
 <div class="code"><span class="l"><?=$order['count']?></span></div>
 <div class="code"><span class="l">&pound; <?=_money($order['price'])?></span></div>
 <div class="clear"></div>
</div>
 <?php $marker = "";
 endforeach; }
else
	{
?>
<div class="list_item">
 <div class="name"><span class="l">Nėra užsakytų produktų.</span></div>
 <div class="clear"></div>
</div>
<?
	}
?>
<div class="list_item">
 <div class="name"><span class="l"><strong>&sum; Siuntimo kaina:</strong></span></div>
 <div class="code"></div>
 <div class="code"></div>
 <div class="code"><span class="l"><strong>&pound; <?=_money($result['price_shipping'])?></strong></span></div>
 <div class="clear"></div>
</div>
<div class="list_item">
 <div class="name"><span class="l"><strong>&sum; Mokesčiai (<?=$settings['tax']?> %):</strong></span></div>
 <div class="code"></div>
 <div class="code"></div>
 <div class="code"><span class="l"><strong>&pound; <?=_money($result['price_tax'])?></strong></span></div>
 <div class="clear"></div>
</div>
<div class="list_item">
 <div class="name"><span class="l"><strong>&sum; Viso mokėti:</strong></span></div>
 <div class="code"></div>
 <div class="code"></div>
 <div class="code"><span class="l"><strong>&pound; <?=_money($result['price'])?></strong></span></div>
 <div class="clear"></div>
</div>
</div>
<script>
function Check(type, id)
	{
		if (confirm("Atlikti veiksmą?"))
			{
				showAlert("Vykdoma.");
				$.post("{ROOT_STUDIO}/editor.php",{id: "orders-view/"+type,value: id}).done(function(data){if(data == "OK"){window.location="{URL}/orders-view/"+id;}showAlert(data);});
			}
	}
$(document).ready(function()
	{
		$(window).load(function(){initialize($(".address").html(), $(".map_canvas").get(0));});
	});
</script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>		
<script type="text/javascript">
    //<![CDATA[
      // this variable will collect the html which will eventually be placed in the side_bar 

      var gmarkers = [];
      var map = null;
      var startLocation = null;
      var endLocation = null;

			function initialize(my_destination, my_canvas) {
				var center = new google.maps.LatLng(24.7756,121.0062);
				
				map = new google.maps.Map(my_canvas, {
			    	center: center,
			    	zoom: 13,
			    	mapTypeId: google.maps.MapTypeId.TERRAIN,
					panControl: false,
					zoomControl: false,
					disableDefaultUI: true
			  	});

				var directionsService = new google.maps.DirectionsService(); 
				var request = { 
					origin: "Burrell Road Ipswich Suffolk IP2 8AN", 
					destination: my_destination,
					travelMode: google.maps.DirectionsTravelMode.DRIVING
				}; 

				var polyline = new google.maps.Polyline({
					path: [],
					geodesic: true,
					strokeColor: '#008aff',
					strokeWeight: 5,
					strokeOpacity: 0.7
				});
				
    directionsService.route(request, function(response, status) {
      if (status == google.maps.DirectionsStatus.OK) {
        var bounds = new google.maps.LatLngBounds();
        var route = response.routes[0];
        startLocation = new Object();
        endLocation = new Object();



	var path = response.routes[0].overview_path;
	var legs = response.routes[0].legs;
        for (i=0;i<legs.length;i++) {
          if (i == 0) { 
            startLocation.latlng = legs[i].start_location;
            startLocation.address = legs[i].start_address;
            createMarker(legs[i].start_location,"start",legs[i].start_address,"green");
          }
          endLocation.latlng = legs[i].end_location;
          endLocation.address = legs[i].end_address;
          var steps = legs[i].steps;
          for (j=0;j<steps.length;j++) {
            var nextSegment = steps[j].path;
            for (k=0;k<nextSegment.length;k++) {
              polyline.getPath().push(nextSegment[k]);
              bounds.extend(nextSegment[k]);
            }
          }
        }

        polyline.setMap(map);
        map.fitBounds(bounds);
        createMarker(endLocation.latlng,"end",endLocation.address,"red");
                                                
      }
    });
  }
var icons = new Array();
icons["red"] = new google.maps.MarkerImage("{DISPLAY_STUDIO}/images/marker_destination.png",
      // This marker is 20 pixels wide by 34 pixels tall.
      new google.maps.Size(64, 64),
      // The origin for this image is 0,0.
      new google.maps.Point(0,0),
      // The anchor for this image is at 9,34.
      new google.maps.Point(32, 62));
function getMarkerImage(iconColor) {
   if ((typeof(iconColor)=="undefined") || (iconColor==null)) { 
      iconColor = "red"; 
   }
   if (!icons[iconColor]) {
      icons[iconColor] = new google.maps.MarkerImage("{DISPLAY_STUDIO}/images/marker_start.png",
      // This marker is 20 pixels wide by 34 pixels tall.
      new google.maps.Size(64, 64),
      // The origin for this image is 0,0.
      new google.maps.Point(0,0),
      // The anchor for this image is at 6,20.
      new google.maps.Point(32, 62));
   } 
   return icons[iconColor];

}
  // Marker sizes are expressed as a Size of X,Y
  // where the origin of the image (0,0) is located
  // in the top left of the image.
 
  // Origins, anchor positions and coordinates of the marker
  // increase in the X direction to the right and in
  // the Y direction down.

  var iconImage = new google.maps.MarkerImage('{DISPLAY_STUDIO}/images/marker_start.png',
      // This marker is 20 pixels wide by 34 pixels tall.
      new google.maps.Size(64, 64),
      // The origin for this image is 0,0.
      new google.maps.Point(0,0),
      // The anchor for this image is at 9,34.
      new google.maps.Point(32, 62));
  var iconShadow = new google.maps.MarkerImage('http://www.google.com/mapfiles/shadow50.png',
      // The shadow image is larger in the horizontal dimension
      // while the position and offset are the same as for the main image.
      new google.maps.Size(37, 34),
      new google.maps.Point(0,0),
      new google.maps.Point(9, 34));
      // Shapes define the clickable region of the icon.
      // The type defines an HTML &lt;area&gt; element 'poly' which
      // traces out a polygon as a series of X,Y points. The final
      // coordinate closes the poly by connecting to the first
      // coordinate.
  var iconShape = {
      coord: [9,9,6,1,4,2,2,4,0,8,0,12,1,14,2,16,5,19,7,23,8,26,9,30,9,34,11,34,11,30,12,26,13,24,14,21,16,18,18,16,20,12,20,8,18,4,16,2,15,1,13,0],
      type: 'poly'
  };
    
function createMarker(latlng, label, html, color) {
 //alert("createMarker("+latlng+","+label+","+html+","+color+")");
    var contentString = '<b>'+label+'</b><br>'+html;
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        shadow: iconShadow,
        icon: getMarkerImage(color),
        shape: iconShape,
        title: label,
        zIndex: Math.round(latlng.lat()*-100000)<<5
        });
        marker.myname = label;
        gmarkers.push(marker);

}
</script>
