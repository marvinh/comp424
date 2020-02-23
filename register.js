//Strong Weak Password Checker


//Get Questions

$.getJSON("getQuestions.php",function (data) {
    console.log(data);
    document.getElementById("questions").innerHTML = '<p class="col-6"> <strong> Security Questions </strong> </p>';
    for(x in data)
    {
        document.getElementById("questions").innerHTML+=
        '<div class="form-group col-6"> <label> '+data[x]+' </label> <input type="text" class="form-control" placeholder="Answer" required/> </div> '
    }
    
});