//Switch sections depending on the div clicked
function show_partition(obj)
{
    menu=document.getElementById("partition_menu");
    pictures=document.getElementById("partition_pictures");
    reviews=document.getElementById("partition_reviews");
    contact=document.getElementById("partition_contact");

    switch(obj)
    {
        case "Menu":
        menu.style.display="block";
        pictures.style.display="none";
        reviews.style.display="none";
        contact.style.display="none";
        break;

        case "Pictures":
        menu.style.display="none";
        pictures.style.display="block";
        reviews.style.display="none";
        contact.style.display="none";
        break;

        case "Reviews":
        menu.style.display="none";
        pictures.style.display="none";
        reviews.style.display="block";
        contact.style.display="none";
        break;

        case "Contact":
        menu.style.display="none";
        pictures.style.display="none";
        reviews.style.display="none";
        contact.style.display="block";
        break;
    }
}

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
