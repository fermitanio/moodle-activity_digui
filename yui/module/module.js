YUI.add('moodle-digui-module', function(Y) {
  // Your module code goes here.
 
  // Define a name space to call
  M.digui = M.digui || {};
  M.digui.module = {
    init: function() {
	var ss1 = document.createElement('script');
	ss1.type= 'text/javascript';
	ss1.src= 'helper.js';
	var hh1 = document.getElementsByTagName('head')[0];
	hh1.appendChild(ss1);
    }
	
  };
}, '@VERSION@', {
  requires: ['node']
});
