jQuery(function ($) {
  $(document).ready(function () {
    $(".sbw_sidebar-widget__menu-heading").click(function () {
      $(this).next().toggleClass("sbw-hidden");
    });

    $(".sbw_sidebar-widget__category-heading").click(function () {
      $(this).next().toggleClass("sbw-hidden");
    });

    //price slider
    var minPrice = $("#amount_min").val();
    var maxPrice = $("#amount_max").val();

    $("#anps-price-range-slider").slider({
      range: true,
      step: 10,
      min: parseInt(minPrice),
      max: parseInt(maxPrice),
      values: [parseInt(minPrice), parseInt(maxPrice)],
      slide: function (event, ui) {
        $("#amount_min").replaceWith(
          `<input id='amount_min' type='text' value='${ui.values[0]}' style="display:none;"/>`
        );
        $("#amount_max").replaceWith(
          `<input id='amount_max' type='text' value='${ui.values[1]}' style="display:none;"/>`
        );
        $(".value-left").text(`Min: ${ui.values[0]}`);
        $(".value-right").text(`Max: ${ui.values[1]}`);
      },
    });
  });
});
