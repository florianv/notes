###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'core/bus'
  'marionette'
  'components/alert/controller'
], (Bus, Marionette, AlertController) ->

  Bus.commands.setHandler 'show:alert', (region, type, message) ->
    new AlertController
      region: region
      type: type
      message: message
