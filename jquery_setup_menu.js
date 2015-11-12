$(document).ready(
  function()
  {
    $(".js-example-basic-multiple").select2();
    moveRows();
  }
);

var obj;
function readTextFile(file)
{
  var xhttp = new XMLHttpRequest();
  xhttp.open("GET", file, false);
  xhttp.send();
  return xhttp.responseText;
}

function initiate()
{
  //Load list of countries
  var text=readTextFile("content_list.txt");
  obj=JSON.parse(text);
  var string="<select name='Food_Contents_1[]' class='js-example-basic-multiple' multiple='multiple' style='width:200px;border:0px;'>";
  for (i=0;i<obj.List.length;i++)
  {
    var type=obj.List[i].Type;
    var final_id=type.replace(/\s+/g, '_').toLowerCase();
    string+="<option value="+final_id+">";
    string+=type;
    string+="</option>";
  }

  string+="</select>";

  document.getElementById("food_contents").innerHTML=string;
  $(".js-example-basic-multiple").select2();
}

var section=0;
var order=1;
var sections=[];

function moveRows()
{
  //Move rows
  $('.up,.down').unbind('click').bind('click',
    function(e)
    {
      var row = $(this).parents("tr:first");

      if ($(this).is(".up"))
      {
        if (row.index()>1)
        {
          row.insertBefore(row.prev());
        }
      }
      else
      if (row.index()<order+section)
      {
        row.insertAfter(row.next());
      }


      //Iterate through all first cells in each row
      document.getElementById('FA_Sections').value='';
      var skip=0;
      sections=[];

      //Parse throught the table and rename first cell to adjust movement of rows
      var count=0;
      $('#menu_table').find('tr').each(
        function(i, el)
        {
          if ($(el).index()<order+section+1 && $(el).index()>0)
          {
            if ($(el).find('td').eq(0).attr('colspan')!=7)
            {
               /*            ADJUST NAMES TO SEND CORRECT ORDER TO PHP FILE            */
               $(el).find("input[name^='Product_name_']").attr('name','Product_name_'+(i-skip));
               $(el).find("input[name^='Price_']").attr('name','Price_'+(i-skip));
               $(el).find("input[name^='Description_']").attr('name','Description_'+(i-skip));
               $(el).find("input[name^='Food_Contents_']").attr('name','Food_Contents_'+(i-skip));
               $(el).find('td').eq(0).html(i-skip);
               count++;
             }
            else
            {
              var add_index=sections.length;
              skip++;
              sections.push(i);

              //Adjust the index of sections+++
              document.getElementById('FA_Sections').value=document.getElementById('FA_Sections').value+(i-add_index)+'.';
            }
          }
        });


      $("input[name=Row_Count]").val(count);
      //-----
     });
 }


function addRow()
{
  order++;
  var x = document.getElementById("menu_table").rows.length;
  var table = document.getElementById("menu_table");
  // Create an empty <tr> element and add it to the 1st position of the table:
  var row = table.insertRow(x-2);

  // Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
  var cell1 = row.insertCell(0);
  var cell2 = row.insertCell(1);
  var cell3 = row.insertCell(2);
  var cell4 = row.insertCell(3);
  var cell5 = row.insertCell(4);
  var cell6 = row.insertCell(5);
  var cell7 = row.insertCell(6);    

  //Add some text to the new cells:
  cell1.innerHTML = order;
  cell2.innerHTML="<img src='images/upload_picture.png'>";
  cell3.innerHTML = "<input type='text' name='Product_name_"+order+"'></input>";
  cell4.innerHTML = "<input type='text' style='width:60px;' name='Price_"+order+"'></input><div class='select-style'><select><option value='CAD' select>CAD</option><option value='USD'>USD</option></select><div>";
  cell5.innerHTML="<input type='text' name='Description_"+order+"'></input>";
  //cell6.innerHTML="<input type='button' value='upload picture'></input></td>";

  //Load food content choices in a select box+++
  var string="<select name='Food_Contents_"+order+"[]' class='js-example-basic-multiple' multiple='multiple' style='width:200px;border:0px;'>";

  for (i=0;i<obj.List.length;i++)
  {
    var type=obj.List[i].Type;
    var final_id=type.replace(/\s+/g, '_').toLowerCase();
    string+="<option value="+final_id+">";
    string+=type;        string+="</option>";
  }

  string+="</select>";
  cell6.innerHTML=string;    
  cell7.innerHTML="<a class='up' href='#'>Up</a> <a class='down' href='#'>Down</a>";
  //Load food content choices in a select box---

  $(".js-example-basic-multiple").select2();
  document.getElementById("Row_Count").value=order;
  moveRows();
}

function addSection()
{
  var x = document.getElementById("menu_table").rows.length;
  var table = document.getElementById("menu_table");
  section++;
  var row = table.insertRow(x-2);
  var cell1 = row.insertCell(0);

  cell1.setAttribute("colspan","7");
  cell1.setAttribute("align","center")
  var result="<input type='text' value='Section "+section+"' name='Section_"+(section-1)+"'></input>";
  result+="<input type='button' value='delete' class='remove_section'></input>";
  cell1.innerHTML=result;
  document.getElementById("Row_Count").value=x-2;
  //Remove sections
  $('.remove_section').unbind('click').bind('click',
    function(e)
    {
      var row=$(this).parents("tr:first");
      row.remove();
      section--;
      ReviewSectionIndexing();
    });

    ReviewSectionIndexing();
}

function ReviewSectionIndexing()
{
  document.getElementById('FA_Sections').value='';
  var skip=0;sections=[];$('#menu_table').find('tr').each(
    function(i, el)
    {
      if ($(el).index()<order+section+1 && $(el).index()>0 && $(el).find('td').eq(0).attr('colspan')==7)
      {
        var add_index=sections.length;
        skip++;
        sections.push(i);
        var input=$(el).find('td').eq(0).find('input[type=text]');
        input.attr('value',add_index);
        input.attr('name','Section_'+add_index);

        //Adjust the index of sections+++
        document.getElementById('FA_Sections').value=document.getElementById('FA_Sections').value+(i-add_index)+'.';
      }
    });
}
