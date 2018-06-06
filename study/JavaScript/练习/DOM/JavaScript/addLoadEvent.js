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
