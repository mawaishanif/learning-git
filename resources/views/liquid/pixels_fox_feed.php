{%- layout none -%}
{%- assign getVariant = false -%}
{%- capture contentForQuerystring -%}{{ content_for_header }}{%- endcapture -%}
{%- assign pageUrl = contentForQuerystring | split:'"pageurl":"' | last | split:'"' | first | split:'.myshopify.com' | last |
   replace:'\/','/' | 
   replace:'%20',' ' | 
   replace:'\u0026','&'
-%}
{%- for i in (1..1) -%}
{%- assign pageQuerystring = pageUrl | split:'?' | last -%}
{%- assign parts = pageQuerystring | split:'&' -%}
{%- assign CollectionValue = pageQuerystring | split:'=' -%}
{%- for part in parts -%}
{%- assign keyAndValue = part | split:'=' -%}
{%- if keyAndValue[0] == 'collection' -%}
{%- assign CollectionValue = keyAndValue[1] -%}
{% endif %}
{%- if keyAndValue[0] == 'variant' -%}
{%- assign getVariant = keyAndValue[1] -%}
{% endif %}
{%- if keyAndValue[0] == 'category' -%}
{%- assign getCategory = keyAndValue[1] -%}
{% endif %}
{%- if keyAndValue[0] == 'type' -%}
{%- assign getType = keyAndValue[1] -%}
{% endif %}
{%- endfor -%}
{%- endfor -%}
<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
    <channel>
        <title>{{shop.name | escape }} Products {{ getVariant }} {{ CollectionValue }}</title>
        <description>Facebook Product Feed</description>
        <link>{{shop.url}}</link>
        {% paginate collections[CollectionValue].products by 1000 %}
        {% for product in collections[CollectionValue].products %}
        {% if getType %}
        {% if product.type == getType %}
        <item>
            <title>
                <![CDATA[{{ product.title | strip_html | strip_newlines | escape | replace: 'amp;', 'and' | replace: '&#38;', 'and' | replace: "Ã©", "e" | replace: "Ã ", "a" }}]]>
            </title>
            <g:brand>{{product.vendor | escape }}</g:brand>
            <g:product_type>{{product.type | escape }}</g:product_type>
            <g:id>{{product.id}}</g:id>
            <g:condition>New</g:condition>
            <description>
                <![CDATA[{{ product.description | strip_html | strip_newlines | replace: 'amp;', 'and' | replace: '&#38;', 'and' | replace: "..", ". " | replace: "  ", " " | replace: "â€˜", "&#39;" | replace: "â€™", "&#39;" | replace: "&#8216;", "&#39;" | replace: "&#8217;", "&#39;" | replace: "&#8217;", "&#39;" | replace: "Ã¢â‚¬â„¢", "&#39;" | replace: "Ã¢â‚¬Å“", "&#39;" | replace: "Ã¢â‚¬Ëœ", "&#39;"  | replace: "Ã‚Â´", "&#39;" | replace: "â€œ", "&#34;" | replace: "â€", "&#34;" | replace: "&#8211;", "-" | replace: "Ã¢â‚¬â€œ", "-" | replace: "Ã¢â‚¬â€", "-" | replace: "â€“", "&mdash;" | replace: "â€”", "&mdash;" | replace: "%", "&#37;" | replace: "Ã‚Â©", "&copy;" | replace: "Ã‚Â®", "&reg;" | replace: "Ã¢â€žÂ¢", "&trade;" | replace: "Ã‚Â£", "&pound;" | replace: "Ã¯Â¿Â", "&#42;" | replace: "Ã¢â‚¬Â¢", "&#42;" | replace: "Ã¢â‚¬Â", "&#39;" | replace: "&#233;", "e" | replace: "Ã©", "e" | replace: "Ã ", "a" | replace: "Ã³", "o" | replace: "Ãª", "e" | replace: "ÃƒËœ", "O" | replace: "&#8482;", "" | replace: "&#174;", "" }}]]>
            </description>
            <g:image_link>{{product.featured_image | product_img_url: 'large'}}</g:image_link>
            <link>{{shop.url}}{{product.url}}</link>
            <g:price>{{product.price | money_without_currency}} {{ shop.currency }}</g:price>
            {% if product.available %}
            <g:availibility>In Stock</g:availibility>
            {% else %}
            <g:availibility>Out Of Stock</g:availibility>
            {% endif %}
            <g:quantity>1</g:quantity>
            <g:google_product_category>{{ getCategory }}</g:google_product_category>
            <source>{{ shop.locale.iso_code }}</source>
        </item>
        {% if getVariant == 'true' %}
        {% for variant in product.variants %}
        {% if variant.available %}

        {% else %}

        {% endif %}
        <item>
            <title>
                <![CDATA[{{ product.title | strip_html | strip_newlines | escape | replace: 'amp;', 'and' | replace: '&#38;', 'and' | replace: "Ã©", "e" | replace: "Ã ", "a" }}{{ variant.title | strip_html | strip_newlines | escape | replace: 'amp;', 'and' | replace: '&#38;', 'and' | replace: "Ã©", "e" | replace: "Ã ", "a" }}]]>
            </title>
            <g:brand>{{product.vendor | escape }}</g:brand>
            <g:product_type>{{product.type | escape }}</g:product_type>
            <g:id>{{variant.id}}</g:id>
            <g:condition>New</g:condition>
            <description>
                <![CDATA[{{ product.description | strip_html | strip_newlines | replace: 'amp;', 'and' | replace: '&#38;', 'and' | replace: "..", ". " | replace: "  ", " " | replace: "â€˜", "&#39;" | replace: "â€™", "&#39;" | replace: "&#8216;", "&#39;" | replace: "&#8217;", "&#39;" | replace: "&#8217;", "&#39;" | replace: "Ã¢â‚¬â„¢", "&#39;" | replace: "Ã¢â‚¬Å“", "&#39;" | replace: "Ã¢â‚¬Ëœ", "&#39;"  | replace: "Ã‚Â´", "&#39;" | replace: "â€œ", "&#34;" | replace: "â€", "&#34;" | replace: "&#8211;", "-" | replace: "Ã¢â‚¬â€œ", "-" | replace: "Ã¢â‚¬â€", "-" | replace: "â€“", "&mdash;" | replace: "â€”", "&mdash;" | replace: "%", "&#37;" | replace: "Ã‚Â©", "&copy;" | replace: "Ã‚Â®", "&reg;" | replace: "Ã¢â€žÂ¢", "&trade;" | replace: "Ã‚Â£", "&pound;" | replace: "Ã¯Â¿Â", "&#42;" | replace: "Ã¢â‚¬Â¢", "&#42;" | replace: "Ã¢â‚¬Â", "&#39;" | replace: "&#233;", "e" | replace: "Ã©", "e" | replace: "Ã ", "a" | replace: "Ã³", "o" | replace: "Ãª", "e" | replace: "ÃƒËœ", "O" | replace: "&#8482;", "" | replace: "&#174;", "" }}]]>
            </description>
            {% if variant.image %}
            <g:image_link>{{ variant.image.src | product_img_url: 'large'}}</g:image_link>
            {% else %}
            <g:image_link>{{product.featured_image | product_img_url: 'large'}}</g:image_link>
            {% endif %}
            <link>{{shop.url}}{{product.url}}</link>
            <g:price>{{variant.price | money_without_currency}} {{ shop.currency }}</g:price>
            {% if product.available %}
            <g:availibility>In Stock</g:availibility>
            {% else %}
            <g:availibility>Out Of Stock</g:availibility>
            {% endif %}
            <g:quantity>1</g:quantity>
            <g:google_product_category>{{ getCategory }}</g:google_product_category>
            <source>{{ shop.locale.iso_code }}</source>
        </item>
        {% endfor %}
        {% endif %}
        {% endif %}
        {% else %}

        <item>
            <title>
                <![CDATA[{{ product.title | strip_html | strip_newlines | escape | replace: 'amp;', 'and' | replace: '&#38;', 'and' | replace: "Ã©", "e" | replace: "Ã ", "a" }}]]>
            </title>
            <g:brand>{{product.vendor | escape }}</g:brand>
            <g:product_type>{{product.type | escape }}</g:product_type>
            <g:id>{{product.id}}</g:id>
            <g:condition>New</g:condition>
            <description>
                <![CDATA[{{ product.description | strip_html | strip_newlines | replace: 'amp;', 'and' | replace: '&#38;', 'and' | replace: "..", ". " | replace: "  ", " " | replace: "â€˜", "&#39;" | replace: "â€™", "&#39;" | replace: "&#8216;", "&#39;" | replace: "&#8217;", "&#39;" | replace: "&#8217;", "&#39;" | replace: "Ã¢â‚¬â„¢", "&#39;" | replace: "Ã¢â‚¬Å“", "&#39;" | replace: "Ã¢â‚¬Ëœ", "&#39;"  | replace: "Ã‚Â´", "&#39;" | replace: "â€œ", "&#34;" | replace: "â€", "&#34;" | replace: "&#8211;", "-" | replace: "Ã¢â‚¬â€œ", "-" | replace: "Ã¢â‚¬â€", "-" | replace: "â€“", "&mdash;" | replace: "â€”", "&mdash;" | replace: "%", "&#37;" | replace: "Ã‚Â©", "&copy;" | replace: "Ã‚Â®", "&reg;" | replace: "Ã¢â€žÂ¢", "&trade;" | replace: "Ã‚Â£", "&pound;" | replace: "Ã¯Â¿Â", "&#42;" | replace: "Ã¢â‚¬Â¢", "&#42;" | replace: "Ã¢â‚¬Â", "&#39;" | replace: "&#233;", "e" | replace: "Ã©", "e" | replace: "Ã ", "a" | replace: "Ã³", "o" | replace: "Ãª", "e" | replace: "ÃƒËœ", "O" | replace: "&#8482;", "" | replace: "&#174;", "" }}]]>
            </description>
            <g:image_link>{{product.featured_image | product_img_url: 'large'}}</g:image_link>
            <link>{{shop.url}}{{product.url}}</link>
            <g:price>{{product.price | money_without_currency}} {{ shop.currency }}</g:price>
            {% if product.available %}
            <g:availibility>In Stock</g:availibility>
            {% else %}
            <g:availibility>Out Of Stock</g:availibility>
            {% endif %}
            <g:quantity>1</g:quantity>
            <g:google_product_category>{{ getCategory }}</g:google_product_category>
            <source>{{ shop.locale.iso_code }}</source>
        </item>
        {% if getVariant == '1' %}
        {% for variant in product.variants %}
        {% if variant.available %}

        {% else %}

        {% endif %}
        <item>
            <title>
                <![CDATA[{{ product.title | strip_html | strip_newlines | escape | replace: 'amp;', 'and' | replace: '&#38;', 'and' | replace: "Ã©", "e" | replace: "Ã ", "a" }}{{ variant.title | strip_html | strip_newlines | escape | replace: 'amp;', 'and' | replace: '&#38;', 'and' | replace: "Ã©", "e" | replace: "Ã ", "a" }}]]>
            </title>
            <g:brand>{{product.vendor | escape }}</g:brand>
            <g:product_type>{{product.type | escape }}</g:product_type>
            <g:id>{{variant.id}}</g:id>
            <g:condition>New</g:condition>
            <description>
                <![CDATA[{{ product.description | strip_html | strip_newlines | replace: 'amp;', 'and' | replace: '&#38;', 'and' | replace: "..", ". " | replace: "  ", " " | replace: "â€˜", "&#39;" | replace: "â€™", "&#39;" | replace: "&#8216;", "&#39;" | replace: "&#8217;", "&#39;" | replace: "&#8217;", "&#39;" | replace: "Ã¢â‚¬â„¢", "&#39;" | replace: "Ã¢â‚¬Å“", "&#39;" | replace: "Ã¢â‚¬Ëœ", "&#39;"  | replace: "Ã‚Â´", "&#39;" | replace: "â€œ", "&#34;" | replace: "â€", "&#34;" | replace: "&#8211;", "-" | replace: "Ã¢â‚¬â€œ", "-" | replace: "Ã¢â‚¬â€", "-" | replace: "â€“", "&mdash;" | replace: "â€”", "&mdash;" | replace: "%", "&#37;" | replace: "Ã‚Â©", "&copy;" | replace: "Ã‚Â®", "&reg;" | replace: "Ã¢â€žÂ¢", "&trade;" | replace: "Ã‚Â£", "&pound;" | replace: "Ã¯Â¿Â", "&#42;" | replace: "Ã¢â‚¬Â¢", "&#42;" | replace: "Ã¢â‚¬Â", "&#39;" | replace: "&#233;", "e" | replace: "Ã©", "e" | replace: "Ã ", "a" | replace: "Ã³", "o" | replace: "Ãª", "e" | replace: "ÃƒËœ", "O" | replace: "&#8482;", "" | replace: "&#174;", "" }}]]>
            </description>
            {% if variant.image %}
            <g:image_link>{{ variant.image.src | product_img_url: 'large'}}</g:image_link>
            {% else %}
            <g:image_link>{{product.featured_image | product_img_url: 'large'}}</g:image_link>
            {% endif %}
            <link>{{shop.url}}{{product.url}}</link>
            <g:price>{{variant.price | money_without_currency}} {{ shop.currency }}</g:price>
            {% if product.available %}
            <g:availibility>In Stock</g:availibility>
            {% else %}
            <g:availibility>Out Of Stock</g:availibility>
            {% endif %}
            <g:quantity>1</g:quantity>
            <g:google_product_category>{{ getCategory }}</g:google_product_category>
            <source>{{ shop.locale.iso_code }}</source>
        </item>
        {% endfor %}
        {% endif %}
        {% endif %}
        {% endfor %}
        {% endpaginate %}
    </channel>
</rss>