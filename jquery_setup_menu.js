$(document).ready(
  function()
  {
    $(".js-example-basic-multiple").select2();
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

function addRow()
{
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
  // Add some text to the new cells:

  cell1.innerHTML = x-2;
  cell2.innerHTML = "<input type='text' name='Product_name_"+(x-2)+"'></input>";
  cell3.innerHTML = "<input type='text' style='width:60px;' name='Price_"+(x-2)+"'></input> <select><option value='CAD' select>CAD</option><option value='USD'>USD</option></select>";
  cell4.innerHTML="<input type='text' name='Description_"+(x-2)+"'></input>";
  cell5.innerHTML="<input type='button' value='upload picture'></input></td>";

  //Load food content choices in a select box+++
  var string="<select name='Food_Contents_"+(x-2)+"[]' class='js-example-basic-multiple' multiple='multiple' style='width:200px;border:0px;'>";
  for (i=0;i<obj.List.length;i++)
  {
    var type=obj.List[i].Type;
    var final_id=type.replace(/\s+/g, '_').toLowerCase();
    string+="<option value="+final_id+">";
    string+=type;
    string+="</option>";
  }

  string+="</select>";
  cell6.innerHTML=string;
  //Load food content choices in a select box---

  $(".js-example-basic-multiple").select2();
  document.getElementById("Row_Count").value=x-2;
}
