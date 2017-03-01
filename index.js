// import the intrinio-client module (found in package.json)
var username = 'b53ca7da9d4a36ec6c2880700d200928'
var password = '4f7569d13ad10073691481e2a2667e3f'
var intrinio = require('intrinio-client')(username, password)

var express = require('express')
var app = express()

app.get('/ping', function(data, response) {
	console.log('recieved ping, sending pong')
	response.json('pong')
})
/************************************************
Get stock information by providing ticker symbol*
						*
Example: /stocks?sym=aapl 			*
Example for Keys: 				*
/stocks?keys=ticker				*
/stocks?keys=legal_name				*
/stocks?keys=short_description			*
/stocks?keys=ticker,short_description		*
************************************************/
app.get('/stocks', function(data, response) {

	//this is the variable that holds user input
	var sym = data.query.sym.toUpperCase()	//ensure that the query is uppercase

	//this is for collection of keys
	var keys = data.query.keys 
	//create empty object
	var temp = {} 

	
	intrinio.ticker(sym) //make an api call with sym
	.on('complete', function(tickerData,tickerResponse){
		if(tickerData) {//if there is ticker data then ...
				


		console.log(tickerData.ticker)
		console.log(tickerData.legal_name)
		console.log(tickerData.short_description)
	
		temp = tickerData.short_description
		response.json(temp) //send response to client (this can be "browser" or another file like php file)
		}
	})
})

//the '9090' can be replaced with the proper server we are using
app.listen(9090, function(){
	console.log('Im listening to server local host 9090')
})


