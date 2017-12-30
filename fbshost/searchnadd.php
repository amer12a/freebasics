<?php
/**
 * Created by PhpStorm.
 * User: MITU
 * Date: 12/15/2017
 * Time: 2:13 AM
 */
set_time_limit(0);

// automaticly search and add all site

function func_get_content($myurl, $method, $posts, $headers, $referer)
{
    $ch = curl_init();
    $agent = "opera mini android";
    curl_setopt($ch, CURLOPT_URL, $myurl);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
   // curl_setopt($ch, CURLOPT_HEADER, true); // header
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch,CURLOPT_TIMEOUT , 15);
    $headers[] = "Host: 0.freebasics.com";
    $headers[] = "Cookie: datr=ZxQwWgSZ3awbokWv0Q6_p4v6;ick=zAPylozQDuZcNjkZWGgW4A;fbs_userid=1405952219525530";

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if ($method == 'post') {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($posts));
    }
    $error = curl_error($ch);
    echo $error;
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}
$post = [];
$headers = [];
// $myresutl =  func_get_content("https://0.freebasics.com/searchservices","get",$post,$headers,"https://0.freebasics.com/?appid=1");

// preg_match_all('/addservice\?service_id\=(\d+)/',$myresutl,$mathches);
echo "<pre>";
// print_r($mathches);

// addservice\?service_id\=\(d+)

// start loop
$keeprunning = true;

while($keeprunning == true)
{
    $rand = rand(0,50);
   // $myresutl =  func_get_content("https://0.freebasics.com/searchservices?offset={$rand}&next=%E0%A6%AA%E0%A6%B0%E0%A6%AC%E0%A6%B0%E0%A7%8D%E0%A6%A4%E0%A7%80","get",$post,$headers,"https://0.freebasics.com/?appid=1");
    $myresutl =  func_get_content("https://0.freebasics.com/searchservices","get",$post,$headers,"https://0.freebasics.com/?appid=1");

    preg_match_all('/addservice\?service_id\=(\d+)/',$myresutl,$mathches);
foreach($mathches[1] as $mtc)
{
    if (file_exists('data/'.$mtc))
    {
        echo $mtc . ' file exist';
        continue;
    }
  // start adding n keep storeing
    $postval = [
        "serviceid"=>"{$mtc}",
        "confirmed"=>"1",
        "userid"=>"1405952219525530",
        "submit"=>"যোগ করুন"
    ];
    $subresult =  func_get_content("https://0.freebasics.com/addservice?service_id={$mtc}&previous_url=/searchservices","post",$postval,$headers,"https://0.freebasics.com/addservice?service_id={$mtc}&amp;previous_url=%2Fsearchservices");
// save data
    $handle = fopen('data/'.$mtc,'w+');
    fwrite($handle,$subresult);
    fclose($handle);
}
echo "done";
    if (count($mathches[1]) < 10)
    {
        $keeprunning =false;

    }
}

