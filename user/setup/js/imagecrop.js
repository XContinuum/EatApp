(function($)
{
    var p;
    var scale;
    var currentW,currentH;
    var winW,winH;
    var $superParent;

    var X1,Y1;
    var picture;

    function setup(object,w,h)
    {
        var $image=$(object);
        var originalH=$image.height();
        var originalW=$image.width();
        $superParent=$image.parent();

        winW=w;
        winH=h;

        //Resize and hide image
        $image.css({height:h+"px",display:"none"});
        var image_w=$image.width(); //image width after resizing it

        currentW=image_w;
        currentH=h;

        var relX=(w-image_w)/2;

        var $container=$("<div id='container' class='imageCrop' />").css({
            position : "relative",
            background:"url(" + $image.attr("src") + ") no-repeat",
            backgroundSize:image_w+"px "+h+"px",
            "background-position":relX+"px 0px"
        })
        .width(w)
        .height(h);

        //Wrap the container around the image
        $image.wrap($container).css({ position : "absolute" ,left:"0px", right:"0px" });



        var $opaque_cover=$("<div class='imageCrop'/>").css({
            position : "absolute",
            left:"0px",
            right:"0px",
            backgroundColor:"white",
            opacity:0.75
        })
        .width(w)
        .height(h).insertAfter($image);


        //Create visible circle
        var radius=((w-20)/2);
        relX=((w-20)-image_w)/2;

        var $circle=$("<div id='circle_pic' class='imageCrop'/>")
        .css({
            position : "absolute",
            top: "10px",
            left: "10px",
            "border-radius": radius+"px",
            background : "url(" + $image.attr("src") + ") no-repeat",
            backgroundSize : image_w+"px "+h+"px",
            "background-position":relX+"px -10px"
        })
        .width(w-20)
        .height(h-20)
        .insertAfter($opaque_cover);

        X1=(-1)*relX;
        Y1=10;


        setSlider(w,h,originalH,originalW);
        movePicture();
    }

    function setSlider(w,h,originalH,originalW)
    {
        //Slider+++++++
        var barWidth=w;

        var slider="<div class='imageCrop' style='position:absolute;background-color:#e0e7ec;width:"+barWidth+"px;height:6px;top:50%;margin-top:-3px;left:0px;border:1px solid #d5dbe0;border-radius: 6px;'></div>";
        slider+="<div class='imageCrop' id='slider_circle' style='cursor:move;position:absolute;left:10px;top:50%;margin-top:-10px;border-radius:50%;width:20px;height:20px;background-color:#3a4045;'></div>";
        slider+="<div class='imageCrop' id='percentage'></div>";

        $("<div class='imageCrop' id='sld_con' />").html(slider).css(
        {
        "position": "relative",
        padding: "0px",
        "margin-top": "15px"
        })
        .width(barWidth)
        .height("50px")
        .insertAfter("#container");

        var msdown=false;

        $("#sld_con").on("mousedown", "#slider_circle", function()
        {
        msdown=true;
        });

        $("#sld_con").on("mousemove", function(e)
        {
            if (msdown==true)
            {
                var slider_radius=$("#slider_circle").width()/2;
                var relativeX=e.pageX-$("#sld_con").offset().left+slider_radius;
                var maxX=parseInt(w)+parseInt(slider_radius);

                if((relativeX>=slider_radius) && (relativeX<=maxX))
                {
                    $("#slider_circle").offset(
                    {
                        left:e.pageX-slider_radius
                    });


                    //get the percentage displacement of the slider
                    p=(($("#slider_circle").offset().left-$("#sld_con").offset().left+slider_radius)/w)*100;

                    zoomIn(p,w,h,originalW,originalH); //zooming in
                }
            }
        });

        $("#sld_con").mouseup(function()
        {
            msdown=false;
        })
        .mouseleave(function()
        {
            msdown=false;
        });
    }

    function zoomIn(percentage,w,h,originalW,originalH)
    {
        var minPercentage=(winH/originalH)*100; //minimum

        if(minPercentage<100)
        {
        percentage=minPercentage+(100-minPercentage)*(percentage/100); //min: minPercentage and max:100%, where 0%<minPercentage<100%
        scale=percentage;
        percentage=percentage.toFixed(0);
        //$("#percentage").html(percentage.toFixed(2)+"%");

        currentH=(originalH*percentage)/100;
        currentW=currentH*(originalW/originalH);

        var relX=(w-currentW)*0.5;//PosSquare[0]-dW/2;
        var relY=(h-currentH)*0.5;//PosSquare[1]-dH/2;

        $("#container").css({
            backgroundSize:currentW+"px "+currentH+"px",
            "background-position":relX+"px "+ relY+"px"
        });

        relX=($("#circle_pic").width()-currentW)*0.5;//PosCircle[0]-dW/2;
        relY=($("#circle_pic").height()-currentH)*0.5;//PosCircle[1]-dH/2;

        X1=(-1)*relX;
        Y1=(-1)*relY;

        $("#circle_pic").css({
            backgroundSize : currentW+"px "+currentH+"px",
            "background-position":relX+"px "+ relY+"px"
         });
        }
    }

    function movePicture()
    {
        var move_pic=true;
        var startX,startY;
        var backgroundPos;

        var PosCircle=[], PosSquare=[];

        $("#circle_pic").mousedown(
        function(e)
        {
            move_pic=true;

            startX=e.pageX;
            startY=e.pageY;

            backgroundPos=$("#circle_pic").css('background-position').split(" ");
            PosCircle[0]=parseInt(backgroundPos[0],10);
            PosCircle[1]=parseInt(backgroundPos[1],10);

            backgroundPos=$("#container").css('background-position').split(" ");
            PosSquare[0]=parseInt(backgroundPos[0],10);
            PosSquare[1]=parseInt(backgroundPos[1],10);
        })
        .mouseup(
        function()
        {
            move_pic=false;
        })
        .mousemove(
        function(e)
        {
            if (move_pic==true)
            {
                var dX=e.pageX-startX;
                var dY=e.pageY-startY;

                var a=(PosCircle[0]+dX);
                var b=(PosCircle[1]+dY);
                var c=(PosSquare[0]+dX);
                var d=(PosSquare[1]+dY);

                if(c>winW-currentW && c<0 && d<0 && d>winH-currentH)
                {
                    $("#circle_pic").css({
                    "background-position":a+"px "+b+"px"
                    });

                    $("#container").css({
                    "background-position":c+"px "+d+"px"
                    });

                    X1=(-1)*a;
                    Y1=(-1)*b;
                    //$("#percentage").html("x:"+a+" y:"+b+" "+currentW+":"+currentH); //display
                    //$("#crop_info").val((-1*a)+":"+(-1*b)+":"+(winW-20)+":"+(winH-20)+":"+currentW+":"+currentH);
                }
            }
        })
        .mouseleave(function()
        {
            move_pic=false;
        });
    }

    $.fn.cleanAll = function(img)
    {
        p=0;
        scale=0;
        currentW=0;
        currentH=0;
        winW=0;
        winH=0;

        $(".imageCrop").remove();
        $("#crop_info").val("0:0:0:0:0:0");

        if(!$(img).length)
        {
            $superParent.html("<img id='uploaded_image' src='#'/>");
        }

    };

    $.fn.applyCrop=function(tag,_final,wFinal,hFinal)
    {
        $(this).unbind();

        $(this).click(
        function(e)
        {
            $(tag).val(X1+":"+Y1+":"+(winW-20)+":"+(winH-20)+":"+currentW+":"+currentH);

        if ($(_final).is(":visible"))
        {
            var X2=X1*(wFinal/winW);
            var Y2=Y1*(hFinal/winH);

            var W2=currentW*(wFinal/winW);
            var H2=currentH*(hFinal/winH);

            var $overlay=$("<div />").css({
                background:"url(" + picture + ") no-repeat",
                "background-position": (-X2)+"px "+(-Y2)+"px",
                backgroundSize: W2+"px "+H2+"px",
                "border-radius": (wFinal/2)+"px"
            })
            .width(wFinal)
            .height(hFinal);

            $(_final).wrap($overlay);
            $(_final).hide();
        }
        else
        {
            var X2=X1*(wFinal/winW);
            var Y2=Y1*(hFinal/winH);

            var W2=currentW*(wFinal/winW);
            var H2=currentH*(hFinal/winH);

            $(_final).parent().css({
                background:"url(" + picture + ") no-repeat",
                "background-position": (-X2)+"px "+(-Y2)+"px",
                backgroundSize: W2+"px "+H2+"px",
                "border-radius": (wFinal/2)+"px",
                "border":"1px solid #ccc"
            })
            .width(wFinal)
            .height(hFinal);
        }

        $("#opaque_background").hide();
        $("#modify_image").hide();
        }
        );
    };

    $.fn.imageCrop = function(width,height)
    {
        //Iterate over each object
        this.each(function()
        {
            var currentObject=this;
            var image=new Image();

            //And attach imageCrop when the object is loaded
            image.onload = function()
            {
                setup(currentObject,width,height);
            };

            //Reset the src because cached images don't fire load sometimes
            image.src = currentObject.src;
            picture=currentObject.src;
        });

        return this;
    };

}) (jQuery);
