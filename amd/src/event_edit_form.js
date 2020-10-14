/**
 * @module    block_totem/event_edit_form
 * @package   block_totem
 * @copyright 2020 Aureliano Martini
 * @licence   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since     3.0
 */

define(['jquery'], function() {
    return {
        init: function(params) {
            require(['jquery', 'core/ajax', 'core/notification'], function($, ajax, notification) {
                var promises = ajax.call([
                    { methodname: 'get_userlist', args: { cohortid: parseInt(params.cohortid + "") } }
                ]);
                promises[0].done(function(response) {
                    document.getElementById('id_userid').innerHTML = "";
                    response.forEach(function(item) {
                        var option = document.createElement("option");
                        option.value = item.id;
                        option.text = item.firstname + " " + item.lastname;
                        document.getElementById('id_userid').add(option);
                    });
                }).fail(notification.exception);
             });
        }
    };
});