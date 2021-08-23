/**
 * @module    block_totem/slideshow
 * @package   blocktotem
 * @copyright 2020 Aureliano Martini
 * @licence   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since     3.0
 */

define(['jquery', 'core/ajax', 'core/templates', 'core/notification'], function($, ajax, templates, notification) {
    return {
        load: function(blockid, date, offset, limit, skipweekend, logo) {
            if (offset == limit) {
                offset = 0;
            }

            var promises = ajax.call([
                {
                    methodname: 'get_totemtable',
                    args: {
                        blockid: blockid,
                        date: date,
                        offset: offset,
                        skipweekend: skipweekend,
                        logo: logo
                    }
                }
            ]);
            promises[0].done(function(response) {
                templates.render('block_totem/totem_table_fullscreen', response).done(function(html) {
                    $('[data-region="totem_fullscreen"]').replaceWith(html);

                    var h = $('[data-region="totem_fullscreen"]').height();
                        h -= $('[data-region="totem_fullscreen-title"]').height();
                        h -= $('[data-region="totem_fullscreen-msg"]').height();
                    $('[data-region="totem_fullscreen-scroll"]').css('height', h + 'px');
                    $('[data-region="totem_fullscreen-scroll"]').css('overflow', 'hidden');
                    $('[data-region="totem_fullscreen-table"]').removeClass('hidden');

                    //SET SCROLL FUNCTION
                    var speed = 2000;
                    var scrollItemIndex = 0;
                    var scrollTimeInterval = setInterval(function() {
                        var row = $('[data-region="totem_fullscreen-table-row"]');

                        if (row.length > 0) {
                            row[scrollItemIndex].scrollIntoView();
                            scrollItemIndex++;
                        }

                        if (row.length == scrollItemIndex ) {
                            clearTimeout(scrollTimeInterval);
                            setTimeout(function() {
                                //Show loading screen
                                var loadingHTML =  '<p style="text-align: center">';
                                    loadingHTML += '<img src="pix/loading.gif" alt="loading..." width="16px"></p>';
                                $('[data-region="totem_fullscreen"]').innerHTML = loadingHTML;

                                //AJAX CALL
                                require(["block_totem/get_ajax_totemtable"], function(totemtable) {
                                    totemtable.load(blockid, date, offset+1, limit, skipweekend, logo);
                                });
                            }, speed);
                        }
                    }, speed);
                }).fail(notification.exception);
            }).fail(notification.exception);
        }
    };
});