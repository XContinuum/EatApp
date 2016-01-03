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
    //document.getElementById("finalize_sign_up").style.backgroundColor="#babdc6";
}


//Load country list for location menu---
$(document).ready(
    function(){
     $("input[type=time_picker]").helloWorld();



    var x_timer;
    $("#check_email").keyup(function (e)
    {
        clearTimeout(x_timer);
        var email = $(this).val();
        x_timer = setTimeout(function(){check_email_ajax(email);}, 1000);
    });


    var x_timer2;
    $("#check_username").keyup(function (e)
    {
        clearTimeout(x_timer2);
        var username = $(this).val();
        x_timer2 = setTimeout(function(){check_username_ajax(username);}, 1000);
    });




    //check email
    function check_email_ajax(email)
    {
    $.post('../requests/email_checker.php', {'email':email}, function(data)
    {
       var result=$.trim(data);

    if (result == "0") //not available
    {
       //Email taken
       $("#check_email").removeClass();
       $("#check_email").addClass('wrong_field');

       setError("Email is not available");

       email_available=0;
       setLogin();
    }
    else
      if (result == "1")
       {
        //Email format is invalid
        if (!isValidEmailAddress(email))
        {
            $("#check_email").removeClass();
            $("#check_email").addClass('wrong_field');

            setError("Email has an improper format");
            email_available=0;
            setLogin();
        }
        else
            {
            //Everything is fine
            $("#check_email").removeClass();
            $("#check_email").addClass('approved_field');

            setError("");
            email_available=1;
            setLogin();
            }
       }
 });

}

//check username
function check_username_ajax(username)
{
    $.post('../requests/username_checker.php', {'username':username},
    function(data)
    {
    var result=$.trim(data);

    if (result == "0") //Username is not available
    {
        //Username taken
        $("#check_username").removeClass();
        $("#check_username").addClass('wrong_field');

        setError("Username is not available");
        user_name_available=0;
        setLogin();
    }
    else
        if (result == "1") //Username is available
        {
            //Username format is invalid
            if (username.length<=0 || /[\W]/.test(username)==true)
            {
                $("#check_username").removeClass();
                $("#check_username").addClass('wrong_field');

                setError("Username has an improper format");
                user_name_available=0;
                setLogin();
            }
            else
                {
                    //Everything is fine
                    $("#check_username").removeClass();
                    $("#check_username").addClass('approved_field');
                    setError("");
                    user_name_available=1;
                    setLogin();
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
     if (err=="")
     {
        jQuery('#error_box').animate({ top: '-23px'}, 200, 'swing');
     }
     else
     {
        document.getElementById("error_box").innerHTML=err;
        jQuery('#error_box').animate({ top: '0px'}, 200, 'swing');
     }
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
        document.getElementById('next_location').style.backgroundColor="#ffa25e";
        document.getElementById('next_location').disabled=false;
    }
    else
    {
        login=false;
        document.getElementById('next_location').disabled=true;
        document.getElementById('next_location').style.backgroundColor="#babdc6";
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
//
var city_length=1;
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
        document.getElementById("next_more").disabled=false;
        document.getElementById("next_more").style.backgroundColor="#ffa25e";
    }
    else
        {
        //Disable button
        document.getElementById("next_more").disabled=true;
        document.getElementById("next_more").style.backgroundColor="#babdc6";
        }
}





function showSchedule()
{
  if (document.getElementById('always_open').checked)
    {
        $("#schedule_show").hide();
    }
  else
    {
       $("#schedule_show").show();
    }
}

function hideSchedulePart(week)
{
    var elements=document.getElementsByClassName(week);

    for (var i=0; i<elements.length; i++)
    {
        if (document.getElementById(week+"_open").checked)
        {
            elements[i].style.display="block";
        }
        else
        {
            elements[i].style.display="none";
        }
    }


}


function showTable(tableId)
{
document.getElementById("sign_up_table").style.display="none";
document.getElementById("sign_up_location_table").style.display="none";
document.getElementById("sign_up_more_table").style.display="none";
document.getElementById("sign_up_picture_table").style.display="none";

document.getElementById(tableId).style.display="table";
}








//Upload image+++
$(function ()
{
    $(":file").change(function()
    {
        if (this.files && this.files[0])
        {
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL(this.files[0]);
        }
    });
});

var $superParent;

function imageIsLoaded(e)
{
    $("#opaque_background").show();
    $("#modify_image").show();

    $("body").cleanAll("#uploaded_image");

    $("#uploaded_image").attr("src", e.target.result);
    $("#uploaded_image").imageCrop("290","290");

    $("#apply_cropping").applyCrop("#crop_info","#final_image");

    $("#cancel_image").click(function(e)
        {
            $("#opaque_background").hide();
            $("#modify_image").hide();
            $("#final_image").width("0px").height("0px");

            var control = $("#imageToUpload");
            control.replaceWith(control=control.clone(true));

        });
};
 //Upload image---
