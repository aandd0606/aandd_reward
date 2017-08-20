<?php
//  ------------------------------------------------------------------------ //
// 本模組由 aandd 製作
// 製作日期：2015-01-15
// $Id:$
// ------------------------------------------------------------------------- //
//引入TadTools的函式庫
if(!file_exists(TADTOOLS_PATH."/tad_function.php")){
 redirect_header("http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50",3, _TAD_NEED_TADTOOLS);
}
include_once TADTOOLS_PATH."/tad_function.php";

/********************* 自訂函數 *********************/
$class_array=array(
	'西勢行政人員'=>'西勢行政人員',
	'1'=>'一年忠班',
	'2'=>'一年孝班',
	'3'=>'二年忠班',
	'4'=>'二年孝班',
	'5'=>'三年忠班',
	'6'=>'三年孝班',
	'7'=>'四年忠班',
	'8'=>'四年孝班',
	'9'=>'五年忠班',
	'10'=>'五年孝班',
	'11'=>'六年忠班',
	'12'=>'六年孝班',
	'99'=>'畢業',
);
$sex[0]="女性";
$sex[1]="男性";

function arrayToSelect($arr,$option=true,$default_val="",$use_v=false,$validate=false){
	if(empty($arr))return;
	$opt=($option)?"<option value=''>請選擇</option>\n":"";
	foreach($arr as $i=>$v){
		//false則以陣列索引值為選單的值，true則以陣列的值為選單的值
		$val=($use_v)?$v:$i;
		$selected=($val==$default_val)?'selected="selected"':"";        //設定預設值
		$validate_check=($validate)?"class='required'":"";
		$opt.="<option value='$val' $selected $validate_check>$v</option>\n";
	}
	return  $opt;
}

function arrayToRadio($arr,$use_v=false,$name="default",$default_val=""){
    	if(empty($arr))return;
    	$opt="";
    	foreach($arr as $i=>$v){
    		$val=($use_v)?$v:$i;
    		$checked=($val==$default_val)?"checked='checked'":"";
    		$opt.="<input type='radio' name='{$name}' id='{$val}' value='{$val}' $checked><label for='{$val}' style='display:inline;margin-right:15px;'> $v</label>";
    	}
    	return $opt;
}

function arrayToRadioBS2($arr,$use_v=false,$name="default",$default_val=""){
    	if(empty($arr))return;
    	$opt="";
    	foreach($arr as $i=>$v){
    		$val=($use_v)?$v:$i;
    		$checked=($val==$default_val)?"checked='checked'":"";
    		$opt.="<label class='radio inline'><input type='radio' name='{$name}' id='{$val}' value='{$val}' $checked>$v</label>";
    	}
    	return $opt;
}

/********************* 預設函數 *********************/
//圓角文字框
function div_3d($title="",$main="",$kind="raised",$style="",$other=""){
	$main="<table style='width:auto;{$style}'><tr><td>
	<div class='{$kind}'>
	<h1>$title</h1>
	$other
	<b class='b1'></b><b class='b2'></b><b class='b3'></b><b class='b4'></b>
	<div class='boxcontent'>
 	$main
	</div>
	<b class='b4b'></b><b class='b3b'></b><b class='b2b'></b><b class='b1b'></b>
	</div>
	</td></tr></table>";
	return $main;
}
?>