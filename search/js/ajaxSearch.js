var default_light="AvenirLTStd-Light";
var offset=0;

var source_file=readTextFile("js/search_struct.html");
source_file=source_file.split("##");
var template=source_file[0];
var page_structure=source_file[1];

function readTextFile(file)
{
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", file, false);
    xhttp.send();

    return xhttp.responseText;
}

function writeAddressName(latLng)
{
  var geocoder = new google.maps.Geocoder();
  geocoder.geocode({
  "location": latLng
  },

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
    var userLatLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
    //Write the formatted address
    writeAddressName(userLatLng);
}

function geolocateUser()
{
    //If the browser supports the Geolocation API
    if (navigator.geolocation)
    {
      var positionOptions =
      {
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


$(document).ready(function()
{
  geolocateUser();
  sendRequest($("#search_box").val());

  $("#search_box").setHintBox(function(obj)
  {
    offset=0;
    sendRequest($("#search_box").val());
    $("#livesearch").hide();
  });

  //Filters
  $("#filters").click(function()
  {
    $("#pages").hide();
    $("#query_results").hide();
    $("#filter_settings").show();
  });

  //Back to search
  $("#back").click(function()
  {
    $("#pages").show();
    $("#query_results").show();
    $("#filter_settings").hide();
  });
});


function sendRequest(query)
{
  if (window.XMLHttpRequest)
  {
    //code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  }
  else
  {
    //code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      //console.log(xmlhttp.responseText)
      styleOutput(xmlhttp.responseText, query);
    }
  }


  xmlhttp.open("POST",document.location.origin+"/search/js/retrieve_search.php",true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("search_query="+query+"&offset="+offset+"&address="+$("#user_address").val());
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
   //console.log(response);
   var obj=$.parseJSON(response);

   if (obj["results"]==0)
   {
      $("#query_results").html("<div style='width:100%;text-align:center;'>No results were found</div>");
      $("#pages").html("");
   }
   else
   {
      //Style the JSON output+++
      var output_html="";
      var search=['%src%','%product_name%','%link%','%restaurant_name%','%price%','%distance%'];

     for (var i=0;i<obj["data"].length;i++)
      {
          var current=obj["data"][i];
          var distance=adjustUnits(current["distance"]);

          var replace=[current["image"],current["product_name"],current["link"],current["restaurant_name"],current["price"],distance];
          output_html+=multipleReplace(search,replace,template);
      }
      //---

      $("#query_results").html(output_html);
      $(".item_box").find("a").css({"text-decoration":"none","color":"#4d85f2"});

      //Load Pages
      var pages=Math.ceil(obj["results"]/5); //5 results per pages

      var arr=[];

      for (var i=0;i<pages;i++)
      {
        if (i==offset)
           arr.push("<b>"+(i+1)+"</b>");
        else
           arr.push(i+1);
      }

      var pages_html="<div class='page_block'>"+arr.join("</div><div class='page_block'>")+"</div>";
      pages_html=page_structure.replace("%pages_html%",pages_html);

      if (pages>1)
      {
        $("#pages").html(pages_html);

        $(".page_block").click(function()
          {
               if($(this).attr("id")!="left_p" && $(this).attr("id")!="right_p")
               {
                  var int_off=parseInt($(this).html());
                  offset=int_off-1;
                  sendRequest(query);
               }
               else
                if ($(this).attr("id")=="left_p")
                {
                  if (offset>0)
                  {
                    offset--;
                    sendRequest(query);
                  }
                }
                else
                if ($(this).attr("id")=="right_p")
                {
                  if (offset<pages-1)
                  {
                    offset++;
                    sendRequest(query);
                  }
                }
          });
      }
      else
      {
        $("#pages").html("");
      }
    }
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
