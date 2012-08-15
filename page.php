<?php get_header(); ?>
<script type="text/javascript">
	var wnd_ctaLayer = null;
	var map = null;
	var markerModel = {};
	var maxMarkerId = 1;

	var marker_bubble_template;
	var poi_type_tool_item_template;

	var GET_PAGE_URL = '<?php echo site_url() ?>/?json=get_page';
	var WRITE_PAGE_URL = '<?php echo site_url() ?>/?json=create_post&status=publish&type=page&na=1';
	var DELETE_PAGE_URL = '<?php echo site_url() ?>/?json=create_post&delete=1';

	var poiTypes = ['cocker', 'cyclo', 'eating', 'tent'];
	var poiIcons = {};
	$.each(poiTypes, function (key, poiType) {
		var icon = new google.maps.MarkerImage(
				'<?php echo get_template_directory_uri(); ?>/images/markers/' + poiType + '.png',
				// This marker is 20 pixels wide by 32 pixels tall.
				new google.maps.Size(80, 83),
				// The origin for this image is 0,0.
				new google.maps.Point(0,0),
				// The anchor for this image is the base of the flagpole at 0,32.
				new google.maps.Point(0, 40));
		poiIcons[poiType] = icon;
	});

	var onReadyLocal = function() {
		 marker_bubble_template = _.template($("#marker_bubble_template").html());
		 poi_type_tool_item_template = _.template($("#poi_type_tool_item_template").html());

		$("#editorTools").css('display', 'inline');
		$("#editorToolsPoiTypes").hide();
		$.each(poiIcons, function (poiType, poiIcon) {
			var info = { type: poiType, icon: poiIcon };
			$("#editorToolsPoiTypes").append(poi_type_tool_item_template(info));
		});
		$(".poi_tool_item").click(function () {
			addingPoiType = $(this).data('type');
		});
		$("#editorTools").mouseover(function (ev) {
			ev.stopPropagation();
			return false;
		});
		$(".add_poi_button").mouseover(function (ev) {
			$("#editorToolsPoiTypes").show();
			ev.stopPropagation();
			return false;
		});
		$("body").mouseover(function () {
			$("#editorToolsPoiTypes").hide();
		});

		google.maps.event.addListener(map, 'click', function(event) {
			if (addingPoiType) {
			    var marker = placeMarker(null, '', '', event.latLng, addingPoiType);
			    google.maps.event.trigger(marker, 'click');
			    addingPoiType = null;
			}
		});
		initMarkers($("#content").data('postid'));

		// Inside post show kml
		var postKml = $("#content").data('kml');
		if (postKml) {
			wnd_ctaLayer = new google.maps.KmlLayer(postKml);
			wnd_ctaLayer.setMap(map);
		}
	};

	function placeMarker(postId, title, description, location, poiType) {
		var markerObj = { postId: postId, markerId: maxMarkerId, title: title, descr: description, location: location, poiType: poiType };
		markerModel[markerObj.markerId] = markerObj;
		maxMarkerId++;

		var marker = new google.maps.Marker({
			position: location,
			map: map,
			draggable: true,
			icon: poiIcons[poiType],
			clickable: true
		});
		marker.markerId = markerObj.markerId;

		var markerClickListener = function() {
			if (mapPointInfoWindow) {
				mapPointInfoWindow.close();
			}
			mapPointInfoWindow = new google.maps.InfoWindow({content: 'Loading...'});
			mapPointInfoWindow.setContent(marker_bubble_template(markerObj));
			mapPointInfoWindow.open(map, this);

			google.maps.event.addListener(mapPointInfoWindow, "domready", function() {
				var markerEl = $("#bmarkerInfoWindow" + marker.markerId);
				markerEl.find('.mbubble_title').focus();
				markerEl.find('.mbubble_ok').click(function () {
					markerObj.title = markerEl.find('.mbubble_title').val();
					markerObj.descr = markerEl.find('.mbubble_descr').val();
					markerObj.poiType = markerEl.find('.poi_type_select.active').data('type');
					marker.setIcon(poiIcons[markerObj.poiType]);
					updateSaveMarkerAsync(markerObj);
					mapPointInfoWindow.close();
				});
				markerEl.find('.mbubble_cancel').click(function () {
					mapPointInfoWindow.close();
				});
				markerEl.find('.mbubble_remove').click(function () {
					delete markerModel[marker.markerId];
					marker.setMap(null);
					removeMarkerAsync(markerObj);
					mapPointInfoWindow.close();
				});
				markerEl.find('.poi_type_select').click(function () {
					markerEl.find('.poi_type_select').removeClass('active');
					$(this).addClass('active');
				});
			});
		};
		google.maps.event.addListener(marker, "click", markerClickListener);

		google.maps.event.addListener(marker, 'dragend', function() {
			markerObj.location = marker.getPosition();
			updateSaveMarkerAsync(markerObj);
		});
		
		return marker;
	}

	function initMarkers(parentPageId) {
		var url = GET_PAGE_URL + '&children=1&page_id=' + parentPageId;

		$.ajax({
		    url: url,
		    type: 'GET',
		    dataType: 'json',
		    success: initMarkersSuccess,
		    error: initMarkersError
		});
	}

	function initMarkersSuccess(data) {
		if (data.status == 'ok') {
			var page = data.page;
			$.each(page.children, function (key, childData) {
				var location = defaultLocation;
				var poiType = childData.custom_fields['poiType'];
				if (childData.custom_fields && childData.custom_fields['lat'] && childData.custom_fields['lng']) {
					location = new google.maps.LatLng(childData.custom_fields['lat'], childData.custom_fields['lng']);
				}
				placeMarker(childData.id, childData.title, childData.contentStripped, location, poiType);
			});
			return;
		}
		alert('Failed to read data!');
	}

	function initMarkersError(param) {
		alert('Failed to read data!');
	}

	var mapPointInfoWindow = null;
	var addingPoiType = null;

	function updateSaveMarkerAsync(markerObj) {
		var parentPostId = $("#content").data('postid');
		var url = WRITE_PAGE_URL
						+ '&title=' + markerObj.title
						+ '&content=' + markerObj.descr
						+ '&id=' + markerObj.postId
						+ '&markerId=' + markerObj.markerId
						+ '&lat=' + markerObj.location.lat()
						+ '&lng=' + markerObj.location.lng()
						+ '&poiType=' + markerObj.poiType
						+ '&parent_id=' + parentPostId;

		$.ajax({
		    url: url,
		    type: 'GET',
		    dataType: 'json',
		    success: updateMarkersSuccess,
		    error: updateMarkersError
		});
	}

	function updateMarkersSuccess(data) {
		if (data.status == 'ok') {
			markerModel[data.markerId].postId = data.post.id;
			return;
		}
		alert('Failed to update marker!');
	}

	function updateMarkersError() {
		alert('Failed to update marker!');
	}

	function removeMarkerAsync(markerObj) {
		if (markerObj.postId) {
			var url = DELETE_PAGE_URL
						+ '&postId=' + markerObj.postId;

			$.ajax({
		    	url: url,
			    type: 'GET',
			    dataType: 'json',
			    success: removeMarkersSuccess,
			    error: removeMarkersError
			});
		}
	}

	function removeMarkersSuccess(data) {
		if (data.status == 'ok') {
			return;
		}
		alert('Failed to remove marker!');
	}

	function removeMarkersError() {
		alert('Failed to remove marker!');
	}

</script>

<script type="text/html" id="marker_bubble_template">
	<div id="bmarkerInfoWindow<%= markerId %>" class="poi_info_window">
		title: <input type="text" class="mbubble_title" value="<%= title %>"></input><br/>
		text: <textarea class="mbubble_descr"><%= descr %></textarea><br/>
		<% $.each(poiIcons, function (iterPoiType, iterPoiIcon) { %>
			<div class="poi_type_select <%= iterPoiType %> <%= (iterPoiType == poiType) ? 'active': '' %>"
				data-type="<%= iterPoiType %>"></div>
		<% }); %>
		<input type="button" value="OK" class="mbubble_ok"></input>
		<input type="button" value="Cancel" class="mbubble_cancel"></input>
		<input type="button" value="Remove" class="mbubble_remove"></input>
	</div>
</script>

<script type="text/html" id="poi_type_tool_item_template">
	<div id="poiToolItem<%= type %>" data-type="<%= type %>" class="poi_tool_item"
		style=" background: url('<%= icon.url %>'); width: <%= icon.size.width %>px; height: <%= icon.size.height %>px;">
	</div>
</script>

<?php the_post(); ?>

		<div id="container">
			<div id="content"
				data-kml="<?php if (get_post_custom_values('kml')) { $myKml = get_post_custom_values('kml'); echo $myKml[0]; } ?>"
				data-postid="<?php the_ID(); ?>">
			
				
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
					<h1 class="entry-title"><?php the_title(); ?></h1>
					<div class="entry-content">
<?php the_content(); ?>
<?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'wandrak-02' ) . '&after=</div>') ?>					
<?php edit_post_link( __( 'Edit', 'wandrak-02' ), '<span class="edit-link">', '</span>' ) ?>
					</div><!-- .entry-content -->
				</div><!-- #post-<?php the_ID(); ?> -->			
			
<?php if ( get_post_custom_values('comments') ) comments_template() // Add a custom field with Name and Value of "comments" to enable comments on this page ?>			
			
			</div><!-- #content -->		
		</div><!-- #container -->
		
<?php get_sidebar(); ?>	
<?php get_footer(); ?>
