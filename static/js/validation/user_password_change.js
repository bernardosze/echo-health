/**
 * Performs the form input validation for User Password Change Use Case
 * Author: Leonardo Otoni
 */

$(document).ready(function () {

    $("#hashform").validate({
        rules: {
            password: {
                required: true,
                minlength: 6,
                maxlength: 20
            },
            confirmPassword: {
                required: true,
                minlength: 6,
                maxlength: 20,
                equalTo: "#password"
            },
        },
        messages: {
            password: {
                required: "Password is required",
                minlength: "Password must be at least 6 characters long",
                maxlength: "Password is limited to 20 characters"
            },
            confirmPassword: {
                required: "Password is required",
                minlength: "Password must be at least 6 characters long",
                maxlength: "Password is limited to 20 characters",
                equalTo: "Password confirmed does not match."
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
        }

    });

});