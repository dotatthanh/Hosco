@forelse($productStocks as $key => $product)
   <tr>
      <td class="center">
         <label class="pos-rel">{{$key+ 1}}</label>
      </td>
      <td>{{ $product->StoreName }}</td>
      <td>{{ $product->CurInstock }}</td>
{{--      <td>{{ $product->QtyPurchaseOrder }}</td>--}}
    </tr>
@empty
   <tr>
      <td colspan="16" class="text-capitalize">There are no data</td>
   </tr>
@endforelse
