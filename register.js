//Strong Weak Password Checker


//Get Questions

// $.getJSON("getQuestions.php",function (data) {
//     console.log(data);
//     document.getElementById("questions").innerHTML = '<p class="col-6"> <strong> Security Questions </strong> </p>';
//     for(x in data)
//     {
//         document.getElementById("questions").innerHTML+=
//         '<div class="form-group col-6"> <label> '+data[x]["question"]+' </label> <input name="answer-'+x+'" type="text" class="form-control" placeholder="Answer" required/> </div> '
//     }
    
// });

var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");
var mediumRegex = new RegExp("^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{6,})");

function analyze() {
    var value = $("#password").val();
    var notice = document.getElementById("password-notice");
    if(strongRegex.test(value)) {
        notice.innerHTML = "Strong Password";
        notice.setAttribute("style", "color: green;");
    } else if(mediumRegex.test(value)) {
        notice.innerHTML = "Okay Password";
        notice.setAttribute("style", "color: orange;");
    } else {
        notice.innerHTML = "Weak Password";
        notice.setAttribute("style", "color: red;");
    }
}

function confirmRepeat()
{
    var val1 = $("#password").val();
    var val2 = $("#repeat-password").val();
    var notice = document.getElementById("repeat-notice");
    if(val1 != val2)
    {
        notice.innerHTML = "Passwords do not match!";
        notice.setAttribute("style", "color: red;");
    }else{
        notice.innerHTML = "Passwords match!";
        notice.setAttribute("style", "color: green;");
    }
}

// Registration Form
$("#registerForm").on('submit', function(e) {
        e.preventDefault();

        //var repeat = confirmRepeat();
       
        var formData = $("#registerForm").serialize();

        var val1 = $("#password").val();
        var val2 = $("#repeat-password").val();
        if(val1 != val2) {
            document.getElementById("server-notice").innerHTML = "<p> passwords do not match </p>";
        } else {

            $.ajax({
                type: "POST",
                url: "register.php",
                data: formData,
                success: function(response) {
                    //console.log(response);
                    document.getElementById("server-notice").innerHTML = "<p>"+ response +"</p>";
                    grecaptcha.reset();
                },
                failure: function(error) {
                    console.log(error);
                    grecaptcha.reset();
                }
                
            });

        }
        
        


});
