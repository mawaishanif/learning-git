

        // var st = new XMLHttpRequest();
        // var url = "/apps/foxpixel/checkembbed";
      
        // st.open("GET", url, true);
        // st.onreadystatechange = function() {
        //     if (st.readyState === 4 && st.status === 200) {
        //         var _result = JSON.parse(this.responseText);
                  
        //            if(_result.status==false){
        //             fox();
        //            }
        //            if(_result.status==true){

        //             console.log("Pixels Fox Embeded App Shopifytheme2.o");
        //             if(ShopifyAnalytics.meta.page.path == "/checkout/thank_you")
        //             {
        //                 fox();
        //             }
        //             try {
        //                 var order_page;
        //                 var str = window.location.href; 
        //                 var res = str.match(/orders/);
        //                  order_page=res['0'];
                      
        //                 if(res['0']=='orders')
        //                 {
        //                     fox();
        //                     console.log('order page is working');
        //                 }
        //               }
        //               catch(err) {
                      
        //               }
                   
                   
               
                   
        //            }
                   
              
        //     }
        // };
        // st.send();
        if(ShopifyAnalytics.meta.page.path == "/checkout/thank_you")
        {
            fox();
            console.log(' thanks you page');
        }
        try {
            var order_page;
            var str = window.location.href; 
            var res = str.match(/orders/);
             order_page=res['0'];
          
            if(res['0']=='orders')
            {
                fox();
                console.log('order page is working');
            }
          }
          catch(err) {
          
          }
        


function fox() {
        
        console.log("Pixels Fox Script Tag");
const FB_APP_URL = '/apps/foxpixel'; // proxy URL for shopify app



// script tag counter server side
if(ShopifyAnalytics.meta.page.path == "/checkout/thank_you")
{
    let countParam = {
        route:"count",
    }
    scripttest(countParam)
}

var FB_PIXEL_DATA = [];// Pixel collection from server
var CheckoutPixels = [];// Pixel After Filter Tags and Collection
var alphaPixels = []
var intiPixel = []
var catData = [] // Checkout Data
var aTCIC; // server response variable
var theRandomNumber = Math.floor(Math.random() * 999999) + 1; // random number generate for pixels ids
var d = new Date(); // date for pixel 
var n = d.getTime(); // time for pixel


// Event ids variables
var pageviewId;
var searchId;
var viewContentId;
var pageViewEventId = "";
var searchEventId = "";
var viewContentEventId = "";
var addToCartEventId = "";
var InitiateCheckoutEventId = "";
var purchaseEventId = ""
var viewCategoryEventId = ""
var cartEventId = ""

// Facebook pixel code from facebook
!function(f, b, e, v, n, t, s) {
if (f.fbq) return;
n = f.fbq = function() {
    n.callMethod ?
        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
};
if (!f._fbq) f._fbq = n;
n.push = n;
n.loaded = !0;
n.version = '2.0';
n.queue = [];
t = b.createElement(e);
t.async = !0;
t.src = v;
s = b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t, s)
}(window, document, 'script',
    'https://connect.facebook.net/en_US/fbevents.js');
//End Facebook pixel code from facebook

getPixel() // get pixel from server

function getPixel()
{
    if(sessionStorage.getItem('fb_app_pixel'))
    {
        FB_PIXEL_DATA = JSON.parse(sessionStorage.getItem('fb_app_pixel'))
        eventIds()
        checkPixel()
    }
    else
    {
        let ajaxParam = {
            route:"init",
        }
        
        ajax(ajaxParam , function(data){
            // console.log(data)
            sessionStorage.setItem('fb_app_pixel',data)
            FB_PIXEL_DATA = JSON.parse(data)
            eventIds()
            checkPixel()
        });
    }
}

// Generate event ids
function eventIds()
{
    pageViewEventId = 'Pixels_Fox_'+'PageView.'+FB_PIXEL_DATA[0].shop_id+"."+n+"."+theRandomNumber;
    searchEventId = 'Pixels_Fox_'+'+Search.'+FB_PIXEL_DATA[0].shop_id+"."+n+"."+theRandomNumber;
    viewContentEventId = 'Pixels_Fox_'+'ViewContent.'+FB_PIXEL_DATA[0].shop_id+"."+n+"."+theRandomNumber;
    addToCartEventId = 'Pixels_Fox_'+'AddToCart.'+FB_PIXEL_DATA[0].shop_id+"."+n+"."+theRandomNumber;
    InitiateCheckoutEventId = 'Pixels_Fox_'+'InitiateCheckout.'+FB_PIXEL_DATA[0].shop_id+"."+n+"."+theRandomNumber;
    cartEventId = 'Pixels_Fox_'+'ViewCart.'+FB_PIXEL_DATA[0].shop_id+"."+n+"."+theRandomNumber;
    viewCategoryEventId = 'Pixels_Fox_'+'ViewCategory.'+FB_PIXEL_DATA[0].shop_id+"."+n+"."+theRandomNumber;
}

// save pixel in sessionStorage
function savePixel()
{
    if(sessionStorage.getItem('pixels'))
    {
        let newPixel = sessionStorage.getItem('pixels').split(',')
        newPixel = CheckoutPixels.concat(newPixel)
        sessionStorage.setItem('pixels',newPixel)
    }
    else
    {
        sessionStorage.setItem('pixels',CheckoutPixels)
    }
}

// filter pixel according to tags and collecion
function checkPixel()
{
    let tPixel = [];
    let cPixel = [];
    let mPixel = [];
    
    for(let pixel of FB_PIXEL_DATA)
    {
        if(pixel['type'] == "niche")
        {
            tPixel.push(pixel)
        }
        if(pixel['type'] == "collection")
        {
            cPixel.push(pixel)
        }
        if(pixel['type'] == "master")
        {
            mPixel.push(pixel)
            for(let pix of mPixel)
            {
                intiPixel.push(pix['pixel_id'])   
            }
        }
    }
    
    // console.log(tPixel)
    // console.log(cPixel)
    // console.log(mPixel)
    
    if(typeof pTags !== "undefined" && typeof pCollection !== "undefined")
    {
        let tCheck = false;
        let cCheck = false;
        
        if(pTags.length > 0)
        {
          for(let tag of tPixel)
          {
              for(let t of pTags)
              {
                 if(tag['tag'] == t)
                 {
                    intiPixel.push(tag['pixel_id'])
                    tCheck = true
                 }   
              }
          }
        }
        if(pCollection.length > 0)
        {
          for(let collection of cPixel)
          {
             for(let c of pCollection)
             {   
                 if(collection['collection'].replaceAll('\\uff06','＆').replaceAll('\\uff0c','，').includes(c))
                 {
                    intiPixel.push(collection['pixel_id'])
                    cCheck = true
                 }   
             }
          } 
        }
        intiPixel = intiPixel.filter(onlyUnique)
        CheckoutPixels = intiPixel
        initi(intiPixel)
    }
    else
    {
       if(ShopifyAnalytics.meta.page.path == "/checkout/thank_you")
       {
           if(sessionStorage.getItem('pixels'))
           {
               pixels = sessionStorage.getItem('pixels').split(',')
               pixels = pixels.filter(onlyUnique)
               sessionStorage.removeItem('pixels')
               initi(pixels)
           }
       }
       else
       {
            intiPixel = intiPixel.filter(onlyUnique)
            CheckoutPixels = intiPixel
            // console.log(intiPixel)
            initi(intiPixel)   
       }
        
    }
}

function onlyUnique(value, index, self) {
  return self.indexOf(value) === index;
}

// first function to call after getting pixel from server
function initi(pixels)
{
    if(typeof fbq !== 'undefined') {
       for(let pixel of pixels)
       {
          // if thank_you page inti pixel
          if(ShopifyAnalytics.meta.page.path == "/checkout/thank_you")
          {
            var fn = Shopify.checkout.shipping_address.first_name;
            var ln = Shopify.checkout.shipping_address.last_name;
            var ph = Shopify.checkout.shipping_address.phone;
            var em = Shopify.checkout.email;
            var ct = Shopify.checkout.shipping_address.city;
            var zp = Shopify.checkout.shipping_address.zip;
            var country = Shopify.checkout.shipping_address.country;
            var state = Shopify.checkout.shipping_address.province;
              let adata = {
                fn:fn,
                ln:ln,
                ph:ph,
                em:em,
                ct:ct,
                zp:zp,
                country:country,
                st:state
              }
              fbq('init', pixel, adata);
          }
          else
          {
              // init fb pixel for other pages
              fbq('init', pixel);
          }
       }
       
       //page view event
       
               fbq('track', 'PageView' ,{},{
           eventID: pageViewEventId
       });  
              
         
         
    }    
   
   // call view content only if page is defined and we are on product page
   if(typeof page !== "undefined")
   {
        if(page == "product")
        {
            ViewContent()   
        }    
    }
    
    // if we are on search page call search event
    if(ShopifyAnalytics.meta.page.pageType == "searchresults")
    {
        let q = new URL(location.href).searchParams.get('q')
        searchString = q;
        Search(q)
    }
    
    pixelBeforeRun()// call conversion api for viewcontent , searchevent, pageview
    
    // call view category if page is collection single
    if(ShopifyAnalytics.meta.page.pageType == "collection")
    {
        let q = window.location.pathname.split("/")
        if(q[1] == "collections" && q[2] !== "all")
        {
            catData = {content_type:"product_group", content_category:q[2] , content_ids:[meta.page.resourceId]}
            ViewCategory(catData)   
        }
    }
   
    
    // thank_you page data collection and calling purchase event both browser and server side
    if(ShopifyAnalytics.meta.page.path == "/checkout/thank_you")
    {
        var currency = Shopify.Checkout.currency
        var total = Shopify.checkout.total_price
        var orderId = Shopify.checkout.order_id
        var item_count = Shopify.checkout.line_items.length
        purchaseEventId = 'Pixels_Fox_'+'Purchase.'+FB_PIXEL_DATA[0].shop_id+"."+n+"."+theRandomNumber;
        // purchaseEventId = localStorage.getItem('purchaseEventId')
        var items = []
        var content = []
        
        for(let product of Shopify.checkout.line_items)
        {
            items.push(product.product_id)
            content.push({id:product.product_id , quantity:product.quantity , item_price:product.price})
        }
        
        var item_count = Shopify.checkout.line_items.length;
        var currency = Shopify.Checkout.currency;
        var order_id = Shopify.checkout.order_id;
        var fn = Shopify.checkout.shipping_address.first_name;
        var ln = Shopify.checkout.shipping_address.last_name;
        var ph = Shopify.checkout.shipping_address.phone;
        var em = Shopify.checkout.email;
        var ct = Shopify.checkout.shipping_address.city;
        var zp = Shopify.checkout.shipping_address.zip;
        var country = Shopify.checkout.shipping_address.country;
        var state = Shopify.checkout.shipping_address.province;
        var ids = []
        
        cData = {
            content_type:"product_group",
            num_items:item_count,
            value:total,
            currency:currency,
            content_ids:items,
            order_id:Shopify.checkout.order_id,
            contents:content
        }
        // var content = ""
        // console.log('thankyou call')
        
        var st = new XMLHttpRequest();
        var url = "http://app.pixelsfox.com/purchase/" + Shopify.shop + "?user_ip=" + localStorage.getItem('alphaPixeluserIp') + "&user_agent=" + navigator.userAgent + "&source_url=" + location.origin + location.pathname + encodeURIComponent(location.search) + "&currency=" + Shopify.currency.active + '&fbp=' + getCookie('_fbp') + '&fbc=' + getCookie('_fbc') + "&content_ids=" + items.join(',') + "&order_id=" + order_id + "&contents=" + JSON.stringify(content) + "&value=" + total + "&num_items=" + item_count + "&ln=" + ln + "&ph=" + ph + "&em=" + em + "&ct=" + ct + "&zp=" + zp + "&pixelIds=" + pixels.join(',') + "&fn=" + fn + "&c_user=" + localStorage.getItem('c_user') + "&country=" + country + "&st=" + state + '&purchaseEventId='+purchaseEventId;
        // console.log(url)
        st.open("GET", url, true);
        st.onreadystatechange = function() {
            if (st.readyState === 4 && st.status === 200) {
                var _result = JSON.parse(this.responseText);
                if (_result.status) {
                   
                }
            }
        };
        st.send();
        
        ThankYou(cData,purchaseEventId);
    }
    
    // check if page is defined or not
    if (typeof page === 'undefined') {
      // color is undefined
    }
    else
    {
         if(page  == "cart")
        {
            // view cart event if page is cart
            Cart()
        }   
    }
}

// View content event call browser
function ViewContent()
{
    // alert("View Content")
    // console.log(viewContentId)
    if(typeof fbq !== 'undefined') {
        fbq('track', 'ViewContent' , pData , {
            eventID:viewContentEventId
        });   
    }
}

// Search event call browser 
function Search(q)
{
    // alert("Search Called")
    // console.log(searchId)
    if(typeof fbq !== 'undefined') {
        fbq('track', 'Search' , {search_string:q} , {
            eventID: searchEventId
        });    
    }
    
}

// view cateogry call browser
function ViewCategory(data)
{
    getCat(data)
    if(typeof fbq !== 'undefined') {
        fbq('trackCustom', 'ViewCategory' , data , {
            eventID:viewCategoryEventId
        });    
    }
}

// add to cart call browser
function AddToCart(data)
{
    
    eventIds()
    console.log(data)
    if(page !== "cart")
    {
        if(!addtocart)
        {
            addtocart = true
            getATCIC(data,'addToCart');
            savePixel()
            if(typeof fbq !== 'undefined') {
                fbq('track', 'AddToCart' , data , {
                    eventID:addToCartEventId
                });    
            }
            
        }
    }
}

// checkout call browser
function Checkout()
{
    initiateCheckoutFlag = true;
    eventIds()
    if(cData.value !== 0)
    {
        getATCIC(cData,'InitiateCheckout');
        if(page == "cart")
        {
            sessionStorage.setItem('pixels',CheckoutPixels)
        }
        if(typeof fbq !== 'undefined') {
            fbq('track', 'InitiateCheckout' , cData ,{
                    eventID:InitiateCheckoutEventId
            });    
        }
        
    }
}

// view cart event call browser
function Cart()
{
    eventIds()
    if(cData.value !== 0)
    {
        getATCIC(cData,'ViewCart');
        if(page == "cart")
        {
            sessionStorage.setItem('pixels',CheckoutPixels)
        }
        if(typeof fbq !== 'undefined') {
            fbq('trackCustom', 'ViewCart' , cData ,{
                    eventID:cartEventId
            });    
        }
    }
}

// ThankYou page purchase event call browser
function ThankYou(data,id)
{
    if(typeof fbq !== 'undefined') {
        fbq('track', 'Purchase' , data , {
            eventID:id
        });   
    }
}

// check if string is valid JSON
function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

// Global Api calling function
function ajax(params, callback) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            callback(this.responseText);
        }
    };
    var link = `${FB_APP_URL}` + formatParams(params)
    xhttp.open("GET", link, true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.setRequestHeader("Accept", "application/json");
    xhttp.send();
}

// Script call counter function
function ajaxtest(params) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // callback(this.responseText);
        }
    };
    var link = `${FB_APP_URL}` + params
    xhttp.open("GET", link, true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.setRequestHeader("Accept", "application/json");
    xhttp.send();
}

// Script call counter function
function scripttest(params) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // callback(this.responseText);
        }
    };
    var link = `${FB_APP_URL}` + formatParams(params)
    // console.log(link)
    xhttp.open("GET", link, true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.setRequestHeader("Accept", "application/json");
    xhttp.send();
}

// Request parameter formatter
function formatParams(params) {
    return "?" + Object
        .keys(params)
        .map(function(key) {
            return key + "=" + encodeURIComponent(params[key])
        })
        .join("&")
}

// Global XHR Request Handler
function newXHR() {
    var realXHR = new oldXHR();
    realXHR.addEventListener("readystatechange", function() {
        if (realXHR.readyState == 1) {
            
        }
        if (realXHR.readyState == 2) {
            if(realXHR._url == "/cart/add.js")
            {   
                //check if event is already called or not
                if(!addtocart)
                {
                    // check if product data is avaialbe 
                    if(pData !== false)
                    {
                        AddToCart(pData)
                    }
                }
            }
            if(realXHR._url == "/cart")
            {
                if(!initiateCheckoutFlag){
                    Checkout()
                }
            }
        }
        if (realXHR.readyState == 3) {
            // console.log('processing request');
        }
        if (realXHR.readyState == 4) {
            if(realXHR._url == "/cart/add.js")
            {
                let resData = JSON.parse(realXHR.response)
                var  customProductId = {
                    content_type: "product_group",
                    value:resData.original_price,
                    currency: shopCurrency,
                    content_ids:[resData.product_id]
                }
                // if there is nothing in the cart
                if(cData.num_items == "0")
                {
                    cData.value = resData.original_price / 100
                    cData.content_ids = [resData.product_id]
                    cData.num_items = 1
                }
                // check if addtocart already called or not
                if(!addtocart)
                {
                    // check if product data is avaialbe
                    if(!pData)
                    {
                        AddToCart(customProductId)
                    }  
                }
            }
        }
    }, false);
    return realXHR;
}

// Global Form Submit Event
Object.keys(window).forEach(key => {
    if (/^on(submit)/.test(key)) {
        window.addEventListener(key.slice(2), event => {
            // console.log(event);
            // console.log(event.submitter.getAttribute('name'))
            if(event.submitter.getAttribute('name') == "checkout")
            {
                // console.log('checkout 1')
                if(page == "product")
                {
                    var cartContents = fetch('/cart.js')
                    .then(response => response.json())
                    .then(data => { 
                        var items = []
                        for(let item of data.items)
                        {
                            items.push(item.id)
                        }
                        cData = {
                            content_type:"product_group",
                            num_items:data.item_count,
                            value:data.total_price/100,
                            currency:shopCurrency,
                            content_ids:items,
                        }
                        // console.log(cData)
                        if(!initiateCheckoutFlag){
                            Checkout()
                        }
                    });
                }
                else
                {
                    if(!initiateCheckoutFlag){
                        Checkout()    
                    }
                }
            }
            // console.log(event.target.getAttribute('action'));
            if(event.target.getAttribute('action') == "/cart/add")
            {
                if(!addtocart)
                {
                    if(pData)
                    {
                        // console.log('flag 4')
                        AddToCart(pData)        
                    }  
                }
            }
            var kvpairs = [];
            var form = event.target
            for ( var i = 0; i < form.elements.length; i++ ) {
               var e = form.elements[i];
               kvpairs.push(encodeURIComponent(e.name) + "=" + encodeURIComponent(e.value));
            }
            // console.log(kvpairs[2])
            // console.log(/[0-9]/.test(kvpairs[2]))
            if(kvpairs[2])
            {
                if(isNumeric(kvpairs[2]))
                {
                    var customProductId = {
                       content_type: "product_group",
                       value:"1.00",
                       currency: shopCurrency,
                       content_ids:[kvpairs[2].replace("id=","")]
                    }
                    // console.log(customProductId)
                    if(!addtocart)
                    {
                        if(pData == false)
                        {
                            // console.log('flag 5')
                            AddToCart(customProductId)
                            // console.log('Custom Event Submit Form')
                        }  
                    }
                }
            }
            var queryStrings = kvpairs.join("&");
            // console.log(queryStrings)
            // console.log(parseQuery(queryStrings))
        });
    }
});

// Global click event
document.addEventListener('click', function(event){
  if(event.target.getAttribute('href') == "/checkout")
  {
      if(!initiateCheckoutFlag){
          Checkout()
      }
  }
  if(event.target.getAttribute('onclick') == "window.location='/checkout'")
  {
      if(!initiateCheckoutFlag){
        Checkout()
      }
  }
  if(event.target.getAttribute('name') == "checkout")
  {
      if(!initiateCheckoutFlag){
        Checkout()
      }
  }
},true);

// Global Fetch Handler
var oldFetch = fetch;  // must be on the global scope
fetch = function(url, options) {
    var promise = oldFetch(url,options);
    try {
        if(url == "/cart/add.js")
        {
            let data = options.body
            let id = ""
            let qty = 0
            
            if(IsJsonString(options.body))
            {
                
                let parseData = JSON.parse(options.body)
                var loopData = Object.values(parseData)
                id = loopData[0][0].id
                qty = loopData[0][0].quantity
            }
            else
            {
                let parseData = parseQuery(options.body)
                id = parseData.id
                qty = parseData.quantity
                
            }
            
            var  customProductId = {
               content_type: "product_group",
               value:"1.00",
               currency: shopCurrency,
               content_ids:[id]
            }
                    
            if(page == "product")
            {
                
                AddToCart(pData)
            }
            else
            {
                
                AddToCart(customProductId)    
            }
            
        }
        if(url == "/wallets/checkouts.json")
        {
            // console.log(url)
            // console.log(options)
            let data = JSON.parse(options.body)
            if(page == "product")
            {
                // console.log('flag 1')
                AddToCart(pData)
                cData = pData
            }
            else
            {
                var  customProductId = {
                       content_type: "product_group",
                       value:"1.00",
                       currency: shopCurrency,
                       content_ids:[data.checkout.line_items[0].variant_id]
                    }
                    // console.log('flag 2')
                AddToCart(customProductId)
            }
            if(!initiateCheckoutFlag){
                Checkout()
            }
        }
    } catch (e) {
        console.log(e)
    }
    return promise;
}

// Global XHR Request Handler
var oldXHR = window.XMLHttpRequest;
window.XMLHttpRequest = newXHR;

// Get user ip and user agent
var xhr = new XMLHttpRequest();
if (!localStorage.getItem('fbPixeluserIp')) {
        var url = "https://www.cloudflare.com/cdn-cgi/trace";
        xhr.open("GET", url, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                data = this.responseText.trim().split('\n').reduce(function(obj, pair) {
                    pair = pair.split('=');
                    return obj[pair[0]] = pair[1], obj;
                }, {});
                localStorage.setItem("fbPixeluserIp", data.ip);
                pixelBeforeRun();
            }
        };
        xhr.send();
} 

// check if string contain valid json fucntion
function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

// check of string contain vlaid numeric value
function isNumeric(str) {
  if (typeof str != "string") return false // we only process strings!  
  return !isNaN(str) && // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
         !isNaN(parseFloat(str)) // ...and ensure strings of whitespace fail
}

// parse url query string into a object 
function parseQuery(queryString) {
    var query = {};
    if(queryString instanceof FormData){
        temp = [...queryString.entries()];
        let query = {};
        for(singleValue of temp){
            query[singleValue[0]] = singleValue[1]
        }
        return query;
    }else{
        var pairs = (queryString[0] === '?' ? queryString.substr(1) : queryString).split('&');
        for (var i = 0; i < pairs.length; i++) {
            var pair = pairs[i].split('=');
            query[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || '');
        }
        return query;
    }
    
}

// server side event call function for viewcontent , pageview, searchevent
function pixelBeforeRun() {
    if (getCookie('c_user')) {
        localStorage.setItem('c_user', getCookie('c_user'));
    }
    var url = "?user_ip=" + localStorage.getItem('fbPixeluserIp') + "&user_agent=" + navigator.userAgent + "&source_url=" + location.origin + location.pathname + encodeURIComponent(location.search) + "&currency=" + Shopify.currency.active + '&fbp=' + getCookie('_fbp') + '&fbc=' + getCookie('_fbc') + '&c_user=' + getCookie('c_user') + "&pixelIds=" + alphaPixels.join(',')+'&pageViewEventId='+pageViewEventId+'&searchEventId='+searchEventId+'&viewContentEventId='+viewContentEventId+'&route=status';
    
    if (typeof(searchedIds) != 'undefined') {
        url += "&content_ids=" + searchedIds.join(',');
    } else if (typeof(productId) != 'undefined') {
        url += "&content_ids=" + productId;
    }
    if (typeof(productId) != 'undefined') {
        url += "&productId=" + productId;
    }
    if (typeof(value) != 'undefined') {
        url += "&value=" + value;
    } else {
        url += "&value=1.0";
    }
    if (typeof(searchString) != 'undefined') {
        url += "&searchString=" + searchString;
    }
    if (typeof(pCollection) != 'undefined') {
        url += "&productCollections=" + pCollection.join(',');
    }
    if (typeof(pTags) != 'undefined') {
        url += "&productTags=" + pTags.join(',');
    }
    if (typeof(productTitle) != 'undefined') {
        url += "&productTitle=" + productTitle;
    }
    if (getCookie('_fbp')) {
        url += "&fbp=" + getCookie('_fbp');
    }
    if (getCookie('_fbc')) {
        url += "&fbc=" + getCookie('_fbc');
    }
    
    ajaxtest(url,function(data){
        // console.log(data)
        var response = JSON.parse(data);
        localStorage.setItem("callone", data);
    })
}

// server side event call for initiate checkout and addToCart
function getATCIC(data, type = null) {
    var url = "?user_ip=" + localStorage.getItem('fbPixeluserIp') + "&user_agent=" + navigator.userAgent + "&source_url=" + location.origin + location.pathname + encodeURIComponent(location.search) + "&currency=" + Shopify.currency.active + "&content_ids=" + data.content_ids + "&value=" + data.value + "&content_name=" + data.content_name + "&contents=" + data.contents + "&num_items=" + (data.num_items != undefined ? data.num_items : 1) + "&pixelIds=" + intiPixel.join(',')+'&addToCartEventId='+addToCartEventId+'&InitiateCheckoutEventId='+InitiateCheckoutEventId+'&route=addToCart&requestType='+type+'&cartEventId='+cartEventId;
    
    if (getCookie('_fbp')) {
        url += "&fbp=" + getCookie('_fbp');
    }
    if (getCookie('_fbc')) {
        url += "&fbc=" + getCookie('_fbc');
    }
    if(type == "InitiateCheckout")
    {
        ajaxtest(url,function(data){
            var response = JSON.parse(data);
            if (response.status) {
                aTCIC = response.aTCIC;
            }
        }) 
    }    
    else
    {
        ajaxtest(url,function(data){
            var response = JSON.parse(data);
            if (response.status) {
                aTCIC = response.aTCIC;
            }
        }) 
    }
}

//server side event for view category
function getCat(data) {
    var url = "?user_ip=" + localStorage.getItem('fbPixeluserIp') + "&user_agent=" + navigator.userAgent + "&source_url=" + location.origin + location.pathname + encodeURIComponent(location.search) + "&currency=" + Shopify.currency.active + "&category="+data.content_category+'&categoryId='+data.content_ids.join(',')+"&pixelIds=" + intiPixel.join(',')+'&viewCategoryEventId='+viewCategoryEventId+'&route=viewCategory';
    
    if (getCookie('_fbp')) {
        url += "&fbp=" + getCookie('_fbp');
    }
    if (getCookie('_fbc')) {
        url += "&fbc=" + getCookie('_fbc');
    }
    ajaxtest(url,function(data){
        var response = JSON.parse(data);
        if (response.status) {
            aTCIC = response.aTCIC;
        }
    })
}

// get cookie by name
function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
    }