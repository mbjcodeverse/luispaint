document.addEventListener('DOMContentLoaded', function() {
   const svgContainer = document.getElementById('map');
   const button = document.getElementById('btn-changecolor');

   let map;
   let memorial_overlay;
   let markers = [];
   
   const ROSELAWN = { lat: 10.709866448650494, lng: 122.97002282780065 };
   const DEFAULT_ZOOM = 18;
   const DEFAULT_TILT = 25;
   const DEFAULT_HEADING = -40;

   const NORTH_BOUND = 10.712408011651695;
   const SOUTH_BOUND = 10.706009446450304;
   const EAST_BOUND = 122.97356933277679;
   const WEST_BOUND = 122.9671846920831;

   const svgUrl = "views/global_assets/images/roselawnmap_edited_complete___.svg";

   async function initMap() {
       const { Map, InfoWindow } = await google.maps.importLibrary("maps");
       const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
       map = new google.maps.Map(document.getElementById("map"), {
         center: ROSELAWN,
         zoom: DEFAULT_ZOOM,
         // disableDefaultUI: true,
         heading: 320,
         tilt: DEFAULT_TILT,
         heading: DEFAULT_HEADING,
         mapId: "90f87356969d889c",
      //    styles: [
      //       {elementType: 'geometry', stylers: [{color: '#212121'}]},
      //       {elementType: 'labels.icon', stylers: [{visibility: 'off'}]},
      //       {elementType: 'labels.text.fill', stylers: [{color: '#757575'}]},
      //       {elementType: 'labels.text.stroke', stylers: [{color: '#212121'}]},
      //       {featureType: 'administrative', elementType: 'geometry', stylers: [{color: '#757575'}]},
      //       {featureType: 'administrative.country', elementType: 'labels.text.fill', stylers: [{color: '#9e9e9e'}]},
      //       {featureType: 'administrative.land_parcel', elementType: 'labels', stylers: [{visibility: 'off'}]},
      //       {featureType: 'administrative.neighborhood', elementType: 'labels', stylers: [{visibility: 'off'}]},
      //       {featureType: 'poi', elementType: 'labels', stylers: [{visibility: 'off'}]},
      //       {featureType: 'poi.park', elementType: 'geometry', stylers: [{color: '#181818'}]},
      //       {featureType: 'poi.park', elementType: 'labels.text.fill', stylers: [{color: '#616161'}]},
      //       {featureType: 'road', elementType: 'geometry', stylers: [{color: '#2c2c2c'}]},
      //       {featureType: 'road.arterial', elementType: 'labels.text.fill', stylers: [{color: '#ffffff'}]},
      //       {featureType: 'road.highway', elementType: 'geometry', stylers: [{color: '#3e3e3e'}]},
      //       {featureType: 'road.highway', elementType: 'labels.text.fill', stylers: [{color: '#f8f8f8'}]},
      //       {featureType: 'transit', elementType: 'labels.text.fill', stylers: [{color: '#757575'}]},
      //       {featureType: 'water', elementType: 'geometry', stylers: [{color: '#000000'}]},
      //       {featureType: 'water', elementType: 'labels.text.fill', stylers: [{color: '#3d3d3d'}]}
      //   ]
       });

       loadGroundOverlay(svgUrl);
       colorizeLots();
       

       // google.maps.event.addListener(polygon, 'click', function(event) {
       //     alert('Polygon clicked!');
       // });  

       // document.getElementById('L3-001A').addEventListener('click', function() {
       //     window.location.href = 'https://www.example.com';
       // });
 
       $("#btn-reset").click(function(){
          map.setCenter(ROSELAWN);
          map.setZoom(DEFAULT_ZOOM);
          map.setTilt(DEFAULT_TILT);
          map.setHeading(DEFAULT_HEADING);
          $("#nav-categorycode").val('< All >').trigger('change');
          deleteMarkers();
       });
       
       // Rotate and Tilt --------------------
       $("#btn-rotateleft").click(function(){
          map.setHeading(map.getHeading() +20);
       });      
 
       $("#btn-rotateright").click(function(){
          map.setHeading(map.getHeading() -20);
       });       
 
       $("#btn-tiltdown").click(function(){
          map.setTilt(map.getTilt() +20);
       });      
 
       $("#btn-tiltup").click(function(){
          map.setTilt(map.getTilt() -20);
       }); 
       // ------------------------------------          
 
       $("#btn-restore-overlay").click(function(){
          restoreOverlay();
       });  
       
       $("#btn-remove-overlay").click(function(){
          removeOverlay();
       });  
 
       // get Latitude and Longitude value
       // map.addListener("rightclick", (mapsMouseEvent) => {
       //    alert(mapsMouseEvent.latLng);
       // });  
 
       $(".salesTransactionTable tbody").on("click", "button.btnSale", function(){
           $("#modal-search-sales").modal("hide");
           var latitude = Number($(this).attr("latitude"));
           var longitude = Number($(this).attr("longitude"));
           var lotid = $(this).attr("lotid");
           var location = { lat: latitude, lng: longitude };
           map.setCenter(location);
           map.setZoom(22);
           deleteMarkers();
           addMarker(location,lotid);
       }); 
       
       // Select Lot category from navigational bar
       $('#nav-categorycode').on("change", function(){
           let categorycode = $("#nav-categorycode").val();
           switch(categorycode) {
              case '0001':         // Lawn 3
                 map.setCenter({lat:10.71044481557827, lng:122.96918917601589});
                 map.setZoom(20);
               //   document.getElementById('map').style.display = 'none';
               //   document.getElementById('map').remove();
               //   svgContainer.classList.add('hidden');
                 break;
              case '0008':         // Lawn 2
                 map.setCenter({lat:10.70920386236257, lng:122.96882348866149});
                 map.setZoom(20);
                 break;   
              default:
                 window.location = 'home';
                 // $("#btn-reset").click();
                 // map.setCenter(ROSELAWN);
                 // map.setZoom(DEFAULT_ZOOM);  
                 // deleteMarkers(); 
           }  

           map.setTilt(DEFAULT_TILT);
           map.setHeading(DEFAULT_HEADING);

           // var data = new FormData();
           // data.append("categorycode", categorycode);
     
           // $.ajax({
           //       url:"ajax/lot_category_list.ajax.php",   
           //       method: "POST",                
           //       data: data,                    
           //       cache: false,                  
           //       contentType: false,            
           //       processData: false,   
           //       async: false,         
           //       dataType:"json",               
           //       success:function(answer){
           //          fetch(svgUrl)
           //            .then(response => response.text())
           //            .then(svgText => {
           //                // Modify the SVG content to change the fill color
           //                let parser = new DOMParser();
           //                let svgDoc = parser.parseFromString(svgText, "image/svg+xml");

           //                for(var i = 0; i < answer.length; i++) {  
           //                   percent = Math.round(i/answer.length*100);
           //                   var options = {
           //                       text: percent + "% complete."
           //                   };   

           //                   let lc = answer[i];
           //                   let lotid = lc.lotid;
           //                   let lotstatus = lc.lotstatus;
           //                   const polygon = svgDoc.getElementById(lotid);
           //                   if (polygon){
           //                      switch(lotstatus){
           //                         case lotstatus = 'Sold':
           //                            polygon.setAttribute("fill", 'palegreen');
           //                            break;
           //                         case lotstatus = 'Cancelled':
           //                            polygon.setAttribute("fill", 'deeppink');
           //                            break;        
           //                         default:     
           //                            polygon.setAttribute("fill", 'azure');                             
           //                      }
           //                      // polygon.setAttribute("fill", 'lemonchiffon');
           //                      // polygon.addEventListener('click', function() {
           //                      //        alert('polygon');
           //                      //    });
           //                   }
           //                }

           //                notice.update(options);
           //                notice.remove();

           //                // Convert the SVG back to a data URL
           //                let serializer = new XMLSerializer();
           //                let newSvgText = serializer.serializeToString(svgDoc);
           //                let newSvgUrl = "data:image/svg+xml;base64," + btoa(newSvgText);

           //                loadGroundOverlay(newSvgUrl);
           //             });
           //       },
           //       beforeSend: function() {
           //       },  
           //       complete: function() {
           //       }, 
           // }) 
       });
       
       $("#btn-changecolor").click(function(){
           changeFillColor('lightyellow');
       });

       // document.querySelectorAll('polygon').forEach(function(polygon) {
       //     alert("mom");
       //     polygon.addEventListener('click', lotClickHandler);
       // });
   } // end async  

   function colorizeLots(){
     var percent = 0;
     var notice = new PNotify({
        text: "Codifying map..",
        addclass: 'stack-left-right bg-primary border-primary',
        type: 'info',
        icon: 'icon-spinner4 spinner',
        hide: false,
        buttons: {
           closer: false,
           sticker: false
        },
        opacity: .9,
        width: "190px"
     });

     $.ajax({
        url:"ajax/lot_all_list.ajax.php",   
        method: "POST",                                    
        cache: false,                  
        contentType: false,            
        processData: false,   
        async: false,         
        dataType:"json",               
        success:function(answer){
           fetch(svgUrl)
             .then(response => response.text())
             .then(svgText => {
                 // Modify the SVG content to change the fill color
                 let parser = new DOMParser();
                 let svgDoc = parser.parseFromString(svgText, "image/svg+xml");

                 for(var i = 0; i < answer.length; i++) {  
                    percent = Math.round(i/answer.length*100);
                    var options = {
                        text: percent + "% complete."
                    };   

                    let lc = answer[i];
                    let lotid = lc.lotid;
                    let lotstatus = lc.lotstatus;
                    const polygon = svgDoc.getElementById(lotid);
                    if (polygon){
                       switch(lotstatus){
                          case lotstatus = 'Sold':
                             polygon.setAttribute("fill", 'palegreen');
                             break;
                          case lotstatus = 'Cancelled':
                             polygon.setAttribute("fill", 'deeppink');
                             break;        
                          default:     
                             polygon.setAttribute("fill", 'azure');                             
                       }
                    }
                 }

                 notice.update(options);
                 notice.remove();

                 // Convert the SVG back to a data URL
                 let serializer = new XMLSerializer();
                 let newSvgText = serializer.serializeToString(svgDoc);
                 let newSvgUrl = "data:image/svg+xml;base64," + btoa(newSvgText);

                 loadGroundOverlay(newSvgUrl);
              });
        },
        beforeSend: function() {
        },  
        complete: function() {
        }, 
     })      
   }
   
   function loadGroundOverlay(url){
     const imageBounds = {
        north: NORTH_BOUND,
        south: SOUTH_BOUND,
        east: EAST_BOUND,
        west: WEST_BOUND,
     };

     const mapGroundOverlay = new google.maps.GroundOverlay(
        url,
        imageBounds
     );
     
     if (memorial_overlay != null){
        memorial_overlay.setMap(null);
     }

     mapGroundOverlay.setMap(map);
     memorial_overlay = mapGroundOverlay;

     google.maps.event.addListener(memorial_overlay,'rightclick',getCoordinates);
   }

   function restoreOverlay() {
       memorial_overlay.setMap(map);
   }
      
   function removeOverlay() {
       memorial_overlay.setMap(null);
   }

   // change Overlay image with another SVG
   function changeOverlay(){
      if (memorial_overlay) {
        memorial_overlay.setMap(null); // Remove the old overlay
      }

      const imageBounds = {
        north: 10.712408011651695,
        south: 10.706009446450304,
        east: 122.97356933277679,
        west: 122.9671846920831,
      };

      memorial_overlay = new google.maps.GroundOverlay(
        "views/global_assets/images/roselawnmap_edited_complete.svg",
        imageBounds,
      );  
      
      memorial_overlay.setMap(map);
   }

   function getCoordinates(event){
      alert("mom");
      alert(event.latLng);
      // alert(memorial_overlay.getBounds());
   }

   // Adds a marker to the map and push to the array.
   function addMarker(position,info) {
      const marker = new google.maps.Marker({
        position,
        map,
        title: info,
        animation: google.maps.Animation.DROP,
      });
      markers.push(marker);
      // Add a click listener for each marker, and set up the info window.
      marker.addListener("click", ({ domEvent, position }) => {
        const { target } = domEvent;
        alert("Lot info here...");
      });
   }

   // Sets the map on all markers in the array.
   function setMapOnAll(map) {
      for (let i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
      }
   }

   // Removes the markers from the map, but keeps them in the array.
   function hideMarkers() {
      setMapOnAll(null);
   }

   // Shows any markers currently in the array.
   function showMarkers() {
      setMapOnAll(map);
   }

   // Deletes all markers in the array by removing references to them.
   function deleteMarkers() {
      hideMarkers();
      markers = [];
   }

   function changeFillColor(newColor) {
       const svgUrl = "views/global_assets/images/roselawnmap_edited_complete.svg"; // Replace with your SVG file URL
       fetch(svgUrl)
         .then(response => response.text())
         .then(svgText => {
           // Modify the SVG content to change the fill color
           const parser = new DOMParser();
           const svgDoc = parser.parseFromString(svgText, "image/svg+xml");

           const polygon = svgDoc.getElementById("L3-010A");
           polygon.setAttribute("fill", newColor);

           const svg_Element = svgDoc.querySelectorAll('polygon');

           // svg_Element.addEventListener('click', () => {
           //     alert('Polygon clicked!');
           // });

           // svg_Element.forEach(polygons => {
           //    polygons.setAttribute("fill", "gold");
           //    //  alert(polygon.outerHTML);
           // });

           // display ID name of the polygon
           // alert(polygon.id);
     
           // Convert the SVG back to a data URL
           const serializer = new XMLSerializer();
           const newSvgText = serializer.serializeToString(svgDoc);
           const newSvgUrl = "data:image/svg+xml;base64," + btoa(newSvgText);
     
           // // Update the ground overlay with the new SVG
           const imageBounds = {
               north: 10.712408011651695,
               south: 10.706009446450304,
               east: 122.97356933277679,
               west: 122.9671846920831,
           };
     
           const newGroundOverlay = new google.maps.GroundOverlay(
             newSvgUrl,
             imageBounds
           );
     
           // Remove the old overlay and set the new one
           memorial_overlay.setMap(null);
           newGroundOverlay.setMap(map);
           memorial_overlay = newGroundOverlay; // Update the reference

           google.maps.event.addListener(memorial_overlay,'rightclick',getCoordinates); 
         });
   } 

   // function lotClickHandler(event) {
   //     var polygonId = event.target.id;
   //     alert("Polygon clicked: " + polygonId);
   // }

   // const svg_Url = "views/global_assets/images/roselawnmap_edited_complete.svg";
   // fetch(svg_Url)
   //     .then(response => response.text())
   //     .then(svgString => {
   //         const polygon_parser = new DOMParser();
   //         const svg_Doc = polygon_parser.parseFromString(svgString, 'image/svg+xml');
   //         const svg_Element = svg_Doc.querySelectorAll('polygon');
   //         // alert(svg_Element.length);

   //         // Display content of each polygon
   //         // svg_Element.forEach(polygon => {
   //         //     alert(polygon.outerHTML);
   //         // });

   //         document.body.appendChild(svg_Element);

   //         svg_Element.addEventListener('click', () => {
   //             alert('Polygon clicked!');
   //         });
   //     });

   initMap();
});
