(function ($) {
  "use strict";

  // ==========================================
  //      Start Document Ready function
  // ==========================================
  $(document).ready(function () {
    // ============== Header Hide Click On Body Js Start ========
    $(".header-button").on("click", function () {
      $(".body-overlay").toggleClass("show");
    });
    $(".body-overlay").on("click", function () {
      $(".header-button").trigger("click");
      $(this).removeClass("show");
    });
    // =============== Header Hide Click On Body Js End =========

    // ========================== Header Hide Scroll Bar Js Start =====================
    $(".navbar-toggler.header-button").on("click", function () {
      $("body").toggleClass("scroll-hide-sm");
    });
    $(".body-overlay").on("click", function () {
      $("body").removeClass("scroll-hide-sm");
    });
    // ========================== Header Hide Scroll Bar Js End =====================

    // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js Start =====================
    $(".dropdown-item").on("click", function () {
      $(this).closest(".dropdown-menu").addClass("d-block");
    });
    // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js End =====================

    // ========================== Add Attribute For Bg Image Js Start =====================
    $(".bg-img").css("background", function () {
      var bg = "url(" + $(this).data("background-image") + ")";
      return bg;
    });

    $(".custom--dropdown > .custom--dropdown__selected").on(
      "click",
      function () {
        $(this).parent().toggleClass("open");
      }
    );

    $(".custom--dropdown > .dropdown-list > .dropdown-list__item").on(
      "click",
      function () {
        $(
          ".custom--dropdown > .dropdown-list > .dropdown-list__item"
        ).removeClass("selected");
        $(this)
          .addClass("selected")
          .parent()
          .parent()
          .removeClass("open")
          .children(".custom--dropdown__selected")
          .html($(this).html());
      }
    );

    $(document).on("keyup", function (evt) {
      if ((evt.keyCode || evt.which) === 27) {
        $(".custom--dropdown").removeClass("open");
      }
    });

    $(document).on("click", function (evt) {
      if (
        $(evt.target).closest(".custom--dropdown > .custom--dropdown__selected")
          .length === 0
      ) {
        $(".custom--dropdown").removeClass("open");
      }
    });

    if ($("a[data-rel^=lightcase]").length) {
      $("a[data-rel^=lightcase]").lightcase();
    }

    // sign up page

    // datepicker

    $(".from-time").timepicker({
      showDuration: true,
      timeFormat: "g:iA",
    });

    $(".to-time").timepicker({
      showDuration: true,
      timeFormat: "g:iA",
    });

    $(".from-time").on("changeTime", function () {
      var fromTime = $(this).val();
      var toTime = $(".to-time").val();

      if (fromTime && toTime && fromTime >= toTime) {
        $(".to-time").val("");
      }
    });

    $(".to-time").on("changeTime", function () {
      var fromTime = $(".from-time").val();
      var toTime = $(this).val();

      if (fromTime && toTime && fromTime >= toTime) {
        $(this).val("");
        notify("error", "To time must be greater than From time.");
      }
    });

    $(".date").datepicker({
      format: "m/d/yyyy",
      autoclose: true,
      startDate: new Date(),
    });

    // filter

    $(".showFilterBtn").on("click", function () {
      $(".responsive-filter-card").slideToggle();
    });

    // profile image change

    $("#userChange").on("change", function () {
      userImageChange(this);
    });

    function userImageChange(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $("#user").attr("src", e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
      }
    }

    // table scroller
    (() => {
      let $tableScrollers = $(".table--scroller");
      let tableScrollersBtn = $(".table--load");

      // Hide the button initially
      tableScrollersBtn.addClass("d-none");

      // Ensure at least one table scroller exists before proceeding
      if ($tableScrollers.length === 0) return;

      let isMaxHeight = Array($tableScrollers.length).fill(false);

      if ($tableScrollers[0] && $tableScrollers[0].scrollHeight > 340) {
        tableScrollersBtn.removeClass("d-none");

        tableScrollersBtn.on("click", function () {
          let index = tableScrollersBtn.index(this);
          let $tableScroller = $tableScrollers.eq(index);

          let targetHeight = isMaxHeight[index]
            ? 340
            : $tableScroller[0].scrollHeight;

          $tableScroller.animate(
            {
              maxHeight: targetHeight,
            },
            300
          );

          $tableScroller.toggleClass("active");
          $(this).addClass("d-none");
        });
      }
    })();

    // ========================= Video js Js start ===================

    // ========================= Video js Js end ===================

    // ========================== Add Attribute For Bg Image Js End =====================

    // ========================== add active class to ul>li top Active current page Js Start =====================

    $(".header li.nav-item.dropdown a").on("click", function () {
      $(".header .dropdown-menu.mega-menu").toggleClass("show");
    });

    // ========================== add active class to ul>li top Active current page Js End =====================

    // ================== Password Show Hide Js Start ==========
    $(".toggle-password").on("click", function () {
      $(this).toggleClass("fa-eye");
      var input = $(this).closest(".position-relative").find("input");
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });
    // =============== Password Show Hide Js End =================

    // ========================= Slick Slider Js Start ==============

    if ($(".testimonial-slider").length) {
      $(".testimonial-slider").slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        speed: 1500,
        dots: true,
        pauseOnHover: true,
        arrows: false,
        prevArrow:
          '<button type="button" class="slick-prev"><i class="fas fa-long-arrow-alt-left"></i></button>',
        nextArrow:
          '<button type="button" class="slick-next"><i class="fas fa-long-arrow-alt-right"></i></button>',
        responsive: [
          {
            breakpoint: 1199,
            settings: {
              arrows: false,
              slidesToShow: 2,
              dots: true,
            },
          },
          {
            breakpoint: 991,
            settings: {
              arrows: false,
              slidesToShow: 2,
            },
          },
          {
            breakpoint: 767,
            settings: {
              arrows: false,
              slidesToShow: 1,
            },
          },
        ],
      });
    }

    // ========================= Slick Slider Js End ===================

    // ========================= Client Slider Js Start ===============
    if ($(".client-slider").length) {
      $(".client-slider").slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1000,
        pauseOnHover: true,
        speed: 2000,
        dots: false,
        arrows: false,
        prevArrow:
          '<button type="button" class="slick-prev"><i class="fas fa-long-arrow-alt-left"></i></button>',
        nextArrow:
          '<button type="button" class="slick-next"><i class="fas fa-long-arrow-alt-right"></i></button>',
        responsive: [
          {
            breakpoint: 1199,
            settings: {
              slidesToShow: 6,
            },
          },
          {
            breakpoint: 991,
            settings: {
              slidesToShow: 5,
            },
          },
          {
            breakpoint: 767,
            settings: {
              slidesToShow: 4,
            },
          },
          {
            breakpoint: 400,
            settings: {
              slidesToShow: 3,
            },
          },
        ],
      });
    }
    // ========================= Client Slider Js End ===================

    // ================== Sidebar Menu Js Start ===============
    // Sidebar Dropdown Menu Start
    $(".has-dropdown > a").on("click", function () {
      $(".sidebar-submenu").slideUp(200);
      if ($(this).parent().hasClass("active")) {
        $(".has-dropdown").removeClass("active");
        $(this).parent().removeClass("active");
      } else {
        $(".has-dropdown").removeClass("active");
        $(this).next(".sidebar-submenu").slideDown(200);
        $(this).parent().addClass("active");
      }
    });
    // Sidebar Dropdown Menu End
    // Sidebar Icon & Overlay js
    $(".dashboard-body__bar-icon").on("click", function () {
      $(".sidebar-menu").addClass("show-sidebar");
      $(".sidebar-overlay").addClass("show");
    });
    $(".sidebar-menu__close, .sidebar-overlay").on("click", function () {
      $(".sidebar-menu").removeClass("show-sidebar");
      $(".sidebar-overlay").removeClass("show");
    });

    // Sidebar Icon & Overlay js
    // ===================== Sidebar Menu Js End =================

    // ==================== Dashboard User Profile Dropdown Start ==================
    $(".user-info__button").on("click", function (event) {
      event.stopPropagation(); // Prevent the click event from propagating to the body
      $(".user-info-dropdown").toggleClass("show");
    });

    $(".user-info-dropdown__link").on("click", function (event) {
      event.stopPropagation(); // Prevent the click event from propagating to the body
      $(".user-info-dropdown").addClass("show");
    });

    $("body").on("click", function () {
      $(".user-info-dropdown").removeClass("show");
    });
    // ==================== Dashboard User Profile Dropdown End ==================

    // ==================== Custom Sidebar Dropdown Menu Js Start ==================
    $(".has-submenu").on("click", function (event) {
      event.preventDefault(); // Prevent the default anchor link behavior

      // Check if this submenu is currently visible
      var isOpen = $(this).find(".sidebar-submenu").is(":visible");

      // Hide all submenus initially
      $(".sidebar-submenu").slideUp();

      // Remove the "active" class from all li elements
      $(".sidebar-menu__item").removeClass("active");

      // If this submenu was not open, toggle its visibility and add the "active" class to the clicked li
      if (!isOpen) {
        $(this).find(".sidebar-submenu").slideToggle(500);
        $(this).addClass("active");
      }
    });
    // ==================== Custom Sidebar Dropdown Menu Js End ==================

    // ========================= Odometer Counter Up Js End ==========
    $(".countdown-item").each(function () {
      $(this).isInViewport(function (status) {
        if (status === "entered") {
          for (
            var i = 0;
            i < document.querySelectorAll(".odometer").length;
            i++
          ) {
            var el = document.querySelectorAll(".odometer")[i];
            el.innerHTML = el.getAttribute("data-odometer-final");
          }
        }
      });
    });
    // ========================= Odometer Up Counter Js End =====================

    // ========================== add active class to ul>li top Active current page Js Start =====================
    function dynamicActiveMenuClass(selector) {
      // Function to update active class based on the current hash
      function updateActiveClass() {
        let currentHash = window.location.hash;

        // Loop through each <a> inside the <li> elements
        selector.find(".sidebar-menu-list__link").each(function () {
          let anchorHref = $(this).attr("href");

          // Check if the href matches the current hash
          if (anchorHref === currentHash) {
            $(this).addClass("active"); // Add active class to the <a> element
          } else {
            $(this).removeClass("active"); // Remove active class if it's not the current page
          }
        });

        // If no hash in the URL, set the first <a> as active
        if (currentHash === "") {
          selector.find(".sidebar-menu-list__link").eq(0).addClass("active");
        }
      }

      // Initial update when the page loads
      updateActiveClass();

      // Listen for hash changes and update active class accordingly
      $(window).on("hashchange", function () {
        updateActiveClass();
      });
    }

    // Call the function if the sidebar menu exists
    if ($("ul.sidebar-menu-list.two").length) {
      dynamicActiveMenuClass($("ul.sidebar-menu-list.two"));
    }

    // ========================== add active class to ul>li top Active current page Js End =====================
  });
  // ==========================================
  //      End Document Ready function
  // ==========================================

  // ========================= Preloader Js Start =====================
  $(window).on("load", function () {
    $(".preloader").fadeOut();
  });
  // ========================= Preloader Js End=====================

  // ========================= Header Sticky Js Start ==============
  $(window).on("scroll", function () {
    if ($(window).scrollTop() >= 100) {
      $(".header").addClass("fixed-header");
    } else {
      $(".header").removeClass("fixed-header");
    }
  });
  // ========================= Header Sticky Js End===================

  //============================ Scroll To Top Icon Js Start =========
  var btn = $(".scroll-top");

  $(window).scroll(function () {
    if ($(window).scrollTop() > 300) {
      btn.addClass("show");
    } else {
      btn.removeClass("show");
    }
  });

  btn.on("click", function (e) {
    e.preventDefault();
    $("html, body").animate(
      {
        scrollTop: 0,
      },
      "300"
    );
  });
  //========================= Scroll To Top Icon Js End ======================
})(jQuery);
