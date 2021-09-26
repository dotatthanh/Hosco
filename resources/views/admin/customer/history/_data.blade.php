@forelse($data as $key => $row)
   <tr>
      <td class="center">
         <label class="pos-rel">{{$key+ 1}}
            <!-- <input type="checkbox" class="ace" id="check"> -->
            <span class="lbl"></span>
         </label>
      </td>
      <td>{{ $row->OrderCode }}</td>
      <td>{{ $row->OrderDate }}</td>
       @if($type == 'need-pay' || $type == 'debit')
           <td>{{ $row->Description }}</td>
       @else
           <td>{{ $row->OrderStatus }}</td>
           <td>{{ $row->EmployeeName }}</td>
       @endif
       <td>{{ $row->TotalMoney }}</td>
      <td>
         <div class="hidden-sm hidden-xs btn-group">
             <a class="btn btn-xs btn-info bolder"
                title="show customer"
                href="{{ route('customer.show',$row->Id) }}"><i
                     class="ace-icon fa fa-eye bigger-120"></i>
             </a>

              {{--@can('customer-edit')--}}
                 <a class="btn btn-warning btn-xs" title="Edit"
                    href="{{route('customer.edit',$row->Id)}}" data-id="{{ $row->Id }}">
                    <i class="ace-icon fa fa-pencil bigger-120"></i></a>
                   {{--
                    <a class="btn btn-yellow btn-xs" title="Edit Attribute"
                       href="{{ route('attribute.edit',$row->Id) }}">
                       <i class="ace-icon fa fa-at bigger-120"></i>
                    </a>--}}
              {{--@endcan--}}
         </div>

         <div class="hidden-md hidden-lg">
            <div class="inline pos-rel">
               <button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto">
                  <i class="ace-icon fa fa-cog icon-only bigger-110"></i>
               </button>

               <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                  @can('product-delete')
                     <li><a class="btn btn-xs btn-danger delete_me" data-id="{{ $row->Id }}">
                           <i class="ace-icon fa fa-trash-o bigger-120"></i>
                        </a></li>
                  @endcan
                  <li>
                     <a class="btn btn-xs btn-info bolder"
                        title="show product"
                        href="{{ route('customer.show',$row->Id) }}"><i
                                class="ace-icon fa fa-eye bigger-120"></i>
                     </a>
                  </li>

               </ul>
            </div>
         </div>

      </td>
   </tr>
@empty
   <tr>
      <td colspan="16" class="text-capitalize">Không có kết quả nào</td>
   </tr>
@endforelse
