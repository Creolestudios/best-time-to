(function ($) {
  $(document).ready(function () {
    // Validate and submit an inquiry of contact form.
    $(".inquiry-form").validate({
      rules: {
        name: {
          required: true,
          minlength: 2,
          noBlankSpaces: true,
        },
        email: {
          required: true,
          customEmail: true,
        },
        reason: {
          required: true,
        },
        message: {
          required: true,
          minlength: 2,
          noBlankSpaces: true,
        },
      },
      messages: {
        name: {
          required: "Please enter your name.",
          minlength: "Name must be at least 2 characters.",
        },
        email: {
          required: "Please enter your email.",
          customEmail: "Please enter a valid email address.",
        },
        reason: {
          required: "Please select a reason.",
        },
        message: {
          required: "Please enter a message.",
          minlength: "Message must be at least 2 characters.",
        },
      },

      submitHandler: function (form) {
        $(".contact-form-loader").show();
        let formdata = new FormData(form);
        formdata.append("action", "btt_submit_inquiry_details");
        formdata.append("nonce", contactScript.nonce);
        $.ajax({
          url: contactScript.ajax_url,
          type: "POST",
          contentType: false,
          processData: false,
          data: formdata,
          success: function (response) {
            // Clear all previous error messages.
            $(".name-error").html("");
            $(".email-error").html("");
            $(".reason-error").html("");
            $(".message-error").html("");
            if (response.success === true) {
              $(".success-response").html(
                "Thank you for your message. It has been sent."
              );
              form.reset();
            } else if (response.success === false) {
              let errorObj = response.data.errors;

              // Display the error messages for each field.
              if (errorObj.name) {
                $(".name-error").html(errorObj.name);
              }
              if (errorObj.email) {
                $(".email-error").html(errorObj.email);
              }
              if (errorObj.reason) {
                $(".reason-error").html(errorObj.reason);
              }
              if (errorObj.message) {
                $(".message-error").html(errorObj.message);
              }
            }
          },
          complete: function () {
            $(".contact-form-loader").hide();
          },
        });
      },
    });

    // Custom validation method for stricter email validation
    $.validator.addMethod("customEmail", function (value, element) {
      let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
      return this.optional(element) || emailPattern.test(value);
    });

    // Custom validation method to prevent blank spaces
    $.validator.addMethod(
      "noBlankSpaces",
      function (value, element) {
        return value.trim() !== "";
      },
      "Please enter a valid value."
    );
  });
})(jQuery);
