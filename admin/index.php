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
//aandd_reward_item編輯表單
function aandd_reward_item_form($rew_id=""){
	global $xoopsDB,$xoopsUser;
	//include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
	//include_once(XOOPS_ROOT_PATH."/class/xoopseditor/xoopseditor.php");

	//抓取預設值
	if(!empty($rew_id)){
		$DBV=get_aandd_reward_item($rew_id);
	}else{
		$DBV=array();
	}

	//預設值設定
	
	
	//設定「rew_id」欄位預設值
	$rew_id=(!isset($DBV['rew_id']))?"":$DBV['rew_id'];
	
	//設定「rew_name」欄位預設值
	$rew_name=(!isset($DBV['rew_name']))?"":$DBV['rew_name'];
	
	//設定「rew_score」欄位預設值
	$rew_score=(!isset($DBV['rew_score']))?"":$DBV['rew_score'];

	$op=(empty($rew_id))?"insert_aandd_reward_item":"update_aandd_reward_item";
	//$op="replace_aandd_reward_item";
	
	if(!file_exists(TADTOOLS_PATH."/formValidator.php")){
   redirect_header("index.php",3, _MA_NEED_TADTOOLS);
  }
  include_once TADTOOLS_PATH."/formValidator.php";
  $formValidator= new formValidator("#myForm",true);
  $formValidator_code=$formValidator->render();
	
	$main="
	$formValidator_code
	
	<form action='{$_SERVER['PHP_SELF']}' method='post' id='myForm' enctype='multipart/form-data'>
	<table class='form_tbl'>
  

	<!--評分項目序號-->
	<input type='hidden' name='rew_id' value='{$rew_id}'>

	<!--評分項目名稱-->
	<tr><td class='title' nowrap>"._MA_AANDDREWARD_REW_NAME."</td>
	<td class='col'><input type='text' name='rew_name' size='20' value='{$rew_name}' id='rew_name' ></td></tr>

	<!--評分項目分數-->
	<tr><td class='title' nowrap>"._MA_AANDDREWARD_REW_SCORE."</td>
	<td class='col'><input type='text' name='rew_score' size='20' value='{$rew_score}' id='rew_score' ></td></tr>
	<tr><td class='bar' colspan='2'>
	<input type='hidden' name='op' value='{$op}'>
	<input type='submit' value='"._MA_SAVE."'></td></tr>
	</table>
	</form>";

	//raised,corners,inset
	$main=div_3d(_MA_AANDD_REWARD_ITEM_FORM,$main,"raised");
  
	return $main;
}



//新增資料到aandd_reward_item中
function insert_aandd_reward_item(){
	global $xoopsDB,$xoopsUser;
	

	$myts =& MyTextSanitizer::getInstance();
	$_POST['rew_name']=$myts->addSlashes($_POST['rew_name']);
	$_POST['rew_score']=$myts->addSlashes($_POST['rew_score']);

  
	$sql = "insert into ".$xoopsDB->prefix("aandd_reward_item")."
	(`rew_name` , `rew_score`)
	values('{$_POST['rew_name']}' , '{$_POST['rew_score']}')";
	$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	
	//取得最後新增資料的流水編號
	$rew_id=$xoopsDB->getInsertId();
	return $rew_id;
}

//更新aandd_reward_item某一筆資料
function update_aandd_reward_item($rew_id=""){
	global $xoopsDB,$xoopsUser;
	

	$myts =& MyTextSanitizer::getInstance();
	$_POST['rew_name']=$myts->addSlashes($_POST['rew_name']);
	$_POST['rew_score']=$myts->addSlashes($_POST['rew_score']);

  
	$sql = "update ".$xoopsDB->prefix("aandd_reward_item")." set 
	 `rew_name` = '{$_POST['rew_name']}' , 
	 `rew_score` = '{$_POST['rew_score']}'
	where rew_id='$rew_id'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	return $rew_id;
}

//列出所有aandd_reward_item資料
function list_aandd_reward_item($show_function=1){
	global $xoopsDB,$xoopsModule;
	$sql = "select * from ".$xoopsDB->prefix("aandd_reward_item")."";

	//getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
  $PageBar=getPageBar($sql,20,10);
  $bar=$PageBar['bar'];
  $sql=$PageBar['sql'];
  $total=$PageBar['total'];

	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	
	$function_title=($show_function)?"<th>"._BP_FUNCTION."</th>":"";
	
	$all_content="";
	
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $rew_id , $rew_name , $rew_score
    foreach($all as $k=>$v){
      $$k=$v;
    }
    
		$fun=($show_function)?"
		<td>
		<a href='{$_SERVER['PHP_SELF']}?op=aandd_reward_item_form&rew_id=$rew_id' class='link_button'>"._BP_EDIT."</a>
		<a href=\"javascript:delete_aandd_reward_item_func($rew_id);\" class='link_button'>"._BP_DEL."</a>
		</td>":"";
		
		$all_content.="<tr>
		<td>{$rew_id}</td>
		<td>{$rew_name}</td>
		<td>{$rew_score}</td>
		$fun
		</tr>";
	}

  //if(empty($all_content))return "";
  
  $add_button=($show_function)?"<a href='{$_SERVER['PHP_SELF']}?op=aandd_reward_item_form'  class='link_button_r'>"._BP_ADD."</a>":"";
	
	//刪除確認的JS
	$data="
	<script>
	function delete_aandd_reward_item_func(rew_id){
		var sure = window.confirm('"._BP_DEL_CHK."');
		if (!sure)	return;
		location.href=\"{$_SERVER['PHP_SELF']}?op=delete_aandd_reward_item&rew_id=\" + rew_id;
	}
	</script>

	<table summary='list_table' id='tbl' style='width:100%;'>
	<tr>
	<th>"._MA_AANDDREWARD_REW_ID."</th>
	<th>"._MA_AANDDREWARD_REW_NAME."</th>
	<th>"._MA_AANDDREWARD_REW_SCORE."</th>
	$function_title</tr>
	<tbody>
	$all_content
	<tr>
	<td colspan=4 class='bar'>
	$add_button
	{$bar}</td></tr>
	</tbody>
	</table>";
	
	//raised,corners,inset
	$main=div_3d("",$data,"corners");
	
	return $main;
}


//以流水號取得某筆aandd_reward_item資料
function get_aandd_reward_item($rew_id=""){
	global $xoopsDB;
	if(empty($rew_id))return;
	$sql = "select * from ".$xoopsDB->prefix("aandd_reward_item")." where rew_id='$rew_id'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data=$xoopsDB->fetchArray($result);
	return $data;
}

//刪除aandd_reward_item某筆資料資料
function delete_aandd_reward_item($rew_id=""){
	global $xoopsDB;
	$sql = "delete from ".$xoopsDB->prefix("aandd_reward_item")." where rew_id='$rew_id'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}

//以流水號秀出某筆aandd_reward_item資料內容
function show_one_aandd_reward_item($rew_id=""){
	global $xoopsDB,$xoopsModule;
	if(empty($rew_id)){
		return;
	}else{
		$rew_id=intval($rew_id);
	}
	
  
	
	$sql = "select * from ".$xoopsDB->prefix("aandd_reward_item")." where rew_id='{$rew_id}'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$all=$xoopsDB->fetchArray($result);
	
	//以下會產生這些變數： $rew_id , $rew_name , $rew_score
	foreach($all as $k=>$v){
		$$k=$v;
	}
  
	$data="
	<table summary='list_table' id='tbl'>
	<tr><th>"._MA_AANDDREWARD_REW_ID."</th><td>{$rew_id}</td></tr>
	<tr><th>"._MA_AANDDREWARD_REW_NAME."</th><td>{$rew_name}</td></tr>
	<tr><th>"._MA_AANDDREWARD_REW_SCORE."</th><td>{$rew_score}</td></tr>
	</table>";
	
	//raised,corners,inset
	$main=div_3d("",$data,"corners");
	
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
	
	//替換資料
	case "replace_aandd_reward_item":
	replace_aandd_reward_item();
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	//新增資料
	case "insert_aandd_reward_item":
	$rew_id=insert_aandd_reward_item();
	header("location: {$_SERVER['PHP_SELF']}?rew_id=$rew_id");
	break;

	//更新資料
	case "update_aandd_reward_item":
	update_aandd_reward_item($rew_id);
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	//輸入表格
	case "aandd_reward_item_form":
	$main=aandd_reward_item_form($rew_id);
	break;

	//刪除資料
	case "delete_aandd_reward_item":
	delete_aandd_reward_item($rew_id);
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	//預設動作
	default:
	if(empty($rew_id)){
		$main=list_aandd_reward_item();
		//$main.=aandd_reward_item_form($rew_id);
	}else{
		$main=show_one_aandd_reward_item($rew_id);
	}
	break;

	
	/*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
module_admin_footer($main,0);

?>