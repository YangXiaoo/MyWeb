function insertAfter(newElement,targetElement){
	var parent = targetElement.parentNode;
	if (parent.lastChild == targetElement){
		parent.appendChild(newElement);//�����Ԫ����Ŀ��Ԫ������ֵ��ͬ����Ԫ��ǡ�ò嵽Ŀ��Ԫ��֮��
	}
	else{
		parent.insertBefore(newElement,targetElement.nextSibling);//������Ԫ�ز嵽Ŀ��Ԫ�ص���һ���ֵ�Ԫ��֮ǰ
	}
	}
