<!-- BEGIN: main -->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="http://hpneo.github.io/gmaps/gmaps.js"></script>
<script type="text/javascript">
var markersArray = [];
var map;

$(document).ready(function () {
    map = new GMaps({
        div: '#map',
        lat: {DATA.latitude},
        lng: {DATA.longitude}
    });
	marker = map.addMarker({
		lat: {DATA.latitude},
		lng: {DATA.longitude}
	});

    GMaps.on('marker_added', map, function (marker) {
        $('#latitude').val(marker.getPosition().lat());
		$('#longitude').val(marker.getPosition().lng());
    });

    GMaps.on('click', map.map, function (event) {
        var index = map.markers.length;
        var lat = event.latLng.lat();
        var lng = event.latLng.lng();

		//var template = $('#edit_marker_template').text();

        //var content = template.replace(/{{index}}/g, index).replace(/{{lat}}/g, lat).replace(/{{lng}}/g, lng);
		deleteOverlays();
		//map.singleMarker = true;
        marker = map.addMarker({
            lat: lat,
            lng: lng,
            title: 'Marker #' + index,
            infoWindow: {
                //content: content
            }
        });
		markersArray.push(marker);
    });
	
	function deleteOverlays() {
		if (markersArray) {
			for (i in markersArray) {
				markersArray[i].setMap(null);
			}
		markersArray.length = 0;
		}
	}
});
</script>
<!-- BEGIN: error -->
<div class="quote">
	<blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post" class="confirm-reload">
	<input name="save" type="hidden" value="1" />
	<table class="tab1">
		<colgroup>
			<col class="w200" />
			<col />
		</colgroup>
		<tfoot>
			<tr>
				<td colspan="2" class="center"><input type="submit" value="{LANG.save}"/></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td class="right strong">{LANG.location_name}</td>
				<td><input class="w500" type="text" value="{DATA.location_name}" name="location_name" id="location_name" maxlength="255" /></td>
			</tr>
			<tr>
				<td class="right strong">{LANG.latitude}</td>
				<td><input class="w500" type="text" value="{DATA.latitude}" name="latitude" id="latitude" maxlength="255" /></td>
			</tr>
			<tr>
				<td class="right strong">{LANG.longitude}</td>
				<td><input class="w500" type="text" name="longitude" id="longitude" value="{DATA.longitude}"/></td>
			</tr>
            <tr>
            	<td class="right strong">{LANG.map}</td>
            	<td><div id="map"></div></td>
          	</tr>
			<tr>
				<td class="right strong">{LANG.location_type}</td>
				<td>
                	<select name="location_type">
                        <!-- BEGIN: location_type -->
                        <option {LOCTYPE.slt} value="{LOCTYPE.locID}">{LOCTYPE.locName}</option>
                        <!-- END: location_type -->
                  	</select>
                </td>
			</tr>
            <tr>
				<td class="right strong">{LANG.parent_id}</td>
				<td>
                	<select name="parent_id">
                        <!-- BEGIN: locations -->
                        <option {LOCATION.slt} value="{LOCATION.location_id}">{LOCATION.location_name}</option>
                        <!-- END: locations -->
                  	</select>
                </td>
			</tr>
			<tr>
				<td class="right strong">{LANG.description}</td>
				<td >{DESC}</td>
			</tr>
		</tbody>
	</table>
</form>
  
<style type="text/css">
#map {
	height: 300px;
	background: #6699cc;
}
</style>

<!-- END: main -->
