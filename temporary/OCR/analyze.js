$(document).ready(function()
{
$("#imgInp").change(function()
{
    readURL(this);
});



});


function save()
{
	 var datastring=new FormData($("#images_file")[0]);

        $.ajax({
        type: "POST",
        url: "analyze.php",
        data: datastring,
        dataType: "text",
        async: false,
        success: function(data)
        {
        	//console.log(data);
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
        cache: false,
        contentType: false,
        processData: false
    });
}

var glob_index=-1;

function setEditFields()
{
	$(".price").click(function()
	{
		if (glob_index!=$(this).index(".price"))
		{
			$(this).css("padding","0px");
			glob_index=$(this).index(".price");
			var value=$(this).text();
			$(this).html("<input value='"+value+"' type='text' id='change_price'/>");
		}
	});

	$(document).mouseup(function(e)
{
 var container=$("#change_price");
  if(!container.is(e.target) && container.has(e.target).length === 0)
  {
  	if (glob_index!=-1)
  	{
  	$(".price").eq(glob_index).html(container.val());
  	glob_index=-1;
  }
     container.remove();
  }
});
}


function readURL(input) 
{
    if (input.files && input.files[0]) 
    {
        var reader = new FileReader();

        reader.onload = function (e) 
        {
            $('#blah').attr('src', e.target.result);
			  $("#process").show();
			save();	
        }

        reader.readAsDataURL(input.files[0]);
    }
}
