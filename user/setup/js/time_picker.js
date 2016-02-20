(function($)
{
    function readTextFile(file)
    {
        var xhttp = new XMLHttpRequest();
        xhttp.open("GET", file, false);
        xhttp.send();

        return xhttp.responseText;
    }

    function multipleReplace(search, replace, string)
    {
        for(var i=0;i<search.length;i++)
        {
            string=string.replace(search[i],replace[i]);
        }

        return string;
    }

    $.fn.setTimePicker=function()
    {
        $(document).HideTicker(); //hides div when not on div

        return this.each( function()
        {
            if ($(this).val()=="")
            $(this).val("9:00 AM");


             $(this).click(function(e)
             {
                $(this).css("background-color","#ced1d7");
                $(".ticker").remove();

                var h;
                var m;
                var t_slot;

                var parse=$(this).val();
                var pos=0;

                for (var i=0;i<parse.length;i++)
                {
                    if (parse.substring(i,i+1)==":" && pos==0)
                    {
                        h=parse.substring(pos,i);
                        pos=i+1;
                    }
                    else
                        if (parse.substring(i,i+1)==" ")
                        {
                            m=parse.substring(pos,i);
                            t_slot=parse.substring(i+1,parse.length);
                        }
                }

                var structure=readFile("js/timer_structure.html");
                var search=["%h%","%m%","%t_slot%"];
                var replace=[h,m,t_slot];

                var code=multipleReplace(search,replace,structure);

                var pos=$(this).position();
                $("<div class='ticker'></div>").html(code).css({
                top: pos.top+$(this).height()+9,
                left: pos.left,
                width: '190px',
                height: '70px',
                border:'1px solid #ced1d7',
                position: 'absolute',
                padding:"5px",
                'background-color':'white'
                }).insertAfter($(this));

                var input=$(this);

                $("#hour_up").click(function(e)
                {
                    var hour=parseInt($("#hour").html());

                    if (hour<12)
                    {
                        hour++;
                    }

                    $("#hour").html(""+hour);
                    input.val($("#hour").html()+":"+$("#minute").html()+" "+$("#time_slot").html());
                });


                $("#hour_down").click(function(e)
                {
                    var hour=parseInt($("#hour").html());

                    if (hour>0)
                    {
                        hour--;
                    }

                    $("#hour").html(""+hour);
                    input.val($("#hour").html()+":"+$("#minute").html()+" "+$("#time_slot").html());
                });

                $("#minute_up").click(function(e)
                {
                    var minute=parseInt($("#minute").html());

                    if (minute<59)
                    {
                        minute++;
                    }

                    var string=minute;

                    if (minute<10)
                    {
                        string="0"+minute;
                    }

                    $("#minute").html(string);
                    input.val($("#hour").html()+":"+$("#minute").html()+" "+$("#time_slot").html());
                });


                $("#minute_down").click(function(e)
                {
                    var minute=parseInt($("#minute").html());

                    if (minute>0)
                    {
                        minute--;
                    }

                    var string=minute;

                    if (minute<10)
                    {
                        string="0"+minute;
                    }

                    $("#minute").html(string);
                    input.val($("#hour").html()+":"+$("#minute").html()+" "+$("#time_slot").html());
                });

                $("#time_slot_up").click(function(e)
                {
                    $("#time_slot").html("PM");
                    input.val($("#hour").html()+":"+$("#minute").html()+" "+$("#time_slot").html());
                });

                $("#time_slot_down").click(function(e)
                {
                    $("#time_slot").html("AM");
                    input.val($("#hour").html()+":"+$("#minute").html()+" "+$("#time_slot").html());
                });

             });

        });
    }

    $.fn.HideTicker=function()
    {
            return this.each(function()
            {
             $(document).mouseup(function (e)
            {
                var container = $(".ticker");

                if (!container.is(e.target) //if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
                {
                    container.hide();
                    $("input[type='time_picker']").css("background-color","#f9f8f8");
                }
                });
            });

    }
}(jQuery));
