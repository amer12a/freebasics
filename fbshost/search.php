<?php
/**
 * Created by PhpStorm.
 * User: MITU
 * Date: 12/15/2017
 * Time: 3:16 PM
 */

$folder = 'data';
$q = 'domain.dot.tk';

if (isset($_REQUEST['q']))
{
    $q = $_REQUEST['q'];
}
$files = scandir($folder);

foreach ($files as $fil)
{
    $filename = $folder.'/'.$fil;
    if(file_exists($filename))
    {
     $handle = @fopen($filename,'r');
        $content = @fread($handle,filesize($filename));
        $pos = strpos($content,$q);
        if ($pos > 10)
        {

            echo '<br/>' . substr($content,$pos,100);
        }

    }
}



?>

<html>
<body>
<form action="search.php">
    <input type="text" name="q">
    <input type="submit">
</form>
</body>
</html>
