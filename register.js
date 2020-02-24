//Strong Weak Password Checker


//Get Questions

$.getJSON("getQuestions.php",function (data) {
    console.log(data);
    document.getElementById("questions").innerHTML = '<p class="col-6"> <strong> Security Questions </strong> </p>';
    for(x in data)
    {
        document.getElementById("questions").innerHTML+=
        '<div class="form-group col-6"> <label> '+data[x]["question"]+' </label> <input name="answer-'+x+'" type="text" class="form-control" placeholder="Answer" required/> </div> '
    }
    
});


// Registration Form
$(document).ready(function(){
    let $registrationForm = $('#registrationForm');
    
    if ($registrationForm.length == 0 ) return; // cancel process not the registration page

    $registrationForm.on('submit', function(e){
        e.preventDefault();
        let data = 'username='+$(this).find('input[name="username"]').val()+
                   'email='+$(this).find('input[name="email"]').val()+
                   'password='+$(this).find('input[name="password"]').val()+
                   'repeat-password='+$(this).find('input[name="repeat-password"]').val()+
                   'first-name='+$(this).find('input[name="first-name"]').val()+
                   'last-name='+$(this).find('input[name="last-name"]').val()+
                   'birth-date='+$(this).find('input[name="birth-date"]').val()+
                   'answer-0='+$(this).find('input[name="answer-0"]').val()+
                   'answer-1='+$(this).find('input[name="answer-1"]').val()+
                   'answer-2='+$(this).find('input[name="answer-2"]').val();
        // attempt to insert user
        $.ajax({
            type: "POST",
            url: "register.php",
            data: data,
            success: function(response) {
                document.getElementById("server-notice").innerHTML = '<p>' + response + '</p>'
            },
            failure: function(error) {
                console.log(error);
            }
            // dataType: 
          });



    })

});