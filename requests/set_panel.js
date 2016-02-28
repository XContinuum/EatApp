/*
    JQuery events for drop down menu of the restaurant panel
*/
$(document).ready(function()
{
    /* Shows drop down pannel on mouseover */
    $("#drop_down_panel").mouseover(function()
    {
        $(this).show();
    })
    .mouseout(function()
    {
        $(this).hide();
    });

    /* Shows drop down pannel on mouseover */
    $("#user_top_panel").mouseover(function()
    {
        $("#drop_down_panel").show();
    })
    .mouseout(function()
    {
        $("#drop_down_panel").hide();
    });

    /* Color blue the dropdown items when mouseover and white when mouse out */
    $(".drop_down_items").mouseover(function()
    {
        $(".drop_down_items").css("background-color","white");
        $(this).css("background-color","#4d85f2");

        if ($(this).html()=="profile")
        {
            var src=$("#small_triangle").attr("src").replace("triangle","blue");
            $("#small_triangle").attr("src",src);
        }
        else
        {
            var src=$("#small_triangle").attr("src").replace("blue","triangle");
            $("#small_triangle").attr("src",src);
        }
    })
    .mouseout(function()
    {
        $(this).css("background-color","white");
        var src=$("#small_triangle").attr("src").replace("blue","triangle");
        $("#small_triangle").attr("src",src);
    });

});
