@extends('layout.admin.index')
@section('extra_css')
   <!-- the script in this page wont work with pjax so i hava to reload it  -->
   @if (env('APP_AJAX'))
      <script type="text/javascript">
          $(document).on('pjax:complete', function() {
              pjax.reload();
          })
      </script>
   @endif
{{--   <link rel="stylesheet" href="{{ asset('admin-assets/css/w3.css') }}">--}}
@endsection
@section('content')
    <a class="btn btn-danger" href="{{ route('news.index') }}"><i class="fa fa-arrow-left"></i> Quay lại</a>
    <div class="hr dotted"></div>
    <div id="user-profile-1" class="user-profile row">
         <div class="col-xs-12 col-sm-3 center">
            @if ($news->picture !=null)
               <div class="clearfix w3-display-container">
                     <a href="{{$news->picture}}" target="_blank">
                        <img class="img-responsive mySlides" src="{{ $news->picture }}"
                             alt="{{ $news->picture }}" style="width:100%; height: 400px">
                     </a>
                 
               </div>
               @else
               <div class="clearfix">
                  <h2 class="text-danger bolder">WITHOUT PHOTO</h2>
               </div>
            @endif

           {{-- <div class="clearfix">
               <div class="grid2">
                  <span class="bigger-175 blue">25</span>
                  <br>
                  Followers
               </div>

               <div class="grid2">
                  <span class="bigger-175 blue">12</span>
                  <br>
                  Following
               </div>
            </div>--}}
            
               <div class="hr hr16 dotted"></div>
               <div class="profile-contact-links align-left ">
                  <a href="{{route('news.edit',$news->id)}}" class="btn btn-link">
                     <i class="ace-icon fa fa-plus-circle bigger-120 warning"></i>
                     Edit News
                  </a>
               </div>

               
            
         </div>

         <div class="col-xs-12 col-sm-9">
            <div class="profile-user-info profile-user-info-striped">
               <div class="profile-info-row">
                  <div class="profile-info-name">Tiều đề</div>
                  <div class="profile-info-value">
                     <span class="editable editable-click" id="username">{{ $news->title }}</span>
                  </div>
               </div>

                <div class="profile-info-row">
                    <div class="profile-info-name">Nội dung ngắn</div>
                    <div class="profile-info-value">
                        <span class="editable editable-click" id="username">{{ $news->short }}</span>
                    </div>
                </div>
              

                <div class="profile-info-row">
                    <div class="profile-info-name">Nội dung dài</div>

                    <div class="profile-info-value">
                        <span class="editable editable-click" id="username">{!! $news->full !!}
                        </span>
                    </div>
                </div>
               
               
                <div class="profile-info-row">
                    <div class="profile-info-name">Chuyên Mục</div>
                    <div class="profile-info-value">
                     <span class="editable editable-click" id="description">
                         <span class='label label-default'>
                            @foreach ($categoryNews as $item)
                                @if ($item->id == $news->category_id)
                                    {{$item->name}}
                                @endif
                            @endforeach  
                         </span>
                     </span>
                    </div>
                </div>
{{--
               <div class="profile-info-row">
                  <div class="profile-info-name">Tags</div>
                  <div class="profile-info-value">
                     <span class="editable editable-click" id="about">
                        @foreach($product->tags as $tag)
                           <span class='label label-info'>{{ $tag->tag_name }}</span>
                        @endforeach
                     </span>
                  </div>
               </div>

               <div class="profile-info-row">
                  <div class="profile-info-name">Attributes</div>
                  <div class="profile-info-value">
                     <span class="editable editable-click" id="about">
                        @forelse($product->attributes as $attribute)
                           <b>{{ $attribute->attr_name }}:</b>
                           @foreach($attribute->attributeValues as $value)
                              <span class='label label-default'>{{ $value->value }}</span>
                           @endforeach
                        @empty
                           <b>NO ATTRIBUTES</b>
                        @endforelse
                     </span>
                  </div>
               </div>

               <div class="profile-info-row">
                  <div class="profile-info-name"> Created Date</div>

                  <div class="profile-info-value">
                     <span class="editable editable-click" id="signup">{{ $product->created_at }}</span>
                  </div>
               </div>
               <div class="profile-info-row">
                  <div class="profile-info-name"> Updated Date</div>

                  <div class="profile-info-value">
                     <span class="editable editable-click" id="signup">{{ $product->updated_at }}</span>
                  </div>
               </div>
               --}}
            </div>
            {{--
            <h2>Comments</h2>
            <h6>red color not approved yet </h6>
            <div class="col-sm-6">
               @forelse($comments as $comment)
                  <div class="well well-lg"
                       style="background-color: {{ $comment->approved == 1 ? '#79ffb2': '#ffaf93'}}">
                     <h4 class="">
                        @if($comment->commenter_id =! null)
                           <span class="tag blue"><b>{{ $comment->guest_name }}</b></span>
                           <small><a href="mailTo:{{ $comment->guest_email }}">{{ $comment->guest_email }}</a></small>
                        @else
                           {{ $comment->commenter() }}
                        @endif
                     </h4>
                     {{$comment->comment}}
                  </div>
               @empty
                  no comments yet
               @endforelse
               $comments->links()
            </div>
            --}}
      </div>
   </div>
    <hr/>
  
   <br/>
  
@endsection
@section('extra_js')
   <!-- FOR IMAGE SLIDER -->
   <script type="text/javascript">
       var slideIndex = 1;
       showDivs(slideIndex);

       function plusDivs(n) {
           showDivs(slideIndex += n);
       }

       function showDivs(n) {
           var i;
           var x = document.getElementsByClassName("mySlides");
           if (n > x.length) {
               slideIndex = 1
           }
           if (n < 1) {
               slideIndex = x.length
           }
           for (i = 0; i < x.length; i++) {
               x[i].style.display = "none";
           }
           x[slideIndex - 1].style.display = "block";
       }
   </script>
@stop
