var bit = document.getElementById("bit");
var doge = document.getElementById("doge");
var eth = document.getElementById("eth");


var settings = {
    "async" : true,
    "scrossDomain" : true,
    "url" : "https://api.coingecko.com/api/v3/simple/price?ids=dogecoin%2Cbitcoin%2Cethereum&vs_currencies=usd",
    "method" : "GET",
    "headers" : {}
}

$.ajax(settings).done(function(response){
    bit.innerHTML = response.bitcoin.usd;
    doge.innerHTML = response.dogecoin.usd;
    eth.innerHTML = response.ethereum.usd;
});