
jQuery(window).load(function() {
   
   // Page Preloader
   jQuery('#status').fadeOut();
   jQuery('#preloader').delay(30).fadeOut(function(){
      jQuery('body').delay(30).css({'overflow':'visible'});
   });
});