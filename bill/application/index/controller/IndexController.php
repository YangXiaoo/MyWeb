<?php
namespace app\index\controller;     //�����ռ䣬Ҳ˵�����ļ����ڵ��ļ���
use think\Controller;
use app\common\model\Personal;      // �����ʦ
/**
 * IndexController����������Ҳ���ļ�����˵������ļ�������ΪIndex.php��
 * ������������Ҫʹ��think\Controller�еĺ����������ڴ˱�����м̳У����ڹ��캯���У����и��๹�캯���ĳ�ʼ��
 */
class IndexController extends Controller
{
    public function __construct()
    {
        // ���ø��๹�캯��(����)
        parent::__construct();

        // ��֤�û��Ƿ��½
        if (!Personal::isLogin()) {
            return $this->redirect('Login/index');
        }
    }

    public function index()
    {
    }
}