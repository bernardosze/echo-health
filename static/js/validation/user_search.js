/**
 * Performs the form input validation for User Searh Use Case
 * @author: Leonardo Otoni
 */

$(document).ready(function () {

    $("#userSearchForm").validate({
        rules: {
            email: {
                email: {
                    depends: function (element) {
                        return $("#email").val() !== "";
                    }
                },
                maxlength: 50,
            },
            firstName: {
                required: {
                    depends: function (element) {
                        return $("#firstName").val().length > 0 ? 3 : 0;
                    }
                },
                minlength: 3,
                maxlength: 45,
            },
            lastName: {
                required: {
                    depends: function (element) {
                        return $("#lastName").val().length > 0 ? 3 : 0;
                    }
                },
                minlength: 3,
                maxlength: 45
            },
        },
        messages: {
            newEmail: {
                email: "Please enter a valid email address",
                maxlength: "Field is limited to 50 characters",
            },
            firstName: {
                minlength: "It must have at least three characters.",
                maxlength: "Field is limited to 45 characters"
            },
            lastName: {
                minlength: "It must have at least three characters.",
                maxlength: "Field is limited to 45 characters"
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

    });

    $("#table").bootstrapTable();
    $("#table").removeAttr("hidden");
    $("#btn-search").click(function () {

        const email = $("#email").val();
        const firstName = $("#firstName").val();
        const lastName = $("#lastName").val();
        const blocked = ($("#blocked").is(':checked') ? "Y" : "");

        let queryString = "searchuser?JSON=true";
        queryString += (email ? "&email=" + email : "");
        queryString += (firstName ? "&firstName=" + firstName : "");
        queryString += (lastName ? "&lastName=" + lastName : "");
        queryString += (blocked ? "&blocked=" + blocked : "");

        $.getJSON(queryString, function (result) {

            if (result.status === "Invalid_Session") {
                $(location).attr("href", "login");
            } else if (result.status === "ok") {
                $("#table").bootstrapTable('load', result.data);
                $("#table").on('click-row.bs.table', function (row, element, field) {
                    $(location).attr("href", "setuserprofile?id=" + element.id);
                });
            } else {
                $("#table").bootstrapTable("removeAll");
            }

        }).done(function (data) {
            $("#email").val("").removeClass("is-valid");
            $("#firstName").val("").removeClass("is-valid");
            $("#lastName").val("").removeClass("is-valid");
            $("#blocked").prop("checked", false).removeClass("is-valid");
        }).fail(function (error) {
            console.error(error);
        });


    });


});
