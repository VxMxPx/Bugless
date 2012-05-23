Bugless = (->

	controllers = {}

	run = (controllerName)->
		controllers[controllerName]()

	register = (controllerName, code)->
		controllers[controllerName] = code

	# Public methods!
	return {
		run: run
		register: register
	}
)()

#@include models/log.coffee
#@include models/messages.coffee
#@include models/select.coffee
#@include models/tags.coffee
#@include controllers/users.coffee

# Export Bugless
window.Bugless = Bugless