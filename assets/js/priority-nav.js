(function () {
    'use strict';
    var menu, moreItem, moreSub;

    function init() {
        menu = document.getElementById('primary-menu');
        if (!menu) return;

        moreItem = document.createElement('li');
        moreItem.id = 'nav-more-item';
        moreItem.innerHTML = '<a href="#" aria-haspopup="true">&middot;&middot;&middot;</a><ul class="sub-menu"></ul>';
        menu.appendChild(moreItem);
        moreSub = moreItem.querySelector('.sub-menu');

        moreItem.querySelector('a').addEventListener('click', function (e) {
            e.preventDefault();
            moreItem.classList.toggle('open');
        });
        document.addEventListener('click', function (e) {
            if (!moreItem.contains(e.target)) moreItem.classList.remove('open');
        });

        calculate();
        menu.style.opacity = '1';
        menu.style.overflow = 'visible';
        window.addEventListener('resize', debounce(calculate, 150));
    }

    function calculate() {
        if (window.innerWidth <= 768) {
            while (moreSub.firstElementChild) menu.insertBefore(moreSub.firstElementChild, moreItem);
            moreItem.style.display = 'none';
            return;
        }

        while (moreSub.firstElementChild) menu.insertBefore(moreSub.firstElementChild, moreItem);
        moreItem.style.display = 'none';

        var nav = document.getElementById('site-nav');
        var container = nav ? nav.querySelector('.container') : menu.parentElement;
        var available = container.offsetWidth;

        var items = Array.from(menu.children).filter(function (li) { return li !== moreItem; });
        var total = items.reduce(function (s, li) { return s + li.offsetWidth; }, 0);

        if (total <= available) return;

        moreItem.style.display = '';
        while (items.length) {
            var w = items.reduce(function (s, li) { return s + li.offsetWidth; }, 0) + moreItem.offsetWidth;
            if (w <= available) break;
            var last = items.pop();
            moreSub.insertBefore(last, moreSub.firstChild);
        }
    }

    function debounce(fn, ms) {
        var t;
        return function () { clearTimeout(t); t = setTimeout(fn, ms); };
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
