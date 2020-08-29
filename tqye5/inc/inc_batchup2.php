<?php
/**
 * 文档操作相关函数
 *
 * @version        $Id: inc_batchup.php 1 10:32 2010年7月21日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
/**
 *  删除文档信息
 *
 * @access    public
 * @param     string  $aid  文档ID
 * @param     string  $type  类型
 * @param     string  $onlyfile  删除数据库记录
 * @return    string
 */
function DelArc($aid, $type='ON', $onlyfile=FALSE,$recycle=0)
{
    global $dsql,$cfg_cookie_encode,$cfg_multi_site,$cfg_medias_dir;
    global $cuserLogin,$cfg_upload_switch,$cfg_delete,$cfg_basedir;
    global $admin_catalogs, $cfg_admin_channel;
    
    if($cfg_delete == 'N') $type = 'OK';

  $row = "select max(sid) From `#@__search` where stitle='$title'  ";
            $dsql->ExecuteNoneQuery($row);
   $sid= $row[max(sid)];
 

    //$issystem==-1 是单表模型，不使用回收站
    if($issystem == -1) $type = 'OK';
    if(!is_array($arcRow)) return FALSE;
    

 
   
        //删除数据库记录
        if(!$onlyfile)
        {
            $query = "Delete From `#@__search` where sid='$sid'  ";
            $dsql->ExecuteNoneQuery($query);
        }
        

    




    //强制转换非多站点模式，以便统一方式获得实际HTML文件
    $GLOBALS['cfg_multi_site'] = 'N';
    $arcurl = GetFileUrl($arcRow['aid'],$arcRow['typeid'],$arcRow['senddate'],$arcRow['title'],$arcRow['ismake'],
                       $arcRow['arcrank'],$arcRow['namerule'],$arcRow['typedir'],$arcRow['money'],$arcRow['filename']);
    if(!preg_match("#\?#", $arcurl))
    {
        $htmlfile = GetTruePath().str_replace($GLOBALS['cfg_basehost'],'',$arcurl);
        if(file_exists($htmlfile) && !is_dir($htmlfile))
        {
            @unlink($htmlfile);
            $arcurls = explode(".", $htmlfile);
            $sname = $arcurls[count($arcurls)-1];
            $fname = preg_replace("#(\.$sname)$#", "", $htmlfile);
            for($i=2; $i<=100; $i++)
            {
                $htmlfile = $fname."_{$i}.".$sname;
                if( @file_exists($htmlfile) ) @unlink($htmlfile);
                else break;
            }
        }
    }

    return true;
}

//获取真实路径
function GetTruePath($siterefer='', $sitepath='')
{
    $truepath = $GLOBALS['cfg_basedir'];
    return $truepath;
}