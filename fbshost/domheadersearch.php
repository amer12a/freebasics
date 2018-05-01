<pre>
<?php
/**
 * Created by PhpStorm.
 * User: MITU
 * Date: 3/15/2018
 * Time: 2:53 PM
 */

$foldername = 'header';

$files = scandir($foldername);

foreach($files as $file)
{
  $filename = $foldername . '/' . $file;
    $handle = fopen($filename,'r');
    @$content = fread($handle,filesize($filename));
    $posx = strpos($content,'1.1 203');
    if($posx>0)
    {
        echo "'".$file . "',\n";
    }



}



?>
