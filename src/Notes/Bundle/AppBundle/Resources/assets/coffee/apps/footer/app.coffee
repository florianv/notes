###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'marionette'
  'core/bus'
  'apps/footer/controller'
], (Marionette, Bus, FooterController) ->

  app = new Marionette.Application()

  app.on 'start', ->
    new FooterController
      region: Bus.reqres.request 'footer_region'

  app
