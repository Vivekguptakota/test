(function ($) {

  Drupal.behaviors.invite = {
    attach: function (context) {
      jQuery('#edit-starttime').wickedpicker();
    }
  };
})(jQuery);
