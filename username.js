$("form").on('submit', function(e){
    e.preventDefault();
    var formData = $("#usernameForm").serialize();
    console.log(formData);
    $.ajax({
        type: "POST",
        url: "username.php",
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