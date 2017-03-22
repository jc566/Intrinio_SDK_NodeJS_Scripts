

// import the intrinio-client module (found in package.json)
var username = 'b53ca7da9d4a36ec6c2880700d200928'
var password = '4f7569d13ad10073691481e2a2667e3f'
var intrinio = require('intrinio-client')(username, password)

var express = require('express')
var app = express()

//this is a worst case scenario, simple test
app.get('/ping', function(data, response) {
	console.log('recieved ping, sending pong')
	response.json('pong')
})
/************************************************
Get stock information by providing ticker symbol*
						*
'/stocks' is the url path extension		*
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
	//var keys = data.query.keys.toLowerCase() //ensure that query is lowercase
	//create empty object
	var stockInfo = {
	Ticker: '',
	Name: '',
	SE: '',
	CEO: '',
	URL: '',
	Desc: '',
	Open: '',
	Close: '',
	High:'',
	Low:''
	}; 

	console.log(sym)
	//console.log(keys)
	
/*
ALSO!!!!
The user will likely be searching by legal name NOT ticker symbol.
THIS block is taking in ticker symbol so we can have a '/searchTicker' area
that will allow users to search by company name, and retrive a ticker symbol, then
use all the functionanlity within this code to provide the information
FINALLY!!!
Need to make a map/dictionary to handle these incoming keys
keys must come in as lower case
must be in array
find a way to split based on commas
*/
	intrinio.ticker(sym) //make an api call with sym
	.on('complete', function(tickerData,tickerResponse){
		if(tickerData) {//if there is ticker data then ...
				
                console.log("MOTHER FUCKER")

		
		
		stockInfo.Ticker = tickerData.ticker
		stockInfo.CEO = tickerData.ceo
		stockInfo.URL = tickerData.company_url
		stockInfo.Name = tickerData.legal_name
		stockInfo.SE = tickerData.stock_exchange
		stockInfo.Desc = tickerData.short_description
		
		//response.json(stockInfo) //send response to client (this can be "browser" or another file like php file)
		
		}//end of 'if(tickerData)'
	})//end of 'intrinio.ticker(sym)'
	
	intrinio.prices(sym)
	.on('complete', function(priceData,priceResponse){
		if(priceData){
		
		stockInfo.Open = priceData.data[0].open
		stockInfo.High = priceData.data[0].high
		stockInfo.Low = priceData.data[0].low
		stockInfo.Close = priceData.data[0].close
		response.json(stockInfo)
		}//end of 'if(priceData)'
	})//end of 'intrinio.prices(sym)'

})//end of '/stocks'


/*
Get News for a company
*/
app.get('/news', function(Data,Response){
	var sym = Data.query.sym.toUpperCase()
	
	var news = {}

	intrinio.news(sym) //make an api call with sym
	.on('complete', function(newsData, newsResponse){
		if(newsData){
	console.log(newsData)
	
		
	news = newsData
	Response.json(news)
}
	
	})//end of 'intrinio.news(sym)'
})//end of '/news'


//the '9090' can be replaced with the proper server we are using
app.listen(9090, function(){
	console.log('Im listening to server local host 9090')
})


