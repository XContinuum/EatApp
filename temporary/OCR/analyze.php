<?php
if (isset($_FILES['picture']))
{
	require_once("../requests/receive_information.php");

	$path = getenv('PATH');
	putenv("PATH=$path:/usr/local/bin");

	require_once 'tesseract-ocr-for-php/TesseractOCR/TesseractOCR.php';


	$tesseract = new TesseractOCR($_FILES['picture']['tmp_name']);
	$tesseract->setWhitelist(range('A','Z'),range('a','z'), range(0,9), '_-@.');
	$text=$tesseract->recognize();
	
  	$data=array(formatOuput($text),$text);

	echo implode("##",$data);
}

function formatOuput($input)
{
	$arr=str_split($input);

	$output="<tr><td style='background-color:#FF9966;color:white;' colspan='2' align='center'>Menu output</td></tr>";
	$output.="<tr><td>";
	$rn_array=array("0","1","2","3","4","5","6","7","8","9",".",",");
	$n=false;
	for ($i=0;$i<count($arr);$i++)
	{
		if (in_array($arr[$i],$rn_array))
		{	
			if ($n==false)
			{
				$output.="</td><td style='background-color:#f1aaa5;color:white;' class='price'>";
			}

			$output.=$arr[$i];
			$n=true;

		}
		else
			if ($n==true && $arr[$i]!=" ")
			{
				$output.="</td></tr><tr><td>";
				$output.=$arr[$i];
				$n=false;
			}
			else
			{
				$output.=$arr[$i];
			}
	}
	return "<table id='output_table'>".$output."</table>";
}

?>