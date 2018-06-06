         function check(form) {

          if(form.userId.value !=='involute') {
                alert("Please put right userID!!");
                form.userId.focus();
                return false;
           }
       if(form.password.value !=='Ab127000'){
                alert("Please put right  password!!");
                form.password.focus();
                return false;
         }
         return true;
         }
 