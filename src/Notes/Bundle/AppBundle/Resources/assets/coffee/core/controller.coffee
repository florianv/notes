###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'marionette'
], (Marionette) ->

  # author: https://github.com/brian-mann
  class Controller extends Marionette.Controller

    constructor: (options = {}) ->
      @region = options.region
      super options

    close: (args...) ->
      delete @region
      delete @options
      super args

    show: (view) ->
      @listenTo view, 'close', @close
      @region.show view
