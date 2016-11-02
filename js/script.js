window.onload = function(){
    document.addEventListener("click", clickEvent);

    if(document.getElementById("login")){
        document.getElementById("login").addEventListener("submit", validateForm);
    }

    if(document.getElementById("register")) {
        document.getElementById("register").addEventListener("submit", validateForm);
    }

    if(document.getElementById("sortBy")) {
        var sortBySelect = document.getElementById("sortBy");
        var itemsPerPageSelect = document.getElementById("itemsPerPage");

        if(!cookieExists("productPage")){
            document.cookie = "productPage=0";
        }
        if(!cookieExists("sortBy")){
            document.cookie = "sortBy=name";
        }
        if(!cookieExists("sortOrder")){
            document.cookie = "sortOrder=asc";
        }
        if(!cookieExists("itemsPerPage")){
            document.cookie = "itemsPerPage=4";
        }

        sortBySelect.value = getCookieValue("sortBy") + "-" + getCookieValue("sortOrder");
        itemsPerPageSelect.value = getCookieValue("itemsPerPage");

        sortBySelect.addEventListener("change", sortProducts);
        itemsPerPageSelect.addEventListener("change", changeItemsPerPage);
    }
}

function clickEvent(e){
    switch(e.target.id) {
        case "prevPage": {
            console.log("prev");
            document.cookie = "productPage=" + (parseInt(getCookieValue("productPage")) - 1);
            location.reload(true);
            break;
        }
        case "nextPage": {
            console.log("next");
            document.cookie = "productPage=" + (parseInt(getCookieValue("productPage")) + 1);
            location.reload(true);
            break;
        }
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
    document.cookie = "productPage=0";

    location.reload(true);
}

function changeItemsPerPage(e){
    var itemsPerPage = e.target.value;

    document.cookie = "itemsPerPage=" + itemsPerPage;

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

function cookieExists(cookieName){
    var result = document.cookie.indexOf(cookieName) > -1 ? true : false;
    return result;
}