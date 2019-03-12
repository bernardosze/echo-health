$(document).ready(function () {

    //form validation rules
    $("#setUserProfileForm").validate({
        rules: {
            firstName: {
                required: true,
                maxlength: 45
            },
            lastName: {
                required: true,
                maxlength: 45
            },
            email: {
                required: true,
                email: true,
                maxlength: 50
            },
            dtbirthday: {
                required: true,
                maxDate: true
            },
        },
        messages: {
            firstName: {
                required: "First name is required.",
                maxlength: "First name is limited to 45 characters"
            },
            lastName: {
                required: "Last name is required.",
                maxlength: "Last name is limited to 45 characters"
            },
            email: {
                required: "Email is required",
                email: "Please enter a valid email address",
                maxlength: "Email is limited to 50 characters"
            },
            dtbirthday: {
                required: "Date of birthday is required",
                maxDate: "Birthday cannot be a future date"
            },
        },
        errorElement: "div",
        errorClass: "is-invalid",
        validClass: "is-valid",
        ignore: ".ignore",
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

    //Enable datepicker
    $("#dtbirthday").flatpickr({
        enableTime: false,
        dateFormat: "Y-m-d",
        maxDate: "today"
    });

    $("#saveButton").click(function () {
        //reset the old messages
        $("#alertSuccessMessage").attr("hidden", true);
        $("#alertErrorMessage").attr("hidden", true);
        $("#alertWarningMessage").attr("hidden", true);

        $("#setUserProfileForm").submit();
    });

    //set tooltips for add icon and delete icons
    $('#addIcon').tooltip({ placement: 'right', delay: { "show": 500, "hide": 100 } });
    $('[data-toggle="tooltip"]').tooltip({ placement: 'right', delay: { "show": 500, "hide": 100 } });

    $('#addProfileButton').click(function () {
        addProfileToList()
    });

});

//specific field validation, not allowing date of birth greater than currentDate
$.validator.addMethod("maxDate", function (value, element) {
    var curDate = new Date();
    var inputDate = new Date(value);
    if (inputDate < curDate)
        return true;
    return false;
}, "Invalid Date!");

//Define if the AddProfile Button must be enabled or disabled
const toggleAddProfileButtonBehaviour = (event) => {

    if (hasProfileSet(event)) {
        $("#addProfileButton").attr("disabled", "");
        $("#addProfileButton").removeClass("btn-outline-success");
        $("#addProfileButton").addClass("btn-outline-secondary");
    } else {
        $("#addProfileButton").removeAttr("disabled");
        $("#addProfileButton").removeClass("btn-outline-secondary");
        $("#addProfileButton").addClass("btn-outline-success");
    }

}

//Checks if a select profile from the Combo just exists in the user's profile list
const hasProfileSet = (event) => {

    const combo = event.target;
    const comboValue = combo[combo.selectedIndex].value;
    const comboText = combo[combo.selectedIndex].text;

    const profilesTable = $("#profilesTable")[0];
    let hasProfile = false;
    if (comboText === "") {
        return true;
    }

    for (let i = 0, row; row = profilesTable.tBodies[0].rows[i]; i++) {
        //cell 1 = profileName
        if (row.cells.length > 1) {
            //inside td has a <input>
            if (row.cells[1].children[0].value === comboText) {
                hasProfile = true;
                break;
            }
        }
    }

    return hasProfile

}

//remove a user's profile from table
const removeProfile = (idElement) => {
    $(idElement).remove();
    const tBody = $("#profilesTable")[0].tBodies[0];

    if (tBody.rows.length === 0) {

        const rowContent = `<tr><td colspan="3" align="center">No Special Profile set</td></tr>`;
        let newRow = tBody.insertRow(tBody.rows.length);
        newRow.id = "profilesTableEmptyRow";
        newRow.innerHTML = rowContent;

    }

}

//Add a profile from Combo to table
const addProfileToList = () => {

    const combo = $("#profilesSelect")[0];
    const comboValue = combo[combo.selectedIndex].value;
    const comboText = combo[combo.selectedIndex].text;
    const idElement = `userProfile-${comboValue}`;

    combo.selectedIndex = 0;
    $("#addProfileButton").attr("disabled", "");
    $("#addProfileButton").removeClass("btn-outline-success");
    $("#addProfileButton").addClass("btn-outline-secondary");
    $("#profilesTableEmptyRow").remove();

    const newRowContent =
        `<tr>
            <td hidden><input type="text" readonly class="form-control-plaintext" name="profile[${comboValue}][id]" value="${comboValue}"></td>
            <td><input type="text" readonly class="form-control-plaintext" name="profile[${comboValue}][name]" value="${comboText}"></td>
            <td>
                <i onclick="removeProfile('#${idElement}')" 
                    data-toggle="tooltip" 
                    title="Remove Profile" 
                    class="text-danger fas fa-minus-circle fa-lg">
                </i>
            </td>
        </tr>`;

    //insert a new line into table's tbody
    const tBody = $("#profilesTable")[0].tBodies[0];
    let newRow = tBody.insertRow(tBody.rows.length);
    newRow.id = `${idElement}`;
    newRow.innerHTML = newRowContent;

}

//Post the form data using AJAX.
const submitFormAjax = () => {

    let formData = $("#setUserProfileForm").serialize();
    $.ajax({
        url: "setuserprofile",
        type: "POST",
        data: formData
    }).done((json) => {

        if (json.status === "ok") {
            $("#alertSuccessMessage").removeAttr("hidden").text(json.message);
        } else {
            $("#alertErrorMessage").removeAttr("hidden").text(json.message);
        }

    }).fail((error) => {
        console.error(error);
    });

};


