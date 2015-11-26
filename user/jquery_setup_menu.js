var obj; //list with all the possible food contents
var section=0; //number of sections
var rows_count=1; //the number of rows in the table
var columns_count=7; //total number of columns in the table

$(document).ready(
    function()
    {
        addMoveRowsEvent();

        jQuery('#success').animate({ top: '0px'}, 400, 'swing',function(){

        //wait 2 seconds
        setTimeout(function(){
        jQuery('#success').animate({ top: '-23px'}, 400, 'swing');
        }, 2000);

        }
        );

    }
);

function readTextFile(file)
{
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", file, false);
    xhttp.send();

    return xhttp.responseText;
}

function returnMultiselect(number)
{
    var multiselect="<select name='Food_Contents_"+number+"[]' class='js-example-basic-multiple' multiple='multiple' style='width:200px;border:0px;'>";
    for (i=0;i<obj.List.length;i++)
    {
        var type=obj.List[i].Type;
        var final_id=type.replace(/\s+/g, '_').toLowerCase();

        multiselect+="<option value="+final_id+">";
        multiselect+=type;
        multiselect+="</option>";
    }
    multiselect+="</select>";

    return multiselect;
}

function returnMultiselectOptions(number,list)
{
    var multiselect="<select name='Food_Contents_"+(number+1)+"[]' class='js-example-basic-multiple' multiple='multiple' style='width:200px;border:0px;'>";
    var selected;

    for (i=0;i<obj.List.length;i++)
    {
        var type=obj.List[i].Type;
        var final_id=type.replace(/\s+/g, '_').toLowerCase();
        selected="";

        for (j=0;j<list.length;j++)
        {
           if (list[j]==final_id)
            {
                selected="selected='selected'";
            }
        }

        multiselect+="<option value="+final_id+" "+selected+">";
        multiselect+=type;
        multiselect+="</option>";
    }
    multiselect+="</select>";

    return multiselect;
}

function initiate()
{
    var text=readTextFile("../content_list.txt");
    obj=JSON.parse(text);

    //Load selected tags+++
    var food_tags=document.getElementsByClassName("food_contents");

    for (var i=0;i<food_tags.length;i++)
    {
        var selected=food_tags[i].innerHTML;
        var list=selected.split(".");

        food_tags[i].innerHTML=returnMultiselectOptions(i,list);
    }
    $(".js-example-basic-multiple").select2();
    //Load selected tags---

    reiterateIDs();
}

/*
    Adding even of moving rows up/down and deleting them
*/
function addMoveRowsEvent()
{
$('.up,.down,.delete_row').unbind('click').bind('click', function(e)
{
    var row=$(this).parents("tr:first");
    var x=document.getElementById("menu_table").rows.length;

    if ($(this).is(".up"))
    {
        if (row.index()>1)
            row.insertBefore(row.prev());
    }
    else
        if ($(this).is(".down"))
        {
            if(row.index()<rows_count+section)
                row.insertAfter(row.next());
        }
        else
            if ($(this).is(".delete_row"))
                {
                    if (x>3)
                    {
                    row.remove();
                    rows_count--;
                    }
                }

    reiterateIDs();
});
}

/*
    Reindexes the names of inputs to be in the proper order when sending to the database
*/
function reiterateIDs()
{
    var count=0; //number of rows in the table
    var section_count=0; //number of sections

    var x=document.getElementById("menu_table").rows.length;

    //Parse throught the table and rename first cell to adjust movement of rows
    $('#menu_table').find('tr').each(function(i, el)
    {
        if ($(el).index()<x-1 && $(el).index()>0)
        {
            if ($(el).find('td').eq(0).attr('colspan')!=columns_count)
            {
            /*
            ADJUST NAMES TO SEND CORRECT ORDER TO PHP FILE
            */
                $(el).find("input[name^='Product_name_']").attr('name','Product_name_'+(i-section_count));
                $(el).find("input[name^='Price_']").attr('name','Price_'+(i-section_count));
                $(el).find("input[name^='Description_']").attr('name','Description_'+(i-section_count));
                $(el).find("input[name^='Food_Contents_']").attr('name','Food_Contents_'+(i-section_count));

                $(el).find("input[name^='food_images_']").attr('name','food_images_'+(i-section_count));
                $(el).find("input[name^='picture_url_']").attr('name','picture_url_'+(i-section_count));


                $(el).find('td').eq(0).html(i-section_count); //rename the first cell
                count++;
            }
            else
            {
                section_count++; //number of sections
            }
        }
    });

    rows_count=count; //global

    $("input[name=Row_Count]").val(count);
    ReviewSectionIndexing();
}

/*
    Adds a row to the end of the table
*/
function addRow()
{
    rows_count++;
    var all_rows=document.getElementById("menu_table").rows.length; //current number of rows
    var row=document.getElementById("menu_table").insertRow(all_rows-1);

    var cells=[];

    for (var i=0;i<columns_count;i++)
        cells[i] = row.insertCell(i);

    cells[0].innerHTML=rows_count;

    //Picture
    var pic="<label><input name='food_images_"+rows_count+"' style='width:110px;display:none;' type='file' value='Select picture'></input><img src='images/upload_picture.png'></img></label>";
    pic+="<input type='text' name='picture_url_"+rows_count+"' value='none' style='display:none;'></input>";
    cells[1].innerHTML=pic;
    //Picture

    cells[2].innerHTML="<input type='text' name='Product_name_"+rows_count+"' placeholder='Meal'></input>";
    cells[3].innerHTML="<input type='text' style='width:60px;' name='Price_"+rows_count+"' placeholder='Price'></input>";
    cells[4].innerHTML="<textarea name='Description_"+rows_count+"' placeholder='Description'></textarea>";
    cells[5].innerHTML=returnMultiselect(rows_count);

    cells[6].innerHTML="<a class='up'><img src='images/up_arrow.png'></a> ";
    cells[6].innerHTML+="<a class='down'><img src='images/down_arrow.png'></a> ";
    cells[6].innerHTML+="<a class='delete_row'><img src='images/delete.png'></a>";

    $(".js-example-basic-multiple").select2();

    $("input[name=Row_Count]").val(rows_count);
    addMoveRowsEvent();
}

/*
    Adds a section to the end of the table
*/
function addSection()
{
    section++;

    var x=document.getElementById("menu_table").rows.length;
    var row=document.getElementById("menu_table").insertRow(x-1);
    var cell=row.insertCell(0);

    cell.setAttribute("colspan",columns_count);
    cell.setAttribute("align","center");

    //Set up the section row+++
    var result="<input type='text' placeholder='Section "+section+"' name='Section_"+(section-1)+"'></input>";
    result+="<input type='button' value='Delete' class='remove_section delete_button'></input>";
    cell.innerHTML=result;
    document.getElementsByName("Section_"+(section-1))[0].select();

    //Add event to remove sections
    $('.remove_section').unbind('click').bind('click', function(e)
    {
        var row=$(this).parents("tr:first");
        row.remove();
        section--;
        ReviewSectionIndexing();
    });


    ReviewSectionIndexing();
}

/*
    Reindexes the section name to be in the proper order when sending to the database
*/
function ReviewSectionIndexing()
{
    document.getElementById('FA_Sections').value="";

    var section_count=0;
    var x=document.getElementById("menu_table").rows.length;

    $('#menu_table').find('tr').each(function(i, el)
    {
        if ($(el).index()<x-1 && $(el).index()>0)
        {
            if ($(el).find('td').eq(0).attr('colspan')==columns_count)
            {
                section_count++;

                var input=$(el).find('td').eq(0).find('input[type=text]');
                input.attr('name','Section_'+section_count);
                input.attr('placeholder','Section '+section_count);
                //Adjust the index of sections+++
                document.getElementById('FA_Sections').value=document.getElementById('FA_Sections').value+(i-section_count+1)+'.';
            }
        }
    });

    section=section_count;
}
