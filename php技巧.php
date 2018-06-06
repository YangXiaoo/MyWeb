<?php
//================================     smarty   =============================//
//smarty分为模板文件和Php文件
$smarty->assign("showmusic_false","F");//将showmusic_false替换为F
$smarty->display("listen_music.tpl");//展示内容
//================================  END  ====================================//



//=======================框架=================================================//

*********先掌握一个案例，再分析一个案例，最后修改案例***********

//===================<1>THINKPAD==========教师管理系统案例===================================//
1. MVC是一个设计模式，它强制性的使应用程序的输入、处理和输出分开。
//   使用MVC应用程序被分成三个核心部件：模型（MODLE）、视图（VIEW）,对数据库进行包装、控制器（CONTROLER），它们各自处理自己的任务。
2. 使用空间名
//(1) 用户编写的代码与PHP内部的类/函数/常量或第三方类/函数/常量之间的名字冲突。
//(2) 为很长的标识符名称(通常是为了缓解第一类问题而定义的)创建一个别名（或简短）的名称，提高源代码的可读性。
2.1 Name collisions means
/*you create a function named db_connect, and somebody elses code that you use in your file (i.e. an include) has the same function with the same name.

To get around that problem, you rename your function SteveWa_db_connect  which makes your code longer and harder to read.

Now you can use namespaces to keep your function name separate from anyone else's function name, and you won't have to make extra_long_named functions to get around the name collision problem.

So a namespace is like a pointer to a file path where you can find the source of the function you are working with
*/
3. 安装THINKPHP框架时*注意*服务器环境的PHP版本设置
//若版本太低则会出现如下错误：Fatal error: require() [function.require]: Failed opening required '__DIR__/......
4.数据库
/**<?php
    namespace app\index\controller;
    use think\Db;/**
              * 数据库操作类型
			  * 地址在：thinkphp\library\think\Db.php
			  **

    //Teacher是文件名 是类名，也是Teacher.php
    class Teacher
    {
        public function index()  //index是方法
    {
        //return 'hell world';//检索数据库中第一条数据var_dump(Db::name('teacher')->find());

		//获取教师表中的所有数据
		$teacher = Db::name('techer')->select();

		//返回给用户
		echo $teacher[0]['name'];

		//查看第一条数据中'name'的值
		var_dump($teacher[0]['name']);

        }
     }
    ?>
*/
5. 继承
/* <?php
    namespace app\common\model;
	use think\Model; //导入think\Model类
	//类名叫Teacher,对应文件名为Teacher.php,该类继承了Model类，Moder提前使用use进行导入
	class Teacher extends Model{
	
	 }
   ?>
*/
6. 面向对象思想：
对象：对象是类的一个实例，有状态和行为。例如，一条狗是一个对象，它的状态有：颜色、名字、品种；行为有：摇尾巴、叫、吃等。
类：类是一个模板，它描述一类对象的行为和状态。
方法（method）：方法就是行为，一个类可以有很多方法。逻辑运算、数据修改以及所有动作都是在方法中完成的。
实例变量：每个对象都有独特的实例变量，对象的状态由这些实例变量的值决定。













//===========================    END    =========================================//
<2>yii框架
<3>Codeiginer
<4>Symfony





                                                      ===================<登录>==============
    protected function setPasswordAttr($value)
    {
        return md5($value);
    }
    protected function setLoginsAttr()
    {
        return '0';
    }
    protected function setRegIpAttr()
    {
        return request()->ip();
    }
    protected function setLastTimeAttr()
    {
        return time();
    }
    protected function setLastIpAttr()
    {
        return request()->ip();
    }


	token令牌
	通过此网址查找ip所在地：http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=".$ip
							http://fw.qq.com/ipaddress