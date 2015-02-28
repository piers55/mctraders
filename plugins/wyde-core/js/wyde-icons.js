(function ($) {


    $(".wyde-icons").each(function () {

        var el = this;
        var toggle = null;
        var name = $("select", el).attr("name");
        if (name && name.indexOf("icon_toggle_") > -1) {
            name = name.replace("icon_toggle_", "");
            toggle = $("input[name='" + name + "']");
        }

        $("select", el).on("change", function () {
            if (toggle) toggle.val($(this).val());
        });

        $("select", el).select2({ language: "en", dropdownParent: el, width: "340px" });


    });

})(jQuery);

