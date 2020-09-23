var option = document.createElement("option");
option.value = "My";
option.text = "Aureliano Martini";

document.getElementById('id_userid').add(option);

require(['core/ajax'], function(ajax) {
    var promises = ajax.call([
        { methodname: 'core_get_string', args: { component: 'mod_wiki', stringid: 'pluginname' } },
        { methodname: 'core_get_string', args: { component: 'mod_wiki', stringid: 'changerate' } }
    ]);
 
   promises[0].done(function(response) {
       console.log('mod_wiki/pluginname is' + response);
   }).fail(function(ex) {
       // do something with the exception
   });
 
   promises[1].done(function(response) {
       console.log('mod_wiki/changerate is' + response);
   }).fail(function(ex) {
       // do something with the exception
   });
});

alert ('AAA');