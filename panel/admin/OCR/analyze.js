$(document).ready(function()
{
  setButtonEvents();

  /*
    Upload image to the server for analysis
  */
  $("#imgInp").change(function()
  {
    readURL(this);
  });

  /*
    Export menu as XML
  */
  $("#export").click(function()
  {
    var prices=[];
    var products=[];

    $(".price").each(function(index)
    {
      prices.push($(this).text());
    });

    $(".product").each(function(index)
    {
      products.push($(this).text());
    });

    var parameters={arrPrices : prices, arrProducts : products};

    var options={
      url:"createXML.php",
      type: "POST",
      data: parameters,
      success:function(data)
      {
        $("#download_frame").attr("src","download_xml.php?name=restaurant_menu");
      }};

    $.ajax(options);
  });


});
/* 

  ONLOAD EVENT+++++++++

*/
function readURL(input) 
{
  if (input.files && input.files[0]) 
  {
    var reader = new FileReader();

    reader.onload = function (e) 
    {
      $('#blah').attr('src', e.target.result);
      $("#process").show();
      processImage();
    }

    reader.readAsDataURL(input.files[0]);
  }
}

/* 
  Send image to server for OCR Processing
*/
function processImage()
{
	 var datastring=new FormData($("#images_file")[0]);

   var parameters={
    type: "POST",
    url: "analyze.php",
    data: datastring,
    dataType: "text",
    async:false,
    success: function(data)
    {
      var obj=data.split("##");

      $("#results").html(obj[0]);
      $("#raw").html(obj[1]);
      $("#process").hide();
      setEditFields();
    },
    
    error: function()
    {
      alert('An error has occured. Please try again.');
    },
    
    cache:false,
    contentType:false,
    processData:false
    };

  $.ajax(parameters);
}

/*
  SET EDIT INPUT
  ~Changes product names and prices~
*/
var glob_index=-1;
function setEditFields()
{
  /*
    Event on clicking into product name cell or price cell
  */
	$(".edit").click(function()
	{
		if (glob_index!=$(this).index(".edit"))
		{
			glob_index=$(this).index(".edit");
			var value=$(this).text();
			$(this).html("<input value='"+value+"' type='text' id='change_edit' autocomplete='off'/>");
      $("#change_edit").select();
      moveKeys();
		}
  });

  /*
    Add up and down events for moving througt the menu
  */
  function moveKeys()
  {
    $("#change_edit").keyup(function (e)
    {
      switch(e.keyCode)
      {
        case 40:
        if (glob_index+2<$(".edit").length)
        {
          $(".edit").eq(glob_index).html($("#change_edit").val());
          $("#change_edit").remove();
          
          glob_index+=2;
          var value=$(".edit").eq(glob_index).text();
          

          $(".edit").eq(glob_index).html("<input value='"+value+"' type='text' id='change_edit' autocomplete='off'/>");
          $("#change_edit").select();
          moveKeys();
        }
        break;

        case 38: //Arrow up
          if (glob_index-2>=0 && $(".edit").length>0)
          {
            $(".edit").eq(glob_index).html($("#change_edit").val());
            $("#change_edit").remove();
          
            glob_index-=2;
            var value=$(".edit").eq(glob_index).text();
          
            $(".edit").eq(glob_index).html("<input value='"+value+"' type='text' id='change_edit' autocomplete='off'/>");
            $("#change_edit").select();
            moveKeys();
            }
        break;
      }
    });
  }

  /*
    Stop edditing when clicked outside of edit input
  */
  $(document).mouseup(function(e)
  {
    var container=$("#change_edit");
    if(!container.is(e.target) && container.has(e.target).length === 0)
    {
  	   if (glob_index!=-1)
  	   {
  	     $(".edit").eq(glob_index).html(container.val());
  	     glob_index=-1;
      }
    
      container.remove();
    }
  });
}

/*
  SET BUTTON EVENTS
*/
function setButtonEvents()
{
  /*
    Erase dollar sign in prices
  */
  $("#clean").click(function()
  {
    $(".price").each(function(index)
    {
      $(this).html($(this).text().replace("$",""));
    });
  });

  /*
    Replace 3 and 8 as dollar sign in price
  */
  $("#template_clean").click(function()
  {
    $(".price").each(function(index)
    {
      var text=$(this).text().replace("3","$").replace("8","$");
      $(this).html(text);
    });
  });

  /*
    Clean extra spaces in product names
  */
  $("#space_clean").click(function()
  {
    $(".product").each(function(index)
    {
      var text=$(this).text().replace(/\s+/g,' ').trim();
      $(this).html(text);
    });
  });

  /*
    Replace comma by dot in prices
  */
  $("#dot_replace").click(function()
  {
    $(".price").each(function(index)
    {
      var text=$(this).text().replace(",",".");
      $(this).html(text);
    });
  });
}