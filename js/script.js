window.onload = function(){
    initialiseCookies();
    setCookieOptions();
    addEventListeners();
    refreshProducts();
}

function initialiseCookies(){
    document.cookie = "productPage=0";
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
        var keyPressTimeout = null;
        document.getElementById("register").addEventListener("submit", validateForm);
        document.getElementById("requestedUsername").addEventListener("blur", checkUsernameAvailablility);
        document.getElementById("requestedUsername").addEventListener("keyup", function(e) {
            if(keyPressTimeout){
                clearTimeout(keyPressTimeout);
            }
            keyPressTimeout = setTimeout(checkUsernameAvailablility, 500);
        });
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

    if(e.target.classList.contains("addToCart")){
        var requestURL = "ajax.php?action=addToCart&productId=" + parseInt(e.target.id);
        ajaxRequest(requestURL, function(response){
            var jsonResponse = JSON.parse(response.responseText);
            document.getElementById("scNumItems").innerHTML = jsonResponse.shoppingCartTotalItems;
            document.getElementById("scTotal").innerHTML = jsonResponse.shoppingCartTotalCost;
        });
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
            var inputToMatchTo = document.getElementsByName(input.getAttribute("data-match"))[0];
            if(input.value != inputToMatchTo.value) {
                var error = input.getAttribute("name") + " does not match with " + input.getAttribute("data-match");
                formValidated = false;

                document.getElementById("passwordMatch").className = "icon icon-error";
                document.getElementById("passwordMatch").setAttribute("title", error);

                inputToMatchTo.setAttribute("title", error);
            } else {
                document.getElementById("passwordMatch").className = "icon";
            }
        }

        if(input.getAttribute("name") == "username"){
            if(input.getAttribute("data-available") == "false"){
                formValidated = false;
            }
        }
    });

    if(formValidated == false) {
        e.preventDefault();
    }
}

function checkUsernameAvailablility(e){
    var usernameAvailable = false;
    var requestedUsernameInput = document.getElementById("requestedUsername");
    var requestedUsernameSpan = document.getElementById("requestedUsernameAvailable");

    if(requestedUsernameInput.value.length > 0){
        var requestURL = "ajax.php?action=checkUsernameAvailability&requestedUsername=" + requestedUsernameInput.value;

        ajaxRequest(requestURL, function(response){
            var jsonResponse = JSON.parse(response.responseText);
            usernameAvailable = jsonResponse.usernameAvailable * jsonResponse.dataValidated == 1 ? true : false;

            var setClassTo = jsonResponse.usernameAvailable ? "icon icon-yes" : "icon icon-no";
            setClassTo = jsonResponse.dataValidated ? setClassTo : "icon icon-error";

            requestedUsernameInput.value = jsonResponse.username;
            requestedUsernameInput.setAttribute("title", jsonResponse.error);
            requestedUsernameInput.setAttribute("data-available", usernameAvailable.toString());

            requestedUsernameSpan.setAttribute("title", jsonResponse.error);
            requestedUsernameSpan.className = setClassTo;

            return usernameAvailable;
        });
    } else {
        requestedUsernameSpan.className = "icon";
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
    document.cookie = "productPage=0";

    refreshProducts();
}

function refreshProducts(){
    if(document.getElementById("products")){
        var category = getParamValue("category").length > 0 ? getParamValue("category") : 1;
        var requestURL = "ajax.php?action=getProducts&category=" + category;

        ajaxRequest(requestURL, function(response){
            document.getElementById("products").innerHTML = response.responseText;
        });
    }
}

function ajaxRequest(url, cb){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            cb(this);
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
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