'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('depwar.services', [])

	.service('flashMessage', function () {
		return {
			message: null,
			callback: null,
			setMessage: function(message) {
				this.message = message;
				this.callback();
			}
		}
	})

	.service('modalWarning', function() {

		return {
			callback: null,
			show: function( message, callback ) {
				this.callback( message, callback );
			}
		}

	})

	.value('version', '0.1');
