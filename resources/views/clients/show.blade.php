@extends('layouts.app')

@section('content')
    <div class="jumbotron jumbotron-fluid bg-light text-dark">
        <div class="container">
            <h2><em class="fa fa-user-circle"></em> {{ $client->name() }}</h2>
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link" id="nav-alerts" href="{{ route('clients.show', [$client->id, 'alerts']) }}">Alerts</a></li>
                <li class="nav-item"><a class="nav-link" id="nav-demographics" href="{{ route('clients.show', [$client->id, 'demographics']) }}">Demographics</a></li>
                <li class="nav-item"><a class="nav-link" id="nav-addresses" href="{{ route('clients.show', [$client->id, 'addresses']) }}">Addresses</a></li>
                <li class="nav-item"><a class="nav-link" id="nav-phones" href="{{ route('clients.show', [$client->id, 'phones']) }}">Phones</a></li>
                <li class="nav-item"><a class="nav-link" id="nav-emails" href="{{ route('clients.show', [$client->id, 'emails']) }}">Emails</a></li>
                <li class="nav-item"><a class="nav-link" id="nav-services" href="{{ route('clients.show', [$client->id, 'services']) }}">Services</a></li>
                <li class="nav-item"><a class="nav-link" id="nav-notes" href="{{ route('clients.show', [$client->id, 'notes']) }}">Notes</a></li>
            </ul>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                @if($panel === 'demographics')
                    @include('partial-session-alerts')
                @elseif(session('success'))
                    <div class="col-12">
                        <div class="alert alert-success fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <div>
                    @include('clients.show.panel-' . $panel)
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')

    <script type="text/javascript">

        var Client = {

            showEditDemographics: function(){

                $('#save-demographics-button-group').show();
                $('#edit-demographics-button-group').hide();
                $('.show-cell').hide();
                $('.edit-cell').show();

            },

            cancelEditDemographics: function(){

                if(confirm('Your changes will not be saved, are you sure?'))
                {
                    window.location.href = window.location.pathname;
                }

            },

            showAddEmailModal: function(hasOldSession){

                $('#emailForm').attr('action', '{{ route('emails.store', $client->id) }}');
                $('#emailModalTitle').html('Add Email');
                $('#emailFormMethod').val('POST');
                $('.lock-on-edit').removeAttr('disabled').removeAttr('readonly');
                $('#emailModal').modal('show');

                if(!hasOldSession)
                {
                    $('.alert').hide();
                    $('#emailAddress').val('').attr('required', true);
                    $('#emailActiveAt').attr('required', true);
                    $('#emailComments').val('');
                }

                $('#emailActiveAt').val('{{ date('Y-m-d', strtotime(old('emailActiveAt', date('Y-m-d', time())))) }}');
                $('#emailExpiresAt').val('{{ date('Y-m-d', strtotime(old('emailExpiresAt', date('Y-m-d', strtotime('+1 year'))))) }}');
                $('#emailPrimary').val('{{ old('emailPrimary', '0') }}');

            },

            showEditEmailModal: function(id, hasOldSession){

                $.post('{{ route('emails.show') }}', {id: id, _token: '{{ csrf_token() }}'})
                    .done(function(d){

                        $('#emailId').val(d['id']);
                        $('#emailPrimary').val(d['primary']);
                        $('#emailAddress').val(d['email']).removeAttr('required');
                        $('#emailComments').val(d['comments']).removeAttr('required');
                        $('#emailActiveAt').val(d['activeAt']).removeAttr('required');
                        $('#emailExpiresAt').val(d['expiresAt']);

                        $('.lock-on-edit').attr('disabled', 'disabled').attr('readonly', 'readonly');

                        $('#emailForm').attr('action', '{{ route('emails.update', $client->id) }}');
                        $('#emailModalTitle').html('Edit Email');
                        $('#emailFormMethod').val('PUT');
                        $('#emailModal').modal('show');

                        if(!hasOldSession)
                        {
                            $('.alert').hide();
                        }

                    });
            },

            showAddPhoneModal: function(hasOldSession){

                $('#phoneForm').attr('action', '{{ route('phones.store', $client->id) }}');
                $('#phoneModalTitle').html('Add Phone');
                $('#phoneFormMethod').val('POST');
                $('.lock-on-edit').removeAttr('disabled').removeAttr('readonly');
                $('#phoneModal').modal('show');

                if(!hasOldSession)
                {
                    $('.alert').hide();
                    $('#phoneNumber').val('').attr('required', true);
                    $('#phoneActiveAt').attr('required', true);
                    $('#phoneComments').val('');
                }

                $('#phoneActiveAt').val('{{ date('Y-m-d', strtotime(old('phoneActiveAt', date('Y-m-d', time())))) }}');
                $('#phoneExpiresAt').val('{{ date('Y-m-d', strtotime(old('phoneExpiresAt', date('Y-m-d', strtotime('+1 year'))))) }}');
                $('#phonePrimary').val('{{ old('phonePrimary', '0') }}');

            },

            showEditPhoneModal: function(id, hasOldSession){

                $.post('{{ route('phones.show') }}', {id: id, _token: '{{ csrf_token() }}'})
                    .done(function(d){

                        $('#phoneId').val(d['id']);
                        $('#phonePrimary').val(d['primary']);
                        $('#phoneContactTime').val(d['contact_time']);
                        $('#phoneNumber').val(d['phone_number']).removeAttr('required');
                        $('#phoneComments').val(d['comments']).removeAttr('required');
                        $('#phoneActiveAt').val(d['activeAt']).removeAttr('required');
                        $('#phoneExpiresAt').val(d['expiresAt']);

                        $('.lock-on-edit').attr('disabled', 'disabled').attr('readonly', 'readonly');

                        $('#phoneForm').attr('action', '{{ route('phones.update', $client->id) }}');
                        $('#phoneModalTitle').html('Edit Phone');
                        $('#phoneFormMethod').val('PUT');
                        $('#phoneModal').modal('show');

                        if(!hasOldSession)
                        {
                            $('.alert').hide();
                        }

                    });
            },

            showAddAddressModal: function(hasOldSession){

                $('#addressForm').attr('action', '{{ route('addresses.store', $client->id) }}');
                $('#addressModalTitle').html('Add Address');
                $('#addressFormMethod').val('POST');
                $('.lock-on-edit').removeAttr('disabled').removeAttr('readonly');
                $('#addressModal').modal('show');

                if(!hasOldSession)
                {
                    $('.alert').hide();
                    $('#addressLine1').val('').attr('required', true);
                    $('#addressLine2').val('');
                    $('#addressLine3').val('');
                    $('#city').val('').attr('required', true);
                    $('#state').val('').attr('required', true);
                    $('#zip').val('').attr('required', true);
                    $('#addressActiveAt').attr('required', true);
                    $('#addressComments').val('');
                }

                $('#addressActiveAt').val('{{ date('Y-m-d', strtotime(old('addressActiveAt', date('Y-m-d', time())))) }}');
                $('#addressExpiresAt').val('{{ date('Y-m-d', strtotime(old('addressExpiresAt', date('Y-m-d', strtotime('+1 year'))))) }}');
                $('#addressPrimary').val('{{ old('addressPrimary', '0') }}');

            },

            showEditAddressModal: function(id, hasOldSession){

                $.post('{{ route('addresses.show') }}', {id: id, _token: '{{ csrf_token() }}'})
                    .done(function(d){

                        $('#addressId').val(d['id']);
                        $('#addressPrimary').val(d['primary']);
                        $('#addressLine1').val(d['address_line_1']).removeAttr('required');
                        $('#addressLine2').val(d['address_line_2']);
                        $('#addressLine3').val(d['address_line_3']);
                        $('#city').val(d['city']).removeAttr('required');
                        $('#state').val(d['state']).removeAttr('required');
                        $('#zip').val(d['zip_code']).removeAttr('required');
                        $('#addressComments').val(d['comments']).removeAttr('required');
                        $('#addressActiveAt').val(d['activeAt']).removeAttr('required');
                        $('#addressExpiresAt').val(d['expiresAt']);

                        $('.lock-on-edit').attr('disabled', 'disabled').attr('readonly', 'readonly');

                        $('#addressForm').attr('action', '{{ route('addresses.update', $client->id) }}');
                        $('#addressModalTitle').html('Edit Address');
                        $('#addressFormMethod').val('PUT');
                        $('#addressModal').modal('show');

                        if(!hasOldSession)
                        {
                            $('.alert').hide();
                        }

                    });
            },

            showAddServiceModal: function(hasOldSession){

                $('#serviceForm').attr('action', '{{ route('services.store', $client->id) }}');
                $('#serviceModalTitle').html('Add Service');
                $('#serviceFormMethod').val('POST');
                $('.lock-on-edit').removeAttr('disabled').removeAttr('readonly');
                $('#serviceModal').modal('show');

                if(!hasOldSession)
                {
                    $('.alert').hide();
                    $('#serviceName').attr('required', true);
                    $('#serviceActiveAt').attr('required', true);
                    $('#serviceComments').val('');
                }

                $('#serviceActiveAt').val('{{ date('Y-m-d', strtotime(old('serviceActiveAt', date('Y-m-d', time())))) }}');
                $('#serviceExpiresAt').val('{{ date('Y-m-d', strtotime(old('serviceExpiresAt', date('Y-m-d', strtotime('+1 year'))))) }}');

            },

            showEditServiceModal: function(id, hasOldSession){

                $.post('{{ route('services.show') }}', {id: id, _token: '{{ csrf_token() }}'})
                    .done(function(d){

                        $('#serviceId').val(d['id']);
                        $('#serviceComments').val(d['comments']).removeAttr('required');
                        $('#serviceActiveAt').val(d['activeAt']).removeAttr('required');
                        $('#serviceExpiresAt').val(d['expiresAt']);

                        $('.lock-on-edit').attr('disabled', 'disabled').attr('readonly', 'readonly');

                        $('#serviceForm').attr('action', '{{ route('services.update', $client->id) }}');
                        $('#serviceModalTitle').html('Edit Service');
                        $('#serviceFormMethod').val('PUT');
                        $('#serviceModal').modal('show');

                        if(!hasOldSession)
                        {
                            $('.alert').hide();
                        }

                    });
            },

            showAddNotesModal: function(hasOldSession){

                $('#notesForm').attr('action', '{{ route('notes.store', $client->id) }}');
                $('#notesModalTitle').html('Add Case Note');
                $('#notesModal').modal('show');

                if(!hasOldSession)
                {
                    $('.alert').hide();
                    $('#caseNoteComment').val('');
                }

            },

            showExpired: function(){

                $('[data-expired=1]').toggle();

            },

            createDemographicsButtonListeners: function(){

                $('#edit-demographics-button').click(Client.showEditDemographics);
                $('#cancel-edit-demographics-button').click(Client.cancelEditDemographics);

            },

            createAddressButtonListeners: function(){

                $('#add-address-button').click(Client.showAddAddressModal.bind(null, false));
                $('.edit-address-button').click(function(){

                    Client.showEditAddressModal($(this).attr('data-id'), false);

                });

            },

            createPhoneButtonListeners: function(){

                $('#add-phone-button').click(Client.showAddPhoneModal.bind(null, false));
                $('.edit-phone-button').click(function(){

                    Client.showEditPhoneModal($(this).attr('data-id'), false);

                });

            },

            createEmailButtonListeners: function(){

                $('#add-email-button').click(Client.showAddEmailModal.bind(null, false));
                $('.edit-email-button').click(function(){

                    Client.showEditEmailModal($(this).attr('data-id'), false);

                });

            },

            createServiceButtonListeners: function(){

                $('#add-service-button').click(Client.showAddServiceModal.bind(null, false));
                $('.edit-service-button').click(function(){

                    Client.showEditServiceModal($(this).attr('data-id'), false);

                });

            },

            createCaseNoteButtonListeners: function(){

                $('#add-note-button').click(Client.showAddNotesModal.bind(null, false));

            },

            created: function(){

                this.createDemographicsButtonListeners();
                this.createAddressButtonListeners();
                this.createPhoneButtonListeners();
                this.createEmailButtonListeners();
                this.createServiceButtonListeners();
                this.createCaseNoteButtonListeners();

                $('.show-expired-button').click(Client.showExpired);


                if(window.location.hash === '#editDemographics')
                {
                    Client.showEditDemographics();
                }
                else if(window.location.hash === '#addAddress')
                {
                    Client.showAddAddressModal(true);
                }
                else if(window.location.hash === '#editAddress')
                {
                    @if(old('addressId'))
                        Client.showEditAddressModal('{{ old('addressId') }}', true);
                    @endif
                }
                else if(window.location.hash === '#addPhone')
                {
                    Client.showAddPhoneModal(true);
                }
                else if(window.location.hash === '#editPhone')
                {
                    @if(old('phoneId'))
                        Client.showEditPhoneModal('{{ old('phoneId') }}', true);
                    @endif
                }
                else if(window.location.hash === '#addEmail')
                {
                    Client.showAddEmailModal(true);
                }
                else if(window.location.hash === '#editEmail')
                {
                    @if(old('emailId'))
                        Client.showEditEmailModal('{{ old('emailId') }}', true);
                    @endif
                }
                else if(window.location.hash === '#addNote')
                {
                    Client.showAddNotesModal(true);
                }

                $('#nav-{{ $panel }}').addClass('active');

                $('#gender').val('{{ old('gender', $client->gender) }}');
                $('#birthday').val('{{ old('birthday', $client->date_of_birth->format('Y-m-d')) }}');

                $('#addressType').val('{{ old('addressType', 'Home') }}');

            }

        };

        (function(){

           Client.created();

        })();

    </script>
@endsection
