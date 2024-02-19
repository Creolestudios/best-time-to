(function ($) {
  $(document).ready(function () {
    /**
     * ================================
     * Start JS for search listing.
     * ================================
     */

    let page = 2; // Initialize the page number.
    let perPage = 9; // Number of blogs to load per request.

    // Initial check to hide the "Load more" button if there are no more blogs.
    let blog_track = parseInt($(".search-list").data("blog-track"));
    if (blog_track <= perPage) {
      $(".load-more-search").hide();
    }

    // Load More button click event.
    $(".load-more-search").on("click", function () {
      loadMoreSearchResults();
    });

    /**
     * Function make an AJAX call to load more blogs.
     *
     * @return {void}
     */
    function loadMoreSearchResults() {
      $(".search-list-loader").show();
      let search_value = $(".search-list").data("search");

      $.ajax({
        url: searchScript.ajax_url,
        type: "POST",
        data: {
          action: "btt_load_more_search_results",
          nonce: searchScript.nonce,
          page: page,
          perPage: perPage,
          search_value: search_value,
        },
        success: function (response) {
          let htmlresponse = response.data.html;
          if (htmlresponse) {
            $(".search-list").append(htmlresponse);
            page++;
            if (
              $(".blog").length >= $(".search-list").attr("data-blog-track")
            ) {
              $(".load-more-search").hide();
            } else {
              $(".load-more-search").show();
            }
          }
        },
        complete: function () {
          $(".search-list-loader").hide();
        },
      });
    }

    /**
     * ================================
     * End JS for search listing.
     * ================================
     */
  });
})(jQuery);
