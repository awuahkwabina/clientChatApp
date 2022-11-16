<?php

define('TIMEZONE', 'Africa/Accra');
date_default_timezone_set(TIMEZONE);



function timeAgo($time, $tense='ago'){
	static $periods = array('year', 'month', 'day', 'hour', 'minute', 'second');
	if(!(strtotime($time) > 0)){
		return trigger_error("wrong time formatting: '$time'", E_USER_ERROR);
	}
	$now = new DateTime($time);
	$time = new DateTime('now');
	   $diff = $now->diff($time, true)->format("%y %m %d %h %i %s");
	   $diff = explode(' ', $diff);
	   $diff = array_combine($periods, $diff);
	   $diff = array_filter($diff);
	   $period = key($diff);
	   $value = current($diff);
		if($period == 'second' && $value <= 30){
			$period = '';
			$tense = '';
			$value = '';
			return "online";
		}
	else{
		if($period == 'second' && $value <= 59){
			$period = '';
			$tense = '';
			$value = 'just now';
	 }else{
		if($period == 'day' && $value >= 7){
			$period = 'week';
			$value = floor($value/7);
		}	
		if($value > 1){
			$period .= 's';
		}
	 }

}
	  
	   return "$value $period $tense";
	
}
//  
// 	 $period = key($diff);
// 	   $value = current($diff);


// 	 if($period == 'second' && $value <= 59)
// 		$period = ' ';
// 		$tense = ' ';
// 		$value = 'just now';
// 	 }
	 
	 
// 	 else{

// 		if($period == 'day' && $value >= 7){
// 			$period = 'week';
// 			$value = floor($value/7);
// 		}
		
// 		if($value > 1){
// 			$period .= 's';
// 		}
// 	 }
// 	 return "$value $period $tense";
//  














































// define('TIMEZONE', 'Africa/Accra');
// date_default_timezone_set(TIMEZONE);

// function last_seen($date_time){

//    $timestamp = strtotime($date_time);	
   
//    $strTime = array("second", "minute", "hour", "day", "month", "year");
//    $length = array("60","60","24","30","12","10");

//    $currentTime = time();
//    if($currentTime >= $timestamp) {
// 		$diff     = time()- $timestamp;
// 		for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
// 		$diff = $diff / $length[$i];
// 		}

// 		$diff = round($diff);
// 		if ($diff < 59 && $strTime[$i] == "second") {
// 			return 'Active';
// 		}else {
// 			return $diff . " " . $strTime[$i] . "(s) ago ";
// 		}
		
//    }
// }