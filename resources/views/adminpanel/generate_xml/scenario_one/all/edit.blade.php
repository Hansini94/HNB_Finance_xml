@section('title', 'Scenario One List')
<x-app-layout>
    <x-slot name="header">
        <style>
            .select2-selection__rendered {
                padding-left: 5px !important;
            }
        </style>
    </x-slot>

    @if(Session()->get('applocale')=='ta')
        @php
        $lang = "TA";
        @endphp
        @elseif(Session()->get('applocale')=='si')
        @php
        $lang = "SI";
        @endphp
        @else
        @php
        $lang = "EN";
        @endphp
    @endif

    <div id="main" role="main">
        <!-- RIBBON -->
        <div id="ribbon">
        </div>
        <!-- END RIBBON -->
        <div id="content">
            <div class="row">
            <div class="col-lg-12">
                    <div class="row cms_top_btn_row" style="margin-left:auto;margin-right:auto;">
                        <a href="{{ route('scenario-one-list') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('Generate XML') }}</button>
                        </a>

                        <a href="{{ route('scenario-one-all-list') }}">
                            <button class="btn cms_top_btn top_btn_height cms_top_btn_active">{{ __('user.view_all') }}</button>
                        </a>
                    </div>
                </div>
            </div>

            @if ($errors->any())
            <div class="alert alert-danger">
                <!-- <strong>Whoops!</strong> There were some problems with your input.<br><br> -->
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
            @endif
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">
                <header>
                    <h2>{{ __('Scenario 1') }}</h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->
                    </div>
                    <!-- end widget edit box -->
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <form action="{{ route('save-scenario-one-all') }}" enctype="multipart/form-data" method="post" id="scenario-form" class="smart-form">
                            @csrf
                            @method('PUT')
                            <div class="widget-body padding-10">
                                <ul id="myTab1" class="nav nav-tabs bordered">
                                    <li class="active" id="s1A">
                                        <a href="#s1" onclick="show_submit('T1')" data-toggle="tab">{{ __('Scenario 1 Details') }} </a>
                                    </li>
                                    <li id="s2B">
                                        <a href="#s2" class="nextII" onclick="show_submit('T2')" data-toggle="tab">{{ __('From Entity Details') }} </a>
                                    </li>
                                    <li id="s3C">
                                        <a href="#s3" class="nextIII" onclick="show_submit('T3')" data-toggle="tab">{{ __('To Account Details') }} </a>
                                    </li>
                                    <li id="s4D">
                                        <a href="#s4" class="nextIV" onclick="show_submit('T4')" data-toggle="tab">{{ __('From Person Details') }} </a>
                                    </li>
                                </ul>

                                <div id="myTabContent1" class="tab-content" style="padding: 15px !important;">

                                    <!-------------------------------Tab 1---------------------------------------------------->

                                    <div class="tab-pane fade in active" id="s1">
                                        <div class="widget-body no-padding">
                                            <fieldset>
                                                <div class="row">
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('Account Type') }}<span style=" color: red;">*</span> </label>
                                                        <select id="account_type" name="account_type" class="select2" >
                                                        <option value="Entity" {{ $data->account_type == 'Entity' ? "selected" : "" }}>Entity</option>
                                                        <option value="Person" {{ $data->account_type == 'Person' ? "selected" : "" }}>Person</option>
                                                        </select>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('Report Entity ID') }}<span style=" color: red;">*</span> </label>
                                                        <label class="input">
                                                            <input type="text" id="rentity_id" name="rentity_id" required value="{{ $data->rentity_id }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('Report Entity Branch') }}</label>
                                                        <label class="input">
                                                            <input type="text" id="rentity_branch" name="rentity_branch" value="{{ $data->rentity_branch }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Submission Code<span style=" color: red;">*</span> </label>
                                                        <label class="input">
                                                            <input type="text" id="submission_code" name="submission_code" required value="{{ $data->submission_code }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Report Code<span style=" color: red;">*</span> </label>
                                                        <label class="input">
                                                            <input type="text" id="report_code" name="report_code" required value="{{ $data->report_code }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Entity Reference</label>
                                                        <label class="input">
                                                            <input type="text" id="entity_reference" name="entity_reference" value="{{ $data->entity_reference }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Submission Date<span style=" color: red;">*</span></label>
                                                        {{ $data->submission_date }}
                                                        <label class="input">
                                                            <input type="date" id="submission_date" name="submission_date" required value="{{ $data->submission_date }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Currency Code Local</label>
                                                        <label class="input">
                                                            <input type="text" id="currency_code_local" name="currency_code_local" value="{{ $data->currency_code_local }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Transaction Number <span style=" color: red;">*</span> </label>
                                                        <label class="input">
                                                            <input type="text" id="transactionnumber" name="transactionnumber" required value="{{ $data->transactionnumber }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Internal Ref Number</label>
                                                        <label class="input">
                                                            <input type="text" id="internal_ref_number" name="internal_ref_number" value="{{ $data->internal_ref_number }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Transaction Location</label>
                                                        <label class="input">
                                                            <input type="text" id="transaction_location" name="transaction_location" value="{{ $data->transaction_location }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Transaction Description</label>
                                                        <label class="input">
                                                            <input type="text" id="transaction_description" name="transaction_description" value="{{ $data->transaction_description }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Date Transaction<span style=" color: red;">*</span> </label>
                                                        <label class="input">
                                                            <input type="date" id="date_transaction" name="date_transaction" required value="{{ $data->date_transaction }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Value Date<span style=" color: red;">*</span> </label>
                                                        <label class="input">
                                                            <input type="date" id="value_date" name="value_date" required value="{{ $data->value_date }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Transmode Code</label>
                                                        <label class="input">
                                                            <input type="text" id="transmode_code" name="transmode_code" required value="{{ $data->transmode_code }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Amount Local</label>
                                                        <label class="input">
                                                            <input type="text" id="amount_local" name="amount_local" value="{{ $data->amount_local }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">From Funds Code</label>
                                                        <label class="input">
                                                            <input type="text" id="from_funds_code" name="from_funds_code" value="{{ $data->from_funds_code }}">
                                                        </label>
                                                    </section>
                                                </div>
                                            </fieldset>
                                            <footer style="background-color: #fff; border-top: transparent; padding:0px;">
                                                <a href="#s2" id="testing" class="test" onclick="show_submit('T2');changeactive('s2B', 's1A');" data-toggle="tab">
                                                    <button type="button" class="btn btn-primary next test"> {{ __('Next') }} </button>
                                                </a>
                                            </footer>
                                        </div>
                                    </div>
                                    <!--------------------------------------------------- end Tab 1-------------------------------->

                                    <!---------------------------------Tab 2---------------------------------------------------->
                                    <div class="tab-pane fade" id="s2">
                                        <div class="widget-body no-padding">
                                            <fieldset>
                                                <div class="row">
                                                    <section class="col col-4">
                                                    <label class="label">Name</label>
                                                    <label class="input">
                                                        <input type="text" id="from_entity_name" name="from_entity_name" value="{{ $data->from_entity_name }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Incorporation Legal Form</label>
                                                    <label class="input">
                                                        <input type="tel" id="from_entity_incorporation_legal_form" name="from_entity_incorporation_legal_form" value="{{ $data->from_entity_incorporation_legal_form }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Incorporation Number</label>
                                                    <label class="input">
                                                        <input type="text" id="from_entity_incorporation_number" name="from_entity_incorporation_number" value="{{ $data->from_entity_incorporation_number }}">
                                                    </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                    <label class="label">Business</label>
                                                    <label class="input">
                                                        <input type="text" id="from_entity_business" name="from_entity_business" value="{{ $data->from_entity_business }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Address Type <span style=" color: red;">*</span></label>
                                                    <label class="input">
                                                        <input type="text" id="from_entity_address_type" name="from_entity_address_type" required value="{{ $data->from_entity_address_type }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Address</label>
                                                    <label class="input">
                                                        <input type="text" id="from_entity_address" name="from_entity_address" value="{{ $data->from_entity_address }}">
                                                    </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                    <label class="label">Address City</label>
                                                    <label class="input">
                                                        <input type="text" id="from_entity_address_city" name="from_entity_address_city" value="{{ $data->from_entity_address_city }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Address Country Code</label>
                                                    <label class="input">
                                                        <input type="text" id="from_entity_address_country_code" name="from_entity_address_country_code" value="{{ $data->from_entity_address_country_code }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Deceased Date</label>
                                                    <label class="input">
                                                        <input type="date" id="deceased_date" name="deceased_date" value="{{ $data->deceased_date }}">
                                                    </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                    <label class="label">Incorporation Country Code</label>
                                                    <label class="input">
                                                        <input type="text" id="from_entity_incorporation_country_code" name="from_entity_incorporation_country_code" value="{{ $data->from_entity_incorporation_country_code }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Director Gender <span style=" color: red;">*</span></label>
                                                    <label class="input">
                                                        <select id="from_entity_director_gender" name="from_entity_director_gender" class="select2" required>
                                                            <option value="F" {{ $data->from_entity_director_gender == 'F' ? "selected" : "" }}>Female</option>
                                                            <option value="M" {{ $data->from_entity_director_gender == 'M' ? "selected" : "" }}>Male</option>
                                                            <option value="O" {{ $data->from_entity_director_gender == 'O' ? "selected" : "" }}>Other</option>
                                                        </select>
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Director Title<span style=" color: red;">*</span></label>
                                                    <label class="input">
                                                        <select id="from_entity_director_title" name="from_entity_director_title" class="select2" >
                                                            <option value="Mr" {{ $data->from_entity_director_title == 'Mr' ? "selected" : "" }}>Mr</option>
                                                            <option value="Ms" {{ $data->from_entity_director_title == 'Ms' ? "selected" : "" }}>Ms</option>
                                                            <option value="Mrs" {{ $data->from_entity_director_title == 'Mrs' ? "selected" : "" }}>Mrs</option>
                                                            <option value="Miss" {{ $data->from_entity_director_title == 'Miss' ? "selected" : "" }}>Miss</option>
                                                            <option value="Dr" {{ $data->from_entity_director_title == 'Dr' ? "selected" : "" }}>Dr</option>
                                                        </select>
                                                    </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Director First Name <span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="from_entity_director_first_name" name="from_entity_director_first_name" required value="{{ $data->from_entity_director_first_name }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Last Name<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="from_entity_director_last_name" name="from_entity_director_last_name" required value="{{ $data->from_entity_director_last_name }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Birthdate<span style=" color: red;">*</span> </label>
                                                        <label class="input">
                                                        <input type="date" id="from_entity_director_birthdate" name="from_entity_director_birthdate" required value="{{ $data->from_entity_director_birthdate }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Director SSN<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="from_entity_director_ssn" name="from_entity_director_ssn" required value="{{ $data->from_entity_director_ssn }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Passport Number</label>
                                                        <label class="input">
                                                        <input type="text" id="from_entity_director_passport_number" name="from_entity_director_passport_number" value="{{ $data->from_entity_director_passport_number }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Passport Country</label>
                                                        <label class="input">
                                                        <input type="text" id="from_entity_director_passport_country" name="from_entity_director_passport_country" value="{{ $data->from_entity_director_passport_country }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Director Nationality</label>
                                                        <label class="input">
                                                        <input type="text" id="from_entity_director_nationality1" name="from_entity_director_nationality1" value="{{ $data->from_entity_director_nationality1 }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Residence</label>
                                                        <label class="input">
                                                        <input type="text" id="from_entity_director_residence" name="from_entity_director_residence" value="{{ $data->from_entity_director_residence }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Address Type<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="from_entity_director_address_type" name="from_entity_director_address_type" required value="{{ $data->from_entity_director_address_type }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Director Address<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="from_entity_director_address" name="from_entity_director_address" required value="{{ $data->from_entity_director_address }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director City<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="from_entity_director_city" name="from_entity_director_city" required value="{{ $data->from_entity_director_city }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Country Code<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="from_entity_director_country_code" name="from_entity_director_country_code" required value="{{ $data->from_entity_director_country_code }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Director Occupation</label>
                                                        <label class="input">
                                                        <input type="text" id="from_entity_director_occupation" name="from_entity_director_occupation" value="{{ $data->from_entity_director_occupation }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Role <span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="from_entity_director_role" name="from_entity_director_role" required value="{{ $data->from_entity_director_role }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">From Country<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="from_country" name="from_country" required value="{{ $data->from_country }}">
                                                        </label>
                                                    </section>
                                                </div>
                                            </fieldset>
                                            <footer style="background-color: #fff; border-top: transparent; padding:0px;">
                                                <a href="#s3" id="testing" class="test" onclick="show_submit('T3');changeactive('s3C', 's2B');" data-toggle="tab">
                                                    <button type="button" class="btn btn-primary next test"> {{ __('Next') }} </button>
                                                </a>
                                            </footer>
                                        </div>
                                    </div>
                                    <!--------------------------------------------------- end Tab 2-------------------------------->

                                    <!---------------------------------Tab 3---------------------------------------------------->
                                    <div class="tab-pane fade" id="s3">
                                        <div class="widget-body no-padding">
                                            <fieldset>
                                                <div class="row">
                                                    <section class="col col-4">
                                                    <label class="label">To Funds Code</label>
                                                    <label class="input">
                                                        <input type="text" id="to_funds_code" name="to_funds_code" value="{{ $data->to_funds_code }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Institution Name</label>
                                                    <label class="input">
                                                        <input type="tel" id="to_account_institution_name" name="to_account_institution_name" value="{{ $data->to_account_institution_name }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Swift</label>
                                                    <label class="input">
                                                        <input type="text" id="to_swift" name="to_swift" value="{{ $data->to_swift }}">
                                                    </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                    <label class="label">Non Bank Institution</label>
                                                    <label class="input">
                                                        <input type="text" id="to_non_bank_institution" name="to_non_bank_institution" value="{{ $data->to_non_bank_institution }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Branch</label>
                                                    <label class="input">
                                                        <input type="text" id="to_branch" name="to_branch" value="{{ $data->to_branch }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">To Account</label>
                                                    <label class="input">
                                                        <input type="text" id="to_account" name="to_account" value="{{ $data->to_account }}">
                                                    </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                    <label class="label">Currency Code</label>
                                                    <label class="input">
                                                        <input type="text" id="to_currency_code" name="to_currency_code" value="{{ $data->to_currency_code }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Personal Account Type</label>
                                                    <label class="input">
                                                        <input type="text" id="to_personal_account_type" name="to_personal_account_type" value="{{ $data->to_personal_account_type }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Entity Name<span style=" color: red;">*</span></label>
                                                    <label class="input">
                                                        <input type="text" id="to_entity_name" name="to_entity_name" reuired value="{{ $data->to_entity_name }}">
                                                    </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                    <label class="label">Incorporation Legal Form<span style=" color: red;">*</span></label>
                                                    <label class="input">
                                                        <input type="text" id="to_entity_incorporation_legal_form" name="to_entity_incorporation_legal_form" required value="{{ $data->to_entity_incorporation_legal_form }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Incorporation Number<span style=" color: red;">*</span></label>
                                                    <label class="input">
                                                        <input type="text" id="to_entity_incorporation_number" name="to_entity_incorporation_number" required value="{{ $data->to_entity_incorporation_number }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Business<span style=" color: red;">*</span></label>
                                                    <label class="input">
                                                        <input type="text" id="to_entity_business" name="to_entity_business" required value="{{ $data->to_entity_business }}">
                                                    </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Address Type<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_entity_address_type" name="to_entity_address_type" required value="{{ $data->to_entity_address_type }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Address<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_entity_address" name="to_entity_address" required value="{{ $data->to_entity_address }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">City<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_entity_city" name="to_entity_city" required value="{{ $data->to_entity_city }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Country Code<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_entity_country_code" name="to_entity_country_code" required value="{{ $data->to_entity_country_code }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Incorporation Country Code<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_entity_incorporation_country_code" name="to_entity_incorporation_country_code" required value="{{ $data->to_entity_incorporation_country_code }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Gender<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                            <select id="to_entity_director_gender" name="to_entity_director_gender" class="select2" required>
                                                                <option value="F" {{ $data->to_entity_director_gender == 'F' ? "selected" : "" }}>Female</option>
                                                                <option value="M" {{ $data->to_entity_director_gender == 'M' ? "selected" : "" }}>Male</option>
                                                                <option value="O" {{ $data->to_entity_director_gender == 'O' ? "selected" : "" }}>Other</option>
                                                            </select>
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Director Title<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                            <select id="to_entity_director_title" name="to_entity_director_title" class="select2" required>
                                                                <option value="Mr" {{ $data->to_entity_director_title == 'Mr' ? "selected" : "" }}>Mr</option>
                                                                <option value="Ms" {{ $data->to_entity_director_title == 'Ms' ? "selected" : "" }}>Ms</option>
                                                                <option value="Mrs" {{ $data->to_entity_director_title == 'Mrs' ? "selected" : "" }}>Mrs</option>
                                                                <option value="Miss" {{ $data->to_entity_director_title == 'Miss' ? "selected" : "" }}>Miss</option>
                                                                <option value="Miss" {{ $data->to_entity_director_title == 'Miss' ? "selected" : "" }}>Dr</option>
                                                            </select>
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director First Name<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_entity_director_first_name" name="to_entity_director_first_name" required value="{{ $data->to_entity_director_first_name }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Last Name<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_entity_director_last_name" name="to_entity_director_last_name" required value="{{ $data->to_entity_director_last_name }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Director Birthdate<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="date" id="to_entity_director_birthdate" name="to_entity_director_birthdate" required value="{{ $data->to_entity_director_birthdate }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director SSN<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_entity_director_ssn" name="to_entity_director_ssn" required value="{{ $data->to_entity_director_ssn }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Passport Number</label>
                                                        <label class="input">
                                                        <input type="text" id="to_entity_director_passport_number" name="to_entity_director_passport_number" value="{{ $data->to_entity_director_passport_number }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Director Passport Country</label>
                                                        <label class="input">
                                                        <input type="text" id="to_entity_director_passport_country" name="to_entity_director_passport_country" value="{{ $data->to_entity_director_passport_country }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Nationality</label>
                                                        <label class="input">
                                                        <input type="text" id="from_entity_director_nationality1" name="from_entity_director_nationality1" value="{{ $data->from_entity_director_nationality1 }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Residence<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_entity_director_residence" name="to_entity_director_residence" required value="{{ $data->to_entity_director_residence }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Director Address Type<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_entity_director_address_type" name="to_entity_director_address_type" required value="{{ $data->to_entity_director_address_type }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Address<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_entity_director_address" name="to_entity_director_address" required value="{{ $data->to_entity_director_address }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director City<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_entity_director_city" name="to_entity_director_city" required value="{{ $data->to_entity_director_city }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Director Country Code<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_entity_director_country_code" name="to_entity_director_country_code" required value="{{ $data->to_entity_director_country_code }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Occupation<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_entity_director_occupation" name="to_entity_director_occupation" required value="{{ $data->to_entity_director_occupation }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Role<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_entity_director_role" name="to_entity_director_role" required value="{{ $data->to_entity_director_role }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Status Code<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_status_code" name="to_status_code" required value="{{ $data->to_status_code }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">To Country<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_country" name="to_country" required value="{{ $data->to_country }}">
                                                        </label>
                                                    </section>
                                                </div>
                                            </fieldset>
                                            <footer style="background-color: #fff; border-top: transparent; padding:0px;">
                                                <a href="#s4" id="testing" class="test" onclick="show_submit('T4');changeactive('s4D', 's3C');" data-toggle="tab">
                                                    <button type="button" class="btn btn-primary next test"> {{ __('Next') }} </button>
                                                </a>
                                            </footer>
                                        </div>
                                    </div>
                                    <!--------------------------------------------------- end Tab 3-------------------------------->

                                    <!---------------------------------Tab 4---------------------------------------------------->
                                    <div class="tab-pane fade" id="s4">
                                        <div class="widget-body no-padding">
                                            <fieldset>
                                                <div class="row">
                                                    <section class="col col-4">
                                                    <label class="label">Gender</label>
                                                    <label class="input">
                                                        <select id="from_person_gender" name="from_person_gender" class="select2" required>
                                                            <option value="F" {{ $data->from_person_gender == 'F' ? "selected" : "" }}>Female</option>
                                                            <option value="M" {{ $data->from_person_gender == 'M' ? "selected" : "" }}>Male</option>
                                                            <option value="O" {{ $data->from_person_gender == 'O' ? "selected" : "" }}>Other</option>
                                                        </select>
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Title</label>
                                                    <label class="input">
                                                        <select id="from_person_title" name="from_person_title" class="select2" >
                                                            <option value="Mr" {{ $data->from_person_title == 'Mr' ? "selected" : "" }}>Mr</option>
                                                            <option value="Ms" {{ $data->from_person_title == 'Ms' ? "selected" : "" }}>Ms</option>
                                                            <option value="Mrs" {{ $data->from_person_title == 'Mrs' ? "selected" : "" }}>Mrs</option>
                                                            <option value="Miss" {{ $data->from_person_title == 'Miss' ? "selected" : "" }}>Miss</option>
                                                            <option value="Dr" {{ $data->from_person_title == 'Dr' ? "selected" : "" }}>Dr</option>
                                                        </select>
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">First Name</label>
                                                    <label class="input">
                                                        <input type="text" id="from_person_first_name" name="from_person_first_name" value="{{ $data->from_person_first_name }}">
                                                    </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                    <label class="label">Last Name</label>
                                                    <label class="input">
                                                        <input type="text" id="from_person_last_name" name="from_person_last_name" value="{{ $data->from_person_last_name }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Birthdate<span style=" color: red;">*</span></label>
                                                    <label class="input">
                                                        <input type="date" id="from_person_birthdate" name="from_person_birthdate" required value="{{ $data->from_person_birthdate }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">SSN</label>
                                                    <label class="input">
                                                        <input type="text" id="from_person_ssn" name="from_person_ssn" value="{{ $data->from_person_ssn }}">
                                                    </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                    <label class="label">Nationality</label>
                                                    <label class="input">
                                                        <input type="text" id="from_person_nationality1" name="from_person_nationality1" value="{{ $data->from_person_nationality1 }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Residence</label>
                                                    <label class="input">
                                                        <input type="text" id="from_person_residence" name="from_person_residence" value="{{ $data->from_person_residence }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Address Type</label>
                                                        <label class="input">
                                                        <input type="text" id="from_person_address_type" name="from_person_address_type" value="{{ $data->from_person_address_type }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Address</label>
                                                        <label class="input">
                                                        <input type="text" id="from_person_address" name="from_person_address" value="{{ $data->from_person_address }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">City</label>
                                                        <label class="input">
                                                        <input type="text" id="from_person_city" name="from_person_city" value="{{ $data->from_person_city }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Country Code</label>
                                                        <label class="input">
                                                        <input type="text" id="from_person_country_code" name="from_person_country_code" value="{{ $data->from_person_country_code }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Occupation<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="from_person_occupation" name="from_person_occupation" required value="{{ $data->from_person_occupation }}">
                                                        </label>
                                                    </section>
                                                </div>
                                                <div class="row">
                                                    <h2 style="font-size: 17px; padding-left: 15px; padding-top: 4px;">
                                                        Signatory Details
                                                    </h2>
                                                    <br>
                                                    <section class="col col-4">
                                                        <label class="label">Is Primary</label>
                                                        <label class="input">
                                                        <input type="text" id="to_signatory_is_primary" name="to_signatory_is_primary" value="{{ $data->to_signatory_is_primary }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Gender</label>
                                                        <label class="input">
                                                            <select id="to_signatory_gender" name="to_signatory_gender" class="select2" required>
                                                                <option value="F" {{ $data->to_signatory_gender == 'F' ? "selected" : "" }}>Female</option>
                                                                <option value="M" {{ $data->to_signatory_gender == 'M' ? "selected" : "" }}>Male</option>
                                                                <option value="O" {{ $data->to_signatory_gender == 'O' ? "selected" : "" }}>Other</option>
                                                            </select>                                                        
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Title</label>
                                                        <label class="input">
                                                            <select id="to_signatory_title" name="to_signatory_title" class="select2" >
                                                                <option value="Mr" {{ $data->to_signatory_title == 'Mr' ? "selected" : "" }}>Mr</option>
                                                                <option value="Ms" {{ $data->to_signatory_title == 'Ms' ? "selected" : "" }}>Ms</option>
                                                                <option value="Mrs" {{ $data->to_signatory_title == 'Mrs' ? "selected" : "" }}>Mrs</option>
                                                                <option value="Miss" {{ $data->to_signatory_title == 'Miss' ? "selected" : "" }}>Miss</option>
                                                                <option value="Dr" {{ $data->to_signatory_title == 'Dr' ? "selected" : "" }}>Dr</option>
                                                            </select>
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">First Name</label>
                                                        <label class="input">
                                                        <input type="text" id="to_signatory_first_name" name="to_signatory_first_name" value="{{ $data->to_signatory_first_name }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Last Name</label>
                                                        <label class="input">
                                                        <input type="text" id="to_signatory_last_name" name="to_signatory_last_name" value="{{ $data->to_signatory_last_name }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Birthdate <span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="date" id="to_signatory_birthdate" name="to_signatory_birthdate" required value="{{ $data->to_signatory_birthdate }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">SSN</label>
                                                        <label class="input">
                                                        <input type="text" id="to_signatory_ssn" name="to_signatory_ssn" value="{{ $data->to_signatory_ssn }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Nationality</label>
                                                        <label class="input">
                                                        <input type="text" id="to_signatory_nationality1" name="to_signatory_nationality1" value="{{ $data->to_signatory_nationality1 }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Residence</label>
                                                        <label class="input">
                                                        <input type="text" id="to_signatory_residence" name="to_signatory_residence" value="{{ $data->to_signatory_residence }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Address Type</label>
                                                        <label class="input">
                                                        <input type="text" id="to_signatory_address_type" name="to_signatory_address_type" value="{{ $data->to_signatory_address_type }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Address</label>
                                                        <label class="input">
                                                        <input type="text" id="to_signatory_address" name="to_signatory_address" value="{{ $data->to_signatory_address }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">City</label>
                                                        <label class="input">
                                                        <input type="text" id="to_signatory_city" name="to_signatory_city" value="{{ $data->to_signatory_city }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Country Code</label>
                                                        <label class="input">
                                                        <input type="text" id="to_signatory_country_code" name="to_signatory_country_code" value="{{ $data->to_signatory_country_code }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Occupation<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_signatory_occupation" name="to_signatory_occupation" required value="{{ $data->to_signatory_occupation }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Role<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_signatory_role" name="to_signatory_role" required value="{{ $data->to_signatory_role }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Report Indicator <span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="report_indicator" name="report_indicator" required value="{{ $data->report_indicator }}">
                                                        </label>
                                                    </section>
                                                </div>
                                                
                                            </fieldset>
                                            <footer>
                                                <input type="hidden" name="id" value="{{ $data->id }}">
                                                <input type="hidden" name="is_delete" value="{{ $data->is_delete }}">
                                                <input type="hidden" name="xml_gen_status" value="{{ $data->xml_gen_status }}">
                                                <input type="hidden" name="lang" value="{{ $lang }}">
                                                <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                                    {{ __('Submit') }}
                                                </button>
                                                <button type="button" class="btn btn-default" onclick="window.history.back();">
                                                    {{ __('Back') }}
                                                </button>
                                                <a href="#s1" onclick="show_submit('T1');changeactive('s1A', 's2B','s3C','s4D');" data-toggle="tab">
                                                    <button type="submit" class="btn btn-default"> {{ __('Previous Page') }} </button>
                                                </a>
                                            </footer>
                                        </div>
                                    </div>

                                    <!--------------------------------------------------- end Tab 4-------------------------------->
                        </form>
                    </div>
                    <!-- end widget content -->
                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->
        </div>
    </div>
    <x-slot name="script">
        <script>
            $(function() {
                //window.ParsleyValidator.setLocale('ta');
                $('#scenario-form').parsley();
            });
        </script>

        <script>
            $(".select2").select2();
        </script>

        <script>
            $(document).ready(function () {
                $.validator.addMethod(
                        "regex",
                        function (value, element, regexp) {
                            var re = new RegExp(regexp);
                            return this.optional(element) || re.test(value);
                        },
                        "Please enter only digits and ' - '."
                        );
                $.validator.setDefaults({
                    ignore: ":hidden:not(.selectpicker)"
                });
                $('#scenario-form').validate({
                    onfocusout: false,
                    rules: {


                        name: {
                            required: true,
                            maxlength: 50
                        },

                        status: {
                            required: true,
                        },


                    },
                    messages: {

                        name: {
                            required: "Please enter fuel type",
                            maxlength: "Maximum length is 50"
                        },
                        profile: {
                            required: "Please enter profile size",
                            maxlength: "Maximum length is 50",
                            number:"Enter only the numeric values"

                        },
                        rim: {
                            required: "Please enter rim size",
                            maxlength: "Maximum length is 50",
                            number:"Enter only the numeric values"
                        },

                        status: {
                            required: "Please the status",
                        },


                    },
                    errorElement: 'span',
                    errorPlacement: function (error, element) {
                        error.addClass('invalid-feedback');
                        element.closest('.inp-holder').append(error);
                    },
                    highlight: function (element, errorClass, validClass) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function (element, errorClass, validClass) {
                        $(element).removeClass('is-invalid');
                    },
                    invalidHandler: function (form, validator) {
                        var errors = validator.numberOfInvalids();
                        if (errors) {
                            $("#page_top_error_message").show();
                            window.scrollTo(0, 0);
                            //validator.errorList[0].element.focus();

                        }
                    }
                });

            });
        </script>
    </x-slot>
</x-app-layout>
