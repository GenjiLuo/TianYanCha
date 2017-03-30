<?php
/**
 * Created by PhpStorm.
 * User: darkwind
 * Date: 17-3-30
 * Time: 上午11:10
 */

require_once 'vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

$id = intval($_GET['id']);
//$id=2328474532;

$client = new Client(['cookies' => true]);

$head = getHead($id);
//$sgattrs=getSgAtt($client,$head,$id);
//$js=getJs($client,$head,$id);
//$utm=getUtm($sgattrs[0],$js['fxck']);

$temp = $client->request('GET', 'http://www.tianyancha.com/expanse/staff.json?id=' . $id . '&ps=20&pn=1', [
    'headers' => $head
]);
$staff = $temp->getBody()->getContents();//高管信息

$temp = $client->request('GET', 'http://www.tianyancha.com/expanse/holder.json?id=' . $id . '&ps=20&pn=1', [
    'headers' => $head
]);
$investment = $temp->getBody()->getContents();//股东信息

$temp = $client->request('GET', 'http://www.tianyancha.com/expanse/inverst.json?id=' . $id . '&ps=20&pn=1', [
    'headers' => $head
]);
$outInvestment = $temp->getBody()->getContents();//对外投资

$temp = $client->request('GET', 'http://www.tianyancha.com/expanse/branch.json?id=' . $id . '&ps=10&pn=1', [
    'headers' => $head
]);
$branch = $temp->getBody()->getContents();//分支机构

//$temp=$client->request('GET', 'http://www.tianyancha.com/v2/getlawsuit/%E5%8C%97%E4%BA%AC%E5%A4%A9%E9%94%90%E9%A9%B0%E8%83%BD%E6%BA%90%E7%A7%91%E6%8A%80%E6%9C%89%E9%99%90%E5%85%AC%E5%8F%B8.json?page=1&ps=10');
//$lawSuit=$temp->getBody()->getContents();//法律诉讼


//$temp=$client->request('GET', 'http://www.tianyancha.com/expanse/punishment.json?name=%E5%8C%97%E4%BA%AC%E5%A4%A9%E9%94%90%E9%A9%B0%E8%83%BD%E6%BA%90%E7%A7%91%E6%8A%80%E6%9C%89%E9%99%90%E5%85%AC%E5%8F%B8&ps=20&pn=1');
//$punishment=$temp->getBody()->getContents();//行政处罚

$temp = $client->request('GET', 'http://www.tianyancha.com/expanse/taxcredit.json?id=' . $id . '&ps=20&pn=1', [
    'headers' => $head
]);
$taxcredit = $temp->getBody()->getContents();//税务评级

//$temp=$client->request('GET', 'http://www.tianyancha.com/expanse/staff.json?id='.$id.'&ps=20&pn=1');
//$court=$temp->getBody()->getContents();//法院公告
$temp = $client->request('GET', 'http://www.tianyancha.com/expanse/bid.json?id=' . $id . '&pn=1&ps=10', [
    'headers' => $head
]);
$bid = $temp->getBody()->getContents();//招投标

//$temp=$client->request('GET', 'http://www.tianyancha.com/expanse/companyEquity.json?name=%E5%9B%9B%E5%B7%9D%E9%87%91%E5%88%A9%E9%80%9A%E4%BF%A1%E7%BD%91%E7%BB%9C%E6%9C%8D%E5%8A%A1%E6%9C%89%E9%99%90%E5%85%AC%E5%8F%B8&ps=5&pn=1');
//$bond=$temp->getBody()->getContents();//债券信息

$temp = $client->request('GET', 'http://www.tianyancha.com/expanse/changeinfo.json?id=' . $id . '&ps=20&pn=1', [
    'headers' => $head
]);
$changeInfo = $temp->getBody()->getContents();//变更信息

$report = yearReport($id);//企业年报
//$temp=$client->request('GET', 'http://www.tianyancha.com/expanse/staff.json?id='.$id.'&ps=20&pn=1');
//$abnoInfo=$temp->getBody()->getContents();//经营异常
//$temp=$client->request('GET', 'http://www.tianyancha.com/v2/dishonest/%E5%8C%97%E4%BA%AC%E5%A4%A9%E9%94%90%E9%A9%B0%E8%83%BD%E6%BA%90%E7%A7%91%E6%8A%80%E6%9C%89%E9%99%90%E5%85%AC%E5%8F%B8.json');
//$dishonest=$temp->getBody()->getContents();//失信人
$temp = $client->request('GET', 'http://www.tianyancha.com/tm/getTmList.json?id=' . $id . '&pageNum=1&ps=20', [
    'headers' => $head
]);
$brand = $temp->getBody()->getContents();//商标信息

$temp = $client->request('GET', 'http://www.tianyancha.com/v2/IcpList/' . $id . '.json');
$icp = $temp->getBody()->getContents();//网站备案
//$temp=$client->request('GET', 'http://www.tianyancha.com/expanse/staff.json?id='.$id.'&ps=20&pn=1');
//$patent=$temp->getBody()->getContents();//专利

$temp = $client->request('GET', 'http://www.tianyancha.com/expanse/copyReg.json?id=' . $id . '&pn=1&ps=5', [
    'headers' => $head
]);
$copyright = $temp->getBody()->getContents();//著作权
//$temp=$client->request('GET', 'http://www.tianyancha.com/extend/getEmploymentList.json?companyName=%E7%BB%B5%E9%98%B3%E9%92%BB%E7%BB%B4%E9%80%9A%E4%BF%A1%E6%8A%80%E6%9C%AF%E6%9C%8D%E5%8A%A1%E6%9C%89%E9%99%90%E8%B4%A3%E4%BB%BB%E5%85%AC%E5%8F%B8&pn=1&ps=10');
//$employe=$temp->getBody()->getContents();//招聘
$data = [
    'staff' => json_decode($staff, true)['data'],
    'investment' => json_decode($investment, true)['data'],
    'outInvestment' => json_decode($outInvestment, true)['data'],
    'branch' => json_decode($branch, true)['data'],
    'taxcredit' => json_decode($taxcredit, true)['data'],
    'bid' => json_decode($bid, true)['data'],
    'changeInfo' => json_decode($changeInfo, true)['data'],
    'brand' => json_decode($branch, true)['data'],
    'icp' => json_decode($icp, true)['data'],
    'copyright' => json_decode($copyright, true)['data'],
];

echo json_encode($data);


function getHead($id)
{
    //随机head
    $USER_AGENTS = [
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; AcooBrowser; .NET CLR 1.1.4322; .NET CLR 2.0.50727)",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; Acoo Browser; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.0.04506)",
        "Mozilla/4.0 (compatible; MSIE 7.0; AOL 9.5; AOLBuild 4337.35; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)",
        "Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 9.0; en-US)",
        "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET CLR 2.0.50727; Media Center PC 6.0)",
        "Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; WOW64; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET CLR 1.0.3705; .NET CLR 1.1.4322)",
        "Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 5.2; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.2; .NET CLR 3.0.04506.30)",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN) AppleWebKit/523.15 (KHTML, like Gecko, Safari/419.3) Arora/0.3 (Change: 287 c9dfb30)",
        "Mozilla/5.0 (X11; U; Linux; en-US) AppleWebKit/527+ (KHTML, like Gecko, Safari/419.3) Arora/0.6",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.2pre) Gecko/20070215 K-Ninja/2.1.1",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9) Gecko/20080705 Firefox/3.0 Kapiko/3.0",
        "Mozilla/5.0 (X11; Linux i686; U;) Gecko/20070322 Kazehakase/0.4.5",
        "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.8) Gecko Fedora/1.9.0.8-1.fc10 Kazehakase/0.5.6",
        "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/535.20 (KHTML, like Gecko) Chrome/19.0.1036.7 Safari/535.20",
        "Opera/9.80 (Macintosh; Intel Mac OS X 10.6.8; U; fr) Presto/2.9.168 Version/11.52",
        "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.11 TaoBrowser/2.0 Safari/536.11",
        "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.71 Safari/537.1 LBBROWSER",
        "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E; LBBROWSER)",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; QQDownload 732; .NET4.0C; .NET4.0E; LBBROWSER)",
        "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.84 Safari/535.11 LBBROWSER",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E)",
        "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E; QQBrowser/7.0.3698.400)",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; QQDownload 732; .NET4.0C; .NET4.0E)",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; SV1; QQDownload 732; .NET4.0C; .NET4.0E; 360SE)",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; QQDownload 732; .NET4.0C; .NET4.0E)",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E)",
        "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1",
        "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1",
        "Mozilla/5.0 (iPad; U; CPU OS 4_2_1 like Mac OS X; zh-cn) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8C148 Safari/6533.18.5",
        "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:2.0b13pre) Gecko/20110307 Firefox/4.0b13pre",
        "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:16.0) Gecko/20100101 Firefox/16.0",
        "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11",
        "Mozilla/5.0 (X11; U; Linux x86_64; zh-CN; rv:1.9.2.10) Gecko/20100922 Ubuntu/10.10 (maverick) Firefox/3.6.10"
    ];
    $random = rand(0, count($USER_AGENTS) - 1);
    $heads = [
        'User-Agent' => $random,
        "Tyc-From" => "normal",
        'Accept' => 'application/json, text/plain, */*',
        'CheckError' => 'check',
        'Referer' => 'http://www.tianyancha.com/company/' . $id,
    ];
    return $heads;
}

function getJs($client, $head, $id)
{
    $tongji_url = "http://www.tianyancha.com/tongji/2328474532.json?random=" . time() * 1000;
    $tongji_page = $client->get($tongji_url, [
        'headers' => $head
    ]);
    $temp = $tongji_page->getBody()->getContents();
    $data = json_decode($temp, true);
    $v = $data['data']['v'];

    $js_code = '';
    foreach (explode(',', $v) as $datum) {
        $js_code .= chr($datum);
    }

    preg_match('/token=(\w+);/', $js_code, $temp);
    $token = trim(strstr($temp[0], '='), '=,;');

    preg_match('/\'([\d\,]+)\'/', $js_code, $temp);
    $fxck_chars = explode(',', $temp[1]);

    return [
        'v' => $v,
        'token' => $token,
        'fxck' => $fxck_chars
    ];
}

function getSgAtt($client, $head, $id)
{
//    $iframe_page = $client->get('http://dis.tianyancha.com/dis/old#/show?ids=2328474532&cnz=true',[
//        'headers' => $head
//    ]);
//    $temp=$iframe_page->getBody()->getContents();
//    preg_match('/http.+?c\.tianyancha\.com\/vr\/resources\/js\/\w+.js/',$temp,$js_url);
//    $js_page = $client->get($js_url[0],[
//        'headers' => [
//            'User-Agent' => 'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36',
//            'Accept' => 'application/json, text/plain, */*',
//            'Referer' => 'http://www.tianyancha.com/company/2328474532',
//            'CheckError' => 'check'
//        ]
//    ]);
//    $js_content=$js_page->getBody()->getContents();
//    preg_match('/n\._sgArr=(.+?);/', $js_content, $temp);
//    $sgattrs = $temp[1];
    $sgattrs = [["6", "b", "t", "f", "2", "z", "l", "5", "w", "h", "q", "i", "s", "e", "c", "p", "m", "u", "9", "8", "y", "k", "j", "r", "x", "n", "-", "0", "3", "4", "d", "1", "a", "o", "7", "v", "g"], ["1", "8", "o", "s", "z", "u", "n", "v", "m", "b", "9", "f", "d", "7", "h", "c", "p", "y", "2", "0", "3", "j", "-", "i", "l", "k", "t", "q", "4", "6", "r", "a", "w", "5", "e", "x", "g"], ["s", "6", "h", "0", "p", "g", "3", "n", "m", "y", "l", "d", "x", "e", "a", "k", "z", "u", "f", "4", "r", "b", "-", "7", "o", "c", "i", "8", "v", "2", "1", "9", "q", "w", "t", "j", "5"], ["x", "7", "0", "d", "i", "g", "a", "c", "t", "h", "u", "p", "f", "6", "v", "e", "q", "4", "b", "5", "k", "w", "9", "s", "-", "j", "l", "y", "3", "o", "n", "z", "m", "2", "1", "r", "8"], ["z", "j", "3", "l", "1", "u", "s", "4", "5", "g", "c", "h", "7", "o", "t", "2", "k", "a", "-", "e", "x", "y", "b", "n", "8", "i", "6", "q", "p", "0", "d", "r", "v", "m", "w", "f", "9"], ["j", "h", "p", "x", "3", "d", "6", "5", "8", "k", "t", "l", "z", "b", "4", "n", "r", "v", "y", "m", "g", "a", "0", "1", "c", "9", "-", "2", "7", "q", "e", "w", "u", "s", "f", "o", "i"], ["8", "q", "-", "u", "d", "k", "7", "t", "z", "4", "x", "f", "v", "w", "p", "2", "e", "9", "o", "m", "5", "g", "1", "j", "i", "n", "6", "3", "r", "l", "b", "h", "y", "c", "a", "s", "0"], ["d", "4", "9", "m", "o", "i", "5", "k", "q", "n", "c", "s", "6", "b", "j", "y", "x", "l", "a", "v", "3", "t", "u", "h", "-", "r", "z", "2", "0", "7", "g", "p", "8", "f", "1", "w", "e"], ["7", "-", "g", "x", "6", "5", "n", "u", "q", "z", "w", "t", "m", "0", "h", "o", "y", "p", "i", "f", "k", "s", "9", "l", "r", "1", "2", "v", "4", "e", "8", "c", "b", "a", "d", "j", "3"], ["1", "t", "8", "z", "o", "f", "l", "5", "2", "y", "q", "9", "p", "g", "r", "x", "e", "s", "d", "4", "n", "b", "u", "a", "m", "c", "h", "j", "3", "v", "i", "0", "-", "w", "7", "k", "6"]];
    return $sgattrs;
}

function getUtm($sogou, $fxck)
{
    $utm = '';
    foreach ($fxck as $fxck_char) {
        $utm .= $sogou[intval($fxck_char)];
    }
    return $utm;
}

function yearReport($id)
{
    return '';
}

