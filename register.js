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
$("form").on('submit', function(e){
        e.preventDefault();
        var formData = $("#registerForm").serialize();
        console.log(formData);
        $.ajax({
            type: "POST",
            url: "register.php",
            data: formData,
            success: function(response) {
                //console.log(response);
                document.getElementById("server-notice").innerHTML = "<p>"+ response +"</p>";
            },
            failure: function(error) {
                console.log(error);
            }
            
        });


});
