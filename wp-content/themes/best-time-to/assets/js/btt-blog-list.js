(function ($) {
  $(document).ready(function () {
    /**
     * ================================
     * Start JS for blog listing.
     * ================================
     */

    let page = 2; // Initialize the page number.
    let perPage = 9; // Number of blogs to load per request.

    // Initial check to hide the "Load more" button if there are no more blogs.
    let blog_track = parseInt($(".blog-list").data("blog-track"));
    if (blog_track <= perPage) {
      $(".load-more-blogs").hide();
    }

    // On click event to filter blogs.
    $(".filter-check").on("click", function () {
      if ($(this).is(":checked")) {
        var selectedValue = $(this).val();
        let cat_id = $(".blog-list").data("cat-id");
        let cat_type = $(".blog-list").data("cat-type");
        get_filter_blog_post(selectedValue, cat_id, cat_type);
      }
    });

    /**
     * Function make an AJAX call to filter blog listing.
     *
     * @param {string} selectedValue Selected value
     * @param {int} cat_id Category ID
     * @param {string} cat_type Category type
     *
     * @return {void}
     */
    function get_filter_blog_post(selectedValue, cat_id, cat_type) {
      $(".blog-list").html("");
      $(".blog-list-loader").show();
      $(".load-more-blogs").hide();

      $.ajax({
        url: myVar.ajax_url,
        type: "POST",
        data: {
          action: "btt_get_filter_blog_post",
          nonce: myVar.nonce,
          selected_value: selectedValue,
          cat_type: cat_type,
          cat_id: cat_id,
          perPage: perPage,
        },
        success: function (response) {
          let htmlresponse = response.data.html;
          let totalPosts = response.data.totalPosts;
          $(".blog-list").attr("data-blog-track", totalPosts);
          if (htmlresponse) {
            $(".blog-list").html(htmlresponse);
          }
          if (totalPosts > 9) {
            $(".load-more-blogs").show();
          }
        },
        complete: function () {
          $(".blog-list-loader").hide();
          page = 2;
        },
      });
    }

    // Load More button click event.
    $(".load-more-blogs").on("click", function () {
      loadMoreBlogs();
    });

    /**
     * Function make an AJAX call to load more blogs.
     *
     * @return {void}
     */
    function loadMoreBlogs() {
      $(".blog-list-loader").show();
      let cat_id = $(".blog-list").data("cat-id");
      let cat_type = $(".blog-list").data("cat-type");
      let selected_value = $('input[name="check"]:checked').val();

      $.ajax({
        url: myVar.ajax_url,
        type: "POST",
        data: {
          action: "btt_load_more_blogs",
          nonce: myVar.nonce,
          cat_id: cat_id,
          cat_type: cat_type,
          selected_value: selected_value,
          page: page,
          perPage: perPage,
        },
        success: function (response) {
          let htmlresponse = response.data.html;
          if (htmlresponse) {
            $(".blog-list").append(htmlresponse);
            page++;
            if ($(".blog").length >= $(".blog-list").attr("data-blog-track")) {
              $(".load-more-blogs").hide();
            } else {
              $(".load-more-blogs").show();
            }
          }
        },
        complete: function () {
          $(".blog-list-loader").hide();
        },
      });
    }

    /**
     * ================================
     * End JS for blog listing.
     * ================================
     */
  });
})(jQuery);
