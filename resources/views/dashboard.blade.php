@section('title', 'Dashboard')

<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div id="main" role="main">
        <!-- RIBBON -->
        <div id="ribbon">
        </div>
        <!-- END RIBBON -->
        <div id="content">
            <div class="row m-auto">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <h1 class="page-title txt-color-blueDark mb-0" style="color: #0060AE !important;"><i class="fa-fw fa fa-home"></i>  {{ __('Hello') }} <span>@if(!empty($officename->office_name_en)){{ $officename->office_name_en }}@endif </span></h1>
                  <p style="color: #000000;">Hello Welcome to HNB Finance XML File Generation System
                  </p>
                  <br>
                </div>

            </div>



            <!-- widget grid -->
            <section id="widget-grid" class="mt-4">
				
              <div class="row m-auto">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                  
                  <div class="widget-body smart-form">
                    
                  </div>
                  
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-8">
                  
                  <div class="row">
                    
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    	 
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    	 <div class="widget-body smart-form">
                            
                          </div>
                    </div>
                    
                  </div>
                  
                  
                  <div class="widget-body smart-form">
                    
                  </div>
                  
                  <div class="row">
                    
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    	 <div class="widget-body smart-form">
                            
                          </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    	 <div class="widget-body smart-form">
                            
                          </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    	 <div class="widget-body smart-form">
                            
                          </div>
                    </div>
                    
                  </div>
                  
                </div>
                
              </div>
                
            </section>
            <!-- end widget grid -->
        </div>
    </div>

   
    <x-slot name="script">

        <!-- Morris Chart Dependencies -->
        
        <script src="{{ asset('public/js/plugin/morris/morris.min.js') }}"></script>
        <script src="{{ asset('public/js/plugin/chartjs/chart.min.js') }}"></script> --}}
        <script src="https://lcms.tekgeeks.net/public/js/plugin/morris/raphael.min.js"></script>
        <script src="https://lcms.tekgeeks.net/public/js/plugin/morris/morris.min.js"></script>
        <script src="https://lcms.tekgeeks.net/public/js/plugin/chartjs/chart.min.js"></script>

        
    </x-slot>
</x-app-layout>
