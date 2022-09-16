<table class="table table-hover table-fixed a_heading2">
            <!--Table head-->
                        <thead>
                        <tr>
                            <th>Catalog Name </th>
                            <th>Collection</th>
                            <th>Variants</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <!--Table head-->
                    
                        <!--Table body-->
                        <tbody id="tableData1">  
                        @foreach($catalog as $data)
                        @php
                        dd($catalog);
                          $shop = Auth::user()->name;
                          $domain = url('/');
                          $collection = $data->collection_handle;
                          $variant = $data->variants;
                          $category = $data->category;
                          $type = $data->product_type;
                          $id = $data->id;
                          if($variant == 1)
                          {
                            $variant = "Yes";
                          }
                          else
                          {
                            $variant = "No";
                          }
                          if($type == "all")
                          {
                            $url = "{$domain}/{$shop}/feeds?collection={$collection}&variant={$variant}&category={$category}";  
                          }
                          else
                          {
                            $url = "{$domain}/{$shop}/feeds?collection={$collection}&variant={$variant}&category={$category}&type={$type}";
                          }
                          
                        @endphp
                        <tr>
                            <td scope="row">{{ $data->feedName }} <span class="generate" data-url="{{ $url }}">Click To Copy URL</span></td>
                            <td>{{ $collection }}</td>
                            <td>{{ $variant }}</td>
                            <td class="d-flex">
                                <a class="delc" data-id="{{ $data->id }}">
                                    <i class="fas fa-trash" data-id="{{ $id }}"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
        </table>