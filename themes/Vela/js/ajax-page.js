/*
* AJAX Page Loader 1.2.0
* ------------------*/

(function ($) {
    "use strict";
    $.extend(wyde.page, {
        isLoad: false,
        started: false,
        ajaxPage: function (options) {

            this.settings = $.extend({
                search: ".ajax-search-form",
                scope: this.scope.page,
                ignoreSelectors: "",
                transition: "fade"
            }, options);

            if (typeof ajax_page_settings != "undefined") this.settings = $.extend(this.settings, ajax_page_settings);


            this.searchPath = "";
            this.ignoreURLs = [];

            this.ignoreURLs.push("wp-login");
            this.ignoreURLs.push("wp-admin");
            this.ignoreURLs.push("wp-content");
            this.ignoreURLs.push("/shop/");
            this.ignoreURLs.push("/product/");
            this.ignoreURLs.push("/cart/");
            this.ignoreURLs.push("/checkout/");
            this.ignoreURLs.push("/my-account/");


            if ($("body").hasClass("woocommerce") || $("body").hasClass("woocommerce-page")) return;

            var self = this;

            $(this.settings.ignoreSelectors).each(function () {
                if (this.tagName.toLowerCase() == "a") {
                    self.ignoreURLs.push(this.href);
                } else {
                    $(this).find("a").each(function () {
                        self.ignoreURLs.push(this.href);
                    });
                }

            });


            if (window.history.pushState) {
                $(window).off("popstate").on("popstate", function () {
                    if (self.started === true && !self.isIgnore(document.location)) {
                        self.loadContent(document.location.toString(), true);
                    }
                });
            }

            setTimeout(function () {
                self.updateLink();
            }, 100);

            if (self.settings.search) {

                $(self.settings.search).each(function (index) {

                    if ($(this).attr("action")) {
                        //Get the current action so we know where to submit to
                        self.searchPath = $(this).attr("action");

                        //bind our code to search submit, now we can load everything through ajax :)
                        //$("#searchform").name = "searchform";
                        $(this).submit(function () {
                            self.submitSearch($(this).serialize());
                            return false;
                        });
                    }
                });
            }

        },
        updateLink: function (newElements) {
            var self = this;

            $("a:not(.no-ajax)", newElements ? newElements : document.body).each(function () {

                if (this.href.indexOf(self.siteURL) > -1 && (!this.target || this.target == "_self") && !self.isIgnore(this)) {

                    $(this).off("click").on("click", function (event) {
                        event.preventDefault();
                        this.blur();
                        self.loadContent(this.href);
                    });

                }
            });
        },
        hideContent: function (callback) {

            this.loader = $("#ajax-loader");

            if (!this.loader.length) {
                this.loader = $("<div id=\"ajax-loader\" style=\"display:none;\"/>");
                $("body").append(this.loader);
            }

            var self = this;

            var selectors = $(self.settings.scope);
            if (self.settings.scope == self.scope.content) selectors.add("#slider");

            var windowWidth = $(window).width() + 100;
            var windowHeight = $(window).height() + 100;
            var finish = false;

            switch (self.settings.transition) {
                case "fade":
                    selectors.animate({ opacity: 0 }, 800, function () {
                        finish = true;
                    });
                    break;
                case "slideToggle":
                    selectors.css({ position: "relative" }).animate({ left: -(windowWidth) }, 1000, "easeInOutExpo", function () {
                        $("body").css({ overflow: "hidden" });
                        finish = true;
                    });
                    break;
                case "slideLeft":
                    selectors.css({ position: "relative" }).animate({ left: -(windowWidth) }, 1000, "easeInOutExpo", function () {
                        $("body").css({ overflow: "hidden" });
                        finish = true;
                    });
                    break;
                case "slideRight":
                    selectors.css({ position: "relative" }).animate({ left: windowWidth }, 1000, "easeInOutExpo", function () {
                        $("body").css({ overflow: "hidden" });
                        finish = true;
                    });
                    break;
                case "slideUp":
                    selectors.css({ position: "relative" }).animate({ top: -(windowHeight), opacity: 0 }, 1000, "easeInOutExpo", function () {
                        $("body").css({ overflow: "hidden" });
                        finish = true;
                    });
                    break;
                case "slideDown":
                    selectors.css({ position: "relative" }).animate({ top: windowHeight, opacity: 0 }, 1000, "easeInOutExpo", function () {
                        $("body").css({ overflow: "hidden" });
                        finish = true;
                    });
                    break;
            }

            $(self.loader).delay(300).fadeIn(500, function () {
                self.scrollTo(0, {
                    duration: $(window).scrollTop() == 0 ? 0 : 100
                });

            });


            if (typeof callback == "function") callback();


        },
        showContent: function (callback) {
            var self = this;

            self.loader.fadeOut("fast", function () {

                var selectors = $(self.settings.scope);
                if (self.settings.scope == self.scope.content) selectors.add("#slider");

                var windowWidth = $(window).width() + 100;
                var windowHeight = $(window).height() + 100;
                var finish = false;
                switch (self.settings.transition) {
                    case "fade":
                        selectors.animate({ opacity: 1 }, 800, function () {
                            finish = true;
                        });
                        break;
                    case "slideToggle":
                        selectors.animate({ left: 0 }, 1000, "easeInOutExpo", function () {
                            $("body").css({ overflow: "" });
                            $(this).css("position", "");
                            finish = true;
                        });
                        break;
                    case "slideLeft":
                        selectors.css({ left: windowWidth }).animate({ left: 0 }, 1000, "easeInOutExpo", function () {
                            $("body").css({ overflow: "" });
                            $(this).css("position", "");
                            finish = true;
                        });
                        break;
                    case "slideRight":
                        selectors.css({ left: -(windowWidth) }).animate({ left: 0 }, 1000, "easeInOutExpo", function () {
                            $("body").css({ overflow: "" });
                            $(this).css("position", "");
                            finish = true;
                        });
                        break;
                    case "slideUp":
                        selectors.css({ top: windowHeight }).animate({ top: 0, opacity: 1 }, 1000, "easeInOutExpo", function () {
                            $("body").css({ overflow: "" });
                            $(this).css("position", "");
                            finish = true;
                        });
                        break;
                    case "slideDown":
                        selectors.css({ top: -(windowHeight) }).animate({ top: 0, opacity: 1 }, 1000, "easeInOutExpo", function () {
                            $("body").css({ overflow: "" });
                            $(this).css("position", "");
                            finish = true;
                        });
                        break;
                }

                if (typeof callback == "function") {
                    if (finish) callback();
                    else setTimeout(function () { callback(); }, 1000);
                }
            });
        },
        getNodeAttribute: function (text, tag, attr) {

            var regex = new RegExp(String.format("<{0} {1}=[\"'](.*)[\"'].*>", tag, attr));

            var m = regex.exec(text);
            if (m && m[1]) {
                return m[1];
            }
            return false;
        },
        updateToolBar: function (content) {

            if ($("#wpadminbar").length > 0) {
                var adminBar = $(content).find("#wpadminbar");
                if (adminBar) $("#wpadminbar").html(adminBar.html());

            }

        },
        updateHead: function (content) {
            window.$ = jQuery;

            $("head").find("style[data-type='vc_custom-css']").remove();
            $("head").find("style[data-type='vc_shortcodes-custom-css']").remove();

            var vc_page_style = $(content).filter("style[data-type='vc_custom-css']");
            if (vc_page_style) $("head").append(vc_page_style);

            var vc_shortcode_style = $(content).filter("style[data-type='vc_shortcodes-custom-css']");
            if (vc_shortcode_style) $("head").append(vc_shortcode_style);

            $("head").find("title").replaceWith($(content).filter("title"));

            var bodyClass = this.getNodeAttribute(content, "body", "class");
            if (bodyClass) $("body").removeClass().addClass(bodyClass);
        },
        googleTracking: function () {
            var self = this;
            if (typeof ga == "function") {
                ga(function () {
                    var trackers = ga.getAll();
                    $.each(trackers, function (i, v) {
                        //console.log(v.get("trackingId"));
                        /*v.send("pageview", {
                            "page": self.currentPage,
                            "title": document.title
                        });*/
                        v.send("pageview");
                    });
                });
            }
        },
        updateContent: function (content) {

            window.$ = jQuery;

            this.clearVCClass();


            if (this.settings.scope == this.scope.content) {
                //get new slider
                var slider = $(content).find("#slider");
                if (slider.length) {

                    if ($("#slider").length) {
                        //update slider
                        $("#slider").html(slider.html());
                    } else {
                        //insert new slider
                        if ($("#header").hasClass("below-slider"))
                            $("#header").before(slider);
                        else
                            $("#header").after(slider);
                    }

                } else $("#slider").remove();

            }


            //get content
            var output = $(content).find(this.settings.scope);
            //set content
            if (output.length) $(this.settings.scope).html(output.html());

            this.updateHead(content);

            this.updateToolBar(content);

            this.googleTracking();


        },
        pauseSlider: function () {

            //if exists Rev Slider
            if ($(".rev_slider").length && typeof $.fn.revolution == "function") {
                /*
                $(".rev_slider").each(function () {
                var api = $("#" + this.id).revolution();
                if (api && typeof api.revpause == "function") {
                try {
                api.revpause();
                } catch (e) {
                //console.log(e);
                }
                }
                });*/

                $.fn.revredraw = function () { };
                $(".rev_slider").revpause();


            }
        },
        clearVCClass: function () {
            //if exists visual composer to prevent duplicate class
            //if (typeof vc_js == "function") $("html").removeClass("js_active ontouchstart vc_mobile vc_desktop vc_transform");

        },
        initVCElements: function () {
            if (typeof vc_js == "function") vc_js();
        },
        loadContent: function (url, getData) {

            if (!this.isLoad) {

                this.pauseSlider();

                $(window).unbind("resize");

                this.isLoad = true;
                this.started = true;

                var path = url.replace(/^.*\/\/[^\/]+/, '');

                if (typeof window.history.pushState == "function") {
                    history.pushState({ foo: 1000 + Math.random() * 1001 }, "Loading...", path);
                } else document.location.href = "#" + path;

                this.currentPage = path;

                var self = this;

                this.hideContent(function () {
                    $.ajax({
                        type: "GET",
                        url: url,
                        data: getData,
                        cache: false,
                        dataType: "html",
                        success: function (response) {

                            self.isLoad = false;

                            self.updateContent(response);

                            self.showContent(function () {

                                if (self.settings.scope == self.scope.page && typeof self.load == "function") self.load();
                                else if (self.settings.scope == self.scope.content && typeof self.contentLoad == "function") self.contentLoad();

                                self.initVCElements();


                                setTimeout(function () {
                                    $(window).trigger("resize");
                                }, 500);

                            });


                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            self.isLoad = false;
                            window.location.href = url;
                        },
                        statusCode: {
                            404: function () {
                                console.log("Page not found!");
                            }
                        }
                    });
                });
            }
        },
        submitSearch: function (param) {
            if (!this.isLoad) {
                this.loadContent(this.searchPath, param);
            }
        },
        isIgnore: function (link) {

            if (!link) return true;

            var url = link.href ? link.href : link.toString();

            if (!url) return true;

            if (url.startWith("#")) return true;

            if (link.pathname == window.location.pathname && url.indexOf("#") > -1) return true;


            for (var i in this.ignoreURLs) {
                if (url.indexOf(this.ignoreURLs[i]) > -1) {
                    return true;
                }
            }

            return false;
        }
    });

})(jQuery);


