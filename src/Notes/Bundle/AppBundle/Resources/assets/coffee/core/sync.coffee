###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define ['backbone', 'core/bus'], (Backbone, Bus) ->

  oldSync = Backbone.sync

  Backbone.sync = (method, entity, options = {}) ->

    # Configure triggers on the model & collection before and after syncing
    _.defaults options,
      beforeSend: _.bind(methods.beforeSend, entity)
      complete: _.bind(methods.complete, entity)

    # Apply some headers to all requests
    auth = Bus.reqres.request 'auth'
    options.headers =
      Authorization: 'Bearer ' + auth.getAccessToken()
      Application: 'application/json'
      'Content-Type': 'application/json'

    # Trigger an event when we get a 401 status code
    errorCallback = (jqXHR) ->
      if jqXHR.status == 401
        Bus.events.trigger 'sync:unauthorized'
    if options.error?
      _errorCallback = options.error
      options.error = (jqXHR, textStatus, errorThrown) ->
        errorCallback jqXHR
        _errorCallback jqXHR, textStatus, errorThrown
    else
      options.error = (jqXHR) ->
        errorCallback jqXHR

    oldSync(method, entity, options)

  methods =
    beforeSend: ->
      @trigger "sync:start", @

    complete: ->
      @trigger "sync:stop", @
