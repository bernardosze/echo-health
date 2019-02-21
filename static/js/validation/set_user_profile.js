$(document).ready(function () {

    //Enable datepicker
    $("#dt-birthday").flatpickr({
        enableTime: false,
        dateFormat: "Y-m-d",
        maxDate: "today"
    });

    //set tooltip for add icon
    $('#addIcon').tooltip({ placement: 'right' });

    $("#table").bootstrapTable();
    $("#table").removeAttr("hidden");
});




