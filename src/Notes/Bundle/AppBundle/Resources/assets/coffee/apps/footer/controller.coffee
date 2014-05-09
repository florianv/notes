###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'marionette'
  'core/controller'
  'apps/footer/views/footer'
], (Marionette, Controller, FooterView) ->

  class FooterController extends Controller

    initialize: ->
      footerView = @getFooterView()
      @show footerView

    getFooterView: ->
      new FooterView
