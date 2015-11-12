var email_available=2;
var passwords_valid=2;
var user_name_available=2;
var agreement=0;
var restaurant_name_valid=2;
var login=false;

//Load country list for location menu+++
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
  var string="<div class='select-style'><select name='FA_Country'>";
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

  string+="</select></div>";

  document.getElementById("country_list").innerHTML=string;

  //disable button
  document.getElementById("next_location").style.backgroundColor="grey";
  document.getElementById("finalize_sign_up").style.backgroundColor="grey";
}
//Load country list for location menu---

$(document).ready(function()
{
  var x_timer;$("#check_email").keyup(
    function (e)
    {
      clearTimeout(x_timer);
      var email = $(this).val();
      x_timer = setTimeout(function(){check_email_ajax(email);}, 1000);
    });


    var x_timer2;

    $("#check_username").keyup(
      function (e)
      {
        clearTimeout(x_timer2);
        var username = $(this).val();
        x_timer2 = setTimeout(function(){check_username_ajax(username);}, 1000);
      });

      //check email

      function check_email_ajax(email)
      {
        $("#user-result").html('Loading');
        $.post('requests/email_checker.php', {'email':email},
        function(data)
        {
          var result=$.trim(data);
          if (result == "<img src='images/not_available.png'></img>")
          {
            //Email taken
            $("#check_email").removeClass();
            $("#check_email").attr('class', 'wrong_field');
            setError("Email is not available");
            email_available=0;
            setLogin();
            $("#user-result").html(data);
          }
          else if (result == "<img src='images/available.png'></img>")
          {
            //Email format is invalid
            if (!isValidEmailAddress(email))
            {
              $("#check_email").removeClass();
              $("#check_email").attr('class', 'wrong_field');
              setError("Email has an improper format");
              email_available=0;
              setLogin();
              $("#user-result").html("<img src='images/not_available.png'></img>");
            }
            else
            {
              //Everything is fine
              $("#check_email").removeClass();
              $("#check_email").attr('class', 'approved_field');
              setError("");
              email_available=1;
              setLogin();
              $("#user-result").html(data);
            }
          }
        });
      }


      //check username
      function check_username_ajax(username)
      {
        $("#username-result").html('Loading');
        $.post('requests/username_checker.php',{'username':username},
        function(data)
        {
          var result=$.trim(data);
          if (result == "<img src='images/not_available.png'></img>")
          {
            //Username taken
            $("#check_username").removeClass();
            $("#check_username").attr('class', 'wrong_field');
            setError("Username is not available");
            user_name_available=0;setLogin();
            $("#username-result").html(data);
          }
          else if (result == "<img src='images/available.png'></img>")
          {
            //Username format is invalid
            if (username.length<0 || /[\W]/.test(username)==true)
            {
              $("#check_username").removeClass();
              $("#check_username").attr('class', 'wrong_field');
              setError("Username has an improper format");
              user_name_available=0;setLogin();
              $("#username-result").html("<img src='images/not_available.png'></img>");
            }
            else
            {
              //Everything is fine
              $("#check_username").removeClass();
              $("#check_username").attr('class', 'approved_field');

              setError("");user_name_available=1;
              setLogin();
              $("#username-result").html(data);
            }
          }
        });
    }
    //+++
});



function isValidEmailAddress(emailAddress)
{
  var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;

  return pattern.test(emailAddress);
};

//Show error in div
function setError(err)
{
  document.getElementById("error_box").innerHTML=err;
}

function checkPasswords()
{
    var pass1=document.getElementsByName("FA_Password")[0];
    var pass2=document.getElementsByName("FA_Confirm_Password")[0];
    if (pass1.value!="" && pass2.value!="")
    {
      //Check if passwords are equal
      if (pass1.value!=pass2.value)
      {
        passwords_valid=0;
        setError("Passwords are different!");
        setLogin();
      }
      else
      //Check password length
      if (pass1.value.length<6)
      {
        passwords_valid=0;
        setError("Password is too short");
        setLogin();
      }
      else
      //check if there are letters
      if (/[\W_]/.test(pass1.value) == true)
      {
        passwords_valid=0;
        setError("Password must contain only letters and numbers");
        setLogin();
      }
      else
      {
        //Everything is fine
        passwords_valid=1;
        setError("");
        setLogin();
      }           
    }
}

function checkRestaurantName(field)
{
  var name=field.value;
  if (name.length<1)
  {
    //Name is notlong enough
    restaurant_name_valid=0;
    setLogin();
  }
  else
  {
    //Everything is fine
    restaurant_name_valid=1;
    setError("");
    setLogin();
  }
}

function toggleTable()
{
  if (login==true)
  {
    var lTable = document.getElementById("sign_up_table");
    var lTableLocation = document.getElementById("sign_up_location_table");
    lTable.style.display = (lTable.style.display == "table") ? "none" : "table";
    lTableLocation.style.display = (lTable.style.display == "table") ? "none" : "table";
  }
}


function hideLocationTable()
{
  var lTable = document.getElementById("sign_up_location_table");
  var lTableLocation = document.getElementById("menu_table");
  lTable.style.display = (lTable.style.display == "table") ? "none" : "table";
  lTableLocation.style.display = (lTable.style.display == "table") ? "none" : "table";
}

//Set login value
function setLogin()
{
  var field_pass1=document.getElementsByName("FA_Password")[0];
  var field_pass2=document.getElementsByName("FA_Confirm_Password")[0];
  var restaurant_name=document.getElementById("restaurant_name").value;

  if (passwords_valid==0)
  {
    field_pass1.className = "wrong_field";
    field_pass2.className = "wrong_field";
  }
  else
  if (passwords_valid==1)
  {
    field_pass1.className = "approved_field";
    field_pass2.className = "approved_field";
  }    

  if (email_available==1 && passwords_valid==1 && user_name_available==1 && agreement==1 && restaurant_name_valid==1)
  {
    login=true;
    document.getElementById('next_location').style.backgroundColor="green";
  }
  else
  {
    login=false;
    document.getElementById('next_location').style.backgroundColor="grey";
  }
}

//Agreement
function OnChangeCheckbox(checkbox)
{
  if (checkbox.checked)
  {
    agreement=1;
    setLogin();
  }
  else
  {
    agreement=0;
    setLogin();
  }
}

//LOCATION FUNCTIONS
//var city_length=1;
var address_length=2;
var postal_code_length=2;

function Check_city(field)
{
  if (field.value.length>0)
  {
    city_length=1;
  }
  else
  {
    city_length=0;
  }

  final_result();
}

function Check_Address(field)
{
  if (field.value.length>0)
  {
    address_length=1;
  }
  else
  {
    address_length=0;
  }

  final_result();
}

function Check_postal_code(field)
{
  if (field.value.length>0)
  {
    postal_code_length=1;
  }
  else
  {
    postal_code_length=0;
  }

  final_result();
}

function final_result()
{
  if (city_length==1 && address_length==1 && postal_code_length==1)
  {
    //Enable button
    document.getElementById("finalize_sign_up").disabled=false;
    document.getElementById("finalize_sign_up").style.backgroundColor="green";
  }
  else
  {
    //Disable button
    document.getElementById("finalize_sign_up").disabled=true;
    document.getElementById("finalize_sign_up").style.backgroundColor="grey";
  }
}
