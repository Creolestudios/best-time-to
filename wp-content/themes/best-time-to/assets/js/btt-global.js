(function ($) {
  $(document).ready(function () {
    /**
     * ================================
     * Start JS for country mega menu.
     * ================================
     */

    // On load getting initial posts.
    let active_parent_id = $(".active-parent-term").data("term-id");
    get_country_with_posts_by_filter(active_parent_id);

    // Click on parent term(continents).
    $(".continents-name").on("click", function () {
      let parent_term_id = $(this).data("term-id");
      if (parent_term_id == "all") {
        window.location.href = globalScript.travel_page;
        return;
      }
      $(".continents-name").removeClass("active-parent-term");
      $(this).addClass("active-parent-term");
      get_country_with_posts_by_filter(parent_term_id);
    });

    // Click on child term(country).
    $(document).on("click", ".country-name", function () {
      let parent_term_id = $(".active-parent-term").data("term-id");
      let child_term_id = $(this).data("term-id");
      $(".country-name").removeClass("active-child-term");
      $(this).addClass("active-child-term");
      get_posts_by_country_filter(parent_term_id, child_term_id);
    });

    // Open country mega menu.
    $(".btt-travel-mega-menu").on("click", function (e) {
      e.preventDefault();
      let liIndex = $(this).index();
      let travelClass = "has-child-" + liIndex;
      $(".country-popup-wrapper").toggle();
      $(".country-popup-wrapper").addClass(travelClass);
      $(".category-popup-wrapper").hide();
      $(".nature-popup-wrapper").hide();

      // Prevent click inside the popup from closing it.
      e.stopPropagation();
    });

    // Close country popup when clicking anywhere on the document.
    $(document).on("click", function (e) {
      if (
        !$(".country-popup-wrapper").is(e.target) &&
        $(".country-popup-wrapper").has(e.target).length === 0
      ) {
        $(".country-popup-wrapper").hide();
      }
    });

    /**
     * Function make an AJAX call to filter country mega menu.
     *
     * @param {integer} parent_term_id Selected parent term
     *
     * @return {void}
     */
    function get_country_with_posts_by_filter(parent_term_id) {
      $(".country-blogs-container").html("");
      $(".loader").show();

      $.ajax({
        url: globalScript.ajax_url,
        type: "POST",
        data: {
          action: "btt_filter_country_with_posts",
          nonce: globalScript.nonce,
          parent_term_id: parent_term_id,
        },
        success: function (response) {
          $(".country-blogs-container").html(response.data.html);
        },
        complete: function () {
          $(".loader").hide();
        },
      });
    }

    /**
     * Function make an AJAX call to filter posts from child term.
     *
     * @param {integer} child_term_id Selected child term
     *
     * @return {void}
     */
    function get_posts_by_country_filter(parent_term_id, child_term_id) {
      $(".featured-blogs-wrapper").html("");
      $(".loader").show();

      $.ajax({
        url: globalScript.ajax_url,
        type: "POST",
        data: {
          action: "btt_filter_posts_by_country",
          nonce: globalScript.nonce,
          child_term_id: child_term_id,
          parent_term_id: parent_term_id,
        },
        success: function (response) {
          let htmlresponse = response.data.html;
          $(".featured-blogs-wrapper").remove();
          $(htmlresponse).insertAfter(".country-wrapper");
        },
        complete: function () {
          $(".loader").hide();
        },
      });
    }
    /**
     * ================================
     * End JS for country mega menu.
     * ================================
     */

    /**
     * ================================
     * Start JS for category mega menu.
     * ================================
     */

    // On load getting initial posts.
    let active_category_id = $(".active-category-term").data("term-id");
    get_posts_by_category_filter(active_category_id);

    // Open category mega menu.
    $(".btt-life-mega-menu").on("click", function (e) {
      e.preventDefault();
      let liIndex = $(this).index();
      let lifeClass = "has-child-" + liIndex;
      $(".category-popup-wrapper").toggle();
      $(".category-popup-wrapper").addClass(lifeClass);
      $(".country-popup-wrapper").hide();
      $(".nature-popup-wrapper").hide();

      // Prevent click inside the popup from closing it.
      e.stopPropagation();
    });

    // Close category popup when clicking anywhere on the document.
    $(document).on("click", function (e) {
      if (
        !$(".category-popup-wrapper").is(e.target) &&
        $(".category-popup-wrapper").has(e.target).length === 0
      ) {
        $(".category-popup-wrapper").hide();
      }
    });

    // Click on category to filter posts.
    $(".category-name").on("click", function () {
      let category_term_id = $(this).data("term-id");
      $(".category-name").removeClass("active-category-term");
      $(this).addClass("active-category-term");
      get_posts_by_category_filter(category_term_id);
    });

    /**
     * Function make an AJAX call to filter posts from category term.
     *
     * @param {integer} category_term_id Selected category term
     *
     * @return {void}
     */
    function get_posts_by_category_filter(category_term_id) {
      $(".cat-featured-blog-wrapper").html("");
      $(".loader").show();

      $.ajax({
        url: globalScript.ajax_url,
        type: "POST",
        data: {
          action: "btt_filter_posts_by_category",
          nonce: globalScript.nonce,
          category_term_id: category_term_id,
        },
        success: function (response) {
          let htmlresponse = response.data.html;
          $(".cat-featured-blog-wrapper").html(htmlresponse);
        },
        complete: function () {
          $(".loader").hide();
        },
      });
    }
    /**
     * ================================
     * End JS for category mega menu.
     * ================================
     */

    /**
     * ================================
     * Start JS for nature mega menu.
     * ================================
     */

    // On load getting initial posts.
    let active_nature_term_id = $(".active-nature-term").data("term-id");
    get_posts_by_nature_filter(active_nature_term_id);

    // Open nature mega menu.
    $(".btt-nature-mega-menu").on("click", function (e) {
      e.preventDefault();
      let termIndex = $(this).index();
      // alert(termIndex);
      let natureClass = "has-child-" + termIndex;
      $(".nature-popup-wrapper").toggle();
      $(".nature-popup-wrapper").addClass(natureClass);
      $(".country-popup-wrapper").hide();
      $(".category-popup-wrapper").hide();

      // Prevent click inside the popup from closing it.
      e.stopPropagation();
    });

    // Close nature popup when clicking anywhere on the document.
    $(document).on("click", function (e) {
      if (
        !$(".nature-popup-wrapper").is(e.target) &&
        $(".nature-popup-wrapper").has(e.target).length === 0
      ) {
        $(".nature-popup-wrapper").hide();
      }
    });

    // Click on category to filter posts.
    $(".nature-term").on("click", function () {
      let nature_term_id = $(this).data("term-id");
      $(".nature-term").removeClass("active-nature-term");
      $(this).addClass("active-nature-term");
      get_posts_by_nature_filter(nature_term_id);
    });

    /**
     * Function make an AJAX call to filter posts from category term.
     *
     * @param {integer} nature_term_id Selected category term
     *
     * @return {void}
     */
    function get_posts_by_nature_filter(nature_term_id) {
      $(".nature-featured-blog-wrapper").html("");
      $(".nature-term-loader").show();

      $.ajax({
        url: globalScript.ajax_url,
        type: "POST",
        data: {
          action: "btt_filter_posts_by_nature",
          nonce: globalScript.nonce,
          nature_term_id: nature_term_id,
        },
        success: function (response) {
          let htmlresponse = response.data.html;
          $(".nature-featured-blog-wrapper").html(htmlresponse);
        },
        complete: function () {
          $(".nature-term-loader").hide();
        },
      });
    }
    /**
     * ================================
     * End JS for nature mega menu.
     * ================================
     */

    /**
     * ================================
     * Start JS for global search.
     * ================================
     */

    $("#btt-search, #btt-blog-search").on("keyup", function (e) {
      if (e.key === "Enter") {
        var searchTerm = $(this).val();
        // if (searchTerm.trim() !== "") {
        // Redirect to search results page with query parameter.
        let home_url = globalScript.home_url;
        window.location.href = home_url + "?s=" + searchTerm;
        // }
      }
    });

    /**
     * ================================
     * End JS for global search.
     * ================================
     */
  });
})(jQuery);
