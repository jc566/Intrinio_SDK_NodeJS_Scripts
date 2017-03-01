// import the intrinio-client module (found in package.json)
var username = 'b53ca7da9d4a36ec6c2880700d200928'
var password = '4f7569d13ad10073691481e2a2667e3f'
var intrinio = require('intrinio-client')(username, password)

// express
var express = require('express')
var app = express()


app.get('/ping', function (data, response) {
  console.log('received ping, sending pong')
  response.json({ text: 'pong' })
})

/**
 * Get stock information by providing ticker symbol (required)
 * example: /stocks?sym=AAPL
 *
 * Provide a CSV of keys to fetch (optional, default: ticker,legal_name,stock_echange)
 * Use either of the following methods:
 * 1. example: /stocks?keys=ticker,legal_name,stock_exchange
 */
app.get('/stocks', function (data, response) {
  // TODO safety checks:
  // make sure sym was passed
  // make sure sym is valid
  // no numbers

  // TODO safety checks:
  // check the keys

  // user input
  var sym = data.query.sym.toUpperCase()

  var defaultKeys = [
    'ticker',
    'legal_name',
    'stock_exchange'
  ]
  var keys = data.query.keys || defaultKeys

  var keysAsArray

  if (typeof keys === 'string') {
    // turn the csv string into an array
    keysAsArray = keys.split(',')
  } else if (typeof keys === 'array') {
    // leave it alone
    keysAsArray = keys
  } else {
    // idk what "keys" is
    // TODO throw an error
  }

  // declare the info object
  var info = {}

  intrinio.ticker(sym) // make an api call (async)
  .on('complete', function (d, r) {
    // ... this might take a while
    if (d) {
      for (var i = 0; i < keysAsArray.length; i++) {
        var key = keysAsArray[i]
        info[key] = d[key] // IMPORTANT! use []-notation, not dot-notation
      }

      // send response to "browser" (client)
      response.json(info) // 200
    } else {
      console.log('invalid symbol: ' + sym)
      response.json({ 'error': 'not_found' }) // TODO set status code to 404
    }
  })
})

app.listen(9090, function () {
  console.log('listening on port 9090!')
})
