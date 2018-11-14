/*Drupal.behaviors.myBehavior = {
  attach: function (context, settings) {

})(jQuery);*/

Drupal.behaviors.myBehavior = {
  attach: function (context, settings) {
    jQuery(document).ready(function() {

      /*Check the Path and apply the active class to link on dashboard*/

      var current = location.pathname;
      if(current == '/provider/list' || current == '/patient/list') {
        jQuery('.dashboard-button-cal-list .dashboard-cal-view').removeClass('active-tab');
        jQuery('.dashboard-button-cal-list .dashboard-list-view').addClass('active-tab');
      }


      if(jQuery('.navbar-collapse').hasClass('in')) {
      console.log(jQuery(this).parent().find('.navbar-toggle'))/*.addClass('expandable')*/;
    }
    else {
      jQuery('.navbar-collapse').parent().find('.navbar-toggle').addClass('expandable');
    }
    jQuery('.navbar-toggle, .expandable').click(function () {
      jQuery(this).toggleClass('expand');
      jQuery('header').toggleClass('header-expand');
    });

    var container1 = jQuery('view-content');
    var container2 = jQuery('view-content');

    for (var i = 0; i < 500; i++) {
        container1.append("<p>"+i+"</p>");
    }

    for (var i = 0; i < 500; i++) {
        container2.append("<p>"+i+"</p>");
    }

    jQuery('#container1').mCustomScrollbar({
            theme:"dark-3"
    });

    jQuery('#container2').mCustomScrollbar({
            theme:"dark-3"
    });

    var clicks = 0;

    jQuery('.help-block a, .form-type-webform-multiple label').on('click',function(e) {
      if(clicks == 0) {
        jQuery('.eform-submission-patient-information-submit-form .field--name-field-email, .form-item-add-notify-mails').css('display','block');
        jQuery('.form-item-include-video-session-link').show();
        clicks++;
      }
      else {
        jQuery('.field-add-more-submit').click();
      }
    });
    if(jQuery('.path-frontpage').length > 0){
      jQuery("#views-exposed-form-patient-appointment-calendar-page-month #edit-submit-patient-appointment-calendar").prop('disabled',true);
      jQuery('#views-exposed-form-patient-appointment-calendar-page-month #edit-name--2').keyup(function () {
        if(jQuery(this).val().length > 0){
          jQuery("#views-exposed-form-patient-appointment-calendar-page-month #edit-submit-patient-appointment-calendar").prop('disabled',false);
        }else{
          jQuery("#views-exposed-form-patient-appointment-calendar-page-month #edit-submit-patient-appointment-calendar").prop('disabled',true);
        }
      });
    }
    //jQuery('.block-views-exposed-filter-blockpatient-appointment-calendar-page-month #views-exposed-form-patient-appointment-calendar-page-month input').attr('placeholder','Enter your name and we\'ll find any sessions booked for you')

    /*modal popup display state and add class if group popup is open*/
    var display_state = jQuery('#drupal-modal').css('display');

    if(display_state == 'block') {
      if(jQuery('.views-element-container .view').hasClass('view-user-group-listing')) {
        jQuery('#drupal-modal').addClass('testing');
      }
    }

    jQuery('a.group-icon').click(function() {
      jQuery('#drupal-modal').addClass('group-session-popup');
    });
    jQuery('.webform-submission-send-invite-form .webform-button--submit').click(function() {
      jQuery('body').addClass('success-popup');
    });

    jQuery('.group-session-popup .close').click(function() {
      jQuery('#drupal-modal').removeClass('group-session-popup');
    });

    jQuery(document).on('click',function(){
       jQuery('.collapse').collapse('hide');
       /*jQuery('.expandable').toggleClass('expand');*/
       if(jQuery('.expandable').hasClass('collapsed')) {
         jQuery('.expandable').removeClass('expand');
         jQuery('header').removeClass('header-expand');
       }
       else {
         jQuery('.expandable').addClass('expand');
         jQuery('header').addClass('header-expand');
       }
    });
    if(jQuery('#edit-field-user-preference-0').is(':checked')){
      jQuery('#edit-field-your-preference-email-wrapper').show();
      jQuery('#edit-field-your-phone-number-wrapper').hide();
    }

    if(jQuery('#edit-field-user-preference-1').is(':checked')){
      jQuery('#edit-field-your-preference-email-wrapper').hide();
      jQuery('#edit-field-your-phone-number-wrapper').show();
    }

    jQuery('#edit-field-user-preference-0').click(function(){
      jQuery('#edit-field-your-preference-email-wrapper').show();
      jQuery('#edit-field-your-phone-number-wrapper').hide();
    });
    jQuery('#edit-field-user-preference-1').click(function(){
      jQuery('#edit-field-your-preference-email-wrapper').hide();
      jQuery('#edit-field-your-phone-number-wrapper').show();
    });

     jQuery(".field--name-field-make-default-email input[type=checkbox]").click(function(){
        jQuery(".field--name-field-make-default-email input[type=checkbox]").prop('checked', false);
        if(jQuery(this).is(":checked")){
          jQuery(this).prop('checked', false);
        }
        else{
          jQuery(this).prop('checked', true);
        }
      });
      jQuery(".field--name-field-make-default input[type=checkbox]").click(function(){
        jQuery(".field--name-field-make-default input[type=checkbox]").prop('checked', false);
        if(jQuery(this).is(":checked")){
          jQuery(this).prop('checked', false);
        }
        else{
          jQuery(this).prop('checked', true);
        }
      });

      jQuery('input[type=tel]').keyup(function(e){
        if (/\D/g.test(this.value)){
          this.value = this.value.replace(/\D/g, '');
        }
        if (this.value.length == 10) {
          e.preventDefault();
        }
        else if (this.value.length > 10) {
          this.value = this.value.substring(0, 10);
        }
      });

    });
  }
};

(function($) {
  $.fn.reloadWindow = function() {
    location.reload();
  };
})(jQuery);

/*(function ($, Drupal) {
  Drupal.behaviors.myModuleBehavior = {
    attach: function (context, settings) {

    }
  };
})(jQuery, Drupal);*/
