(function ($) {
  $(document).ready(function () {
    $(".home-banner-slider").slick({
      arrows: true,
      centerPadding: "0px",
      dots: false,
      slidesToShow: 1,
      infinite: false,
      autoplay: true,
      autoplaySpeed: 3000,
    });

    var openClass = "open";

    $(".filter-btn").on("click", function (e) {
      if ($(".filter-dropdown").hasClass(openClass)) {
        $(".filter-dropdown").removeClass(openClass);
      } else {
        $(".filter-dropdown").addClass(openClass);
      }
      e.stopPropagation();
    });

    $(document).on("click", function (e) {
      if (
        !$(".filter-dropdown").is(e.target) &&
        $(".filter-dropdown").has(e.target).length === 0
      ) {
        $(".filter-dropdown").removeClass(openClass);
      }
    });

    $(document).on("click", function (e) {
      if (
        !e.target.matches(".header-search-input") &&
        !e.target.matches(".header-search-input input")
      ) {
        $(".header-search-input").removeClass("active");
      }
    });

    var itemValue;

    const selectValue = document.querySelectorAll("#reason option");

    $(".select-dropdown__button").on("click", function () {
      $(".select-dropdown__list").toggleClass("active");
    });
    $(".select-dropdown__list-item").on("click", function () {
      itemValue = $(this).data("value");
      $(".select-dropdown__button span")
        .text($(this).text())
        .parent()
        .attr("data-value", itemValue);
      $(".select-dropdown__list").toggleClass("active");

      selectValue.forEach((ele) => {
        if (itemValue === ele.attributes.value.value) {
          ele.setAttribute("selected", "selected");
          $("#reason-error").html("");
        }
      });
    });

    $(".select-dropdown__button").on("click", function (e) {
      e.preventDefault();
    });

    $(".humberger-menu").on("click", function () {
      $(this).toggleClass("open");
      $(".mobile-menu").toggleClass("open");
      $(".header-search-input").toggleClass("mobile-input");
      $("body").toggleClass("fixed");
    });

    $(".mobile-menu ul li").on("click", function () {
      $(this).addClass("active").siblings().removeClass("active");
    });

    $(".mobile-menu ul li").each(function (i, elem) {
      if ($(this).find("ul").length > 0) {
        $(this).children("label").append("<i></i>");
      } else {
        $(this).addClass("no-ul");
      }
    });

    $(".header-search-input").on("click", function () {
      $(this).addClass("active");
    });

    $(".header-search-input close").on("click", function () {
      $(".header-search-input").removeClass("active");
    });
  });

  $(document).scroll(function () {
    if (window.scrollY > 100) {
      $("header").addClass("sticky");
    } else {
      $("header").removeClass("sticky");
    }
  });
})(jQuery);
