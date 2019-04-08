<?php
/**
 * Created by PhpStorm.
 * User: MITU
 * Date: 10/23/2017
 * Time: 3:38 PM
 */
 set_time_limit(0);
 
$hostlist2 = array(
);
$hostlist = array();

foreach($hostlist2 as $hosts)
{
    $pos = strrpos($hosts,'.');
    $hosts = substr($hosts,0,$pos);
    $hostlist[] = $hosts;
// echo $hosts . '<br/>';

}

function func_get_content($myurl,$posts,$headers,$referer)
{
    $ch = curl_init();
    $agent = 'tab mobile';
    curl_setopt($ch, CURLOPT_URL, $myurl);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    //  curl_setopt($ch, CURLOPT_HEADER, true); // header
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch,CURLOPT_TIMEOUT,60);
    # sending manually set cookie
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: test=cookie"));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($posts));
// in real life you should use something like:
// curl_setopt($ch, CURLOPT_POSTFIELDS,
//          http_build_query(array('postvar1' => 'value1')));
    //  curl_setopt ($ch, CURLOPT_PORT , 8089);
    $error = curl_error($ch);
    echo $error;
    $result = curl_exec($ch);
    curl_close($ch);

    return  $result;
}

$myheaders = array(
    //  "Cookie: _ga=GA1.2.1467218890.1502439443; __utma=76711234.1467218890.1502439443.1508388218.1508475532.14; __utmz=76711234.1508388218.13.13.utmcsr=freenom.com|utmccn=(referral)|utmcmd=referral|utmcct=/en/index.html; fp_token_7c6a6574-f011-4c9a-abdd-9894a102ccef=\"oXrdmI3NtujK/3Hh9OpLw/pIpjc6zdh8BdSNcsEPOmo=\"; __zlcmid=icglOA01PWVMjp; mydottk_languagenr=0; dottyLn=en; wwwLn=en; WHMCSZH5eHTGhfvzP=6vrvsmc5ddbo7qcpeiam73g3g6; AWSELB=BB755F330E44FE27E970EAECFCC78F629EB1F82E68C017F5DB0928A2C28B92661A762BEECAE34AC7DDF253585D31FBE854D4DE549B7F4D5B8D5E2FC611B6C9BC5592522CA6",
    "Host: my.freenom.com",
    "User-Agent: Mozilla/5.0 (Linux; Android 7.0; PLUS Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko)",
    "Accept: */*",
    "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
    "Origin: http://www.freenom.com",
    "Connection: keep-alive",
    "Cookie: mydottk_languagenr=0; dottyLn=en; wwwLn=en; WHMCSZH5eHTGhfvzP=phnq8qjrmfel4kaap2h83imf90; AWSELB=BB755F330E44FE27E970EAECFCC78F629EB1F82E68734E4AB95DE8A0F941A50818B34F03586F13770100F9C73722798C3D57C651D372F6E3F013AAB976C41977BA4D0359C4; _ga=GA1.2.445211936.1545740282; _gid=GA1.2.1055976574.1545740282"
);

$mypost = array(
    "domain" => "roktim.ml",
    "tld" => ""

);
echo "
<table>
    <tbody align=\"center\">
    <tr>
        <th>Domain </th> <th>.EXT</th><th> Status </th>
    </tr>";


$testdomain = [];

$myresult = '';
if(isset($_REQUEST['domain']))
{
    $domain = $_REQUEST['domain'];

    $mypost['domain'] = $domain;
    $myresult = func_get_content('https://my.freenom.com/includes/domains/fn-available.php',$mypost,$myheaders,'http://www.freenom.com/en/index.html?lang=en');

}else{
    foreach($hostlist as $hosts)
    {



        $domain = $hosts;
        $mypost['domain'] = $domain;
        $filenamex = 'domaininfo/'.$hosts;
        $myresult = '';

        if(is_file($filenamex))
        {

          $myresult =   file_get_contents($filenamex);

        }else{
		$handle = fopen($filenamex,'w+');
        $myresult = func_get_content('https://my.freenom.com/includes/domains/fn-available.php',$mypost,$myheaders,'http://www.freenom.com/en/index.html?lang=en');
        fwrite($handle,$myresult);
       fclose($handle);
		}

       

        $js = json_decode($myresult,true);
        if($js['status']=='OK') {
            $bedel = 0;
            for ($i = 0; $i < 5; $i++) {
                $jsx = $js['free_domains'][$i];
                $clr = "red";
                $xclr = 0;
                if ($jsx['status'] == "AVAILABLE") {
                   $bedel = 1;
                    //  if ($jsx['type'] == "FREE") {
                    $clr = "green";
                    if(($jsx['price_cent'] > 0) or ($jsx['type'] == 'SPECIAL'))
                    {
                        $clr = 'red';
                    }
                    $xclr = 1;
                }
                if($clr == 'green') {

                    foreach ($hostlist2 as $hostl) {
                        if (strtolower($jsx['domain'] . $jsx['tld']) == strtolower($hostl)) {
                            $clr = 'blue';
                        }

                    }

                }else{
                    foreach ($hostlist2 as $hostl) {
                        if (strtolower($jsx['domain'] . $jsx['tld']) == strtolower($hostl)) {
                            $clr = 'pink';

                        }

                    }
                }

                if($clr == 'pink' and $xclr == 1)
                {
                    $testdomain[] = $hosts;
                    unlink($filenamex);
                }
                echo "<tr style='background-color: {$clr};color: white'>";
                echo "<td>{$jsx['domain']}</td><td>{$jsx['tld']}</td><td>{$jsx['status']}</td>";
                echo "</tr>";
            }

        }
    }

    echo " </tbody>
</table>";
}

print_r($testdomain);
?>