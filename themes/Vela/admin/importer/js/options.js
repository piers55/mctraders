(function ($) {

    function initImporter() {

        $(".import-wrapper a").click(function (event) {
            if (confirm("Are you sure you want to import the demo content?")) {
                if ($(".loader-wrapper").length) $(".loader-wrapper").show();
                else $(".import-wrapper").append("<span class=\"loader-wrapper\"><span class=\"import-loader\"></span></span>");
                $(".demo-list").slideUp("fast");
            } else {
                event.preventDefault();
                return false;
            }
        });

        if (typeof theme_options_importer == "object") {
            $("#import-link-value").val(theme_options_importer.import_url);
            var formOptions = $("#redux-form-wrapper");
            var hiddenAction = $("<input type=\"hidden\" id=\"import-hidden\" />");
            hiddenAction.attr("name", $("#redux-import").attr("name")).val("true");
            formOptions.append(hiddenAction);
            formOptions.submit();
        }
    }

    $(document).ready(function () {

        initImporter();

    });
})(jQuery);
