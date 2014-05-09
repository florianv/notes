###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define ['jquery', 'jquery.cookie'], ($) ->

  ###
    Manages Oauth2 (Resource owner grant) authentication.
  ###
  class Auth

    ACCESS_TOKEN_COOKIE_NAME: 'auth.access_token'
    REFRESH_TOKEN_COOKIE_NAME: 'auth.refresh_token'

    ###
      Creates a new Auth.
    ###
    constructor: (tokenUrl, clientId, clientSecret, refreshTokenTtl = 1209600) ->
      @tokenUrl = tokenUrl
      @clientId = clientId
      @clientSecret = clientSecret
      @refreshTokenTtl = refreshTokenTtl

    ###
      Gets the access token if existing and not expired.

      @return The access token or NULL
    ###
    getAccessToken: ->
      accessToken = $.cookie @ACCESS_TOKEN_COOKIE_NAME
      return if accessToken? then accessToken else null;

    ###
      Gets the refresh token if existing and not expired.

      @return The refresh token or NULL
    ###
    getRefreshToken: ->
      refreshToken = $.cookie @REFRESH_TOKEN_COOKIE_NAME
      return if refreshToken? then refreshToken else null;

    ###
      Logs in the user by fetching an access token.

      @param username The username
      @param password The password
      @param success  A success callback (optional)
      @param error    An error callback (optional)
    ###
    login: (username, password, success, error) ->
      @_fetchPost
        grant_type: 'password'
        username: username
        password: password,
        success,
        error

    ###
     Tells if the user needs to login.
     If no access token is present we try to fetch it from the refresh token.

     @return True if the user needs to login, false otherwise
    ###
    needsLogin: ->
      if @getAccessToken() != null
        return false

      refreshToken = @getRefreshToken()
      if refreshToken != null
        @_fetchAccessTokenWithRefreshToken(refreshToken)
        if @getAccessToken() == null
          return true
        return false
      true

    ###
      Logs out the user by clearing the stored tokens.
    ###
    logout: ->
      $.removeCookie @ACCESS_TOKEN_COOKIE_NAME
      $.removeCookie @REFRESH_TOKEN_COOKIE_NAME

    ###
      Fetches an access token with a previously created refresh token.

      @param refreshToken The refresh token
      @param success      A success callback (optional)
      @param error        An error callback (optional)
    ###
    _fetchAccessTokenWithRefreshToken: (refreshToken, success, error) ->
      @_fetchPost
        grant_type: 'refresh_token'
        refresh_token: refreshToken,
        success,
        error

    ###
      Sends a json post request to fetch a token (internal).

      @param postData The data to post
      @param success  A success callback (optional)
      @param error    An error callback (optional)
    ###
    _fetchPost: (postData, success, error) ->
      $.ajax
        url: @tokenUrl
        async: false
        type: 'POST'
        headers:
          Accept: 'application/json'
          'Content-Type': 'application/json'
          Authorization: 'Basic ' + btoa(@clientId + ':' + @clientSecret)
        dataType: 'json'
        data: JSON.stringify postData
        success: (data, textStatus, jqXHR) =>
          @_storeData data
          success(data, textStatus, jqXHR) if success?
        error: (jqXHR, textStatus, errorThrown) ->
          error(jqXHR, textStatus, errorThrown) if error?

    ###
      Stores the data received in the token response (internal).

      @param data The received data
    ###
    _storeData: (data) ->
      accessTokenExpiration = new Date
      accessTokenExpiration.setSeconds accessTokenExpiration.getSeconds + parseInt data.expires_in

      refreshTokenExpiration = new Date
      refreshTokenExpiration.setSeconds refreshTokenExpiration.getSeconds + @refreshTokenTtl

      $.cookie @ACCESS_TOKEN_COOKIE_NAME, data.access_token, expires: accessTokenExpiration
      $.cookie @REFRESH_TOKEN_COOKIE_NAME, data.refresh_token, expires: refreshTokenExpiration
