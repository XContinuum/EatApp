var default_light="AvenirLTStd-Light";
var offset=0;

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
          console.log(component);

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
    console.log('Street: ' + street);
    console.log('Street number: ' + street_number);
    console.log('Postal code: ' + postal_code);
    console.log(results[0].formatted_address);

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
        //else
          //document.getElementById("error").innerHTML += "Your browser doesn't support the Geolocation API";
      }


$(document).ready(
    function()
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

   }
);


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

function styleOutput(response,query)
{
   var obj=$.parseJSON(response);

   if (obj[0]["results"]==0)
   {
      $("#query_results").html("<div style='width:100%;text-align:center;'>No results were found</div>");
      $("#pages").html("");
   }
   else
   {
      //Style the JSON output+++
      var output_html="";

      for (var i=0;i<obj[0]["data"].length;i++)
      {
          var current=obj[0]["data"][i];
          var dist=parseFloat(current["distance"]);

          if (current["distance"]!="")
          {
          if (dist>10)
          {
            dist=dist.toFixed(1)+" km";
          }
          else
          if (dist>1)
          {
            dist=dist.toFixed(2)+" km";
          }
          else
            if (dist<1)
            {
              dist=dist*1000;
              dist=dist.toFixed(0)+" m";
            }
          }
          else
              {
                dist="";
              }


            output_html+="<div class='item_box'><table><tr>";
            output_html+="<td class='img_td'><img class='item_img' src='"+current["image"]+"' /></td>";

            output_html+="<td valign='top'>"+current["product_name"]+"<br /><a href='/"+current["username"]+"' target='_blank'>"+current["restaurant_name"]+"</a>";
            output_html+="<br /><br />"+current["price"]+"$</td>";

            output_html+="<td valign='center' align='right' width='60px' style='color:#4d85f2;'>"+dist+"</td>";
            output_html+="</tr></table></div>";
      }
      //---


      $("#query_results").html(output_html);

      $(".item_box").css({
          "font-family":default_light,
          "background-color":"white",
          "padding":"5px",
          "box-shadow": "0px 0px 3px #ccc",
          "margin-bottom":"5px"
        });

      $(".item_box").find("a").css("text-decoration","none");
      $(".item_box").find("table").css({
        "border-collapse":"collapse",
        "text-shadow" :"0px 0px 3px rgba(0, 0, 0, 0.20)",
        "width" : "100%"
      }).find("td").css("border","0px solid black");

      $(".img_td").css("padding-right","15px").width("60px");

      $(".item_img").css({
          "width":"60px",
          "height":"60px",
          "border":"0px solid black",
          "margin":"5px",
          "border-radius":"50%",
          "box-shadow": "0px 0px 2px rgba(0, 0, 0, 0.35)"
        });


      var pages=Math.ceil(obj[0]["results"]/5);

      var pages_html="";
      for (var i=0;i<pages;i++)
      {
        pages_html+="<div class='page_block'>";

        if (i==offset)
          pages_html+="<b>"+(i+1)+"</b>";
        else
          pages_html+=(i+1);

        pages_html+="</div>";
      }
      pages_html="<div class='page_block' id='left_p' style='padding-top:4px;padding-bottom:2px;'><img src='/images/arrow_left.png' align='center'/></div>"+pages_html;
      pages_html+="<div class='page_block' id='right_p' style='padding-top:4px;padding-bottom:2px;'><img src='/images/arrow_right.png' align='center'/></div>";


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

                  //console.log($(this).html());
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
