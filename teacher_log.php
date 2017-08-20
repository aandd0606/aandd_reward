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




function index(){
	global $xoopsUser,$xoopsDB,$class_array,$sex;
	$main="
	<table class='table table-condensed table-bordered table-striped'>
	<tr><td>姓名</td><td>性別</td><td>學號</td><td>分數</td></tr>
	";
	if($xoopsUser){
		$key = $xoopsUser->uid();
		//die($key);
		
		$sql="select * from ".$xoopsDB->prefix("aandd_reward_log")." where tea_id = '{$key}' ORDER BY date DESC";
		
		//die($sql);
		$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
		while($all=$xoopsDB->fetchArray($result)){
			foreach($all as $k=>$v){
				$$k=$v;
			}
			$std_data_arr=get_aandd_reward_student($stu_id);
			$main.="<tr><td>{$std_data_arr['stu_name']}</td><td>{$class_name}</td>
			<td>{$rew_name}</td><td>{$rew_score}</td><td>{$date}</td></tr>";
		}

	}
	$main.="</table>";
	//$main="test";

	return $main;
}

//以流水號取得某筆aandd_reward_student資料
function get_aandd_reward_student($stu_id=""){
	global $xoopsDB;
	if(empty($stu_id))return;
	$sql = "select * from ".$xoopsDB->prefix("aandd_reward_student")." where stu_id='$stu_id'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data=$xoopsDB->fetchArray($result);
	return $data;
}



/*-----------執行動作判斷區----------*/
$op=empty($_REQUEST['op'])?"":$_REQUEST['op'];
$rew_id=empty($_REQUEST['rew_id'])?"":intval($_REQUEST['rew_id']);
$stu_id=empty($_REQUEST['stu_id'])?"":intval($_REQUEST['stu_id']);
$stu_sid=empty($_REQUEST['stu_sid'])?"":$_REQUEST['stu_sid'];
$tea_id=empty($_REQUEST['tea_id'])?"":intval($_REQUEST['tea_id']);
$log_id=empty($_REQUEST['log_id'])?"":intval($_REQUEST['log_id']);


switch($op){

	default:
	$main=index();
	break;
}

/*-----------秀出結果區--------------*/
module_footer($main);
?>