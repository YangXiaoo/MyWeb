<?php
use app\common\model\Config;
use app\common\model\Arctype;
use app\common\model\Archive;
use app\common\model\Flink;
use app\common\model\Banner;
use app\common\model\ArcComment;

/**
 * @Title: confv
 * @Description: todo(获取配置值)
 * @param string $k
 * @param string $type
 * @return string
 * @author 苏晓信
 * @date 2017年8月26日
 * @throws
 */
function confv($k, $type = 'web'){
    $config = new Config();
    return $config->confv($k, $type);
}

/**
 * @Title: channeldata
 * @Description: todo(当前ID的平级栏目)
 * @param int $pid 上级栏目ID
 * @return array
 * @author 苏晓信
 * @date 2017年7月2日
 * @throws
 */
function channeldata($pid){
    $arctype = new Arctype();
    return $arctype->channeldata($pid);
}

/**
 * @Title: arctypeCount
 * @Description: todo(栏目文章统计数)
 * @param int $pid 上级栏目ID
 * @author 苏晓信
 * @date 2017年11月11日
 * @throws
 */
function arctypeCount($pid){
    $arctype = new Arctype();
    return $arctype->arctypeCount($pid);
}

/**
 * @Title: position
 * @Description: todo(当前位置)
 * @param int $id
 * @param string $home
 * @param string $line
 * @return string
 * @author 苏晓信
 * @date 2017年7月2日
 * @throws
 */
function position($id, $home="首页", $line=">"){
    $arctype = new Arctype();
    return $arctype->position($id, $home, $line);
}

/**
 * @Title: arclist
 * @Description: todo(查询栏目下的文章)
 * @param int $typeid 栏目ID（当前栏目下的所有[无限级]栏目ID）
 * @param int $limit 查询数量
 * @param string $flag 推荐[c] 特荐[a] 头条[h] 滚动[s] 图片[p] 跳转[j]
 * @param string $order 排序
 * @return array
 * @author 苏晓信
 * @date 2017年7月5日
 * @throws
 */
function arclist($typeid='0', $limit='', $flag='', $order='id DESC'){
    $archive = new Archive();
    return $archive->arclist($typeid, $limit, $flag, $order);
}

/**
 * @Title: prenext
 * @Description: todo(上一篇、下一篇)
 * @param array $archiveArr 当前文档数组
 * @return string
 * @author 苏晓信
 * @date 2017年7月5日
 * @throws
 */
function prenext($archiveArr){
    $archive = new Archive();
    return $archive->prenext($archiveArr);
}

/**
 * @Title: click
 * @Description: todo(文档点击数+1)
 * @param array $archiveArr 当前文档数组
 * @author 苏晓信
 * @date 2017年7月6日
 * @throws
 */
function click($archiveArr){
    $archive = new Archive();
    $archive->click($archiveArr);
}

/**
 * @Title: flinks
 * @Description: todo(友情链接)
 * @param int $mid 分类ID
 * @param int $limit 查询条数
 * @return array
 * @author 苏晓信
 * @date 2017年11月10日
 * @throws
 */
function flinks($mid, $limit=''){
    $flink = new Flink();
    return $flink->flinks($mid, $limit='');
}

/**
 * @Title: arc_comment
 * @Description: todo(文章评论)
 * @param int $limit
 * @author 苏晓信
 * @date 2018年2月9日
 * @throws
 */
function arc_comment($limit = '3'){
    $arcComment = new ArcComment();
    return $arcComment->newComment($limit);
}