/**
 * Performs the form input validation for User Email Change Use Case
 * @author: Leonardo Otoni
 */

$(document).ready(function () {

    $("#changeEmailForm").validate({
        rules: {
            newEmail: {
                required: true,
                email: true,
                maxlength: 50,
                differentCurrentEmail: true
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 20,
            },
        },
        messages: {
            newEmail: {
                required: "Email is required",
                email: "Please enter a valid email address",
                maxlength: "Email is limited to 50 characters",
                differentCurrentEmail: "New email cannot be equals the current one",
            },
            password: {
                required: "Password is required",
                minlength: "Password must be at least 6 characters long",
                maxlength: "New Password is limited to 20 characters",
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

    $("#saveButton").click((event) => {
        //reset the old messages
        $("#alertSuccessMessage").attr("hidden", true);
        $("#alertErrorMessage").attr("hidden", true);
        $("#alertWarningMessage").attr("hidden", true);
        $("#changeEmailForm").submit();
    });

});

//specific field validation, not allowing the  new email equals the current one
$.validator.addMethod("differentCurrentEmail", function (value, element) {
    let currentEmail = $("#currentEmail").val();
    if (currentEmail !== value) {
        return true;
    } else {
        return false;
    }

}, "email are matching.");

const submitFormAjax = () => {

    let formData = $("#changeEmailForm").serialize();
    $.ajax({
        url: "changeemail",
        type: "POST",
        data: formData
    }).done((json) => {

        if (json.status === "ok") {
            $("#currentEmail").val($("#newEmail").val());
            $("#newEmail").val("");
            $("#alertSuccessMessage").removeAttr("hidden").text(json.message);
        } else {
            $("#alertErrorMessage").removeAttr("hidden").text(json.message);
        }

    }).done(() => {
        $("#password").val("");
        $("input").removeClass("is-valid");
    }).fail((error) => {
        console.error(error);
    });


}