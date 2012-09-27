<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Entre First Search</title>

<style>

.twitter_thumb{
float:left;
margin-right:20px;
margin-bottom:0px;
}


body{
font-family:Verdana, Geneva, sans-serif;
font-size:14px;}

.user{
background-color:#efefef;
margin-bottom:10px;
border-bottom:;
padding:10px;}


.clear{
clear:both;
}

#search{
padding:8px;
background-color:#CCFFFF;
}


</style>

</head>

<body>


<div id="container">




<?php

function date_diff2($d1, $d2){
	$d1 = (is_string($d1) ? strtotime($d1) : $d1);
	$d2 = (is_string($d2) ? strtotime($d2) : $d2);

	$diff_secs = abs($d1 - $d2);
	$base_year = min(date("Y", $d1), date("Y", $d2));

	$diff = mktime(0, 0, $diff_secs, 1, 1, $base_year);
	$diffArray = array(
		"years" => date("Y", $diff) - $base_year,
		"months_total" => (date("Y", $diff) - $base_year) * 12 + date("n", $diff) - 1,
		"months" => date("n", $diff) - 1,
		"days_total" => floor($diff_secs / (3600 * 24)),
		"days" => date("j", $diff) - 1,
		"hours_total" => floor($diff_secs / 3600),
		"hours" => date("G", $diff),
		"minutes_total" => floor($diff_secs / 60),
		"minutes" => (int) date("i", $diff),
		"seconds_total" => $diff_secs,
		"seconds" => (int) date("s", $diff)
	);
	if($diffArray['days'] > 0){
		if($diffArray['days'] == 1){
			$days = '1 day';
		}else{
			$days = $diffArray['days'] . ' days';
		}
		return $days . ' and ' . $diffArray['hours'] . ' hours ago';
	}else if($diffArray['hours'] > 0){
		if($diffArray['hours'] == 1){
			$hours = '1 hour';
		}else{
			$hours = $diffArray['hours'] . ' hours';
		}
		return $hours . ' and ' . $diffArray['minutes'] . ' minutes ago';
	}else if($diffArray['minutes'] > 0){
		if($diffArray['minutes'] == 1){
			$minutes = '1 minute';
		}else{
			$minutes = $diffArray['minutes'] . ' minutes';
		}
		return $minutes . ' and ' . $diffArray['seconds'] . ' seconds ago';
	}else{
		return 'Less than a minute ago';
	}
}


$timestamp = time();
$date_time_array = getdate($timestamp);

$hours = $date_time_array['hours'];
$minutes = $date_time_array['minutes'];
$seconds = $date_time_array['seconds'];
$month = $date_time_array['mon'];
$day = $date_time_array['mday'];
$year = $date_time_array['year'];


$timestamp = mktime($hours + 0,$minutes,$seconds,$month,$day,$year);
$theDate = strftime('%Y-%m-%d %H:%M:%S',$timestamp);	

/**
 *
 *
 *
 *
 *  SEARCH TERMS BELOW
 *
 *
 *
 */
$q = Array('EntreFirst', 'efbootcamp', 'quantifiedself', 'self-tracking');

echo "<h3>Search Terms</h3>";
echo "<p>";
foreach($q as $searchTerm){
  echo " ".$searchTerm;
}
echo "</p>";

$search = "http://search.twitter.com/search.atom?q=".$q[0]."+OR+".$q[1]."+OR+".$q[2]."+OR+".$q[3]."&rpp=100";

$tw = curl_init();

curl_setopt($tw, CURLOPT_URL, $search);
curl_setopt($tw, CURLOPT_RETURNTRANSFER, TRUE);
$twi = curl_exec($tw);
$search_res = new SimpleXMLElement($twi);

foreach ($search_res->entry as $twit1) {



$description = $twit1->content;

$description = preg_replace("#(^|[\n ])@([^ \"\t\n\r<]*)#ise", "'\\1<a href=\"http://www.twitter.com/\\2\" >@\\2</a>'", $description);  
$description = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t<]*)#ise", "'\\1<a href=\"\\2\" >\\2</a>'", $description);
$description = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#ise", "'\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>'", $description);

$retweet = strip_tags($description);


$date =  strtotime($twit1->updated);
$dayMonth = date('d M', $date);
$year = date('y', $date);
$message = "";
$datediff = date_diff2($theDate, $date);


echo "<div class='user'><a href=\"",$twit1->author->uri,"\" target=\"_blank\"><img border=\"0\" width=\"48\" class=\"twitter_thumb\" src=\"",$twit1->link[1]->attributes()->href,"\" title=\"", $twit1->author->name, "\"  target=\"_blank\"/></a>\n";
echo "<div class='text'>".$description."<div class='description'>From: ", $twit1->author->name," </div><strong>".$datediff."</strong></div><div class='clear'></div></div>";

}

//echo "<a href=''>Next</a>";

curl_close($tw);

?>


</body>
</html>
