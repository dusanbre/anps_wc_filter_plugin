jQuery(function ($) {
  $(document).ready(function () {
    $(".sbw_sidebar-widget__menu-heading").click(function () {
      $(this).next().toggleClass("sbw-hidden");
    });

    $(".sbw_sidebar-widget__category-heading").click(function () {
      $(this).next().toggleClass("sbw-hidden");
    });
  });
});
