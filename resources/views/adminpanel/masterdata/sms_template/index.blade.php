@section('title', 'SMS Templates')

<x-app-layout>
    <x-slot name="header">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            #sparks li {
                display: inline-block;
                max-height: 47px;
                overflow: hidden;
                text-align: left;
                box-sizing: content-box;
                -moz-box-sizing: content-box;
                -webkit-box-sizing: content-box;
                width: 95px;
            }

            #sparks li h5 {
                color: #555;
                float: none;
                font-size: 11px;
                font-weight: 400;
                margin: -3px 0 0 0;
                padding: 0;
                border: none;
                font-weight: 900;
                text-transform: uppercase;
                webkit-transition: all 500ms ease;
                -moz-transition: all 500ms ease;
                -ms-transition: all 500ms ease;
                -o-transition: all 500ms ease;
                transition: all 500ms ease;
                text-align: center;
            }

            #sparks li span {
                color: #324b7d;
                display: block;
                font-weight: 900;
                margin-top: 5px;
                webkit-transition: all 500ms ease;
                -moz-transition: all 500ms ease;
                -ms-transition: all 500ms ease;
                -o-transition: all 500ms ease;
                transition: all 500ms ease;
            }

            #sparks li h5:hover {
                color: #999999;
            }

            #sparks li span:hover {
                color: #ffffff;
            }
        </style>
    </x-slot>

    <div id="main" role="main">
        <!-- RIBBON -->
        <div id="ribbon"></div>
        <!-- END RIBBON -->
        <div id="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row cms_top_btn_row" style="margin-left:auto;margin-right:auto;">
                        <a href="{{ route('sms-template') }}">
                            <button class="btn cms_top_btn top_btn_height cms_top_btn_active">{{ __('province.view_all') }}</button>
                        </a>
                    </div>
                </div>
                <!-- <div class="col-lg-8">
                    <ul id="sparks" class="">
                        <ul id="sparks" class="">
                            {{-- <li class="sparks-info" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 22px 15px; min-width: auto;">
                                <a href="{{ route('province') }}">
                                    <h5>{{ __('province.add_new') }}</h5>
                                </a>

                            </li> --}}
                            <li class="sparks-info sparks-info_active" style="border: 1px solid #c5c5c5; padding-right: 0px; padding: 22px 15px; min-width: auto;">
                                <a href="{{ route('sms-template') }}">
                                    <h5>{{ __('smstemplate.view_all') }}</h5>
                                </a>

                            </li>
                        </ul>
                    </ul>
                </div> -->
            </div>
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
            @endif
            <section id="widget-grid" class="">

                <!-- row -->
                <div class="row">
                    <!-- NEW WIDGET START -->

                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <!-- Widget ID (each widget will need unique ID)-->

                        <div class="jarviswidget jarviswidget-color-darken" id="user_types" data-widget-editbutton="false">
                            <header>
                                <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                                <h2>{{ __('smstemplate.title') }}</h2>
                            </header>
                            <!-- widget div-->
                            <div>
                                <!-- widget edit box -->
                                <div class="jarviswidget-editbox">
                                    <!-- This area used as dropdown edit box -->
                                </div>
                                <!-- end widget edit box -->
                                <!-- widget content -->
                                <div class="widget-body no-padding table-responsive">
                                    <table class="table table-bordered data-table" width="100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('smstemplate.template_no') }}</th>
                                                <th>{{ __('smstemplate.sms_template_name_en') }}</th>
                                                <th>{{ __('smstemplate.sms_template_name_sin') }}</th>
                                                <th>{{ __('smstemplate.sms_template_name_tam') }}</th>
                                                <th>{{ __('smstemplate.edit') }}</th>
                                                <th width="100px">{{ __('smstemplate.status') }}</th>
                                                <!-- <th width="100px">{{ __('smstemplate.is_delete') }}</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                                </div>
                                <!-- end widget content -->
                            </div>
                            <!-- end widget div -->
                        </div>
                        <!-- end widget -->
                    </article>
                    <!-- WIDGET END -->
                </div>
                <!-- end row -->
                <!-- end row -->
            </section>
        </div>
    </div>
    <x-slot name="script">
        <script src="{{ asset('public/back/js/plugin/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatables/dataTables.colVis.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatables/dataTables.tableTools.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatables/dataTables.bootstrap.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatable-responsive/datatables.responsive.min.js') }}"></script>
        <script type="text/javascript">
            $(function() {

                var table = $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('sms-template-list') }}",
                    order: [ 1, 'asc' ],
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'sms_template_name_en',
                            name: 'sms_template_name_en'
                        },
                        {
                            data: 'sms_template_name_sin',
                            name: 'sms_template_name_sin'
                        },
                        {
                            data: 'sms_template_name_tam',
                            name: 'sms_template_name_tam'
                        },
                        {
                            data: 'edit',
                            name: 'edit',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'activation',
                            name: 'activation',
                            orderable: false,
                            searchable: false
                        },
                        // {
                        //     data: 'blocksmstemplate',
                        //     name: 'blocksmstemplate',
                        //     orderable: false,
                        //     searchable: false
                        // },
                    ]
                });

            });
        </script>
    </x-slot>
</x-app-layout>
