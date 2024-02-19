(function ($) {
  $(document).ready(function () {
    /**
     * =============================
     * Start JS for tag listing.
     * =============================
     */

    let page = 2; // Initialize the page number.
    let perPage = 9; // Number of blogs to load per request.

    // Initial check to hide the "Load more" button if there are no more blogs.
    let blog_track = parseInt($(".tag-list").data("blog-track"));
    if (blog_track <= perPage) {
      $(".load-more-tag").hide();
    }

    // Load More button click event.
    $(".load-more-tag").on("click", function () {
      loadMoreTagResults();
    });

    /**
     * Function make an AJAX call to load more blogs.
     *
     * @return {void}
     */
    function loadMoreTagResults() {
      $(".tag-list-loader").show();
      let tag_id = $(".tag-list").data("tag-id");

      $.ajax({
        url: tagScript.ajax_url,
        type: "POST",
        data: {
          action: "btt_load_more_tag_results",
          nonce: tagScript.nonce,
          page: page,
          perPage: perPage,
          tag_id: tag_id,
        },
        success: function (response) {
          let htmlresponse = response.data.html;
          if (htmlresponse) {
            $(".tag-list").append(htmlresponse);
            page++;
            if ($(".blog").length >= $(".tag-list").attr("data-blog-track")) {
              $(".load-more-tag").hide();
            } else {
              $(".load-more-tag").show();
            }
          }
        },
        complete: function () {
          $(".tag-list-loader").hide();
        },
      });
    }

    /**
     * ===========================
     * End JS for tag listing.
     * ===========================
     */
  });
})(jQuery);
