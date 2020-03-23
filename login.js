
$('#loginForm').on('submit', function(e){
        e.preventDefault();
        var formData = $("#loginForm").serialize();
		
        $.ajax({
                type: "POST",
                url: "login.php",
                data: formData,
                success: function(response) {
                    //console.log(response);
                    if(response=="success")
                    {
                        window.location = "secret.php";
                    }else{
                        grecaptcha.reset();
                        document.getElementById("server-notice").innerHTML = "<p>"+ response +"</p>";
                    }
                },
                failure: function(error) {
                    console.log(error);
                }
                
            });

        }
        
);