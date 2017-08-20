<?php
//  ------------------------------------------------------------------------ //
// 本模組由 aandd 製作
// 製作日期：2015-01-15
// $Id:$
// ------------------------------------------------------------------------- //


/*-----------引入檔案區--------------*/
include_once "header.php";
$xoopsOption['template_main'] = "aandd_reward_index_tpl.html";
/*-----------function區--------------*/
// function index(){
	// $main="test";
	// return $main;
	
// }
function arrayToCheckbox($arr,$name,$default_val="",$use_v=false){
	//<input type="checkbox" name="option1" value="Milk">
	if(empty($arr))return;
	foreach($arr as $i=>$v){
		//false則以陣列索引值為選單的值，true則以陣列的值為選單的值
		$val=($use_v)?$v:$i;
		$selected=($val==$default_val)?"checked":"";        //設定預設值
		$opt.="<input type='checkbox' name='{$name}' value='{$val}' id='stu_{$val}'><label for='stu_{$val}'>{$v}</label>";
	}
	return  $opt;
}

function search($stu_sid=""){
	global $xoopsDB,$xoopsUser;
	$sql="select * from `".$xoopsDB->prefix("aandd_reward_log")."` as log,
	 `".$xoopsDB->prefix("aandd_reward_student")."` as stu where 
	 log.stu_id=stu.stu_id AND stu.stu_sid='{$stu_sid}'
	 ";
	//die($sql);
	$result = $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	// die(var_dump($all));
	$main="<table class='table table-condensed table-bordered table-striped'>
	<tr><th>姓名</th><th>班級</th><th>獎懲事項</th><th>點數</th><th>時間</th><th>給點教師</th></tr>
	";
	$total=0;

	while($all=$xoopsDB->fetchArray($result)){
		foreach($all as $k=>$v){
			$$k=$v;
		}
		$tea=XoopsUser::getUnameFromId($tea_id,1);
		$main.="<tr><td>{$stu_name}</td><td>{$class_name}</td><td>{$rew_name}</td><td>{$rew_score}</td>
				<td>{$date}</td><td>{$tea}</td></tr>";
		$total+=$rew_score;
	}
	$main.="
	</table>
	<h1>目前總分：{$total}</h1>
	
	";
	return $main;
}






function index(){
	global $xoopsUser,$xoopsDB,$class_array,$sex;
	$main="
	<table class='table table-condensed table-bordered table-striped'>
	<tr><td>姓名</td><td>性別</td><td>學號</td><td>分數</td></tr>
	";
	if($xoopsUser){
		$key = array_search($xoopsUser->user_occ(), $class_array);
		//die($key);
		if($key == '西勢行政人員'){
			$sql="select * from ".$xoopsDB->prefix("aandd_reward_student")."";
		}else{
			$sql="select * from ".$xoopsDB->prefix("aandd_reward_student")." where stu_class = '{$key}'";
		}
		//die($sql);
		$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
		while($all=$xoopsDB->fetchArray($result)){
			foreach($all as $k=>$v){
				$$k=$v;
			}
			$main.="<tr><td><a href='{$_SERVER['PHP_SELF']}?op=search&stu_sid={$stu_sid}'>{$stu_name}</a></td>
			<td>{$sex[$stu_sex]}</td><td>{$stu_num}</td><td>".counttotal($stu_sid)."</td></tr>";
		}

	}
	
	$main.="</table>";
	$main.="
	<form action='{$_SERVER['PHP_SELF']}' method='post'>
	輸入學生身分證字號：<input type='text' name='stu_sid'>
	<input type='hidden' name='op' value='search'>
	<input type='submit' value='送出搜尋'>
	</form>
	";
	//$main="test";

	return $main;
}


function counttotal($sid=""){
	global $xoopsUser,$xoopsDB;
	$sql="select * from `".$xoopsDB->prefix("aandd_reward_log")."` as log,
	 `".$xoopsDB->prefix("aandd_reward_student")."` as stu where 
	 log.stu_id=stu.stu_id AND stu.stu_sid='{$sid}'
	 ";
	$result = $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$total=0;
	while($all=$xoopsDB->fetchArray($result)){
		foreach($all as $k=>$v){
			$$k=$v;
		}
		$total+=$rew_score;
	}
	return $total;
}


/*-----------執行動作判斷區----------*/
$op=empty($_REQUEST['op'])?"":$_REQUEST['op'];
$rew_id=empty($_REQUEST['rew_id'])?"":intval($_REQUEST['rew_id']);
$stu_id=empty($_REQUEST['stu_id'])?"":intval($_REQUEST['stu_id']);
$stu_sid=empty($_REQUEST['stu_sid'])?"":$_REQUEST['stu_sid'];
$tea_id=empty($_REQUEST['tea_id'])?"":intval($_REQUEST['tea_id']);
$log_id=empty($_REQUEST['log_id'])?"":intval($_REQUEST['log_id']);


switch($op){
	case "search":
	$main=search($stu_sid);
	break;

	default:
	$main=index();
	//$main="12345678";
	break;
}

/*-----------秀出結果區--------------*/
module_footer($main);
?>