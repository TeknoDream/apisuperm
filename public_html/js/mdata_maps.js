/*MAPAS GOOGLE*/
GMaps=[];
function InitMaps($where,options) {
	options=options==undefined?{}:options
	var $lat=$where.find('[data-name="lat"]')
	,	$lon=$where.find('[data-name="lon"]')
	,	$zoom=$where.find('[data-name="zoom"]')
	,	$polygon=$where.find('[data-name="polygon"]')
	,	$decode=$where.find('[data-name="decode"]')
	,	$direction=$where.find('[data-name="direction"]')
	,	$reset=$where.find('[data-name="reset"]')
	,	$city=$where.find('[data-name="city"]')	
	,	setMarker=$where.attr('data-setMarker')=='true'
	,	setInverse=$where.attr('data-setInverse')=='true'
	,	setDecode=$where.attr('data-setDecode')=='true'
		_id=$where.attr('data-id')
	,	$map=$where.find('[data-name="map"]')

	var	lat=Number($lat.val())
	,	lon=Number($lon.val())
	,	zoom=$zoom.length?Number($zoom.val()):15
	,	latlng = new google.maps.LatLng(lat, lon)
	,	myOptions = {
			zoom: zoom,
			center: latlng,
			streetViewControl: true,
			panControl: true,
			zoomControl: true,
			scaleControl: true,
			scrollwheel: false,
			mapTypeControl: true,		
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
	,	map = new google.maps.Map($map.get(0), myOptions)
	
	if(options.styles!=undefined)
		map.setOptions({styles: options.styles});
	
	if($zoom.length){
		google.maps.event.addListener(map, 'zoom_changed', function(){
			$zoom.val(map.getZoom());
		});
	}
	
	var TMap={map:map}
	TMap.markers={}
	TMap.info={}
	if(setMarker&&!$polygon.length){
		var marker = new google.maps.Marker({
			position: latlng, 
			map: map,
			draggable: true
		});	
		TMap.marker=marker;
		google.maps.event.addListener(marker, 'mouseup', function(){
			var markerLatLng = marker.getPosition();
			$lat.val(markerLatLng.lat());
			$lon.val(markerLatLng.lng());
			if($zoom.length) $zoom.val(map.getZoom());
			if(setInverse){
				SearchDirection(markerLatLng, function(results) {		
					var getSearch=getObjects(results,"0","locality")
					,	ciudad=getSearch[0]["long_name"];	
					$city.find('option[data-city="'+ciudad+'"]').attr('selected','selected');				
				});
			}			
		});
	
	}
	if(setDecode){
		if($decode.length){
			$decode.bind('click',function(event){ 
				event.preventDefault();
				event.stopPropagation();
				var direccion=$direction.val()
				if($city.length){
					var $OptSel=$city.find('option:selected')
					if($OptSel.length)
						direccion=$OptSel.attr('data-country')
								+	', '
								+	$OptSel.attr('data-district')
								+	', '
								+	$OptSel.attr('data-city')
								+	', '
								+	direccion;
				}

				SearchInMap(direccion, function(results) {			
					map.setCenter(results[0].geometry.location);
					map.setZoom(16);
					if(marker!=undefined) marker.setPosition(results[0].geometry.location)						
					$lat.val(results[0].geometry.location.lat());
					$lon.val(results[0].geometry.location.lng());
				});
			});
		}
	}
	if($polygon.length){
		var creator = new PolygonCreator(map,$polygon)
		,	polygon_string=$polygon.val()

		if(polygon_string!=""){				
			var polygon_array = polygon_string.split(",")
			$.each(polygon_array, function(index, value) { 
				var polygon_string_latlng = value.split(" ")
				,	polygon_latlng = new google.maps.LatLng(polygon_string_latlng[0],polygon_string_latlng[1]);
				creator.pen.draw(polygon_latlng);							
			});
			creator.pen.drawPloygon(creator.pen.listOfDots,creator.map,creator.pen);
		}
		if($reset.length){
			$reset.bind('click',function(event){ 
				event.preventDefault();
				event.stopPropagation();
				creator.destroy();
				creator = new PolygonCreator(map,$polygon)
				$polygon.val("");
			});
		}
	}	
	if(_id!=undefined)	var toAdd={TMap:TMap,latlng:latlng,id:_id}
	else 				var toAdd={TMap:TMap,latlng:latlng}


	GMaps.push(toAdd)
	return toAdd;
};
var map_refresh=function(){
	$.each(GMaps,function(index,GMap){
		google.maps.event.trigger(GMap.TMap.map, "resize");
		if(GMap.TMap.marker!=undefined)
			GMap.TMap.map.setCenter(GMap.TMap.marker.getPosition());
		else
			GMap.TMap.map.setCenter(GMap.latlng);
	});
}
var SearchInMap=function(direccion,callBack){
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({'address': direccion}, function(results, status) {	
		if (status == google.maps.GeocoderStatus.OK) {
			if(typeof callBack == 'function') callBack(results);
		}
		else{
			console.log("Geocode generó un error: " + status);
			return false;
		}
	});
}
var SearchDirection=function(markerLatLng,callBack){
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({'location': markerLatLng}, function(results, status) {		
		if (status == google.maps.GeocoderStatus.OK) {
			if(typeof callBack == 'function') callBack(results);
			var Busqueda=getObjects(results,"0","locality")
			,	direccion=Busqueda[0]["long_name"];	
			/*AQUI LA CIUDAD*/						
		}
		else{
			console.log("Geocode generó un error: " + status);
			return false;
		}
	});
}

function CreateMarker(TMap,loc,id,content,options){
	if(options==undefined) options={}
	var	map=TMap.map
	,	latlng = new google.maps.LatLng(loc.lat, loc.lon)
	,	icon=options.icon==undefined?'/img/pin-red.png':options.icon
	,	marker = new google.maps.Marker({
				position: latlng
			,	map: map
			,	icon:icon
		})
	,	content=content
	,	InfoWindow = new google.maps.InfoWindow({content: content});

	TMap.markers[id]=marker;	
	TMap.info[id]=InfoWindow;
	google.maps.event.addListener(marker, 'mouseup', function(){
		ShowInfoWIndow(id,TMap)
	});
	return marker;
}

var CloseAllInfoWindow=function(TMap){
	$.each(TMap.info,function(index,data){
		CloseInfoWindow(data,index)
	})	
}
var CloseInfoWindow=function(InfoW,id){
	InfoW.close();
}
var ShowInfoWIndow=function(id,TMap){
	CloseAllInfoWindow(TMap);
	TMap.info[id].open(TMap.map,TMap.markers[id]);
}	
