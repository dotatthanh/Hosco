@forelse($data as $key => $product)
   @php
      $style='';
      if ($product->InStock<=0){
      $style="background-color: #d6cbcb";
      }
   @endphp
   <tr style="{{$style}}">
      <td class="center">
         <label class="pos-rel">{{$key+ 1}}
            <!-- <input type="checkbox" class="ace" id="check"> -->
            <span class="lbl"></span>
         </label>
      </td>
      <td>{{ $product->ProductCode }}</td>
{{--      <td>{{ $product->ProductCode }}</td>--}}
      <td><a class="click_me bolder" data-path="/product/show" title="show product"
             href="{{ route('product.show',$product->Id) }}">{{ $product->Name }}</a>
      </td>
{{--      <td>{{ $product->Name }}</td>--}}
      <td>{{ $product->BranchName }}</td>
      <td>{{ $product->Unit }}</td>
      <td>
         @if(isRead(env('VIEW_UNIT_PRICE_CODE')))
            {{ number_format($product->UnitPrice) }}
         @else
            *************
         @endif
          {{--
         @foreach($product->categories as $category)
            <span class='label label-default smaller-90'>{{ $category->category_name }}</span>
         @endforeach
         --}}
      </td>
      <td>
         {{ number_format($product->Price) }}
          {{--
         @foreach($product->colors as $color)
            <span style=" background: {{ $color->s_Color }} "
                  class="label label-sm">{{ $color->s_Color }}</span>
         @endforeach
         --}}
      </td>
      <td>{{ number_format($product->InStock) }}</td>
      <td>{{ Str::limit($product->Description,50,'...')  }}</td>
      <td>
         {{--<img src="{{ asset($product->Picture) }}" alt="cover photo" width="80" height="80">--}}
      </td>
      {{--<td class="smaller-80">{{ $product->created_at }}</td>--}}
      <td>
         <div class="hidden-sm hidden-xs btn-group">
            @if(isRead(env('PRODUCT_ROLE_CODE')))
            <a class="btn btn-xs btn-info bolder"
               title="Chi tiết sản phẩm"
               href="{{ route('product.show',$product->Id) }}"><i
                       class="ace-icon fa fa-eye bigger-120"></i>
            </a>
            @endif
            @if(isUpdate(env('PRODUCT_ROLE_CODE')))
             <a class="btn btn-xs btn-warning bolder"
                title="Sửa sản phẩm"
                href="{{ route('product.edit',$product->Id) }}"><i
                     class="ace-icon fa fa-edit bigger-120"></i>
             </a>
             @endif
             @if(isDelete(env('PRODUCT_ROLE_CODE')))
             <button class="btn btn-xs btn-danger delete_me" data-id="{{ $product->Id }}">
                     <i class="ace-icon fa fa-trash bigger-120"></i>
                  </button>
             @endif
         </div>

         <div class="hidden-md hidden-lg">
            <div class="inline pos-rel">
               <button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto">
                  <i class="ace-icon fa fa-cog icon-only bigger-110"></i>
               </button>

               <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                  @if(isRead(env('PRODUCT_ROLE_CODE')))
                  <li>
                     <a class="btn btn-xs btn-info bolder"
                        title="show product"
                        href="{{ route('product.show',$product->Id) }}"><i
                                class="ace-icon fa fa-eye bigger-120"></i>
                     </a>
                  </li>
                  @endif
                  @if(isUpdate(env('PRODUCT_ROLE_CODE')))
                  <li>
                  <a class="btn btn-xs btn-warning bolder"
                     title="Sửa sản phẩm"
                     href="{{ route('product.edit',$product->Id) }}"><i
                           class="ace-icon fa fa-edit bigger-120"></i>
                  </a>
                  </li>
                  @endif
                  @if(isDelete(env('PRODUCT_ROLE_CODE')))
                  <li><a class="btn btn-xs btn-danger delete_me" data-id="{{ $product->Id }}">
                           <i class="ace-icon fa fa-trash bigger-120"></i>
                    </a></li>
                  @endif
               </ul>
            </div>
         </div>

      </td>
   </tr>
@empty
   <tr>
      <td colspan="16" class="text-capitalize">There are no data</td>
   </tr>
@endforelse
