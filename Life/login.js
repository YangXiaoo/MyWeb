pass = function (){
	var pa = document.form.password.value;
	if(pa == "Ab127000")
		return true;
	else
		return false;
}
 name = function(){
	var na = document.form.name.value;
	if(na == "involute")
		return true;
	else
		return false;
}
 function submit(){
	 if(pass && name){
	 return true;
	 }
	 else{
		 alert("������˺Ŵ���")��
			 return false;
			 }
		 }
window.onload = function(){
	submit();
}


