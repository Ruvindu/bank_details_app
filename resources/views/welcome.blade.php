<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>{{ config('app.name') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <script src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
</head>

<body>

    <div class="container">

        <div class="row justify-content-center">
            <h1 class="mt-5">Bank Details Saver</h1>
        </div>

        <div class="row justify-content-center mt-3">



            <form id="insertform" action="insert" method="post">
   
                {{-- <div class="col-md-4 mb-3">
                        <label for="validationServer02">Last name</label>
                        <input type="text" class="form-control is-valid" id="validationServer02" placeholder="Last name" value="Otto" required>
                        <div class="valid-feedback">
                          Looks good!
                        </div>
                      </div> --}}

                <div class="form-row mt-5">

                    <div class="col-md-3 mb-3">
                        <input type="hidden" id="_id" value=0>
                        <input type="text" class="form-control" id="bankname" placeholder="Bank name">
                        <div class="invalid-feedback" id="bankname_err">
                        
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">

                        <input type="text" class="form-control " id="branch" placeholder="Branch">
                        <div class="invalid-feedback" id="branch_err">
                            
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">

                        <input type="text" class="form-control " id="accno" placeholder="Account No">
                        <div class="invalid-feedback" id="accno_err">
                            
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>

                </div>



            </form>


        </div>


        <div class="justify-content-center success-msg">


        </div>


        <div class="justify-content-center mt-5">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">BanK Name</th>
                        <th scope="col">Branch</th>
                        <th scope="col">Account number</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>


                    @foreach ($data as $row)
                        <tr>
                            <th scope="row">{{ $row->id }}</th>
                            <td>{{ $row->bankname }}</td>
                            <td>{{ $row->branch }}</td>
                            <td>{{ $row->accno }}</td>
                            <td>
                                <button onclick="editme({{ $row->id }},'{{ $row->bankname }}','{{ $row->branch }}','{{ $row->accno }}')" class="btn btn-primary">Edit</button>
                                <button class="btn btn-danger ml-2" onclick="deleteme({{ $row->id }})">Delete</button>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

    </div>





    <script>
        //insert new recode
        $('#insertform').on('submit', function(event) {
            event.preventDefault();

            let _id = $('#_id').val();
            let bankname = $('#bankname').val();
            let branch = $('#branch').val();
            let accno = $('#accno').val();

            var navigateTo;
            //navigate to update or insert
            if(_id==0){
                navigateTo = "{{ URL::to('/insert') }}";
            }else{
                navigateTo = "{{ URL::to('/edit') }}";
            }
            

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: navigateTo,
                type: 'POST',
                data: {
                    'id':_id,
                    'bankname': bankname,
                    'branch': branch,
                    'accno': accno
                },
                dataType: 'json',
                success: function(response) {

                    if (response.status == 0) {
                        console.log(response);

                        //init success message
                        $('.success-msg').replaceWith(
                            "<div class='justify-content-center success-msg'></div>");

                        //init error messages
                        $('.form-control').removeClass('is-invalid');
                        $('.invalid-feedback').html('');

                        //set error messages
                        $.each(response.error, function(index, value) {
                            $('#' + index + '_err').html(value[0]);
                            $('#' + index + '').addClass('is-invalid');
                        });


                    } else {
                        // remove error messages return when success
                        $('.form-control').removeClass('is-invalid');
                        $('.invalid-feedback').html('');

                        //reset form
                        $('#insertform')[0].reset();
                        document.getElementById('_id').value = 0;

                        //set succsess message
                        $('.success-msg').replaceWith(
                            "<div class='justify-content-center success-msg'> <div class='alert alert-primary' role='alert'>" +
                            response.msg + "</div></div>");

                        displaydata(response.data);
                    }

                },
                error: function(response) {
                    console.log(response);
                }
            });
        });


        //delete selected recode
        function deleteme(id) {

            $.ajax({
                url: "{{ URL::to('delete') }}",
                type: 'GET',
                data: {
                    'id': id,
                },
                dataType: 'json',
                beforeSend:function(){
                    if (!confirm('Are you sure you want to delte this ?')) {
                        return false;
                    }
                },
                success:function(response){
                   
                    if(response.status==1){
                        //set succsess message
                        $('.success-msg').replaceWith(
                            "<div class='justify-content-center success-msg'> <div class='alert alert-primary' role='alert'>" +
                            response.msg + "</div></div>");

                        displaydata(response.data);
                    }

                }

            });
        }




        function displaydata(data) {
            $('tbody').replaceWith("<tbody></tbody>");

            $.each(data, function(index, value) {
                $("<tr><td>" + value.id + "</td><td>" + value.bankname + "</td><td>" + value.branch +
                    "</td><td>" + value.accno +
                    "</td><td>  <button class='btn btn-primary' onclick=editme(" + value.id + ",'" + value.bankname + "','" + value.branch + "','" +  value.accno + "')>Edit</button> <button class='btn btn-danger ml-2' onclick=deleteme(" +
                    value.id + ")>Delete</button> </td> </tr>").appendTo('tbody');
            });
        }


        function editme(id,bankname,branch,accno){
            
            document.getElementById('_id').value = id;
            document.getElementById('bankname').value = bankname;
            document.getElementById('branch').value = branch;
            document.getElementById('accno').value = accno;

        }

    </script>

</body>

</html>
