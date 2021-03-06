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
//aandd_reward_student編輯表單
function aandd_reward_student_form($stu_id=""){
	global $xoopsDB,$xoopsUser,$class_array,$sex;
	//include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
	//include_once(XOOPS_ROOT_PATH."/class/xoopseditor/xoopseditor.php");

	//抓取預設值
	if(!empty($stu_id)){
		$DBV=get_aandd_reward_student($stu_id);
	}else{
		$DBV=array();
	}

	//預設值設定
	
	
	//設定「stu_id」欄位預設值
	$stu_id=(!isset($DBV['stu_id']))?"":$DBV['stu_id'];

	//設定「stu_id」欄位預設值
	$stu_sid=(!isset($DBV['stu_sid']))?"":$DBV['stu_sid'];
	
	//設定「stu_name」欄位預設值
	$stu_name=(!isset($DBV['stu_name']))?"":$DBV['stu_name'];
	
	//設定「stu_class」欄位預設值
	$stu_class=(!isset($DBV['stu_class']))?"":$DBV['stu_class'];
	
	//設定「stu_sex」欄位預設值
	$stu_sex=(!isset($DBV['stu_sex']))?"":$DBV['stu_sex'];
	
	//設定「stu_num」欄位預設值
	$stu_num=(!isset($DBV['stu_num']))?"":$DBV['stu_num'];

	$op=(empty($stu_id))?"insert_aandd_reward_student":"update_aandd_reward_student";
	//$op="replace_aandd_reward_student";
	
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
  

	<!--學生序號-->
	<input type='hidden' name='stu_id' value='{$stu_id}'>

	<!--學生名字-->
	<tr><td class='title' nowrap>"._MA_AANDDREWARD_STU_NAME."</td>
	<td class='col'><input type='text' name='stu_name' size='20' value='{$stu_name}' id='stu_name' ></td></tr>
	
	<!--學生身分證-->
	<tr><td class='title' nowrap>"._MA_AANDDREWARD_STU_SID."</td>
	<td class='col'><input type='text' name='stu_sid' size='20' value='{$stu_sid}' id='stu_sid' ></td></tr>	

	<!--學生班級-->
	<tr><td class='title' nowrap>"._MA_AANDDREWARD_STU_CLASS."</td>
	<td class='col'><select name='stu_class' size=1 >
		".arrayToSelect($class_array,false,$stu_class)."
	</select></td></tr>

	<!--學生性別-->
	<tr><td class='title' nowrap>"._MA_AANDDREWARD_STU_SEX."</td>
	<td class='col'>
	".arrayToRadio($sex,false,'stu_sex',$stu_sex)."
	
	</td></tr>

	<!--學生學號-->
	<tr><td class='title' nowrap>"._MA_AANDDREWARD_STU_NUM."</td>
	<td class='col'><input type='text' name='stu_num' size='20' value='{$stu_num}' id='stu_num' ></td></tr>
	<tr><td class='bar' colspan='2'>
	<input type='hidden' name='op' value='{$op}'>
	<input type='submit' value='"._MA_SAVE."'></td></tr>
	</table>
	</form>";

	//raised,corners,inset
	$main=div_3d(_MA_AANDD_REWARD_STUDENT_FORM,$main,"raised");
  
	return $main;
}



//新增資料到aandd_reward_student中
function insert_aandd_reward_student(){
	global $xoopsDB,$xoopsUser;
	

	$myts =& MyTextSanitizer::getInstance();
	$_POST['stu_name']=$myts->addSlashes($_POST['stu_name']);
	$_POST['stu_sid']=$myts->addSlashes($_POST['stu_sid']);
	$_POST['stu_num']=$myts->addSlashes($_POST['stu_num']);

  
	$sql = "insert into ".$xoopsDB->prefix("aandd_reward_student")."
	(`stu_sid` , `stu_name` , `stu_class` , `stu_sex` , `stu_num`)
	values('{$_POST['stu_sid']}','{$_POST['stu_name']}' , '{$_POST['stu_class']}' , '{$_POST['stu_sex']}' , '{$_POST['stu_num']}')";
	$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	
	//取得最後新增資料的流水編號
	$stu_id=$xoopsDB->getInsertId();
	return $stu_id;
}

//更新aandd_reward_student某一筆資料
function update_aandd_reward_student($stu_id=""){
	global $xoopsDB,$xoopsUser;
	

	$myts =& MyTextSanitizer::getInstance();
	$_POST['stu_name']=$myts->addSlashes($_POST['stu_name']);
	$_POST['stu_sid']=$myts->addSlashes($_POST['stu_sid']);
	$_POST['stu_num']=$myts->addSlashes($_POST['stu_num']);

  
	$sql = "update ".$xoopsDB->prefix("aandd_reward_student")." set 
	 `stu_sid` = '{$_POST['stu_sid']}' , 
	 `stu_name` = '{$_POST['stu_name']}' , 
	 `stu_class` = '{$_POST['stu_class']}' , 
	 `stu_sex` = '{$_POST['stu_sex']}' , 
	 `stu_num` = '{$_POST['stu_num']}'
	where stu_id='$stu_id'";
	//die($sql);
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	return $stu_id;
}

//列出所有aandd_reward_student資料
function list_aandd_reward_student($show_function=1){
	global $xoopsDB,$xoopsModule,$class_array,$sex;
	$sql = "select * from ".$xoopsDB->prefix("aandd_reward_student")."";

	//getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
  // $PageBar=getPageBar($sql,20,10);
  // $bar=$PageBar['bar'];
  // $sql=$PageBar['sql'];
  // $total=$PageBar['total'];

	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	
	$function_title=($show_function)?"<th>"._BP_FUNCTION."</th>":"";
	
	$all_content="";
	
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $stu_id , $stu_name , $stu_class , $stu_sex , $stu_num
    foreach($all as $k=>$v){
      $$k=$v;
    }
    
		$fun=($show_function)?"
		<td>
		<a href='{$_SERVER['PHP_SELF']}?op=aandd_reward_student_form&stu_id=$stu_id' class='link_button'>"._BP_EDIT."</a>
		<a href=\"javascript:delete_aandd_reward_student_func($stu_id);\" class='link_button'>"._BP_DEL."</a>
		<a href='{$_SERVER['PHP_SELF']}?op=see_log&stu_id=$stu_id' class='link_button'>檢視紀錄</a>
		</td>":"";
		
		$all_content.="<tr>
		<td>{$stu_id}</td>
		<td>{$stu_sid}</td>
		<td>{$stu_name}</td>
		<td>{$class_array[$stu_class]}</td>
		<td>{$sex[$stu_sex]}</td>
		<td>{$stu_num}</td>
		$fun
		</tr>";
	}

  //if(empty($all_content))return "";
  
  $add_button=($show_function)?"<a href='{$_SERVER['PHP_SELF']}?op=aandd_reward_student_form'  class='link_button_r'>"._BP_ADD."</a>":"";
	
	//刪除確認的JS
	$data="
	<script>
	function delete_aandd_reward_student_func(stu_id){
		var sure = window.confirm('"._BP_DEL_CHK."');
		if (!sure)	return;
		location.href=\"{$_SERVER['PHP_SELF']}?op=delete_aandd_reward_student&stu_id=\" + stu_id;
	}
	</script>

	<table summary='list_table' id='tbl' style='width:100%;'>
	<tr>
	<th>"._MA_AANDDREWARD_STU_ID."</th>
	<th>"._MA_AANDDREWARD_STU_SID."</th>
	<th>"._MA_AANDDREWARD_STU_NAME."</th>
	<th>"._MA_AANDDREWARD_STU_CLASS."</th>
	<th>"._MA_AANDDREWARD_STU_SEX."</th>
	<th>"._MA_AANDDREWARD_STU_NUM."</th>
	$function_title</tr>
	<tbody>
	$all_content
	<tr>
	<td colspan=6 class='bar'>
	$add_button
	{$bar}</td></tr>
	</tbody>
	</table>";
	
	//raised,corners,inset
	$main=div_3d("",$data,"corners");
	
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

//刪除aandd_reward_student某筆資料資料
function delete_aandd_reward_student($stu_id=""){
	global $xoopsDB;
	$sql = "delete from ".$xoopsDB->prefix("aandd_reward_student")." where stu_id='$stu_id'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}

//以流水號秀出某筆aandd_reward_student資料內容
function show_one_aandd_reward_student($stu_id=""){
	global $xoopsDB,$xoopsModule;
	if(empty($stu_id)){
		return;
	}else{
		$stu_id=intval($stu_id);
	}
	
  
	
	$sql = "select * from ".$xoopsDB->prefix("aandd_reward_student")." where stu_id='{$stu_id}'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$all=$xoopsDB->fetchArray($result);
	
	//以下會產生這些變數： $stu_id , $stu_name , $stu_class , $stu_sex , $stu_num
	foreach($all as $k=>$v){
		$$k=$v;
	}
  
	$data="
	<table summary='list_table' id='tbl'>
	<tr><th>"._MA_AANDDREWARD_STU_ID."</th><td>{$stu_id}</td></tr>
	<tr><th>"._MA_AANDDREWARD_STU_SID."</th><td>{$stu_id}</td></tr>
	<tr><th>"._MA_AANDDREWARD_STU_NAME."</th><td>{$stu_name}</td></tr>
	<tr><th>"._MA_AANDDREWARD_STU_CLASS."</th><td>{$stu_class}</td></tr>
	<tr><th>"._MA_AANDDREWARD_STU_SEX."</th><td>{$stu_sex}</td></tr>
	<tr><th>"._MA_AANDDREWARD_STU_NUM."</th><td>{$stu_num}</td></tr>
	</table>";
	
	//raised,corners,inset
	$main=div_3d("",$data,"corners");
	
	return $main;
}

function see_log(){
	global $xoopsDB,$xoopsUser;
	$sql="select * from `".$xoopsDB->prefix("aandd_reward_log")."` as log,
	 `".$xoopsDB->prefix("aandd_reward_student")."` as stu where 
	 log.stu_id=stu.stu_id AND stu.stu_id='{$_GET['stu_id']}'
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
		$tea=XoopsUser::getUnameFromId($stu_id,1);
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




/*-----------執行動作判斷區----------*/
$op = empty($_REQUEST['op'])? "":$_REQUEST['op'];
$rew_id=empty($_REQUEST['rew_id'])?"":intval($_REQUEST['rew_id']);
$stu_id=empty($_REQUEST['stu_id'])?"":intval($_REQUEST['stu_id']);
$tea_id=empty($_REQUEST['tea_id'])?"":intval($_REQUEST['tea_id']);
$log_id=empty($_REQUEST['log_id'])?"":intval($_REQUEST['log_id']);


switch($op){
	/*---判斷動作請貼在下方---*/
	//檢視個別基點紀錄
	case "see_log":
	$main=see_log();
	break;
	
	//替換資料
	case "replace_aandd_reward_student":
	replace_aandd_reward_student();
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	//新增資料
	case "insert_aandd_reward_student":
	$stu_id=insert_aandd_reward_student();
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	//更新資料
	case "update_aandd_reward_student":
	update_aandd_reward_student($stu_id);
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	//輸入表格
	case "aandd_reward_student_form":
	$main=aandd_reward_student_form($stu_id);
	break;

	//刪除資料
	case "delete_aandd_reward_student":
	delete_aandd_reward_student($stu_id);
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	//預設動作
	default:
	if(empty($stu_id)){
		$main=list_aandd_reward_student();
		//$main.=aandd_reward_student_form($stu_id);
	}else{
		$main=show_one_aandd_reward_student($stu_id);
	}
	break;

	
	/*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
module_admin_footer($main,1);

?>