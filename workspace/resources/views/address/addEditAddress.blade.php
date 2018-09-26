<form id="addCompanyAddress" [formGroup]="companyAddrForm" *ngIf="!nextTab">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pageheadcolor">
        <h2>Company Address </h2>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" *ngIf="isError">
        <div class="m-alert m-alert--outline alert alert-danger alert-dismissible fade show" role="alert">
            {{responseMsg}}
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row merchant">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="group textcolor one">
                <input type="text" trim="blur" formControlName="nickname" [ngClass]="companyAddrForm.get('nickname').value?'used':''">
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Nickname <b class="redcolor">*</b></label>
            </div>
            <span class="error">
                <p *ngIf="errors && !companyAddrForm.controls['nickname'].invalid && errors.hasOwnProperty(fieldCode.nickname)">{{errors[fieldCode.nickname]}}</p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['nickname'].errors && companyAddrForm.controls['nickname'].errors.required">The Nickname field is required.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.nickname}})</span>
                </p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['nickname'].errors && companyAddrForm.controls['nickname'].errors.maxlength">The Nickname field contains maximum 1024 characters.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.nickname}})</span>
                </p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['nickname'].errors && companyAddrForm.controls['nickname'].errors.pattern">The Nickname has invalid data.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.nickname}})</span>
                </p>
            </span>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row merchant">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="group textcolor one">
                <input type="text" trim="blur" formControlName="address_line1" [ngClass]="companyAddrForm.get('address_line1').value?'used':''">
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Address-1 <b class="redcolor">*</b></label>
            </div>
            <span class="error">
                <p *ngIf="errors && !companyAddrForm.controls['address_line1'].invalid && errors.hasOwnProperty(fieldCode.address_line1)">{{errors[fieldCode.address_line1]}}</p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['address_line1'].errors && companyAddrForm.controls['address_line1'].errors.required">The Address-1 field is required.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.address_line1}})</span>
                </p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['address_line1'].errors && companyAddrForm.controls['address_line1'].errors.maxlength">The Address-1 field contains maximum 1024 characters.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.address_line1}})</span>
                </p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['address_line1'].errors && companyAddrForm.controls['address_line1'].errors.pattern">The Address-1 has invalid data.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.address_line1}})</span>
                </p>
            </span>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="group textcolor one">
                <input type="text" trim="blur" formControlName="address_line2" [ngClass]="companyAddrForm.get('address_line2').value?'used':''">
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Address-2 <b class="redcolor">*</b></label>
            </div>
            <span class="error">
                <p *ngIf="errors && !companyAddrForm.controls['address_line2'].invalid && errors.hasOwnProperty(fieldCode.address_line2)">{{errors[fieldCode.address_line2]}}</p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['address_line2'].errors && companyAddrForm.controls['address_line2'].errors.required">The Address-2 field is required.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.address_line2}})</span>
                </p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['address_line2'].errors && companyAddrForm.controls['address_line2'].errors.maxlength">The Address-2 field contains maximum 1024 characters.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.address_line2}})</span>
                </p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['address_line2'].errors && companyAddrForm.controls['address_line2'].errors.pattern">The Address-2 field has invalid data.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.address_line2}})</span>
                </p>
            </span>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  row merchant">
        <div class="col-lg-6  col-md-6 col-sm-6 col-xs-12">
            <div class="group textcolor one">
                <input type="text" trim="blur" formControlName="city" [ngClass]="companyAddrForm.get('city').value?'used':''">
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>City <b class="redcolor">*</b></label>
            </div>
            <span class="error">
                <p *ngIf="errors && !companyAddrForm.controls['city'].invalid && errors.hasOwnProperty(fieldCode.city)">{{errors[fieldCode.city]}}</p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['city'].errors && companyAddrForm.controls['city'].errors.required">The City field is required.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.city}})</span>
                </p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['city'].errors && companyAddrForm.controls['city'].errors.maxlength">The City field exceeds maxlength.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.city}})</span>
                </p>
            </span>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
            <div class="group textcolor one">
                <p class="selecttext">State <b class="redcolor">*</b></p>
                <select class="form-control" formControlName="state">
                    <option value=''>Select State</option>
                    <option *ngFor="let state of stateList" value="{{state.id}}">{{state.name}}</option>
                </select>
            </div>
            <span class="error">
                <p *ngIf="errors && !companyAddrForm.controls['state'].invalid && errors.hasOwnProperty(fieldCode.state)">{{errors[fieldCode.state]}}</p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['state'].errors && companyAddrForm.controls['state'].errors.required">The State field is required.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.state}})</span>
                </p>
            </span>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
            <div class="group textcolor one">
                <input type="text" id="zip" [formControl]="companyAddrForm.controls['zip']" mask="99999" maxlength="5" [ngClass]="companyAddrForm.get('zip').value?'used':''">
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Zip <b class="redcolor">*</b></label>
            </div>
            <span class="error">
                <p *ngIf="errors && !companyAddrForm.controls['zip'].invalid && errors.hasOwnProperty(fieldCode.zip)">{{errors[fieldCode.zip]}}</p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['zip'].errors && companyAddrForm.controls['zip'].errors.required">The Zip field is required.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.zip}})</span>
                </p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['zip'].errors && companyAddrForm.controls['zip'].errors.maxlength">The Zip field must be 5 digits.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.zip}})</span>
                </p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['zip'].errors && companyAddrForm.controls['zip'].errors.minlength">The Zip field must be 5 digits.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.zip}})</span>
                </p>
            </span>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row merchant paddingdiffer">
        <div class="col-lg-4 col-md-12 col-sm-6 col-xs-12">
            <div class="group textcolor one">
                <input type="text" id="primary_phone" [formControl]="companyAddrForm.controls['primary_phone']" maxlength="13" mask="999-999-9999" [ngClass]="companyAddrForm.get('primary_phone').value?'used':''">
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Primary Phone <b class="redcolor">*</b></label>
            </div>
            <span class="error">
                <p *ngIf="errors && !companyAddrForm.controls['primary_phone'].invalid && errors.hasOwnProperty(fieldCode.primary_phone)">{{errors[fieldCode.primary_phone]}}</p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['primary_phone'].errors && companyAddrForm.controls['primary_phone'].errors.required">The Primary Phone field is required.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.primary_phone}})</span>
                </p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['primary_phone'].errors && companyAddrForm.controls['primary_phone'].errors.maxlength">The Primary Phone field must be 10 digits.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.primary_phone}})</span>
                </p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['primary_phone'].errors && companyAddrForm.controls['primary_phone'].errors.pattern">The Primary Phone field must be 10 digits.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.primary_phone}})</span>
                </p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['primary_phone'].errors && companyAddrForm.controls['primary_phone'].errors.minlength">The Primary Phone field must be 10 digits.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.primary_phone}})</span>
                </p>
            </span>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-1">
            <div class="group textcolor one">
                <input type="text" id="secondary_phone" [formControl]="companyAddrForm.controls['secondary_phone']" maxlength="13" mask="999-999-9999" [ngClass]="companyAddrForm.get('secondary_phone').value?'used':''">
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Secondary Phone</label>
            </div>
            <span class="error">
                <p *ngIf="errors && !companyAddrForm.controls['secondary_phone'].invalid && errors.hasOwnProperty(fieldCode.secondary_phone)">{{errors[fieldCode.secondary_phone]}}</p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['secondary_phone'].errors && companyAddrForm.controls['secondary_phone'].errors.maxlength">The Secondary Phone field field must be 10 digits.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.secondary_phone}})</span>
                </p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['secondary_phone'].errors && companyAddrForm.controls['secondary_phone'].errors.pattern">The Secondary Phone field field must be 10 digits.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.secondary_phone}})</span>
                </p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['secondary_phone'].errors && companyAddrForm.controls['secondary_phone'].errors.minlength">The Secondary Phone field field must be 10 digits.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.secondary_phone}})</span>
                </p>
            </span>

        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-1">
            <div class="group textcolor one">
                <input type="text" id="fax" mask="999-999-9999" maxlength="13" [formControl]="companyAddrForm.controls['fax']" [ngClass]="companyAddrForm.get('fax').value?'used':''">
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Fax</label>
            </div>
            <span class="error" *ngIf="errors && !companyAddrForm.controls['fax'].invalid && errors.hasOwnProperty(fieldCode.fax)">{{errors[fieldCode.fax]}}</span>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row merchant">
        <div class="col-lg-6 col-md-6">
            <div class="group textcolor one">
                <input type="text" trim="blur" formControlName="primary_email" [ngClass]="companyAddrForm.get('primary_email').value?'used':''">
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Email 1 <b class="redcolor">*</b></label>
            </div>
            <span class="error">
                <p *ngIf="errors && !companyAddrForm.controls['primary_email'].invalid && errors.hasOwnProperty(fieldCode.primary_email)">{{errors[fieldCode.primary_email]}}</p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['primary_email'].errors && companyAddrForm.controls['primary_email'].errors.required">The Email 1 field is required.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.primary_email}})</span>
                </p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['primary_email'].errors && companyAddrForm.controls['primary_email'].errors.pattern">The Email 1 field has invalid email address.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.primary_email}})
                    </span>
                </p>
            </span>

        </div>
        <div class="col-lg-6 col-md-6">
            <div class="group textcolor one">
                <input type="text" trim="blur" formControlName="secondary_email" [ngClass]="companyAddrForm.get('secondary_email').value?'used':''">
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Email 2</label>
            </div>
            <span class="error">
                <p *ngIf="errors && !companyAddrForm.controls['secondary_email'].invalid && errors.hasOwnProperty(fieldCode.secondary_email)">{{errors[fieldCode.secondary_email]}}</p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['secondary_email'].errors && companyAddrForm.controls['secondary_email'].errors.pattern">The Email 2 field has invalid email address.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.secondary_email}})</span>
                </p>
            </span>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row merchant">
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
            <div class="group textcolor one">
                <p class="selecttext">Address Type<b class="redcolor">*</b></p>
                <select class="form-control" formControlName="address_type" [ngClass]="companyAddrForm.get('address_type').value?'used':''">
                    <option value=''>Select Address Type</option>
                    <option value='primary'>Primary</option>
                    <option value='secondary'>Secondary</option>
                </select>
                <span class="highlight"></span>
                <span class="bar"></span>
            </div>
            <span class="error">
                <p *ngIf="errors && !companyAddrForm.controls['address_type'].invalid && errors.hasOwnProperty(fieldCode.address_type)">{{errors[fieldCode.address_type]}}</p>
                <p *ngIf="isCompanyAddrFormSubmit && companyAddrForm.controls['address_type'].errors && companyAddrForm.controls['address_type'].errors.required">The Address Type field is required.
                    <span *ngIf="showErrorCode">(Err Code: {{fieldCode.address_type}})</span>
                </p>
            </span>
        </div>
    </div>
    <app-notes-section *ngIf="!isAdd" (NoteChanged)=NoteHandler($event)></app-notes-section>
    <div class="col-lg-12 col-md-12 row merchant">
        <div class="col-lg-12">
            <p class="pull-right">
                <button class="newcancel" (click)="cancel(companyAddrDetails)">Cancel</button>
                <button (click)="saveAndNext()" class="newsavenext">Save & Next</button>
                <button id="saveAddresses" (click)="update()" class="savegreen">Save</button>
            </p>
        </div>
    </div>
</form>
<div *ngIf="nextTab">
    <app-business-data-view></app-business-data-view>
</div>