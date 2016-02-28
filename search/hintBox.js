(function($)
{
  var shift=false;
  var inc_shift=0;

  function sendRequest(query_,inputbox)
  {
    var data={search_query : query_};

    $.post(document.location.origin+"/search/hintParser.php",data,function(data)
    {
      var obj=$.parseJSON(data);

        if (obj.length>0)
        {
            showOutput(obj, inputbox);
        }
        else
        {
            $("#livesearch").hide();
        }
    });

  }

  function showOutput(response,inputbox)
  {
    //Parse through the json+++
    var list_code="";

    for (var i=0;i<response.length;i++)
    {
        list_code+="<div class='dropdown'>";
        list_code+=response[i]["icon"];
        list_code+=" <span class='cleanDropdown'>";
        list_code+=response[i]["item"];
        list_code+="</span></div>";
    }
    //Parse through the json---


    $("#livesearch").html(list_code);
    $("#livesearch").show();


    $("#livesearch").height("128px");//Reset hint box size


    //Resize livesearch box if items are less than 4
    if ($(".dropdown").length<=4)
    {
      $("#livesearch").height(($(".dropdown").length)*32);
    }

    //Highlight background if mouse on dropdown item in hint
    $(".dropdown").mouseover(function(e)
    {
      $(".dropdown").css("background-color","white");
      $(this).css("background-color","#4d85f2");
    });

    //Unselect if mouse away
    $(".dropdown").mouseout(function(e)
    {
      $(".dropdown").css("background-color","white");
    });

    $(".dropdown").click(function()
    {
      $(inputbox).val($(this).find(".cleanDropdown").html());
      $("#livesearch").hide();
    });

  }

  function setUp(inputbox)
  {
    var pos=$(inputbox).position();
    var left=pos.left;

    if (shift==true)
    {
      left+=inc_shift;
    }

    $("<div id='livesearch'></div>").insertAfter($(inputbox));

    $("#livesearch").css(
    {
      "position":"absolute",
      top:pos.top+$(inputbox).height()+($(inputbox).css("padding-top"))+1,
      left:left,
      width:$(inputbox).width()+15+1+2,
      height:"128px",
      "box-shadow":"0px 0px 3px #ccc",
      padding:"0px",
      margin:"0px",
      "text-align":"left",
      "background-color":"white",
      "color":"black",
      "overflow":"auto",
      "z-index":"20"
    });

    $("#livesearch").hide();

    var inp=$(inputbox);

    $(document).mouseup(function (e)
    {
      var container=$("#livesearch");

      if ((!container.is(e.target) && container.has(e.target).length === 0) && (!inp.is(e.target) && inp.has(e.target).length === 0))
      {
        container.hide();
      }
    });
  }

$.fn.shiftBox=function(inc)
{
  shift=true;
  inc_shift=inc;
}

$.fn.setHintBox=function(onEnter)
{
    return this.each(function()
    {
      setUp(this);
  	  var current_item=-1; //When pressing keys in the searching bar

      /*
          Typing on different keys result in different events:

            13 - enter - set by paramater
            40 - arrow down - go down the hint list
            38 - arrow up - go up the hint list
            Any key code - hint search
      */
      $(this).keyup(function(e)
      {
         switch(e.keyCode)
   		   {
              case 13: //Enter
                  onEnter(this);
              break;



              case 40: //Arrow down
                if ($("#livesearch").is(":visible"))
                {

      	           if ($(".dropdown").length>current_item+1)
   		 	             current_item++;

   			            $(".dropdown").css("background-color","white");
      	           $(".dropdown").eq(current_item).css("background-color","#4d85f2"); //Set selection color
      	           $("#search_box").val($(".dropdown").eq(current_item).find(".cleanDropdown").html()); //Set the search value with current selection
    	         }
             break;



    	       case 38: //Arrow up
              if ($("#livesearch").is(":visible"))
                {
      		        if (current_item>0)
   				         current_item--;

    			         $(".dropdown").css("background-color","white");
				          $(".dropdown").eq(current_item).css("background-color","#4d85f2"); //Set selection color
  				        $(this).val($(".dropdown").eq(current_item).find(".cleanDropdown").html()); //Set the search value with current selection
                }
            break;



            default: //Normally typing & showing hints in box
      	     current_item=-1;

    		      if ($(this).val().length==0)
              {
    			       $("#livesearch").html("");
    			       $("#livesearch").hide();
    			       return;
  			     }
 			        else
 			            sendRequest($(this).val(),this); //send request to parse for hints
		        break;
          }

      });


  });
}


}(jQuery));
