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
//要登入
if(!$xoopsUser) redirect_header("index.php",3, "必須是教師使用者才可以瀏覽頁面");


////ajax部分
// if($_POST['op']=='ajaxclassdata'){
	// //die($_POST['class']);
	// $datahtml=ajaxclassdata($_POST['class']);
	// die($datahtml);
// }
function reward_log(){
	global $xoopsDB,$xoopsUser,$class_array;
	$tea_id=$xoopsUser->uid();
	$date=date("Y-m-d");
	//預設獎懲優先
	if(!$_POST['new_reward_item'] && !$_POST['new_reward_score']){
		if(empty($_POST['reward_item'])) redirect_header($_SERVER['PHP_SELF'],3, "沒有選取獎懲");
		//die(var_dump($_POST['stu_id']).var_dump($_POST['reward_item']));
		$sql="select * from ".$xoopsDB->prefix("aandd_reward_item")." where rew_id ='{$_POST['reward_item']}'";
		$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
		$one_item=$xoopsDB->fetchArray($result);
		//die(var_dump($one_item));

		//$sql="";
		foreach($_POST['stu_id'] as $i=>$v){
			//die(var_dump($_POST['stu_id']));
			//取得學生資料
			$sql2="select * from ".$xoopsDB->prefix("aandd_reward_student")." where stu_id ='{$v}'";
			$result2 = $xoopsDB->query($sql2) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
			$one_stu=$xoopsDB->fetchArray($result2);	
			
			$sql="insert into ".$xoopsDB->prefix("aandd_reward_log")." (`tea_id`, `stu_id`, `class_name` , `rew_name`, `rew_score`, `date`) values ('{$tea_id}','{$v}','{$class_array[$one_stu['stu_class']]}','{$one_item['rew_name']}','{$one_item['rew_score']}','{$date}')";
			$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());	
		}
		//redirect_header($_SERVER['PHP_SELF'],3, "輸入點數成功1");
		//die("123456");
	//接續自訂獎懲
	}elseif(!empty($_POST['new_reward_item']) && !empty($_POST['new_reward_score'])){
		if(!is_numeric($_POST['new_reward_score'])) redirect_header($_SERVER['PHP_SELF'],3, "分數要輸入數字");
			foreach($_POST['stu_id'] as $i=>$v){
			//取得學生資料
				$sql2="select * from ".$xoopsDB->prefix("aandd_reward_student")." where stu_id ='{$v}'";
				$result2 = $xoopsDB->query($sql2) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
				$one_stu=$xoopsDB->fetchArray($result2);	
				
				$sql="insert into ".$xoopsDB->prefix("aandd_reward_log")." (`tea_id`, `stu_id`, `class_name` , `rew_name`, `rew_score`, `date`) values ('{$tea_id}','{$v}','{$class_array[$one_stu['stu_class']]}','{$_POST['new_reward_item']}','{$_POST['new_reward_score']}','{$date}')";
				$result = $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
			}

	}else{
		redirect_header($_SERVER['PHP_SELF'],3, "資料有缺，給分失敗");
	}
	redirect_header($_SERVER['PHP_SELF'],3, "輸入點數成功");
	
	
	
} 


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

function showrewarditem(){
	global $xoopsDB,$xoopsUser;
	$sql="select * from ".$xoopsDB->prefix("aandd_reward_item")." order by `rew_score` desc";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$main="";
	while($all=$xoopsDB->fetchArray($result)){
		foreach($all as $k=>$v){
			$$k=$v;
		}
		if($rew_score>0){
			$detail="<span class='label  label-success col-md-3'>({$rew_name}{$rew_score})</span>";
		}else{
			$detail="<span class='label  label-important col-md-3'>{$rew_name}({$rew_score})</span>";
		} 
		$main.="<label class='radio inline col-md-3'><input type='radio' name='reward_item' value='{$rew_id}'>{$detail}</label>";
	}
	return $main;
}


//
function check_stu(){
	global $xoopsUser,$class_array,$sex;
	//$check=arrayToCheckbox($class_array,'class');
	array_unshift($class_array,"顯示全校");
	$main="
		<h3>請選擇班級，快速找到學生</h3>
		<form action='{$_SERVER['PHP_SELF']}' method='post' class='form-inline'>";
	$main.=arrayToRadioBS2($class_array,false,'class');
	$main.="<h3>點選要增加或刪除點數的學生，可一次多選</h3>";
	$main.="<input type='checkbox' id='checkall'> 點此全選/或取消";
	$main.="<div id='ajaxStuInput'></div>";

	$main.="<h3>給予積點或刪除點數項目</h3>";
	$main.=showrewarditem();
	$main.="
		<br><br>
		自訂獎懲事項：
		<input type='text' name='new_reward_item'>
		點數：<input type='text' name='new_reward_score'><br>
		<input type='hidden' name='op' value='reward_log'>
		<input type='submit' value='送出紀錄'>
		</form>";	
	
	return $main;
	
	
}


function index(){
	$main='
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script>
	$(function(){
		$( "input[type=radio][name=class]" ).click(function(){
			//console.log($(this));
			var class_name = ($(this).val());
			// alert(class_name);
			$.get(
				"reward.php",{class:class_name,op:"ajaxclassdata"},function(data){
					//alert(data);
					$("#ajaxStuInput").html(data);
					
			});
		});
		$("#selectall").click(function(){
			$("input:checkbox").attr("checked",true);
		});
		
		$("#cancelall").click(function(){
			$("input:checkbox").attr("checked",false);
		});
		$("#checkall").change(function(){
			var checkval = $(this).prop("checked");
			//alert(checkval);
			if(checkval){
				$("input:checkbox").attr("checked",true);
			}else{
				$("input:checkbox").attr("checked",false);
			}
		});

	})
	</script>
	';
	$main.="
	<form action='{$_SERVER['PHP_SELF']}' method='post'>
	輸入要搜尋的內容：<input type='text' name='searchcontent'>
	<input type='hidden' name='op' value='search'>
	<input type='submit' value='送出搜尋'>
	</form>
	";
	$main.=check_stu();

	return $main;
}
//計算學生分數
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


//ajax取得學生資料
function ajaxclassdata($class=""){
	global $xoopsDB,$xoopsUser,$class_array;
	//if(empty($class)) return;
	$sql = "select * from ".$xoopsDB->prefix("aandd_reward_student")." where stu_class={$class} order by `stu_sex` desc,`stu_num`";
	if($class==0) $sql="select * from ".$xoopsDB->prefix("aandd_reward_student")."";
	//return $sql;
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$main="";
	while($all=$xoopsDB->fetchArray($result)){
		foreach($all as $k=>$v){
			$$k=$v;
		}
		if($stu_sex==1){
			$stu="<span class='label  label-primary'>{$stu_name}(".counttotal($stu_sid).")</span>";
			
		}else{
			$stu="<span class='label  label-danger'>{$stu_name}(".counttotal($stu_sid).")</span>";
		
		}
		$main.="<label class='checkbox inline'><input type='checkbox' name='stu_id[]' value='{$stu_id}' id='stu_{$stu_id}'>{$stu}({$class_array[$stu_class]})</label>";
	}
	//return "test";
	return $main;
	

}

function search(){
	global $xoopsDB;
	$sql="select * from ".$xoopsDB->prefix("aandd_reward_log")." as log,".$xoopsDB->prefix("aandd_reward_student")." as stu 
			where log.rew_name LIKE '%{$_POST['searchcontent']}%' AND log.stu_id=stu.stu_id ORDER BY date DESC ,class_name";
	//die($sql);
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$main="<table class='table table-condensed table-bordered table-striped'>
		<tr><th>姓名</th><th>班級</th><th>獎懲事項</th><th>點數</th><th>時間</th><th>給點教師</th></tr>
	";
	while($all=$xoopsDB->fetchArray($result)){
		foreach($all as $k=>$v){
			$$k=$v;
		}
		//以uid取得使用者名稱
		 $uid_name=XoopsUser::getUnameFromId($tea_id,1);
		 if(empty($uid_name))$uid_name=XoopsUser::getUnameFromId($tea_id,0);
		
		$main.="<tr><td>{$stu_name}</td><td>{$class_name}</td><td>{$rew_name}</td><td>{$rew_score}</td><td>{$date}</td><td>{$uid_name}</td></tr>";
	}
	$main.="</table>";
	
	return $main;
	
}

//以流水號取得某筆aandd_reward_student資料
function get_aandd_student($stu_id=""){
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
$tea_id=empty($_REQUEST['tea_id'])?"":intval($_REQUEST['tea_id']);
$log_id=empty($_REQUEST['log_id'])?"":intval($_REQUEST['log_id']);


switch($op){
	//呈現搜尋資料
	case "search":
	$main=search();
	break;
	
	//AJAX取得學生資料
	case "ajaxclassdata":
	die(ajaxclassdata($_GET['class']));
	//die("123成功了{$_GET['class']}123");
	break;
	
	//紀錄學生獎懲
	case "reward_log":
	reward_log();
	$main=index();
	break;

	default:
	$main=index();
	break;
}

/*-----------秀出結果區--------------*/
module_footer($main);
?>


