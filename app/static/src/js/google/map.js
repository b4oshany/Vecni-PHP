var gmap = null;
function searchFor(text){
    // Allow the first added marker to be in the center of map.
    gmap.settings.placeCenter = true;
    gmap.clearMap();
    // Limit the result to one.
    gmap.settings.limitResults = 1;
    // Perform a search query based on the address string.
    // In addition, override the default callback function by supplying a function as the
    // the second argument. This function takes in the results of the search and the status.
    gmap.textSearch(text, function(results, status){
        // Use the actually default callback function to filter places but override the
        // creation of markers by supply a function as the third argument in the mapQueryResult function. This function
        // takes in the current indexed place and the index number within the filter process.
        gmap.mapQueryResult(results, status, function(place, index){
            // Note. We don't have to manage the result status since it is already been taken care of by the mapQueryResult function.
            // Now that we are in the filtering process of places, we can create markers or do anything we like.
            // In this case, I'm adding additional options (i.e. allow the marker to be draggable)
            // to the marker.
            gmap.createMarker(
                GMaps.formatQueryResult(place),
                place.geometry.location,
                { draggable: true },
                function(marker){              
                    // Set queue to 0,0 as start.
                    app.queue.update_cords = 0;
                    // Adding event handlers to a marker.
                    GMaps.addListener(marker, "dragend", function(e){
                        var position = this.getPosition();
                        $("#property_update_address #latitude").val(position.k);
                        $("#property_update_address #longitude").val(position.D);
                        // If a dragend occurs, increase the queue by 1.
                        app.queue.update_cords += 1;
                        console.log(app.queue.update_cords);
                        // Update the latitudinal and longitudinal cordinates of the property after 5 seconds.
                        setTimeout(function(){
                            // If another queue is in process, indicate by the different from queue_update_cords and 1,
                            // do not perform a update and decrement the queue.
                            console.log("s---");
                            console.log(app.queue.update_cords)
                            console.log("e---");
                            if(app.queue.update_cords != 1){
                                app.queue.update_cords -= 1;
                                return;
                            }
                            $form = $("#property_update_address");
                            $lat = $("#property_update_address #latitude");
                            $long = $("#property_update_address #longitude");
                            $.post($form.attr("action"), {"latitude": $lat.val(), "longitude":$long.val()}, function(response){
                                
                            });
                            app.queue.update_cords = 0;
                        }, 8000);
                    });
                }
            );
        });
    });
}

function mapInitializer(){
    // Create a map object by passing the canvas id of the map.
    gmap = new GMaps('map-canvas');
    gmap.mapOptions.zoom = 16;
    // Initiate the map.
    gmap.initialize();
    // Disable the displaying of places nearby.
    gmap.settings.showPlacesNearby = false;
    
    // Get the property address string.
    var address = $(".property_address address").text();
    if(!isEmpty(property.address.latitude)){
        gmap.createMarker(
            property.title,
            GMaps.createCords(property.address.latitude, property.address.longitude),
            {draggable: true}
        );
    }else{
        searchFor(address);
    }
}

$(document).ready(function(){
    setTimeout(function(){
        // Map querys.
        var url = 'https://maps.googleapis.com/maps/api/js?';
        var key = 'key=AIzaSyDAxXjRqttkCioFimHnLUhq6ajAF05blC8';
        url = url.join('', key);
        var region = 'region=JAM';
        var call_back = 'callback=mapInitializer';
        var signin_enable = "signed_in=true";
        var places_library = "libraries=places";
        // Script a script and inject it into the page.
        infer.script(url.join('&', region, call_back, signin_enable, places_library));
    }, 5000);
});
