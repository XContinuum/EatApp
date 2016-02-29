var email_available=2;
var passwords_valid=2;
var user_name_available=2;
var agreement=0;
var restaurant_name_valid=2;
var login=false;

var enable_color="#ff9966";
var disable_color="#babdc6";

$(document).ready(function()
{
    var timer1;
    $("input[name='DB_Email']").keyup(function (e)
    {
        clearTimeout(timer1);
        var email = $(this).val();
        timer1 = setTimeout(function(){sendRequest(email,"Email");}, 1000);
    });

    var timer2;
    $("input[name='DB_Link_Name']").keyup(function (e)
    {
        clearTimeout(timer2);
        var link_name = $(this).val();
        timer2 = setTimeout(function(){sendRequest(link_name,"Link");}, 1000);
    });

    $("input[type='password']").keyup(function (e)
    {
        checkPasswords();
    });

    $("input[name='DB_Restaurant_Name']").keyup(function (e)
    {
        checkRestaurantName();
    });
});
/*

    ONLOAD EVENT+++++++++

*/

/*
    Checks email and link name
*/
function sendRequest(string,type)
{
    $.post('../requests/check_availability.php',{"input":string,"type":type},
    function(data)
    {
        var result=$.trim(data);

        if (type=="Link")
            analyze_link("input[name='DB_Link_Name']",result,string);
        else
            if (type=="Email")
                analyze_email("input[name='DB_Email']",result,string);
    });
}

function analyze_link(input,result,initial)
{
    var $link_input=$(input);

    switch (result)
    {
        case "0": //Linkname is not available
            $link_input.removeClass();
            $link_input.addClass('wrong_field');

            setError("Link is not available");
            user_name_available=0;
            setLogin();
        break;

        case "1": //Linkname is available

            if (initial.length<=0 || /[\W]/.test(initial)==true) //Linkname format is invalid
            {
                $link_input.removeClass();
                $link_input.addClass('wrong_field');

                setError("Link has an improper format");
                user_name_available=0;
                setLogin();
            }
            else
                {
                    //Everything is fine
                    $link_input.removeClass();
                    $link_input.addClass('approved_field');

                    setError("");
                    user_name_available=1;
                    setLogin();
                }
        break;
    }
}

function analyze_email(input,result,initial)
{
    $email_input=$(input);

    switch (result)
    {
        case "0": //Email not available
            $email_input.removeClass(); //remove all classes
            $email_input.addClass('wrong_field'); //add "wrong_field" layout

            setError("Email is not available"); //Show bar with  corresponding error

            email_available=0;
            setLogin(); //Disable button
        break;

        case "1":

            if (!isValidEmailAddress(initial)) //Email format is invalid
            {
                $email_input.removeClass();
                $email_input.addClass('wrong_field');

                setError("Email has an improper format");
                email_available=0;
                setLogin(); //Disable button
            }
            else
            {
                //Everything is fine
                $email_input.removeClass();
                $email_input.addClass('approved_field');

                setError("");
                email_available=1;
                setLogin(); //Disable button
            }
        break;
    }
}

/*
    Check email pattern
*/
function isValidEmailAddress(emailAddress)
{
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
};

function isURLValid(str)
{
  var pattern = new RegExp('^(https?:\/\/)?'+ // protocol
    '((([a-z\d]([a-z\d-]*[a-z\d])*)\.)+[a-z]{2,}|'+ // domain name
    '((\d{1,3}\.){3}\d{1,3}))'+ // OR ip (v4) address
    '(\:\d+)?(\/[-a-z\d%_.~+]*)*'+ // port and path
    '(\?[;&a-z\d%_.~+=-]*)?'+ // query string
    '(\#[-a-z\d_]*)?$','i'); // fragment locater

  if(!pattern.test(str))
  {
    return false; //invalid URL
  }
  else
    {
        return true;
    }
}

function checkPasswords()
{
    var pass1=$("input[name='DB_Password']").val();
    var pass2=$("input[name='DB_Confirm_Password']").val();

    if (pass1!="" && pass2!="")
    {
        //Check if passwords are equal
        if (pass1!=pass2)
        {
            passwords_valid=0;
            setError("Passwords are different!");
            setLogin();
        }
        else
        //Check password length
        if (pass1.length<6)
        {
            passwords_valid=0;
            setError("Password is too short");
            setLogin();
        }
        else
        //check if there are letters
        if (/[\W_]/.test(pass1) == true)
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
    var name=$("input[name='DB_Restaurant_Name']").val();

    if (name.length<1)
    {
        //Name is not long enough
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
    var $password=$("input[name='DB_Password']");
    var $confirm_password=$("input[name='DB_Confirm_Password']");

    if (passwords_valid==0)
    {
        $password.removeClass();
        $confirm_password.removeClass();

        $password.addClass("wrong_field");
        $confirm_password.addClass("wrong_field");
    }
    else
        if (passwords_valid==1)
        {
            $password.removeClass();
            $confirm_password.removeClass();

            $password.addClass("approved_field");
            $confirm_password.addClass("approved_field");
        }


    if (email_available==1 && passwords_valid==1 && user_name_available==1 && agreement==1 && restaurant_name_valid==1)
    {
        login=true;
        $('#sign_up').css("background-color",enable_color);
        document.getElementById('sign_up').disabled=false;
    }
    else
    {
        login=false;
        $('#sign_up').css("background-color",disable_color);
        document.getElementById('sign_up').disabled=true;
    }
}

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
