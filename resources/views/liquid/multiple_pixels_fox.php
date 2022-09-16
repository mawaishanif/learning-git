<script>
var page = ""
var addtocart = false;
var initiateCheckoutFlag = false;
var pData = false;
var pTags = [];
var pCollection = [];
var shopCurrency = Shopify.currency.active
{% if template == 'index' %}
	var page = "index"
	
var htmlData = document.querySelectorAll('[type="application/json"]')
var homeProducts = []
var pTags = []
for(let html of htmlData)
{
    let data = JSON.parse(html.innerHTML)
    if(data.hasOwnProperty('id'))
    {
        homeProducts.push(data)
        for(let tag of data.tags)
        {
            pTags.push(tag)    
        }
    }    
}
{% endif %}
</script>

{% if template == 'product' %}
<script>
var productId = {{ product.id }};
var productTitle = `{{ product.title }}`;
var value = "{{ product.price | money_without_currency }}";
var niche = [{"id":"{{ product.id }}","quantity":"1"}];
var productType = "{{ product.type }}";
var name = `{{ product.title }}`;
  var pData = {
    content_type:"product_group",
    content_ids:[{{ product.id }}],
    value: "{{ product.price | money_without_currency }}",
    content_name: `{{ product.title }}`,
    currency:shopCurrency,
    content_category: "{{ product.type }}"
  }
  var lineitem = []
  {% for item in cart.items %}
  lineitem.push({{ item.product.id }})
  {% endfor %}	

  var cData = {
    content_type:"product_group",
    num_items:"{{ cart.item_count }}",
    value:"{{ cart.original_total_price | money_without_currency }}",
    currency:shopCurrency,
    content_ids:lineitem
  }
  var page = "product"
  
  var pTags = []
  {% for tag in product.tags %}
  pTags.push("{{ tag }}")
  {% endfor %}
  
  var pCollection = []
  {% for collection in product.collections %}
    pCollection.push("{{ collection.title }}")
  {% endfor %}  
             
  console.log(pData)
</script>
{% endif %}

{% if template == 'cart' %}
<script>
	var page = "cart"
	var pTags = []
    var pCollection = []
	{% for item in cart.items %}
		{% for tag in item.product.tags %}
			pTags.push("{{ tag }}")
		{% endfor %}
	{% endfor %}
                       
    {% for item in cart.items %}
		{% for collection in item.product.collections %}
			pCollection.push("{{ collection.title }}")
		{% endfor %}
	{% endfor %}

 pTags = pTags.filter(onlyUnique);
  pCollection = pCollection.filter(onlyUnique);
function onlyUnique(value, index, self) {
  return self.indexOf(value) === index;
}
</script>
{% endif %}

<script>
  var lineitem = []
  {% for item in cart.items %}
    lineitem.push({{ item.product.id }})
  {% endfor %}	
  
  var cData = {
    content_type:"product_group",
    num_items:"{{ cart.item_count }}",
    value:"{{ cart.original_total_price | money_without_currency }}",
    currency:shopCurrency,
    content_ids:lineitem
  }
</script>