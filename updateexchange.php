<?php
class Rate{
var $source;
var $mydate;

function getXML(){
return file_get_contents($this->source);
}
function getRate(){
$xmlData = NULL;
$p = xml_parser_create();
xml_parse_into_struct($p,$this->getXML() , $xmlData);
xml_parser_free($p);
$this->mydate = $xmlData['1']['value'];
$data = array();
if($xmlData){
foreach($xmlData as $v)
if(isset($v['attributes']))
{
$data[] = $v['attributes'];

}
return $data;
}
return false;
}
function show(){
$data = $this->getRate();
$connection = mysqli_connect("localhost", "root", "trinhvandieu", "freemartket_9cd9") or die("Error " . mysqli_error($connection));
$connection->set_charset('utf8');

foreach($data as $k=>$v){
	$code = $v['CURRENCYCODE'];
	$name = $v['CURRENCYNAME'];
	$buy= $v['BUY'];
	$transfer = $v['TRANSFER'];
	$sell = $v['SELL'];
	
     $sql = "INSERT INTO currency(currencycode, currencyname,buy,transfer,sell) VALUES('$code','$name', ' $buy', '$transfer','$sell')  ON DUPLICATE KEY UPDATE    
currencycode=$code, currencyname=$name,buy=$buy,transfer=$transfer,sell=$sell";

        $query = $connection->query($sql);

	

}
  
}
}

$rate = new Rate();
$rate->source = 'http://www.vietcombank.com.vn/ExchangeRates/ExrateXML.aspx';
$rate->show();
?>
