@extends($theme.'.layouts.app')
@section('content')
<script>
    var avatarPath = "{{url('/profile')}}/{{isset($userDetails['avatar']) ? $userDetails['avatar'] : 'profile.png'}}";
</script>
<div class="container">
    <div class="left">
        <h2>Profile</h2>
    </div>
    <div class="row">
        <div style="float: left;padding-right:10px">
            <div id="kv-avatar-errors-2" class="center-block" style="width:800px;display:none"></div>
            <form class="text-center"  method="post" action="{{url('/profile/update/avatar')}}" enctype="multipart/form-data" target="upload_target">
                <div class="kv-avatar center-block" style="width:200px">
                    <div id="iframeText"></div>
                    <input id="avatar" name="avatar" type="file" class="file-loading">
                </div>
                <input type="hidden" name="_token" value="{{csrf_token() }}">
            </form>
            <iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>

        </div>


        <div class="tab-wrap" >
            <div class="media">
                <div class="parrent pull-left">
                    <ul class="nav nav-tabs nav-stacked">
                        <li class="active"><a class="analistic-01" data-toggle="tab" href="#tab1">General Information</a></li>
                        <li class=""><a class="analistic-02" data-toggle="tab" href="#tab2">Contact Information</a></li>
                        <li class=""><a class="tehnical" data-toggle="tab" href="#tab3">Social Media</a></li>
                        <li class=""><a class="tehnical" data-toggle="tab" href="#tab4">About Me</a></li>
                        <li class=""><a class="tehnical" data-toggle="tab" href="#tab5">Bio</a></li>
                    </ul>
                </div>
                <div class="parrent media-body">

                    <div class="tab-content">
                        <div id="tab1" class="tab-pane fade active in">
                            <div class="media">
                                <div class="media-body">
                                    <div class="col-xs-5 col-sm-12">
                                        <ul class="profile-details">
                                            <li>
                                                <div><i class="fa fa-briefcase"></i> name</div>
                                                <a href="#" id="username" data-type="text" data-pk="1" data-url="{{url('profile/update')}}" data-title="Enter name" >{{$userDetails['name']}}</a>
                                            </li>
                                            <li>
                                                <div><i class="fa fa-briefcase"></i> gender</div>
                                                <a href="#" id="gender" data-type="select" data-pk="2"  data-url="{{url('profile/update')}}" data-title="Enter gender" >{{isset($userDetails['gender']) ? $userDetails['gender'] : ''}}</a>
                                            </li>
                                            <li>
                                                <div><i class="fa fa-briefcase"></i> date of birth</div>
                                                <a href="#" id="dateOfBirth" data-type="combodate" data-pk="3" data-url="{{url('profile/update')}}" data-value="{{isset($userDetails['date_of_birth']) ? $userDetails['date_of_birth']: ''}}" data-title="Select date">{{isset($userDetails['date_of_birth']) ? $userDetails['date_of_birth']: ''}}</a>
                                            </li>
                                            <li>
                                                <div><i class="fa fa-briefcase"></i> position</div>
                                                <a href="#" id="position" data-type="text"  data-pk="4" data-url="{{url('profile/update')}}" data-title="Enter position" >{{isset($userDetails['position']) ? $userDetails['position'] : ''}}</a>
                                            </li>
                                            <li>
                                                <div><i class="fa fa-building-o"></i> company</div>
                                                <a href="#" id="company" data-type="text" data-pk="5" data-url="{{url('profile/update')}}" data-title="Enter company" >{{isset($userDetails['company']) ? $userDetails['company'] : ''}}</a>
                                            </li>
                                            <li>
                                                <div><i class="fa fa-building-o"></i> Subscribe Newsletter</div>
                                                <a href="#" id="news_letters" data-type="select" data-pk="6" data-url="{{url('profile/update')}}" data-title="Subsribe for Newsletter" >{{(isset($userDetails['news_letters']) && $userDetails['news_letters']==1) ? 'Yes' : 'No'}}</a>
                                            </li>
                                            <!--Signature code start-->
                                            <li>
                                                <div><i class="fa fa-building-o"></i> Signature</div>
                                                <textarea id="signature" style="visibility:hidden;height:1px;width:1px;">{{$userDetails['signature']}}</textarea>
                                                <div id="captureSignature" style="height:150px;width:400px;">
                                                </div>
                                                <button type="button" id="clear2Button">Clear</button>
                                                <button type="button" id="jsonSubmit">Save</button>
                                            </li>
                                            <li>
                                                <button id="download">Download Pdf</button>
                                            </li>
                                            <!--Signature code end-->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="tab2" class="tab-pane fade">
                            <div class="media">
                                <div class="media-body">
                                    <div class="col-xs-5 col-sm-12">
                                        <ul class="profile-details">
                                            <li>
                                                <div><i class="fa fa-phone"></i> phone</div>
                                                <a href="#" id="phone" data-type="tel" data-pk="6" data-url="{{url('profile/update')}}" data-title="Enter phone" >{{isset($userDetails['phone']) ? $userDetails['phone'] : ''}}</a>
                                            </li>
                                            <li>
                                                <div><i class="fa fa-tablet"></i> mobile phone</div>
                                                <a href="#" id="mobilePhone" data-type="tel" data-pk="7" data-url="{{url('profile/update')}}" data-title="Enter mobile phone" >{{isset($userDetails['mobile_phone']) ? $userDetails['mobile_phone'] : ''}}</a>
                                            </li>
                                            <li>
                                                <div><i class="fa fa-map-marker"></i> address</div>
                                                <a href="#" id="address" data-type="textarea" data-pk="9" data-url="{{url('profile/update')}}" data-title="Enter address" >{{isset($userDetails['address']) ? $userDetails['address'] : ''}}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="tab3" class="tab-pane fade">
                            <div class="col-xs-5 col-sm-12">
                                <ul class="profile-details">
                                    <li>
                                        <div><i class="fa fa-phone"></i> facebook</div>
                                        <a href="#" id="facebook" data-type="url" data-pk="10" data-url="{{url('profile/update')}}" data-title="Enter facebook" >{{isset($userDetails['facebook']) ? $userDetails['facebook'] : ''}}</a>
                                    </li>
                                    <li>
                                        <div><i class="fa fa-tablet"></i> twitter</div>
                                        <a href="#" id="twitter" data-type="url" data-pk="11" data-url="{{url('profile/update')}}" data-title="Enter twitter" >{{isset($userDetails['twitter']) ? $userDetails['twitter'] : ''}}</a>
                                    </li>
                                    <li>
                                        <div><i class="fa fa-envelope"></i> google+l</div>
                                        <a href="#" id="google" data-type="url" data-pk="12" data-url="{{url('profile/update')}}" data-title="Enter google" >{{isset($userDetails['google']) ? $userDetails['google'] : ''}}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div id="tab4" class="tab-pane fade">
                            <p><a href="#" id="aboutMe" data-type="textarea" data-pk="13" data-url="{{url('profile/update')}}" data-title="Enter about me" >{{isset($userDetails['about_me']) ? $userDetails['about_me'] : ''}}</a></p>
                        </div>
                        <div id="tab5" class="tab-pane fade">
                            <p><a href="#" id="biography" data-type="textarea" data-pk="14" data-url="{{url('profile/update')}}" data-title="Enter biography" >{{isset($userDetails['biography']) ? $userDetails['biography'] : ''}}</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection