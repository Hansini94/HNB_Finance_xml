@section('title', 'Profile')
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
                        <a href="{{ route('users.index') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('user.add_new') }}</button>
                        </a>

                        <a href="{{ route('users-list') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('user.view_all') }}</button>
                        </a>
                    </div>
                </div>
                <!-- <div class="col-lg-8">
                    <ul id="sparks" class="">
                        <ul id="sparks" class="">
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 22px 15px; min-width: auto;">
                                <a href="{{ route('users.index') }}">
                                    <h5>{{ __('user.add_new') }}</h5>
                                </a>
                            </li>
                            <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 10px; min-width: auto;">
                                <a href="{{ route('users-list') }}">
                                    <h5>{{ __('user.view_all') }}<span class="txt-color-blue" style="text-align: center"><i class=""></i></span></h5>
                                </a>
                            </li>
                        </ul>
                    </ul>
                </div> -->
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
                    <h2>{{ __('user.title') }}</h2>
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
                        <form action="{{ route('save-user') }}" enctype="multipart/form-data" method="post" id="user-form" class="smart-form">
                            @csrf
                            @method('PUT')
                            <fieldset>
                                <div class="row">
                                        <section class="col col-4">
                                          <label class="label">{{ __('Gender') }}</label>
                                            <select id="gender" name="gender" class="select2" >
                                              <option value="Male" {{ $user->gender == 'Male' ? "selected" : "" }}>Male</option>
                                              <option value="Female" {{ $user->gender == 'Female' ? "selected" : "" }}>Female</option>
                                              <option value="Other" {{ $user->gender == 'Other' ? "selected" : "" }}>Other</option>
                                            </select>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">{{ __('Title') }}</label>
                                            <select id="title" name="title" class="select2" >
                                              <option value="Mr" {{ $user->title == 'Mr' ? "selected" : "" }}>Mr</option>
                                              <option value="Ms" {{ $user->title == 'Ms' ? "selected" : "" }}>Ms</option>
                                              <option value="Mrs" {{ $user->title == 'Mrs' ? "selected" : "" }}>Mrs</option>
                                              <option value="Miss" {{ $user->title == 'Miss' ? "selected" : "" }}>Miss</option>
                                              <option value="Dr" {{ $user->title == 'Dr' ? "selected" : "" }}>Dr</option>
                                            </select>
                                        </section>
                                        <div class="clearfix"></div>
                                        <section class="col col-4">
                                          <label class="label">First Name<span style=" color: red;">*</span> </label>
                                          <label class="input">
                                            <input type="text" id="first_name" name="first_name" required value="{{ $user->first_name }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">Middle Name</label>
                                          <label class="input">
                                            <input type="text" id="middle_name" name="middle_name"  value="{{ $user->middle_name }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">Last Name<span style=" color: red;">*</span> </label>
                                          <label class="input">
                                            <input type="text" id="last_name" name="last_name" required value="{{ $user->last_name }}">
                                          </label>
                                        </section>
                                        <div class="clearfix"></div>
                                        <section class="col col-4">
                                          <label class="label">Prefix</label>
                                          <label class="input">
                                            <input type="text" id="prefix" name="prefix" value="{{ $user->prefix }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">Birthdate</label>
                                          <label class="input">
                                            <input type="date" id="birthdate" name="birthdate" value="{{ $user->birthdate }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">Birth Place</label>
                                          <label class="input">
                                            <input type="text" id="birth_place" name="birth_place" value="{{ $user->birth_place }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">Mothers Name</label>
                                          <label class="input">
                                            <input type="text" id="mothers_name" name="mothers_name" value="{{ $user->mothers_name }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">Alias</label>
                                          <label class="input">
                                            <input type="text" id="alias" name="alias" value="{{ $user->alias }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">SSN</label>
                                          <label class="input">
                                            <input type="text" id="ssn" name="ssn" value="{{ $user->ssn }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">Passport Number</label>
                                          <label class="input">
                                            <input type="text" id="passport_number" name="passport_number" value="{{ $user->passport_number }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">Passport Country</label>
                                          <label class="input">
                                            <input type="text" id="passport_country" name="passport_country" value="{{ $user->passport_country }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">ID Number</label>
                                          <label class="input">
                                            <input type="text" id="id_number" name="id_number" value="{{ $user->id_number }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">Nationality 1</label>
                                          <label class="input">
                                            <input type="text" id="nationality1" name="nationality1" value="{{ $user->nationality1 }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">Nationality 2</label>
                                          <label class="input">
                                            <input type="text" id="nationality2" name="nationality2" value="{{ $user->nationality2 }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">Nationality 3</label>
                                          <label class="input">
                                            <input type="text" id="nationality3" name="nationality3" value="{{ $user->nationality3 }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">Residence</label>
                                          <label class="input">
                                            <input type="text" id="residence" name="residence" value="{{ $user->residence }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">Contact No</label>
                                          <label class="input">
                                            <input type="tel" id="phones" name="phones" value="{{ $user->phones }}">
                                          </label>
                                        </section>
                                        <div class="clearfix"></div>
                                        <section class="col col-4">
                                          <label class="label">Address Type</label>
                                          <label class="input">
                                            <input type="text" id="address_type" name="address_type" value="{{ $user->address_type }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">Address</label>
                                          <label class="input">
                                            <input type="text" id="address" name="address" value="{{ $user->address }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">City</label>
                                          <label class="input">
                                            <input type="text" id="city" name="city" value="{{ $user->city }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">Country Code</label>
                                          <label class="input">
                                            <input type="text" id="country_code" name="country_code" value="{{ $user->country_code }}">
                                          </label>
                                        </section>
                                        <div class="clearfix"></div>
                                        <section class="col col-4">
                                          <label class="label">Occupation</label>
                                          <label class="input">
                                            <input type="text" id="occupation" name="occupation" value="{{ $user->occupation }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">Deceased</label>
                                          <label class="input">
                                            <input type="text" id="deceased" name="deceased" value="{{ $user->deceased }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">Deceased Date</label>
                                          <label class="input">
                                            <input type="date" id="deceased_date" name="deceased_date" value="{{ $user->deceased_date }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">Tax Number</label>
                                          <label class="input">
                                            <input type="text" id="tax_number" name="tax_number" value="{{ $user->tax_number }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">Tax Registration Number</label>
                                          <label class="input">
                                            <input type="text" id="tax_reg_numebr" name="tax_reg_numebr" value="{{ $user->tax_reg_number }}">
                                          </label>
                                        </section>
                                        <section class="col col-4">
                                          <label class="label">Source of Wealth</label>
                                          <label class="input">
                                            <input type="text" id="source_of_wealth" name="source_of_wealth" value="{{ $user->source_of_wealth }}">
                                          </label>
                                        </section>

                                    <section class="col col-4">
                                        <label class="label">{{ __('user.email') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="text" id="email" name="email" required value="{{ $user->email }}">
                                        </label>
                                    </section>


                                    <?php
                                    $uval = "";
                                    // foreach ($userRole as $rol => $uval) {
                                        $uval = $userRole->name;
                                    // }
                                    ?>

                                    <section class="col col-4">
                                        <label class="label">{{ __('user.role') }} <span style=" color: red;">*</span></label>
                                        {{-- <label class="select"> --}}
                                            <select id="roles" name="roles" class="select2" required>
                                                <option value=""></option>
                                                @foreach ($roles as $x => $val)
                                                <option value="{{ $val }}" {{ $uval == $val ? 'selected' : ''}}>{{ $val }}</option>
                                                @endforeach
                                            </select>
                                            <i></i>
                                        {{-- </label> --}}
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col-lg-12" style="margin-top: 2%; margin-left:16px;">
                                        <label class="label">{{ __('user.change_password') }}
                                        <button id="changepwyes" type="button" style="margin-left: 2%; width: 90px; background-color: #963c2c; color: #e7e7e7;" class="btn btn-default"> {{ __('action.yes') }} </button>
                                        <button id="changepwno" type="button" style="margin-left: 2%; width: 90px; background-color: #963c2c; color: #e7e7e7;" class="btn btn-default"> {{ __('action.no') }} </button></label>
                                    </section>
                                </div>
                                <div class="row" id="changepassword" style="display: none;">
                                    <section class="col col-4">
                                        <label class="label">{{ __('user.password') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="password" id="password" name="password" value="" minlength="6" class="password" disabled>
                                        </label>
                                    </section>

                                    <section class="col col-4">
                                        <label class="label">{{ __('user.confirmpassword') }} <span style=" color: red;">*</span> </label>
                                        <label class="input">
                                            <input type="password" id="confirm-password" name="confirm-password" value="" data-parsley-equalto="#password" class="confirmpassword" disabled>
                                        </label>
                                    </section>
                                </div>

                            </fieldset>
                            <footer>
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <input type="hidden" name="lang" value="{{ $lang }}">
                                <button id="button1id" name="button1id" type="submit" class="btn btn-primary">
                                    {{ __('user.submit') }}
                                </button>
                                <button type="button" class="btn btn-default" onclick="window.history.back();">
                                    {{ __('user.back') }}
                                </button>
                            </footer>
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
                $('#user-form').parsley();
            });
        </script>

        <script>
            $(".select2").select2();
        </script>

        <script>
            $(document).ready(function () {
                $('#changepwyes').click(function(){ // click to
                    $('#changepassword').show(); // removing disabled in this class
                    $('.password').attr('disabled',false); // removing disabled in this class
                    $('.confirmpassword').attr('disabled',false); // removing disabled in this class
                });

                $('#changepwno').click(function(){ // click to
                    $("#changepassword").hide(); // removing disabled in this class
                    $("#confirm-password").val('');
                    $("#password").val('');
                });

                $('#mobileyes').click(function(){ // click to
                    $("#mobilearea").show();
                    // $('.mobile_no').attr('disabled',false); // removing disabled in this class
                });

                $('#mobileno').click(function(){ // click to
                    $("#mobilearea").hide();
                    $("#mobile_no").val('');
                    // $('.mobile_no').attr('disabled',false); // removing disabled in this class
                });

            });
        </script>
    </x-slot>
</x-app-layout>
