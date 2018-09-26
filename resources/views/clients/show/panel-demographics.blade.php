<form action="{{ route('clients.update', $client->id) }}" method="post" id="editDemographicsForm">

    @csrf
    @method('PUT')

    <div>
        @permission('edit_client_demographics')
        <div id="edit-demographics-button-group">
            <button type="button" class="btn btn-sm btn-primary text-white" id="edit-demographics-button"><em class="fa fa-pencil"></em> Edit</button>
        </div>
        <div id="save-demographics-button-group">
            <button type="button" class="btn btn-secondary btn-sm" id="cancel-edit-demographics-button">Cancel</button>
            <button type="submit" class="btn btn-success btn-sm"><em class="fa fa-save"></em> Save</button>
        </div>
        <br>
        @endpermission
        <div class="card">
            <div>
                <table class="table">
                    <tr>
                        <td style="width:200px"><b>Chart Number</b></td>
                        <td>{{ $client->chart_number }}</td>
                    </tr>
                    <tr>
                        <td><b>SSN</b></td>
                        <td class="show-cell">{{ $client->ssn_formatted }}</td>
                        @permission('edit_client_demographics')
                        <td class="edit-cell">
                            <input type="number" placeholder="Social Security Number" class="form-control form-control-sm" id="ssn" name="ssn" value="{{ old('ssn', $client->ssn) }}">
                        </td>
                        @endpermission
                    </tr>
                    <tr>
                        <td><b>Legal Name</b> <span class="text-danger required"><b>*</b></span></td>
                        <td class="show-cell">{{ $client->name() }}</td>
                        @permission('edit_client_demographics')
                        <td class="edit-cell">
                            <table class="full-width">
                                <tr class="tbl-padding">
                                    <td style="width:10%">
                                        <input type="text" class="form-control form-control-sm" name="legalPrefix" id="legalPrefix" aria-describedby="legalPrefix" placeholder="Prefix" value="{{ old('legalPrefix', $client->legal_prefix) }}">
                                    </td>
                                    <td style="width:30%">
                                        <input type="text" class="form-control form-control-sm" name="legalFirstName" id="legalFirstName" aria-describedby="legalFirstName" placeholder="First Name" value="{{ old('legalFirstName', $client->legal_first_name) }}" required>
                                    </td>
                                    <td style="width:20%">
                                        <input type="text" class="form-control form-control-sm" name="legalMiddleName" id="legalMiddleName" aria-describedby="legalMiddleName" placeholder="Middle Name" value="{{ old('legalMiddleName', $client->legal_middle_name) }}">
                                    </td>
                                    <td style="width:30%">
                                        <input type="text" class="form-control form-control-sm" name="legalLastName" id="legalLastName" aria-describedby="legalLastName" placeholder="Last Name" value="{{ old('legalLastName', $client->legal_last_name) }}" required>
                                    </td>
                                    <td style="width:10%">
                                        <input type="text" class="form-control form-control-sm" name="legalSuffix" id="legalSuffix" aria-describedby="legalSuffix" placeholder="Suffix" value="{{ old('legalSuffix', $client->legal_suffix) }}">
                                    </td>
                                </tr>
                            </table>
                        </td>
                        @endpermission
                    </tr>
                    <tr>
                        <td><b>Birth Name</b></td>
                        <td class="show-cell">{{ $client->name('birth') }}</td>
                        @permission('edit_client_demographics')
                        <td class="edit-cell">
                            <table class="full-width">
                                <tr class="tbl-padding">
                                    <td style="width:10%">
                                        <input type="text" class="form-control form-control-sm" name="birthPrefix" id="birthPrefix" aria-describedby="birthPrefix" placeholder="Prefix" value="{{ old('birthPrefix', $client->birth_prefix) }}">
                                    </td>
                                    <td style="width:30%">
                                        <input type="text" class="form-control form-control-sm" name="birthFirstName" id="birthFirstName" aria-describedby="birthFirstName" placeholder="First Name" value="{{ old('birthFirstName', $client->birth_first_name) }}">
                                    </td>
                                    <td style="width:20%">
                                        <input type="text" class="form-control form-control-sm" name="birthMiddleName" id="birthMiddleName" aria-describedby="birthMiddleName" placeholder="Middle Name" value="{{ old('birthMiddleName', $client->birth_middle_name) }}">
                                    </td>
                                    <td style="width:30%">
                                        <input type="text" class="form-control form-control-sm" name="birthLastName" id="birthLastName" aria-describedby="birthLastName" placeholder="Last Name" value="{{ old('birthLastName', $client->birth_last_name) }}">
                                    </td>
                                    <td style="width:10%">
                                        <input type="text" class="form-control form-control-sm" name="birthSuffix" id="birthSuffix" aria-describedby="birthSuffix" placeholder="Suffix" value="{{ old('birthSuffix', $client->birth_suffix) }}">
                                    </td>
                                </tr>
                            </table>
                        </td>
                        @endpermission
                    </tr>
                    <tr>
                        <td><b>Gender</b> <span class="text-danger required"><b>*</b></span></td>
                        <td class="show-cell">{{ $client->gender }}</td>
                        @permission('edit_client_demographics')
                        <td class="edit-cell">
                            <select name="gender" id="gender" class="form-control form-control-sm" required>
                                @foreach(\App\AppList::items('genders') as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </td>
                        @endpermission
                    </tr>
                    <tr>
                        <td><b>Date Of Birth</b>  <span class="text-danger required"><b>*</b></span></td>
                        <td class="show-cell">{{ $client->date_of_birth->format('m/d/Y') }}</td>
                        @permission('edit_client_demographics')
                        <td class="edit-cell">
                            <input type="date" class="form-control form-control-sm" id="birthday" name="birthday" required>
                        </td>
                        @endpermission
                    </tr>
                    <tr>
                        <td><b>Age</b></td>
                        <td>{{ $client->age }}</td>
                    </tr>
                    <tr>
                        <td><b>Comments</b></td>
                        <td class="show-cell">{!! nl2br($client->comments) !!}</td>
                        @permission('edit_client_demographics')
                        <td class="edit-cell">
                            <textarea name="comments" id="comments" cols="30" rows="3" class="form-control">{{ old('comments', $client->comments) }}</textarea>
                        </td>
                        @endpermission
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div>
                <table class="table">
                    <tr>
                        <td style="width:200px"><b>Created At</b></td>
                        <td>{{ $client->created_at->format('m/d/Y') }}</td>
                    </tr>
                    <tr>
                        <td><b>Created By</b></td>
                        <td>{{ $client->author->name }}</td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
</form>