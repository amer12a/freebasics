<pre>
<?php
set_time_limit(0);
/**
 * Created by PhpStorm.
 * User: MITU
 * Date: 3/13/2018
 * Time: 5:40 PM
 */
 $folder_name = "header";

if ($handle = opendir($folder_name)) {

    while (false !== ($entry = readdir($handle))) {

        if ($entry != "." && $entry != "..") {
			
			$file_name = $folder_name . '/' . $entry;
		//	unlink($file_name);
        }
    }

    closedir($handle);
}

 
 
// array_map('unlink', glob("$foder_name/*")); // delete all files in folder 
 
$sitelist = array(
   
);



function func_get_content($myurl)
{
	$host = parse_url($myurl, PHP_URL_HOST);
    $ch = curl_init();
	$agents = ['tab mobile','opera mini android','google chrome','uc browser','mozilla firefox 70'];
    $agent = $agents[1];
    curl_setopt($ch, CURLOPT_URL, $myurl);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
      curl_setopt($ch, CURLOPT_HEADER, true); // header
    curl_setopt($ch,CURLOPT_NOBODY,true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       // "Host: $host",
      //  "Cookie: ",
        "Accept-Language: en-US,en;q=0.5",
      //  "DNT: 1",
        "Connection: keep-alive",
      //  "Accept-Encoding: gzip, deflate"
        )
    );
    curl_setopt($ch,CURLOPT_TIMEOUT,30);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $error = curl_error($ch);
    echo $error;
    $result = curl_exec($ch);
    curl_close($ch);
    sleep(1);
    return  $result;
}
$i = 0;

foreach($sitelist as $site)
{


    echo "'".$site . "',\n";

    if(is_file('header/'.$site))
    {

    }else{
        $handle = fopen("$folder_name/".$site,'w+');
        $respose = func_get_content('http://' . $site);
        fwrite($handle,$respose);
        fclose($handle);
    }

}

echo "\n--------- all complete ";