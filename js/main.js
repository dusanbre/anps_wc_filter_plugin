function toggleCat() {
  const cat = document.getElementById("visible-cat");
  cat.classList.toggle("hidden");
}

function toggleColor() {
  const color = document.getElementById("visible-color");
  color.classList.toggle("hidden");
}

function toggleSize() {
  const size = document.getElementById("visible-size");
  size.classList.toggle("hidden");
}

var inputLeft = document.getElementById("input-left");
var inputRight = document.getElementById("input-right");

var thumbLeft = document.querySelector(
  ".sidebar-widget__price-slider-thumb.left"
);
var thumbRight = document.querySelector(
  ".sidebar-widget__price-slider-thumb.right"
);
var range = document.querySelector(".sidebar-widget__price-slider-range");

const valLeft = document.querySelector(".value-left");
const valRight = document.querySelector(".value-right");

function setLeftValue() {
  const minimum = inputLeft.min;
  const max = inputLeft.max;

  inputLeft.value = Math.min(inputLeft.value, inputRight.value - 1);
  var percent = ((inputLeft.value - minimum) / (max - minimum)) * 100;

  thumbLeft.style.left = percent + "%";
  range.style.left = percent + "%";

  const value = inputLeft.value + `€`;
  valLeft.innerHTML = value;
}
setLeftValue();

function setRightValue() {
  const min = inputRight.min;
  const max = inputRight.max;

  inputRight.value = Math.max(inputRight.value, inputLeft.value + 1);
  var percent = ((inputRight.value - min) / (max - min)) * 100;

  thumbRight.style.right = 100 - percent + "%";
  range.style.right = 100 - percent + "%";

  const value = inputRight.value + "€";
  valRight.innerHTML = value;
}
setRightValue();

inputLeft.addEventListener("input", setLeftValue);
inputRight.addEventListener("input", setRightValue);
