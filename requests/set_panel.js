$(document).ready(
    function(){

        $(".menu_elem").mouseover(function()
            {
                $(".menu_elem").css("color","black");
                $(this).css("color","#4d85f2");
            })
        .mouseout(function()
            {
                $(".menu_elem").css("color","black");
            });

         $("#drop_down_panel").mouseover(function()
            {
                $(this).show();
            })
         .mouseout(function()
            {
                $(this).hide();
            });

         $("#user_top_panel").mouseover(function()
            {
                $("#drop_down_panel").show();
            })
         .mouseout(function()
            {
                $("#drop_down_panel").hide();
            });


         $(".drop_down_items").mouseover(function()
            {
                $(".drop_down_items").css("background-color","white");
                $(this).css("background-color","#4d85f2");
            })
        .mouseout(function()
            {
                $(this).css("background-color","white");
            });

    }
);
