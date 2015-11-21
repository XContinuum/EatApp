<?php
    if (isset($_POST["save_menu_btn"]))
    {
        require ("server_connection.php");

        $product=array();
        $price=array();
        $desc=array();
        $contents=array();
        $sections=explode('.', $_POST["FA_Sections_order"]);
        $section_name=array();

        $final_query = "";

        //ASSEMBLE SECTIONS INTO AN ARRAY
        array_pop($sections);

        for($k=0;$k<count($sections);$k++)
        {
            $section_name[]=$_POST["Section_".$k];
        }

        //Get restaurant ID
        session_start();
        $sql = "SELECT ID FROM FA_RESTORANTS WHERE FA_Token='".$_SESSION['token']."'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
        //----

        for ($i=0;$i<$_POST["Row_Count"];$i++)
        {
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


            $final_query.="('".$row['ID']."','".($i+1)."','".$product[$i]."','".$price[$i]."','".$desc[$i]."','".$str."','".$section."')";
        }

        $sql="INSERT INTO FA_MENUS (RESTAURANT_ID,FA_Order,FA_Product_Name,FA_Price,FA_Desc,FA_Contents,FA_Section) VALUES ".$final_query;

        echo $final_query;
        mysqli_query($conn,$sql);

        echo "<script> location.replace('../user/setup_menu.php'); </script>"; //MOD 2017

        mysqli_close($conn);
    }
?>
