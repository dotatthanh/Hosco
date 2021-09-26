@forelse($data as $key => $row)

   <tr>
      <td class="center">
         <label class="pos-rel">{{$key+ 1}}</label>
      </td>
      <td>{{ $row->OrderCode }}</td>
      <td>{{ $row->ObjectName }}</td>
      <td>{{ $row->OrderDate }}</td>
      <td>{{ $row->Qty }}</td>
      <td>{{ $row->Price }}</td>
      <td>{{ $row->Unit }}</td>
      <td>{{ $row->Discount }}</td>
      <td>{{ $row->Exchange }}</td>
   </tr>
@empty
   <tr>
      <td colspan="10" class="text-capitalize">There are no data</td>
   </tr>
@endforelse
