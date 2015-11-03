$(document).ready(function()
{
  var x_timer;
  $("#check_email").keyup(
    function (e){clearTimeout(x_timer)
      var email = $(this).val();
      x_timer = setTimeout(
        function()
        {
          check_username_ajax(email);
        }, 1000);
      });

    function check_username_ajax(email)
    {
      $("#user-result").html('Available');
      $.post('requests/email_checker.php', {'email':email},
        function(data)
        {
          var result=$.trim(data);
          if (result == "<img src='images/not_available.png'></img>")
          {
            $("#check_email").css({"border-color": "red","border-style":"solid","border-width":"1px","padding":"2px"});
            $("#sign_up_button").attr("disabled","true");
          }
          else if (result == "<img src='images/available.png'></img>")
          {
            $("#check_email").css({"border-color": "#5db58a","border-style":"solid","border-width":"1px","padding":"2px"});
            $("#sign_up_button").removeAttr('disabled');
          }

          $("#user-result").html(data);
        });
    }
});


function checkPasswords()
{
  var pass1=document.getElementsByName("FA_Password");
  var pass2=document.getElementsByName("FA_Confirm_Password");
  if (pass1[0].value!="" && pass2[0].value!="")
  {
    if (pass1[0].value!=pass2[0].value)
    {
      pass1[0].style.borderColor="#ff0000";
      pass2[0].style.borderColor="#ff0000";

      pass1[0].style.borderStyle="solid";
      pass2[0].style.borderStyle="solid";

      pass1[0].style.borderWidth="1px";
      pass2[0].style.borderWidth="1px";

      pass1[0].style.padding="2px";
      pass2[0].style.padding="2px";

      document.getElementById("sign_up_button").disabled=true;
    }
    else
    {
      pass1[0].style.borderColor="#5db58a";
      pass2[0].style.borderColor="#5db58a";
      pass1[0].style.borderStyle="solid";
      pass2[0].style.borderStyle="solid";            
      pass1[0].style.borderWidth="1px";
      pass2[0].style.borderWidth="1px";
      pass1[0].style.padding="2px";
      pass2[0].style.padding="2px";
      document.getElementById("sign_up_button").disabled=false;
    }
  }
}


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
  var text=readTextFile("country_list.txt");
  var obj=JSON.parse(text);
  var string="<select name='FA_Country'>";
  var sel="";
  for (i=0;i<obj.List.length;i++)
  {
    var country=obj.List[i].Country;
    if (country=="Canada")
    {
      sel=" selected";
    }
    else
    {
      sel="";
    }

    string+="<option value="+country+sel+">";
    string+=country;
    string+="</option>";
  }

  string+="</select>";
  document.getElementById("country_list").innerHTML=string;
}
