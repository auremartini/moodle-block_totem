/**
 * @module    block_totem/slideshow
 * @package   blocktotem
 * @copyright 2020 Aureliano Martini
 * @licence   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since     3.0
 */

define(['jquery', 'core/ajax', 'core/templates', 'core/notification'], function($, ajax, templates, notification) {
    return {
        load: function(date, index, limit) {
            var promises = ajax.call([
                {
                    methodname: 'get_totemtable',
                    args: {
                        date: date,
                        index: index,
                        limit: limit
                    }
                }
            ]);
            promises[0].done(function(response) {
//                templates.renderer('block_totem/totem_table_fullscreen', response).done(function(html, js) {
//                    $('[data-region="index-page"]').replaceWith(html);
//                    templates.runTemplateJS(js);
//                }).fail(notification.exception);
            }).fail(notification.exception);
        }
    };
});