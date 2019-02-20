/**
 * Performs the form input validation for User Password Change Use Case
 * Author: Leonardo Otoni
 */

$(document).ready(function () {

    $("#changePasswordform").validate({
        rules: {
            currentPassword: {
                required: true,
                minlength: 6,
                maxlength: 20
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 20,
                differentCurrentPassword: true
            },
            confirmPassword: {
                required: true,
                minlength: 6,
                maxlength: 20,
                equalTo: "#password"
            },
        },
        messages: {
            currentPassword: {
                required: "Current Password is required",
                minlength: "Current Password must be at least 6 characters long",
                maxlength: "Current Password is limited to 20 characters",
            },
            password: {
                required: "New Password is required",
                minlength: "New Password must be at least 6 characters long",
                maxlength: "New Password is limited to 20 characters",
                differentCurrentPassword: "New Password cannot be equals to Current Password"
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
        },
        submitHandler: function (form) {
            //generate a hash SHA1
            $("#currentPassword").val(generateSHA1Hash($("#email").val() + $("#currentPassword").val()));
            $("#password").val(generateSHA1Hash($("#email").val() + $("#password").val()));
            form.submit();
        },

    });

});

//specific field validation, not allowing currentPassword equals the new one
$.validator.addMethod("differentCurrentPassword", function (value, element) {
    let currentPassword = $("#currentPassword").val();
    if (currentPassword !== value)
        return true;
    return false;
}, "Passwords are matching.");
