<?php
//  ------------------------------------------------------------------------ //
// 本模組由 aandd 製作
// 製作日期：2015-01-15
// $Id:$
// ------------------------------------------------------------------------- //

//---基本設定---//
//模組名稱
$modversion['name'] = _MI_AANDDREWARD_NAME;
//模組版次
$modversion['version']	= '1.0';
//模組作者
$modversion['author'] = _MI_AANDDREWARD_AUTHOR;
//模組說明
$modversion['description'] = _MI_AANDDREWARD_DESC;
//模組授權者
$modversion['credits']	= _MI_AANDDREWARD_CREDITS;
//模組版權
$modversion['license']		= "GPL see LICENSE";
//模組是否為官方發佈1，非官方0
$modversion['official']		= 0;
//模組圖示
$modversion['image']		= "images/logo.png";
//模組目錄名稱
$modversion['dirname']		= "aandd_reward";

//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;//---資料表架構---//
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][1] = "aandd_reward_item";
$modversion['tables'][2] = "aandd_reward_student";
$modversion['tables'][3] = "aandd_reward_teacher";
$modversion['tables'][4] = "aandd_reward_log";

//---管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

//---使用者主選單設定---//
$modversion['hasMain'] = 1;
$modversion['sub'][2]['name'] =_MI_AANDDREWARD_SMNAME2;
$modversion['sub'][2]['url'] = "reward.php";
$modversion['sub'][3]['name'] ="給過點數查詢";
$modversion['sub'][3]['url'] = "teacher_log.php";


//---樣板設定---//

$modversion['templates'][1]['file'] = 'aandd_reward_index_tpl.html';
$modversion['templates'][1]['description'] = _MI_AANDDREWARD_TEMPLATE_DESC1;
$modversion['templates'][2]['file'] = 'aandd_reward_reward_tpl.html';
$modversion['templates'][2]['description'] = _MI_AANDDREWARD_TEMPLATE_DESC2;
//---區塊設定---//
$modversion['blocks'][1]['file'] = "aandd_reward_b_show_1.php";
$modversion['blocks'][1]['name'] = _MI_AANDDREWARD_BNAME1;
$modversion['blocks'][1]['description'] = _MI_AANDDREWARD_BDESC1;
$modversion['blocks'][1]['show_func'] = "aandd_reward_b_show_1";
$modversion['blocks'][1]['template'] = "aandd_reward_b_show_1.html";

$modversion['blocks'][2]['file'] = "aandd_reward_b_show_2.php";
$modversion['blocks'][2]['name'] = _MI_AANDDREWARD_BNAME2;
$modversion['blocks'][2]['description'] = _MI_AANDDREWARD_BDESC2;
$modversion['blocks'][2]['show_func'] = "aandd_reward_b_show_2";
$modversion['blocks'][2]['template'] = "aandd_reward_b_show_2.html";

$modversion['blocks'][3]['file'] = "aandd_reward_b_show_3.php";
$modversion['blocks'][3]['name'] = _MI_AANDDREWARD_BNAME3;
$modversion['blocks'][3]['description'] = _MI_AANDDREWARD_BDESC3;
$modversion['blocks'][3]['show_func'] = "aandd_reward_b_show_3";
$modversion['blocks'][3]['template'] = "aandd_reward_b_show_3.html";


?>