window.onload = function(){
    initialiseCookies();
    setCookieOptions();
    addEventListeners();
    refreshProducts();
}

function initialiseCookies(){
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
}

function setCookieOptions() {
    if(document.getElementById("products")) {
        document.getElementById("sortBy").value = getCookieValue("sortBy") + "-" + getCookieValue("sortOrder");
        document.getElementById("itemsPerPage").value = getCookieValue("itemsPerPage");
    }
}

function addEventListeners() {
    document.addEventListener("click", clickEvent);

    if(document.getElementById("login")){
        document.getElementById("login").addEventListener("submit", validateForm);
    }

    if(document.getElementById("register")) {
        document.getElementById("register").addEventListener("submit", validateForm);
    }

    if(document.getElementById("products")) {
        document.getElementById("sortBy").addEventListener("change", sortProducts);
        document.getElementById("itemsPerPage").addEventListener("change", changeItemsPerPage);
    }
}

function clickEvent(e){
    switch(e.target.id) {
        case "prevPage": {
            incrementCookie("productPage", -1);
            break;
        }
        case "nextPage": {
            incrementCookie("productPage", 1);
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

    refreshProducts();
}

function changeItemsPerPage(e){
    var itemsPerPage = e.target.value;

    document.cookie = "itemsPerPage=" + itemsPerPage;

    refreshProducts();
}

function refreshProducts(){
    if(document.getElementById("products")){
        var category = getParamValue("category").length > 0 ? category : 1;
        var requestURL = "ajax.php?action=getProducts&category=" + category + "";

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("products").innerHTML = this.responseText;
            }
        };

        xhttp.open("GET", requestURL, true);
        xhttp.send();
    }
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

function incrementCookie(cookieName, incrementBy){
    var newCookieValue = parseInt(getCookieValue("productPage"))  + incrementBy;
    if(newCookieValue > -1){
        document.cookie = "productPage=" + newCookieValue;
        refreshProducts();
    }
}

function getParamValue(paramName){
    var result = "";
    if(queryStringExists()){
        var queryString = document.location.toString().split("?")[1];
        var params = queryString.split("&");
        for(var i=0; i < params.length; i++){
            var name = params[i].split("=")[0];
            if(name == paramName){
                result = params[i].split("=")[1];
            }
        }
    }
    return result;
}

function queryStringExists(){
    var result = false;
    if(document.location.toString().indexOf('?') > -1){
        result = true;
    }
    return result;
}