/**
 * @module    block_totem/add_event_edit_form_dynamics
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
                    {
                        methodname: 'get_userlist',
                        args: { source: parseInt(params.source + ""), sourceid: parseInt(params.sourceid + "") }
                    }
                ]);
                promises[0].done(function(response) {
                    document.getElementById('id_useridlist').innerHTML = "";
                    var option = document.createElement("option");
                    option.value = '';
                    option.text = '';
                    document.getElementById('id_useridlist').add(option);
                    response.forEach(function(item) {
                        var option = document.createElement("option");
                        option.value = item.id;
                        option.text = item.firstname + " " + item.lastname;
                        document.getElementById('id_useridlist').add(option);
                    });
                    document.getElementById("id_useridlist").value = document.getElementsByName('userid')[0].value;
                    document.getElementById('id_useridlist').addEventListener('change', function(){
                        document.getElementsByName('userid')[0].value = document.getElementById("id_useridlist").value;
                        var promises2 = ajax.call([
                            {
                                methodname: 'get_teachinglist',
                                args: {
                                    blockteachings: String(params.blockteachings),
                                    teacherid: parseInt(document.getElementById("id_useridlist").value + '')
                                }
                            }
                        ]);
                        promises2[0].done(function(response2) {
                            document.getElementById('id_teachinglist').innerHTML = "";
                            var option = document.createElement("option");
                            option.value = '';
                            option.text = '';
                            document.getElementById('id_teachinglist').add(option);
                            response2.forEach(function(item) {
                                var option = document.createElement("option");
                                option.value = item.id;
                                option.text = item.name;
                                document.getElementById('id_teachinglist').add(option);
                            });
                            if(response2.length == 1) {
                                document.getElementsByName('teaching')[0].value = response2[0].id;
                                document.getElementById("id_teachinglist").value = response2[0].id;
                            }
                            document.getElementById('id_teachinglist').addEventListener('change', function(){
                                document.getElementsByName('teaching')[0].value = document.getElementById("id_teachinglist").value;
                            }, false);
                        }).fail(notification.exception);
                    }, false);
                }).fail(notification.exception);
             });
        }
    };
});