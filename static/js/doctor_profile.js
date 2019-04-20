$(document).ready(function () {

    $("#saveButton").click(function () {
        //reset the old messages
        $("#alertSuccessMessage").attr("hidden", true);
        $("#alertErrorMessage").attr("hidden", true);
        $("#alertWarningMessage").attr("hidden", true);
        $('#medicalSpecialtySelection option').prop('selected', true);

        $("#setDoctorProfileForm").submit();
    });
});

(function () {
    $('#btnRight').click(function (e) {
        var selectedOpts = $('#medicalSpecialtySelect option:selected');
        if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
        }
        $('#medicalSpecialtySelection').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
    });

    $('#btnLeft').click(function (e) {
        var selectedOpts = $('#medicalSpecialtySelection option:selected');
        if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
        }
        $('#medicalSpecialtySelect').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
    });
}(jQuery));