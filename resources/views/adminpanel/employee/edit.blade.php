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
                        <form action="{{ route('save-employee-details') }}" enctype="multipart/form-data" method="post" id="user-form" class="smart-form">
                            @csrf
                            @method('PUT')
                            <fieldset>
                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">Employer Name<span style="color: red;">*</span></label>
                                        <label class="input">
                                            <input type="text" id="employer_name" name="employer_name" required value="{{ $user->employer_name }}">
                                        </label>
                                    </section>
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
                                </div>
                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">Town</label>
                                        <label class="input">
                                            <input type="text" id="town" name="town" value="{{ $user->town }}">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">City</label>
                                        <label class="input">
                                            <input type="text" id="city" name="city" value="{{ $user->city }}">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">ZIP</label>
                                        <label class="input">
                                            <input type="text" id="zip" name="zip" value="{{ $user->zip }}">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">Country Code</label>
                                        <label class="input">
                                            <input type="text" id="country_code" name="country_code" value="{{ $user->country_code }}">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">State</label>
                                        <label class="input">
                                            <input type="text" id="state" name="state" value="{{ $user->state }}">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">Comments</label>
                                        <label class="input">
                                            <textarea id="comments" name="comments" style="width: 100%;" rows="3">{{ $user->comments }}</textarea>
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">Phone Contact Type</label>
                                        <label class="input">
                                            <input type="text" id="tph_contact_type" name="tph_contact_type" value="{{ $user->tph_contact_type }}">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">Phone Communication Type</label>
                                        <label class="input">
                                            <input type="text" id="tph_communication_type" name="tph_communication_type" value="{{ $user->tph_communication_type }}">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">Phone Number</label>
                                        <label class="input">
                                            <input type="text" id="tph_number" name="tph_number" value="{{ $user->tph_number }}">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">Phone Extension</label>
                                        <label class="input">
                                            <input type="text" id="tph_extension" name="tph_extension" value="{{ $user->tph_extension }}">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">Phone Comments</label>
                                        <label class="input">
                                            <textarea id="employer_phone_id_comments" name="employer_phone_id_comments" style="width: 100%;" rows="3">{{ $user->employer_phone_id_comments }}</textarea>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">Identification Type</label>
                                        <label class="input">
                                            <input type="text" id="identification_type" name="identification_type" value="{{ $user->identification_type }}">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">Identification Number</label>
                                        <label class="input">
                                            <input type="text" id="identification_number" name="identification_number" value="{{ $user->identification_number }}">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">Identification Issue Date</label>
                                        <label class="input">
                                            <input type="text" id="identification_issue_date" name="identification_issue_date" value="{{ $user->identification_issue_date }}">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-4">
                                        <label class="label">Identification Issued By</label>
                                        <label class="input">
                                            <input type="text" id="identification_issued_by" name="identification_issued_by" value="{{ $user->identification_issued_by }}">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">Identification Issue Country</label>
                                        <label class="input">
                                            <input type="text" id="identification_issue_country" name="identification_issue_country" value="{{ $user->identification_issue_country }}">
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label class="label">Identification Comments</label>
                                        <label class="input">
                                            <textarea id="identification_comments" name="identification_comments" style="width: 100%;" rows="3">{{ $user->identification_comments }}</textarea>
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
