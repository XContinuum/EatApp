(function($)
{
    $.fn.setTimePicker=function()
    {
        $(document).HideTicker(); //hides div when not on div

        return this.each( function()
        {
            $(this).val("9:00 AM");



             $(this).click(function(e)
             {
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

                var code="<table style='width:100%;'>";
                code+="<tr><td><img id='hour_up' src='../images/time_up.png'/></td><td><img id='minute_up' src='../images/time_up.png'/></td><td><img id='time_slot_up' src='../images/time_up.png'/></td></tr>";
                code+="<tr><td><div id='hour'>"+h+"</div></td><td><div id='minute'>"+m+"</div></td><td><div id='time_slot'>"+t_slot+"</div></td></tr>";
                code+="<tr><td><img id='hour_down' src='../images/time_down.png'/></td><td><img id='minute_down' src='../images/time_down.png'/></td><td><img id='time_slot_down' src='../images/time_down.png'/></td></tr>";
                code+="</table>";

               var pos=$(this).position();
                $("<div class='ticker'></div>").html(code).css({
                top: pos.top+$(this).height()+13,
                left: pos.left,
                width: '120px',
                height: '60px',
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
                }
                });
            });

    }
}(jQuery));
