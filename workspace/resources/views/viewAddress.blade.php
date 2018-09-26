<div *ngIf="!isEdit">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pageheadcolor">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-8 col-xs-6">
                <h2>Company Address</h2>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-6">
                <p class="pull-right editbtn">
                    <a href="" (click)="routeToEdit()" id="editCompanyAddress" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>
                </p>
            </div>
        </div>
    </div>
    <div class="row padding20b">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" *ngIf="isSuccess">
            <div class="m-alert m-alert--outline alert alert-success alert-dismissible fade show" role="alert">
                <button aria-label="Close" class="close" data-dismiss="alert" type="button"></button>
                {{responseMsg}}
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" *ngIf="isError">
            <div class="m-alert m-alert--outline alert alert-danger alert-dismissible fade show" role="alert">
                <button aria-label="Close" class="close" data-dismiss="alert" type="button"></button>
                {{responseMsg}}
            </div>
        </div>
    </div>
    <div class="editform" *ngIf="isValid">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row merchant marbtn20">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Nickname</label>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 blacklabel">
                    <label>{{(companyAddrDetails.attributes.nickname)?companyAddrDetails.attributes.nickname:'-'}}</label>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row merchant marbtn20">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Address 1</label>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 blacklabel">
                    <label>{{(companyAddrDetails.attributes.address_line1)?companyAddrDetails.attributes.address_line1:'-'}}</label>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Address 2</label>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 blacklabel">
                    <label>{{(companyAddrDetails.attributes.address_line2)?companyAddrDetails.attributes.address_line2:'-'}}</label>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12  row merchant marbtn20">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>City</label>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 blacklabel">
                    <label>{{(companyAddrDetails.attributes.city)?companyAddrDetails.attributes.city:'-'}}</label>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>State</label>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 blacklabel">
                    <label>{{(companyAddrDetails.attributes.state)? objState[companyAddrDetails.attributes.state]:'-'}}</label>
                </div>

            </div>

            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Zip</label>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 blacklabel">
                    <label>{{(companyAddrDetails.attributes.zip)?companyAddrDetails.attributes.zip:'-'}}</label>
                </div>

            </div>
        </div>

        <div class="col-lg-12 row merchant marbtn20">
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Primary Phone</label>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 blacklabel">
                    <label>{{companyAddrDetails.attributes.primary_phone|slice:0:3}}-{{companyAddrDetails.attributes.primary_phone|slice:3:6}}-{{companyAddrDetails.attributes.primary_phone|slice:6:10}}
                    </label>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Secondary Phone</label>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 blacklabel">
                    <label>{{companyAddrDetails.attributes.secondary_phone|slice:0:3}}-{{companyAddrDetails.attributes.secondary_phone|slice:3:6}}-{{companyAddrDetails.attributes.secondary_phone|slice:6:10}}
                    </label>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Fax</label>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 blacklabel">
                    <label>{{companyAddrDetails.attributes.fax|slice:0:3}}-{{companyAddrDetails.attributes.fax|slice:3:6}}-{{companyAddrDetails.attributes.fax|slice:6:10}}
                    </label>
                </div>
            </div>
        </div>

        <div class="col-lg-12  col-md-12 col-sm-12 col-xs-12 row merchant marbtn20">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Email 1</label>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 blacklabel">
                    <label>{{(companyAddrDetails.attributes.primary_email)?companyAddrDetails.attributes.primary_email:'-'}}</label>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Email 2</label>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 blacklabel">
                    <label>{{(companyAddrDetails.attributes.secondary_email)?companyAddrDetails.attributes.secondary_email:'-'}}</label>
                </div>
            </div>
        </div>
        <div class="col-lg-12  col-md-12 col-sm-12 col-xs-12 row merchant marbtn20">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Address Type</label>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 blacklabel">
                    <label>{{(companyAddrDetails.attributes.address_type)?companyAddrDetails.attributes.address_type:'-'}}</label>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row merchant">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-1">
                <p class="pull-right">
                    <button class="newcancel" (click)="cancel()">Back</button>
                </p>
            </div>
        </div>
    </div>
</div>
<div *ngIf="isEdit" class="col-lg-12 col-md-12 padding20">
    <app-company-addr-edit [addressId]="addressId" (action)="actionHandler($event)" (isEditSuccess)="showEditMessage($event)"></app-company-addr-edit>
</div>