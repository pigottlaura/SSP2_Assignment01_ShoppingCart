window.onload = function(){
    if(document.getElementById("login")){
        document.getElementById("login").addEventListener("submit", validateForm);
    }

    if(document.getElementById("register")) {
        document.getElementById("register").addEventListener("submit", validateForm);
    }

    if(document.getElementById("sortBy")) {
        var sortBySelect = document.getElementById("sortBy");
        console.log(document.cookie);
        console.log(getCookieValue("sortOrder"));
        if(document.cookie.indexOf("sortBy") > -1 && document.cookie.indexOf("sortOrder") > -1){
            console.log(getCookieValue("sortBy") + "-" + getCookieValue("sortOrder"));
            sortBySelect.value = getCookieValue("sortBy") + "-" + getCookieValue("sortOrder");
        }
        sortBySelect.addEventListener("change", sortProducts);
    }

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

function sortProducts(e){
    var sortBy = e.target.value.split("-")[0];
    var sortOrder = e.target.value.split("-")[1];

    document.cookie = "sortBy=" + sortBy;
    document.cookie = "sortOrder=" + sortOrder;

    location.reload(true);
}

function getCookieValue(cookieName){
    var allCookies = document.cookie.split('; ');
    var cookieValue = "";

    allCookies.forEach(function(cookie) {
        if(cookie.split("=")[0] == cookieName){
            cookieValue = cookie.split("=")[1];
        }
    });
    return cookieValue;
}