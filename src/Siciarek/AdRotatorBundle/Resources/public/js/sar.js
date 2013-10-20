var Sar = Sar || {};

$(document).ready(function () {
    Sar.rotateAfter = 30;
    Sar.sarDataUrl = '/sar/data/__TYPE__/c/__COUNT__';
    Sar.sarIncrementClicksUrl = '/sar/click/__SLUG__';
    Sar.ads = {};
    Sar.links = $('a[id^="a-link-"]');

    Sar.incrementClicks = function (id) {

        var elem = $('#' + id);

        if (elem.attr('href') === '#a-link-empty') {
            if (elem.hasClass('flash')) {

                var slug = elem.attr('data-var');
                var url = this.sarIncrementClicksUrl.replace(/__SLUG__/, slug);

                $.ajax({
                    url: url,
                    async: false
                });
            }
            return false;
        }

        return true;
    };

    Sar.rotate = function () {

        for (var type in this.ads) {
            if (this.ads.hasOwnProperty(type)) {
                var count = this.ads[type];
                var data = [];

                $.ajax({
                    dataType: 'json',
                    url: this.sarDataUrl.replace(/__TYPE__/, type).replace(/__COUNT__/, count),
                    async: false,
                    success: function (response) {
                        data = response;
                    }
                });

                var tlinks = $('a[id^="a-link-' + type + '-"]');

                tlinks.each(function (i, e) {

                    var ad = data[i];
                    var href = ad.href === null ? '#a-link-empty' : ad.href;

                    var link = $(e);

                    var movie = link.find('object');
                    var image = link.find('img');
                    image.hide();
                    movie.hide();

                    link.attr('href', href);
                    link.attr('title', ad.title);
                    link.attr('class', ad.filetype);
                    link.attr('data-var', ad.slug);
                    link.css('cursor', ad.href == null ? 'default' : 'pointer');

                    if (ad.filetype === 'flash') {
                        movie.find('param[name="movie"]').attr('value', ad.src);
                        movie.find('embed').attr('src', ad.src);
                        movie.show();
                    }
                    else {
                        image.attr('src', ad.src);
                        image.attr('alt', ad.title);
                        image.show();
                    }
                });
            }
        }
    };

    Sar.links.each(function (i, e) {
        var type = $(e).attr('id').replace(/^\D+(\d+)\-.*$/, '$1');
        if (typeof Sar.ads[type] === 'undefined') {
            Sar.ads[type] = 0;
        }
        Sar.ads[type]++;

    });

    setInterval(function () {
        Sar.rotate()
    }, Sar.rotateAfter * 1000);
});
