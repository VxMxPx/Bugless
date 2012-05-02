(function($, window, undefined) {

	var Bugless = {
		// List of controllers to be autorun and loaded controllers
		List : {
			autorun: ['global'],
			controller: {}
		},

		/**
		 * Register new controller or model
		 * @param  {string} name    controller's / model's name
		 * @param  {string} type    type: controller || model
		 * @param  {object} content
		 */
		register: function(name, type, content) {
			if (type === 'controller') {
				this.List[type][name] = content;
			}
			else if (type === 'model') {
				Bugless[name] = content;
			}
			else {
				console.warn('Invalid type provided: ' + type);
			}
		},

		/**
		 * Will autorun controllers
		 */
		autorun: function() {
			for (var i = 0; i < this.List.autorun.count; i++) {
				this.run(this.autorunList[i]);
			}
		},

		/**
		 * Will run particular controller
		 * @param  {string} controllerName Controller's name
		 */
		run: function(controllerName) {
			this.List.controller[controllerName].init();
		},

		init: function() {
			this.autorun();
			this.Messages.init();
		}
	};

	//@include controllers/global.js
	//@include controllers/projects.js
	//@include controllers/users.js
	//@include models/messages.js
	//@include models/select.js

	Bugless.init();

	// Export to public space!
	window.Bugless = Bugless;

}(jQuery, window));