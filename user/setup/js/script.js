var saving=false; //to avoid multiple save clicks create a lag


$(document).ready
(function()
    {
        //Initial block
        var redirect_link="/user/setup/setup_menu.php?name=";

        var chain_link=$("#link_name").val();

        var template=readFile("table_template.html");
        var template=template.split("##")[0];
        var search=['%chain_link%','%link_name%','%phone_number%','%address%','%postal_code%','%options%'];
        var replace=[chain_link,'','','','',$("#option_template").html()];

        template=multipleReplace(search,replace,template);


        setDeleteButton();
        disableSpace();

        $("#add_restaurant").click(function()
          {
            if ($(".restaurant_list").length>0)
            {
                $(".restaurant_list").last().after(template);
            }
            else
            {
                $("#restaurants_form").html(template);
            }

            setDeleteButton();
            disableSpace();
         });

        $("#create_menu").click(function()
           {
                $("#menu_panel").show();
                $("#current_menu_name").val(chain_link+"_menu_"+($(".menu_items").length+1));
           });

        $("#move_to_edit").click(function()
        {
            $("#menu_panel").hide();

            var win = window.open(document.location.origin+redirect_link+ $("#current_menu_name").val(), '_blank');

            if(win)
            {
                //Browser has allowed it to be opened
                win.focus();
            }
            else
            {
                //Broswer has blocked it
                alert('Please allow popups for this site');
            }
        });


        $("#save_restaurant").click(function()
            {
                Save();
            });


        $(document).mouseup(function(e)
        {
            var container=$("#sub_panel");

            //if the target of the click isn't the container...
            // ... nor a descendant of the container

            if(!container.is(e.target) && container.has(e.target).length === 0)
            {
                $("#menu_panel").hide();
            }
        });
    }
);


function disableSpace()
{
    $("input[name^='link_name']").keypress(function(e)
    {
        return "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890_-".indexOf(String.fromCharCode(e.which))>=0;
    });
}

function setDeleteButton()
{
    $(".delete_btn").click(function()
    {
        $(this).closest(".restaurant_list").remove();

        Save();
    });
}

function Save()
{
if (!saving)
{
    var error=0;

    //check if all link names are not empty
    for (var i=0;i<$("input[name^='link_name']").length;i++)
    {
        if ($("input[name^='link_name']").eq(i).val()=="")
        {
            error=1;

            $("#server_result").html("Please fill required fields");
            $("#server_result").css("background-color","#fc5d5d");
            $("#server_result").stop().animate({opacity:1}, 500).delay(1000).animate({opacity:0}, 500,function(){$("#server_result").css("background-color","#57e68e");});

            $("input[name^='link_name']").eq(i).css("border","solid 1px #fc5d5d");
        }
    }


    if (error==0)
    {
    saving=true;
    var datastring=$("#restaurants_form").serialize();

    $.ajax({
        type: "POST",
        url: "save_restaurant.php",
        data: datastring,
        dataType: "text",
        success: function(data)
        {
            $("#server_result").html("Saved!");
            $("#server_result").stop().animate({opacity:1}, 500).delay(1000).animate({opacity:0}, 500);

            console.log(data);
            saving=false;
        },
        error: function()
        {
        alert('An error has occured saving your data. Please try again.');
        }

    });
}
}
}

function multipleReplace(search, replace, string)
{
    for(var i=0;i<search.length;i++)
    {
        string=string.replace(search[i],replace[i]);
    }

    return string;
}

function readFile(file)
{
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", file, false);
    xhttp.send();

    return xhttp.responseText;
}
