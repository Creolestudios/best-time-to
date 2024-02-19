(function ($) {
  $(document).ready(function () {
    /**
     * ================================
     * Start JS for category listing.
     * ================================
     */

    let page = 2; // Initialize the page number.
    let perPage = 9; // Number of categories to load per request.

    // Load More button click event.
    $(".load-more-category").on("click", function () {
      loadMoreCategories();
    });

    /**
     * Function make an AJAX call to load more categories.
     *
     * @return {void}
     */
    function loadMoreCategories() {
      $(".life-category-loader").show();

      $.ajax({
        url: categoryScript.ajax_url,
        type: "POST",
        data: {
          action: "btt_load_more_categories",
          nonce: categoryScript.nonce,
          page: page,
          perPage: perPage,
        },
        success: function (response) {
          let htmlresponse = response.data.html;
          if (htmlresponse) {
            $(".category-item-list").append(htmlresponse);
            page++;
            // Check if there are more categories.
            if ($(".item").length >= cat_track) {
              $(".load-more-category").hide();
            } else {
              $(".load-more-category").show();
            }
          }
        },
        complete: function () {
          $(".life-category-loader").hide();
        },
      });
    }

    // Initial check to hide the "Load more" button if there are no more categories.
    let cat_track = $(".category-item-list").data("track");
    if (cat_track <= perPage) {
      $(".load-more-category").hide();
    }
    /**
     * ================================
     * End JS for category listing.
     * ================================
     */

    /**
     * ================================
     * Start JS for country listing.
     * ================================
     */

    // Initial check to hide the "Load more" button if there are no more countries.
    let country_track = $(".country-item-list").data("track");
    if (country_track <= perPage) {
      $(".load-more-country").hide();
    }

    // On click event to filter country.
    $(".travel-country-name").on("click", function () {
      let active_parent_id = $(this).data("term-id");
      $(".travel-country-name").removeClass("active-travel-term");
      $(this).addClass("active-travel-term");
      get_country_by_filter(active_parent_id);
    });

    /**
     * Function make an AJAX call to filter country.
     *
     * @param {integer} parent_term_id Selected parent term
     *
     * @return {void}
     */
    function get_country_by_filter(parent_term_id) {
      $(".country-item-list").html("");
      $(".country-loader").show();
      $(".load-more-country").hide();

      $.ajax({
        url: categoryScript.ajax_url,
        type: "POST",
        data: {
          action: "btt_get_country_by_filter",
          nonce: categoryScript.nonce,
          parent_term_id: parent_term_id,
        },
        success: function (response) {
          let htmlresponse = response.data.html;
          let totalTerms = response.data.totalTerms;
          $(".country-item-list").attr("data-track", totalTerms);
          if (htmlresponse) {
            $(".country-item-list").html(htmlresponse);
          }
          if (totalTerms > 9) {
            $(".load-more-country").show();
          }
        },
        complete: function () {
          $(".country-loader").hide();
          page = 2;
        },
      });
    }

    // Load More button click event.
    $(".load-more-country").on("click", function () {
      loadMoreCountries();
    });

    /**
     * Function make an AJAX call to load more countries.
     *
     * @return {void}
     */
    function loadMoreCountries() {
      $(".country-loader").show();
      let current_parent_id = $(".active-travel-term").data("term-id");

      $.ajax({
        url: categoryScript.ajax_url,
        type: "POST",
        data: {
          action: "btt_load_more_countries",
          nonce: categoryScript.nonce,
          current_parent_id: current_parent_id,
          page: page,
          perPage: perPage,
        },
        success: function (response) {
          let htmlresponse = response.data.html;
          if (htmlresponse) {
            $(".country-item-list").append(htmlresponse);
            page++;
            // Check if there are more countries.
            if ($(".item").length >= country_track) {
              $(".load-more-country").hide();
            } else {
              $(".load-more-country").show();
            }
          }
        },
        complete: function () {
          $(".country-loader").hide();
        },
      });
    }
    /**
     * ================================
     * End JS for country listing.
     * ================================
     */
  });
})(jQuery);
