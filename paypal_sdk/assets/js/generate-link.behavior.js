(function ($, Drupal) {
  Drupal.behaviors.ppssGenerateLink = {
    attach: function (context, settings) {
      /////////////////
      $('[data-agreement-plan-id]').once().each(function (e) {
        var $placeholder = $(this);
        var agreementPlanId = $(this).data('agreement-plan-id');

        // Time to ask for the real link.
        $.ajax({
          type: 'POST',
          cache: false,
          async: true,
          url: drupalSettings.ppssFieldFormatter.url,
          data: {id: agreementPlanId}
        }).always(function (data) {
          $placeholder.replaceWith(data.res);
        });

      });
      /////////////////
    }
  };

})(jQuery, Drupal);