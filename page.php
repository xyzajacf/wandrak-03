<?php get_header(); ?>
<script type="text/javascript">
	var wnd_ctaLayer = null;
	var map = null;
	var markerModel = {};
	var maxMarkerId = 1;

	var marker_bubble_template;
	var GET_PAGE_URL = '<?php $BASE_URL ?>/?json=get_page'

	var onReadyLocal = function() {
		 marker_bubble_template = _.template($("#marker_bubble_template").html());

		$("#editorTools").css('display', 'inline');
		$(".add_poi_button").click(function () {
			addingMapPoint = true;
		});

		google.maps.event.addListener(map, 'click', function(event) {
			if (addingMapPoint) {
			    placeMarker(null, '', '', event.latLng);
			    addingMapPoint = false;
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

	function placeMarker(postId, title, description, location) {
		var markerObj = { postId: postId, markerId: maxMarkerId, title: title, descr: description, location: location };
		markerModel[markerObj.markerId] = markerObj;
		maxMarkerId++;

		var marker = new google.maps.Marker({
			position: location,
			map: map,
			draggable: true,
			clickable: true
		});
		marker.markerId = markerObj.markerId;

		google.maps.event.addListener(marker, "click", function() {
			if (mapPointInfoWindow) {
				mapPointInfoWindow.close();
			}
			mapPointInfoWindow = new google.maps.InfoWindow({content: 'Loading...'});
			mapPointInfoWindow.setContent(marker_bubble_template(markerObj));
			mapPointInfoWindow.open(map, this);

			google.maps.event.addListener(mapPointInfoWindow, "domready", function() {
				var markerEl = $("#bmarkerInfoWindow" + marker.markerId);
				markerEl.find('.mbubble_ok').click(function () {
					markerObj.title = markerEl.find('.mbubble_title').val();
					markerObj.descr = markerEl.find('.mbubble_descr').val();
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
			});
		});
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
				placeMarker(childData.id, childData.title, childData.content, new google.maps.LatLng(46.980252,16.54541));
			});
			return;
		}
		alert('Failed to read data!');
	}

	function initMarkersError(param) {
		alert('Failed to read data!');
	}

	var mapPointInfoWindow = null;
	var addingMapPoint = false;

	function updateSaveMarkerAsync(markerObj) {
		var url = GET_PAGE_URL + '&children=1&page_id=' + parentPageId;

		
		$.ajax({
		    url: url,
		    type: 'GET',
		    dataType: 'json',
		    success: initMarkersSuccess,
		    error: initMarkersError
		});
		// responds with status: ok, posts: {id: ..}
	}

	function removeMarkerAsync(markerObj) {
		if (markerObj.postId) {
			var url = GET_PAGE_URL + '&children=1&page_id=' + parentPageId;

			$.ajax({
			    url: url,
			    type: 'GET',
			    dataType: 'json',
			    success: initMarkersSuccess,
			    error: initMarkersError
			});
		}
	}

</script>

<script type="text/html" id="marker_bubble_template">
	<div id="bmarkerInfoWindow<%= markerId %>">
		title: <input type="text" class="mbubble_title" value="<%= title %>"></input><br/>
		text: <textarea class="mbubble_descr"><%= descr %></textarea><br/>
		<input type="button" value="OK" class="mbubble_ok"></input>
		<input type="button" value="Cancel" class="mbubble_cancel"></input>
		<input type="button" value="Remove" class="mbubble_remove"></input>
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
