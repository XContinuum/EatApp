<?php
    if (isset($_POST["save_menu_btn"]))
    {
        require ("server_connection.php");

        $product=array();
        $price=array();
        $desc=array();
        $contents=array();
        $sections=$_POST["FA_Sections"];
        $section_name=array();

        $final_query = "";

        session_start();

        $sql = "SELECT ID FROM FA_RESTORANTS WHERE FA_Token='".$_SESSION['token']."'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);

        //ASSEMBLE SECTIONS INTO AN ARRAY
        for ($i=0;$i<count($sections);$i++)
        {
          $section_name.push($_POST["Section_".$i]);
        }
        //---

        for ($i=0;$i<$_POST["Row_Count"];$i++)
        {
            //Divide into sections+++
            $section="none";

            for ($j=0;$j<count($sections);$j++)
            {
                if ($i<($sections[j]-1))
                {
                    $section=$section_name[j];
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

            echo $product[$i]." ".$price[$i]." ".$desc[$i]." ".$contents[$i][0];
            echo "<br>";

            $final_query.="('".$row['ID']."','".($i+1)."','".$product[$i]."','".$price[$i]."','".$desc[$i]."','".$str."','".$section."')";
        }

        $sql="INSERT INTO FA_MENUS (RESTAURANT_ID,FA_Order,FA_Product_Name,FA_Price,FA_Desc,FA_Contents,FA_Section) VALUES ".$final_query;
        mysqli_query($conn,$sql);

        echo "<script> location.replace('../setup_menu.php'); </script>";

        mysqli_close($conn);
    }
?>
