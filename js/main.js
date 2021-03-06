const inputLeft = document.getElementById("sbw_input-left");
console.log(inputLeft);
// const inputRight = document.getElementById("input-right");

// const thumbLeft = document.querySelector(
//   ".sbw_sidebar-widget__price-slider-thumb.left"
// );
// const thumbRight = document.querySelector(
//   ".sbw_sidebar-widget__price-slider-thumb.right"
// );
// const range = document.querySelector(".sbw_sidebar-widget__price-slider-range");

// const valLeft = document.querySelector(".value-left");
// const valRight = document.querySelector(".value-right");

// function setLeftValue() {
//   const min = inputLeft.min;
//   const max = inputLeft.max;

//   inputLeft.value = Math.min(inputLeft.value, inputRight.value - 1);
//   const percent = ((inputLeft.value - min) / (max - min)) * 100;

//   thumbLeft.style.left = percent + "%";
//   range.style.left = percent + "%";

//   const value = inputLeft.value + `€`;
//   valLeft.innerHTML = value;
// }
// setLeftValue();

// function setRightValue() {
//   const min = inputRight.min;
//   const max = inputRight.max;

//   inputRight.value = Math.max(inputRight.value, inputLeft.value + 1);
//   const percent = ((inputRight.value - min) / (max - min)) * 100;

//   thumbRight.style.right = 100 - percent + "%";
//   range.style.right = 100 - percent + "%";

//   const value = inputRight.value + "€";
//   valRight.innerHTML = value;
// }
// setRightValue();

// inputLeft.addEventListener("input", setLeftValue);
// inputRight.addEventListener("input", setRightValue);

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
