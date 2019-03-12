/**
 * Performs the form input validation for User Password Change Use Case
 * @author: Leonardo Otoni
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
                required: "Confirm Password is required",
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
            $("#currentPassword").val(generateSHA1Hash($("#currentPassword").val()));
            $("#password").val(generateSHA1Hash($("#password").val()));
            $("#confirmPassword").val(generateSHA1Hash($("#confirmPassword").val()));
            submitFormAjax();
        },

    });

    $('#saveButton').click((event) => {
        //reset the old messages
        $("#alertSuccessMessage").attr("hidden", true);
        $("#alertErrorMessage").attr("hidden", true);
        $("#alertWarningMessage").attr("hidden", true);

        $('#changePasswordform').submit();
    });

});

//specific field validation, not allowing currentPassword equals the new one
$.validator.addMethod("differentCurrentPassword", function (value, element) {
    let currentPassword = $("#currentPassword").val();
    if (currentPassword !== value)
        return true;
    return false;
}, "Passwords are matching.");

const submitFormAjax = () => {

    let formData = $("#changePasswordform").serialize();
    $.ajax({
        url: "changepasswd",
        type: "POST",
        data: formData
    }).done((json) => {
        if (json.status === "ok") {
            $("#alertSuccessMessage").removeAttr("hidden").text(json.message);
        } else {
            $("#alertErrorMessage").removeAttr("hidden").text(json.message);
        }
    }).done(() => {
        $("#currentPassword").val("");
        $("#password").val("");
        $("#confirmPassword").val("");
        $("input").removeClass("is-valid");
    }).fail((error) => {
        console.error(error);
    });
};