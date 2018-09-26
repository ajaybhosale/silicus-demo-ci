@extends($theme.'.layouts.app')
@section('content')
<div class="container mainblock">
    <div class="left" style="background: #eceaea; padding: 2px 5px; margin: 30px 0px 10px 0px;" >
        <h5><a href="{{$url}}/companies/{{$strCompanyId}}/address/list">Address</a> > Update Address</h5>
    </div>
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel-default" style="background: #ebf4f1">
                <div class="panel-body">
                    <form class="contact-form" action="{{$url}}/company/{{$strCompanyId}}/address/{{$addressId}}" method="post">
                        <input type="hidden" name="companyId" value="{{$strCompanyId}}" id="companyId">
                        <input type="hidden" name="addressId" value="{{$addressId}}" id="addressId">
                        <div class="col-sm-12 ">
                            <div class="col-sm-6 form-group {{ !empty($errors) && isset($errors['CA013']) ? ' has-error' :'' }}'" >
                                <label>Nickname:</label>
                                <input type="text" trim="blur" name="nickname" value="{{$response->nickname}}" id="nickname" required="" class="form-control">
                                @if (!empty($errors) && isset($errors['CA013']))
                                <span class="help-block">
                                    <strong>{{$errors['CA013'] }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="col-sm-6 form-group {{ !empty($errors) && isset($errors['CA002']) ? ' has-error' : '' }}">
                                <label>address1:</label>
                                <input type="text" trim="blur" name="address_line1" value="{{$response->address_line1}}" id="address_line1" class="form-control" required="">
                                @if (!empty($errors) && isset($errors['CA002']))
                                <span class="help-block">
                                    <strong>{{ $errors['CA002'] }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-sm-6 form-group {{ !empty($errors) && isset($errors['CA003']) ? ' has-error' : '' }}">
                                <label>address2:</label>
                                <input type="text" trim="blur" name="address_line2" value="{{$response->address_line2}}" id="address_line2" class="form-control" required="">
                                @if (!empty($errors) && isset($errors['CA003']))
                                <span class="help-block">
                                    <strong>{{ $errors['CA003'] }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="col-sm-6 form-group {{ !empty($errors) && isset($errors['CA004']) ? ' has-error' : '' }}">
                                <label>City:</label>
                                <input type="text" trim="blur" name="city" id="city" value="{{$response->city}}" class="form-control" required="">
                                @if (!empty($errors) && isset($errors['CA004']))
                                <span class="help-block">
                                    <strong>{{ $errors['CA004'] }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="col-sm-6 form-group {{ !empty($errors) && isset($errors['CA005']) ? ' has-error' : '' }}">
                                <label>State:</label>
                                <input type="text" trim="blur" name="state" id="state"  value="{{$response->state}}" class="form-control" required="">
                                @if (!empty($errors) && isset($errors['CA005']))
                                <span class="help-block">
                                    <strong>{{ $errors['CA005'] }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-sm-6 form-group {{ !empty($errors) && isset($errors['CA006']) ? ' has-error' : '' }}">
                                <label>Zip:</label>
                                <input type="text" trim="blur" name="zip" id="zip" value="{{$response->zip}}" class="form-control" required="">
                                @if (!empty($errors) && isset($errors['CA006']))
                                <span class="help-block">
                                    <strong>{{ $errors['CA006'] }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-sm-6 form-group {{ !empty($errors) && isset($errors['CA007']) ? ' has-error' : '' }}">
                                <label>Primary Phone:</label>
                                <input type="text" trim="blur" name="primary_phone" value="{{$response->primary_phone}}" class="form-control" id="primary_phone" required="">
                                @if (!empty($errors) && isset($errors['CA007']))
                                <span class="help-block">
                                    <strong>{{ $errors['CA007'] }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-sm-6 form-group {{ !empty($errors) && isset($errors['CA008']) ? ' has-error' : '' }}">
                                <label>Secondary Phone:</label>
                                <input type="text" trim="blur" name="secondary_phone" value="{{$response->secondary_phone}}" class="form-control" id="secondary_phone" required="">
                                @if (!empty($errors) && isset($errors['CA008']))
                                <span class="help-block">
                                    <strong>{{ $errors['CA008'] }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-sm-6 form-group {{ !empty($errors) && isset($errors['CA011']) ? ' has-error' : '' }}">
                                <label>Fax:</label>
                                <input type="text" trim="blur" name="fax" id="fax" value="{{$response->fax}}" class="form-control" required="">
                                @if (!empty($errors) && isset($errors['CA011']))
                                <span class="help-block">
                                    <strong>{{ $errors['CA011'] }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-sm-6 form-group {{ !empty($errors) && isset($errors['CA009']) ? ' has-error' : '' }}">
                                <label>Email1:</label>
                                <input type="text" trim="blur" name="primary_email" value="{{$response->primary_email}}" class="form-control" id="primary_email" required="">
                                @if (!empty($errors) && isset($errors['CA009']))
                                <span class="help-block">
                                    <strong>{{ $errors['CA009'] }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-sm-6 form-group {{ !empty($errors) && isset($errors['CA010']) ? ' has-error' : '' }}">
                                <label>Email2:</label>
                                <input type="text" trim="blur" name="secondary_email" value="{{$response->secondary_email}}" class="form-control" id="secondary_email" required="">
                                @if (!empty($errors) && isset($errors['CA010']))
                                <span class="help-block">
                                    <strong>{{ $errors['CA010'] }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-sm-6 form-group {{ !empty($errors) && isset($errors['CA012']) ? ' has-error' : '' }}">
                                <label>Address Type:</label>
                                <select name="address_type" id="address_type" class="form-control" required = 'true'>
                                    <option value="primary">Primary</option>
                                    <option value="secondary">Secondary</option>
                                </select>
                                @if (!empty($errors) && isset($errors['CA012']))
                                <span class="help-block">
                                    <strong>{{ $errors['CA012'] }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-sm-6 form-group">
                                {!! csrf_field() !!}
<!--                                <input type="submit" name="submit" value="Save" class="btn btn-primary btn-lg" required="required">-->
                                <button type="submit" onclick="validate_contact_form()" class="btn btn-primary btn-lg">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
