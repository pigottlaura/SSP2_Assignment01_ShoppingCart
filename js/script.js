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

    if(document.getElementById("update-details")){
        document.getElementById("update-details").addEventListener("submit", validateForm);
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
        case "changePassword": {
            e.target.parentNode.className = "hidden";
            document.getElementById("newPassword").className = "";

            // Creating a hidden input element, to act as a marker server side, to
            // indicate that the user is also choosing to change their password
            var newPasswordHoneypot = document.createElement("input");
            newPasswordHoneypot.setAttribute("name", "password_change");
            newPasswordHoneypot.setAttribute("type", "hidden");
            document.getElementById("update-details").appendChild(newPasswordHoneypot);
            break;
        }
        case "addAddress": {
            e.target.className = "hidden";
            var addressFieldset = document.getElementById("address");
            addressFieldset.className = "";
            var addressInputs = addressFieldset.querySelectorAll("input");

            // Making all address fields required
            for(var i=0; i<addressInputs.length; i++){
                addressInputs[i].setAttribute("required", "required");
            }

            // Creating a hidden input element, to act as a marker server side, to
            // indicate that the user is also choosing to add a new address
            var newAddressHoneypot = document.createElement("input");
            newAddressHoneypot.setAttribute("name", "address_new");
            newAddressHoneypot.setAttribute("type", "hidden");
            document.getElementById("update-details").appendChild(newAddressHoneypot);
            break;
        }
        case "resetFields": {
            var currentForm = e.target.parentNode;
            var formFields = currentForm.querySelectorAll("input");

            // Loop through all of the form fields, and empty their values (unless they are a functional button)
            for(var i=0; i< formFields.length; i++){
                if(formFields[i].getAttribute("type") != "submit"
                    && formFields[i].getAttribute("type") != "reset"
                    && formFields[i].getAttribute("type") != "button"
                    && formFields[i].getAttribute("disabled") == null){
                    formFields[i].value = "";
                }
            }
        }
    }

    // If the target is an "addToCart" button (indicated by it's class), initiate an AJAX
    // request to the server to add this item to the cart
    if(e.target.classList.contains("addToCart")){
        var requestURL = "ajax.php?action=addToCart&productId=" + parseInt(e.target.id);

        ajaxRequest(requestURL, function(response){
            // When a response is received from the server, update the number of items and order
            // total in the header
            var jsonResponse = JSON.parse(response.responseText);
            document.getElementById("scNumItems").innerHTML = jsonResponse.shoppingCartTotalItems;
            document.getElementById("scTotal").innerHTML = jsonResponse.shoppingCartTotalCost;
        });
    }

    // Fix for IE and Firefox not recognising click on link in a button
    if(e.target.tagName == "BUTTON" && e.target.querySelectorAll("a").length > 0){
        e.target.querySelector("a").click();
    }
}


function validateForm(e){
    var formValidated = true;
    var formInputs = e.target.querySelectorAll("input:not([type='submit'])");

    // Loop through each of the form inputs
    formInputs.forEach(function(input, i){

        //
        if(input.hasAttribute("required")) {
            if(input.value.length == 0){
                formValidated = false;
                input.setAttribute("title", "Value required for " + input.getAttribute("name"));
            }
        }

        // If this input has a data match attribute, ensure it's value matches with the input specified i.e.
        // password and confirm_password must match before the form can be submitted
        if(input.hasAttribute("data-match") && input.parentNode.parentNode.classList.contains("hidden") == false){
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

        // This is the username input, check if it has been marked as available (following the
        // AJAX requests made to the server when it was entered
        if(input.getAttribute("name") == "username"){
            if(input.getAttribute("data-available") == "false"){
                formValidated = false;
            }
        }
    });

    // If the form is not validated, then do not allow it to be submitted
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
            //console.log(response.responseText);
            var jsonResponse = JSON.parse(response.responseText);

            // Determine if the username is available
            usernameAvailable = jsonResponse.usernameAvailable * jsonResponse.dataValidated == 1 ? true : false;

            // Figure out what icon to display beside the username
            var setClassTo = jsonResponse.usernameAvailable ? "icon icon-yes" : "icon icon-no";
            setClassTo = jsonResponse.dataValidated ? setClassTo : "icon icon-error";

            // Determine if there is errors that need to be displayed in the input's title
            var title = jsonResponse.errors.length > 0 ? jsonResponse.errors : "";

            // Update the form input
            requestedUsernameInput.value = jsonResponse.username;
            requestedUsernameInput.setAttribute("title", title);
            requestedUsernameInput.setAttribute("data-available", usernameAvailable.toString());

            // Update the input's icon
            requestedUsernameSpan.className = setClassTo;
            requestedUsernameSpan.setAttribute("title", title);

            return usernameAvailable;
        });
    } else {
        // Default the input's icon to an empty icon
        requestedUsernameSpan.className = "icon";
    }
}
function sortProducts(e){
    var sortBy = e.target.value.split("-")[0];
    var sortOrder = e.target.value.split("-")[1];

    document.cookie = "sortBy=" + sortBy;
    document.cookie = "sortOrder=" + sortOrder;
    document.cookie = "productPage=0";

    // Call to initiate an AJAX request to update the products based on the values above
    refreshProducts();
}

function changeItemsPerPage(e){
    var itemsPerPage = e.target.value;

    document.cookie = "itemsPerPage=" + itemsPerPage;
    document.cookie = "productPage=0";

    // Call to initiate an AJAX request to update the products based on the values above
    refreshProducts();
}

function refreshProducts(){
    if(document.getElementById("products")){
        var category = getParamValue("category").length > 0 ? getParamValue("category") : 1;
        var requestURL = "ajax.php?action=getProducts&category=" + category;

        ajaxRequest(requestURL, function(response){
            // Update the display of all products
            document.getElementById("products").innerHTML = response.responseText;
        });
    }
}

function ajaxRequest(url, cb){
    // Making AJAX requests to the server
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Call the function supplied as an argument i.e. the callback function,
            // passing back the response object
            cb(this);
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}

function getCookieValue(cookieName){
    // Getting the cookies string and splitting it into an array
    var allCookies = document.cookie.split('; ');
    var cookieValue = "";

    // Looping through all the name=value pairs of the cookies array
    allCookies.forEach(function(cookie) {
        if(cookie.split("=")[0] == cookieName){
            // Since this cookie's name matches with the one requested, return
            // its value
            cookieValue = cookie.split("=")[1];
        }
    });
    return cookieValue;
}

function cookieExists(cookieName){
    // Checking if the cookie name exists within the document's cookie string
    var result = document.cookie.indexOf(cookieName) > -1 ? true : false;
    return result;
}

function incrementCookie(cookieName, incrementBy){
    var newCookieValue = parseInt(getCookieValue("productPage"))  + incrementBy;

    // Ensuring the resulting cookie value will always be 0 or more before
    // setting it as a cookie
    if(newCookieValue > -1){
        document.cookie = "productPage=" + newCookieValue;
        refreshProducts();
    }
}

function getParamValue(paramName){
    var result = "";

    // Checking if there is currently a query string in the URL
    if(queryStringExists()){
        // Accessing the query string portion of the URL, by splitting the
        // current location at "?"
        var queryString = document.location.toString().split("?")[1];

        // Splitting the query string into an array (split after every "&")
        var params = queryString.split("&");

        // Looping through alll of the parameters of the query string
        for(var i=0; i < params.length; i++){
            var name = params[i].split("=")[0];
            if(name == paramName){
                // Since this parameter matches with the one requested, return it's value
                // by splitting it at the "=" and taking the second portion of the
                // resulting array
                result = params[i].split("=")[1];
            }
        }
    }
    return result;
}

function queryStringExists(){
    var result = false;

    // Checking if the current URL contains a query string
    if(document.location.toString().indexOf('?') > -1){
        result = true;
    }
    return result;
}