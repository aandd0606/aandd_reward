<?php
//  ------------------------------------------------------------------------ //
// 本模組由 aandd 製作
// 製作日期：2015-01-15
// $Id:$
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
include_once "header_admin.php";

/*-----------function區--------------*/
//
function list_alluser(){
	global $xoopsUser,$xoopsDB,$xoopsModule;
	//取得評分老師陣列
	$reward_tea_arr=array();
	$sql = "select * from ".$xoopsDB->prefix("aandd_reward_teacher")."";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	while($all=$xoopsDB->fetchArray($result)){
		foreach($all as $k=>$v){
			$$k=$v;
		}
		$reward_tea_arr[]=$tea_id;
	}
	
	
	$sql = "select * from ".$xoopsDB->prefix("users")."";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$main="
	<table>
	<tr><td><h1>系統使用者</h1>
	";
	while($all=$xoopsDB->fetchArray($result)){
		foreach($all as $k=>$v){
			$$k=$v;
		}
		if(in_array($uid,$reward_tea_arr)) continue;
		$main.="{$uid}、{$name}、{$uname}、{$email}<a href='{$_SERVER['PHP_SELF']}?op=insert_newtea&uid={$uid}'>--></a><br>";
	}
	$teadata="";
	$sql = "select * from ".$xoopsDB->prefix("aandd_reward_teacher")."";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	while($all=$xoopsDB->fetchArray($result)){
		foreach($all as $k=>$v){
			$$k=$v;
		}
		$teadata.="<a href='{$_SERVER['PHP_SELF']}?op=del_newtea&tea_id={$tea_id}'><--</a>{$tea_id}、{$tea_name}、{$tea_score}<br>";
	}		
	$main.="</td><td>
	<h1>評分老師</h1>
	{$teadata}
	</td></tr></table>";
	return $main;

}
//新增評分教師
function insert_newtea($uid=""){
	global $xoopsDB,$xoopsUser;
	$sql = "select * from ".$xoopsDB->prefix("users")." where uid={$uid}";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	while($all=$xoopsDB->fetchArray($result)){
		foreach($all as $k=>$v){
			$$k=$v;
		}
		$sql="insert into `".$xoopsDB->prefix("aandd_reward_teacher")."` (`tea_id`,`tea_name`,`tea_score`) values ('{$uid}','{$name}','500')";
		$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	}
	return $main;
}
//刪除評分教師
function del_newtea($tea_id=""){
	global $xoopsDB,$xoopsUser;
	$sql = "delete from ".$xoopsDB->prefix("aandd_reward_teacher")." where tea_id={$tea_id}";
	//die($sql);
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	// while($all=$xoopsDB->fetchArray($result)){
		// foreach($all as $k=>$v){
			// $$k=$v;
		// }
		// $sql="insert into `".$xoopsDB->prefix("aandd_reward_teacher")."` (`tea_id`,`tea_name`,`tea_score`) values ('{$uid}','{$name}','500')";
		// $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	// }
	// return $main;
}

//
function f2(){
	$main="";
	return $main;
}


/*-----------執行動作判斷區----------*/
$op = empty($_REQUEST['op'])? "":$_REQUEST['op'];
$rew_id=empty($_REQUEST['rew_id'])?"":intval($_REQUEST['rew_id']);
$stu_id=empty($_REQUEST['stu_id'])?"":intval($_REQUEST['stu_id']);
$tea_id=empty($_REQUEST['tea_id'])?"":intval($_REQUEST['tea_id']);
$log_id=empty($_REQUEST['log_id'])?"":intval($_REQUEST['log_id']);


switch($op){
	/*---判斷動作請貼在下方---*/
	case "insert_newtea":
	insert_newtea($_GET['uid']);
	header("location: {$_SERVER['PHP_SELF']}");
	break;
	
	case "del_newtea":
	del_newtea($_GET['tea_id']);
	header("location: {$_SERVER['PHP_SELF']}");
	break;
	
	default:
	$main=list_alluser();
	break;
	
	/*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
module_admin_footer($main,2);

?>