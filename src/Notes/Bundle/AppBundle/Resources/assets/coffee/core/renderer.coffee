###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define ['marionette', 'handlebars', 'moment'], (Marionette, Handlebars, Moment) ->

  Handlebars.default.registerHelper 'formatDate', (string) ->
    moment = Moment string, 'YYYY-MM-DD HH:mm:ss'
    moment.format 'MMM Do YYYY'

  Marionette.Renderer.render = (template, data) ->
    return if template == false
    compiled = Handlebars.default.compile template
    compiled data
