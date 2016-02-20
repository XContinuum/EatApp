var obj=JSON.parse(readTextFile("filters.txt")); //list with all the possible food contents

var reading=readTextFile("menu_row_structure.html");
var split_info=reading.split("##");

var row_template=split_info[1];
var edit_section=split_info[0];
var section=0;

$(document).ready(function()
{
    moveRowsEvent();

    $("#add_section").click(function()
    {
        addSection();
    });

    $("#add_row").click(function()
    {
        addRow();
    });

    $("#save_menu_btn").click(function()
    {
      var datastring=new FormData($("#save_menu_form")[0]);

        $.ajax({
        type: "POST",
        url: "save_menu.php",
        data: datastring,
        dataType: "text",
        async: false,
        success: function(data)
        {
            $("#server_result").html(data);
            $("#server_result").stop().animate({opacity:1}, 500).delay(1000).animate({opacity:0}, 500);
            console.log(data);
        },
        error: function()
        {
            alert('An error has occured saving your data. Please try again.');
        },
        cache: false,
        contentType: false,
        processData: false

    });
    });

    initiate();
    disableCharacters();
});

function readTextFile(file)
{
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", file, false);
    xhttp.send();

    return xhttp.responseText;
}

function loadFilters(list)
{
    var options="";

    for (i=0;i<obj.List.length;i++)
    {
        var filter=obj.List[i].Type;
        var option_id=filter.replace(/\s+/g, '_').toLowerCase();
        var selected="";

        if (list!=0)
        {
            for (j=0;j<list.length;j++)
            {
                if (list[j]==option_id)
                    selected="selected='selected'";
            }
        }

        options+="<option value="+option_id+" "+selected+">";
        options+=filter;
        options+="</option>";
    }

    return options;
}

function initiate()
{
    //Highlight selected tags
    for (var i=0;i<$("select[name^='Food_Contents']").length;i++)
    {
         var selected=$("select[name^='Food_Contents']").eq(i).html();
         var list=selected.split(".");
         //loadFilters(list)
         $("select[name^='Food_Contents']").eq(i).html(loadFilters(list));
     }
    //$(".js-example-basic-multiple").select2();

    $(".multi_select").setMultiSelect();

     moveRowsEvent();

    //Add event to remove sections
    $('.remove_section').unbind('click').bind('click', function(e)
    {
        $(this).parents("tr:first").remove();
        section--;
        updateSectionIndex();
    });
    //-----
    updateSectionIndex();
}

/*
    Adding event of moving rows up/down and deleting them
*/
function moveRowsEvent()
{
$('.up,.down,.delete_row').unbind('click').bind('click', function(e)
{
    var row=$(this).parents("tr:first");
    var num_r=$("#menu_table tr").length;

    if ($(this).is(".up"))
    {
        if (row.index()>1)
            row.insertBefore(row.prev());
    }
    else
        if ($(this).is(".down"))
        {
            if(row.index()<num_r+section)
                row.insertAfter(row.next());
        }
        else
            if ($(this).is(".delete_row"))
            {
                if (num_r>3)
                    row.remove();
            }

    //Reindex first cell in the row+++
    var count=0;
    $('#menu_table > tbody  > tr').each(function(i,row)
    {
        if ($(row).find("td").eq(0).attr("colspan")!="7" && i!=0 && i<$("#menu_table tr").length-1)
        {
            count++;
            $(row).find("td").eq(0).html(count);
        }
    });
    //Reindex first cell in the row---

    //reindex Food_Contents for multidimentional array
    for (var i=0;i<$("select[name^='Food_Contents']").length;i++)
    {
        $("select[name^='Food_Contents']").eq(i).attr("name","Food_Contents["+i+"][]");
    }

    updateSectionIndex();
     $(".multi_select").setMultiSelect();
});
}

/*
    Adds a row to the end of the table
*/
function addRow()
{
    var current_index=$("#menu_table tr").length-1-section;

    var search=['%order%','%i%','%SRC%','%picture_url%','%product_name%','%price%','%description%','%food_content%'];
    var replace=[current_index,current_index-1,'images/upload_picture.png','none','','','',loadFilters(0)];

    var new_row=multipleReplace(search,replace,row_template);
    $('#menu_table tr:last').before(new_row);

    //Other settings----
    $(".multi_select").setMultiSelect();

    updateFileChange();
    moveRowsEvent();
    disableCharacters();
}

function multipleReplace(search, replace, string)
{
    for(var i=0;i<search.length;i++)
    {
        string=string.replace(search[i],replace[i]);
    }

    return string;
}

/*
    Adds a section to the end of the table
*/
function addSection()
{
    section++;
    var search=['%section%',"%section_name%"];
    var replace=[section,""];
    var new_row=multipleReplace(search,replace,edit_section);

    $('#menu_table tr:last').before(new_row);


    //Add event to remove sections
    $('.remove_section').unbind('click').bind('click', function(e)
    {
        $(this).parents("tr:first").remove();
        section--;
        updateSectionIndex();
    });
    //-----


    updateSectionIndex();
}

/*
    Adds a list of indexes of all sections into a hidden input for
    the server to localize the position of sections
*/
function updateSectionIndex()
{
    //Add list of indexes of sections
    var indexes="";
    var c=0;

    $('#menu_table > tbody  > tr').each(function(i,row)
    {
        if ($(row).find("td").eq(0).attr("colspan")=="7" && i<$("#menu_table tr").length-1)
        {
            indexes+=(i-1)-c+":";
            c++;
        }
    });

    $("input[name='section_index']").val(indexes.substring(0,indexes.length-1));
}



/*
    Characters
*/
function disableCharacters()
{
    $("input[name^='Price']").keypress(function(e)
    {
        return "1234567890.".indexOf(String.fromCharCode(e.which))>=0;
    });
}



//Upload image+++
var current_row=0;

function updateFileChange()
{
    $(":file").change(function()
    {
        if (this.files && this.files[0])
        {
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL(this.files[0]);

            current_row=$(this).index("input[name^='Food_Images']");
        }
    });
}

$(function ()
{
    updateFileChange();
});

var $superParent;

function imageIsLoaded(e)
{
    $("#opaque_background").show();
    $("#modify_image").show();

    $("body").cleanAll("#uploaded_image");

    $("#uploaded_image").attr("src", e.target.result);
    $("#uploaded_image").imageCrop("200","200");

    $("#apply_cropping").applyCrop("input[name^='Crop_Info']:eq("+current_row+")",".Display_IMG:eq("+current_row+")","50","50");

    $("#cancel_image").click(function(e)
        {
            $("#opaque_background").hide();
            $("#modify_image").hide();

            var control=$("input[name^='Food_Images']:eq("+current_row+")");
            control.replaceWith(control=control.clone(true));

        });
};
 //Upload image---
