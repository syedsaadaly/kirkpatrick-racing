$(document).ready(function () {
    $(document).on("change", ".const-control-d-toggle", function () {
        $(this).parents('.const-control-d-parent').find('.const-control-d-grp').toggleClass('d-none')
    })

    $(document).ajaxStart(function() {
        $("#global-loader").fadeIn(200);
    });

    $(document).ajaxStop(function() {
        $("#global-loader").fadeOut(200);
    });
})
