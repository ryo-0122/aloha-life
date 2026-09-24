/**
 * 施工事例詳細（single-cases.php）のギャラリースライダー
 * 矢印・サムネイルのクリック、キーボードの左右キーで切り替え。
 */
(function () {
  'use strict';

  var slider = document.querySelector('[data-case-slider]');
  if (!slider) return;

  var slides = slider.querySelectorAll('.CaseDetail__slider-slide');
  var dots = slider.querySelectorAll('.CaseDetail__slider-dots i');
  var thumbs = document.querySelectorAll('.CaseDetail__thumb');
  var prev = slider.querySelector('.CaseDetail__slider-arrow--prev');
  var next = slider.querySelector('.CaseDetail__slider-arrow--next');
  var current = 0;

  if (slides.length < 2) return;

  function show(index) {
    current = (index + slides.length) % slides.length;
    [slides, dots, thumbs].forEach(function (list) {
      Array.prototype.forEach.call(list, function (el, i) {
        el.classList.toggle('is-active', i === current);
      });
    });
    Array.prototype.forEach.call(thumbs, function (el, i) {
      el.setAttribute('aria-current', i === current ? 'true' : 'false');
    });
  }

  if (prev) prev.addEventListener('click', function () { show(current - 1); });
  if (next) next.addEventListener('click', function () { show(current + 1); });

  Array.prototype.forEach.call(thumbs, function (thumb) {
    thumb.addEventListener('click', function () {
      show(parseInt(thumb.getAttribute('data-index'), 10) || 0);
    });
  });

  slider.setAttribute('tabindex', '0');
  slider.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowLeft') show(current - 1);
    if (e.key === 'ArrowRight') show(current + 1);
  });
})();
