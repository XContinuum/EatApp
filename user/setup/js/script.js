$(document).ready
(
    function()
    {
        //Initial block
        var arr_info=$("#link_name").val().split(":");

        var chain_link=arr_info[0];
        var count=arr_info[1] || 0;

        var template=readFile("table_template.html");
        var template=template.split("##")[0];
        var search=['%chain_link%','%value_2%','%value_3%','%value_4%','%value_5%','%options%'];
        var replace=[chain_link,'','','','',$("#option_template").html()];

        template=multipleReplace(search,replace,template);


        $("#add_restaurant").click(function()
          {
            count++;
            var code=template.replace('%value_1%',count);

            if ($(".restaurant_list").length>0)
            {
                $(".restaurant_list").last().after(code);
            }
            else
            {
                $("#restaurants_form").html(code);
            }
         });

        $("#create_menu").click(function()
           {
                $("#menu_panel").show();
                $("#current_menu_name").val(chain_link+"_menu_"+($(".menu_items").length+1));
           });

        $("#move_to_edit").click(function()
        {
            $("#menu_panel").hide();

            var win = window.open(document.location.origin+'/user/setup/setup_menu.php?name='+ $("#current_menu_name").val(), '_blank');

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

        $(".delete_btn").click(function()
            {
                var indx=$(this).closest(".restaurant_list").index(".restaurant_list");
                count--;

                $(this).closest(".restaurant_list").remove();

                Save();
            });

        $("#save_restaurant").click(function()
            {
                Save();
            });
    }
);

function Save()
{
    var datastring=$("#restaurants_form").serialize();

            $.ajax({
                type: "POST",
                url: "save_restaurant.php",
                data: datastring,
                dataType: "text",
            success: function(data)
            {
                $("#server_result").html("Saved!");
                $("#server_result").css({"color":"white","background-color":"#58ec74"});

                $("#server_result").stop().animate({opacity:1}, 500).delay(1000).animate({opacity:0}, 500);
            },
            error: function()
            {
                  alert('An error has occured saving your data. Please try again.');
            }
        });
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
