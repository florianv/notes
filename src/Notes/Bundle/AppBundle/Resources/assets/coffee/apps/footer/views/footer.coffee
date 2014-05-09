###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'marionette'
  'text!apps/footer/templates/footer.html'
], (Marionette, Template) ->

  class FooterView extends Marionette.ItemView
    template: Template
