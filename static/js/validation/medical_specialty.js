$(document).ready(() => {

    $.getJSON("medicalspecialty?JSON=true", (result) => {

        if (result.status === "Invalid_Session") {
            //User session is invalid.
            $(location).attr("href", "login");
        } else if (result.status === "ok") {
            renderData(result.data);
        } else {
            $("#alertErrorMessage").removeAttr("hidden").text(result.message);
        }

    }).done(function (data) {
        // 
    }).fail(function (error) {
        console.error(error);
    });

    $("#newButton").click(() => {
        for (let i = 0; i < 5; i++) {
            renderEmptyFields();
        }
    });

    $("#saveButton").click(() => {
        //reset the old messages
        $("#alertSuccessMessage").attr("hidden", true);
        $("#alertErrorMessage").attr("hidden", true);
        $("#alertWarningMessage").attr("hidden", true);

        $("#submit").click();
    });

    $("#medicalSpecialtyForm").submit((event) => {

        event.preventDefault();
        let formData = $(event.target).serialize();
        $.ajax({
            url: "medicalspecialty",
            type: "POST",
            data: formData
        }).done(function (response) { //

            const json = JSON.parse(response);
            if (json.status === "ok") {
                renderData(json.data);
                $("#alertSuccessMessage").removeAttr("hidden").text(json.message);
            } else {
                $("#alertErrorMessage").removeAttr("hidden").text(json.message);
            }


        });

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

const renderEmptyFields = () => {

    const list = document.querySelectorAll("tr[data-row]");
    let sequence = list.length + 1;
    const itemId = "";
    const itemName = "";

    const trTemplate =
        `<tr>
        <td class="align-middle"></td>
        <td>
            <input type="hidden" name="medicalSpecialty[${sequence}][id]" value="">
            <input type="hidden" name="medicalSpecialty[${sequence}][action]" value="insert">
            <input type="text" readonly class="form-control-plaintext" value="${sequence}">
        </td>
        <td><input type="text" class="form-control" name="medicalSpecialty[${sequence}][name]" value="${itemName}"></td>
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


