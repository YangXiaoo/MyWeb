function insertAfter(newElement,targetElement){
	var parent = targetElement.parentNode;
	if (parent.lastChild == targetElement){
		parent.appendChild(newElement);//如果新元素与目标元素属性值相同，新元素恰好插到目标元素之后
	}
	else{
		parent.insertBefore(newElement,targetElement.nextSibling);//否则新元素插到目标元素的下一个兄弟元素之前
	}
	}
