@section('title', 'Profile')
<x-app-layout>
  <x-slot name="header">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
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
                            <button class="btn cms_top_btn top_btn_height cms_top_btn_active">{{ __('user.add_new') }}</button>
                        </a>

                        <a href="{{ route('users-list') }}">
                            <button class="btn cms_top_btn top_btn_height ">{{ __('user.view_all') }}</button>
                        </a>
                    </div>
                </div>
        <!-- <div class="col-lg-8">
          <ul id="sparks" class="">
            <ul id="sparks" class="">
              <li class="sparks-info sparks-info_active" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 22px 15px; min-width: auto;">
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
            <form action="{{ route('users.store') }}" enctype="multipart/form-data" method="post" id="user-form" class="smart-form">
              @csrf
              <fieldset>
                  <input type="hidden" name="lang" value="{{ $lang }}">
                <div class="row">
                  <section class="col col-4">
                    <label class="label">{{ __('Gender') }}</label>
                      <select id="gender" name="gender" class="select2" >
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                      </select>
                  </section>
                  <section class="col col-4">
                    <label class="label">{{ __('Title') }}</label>
                      <select id="title" name="title" class="select2" >
                        <option value="Mr">Mr</option>
                        <option value="Ms">Ms</option>
                        <option value="Mrs">Mrs</option>
                        <option value="Miss">Miss</option>
                        <option value="Dr">Dr</option>
                      </select>
                  </section>
                </div>
                <div class="row">
                  <section class="col col-4">
                    <label class="label">First Name<span style=" color: red;">*</span> </label>
                    <label class="input">
                      <input type="text" id="first_name" name="first_name" required value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">Middle Name</label>
                    <label class="input">
                      <input type="text" id="middle_name" name="middle_name"  value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">Last Name<span style=" color: red;">*</span> </label>
                    <label class="input">
                      <input type="text" id="last_name" name="last_name" required value="">
                    </label>
                  </section>
                </div>
                <div class="row">
                  <section class="col col-4">
                    <label class="label">Prefix</label>
                    <label class="input">
                      <input type="text" id="prefix" name="prefix" value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">Birthdate<span style=" color: red;">*</span> </label>
                    <label class="input">
                      <input type="date" id="birthdate" name="birthdate" required value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">Birth Place</label>
                    <label class="input">
                      <input type="text" id="birth_place" name="birth_place" value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">Mothers Name</label>
                    <label class="input">
                      <input type="text" id="mothers_name" name="mothers_name" value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">Alias</label>
                    <label class="input">
                      <input type="text" id="alias" name="alias" value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">SSN</label>
                    <label class="input">
                      <input type="text" id="ssn" name="ssn" value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">Passport Number</label>
                    <label class="input">
                      <input type="text" id="passport_number" name="passport_number" value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">Passport Country</label>
                    <label class="input">
                      <input type="text" id="passport_country" name="passport_country" value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">ID Number</label>
                    <label class="input">
                      <input type="text" id="id_number" name="id_number" value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">Nationality 1</label>
                    <label class="input">
                      <input type="text" id="nationality1" name="nationality1" required value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">Nationality 2</label>
                    <label class="input">
                      <input type="text" id="nationality2" name="nationality2" value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">Nationality 3</label>
                    <label class="input">
                      <input type="text" id="nationality3" name="nationality3" value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">Residence</label>
                    <label class="input">
                      <input type="text" id="residence" name="residence" value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">Contact No</label>
                    <label class="input">
                      <input type="tel" id="phones" name="phones" value="">
                    </label>
                  </section>
                  <div class="clearfix"></div>
                  <section class="col col-4">
                    <label class="label">Address Type</label>
                    <label class="input">
                      <input type="text" id="address_type" name="address_type" value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">Address</label>
                    <label class="input">
                      <input type="text" id="address" name="address" value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">City</label>
                    <label class="input">
                      <input type="text" id="city" name="city" value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">Country Code</label>
                    <label class="input">
                      <input type="text" id="country_code" name="country_code" value="">
                    </label>
                  </section>
                  <div class="clearfix"></div>
                  <section class="col col-4">
                    <label class="label">Occupation</label>
                    <label class="input">
                      <input type="text" id="occupation" name="occupation" value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">Deceased</label>
                    <label class="input">
                      <input type="text" id="deceased" name="deceased" value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">Deceased Date</label>
                    <label class="input">
                      <input type="date" id="deceased_date" name="deceased_date" value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">Tax Number</label>
                    <label class="input">
                      <input type="text" id="tax_number" name="tax_number" value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">Tax Registration Number</label>
                    <label class="input">
                      <input type="text" id="tax_reg_numebr" name="tax_reg_numebr" value="">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">Source of Wealth</label>
                    <label class="input">
                      <input type="text" id="source_of_wealth" name="source_of_wealth" value="">
                    </label>
                  </section>
                </div>
                <div class="row">
                  <section class="col col-4">
                    <label class="label">{{ __('user.email') }} <span style=" color: red;">*</span> </label>
                    <label class="input">
                      <input type="text" id="email" name="email" required value="">
                    </label>
                    <p id="duplicatecheck-msg" style="color: red; display:none;">This email address already has a user account. </p>
                  </section>
                  <section class="col col-4">
                    <label class="label">{{ __('user.role') }} <span style=" color: red;">*</span></label>

                      <select id="roles" name="roles" class="select2" required>
                        <option value=""></option>
                        @foreach ($roles as $x => $val)
                        <option value="{{ $val }}">{{ $val }}</option>
                        @endforeach
                      </select>
                      <i></i>

                  </section>
                </div>

                <div class="row">


                  <section class="col col-4">
                    <label class="label">{{ __('user.password') }} <span style=" color: red;">*</span> </label>
                    <label class="input">
                      <input type="password" id="password" name="password" required value="" minlength="6">
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label">{{ __('user.confirmpassword') }} <span style=" color: red;">*</span> </label>
                    <label class="input">
                      <input type="password" id="confirmpassword" name="confirmpassword" required value="" data-parsley-equalto="#password">
                    </label>
                  </section>
                </div>
                <div class="row">


                </div>

                <!-- <div class="row">
                    <section class="col-lg-12" style="margin-top: 2%; margin-left:16px;">
                        <label class="label">{{ __('user.login_credentials') }}
                        {{-- <button id="mobile" type="button" style="margin-left: 2%; width: 90px; background-color: #963c2c; color: #e7e7e7;" class="btn btn-default"> {{ __('action.yes') }} </button></label> --}}
                        <button id="mobileyes" type="button" style="margin-left: 2%; width: 90px; background-color: #963c2c; color: #e7e7e7;" class="btn btn-default"> {{ __('action.yes') }} </button>
                        <button id="mobileno" type="button" style="margin-left: 2%; width: 90px; background-color: #963c2c; color: #e7e7e7;" class="btn btn-default"> {{ __('action.no') }} </button></label>
                    </section>
                </div>
                <div class="row" id="mobilearea" style="display: none;">
                    <section class="col col-4">
                        <label class="label">{{ __('user.mobile_no') }} </label>
                        <label class="input">
                            <input type="mobile_no" id="mobile_no" name="mobile_no" value="" class="mobile_no">
                        </label>
                    </section>
                </div> -->
              </fieldset>
              <footer>
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
            // $('#mobile').click(function(){ // click to
            //     $('.mobile_no').attr('disabled',false); // removing disabled in this class
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

        $(document).ready(function () {

            $("#user-form").submit(function (e) {

                $("#button1id").attr("disabled", true);

                return true;

            });

            $('#email').blur(function () {
                var email = $(this).val();
                $("#duplicatecheck-msg").hide();

                if(email) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('checkEmailAvailability') }}",
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        data: {email:email},
                        success: function(data) {
                            if (data != "") {

                                console.log(data);

                                    $("#duplicatecheck-msg").show();

                            } else {

                                $("#duplicatecheck-msg").hide();


                            }
                        }
                    });
                } else {

                    $("#district_id").empty();
                }
            });
        });

        // Get today's date
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;

        // Set max attribute of the birthdate input field to today's date
        document.getElementById("birthdate").setAttribute("max", today);
    </script>
  </x-slot>
</x-app-layout>
