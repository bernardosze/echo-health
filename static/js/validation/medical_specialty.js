$(document).ready(() => {

    //enable form validation (without any rules) and styles
    $("#medicalSpecialtyForm").validate({
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
            submitFormAjax();
        },
    });

    //Get data from server
    $.getJSON("medicalspecialty?JSON=true", (result) => {
        if (result.status === "Invalid_Session") {
            //User session is invalid.
            $(location).attr("href", "login");
        } else if (result.status === "ok") {
            renderData(result.data);
        } else {
            $("#alertErrorMessage").removeAttr("hidden").text(result.message);
        }
    }).done(() => {
        setValidationRulesToExistingFields();
    }).fail((error) => {
        console.error(error);
    });

    $("#newButton").click(() => {
        for (let i = 0; i < 5; i++) {
            renderEmptyFields();
        }
        setValidationRulesToNewFields();
    });

    $("#saveButton").click(() => {
        //reset the old messages
        $("#alertSuccessMessage").attr("hidden", true);
        $("#alertErrorMessage").attr("hidden", true);
        $("#alertWarningMessage").attr("hidden", true);
        $("#medicalSpecialtyForm").submit();
    });

});

const renderData = (json) => {

    const tBody = $("#dataTable")[0].tBodies[0];

    //clean data before show
    tBody.innerHTML = "";

    let sequence = 1;
    json.forEach(element => {

        const itemId = element.id;
        const itemName = element.name;

        const trTemplate =
            `<tr>
        <td class="align-middle">
            <input id="chk-${sequence}" type="checkbox" name="medicalSpecialty[${sequence}][action]" value="delete" onclick="setRowColor(event);">
        </td>
        <td>
            <input type="hidden" name="medicalSpecialty[${sequence}][id]" value="${itemId}">
            <input type="text" id="id-${sequence}" readonly class="form-control-plaintext" value="${sequence}">
        </td>
        <td><input type="text" id="name-${sequence}" class="form-control" name="medicalSpecialty[${sequence}][name]" value="${itemName}"></td>
        </tr>`;

        let newRow = tBody.insertRow(tBody.rows.length);
        newRow.id = "row-" + sequence;
        newRow.setAttribute("data-row", true);
        newRow.innerHTML = trTemplate;
        sequence++;
    });

}

//specific form field validation, not allowing fields having less than 3 characters.
$.validator.addMethod("notBlankFieldsMinLength", function (value, element) {
    if (value === "") {
        return true;
    } else {
        return value.length >= 3 ? true : false;
    }

}, "Field length is ok.");

//set form validation rules to fields
const setValidationRulesToExistingFields = () => {

    $('.form-control').each(function () {
        $(this).rules("add", {
            required: true,
            notBlankFieldsMinLength: true,
            messages: {
                required: "Medical Specialty required",
                notBlankFieldsMinLength: "Medical Specialty must have at least 3 characters length",
            }
        });
    });

}

//New Fields have a different validation rule
const setValidationRulesToNewFields = () => {

    $('.new-field').each(function () {
        //const field = '#' + object.id;
        //console.log(field);
        $(this).rules("add", {
            notBlankFieldsMinLength: true,
            messages: {
                notBlankFieldsMinLength: "Medical Specialty must have at least 3 characters length",
            }
        });
    });

}

const renderEmptyFields = () => {

    const list = document.querySelectorAll("tr[data-row]");
    let sequence = list.length + 1;
    const itemId = "name-" + parseInt($("[id^='name-']").length + 1);
    const itemName = "";

    const trTemplate =
        `<tr>
        <td class="align-middle"></td>
        <td>
            <input type="hidden" name="medicalSpecialty[${sequence}][id]" value="">
            <input type="hidden" name="medicalSpecialty[${sequence}][action]" value="insert">
            <input type="text" readonly class="form-control-plaintext" value="${sequence}">
        </td>
        <td><input id="${itemId}" type="text" class="form-control new-field" name="medicalSpecialty[${sequence}][name]" value="${itemName}"></td>
        </tr>`;

    const tBody = $("#dataTable")[0].tBodies[0];
    let newRow = tBody.insertRow(tBody.rows.length);
    newRow.id = "row-" + sequence;
    newRow.setAttribute("data-row", true);
    newRow.innerHTML = trTemplate;

}

const setRowColor = (event) => {

    const chkId = event.target.id;
    const rowId = chkId.replace("chk-", "row-");

    if (event.target.checked) {
        $("#" + rowId).addClass("table-row-to-delete");
    } else {
        $("#" + rowId).removeClass("table-row-to-delete");
    }
}

const submitFormAjax = () => {

    //event.preventDefault();
    let formData = $("#medicalSpecialtyForm").serialize(); //$(event.target).serialize();
    $.ajax({
        url: "medicalspecialty",
        type: "POST",
        data: formData
    }).done((json) => {

        if (json.status === "ok") {
            renderData(json.data);
            $("#alertSuccessMessage").removeAttr("hidden").text(json.message);
        } else {
            $("#alertErrorMessage").removeAttr("hidden").text(json.message);
        }

    }).done(() => {
        $("input").removeClass("is-valid");
        $("input").removeClass("is-invalid");
    }).fail((error) => {
        console.error(error);
    });

    return false;

}