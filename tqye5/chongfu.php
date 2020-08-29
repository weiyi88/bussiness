<?php
/**
 * 检测重复文档
 *
 * @version        $Id: article_test_same.php 1 14:31 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
@set_time_limit(0);
CheckPurview('sys_ArcBatch');
if(empty($dopost)) $dopost = '';
if($dopost=='analyse')
{
    
    $dsql->SetQuery("SELECT COUNT(stitle) AS dd,stitle FROM #@__search GROUP BY stitle ORDER BY dd DESC LIMIT 0, $pagesize");
    $dsql->Execute();
    $allarc = 0;
    include DedeInclude('templets/chongfu_result_same.htm');
    exit();
}
//删除选中的内容（只保留一条）
else if($dopost=='delsel')
{
    
     require_once(dirname(__FILE__)."/inc/inc_batchup2.php");   
    
    if(empty($titles))
    {
        header("Content-Type: text/html; charset={$cfg_ver_lang}");
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset={$cfg_ver_lang}\">\r\n";
        echo "没有指定删除的文档！";
        exit();
    }
    
    $titless = explode('`',$titles);

   
    $orderby = ($deltype=='delnew' ? " ORDER BY sid DESC " : " ORDER BY sid ASC ");
    
    
    $totalarc = 0;
    
    foreach($titless as $title)
    {
         $title = trim($title);
         $title = addslashes( $title=='' ? '' : urldecode($title) );
         echo $title;
          $q1 = "SELECT sid,stitle FROM #@__search WHERE  stitle='$title' $orderby ";
        
         $dsql->SetQuery($q1);
         $dsql->Execute();
         $rownum = $dsql->GetTotalRow();
         if($rownum < 2) continue;
         $i = 1;
         while($row = $dsql->GetObject())
         {
               $i++;
               $naid = $row->sid;
               $ntitle = $row->stitle;
               if($i > $rownum) continue;
               $totalarc++;
               DelArc($naid, 'OFF');
         }
    }
    $dsql->ExecuteNoneQuery(" OPTIMIZE TABLE `#@__search`; ");
    ShowMsg("一共删除了[{$totalarc}]篇重复的文档！","javascript:;");
    exit();
}

//向导页
$channelinfos = array();
$dsql->setquery("SELECT id,typename,maintable,addtable FROM `#@__channeltype` ");
$dsql->execute();
while($row = $dsql->getarray()) $channelinfos[] = $row;
include DedeInclude('templets/chongfu_test_same.htm');