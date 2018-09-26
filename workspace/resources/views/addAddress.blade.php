<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.14.0/jquery.validate.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.0/js/responsive.bootstrap.min.js"></script>
<section class="content-header">
    <h1>Add address </h1>
</section>
<section class="content">
    <div class="row">
    </div>
    <div class="panel-default">
        <div class="panel-body">
            <div id="fromDiv" style="border: 1px solid #ddd;padding: 10px;">
                <div class="tab-wrap">
                    <div class="media">
                        <form action="{{url()}}/company/{{$strCompanyId}}/create/address" method="post">
                            <input type="hidden" name="companyId" value="{{$strCompanyId}}" id="companyId">
                            <div class = 'form-group col-lg-4 row {{ !empty($errors) && $errors->has('name') ? ' has-error' :'' }}'>
                                <label>Nickname:</label>
                                <input type="text" trim="blur" name="nickname" id="nickname" required="">
                                @if (!empty($errors) && $errors->has('nickname'))
                                <span class="help-block">
                                    <strong>{{ !empty($errors) &&  $errors->first('nickname') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class = "clearfix"></div>
                            <div class = 'form-group col-lg-4 row {{ !empty($errors) && $errors->has('address_line1') ? ' has-error' : '' }}'>
                                <label>address1:</label>
                                <input type="text" trim="blur" name="address_line1" id="address_line1" required="">
                                @if (!empty($errors) && $errors->has('address_line1'))
                                <span class="help-block">
                                    <strong>{{ !empty($errors) &&  $errors->first('address_line1') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class = "clearfix"></div>
                            <div class = 'form-group col-lg-4 row {{ !empty($errors) && $errors->has('address_line2') ? ' has-error' : '' }}'>
                                <label>address2:</label>
                                <input type="text" trim="blur" name="address_line2" id="address_line2" required="">
                                @if (!empty($errors) && $errors->has('address_line2'))
                                <span class="help-block">
                                    <strong>{{ !empty($errors) &&  $errors->first('address_line2') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class = "clearfix"></div>
                            <div class = 'form-group col-lg-4 row {{ !empty($errors) && $errors->has('city') ? ' has-error' : '' }}'>
                                <label>City:</label>
                                <input type="text" trim="blur" name="city" id="city" required="">
                                @if (!empty($errors) && $errors->has('address_line2'))
                                <span class="help-block">
                                    <strong>{{ !empty($errors) &&  $errors->first('city') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class = "clearfix"></div>
                            <div class = 'form-group col-lg-4 row {{ !empty($errors) && $errors->has('state') ? ' has-error' : '' }}'>
                                <label>State:</label>
                                <input type="text" trim="blur" name="state" id="state" required="">
                                @if (!empty($errors) && $errors->has('state'))
                                <span class="help-block">
                                    <strong>{{ !empty($errors) &&  $errors->first('state') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class = "clearfix"></div>
                            <div class = 'form-group col-lg-4 row {{ !empty($errors) && $errors->has('zip') ? ' has-error' : '' }}'>
                                <label>Zip:</label>
                                <input type="text" trim="blur" name="zip" id="zip" required="">
                                @if (!empty($errors) && $errors->has('zip'))
                                <span class="help-block">
                                    <strong>{{ !empty($errors) &&  $errors->first('zip') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class = "clearfix"></div>
                            <div class = 'form-group col-lg-4 row {{ !empty($errors) && $errors->has('primary_phone') ? ' has-error' : '' }}'>
                                <label>Primary Phone:</label>
                                <input type="text" trim="blur" name="primary_phone" id="primary_phone" required="">
                                @if (!empty($errors) && $errors->has('primary_phone'))
                                <span class="help-block">
                                    <strong>{{ !empty($errors) &&  $errors->first('primary_phone') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class = "clearfix"></div>
                            <div class = 'form-group col-lg-4 row {{ !empty($errors) && $errors->has('secondary_phone') ? ' has-error' : '' }}'>
                                <label>Secondary Phone:</label>
                                <input type="text" trim="blur" name="secondary_phone" id="secondary_phone" required="">
                                @if (!empty($errors) && $errors->has('secondary_phone'))
                                <span class="help-block">
                                    <strong>{{ !empty($errors) &&  $errors->first('secondary_phone') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class = "clearfix"></div>
                            <div class = 'form-group col-lg-4 row {{ !empty($errors) && $errors->has('fax') ? ' has-error' : '' }}'>
                                <label>Fax:</label>
                                <input type="text" trim="blur" name="fax" id="fax" required="">
                                @if (!empty($errors) && $errors->has('fax'))
                                <span class="help-block">
                                    <strong>{{ !empty($errors) &&  $errors->first('fax') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class = "clearfix"></div>
                            <div class = 'form-group col-lg-4 row {{ !empty($errors) && $errors->has('primary_email') ? ' has-error' : '' }}'>
                                <label>Email1:</label>
                                <input type="text" trim="blur" name="primary_email" id="primary_email" required="">
                                @if (!empty($errors) && $errors->has('primary_email'))
                                <span class="help-block">
                                    <strong>{{ !empty($errors) &&  $errors->first('primary_email') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class = "clearfix"></div>
                            <div class = 'form-group col-lg-4 row {{ !empty($errors) && $errors->has('secondary_email') ? ' has-error' : '' }}'>
                                <label>Email2:</label>
                                <input type="text" trim="blur" name="secondary_email" id="secondary_email" required="">
                                @if (!empty($errors) && $errors->has('secondary_email'))
                                <span class="help-block">
                                    <strong>{{ !empty($errors) &&  $errors->first('secondary_email') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class = "clearfix"></div>
                            <div class = 'form-group col-lg-4 row {{ !empty($errors) && $errors->has('address_type') ? ' has-error' : '' }}'>
                                <label>Address Type:</label>
                                <input type="text" trim="blur" name="address_type" id="address_type" required="">
                                @if (!empty($errors) && $errors->has('address_type'))
                                <span class="help-block">
                                    <strong>{{ !empty($errors) &&  $errors->first('address_type') }}</strong>
                                </span>
                                @endif
                            </div>
                            <input type="submit" class="btn btn-primary btn-lg" value="Submit" name="Submit" >
                        </form>
                    </div>
                </div>
            </div> <!--/.tab-content-->
        </div> <!--/.media-body-->
    </div> <!--/.media-->
</section>


