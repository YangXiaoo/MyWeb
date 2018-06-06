         function check(form) {

          if((form.username.value !=="") && (form.password.value !== "")) {
                return true;
           }
		   else{
                  if(form.username.value !==""){
					  
                        alert("Please put password")
							return false
						form.password.focous();

                     }
					 else{
						  
		                 alert("Please put username")
							 return false
						 form.username.focous();	
		 }
         
         }
		 }
 