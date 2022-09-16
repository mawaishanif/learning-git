<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
    <channel>
        <title>{{ $title }}</title>
        <description></description>
        <link>{{ $permalink }}</link>
        @foreach ($items as $item)
                <item>
                    <g:id>{{ $item->get_item_tags('http://base.google.com/ns/1.0', 'id')[0]['data'] }}</g:id>
                    <g:title>{{ $item->get_title() }}</g:title>
                    <g:link>{{ $item->get_link() }}</g:link>
                    <g:image_link>https:{{ $item->get_item_tags('http://base.google.com/ns/1.0', 'image_link')[0]['data'] }}</g:image_link>
                    <g:additional_image_link></g:additional_image_link>
                    <g:description>{{ $item->get_description() }}</g:description>
                    <g:brand>{{ $item->get_item_tags('http://base.google.com/ns/1.0', 'brand')[0]['data'] }}</g:brand>
                    <g:type></g:type>
                    <g:override>en_XX</g:override>
                    <g:condition>{{ $item->get_item_tags('http://base.google.com/ns/1.0', 'condition')[0]['data'] }}</g:condition>
                    <g:availability>{{ $item->get_item_tags('http://base.google.com/ns/1.0', 'availibility')[0]['data'] }}</g:availability>
                    <g:price>{{ $item->get_item_tags('http://base.google.com/ns/1.0', 'price')[0]['data'] }}</g:price>
                    <g:product_type>{{ $item->get_item_tags('http://base.google.com/ns/1.0', 'product_type')[0]['data'] }}</g:product_type>
                    <g:google_product_category>{{ $category }}</g:google_product_category>
                </item>
            @endforeach
    </channel>
</rss>