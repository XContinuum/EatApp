var geocoder=new google.maps.Geocoder();
var map;

function initialize()
{
    var mapCanvas=document.getElementById('map');
    var mapOptions={
      center:new google.maps.LatLng(44.5403, -78.5463),
      zoom:6,
      mapTypeId:google.maps.MapTypeId.ROADMAP
    }

    map = new google.maps.Map(mapCanvas,mapOptions);
    codeAddress();
}

google.maps.event.addDomListener(window,'load',initialize);

function codeAddress()
{
  //In this case it gets the address from an element on the page, but obviously you  could just pass it to the method instead
  var address = "Ottawa, ON";
  geocoder.geocode({'address': address},
    function(results, status)
    {
      if (status == google.maps.GeocoderStatus.OK)
      {
        //In this case it creates a marker, but you can get the lat and lng from the location.LatLng
        map.setCenter(results[0].geometry.location);
        var marker = new google.maps.Marker({map: map,position: results[0].geometry.location});
      }
      else
      {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });

  map.setZoom(11);
}


function highlightMenu(object)
{
  unHighlightTheRest();
  object.className="highlighted";
}

function unHighlightTheRest()
{
  document.getElementById("side_bar_home").className="unhighlighted";
  document.getElementById("side_bar_login").className="unhighlighted";
  document.getElementById("side_bar_sign_up").className="unhighlighted";
}
