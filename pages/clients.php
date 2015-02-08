<?php
	if ($ruinedstudio == false)
		exit;
	$header = array('title'=>'Klientai','type'=>'main','robots'=>'noindex,nofollow','author'=>'By Ruined Studio');
?>

<div class="fill_top"></div>
<div class="top_fixed">
 <div class="top_rel"><div class="top_abs">
  <div class="top_navigation">
	<a class="ruins" href="{URL}/clients"><span>Naujausi</span></a>
	<a class="ruins" href="{URL}/clients/1/immaculate"><span>Nepriekaištingi</span></a>
	<a class="ruins" href="{URL}/clients/1/owe"><span>Skolingi</span></a>
	<a class="ruins" href="{URL}/clients/1/asc"><span>Seniausi</span></a>
	<a class="logout" href="{URL}/logout" title="Atsijungti"></a>
	<a class="website" href="{ROOT}" title="Peržiūrėti svetainę" target="_blank"></a>
  </div>
 </div></div>	
</div>
<h1>Klientai</h1>
<br/>
<div class="list">
<div class="list_item top">
 <div class="marker"></div>
 <div class="name"><span class="l"><strong>Pavadinimas</strong></span></div>
 <div class="code"><span class="l"><strong>Telefonas</strong></span></div>
 <div class="code"><span class="l"><strong>Užsakymai</strong></span></div>
 <div class="vatcode"><span class="l"><strong>Skola</strong></span></div>
</div>
<?php
	if ($set_id[1] == "asc")
		{
			$SQL = $DB->prepare("SELECT * FROM studio_commerce_clients ORDER BY commerce_client ASC");
			$SQL->execute();
		}
	elseif ($set_id[1] == "owe")
		{
			$SQL = $DB->prepare("SELECT * FROM studio_commerce_clients WHERE indebted > ? ORDER BY indebted DESC");
			$SQL->execute(array('0'));
		}
	elseif ($set_id[1] == "immaculate")
		{
			$SQL = $DB->prepare("SELECT * FROM studio_commerce_clients WHERE indebted = ? AND bought > ? ORDER BY indebted DESC");
			$SQL->execute(array('0','0'));
		}
	else
		{
			$SQL = $DB->prepare("SELECT * FROM studio_commerce_clients ORDER BY commerce_client DESC");
			$SQL->execute();
		}
	$result = $SQL->fetchAll();
	if ($result)
		{
		foreach($result as $client):
		if ($client['bought']>0 AND $client['indepted'] == 0) { $marker = 'green'; }
		if ($client['indebted'] > 0) { $marker = 'red'; }
		$dist = _getDistanceAndTime($settings['address'],$client['street_number']." ".$client['address'], "driving", "imperial");
?>
<card id="client<?=$client['commerce_client']?>">
<div class="desc_item">
<div class="list_item cursor">
 <div class="marker <?=$marker?>"></div>
 <div class="name"><span class="l"><?=$client['name']?></span></div>
 <div class="code"><span class="l"><?=$client['phone']?></span></div>
 <div class="code"><span class="l"><?=$client['bought']?></span></div>
 <div class="vatcode"><span class="l">&pound; <?=_money($client['indebted'])?></span></div>
 <div class="clear"></div>
</div>
<div class="desc">
 <div class="map_canvas"></div>
 <span class="address_title">Adresas:</span>
 <span class="address"><?=$client['address']?></span>
 <span class="distance_title">Atstumas:</span>
 <span class="distance"><?=$dist['distance']." (".$dist['time'].")";?></span>
 <span class="phone_title">Telefonas:</span>
 <span class="phone"><?=$client['phone']?></span>
 <span class="email_title">El. paštas:</span>
 <span class="email"><?=$client['email']?></span>
 <span class="notes_title">Pastabos:</span>
 <span class="notes"><?=$client['notes']?><br/>Butas/kambarys: <?=$client['flat_number']?><br/>Gatvės numeris: <?=$client['street_number']?></span>
</div>
</div>
</card>
 <?php $marker = "";	endforeach; }
else
	{
?>
<div class="list_item">
 <div class="name"><span class="l">Klientų nerasta.</span></div>
 <div class="clear"></div>
</div>
<?
	}
?>
</div>
<script>
$(document).ready(function(){
$(window).load(function(){
		$(".desc_item .list_item").click(function(){
		$(".desc_item").each(function(i){$(this).css({"height": "47px"});});
		$(this).parent().css({"height": "290px"});
		initialize($(this).parent().find(".desc").find(".address").html(), $(this).parent().find(".desc").find(".map_canvas").get(0));
	});
});
	
});
</script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>		
<script type="text/javascript">
<?
	$latlng = getLnt($settings['address']);
?>
    //<![CDATA[
      // this variable will collect the html which will eventually be placed in the side_bar 

      var gmarkers = [];
      var map = null;
      var startLocation = null;
      var endLocation = null;

			function initialize(my_destination, my_canvas) {
				var center = new google.maps.LatLng(<?=$latlng['lat']?>,<?=$latlng['lng']?>);
				
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
					origin: "<?=$settings['address']?>", 
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
