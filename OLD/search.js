$(document).ready(
    function()
    {
    	var pos=$("#search_box").position();

        $("<div id='livesearch'></div>").css(
        {
            position: 'absolute',
            top: pos.top+$("#search_box").height()+($("#search_box").css("padding-top"))+1,
            left: pos.left,
            width: $("#search_box").width()+16,
            height: '128px',
            border:'1px solid #ced1d7',
            padding:"0px",
            margin:"0px",
            "text-align":"left",
            'background-color':'white',
            "overflow":"auto"
        }).insertAfter($("#search_box"));

        $("#livesearch").hide();


  			$(document).mouseup(function (e)
            {
                var container = $("#livesearch");
                var inp = $("#search_box");

                if ((!container.is(e.target) && container.has(e.target).length === 0) && (!inp.is(e.target) && inp.has(e.target).length === 0))
                {
                	container.hide();
                }
            });




  		//When pressing keys in the searching bar
  		var current_item=-1;

        $("#search_box").keyup(function (e)
    	{
    		//Search+++
    		if(e.keyCode==13 && $(this).val().length>0)
   			{
        		//window.location.replace("/search/index.php?q="+$(this).val());
        		window.location.href = "/search/index.php?q="+$(this).val();
    		}

    		if(e.keyCode==40) //Down
   			{
				if ($(".dropdown").length>current_item+1)
   				{
        			current_item++;
        		}

   				$(".dropdown").css("background-color","white");

   				//Set selection color
   				$(".dropdown").eq(current_item).css("background-color","#4d85f2");

   				//Set the search value with current selection
				$("#search_box").val($(".dropdown").eq(current_item).find(".cleanDropdown").html());
    		}
    		else
    		if(e.keyCode==38) //Up
    		{
    			if (current_item>0)
   				{
        			current_item--;
				}

    			$(".dropdown").css("background-color","white");

				//Set selection color
   				$(".dropdown").eq(current_item).css("background-color","#4d85f2");

   				//Set the search value with current selection
				$("#search_box").val($(".dropdown").eq(current_item).find(".cleanDropdown").html());

        	}
    		else
    		{
    		current_item=-1;

    		//Search---
        	if ($(this).val().length==0)
        	{
    			$("#livesearch").html("");
    			$("#livesearch").hide();
    			return;
  			}

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
    				if (xmlhttp.responseText.length>0)
    				{
    					$("#livesearch").html(xmlhttp.responseText);
      					$("#livesearch").show();

      					$(".dropdown").css({
      						"font":"AvenirLTStd-Light",
      						"box-sizing":"border-box",
      						"border":"0px solid black",
      						"height":"32px",
      						"padding":"5px",
      					 	"font-size":"16px"
      					});


      					$("#livesearch").height("128px");//Reset hint box size


      					//Resize livesearch box if items are less than 4
      					if ($(".dropdown").length<=4)
    					{
    						$("#livesearch").height(($(".dropdown").length)*32);
    					}

    					//Highloght background if mouse on dropdown item in hint
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
    							$("#search_box").val($(this).find(".cleanDropdown").html());
    							$("#livesearch").hide();
    						});

      				}
      				else
      				{
      					$("#livesearch").hide();
      				}
    			}
  			}


  			xmlhttp.open("POST","search/search.php",true);
  			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send("search_query="+$(this).val());
		}
        });

    }
);
