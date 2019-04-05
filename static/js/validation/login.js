$(document).ready(function () {

    $("#loginForm").validate({
        rules: {
            email: {
                required: true,
                email: true,
                maxlength: 50
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 20
            },
        },
        messages: {
            email: {
                required: "Email is required",
                email: "Please enter a valid email address",
                maxlength: "Email is limited to 50 characters"
            },
            password: {
                required: "Password is prequired",
                minlength: "Password must be at least 6 characters long",
                maxlength: "Password is limited to 20 characters"
            },
        },
        errorElement: "div",
        errorClass: "is-invalid",
        validClass: "is-valid",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("invalid-feedback");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }
        },

        highlight: function (element, errorClass, validClass) {
            $(element).addClass(errorClass).removeClass(validClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass(errorClass).addClass(validClass);
        },
        submitHandler: function (form) {
            //generate a hash SHA1
            $("#password").val(generateSHA1Hash($("#password").val()));
            submitFormAjax();
        },
    });

    $("#loginButton").click(() => {
        $("#loginForm").submit();
    });

});


const submitFormAjax = () => {

    let formData = $("#loginForm").serialize();

    $.ajax({
        url: "login",
        type: "POST",
        data: formData
    }).done((json) => {

        if (json.status === "ok" && json.message === "Authenticated") {
            $(location).attr("href", json.url);
        } else {
            $("#alertErrorMessage").removeAttr("hidden").text(json.message);
        }

    }).done(() => {
        $("#password").val("");
        $("#password").removeClass("is-invalid");
        $("#password-error").remove();
    }).fail((error) => {
        $("#alertErrorMessage").removeAttr("hidden").text("Impossible to authenticate. Server unreached.");
        $("#password").val("");
        console.error(error);
    });

}