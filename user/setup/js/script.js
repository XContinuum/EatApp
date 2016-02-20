var saving=false; //to avoid multiple save clicks create a lag


$(document).ready(function()
    {
        //Initial block
        $("input[type='time_picker']").setTimePicker();

        var redirect_link="/user/setup/setup_menu.php?name=";

        var chain_link=$("#link_name").val();

        var structure=readFile("table_template.html");
        var template=structure.split("##")[0];

        var schedule=structure.split("##")[2];
        var schedule_search=["%name%", "%monday_start%", "%monday_end%", "%tuesday_start%", "%tuesday_end%", "%wednesday_start%", "%wednesday_end%", "%thursday_start%", "%thursday_end%", "%friday_start%", "%friday_end%", "%saturday_start%", "%saturday_end%", "%sunday_start%", "%sunday_end%"];


        var search=['%chain_link%','%link_name%','%phone_number%','%address%','%postal_code%','%options%','%country%','%state_province%','%city%','%schedule%'];
        var replace=[chain_link,'','','','',$("#option_template").html(),'','','',$("#sch_template").html()];

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


        $("#create_schedule").click(function()
          {
            if ($(".schedule_list").length>0)
            {
                var schedule_replace=[chain_link+"_sch_"+($(".schedule_list").length+1),"","","","","","","","","","","","","",""];
                var rep=multipleReplace(schedule_search,schedule_replace,schedule);

                $(".schedule_list").last().after(rep);
            }
            else
            {
                var schedule_replace=[chain_link+"_sch_1","","","","","","","","","","","","","",""];
                var rep=multipleReplace(schedule_search,schedule_replace,schedule);

                $("#sch_form").html(rep);
            }

            $("input[type='time_picker']").setTimePicker();
         });

         $(".delete_sch").click(function()
         {
            var index=$(this).index(".delete_sch");

            $(".schedule_list").eq(index).remove();
            Save_Sch();

            $(".sch_name").each(function(index)
            {
                $(this).html(chain_link+"_sch_"+(index+1));
            });
         });



        $("#create_menu").click(function()
           {
                $("#menu_panel").show();

                if ($(".menu_items").length>0)
                {
                    var names=[];
                    $(".mn_name").each(function(index)
                    {
                        names.push(parseInt($(this).text().replace(chain_link+"_menu_","")));
                    });

                    names.sort();

                    var count=1;
                    while (isInArray(count,names))
                    {
                        count++;
                    }

                    $("#current_menu_name").html(chain_link+"_menu_"+count);
                }
                else
                {
                    $("#current_menu_name").html(chain_link+"_menu_1");
                }
           });

        $("#move_to_edit").click(function()
        {
            $("#menu_panel").hide();

            var win = window.open(document.location.origin+redirect_link+ $("#current_menu_name").text(), '_blank');

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

        $("#save_schedule").click(function()
            {
                Save_Sch();
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

        //Delete Menu
        $(".delete_menu").click(function(e)
        {
            e.preventDefault();

            var menuName=$(this).attr("alt");

            $.post("delete_menu.php",{menu_name : menuName}).done(function(data)
            {
                console.log(data);
            });

            $(this).closest(".menu_items").remove();
        });
        //---


        //Show
        $("#res_btn").click(function()
        {
            $("#res_win").show();
            $("#menu_win").hide();
            $("#sch_win").hide();

            $("#res_btn").stop().animate({opacity:1}, 400);
            $("#mn_btn").stop().animate({opacity:0.5}, 400);
            $("#sch_btn").stop().animate({opacity:0.5}, 400);

        });
        //---

        //Show
        $("#mn_btn").click(function()
        {
            $("#res_win").hide();
            $("#menu_win").show();
            $("#sch_win").hide();

            $("#res_btn").stop().animate({opacity:0.5}, 400);
            $("#mn_btn").stop().animate({opacity:1}, 400);
            $("#sch_btn").stop().animate({opacity:0.5}, 400);

        });
        //---

        //Show
        $("#sch_btn").click(function()
        {
            $("#res_win").hide();
            $("#menu_win").hide();
            $("#sch_win").show();

            $("#res_btn").stop().animate({opacity:0.5}, 400);
            $("#mn_btn").stop().animate({opacity:0.5}, 400);
            $("#sch_btn").stop().animate({opacity:1}, 400);
        });
        //---
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

function Save_Sch()
{
    if (!saving)
    {
        saving=true;
        var datastring=$("#sch_form").serialize();

    $.ajax({
        type: "POST",
        url: "save_schedule.php",
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

function isInArray(value, array)
{
  return array.indexOf(value) > -1;
}

function readFile(file)
{
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", file, false);
    xhttp.send();

    return xhttp.responseText;
}
