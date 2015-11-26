<?php
    if (isset($_POST["save_menu_btn"]))
    {
        require("server_connection.php");
        require("receive_information.php");

        $restaurant_id=get_restaurant_id();
        $res_username=get_restaurant_username();

        $product=array();
        $price=array();
        $desc=array();
        $contents=array();
        $sections=explode('.', $_POST["FA_Sections_order"]);
        $section_name=array();
        $currency=$_POST["currency"];

        $final_query = "";

        //ASSEMBLE SECTIONS INTO AN ARRAY
        array_pop($sections);

        for($k=0;$k<count($sections);$k++)
            $section_name[]=$_POST["Section_".($k+1)];


        for ($i=0;$i<$_POST["Row_Count"];$i++)
        {
            //Save images
            $pic_name="none";
            $pic_name=saveImage($_FILES['food_images_'.($i+1)]);

            if ($pic_name=="none")
            {
                $pic_name=$_POST["picture_url_".($i+1)];
            }
            else
            {
                //Delete picture
                if ($_POST["picture_url_".($i+1)]!="none")
                {
                    $old_file=$_POST["picture_url_".($i+1)];

                    if (file_exists("../restaurant_data/Pictures/$res_username/$old_file"))
                    {
                        unlink("../restaurant_data/Pictures/$res_username/$old_file");
                    }
                }
            }

            //Divide into sections+++
            $section="none";
            $stop=0;

            for ($j=count($sections)-1;$j>=0;$j--)
            {
                if ($i>($sections[$j]-2) && $stop==0)
                {
                   $section=$section_name[$j];
                   $stop=1;
                }
            }
            //Divide into sections---

            if ($i!=0)
                $final_query.=", ";

            array_push($product,$_POST["Product_name_" . ($i+1)]);
            array_push($price,$_POST["Price_" . ($i+1)]);
            array_push($desc,$_POST["Description_" . ($i+1)]);
            array_push($contents,$_POST["Food_Contents_" . ($i+1)]);

            $str="";

            foreach ($_POST["Food_Contents_" . ($i+1)] as $selectedOption)
            {
                $str.=$selectedOption.".";
            }


            $final_query.="('".$restaurant_id."','".($i+1)."','".$product[$i]."','".$price[$i]."','".$desc[$i]."','".$str."','".$section."','".$pic_name."')";
        }

        mysqli_query($conn,"DELETE FROM FA_MENUS WHERE RESTAURANT_ID=$restaurant_id"); //DELETE PREVIOUS MENU

        $sql="INSERT INTO FA_MENUS (RESTAURANT_ID,FA_Order,FA_Product_Name,FA_Price,FA_Desc,FA_Contents,FA_Section,FA_Pic) VALUES ".$final_query;

        //echo $final_query;
        mysqli_query($conn,$sql);
        mysqli_close($conn);

        header("Location: ../user/setup_menu.php?saved=1");
    }



    function saveImage($upload_image)
    {
        if(!empty($upload_image['tmp_name']))
         {
            $imagename = $upload_image['name']; //Stores the filename as it was on the client computer.
            $imagetype = $upload_image['type']; //Stores the filetype e.g image/jpeg
            $imageerror = $upload_image['error']; //Stores any error codes from the upload
            $imagetemp = $upload_image['tmp_name']; //Stores the tempname as it is given by the host when uploaded

            $ext = pathinfo($imagename, PATHINFO_EXTENSION);


            //Make directory+++
            $username=get_restaurant_username();
            $path="../restaurant_data/Pictures/".$username;

            if (!is_dir($path))
            {
                mkdir($path);
            }

            $path="../restaurant_data/Pictures/".$username."/";
            //Make directiry---


            //To avoid collisions+++
            do
            {
            $random_image_name=uniqid();
            }
            while(file_exists($path.$random_image_name.".".$ext));
            //To avoid collisions---

            if(is_uploaded_file($imagetemp))
            {
                if(move_uploaded_file($imagetemp, $path.$random_image_name.".".$ext))
                {
                    //echo "Sussecfully uploaded your image. ";
                    //echo $random_image_name.".".$ext."<br>";
                }
                else
                {
                    //echo "Failed to move your image.<br>";
                }
            }
            else
            {
                //echo "Failed to upload your image.<br>";
            }

            return $random_image_name.".".$ext;
        }
        else
        {
            return "none";
        }

    }
?>
