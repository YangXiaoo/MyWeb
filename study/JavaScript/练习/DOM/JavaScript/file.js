
/*
var shopping =  ("purchase");//获取元素
var item =shopping.getElementsByTagName("li");//获取对象数组
//alert(item.length);=>2
//alert(document.getElementById("*").length);"*":通配符，此条程序显示当前文档元素节点数
document.getElementsByClassName("sale");//通过class属性中的类名来访问元素
*/ 

//获取和设置属性
/*
var paras = document.getElementsByTagName("p");
for (var i=0; i< paras.length; i++ )
{
	var title_text = paras[i].getAttribute("title");
	if (title_text) alert(title_text);
}
=>a gentle reminder
*/


/* 设置属性

var shopping = document.getElementsById("purchase");
alert (shopping.getAttribute("title"));  =>null
shopping.setAttribute("title",a list of goods);
alert (shopping.getAttribute("title")); =>a list of goods

*/

/* 批量设置属性
var paras = document.getElementsByTagName("p");
for (var i=0; i< paras.length; i++ )
{
	var title_text = paras[i].getAttribute("title");
	if (title_text) {
		paras[i].setAttribute("title",new title);
		alert(paras[i].getAttribute("title"));
		}
}
=>从文档中获取全部带有title属性的<p>元素，将title属性值都修改为new title，动态刷新不影响源文件的内容
*/



	                                        

function popUp(winURL){
	window.open(winURL,"popup","width=320");//创建浏览器窗口，
	//三个参数可选，第一个参数打开网页的URL，第二个参数为新窗口的名字，第三个桉树新窗口的各种属性
	}

/*，没有对象检测
window.onload = prepareLinks;
function prepareLinks(){
	var links = document.getElementsByTagName("a");
	for (var i=0; i<links.length; i++ )
	{
		if (links[i].getAttribute("class") == "popup")
		{
			links[i].onclick = function{
				popUp(this.getAtrribute("href"));
				return flase;//点击链接无效，在新窗口打开
		}
	}
	*/
//有对象检测
/*    window.onload = function() {
	if (!document.getElementsByTagName) return false;
	var lnks = document.getElementsByTagName("a");
	for (var i=0; i<lnks.length; i++ )
	{
		if (lnks[i].getAttribute("class") == "popup")
		{
			lnks[i].onclick = function(){
				popUp(this.getAttribute("href"));
				return flase;//点击链接无效，在新窗口打开
		          }
	    }
	}
}//??????????????????????????????????????????????????????????
*/


//javascript与HTML分离：
function prepareGallery(){
	if (!document.getElementsByTagName) return false;
	if (!document.getElementById) return false;
	if (!document.getElementById("imagegallery")) return false;
	var gallery = document.getElementById("imagegallery");
	var lnks = gallery.getElementsByTagName("a");
	for (var i=0; i<lnks.length; i++ )
	{
		lnks[i].onclick = function(){
			return showPic(this) ? false : true;
		}
		lnks[i].onkeypress = lnks[i].onclick;//use Tab to open links
	}
}

 function showPic(whichpic){
    if (!document.getElementById("placeholder"))
    {
		return false;
    }
	var source = whichpic.getAttribute("href");
	var placeholder = document.getElementById("placeholder");
	if (placeholder.nodeName != "IMG")
	{
		return false;
	}
	placeholder.setAttribute("src",source);//src等号右边替换
	if (document.getElementById("description"))
	{
		var text = whichpic.getAttribute("title") ? whichpic.getAttribute("title") : "";
	}
	var description = document.getElementById("description");
	if (description.firstChild.nodeType == 3)//元素节点nodeType==1;属性节点nodeType==2；文本节点nodeType==3；
	    {
		description.firstChild.nodeValue = text;//alert(description.nodeValue);返回的值为null，<p>元素本身的值为空 
		                                        //description.childNodes[0].nodeValue = text;等价于上一个语句
	    }
    return true;
}

	//网页加载完触发onload事件，这个事件与window事件相关联
	//共享多个onload事件
function addLoadEvent(func){
	var onload = window.onload;
	if (typeof window.onload != 'function')
	{
		window.onload = func;
	}
	 else{
		 window.onload = function(){
			 oldonload();
			 func();
		 }
	 }
}


function add(){
	var para=document.createElement("p");//创建新的元素
	var add = document.getElementById("add");
	add.appendChild(para);//para成为add的子节点
	var txt = document.createTextNode("This sentence is created by js function. The following use the same way:");
	para.appendChild(txt);
}
function addtwo(){
	var para=document.createElement("span");//创建新的元素
	var txt1 = document.createTextNode(" Love is a touch ");
    para.appendChild(txt1);
	var em = document.createElement("em");
	var txt2 = document.createTextNode("and");
	em.appendChild(txt2);
	para.appendChild(em);
	var txt3 = document.createTextNode(" yet not a touch!");
	para.appendChild(txt3);//para成为add的子节点
	var addtwo = document.getElementById("addtwo");
	addtwo.appendChild(para);
}
function addthree(){
	var now = document.getElementById("addtwo");
	var para=document.createElement("p");
	var txt1 = document.createTextNode(" Have ");
    para.appendChild(txt1);
	var em = document.createElement("em");
	var txt2 = document.createTextNode("a");
	em.appendChild(txt2);
	para.appendChild(em);
	var txt3 = document.createTextNode(" try!");
	para.appendChild(txt3);//para成为add的子节点
	now.parentNode.insertBefore(para,now);//元素的父节点元素必须是另一个元素节点（属性节点和文本节点的子元素不允许是元素节点）
}


//Ajax:
function getHTTPObject(){
	if (typeof XMLHttpRequest == "undefined")//eorr:undifine
	{
		XMLHttpRequest = function(){
			try{return new ActiveXObject("Msxml2.XMLHTTP.6.0");}
			catch (e){}
			try{return new ActiveXObject("Msxml2.XMLHTTP.3.0");}
			catch (e){}
			try{return new ActiveXObject("Msxml2.XMLHTTP");}
			catch (e){}
			return false;
	       }
	}
	return new XMLHttpRequest();
}

function getNewContent(){
	var request = getHTTPObject();
	if (request)
	{
		request.open("GET", "example.txt", true);
		request.onreadystatechange = function(){
			if (request.readyState == 4){//readyState共有4个属性，0表示初始化；1正在加载；2加载完成；3正在交互；4完成
				var para = document.createElement("p");
				var txt = document.createTextNode(request.responseText);
				para.appendChild(txt);
				document.getElementById("addfour").appendChild(para);
			}
	};
	request.send(null);
	}else{
		alert("sorry,your brower doesn't support ajax");
	}
	}
//Ajax有问题 ？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？

function highlightTitle(){
	if (!document.getElementsByTagName) return false;
	var nav = document.getElementById("nn");
	var li = nav.getElementsByTagName("li");
  
	for (var i=0; i<li.length; i++ )
	{
		li[i].onmouseover = function(){
			this.style.backgroundColor = "#3f0";}
		li[i].onmouseout = function(){
			this.style.backgroundColor = "#9cf";}

	}
}



//移动内容：按指定目的和间隔时间移动的抽象函数
function moveElement(elementID,final_x,final_y,interval,ds){
	if (!document.getElementById) return false;
	if (!document.getElementById(elementID)) return false;
	var elem = document.getElementById(elementID);

	if (elem.movement){
		clearTimeout(elem.movement);
	}//若当前元素在执行移动时就已经有一个movement属性，就用clearTimeout函数对它进行复位
	if (!elem.style.left){
		elem.style.left = "0px";
	}
	if (!elem.style.top){
		elem.style.top = "0px";
	}
	//获取当年坐标的数字值，parseInt函数
	var xpos = parseInt(elem.style.left);
	var ypos = parseInt(elem.style.top);
	if (xpos == final_x && ypos == final_y){
		return true;
	}
	var dist = 0;
	if (xpos < final_x){
		dist = Math.ceil((final_x-xpos)/ds);//Math.ceil返回一个不小于dist的整数
		xpos =xpos+dist;
	}
	if (xpos > final_x){
		dist = Math.ceil((xpos-final_x)/ds);
		xpos = xpos-dist;
	}
	if (ypos < final_y){
		dist = Math.ceil((final_y-ypos)/ds);
		ypos =ypos+dist;
	}
	if (ypos > final_y){
		dist = Math.ceil((ypos-final_y)/ds);
		ypos = ypos-dist;
	}
	//改变后的坐标
	elem.style.left = xpos+"px"
	elem.style.top = ypos+"px";
	var repeat = "moveElement('"+elementID+"',"+final_x+","+final_y+","+interval+","+ds+")";
	elem.movement = setTimeout(repeat,interval);//预定一段时间只有执行
}

//用JavaScript定义元素属性，并移动
function positionMove(){
	if (!document.getElementById) return false;
	if (!document.getElementById("move")) return false;
	var move = document.getElementById("move");
	move.style.position = "relative";
	move.style.left = "50px";
	move.style.top = "5px";
	moveElement("move",600,0,2,500);
}



//inserAfter函数，inserBefore()由DOM提供
function insertAfter(newElement,targetElement){
	var parent = targetElement.parentNode;
	if (parent.lastChild == targetElement){
		parent.appendChild(newElement);//如果新元素与目标元素属性值相同，新元素恰好插到目标元素之后
	}
	else{
		parent.insertBefore(newElement,targetElement.nextSibling);//否则新元素插到目标元素的下一个兄弟元素之前
	}
	}



//缩略图显示
function prepareSlideshow(){
	//确保浏览器支持DOM方法
	if (!document.getElementById) return false;
	if (!document.getElementsByTagName) return false;
	//确保元素存在
	if (!document.getElementById("linklist")) return false;
	//添加图片的div与属性
	var div = document.createElement("div");
	div.setAttribute("id","slideshow");
	var img = document.createElement("img");
	img.setAttribute("src","../../../../PIC/13.png");
	img.setAttribute("id","preview");
	div.appendChild(img);
    var list = document.getElementById("linklist");
	insertAfter(div,list);//函数在上面
	//遍历获取连接
	//var preview = document.getElementById("preview");
	//preview.style.position = "absolute";已经在CSS定义
	var li = list.getElementsByTagName("a");
	//添加动画效果
    li[0].onmouseover = function(){
		moveElement("preview",0,0,0,10);
	}
	li[1].onmouseover = function(){
		moveElement("preview",-150,0,10,10);
	}
	li[2].onmouseover = function(){
		moveElement("preview",-300,0,10,10);
	}
	li[3].onmouseover = function(){
		moveElement("preview",-450,0,10,10);
	}
}







//addLoadEvent(prepareGallery);
window.onload = function(){
    prepareGallery();
	add();
	addtwo();
	addthree();
	getNewContent();
	highlightTitle();
	positionMove();
	prepareSlideshow();
}//onload事件共享