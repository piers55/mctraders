/*
* Wyde Page 1.2.0
* ------------------*/

(function ($) {
    "use strict";

    window.$ = $;

    $.extend(window, {
        wyde: {
            version: "1.1"
        }
    });


    $.extend(wyde, {

        page: {

            init: function () {

                this.isHome = true;
                this.onePage = false;
                this.scope = { page: "#page", content: "#content" };

                this.loaded = function () { };
                this.contentLoaded = function () { };

                if (typeof page_settings != "undefined") $.extend(this, page_settings);

                var self = this;

                $(document).ready(function () {
                    "use strict";
                    self.load();
                });

                $(window).bind("scroll", function (event) {
                    self.scrolled(event);
                });


            },
            load: function () {
                this.parallaxEnabled = this.fadeAwayEnabled = ($(window).width() > 800);
                this.pageTop = $("#page").offset().top;
                this.windowScroll = $(window).scrollTop();
                this.headerTop = $("#header").length ? $("#header").offset().top : 0;
                this.stickyHeight = $("#header").length ? 50 : 0;

                this.initMenu();

                this.initSearch();

                this.sectionOffset = ($("#header").hasClass("sticky") && !$("#header").hasClass("mobile")) ? this.stickyHeight : 0;
                if ($("#wpadminbar").css("position") == "fixed") this.sectionOffset += this.pageTop;



                this.contentLoad();

                var self = this;

                $(window).smartresize(function (event) {
                    self.resize(event);
                });

                this.loaded();

            },
            contentLoad: function () {

                this.isHome = $("body").hasClass("home");
                this.onePage = $("body").hasClass("onepage");

                if (typeof this.ajaxPage == "function") this.ajaxPage({ scope: this.scope.page });

                if (this.onePage) this.initOnePageMenu();

                this.initPlugins();

                var self = this;
                var hash = window.location.hash;
                hash = hash.replace(/[^\w#_-]+/g, '');
                if (hash && $(hash).length) {
                    if (hash == $("#nav .menu > li").first().find("a").attr("href")) hash = 0;
                    this.scrollTimer = setTimeout(function () { self.scrollTo(hash, { offset: -self.sectionOffset }); }, 100);
                }

                this.contentLoaded();


            },
            scrolled: function (event) {

                this.windowScroll = $(window).scrollTop();

                this.headerSticky(this.windowScroll);

                this.parallax(this.windowScroll);

                this.fadeAway(this.windowScroll);



            },
            resize: function (event) {

                this.pageTop = $("#page").offset().top;
                this.headerTop = $("#header").length ? $("#header").offset().top : 0;
                this.parallaxEnabled = this.fadeAwayEnabled = ($(window).width() > 800);

                this.initPrimaryNav();
                this.sectionOffset = ($("#header").hasClass("sticky") && !$("#header").hasClass("mobile")) ? this.stickyHeight : 0;
                if ($("#wpadminbar").css("position") == "fixed") this.sectionOffset += this.pageTop;
                this.initFullscreen();

            },
            headerSticky: function (scrolled) {
                if ($("#header").length && !$("#header").hasClass("mobile") && $("#header").hasClass("sticky")) {

                    if ((scrolled + this.pageTop) > (this.headerTop + ($("#header").height() / 2))) {
                        $("#header").addClass("fixed").css("top", this.pageTop);
                    } else {
                        $("#header").removeClass("fixed").css("top", "");
                    }

                }
            },
            initMenu: function () {

                $(".mobile-nav-icon").on("click", function () {
                    $("#header #nav .menu").slideToggle();
                });


                this.initPrimaryNav();

            },
            initPrimaryNav: function () {


                if ($(window).width() >= 992) {

                    if ($("#header .header-right").length) $("#shop-menu, #search").appendTo(".header-right");
                    $("#nav").prependTo(".header .nav-wrapper");
                    $("#nav li").removeClass("open");
                    $("#nav, #nav ul").css({ "display": "" });
                    $("#nav").removeClass("mobile-nav").addClass("dropdown-nav");
                    $("#header").removeClass("mobile");
                    if ($("#header").hasClass("fixed")) $("#header").css({ top: this.pageTop });

                    if ($("#header").hasClass("header-v6") || $("#header").hasClass("header-v7")) {
                        var logoMenu = $("#menu-center-logo");
                        if (logoMenu.length == 0) {
                            var centerPos = Math.floor($("#nav > ul > li").length / 2);
                            $("#nav > ul > li").eq(centerPos - 1).after($("<li id=\"menu-center-logo\"/>").append($("#logo")));
                            if (this.onePage) this.initOnePageMenu();
                        }
                    }

                    $("#nav li.menu-item-has-children").each(function () {

                        var rPos = $(this).offset().left + $(this).outerWidth() + $("> .sub-menu", this).outerWidth();
                        if (rPos > $(window).width()) $(this).addClass("align-right");
                        else $(this).removeClass("align-right");

                    });

                    $("#nav a[href='#']").off("click").on("click", function (event) {
                        event.preventDefault();
                        return false;
                    });

                } else {

                    $("#header").removeClass("scrolled fixed").css({ top: "" }).addClass("mobile");


                    $("#nav").appendTo(".header .container").removeClass("dropdown-nav").addClass("mobile-nav").css("display", "");
                    $(".header-right #shop-menu, .header-right #search").appendTo(".header .nav-wrapper");

                    if ($("#header").hasClass("header-v6") || $("#header").hasClass("header-v7")) {

                        $(".mobile-nav-icon").after($("#logo"));
                        $("#menu-center-logo").remove();
                        if (this.onePage) this.initOnePageMenu();
                    }

                    $("#nav li.menu-item-has-children > a").off("click").on("click", function (event) {
                        event.preventDefault();
                        var element = $(this).parent("li");
                        if (element.hasClass("open")) {
                            element.removeClass("open");
                            element.find("li").removeClass("open");
                            element.find("ul").slideUp();
                        } else {
                            element.addClass("open");
                            element.children("ul").slideDown();
                            element.siblings("li").children("ul").slideUp();
                            element.siblings("li").removeClass("open");
                            element.siblings("li").find("li").removeClass("open");
                            element.siblings("li").find("ul").slideUp();
                        }
                        return false;
                    });

                }

            },
            initOnePageMenu: function () {

                var self = this;

                setTimeout(function () {

                    $("#nav a[href^='#'], #footer-nav a[href^='#']").off("click").on("click", function (event) {
                        var hash = self.getHash($(this).attr("href"));
                        if (!hash) {
                            return true;
                        } else if (hash == '#') {

                            event.preventDefault();
                            return false;

                        } else {

                            event.preventDefault();

                            if ($("#nav .menu > li > a").index(this) == 0) {
                                self.scrollTo(0);
                            } else {
                                if (self.scrollTimer) clearTimeout(self.scrollTimer);
                                self.scrollTo(hash, { offset: -self.sectionOffset });
                            }

                            if (window.location.hash) {
                                if (typeof window.history.pushState == "function") {
                                    history.pushState({ path: self.siteURL }, "", self.siteURL);
                                } else document.location.href = self.siteURL;
                            }

                            return false;
                        }

                    });

                    if (self.isHome) {

                        $("#logo a").off("click").on("click", function (event) {
                            event.preventDefault();

                            self.scrollTo(0);

                            if (window.location.hash) {
                                if (typeof window.history.pushState == "function") {
                                    history.pushState({ path: self.siteURL }, "", self.siteURL);
                                } else document.location.href = self.siteURL;
                            }

                            return false;

                        });

                    }

                }, 500);




                if (this.windowScroll == 0) $("#nav li").first().addClass("active");
                $("body").scrollspy({ target: "#header .header", offset: $("#header").outerHeight() + 100 });

            },
            initPlugins: function () {

                this.initSection();

                $("img[data-retina]").retina();

                $("select:not(.woocommerce-checkout select, #rating)").wrap("<span class=\"dropdown-select\" />");

                var self = this;

                $(".tooltip-item, .icon-block, .social-icons a").tooltip();
                $(".toggle").toggleSection();
                $(".counter-box").counter();
                $(".gmaps").gMaps();
                $(".donut-chart").donutChart();
                $(".vela-flickr").flickrStream();

                $(".wpb_alert .close-box").on("click", function (event) {
                    event.preventDefault();
                    $(this).parent().slideUp();
                    return false;
                });

                $(".single-product .product .thumbnails").data({ "items": 4, "pagination": false }).addClass("flexslider").wrapInner("<ul class=\"slides\"></ul>").find("a").wrap("<li />").css({ width: "100%", padding: "2px" });

                this.initButtons();

                this.initIcons();

                this.initPrettyPhoto();

                this.initSlider();

                this.initGrid();

                this.initScrollMore();

                $("[data-animation]").animated();

                this.initToTopButton();



                if ($.fn.styleSelector) $("#style-selector").styleSelector();

            },
            initSection: function () {

                /*$(".title-wrapper .title").addClass("fade-away");
                $(".section[data-fadeaway]").waypoint(function () {
                $(this).addClass("fade-away");
                }, {
                offset: function () {
                return -($(this).height() - 200);
                },
                triggerOnce: true
                });*/
                this.initFullscreen();
            },
            initFullscreen: function () {

                if ($(".section.fullscreen").length == 0) return;


                var self = this;

                $(".section.fullscreen").each(function () {

                    if ($(".row-inner", this).outerHeight() >= $(window).height()) {

                        $(this).addClass("ready");

                    } else {

                        //$(".row-inner .vc_col-sm-12, .row-inner .vc_col-md-12, .row-inner .vc_col-lg-12", this).width($(".container", this).width());
                        $(this).animate({ height: $(window).height() }, 800, "easeInOutExpo");
                        $(this).waitForImages({
                            waitForAll: true,
                            finished: function () {
                                setTimeout(function () {
                                    $(".section.fullscreen").addClass("ready");
                                }, 500);
                            }
                        });

                        self.isScrolling = false;

                        $(this).bind("mousewheel", function (e) {

                            e = window.event || e;

                            /*if (e.wheelDelta / 120 > 0) {
                            target = $(this).prev(".section");
        
                            }
                            else {
                            target = $(this).next(".section");
        
                            }*/

                            var target = null;

                            var delta = Math.max(-1, Math.min(1, (e.wheelDelta || -e.deltaY || -e.detail)));
                            if (delta < 0) {
                                //scrolling down
                                target = $(this).next(".section");

                            } else {
                                //scrolling up
                                target = $(this).prev(".section");
                            }
                            if (target && target.length) {
                                if (!self.isScrolling) {
                                    self.isScrolling = true;

                                    self.scrollTo(target, {
                                        offset: -self.sectionOffset,
                                        onAfter: function () {
                                            $(".section.active").removeClass("active");
                                            target.addClass("active");
                                            self.updateSectionNav();
                                            self.isScrolling = false;
                                        }
                                    });
                                }
                                return false;
                            }

                        });

                    }

                });

                var btnPrev = $(".prevsection");
                if (!btnPrev.length) btnPrev = $("<span class=\"section-nav prevsection\"><a href=\"#prevsection\"><i class=\"fa fa-angle-up\"></i></a></span>");
                btnPrev.css("top", self.sectionOffset);
                btnPrev.find("a").on("click", function (event) {
                    event.preventDefault();
                    var prevSection = $(".section.active").prev(".section");
                    if (prevSection.length) {
                        self.scrollTo(prevSection, {
                            onAfter: function () {
                                $(".section.active").removeClass("active");
                                prevSection.addClass("active");
                                self.updateSectionNav();
                            },
                            offset: -self.sectionOffset
                        });
                    }
                    return false;
                });
                $(".main-content").prepend(btnPrev);

                var btnNext = $(".nextsection");
                if (!btnNext.length) btnNext = $("<span class=\"section-nav nextsection\"><a href=\"#nextsection\"><i class=\"fa fa-angle-down\"></i></a></span>");
                btnNext.find("a").on("click", function (event) {
                    event.preventDefault();
                    var nextSection = $(".section.active").next(".section");
                    if (nextSection.length) {
                        self.scrollTo(nextSection, {
                            onAfter: function () {
                                $(".section.active").removeClass("active");
                                nextSection.addClass("active");
                                self.updateSectionNav();
                            },
                            offset: -self.sectionOffset
                        });
                    }
                    return false;
                });
                $(".main-content").append(btnNext);


                if (this.windowScroll <= $(window).height()) {
                    var firstSection = $(".section").first();
                    if (!firstSection.hasClass("active")) {
                        $(".section.active").removeClass("active");
                        firstSection.addClass("active");
                        self.updateSectionNav();
                    }
                }

            },
            updateSectionNav: function () {
                if ($(".section.fullscreen.active").prev(".section.fullscreen").length > 0) $(".prevsection").removeClass("disabled");
                else $(".prevsection").addClass("disabled");

                if ($(".section.fullscreen.active").next(".section.fullscreen").length > 0) $(".nextsection").removeClass("disabled");
                else $(".nextsection").addClass("disabled");
            },
            initToTopButton: function () {
                $("#content").waypoint(function (direction) {
                    if (direction == 'down') $("#toTop").addClass("active");
                    else
                        $("#toTop").removeClass("active");

                }, {
                    offset: -100
                });

                var self = this;
                $("#toTop").on("click", function (event) {
                    event.preventDefault();

                    self.scrollTo(0, function () {
                        if ($(".section-nav").length) {
                            $(".section.active").removeClass("active");
                            $(".section").first().addClass("active");
                            self.updateSectionNav();
                        }
                    });

                    return false;
                });

            },
            initButtons: function () {


                var self = this;

                $(".link-button[href^='#'], a.button[href^='#'], a.ghost-button[href^='#']").on("click", function (event) {
                    var hash = $(this).attr("href");
                    if (!hash) {
                        return true;
                    } else if (hash == '#') {
                        event.preventDefault();
                        return false;
                    } else {
                        event.preventDefault();

                        if (self.scrollTimer) clearTimeout(self.scrollTimer);

                        var target = hash;

                        if (hash == "#nextsection") {
                            target = $(this).parents(".section").next(".section");
                            if (!target.length) target = $(".section").first();
                        }
                        else if (hash == "#prevsection") {
                            target = $(this).parents(".section").prev(".section");
                            if (!target.length) target = $(".section").last();
                        }
                        self.scrollTo(target, { offset: -self.sectionOffset });

                        return false;
                    }
                });

                $(".link-button").each(function () {

                    if ($(this).attr("style") || $(this).data("hover-color")) {
                        var color = $(this).data("hover-color");
                        if (!color) color = "#FFF";
                        $(this).hover(function () {
                            $(this).css("color", color);
                        }, function () {
                            $(this).css("color", $(this).css("border-color"));
                        });
                    }

                });


            },
            initIcons: function () {

                $(".icon-block").each(function () {

                    var color = $(this).css("border-color");
                    $(this).hover(function () {
                        $(this).css("color", color);
                    },
                    function () {
                        $(this).css("color", "");
                    });


                });


                $(".info-box").not(".border-none").each(function () {
                    if ($(".box-icon", this).attr("style")) {
                        var color = $(".box-icon", this).css("color");
                        $(this).hover(function () {
                            $(".icon-wrapper", this).css({ "background-color": color, "border-color": color });
                        }, function () {
                            $(".icon-wrapper", this).css({ "background-color": "", "border-color": "" });
                        });
                    }
                });
            },
            initPrettyPhoto: function () {
                $(".prettyphoto").removeClass("prettyphoto").attr("rel", "prettyPhoto[Gallery]");
                $("a[rel^='prettyPhoto']").prettyPhoto({
                    theme: 'dark_square',
                    deeplinking: false,
                    social_tools: false,
                    overlay_gallery: false,
                    show_title: false
                });
            },
            initSearch: function () {

                var $el = $("#search");
                var self = this;
                var isActive = false;

                if (typeof this.ajaxSearch == "function") this.ajaxSearch({ element: $el });


                function showSearch() {
                    $el.parent().css("width", $el.parent().width());
                    self.hideNav();
                    $el.addClass("active");
                    $("input", $el).animate({ width: $el.width() }, 200, function () {
                        $("input", $el).focus();
                        if ($(".dropdown-menu li", $el).length > 0) $(".autocomplete", $el).addClass("open").show();
                    });
                }

                function hideSearch() {
                    $(".autocomplete", $el).removeClass("open").hide();
                    $("input", $el).animate({ width: 0 }, 200, function () {
                        $el.removeClass("active");
                        self.showNav();
                        $el.parent().css("width", "");
                    });
                }

                $(".search-button", $el).off("click").on("click", function () {
                    if ($el.hasClass("active")) {
                        hideSearch();
                    } else {
                        showSearch();
                    }
                    return false;
                });

                $("input", $el).keypress(function (event) {
                    if (event.which == 13) {
                        event.preventDefault();
                        $("form", $el).submit();
                    }
                });

                $(document.body).on("click", function (event) {
                    var o = $(event.target);
                    setTimeout(function () {
                        if (!(o.is($el) || o.parents("#" + $el.attr("id")).length)) {
                            hideSearch();
                        }
                    }, 200);

                });




            },
            initGrid: function () {

                var self = this;

                $(".grid").each(function () {

                    var grid = $(this);

                    $(".view", this).imagesLoaded(function () {

                        var view = $(this.elements);

                        setTimeout(function () {

                            view.isotope({
                                itemSelector: '.item',
                                transitionDuration: '0.6s',
                                masonry: { columnWidth: grid.hasClass("masonry") ? view.width() / 12 : ".item" }
                            });

                        }, 100);

                        $(window).smartresize(function () {
                            view.isotope({
                                masonry: { columnWidth: grid.hasClass("masonry") ? view.width() / 12 : ".item" }
                            });
                        });
                    });



                });



                $(".post-filter").each(function () {

                    var el = this;
                    var filters = $(el).parents(".filterable");
                    var p = filters.length ? filters : document.body;


                    $(".filter a", el).click(function (event) {

                        event.preventDefault();
                        var hash = self.getHash(this.href);
                        if (hash) {
                            hash = hash.replace("#", ".");
                            if (hash == ".all") hash = "*";
                            $(".view", p).isotope({ filter: hash });
                        }

                        $(".filter li", el).removeClass("selected");
                        $(this).parent().addClass("selected");
                        return false;
                    });
                });



            },
            initScrollMore: function () {

                var self = this;

                $(".scrollmore").each(function () {

                    var el = this;

                    if (!$(".showmore a.next", el).length) return;

                    var trigger = 3;
                    if ($(el).data("trigger") != null) trigger = parseInt($(el).data("trigger"));


                    var idx = $(".scrollmore").index(el);


                    var contentSelector = ".item";

                    if ($(el).data("selector") != null) contentSelector = $(el).data("selector");
                    else {
                        contentSelector = ".scrollmore:eq(" + idx + ") " + contentSelector;
                    }

                    var nextSelector = ".scrollmore:eq(" + idx + ") .showmore a.next";


                    $(this).scrollmore({
                        autoTriggerUntil: trigger,
                        nextSelector: nextSelector,
                        contentSelector: contentSelector,
                        callback: function (newElements) {

                            self.initSlider(newElements);

                            if (typeof self.updateLink == "function") {
                                setTimeout(function () {
                                    self.updateLink(newElements);
                                }, 100);
                            }

                            $(".view", el).each(function () {
                                var view = $(this);
                                var newItems = $(newElements).css({ opacity: 0 });
                                newItems.imagesLoaded(function () {
                                    newItems.animate({ opacity: 1 });
                                    var iso = view.data("isotope");
                                    if (iso) iso.appended(newItems);

                                });
                            });

                        }
                    });
                });
            },
            initSlider: function (p) {


                $(".flexslider", p ? p : document.body).each(function () {


                    var slide = $(this);

                    var effect = slide.data("effect") ? slide.data("effect") : "slide";

                    //if (slide.hasClass("vdo")) slide = slide.fitVids();

                    var items = 1;
                    if ($(this).data("items")) items = parseInt($(this).data("items"));

                    var loop = false;
                    if ($(this).data("loop")) loop = $(this).data("loop");

                    var autoPlay = true;
                    if ($(this).data("autoPlay") != null) autoPlay = $(this).data("autoPlay");

                    var autoHeight = true;
                    if ($(this).data("autoHeight") != null) autoHeight = $(this).data("autoHeight");

                    var navigation = true;
                    if ($(this).data("navigation") != null) navigation = $(this).data("navigation");

                    var pagination = false;
                    if ($(this).data("pagination")) pagination = $(this).data("pagination");

                    var itemMargin = 0;
                    if ($(this).data("itemMargin")) itemMargin = $(this).data("itemMargin");

                    var itemWidth = 0;
                    if (items > 1) {
                        itemWidth = Math.floor(1100 / items);
                    }

                    var options = {
                        prevText: "",
                        nextText: "",
                        animation: effect,
                        pauseOnHover: true,
                        animationLoop: loop,
                        smoothHeight: autoHeight,
                        slideshow: autoPlay,
                        directionNav: navigation,
                        controlNav: pagination,
                        itemWidth: itemWidth,
                        itemMargin: itemMargin,
                        minItems: items,
                        maxItems: items
                    };

                    if (autoHeight) {
                        slide.imagesLoaded(function () {
                            slide.flexslider(options);
                        });
                    } else {
                        slide.flexslider(options);
                    }



                });





                $(".owl-carousel").each(function () {
                    var items = 1;
                    if ($(this).data("items")) items = parseInt($(this).data("items"));

                    var loop = false;
                    if ($(this).data("loop")) loop = $(this).data("loop");

                    var autoPlay = 5000;
                    if ($(this).data("autoPlay") !== true) autoPlay = $(this).data("autoPlay");



                    var autoHeight = true;
                    if ($(this).data("autoHeight") != null) autoHeight = $(this).data("autoHeight");

                    var navigation = true;
                    if ($(this).data("navigation") != null) navigation = $(this).data("navigation");

                    var pagination = false;
                    if ($(this).data("pagination")) pagination = $(this).data("pagination");

                    var speed


                    $(this).owlCarousel({
                        stopOnHover: true,
                        navigationText: ["", ""],
                        slideSpeed: 600,
                        rewindSpeed: 800,
                        items: items,
                        itemsDesktop: items,
                        singleItem: items == 1,
                        autoPlay: autoPlay,
                        autoHeight: autoHeight,
                        navigation: navigation,
                        pagination: pagination
                    });

                });


            },
            hideNav: function () {
                $("#header.mobile #logo, .header-v1 #logo, .header-v1 #nav, .header-v3 #nav, .header-v5 #nav, .header-v6 #nav, #shop-menu, .top-nav, .social-icons").hide().css("opacity", 0);
            },
            showNav: function () {
                $("#header.mobile #logo, .header-v1 #logo, .header-v1 #nav, .header-v3 #nav, .header-v5 #nav, .header-v6 #nav, #shop-menu, .top-nav, .social-icons").show().css("opacity", 1);
            },
            fadeAway: function (scrolled) {
                if (this.fadeAwayEnabled) {
                    $(".fade-away").each(function () {
                        if ($(this).isOnScreen()) {
                            $(this).css({
                                "opacity": 1 - (scrolled / 300)
                            });
                        }
                    });
                }
            },
            parallax: function (scrolled) {

                if (this.parallaxEnabled) {
                    $(".parallax").each(function () {
                        if ($(this).isOnScreen()) {
                            var reverse = $(this).hasClass("reverse");
                            var pos = 0;
                            var top = $(this).offset().top;
                            pos = parseInt((scrolled - top) * 0.2);
                            $(this).css("background-position", "50% " + (reverse ? pos : -pos) + "px");
                        }
                    });
                }
            },
            getHash: function (url) {
                return (url && url.indexOf("#") > -1) ? url.substr(url.indexOf("#")) : null;
            },
            scrollTo: function (target, options) {

                if (typeof options == "function") options = { onAfter: options };
                var settings = $.extend({}, { duration: 1000, easing: 'easeInOutExpo' }, options);
                $(window).scrollTo(target, settings);
            }

        }

    });

    wyde.page.init();

})(jQuery);