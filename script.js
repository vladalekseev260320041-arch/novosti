document.addEventListener('DOMContentLoaded', function () {
  var container = document.querySelector('.pagination');
  if (!container) return;

  var numberButtons = Array.prototype.slice.call(container.querySelectorAll('.page:not(.page-arrow)'));
  var prevBtn = null;
  var nextBtn = container.querySelector('.page-arrow.next');
  var cards = Array.prototype.slice.call(document.querySelectorAll('.news-card'));
  var hasClientPaging = cards.some(function (c) { return c.hasAttribute('data-page'); });

 
  if (!hasClientPaging) return;

  function setActive(index) {
    numberButtons.forEach(function (b) { b.classList.remove('is-active'); b.removeAttribute('aria-current'); });
    var btn = numberButtons[index];
    if (!btn) return;
    btn.classList.add('is-active');
    btn.setAttribute('aria-current', 'page');

    var pageNum = parseInt(btn.textContent.trim(), 10);
    cards.forEach(function (card) {
      var cardPage = parseInt(card.getAttribute('data-page') || '1', 10);
      if (cardPage === pageNum) {
        card.classList.remove('is-hidden');
      } else {
        card.classList.add('is-hidden');
      }
    });
  }
  
  numberButtons.forEach(function (btn, idx) {
    btn.addEventListener('click', function (e) {
      
      if (btn.tagName === 'A') e.preventDefault();
      setActive(idx);
    });
  });

  function getActiveIndex() {
    var i = numberButtons.findIndex(function (b) { return b.classList.contains('is-active'); });
    return i === -1 ? 0 : i;
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', function (e) {
      if (nextBtn.tagName === 'A') e.preventDefault();
      var i = getActiveIndex();
      setActive(Math.min(numberButtons.length - 1, i + 1));
    });
  }

  setActive(getActiveIndex());
});



