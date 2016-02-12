(function($)
{
    function wrapSelected(count)
    {
        var tags="<div style='display:inline-block;background-color:#a5c3fe;padding:2px;font-family:AvenirLTStd-Light;'><div class='delete_tag' style='display:inline-block;'><img src='images/delete_tag.png' align='center'/></div> %tag%</div> ";

        var list="";
        $('.multi_select:eq('+count+') option:selected').each(function()
        {
            list+=tags.replace("%tag%",$(this).val());
        });

        $('.tags').eq(count).html(list);

        $('.delete_tag').click(function()
        {
            var $t=$(this).parent().closest('.tags');
            var index=$('.tags').index($t);

            var s_val=$(this).parent().text();
            s_val=s_val.replace(" ","");

            $('.multi_select:eq('+index+') option[value='+s_val+']').removeAttr('selected');

            wrapSelected(index);
        });
    }

    function setOptions(box)
    {
        var pos=$(box).position();
        var index=$(box).parent().find(".multiBox").index(box);
        var layer=$(".multiBox").eq(index).css("z-index");

        //Load list
        var list="";
        var tag="<div style='height:25px;padding:2px;padding-top:4px;font-family:AvenirLTStd-Light;box-sizing:border-box;' class='item'>%option%</div>";

        $('.multi_select:eq('+index+') option').each(function()
        {
            list+=tag.replace("%option%",$(this).val());
        });
        //Load list

        $("<div class='drop_down'/>").html(list).css({
        top:pos.top+$(box).height()+1,
        left:pos.left,
        width:'200px',
        height:'75px',
        position:'absolute',
        'background-color':'white',
        "box-sizing":"border-box",
        "box-shadow":"0px 0px 3px #ccc",
        "z-index":layer-1,
        "overflow":"auto"
        }).insertAfter($(box));

        $(".drop_down").attr("id","id_"+index);

        setItemEvents();
    }

    function setItemEvents()
    {
        $(".item").mouseover(function()
        {
            $(".item").css("background-color","white");
            $(this).css("background-color","#4d85f2");
        }).mouseleave(function()
        {
            $(".item").css("background-color","white");
        }).click(function()
        {
            var index=$(this).parent().attr("id").replace("id_","");
            $('.multi_select:eq('+index+') option[value='+$(this).html()+']').attr('selected', true);
            wrapSelected(index);

            $(".drop_down").hide();
            $(".drop_down").remove();
        });
    }

    $.fn.setMultiSelect=function()
    {
        var count=0;
        var layer=100;
        return this.each(function()
        {
            count++;

            var $box=$("<div class='multiBox'/>").css({
                "position":"relative",
                "border":"1px solid #ccc",
                "width":"200px",
                "height":"30px",
                "box-sizing" : "border-box",
                 "z-index":layer,
                 "background-color":"white"
            }).html("");

            layer-=2;

            $(this).wrap($box);
            $(this).hide();
            $("<div class='tags'/>").css({"white-space":"nowrap","display":"inline-block","padding":"3px","width":"190px","height":"25px","overflow":"scroll"}).insertAfter(this);


            wrapSelected(count-1);

            $(document).mouseup(function(e)
            {
                var container=$(".drop_down");

                if(!container.is(e.target) && container.has(e.target).length === 0)
                {
                    container.hide();
                    $(".drop_down").remove();
                }
            });


             $(".multiBox").unbind('click').bind('click', function(e)
             {
               setOptions(this);
             });

        });
    }

}(jQuery));
