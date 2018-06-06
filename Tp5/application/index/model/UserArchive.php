<?php
namespace app\index\model;

use think\Model;

class UserArchive extends Model{
	public function Arctype(){
		return $this->hasOne('UserArctype','id','typeid');
	}
	public function Paperinfo(){
		return $this->hasOne('UserPaper', 'aid', 'id');
	}
    public function click($archiveArr)
    {
        //setDec为减，setInc为加，配合where一起使用
        return $this->where('id', $archiveArr->id)->setInc('click');
    }
/**
 * @todo  hot_article
 * @author  yx <[email address]>
 * @time(2018-3-5)
 */
	public function hot_article(){
		$uid = session('oId');
		$where = [
			'uid' => $uid,
			'status' => 1,
			];
		$dataList = $this->where($where)->limit(5)->order('click DESC')->select();
		foreach ($dataList as $k => $v) {
			$flag_arr = explode(',', $v['flag']);
            if (in_array('j', $flag_arr) && !empty($v['jumplink'])) {
                $dataList[$k]['arcurl'] = $v['jumplink'];
                $dataList[$k]['target'] = 'target="_blank';
            }else{
            	$dataList[$k]['arcurl'] = url('userdetail/'.$v->Arctype->dirs.'/'.$v['id']);   
            	$dataList[$k]['target'] = 'target="_self"';
            }
		}
		return $dataList;
	}

/**
 * @todo  上下页按钮
 * @time(2018-3-7)
 * @author yx
 */
	public function preview($archive){

		$upLabel = "<div class=\"row\">";
		$endLabel = "</div>";
		$leftLabel = "<div class=\"col-sm-6\">";
		$preLabel = $leftLabel."<span>上一篇:</span>";
		$nxtLabel = $leftLabel."<span>下一篇:</span>";
		$precd = [
			'uid'	=>	$archive['uid'],
			'typeid'=>	$archive['typeid'],
			'id'	=>	['gt', $archive['id']]
		];
		$nxtcd = [
			'uid'	=>	$archive['uid'],
			'typeid'=>	$archive['typeid'],
			'id'	=>	['lt', $archive['id']]
		];
		$pre = $this->where($precd)->order('id DESC')->find();
		if (!empty($pre)) {
			$flag_arr = explode($pre['flag']);
			if (in_array('j', $falg_arr) && !empty($pre['jumplink'])) {
				$pre['arcurl'] = $pre['jumplink'];
				$prelink = $leftLabel.$preLabel."<a href=\"".$pre['arcurl']."\" target=\"_blank\">".$pre['arcurl']."</a>".$endLabel;
			}else{
				$pre['arcurl'] = url('@userdetail/'.$pre->Arctype->dirs.'/'.$pre['id']);
				$preLink = $preLabel."<a href=\"".$pre['arcurl']."\" target=\"_self\">".$pre['title']."</a>".$endLabel;
			}
		}else{
			$preLink = $preLabel."没有了哦".$endLabel;
		}
		$nxt = $this->where($nxtcd)->order('id DESC')->find();
		if (!empty($nxt)) {
			$flag_arr = explode(',', $nxt['flag']);
			if (in_array('j', $falg_arr) && !empty($nxt['jumplink'])) {
				$nxt['arcurl'] = $pre['jumplink'];
				$nxtlink = $nxtLabel."<a href=\"".$nxt['arcurl']."\" target=\"_blank\">".$nxt['title']."</a>".$endLabel;
			}else{
				$nxt['arcurl'] = url('@userdetail/'.$nxt->Arctype->dirs.'/'.$nxt['id']);
				$nxtLink = $nxtLabel."<a href=\"".$nxt['arcurl']."\" target=\"_self\">".$nxt['title']."</a>".$endLabel;
			}
		}else{
				$nxtLink = $nxtLabel."没有了哦".$endLabel;
			}			
		return $upLabel.$preLink.$nxtLink.$endLabel;
	}
	
}