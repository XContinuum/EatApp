var default_light="AvenirLTStd-Light";
var offset=0;

$(document).ready(
    function()
    {
      sendRequest($("#search_box").val());
  		//When pressing keys in the searching bar
  		$("#search_box").keyup(function (e)
    	{
    		//Search+++
    		if(e.keyCode==13)
   			{
          offset=0;
          sendRequest($("#search_box").val());
        }
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
      var obj=$.parseJSON(xmlhttp.responseText);

      $("#query_results").html(obj[0]["output"]);

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


      var pages=Math.ceil(obj[0]["pages"]/5);

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
      pages_html="<div class='page_block' id='left_p'>&#60;</div>"+pages_html+"<div class='page_block' id='right_p'>&#62;</div>";


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


  xmlhttp.open("POST","retrieve_search.php",true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("search_query="+query+"&offset="+offset);
}
