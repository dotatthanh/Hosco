@forelse($data as $key => $row)
    <tr>
        <td class="center">
            <label class="pos-rel">{{ $key + 1 }}
                <!-- <input type="checkbox" class="ace" id="check"> -->
                <span class="lbl"></span>
            </label>
        </td>

        <td style="max-width:200px"><a class="click_me bolder" data-path="/TinTuc/show" title="show TinTuc"
                href="{{ route('news.show', $row->id) }}">{{ $row->title}}</a>
        </td>
        
        <td>{{ Str::limit( $row->short, 50, '...') }}</td>
        {{-- <td>{{ Str::limit($row->full, 50, '...') }}</td> --}}
        <td>{{ Str::limit($row->category, 100, '...') }}</td>
        <td style="width:100px;height:70px"><img src="{{ $row->picture }}" alt="" width="100%" height="100%"></td>
        
        
        
        <td  style="width:110px">
            <div class="hidden-sm hidden-xs btn-group">
                <a class="btn btn-xs btn-info bolder pushNewsNotification " title="Gửi thông báo"   data-id={{$row->id}}><i class="bigger-120 far fa-paper-plane"></i>


                </a>

                {{-- @can('customer-edit') --}}
                <a class="btn btn-warning btn-xs" title="Edit" href="{{ route('news.edit', $row->id) }}"
                    data-id="{{ $row->id }}">
                    <i class="ace-icon fa fa-edit bigger-120"></i></a>
                {{-- <a class="btn btn-yellow btn-xs" title="Edit Attribute"
                       href="{{ route('attribute.edit',$row->Id) }}">
                       <i class="ace-icon fa fa-at bigger-120"></i>
                    </a> --}}
                {{-- @endcan --}}
                <a class="btn btn-xs btn-danger delete_me" data-id="{{ $row->id }}">
                    <i class="ace-icon fa fa-trash bigger-120"></i>
                </a>
            </div>

            <div class="hidden-md hidden-lg">
                <div class="inline pos-rel">
                    <button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown"
                        data-position="auto">
                        <i class="ace-icon fa fa-cog icon-only bigger-110"></i>
                    </button>

                    <ul
                        class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                        @can('product-delete')
                            <li><a class="btn btn-xs btn-danger delete_me" data-id="{{ $row->id }}">
                                    <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                </a></li>
                        @endcan
                        {{-- <li>
                            <a class="btn btn-xs btn-info bolder" title="show product"
                                href="{{ route('news.show', $row->id) }}"><i
                                    class="ace-icon fa fa-eye bigger-120"></i>
                            </a>
                        </li> --}}
                        <li>
                            <a class="btn btn-warning btn-xs" title="Edit"
                                href="{{ route('news.edit', $row->id) }}"
                                data-id="{{ $row->id }}">
                                <i class="ace-icon fa fa-edit bigger-120"></i></a>
                        </li>
                        <li><a class="btn btn-xs btn-danger delete_me" data-id="{{ $row->id }}">
                                <i class="ace-icon fa fa-trash bigger-120"></i>
                            </a></li>
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
