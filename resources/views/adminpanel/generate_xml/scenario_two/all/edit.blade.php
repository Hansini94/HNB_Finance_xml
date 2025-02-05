@section('title', 'Scenario Two List')
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
                        <a href="{{ route('scenario-two-list') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('Generate XML') }}</button>
                        </a>

                        <a href="{{ route('scenario-two-all-list') }}">
                            <button class="btn cms_top_btn top_btn_height cms_top_btn_active">{{ __('View All') }}</button>
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
                    <h2>{{ __('Scenario 2') }}</h2>
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
                        <form action="{{ route('save-scenario-two-all') }}" enctype="multipart/form-data" method="post" id="scenario-form" class="smart-form">
                            @csrf
                            @method('PUT')
                            <div class="widget-body padding-10">
                                <ul id="myTab1" class="nav nav-tabs bordered">
                                    <li class="active" id="s1A">
                                        <a href="#s1" onclick="show_submit('T1')" data-toggle="tab">{{ __('Scenario 2 Details') }} </a>
                                    </li>
                                    <li id="s2B">
                                        <a href="#s2" class="nextII" onclick="show_submit('T2')" data-toggle="tab">{{ __('From Entity Details') }} </a>
                                    </li>
                                    <li id="s3C">
                                        <a href="#s3" class="nextIII" onclick="show_submit('T3')" data-toggle="tab">{{ __('To Account Details') }} </a>
                                    </li>
                                    <li id="s4D">
                                        <a href="#s4" class="nextIV" onclick="show_submit('T4')" data-toggle="tab">{{ __('Signatory and To Person Details') }} </a>
                                    </li>
                                </ul>

                                <div id="myTabContent1" class="tab-content" style="padding: 15px !important;">

                                    <!-------------------------------Tab 1---------------------------------------------------->

                                    <div class="tab-pane fade in active" id="s1">
                                        <div class="widget-body no-padding">
                                            <fieldset>
                                                <div class="row">
                                                    <section class="col col-4">
                                                        <label class="label">{{ __('Scenario Type') }}<span style=" color: red;">*</span> </label>
                                                        <select id="scenario_type" name="scenario_type" class="select2" >
                                                        <option value="Entity" {{ $data->scenario_type == 'Entity' ? "selected" : "" }}>Entity</option>
                                                        <option value="Person" {{ $data->scenario_type == 'Person' ? "selected" : "" }}>Person</option>
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
                                                        <label class="label">Reason</label>
                                                        <label class="input">
                                                            <input type="text" id="reason" name="reason" value="{{ $data->reason }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Transaction Number <span style=" color: red;">*</span> </label>
                                                        <label class="input">
                                                            <input type="text" id="transactionnumber" name="transactionnumber" required value="{{ $data->transactionnumber }}">
                                                        </label>
                                                    </section>
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
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Transaction Description</label>
                                                        <label class="input">
                                                            <input type="text" id="transaction_description" name="transaction_description" value="{{ $data->transaction_description }}">
                                                        </label>
                                                    </section>
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
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Transmode Code</label>
                                                        <label class="input">
                                                            <input type="text" id="transmode_code" name="transmode_code" required value="{{ $data->transmode_code }}">
                                                        </label>
                                                    </section>
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
                                                        <label class="label">Institution Name</label>
                                                        <label class="input">
                                                            <input type="text" id="from_account_institution_name" name="from_account_institution_name" value="{{ $data->from_account_institution_name }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Swift</label>
                                                        <label class="input">
                                                            <input type="text" id="from_account_swift" name="from_account_swift" value="{{ $data->from_account_swift }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Non Bank Institution</label>
                                                        <label class="input">
                                                            <input type="text" id="from_account_non_bank_institution" name="from_account_non_bank_institution" value="{{ $data->from_account_non_bank_institution }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">From Account Branch</label>
                                                        <label class="input">
                                                            <input type="text" id="from_account_branch" name="from_account_branch" value="{{ $data->from_account_branch }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">From Account Number</label>
                                                        <label class="input">
                                                            <input type="text" id="from_account_number" name="from_account_number" value="{{ $data->from_account_number }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Currency Code</label>
                                                        <label class="input">
                                                            <input type="text" id="from_account_currency_code" name="from_account_currency_code" value="{{ $data->from_account_currency_code }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Personal Account Type</label>
                                                        <label class="input">
                                                            <input type="text" id="from_account_personal_account_type" name="from_account_personal_account_type" value="{{ $data->from_account_personal_account_type }}">
                                                        </label>
                                                    </section>
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
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                    <label class="label">Incorporation Number</label>
                                                    <label class="input">
                                                        <input type="text" id="from_entity_incorporation_number" name="from_entity_incorporation_number" value="{{ $data->from_entity_incorporation_number }}">
                                                    </label>
                                                    </section>
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
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                    <label class="label">Address</label>
                                                    <label class="input">
                                                        <input type="text" id="from_entity_address" name="from_entity_address" value="{{ $data->from_entity_address }}">
                                                    </label>
                                                    </section>
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
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                    <label class="label">Incorporation Country Code</label>
                                                    <label class="input">
                                                        <input type="text" id="from_entity_incorporation_country_code" name="from_entity_incorporation_country_code" value="{{ $data->from_entity_incorporation_country_code }}">
                                                    </label>
                                                    </section>
                                                    @if (!empty($directors))

                                                    @foreach ($directors as $index => $director)
                                                    @if($director->from_or_to == 'from')
                                                    <input type="hidden"
                                                        name="directors[{{ $index }}][id]"
                                                        value="{{ $director->id }}">
                                                    <input type="hidden"
                                                        name="directors[{{ $index }}][scenario_type]"
                                                        value="{{ $director->scenario_type }}">
                                                    <input type="hidden"
                                                        name="directors[{{ $index }}][from_or_to]"
                                                        value="{{ $director->from_or_to }}">
                                                    <section class="col col-4">
                                                        <label class="label">Director Gender <span
                                                                style="color: red;">*</span></label>
                                                        <label class="input">
                                                            <select id="gender_{{ $index }}"
                                                                name="directors[{{ $index }}][gender]"
                                                                class="select2">
                                                                <option value="">Select Option</option>
                                                                <option value="F"
                                                                    {{ $director->gender == 'F' ? 'selected' : '' }}>
                                                                    Female</option>
                                                                <option value="M"
                                                                    {{ $director->gender == 'M' ? 'selected' : '' }}>
                                                                    Male</option>
                                                                <option value="O"
                                                                    {{ $director->gender == 'O' ? 'selected' : '' }}>
                                                                    Other</option>
                                                            </select>
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Title<span
                                                                style="color: red;">*</span></label>
                                                        <label class="input">
                                                            <select id="title_{{ $index }}"
                                                                name="directors[{{ $index }}][title]"
                                                                class="select2">
                                                                <option value="">Select Option</option>
                                                                <option value="Mr"
                                                                    {{ $director->title == 'Mr' ? 'selected' : '' }}>
                                                                    Mr</option>
                                                                <option value="Ms"
                                                                    {{ $director->title == 'Ms' ? 'selected' : '' }}>
                                                                    Ms</option>
                                                                <option value="Mrs"
                                                                    {{ $director->title == 'Mrs' ? 'selected' : '' }}>
                                                                    Mrs</option>
                                                                <option value="Miss"
                                                                    {{ $director->title == 'Miss' ? 'selected' : '' }}>
                                                                    Miss</option>
                                                                <option value="Dr"
                                                                    {{ $director->title == 'Dr' ? 'selected' : '' }}>
                                                                    Dr</option>
                                                            </select>
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director First Name <span
                                                                style="color: red;">*</span></label>
                                                        <label class="input">
                                                            <input type="text"
                                                                id="first_name_{{ $index }}"
                                                                name="directors[{{ $index }}][first_name]"
                                                                required value="{{ $director->first_name }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Last Name<span
                                                                style="color: red;">*</span></label>
                                                        <label class="input">
                                                            <input type="text"
                                                                id="last_name_{{ $index }}"
                                                                name="directors[{{ $index }}][last_name]"
                                                                required value="{{ $director->last_name }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Birthdate<span
                                                                style="color: red;">*</span></label>
                                                        <label class="input">
                                                            <input type="date"
                                                                id="birthdate_{{ $index }}"
                                                                name="directors[{{ $index }}][birthdate]"
                                                                required value="{{ $director->birthdate }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director SSN<span
                                                                style="color: red;">*</span></label>
                                                        <label class="input">
                                                            <input type="text" id="ssn_{{ $index }}"
                                                                name="directors[{{ $index }}][ssn]"
                                                                required value="{{ $director->ssn }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Passport Number</label>
                                                        <label class="input">
                                                            <input type="text"
                                                                id="passport_number_{{ $index }}"
                                                                name="directors[{{ $index }}][passport_number]"
                                                                value="{{ $director->passport_number }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Passport Country</label>
                                                        <label class="input">
                                                            <input type="text"
                                                                id="passport_country_{{ $index }}"
                                                                name="directors[{{ $index }}][passport_country]"
                                                                value="{{ $director->passport_country }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Nationality</label>
                                                        <label class="input">
                                                            <input type="text"
                                                                id="nationality1_{{ $index }}"
                                                                name="directors[{{ $index }}][nationality1]"
                                                                value="{{ $director->nationality1 }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Residence</label>
                                                        <label class="input">
                                                            <input type="text"
                                                                id="residence_{{ $index }}"
                                                                name="directors[{{ $index }}][residence]"
                                                                value="{{ $director->residence }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Address Type<span
                                                                style="color: red;">*</span></label>
                                                        <label class="input">
                                                            <input type="text"
                                                                id="address_type_{{ $index }}"
                                                                name="directors[{{ $index }}][address_type]"
                                                                required value="{{ $director->address_type }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Address<span
                                                                style="color: red;">*</span></label>
                                                        <label class="input">
                                                            <input type="text"
                                                                id="address_{{ $index }}"
                                                                name="directors[{{ $index }}][address]"
                                                                required value="{{ $director->address }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director City<span
                                                                style="color: red;">*</span></label>
                                                        <label class="input">
                                                            <input type="text" id="city_{{ $index }}"
                                                                name="directors[{{ $index }}][city]"
                                                                required value="{{ $director->city }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Country Code<span
                                                                style="color: red;">*</span></label>
                                                        <label class="input">
                                                            <input type="text"
                                                                id="country_code_{{ $index }}"
                                                                name="directors[{{ $index }}][country_code]"
                                                                required value="{{ $director->country_code }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Occupation</label>
                                                        <label class="input">
                                                            <input type="text"
                                                                id="occupation_{{ $index }}"
                                                                name="directors[{{ $index }}][occupation]"
                                                                value="{{ $director->occupation }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Director Role <span
                                                                style="color: red;">*</span></label>
                                                        <label class="input">
                                                            <input type="text" id="role_{{ $index }}"
                                                                name="directors[{{ $index }}][role]"
                                                                required value="{{ $director->role }}">
                                                        </label>
                                                    </section>
                                                    @endif
                                                @endforeach
                                                    @endif
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">From Account Status Code<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="from_account_status_code" name="from_account_status_code" required value="{{ $data->from_account_status_code }}">
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
                                                        <input type="text" id="to_entity_address_city" name="to_entity_address_city" required value="{{ $data->to_entity_address_city }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Country Code<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_entity_address_country_code" name="to_entity_address_country_code" required value="{{ $data->to_entity_address_country_code }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Incorporation Country Code<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_entity_incorporation_country_code" name="to_entity_incorporation_country_code" required value="{{ $data->to_entity_incorporation_country_code }}">
                                                        </label>
                                                    </section>
                                                    @if (!empty($directors))
                                                    @foreach ($directors as $index => $director)
                                                        @if($director->from_or_to == 'to')
                                                        <input type="hidden"
                                                            name="directors[{{ $index }}][id]"
                                                            value="{{ $director->id }}">
                                                        <input type="hidden"
                                                            name="directors[{{ $index }}][scenario_type]"
                                                            value="{{ $director->scenario_type }}">
                                                        <input type="hidden"
                                                            name="directors[{{ $index }}][from_or_to]"
                                                            value="{{ $director->from_or_to }}">
                                                        <section class="col col-4">
                                                            <label class="label">Director Gender <span
                                                                    style="color: red;">*</span></label>
                                                            <label class="input">
                                                                <select id="gender_{{ $index }}"
                                                                    name="directors[{{ $index }}][gender]"
                                                                    class="select2">
                                                                    <option value="">Select Option</option>
                                                                    <option value="F"
                                                                        {{ $director->gender == 'F' ? 'selected' : '' }}>
                                                                        Female</option>
                                                                    <option value="M"
                                                                        {{ $director->gender == 'M' ? 'selected' : '' }}>
                                                                        Male</option>
                                                                    <option value="O"
                                                                        {{ $director->gender == 'O' ? 'selected' : '' }}>
                                                                        Other</option>
                                                                </select>
                                                            </label>
                                                        </section>
                                                        <section class="col col-4">
                                                            <label class="label">Director Title<span
                                                                    style="color: red;">*</span></label>
                                                            <label class="input">
                                                                <select id="title_{{ $index }}"
                                                                    name="directors[{{ $index }}][title]"
                                                                    class="select2">
                                                                    <option value="">Select Option</option>
                                                                    <option value="Mr"
                                                                        {{ $director->title == 'Mr' ? 'selected' : '' }}>
                                                                        Mr</option>
                                                                    <option value="Ms"
                                                                        {{ $director->title == 'Ms' ? 'selected' : '' }}>
                                                                        Ms</option>
                                                                    <option value="Mrs"
                                                                        {{ $director->title == 'Mrs' ? 'selected' : '' }}>
                                                                        Mrs</option>
                                                                    <option value="Miss"
                                                                        {{ $director->title == 'Miss' ? 'selected' : '' }}>
                                                                        Miss</option>
                                                                    <option value="Dr"
                                                                        {{ $director->title == 'Dr' ? 'selected' : '' }}>
                                                                        Dr</option>
                                                                </select>
                                                            </label>
                                                        </section>
                                                        <section class="col col-4">
                                                            <label class="label">Director First Name <span
                                                                    style="color: red;">*</span></label>
                                                            <label class="input">
                                                                <input type="text"
                                                                    id="first_name_{{ $index }}"
                                                                    name="directors[{{ $index }}][first_name]"
                                                                    required value="{{ $director->first_name }}">
                                                            </label>
                                                        </section>
                                                        <section class="col col-4">
                                                            <label class="label">Director Last Name<span
                                                                    style="color: red;">*</span></label>
                                                            <label class="input">
                                                                <input type="text"
                                                                    id="last_name_{{ $index }}"
                                                                    name="directors[{{ $index }}][last_name]"
                                                                    required value="{{ $director->last_name }}">
                                                            </label>
                                                        </section>
                                                        <section class="col col-4">
                                                            <label class="label">Director Birthdate<span
                                                                    style="color: red;">*</span></label>
                                                            <label class="input">
                                                                <input type="date"
                                                                    id="birthdate_{{ $index }}"
                                                                    name="directors[{{ $index }}][birthdate]"
                                                                    required value="{{ $director->birthdate }}">
                                                            </label>
                                                        </section>
                                                        <section class="col col-4">
                                                            <label class="label">Director SSN<span
                                                                    style="color: red;">*</span></label>
                                                            <label class="input">
                                                                <input type="text" id="ssn_{{ $index }}"
                                                                    name="directors[{{ $index }}][ssn]"
                                                                    required value="{{ $director->ssn }}">
                                                            </label>
                                                        </section>
                                                        <section class="col col-4">
                                                            <label class="label">Director Passport Number</label>
                                                            <label class="input">
                                                                <input type="text"
                                                                    id="passport_number_{{ $index }}"
                                                                    name="directors[{{ $index }}][passport_number]"
                                                                    value="{{ $director->passport_number }}">
                                                            </label>
                                                        </section>
                                                        <section class="col col-4">
                                                            <label class="label">Director Passport Country</label>
                                                            <label class="input">
                                                                <input type="text"
                                                                    id="passport_country_{{ $index }}"
                                                                    name="directors[{{ $index }}][passport_country]"
                                                                    value="{{ $director->passport_country }}">
                                                            </label>
                                                        </section>
                                                        <section class="col col-4">
                                                            <label class="label">Director Nationality</label>
                                                            <label class="input">
                                                                <input type="text"
                                                                    id="nationality1_{{ $index }}"
                                                                    name="directors[{{ $index }}][nationality1]"
                                                                    value="{{ $director->nationality1 }}">
                                                            </label>
                                                        </section>
                                                        <section class="col col-4">
                                                            <label class="label">Director Residence</label>
                                                            <label class="input">
                                                                <input type="text"
                                                                    id="residence_{{ $index }}"
                                                                    name="directors[{{ $index }}][residence]"
                                                                    value="{{ $director->residence }}">
                                                            </label>
                                                        </section>
                                                        <section class="col col-4">
                                                            <label class="label">Director Address Type<span
                                                                    style="color: red;">*</span></label>
                                                            <label class="input">
                                                                <input type="text"
                                                                    id="address_type_{{ $index }}"
                                                                    name="directors[{{ $index }}][address_type]"
                                                                    required value="{{ $director->address_type }}">
                                                            </label>
                                                        </section>
                                                        <section class="col col-4">
                                                            <label class="label">Director Address<span
                                                                    style="color: red;">*</span></label>
                                                            <label class="input">
                                                                <input type="text"
                                                                    id="address_{{ $index }}"
                                                                    name="directors[{{ $index }}][address]"
                                                                    required value="{{ $director->address }}">
                                                            </label>
                                                        </section>
                                                        <section class="col col-4">
                                                            <label class="label">Director City<span
                                                                    style="color: red;">*</span></label>
                                                            <label class="input">
                                                                <input type="text" id="city_{{ $index }}"
                                                                    name="directors[{{ $index }}][city]"
                                                                    required value="{{ $director->city }}">
                                                            </label>
                                                        </section>
                                                        <section class="col col-4">
                                                            <label class="label">Director Country Code<span
                                                                    style="color: red;">*</span></label>
                                                            <label class="input">
                                                                <input type="text"
                                                                    id="country_code_{{ $index }}"
                                                                    name="directors[{{ $index }}][country_code]"
                                                                    required value="{{ $director->country_code }}">
                                                            </label>
                                                        </section>
                                                        <section class="col col-4">
                                                            <label class="label">Director Occupation</label>
                                                            <label class="input">
                                                                <input type="text"
                                                                    id="occupation_{{ $index }}"
                                                                    name="directors[{{ $index }}][occupation]"
                                                                    value="{{ $director->occupation }}">
                                                            </label>
                                                        </section>
                                                        <section class="col col-4">
                                                            <label class="label">Director Role <span
                                                                    style="color: red;">*</span></label>
                                                            <label class="input">
                                                                <input type="text" id="role_{{ $index }}"
                                                                    name="directors[{{ $index }}][role]"
                                                                    required value="{{ $director->role }}">
                                                            </label>
                                                        </section>
                                                        @endif
                                                    @endforeach
                                                    @endif
                                                    <div class="clearfix"></div>
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
                                                    <h2 style="font-size: 17px; padding-left: 15px; padding-top: 4px;">
                                                        To Person Details
                                                    </h2>
                                                    <br>
                                                    <section class="col col-4">
                                                    <label class="label">Gender</label>
                                                    <label class="input">
                                                        <select id="to_person_gender" name="to_person_gender" class="select2" required>
                                                            <option value="F" {{ $data->to_person_gender == 'F' ? "selected" : "" }}>Female</option>
                                                            <option value="M" {{ $data->to_person_gender == 'M' ? "selected" : "" }}>Male</option>
                                                            <option value="O" {{ $data->to_person_gender == 'O' ? "selected" : "" }}>Other</option>
                                                        </select>
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Title</label>
                                                    <label class="input">
                                                        <select id="to_person_title" name="to_person_title" class="select2" >
                                                            <option value="Mr" {{ $data->to_person_title == 'Mr' ? "selected" : "" }}>Mr</option>
                                                            <option value="Ms" {{ $data->to_person_title == 'Ms' ? "selected" : "" }}>Ms</option>
                                                            <option value="Mrs" {{ $data->to_person_title == 'Mrs' ? "selected" : "" }}>Mrs</option>
                                                            <option value="Miss" {{ $data->to_person_title == 'Miss' ? "selected" : "" }}>Miss</option>
                                                            <option value="Dr" {{ $data->to_person_title == 'Dr' ? "selected" : "" }}>Dr</option>
                                                        </select>
                                                    </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                    <label class="label">First Name</label>
                                                    <label class="input">
                                                        <input type="text" id="to_person_first_name" name="to_person_first_name" value="{{ $data->to_person_first_name }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Last Name</label>
                                                    <label class="input">
                                                        <input type="text" id="to_person_last_name" name="to_person_last_name" value="{{ $data->to_person_last_name }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Birthdate<span style=" color: red;">*</span></label>
                                                    <label class="input">
                                                        <input type="date" id="to_person_birthdate" name="to_person_birthdate" required value="{{ $data->to_person_birthdate }}">
                                                    </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                    <label class="label">SSN</label>
                                                    <label class="input">
                                                        <input type="text" id="to_person_ssn" name="to_person_ssn" value="{{ $data->to_person_ssn }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Nationality</label>
                                                    <label class="input">
                                                        <input type="text" id="to_person_nationality1" name="to_person_nationality1" value="{{ $data->to_person_nationality1 }}">
                                                    </label>
                                                    </section>
                                                    <section class="col col-4">
                                                    <label class="label">Residence</label>
                                                    <label class="input">
                                                        <input type="text" id="to_person_residence" name="to_person_residence" value="{{ $data->to_person_residence }}">
                                                    </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Address Type</label>
                                                        <label class="input">
                                                        <input type="text" id="to_person_address_type" name="to_person_address_type" value="{{ $data->to_person_address_type }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Address</label>
                                                        <label class="input">
                                                        <input type="text" id="to_person_address" name="to_person_address" value="{{ $data->to_person_address }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">City</label>
                                                        <label class="input">
                                                        <input type="text" id="to_person_city" name="to_person_city" value="{{ $data->to_person_city }}">
                                                        </label>
                                                    </section>
                                                    <div class="clearfix"></div>
                                                    <section class="col col-4">
                                                        <label class="label">Country Code</label>
                                                        <label class="input">
                                                        <input type="text" id="to_person_country_code" name="to_person_country_code" value="{{ $data->to_person_country_code }}">
                                                        </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label class="label">Occupation<span style=" color: red;">*</span></label>
                                                        <label class="input">
                                                        <input type="text" id="to_person_occupation" name="to_person_occupation" required value="{{ $data->to_person_occupation }}">
                                                        </label>
                                                    </section>
                                                </div>
                                                <div class="row">
                                                    @if (!empty($signatories))
                                                        @foreach ($signatories as $index => $signatory)
                                                            <h2
                                                                style="font-size: 17px; padding-left: 15px; padding-top: 4px;">
                                                                Signatory Details : {{ $signatory->entity_type }}
                                                            </h2>
                                                            <div class="clearfix"></div>
                                                            <br>
                                                            <section class="col col-4">
                                                                <label class="label">Is Primary</label>
                                                                <label class="input">
                                                                    <input type="text"
                                                                        id="is_primary_{{ $index }}"
                                                                        name="signatories[{{ $index }}][title]"
                                                                        value="{{ $signatory->is_primary }}">
                                                                </label>
                                                            </section>
                                                            <section class="col col-4">
                                                                <label class="label">Gender</label>
                                                                <label class="input">
                                                                    <select id="gender_{{ $index }}"
                                                                        name="signatories[{{ $index }}][gender]"
                                                                        class="select2" required>
                                                                        <option value="F"
                                                                            {{ $signatory->gender == 'F' ? 'selected' : '' }}>
                                                                            Female</option>
                                                                        <option value="M"
                                                                            {{ $signatory->gender == 'M' ? 'selected' : '' }}>
                                                                            Male</option>
                                                                        <option value="O"
                                                                            {{ $signatory->gender == 'O' ? 'selected' : '' }}>
                                                                            Other</option>
                                                                    </select>
                                                                </label>
                                                            </section>
                                                            <section class="col col-4">
                                                                <label class="label">Title</label>
                                                                <label class="input">
                                                                    <select id="title_{{ $index }}"
                                                                        name="signatories[{{ $index }}][title]"
                                                                        class="select2">
                                                                        <option value="Mr"
                                                                            {{ $signatory->title == 'Mr' ? 'selected' : '' }}>
                                                                            Mr</option>
                                                                        <option value="Ms"
                                                                            {{ $signatory->title == 'Ms' ? 'selected' : '' }}>
                                                                            Ms</option>
                                                                        <option value="Mrs"
                                                                            {{ $signatory->title == 'Mrs' ? 'selected' : '' }}>
                                                                            Mrs</option>
                                                                        <option value="Miss"
                                                                            {{ $signatory->title == 'Miss' ? 'selected' : '' }}>
                                                                            Miss</option>
                                                                        <option value="Dr"
                                                                            {{ $signatory->title == 'Dr' ? 'selected' : '' }}>
                                                                            Dr</option>
                                                                    </select>
                                                                </label>
                                                            </section>
                                                            <div class="clearfix"></div>
                                                            <section class="col col-4">
                                                                <label class="label">First Name</label>
                                                                <label class="input">
                                                                    <input type="text"
                                                                        id="first_name_{{ $index }}"
                                                                        name="signatories[{{ $index }}][first_name]"
                                                                        value="{{ $signatory->first_name }}">
                                                                </label>
                                                            </section>
                                                            <section class="col col-4">
                                                                <label class="label">Last Name</label>
                                                                <label class="input">
                                                                    <input type="text"
                                                                        id="last_name_{{ $index }}"
                                                                        name="signatories[{{ $index }}][last_name]"
                                                                        value="{{ $signatory->last_name }}">
                                                                </label>
                                                            </section>
                                                            <section class="col col-4">
                                                                <label class="label">Birthdate <span
                                                                        style=" color: red;">*</span></label>
                                                                <label class="input">
                                                                    <input type="date"
                                                                        id="birthdate_{{ $index }}"
                                                                        name="signatories[{{ $index }}][birthdate]"
                                                                        required value="{{ $signatory->birthdate }}">
                                                                </label>
                                                            </section>
                                                            <div class="clearfix"></div>
                                                            <section class="col col-4">
                                                                <label class="label">SSN</label>
                                                                <label class="input">
                                                                    <input type="text"
                                                                        id="ssn_{{ $index }}"
                                                                        name="signatories[{{ $index }}][ssn]"
                                                                        value="{{ $signatory->ssn }}">
                                                                </label>
                                                            </section>
                                                            <section class="col col-4">
                                                                <label class="label">Nationality</label>
                                                                <label class="input">
                                                                    <input type="text"
                                                                        id="nationality1_{{ $index }}"
                                                                        name="signatories[{{ $index }}][nationality1]"
                                                                        value="{{ $signatory->nationality1 }}">
                                                                </label>
                                                            </section>
                                                            <section class="col col-4">
                                                                <label class="label">Residence</label>
                                                                <label class="input">
                                                                    <input type="text"
                                                                        id="residence_{{ $index }}"
                                                                        name="signatories[{{ $index }}][residence]"
                                                                        value="{{ $signatory->residence }}">
                                                                </label>
                                                            </section>
                                                            <div class="clearfix"></div>
                                                            <section class="col col-4">
                                                                <label class="label">Address Type</label>
                                                                <label class="input">
                                                                    <input type="text"
                                                                        id="address_type_{{ $index }}"
                                                                        name="signatories[{{ $index }}][address_type]"
                                                                        value="{{ $signatory->address_type }}">
                                                                </label>
                                                            </section>
                                                            <section class="col col-4">
                                                                <label class="label">Address</label>
                                                                <label class="input">
                                                                    <input type="text"
                                                                        id="address_{{ $index }}"
                                                                        name="signatories[{{ $index }}][address]"
                                                                        value="{{ $signatory->address }}">
                                                                </label>
                                                            </section>
                                                            <section class="col col-4">
                                                                <label class="label">City</label>
                                                                <label class="input">
                                                                    <input type="text"
                                                                        id="city_{{ $index }}"
                                                                        name="signatories[{{ $index }}][city]"
                                                                        value="{{ $signatory->city }}">
                                                                </label>
                                                            </section>
                                                            <div class="clearfix"></div>
                                                            <section class="col col-4">
                                                                <label class="label">Country Code</label>
                                                                <label class="input">
                                                                    <input type="text"
                                                                        id="country_code_{{ $index }}"
                                                                        name="signatories[{{ $index }}][country_code]"
                                                                        value="{{ $signatory->country_code }}">
                                                                </label>
                                                            </section>
                                                            <section class="col col-4">
                                                                <label class="label">Occupation<span
                                                                        style=" color: red;">*</span></label>
                                                                <label class="input">
                                                                    <input type="text"
                                                                        id="occupation_{{ $index }}"
                                                                        name="signatories[{{ $index }}][occupation]"
                                                                        required
                                                                        value="{{ $signatory->occupation }}">
                                                                </label>
                                                            </section>
                                                            <section class="col col-4">
                                                                <label class="label">Role<span
                                                                        style=" color: red;">*</span></label>
                                                                <label class="input">
                                                                    <input type="text"
                                                                        id="role_{{ $index }}"
                                                                        name="signatories[{{ $index }}][role]"
                                                                        required value="{{ $signatory->role }}">
                                                                </label>
                                                            </section>
                                                            <input type="hidden" id="id_{{ $index }}"
                                                                name="signatories[{{ $index }}][id]" required
                                                                value="{{ $signatory->id }}">
                                                        @endforeach
                                                    @endif
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
