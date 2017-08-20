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
function list_all(){
	global $xoopsDB,$class_array,$sex;
	$sql="select * from ".$xoopsDB->prefix("aandd_reward_student")." where stu_class !='99'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$main="
	 <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js'></script>
	 <script>
	 $(function(){
		$('.class_bat_form').change(function(){
			//alert('change');
			var class_key = $(this).val();//班級資料
			var stu_id = $(this).attr('id');
			//alert(class_key + stu_id);
			$.get(
				'student_fast.php',{class:class_key,stu:stu_id,op:'ajaxstufast'},function(data){
					alert(data);
					
			});
			
		});
	 });
	 </script>
	<form action='{$_SERVER['PHP_SELF']}' method='post'>
	";
	while($all=$xoopsDB->fetchArray($result)){
		foreach($all as $k=>$v){
			$$k=$v;
		}
		$main.="{$stu_sid}、{$stu_name}、{$sex[$stu_sex]}、{$stu_num}、{$class_array[$stu_class]}{$stu_class}
		<select name='stu_class[\"{$stu_id}\"]' class='class_bat_form' id='{$stu_id}'>
		".arrayToSelect($class_array,false,$stu_class)."
		</select>
		<br>";
	}
	$main.="
	<input type='hidden' name='op' value='bat_class'>
	<input type='submit' value='送出修改'>
	</form>";

  return $main;
}

//
function ajaxstufast($class="",$stu=""){
	global $xoopsDB,$xoopsUser;
	//return "OK".$class.$stu;

	if(empty($class) OR empty($stu)) redirect_header($_SERVER['PHP_SELF'],3, "不要亂來");
	$sql="update ".$xoopsDB->prefix("aandd_reward_student")." set `stu_class` = '{$class}' where `stu_id` = '{$stu}' ";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	
	return "OK啦!";
}


/*-----------執行動作判斷區----------*/
$op = empty($_REQUEST['op'])? "":$_REQUEST['op'];
$rew_id=empty($_REQUEST['rew_id'])?"":intval($_REQUEST['rew_id']);
$stu_id=empty($_REQUEST['stu_id'])?"":intval($_REQUEST['stu_id']);
$tea_id=empty($_REQUEST['tea_id'])?"":intval($_REQUEST['tea_id']);
$log_id=empty($_REQUEST['log_id'])?"":intval($_REQUEST['log_id']);


switch($op){
	/*---判斷動作請貼在下方---*/
	//AJAX改變學生班級
	case "ajaxstufast":
	die(ajaxstufast($_GET['class'],$_GET['stu']));
	//die($_GET['stu']);
	//die(ajaxstufast($_GET['class'],$_GET['stu']));
	//die("123成功了{$_GET['class']}123");
	break;
	
	case "bat_class":
	bat_class();
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	default:
	$main=list_all();
	break;
	
	/*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
module_admin_footer($main,2);

?>