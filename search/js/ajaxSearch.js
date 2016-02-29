/* Set global variables */
var offset=0;
var source_file=readTextFile("js/search_struct.html").split("##");
var template=source_file[0];
var no_result=source_file[1];

$(document).ready(function()
{
  /* Set waiting logo rotation */
  var rotation = function()
  {
    $("#wait").rotate({
    angle:0,
    animateTo:360,
    callback: rotation});
  }
  rotation();

  /* Load cookies into fields*/
  $("#user_address").val((getCookie("location")=="")?"":getCookie("location"));
  $("#max_radius").val((getCookie("max_radius")=="")?"10.0":getCookie("max_radius"));
  $("#min_radius").val((getCookie("min_radius")=="")?"0.00":getCookie("min_radius"));
  $("#max_price").val((getCookie("max_price")=="")?"$20.00":getCookie("max_price"));
  $("#min_price").val((getCookie("min_price")=="")?"$0.00":getCookie("min_price"));

  sendRequest($("#search_box_query").val());
  geolocateUser();

  /*
    setHindBox on searchBox
    and add event on enter key
  */

  $("#search_box_query").shiftBox(-20);

  $("#search_box_query").setHintBox(function(obj)
  {
    var query=$("#search_box_query").val().replace(/ /g,'');

    if (query.length>0)
    {
      offset=0;
      sendRequest($("#search_box_query").val());
      $("#livesearch").hide();
    }
  });

  /* Open search parameters on click */
  $("#filters").click(function()
  {
    $("#pages").hide();
    $("#query_results").hide();
    $("#filter_settings").show();
  });

  /* Back to search */
  $("#back").click(function()
  {
    $("#pages").show();
    $("#query_results").show();
    $("#filter_settings").hide();
  });

  /* Save cookies */
  $("#save_settings").click(function()
  {
      var storageTime=365;
      setCookie("location",$("#user_address").val(),storageTime);
      setCookie("max_radius",$("#max_radius").val(),storageTime);
      setCookie("min_radius",$("#min_radius").val(),storageTime);
      setCookie("max_price",$("#max_price").val(),storageTime);
      setCookie("min_price",$("#min_price").val(),storageTime);
      setCookie("currency",$("#currency option:selected").text(),storageTime);
  });

});
/*

  ONLOAD EVENT+++++++++

*/

function setCookie(cname, cvalue, exdays)
{
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname)
{
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}

function readTextFile(file)
{
    var xhttp=new XMLHttpRequest();
    xhttp.open("GET", file, false);
    xhttp.send();

    return xhttp.responseText;
}

function writeAddressName(latLng)
{
  var geocoder = new google.maps.Geocoder();
  geocoder.geocode({"location": latLng},
  function(results, status)
  {
    if (status == google.maps.GeocoderStatus.OK)
    {
      var components = results[0].address_components;
      var street_number = null;
      var street = null;
      var postal_code=null;

      for (var i = 0, component; component = components[i]; i++)
      {
          //console.log(component);
          switch (component.types[0])
          {
            case "street_number":
              street_number = component['long_name'];
            break;

            case "route":
              street = component['long_name'];
            break;

            case "postal_code":
              postal_code = component['long_name'];
            break;
          }
      }
    //console.log('Street: ' + street);
    //console.log('Street number: ' + street_number);
    //console.log('Postal code: ' + postal_code);
    //console.log(results[0].formatted_address);

      var show_address="";

      if (postal_code!=null)
      {
        show_address=postal_code;
      }
      else
        if (street_number!=null && street!=null)
        {
          show_address=street_number+" "+street;
        }


      $("#user_address").val(show_address);
    }
    else
      {
        $("#error").val("Unable to retrieve your address" + "<br />");
      }
    });
}

function geolocationError(positionError)
{
  $("#error").html("Error: " + positionError.message + "<br />");
}


function geolocationSuccess(position)
{
    var userLatLng=new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
    //Write the formatted address
    writeAddressName(userLatLng);
}

function geolocateUser()
{
    //If the browser supports the Geolocation API
    if (navigator.geolocation)
    {
      var positionOptions={
        enableHighAccuracy: true,
        timeout: 10 * 1000 // 10 seconds
      };
      navigator.geolocation.getCurrentPosition(geolocationSuccess, geolocationError, positionOptions);
    }
    else
    {
      alert("Your browser doesn't support the Geolocation API");
    }
}


  /*
  *
  SEARCH REQUEST
  *
  */

function sendRequest(query)
{
  $("#output").css("opacity","0.5");
  $("#loading_display").show();

  var data={
    search_query:query,
    pageOffset:offset,
    address:$("#user_address").val(),
    maxRadius:$("#max_radius").val(),
    minRadius:$("#min_radius").val(),
    maxPrice:$("#max_price").val(),
    minPrice:$("#min_price").val(),
    currency:$("#currency option:selected").text(),
    device: "Website"
  };

  $.post("js/retrieve_search.php",data,function(data)
  {
    styleOutput(data,query);
  });
}


function adjustUnits(distance)
{
  var result="";
  var temp_d=parseFloat(distance);


    if (distance!="")
    {
      if (temp_d>10)
      {
        result=temp_d.toFixed(1)+" km";
      }
      else
        if (temp_d>1)
        {
          result=temp_d.toFixed(2)+" km";
        }
        else
          if (temp_d<1)
          {
            result=temp_d*1000;
            result=temp_d.toFixed(0)+" m";
          }
    }

    return result;
}

function styleOutput(response,query)
{
   var obj=$.parseJSON(response);

   $("#output").css("opacity","1");
   $("#loading_display").hide();

   if (obj["results"]==0)
   {
      $("#output").html(no_result);
   }
   else
   {
      /* Style the JSON output */
      var output_html="";
      var search=['%src%','%product_name%','%link%','%restaurant_name%','%price%','%distance%'];

      for (var i=0;i<obj["data"].length;i++)
      {
          var current=obj["data"][i];
          var distance=adjustUnits(current["distance"]);

          var replace=[current["image"],current["product_name"],current["link"],current["restaurant_name"],current["price"],distance];
          output_html+=multipleReplace(search,replace,template);
      }

      var numberOfPages=Math.ceil(obj["results"]/5);

      if (numberOfPages>offset+1) /* Add load more only if more results are available */
      output_html+="<div id='load_more' style='color:#C8C8CD;'>Load more</div>";


      if(offset==0)
      {
        $("#output").html(output_html);
      }
      else
      {
        $("#load_more").remove();
        $("#output").html($("#output").html()+output_html);
      }

      loadMore(query);
    }
}

function loadMore(query)
{
  $("#load_more").click(function()
  {
      offset++;
      sendRequest(query);
  });
}


function multipleReplace(search, replace, string)
{
    var result=string;

    for(var i=0;i<search.length;i++)
    {
        result=result.replace(search[i],replace[i]);
    }

    return result;
}
