// show more post
$(document).ready(function(){
    $(".user-post").slice(0, 5).show();
    $("#loadMore").on("click", function(e){
      e.preventDefault();
      $(".user-post:hidden").slice(0, 4).slideDown();
      if($(".user-post:hidden").length == 0) {
        $("#loadMore").text("No more to load").addClass("noContent");
      }
    });

    // hide other uploads
    $(".img").slice(0,9).show();
    // hide other music
    $(".music").slice(0,4).show();

    // prevent page resetting when reloading
    $('html, body').animate({
      scrollTop: $(this).offset().top
    }, 300);
})
$(window).on('load', function () {
  $('.loader').hide();
});

