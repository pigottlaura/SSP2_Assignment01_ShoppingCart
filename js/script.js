window.onload = function(){
    document.getElementById("login").addEventListener("submit", validateForm);
    document.getElementById("register").addEventListener("submit", validateForm);
}


function validateForm(e){
    var formValidated = true;
    var formInputs = e.target.querySelectorAll("input:not([type='submit'])");

    formInputs.forEach(function(input, i){

        if(input.hasAttribute("required")) {
            if(input.value.length == 0){
                formValidated = false;
                console.log("value required for " + input.getAttribute("name"));
            }
        }

        if(input.hasAttribute("data-match")){
            if(input.value != document.getElementsByName(input.getAttribute("data-match"))[0].value) {
                formValidated = false;
                console.log(input.getAttribute("name") + " does not match with " + input.getAttribute("data-match"));
            }
        }
    });

    if(formValidated){

    } else {
        e.preventDefault();
    }
}